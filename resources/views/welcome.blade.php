@extends('layouts.app')

@section('title', 'Lebayo - Livraison de nourriture')

@section('content')
<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content">
            <div class="hero-text">
                <h1>Découvrez les restaurants qui livrent près de chez vous</h1>
                <div class="search-container">
                    <form action="{{ route('search') }}" method="GET" class="search-form">
                        <div class="search-box">
                            <i class="search-icon">🔍</i>
                            <input type="text" 
                                   name="q" 
                                   placeholder="Rechercher un restaurant ou un produit..." 
                                   class="search-input"
                                   id="homeSearchInput"
                                   autocomplete="off">
                            <button type="submit" class="search-btn btn-red">Rechercher</button>
                        </div>
                        <div class="search-suggestions-home" id="homeSearchSuggestions"></div>
                    </form>
                </div>
            </div>
            <div class="hero-delivery-animation">
                <div class="delivery-map">
                    <!-- Point de départ (Restaurant) -->
                    <div class="delivery-point restaurant-point">
                        <div class="point-icon">🏪</div>
                        <div class="point-label">Restaurant</div>
                        <div class="point-pulse"></div>
                    </div>
                    
                    <!-- Tracé de livraison -->
                    <div class="delivery-path">
                        <svg class="path-svg" viewBox="0 0 300 200">
                            <path id="deliveryRoute" d="M 50 100 Q 150 50 250 100" 
                                  stroke="url(#pathGradient)" 
                                  stroke-width="4" 
                                  fill="none" 
                                  stroke-dasharray="8,4"/>
                            <defs>
                                <linearGradient id="pathGradient" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#FF6B35;stop-opacity:0.8" />
                                    <stop offset="50%" style="stop-color:#FFB830;stop-opacity:0.9" />
                                    <stop offset="100%" style="stop-color:#FF6B35;stop-opacity:0.8" />
                                </linearGradient>
                            </defs>
                        </svg>
                        
                        <!-- Véhicule de livraison animé -->
                        <div class="delivery-vehicle">
                            <div class="vehicle-icon">🛵</div>
                            <div class="vehicle-trail"></div>
                        </div>
                        
                        <!-- Plats en transit -->
                        <div class="food-item food-1">🍕</div>
                        <div class="food-item food-2">🍔</div>
                        <div class="food-item food-3">🍟</div>
                        <div class="food-item food-4">🥗</div>
                    </div>
                    
                    <!-- Point d'arrivée (Client) -->
                    <div class="delivery-point client-point">
                        <div class="point-icon">🏠</div>
                        <div class="point-label">Chez vous</div>
                        <div class="point-pulse"></div>
                    </div>
                    
                    <!-- Indicateurs de sécurité -->
                    <div class="security-indicators">
                        <div class="security-badge">
                            <div class="badge-icon">🔒</div>
                            <div class="badge-text">Sécurisé</div>
                            <div class="badge-glow"></div>
                        </div>
                        <div class="security-badge">
                            <div class="badge-icon">⚡</div>
                            <div class="badge-text">Rapide</div>
                            <div class="badge-glow"></div>
                        </div>
                        <div class="security-badge">
                            <div class="badge-icon">📍</div>
                            <div class="badge-text">Tracking</div>
                            <div class="badge-glow"></div>
                        </div>
                    </div>
                    
                    <!-- Statut de livraison -->
                    <div class="delivery-status">
                        <div class="status-item" data-status="0">
                            <div class="status-dot"></div>
                            <div class="status-text">Commande confirmée</div>
                        </div>
                        <div class="status-item" data-status="1">
                            <div class="status-dot"></div>
                            <div class="status-text">En préparation</div>
                        </div>
                        <div class="status-item" data-status="2">
                            <div class="status-dot"></div>
                            <div class="status-text">En livraison</div>
                        </div>
                        <div class="status-item" data-status="3">
                            <div class="status-dot"></div>
                            <div class="status-text">Livré</div>
                        </div>
                    </div>
                    
                    <!-- Particules de confiance -->
                    <div class="trust-particles">
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                        <div class="particle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Commerce Types Quick Navigation -->
<section class="commerce-types-quick-nav">
    <div class="container">
        <div class="commerce-types-quick-grid">
            @foreach($commerceTypes as $type)
            <a href="{{ route('commerce-type.show', $type) }}" class="commerce-type-quick-item">
                <div class="commerce-type-icon" style="background-image: url('{{ $type->header_image }}');"></div>
                <div class="commerce-type-info">
                    <span class="commerce-type-name">{{ $type->name }}</span>
                    <span class="commerce-type-count">{{ $type->commerces->count() }}{{ $type->commerces->count() >= 5 ? '+' : '' }}</span>
                </div>
            </a>
            @endforeach
            <a href="{{ route('errand.create') }}" class="commerce-type-quick-item">
                <div class="commerce-type-icon" style="background-image: url('{{asset('images/faire-une-course-lebayo.jpg')}}');"></div>
                <div class="commerce-type-info">
                    <span class="commerce-type-name">Faire une course</span>
                    <span class="commerce-type-count">Livraison express</span>
                </div>
            </a>
        </div>
    </div>
</section>

<!-- Product from Type section-->
@foreach($commerceTypes as $index => $type)
    @if($type->commerces->count() > 0)
    <section class="grocery-section">
        <div class="container">
            <div class="section-header">
                <h2>{{ $type->name }}</h2>
                <p>{{ $type->description }}</p>
            </div>
    
            <div class="grocery-grid">
                @foreach($type->commerces->take(3) as $commerce)
                <a href="{{ route('commerce.show', $commerce) }}" class="grocery-category-card">
                    <div class="grocery-image" style="background-image: url('{{ $commerce->placeholder_image }}');">
                        <div class="grocery-overlay">
                            @if($commerce->is_active)
                                <div class="grocery-badge">✅ Ouvert</div>
                            @else
                                <div class="grocery-badge">❌ Fermé</div>
                            @endif
                        </div>
                    </div>
                    <div class="grocery-info">
                        <h3>{{ $commerce->name }}</h3>
                        <p>{{ Str::limit($commerce->description ?? 'Commerce local de qualité', 50) }}</p>
                        <div class="grocery-stats">
                            <span>📍 {{ $commerce->city }}</span>
                            <span class="delivery-time">🛍️ {{ $commerce->products_count ?? 0 }} produits</span>
                        </div>
                        @if($commerce->phone)
                            <div class="commerce-contact">
                                <span class="phone-info">📞 {{ $commerce->phone }}</span>
                            </div>
                        @endif
                    </div>
                </a>
                @endforeach
            </div>
    
            @if($type->commerces->count() >= 3)
            <div class="grocery-cta">
                <a href="{{ route('search') }}?q=supermarché" class="grocery-btn">
                    <span class="grocery-btn-icon">🛒</span>
                    Voir plus
                </a>
            </div>
            @endif
        </div>
    </section>
    @endif
@endforeach

<!-- Popular Restaurants Section -->
{{-- <section class="restaurants-section">
    <div class="container">
        <div class="section-header">
            <h2>Featured Restaurants</h2>
            <p>Découvrez les restaurants les plus appréciés de votre région</p>
        </div>

        <div class="featured-restaurants-grid">
            @forelse($popularRestaurants as $index => $restaurant)
            <a href="{{ route('commerce.show', $restaurant) }}" class="featured-restaurant-card">
                <div class="restaurant-image-bg" style="background-image: url('{{ $restaurant->placeholder_image ?? $restaurant->logo_url }}');">
                    <div class="restaurant-overlay">
                        @if($index === 1)
                            <div class="exclusive-badge">
                                <i class="crown-icon">👑</i>
                                Exclusif
                            </div>
                        @endif
                        
                        @if($index === 0 || $index === 2 || $index === 4)
                            <div class="promotion-badge">
                                <div class="promo-text">jusqu'à {{ number_format(rand(1500, 3000)) }}F</div>
                                <div class="promo-discount">{{ rand(20, 50) }}% OFF</div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="restaurant-card-info">
                    <div class="restaurant-header">
                        <h3 class="restaurant-name">{{ $restaurant->name }}</h3>
                        <div class="restaurant-rating">
                            <span class="rating-star">⭐</span>
                            <span class="rating-value">{{ number_format($restaurant->rating, 1) }}</span>
                        </div>
                    </div>
                    
                    <p class="restaurant-description">{{ $restaurant->description ?: $restaurant->commerce_type_name }}</p>
                    
                    <div class="restaurant-meta">
                        <div class="meta-item">
                            <span class="meta-icon">📍</span>
                            <span class="meta-text">{{ $restaurant->city }}</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🕒</span>
                            <span class="meta-text">{{ $restaurant->estimated_delivery_time }} min</span>
                        </div>
                    </div>
                </div>
            </a>
            @empty
            <div class="no-restaurants">
                <p>Aucun restaurant disponible pour le moment.</p>
            </div>
            @endforelse
        </div>
    </div>
</section> --}}

<!-- Faire une Course Section -->
{{-- <section class="grocery-section">
    <div class="container">
        <div class="section-header">
            <h2>Faire une Course</h2>
            <p>Commandez vos produits essentiels en quelques clics</p>
        </div>

        <div class="grocery-grid">
            <div class="grocery-category-card">
                <div class="grocery-image" style="background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?w=400&h=300&fit=crop');">
                    <div class="grocery-overlay">
                        <div class="grocery-badge">🥕 Frais</div>
                    </div>
                </div>
                <div class="grocery-info">
                    <h3>Fruits & Légumes</h3>
                    <p>Produits frais de saison</p>
                    <div class="grocery-stats">
                        <span>200+ produits</span>
                        <span class="delivery-time">🚚 30 min</span>
                    </div>
                </div>
            </div>

            <div class="grocery-category-card">
                <div class="grocery-image" style="background-image: url('https://images.unsplash.com/photo-1586201375761-83865001e31c?w=400&h=300&fit=crop');">
                    <div class="grocery-overlay">
                        <div class="grocery-badge">🥛 Frais</div>
                    </div>
                </div>
                <div class="grocery-info">
                    <h3>Produits Laitiers</h3>
                    <p>Lait, fromages, yaourts</p>
                    <div class="grocery-stats">
                        <span>80+ produits</span>
                        <span class="delivery-time">🚚 25 min</span>
                    </div>
                </div>
            </div>

            <div class="grocery-category-card">
                <div class="grocery-image" style="background-image: url('https://images.unsplash.com/photo-1574484284002-952d92456975?w=400&h=300&fit=crop');">
                    <div class="grocery-overlay">
                        <div class="grocery-badge">🍞 Artisanal</div>
                    </div>
                </div>
                <div class="grocery-info">
                    <h3>Boulangerie</h3>
                    <p>Pain frais et viennoiseries</p>
                    <div class="grocery-stats">
                        <span>50+ produits</span>
                        <span class="delivery-time">🚚 20 min</span>
                    </div>
                </div>
            </div>

            <div class="grocery-category-card">
                <div class="grocery-image" style="background-image: url('https://images.unsplash.com/photo-1563379091339-03246963d25a?w=400&h=300&fit=crop');">
                    <div class="grocery-overlay">
                        <div class="grocery-badge">🥫 Stock</div>
                    </div>
                </div>
                <div class="grocery-info">
                    <h3>Épicerie</h3>
                    <p>Conserves, pâtes, riz</p>
                    <div class="grocery-stats">
                        <span>300+ produits</span>
                        <span class="delivery-time">🚚 35 min</span>
                    </div>
                </div>
            </div>

            <div class="grocery-category-card">
                <div class="grocery-image" style="background-image: url('https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?w=400&h=300&fit=crop');">
                    <div class="grocery-overlay">
                        <div class="grocery-badge">🥤 Rafraîchissant</div>
                    </div>
                </div>
                <div class="grocery-info">
                    <h3>Boissons</h3>
                    <p>Eaux, jus, sodas</p>
                    <div class="grocery-stats">
                        <span>150+ produits</span>
                        <span class="delivery-time">🚚 25 min</span>
                    </div>
                </div>
            </div>

            <div class="grocery-category-card">
                <div class="grocery-image" style="background-image: url('https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=400&h=300&fit=crop');">
                    <div class="grocery-overlay">
                        <div class="grocery-badge">🧴 Entretien</div>
                    </div>
                </div>
                <div class="grocery-info">
                    <h3>Hygiène & Entretien</h3>
                    <p>Produits d'hygiène et ménage</p>
                    <div class="grocery-stats">
                        <span>120+ produits</span>
                        <span class="delivery-time">🚚 40 min</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grocery-cta">
            <a href="{{ route('search') }}?q=supermarché" class="grocery-btn">
                <span class="grocery-btn-icon">🛒</span>
                Commencer mes courses
            </a>
        </div>
    </div>
</section> --}}


<script>
// Autocomplétion pour la recherche sur la page d'accueil
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('homeSearchInput');
    const suggestionsContainer = document.getElementById('homeSearchSuggestions');
    let currentTimeout;
    
    if (searchInput && suggestionsContainer) {
        searchInput.addEventListener('input', function() {
            const query = this.value.trim();
            
            // Annuler la recherche précédente
            if (currentTimeout) {
                clearTimeout(currentTimeout);
            }
            
            // Masquer les suggestions si la requête est trop courte
            if (query.length < 2) {
                suggestionsContainer.style.display = 'none';
                return;
            }
            
            // Délai avant la recherche
            currentTimeout = setTimeout(() => {
                fetch(`{{ route('search.autocomplete') }}?q=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        displayHomeSuggestions(data);
                    })
                    .catch(error => {
                        console.error('Erreur lors de la recherche:', error);
                        suggestionsContainer.style.display = 'none';
                    });
            }, 300);
        });
        
        function displayHomeSuggestions(suggestions) {
            if (suggestions.length === 0) {
                suggestionsContainer.style.display = 'none';
                return;
            }
            
            const html = suggestions.map(item => `
                <div class="suggestion-item" data-url="${item.url}">
                    <div class="suggestion-icon">${item.icon}</div>
                    <div class="suggestion-content">
                        <div class="suggestion-name">${item.name}</div>
                        <div class="suggestion-subtitle">${item.subtitle}</div>
                    </div>
                </div>
            `).join('');
            
            suggestionsContainer.innerHTML = html;
            suggestionsContainer.style.display = 'block';
            
            // Ajouter les événements de clic
            suggestionsContainer.querySelectorAll('.suggestion-item').forEach(item => {
                item.addEventListener('click', function() {
                    window.location.href = this.dataset.url;
                });
            });
        }
        
        // Masquer les suggestions quand on clique ailleurs
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.search-container')) {
                suggestionsContainer.style.display = 'none';
            }
        });
    }
});
</script>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Animation du statut de livraison
    const statusItems = document.querySelectorAll('.status-item');
    let currentStatusIndex = 0;
    
    // Fonction pour mettre à jour le statut
    function updateDeliveryStatus() {
        // Retirer la classe active de tous les éléments
        statusItems.forEach(item => item.classList.remove('active'));
        
        // Ajouter la classe active jusqu'à l'index actuel
        for (let i = 0; i <= currentStatusIndex; i++) {
            if (statusItems[i]) {
                statusItems[i].classList.add('active');
            }
        }
        
        // Passer au statut suivant
        currentStatusIndex++;
        
        // Réinitialiser après le dernier statut
        if (currentStatusIndex >= statusItems.length) {
            setTimeout(() => {
                statusItems.forEach(item => item.classList.remove('active'));
                currentStatusIndex = 0;
            }, 2000); // Pause de 2 secondes avant de recommencer
        }
    }
    
    // Initialiser avec le premier statut
    if (statusItems.length > 0) {
        statusItems[0].classList.add('active');
        
        // Mettre à jour le statut toutes les 3 secondes
        setInterval(updateDeliveryStatus, 3000);
    }
    
    // Animation des badges de sécurité avec des délais différents
    const securityBadges = document.querySelectorAll('.security-badge');
    securityBadges.forEach((badge, index) => {
        badge.style.animationDelay = `${index * 0.5}s`;
    });
    
    // Effet de vibration subtile sur hover des points de livraison
    const deliveryPoints = document.querySelectorAll('.delivery-point');
    deliveryPoints.forEach(point => {
        point.addEventListener('mouseenter', function() {
            const icon = this.querySelector('.point-icon');
            icon.style.animation = 'pointBounce 0.6s ease-in-out 3';
        });
        
        point.addEventListener('mouseleave', function() {
            const icon = this.querySelector('.point-icon');
            icon.style.animation = 'pointBounce 2s ease-in-out infinite';
        });
    });
    
    // Animation progressive des particules de confiance
    const particles = document.querySelectorAll('.particle');
    particles.forEach((particle, index) => {
        particle.style.animationDelay = `${index}s`;
        particle.style.left = `${Math.random() * 80 + 10}%`;
        particle.style.top = `${Math.random() * 80 + 10}%`;
    });
    
    // Effet de parallax léger sur le mouvement de la souris
    const deliveryMap = document.querySelector('.delivery-map');
    if (deliveryMap) {
        document.addEventListener('mousemove', function(e) {
            const rect = deliveryMap.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            
            if (x > 0 && x < rect.width && y > 0 && y < rect.height) {
                const moveX = (x / rect.width - 0.5) * 10;
                const moveY = (y / rect.height - 0.5) * 10;
                
                const vehicle = deliveryMap.querySelector('.delivery-vehicle');
                if (vehicle) {
                    vehicle.style.transform = `translate(${moveX}px, ${moveY}px)`;
                }
                
                const foodItems = deliveryMap.querySelectorAll('.food-item');
                foodItems.forEach((item, index) => {
                    const delay = index * 0.1;
                    setTimeout(() => {
                        item.style.transform = `translate(${moveX * 0.5}px, ${moveY * 0.5}px)`;
                    }, delay * 100);
                });
            }
        });
        
        document.addEventListener('mouseleave', function() {
            const vehicle = deliveryMap.querySelector('.delivery-vehicle');
            if (vehicle) {
                vehicle.style.transform = '';
            }
            
            const foodItems = deliveryMap.querySelectorAll('.food-item');
            foodItems.forEach(item => {
                item.style.transform = '';
            });
        });
    }
    
    // Effet sonore visuel lors du passage d'étapes (simulation)
    function triggerVisualFeedback() {
        const currentActiveStatus = document.querySelector('.status-item.active:last-of-type');
        if (currentActiveStatus) {
            // Effet de flash sur le statut actuel
            currentActiveStatus.style.background = 'rgba(255, 107, 53, 0.1)';
            setTimeout(() => {
                currentActiveStatus.style.background = '';
            }, 300);
            
            // Effet de pulsation sur les badges de sécurité
            securityBadges.forEach(badge => {
                badge.style.transform = 'scale(1.1)';
                setTimeout(() => {
                    badge.style.transform = '';
                }, 200);
            });
        }
    }
    
    // Déclencher l'effet visuel à chaque mise à jour de statut
    const originalUpdateStatus = updateDeliveryStatus;
    updateDeliveryStatus = function() {
        originalUpdateStatus();
        triggerVisualFeedback();
    };
    
    // Fonction pour redémarrer les animations si elles se figent
    function restartAnimations() {
        const foodItems = document.querySelectorAll('.food-item');
        const vehicle = document.querySelector('.delivery-vehicle');
        
        foodItems.forEach((item, index) => {
            // Forcer la position initiale
            item.style.left = '10%';
            item.style.top = '30%';
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8) translateZ(0)';
            
            // Redémarrer l'animation
            item.style.animation = 'none';
            setTimeout(() => {
                item.style.animation = `foodTravel 12s ease-in-out infinite`;
                item.style.animationDelay = `${index * 3}s`;
            }, 50);
        });
        
        if (vehicle) {
            vehicle.style.left = '10%';
            vehicle.style.top = '30%';
            vehicle.style.animation = 'none';
            setTimeout(() => {
                vehicle.style.animation = 'vehicleMove 8s ease-in-out infinite';
            }, 50);
        }
    }
    
    // Vérifier et redémarrer les animations toutes les 15 secondes
    setInterval(() => {
        const foodItems = document.querySelectorAll('.food-item');
        let hasStuckItem = false;
        
        foodItems.forEach(item => {
            const rect = item.getBoundingClientRect();
            const parentRect = item.parentElement.getBoundingClientRect();
            
            // Si un élément est resté dans le coin supérieur gauche trop longtemps
            if (rect.left < parentRect.left + 50 && rect.top < parentRect.top + 50) {
                hasStuckItem = true;
            }
        });
        
        if (hasStuckItem) {
            console.log('Animation figée détectée, redémarrage...');
            restartAnimations();
        }
    }, 15000);
    
    // Redémarrer les animations lors du focus/retour sur la page
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            setTimeout(restartAnimations, 500);
        }
    });
});
</script>
@endpush

