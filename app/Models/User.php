<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Énumération pour le type de compte
 */
enum AccountType: string
{
    case CLIENT = 'client';
    case ADMIN = 'admin';
    case AGENT = 'agent';
}

/**
 * Énumération pour les rôles
 */
enum UserRole: string
{
    case USER = 'user';
    case MODERATOR = 'moderator';
    case MANAGER = 'manager';
    case DEVELOPER = 'developer';
}

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'nom',
        'prenoms',
        'date_naissance',
        'lieu_naissance',
        'ville',
        'commune',
        'photo',
        'email',
        'indicatif',
        'numero_telephone',
        'password',
        'account_type',
        'is_super_admin',
        'numero_cni',
        'numero_passeport',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'date_naissance' => 'date',
            'account_type' => AccountType::class,
            'role' => UserRole::class,
            'is_super_admin' => 'boolean',
        ];
    }

    /**
     * Vérifier si l'utilisateur est un administrateur
     */
    public function isAdmin(): bool
    {
        return $this->account_type === AccountType::ADMIN || $this->is_super_admin;
    }

    /**
     * Vérifier si l'utilisateur est un super administrateur
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    /**
     * Obtenir le nom complet de l'utilisateur
     */
    public function getFullNameAttribute(): string
    {
        return $this->prenoms . ' ' . $this->nom;
    }

    /**
     * Obtenir l'URL de la photo ou une photo par défaut
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo) {
            return asset('storage/' . $this->photo);
        }
        
        // Image par défaut selon le type d'utilisateur
        return match($this->account_type) {
            AccountType::AGENT => asset('images/delivery-avatar-placeholder.png'),
            AccountType::ADMIN => asset('images/admin-avatar-placeholder.png'),
            AccountType::CLIENT => asset('images/client-avatar-placeholder.png'),
            default => asset('images/default-avatar.png'),
        };
    }

    /**
     * Obtenir le numéro de téléphone complet avec indicatif
     */
    public function getFullPhoneAttribute(): string
    {
        if (!$this->numero_telephone) {
            return '';
        }
        return $this->indicatif . ' ' . $this->numero_telephone;
    }

    /**
     * Obtenir le numéro de téléphone formaté pour l'affichage
     */
    public function getFormattedPhoneAttribute(): string
    {
        if (!$this->numero_telephone) {
            return '';
        }
        
        $numero = $this->numero_telephone;
        // Format: XX XX XX XX XX pour un numéro à 10 chiffres
        if (strlen($numero) === 10) {
            $formatted = substr($numero, 0, 2) . ' ' . 
                        substr($numero, 2, 2) . ' ' . 
                        substr($numero, 4, 2) . ' ' . 
                        substr($numero, 6, 2) . ' ' . 
                        substr($numero, 8, 2);
            return $this->indicatif . ' ' . $formatted;
        }
        
        return $this->indicatif . ' ' . $numero;
    }

    /**
     * Vérifier si l'utilisateur est un agent
     */
    public function isAgent(): bool
    {
        return $this->account_type === AccountType::AGENT;
    }

    /**
     * Relation avec le panier de l'utilisateur
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }

    /**
     * Obtenir ou créer le panier de l'utilisateur
     */
    public function getOrCreateCart()
    {
        return Cart::getOrCreateForUser($this->id);
    }
}
