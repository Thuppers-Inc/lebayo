<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Commerce;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les commerces et catégories
        $commerces = Commerce::with('commerceType')->get();
        $categories = Category::all();
        
        // Produits pour chaque type de commerce (prix en FCFA)
        $productsData = [
            // Pour les restaurants (Pizza, Poulet, Burger)
            'Pizza Corner' => [
                ['name' => 'Pizza Margherita', 'category' => 'Pizza 🍕', 'price' => 8500, 'old_price' => 9800, 'description' => 'Tomate, mozzarella, basilic frais'],
                ['name' => 'Pizza 4 Fromages', 'category' => 'Pizza 🍕', 'price' => 10500, 'description' => 'Mozzarella, gorgonzola, parmesan, chèvre'],
                ['name' => 'Pizza Pepperoni', 'category' => 'Pizza 🍕', 'price' => 9800, 'description' => 'Tomate, mozzarella, pepperoni épicé'],
                ['name' => 'Coca-Cola 33cl', 'category' => 'Boissons 🥤', 'price' => 1650, 'stock' => 50, 'unit' => 'canettes'],
                ['name' => 'Tiramisu', 'category' => 'Desserts 🍰', 'price' => 4500, 'description' => 'Fait maison, mascarpone et café'],
            ],
            
            'Chez Mamadou' => [
                ['name' => 'Poulet Yassa', 'category' => 'Poulet 🍗', 'price' => 8900, 'description' => 'Poulet mariné aux oignons et citron'],
                ['name' => 'Poulet DG', 'category' => 'Poulet 🍗', 'price' => 9900, 'description' => 'Poulet sauté aux légumes et plantain'],
                ['name' => 'Thieboudienne', 'category' => 'Poissons 🐟', 'price' => 9800, 'description' => 'Riz au poisson, légumes sénégalais'],
                ['name' => 'Attiéké Poisson', 'category' => 'Poissons 🐟', 'price' => 8200, 'description' => 'Semoule de manioc et poisson braisé'],
                ['name' => 'Bissap', 'category' => 'Boissons 🥤', 'price' => 2000, 'description' => 'Boisson à l\'hibiscus maison'],
            ],
            
            'Le Gourmet' => [
                ['name' => 'Burger Classic', 'category' => 'Burger 🍔', 'price' => 7800, 'description' => 'Steak, salade, tomate, oignon, sauce'],
                ['name' => 'Burger Bacon Cheese', 'category' => 'Burger 🍔', 'price' => 9100, 'description' => 'Double steak, bacon, cheddar'],
                ['name' => 'Frites Classiques', 'category' => 'Frites 🍟', 'price' => 3000, 'description' => 'Pommes de terre fraîches'],
                ['name' => 'Frites de Patate Douce', 'category' => 'Frites 🍟', 'price' => 3600, 'description' => 'Alternatives healthy'],
                ['name' => 'Milkshake Vanille', 'category' => 'Boissons 🥤', 'price' => 3200, 'description' => 'Onctueux et crémeux'],
            ],
            
            // Pour les boutiques
            'Mode & Style' => [
                ['name' => 'T-shirt Coton Bio', 'category' => 'Vêtements 👕', 'price' => 19600, 'old_price' => 26200, 'description' => 'Coton biologique, plusieurs couleurs'],
                ['name' => 'Jean Slim Fit', 'category' => 'Vêtements 👕', 'price' => 52400, 'description' => 'Coupe moderne, denim stretch'],
                ['name' => 'Sneakers Blanches', 'category' => 'Chaussures 👟', 'price' => 59000, 'description' => 'Cuir véritable, confort optimal'],
                ['name' => 'Sac à Main Cuir', 'category' => 'Accessoires 👜', 'price' => 98300, 'description' => 'Cuir italien, plusieurs compartiments'],
            ],
            
            'Électro Plus' => [
                ['name' => 'Smartphone Galaxy', 'category' => 'Téléphones 📱', 'price' => 393000, 'old_price' => 458400, 'description' => '128GB, double SIM, garantie 2 ans'],
                ['name' => 'Écouteurs Bluetooth', 'category' => 'Audio 🎧', 'price' => 59000, 'description' => 'Réduction de bruit active'],
                ['name' => 'Chargeur Sans Fil', 'category' => 'Accessoires 🔌', 'price' => 16300, 'description' => 'Charge rapide 15W'],
                ['name' => 'Power Bank 10000mAh', 'category' => 'Accessoires 🔌', 'price' => 19600, 'description' => 'Charge rapide, USB-C'],
            ],
            
            // Pour les pharmacies
            'Pharmacie du Centre' => [
                ['name' => 'Paracétamol 1000mg', 'category' => 'Médicaments 💊', 'price' => 2300, 'stock' => 100, 'unit' => 'boîtes', 'description' => 'Antalgique, boîte de 8 comprimés'],
                ['name' => 'Vitamine C 500mg', 'category' => 'Médicaments 💊', 'price' => 5800, 'stock' => 50, 'description' => 'Complément alimentaire, 30 comprimés'],
                ['name' => 'Masques Chirurgicaux', 'category' => 'Hygiène 🧴', 'price' => 8500, 'stock' => 200, 'unit' => 'boîtes', 'description' => 'Boîte de 50 masques'],
                ['name' => 'Gel Hydroalcoolique', 'category' => 'Hygiène 🧴', 'price' => 3200, 'stock' => 80, 'description' => 'Flacon 250ml'],
                ['name' => 'Thermomètre Digital', 'category' => 'Matériel Médical 🩺', 'price' => 10400, 'description' => 'Mesure précise, affichage LCD'],
            ],
            
            // Pour les supermarchés
            'Supermarché Fresh' => [
                ['name' => 'Bananes Bio', 'category' => 'Fruits 🍌', 'price' => 1900, 'unit' => 'kg', 'description' => 'Bananes biologiques du commerce équitable'],
                ['name' => 'Pommes Golden', 'category' => 'Fruits 🍌', 'price' => 2300, 'unit' => 'kg', 'description' => 'Pommes croquantes et sucrées'],
                ['name' => 'Carottes Bio', 'category' => 'Légumes 🥕', 'price' => 1400, 'unit' => 'kg', 'description' => 'Carottes bio de saison'],
                ['name' => 'Lait Demi-Écrémé', 'category' => 'Produits Laitiers 🥛', 'price' => 1200, 'description' => 'Bouteille 1L, origine France'],
                ['name' => 'Pain de Mie Complet', 'category' => 'Boulangerie 🍞', 'price' => 1600, 'description' => 'Pain complet, 20 tranches'],
                ['name' => 'Eau Minérale', 'category' => 'Boissons 🥤', 'price' => 600, 'stock' => 200, 'description' => 'Bouteille 1.5L'],
            ],
            
            'Market Express' => [
                ['name' => 'Tomates Cerises', 'category' => 'Légumes 🥕', 'price' => 3200, 'unit' => 'barquette', 'description' => 'Tomates cerises 250g'],
                ['name' => 'Yaourt Nature', 'category' => 'Produits Laitiers 🥛', 'price' => 2100, 'description' => 'Pack de 4 yaourts'],
                ['name' => 'Pâtes Italiennes', 'category' => 'Épicerie 🥫', 'price' => 1300, 'description' => 'Spaghetti 500g, blé dur'],
                ['name' => 'Huile d\'Olive Extra', 'category' => 'Épicerie 🥫', 'price' => 5800, 'description' => 'Huile d\'olive vierge extra 500ml'],
                ['name' => 'Chocolat Noir 70%', 'category' => 'Confiserie 🍫', 'price' => 1900, 'description' => 'Tablette 100g, cacao équitable'],
            ]
        ];
        
        foreach ($commerces as $commerce) {
            if (isset($productsData[$commerce->name])) {
                foreach ($productsData[$commerce->name] as $productData) {
                    // Trouver la catégorie
                    $category = null;
                    if (isset($productData['category'])) {
                        $category = $categories->where('name', str_replace(['🍕', '🍗', '🍔', '🍟', '🥤', '🍰', '🐟', '👕', '👟', '👜', '📱', '🎧', '🔌', '💊', '🧴', '🩺', '🍌', '🥕', '🥛', '🍞', '🥫', '🍫'], '', $productData['category']))->first();
                    }
                    
                    // Product::create([
                    //     'name' => $productData['name'],
                    //     'description' => $productData['description'] ?? null,
                    //     'price' => $productData['price'],
                    //     'old_price' => $productData['old_price'] ?? null,
                    //     'stock' => $productData['stock'] ?? 0,
                    //     'unit' => $productData['unit'] ?? 'pièces',
                    //     'commerce_id' => $commerce->id,
                    //     'category_id' => $category?->id,
                    //     'is_available' => true,
                    //     'is_featured' => rand(0, 3) === 0, // 25% de chance d'être en vedette
                    //     'sku' => strtoupper(substr($commerce->name, 0, 3)) . '-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                    //     'preparation_time' => in_array($commerce->commerceType->name, ['Restaurants', 'Fast Food']) ? rand(5, 30) : null,
                    // ]);
                }
            }
        }
        
        // Les produits de base sont suffisants pour commencer
    }
}
