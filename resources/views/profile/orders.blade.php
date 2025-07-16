@extends('layouts.app')

@section('title', 'Mes Commandes')

@section('content')
<div class="profile-section">
    <div class="container">
        <!-- Header du profil -->
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    {{ substr(auth()->user()->prenoms ?? 'U', 0, 1) }}
                </div>
            </div>
            <div class="profile-info">
                <h1>{{ auth()->user()->full_name }}</h1>
                <p class="profile-subtitle">Gestion de vos commandes</p>
            </div>
        </div>

        <!-- Navigation du profil -->
        <div class="profile-nav">
            <a href="{{ route('profile.index') }}" class="nav-item">
                <i class="nav-icon">👤</i>
                Informations personnelles
            </a>
            <a href="{{ route('profile.orders') }}" class="nav-item active">
                <i class="nav-icon">📦</i>
                Mes commandes
            </a>
            <a href="{{ route('profile.addresses') }}" class="nav-item">
                <i class="nav-icon">📍</i>
                Mes adresses
            </a>
        </div>

        <!-- Contenu principal -->
        <div class="profile-content">
            @if($orders->count() > 0)
                <!-- Liste des commandes -->
                <div class="orders-grid">
                    @foreach($orders as $order)
                        <div class="order-card">
                            <div class="order-header">
                                <div class="order-info">
                                    <h3 class="order-number">{{ $order->order_number }}</h3>
                                    <p class="order-date">{{ $order->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div class="order-status">
                                    <span class="status-badge status-{{ $order->status }}">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                            </div>

                            <div class="order-summary">
                                <div class="order-items">
                                    @php
                                        $displayedItems = $order->items->take(3);
                                        $remainingCount = $order->items->count() - 3;
                                    @endphp
                                    
                                    @foreach($displayedItems as $item)
                                        <div class="order-item">
                                            <div class="item-image">
                                                <img src="{{ $item->display_image }}" alt="{{ $item->display_name }}">
                                            </div>
                                            <div class="item-details">
                                                <span class="item-name">{{ $item->display_name }}</span>
                                                <span class="item-quantity">x{{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($remainingCount > 0)
                                        <div class="more-items">
                                            +{{ $remainingCount }} autre{{ $remainingCount > 1 ? 's' : '' }} article{{ $remainingCount > 1 ? 's' : '' }}
                                        </div>
                                    @endif
                                </div>

                                <div class="order-details">
                                    <div class="delivery-address">
                                        <i class="address-icon">📍</i>
                                        {{ $order->deliveryAddress ? $order->deliveryAddress->street . ', ' . $order->deliveryAddress->city : 'Adresse non disponible' }}
                                    </div>
                                    
                                    <div class="order-meta">
                                        <div class="payment-method">
                                            <i class="payment-icon">💳</i>
                                            {{ $order->payment_method_label }}
                                        </div>
                                        <div class="order-total">
                                            <strong>{{ $order->formatted_total }}</strong>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="order-actions">
                                <a href="{{ route('profile.orders.show', $order) }}" class="btn btn-outline">
                                    <i class="btn-icon">👁️</i>
                                    Voir détails
                                </a>
                                
                                @if($order->canBeCancelled())
                                    <button class="btn btn-danger" onclick="cancelOrder({{ $order->id }})">
                                        <i class="btn-icon">❌</i>
                                        Annuler
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="pagination-wrapper">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-icon">📦</div>
                    <h3>Aucune commande</h3>
                    <p>Vous n'avez pas encore passé de commande.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="btn-icon">🛒</i>
                        Commencer à commander
                    </a>
                </div>
            @endif
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

.profile-header {
    background: white;
    border-radius: 16px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 30px;
}

.profile-avatar .avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
}

.profile-info h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.profile-subtitle {
    color: var(--text-light);
    font-size: 1rem;
    margin-bottom: 0;
}

.profile-nav {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: white;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-light);
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.nav-item:hover,
.nav-item.active {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.nav-icon {
    font-size: 1rem;
}

.orders-grid {
    display: grid;
    gap: 20px;
}

.order-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.order-card:hover {
    transform: translateY(-2px);
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 24px 16px;
    border-bottom: 1px solid #f0f0f0;
}

.order-number {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0 0 4px 0;
}

.order-date {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

.status-badge {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.75rem;
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

.order-summary {
    padding: 24px;
}

.order-items {
    margin-bottom: 20px;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.item-image {
    width: 50px;
    height: 50px;
    border-radius: 8px;
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
    align-items: center;
}

.item-name {
    font-weight: 500;
    color: var(--text-dark);
    font-size: 0.9rem;
}

.item-quantity {
    color: var(--text-light);
    font-size: 0.875rem;
}

.more-items {
    color: var(--text-light);
    font-size: 0.875rem;
    font-style: italic;
    text-align: center;
    padding: 8px;
    background: #f8f9fa;
    border-radius: 8px;
}

.order-details {
    display: grid;
    gap: 12px;
}

.delivery-address {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-light);
    font-size: 0.875rem;
}

.address-icon {
    font-size: 1rem;
}

.order-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.payment-method {
    display: flex;
    align-items: center;
    gap: 6px;
    color: var(--text-light);
    font-size: 0.875rem;
}

.payment-icon {
    font-size: 1rem;
}

.order-total {
    font-size: 1.125rem;
    color: var(--primary-color);
}

.order-actions {
    display: flex;
    gap: 12px;
    padding: 20px 24px;
    background: #f8f9fa;
    border-top: 1px solid #f0f0f0;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
}

.btn-outline {
    background: white;
    color: var(--text-dark);
    border: 1px solid #e2e8f0;
}

.btn-outline:hover {
    background: var(--primary-color);
    color: white;
    border-color: var(--primary-color);
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background: #c53030;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-color);
}

.btn-icon {
    font-size: 0.875rem;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 12px;
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 30px;
    font-size: 1rem;
}

.pagination-wrapper {
    margin-top: 40px;
    display: flex;
    justify-content: center;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-section {
        padding: 40px 0;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 30px 20px;
    }

    .profile-nav {
        flex-direction: column;
        gap: 10px;
    }

    .nav-item {
        justify-content: center;
    }

    .order-header {
        flex-direction: column;
        gap: 12px;
        text-align: center;
    }

    .order-meta {
        flex-direction: column;
        gap: 8px;
        align-items: flex-start;
    }

    .order-actions {
        flex-direction: column;
        gap: 8px;
    }

    .btn {
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder(orderId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette commande ?')) {
        // Ici vous pouvez ajouter la logique d'annulation
        // par exemple, faire un appel AJAX vers une route d'annulation
        alert('Fonctionnalité d\'annulation à implémenter');
    }
}
</script>
@endpush 