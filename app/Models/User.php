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
     * Vérifier si l'utilisateur est un administrateur (accès complet au panel admin)
     * Seuls les super admins, developers et managers ont accès
     */
    public function isAdmin(): bool
    {
        // Super admin a toujours accès
        if ($this->is_super_admin) {
            return true;
        }
        
        // Pour les account_type admin, vérifier le rôle
        if ($this->account_type === AccountType::ADMIN) {
            return in_array($this->role, [
                UserRole::DEVELOPER,
                UserRole::MANAGER
            ]);
        }
        
        return false;
    }

    /**
     * Vérifier si l'utilisateur est un super administrateur
     */
    public function isSuperAdmin(): bool
    {
        return $this->is_super_admin;
    }

    /**
     * Vérifier si l'utilisateur peut modérer (access limité)
     */
    public function canModerate(): bool
    {
        if ($this->isAdmin()) {
            return true;
        }
        
        return $this->account_type === AccountType::ADMIN && $this->role === UserRole::MODERATOR;
    }

    /**
     * Vérifier si l'utilisateur peut gérer les utilisateurs
     */
    public function canManageUsers(): bool
    {
        if ($this->is_super_admin) {
            return true;
        }
        
        return $this->account_type === AccountType::ADMIN && 
               in_array($this->role, [UserRole::DEVELOPER, UserRole::MANAGER]);
    }

    /**
     * Vérifier si l'utilisateur peut gérer les commerces
     */
    public function canManageCommerces(): bool
    {
        return $this->isAdmin(); // Seuls les vrais admins
    }

    /**
     * Vérifier si l'utilisateur peut voir les statistiques complètes
     */
    public function canViewFullStats(): bool
    {
        return $this->isAdmin(); // Seuls les vrais admins
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
        
        // Générer un avatar par défaut basé sur les initiales et le type d'utilisateur
        $name = urlencode($this->full_name);
        
        // Couleurs selon le type d'utilisateur
        $colors = match($this->account_type) {
            AccountType::AGENT => ['background' => '28a745', 'color' => 'ffffff'], // Vert
            AccountType::ADMIN => ['background' => '003049', 'color' => 'ffffff'], // Bleu foncé
            AccountType::CLIENT => ['background' => 'F77F00', 'color' => 'ffffff'], // Orange
            default => ['background' => '6c757d', 'color' => 'ffffff'], // Gris
        };
        
        // Utiliser le service UI Avatars avec fallback local
        try {
            // Essayer d'abord le service externe
            $externalUrl = "https://ui-avatars.com/api/?name={$name}&size=150&background={$colors['background']}&color={$colors['color']}&bold=true&format=png";
            
            // Pour éviter les problèmes de réseau, on retourne l'URL externe
            // mais on a des images locales en fallback
            return $externalUrl;
        } catch (\Exception $e) {
            // En cas d'erreur, utiliser les images locales
            return $this->getLocalAvatarUrl();
        }
    }

    /**
     * Obtenir l'URL de l'avatar local par défaut
     */
    public function getLocalAvatarUrl(): string
    {
        return match($this->account_type) {
            AccountType::AGENT => asset('images/delivery-avatar-placeholder.png'),
            AccountType::ADMIN => asset('images/admin-avatar-placeholder.png'),
            AccountType::CLIENT => asset('images/client-avatar-placeholder.png'),
            default => asset('images/default-avatar.png'),
        };
    }

    /**
     * Générer un avatar SVG local par défaut
     */
    public function getLocalAvatarAttribute(): string
    {
        $initials = $this->initials;
        
        // Couleurs selon le type d'utilisateur
        $colors = match($this->account_type) {
            AccountType::AGENT => ['background' => '#28a745', 'color' => '#ffffff'], // Vert
            AccountType::ADMIN => ['background' => '#003049', 'color' => '#ffffff'], // Bleu foncé
            AccountType::CLIENT => ['background' => '#F77F00', 'color' => '#ffffff'], // Orange
            default => ['background' => '#6c757d', 'color' => '#ffffff'], // Gris
        };
        
        $svg = '
        <svg xmlns="http://www.w3.org/2000/svg" width="150" height="150" viewBox="0 0 150 150">
            <rect width="150" height="150" fill="' . $colors['background'] . '" rx="75"/>
            <text x="75" y="85" text-anchor="middle" font-family="Arial, sans-serif" font-size="48" font-weight="bold" fill="' . $colors['color'] . '">' . $initials . '</text>
        </svg>';
        
        return 'data:image/svg+xml;base64,' . base64_encode($svg);
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

    /**
     * Relation avec les adresses de l'utilisateur
     */
    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    /**
     * Relation avec les commandes de l'utilisateur
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Obtenir l'adresse par défaut de l'utilisateur
     */
    public function getDefaultAddressAttribute()
    {
        return $this->addresses()->where('is_default', true)->first();
    }

    /**
     * Obtenir les initiales de l'utilisateur pour l'avatar
     */
    public function getInitialsAttribute(): string
    {
        $names = explode(' ', $this->full_name);
        $initials = '';
        
        foreach ($names as $name) {
            $initials .= strtoupper(substr($name, 0, 1));
        }
        
        return substr($initials, 0, 2);
    }
}
