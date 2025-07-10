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
            <div class="hero-image">
                <div class="delivery-illustration">
                    <div class="delivery-person">🏍️</div>
                    <div class="floating-food pizza">🍕</div>
                    <div class="floating-food burger">🍔</div>
                    <div class="floating-food">🍟</div>
                    <div class="delivery-bag">🛍️</div>
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
                    <span class="commerce-type-count">{{ $type->commerces->count() }}{{ $type->commerces->count() >= 4 ? '+' : '' }}</span>
                </div>
            </a>
            @endforeach
            <a href="#" class="commerce-type-quick-item">
                <div class="commerce-type-icon" style="background-image: url('');"></div>
                <div class="commerce-type-info">
                    <span class="commerce-type-name">Faire un course</span>
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
                <a href="{{ route('restaurant.show', $commerce) }}" class="grocery-category-card">
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
            <a href="{{ route('restaurant.show', $restaurant) }}" class="featured-restaurant-card">
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
                            <span class="rating-value">{{ number_format(rand(32, 39) / 10, 1) }}</span>
                        </div>
                    </div>
                    
                    <p class="restaurant-description">{{ $restaurant->description ?: $restaurant->commerce_type_name }}</p>
                    
                    <div class="restaurant-meta">
                        <div class="meta-item">
                            <span class="meta-icon">📍</span>
                            <span class="meta-text">{{ $restaurant->city }}</span>
                            <span class="meta-distance">{{ rand(1, 5) }} km</span>
                        </div>
                        <div class="meta-item">
                            <span class="meta-icon">🕒</span>
                            <span class="meta-text">{{ rand(15, 35) }} min</span>
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

