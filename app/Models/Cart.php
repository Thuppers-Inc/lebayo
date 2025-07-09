<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id'
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec les articles du panier
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Obtenir le nombre total d'articles dans le panier
     */
    public function getTotalItemsAttribute()
    {
        return $this->items->sum('quantity');
    }

    /**
     * Obtenir le prix total du panier
     */
    public function getTotalPriceAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->price;
        });
    }

    /**
     * Obtenir le prix total formaté
     */
    public function getFormattedTotalAttribute()
    {
        return number_format($this->total_price, 0, ',', ' ') . ' F';
    }

    /**
     * Vérifier si le panier est vide
     */
    public function isEmpty()
    {
        return $this->items->isEmpty();
    }

    /**
     * Obtenir ou créer un panier pour un utilisateur connecté
     */
    public static function getOrCreateForUser($userId)
    {
        return static::firstOrCreate(
            ['user_id' => $userId],
            ['user_id' => $userId]
        );
    }

    /**
     * Obtenir ou créer un panier pour une session
     */
    public static function getOrCreateForSession($sessionId)
    {
        return static::firstOrCreate(
            ['session_id' => $sessionId],
            ['session_id' => $sessionId]
        );
    }

    /**
     * Ajouter un produit au panier
     */
    public function addProduct($product, $quantity = 1)
    {
        $cartItem = $this->items()->where('product_id', $product->id)->first();

        if ($cartItem) {
            // Si le produit existe déjà, augmenter la quantité
            $cartItem->increment('quantity', $quantity);
        } else {
            // Sinon, créer un nouvel article
            $this->items()->create([
                'product_id' => $product->id,
                'quantity' => $quantity,
                'price' => $product->price // Prix au moment de l'ajout
            ]);
        }

        return $this;
    }

    /**
     * Mettre à jour la quantité d'un produit
     */
    public function updateProductQuantity($productId, $quantity)
    {
        $cartItem = $this->items()->where('product_id', $productId)->first();

        if ($cartItem) {
            if ($quantity <= 0) {
                $cartItem->delete();
            } else {
                $cartItem->update(['quantity' => $quantity]);
            }
        }

        return $this;
    }

    /**
     * Supprimer un produit du panier
     */
    public function removeProduct($productId)
    {
        $this->items()->where('product_id', $productId)->delete();
        return $this;
    }

    /**
     * Vider le panier
     */
    public function clear()
    {
        $this->items()->delete();
        return $this;
    }
}
