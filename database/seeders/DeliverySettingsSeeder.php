<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\DeliverySettings;

class DeliverySettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les paramètres par défaut
        DeliverySettings::create([
            'delivery_fee_per_commerce' => 500,
            'first_order_discount' => 500,
            'free_delivery_threshold' => 0,
            'is_active' => true
        ]);

        $this->command->info('Paramètres de livraison par défaut créés avec succès !');
    }
}
