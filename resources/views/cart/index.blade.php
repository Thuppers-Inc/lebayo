@extends('layouts.app')

@section('title', 'Mon Panier - Lebayo')

@section('content')
<div class="cart-page">
    <div class="container">
        <!-- En-tête du panier -->
        <div class="cart-header">
            <div class="back-section">
                <a href="{{ url()->previous() }}" class="back-btn">
                    <span class="back-icon">←</span>
                    Continuer les achats
                </a>
            </div>

            <div class="cart-title-section">
                <h1 class="cart-title">Mon Panier</h1>
                @if($cart && !$cart->isEmpty())
                    <p class="cart-subtitle">{{ $totalItems }} {{ $totalItems > 1 ? 'articles' : 'article' }} dans votre panier</p>
                @endif
            </div>
        </div>

        @if(!$cart || $cart->isEmpty())
            <!-- Panier vide -->
            <div class="empty-cart">
                <div class="empty-cart-icon">🛒</div>
                <h2>Votre panier est vide</h2>
                <p>Découvrez nos commerces et ajoutez des produits à votre panier</p>
                <a href="{{ route('home') }}" class="btn btn-red">Découvrir nos commerces</a>
            </div>
        @else
            <!-- Contenu du panier -->
            <div class="cart-content">
                <div class="cart-items-section">
                    <div class="cart-items-header">
                        <h3>Articles</h3>
                        <button type="button" class="clear-cart-btn" id="clearCartBtn">
                            🗑️ Vider le panier
                        </button>
                    </div>

                    <div class="cart-items-list" id="cartItemsList">
                        @foreach($cartItems as $item)
                            @php
                                $commerce = $item->product->commerce ?? null;
                                $isOpen = $commerce ? $commerce->isOpen() : true;
                                $isRealEstate = $commerce && in_array($commerce->commerceType->name ?? '', ['Immobilier', 'Résidence Meublée']);
                            @endphp
                            <div class="cart-item {{ !$isOpen ? 'commerce-closed-item' : '' }}" id="cart-item-{{ $item->product->id ?? $item->id }}">
                                <div class="item-image">
                                    <img src="{{ $item->product->image_url ?? asset('images/product-placeholder.png') }}" alt="{{ $item->product->name ?? 'Produit supprimé' }}" loading="lazy">
                                    @if($commerce && !$isOpen)
                                        <div class="closed-overlay">
                                            <div class="status-badge status-badge-danger">
                                                <span class="status-icon">{{ $commerce->status_icon }}</span>
                                                <span class="status-label">{{ $commerce->status_label }}</span>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                <div class="item-details">
                                    <div class="item-info">
                                        <div class="item-header-row">
                                            <h4 class="item-name">{{ $item->product->name ?? 'Produit supprimé' }}</h4>
                                            <div class="item-price-mobile">
                                                <span class="total-price">{{ $item->formatted_subtotal }}</span>
                                            </div>
                                        </div>
                                        <p class="item-commerce">{{ $commerce->name ?? 'Commerce supprimé' }}</p>
                                        @if($commerce && !$isOpen)
                                            <div class="commerce-closed-notice">
                                                <span class="notice-icon">⚠️</span>
                                                <span class="notice-text">Commerce {{ $isRealEstate ? 'indisponible' : 'fermé' }}. Commande impossible.</span>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="item-actions">
                                        <div class="quantity-controls">
                                            @if($item->product)
                                                <button type="button" class="quantity-btn minus {{ !$isOpen ? 'disabled' : '' }}" 
                                                        data-product-id="{{ $item->product->id }}" 
                                                        data-quantity="{{ $item->quantity - 1 }}"
                                                        {{ !$isOpen ? 'disabled title="Commerce fermé"' : '' }}>-</button>
                                                <span class="quantity">{{ $item->quantity }}</span>
                                                <button type="button" class="quantity-btn plus {{ !$isOpen ? 'disabled' : '' }}" 
                                                        data-product-id="{{ $item->product->id }}" 
                                                        data-quantity="{{ $item->quantity + 1 }}"
                                                        {{ !$isOpen ? 'disabled title="Commerce fermé"' : '' }}>+</button>
                                            @else
                                                <span class="quantity">{{ $item->quantity }}</span>
                                            @endif
                                        </div>

                                        <div class="item-price-desktop">
                                            <span class="unit-price">{{ $item->formatted_price }}</span>
                                            <span class="total-price">{{ $item->formatted_subtotal }}</span>
                                        </div>

                                        @if($item->product)
                                            <button type="button" class="remove-item-btn" data-product-id="{{ $item->product->id }}" title="Supprimer">
                                                🗑️
                                            </button>
                                        @else
                                            <button type="button" class="remove-item-btn" data-cart-item-id="{{ $item->id }}" title="Supprimer">
                                                🗑️
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Résumé du panier -->
                <div class="cart-summary">
                    <div class="summary-card">
                        <h3>Résumé de la commande</h3>

                        <div class="summary-line">
                            <span>Sous-total ({{ $totalItems }} {{ $totalItems > 1 ? 'articles' : 'article' }})</span>
                            <span id="cartSubtotal">{{ $cart->formatted_total }}</span>
                        </div>

                        <div class="summary-line">
                            <span>Frais de livraison ({{ $cart->unique_commerces_count }} {{ $cart->unique_commerces_count > 1 ? 'boutiques' : 'boutique' }})</span>
                            <span class="delivery-fee">{{ $cart->formatted_delivery_fee }}</span>
                        </div>

                        @if($discount > 0)
                        <div class="summary-line discount-line">
                            <span>Remise première commande</span>
                            <span class="discount-amount">-{{ $cart->formatted_discount }}</span>
                        </div>
                        @endif

                        <div class="summary-divider"></div>

                        <div class="summary-total">
                            <span>Total</span>
                            <span id="cartTotal">{{ $cart->formatted_final_total }}</span>
                        </div>

                        @php
                            $hasClosedCommerces = $cartItems->filter(function($item) {
                                $commerce = $item->product->commerce ?? null;
                                return $commerce && !$commerce->isOpen();
                            })->isNotEmpty();
                        @endphp
                        @if($hasClosedCommerces)
                            <button type="button" class="checkout-btn checkout-btn-disabled" disabled title="Impossible de commander : certains commerces sont fermés">
                                <span>Commande indisponible</span>
                                <span class="checkout-icon">🔒</span>
                            </button>
                            <div class="checkout-warning">
                                <span class="warning-icon">⚠️</span>
                                <span>Certains commerces de votre panier sont fermés. Veuillez retirer ces articles ou attendre leur réouverture.</span>
                            </div>
                        @else
                            <a href="{{ route('checkout.index') }}" class="checkout-btn">
                                <span>Passer la commande</span>
                                <span class="checkout-icon">→</span>
                            </a>
                        @endif

                        <div class="security-info">
                            <span class="security-icon">🔒</span>
                            <span>Paiement sécurisé</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Modal personnalisé pour vider le panier -->
