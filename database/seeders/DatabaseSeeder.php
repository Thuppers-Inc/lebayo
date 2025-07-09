<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\AccountType;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seeders pour les données de base
        $this->call([
            CommerceTypeSeeder::class,
            CategorySeeder::class,
            CommerceSeeder::class,
            ProductSeeder::class,
            UserSeeder::class,
            ClientSeeder::class,
        ]);
        
        // Créer un super administrateur
        User::factory()->superAdmin()->create([
            'nom' => 'TRAORE',
            'prenoms' => 'Ismael Junior',
            'email' => 'ismaeltraore900@gmail.com',
            'indicatif' => '+225',
            'numero_telephone' => '0788948127',
            'ville' => 'Abidjan',
            'commune' => 'Cocody',
        ]);

        // Créer un administrateur
        // User::factory()->admin()->create([
        //     'nom' => 'KOUAME',
        //     'prenoms' => 'Marie Claire',
        //     'email' => 'manager@lebayo.com',
        //     'indicatif' => '+225',
        //     'numero_telephone' => '0511223344',
        //     'ville' => 'Abidjan',
        //     'commune' => 'Plateau',
        // ]);

        // // Créer quelques agents
        // User::factory()->agent()->count(3)->create();

        // // Créer quelques utilisateurs clients
        // User::factory()->client()->count(5)->create();

        // // Créer des utilisateurs avec photos
        // User::factory()->client()->withPhoto()->count(3)->create();

        // Créer un utilisateur de test
        // User::factory()->client()->create([
        //     'nom' => 'Test',
        //     'prenoms' => 'Utilisateur',
        //     'email' => 'test@example.com',
        //     'indicatif' => '+225',
        //     'numero_telephone' => '0123456789',
        //     'ville' => 'Abidjan',
        //     'commune' => 'Yopougon',
        // ]);

        // Créer des modérateurs
        // User::factory()->count(2)->create([
        //     'account_type' => AccountType::ADMIN,
        //     'role' => UserRole::MODERATOR,
        // ]);

        // Créer un agent avec un indicatif différent
        // User::factory()->agent()->withIndicatif('+33')->create([
        //     'nom' => 'MARTIN',
        //     'prenoms' => 'Jean Pierre',
        //     'email' => 'agent.france@lebayo.com',
        //     'numero_telephone' => '0612345678',
        //     'ville' => 'Paris',
        // ]);
    }
}
