@extends('layouts.app')

@section('title', 'Résultats de recherche pour "' . $query . '" - Lebayo')

@section('content')
<!-- Search Results Header -->
<section class="search-results-header">
    <div class="container">
        <div class="search-header-content">
            <h1>Résultats de recherche</h1>
            <p class="search-query">Vous avez recherché : <strong>"{{ $query }}"</strong></p>
            <div class="search-stats">
                <span class="results-count">{{ $totalResults }}</span>
                <span class="results-text">{{ $totalResults > 1 ? 'résultats trouvés' : 'résultat trouvé' }}</span>
            </div>
        </div>
        
        <!-- New Search Bar -->
        <div class="search-again-container">
            <form action="{{ route('search') }}" method="GET" class="search-form">
                <div class="search-input-group">
                    <div class="search-icon">🔍</div>
                    <input type="text" 
                           name="q" 
                           value="{{ $query }}" 
                           placeholder="Rechercher un restaurant ou un produit..." 
                           class="search-input-field"
                           id="searchInput"
                           autocomplete="off">
                    <button type="submit" class="search-submit-btn">Rechercher</button>
                </div>
                <div class="search-suggestions" id="searchSuggestions"></div>
            </form>
        </div>
    </div>
</section>

<!-- Search Results Content -->
<section class="search-results-content">
    <div class="container">
        
        @if($totalResults > 0)
            
            <!-- Commerces Results -->
            @if($commerces->count() > 0)
                <div class="results-section">
                    <div class="section-header">
                        <h2>Restaurants & Commerces</h2>
                        <span class="section-count">{{ $commerces->count() }} {{ $commerces->count() > 1 ? 'commerces' : 'commerce' }}</span>
                    </div>
                    
                    <div class="commerces-results-grid">
                        @foreach($commerces as $commerce)
                            @php
                                $isOpen = $commerce->isOpen();
                            @endphp
                            <a href="{{ route('commerce.show', $commerce) }}" class="commerce-result-card {{ !$isOpen ? 'commerce-closed' : '' }}">
                                <div class="commerce-image" style="background-image: url('{{ $commerce->placeholder_image }}');">
                                    <div class="commerce-overlay">
                                        <div class="status-badge status-badge-{{ $commerce->status_class }}">
                                            <span class="status-icon">{{ $commerce->status_icon }}</span>
                                            <span class="status-label">{{ $commerce->status_label }}</span>
                                        </div>
                                        <div class="commerce-badge">{{ $commerce->commerceType->name }}</div>
                                    </div>
                                </div>
                                
                                <div class="commerce-info">
                                    <div class="commerce-header">
                                        <h3 class="commerce-name">{{ $commerce->name }}</h3>
                                        <div class="commerce-rating">
                                            <span class="rating-star">⭐</span>
                                            <span class="rating-value">{{ number_format($commerce->rating, 1) }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($commerce->description)
                                        <p class="commerce-description">{{ Str::limit($commerce->description, 80) }}</p>
                                    @endif
                                    
                                    <div class="commerce-meta">
                                        <div class="meta-item">
                                            <span class="meta-icon">📍</span>
                                            <span class="meta-text">{{ $commerce->city }}</span>
                                        </div>
                                        <div class="meta-item">
                                            <span class="meta-icon">🛍️</span>
                                            <span class="meta-text">{{ $commerce->products_count }} {{ $commerce->products_count > 1 ? 'produits' : 'produit' }}</span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
            <!-- Products Results -->
            @if($products->count() > 0)
                <div class="results-section">
                    <div class="section-header">
                        <h2>Produits</h2>
                        <span class="section-count">{{ $products->count() }} {{ $products->count() > 1 ? 'produits' : 'produit' }}</span>
                    </div>
                    
                    <div class="products-results-grid">
                        @foreach($products as $product)
                            <a href="{{ route('commerce.show', $product->commerce) }}" class="product-result-card">
                                <div class="product-image">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    @if($product->is_featured)
                                        <div class="featured-badge">⭐ Vedette</div>
                                    @endif
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
                                        <p class="product-description">{{ Str::limit($product->description, 60) }}</p>
                                    @endif
                                    
                                    <div class="product-meta">
                                        <div class="meta-item">
                                            <span class="meta-icon">🏪</span>
                                            <span class="meta-text">{{ $product->commerce->name }}</span>
                                        </div>
                                        @if($product->category)
                                            <div class="meta-item">
                                                <span class="meta-icon">{{ $product->category->emoji }}</span>
                                                <span class="meta-text">{{ $product->category->name }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
            
        @else
            <!-- No Results -->
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h3>Aucun résultat trouvé</h3>
                <p>Nous n'avons trouvé aucun restaurant ou produit correspondant à votre recherche <strong>"{{ $query }}"</strong>.</p>
                
                <div class="search-suggestions-text">
                    <h4>Suggestions :</h4>
                    <ul>
                        <li>Vérifiez l'orthographe des mots-clés</li>
                        <li>Essayez des termes plus généraux</li>
                        <li>Cherchez par type de cuisine (pizza, burger, sushi...)</li>
                        <li>Cherchez par ville ou quartier</li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Back to Home -->
<section class="back-section">
    <div class="container">
        <a href="{{ route('home') }}" class="back-btn">
            <span class="back-icon">←</span>
            Retour à l'accueil
        </a>
    </div>
</section>

<script>
// Autocomplétion pour la recherche
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchInput');
    const suggestionsContainer = document.getElementById('searchSuggestions');
    let currentTimeout;
    
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
                    displaySuggestions(data);
                })
                .catch(error => {
                    console.error('Erreur lors de la recherche:', error);
                    suggestionsContainer.style.display = 'none';
                });
        }, 300);
    });
    
    function displaySuggestions(suggestions) {
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
        if (!e.target.closest('.search-again-container')) {
            suggestionsContainer.style.display = 'none';
        }
    });
});
</script>
@endsection 