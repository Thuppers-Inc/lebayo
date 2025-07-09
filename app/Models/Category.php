<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'commerce_type_id',
        'name',
        'emoji',
        'description',
        'is_active',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Relation avec le type de commerce
     */
    public function commerceType()
    {
        return $this->belongsTo(CommerceType::class);
    }

    /**
     * Relation avec les produits
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Obtenir seulement les catégories actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtenir les catégories par type de commerce
     */
    public function scopeByCommerceType($query, $commerceTypeId)
    {
        return $query->where('commerce_type_id', $commerceTypeId);
    }

    /**
     * Obtenir le nom avec l'emoji
     */
    public function getFullNameAttribute(): string
    {
        return $this->emoji . ' ' . $this->name;
    }

    /**
     * Obtenir le nom du type de commerce
     */
    public function getCommerceTypeNameAttribute(): string
    {
        return $this->commerceType ? $this->commerceType->full_name : 'Non défini';
    }

    /**
     * Obtenir le statut formaté
     */
    public function getStatusBadgeAttribute(): string
    {
        return $this->is_active 
            ? '<span class="badge bg-success">Actif</span>'
            : '<span class="badge bg-secondary">Inactif</span>';
    }
}
