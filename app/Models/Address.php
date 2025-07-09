<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'name',
        'street',
        'city',
        'postal_code',
        'country',
        'phone',
        'is_default',
        'latitude',
        'longitude',
        'additional_info'
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec les commandes
    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_address_id');
    }

    // Adresse complète formatée
    public function getFullAddressAttribute()
    {
        return "{$this->street}, {$this->city}, {$this->postal_code}, {$this->country}";
    }

    // Scope pour les adresses par défaut
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Scope pour les adresses d'un utilisateur
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Marquer comme adresse par défaut
    public function markAsDefault()
    {
        // Retirer le statut par défaut des autres adresses
        $this->user->addresses()->where('id', '!=', $this->id)->update(['is_default' => false]);
        
        // Marquer cette adresse comme par défaut
        $this->update(['is_default' => true]);
    }

    // Calculer la distance depuis une position
    public function calculateDistance($latitude, $longitude)
    {
        if (!$this->latitude || !$this->longitude) {
            return null;
        }

        $earthRadius = 6371; // Rayon de la Terre en km

        $latDiff = deg2rad($this->latitude - $latitude);
        $lonDiff = deg2rad($this->longitude - $longitude);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
             cos(deg2rad($latitude)) * cos(deg2rad($this->latitude)) *
             sin($lonDiff / 2) * sin($lonDiff / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
} 