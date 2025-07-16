@extends('layouts.app')

@section('title', 'Commande ' . $order->order_number)

@section('content')
<div class="profile-section">
    <div class="container">
        <!-- Breadcrumb -->
        <div class="breadcrumb">
            <a href="{{ route('profile.orders') }}" class="breadcrumb-link">
                <i class="breadcrumb-icon">←</i>
                Retour aux commandes
            </a>
        </div>

        <!-- Header de la commande -->
        <div class="order-detail-header">
            <div class="order-info">
                <h1>Commande {{ $order->order_number }}</h1>
                <p class="order-meta">
                    Passée le {{ $order->created_at->format('d/m/Y à H:i') }}
                    @if($order->estimated_delivery_time)
                        • Livraison estimée le {{ $order->estimated_delivery_time->format('d/m/Y à H:i') }}
                    @endif
                </p>
            </div>
            <div class="order-status-section">
                <span class="status-badge status-{{ $order->status }}">
                    {{ $order->status_label }}
                </span>
                <span class="payment-badge payment-{{ $order->payment_status }}">
                    {{ $order->payment_status_label }}
                </span>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="order-detail-content">
            <!-- Informations de livraison -->
            <div class="detail-card">
                <div class="card-header">
                    <h2>Informations de livraison</h2>
                </div>
                <div class="card-body">
                    @if($order->deliveryAddress)
                        <div class="delivery-info">
                            <div class="address-section">
                                <h3>{{ $order->deliveryAddress->name }}</h3>
                                <div class="address-details">
                                    <div class="detail-line">
                                        <i class="detail-icon">📍</i>
                                        {{ $order->deliveryAddress->street }}
                                    </div>
                                    <div class="detail-line">
                                        <i class="detail-icon">🏙️</i>
                                        {{ $order->deliveryAddress->city }}, {{ $order->deliveryAddress->country }}
                                    </div>
                                    <div class="detail-line">
                                        <i class="detail-icon">📞</i>
                                        {{ $order->deliveryAddress->phone }}
                                    </div>
                                    @if($order->deliveryAddress->additional_info)
                                        <div class="detail-line">
                                            <i class="detail-icon">ℹ️</i>
                                            {{ $order->deliveryAddress->additional_info }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="payment-section">
                                <h3>Mode de paiement</h3>
                                <div class="payment-method">
                                    <i class="payment-icon">💳</i>
                                    {{ $order->payment_method_label }}
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="no-address">
                            <i class="no-address-icon">❌</i>
                            Adresse de livraison non disponible
                        </div>
                    @endif
                </div>
            </div>

            <!-- Articles commandés -->
            <div class="detail-card">
                <div class="card-header">
                    <h2>Articles commandés</h2>
                    <span class="items-count">{{ $order->items->count() }} article{{ $order->items->count() > 1 ? 's' : '' }}</span>
                </div>
                <div class="card-body">
                    <div class="order-items">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ $item->display_image }}" alt="{{ $item->display_name }}">
                                </div>
                                <div class="item-details">
                                    <div class="item-info">
                                        <h4 class="item-name">{{ $item->display_name }}</h4>
                                        <p class="item-description">{{ $item->display_description }}</p>
                                        <div class="item-commerce">
                                            <i class="commerce-icon">🏪</i>
                                            {{ $item->commerce_name }}
                                        </div>
                                    </div>
                                    <div class="item-pricing">
                                        <div class="item-quantity">Quantité: {{ $item->quantity }}</div>
                                        <div class="item-price">{{ $item->formatted_price }} × {{ $item->quantity }}</div>
                                        <div class="item-total">{{ $item->formatted_subtotal }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Récapitulatif financier -->
            <div class="detail-card">
                <div class="card-header">
                    <h2>Récapitulatif</h2>
                </div>
                <div class="card-body">
                    <div class="order-summary">
                        <div class="summary-line">
                            <span class="summary-label">Sous-total</span>
                            <span class="summary-value">{{ $order->formatted_subtotal }}</span>
                        </div>
                        <div class="summary-line">
                            <span class="summary-label">Frais de livraison</span>
                            <span class="summary-value">{{ $order->formatted_delivery_fee }}</span>
                        </div>
                        @if($order->discount > 0)
                            <div class="summary-line discount">
                                <span class="summary-label">Remise</span>
                                <span class="summary-value">-{{ $order->formatted_discount }}</span>
                            </div>
                        @endif
                        <div class="summary-line total">
                            <span class="summary-label">Total</span>
                            <span class="summary-value">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="detail-card">
                    <div class="card-header">
                        <h2>Notes</h2>
                    </div>
                    <div class="card-body">
                        <div class="order-notes">
                            <i class="notes-icon">📝</i>
                            {{ $order->notes }}
                        </div>
                    </div>
                </div>
            @endif

            <!-- Actions -->
            <div class="order-actions">
                @if($order->canBeCancelled())
                    <button class="btn btn-danger" onclick="cancelOrder()">
                        <i class="btn-icon">❌</i>
                        Annuler la commande
                    </button>
                @endif
                
                <a href="{{ route('profile.orders') }}" class="btn btn-outline">
                    <i class="btn-icon">📦</i>
                    Voir toutes mes commandes
                </a>
                
                @if($order->isDelivered())
                    <button class="btn btn-primary" onclick="reorderItems()">
                        <i class="btn-icon">🔄</i>
                        Commander à nouveau
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-section {
    padding: 60px 0;
    background: var(--light-bg);
    min-height: 80vh;
}

.breadcrumb {
    margin-bottom: 30px;
}

.breadcrumb-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    text-decoration: none;
    color: var(--text-light);
    font-weight: 500;
    transition: color 0.3s ease;
}

