<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Commerce extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
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
     * Relation avec les commandes (one-to-many)
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
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
} 