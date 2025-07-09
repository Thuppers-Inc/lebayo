@extends('layouts.app')

@section('title', 'Checkout - Lebayo')

@section('content')
<div class="checkout-page">
    <div class="container">
        <!-- En-tête du checkout -->
        <div class="checkout-header">
            <div class="back-section">
                <a href="{{ route('cart.index') }}" class="back-btn">
                    <span class="back-icon">←</span>
                    Retour au panier
                </a>
            </div>
            
            <div class="checkout-title-section">
                <h1 class="checkout-title">@yield('checkout-title', 'Checkout')</h1>
            </div>
        </div>

        <!-- Contenu principal du checkout -->
        <div class="checkout-content">
            <!-- Étapes du processus -->
            <div class="checkout-steps">
                @php
                    $stepKeys = array_keys($steps);
                    $currentStepIndex = array_search($currentStep, $stepKeys);
                @endphp
                
                @foreach($steps as $key => $step)
                    @php
                        $stepIndex = array_search($key, $stepKeys);
                        $isActive = $key === $currentStep;
                        $isCompleted = $stepIndex < $currentStepIndex || $step['completed'];
                    @endphp
                    
                    <div class="checkout-step {{ $isActive ? 'active' : '' }} {{ $isCompleted ? 'completed' : '' }}">
                        <div class="step-connector"></div>
                        <div class="step-circle">
                            @if($isCompleted)
                                <i class="fas fa-check"></i>
                            @else
                                <i class="{{ $step['icon'] }}"></i>
                            @endif
                        </div>
                        <div class="step-content">
                            <h3 class="step-title">{{ $step['title'] }}</h3>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Contenu principal -->
            <div class="checkout-main">
                <!-- Contenu de l'étape courante -->
                <div class="checkout-step-content">
                    @yield('step-content')
                </div>

                <!-- Résumé de commande -->
                <div class="checkout-summary">
                    <div class="summary-card">
                        <h3>Résumé de commande</h3>
                        
                        <!-- Articles du panier -->
                        <div class="order-items">
                            @foreach($cartItems as $item)
                                <div class="order-item">
                                    <div class="item-image">
                                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                    </div>
                                    <div class="item-details">
                                        <h4 class="item-name">{{ $item->product->name }}</h4>
                                        <p class="item-quantity">Quantité: {{ $item->quantity }}</p>
                                        <p class="item-price">{{ $item->formatted_subtotal }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Détails de la facture -->
                        <div class="bill-details">
                            <div class="bill-line">
                                <span>Sous-total ({{ $totalItems }} {{ $totalItems > 1 ? 'articles' : 'article' }})</span>
                                <span>{{ number_format($subtotal, 0, ',', ' ') }} F</span>
                            </div>
                            
                            <div class="bill-line">
                                <span>Frais de livraison</span>
                                <span class="free-delivery">Gratuit</span>
                            </div>
                            
                            @if($discount > 0)
                                <div class="bill-line discount">
                                    <span>Remise</span>
                                    <span>-{{ number_format($discount, 0, ',', ' ') }} F</span>
                                </div>
                            @endif
                            
                            <div class="bill-divider"></div>
                            
                            <div class="bill-total">
                                <span>Total</span>
                                <span>{{ number_format($total, 0, ',', ' ') }} F</span>
                            </div>
                        </div>

                        <!-- Informations supplémentaires selon l'étape -->
                        @if(isset($selectedAddress))
                            <div class="delivery-info">
                                <h4>Adresse de livraison</h4>
                                <p><strong>{{ $selectedAddress->name }}</strong></p>
                                <p>{{ $selectedAddress->full_address }}</p>
                                <p>{{ $selectedAddress->phone }}</p>
                            </div>
                        @endif

                        @if(isset($paymentMethod))
                            <div class="payment-info">
                                <h4>Mode de paiement</h4>
                                <p>{{ $paymentMethod === 'cash_on_delivery' ? 'Paiement à la livraison' : 'Autre' }}</p>
                            </div>
                        @endif

                        <!-- Bouton d'action -->
                        <div class="checkout-action">
                            @yield('checkout-action')
                        </div>
                        
                        <div class="security-info">
                            <span class="security-icon">🔒</span>
                            <span>Commande sécurisée</span>
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
.checkout-page {
    padding: 2rem 0;
    min-height: 80vh;
    background: #f8f9fa;
}

.checkout-header {
    margin-bottom: 3rem;
}

.checkout-title-section {
    text-align: center;
    margin-top: 1rem;
}

.checkout-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

/* Étapes du processus */
.checkout-steps {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 3rem;
    position: relative;
}

.checkout-step {
    display: flex;
    flex-direction: column;
    align-items: center;
    position: relative;
    flex: 1;
    max-width: 200px;
}

.checkout-step:not(:last-child) .step-connector {
    position: absolute;
    top: 25px;
    right: -50%;
    width: 100%;
    height: 2px;
    background: #e5e7eb;
    z-index: 1;
}

.checkout-step.completed:not(:last-child) .step-connector {
    background: var(--primary-color);
}

.step-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background: #e5e7eb;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #6b7280;
    position: relative;
    z-index: 2;
    transition: all 0.3s ease;
}

