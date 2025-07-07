<header class="site-header">
    <nav class="navbar">
        <div class="container">
            <div class="navbar-brand">
                <a href="{{ url('/') }}" class="brand-link">
                    <h1>LEBAYO</h1>
                </a>
                <button class="location-btn">
                    <i class="icon-location">📍</i>
                    Location
                </button>
            </div>
            
            <div class="navbar-menu">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Commander</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Pages</a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">Contact</a>
                    </li>
                </ul>
            </div>
            
            <div class="navbar-actions">
                <div class="cart-icon">
                    <a href="#" class="cart-link">
                        <i class="icon-cart">🛒</i>
                        <span class="cart-count">1</span>
                    </a>
                </div>
                
                @auth
                <!-- Menu utilisateur connecté -->
                <div class="user-account-dropdown">
                    <div class="user-account" onclick="toggleUserMenu()">
                        <div class="user-avatar">
                            <span class="avatar-initials">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                        </div>
                        <div class="user-info">
                            <span class="user-greeting">Salut, {{ auth()->user()->name ?? 'Utilisateur' }}</span>
                            <span class="user-account-text">Mon Compte</span>
                        </div>
                    </div>
                    
                    <div class="user-menu" id="userMenu">
                        <div class="user-menu-header">
                            <div class="user-menu-avatar">
                                <span class="avatar-initials-lg">{{ substr(auth()->user()->name ?? 'U', 0, 1) }}</span>
                            </div>
                            <div class="user-menu-info">
                                <span class="user-menu-greeting">Salut, {{ auth()->user()->name ?? 'Utilisateur' }}</span>
                                <span class="user-menu-account">Mon Compte</span>
                            </div>
                        </div>
                        
                        <div class="user-menu-items">
                            <a href="#" class="user-menu-item">
                                <i class="menu-icon">👤</i>
                                Profile
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="menu-icon">📦</i>
                                My orders
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="menu-icon">📍</i>
                                Saved Address
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="menu-icon">💳</i>
                                Saved cards
                            </a>
                            <a href="#" class="user-menu-item">
                                <i class="menu-icon">⚙️</i>
                                Settings
                            </a>
                            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                                @csrf
                                <button type="submit" class="user-menu-item logout" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                                    <i class="menu-icon">🚪</i>
                                    Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth
                
                @guest
                <!-- Boutons pour utilisateurs non connectés -->
                <div class="auth-buttons">
                    <a href="{{ route('login') }}" class="btn btn-outline">Se connecter</a>
                    <a href="{{ route('register') }}" class="btn btn-red">Créer un compte</a>
                </div>
                @endguest
            </div>
            
            <div class="navbar-toggle">
                <span></span>
                <span></span>
                <span></span>
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
</script> 