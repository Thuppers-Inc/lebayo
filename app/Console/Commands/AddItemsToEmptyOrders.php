<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;

class AddItemsToEmptyOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:add-items-to-empty';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ajoute des articles aux commandes vides pour les tests';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recherche des commandes vides...');
        
        // Récupérer les commandes qui n'ont pas d'articles
        $emptyOrders = Order::with('commerce.products')
            ->whereDoesntHave('items')
            ->get();
        
        if ($emptyOrders->isEmpty()) {
            $this->info('Aucune commande vide trouvée.');
            return;
        }
        
        $this->info("Traitement de {$emptyOrders->count()} commande(s) vide(s)...");
        
        $progressBar = $this->output->createProgressBar($emptyOrders->count());
        $progressBar->start();
        
        $updated = 0;
        
        foreach ($emptyOrders as $order) {
            try {
                $commerce = $order->commerce;
                
                if (!$commerce || $commerce->products->isEmpty()) {
                    $this->warn("Commande #{$order->order_number}: Commerce sans produits.");
                    continue;
                }
                
                // Prendre 1 à 3 produits au hasard du commerce
                $products = $commerce->products->random(min(3, $commerce->products->count()));
                $subtotal = 0;
                
                foreach ($products as $product) {
                    $quantity = rand(1, 2);
                    $price = $product->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'product_name' => $product->name,
                        'product_image' => $product->image,
                        'product_description' => $product->description,
                    ]);
                    
                    $subtotal += $quantity * $price;
                }
                
                // Mettre à jour les totaux de la commande
                $total = $subtotal + $order->delivery_fee - $order->discount;
                $order->update([
                    'subtotal' => $subtotal,
                    'total' => $total,
                ]);
                
                $updated++;
                
            } catch (\Exception $e) {
                $this->error("Erreur pour la commande #{$order->order_number}: " . $e->getMessage());
            }
            
            $progressBar->advance();
        }
        
        $progressBar->finish();
        $this->newLine();
        
        $this->info("Traitement terminé !");
        $this->info("Commandes mises à jour : {$updated}");
    }
}
