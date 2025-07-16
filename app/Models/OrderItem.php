<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
        'product_name',
        'product_image',
        'product_description',
        'notes'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    // Relation avec la commande
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    // Relation avec le produit
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Calculer le sous-total de l'article
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    // Sous-total formaté
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' F';
    }

    // Prix formaté
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' F';
    }

    // Scope pour une commande spécifique
    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    // Sauvegarder les informations du produit au moment de la commande
    public function saveProductSnapshot()
    {
        if ($this->product) {
            $this->update([
                'product_name' => $this->product->name,
                'product_image' => $this->product->image_url,
                'product_description' => $this->product->description,
                'price' => $this->product->price
            ]);
        }
    }

    // Obtenir le nom du produit (snapshot ou actuel)
    public function getDisplayNameAttribute()
    {
        return $this->product_name ?? ($this->product ? $this->product->name : 'Produit supprimé');
    }

    // Obtenir l'image du produit (snapshot ou actuelle)
    public function getDisplayImageAttribute()
    {
        if ($this->product_image) {
            return $this->product_image;
        }
        
        if ($this->product) {
            return $this->product->image_url;
        }
        
        return asset('images/product-placeholder.png');
    }

    // Obtenir la description du produit (snapshot ou actuelle)
    public function getDisplayDescriptionAttribute()
    {
        return $this->product_description ?? ($this->product ? $this->product->description : '');
    }

    // Obtenir le nom du commerce
    public function getCommerceNameAttribute()
    {
        return $this->product && $this->product->commerce ? $this->product->commerce->name : 'Commerce supprimé';
    }

    // Obtenir le logo du commerce
    public function getCommerceLogoAttribute()
    {
        return $this->product && $this->product->commerce ? $this->product->commerce->logo_url : asset('images/default-avatar.png');
    }

    // Obtenir le type de commerce
    public function getCommerceTypeNameAttribute()
    {
        return $this->product && $this->product->commerce && $this->product->commerce->commerceType 
            ? $this->product->commerce->commerceType->full_name 
            : 'N/A';
    }

    // Obtenir l'adresse du commerce
    public function getCommerceFullAddressAttribute()
    {
        return $this->product && $this->product->commerce ? $this->product->commerce->full_address : 'Adresse non disponible';
    }
} 