<div class="custom-modal" id="clearCartModal" style="display: none;">
    <div class="custom-modal-backdrop"></div>
    <div class="custom-modal-dialog">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                <h3>Vider le panier</h3>
                <button type="button" class="custom-modal-close" id="closeModalBtn">&times;</button>
            </div>
            <div class="custom-modal-body">
                <p>Êtes-vous sûr de vouloir vider complètement votre panier ?</p>
                <p style="color: #666; font-size: 0.9rem;">Cette action ne peut pas être annulée.</p>
            </div>
            <div class="custom-modal-footer">
                <button type="button" class="btn btn-outline" id="cancelClearBtn">Annuler</button>
                <button type="button" class="btn btn-danger" id="confirmClearCartBtn">Vider le panier</button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.cart-page {
    padding: max(calc(env(safe-area-inset-top) + 1rem), 1rem) 1rem 2rem;
    min-height: calc(100vh - 200px);
    background: linear-gradient(to bottom, #f8f9fa 0%, #ffffff 100%);
}

.cart-header {
    margin-bottom: 1.5rem;
    padding: 0 0.5rem;
}

.cart-header .back-section {
    padding: 0.5rem 0 1rem;
    background: transparent;
}

.cart-header .back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: white;
    color: var(--text-dark);
    text-decoration: none;
    padding: 0.75rem 1.25rem;
    border-radius: 12px;
    font-weight: 600;
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: 1px solid #e9ecef;
    min-height: 44px; /* Touch target pour mobile */
}

