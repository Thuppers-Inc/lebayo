<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Commerce extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'logo',
        'commerce_type_id',
        'city',
        'address',
        'contact',
        'phone',
        'email',
        'description',
        'is_active',
        'opening_hours',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'opening_hours' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    /**
     * Relation avec le type de commerce
     */
    public function commerceType()
    {
        return $this->belongsTo(CommerceType::class);
    }

    /**
     * Relation avec les catégories (many-to-many)
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'commerce_categories');
    }

    /**
     * Relation avec les produits (one-to-many)
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Relation avec les produits disponibles
     */
    public function availableProducts()
    {
        return $this->hasMany(Product::class)->available();
    }

    /**
     * Relation avec les produits mis en avant
     */
    public function featuredProducts()
    {
        return $this->hasMany(Product::class)->featured();
    }

    /**
     * Accessor pour le statut formaté
     */
    public function getStatusBadgeAttribute()
    {
        return $this->is_active
            ? '<span class="badge bg-success">Actif</span>'
            : '<span class="badge bg-danger">Inactif</span>';
    }

    /**
     * Accessor pour l'adresse complète
     */
    public function getFullAddressAttribute()
    {
        return $this->address . ', ' . $this->city;
    }

    /**
     * Accessor pour le logo URL
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('commerces/' . $this->logo);
        }
        // Placeholder SVG pour les commerces sans logo
        return 'data:image/svg+xml;base64,' . base64_encode('
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <rect width="40" height="40" rx="20" fill="#e9ecef"/>
                <path d="M12 16h16v12H12V16z" fill="#6c757d"/>
                <path d="M14 14h12v2H14v-2z" fill="#6c757d"/>
                <circle cx="16" cy="20" r="1" fill="#fff"/>
                <circle cx="20" cy="20" r="1" fill="#fff"/>
                <circle cx="24" cy="20" r="1" fill="#fff"/>
            </svg>
        ');
    }

    /**
     * Accessor pour le nom du type de commerce
     */
    public function getCommerceTypeNameAttribute()
    {
        return $this->commerceType?->name ?? 'N/A';
    }

    /**
     * Accessor pour l'image placeholder selon le type de commerce
     */
    public function getPlaceholderImageAttribute()
    {
        if ($this->logo) {
            return asset('commerces/' . $this->logo);
        }

        // Images par défaut selon le type de commerce
        $defaultImages = [
            'Restaurant' => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=400&h=300&fit=crop',
            'Supermarché' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=300&fit=crop',
            'Pharmacie' => 'https://images.unsplash.com/photo-1576091160399-112ba8d25d1f?w=400&h=300&fit=crop',
            'Boulangerie' => 'https://images.unsplash.com/photo-1574484284002-952d92456975?w=400&h=300&fit=crop',
            'Épicerie' => 'https://images.unsplash.com/photo-1563379091339-03246963d25a?w=400&h=300&fit=crop',
        ];

        $typeName = $this->commerceType?->name;
        return $defaultImages[$typeName] ?? 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop';
    }

    /**
     * Scope pour les commerces actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope par type de commerce
     */
    public function scopeByType($query, $typeId)
    {
        return $query->where('commerce_type_id', $typeId);
    }

    /**
     * Scope par ville
     */
    public function scopeByCity($query, $city)
    {
        return $query->where('city', 'LIKE', '%' . $city . '%');
    }

    /**
     * Boot method pour générer automatiquement le slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($commerce) {
            if (empty($commerce->slug)) {
                $baseSlug = Str::slug($commerce->name);
                $slug = $baseSlug;
                $counter = 1;
                
                // Vérifier l'unicité du slug
                while (static::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $commerce->slug = $slug;
            }
        });

        static::updating(function ($commerce) {
            if ($commerce->isDirty('name') && empty($commerce->slug)) {
                $baseSlug = Str::slug($commerce->name);
                $slug = $baseSlug;
                $counter = 1;
                
                // Vérifier l'unicité du slug (en excluant l'ID actuel)
                while (static::where('slug', $slug)->where('id', '!=', $commerce->id)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                
                $commerce->slug = $slug;
            }
        });
    }

    /**
     * Obtenir la clé de route pour le binding de modèle
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * Résoudre le modèle pour le route model binding
     * Gère à la fois les IDs numériques (pour l'admin) et les slugs (pour le frontend)
     */
    public function resolveRouteBinding($value, $field = null)
    {
        // Si un champ spécifique est demandé, l'utiliser
        if ($field !== null) {
            return $this->where($field, $value)->firstOrFail();
        }
        
        // Si c'est un ID numérique (pour les routes admin), chercher par ID
        if (is_numeric($value)) {
            return $this->where('id', $value)->firstOrFail();
        }
        
        // Sinon, chercher par slug (pour les routes frontend)
        return $this->where('slug', $value)->firstOrFail();
    }

    /**
     * Accessor pour la note (rating) stable basée sur l'ID
     * Génère une note entre 3.2 et 4.9 qui reste constante pour chaque commerce
     */
    public function getRatingAttribute()
    {
        // Génère une note stable entre 3.2 et 4.9 basée sur l'ID
        // Utilise un modulo pour garantir une valeur stable
        $seed = $this->id * 17 + 23; // Multiplicateur pour varier les notes
        $rating = 3.2 + (($seed % 18) / 10); // Entre 3.2 et 4.9
        
        return round($rating, 1);
    }

    /**
     * Accessor pour le nombre d'avis (reviews count) stable basé sur l'ID
     * Génère un nombre d'avis réaliste qui reste constant
     */
    public function getReviewsCountAttribute()
    {
        // Génère un nombre d'avis stable entre 25 et 350 basé sur l'ID
        $seed = $this->id * 13 + 7;
        $count = 25 + ($seed % 326); // Entre 25 et 350
        
        return $count;
    }

    /**
     * Accessor pour le temps de livraison estimé stable
     * Génère un temps entre 15-45 min qui reste constant
     */
    public function getEstimatedDeliveryTimeAttribute()
    {
        // Génère un temps stable basé sur l'ID
        $seed = $this->id * 11 + 5;
        $minTime = 15 + ($seed % 21); // Entre 15 et 35
        $maxTime = $minTime + 10 + (($seed * 3) % 11); // Entre minTime+10 et minTime+20
        
        return $minTime . '-' . $maxTime;
    }

    /**
     * Vérifie si le commerce est ouvert selon les horaires
     * Retourne true si ouvert, false si fermé
     */
    public function isOpen(): bool
    {
        // Si pas d'horaires configurés, considérer comme toujours ouvert
        if (empty($this->opening_hours) || !is_array($this->opening_hours)) {
            return true;
        }

        $now = now();
        // Carbon retourne les jours en anglais en minuscules
        $currentDay = strtolower($now->format('l')); // 'monday', 'tuesday', etc.
        $currentTime = $now->format('H:i'); // '14:30'

        // Vérifier si le jour actuel est dans les horaires
        if (!isset($this->opening_hours[$currentDay])) {
            // Si le jour n'est pas configuré, considérer comme ouvert (comportement par défaut)
            return true;
        }

        $dayHours = $this->opening_hours[$currentDay];

        // Si fermé ce jour
        if (isset($dayHours['closed']) && $dayHours['closed'] === true) {
            return false;
        }

        // Si ouvert 24/7 ce jour
        if (isset($dayHours['open_24h']) && $dayHours['open_24h'] === true) {
            return true;
        }

        // Vérifier les heures d'ouverture/fermeture
        if (isset($dayHours['open']) && isset($dayHours['close'])) {
            $openTime = $dayHours['open'];
            $closeTime = $dayHours['close'];

            // Gérer le cas où la fermeture est après minuit (ex: 22:00 - 02:00)
            if ($closeTime < $openTime) {
                // Le commerce ferme le lendemain
                return $currentTime >= $openTime || $currentTime <= $closeTime;
            } else {
                // Horaires normaux
                return $currentTime >= $openTime && $currentTime <= $closeTime;
            }
        }

        // Si les horaires ne sont pas valides mais le jour est configuré, considérer comme ouvert
        return true;
    }

    /**
     * Accessor pour le statut (ouvert/fermé ou disponible/indisponible)
     */
    public function getStatusAttribute(): string
    {
        return $this->isOpen() ? 'open' : 'closed';
    }

    /**
     * Accessor pour le label du statut selon le type de commerce
     */
    public function getStatusLabelAttribute(): string
    {
        $isRealEstate = in_array($this->commerceType->name ?? '', ['Immobilier', 'Résidence Meublée']);
        
        if ($this->isOpen()) {
            return $isRealEstate ? 'Disponible' : 'Ouvert';
        } else {
            return $isRealEstate ? 'Indisponible' : 'Fermé';
        }
    }

    /**
     * Accessor pour la classe CSS du statut
     */
    public function getStatusClassAttribute(): string
    {
        return $this->isOpen() ? 'success' : 'danger';
    }

    /**
     * Accessor pour l'icône du statut
     */
    public function getStatusIconAttribute(): string
    {
        return $this->isOpen() ? '🟢' : '🔴';
    }
} 