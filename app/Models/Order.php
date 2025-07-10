<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'commerce_id',
        'order_number',
        'delivery_address_id',
        'payment_method',
        'status',
        'subtotal',
        'delivery_fee',
        'discount',
        'total',
        'notes',
        'estimated_delivery_time',
        'actual_delivery_time',
        'payment_status',
        'delivery_notes'
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'delivery_fee' => 'decimal:2',
        'discount' => 'decimal:2',
        'total' => 'decimal:2',
        'estimated_delivery_time' => 'datetime',
        'actual_delivery_time' => 'datetime'
    ];

    // Statuts de commande
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY = 'ready';
    const STATUS_OUT_FOR_DELIVERY = 'out_for_delivery';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    // Statuts de paiement
    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    // Méthodes de paiement
    const PAYMENT_METHOD_CASH = 'cash_on_delivery';
    const PAYMENT_METHOD_CARD = 'card';
    const PAYMENT_METHOD_MOBILE = 'mobile_money';

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relation avec le commerce
    public function commerce()
    {
        return $this->belongsTo(Commerce::class);
    }

    // Relation avec l'adresse de livraison
    public function deliveryAddress()
    {
        return $this->belongsTo(Address::class, 'delivery_address_id');
    }

    // Relation avec les articles de commande
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Générer un numéro de commande unique
    public static function generateOrderNumber()
    {
        $prefix = 'LEB' . date('ymd');
        $lastOrder = static::where('order_number', 'like', $prefix . '%')
            ->orderBy('order_number', 'desc')
            ->first();

        if ($lastOrder) {
            $lastNumber = intval(substr($lastOrder->order_number, -4));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    // Accesseurs pour les statuts
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_CONFIRMED => 'Confirmée',
            self::STATUS_PREPARING => 'En préparation',
            self::STATUS_READY => 'Prête',
            self::STATUS_OUT_FOR_DELIVERY => 'En livraison',
            self::STATUS_DELIVERED => 'Livrée',
            self::STATUS_CANCELLED => 'Annulée'
        ];

        return $labels[$this->status] ?? 'Statut inconnu';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            self::PAYMENT_STATUS_PENDING => 'En attente',
            self::PAYMENT_STATUS_PAID => 'Payé',
            self::PAYMENT_STATUS_FAILED => 'Échoué',
            self::PAYMENT_STATUS_REFUNDED => 'Remboursé'
        ];

        return $labels[$this->payment_status] ?? 'Statut inconnu';
    }

    public function getPaymentMethodLabelAttribute()
    {
        $labels = [
            self::PAYMENT_METHOD_CASH => 'Paiement à la livraison',
            self::PAYMENT_METHOD_CARD => 'Carte bancaire',
            self::PAYMENT_METHOD_MOBILE => 'Mobile Money'
        ];

        return $labels[$this->payment_method] ?? 'Méthode inconnue';
    }

    // Formatage des prix
    public function getFormattedSubtotalAttribute()
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' F';
    }

    public function getFormattedDeliveryFeeAttribute()
    {
        return number_format($this->delivery_fee, 0, ',', ' ') . ' F';
    }

    public function getFormattedDiscountAttribute()
    {
        return number_format($this->discount, 0, ',', ' ') . ' F';
    }

    public function getFormattedTotalAttribute()
    {
        return number_format($this->total, 0, ',', ' ') . ' F';
    }

    // Scopes
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeRecentFirst($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Méthodes utilitaires
    public function canBeCancelled()
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_CONFIRMED
        ]);
    }

    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCancelled()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function getEstimatedDeliveryTimeAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return \Carbon\Carbon::parse($value);
    }

    public function getActualDeliveryTimeAttribute($value)
    {
        if (!$value) {
            return null;
        }

        return \Carbon\Carbon::parse($value);
    }

    // Calculer le total des articles
    public function calculateTotals()
    {
        $subtotal = $this->items()->sum(\DB::raw('quantity * price'));
        $deliveryFee = $this->delivery_fee ?? 0;
        $discount = $this->discount ?? 0;
        $total = $subtotal + $deliveryFee - $discount;

        $this->update([
            'subtotal' => $subtotal,
            'total' => $total
        ]);
    }
} 