.cart-header .back-btn:hover {
    background: #f8f9fa;
    transform: translateX(-4px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.cart-header .back-icon {
    font-size: clamp(1.1rem, 3vw, 1.3rem);
    transition: transform 0.3s ease;
}

.cart-header .back-btn:hover .back-icon {
    transform: translateX(-2px);
}

.cart-title-section {
    text-align: center;
    margin-top: 0.5rem;
}

.cart-title {
    font-size: clamp(1.8rem, 5vw, 2.5rem);
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

.cart-subtitle {
    color: var(--text-light);
    font-size: clamp(0.9rem, 3vw, 1.1rem);
}

.empty-cart {
    text-align: center;
    padding: clamp(3rem, 8vw, 4rem) 1rem;
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--box-shadow);
    margin: 2rem 0;
}

.empty-cart-icon {
    font-size: clamp(3rem, 10vw, 4rem);
    margin-bottom: 1rem;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
    40% { transform: translateY(-10px); }
    60% { transform: translateY(-5px); }
}

.empty-cart h2 {
    font-size: clamp(1.4rem, 4vw, 1.8rem);
    color: var(--text-dark);
    margin-bottom: 1rem;
    font-weight: 700;
}

.empty-cart p {
    color: var(--text-light);
    margin-bottom: 2rem;
    font-size: clamp(0.9rem, 3vw, 1rem);
}

.cart-content {
    display: grid;
    grid-template-columns: 1fr minmax(300px, 380px);
    gap: clamp(1rem, 3vw, 2rem);
    align-items: start;
}

.cart-items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #e9ecef;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.cart-items-header h3 {
    font-size: clamp(1.2rem, 4vw, 1.5rem);
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.clear-cart-btn {
    background: rgba(229, 62, 62, 0.1);
    border: 1px solid rgba(229, 62, 62, 0.2);
    color: var(--danger-color);
    font-size: clamp(0.85rem, 2.5vw, 0.9rem);
    cursor: pointer;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 500;
    min-height: 44px; /* Touch target pour mobile */
}

.clear-cart-btn:hover {
    background-color: rgba(229, 62, 62, 0.15);
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(229, 62, 62, 0.2);
}

.clear-cart-btn:active {
    transform: translateY(0);
}

.cart-item {
    display: flex;
    gap: 0.875rem;
    padding: 1rem;
    background: white;
    border-radius: 14px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    margin-bottom: 0.875rem;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid transparent;
    position: relative;
    overflow: hidden;
}

.cart-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    transform: scaleY(0);
    transition: transform 0.3s ease;
}

.cart-item:hover {
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.12);
    transform: translateY(-2px);
    border-color: rgba(255, 107, 53, 0.2);
}

.cart-item:hover::before {
    transform: scaleY(1);
}

.commerce-closed-item {
    opacity: 0.65;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-left: 4px solid var(--danger-color);
    filter: grayscale(0.3);
}

.commerce-closed-item::before {
    background: var(--danger-color);
    transform: scaleY(1);
}

.commerce-closed-item:hover {
    transform: none;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.commerce-closed-item .item-image {
    position: relative;
}

.closed-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(0, 0, 0, 0.4) 0%, rgba(0, 0, 0, 0.2) 100%);
    display: flex;
    align-items: flex-start;
    justify-content: flex-start;
    padding: 0.5rem;
    border-radius: 12px;
    backdrop-filter: blur(2px);
}

