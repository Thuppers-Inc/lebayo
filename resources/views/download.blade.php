@extends('layouts.app')

@section('title', 'Télécharger l\'application Lebayo')

@push('styles')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=Playfair+Display:wght@400;500;600;700;800;900&display=swap');

    .download-hero {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000000;
        background: linear-gradient(135deg, #0a0a0a 0%, #000000 50%, #1a1a1a 100%);
        position: relative;
        overflow: hidden;
        padding: 80px 20px;
    }

    .download-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 1;
    }

    .download-hero::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(255, 107, 53, 0.1) 0%, transparent 70%);
        animation: rotate 20s linear infinite;
    }

    .download-container {
        max-width: 1200px;
        margin: 0 auto;
        position: relative;
        z-index: 1;
    }

    .download-content {
        text-align: center;
        color: white;
        animation: fadeInUp 0.8s ease-out;
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    }

    .app-icon {
        width: 150px;
        height: 150px;
        margin: 0 auto 30px;
        background: white;
        border-radius: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        animation: float 3s ease-in-out infinite;
        position: relative;
    }

    .app-icon::after {
        content: '';
        position: absolute;
        top: -10px;
        left: -10px;
        right: -10px;
        bottom: -10px;
        border-radius: 40px;
        background: linear-gradient(135deg, rgba(255,255,255,0.3), rgba(255,255,255,0));
        animation: pulse 2s ease-in-out infinite;
    }

    .app-icon img {
        width: 100px;
        height: 100px;
        border-radius: 20px;
    }

    .app-icon .icon-placeholder {
        font-size: 80px;
        color: #FF6B35;
    }

    @keyframes rotate {
        from {
            transform: rotate(0deg);
        }
        to {
            transform: rotate(360deg);
        }
    }

    .download-title {
        font-family: 'Playfair Display', serif;
        font-size: 4.5rem;
        font-weight: 700;
        margin-bottom: 30px;
        letter-spacing: -0.02em;
        line-height: 1.1;
        color: #ffffff;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
        background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .download-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 1.25rem;
        font-weight: 400;
        margin-bottom: 50px;
        color: rgba(255, 255, 255, 0.85);
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.7;
        letter-spacing: 0.01em;
    }

    .download-button {
        display: inline-block;
        padding: 20px 50px;
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 100%);
        color: white;
        text-decoration: none;
        border-radius: 50px;
        font-size: 1.3rem;
        font-weight: 700;
        box-shadow: 0 10px 40px rgba(255, 107, 53, 0.4);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        margin: 10px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .download-button::before {
        content: '';
        position: absolute;
        top: 50%;
        left: 50%;
        width: 0;
        height: 0;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.3);
        transform: translate(-50%, -50%);
        transition: width 0.6s, height 0.6s;
    }

    .download-button:hover::before {
        width: 300px;
        height: 300px;
    }

    .download-button:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 50px rgba(255, 107, 53, 0.6);
    }

    .download-button:active {
        transform: translateY(-2px);
    }

    .download-button i {
        margin-right: 10px;
        font-size: 1.5rem;
    }

    .features-section {
        padding: 100px 20px;
        background: #f8f9fa;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-card {
        background: white;
        padding: 40px 30px;
        border-radius: 20px;
        text-align: center;
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .feature-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 5px;
        background: linear-gradient(90deg, #FF6B35, #FFB830, #FFD700);
        transform: scaleX(0);
        transition: transform 0.3s ease;
    }

    .feature-card:hover::before {
        transform: scaleX(1);
    }

    .feature-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .feature-icon {
        width: 80px;
        height: 80px;
        margin: 0 auto 25px;
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 50%, #FFD700 100%);
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.5rem;
        color: white;
        box-shadow: 0 10px 30px rgba(255, 107, 53, 0.3);
    }

    .feature-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.6rem;
        font-weight: 600;
        margin-bottom: 18px;
        color: #1a1a1a;
        letter-spacing: -0.01em;
    }

    .feature-description {
        font-family: 'Inter', sans-serif;
        color: #6c757d;
        line-height: 1.7;
        font-size: 0.95rem;
        font-weight: 400;
        letter-spacing: 0.01em;
    }

    .stats-section {
        padding: 80px 20px;
        background: #000000;
        background: linear-gradient(135deg, #0a0a0a 0%, #000000 50%, #1a1a1a 100%);
        color: white;
        position: relative;
    }

    .stats-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.03)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 1;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 40px;
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }

    .stat-item {
        animation: fadeInUp 0.8s ease-out;
        position: relative;
        z-index: 1;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 4rem;
        font-weight: 700;
        margin-bottom: 12px;
        display: block;
        letter-spacing: -0.02em;
        background: linear-gradient(135deg, #ffffff 0%, #f0f0f0 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-label {
        font-family: 'Inter', sans-serif;
        font-size: 1.1rem;
        opacity: 0.9;
        font-weight: 400;
        letter-spacing: 0.01em;
    }

    .qr-section {
        padding: 100px 20px;
        background: white;
        text-align: center;
    }

    .qr-container {
        max-width: 600px;
        margin: 0 auto;
    }

    .qr-code {
        width: 250px;
        height: 250px;
        margin: 0 auto 30px;
        background: white;
        border: 10px solid #f8f9fa;
        border-radius: 20px;
        padding: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .qr-code img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    .qr-placeholder {
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 50%, #FFD700 100%);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1.2rem;
        font-weight: 600;
    }

    /* Section Stores */
    .stores-section {
        padding: 100px 20px;
        background: white;
    }

    .stores-container {
        max-width: 1200px;
        margin: 0 auto;
        text-align: center;
    }

    .stores-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: #1a1a1a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .stores-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        color: #6c757d;
        margin-bottom: 60px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
        font-weight: 400;
        line-height: 1.7;
        letter-spacing: 0.01em;
    }

    .stores-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 40px;
        max-width: 900px;
        margin: 0 auto;
    }

    .store-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        padding: 50px 30px;
        border-radius: 25px;
        text-align: center;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        border: 2px solid transparent;
    }

    .store-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.05), rgba(255, 184, 48, 0.05), rgba(255, 215, 0, 0.05));
        opacity: 0;
        transition: opacity 0.3s ease;
    }

    .store-card:hover::before {
        opacity: 1;
    }

    .store-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 20px 60px rgba(255, 107, 53, 0.2);
        border-color: #FF6B35;
    }

    .store-icon {
        width: 100px;
        height: 100px;
        margin: 0 auto 30px;
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 50%, #FFD700 100%);
        border-radius: 25px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        color: white;
        box-shadow: 0 15px 40px rgba(255, 107, 53, 0.3);
        position: relative;
        z-index: 1;
    }

    .store-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.9rem;
        font-weight: 600;
        margin-bottom: 15px;
        color: #1a1a1a;
        position: relative;
        z-index: 1;
        letter-spacing: -0.01em;
    }

    .store-status {
        display: inline-block;
        padding: 12px 30px;
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 50%, #FFD700 100%);
        color: white;
        border-radius: 50px;
        font-size: 1.1rem;
        font-weight: 600;
        margin-top: 20px;
        box-shadow: 0 5px 20px rgba(255, 107, 53, 0.3);
        position: relative;
        z-index: 1;
    }

    .store-status i {
        margin-right: 8px;
    }

    .apk-download-section {
        padding: 80px 20px;
        background: linear-gradient(135deg, rgba(255, 107, 53, 0.05) 0%, rgba(255, 184, 48, 0.05) 50%, rgba(255, 215, 0, 0.05) 100%);
        text-align: center;
    }

    .apk-download-container {
        max-width: 800px;
        margin: 0 auto;
    }

    .apk-download-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 25px;
        color: #1a1a1a;
        letter-spacing: -0.02em;
        line-height: 1.2;
    }

    .apk-download-subtitle {
        font-family: 'Inter', sans-serif;
        font-size: 1.15rem;
        color: #6c757d;
        margin-bottom: 40px;
        font-weight: 400;
        line-height: 1.7;
        letter-spacing: 0.01em;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 0.5;
        }
        50% {
            opacity: 0.8;
        }
    }

    @media (max-width: 768px) {
        .download-title {
            font-size: 2.5rem;
        }

        .download-subtitle {
            font-size: 1.2rem;
        }

        .download-button {
            padding: 15px 35px;
            font-size: 1.1rem;
        }

        .app-icon {
            width: 120px;
            height: 120px;
        }

        .app-icon img {
            width: 80px;
            height: 80px;
        }

        .features-grid {
            grid-template-columns: 1fr;
            gap: 30px;
        }

        .stats-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 30px;
        }

        .stat-number {
            font-size: 2.5rem;
        }

        .stores-grid {
            grid-template-columns: 1fr;
        }

        .stores-title {
            font-size: 2.2rem;
        }

        .apk-download-title {
            font-size: 2.2rem;
        }

        .download-title {
            font-size: 3rem;
        }

        .stat-number {
            font-size: 2.8rem;
        }
    }

    .download-info {
        margin-top: 50px;
        padding: 35px 40px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        backdrop-filter: blur(20px);
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .download-info p {
        margin: 12px 0;
        font-size: 0.95rem;
        color: rgba(255, 255, 255, 0.8);
        font-weight: 400;
        letter-spacing: 0.01em;
        line-height: 1.6;
    }

    .download-info i {
        margin-right: 12px;
        color: #FFB830;
        font-size: 1rem;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="download-hero">
    <div class="download-container">
        <div class="download-content">
            <div class="app-icon">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Lebayo App">
                @else
                    <div class="icon-placeholder">📱</div>
                @endif
            </div>

            <h1 class="download-title">Téléchargez Lebayo</h1>
            <p class="download-subtitle">
                Commandez vos plats préférés, faites vos courses et profitez de la livraison rapide directement depuis votre smartphone.
            </p>

            <div class="download-info">
                <p><i class="fas fa-check-circle"></i> Version Android 5.0+ requise</p>
                <p><i class="fas fa-shield-alt"></i> Application sécurisée et vérifiée</p>
                <p><i class="fas fa-clock"></i> Installation rapide en quelques secondes</p>
            </div>
        </div>
    </div>
</section>

<!-- Stores Section -->
<section class="stores-section">
    <div class="stores-container">
        <h2 class="stores-title">Disponible bientôt sur</h2>
        <p class="stores-subtitle">
            L'application Lebayo sera prochainement disponible sur les stores officiels
        </p>
        <div class="stores-grid">
            <div class="store-card">
                <div class="store-icon">
                    <i class="fab fa-google-play"></i>
                </div>
                <h3 class="store-name">Google Play Store</h3>
                <p style="color: #6c757d; margin-bottom: 20px; position: relative; z-index: 1;">
                    Pour les utilisateurs Android
                </p>
                <div class="store-status">
                    <i class="fas fa-clock"></i>
                    Bientôt disponible
                </div>
            </div>
            <div class="store-card">
                <div class="store-icon">
                    <i class="fab fa-apple"></i>
                </div>
                <h3 class="store-name">Apple App Store</h3>
                <p style="color: #6c757d; margin-bottom: 20px; position: relative; z-index: 1;">
                    Pour les utilisateurs iOS
                </p>
                <div class="store-status">
                    <i class="fas fa-clock"></i>
                    Bientôt disponible
                </div>
            </div>
        </div>
    </div>
</section>

<!-- APK Download Section -->
<section class="apk-download-section">
    <div class="apk-download-container">
        <h2 class="apk-download-title">Télécharger l'APK directement</h2>
        <p class="apk-download-subtitle">
            Vous pouvez télécharger et installer l'application Android directement depuis cette page
        </p>

        @php
            $apkPath = public_path('app/lebayo.apk');
            $apkExists = file_exists($apkPath);
        @endphp

        @if($apkExists)
            <a href="{{ asset('app/lebayo.apk') }}" class="download-button" download>
                <i class="fas fa-download"></i>
                Télécharger l'APK Android
            </a>
            <p style="margin-top: 30px; color: #6c757d; font-size: 1rem;">
                <i class="fas fa-info-circle"></i>
                Après le téléchargement, activez l'installation depuis des sources inconnues dans les paramètres de votre appareil Android
            </p>
        @else
            <div class="download-button" style="opacity: 0.7; cursor: not-allowed; pointer-events: none;">
                <i class="fas fa-clock"></i>
                APK bientôt disponible
            </div>
            <p style="margin-top: 30px; color: #6c757d; font-size: 1rem;">
                <i class="fas fa-info-circle"></i>
                Le fichier APK sera bientôt disponible au téléchargement
            </p>
        @endif
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="feature-title">Commandes Faciles</h3>
            <p class="feature-description">
                Parcourez des centaines de restaurants et commerces, ajoutez vos produits au panier et commandez en quelques clics.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-truck"></i>
            </div>
            <h3 class="feature-title">Livraison Rapide</h3>
            <p class="feature-description">
                Recevez vos commandes rapidement grâce à notre réseau de livreurs professionnels disponibles 24/7.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <h3 class="feature-title">Géolocalisation</h3>
            <p class="feature-description">
                Trouvez les meilleurs commerces près de chez vous et suivez votre commande en temps réel.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-credit-card"></i>
            </div>
            <h3 class="feature-title">Paiement Sécurisé</h3>
            <p class="feature-description">
                Payez facilement et en toute sécurité avec plusieurs méthodes de paiement disponibles.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-bell"></i>
            </div>
            <h3 class="feature-title">Notifications</h3>
            <p class="feature-description">
                Recevez des notifications en temps réel sur le statut de vos commandes et les meilleures offres.
            </p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-star"></i>
            </div>
            <h3 class="feature-title">Avis & Notes</h3>
            <p class="feature-description">
                Partagez votre expérience et lisez les avis des autres clients pour faire les meilleurs choix.
            </p>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section">
    <div class="stats-grid">
        <div class="stat-item">
            <span class="stat-number" data-value="{{ $stats['total_commerces'] }}">{{ number_format($stats['total_commerces'], 0, ',', ' ') }}+</span>
            <span class="stat-label">Commerces Partenaires</span>
        </div>
        <div class="stat-item">
            <span class="stat-number" data-value="{{ $stats['active_users'] }}">@if($stats['active_users'] >= 1000){{ number_format($stats['active_users'] / 1000, 1, ',', ' ') }}K+@else{{ number_format($stats['active_users'], 0, ',', ' ') }}+@endif</span>
            <span class="stat-label">Utilisateurs Actifs</span>
        </div>
        <div class="stat-item">
            <span class="stat-number" data-value="{{ $stats['delivered_orders'] }}">@if($stats['delivered_orders'] >= 1000){{ number_format($stats['delivered_orders'] / 1000, 1, ',', ' ') }}K+@else{{ number_format($stats['delivered_orders'], 0, ',', ' ') }}+@endif</span>
            <span class="stat-label">Commandes Livrées</span>
        </div>
        <div class="stat-item">
            <span class="stat-number">{{ $stats['rating'] }}</span>
            <span class="stat-label">Note Moyenne</span>
        </div>
    </div>
</section>

<!-- APK Direct Download Section -->
<section class="qr-section">
    <div class="qr-container">
        <h2 style="font-size: 2.5rem; font-weight: 700; margin-bottom: 20px; color: #2c2c2c;">
            Télécharger l'APK
        </h2>
        <p style="font-size: 1.2rem; color: #6c757d; margin-bottom: 40px;">
            Téléchargez et installez l'application Android directement sur votre appareil
        </p>

        @php
            $apkPath = public_path('app/lebayo.apk');
            $apkExists = file_exists($apkPath);
        @endphp

        @if($apkExists)
            <div style="text-align: center;">
                <a href="{{ asset('app/lebayo.apk') }}" class="download-button" download style="display: inline-block; margin: 20px 0;">
                    <i class="fas fa-download"></i>
                    Télécharger l'APK Android
                </a>
                <p style="margin-top: 30px; color: #6c757d; font-size: 1rem;">
                    <i class="fas fa-info-circle"></i>
                    Après le téléchargement, activez l'installation depuis des sources inconnues dans les paramètres de votre appareil Android
                </p>
            </div>
        @else
            <div style="text-align: center;">
                <div class="download-button" style="opacity: 0.7; cursor: not-allowed; pointer-events: none; display: inline-block; margin: 20px 0;">
                    <i class="fas fa-clock"></i>
                    APK bientôt disponible
                </div>
                <p style="margin-top: 30px; color: #6c757d; font-size: 1rem;">
                    <i class="fas fa-info-circle"></i>
                    Le fichier APK sera bientôt disponible au téléchargement
                </p>
            </div>
        @endif
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animation = 'fadeInUp 0.8s ease-out';
                entry.target.style.opacity = '1';
            }
        });
    }, observerOptions);

    document.querySelectorAll('.feature-card, .stat-item').forEach(el => {
        el.style.opacity = '0';
        observer.observe(el);
    });

    // Fonction pour formater un nombre
    function formatNumber(num) {
        if (num >= 1000) {
            return (num / 1000).toFixed(1).replace('.', ',') + 'K+';
        }
        return num.toLocaleString('fr-FR') + '+';
    }

    // Animation des statistiques
    function animateCounter(element, target, duration = 2000) {
        const hasStar = element.textContent.includes('★');
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                if (hasStar) {
                    element.textContent = '4.8★';
                } else {
                    element.textContent = formatNumber(target);
                }
                clearInterval(timer);
            } else {
                if (!hasStar) {
                    element.textContent = formatNumber(Math.floor(start));
                }
            }
        }, 16);
    }

    // Démarrer l'animation des compteurs quand la section est visible
    const statsObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const statNumbers = entry.target.querySelectorAll('.stat-number');
                statNumbers.forEach(stat => {
                    // Utiliser data-value si disponible, sinon parser le texte
                    const dataValue = stat.getAttribute('data-value');
                    let number = null;

                    if (dataValue) {
                        number = parseFloat(dataValue);
                    } else {
                        const text = stat.textContent;
                        number = parseFloat(text.replace(/[^0-9.]/g, ''));
                    }

                    // Ne pas animer la note (elle contient ★)
                    if (number && !stat.textContent.includes('★') && !stat.dataset.animated) {
                        stat.dataset.animated = 'true';
                        animateCounter(stat, number, 2000);
                    }
                });
            }
        });
    }, { threshold: 0.5 });

    const statsSection = document.querySelector('.stats-section');
    if (statsSection) {
        statsObserver.observe(statsSection);
    }
</script>
@endpush

