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
                    <div class="search-box">
                        <i class="search-icon">🔍</i>
                        <input type="text" placeholder="Rechercher un restaurant" class="search-input">
                        <button class="search-btn btn-red">Rechercher</button>
                    </div>
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

<!-- Categories Section -->
<section class="categories-section">
    <div class="container">
        <div class="categories-grid">
            <div class="category-item">
                <div class="category-icon">🍕</div>
                <span class="category-name">Pizza</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🍗</div>
                <span class="category-name">Chicken</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🍔</div>
                <span class="category-name">Burger</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🍟</div>
                <span class="category-name">Frites</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🌯</div>
                <span class="category-name">Burrito</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🌮</div>
                <span class="category-name">Taco</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🧁</div>
                <span class="category-name">Muffin</span>
            </div>
            <div class="category-item">
                <div class="category-icon">🥩</div>
                <span class="category-name">Viande</span>
            </div>
        </div>
    </div>
</section>

<!-- Today's Deal Section -->
<section class="deals-section">
    <div class="container">
        <div class="section-header">
            <h2>Offres du jour</h2>
            <p>Profitez de nos dernières offres.</p>
        </div>
        
        <div class="deals-grid">
            <div class="deal-card deal-1">
                <div class="deal-content">
                    <div class="deal-discount">-25%</div>
                    <h3>Pizza Margherita</h3>
                    <p>Délicieuse pizza italienne traditionnelle</p>
                    <div class="deal-price">
                        <span class="old-price">15€</span>
                        <span class="new-price">11,25€</span>
                    </div>
                </div>
                <div class="deal-image">🍕</div>
            </div>
            
            <div class="deal-card deal-red">
                <div class="deal-content">
                    <div class="deal-discount">-30%</div>
                    <span class="badge-red">HOT</span>
                    <h3>Burger Deluxe</h3>
                    <p>Burger juteux avec frites incluses</p>
                    <div class="deal-price">
                        <span class="old-price">12€</span>
                        <span class="new-price">8,40€</span>
                    </div>
                </div>
                <div class="deal-image">🍔</div>
            </div>
            
            <div class="deal-card deal-3">
                <div class="deal-content">
                    <div class="deal-discount">-20%</div>
                    <h3>Salade César</h3>
                    <p>Salade fraîche et légère</p>
                    <div class="deal-price">
                        <span class="old-price">10€</span>
                        <span class="new-price">8€</span>
                    </div>
                </div>
                <div class="deal-image">🥗</div>
            </div>
            
            <div class="deal-card deal-red-soft">
                <div class="deal-content">
                    <div class="deal-discount">-35%</div>
                    <span class="badge-red">NOUVEAU</span>
                    <h3>Sushi Mix</h3>
                    <p>Assortiment de sushis frais</p>
                    <div class="deal-price">
                        <span class="old-price">20€</span>
                        <span class="new-price">13€</span>
                    </div>
                </div>
                <div class="deal-image">🍣</div>
            </div>
            
            <div class="deal-card deal-5">
                <div class="deal-content">
                    <div class="deal-discount">-40%</div>
                    <h3>Menu Dessert</h3>
                    <p>Gâteau au chocolat et glace</p>
                    <div class="deal-price">
                        <span class="old-price">8€</span>
                        <span class="new-price">4,80€</span>
                    </div>
                </div>
                <div class="deal-image">🍰</div>
            </div>
        </div>
    </div>
</section>

<!-- Popular Restaurants Section -->
<section class="restaurants-section">
    <div class="container">
        <div class="section-header">
            <h2>Restaurants populaires</h2>
            <p>Découvrez les restaurants les plus appréciés</p>
        </div>

        <div class="restaurants-grid">
            <div class="restaurant-card">
                <div class="restaurant-image">🏪</div>
                <div class="restaurant-info">
                    <h3>Chez Mario</h3>
                    <p>Cuisine italienne authentique</p>
                    <div class="restaurant-rating">
                        <span class="stars">⭐⭐⭐⭐⭐</span>
                        <span class="rating-text">4.8 (127 avis)</span>
                    </div>
                    <div class="restaurant-details">
                        <span class="delivery-time">⏱️ 25-35 min</span>
                        <span class="delivery-fee">🚚 Livraison gratuite</span>
                    </div>
                </div>
            </div>
            
            <div class="restaurant-card">
                <div class="restaurant-image">🏪</div>
                <div class="restaurant-info">
                    <h3>Burger King</h3>
                    <p>Fast-food américain</p>
                    <div class="restaurant-rating">
                        <span class="stars">⭐⭐⭐⭐</span>
                        <span class="rating-text">4.2 (89 avis)</span>
                    </div>
                    <div class="restaurant-details">
                        <span class="delivery-time">⏱️ 15-25 min</span>
                        <span class="delivery-fee">🚚 2€ livraison</span>
                    </div>
                </div>
            </div>
            
            <div class="restaurant-card">
                <div class="restaurant-image">🏪</div>
                <div class="restaurant-info">
                    <h3>Sushi Zen</h3>
                    <p>Cuisine japonaise raffinée</p>
                    <div class="restaurant-rating">
                        <span class="stars">⭐⭐⭐⭐⭐</span>
                        <span class="rating-text">4.9 (156 avis)</span>
                    </div>
                    <div class="restaurant-details">
                        <span class="delivery-time">⏱️ 30-40 min</span>
                        <span class="delivery-fee">🚚 Livraison gratuite</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