.breadcrumb-link:hover {
    color: var(--primary-color);
}

.breadcrumb-icon {
    font-size: 1.2rem;
}

.order-detail-header {
    background: white;
    border-radius: 16px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.order-info h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.order-meta {
    color: var(--text-light);
    font-size: 1rem;
    margin: 0;
}

.order-status-section {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: flex-end;
}

.status-badge,
.payment-badge {
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d1ecf1;
    color: #0c5460;
}

.status-preparing {
    background: #d4edda;
    color: #155724;
}

.status-ready {
    background: #d1ecf1;
    color: #0c5460;
}

.status-out_for_delivery {
    background: #cce5ff;
    color: #004085;
}

.status-delivered {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.payment-pending {
    background: #fff3cd;
    color: #856404;
}

.payment-paid {
    background: #d4edda;
    color: #155724;
}

.payment-failed {
    background: #f8d7da;
    color: #721c24;
}

.payment-refunded {
    background: #cce5ff;
    color: #004085;
}

.order-detail-content {
    display: grid;
    gap: 30px;
}

.detail-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    padding: 24px 24px 16px;
    border-bottom: 1px solid #f0f0f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.card-header h2 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.items-count {
    background: var(--light-bg);
    color: var(--text-light);
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 0.875rem;
    font-weight: 500;
}

.card-body {
    padding: 24px;
}

.delivery-info {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 40px;
}

.address-section h3,
.payment-section h3 {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 16px;
}

.address-details {
    display: grid;
    gap: 8px;
}

.detail-line {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-light);
    font-size: 0.9rem;
}

.detail-icon {
    font-size: 1rem;
    width: 20px;
    flex-shrink: 0;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-dark);
    font-weight: 500;
}

.payment-icon {
    font-size: 1.25rem;
}

.no-address {
    text-align: center;
    padding: 40px;
    color: var(--text-light);
}

.no-address-icon {
    font-size: 2rem;
    margin-bottom: 12px;
    display: block;
}

.order-items {
    display: grid;
    gap: 20px;
}

.order-item {
    display: flex;
    gap: 16px;
    padding: 20px;
    background: #f8f9fa;
    border-radius: 12px;
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.item-details {
    flex: 1;
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.item-info {
    flex: 1;
}

.item-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 4px;
}

.item-description {
    color: var(--text-light);
    font-size: 0.875rem;
    margin-bottom: 8px;
    line-height: 1.4;
}

.item-commerce {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--primary-color);
    font-size: 0.875rem;
    font-weight: 500;
}

.commerce-icon {
    font-size: 1rem;
}

.item-pricing {
    text-align: right;
}

.item-quantity {
    color: var(--text-light);
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.item-price {
    color: var(--text-light);
    font-size: 0.875rem;
    margin-bottom: 4px;
}

.item-total {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
}

.order-summary {
    display: grid;
    gap: 12px;
    max-width: 300px;
    margin-left: auto;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 8px 0;
}

.summary-line.discount .summary-value {
    color: var(--success-color);
}

.summary-line.total {
    border-top: 2px solid #f0f0f0;
    padding-top: 16px;
    margin-top: 8px;
    font-size: 1.125rem;
    font-weight: 700;
}

.summary-label {
    color: var(--text-light);
}

.summary-value {
    color: var(--text-dark);
    font-weight: 600;
}

.order-notes {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    background: #f8f9fa;
    padding: 20px;
    border-radius: 12px;
}

.notes-icon {
    font-size: 1.25rem;
    color: var(--primary-color);
    flex-shrink: 0;
}

.order-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    margin-top: 40px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.btn-outline {
    background: white;
    color: var(--text-dark);
    border: 1px solid #e2e8f0;
}

.btn-outline:hover {
    background: var(--text-dark);
    color: white;
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
}

.btn-icon {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-section {
        padding: 40px 0;
    }

    .order-detail-header {
        flex-direction: column;
        gap: 20px;
        text-align: center;
        padding: 30px 20px;
    }

    .order-status-section {
        align-items: center;
    }

    .delivery-info {
        grid-template-columns: 1fr;
        gap: 30px;
    }

    .card-header {
        padding: 20px 20px 15px;
    }

    .card-body {
        padding: 20px;
    }

    .order-item {
        flex-direction: column;
        gap: 12px;
    }

    .item-details {
        flex-direction: column;
        gap: 12px;
    }

    .item-pricing {
        text-align: left;
    }

    .order-summary {
        max-width: 100%;
        margin-left: 0;
    }

    .order-actions {
        flex-direction: column;
        gap: 12px;
    }

    .btn {
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder() {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        // Ici vous pouvez ajouter la logique d'annulation
        // par exemple, faire un appel AJAX vers une route d'annulation
        alert('Fonctionnalité d\'annulation à implémenter');
    }
}

function reorderItems() {
    if (confirm('Voulez-vous ajouter tous ces articles à votre panier ?')) {
        // Ici vous pouvez ajouter la logique de recommande
        // par exemple, faire un appel AJAX pour ajouter les articles au panier
        alert('Fonctionnalité de recommande à implémenter');
    }
}
</script>
@endpush 