.closed-overlay .status-badge {
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

.commerce-closed-notice {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    border: 1.5px solid #ffc107;
    border-radius: 10px;
    margin-top: 0.5rem;
    margin-bottom: 0;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.15);
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.9; }
}

.notice-icon {
    font-size: clamp(1rem, 3vw, 1.2rem);
    flex-shrink: 0;
    animation: shake 0.5s ease-in-out infinite;
}

@keyframes shake {
    0%, 100% { transform: rotate(0deg); }
    25% { transform: rotate(-5deg); }
    75% { transform: rotate(5deg); }
}

.notice-text {
    font-size: clamp(0.75rem, 2vw, 0.8rem);
    color: #856404;
    line-height: 1.4;
    font-weight: 600;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    flex: 1;
    min-width: 0;
}

.quantity-btn.disabled {
    opacity: 0.5;
    cursor: not-allowed;
    pointer-events: none;
}

.checkout-btn-disabled {
    background: linear-gradient(135deg, #6c757d, #5a6268) !important;
    cursor: not-allowed;
    opacity: 0.8;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2) !important;
}

.checkout-btn-disabled:hover {
    transform: none !important;
    box-shadow: 0 2px 8px rgba(108, 117, 125, 0.2) !important;
}

.checkout-btn-disabled::before {
    display: none;
}

.checkout-warning {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 1rem;
    background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
    border: 1.5px solid #ffc107;
    border-radius: 12px;
    margin-top: 0.75rem;
    font-size: clamp(0.8rem, 2.2vw, 0.85rem);
    color: #856404;
    line-height: 1.5;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.15);
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.warning-icon {
    font-size: clamp(1rem, 3vw, 1.2rem);
    flex-shrink: 0;
    animation: shake 0.5s ease-in-out infinite;
}

