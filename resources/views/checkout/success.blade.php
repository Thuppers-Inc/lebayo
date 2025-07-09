@extends('layouts.app')

@section('title', 'Commande confirmée - Lebayo')

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-content">
            <!-- Animation et message de succès -->
            <div class="success-animation">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-message">
                    <h1>Commande confirmée !</h1>
                    <p>Votre commande a été passée avec succès</p>
                </div>
            </div>

            <!-- Détails de la commande -->
            <div class="order-details">
                <div class="order-header">
                    <h2>Détails de votre commande</h2>
                    <div class="order-number">
                        <span>Numéro de commande</span>
                        <strong>#{{ $order->order_number }}</strong>
                    </div>
                </div>

                <div class="order-info-grid">
                    <!-- Informations générales -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-info-circle"></i>
                            <h3>Informations générales</h3>
                        </div>
                        <div class="info-content">
                            <div class="info-item">
                                <span class="info-label">Date de commande</span>
                                <span class="info-value">{{ $order->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Statut</span>
                                <span class="info-value status-{{ $order->status }}">
                                    {{ $order->status_label }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Mode de paiement</span>
                                <span class="info-value">{{ $order->payment_method_label }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Adresse de livraison -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>Adresse de livraison</h3>
                        </div>
                        <div class="info-content">
                            <div class="delivery-address">
                                <div class="address-name">{{ $order->deliveryAddress->name }}</div>
                                <div class="address-details">{{ $order->deliveryAddress->full_address }}</div>
                                <div class="address-phone">{{ $order->deliveryAddress->phone }}</div>
                                @if($order->deliveryAddress->additional_info)
                                    <div class="address-additional">{{ $order->deliveryAddress->additional_info }}</div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Temps de livraison -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-clock"></i>
                            <h3>Temps de livraison</h3>
                        </div>
                        <div class="info-content">
                            <div class="delivery-time">
                                <div class="time-estimate">
                                    <span class="time-range">25-35 minutes</span>
                                    <span class="time-description">estimé</span>
                                </div>
                                <div class="delivery-note">
                                    <i class="fas fa-truck"></i>
                                    <span>Votre commande sera livrée dès que possible</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Total de la commande -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-receipt"></i>
                            <h3>Total de la commande</h3>
                        </div>
                        <div class="info-content">
                            <div class="order-total">
                                <div class="total-breakdown">
                                    <div class="total-line">
                                        <span>Sous-total</span>
                                        <span>{{ number_format($order->subtotal, 0, ',', ' ') }} F</span>
                                    </div>
                                    <div class="total-line">
                                        <span>Frais de livraison</span>
                                        <span class="free-delivery">Gratuit</span>
                                    </div>
                                    @if($order->discount > 0)
                                        <div class="total-line discount">
                                            <span>Remise</span>
                                            <span>-{{ number_format($order->discount, 0, ',', ' ') }} F</span>
                                        </div>
                                    @endif
                                    <div class="total-line total">
                                        <span>Total</span>
                                        <span>{{ number_format($order->total, 0, ',', ' ') }} F</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Articles commandés -->
                <div class="order-items-section">
                    <h3>Articles commandés</h3>
                    <div class="order-items-list">
                        @foreach($order->items as $item)
                            <div class="order-item">
                                <div class="item-image">
                                    <img src="{{ $item->product_image ?: asset('images/product-placeholder.png') }}" 
                                         alt="{{ $item->product_name }}">
                                </div>
                                <div class="item-details">
                                    <h4 class="item-name">{{ $item->product_name }}</h4>
                                    <p class="item-commerce">{{ $item->product->commerce->name }}</p>
                                    <div class="item-quantity-price">
                                        <span class="quantity">Quantité: {{ $item->quantity }}</span>
                                        <span class="price">{{ number_format($item->price, 0, ',', ' ') }} F</span>
                                    </div>
                                </div>
                                <div class="item-total">
                                    <span class="total-price">{{ number_format($item->subtotal, 0, ',', ' ') }} F</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    <span>Retour à l'accueil</span>
                </a>
                <a href="#" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    <span>Voir mes commandes</span>
                </a>
            </div>

            <!-- Prochaines étapes -->
            <div class="next-steps">
                <h3>Prochaines étapes</h3>
                <div class="steps-list">
                    <div class="step-item">
                        <div class="step-icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <div class="step-content">
                            <h4>Préparation</h4>
                            <p>Le restaurant prépare votre commande</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <i class="fas fa-motorcycle"></i>
                        </div>
                        <div class="step-content">
                            <h4>Livraison</h4>
                            <p>Un livreur vient chercher votre commande</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                        <div class="step-content">
                            <h4>Notification</h4>
                            <p>Vous recevrez une notification à chaque étape</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem 0;
}

.success-content {
    max-width: 1000px;
    margin: 0 auto;
}

/* Animation et message de succès */
.success-animation {
    text-align: center;
    margin-bottom: 3rem;
}

.success-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--success-color), #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: white;
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
    animation: successPulse 2s ease-in-out infinite;
}

@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.success-message h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.success-message p {
    font-size: 1.25rem;
    color: var(--text-light);
    margin: 0;
}

/* Détails de la commande */
.order-details {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 3rem;
}

.order-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.order-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.order-number {
    text-align: right;
}

.order-number span {
    display: block;
    font-size: 0.875rem;
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.order-number strong {
    font-size: 1.25rem;
    color: var(--primary-color);
}

/* Grille d'informations */
.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.info-header i {
    color: var(--primary-color);
    font-size: 1.25rem;
}

.info-header h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-label {
    color: var(--text-light);
    font-size: 0.875rem;
}

.info-value {
    font-weight: 600;
    color: var(--text-dark);
}

.status-pending {
    color: #f59e0b;
}

.status-confirmed {
    color: var(--success-color);
}

.status-delivered {
    color: var(--success-color);
}

/* Adresse de livraison */
.delivery-address {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.address-name {
    font-weight: 700;
    color: var(--text-dark);
}

.address-details {
    color: var(--text-dark);
}

.address-phone {
    color: var(--text-light);
    font-size: 0.875rem;
}

.address-additional {
    color: var(--text-light);
    font-size: 0.875rem;
    font-style: italic;
}

/* Temps de livraison */
.delivery-time {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.time-estimate {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.time-range {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--success-color);
}

.time-description {
    font-size: 0.875rem;
    color: var(--text-light);
}

.delivery-note {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    font-size: 0.875rem;
}

.delivery-note i {
    color: var(--primary-color);
}

/* Total de la commande */
.order-total {
    display: flex;
    flex-direction: column;
}

.total-breakdown {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.total-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    color: var(--text-dark);
}

.total-line.discount {
    color: var(--success-color);
}

.total-line.total {
    font-weight: 700;
    font-size: 1.125rem;
    padding-top: 0.5rem;
    border-top: 1px solid #e5e7eb;
    margin-top: 0.5rem;
}

.free-delivery {
    color: var(--success-color);
    font-weight: 600;
}

/* Articles commandés */
.order-items-section {
    margin-bottom: 2rem;
}

.order-items-section h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.order-items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1rem;
    border: 1px solid #e5e7eb;
}

.order-item .item-image {
    width: 80px;
    height: 80px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.order-item .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item .item-details {
    flex: 1;
}

.order-item .item-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.order-item .item-commerce {
    color: var(--primary-color);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.order-item .item-quantity-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.order-item .quantity {
    color: var(--text-light);
    font-size: 0.875rem;
}

.order-item .price {
    color: var(--text-dark);
    font-weight: 600;
}

.order-item .item-total {
    text-align: right;
}

.order-item .total-price {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
}

/* Actions */
.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 3rem;
}

.btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    color: white;
}

.btn-secondary {
    background: white;
    color: var(--text-dark);
    border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
    background: #f8f9fa;
    color: var(--text-dark);
}

/* Prochaines étapes */
.next-steps {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    text-align: center;
}

.next-steps h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 2rem;
}

.steps-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.step-item .step-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.step-item .step-content h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.step-item .step-content p {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

/* Responsive */
@media (max-width: 768px) {
    .success-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
    
    .success-message h1 {
        font-size: 1.75rem;
    }
    
    .order-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .order-number {
        text-align: center;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
    }
    
    .order-item .item-details {
        text-align: center;
    }
    
    .order-item .item-quantity-price {
        justify-content: center;
        gap: 2rem;
    }
    
    .success-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    .steps-list {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush 