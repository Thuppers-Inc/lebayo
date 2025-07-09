<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer'
    ];

    /**
     * Relation avec le panier
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Relation avec le produit
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Obtenir le sous-total de cet article (quantité × prix)
     */
    public function getSubtotalAttribute()
    {
        return $this->quantity * $this->price;
    }

    /**
     * Obtenir le sous-total formaté
     */
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' F';
    }

    /**
     * Obtenir le prix formaté
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0, ',', ' ') . ' F';
    }
}
