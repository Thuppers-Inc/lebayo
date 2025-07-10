<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class UpdateOrdersCommerceId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:update-commerce-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour les commandes existantes avec les bons commerces';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Démarrage de la mise à jour des commandes...');
        
        // Récupérer les commandes sans commerce ou avec un commerce inexistant
        $orders = Order::where(function($query) {
            $query->whereNull('commerce_id')
                  ->orWhere('commerce_id', 0)
                  ->orWhereDoesntHave('commerce');
        })->get();
        
        if ($orders->isEmpty()) {
            $this->info('Aucune commande à mettre à jour.');
            return;
        }
        
        $this->info("Traitement de {$orders->count()} commande(s)...");
        
        $progressBar = $this->output->createProgressBar($orders->count());
        $progressBar->start();
        
        $updated = 0;
        $errors = 0;
        
        foreach ($orders as $order) {
            try {
                // Récupérer le premier produit de la commande pour déterminer le commerce
                $firstItem = $order->items()->with('product.commerce')->first();
                
                if ($firstItem && $firstItem->product && $firstItem->product->commerce) {
                    $commerceId = $firstItem->product->commerce->id;
                    
                    // Vérifier que tous les produits de la commande appartiennent au même commerce
                    $allItemsFromSameCommerce = $order->items()
                        ->whereHas('product', function($query) use ($commerceId) {
                            $query->where('commerce_id', $commerceId);
                        })
                        ->count() === $order->items()->count();
                    
                    if ($allItemsFromSameCommerce) {
                        $order->update(['commerce_id' => $commerceId]);
                        $updated++;
                    } else {
                        $this->warn("Commande #{$order->order_number} contient des produits de différents commerces.");
                        
                        // Prendre le commerce du premier produit quand même
                        $order->update(['commerce_id' => $commerceId]);
                        $updated++;
                    }
                } else {
                    $this->warn("Impossible de déterminer le commerce pour la commande #{$order->order_number}.");
                    
                    // Assigner un commerce par défaut si aucun produit n'est trouvé
                    $defaultCommerce = \App\Models\Commerce::first();
                    if ($defaultCommerce) {
                        $order->update(['commerce_id' => $defaultCommerce->id]);
                        $updated++;
                        $this->info("Commerce par défaut assigné à la commande #{$order->order_number}.");
                    } else {
                        $errors++;
                    }
                }
            } catch (\Exception $e) {
                $this->error("Erreur lors du traitement de la commande #{$order->order_number}: " . $e->getMessage());
                $errors++;
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Mise à jour terminée !");
        $this->info("Commandes mises à jour : {$updated}");
        if ($errors > 0) {
            $this->warn("Erreurs rencontrées : {$errors}");
        }
    }
}
