<?php

namespace Database\Seeders;

use App\Models\CommerceType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommerceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $commerceTypes = [
            [
                'name' => 'Restaurants',
                'emoji' => '🍕',
                'description' => 'Restaurants, fast-foods, boulangeries et tous types d\'établissements de restauration',
                'is_active' => true,
            ],
            [
                'name' => 'Boutiques',
                'emoji' => '🛍️',
                'description' => 'Magasins de vêtements, accessoires, chaussures et articles de mode',
                'is_active' => true,
            ],
            [
                'name' => 'Pharmacies',
                'emoji' => '💊',
                'description' => 'Pharmacies et parapharmacies pour médicaments et produits de santé',
                'is_active' => true,
            ],
            [
                'name' => 'Supermarchés',
                'emoji' => '🛒',
                'description' => 'Supermarchés, épiceries et magasins d\'alimentation générale',
                'is_active' => true,
            ],
        ];

        foreach ($commerceTypes as $type) {
            CommerceType::updateOrCreate(
                ['name' => $type['name']],
                $type
            );
        }
    }
}
