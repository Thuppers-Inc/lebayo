<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Commerce;
use App\Models\CommerceType;
use App\Models\Category;

class CommerceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les types de commerce
        $restaurantType = CommerceType::where('name', 'Restaurant')->first();
        $boutiqueType = CommerceType::where('name', 'Boutique')->first();
        $pharmacieType = CommerceType::where('name', 'Pharmacie')->first();
        $supermarcheType = CommerceType::where('name', 'Supermarché')->first();

        // Récupérer quelques catégories
        $pizzaCategory = Category::where('name', 'Pizza')->first();
        $burgerCategory = Category::where('name', 'Burger')->first();
        $pouletCategory = Category::where('name', 'Poulet')->first();

        // Commerces d'exemple
        $commerces = [
            // Restaurants
            [
                'name' => 'Pizza Mario',
                'commerce_type_id' => $restaurantType?->id ?? 1,
                'city' => 'Paris',
                'address' => '123 Rue de la Paix, 75001 Paris',
                'contact' => 'Mario Rossi',
                'phone' => '+33 1 42 33 44 55',
                'email' => 'contact@pizzamario.com',
                'description' => 'Authentique pizzeria italienne avec des recettes traditionnelles transmises de génération en génération.',
                'is_active' => true,
                'categories' => [$pizzaCategory?->id]
            ],
            [
                'name' => 'Burger House',
                'commerce_type_id' => $restaurantType?->id ?? 1,
                'city' => 'Lyon',
                'address' => '45 Avenue de la République, 69001 Lyon',
                'contact' => 'Jean-Pierre Dubois',
                'phone' => '+33 4 72 11 22 33',
                'email' => 'contact@burgerhouse.fr',
                'description' => 'Burgers artisanaux avec des produits frais et locaux.',
                'is_active' => true,
                'categories' => [$burgerCategory?->id]
            ],
            [
                'name' => 'Chicken & Co',
                'commerce_type_id' => $restaurantType?->id ?? 1,
                'city' => 'Marseille',
                'address' => '78 La Canebière, 13001 Marseille',
                'contact' => 'Sophie Martin',
                'phone' => '+33 4 91 55 66 77',
                'email' => 'contact@chickenco.fr',
                'description' => 'Spécialiste du poulet grillé et des plats mijotés.',
                'is_active' => true,
                'categories' => [$pouletCategory?->id]
            ],
            [
                'name' => 'Sushi Tokyo',
                'commerce_type_id' => $restaurantType?->id ?? 1,
                'city' => 'Nice',
                'address' => '15 Promenade des Anglais, 06000 Nice',
                'contact' => 'Takeshi Yamamoto',
                'phone' => '+33 4 93 87 65 43',
                'email' => 'contact@sushitokyo.fr',
                'description' => 'Restaurant japonais authentique avec des sushis frais préparés par un chef japonais.',
                'is_active' => true,
                'categories' => []
            ],
            [
                'name' => 'Brasserie du Coin',
                'commerce_type_id' => $restaurantType?->id ?? 1,
                'city' => 'Bordeaux',
                'address' => '32 Rue Sainte-Catherine, 33000 Bordeaux',
                'contact' => 'Pierre Rousseau',
                'phone' => '+33 5 56 44 33 22',
                'email' => 'contact@brasserieducoin.fr',
                'description' => 'Brasserie traditionnelle française avec une cuisine de bistrot.',
                'is_active' => false,
                'categories' => []
            ],

            // Boutiques
            [
                'name' => 'Mode & Style',
                'commerce_type_id' => $boutiqueType?->id ?? 2,
                'city' => 'Paris',
                'address' => '67 Rue de Rivoli, 75001 Paris',
                'contact' => 'Catherine Leroy',
                'phone' => '+33 1 42 77 88 99',
                'email' => 'contact@modestyle.fr',
                'description' => 'Boutique de mode féminine avec les dernières tendances.',
                'is_active' => true,
                'categories' => []
            ],
            [
                'name' => 'Bijoux Précieux',
                'commerce_type_id' => $boutiqueType?->id ?? 2,
                'city' => 'Lyon',
                'address' => '89 Rue du Président Édouard Herriot, 69002 Lyon',
                'contact' => 'Marie Dubois',
                'phone' => '+33 4 78 22 33 44',
                'email' => 'contact@bijouxprecieux.fr',
                'description' => 'Bijouterie fine avec des créations artisanales.',
                'is_active' => true,
                'categories' => []
            ],

            // Pharmacies
            [
                'name' => 'Pharmacie Centrale',
                'commerce_type_id' => $pharmacieType?->id ?? 3,
                'city' => 'Toulouse',
                'address' => '12 Place du Capitole, 31000 Toulouse',
                'contact' => 'Dr. Michel Blanc',
                'phone' => '+33 5 61 11 22 33',
                'email' => 'contact@pharmaciecentrale.fr',
                'description' => 'Pharmacie au cœur de Toulouse avec service de garde.',
                'is_active' => true,
                'categories' => []
            ],

            // Supermarchés
            [
                'name' => 'Super Fresh',
                'commerce_type_id' => $supermarcheType?->id ?? 4,
                'city' => 'Lille',
                'address' => '156 Rue de la Monnaie, 59000 Lille',
                'contact' => 'Antoine Moreau',
                'phone' => '+33 3 20 55 66 77',
                'email' => 'contact@superfresh.fr',
                'description' => 'Supermarché de proximité avec des produits frais et locaux.',
                'is_active' => true,
                'categories' => []
            ]
        ];

        // Créer les commerces
        foreach ($commerces as $commerceData) {
            $categories = $commerceData['categories'] ?? [];
            unset($commerceData['categories']);

            $commerce = Commerce::create($commerceData);

            // Associer les catégories
            if (!empty($categories)) {
                $commerce->categories()->sync(array_filter($categories));
            }
        }

        $this->command->info('✅ Commerces créés avec succès !');
    }
}
