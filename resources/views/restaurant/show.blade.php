@extends('layouts.app')

@section('title', $commerce->name . ' - Lebayo')

@section('content')
<!-- Restaurant Header -->
<section class="restaurant-hero" style="background-image: url('{{ $commerce->header_image }}');">
    <div class="restaurant-hero-overlay">
        <div class="container">
            <div class="restaurant-hero-content">
                <div class="restaurant-info-header">
                    <div class="restaurant-logo">
                        <img src="{{ $commerce->logo_url }}" alt="{{ $commerce->name }}">
                    </div>
                    <div class="restaurant-details">
                        <h1 class="restaurant-title">{{ $commerce->name }}</h1>
                        <p class="restaurant-subtitle">{{ $commerce->description ?: $commerce->commerce_type_name }}</p>
                        
                        <div class="restaurant-badges">
                            <div class="rating-badge">
                                <span class="rating-star">⭐</span>
                                <span class="rating-value">{{ number_format(rand(32, 39) / 10, 1) }}</span>
                                <span class="rating-count">({{ rand(50, 300) }} avis)</span>
                            </div>
                            <div class="delivery-badge">
                                <span class="delivery-icon">🚚</span>
                                <span class="delivery-text">{{ rand(15, 35) }}-{{ rand(25, 45) }} min</span>
                            </div>
                            <div class="location-badge">
                                <span class="location-icon">📍</span>
                                <span class="location-text">{{ $commerce->city }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="restaurant-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $totalProducts }}</span>
                        <span class="stat-label">Produits</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($avgPrice, 0) }}F</span>
                        <span class="stat-label">Prix moyen</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $featuredProducts->count() }}</span>
                        <span class="stat-label">Spécialités</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Restaurant Menu -->
<section class="restaurant-menu">
    <div class="container">
        <div class="menu-header">
            <h2>Notre Menu</h2>
            <p>Découvrez nos délicieux plats préparés avec amour</p>
        </div>

        @if($productsByCategory->count() > 0)
            <div class="menu-categories">
                @foreach($productsByCategory as $categoryName => $products)
                    <div class="menu-category">
                        <div class="category-header">
                            <h3 class="category-title">
                                @if($products->first()->category)
                                    {{ $products->first()->category->emoji }} {{ $categoryName }}
                                @else
                                    🍽️ {{ $categoryName ?: 'Autres produits' }}
                                @endif
                            </h3>
                            <span class="category-count">{{ $products->count() }} {{ $products->count() > 1 ? 'produits' : 'produit' }}</span>
                        </div>
                        
                        <div class="products-grid">
                            @foreach($products as $product)
                                <div class="product-card {{ $product->is_featured ? 'featured' : '' }}">
                                    @if($product->is_featured)
                                        <div class="featured-badge">⭐ Spécialité</div>
                                    @endif
                                    
                                    <div class="product-image">
                                        <img src="{{ $product->image_url ?? 'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=300&h=200&fit=crop' }}" 
                                             alt="{{ $product->name }}">
                                    </div>
                                    
                                    <div class="product-info">
                                        <div class="product-header">
                                            <h4 class="product-name">{{ $product->name }}</h4>
                                            <div class="product-price">
                                                @if($product->old_price)
                                                    <span class="old-price">{{ number_format($product->old_price) }}F</span>
                                                @endif
                                                <span class="current-price">{{ number_format($product->price) }}F</span>
                                            </div>
                                        </div>
                                        
                                        @if($product->description)
                                            <p class="product-description">{{ $product->description }}</p>
                                        @endif
                                        
                                        <div class="product-meta">
                                            @if($product->preparation_time)
                                                <span class="prep-time">🕒 {{ $product->preparation_time }} min</span>
                                            @endif
                                            
                                            @if($product->stock > 0)
                                                <span class="stock-info">📦 {{ $product->stock }} {{ $product->unit }}</span>
                                            @endif
                                        </div>
                                        
                                        @if($product->is_available && $product->stock > 0)
                                            <button type="button" class="add-to-cart-btn" data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}">
                                                <span class="cart-icon">🛒</span>
                                                Ajouter au panier
                                            </button>
                                        @else
                                            <button type="button" class="add-to-cart-btn disabled" disabled>
                                                <span class="cart-icon">❌</span>
                                                Indisponible
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="empty-menu">
                <div class="empty-icon">🍽️</div>
                <h3>Menu en préparation</h3>
                <p>Ce restaurant met à jour son menu. Revenez bientôt !</p>
            </div>
        @endif
    </div>
