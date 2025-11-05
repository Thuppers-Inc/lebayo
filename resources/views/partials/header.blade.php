<header class="site-header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="{{ url('/') }}" class="brand-link">
                    <img src="{{ asset('images/logo.png') }}" alt="Lebayo" class="brand-logo">
                </a>
                <button class="location-btn" id="locationBtn" onclick="getCurrentLocation()">
                    <i class="icon-location">📍</i>
                    <span id="locationText">Localisation...</span>
                </button>
            </div>

            <div class="navbar-menu">
                <ul class="navbar-nav">
                    {{-- <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Commander</a>
                    </li> --}}
                </ul>
            </div>

            <div class="navbar-actions">
                <div class="cart-icon">
                    <a href="{{ route('cart.index') }}" class="cart-link">
                        <i class="icon-cart">🛒</i>
                        @php
                            $cartCount = \App\Models\Cart::getCurrentCartCount();
                        @endphp
                        <span class="cart-count" style="{{ $cartCount > 0 ? '' : 'display: none;' }}">{{ $cartCount }}</span>
                    </a>
                </div>

                @auth
                <!-- Menu utilisateur connecté -->
                <div class="user-account-dropdown">
                    <div class="user-account" onclick="toggleUserMenu()">
                        <div class="user-avatar">
                            <span class="avatar-initials">{{ substr(auth()->user()->prenoms ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="user-info">
                            <span class="user-greeting">Salut, {{ auth()->user()->prenoms ?? 'Utilisateur' }}</span>
                            <span class="user-account-text">Mon Compte</span>
                        </div>
                    </div>

                    <div class="user-menu" id="userMenu">
                        <div class="user-menu-header">
                            <div class="user-menu-avatar">
                                <span class="avatar-initials-lg">{{ substr(auth()->user()->prenoms ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="user-menu-info">
                                <span class="user-menu-greeting">Salut, {{ auth()->user()->prenoms ?? 'Utilisateur' }}</span>
                                <span class="user-menu-account">Mon Compte</span>
                            </div>
                        </div>

                        <div class="user-menu-items">
                            <a href="{{ route('profile.index') }}" class="user-menu-item">
                                <i class="menu-icon">👤</i>
                                Mon profil
                            </a>
                            <a href="{{ route('profile.orders') }}" class="user-menu-item">
                                <i class="menu-icon">📦</i>
                                Mes commandes
                            </a>
                            <a href="{{ route('profile.addresses') }}" class="user-menu-item">
                                <i class="menu-icon">📍</i>
                                Mes adresses
                            </a>
                            <a href="{{ route('errand.index') }}" class="user-menu-item">
                                <i class="menu-icon">🚚</i>
                                Mes courses
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="user-menu-item logout" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                    <i class="menu-icon">🚪</i>
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth

                @guest
                <!-- Bouton pour utilisateurs non connectés -->
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-red">Connexion</a>
                </div>
                @endguest
            </div>
        </div>
    </nav>
</header>

<script>
function toggleUserMenu() {
    const userMenu = document.getElementById('userMenu');
    userMenu.classList.toggle('active');
}

// Fermer le menu si on clique ailleurs
document.addEventListener('click', function(event) {
    const userAccount = document.querySelector('.user-account-dropdown');
    const userMenu = document.getElementById('userMenu');

    if (!userAccount.contains(event.target)) {
        userMenu.classList.remove('active');
    }
});

// ===== GÉOLOCALISATION =====
let userLocation = null;

function getCurrentLocation() {
    const locationBtn = document.getElementById('locationBtn');
    const locationText = document.getElementById('locationText');

    // Vérifier si la géolocalisation est supportée
    if (!navigator.geolocation) {
        locationText.textContent = 'Géolocalisation non supportée';
        return;
    }

    // Désactiver le bouton pendant la recherche
    locationBtn.disabled = true;
    locationText.innerHTML = '<span class="loading-dots">Localisation</span>';

    // Options de géolocalisation
    const options = {
        enableHighAccuracy: true,
        timeout: 10000,
        maximumAge: 300000 // 5 minutes
    };

    navigator.geolocation.getCurrentPosition(
        successCallback,
        errorCallback,
        options
    );
}

function successCallback(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;

    userLocation = { latitude, longitude };

    // Obtenir l'adresse via géocodage inverse
    reverseGeocode(latitude, longitude);
}

function errorCallback(error) {
    console.log('Erreur géolocalisation GPS, tentative avec IP...', error);

    // Essayer la géolocalisation par IP en fallback
    tryLocationByIp();
}



// Charger la localisation sauvegardée au chargement de la page
function loadSavedLocation() {
    const saved = localStorage.getItem('userLocation');
    const locationText = document.getElementById('locationText');

    if (saved) {
        try {
            const locationData = JSON.parse(saved);
            const now = Date.now();
            const oneHour = 60 * 60 * 1000; // 1 heure en millisecondes

            // Vérifier si la localisation n'est pas trop ancienne (1 heure)
            if (now - locationData.timestamp < oneHour) {
                locationText.textContent = locationData.city;
                userLocation = locationData.coordinates;
                return;
            }
        } catch (e) {
            console.error('Erreur parsing localisation:', e);
        }
    }

    // Si pas de localisation sauvegardée ou trop ancienne
    locationText.textContent = 'Cliquer pour localiser';
}

// Fonction fallback : géolocalisation par IP
async function tryLocationByIp() {
    const locationBtn = document.getElementById('locationBtn');
    const locationText = document.getElementById('locationText');

    try {
        const response = await fetch('{{ route("api.location-by-ip") }}', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        const data = await response.json();

        if (data.success && data.city) {
            locationText.textContent = data.city;

            // Stocker la localisation approximative
            localStorage.setItem('userLocation', JSON.stringify({
                city: data.city,
                coordinates: data.coordinates,
                timestamp: Date.now(),
                method: 'ip'
            }));

            userLocation = data.coordinates;
        } else {
            throw new Error('Localisation IP échouée');
        }

    } catch (error) {
        console.error('Erreur localisation IP:', error);
        showLocationError();
    } finally {
        locationBtn.disabled = false;
    }
}

// Afficher une erreur de localisation
function showLocationError() {
    const locationBtn = document.getElementById('locationBtn');
    const locationText = document.getElementById('locationText');

    locationText.textContent = 'Localisation indisponible';
    locationBtn.disabled = false;

    // Remettre le texte par défaut après 3 secondes
    setTimeout(() => {
        locationText.textContent = 'Cliquer pour localiser';
    }, 3000);
}

// Amélioration de reverseGeocode avec fallback Laravel
async function reverseGeocode(lat, lon) {
    const locationBtn = document.getElementById('locationBtn');
    const locationText = document.getElementById('locationText');

    try {
        // Essayer d'abord l'API directe (Nominatim)
        const response = await fetch(
            `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}&zoom=10&addressdetails=1`,
            {
                headers: {
                    'User-Agent': 'Lebayo-App/1.0'
                }
            }
        );

        if (!response.ok) {
            throw new Error('API Nominatim échouée');
        }

        const data = await response.json();

        if (data && data.address) {
            const address = data.address;
            const city = address.city ||
                         address.town ||
                         address.village ||
                         address.municipality ||
                         address.suburb ||
                         address.county ||
                         'Ville inconnue';

            locationText.textContent = city;

            localStorage.setItem('userLocation', JSON.stringify({
                city: city,
                coordinates: { lat, lon },
                timestamp: Date.now(),
                method: 'gps'
            }));

            userLocation = { lat, lon };
        } else {
            throw new Error('Pas de données d\'adresse');
        }

    } catch (error) {
        console.log('API Nominatim échouée, utilisation de l\'API Laravel...', error);

        // Fallback: utiliser l'API Laravel
        try {
            const response = await fetch('{{ route("api.reverse-geocode") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ lat, lon })
            });

            const data = await response.json();

            if (data.success && data.city) {
                locationText.textContent = data.city;

                localStorage.setItem('userLocation', JSON.stringify({
                    city: data.city,
                    coordinates: { lat, lon },
                    timestamp: Date.now(),
                    method: 'gps-laravel'
                }));

                userLocation = { lat, lon };
            } else {
                throw new Error('API Laravel échouée');
            }

        } catch (laravelError) {
            console.error('Toutes les APIs ont échoué:', laravelError);
            showLocationError();
        }
    } finally {
        locationBtn.disabled = false;
    }
}

// Initialiser au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    loadSavedLocation();
});
</script>
