<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ErrandRequest extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'pickup_address',
        'delivery_address',
        'estimated_cost',
        'urgency_level',
        'status',
        'photo_path',
        'notes',
        'contact_phone',
        'preferred_delivery_time'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2',
        'preferred_delivery_time' => 'datetime'
    ];

    // Statuts de demande
    const STATUS_PENDING = 'pending';
    const STATUS_ACCEPTED = 'accepted';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Niveaux d'urgence
    const URGENCY_LOW = 'low';
    const URGENCY_MEDIUM = 'medium';
    const URGENCY_HIGH = 'high';
    const URGENCY_URGENT = 'urgent';

    // Relation avec l'utilisateur
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Obtenir le libellé du statut
    public function getStatusLabelAttribute()
    {
        $labels = [
            self::STATUS_PENDING => 'En attente',
            self::STATUS_ACCEPTED => 'Acceptée',
            self::STATUS_IN_PROGRESS => 'En cours',
            self::STATUS_COMPLETED => 'Terminée',
            self::STATUS_CANCELLED => 'Annulée'
        ];

        return $labels[$this->status] ?? 'Inconnu';
    }

    // Obtenir le libellé du niveau d'urgence
    public function getUrgencyLabelAttribute()
    {
        $labels = [
            self::URGENCY_LOW => 'Faible',
            self::URGENCY_MEDIUM => 'Moyenne',
            self::URGENCY_HIGH => 'Élevée',
            self::URGENCY_URGENT => 'Urgente'
        ];

        return $labels[$this->urgency_level] ?? 'Inconnu';
    }

    // Obtenir la classe CSS pour le statut
    public function getStatusClassAttribute()
    {
        $classes = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_ACCEPTED => 'info',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_CANCELLED => 'danger'
        ];

        return $classes[$this->status] ?? 'secondary';
    }

    // Obtenir la classe CSS pour l'urgence
    public function getUrgencyClassAttribute()
    {
        $classes = [
            self::URGENCY_LOW => 'success',
            self::URGENCY_MEDIUM => 'info',
            self::URGENCY_HIGH => 'warning',
            self::URGENCY_URGENT => 'danger'
        ];

        return $classes[$this->urgency_level] ?? 'secondary';
    }

    // Obtenir le coût estimé formaté
    public function getFormattedEstimatedCostAttribute()
    {
        return number_format($this->estimated_cost, 0, ',', ' ') . ' F';
    }

    // Obtenir l'URL de la photo
    public function getPhotoUrlAttribute()
    {
        if ($this->photo_path) {
            return asset('storage/' . $this->photo_path);
        }
        return asset('images/errand-placeholder.png');
    }

    // Scope pour les demandes en attente
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    // Scope pour les demandes actives
    public function scopeActive($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_ACCEPTED, self::STATUS_IN_PROGRESS]);
    }

    // Scope pour les demandes d'un utilisateur
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Configuration des logs d'activité
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['status', 'urgency_level', 'estimated_cost'])
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName('errand_request');
    }
}