</section>

<!-- Back to Home -->
<section class="back-section">
    <div class="container">
        <a href="{{ route('home') }}" class="back-btn">
            <span class="back-icon">←</span>
            Retour aux restaurants
        </a>
    </div>
</section>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Attacher les événements aux boutons d'ajout au panier
    const addToCartButtons = document.querySelectorAll('.add-to-cart-btn:not(.disabled)');
    
    addToCartButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            const productId = this.getAttribute('data-product-id');
            const productName = this.getAttribute('data-product-name');
            
            addToCart(productId, productName, this);
        });
    });
});

function addToCart(productId, productName, button) {
    const originalText = button.innerHTML;
    
    // Désactiver le bouton et montrer le loading
    button.disabled = true;
    button.innerHTML = '<span class="cart-icon">⏳</span> Ajout...';
    
    fetch(`/cart/add/${productId}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ quantity: 1 })
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', response.headers);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Response data:', data);
        
        if (data.success) {
            // Animation de succès
            button.innerHTML = '<span class="cart-icon">✅</span> Ajouté !';
            button.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            
            // Mettre à jour le compteur du panier dans le header
            updateCartCounter(data.cart.total_items);
            
            // Afficher une notification
            showNotification(`${productName} ajouté au panier !`, 'success');
            
            // Restaurer le bouton après 2 secondes
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.disabled = false;
            }, 2000);
        } else {
            // Erreur
            button.innerHTML = '<span class="cart-icon">❌</span> Erreur';
            button.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
            
            showNotification(data.message || 'Erreur lors de l\'ajout au panier', 'error');
            
            // Restaurer le bouton après 2 secondes
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.background = '';
                button.disabled = false;
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Erreur complète:', error);
        console.error('Stack trace:', error.stack);
        
        button.innerHTML = '<span class="cart-icon">❌</span> Erreur';
        button.style.background = 'linear-gradient(135deg, #dc3545, #c82333)';
        
        showNotification(`Erreur technique: ${error.message}`, 'error');
        
        // Restaurer le bouton après 2 secondes
        setTimeout(() => {
            button.innerHTML = originalText;
            button.style.background = '';
            button.disabled = false;
        }, 2000);
    });
}

function updateCartCounter(count) {
    const cartCount = document.querySelector('.cart-count');
    if (cartCount) {
        cartCount.textContent = count;
        cartCount.style.display = count > 0 ? 'block' : 'none';
        
        // Animation du compteur
        cartCount.style.transform = 'scale(1.2)';
        setTimeout(() => {
            cartCount.style.transform = 'scale(1)';
        }, 200);
    }
}

function showNotification(message, type = 'success') {
    // Créer la notification
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-icon">${type === 'success' ? '✅' : '❌'}</span>
            <span class="notification-message">${message}</span>
        </div>
    `;
    
    // Ajouter au body
    document.body.appendChild(notification);
    
    // Animation d'entrée
    setTimeout(() => {
        notification.classList.add('show');
    }, 100);
    
    // Supprimer après 3 secondes
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}
</script>
@endpush

@push('styles')
<style>
.add-to-cart-btn.disabled {
    background: #6c757d !important;
    cursor: not-allowed;
    opacity: 0.7;
}

.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 10000;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
    padding: 1rem 1.5rem;
    transform: translateX(400px);
    transition: transform 0.3s ease;
    border-left: 4px solid #28a745;
    max-width: 350px;
}

.notification.error {
    border-left-color: #dc3545;
}

.notification.show {
    transform: translateX(0);
}

.notification-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.notification-icon {
    font-size: 1.2rem;
}

.notification-message {
    color: var(--text-dark);
    font-weight: 500;
}

@media (max-width: 768px) {
    .notification {
        right: 10px;
        left: 10px;
        transform: translateY(-100px);
        max-width: none;
    }
    
    .notification.show {
        transform: translateY(0);
    }
}
</style>
@endpush 