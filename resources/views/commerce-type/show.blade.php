@extends('layouts.app')

@section('title', $commerceType->name . ' - Lebayo')

@section('content')
<!-- Commerce Type Header -->
<section class="commerce-type-hero" style="background-image: url('{{ $commerceType->header_image }}');">
    <div class="commerce-type-hero-overlay">
        <div class="container">
            <div class="commerce-type-hero-content">
                <div class="commerce-type-info">
                    <h1 class="commerce-type-title">{{ $commerceType->name }}</h1>
                    <p class="commerce-type-subtitle">{{ $commerceType->description }}</p>
                    
                    <div class="commerce-type-stats">
                        <div class="stat-item">
                            <span class="stat-number">{{ $totalCommerces }}</span>
                            <span class="stat-label">{{ $totalCommerces > 1 ? 'Commerces' : 'Commerce' }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $activeCommerces }}</span>
                            <span class="stat-label">{{ $activeCommerces > 1 ? 'Actifs' : 'Actif' }}</span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-number">{{ $commerces->sum('products_count') }}</span>
                            <span class="stat-label">{{ $commerces->sum('products_count') > 1 ? 'Produits' : 'Produit' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Commerces Grid -->
<section class="commerce-type-content">
    <div class="container">
        <div class="content-header">
            <h2>Tous les {{ $commerceType->name }}</h2>
            <p>Découvrez {{ $totalCommerces }} {{ $totalCommerces > 1 ? 'commerces' : 'commerce' }} dans cette catégorie</p>
        </div>

        @if($commerces->count() > 0)
            <div class="commerces-grid">
                @foreach($commerces as $commerce)
                    <a href="{{ route('restaurant.show', $commerce) }}" class="commerce-card">
                        <div class="commerce-image" style="background-image: url('{{ $commerce->placeholder_image }}');">
                            <div class="commerce-overlay">
                                @if($commerce->products_count >= 20)
                                    <div class="commerce-badge">
                                        <span class="badge-icon">⭐</span>
                                        Populaire
                                    </div>
                                @endif
                                
                                @if($commerce->products_count >= 50)
                                    <div class="variety-badge">
                                        <span class="badge-icon">🏆</span>
                                        Large choix
                                    </div>
                                @endif
                            </div>
                        </div>
                        
                        <div class="commerce-info">
                            <div class="commerce-header">
                                <h3 class="commerce-name">{{ $commerce->name }}</h3>
                                <div class="commerce-rating">
                                    <span class="rating-star">⭐</span>
                                    <span class="rating-value">{{ number_format(rand(32, 39) / 10, 1) }}</span>
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
                                <div class="meta-item">
                                    <span class="meta-icon">🕒</span>
                                    <span class="meta-text">{{ rand(15, 35) }}-{{ rand(25, 45) }} min</span>
                                </div>
                            </div>
                            
                            @if($commerce->categories->count() > 0)
                                <div class="commerce-categories">
                                    @foreach($commerce->categories->take(3) as $category)
                                        <span class="category-tag">{{ $category->emoji }} {{ $category->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($commerces->hasPages())
                <div class="pagination-wrapper">
                    {{ $commerces->links() }}
                </div>
            @endif

        @else
            <!-- No Commerces -->
            <div class="no-commerces">
                <div class="no-commerces-icon">🏪</div>
                <h3>Aucun commerce disponible</h3>
                <p>Il n'y a actuellement aucun commerce actif dans la catégorie "{{ $commerceType->name }}".</p>
                <a href="{{ route('home') }}" class="back-btn">
                    <span class="back-icon">←</span>
                    Retour à l'accueil
                </a>
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
@endsection 