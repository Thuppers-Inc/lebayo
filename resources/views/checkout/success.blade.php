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
                    @if(isset($isMultipleOrders) && $isMultipleOrders)
                        <h1>Commandes confirmées !</h1>
                        <p>Vos {{ count($allOrders) }} commandes ont été passées avec succès</p>
                        <div class="multiple-orders-note">
                            <i class="fas fa-info-circle"></i>
                            <span>Vos produits proviennent de différents commerces, nous avons donc créé une commande séparée pour chaque commerce.</span>
                        </div>
                    @else
                        <h1>Commande confirmée !</h1>
                        <p>Votre commande a été passée avec succès</p>
                    @endif
                </div>
            </div>

            <!-- Détails de la commande -->
            <div class="order-details">
                @if(isset($isMultipleOrders) && $isMultipleOrders)
                    <!-- Affichage pour plusieurs commandes -->
                    <div class="order-header">
                        <h2>Détails de vos commandes</h2>
                        <div class="multiple-orders-summary">
                            <div class="summary-item">
                                <span>{{ count($allOrders) }} commandes</span>
                            </div>
                            <div class="summary-item">
                                <span>{{ $totalItems }} articles au total</span>
                            </div>
                            <div class="summary-item">
                                <strong>Total général : {{ number_format($totalAmount, 0, ',', ' ') }} F</strong>
                            </div>
                        </div>
                    </div>

                    @foreach($allOrders as $orderItem)
                    <div class="single-order-card">
                        <div class="order-card-header">
                            <div class="order-info">
                                <h3>Commande #{{ $orderItem->order_number }}</h3>
                                <p class="commerce-name">
                                    <i class="fas fa-store"></i>
                                    {{ $orderItem->commerce->name }} ({{ $orderItem->commerce->commerce_type_name }})
                                </p>
                            </div>
                            <div class="order-total">
                                <span class="total-amount">{{ number_format($orderItem->total, 0, ',', ' ') }} F</span>
                            </div>
                        </div>
                        
                        <div class="order-items-mini">
                            @foreach($orderItem->items as $item)
                                <div class="mini-item">
                                    <img src="{{ $item->product_image ?: asset('images/product-placeholder.png') }}" 
                                         alt="{{ $item->product_name }}">
                                    <div class="mini-item-details">
                                        <span class="item-name">{{ $item->product_name }}</span>
                                        <span class="item-qty">x{{ $item->quantity }}</span>
                                    </div>
                                    <span class="item-price">{{ number_format($item->subtotal, 0, ',', ' ') }} F</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach

                @else
                    <!-- Affichage pour une seule commande (logique existante) -->
                    <div class="order-header">
                        <h2>Détails de votre commande</h2>
                        <div class="order-number">
                            <span>Numéro de commande</span>
                            <strong>#{{ $order->order_number }}</strong>
                        </div>
                    </div>

                    @if(!isset($isMultipleOrders) || !$isMultipleOrders)
                        <!-- Sections pour une seule commande -->
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
                    @endif
                @endif
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

.success-animation {
    text-align: center;
    margin-bottom: 3rem;
    animation: slideInUp 0.8s ease-out;
}

.success-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, #28a745, #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    box-shadow: 0 20px 40px rgba(40, 167, 69, 0.3);
    animation: bounceIn 1s ease-out 0.3s both;
}

.success-icon i {
    font-size: 3rem;
    color: white;
}

.success-message h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.success-message p {
    font-size: 1.2rem;
    color: #718096;
    margin-bottom: 1rem;
}

.multiple-orders-note {
    background: linear-gradient(135deg, #e3f2fd, #f3e5f5);
    border-left: 4px solid #2196f3;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    margin-top: 1rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.95rem;
    color: #37474f;
}

.multiple-orders-note i {
    color: #2196f3;
    font-size: 1.1rem;
}

.multiple-orders-summary {
    display: flex;
    gap: 2rem;
    flex-wrap: wrap;
    justify-content: center;
    margin-top: 1rem;
}

.summary-item {
    background: white;
    padding: 1rem 1.5rem;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    font-weight: 600;
    color: #2d3748;
}

.single-order-card {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
    border: 1px solid #e2e8f0;
    transition: transform 0.3s ease;
}

.single-order-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15);
}

.order-card-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e2e8f0;
}

.order-info h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.commerce-name {
    color: #718096;
    font-size: 0.95rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0;
}

.commerce-name i {
    color: #ff6b35;
}

.order-total .total-amount {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ff6b35;
}

