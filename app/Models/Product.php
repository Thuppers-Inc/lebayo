<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'commerce_id',
        'category_id',
        'name',
        'description',
        'price',
        'old_price',
        'image',
        'gallery',
        'sku',
        'stock',
        'is_available',
        'is_featured',
        'specifications',
        'tags',
        'weight',
        'unit',
        'preparation_time'
    ];

    protected $casts = [
        'price' => 'integer',
        'old_price' => 'integer',
        'weight' => 'decimal:2',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
        'gallery' => 'array',
        'specifications' => 'array',
        'tags' => 'array',
    ];

    /**
     * Relation vers le commerce
     */
    public function commerce(): BelongsTo
    {
        return $this->belongsTo(Commerce::class);
    }

    /**
     * Relation vers la catégorie
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Accessor pour l'URL de l'image
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            return asset('products/' . $this->image);
        }
        
        return asset('images/product-placeholder.png');
    }

    /**
     * Accessor pour le statut de disponibilité
     */
    public function getStatusTextAttribute(): string
    {
        if (!$this->is_available) {
            return 'Indisponible';
        }
        
        if ($this->stock <= 0) {
            return 'En rupture';
        }
        
        if ($this->stock <= 5) {
            return 'Stock faible';
        }
        
        return 'Disponible';
    }

    /**
     * Accessor pour la classe CSS du statut
     */
    public function getStatusClassAttribute(): string
    {
        if (!$this->is_available) {
            return 'danger';
        }
        
        if ($this->stock <= 0) {
            return 'danger';
        }
        
        if ($this->stock <= 5) {
            return 'warning';
        }
        
        return 'success';
    }

    /**
     * Accessor pour le prix formaté en FCFA
     */
    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    /**
     * Accessor pour l'ancien prix formaté en FCFA
     */
    public function getFormattedOldPriceAttribute(): ?string
    {
        if ($this->old_price) {
            return number_format($this->old_price, 0, ',', ' ') . ' FCFA';
        }
        
        return null;
    }

    /**
     * Accessor pour le pourcentage de réduction
     */
    public function getDiscountPercentageAttribute(): ?int
    {
        if ($this->old_price && $this->old_price > $this->price) {
            return round((($this->old_price - $this->price) / $this->old_price) * 100);
        }
        
        return null;
    }

    /**
     * Scope pour les produits disponibles
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }

    /**
     * Scope pour les produits mis en avant
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope pour les produits d'un commerce
     */
    public function scopeByCommerce($query, $commerceId)
    {
        return $query->where('commerce_id', $commerceId);
    }

    /**
     * Scope pour les produits d'une catégorie
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
}
