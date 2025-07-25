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
     * Calculer les frais de livraison (configurable par commerce différent)
     */
    public function getDeliveryFeeAttribute()
    {
        $settings = \App\Models\DeliverySettings::getActiveSettings();
        $uniqueCommerces = $this->items->pluck('product.commerce.id')->unique();
        return $uniqueCommerces->count() * $settings->delivery_fee_per_commerce;
    }

    /**
     * Obtenir les frais de livraison formatés
     */
    public function getFormattedDeliveryFeeAttribute()
    {
        return number_format($this->delivery_fee, 0, ',', ' ') . ' F';
    }

    /**
     * Calculer la remise (configurable pour la première commande)
     */
    public function getDiscountAttribute()
    {
        if (!$this->user) {
            return 0;
        }
        
        $settings = \App\Models\DeliverySettings::getActiveSettings();
        return !$this->user->orders()->exists() ? $settings->first_order_discount : 0;
    }

    /**
     * Obtenir la remise formatée
     */
    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount, 0, ',', ' ') . ' F';
    }

    /**
     * Calculer le total final
     */
    public function getFinalTotalAttribute()
    {
        return $this->total_price + $this->delivery_fee - $this->discount;
    }

    /**
     * Obtenir le total final formaté
     */
    public function getFormattedFinalTotalAttribute()
    {
        return number_format($this->final_total, 0, ',', ' ') . ' F';
    }

    /**
     * Obtenir le nombre de commerces uniques
     */
    public function getUniqueCommercesCountAttribute()
    {
        return $this->items->pluck('product.commerce.id')->unique()->count();
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
        )->load(['items.product.commerce', 'user']);
    }

    /**
     * Obtenir le panier d'un utilisateur (sans le créer)
     */
    public static function getForUser($userId)
    {
        return static::where('user_id', $userId)->with(['items.product.commerce', 'user'])->first();
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

    /**
     * Migrer le panier de session vers l'utilisateur connecté
     */
    public static function migrateSessionCartToUser($userId, $sessionId)
    {
        \Log::info('Cart migration started', [
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);

        // Récupérer le panier de session
        $sessionCart = static::where('session_id', $sessionId)->first();
        
        \Log::info('Session cart found', [
            'session_cart_exists' => $sessionCart !== null,
            'session_cart_id' => $sessionCart ? $sessionCart->id : null,
            'session_cart_items' => $sessionCart ? $sessionCart->total_items : 0
        ]);
        
        if (!$sessionCart || $sessionCart->isEmpty()) {
            \Log::info('No session cart or empty cart, migration skipped');
            return null; // Pas de panier de session ou panier vide
        }

        // Récupérer ou créer le panier utilisateur
        $userCart = static::getOrCreateForUser($userId);
        
        \Log::info('User cart prepared', [
            'user_cart_id' => $userCart->id,
            'user_cart_items_before' => $userCart->total_items
        ]);

        // Migrer tous les articles du panier de session vers le panier utilisateur
        $sessionItems = $sessionCart->items()->with('product')->get();
        
        \Log::info('Session items to migrate', [
            'items_count' => $sessionItems->count(),
            'items' => $sessionItems->map(function($item) {
                return [
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->price
                ];
            })->toArray()
        ]);
        
        foreach ($sessionItems as $sessionItem) {
            // Vérifier si le produit existe déjà dans le panier utilisateur
            $existingItem = $userCart->items()->where('product_id', $sessionItem->product_id)->first();
            
            if ($existingItem) {
                // Si le produit existe déjà, additionner les quantités
                $oldQuantity = $existingItem->quantity;
                $existingItem->increment('quantity', $sessionItem->quantity);
                \Log::info('Product quantity updated', [
                    'product_id' => $sessionItem->product_id,
                    'old_quantity' => $oldQuantity,
                    'added_quantity' => $sessionItem->quantity,
                    'new_quantity' => $existingItem->fresh()->quantity
                ]);
            } else {
                // Sinon, créer un nouvel article dans le panier utilisateur
                $newItem = $userCart->items()->create([
                    'product_id' => $sessionItem->product_id,
                    'quantity' => $sessionItem->quantity,
                    'price' => $sessionItem->price
                ]);
                \Log::info('New product added to cart', [
                    'product_id' => $sessionItem->product_id,
                    'quantity' => $sessionItem->quantity,
                    'cart_item_id' => $newItem->id
                ]);
            }
        }

        // Recharger le panier utilisateur pour obtenir les totaux mis à jour
        $userCart->refresh();
        
        \Log::info('User cart after migration', [
            'user_cart_items_after' => $userCart->total_items,
            'user_cart_total_price' => $userCart->total_price
        ]);

        // Supprimer le panier de session après migration
        $sessionCart->delete();
        \Log::info('Session cart deleted', [
            'session_cart_id' => $sessionCart->id
        ]);

        return $userCart;
    }

    /**
     * Obtenir le nombre d'articles dans le panier actuel (utilisateur ou session)
     */
    public static function getCurrentCartCount()
    {
        if (auth()->check()) {
            $cart = static::where('user_id', auth()->id())->first();
            $count = $cart ? $cart->total_items : 0;
            \Log::info('Cart count for authenticated user', [
                'user_id' => auth()->id(),
                'cart_id' => $cart ? $cart->id : null,
                'count' => $count
            ]);
            return $count;
        }

        $sessionId = session()->getId();
        $cart = static::where('session_id', $sessionId)->first();
        $count = $cart ? $cart->total_items : 0;
        \Log::info('Cart count for session user', [
            'session_id' => $sessionId,
            'cart_id' => $cart ? $cart->id : null,
            'count' => $count
        ]);
        return $count;
    }
}
