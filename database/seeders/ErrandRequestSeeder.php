<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\ErrandRequest;
use App\Models\User;

class ErrandRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer un utilisateur existant ou créer un utilisateur de test
        $user = User::first();
        
        if (!$user) {
            $this->command->error('Aucun utilisateur trouvé. Veuillez d\'abord exécuter UserSeeder.');
            return;
        }

        $errands = [
            [
                'user_id' => $user->id,
                'title' => 'Acheter des médicaments',
                'description' => 'J\'ai besoin d\'acheter des médicaments pour ma mère. Il me faut des comprimés contre la fièvre et des vitamines.',
                'pickup_address' => 'Pharmacie Centrale, Avenue de la Paix, Abidjan',
                'delivery_address' => 'Résidence Les Cocotiers, Rue des Palmiers, Cocody, Abidjan',
                'estimated_cost' => 15000,
                'urgency_level' => 'high',
                'status' => 'pending',
                'contact_phone' => '+2250701234567',
                'notes' => 'Merci de vérifier la date d\'expiration des médicaments.',
                'preferred_delivery_time' => now()->addHours(2)
            ],
            [
                'user_id' => $user->id,
                'title' => 'Retirer un colis',
                'description' => 'Je dois retirer un colis au bureau de poste. C\'est un colis fragile qui contient des documents importants.',
                'pickup_address' => 'Bureau de Poste Central, Boulevard de la République, Plateau, Abidjan',
                'delivery_address' => 'Bureau de Direction, Rue du Commerce, Treichville, Abidjan',
                'estimated_cost' => 8000,
                'urgency_level' => 'medium',
                'status' => 'accepted',
                'contact_phone' => '+2250701234567',
                'notes' => 'Le colis est fragile, merci de le manipuler avec précaution.',
                'preferred_delivery_time' => now()->addHours(3)
            ],
            [
                'user_id' => $user->id,
                'title' => 'Acheter des fruits et légumes',
                'description' => 'J\'ai besoin d\'acheter des fruits frais et des légumes pour la semaine. Privilégier les produits locaux.',
                'pickup_address' => 'Marché de Cocody, Avenue des Jardins, Cocody, Abidjan',
                'delivery_address' => 'Appartement 15, Résidence Le Soleil, Rue des Fleurs, Cocody, Abidjan',
                'estimated_cost' => 12000,
                'urgency_level' => 'low',
                'status' => 'completed',
                'contact_phone' => '+2250701234567',
                'notes' => 'Préférer les bananes plantains et les tomates fraîches.',
                'preferred_delivery_time' => now()->addHours(1)
            ],
            [
                'user_id' => $user->id,
                'title' => 'Livrer des documents urgents',
                'description' => 'Documents contractuels urgents à livrer au client. Signature requise.',
                'pickup_address' => 'Cabinet d\'avocats, Rue de la Justice, Plateau, Abidjan',
                'delivery_address' => 'Siège social, Boulevard Vridi, Zone 4, Abidjan',
                'estimated_cost' => 20000,
                'urgency_level' => 'urgent',
                'status' => 'in_progress',
                'contact_phone' => '+2250701234567',
                'notes' => 'Documents confidentiels. Signature obligatoire à la réception.',
                'preferred_delivery_time' => now()->addHours(1)
            ],
            [
                'user_id' => $user->id,
                'title' => 'Acheter du pain et des viennoiseries',
                'description' => 'Petit déjeuner pour la famille. Pain frais, croissants et brioches.',
                'pickup_address' => 'Boulangerie Le Petit Paris, Rue de la Gastronomie, Cocody, Abidjan',
                'delivery_address' => 'Maison familiale, Avenue des Roses, Cocody, Abidjan',
                'estimated_cost' => 5000,
                'urgency_level' => 'medium',
                'status' => 'cancelled',
                'contact_phone' => '+2250701234567',
                'notes' => 'Préférer le pain de campagne et les croissants au beurre.',
                'preferred_delivery_time' => now()->addHours(1)
            ]
        ];

        foreach ($errands as $errand) {
            ErrandRequest::create($errand);
        }

        $this->command->info('Demandes de course d\'exemple créées avec succès !');
    }
}
