<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Address;
use App\Models\Commerce;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer des utilisateurs, adresses, commerces et produits existants
        $users = User::all();
        $addresses = Address::all();
        $commerces = Commerce::with('products')->get();
        
        if ($users->isEmpty() || $addresses->isEmpty() || $commerces->isEmpty()) {
            $this->command->info('Veuillez d\'abord créer des utilisateurs, adresses et commerces avec des produits.');
            return;
        }
        
        $this->command->info('Création de commandes de test...');
        
        // Créer 10 commandes de test
        for ($i = 1; $i <= 10; $i++) {
            $user = $users->random();
            $address = $addresses->random();
            $commerce = $commerces->random();
            
            // Vérifier que le commerce a des produits
            if ($commerce->products->isEmpty()) {
                continue;
            }
            
            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'commerce_id' => $commerce->id,
                'order_number' => Order::generateOrderNumber(),
                'delivery_address_id' => $address->id,
                'payment_method' => collect(['cash_on_delivery', 'card', 'mobile_money'])->random(),
                'status' => collect(['pending', 'confirmed', 'preparing', 'ready', 'delivered'])->random(),
                'payment_status' => collect(['pending', 'paid'])->random(),
                'subtotal' => 0,
                'delivery_fee' => rand(0, 1000),
                'discount' => 0,
                'total' => 0,
                'notes' => 'Commande de test #' . $i,
                'estimated_delivery_time' => now()->addHours(rand(1, 3)),
            ]);
            
            // Ajouter 1 à 5 produits à la commande
            $productsToAdd = $commerce->products->random(rand(1, min(5, $commerce->products->count())));
            $subtotal = 0;
            
            foreach ($productsToAdd as $product) {
                $quantity = rand(1, 3);
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
        }
        
        $this->command->info('Commandes de test créées avec succès !');
    }
}
