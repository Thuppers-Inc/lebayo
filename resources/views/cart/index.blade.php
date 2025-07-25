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
                            <div class="cart-item" id="cart-item-{{ $item->product->id }}">
                                <div class="item-image">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" loading="lazy">
                                </div>
                                
                                <div class="item-details">
                                    <div class="item-info">
                                        <h4 class="item-name">{{ $item->product->name }}</h4>
                                        <p class="item-commerce">{{ $item->product->commerce->name }}</p>
                                        @if($item->product->description)
                                            <p class="item-description">{{ Str::limit($item->product->description, 80) }}</p>
                                        @endif
                                    </div>
                                    
                                    <div class="item-actions">
                                        <div class="quantity-controls">
                                            <button type="button" class="quantity-btn minus" data-product-id="{{ $item->product->id }}" data-quantity="{{ $item->quantity - 1 }}">-</button>
                                            <span class="quantity">{{ $item->quantity }}</span>
                                            <button type="button" class="quantity-btn plus" data-product-id="{{ $item->product->id }}" data-quantity="{{ $item->quantity + 1 }}">+</button>
                                        </div>
                                        
                                        <div class="item-price">
                                            <span class="unit-price">{{ $item->formatted_price }}</span>
                                            <span class="total-price">{{ $item->formatted_subtotal }}</span>
                                        </div>
                                        
                                        <button type="button" class="remove-item-btn" data-product-id="{{ $item->product->id }}">
                                            🗑️
                                        </button>
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
                        
                        <a href="{{ route('checkout.index') }}" class="checkout-btn">
                            <span>Passer la commande</span>
                            <span class="checkout-icon">→</span>
                        </a>
                        
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
    padding: 2rem 0;
    min-height: 70vh;
}

.cart-header {
    margin-bottom: 2rem;
}

.cart-title-section {
    text-align: center;
    margin-top: 1rem;
}

.cart-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.cart-subtitle {
    color: var(--text-light);
    font-size: 1.1rem;
}

.empty-cart {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-cart-icon {
    font-size: 4rem;
    margin-bottom: 1rem;
}

.empty-cart h2 {
    font-size: 1.8rem;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.empty-cart p {
    color: var(--text-light);
    margin-bottom: 2rem;
}

.cart-content {
    display: grid;
    grid-template-columns: 1fr 350px;
    gap: 2rem;
    align-items: start;
}

.cart-items-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--light-bg);
}

.clear-cart-btn {
    background: none;
    border: none;
    color: var(--danger-color);
    font-size: 0.9rem;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: var(--transition);
}

.clear-cart-btn:hover {
    background-color: rgba(229, 62, 62, 0.1);
}

.cart-item {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--box-shadow);
    margin-bottom: 1rem;
    transition: var(--transition);
}

.cart-item:hover {
    box-shadow: var(--box-shadow-lg);
}

.item-image {
    width: 80px;
    height: 80px;
    border-radius: var(--border-radius);
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
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.25rem;
    color: var(--text-dark);
}

.item-commerce {
    color: var(--primary-color);
    font-size: 0.9rem;
    font-weight: 500;
    margin-bottom: 0.25rem;
}

.item-description {
    color: var(--text-light);
    font-size: 0.85rem;
    margin: 0;
}

.item-actions {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.quantity-btn {
    width: 32px;
    height: 32px;
    border: 1px solid #ddd;
    background: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    font-weight: 600;
    transition: var(--transition);
}

.quantity-btn:hover {
    border-color: var(--primary-color);
    color: var(--primary-color);
}

.quantity {
    font-weight: 600;
    min-width: 2rem;
    text-align: center;
}

.item-price {
    text-align: right;
}

.unit-price {
    display: block;
    color: var(--text-light);
    font-size: 0.85rem;
}

.total-price {
    display: block;
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.1rem;
}

.remove-item-btn {
    background: none;
    border: none;
    color: var(--danger-color);
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 8px;
    transition: var(--transition);
}

.remove-item-btn:hover {
    background-color: rgba(229, 62, 62, 0.1);
}

.cart-summary {
    position: sticky;
    top: 2rem;
}

.summary-card {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--box-shadow);
    padding: 1.5rem;
}

.summary-card h3 {
    margin-bottom: 1rem;
    font-size: 1.3rem;
    color: var(--text-dark);
}

.summary-line {
    display: flex;
    justify-content: space-between;
    margin-bottom: 0.75rem;
    color: var(--text-dark);
}

.delivery-fee {
    color: var(--success-color);
    font-weight: 600;
}

.discount-line {
    color: var(--success-color);
}

.discount-amount {
    color: var(--success-color);
    font-weight: 600;
}

.summary-divider {
    height: 1px;
    background-color: #eee;
    margin: 1rem 0;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    font-size: 1.2rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
}

.checkout-btn {
    width: 100%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    padding: 1rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    font-size: 1.1rem;
    cursor: pointer;
    transition: var(--transition);
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.checkout-btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-lg);
}

.security-info {
    text-align: center;
    color: var(--text-light);
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
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

@media (max-width: 768px) {
    .cart-content {
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    
    .cart-item {
        flex-direction: column;
        text-align: center;
    }
    
    .item-details {
        flex-direction: column;
        gap: 1rem;
    }
    
    .item-actions {
        justify-content: space-between;
        width: 100%;
    }
    
    .cart-summary {
        order: -1;
        position: static;
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
            removeItem(productId);
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