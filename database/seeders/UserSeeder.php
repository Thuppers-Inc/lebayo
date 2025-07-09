<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer un utilisateur administrateur
        // User::firstOrCreate(
        //     ['email' => 'admin@lebayo.com'],
        //     [
        //         'nom' => 'ADMIN',
        //         'prenoms' => 'Administrateur',
        //         'password' => Hash::make('password'),
        //         'account_type' => 'admin',
        //         'is_super_admin' => true,
        //         'indicatif' => '+221',
        //         'numero_telephone' => '771234567',
        //         'ville' => 'Dakar',
        //         'commune' => 'Plateau',
        //         'date_naissance' => '1990-01-01',
        //     ]
        // );

        // Créer un utilisateur client
        // User::firstOrCreate(
        //     ['email' => 'client@lebayo.com'],
        //     [
        //         'nom' => 'CLIENT',
        //         'prenoms' => 'Test',
        //         'password' => Hash::make('password'),
        //         'account_type' => 'client',
        //         'is_super_admin' => false,
        //         'indicatif' => '+221',
        //         'numero_telephone' => '779876543',
        //         'ville' => 'Dakar',
        //         'commune' => 'Sacré-Cœur',
        //         'date_naissance' => '1995-05-15',
        //     ]
        // );

        // Créer un utilisateur livreur
        // User::firstOrCreate(
        //     ['email' => 'livreur@lebayo.com'],
        //     [
        //         'nom' => 'LIVREUR',
        //         'prenoms' => 'Test',
        //         'password' => Hash::make('password'),
        //         'account_type' => 'agent',
        //         'is_super_admin' => false,
        //         'indicatif' => '+221',
        //         'numero_telephone' => '774567890',
        //         'ville' => 'Dakar',
        //         'commune' => 'Médina',
        //         'date_naissance' => '1988-12-20',
        //     ]
        // );

        // Créer quelques utilisateurs clients supplémentaires
        // User::firstOrCreate(
        //     ['email' => 'marie.diop@email.com'],
        //     [
        //         'nom' => 'DIOP',
        //         'prenoms' => 'Marie',
        //         'password' => Hash::make('password'),
        //         'account_type' => 'client',
        //         'is_super_admin' => false,
        //         'indicatif' => '+221',
        //         'numero_telephone' => '771112233',
        //         'ville' => 'Dakar',
        //         'commune' => 'Pikine',
        //         'date_naissance' => '1992-07-10',
        //     ]
        // );

        // User::firstOrCreate(
        //     ['email' => 'ibrahima.sarr@email.com'],
        //     [
        //         'nom' => 'SARR',
        //         'prenoms' => 'Ibrahima',
        //         'password' => Hash::make('password'),
        //         'account_type' => 'client',
        //         'is_super_admin' => false,
        //         'indicatif' => '+221',
        //         'numero_telephone' => '774445566',
        //         'ville' => 'Dakar',
        //         'commune' => 'Guédiawaye',
        //         'date_naissance' => '1985-03-25',
        //     ]
        // );

        // User::firstOrCreate(
        //     ['email' => 'fatou.ndiaye@email.com'],
        //     [
        //         'nom' => 'NDIAYE',
        //         'prenoms' => 'Fatou',
        //         'password' => Hash::make('password'),
        //         'account_type' => 'client',
        //         'is_super_admin' => false,
        //         'indicatif' => '+221',
        //         'numero_telephone' => '777778899',
        //         'ville' => 'Dakar',
        //         'commune' => 'Parcelles Assainies',
        //         'date_naissance' => '1993-11-08',
        //     ]
        // );

        // $this->command->info('✅ Utilisateurs de test créés avec succès !');
        // $this->command->info('📧 Admin: admin@lebayo.com | 🔐 Mot de passe: password');
        // $this->command->info('📧 Client: client@lebayo.com | 🔐 Mot de passe: password');
        // $this->command->info('📧 Livreur: livreur@lebayo.com | 🔐 Mot de passe: password');
    }
} 