.order-items-mini {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.mini-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.mini-item img {
    width: 40px;
    height: 40px;
    border-radius: 6px;
    object-fit: cover;
    flex-shrink: 0;
}

.mini-item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.mini-item-details .item-name {
    font-weight: 600;
    color: #2d3748;
    font-size: 0.9rem;
}

.mini-item-details .item-qty {
    font-size: 0.8rem;
    color: #718096;
}

.mini-item .item-price {
    font-weight: 600;
    color: #ff6b35;
    font-size: 0.9rem;
}

.order-details {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    animation: slideInUp 0.8s ease-out 0.2s both;
}

.order-header {
    text-align: center;
    margin-bottom: 2rem;
    padding-bottom: 1.5rem;
    border-bottom: 2px solid #f1f5f9;
}

.order-header h2 {
    font-size: 2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1rem;
}

.order-number {
    display: inline-flex;
    flex-direction: column;
    background: linear-gradient(135deg, #ff6b35, #f093fb);
    color: white;
    padding: 1rem 2rem;
    border-radius: 15px;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

.order-number span {
    font-size: 0.875rem;
    opacity: 0.9;
    margin-bottom: 0.25rem;
}

.order-number strong {
    font-size: 1.25rem;
    font-weight: 700;
}

.order-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 15px;
    padding: 1.5rem;
    border: 1px solid #e9ecef;
    transition: transform 0.3s ease;
}

.info-card:hover {
    transform: translateY(-3px);
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.info-header i {
    font-size: 1.5rem;
    color: #ff6b35;
    width: 30px;
}

.info-header h3 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin: 0;
}

.info-content {
    padding-left: 2.25rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.75rem;
}

.info-item:last-child {
    margin-bottom: 0;
}

.info-label {
    color: #718096;
    font-weight: 500;
}

.info-value {
    font-weight: 600;
    color: #2d3748;
}

.status-pending {
    color: #f59e0b;
}

.status-confirmed {
    color: #10b981;
}

.delivery-address {
    line-height: 1.6;
}

.address-name {
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.address-details,
.address-phone {
    color: #718096;
    margin-bottom: 0.25rem;
}

.address-additional {
    color: #9ca3af;
    font-style: italic;
    font-size: 0.9rem;
}

.delivery-time {
    text-align: center;
}

.time-estimate {
    margin-bottom: 1rem;
}

.time-range {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ff6b35;
    display: block;
}

.time-description {
    color: #718096;
    font-size: 0.9rem;
}

.delivery-note {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    color: #10b981;
    font-weight: 500;
}

.total-breakdown {
    space-y: 0.5rem;
}

.total-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.5rem;
    color: #4a5568;
}

.total-line.discount {
    color: #10b981;
}

.total-line.total {
    border-top: 2px solid #e2e8f0;
    padding-top: 0.75rem;
    margin-top: 0.75rem;
    font-weight: 700;
    font-size: 1.1rem;
    color: #2d3748;
}

.free-delivery {
    color: #10b981;
    font-weight: 600;
}

.order-items-section {
    margin-top: 2rem;
}

.order-items-section h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 1.5rem;
    text-align: center;
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
    padding: 1.5rem;
    background: #f8f9fa;
    border-radius: 15px;
    border: 1px solid #e9ecef;
    transition: transform 0.2s ease;
}

.order-item:hover {
    transform: translateX(5px);
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
}

.item-name {
    font-size: 1.1rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.item-commerce {
    color: #718096;
    font-size: 0.9rem;
    margin-bottom: 0.5rem;
}

.item-quantity-price {
    display: flex;
    gap: 1rem;
    font-size: 0.9rem;
}

.quantity {
    color: #718096;
}

.price {
    color: #ff6b35;
    font-weight: 600;
}

.item-total {
    text-align: right;
}

.total-price {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
}

.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-bottom: 3rem;
    animation: slideInUp 0.8s ease-out 0.4s both;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    font-size: 1rem;
}

.btn-primary {
    background: linear-gradient(135deg, #ff6b35, #f093fb);
    color: white;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 12px 35px rgba(255, 107, 53, 0.4);
    color: white;
}

.btn-secondary {
    background: white;
    color: #4a5568;
    border: 2px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #f7fafc;
    border-color: #cbd5e0;
    transform: translateY(-2px);
    color: #4a5568;
}

.next-steps {
    background: white;
    border-radius: 20px;
    padding: 2.5rem;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    animation: slideInUp 0.8s ease-out 0.6s both;
}

.next-steps h3 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2d3748;
    text-align: center;
    margin-bottom: 2rem;
}

.steps-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 2rem;
}

.step-item {
    text-align: center;
    padding: 1.5rem;
    border-radius: 15px;
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    transition: transform 0.3s ease;
}

.step-item:hover {
    transform: translateY(-5px);
}

.step-icon {
    width: 70px;
    height: 70px;
    background: linear-gradient(135deg, #ff6b35, #f093fb);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    color: white;
    font-size: 1.5rem;
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
}

.step-content h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #2d3748;
    margin-bottom: 0.5rem;
}

.step-content p {
    color: #718096;
    font-size: 0.9rem;
    line-height: 1.5;
}

/* Animations */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes bounceIn {
    0% {
        opacity: 0;
        transform: scale(0.3);
    }
    50% {
        opacity: 1;
        transform: scale(1.05);
    }
    70% {
        transform: scale(0.9);
    }
    100% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Responsive */
@media (max-width: 768px) {
    .success-page {
        padding: 1rem 0;
    }
    
    .order-details,
    .next-steps {
        padding: 1.5rem;
        margin: 0 1rem 1.5rem;
    }
    
    .success-icon {
        width: 100px;
        height: 100px;
    }
    
    .success-icon i {
        font-size: 2.5rem;
    }
    
    .success-message h1 {
        font-size: 2rem;
    }
    
    .order-info-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .order-item {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .item-quantity-price {
        justify-content: center;
    }
    
    .success-actions {
        flex-direction: column;
        align-items: center;
        margin: 0 1rem 2rem;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    .steps-list {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .multiple-orders-summary {
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }
    
    .order-card-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1rem;
    }
    
    .mini-item {
        padding: 1rem;
    }
}
</style>
@endpush 