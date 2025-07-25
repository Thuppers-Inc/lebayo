<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliverySettings extends Model
{
    use HasFactory;

    protected $fillable = [
        'delivery_fee_per_commerce',
        'first_order_discount',
        'free_delivery_threshold',
        'is_active'
    ];

    protected $casts = [
        'delivery_fee_per_commerce' => 'decimal:2',
        'first_order_discount' => 'decimal:2',
        'free_delivery_threshold' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    /**
     * Obtenir les paramètres actifs
     */
    public static function getActiveSettings()
    {
        return static::where('is_active', true)->first() ?? static::getDefaultSettings();
    }

    /**
     * Obtenir les paramètres par défaut
     */
    public static function getDefaultSettings()
    {
        return new static([
            'delivery_fee_per_commerce' => 500,
            'first_order_discount' => 500,
            'free_delivery_threshold' => 0,
            'is_active' => true
        ]);
    }

    /**
     * Activer ces paramètres et désactiver les autres
     */
    public function activate()
    {
        // Désactiver tous les autres paramètres
        static::where('id', '!=', $this->id)->update(['is_active' => false]);
        
        // Activer ces paramètres
        $this->update(['is_active' => true]);
    }

    /**
     * Obtenir les frais de livraison formatés
     */
    public function getFormattedDeliveryFeeAttribute()
    {
        return number_format($this->delivery_fee_per_commerce, 0, ',', ' ') . ' F';
    }

    /**
     * Obtenir la remise formatée
     */
    public function getFormattedDiscountAttribute()
    {
        return number_format($this->first_order_discount, 0, ',', ' ') . ' F';
    }

    /**
     * Obtenir le seuil de livraison gratuite formaté
     */
    public function getFormattedThresholdAttribute()
    {
        return $this->free_delivery_threshold > 0 
            ? number_format($this->free_delivery_threshold, 0, ',', ' ') . ' F'
            : 'Non configuré';
    }
} 