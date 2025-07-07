<?php

namespace Database\Factories;

use App\Models\AccountType;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $villes = ['Abidjan', 'Bouaké', 'Daloa', 'Yamoussoukro', 'San-Pédro', 'Korhogo', 'Man', 'Divo', 'Gagnoa', 'Anyama'];
        $communes = ['Cocody', 'Yopougon', 'Adjamé', 'Plateau', 'Marcory', 'Treichville', 'Koumassi', 'Port-Bouët', 'Abobo', 'Attécoubé'];
        $lieux_naissance = ['Abidjan', 'Bouaké', 'Daloa', 'Yamoussoukro', 'Korhogo', 'Man', 'Divo', 'Gagnoa', 'Bassam', 'Dabou'];

        return [
            'nom' => fake()->lastName(),
            'prenoms' => fake()->firstName() . ' ' . fake()->firstName(),
            'date_naissance' => fake()->dateTimeBetween('-60 years', '-18 years')->format('Y-m-d'),
            'lieu_naissance' => fake()->randomElement($lieux_naissance),
            'ville' => fake()->randomElement($villes),
            'commune' => fake()->randomElement($communes),
            'photo' => null, // Sera défini séparément si nécessaire
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'indicatif' => '+225',
            'numero_telephone' => fake()->numerify('##########'), // 10 chiffres sans espace
            'password' => static::$password ??= Hash::make('password'),
            'account_type' => fake()->randomElement(AccountType::cases()),
            'is_super_admin' => false,
            'role' => fake()->randomElement(UserRole::cases()),
            'numero_cni' => fake()->numerify('CI############'),
            'numero_passeport' => fake()->optional(0.3)->numerify('##CI#####'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Créer un utilisateur administrateur.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => AccountType::ADMIN,
            'role' => UserRole::MANAGER,
        ]);
    }

    /**
     * Créer un super administrateur.
     */
    public function superAdmin(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => AccountType::ADMIN,
            'is_super_admin' => true,
            // 'role' => UserRole::DEVELOPER,
        ]);
    }

    /**
     * Créer un utilisateur client.
     */
    public function client(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => AccountType::CLIENT,
            'role' => UserRole::USER,
        ]);
    }

    /**
     * Créer un utilisateur agent.
     */
    public function agent(): static
    {
        return $this->state(fn (array $attributes) => [
            'account_type' => AccountType::AGENT,
            'role' => UserRole::MODERATOR,
        ]);
    }

    /**
     * Créer un utilisateur avec une photo.
     */
    public function withPhoto(): static
    {
        return $this->state(fn (array $attributes) => [
            'photo' => 'photos/user-' . fake()->uuid() . '.jpg',
        ]);
    }

    /**
     * Définir un indicatif personnalisé.
     */
    public function withIndicatif(string $indicatif): static
    {
        return $this->state(fn (array $attributes) => [
            'indicatif' => $indicatif,
        ]);
    }
}