.checkout-step.active .step-circle {
    background: var(--primary-color);
    color: white;
    transform: scale(1.1);
}

.checkout-step.completed .step-circle {
    background: var(--success-color);
    color: white;
}

.step-content {
    margin-top: 1rem;
    text-align: center;
}

.step-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin: 0;
}

.checkout-step.active .step-title {
    color: var(--primary-color);
}

.checkout-step.completed .step-title {
    color: var(--success-color);
}

/* Contenu principal */
.checkout-main {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 3rem;
    align-items: start;
}

.checkout-step-content {
    background: white;
    padding: 2.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

/* Résumé de commande */
.checkout-summary {
    position: sticky;
    top: 2rem;
}

.summary-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
}

.summary-card h3 {
    margin-bottom: 1.5rem;
    font-size: 1.5rem;
    color: var(--text-dark);
    font-weight: 700;
}

.order-items {
    margin-bottom: 2rem;
}

.order-item {
    display: flex;
    gap: 1rem;
    margin-bottom: 1.5rem;
    padding-bottom: 1.5rem;
    border-bottom: 1px solid #f3f4f6;
}

.order-item:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.item-image {
    width: 60px;
    height: 60px;
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
}

.item-name {
    font-size: 1rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.item-quantity {
    font-size: 0.875rem;
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.item-price {
    font-size: 1rem;
    font-weight: 700;
    color: var(--primary-color);
    margin: 0;
}

/* Détails de la facture */
.bill-details {
    margin-bottom: 2rem;
}

.bill-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    color: var(--text-dark);
}

.bill-line.discount {
    color: var(--success-color);
}

.free-delivery {
    color: var(--success-color);
    font-weight: 600;
}

.bill-divider {
    height: 1px;
    background: #e5e7eb;
    margin: 1rem 0;
}

.bill-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
}

/* Informations supplémentaires */
.delivery-info,
.payment-info {
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.delivery-info h4,
.payment-info h4 {
    margin-bottom: 0.5rem;
    font-size: 1rem;
    color: var(--text-dark);
}

.delivery-info p,
.payment-info p {
    margin: 0.25rem 0;
    color: var(--text-light);
    font-size: 0.875rem;
}

/* Bouton d'action */
.checkout-action {
    margin-bottom: 1rem;
}

.checkout-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    color: white;
}

.checkout-btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none;
}

.security-info {
    text-align: center;
    color: var(--text-light);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

/* Responsive */
@media (max-width: 768px) {
    .checkout-main {
        grid-template-columns: 1fr;
        gap: 2rem;
    }
    
    .checkout-summary {
        order: -1;
        position: static;
    }
    
    .checkout-steps {
        flex-direction: column;
        gap: 1rem;
    }
    
    .checkout-step {
        flex-direction: row;
        max-width: none;
        width: 100%;
    }
    
    .checkout-step:not(:last-child) .step-connector {
        display: none;
    }
    
    .step-content {
        margin-top: 0;
        margin-left: 1rem;
        text-align: left;
    }
    
    .checkout-step-content {
        padding: 1.5rem;
    }
    
    .summary-card {
        padding: 1.5rem;
    }
}
</style>
@endpush 