.item-image {
    width: clamp(75px, 20vw, 90px);
    height: clamp(75px, 20vw, 90px);
    min-width: 75px;
    min-height: 75px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
    position: relative;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.cart-item:hover .item-image {
    transform: scale(1.05);
}

.commerce-closed-item .item-image {
    transform: none;
}

.item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.item-details {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 0.625rem;
    min-width: 0;
    justify-content: space-between;
}

.item-info {
    flex: 1;
    min-width: 0;
}

.item-header-row {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 0.5rem;
    margin-bottom: 0.4rem;
}

.item-name {
    font-size: clamp(0.95rem, 2.8vw, 1.05rem);
    font-weight: 600;
    color: var(--text-dark);
    line-height: 1.3;
    flex: 1;
    min-width: 0;
    margin: 0;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.item-price-mobile {
    display: none;
    flex-shrink: 0;
}

.item-commerce {
    color: var(--primary-color);
    font-size: clamp(0.8rem, 2.2vw, 0.85rem);
    font-weight: 600;
    margin-bottom: 0.4rem;
    display: inline-block;
    padding: 0.15rem 0.5rem;
    background: rgba(255, 107, 53, 0.1);
    border-radius: 10px;
}

.item-description {
    display: none; /* Masqué sur mobile pour économiser l'espace */
}

.item-actions {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 0.75rem;
    flex-wrap: nowrap;
    padding-top: 0.625rem;
    border-top: 1px solid #f0f0f0;
    margin-top: 0.25rem;
}

.item-price-desktop {
    display: flex;
    flex-direction: column;
    text-align: right;
    flex: 1;
    min-width: 80px;
}

.item-price-mobile .total-price {
    font-size: clamp(1rem, 3vw, 1.15rem);
    font-weight: 700;
    color: var(--text-dark);
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: #f8f9fa;
    padding: 0.3rem;
    border-radius: 12px;
    border: 1px solid #e9ecef;
    flex-shrink: 0;
}

.quantity-btn {
    width: 36px;
    height: 36px;
    min-width: 44px;
    min-height: 44px;
    border: 2px solid #dee2e6;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: 700;
    font-size: 1.1rem;
    color: var(--text-dark);
    transition: all 0.2s ease;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.quantity-btn:active {
    transform: scale(0.95);
}

.quantity-btn:hover:not(.disabled) {
    border-color: var(--primary-color);
    color: var(--primary-color);
    background: rgba(255, 107, 53, 0.05);
    box-shadow: 0 2px 6px rgba(255, 107, 53, 0.2);
}

.quantity-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    pointer-events: none;
    background: #e9ecef;
}

.quantity {
    font-weight: 700;
    min-width: 2rem;
    text-align: center;
    font-size: 1rem;
    color: var(--text-dark);
    padding: 0 0.25rem;
}

.unit-price {
    display: block;
    color: var(--text-light);
    font-size: clamp(0.75rem, 2vw, 0.8rem);
    margin-bottom: 0.2rem;
}

.item-price-desktop .total-price {
    display: block;
    font-weight: 700;
    color: var(--text-dark);
    font-size: clamp(0.95rem, 2.8vw, 1.1rem);
}

.remove-item-btn {
    background: rgba(229, 62, 62, 0.1);
    border: 1px solid rgba(229, 62, 62, 0.2);
    color: var(--danger-color);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 10px;
    transition: all 0.2s ease;
    min-width: 44px;
    min-height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.remove-item-btn:hover {
    background-color: rgba(229, 62, 62, 0.15);
    transform: scale(1.1);
}

.remove-item-btn:active {
    transform: scale(0.95);
}

.cart-summary {
    position: sticky;
    top: max(calc(env(safe-area-inset-top) + 1rem), 1rem);
}

.summary-card {
    background: white;
    border-radius: 20px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    padding: clamp(1.25rem, 4vw, 1.5rem);
    border: 1px solid #f0f0f0;
}

.summary-card h3 {
    margin-bottom: 1.25rem;
    font-size: clamp(1.2rem, 3.5vw, 1.3rem);
    color: var(--text-dark);
    font-weight: 700;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f0f0f0;
}

.summary-line {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 0.875rem;
    color: var(--text-dark);
    font-size: clamp(0.9rem, 2.5vw, 1rem);
    padding: 0.5rem 0;
}

.summary-line span:first-child {
    color: var(--text-light);
}

.delivery-fee {
    color: var(--success-color);
    font-weight: 700;
    font-size: clamp(0.95rem, 2.5vw, 1.05rem);
}

.discount-line {
    color: var(--success-color);
    background: rgba(40, 167, 69, 0.05);
    padding: 0.75rem;
    border-radius: 10px;
    margin: 0.5rem 0;
}

.discount-amount {
    color: var(--success-color);
    font-weight: 700;
    font-size: clamp(1rem, 2.5vw, 1.1rem);
}

.summary-divider {
    height: 2px;
    background: linear-gradient(90deg, transparent, #e9ecef, transparent);
    margin: 1.25rem 0;
    border: none;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: clamp(1.1rem, 3vw, 1.3rem);
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    padding: 1rem;
    background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(255, 184, 48, 0.05));
    border-radius: 12px;
    border: 1px solid rgba(255, 107, 53, 0.1);
}

.checkout-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    padding: clamp(1rem, 3vw, 1.25rem);
    border-radius: 14px;
    font-weight: 700;
    font-size: clamp(1rem, 3vw, 1.1rem);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    min-height: 56px; /* Touch target pour mobile */
    box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
    position: relative;
    overflow: hidden;
}

.checkout-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.checkout-btn:hover::before {
    left: 100%;
}

.checkout-btn:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
}

.checkout-btn:active {
    transform: translateY(-1px);
}

.checkout-icon {
    font-size: 1.3rem;
    transition: transform 0.3s ease;
}

.checkout-btn:hover .checkout-icon {
    transform: translateX(4px);
}

.security-info {
    text-align: center;
    color: var(--text-light);
    font-size: clamp(0.8rem, 2.2vw, 0.85rem);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    padding-top: 0.75rem;
    border-top: 1px solid #f0f0f0;
    margin-top: 0.75rem;
}

.security-icon {
    font-size: 1rem;
}

/* Modal personnalisé */
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 1050;
    display: none;
}

