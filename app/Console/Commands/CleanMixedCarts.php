<?php

namespace App\Console\Commands;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanMixedCarts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:clean-mixed {--dry-run : Afficher les actions sans les exécuter}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoyer les paniers contenant des produits de plusieurs commerces';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');
        
        $this->info('🔍 Recherche des paniers contenant des produits de plusieurs commerces...');
        
        // Récupérer tous les paniers avec leurs articles et commerces
        $carts = Cart::with('items.product.commerce')->get();
        $mixedCarts = [];
        
        foreach ($carts as $cart) {
            if ($cart->items->count() > 1) {
                $commerceIds = $cart->items->pluck('product.commerce_id')->unique();
                if ($commerceIds->count() > 1) {
                    $mixedCarts[] = $cart;
                }
            }
        }
        
        if (empty($mixedCarts)) {
            $this->info('✅ Aucun panier mixte trouvé.');
            return;
        }
        
        $this->info("🔍 Trouvé " . count($mixedCarts) . " panier(s) contenant des produits de plusieurs commerces.");
        
        if ($dryRun) {
            $this->info("⚠️  MODE DRY-RUN : Aucune modification ne sera effectuée.");
        }
        
        foreach ($mixedCarts as $cart) {
            $this->line('');
            $this->info("📦 Panier ID: {$cart->id}");
            
            // Grouper par commerce
            $itemsByCommerce = $cart->items->groupBy('product.commerce_id');
            
            $this->info("   Commerces détectés:");
            foreach ($itemsByCommerce as $commerceId => $items) {
                $commerceName = $items->first()->product->commerce->name;
                $itemCount = $items->sum('quantity');
                $totalPrice = $items->sum(function($item) {
                    return $item->quantity * $item->product->price;
                });
                
                $this->info("   - {$commerceName} (ID: {$commerceId}): {$itemCount} articles, " . number_format($totalPrice, 0, ',', ' ') . " F");
            }
            
            // Stratégie : Garder les produits du commerce le plus récent (dernier ajouté)
            $latestItem = $cart->items()->orderBy('created_at', 'desc')->first();
            $keepCommerceId = $latestItem->product->commerce_id;
            $keepCommerceName = $latestItem->product->commerce->name;
            
            $this->info("   🎯 Décision : Garder les produits de '{$keepCommerceName}' (dernier ajouté)");
            
            if (!$dryRun) {
                try {
                    DB::beginTransaction();
                    
                    // Supprimer les articles des autres commerces
                    $deletedCount = $cart->items()
                        ->whereHas('product', function($query) use ($keepCommerceId) {
                            $query->where('commerce_id', '!=', $keepCommerceId);
                        })
                        ->delete();
                    
                    DB::commit();
                    
                    $this->info("   ✅ Supprimé {$deletedCount} article(s) des autres commerces");
                    
                } catch (\Exception $e) {
                    DB::rollback();
                    $this->error("   ❌ Erreur lors du nettoyage : " . $e->getMessage());
                }
            } else {
                $toDeleteCount = $cart->items()
                    ->whereHas('product', function($query) use ($keepCommerceId) {
                        $query->where('commerce_id', '!=', $keepCommerceId);
                    })
                    ->count();
                
                $this->info("   🔄 Supprimerait {$toDeleteCount} article(s) des autres commerces");
            }
        }
        
        $this->line('');
        if ($dryRun) {
            $this->info("⚠️  Pour appliquer les modifications, exécutez la commande sans --dry-run");
        } else {
            $this->info("✅ Nettoyage terminé !");
        }
    }
}
