<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $clients = [
            [
                'nom' => 'Kouassi',
                'prenoms' => 'Aya Marie',
                'email' => 'aya.kouassi@email.com',
                'indicatif' => '+225',
                'numero_telephone' => '0102030405',
                'date_naissance' => '1992-05-15',
                'lieu_naissance' => 'Abidjan',
                'ville' => 'Abidjan',
                'commune' => 'Cocody',
                'numero_cni' => 'CI0123456789',
                'password' => Hash::make('password123'),
                'account_type' => 'client',
                'created_at' => now()->subDays(30),
            ],
            [
                'nom' => 'Diabaté',
                'prenoms' => 'Amadou',
                'email' => 'amadou.diabate@email.com',
                'indicatif' => '+225',
                'numero_telephone' => '0506070809',
                'date_naissance' => '1988-11-22',
                'lieu_naissance' => 'Bouaké',
                'ville' => 'Abidjan',
                'commune' => 'Marcory',
                'numero_cni' => 'CI0987654321',
                'password' => Hash::make('password123'),
                'account_type' => 'client',
                'created_at' => now()->subDays(15),
            ],
            [
                'nom' => 'Bamba',
                'prenoms' => 'Fatou',
                'email' => 'fatou.bamba@email.com',
                'indicatif' => '+225',
                'numero_telephone' => '0708090102',
                'date_naissance' => '1995-03-08',
                'lieu_naissance' => 'Yamoussoukro',
                'ville' => 'Abidjan',
                'commune' => 'Plateau',
                'numero_passeport' => 'PS0123456',
                'password' => Hash::make('password123'),
                'account_type' => 'client',
                'created_at' => now()->subDays(7),
            ],
            [
                'nom' => 'Ouattara',
                'prenoms' => 'Moussa',
                'email' => 'moussa.ouattara@email.com',
                'indicatif' => '+225',
                'numero_telephone' => '0304050607',
                'date_naissance' => '1990-07-12',
                'lieu_naissance' => 'Korhogo',
                'ville' => 'Abidjan',
                'commune' => 'Adjamé',
                'numero_cni' => 'CI0555666777',
                'password' => Hash::make('password123'),
                'account_type' => 'client',
                'created_at' => now()->subDays(3),
            ],
            [
                'nom' => 'Yao',
                'prenoms' => 'Akissi Brigitte',
                'email' => 'brigitte.yao@email.com',
                'indicatif' => '+225',
                'numero_telephone' => '0809010203',
                'date_naissance' => '1985-12-25',
                'lieu_naissance' => 'San Pedro',
                'ville' => 'Abidjan',
                'commune' => 'Treichville',
                'numero_cni' => 'CI0888999000',
                'password' => Hash::make('password123'),
                'account_type' => 'client',
                'created_at' => now()->subDays(1),
            ],
        ];

        foreach ($clients as $clientData) {
            User::create($clientData);
        }
    }
} 