.custom-modal-backdrop {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
}

.custom-modal-dialog {
    position: relative;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 1rem;
}

.custom-modal-content {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    max-width: 400px;
    width: 100%;
    animation: modalFadeIn 0.3s ease-out;
}

@keyframes modalFadeIn {
    from {
        opacity: 0;
        transform: scale(0.9) translateY(-50px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.custom-modal-header {
    padding: 1.5rem 1.5rem 0 1.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    border-bottom: 1px solid #eee;
    margin-bottom: 1rem;
    padding-bottom: 1rem;
}

.custom-modal-header h3 {
    margin: 0;
    color: var(--text-dark);
    font-size: 1.3rem;
}

.custom-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #999;
    padding: 0;
    width: 30px;
    height: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: var(--transition);
}

.custom-modal-close:hover {
    background-color: #f5f5f5;
    color: #333;
}

.custom-modal-body {
    padding: 0 1.5rem 1rem;
}

.custom-modal-body p {
    margin-bottom: 0.5rem;
    color: var(--text-dark);
}

.custom-modal-footer {
    padding: 1rem 1.5rem 1.5rem;
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    border-top: 1px solid #eee;
    margin-top: 1rem;
    padding-top: 1rem;
}

.btn {
    padding: 0.5rem 1rem;
    border-radius: var(--border-radius);
    cursor: pointer;
    font-weight: 500;
    transition: var(--transition);
    border: none;
}

.btn-outline {
    background: white;
    border: 1px solid #ddd;
    color: var(--text-dark);
}

.btn-outline:hover {
    background-color: #f5f5f5;
}

.btn-danger {
    background-color: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Responsive pour tablette */
@media (max-width: 1024px) {
    .cart-content {
        grid-template-columns: 1fr minmax(280px, 340px);
        gap: 1.5rem;
    }
}

/* Responsive pour mobile */
@media (max-width: 768px) {
    .cart-page {
        padding: max(calc(env(safe-area-inset-top) + 0.5rem), 0.5rem) 0.75rem 1.25rem;
    }

    .cart-content {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .cart-items-header {
        padding: 0 0.25rem;
        margin-bottom: 1rem;
    }

    .cart-items-header h3 {
        font-size: 1.3rem;
    }

    .clear-cart-btn {
        padding: 0.4rem 0.75rem;
        font-size: 0.85rem;
    }

    .cart-item {
        padding: 0.875rem;
        gap: 0.75rem;
        border-radius: 12px;
        margin-bottom: 0.75rem;
    }

    .item-image {
        width: 80px;
        height: 80px;
        min-width: 80px;
        min-height: 80px;
    }

    .item-header-row {
        margin-bottom: 0.3rem;
    }

    .item-price-mobile {
        display: block;
    }

    .item-price-desktop {
        display: none;
    }

    .item-actions {
        gap: 0.5rem;
        padding-top: 0.5rem;
        margin-top: 0.5rem;
    }

    .quantity-controls {
        gap: 0.4rem;
        padding: 0.25rem;
    }

    .quantity-btn {
        width: 38px;
        height: 38px;
        font-size: 1rem;
    }

    .quantity {
        font-size: 0.95rem;
        min-width: 1.75rem;
    }

    .remove-item-btn {
        padding: 0.4rem;
        font-size: 1rem;
    }

    .cart-summary {
        order: -1;
        position: static;
        margin-bottom: 1rem;
    }

    .summary-card {
        border-radius: 14px;
        padding: 1.25rem;
    }

    .checkout-btn {
        min-height: 50px;
        padding: 0.875rem;
        font-size: 1rem;
    }

    .commerce-closed-notice {
        padding: 0.4rem 0.625rem;
        margin-top: 0.4rem;
    }

    .notice-icon {
        font-size: 0.9rem;
    }

    .notice-text {
        font-size: 0.75rem;
    }
}

/* Responsive pour très petits écrans (webview mobile) */
@media (max-width: 480px) {
    .cart-page {
        padding: max(calc(env(safe-area-inset-top) + 0.5rem), 0.5rem) 0.5rem 1rem;
    }

    .cart-title {
        font-size: 1.5rem;
    }

    .cart-subtitle {
        font-size: 0.85rem;
    }

    .cart-item {
        padding: 0.75rem;
        gap: 0.625rem;
        margin-bottom: 0.625rem;
    }

    .item-image {
        width: 75px;
        height: 75px;
        min-width: 75px;
        min-height: 75px;
    }

    .item-name {
        font-size: 0.9rem;
        -webkit-line-clamp: 2;
    }

    .item-commerce {
        font-size: 0.75rem;
        padding: 0.15rem 0.4rem;
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        font-size: 0.95rem;
    }

    .quantity {
        font-size: 0.9rem;
        min-width: 1.5rem;
    }

    .item-price-mobile .total-price {
        font-size: 1rem;
    }

    .summary-card {
        padding: 1rem;
    }

    .checkout-btn {
        padding: 0.75rem;
        font-size: 0.95rem;
        min-height: 48px;
    }

    .commerce-closed-notice {
        padding: 0.4rem 0.5rem;
    }

    .notice-text {
        font-size: 0.7rem;
    }
}

@media (max-width: 360px) {
    .cart-item {
        padding: 0.625rem;
        gap: 0.5rem;
    }

    .item-image {
        width: 70px;
        height: 70px;
        min-width: 70px;
        min-height: 70px;
    }

    .item-name {
        font-size: 0.85rem;
    }

    .quantity-btn {
        width: 34px;
        height: 34px;
        font-size: 0.9rem;
    }

    .checkout-btn {
        padding: 0.7rem;
        font-size: 0.9rem;
        min-height: 46px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Event listeners pour les boutons de quantité
    document.querySelectorAll('.quantity-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.getAttribute('data-product-id');
            const newQuantity = parseInt(this.getAttribute('data-quantity'));

            updateQuantity(productId, newQuantity);
        });
    });

    // Event listeners pour les boutons de suppression
    document.querySelectorAll('.remove-item-btn').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();

            const productId = this.getAttribute('data-product-id');
            const cartItemId = this.getAttribute('data-cart-item-id');

            if (productId) {
                removeItem(productId);
            } else if (cartItemId) {
                removeCartItem(cartItemId);
            }
        });
    });

    // Event listener pour le bouton vider le panier
    const clearCartBtn = document.getElementById('clearCartBtn');
    if (clearCartBtn) {
        clearCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            clearCart();
        });
    }

    // Event listener pour la confirmation de vidage
    const confirmClearCartBtn = document.getElementById('confirmClearCartBtn');
    if (confirmClearCartBtn) {
        confirmClearCartBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            confirmClearCart();
        });
    }

    // Event listeners pour fermer le modal
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelClearBtn = document.getElementById('cancelClearBtn');
    const modalBackdrop = document.querySelector('.custom-modal-backdrop');

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }
    if (cancelClearBtn) {
        cancelClearBtn.addEventListener('click', closeModal);
    }
    if (modalBackdrop) {
        modalBackdrop.addEventListener('click', closeModal);
    }

    // Fermer le modal avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeModal();
        }
    });
});

