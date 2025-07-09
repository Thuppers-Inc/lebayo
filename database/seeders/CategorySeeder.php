<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\CommerceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Récupérer les types de commerce
        $restaurants = CommerceType::where('name', 'Restaurants')->first();
        $boutiques = CommerceType::where('name', 'Boutiques')->first();
        $pharmacies = CommerceType::where('name', 'Pharmacies')->first();
        $supermarches = CommerceType::where('name', 'Supermarchés')->first();

        // Catégories pour Restaurants
        if ($restaurants) {
            $restaurantCategories = [
                ['name' => 'Pizza', 'emoji' => '🍕', 'description' => 'Pizzas italiennes et spécialités'],
                ['name' => 'Poulet', 'emoji' => '🍗', 'description' => 'Plats à base de poulet grillé ou frit'],
                ['name' => 'Burger', 'emoji' => '🍔', 'description' => 'Hamburgers et sandwichs gourmets'],
                ['name' => 'Frites', 'emoji' => '🍟', 'description' => 'Frites et accompagnements frits'],
                ['name' => 'Burrito', 'emoji' => '🌯', 'description' => 'Cuisine mexicaine et tex-mex'],
                ['name' => 'Taco', 'emoji' => '🌮', 'description' => 'Tacos et spécialités mexicaines'],
                ['name' => 'Muffin', 'emoji' => '🧁', 'description' => 'Pâtisseries et desserts sucrés'],
                ['name' => 'Viande', 'emoji' => '🥩', 'description' => 'Grillades et plats de viande'],
                ['name' => 'Sushi', 'emoji' => '🍣', 'description' => 'Cuisine japonaise et sushis'],
                ['name' => 'Pâtes', 'emoji' => '🍝', 'description' => 'Pâtes italiennes et sauces'],
            ];

            foreach ($restaurantCategories as $category) {
                Category::create([
                    'commerce_type_id' => $restaurants->id,
                    'name' => $category['name'],
                    'emoji' => $category['emoji'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]);
            }
        }

        // Catégories pour Boutiques
        if ($boutiques) {
            $boutiqueCategories = [
                ['name' => 'Vêtements', 'emoji' => '👕', 'description' => 'Vêtements et accessoires de mode'],
                ['name' => 'Chaussures', 'emoji' => '👟', 'description' => 'Chaussures pour tous les styles'],
                ['name' => 'Sacs', 'emoji' => '👜', 'description' => 'Sacs à main et maroquinerie'],
                ['name' => 'Bijoux', 'emoji' => '💍', 'description' => 'Bijoux et accessoires précieux'],
                ['name' => 'Parfums', 'emoji' => '🧴', 'description' => 'Parfums et cosmétiques'],
            ];

            foreach ($boutiqueCategories as $category) {
                Category::create([
                    'commerce_type_id' => $boutiques->id,
                    'name' => $category['name'],
                    'emoji' => $category['emoji'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]);
            }
        }

        // Catégories pour Pharmacies
        if ($pharmacies) {
            $pharmacieCategories = [
                ['name' => 'Médicaments', 'emoji' => '💊', 'description' => 'Médicaments sur ordonnance'],
                ['name' => 'Parapharmacie', 'emoji' => '🧴', 'description' => 'Produits de soins et beauté'],
                ['name' => 'Vitamines', 'emoji' => '💊', 'description' => 'Compléments alimentaires'],
                ['name' => 'Premiers secours', 'emoji' => '🩹', 'description' => 'Matériel de premiers secours'],
            ];

            foreach ($pharmacieCategories as $category) {
                Category::create([
                    'commerce_type_id' => $pharmacies->id,
                    'name' => $category['name'],
                    'emoji' => $category['emoji'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]);
            }
        }

        // Catégories pour Supermarchés
        if ($supermarches) {
            $supermarcheCategories = [
                ['name' => 'Fruits & Légumes', 'emoji' => '🥕', 'description' => 'Produits frais du marché'],
                ['name' => 'Boulangerie', 'emoji' => '🥖', 'description' => 'Pain frais et viennoiseries'],
                ['name' => 'Viandes', 'emoji' => '🥩', 'description' => 'Boucherie et charcuterie'],
                ['name' => 'Poissons', 'emoji' => '🐟', 'description' => 'Poissonnerie fraîche'],
                ['name' => 'Produits laitiers', 'emoji' => '🥛', 'description' => 'Lait, fromages et yaourts'],
                ['name' => 'Épicerie', 'emoji' => '🥫', 'description' => 'Produits d\'épicerie générale'],
            ];

            foreach ($supermarcheCategories as $category) {
                Category::create([
                    'commerce_type_id' => $supermarches->id,
                    'name' => $category['name'],
                    'emoji' => $category['emoji'],
                    'description' => $category['description'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
