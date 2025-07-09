<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommerceType extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
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
     * Obtenir seulement les types de commerce actifs
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Obtenir le nom avec l'emoji
     */
    public function getFullNameAttribute(): string
    {
        return $this->emoji . ' ' . $this->name;
    }

    /**
     * Relation avec les catégories
     */
    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    /**
     * Relation avec les commerces
     */
    public function commerces()
    {
        return $this->hasMany(Commerce::class);
    }

    /**
     * Obtenir les catégories actives
     */
    public function activeCategories()
    {
        return $this->hasMany(Category::class)->where('is_active', true);
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