// Fonctions pour gérer le panier
function updateQuantity(productId, newQuantity) {
    if (newQuantity < 0) return;

    console.log('Updating quantity:', { productId, newQuantity });

    fetch(`/cart/update/${productId}`, {
        method: 'PATCH',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: newQuantity })
    })
    .then(response => {
        console.log('Update response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Update response data:', data);

        if (data.success) {
            if (newQuantity === 0) {
                document.getElementById(`cart-item-${productId}`).remove();
            } else {
                location.reload(); // Recharger pour mettre à jour les totaux
            }
            updateCartDisplay(data.cart);
        } else {
            console.error('Update failed:', data.message);
            alert(data.message || 'Erreur lors de la mise à jour');
        }
    })
    .catch(error => {
        console.error('Update error:', error);
        alert(`Erreur technique: ${error.message}`);
    });
}

function removeItem(productId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

    console.log('Removing item:', productId);

    fetch(`/cart/remove/${productId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Remove response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Remove response data:', data);

        if (data.success) {
            document.getElementById(`cart-item-${productId}`).remove();
            updateCartDisplay(data.cart);

            // Si le panier est vide, recharger la page
            if (data.cart.total_items === 0) {
                location.reload();
            }
        } else {
            console.error('Remove failed:', data.message);
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Remove error:', error);
        alert(`Erreur technique: ${error.message}`);
    });
}

function removeCartItem(cartItemId) {
    if (!confirm('Êtes-vous sûr de vouloir supprimer cet article ?')) return;

    console.log('Removing cart item:', cartItemId);

    fetch(`/cart/remove-item/${cartItemId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Remove cart item response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Remove cart item response data:', data);

        if (data.success) {
            document.getElementById(`cart-item-${cartItemId}`).remove();
            updateCartDisplay(data.cart);

            // Si le panier est vide, recharger la page
            if (data.cart.total_items === 0) {
                location.reload();
            }
        } else {
            console.error('Remove cart item failed:', data.message);
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Remove cart item error:', error);
        alert(`Erreur technique: ${error.message}`);
    });
}

function clearCart() {
    const modal = document.getElementById('clearCartModal');
    if (modal) {
        modal.style.display = 'block';
        // Empêcher le scroll du body
        document.body.style.overflow = 'hidden';
    }
}

function closeModal() {
    const modal = document.getElementById('clearCartModal');
    if (modal) {
        modal.style.display = 'none';
        // Restaurer le scroll du body
        document.body.style.overflow = '';
    }
}

function confirmClearCart() {
    console.log('Clearing cart');

    // Fermer le modal d'abord
    closeModal();

    fetch('/cart/clear', {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('Clear response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Clear response data:', data);

        if (data.success) {
            location.reload();
        } else {
            console.error('Clear failed:', data.message);
            alert(data.message || 'Erreur lors de la suppression');
        }
    })
    .catch(error => {
        console.error('Clear error:', error);
        alert(`Erreur technique: ${error.message}`);
    });
}

function updateCartDisplay(cart) {
    // Mettre à jour les totaux
    if (document.getElementById('cartSubtotal')) {
        document.getElementById('cartSubtotal').textContent = cart.formatted_subtotal;
    }
    if (document.getElementById('cartTotal')) {
        document.getElementById('cartTotal').textContent = cart.formatted_final_total;
    }

    // Mettre à jour les frais de livraison
    if (document.querySelector('.delivery-fee')) {
        document.querySelector('.delivery-fee').textContent = cart.formatted_delivery_fee;
    }

    // Mettre à jour la remise si elle existe
    const discountLine = document.querySelector('.discount-line');
    if (discountLine && cart.discount > 0) {
        discountLine.style.display = 'flex';
        const discountAmount = discountLine.querySelector('.discount-amount');
        if (discountAmount) {
            discountAmount.textContent = `-${cart.formatted_discount}`;
        }
    } else if (discountLine) {
        discountLine.style.display = 'none';
    }

    // Mettre à jour le header
    updateCartHeader(cart.total_items);
}

function updateCartHeader(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'block' : 'none';
    }
}
</script>
@endpush
