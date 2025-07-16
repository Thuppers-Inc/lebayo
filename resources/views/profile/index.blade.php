@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="profile-section">
    <div class="container">
        <!-- Header du profil -->
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    {{ substr($user->prenoms ?? 'U', 0, 1) }}
                </div>
            </div>
            <div class="profile-info">
                <h1>{{ $user->full_name }}</h1>
                <p class="profile-subtitle">Membre depuis {{ $stats['member_since'] }}</p>
                <div class="profile-stats">
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['total_orders'] }}</span>
                        <span class="stat-label">Commandes</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ number_format($stats['total_spent'], 0, ',', ' ') }} F</span>
                        <span class="stat-label">Total dépensé</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">{{ $stats['addresses_count'] }}</span>
                        <span class="stat-label">Adresses</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Navigation du profil -->
        <div class="profile-nav">
            <a href="{{ route('profile.index') }}" class="nav-item active">
                <i class="nav-icon">👤</i>
                Informations personnelles
            </a>
            <a href="{{ route('profile.orders') }}" class="nav-item">
                <i class="nav-icon">📦</i>
                Mes commandes
            </a>
            <a href="{{ route('profile.addresses') }}" class="nav-item">
                <i class="nav-icon">📍</i>
                Mes adresses
            </a>
        </div>

        <!-- Contenu principal -->
        <div class="profile-content">
            <div class="profile-cards">
                <!-- Informations personnelles -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2>Informations personnelles</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}" class="profile-form">
                            @csrf
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="prenoms">Prénoms</label>
                                    <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms', $user->prenoms) }}" required>
                                    @error('prenoms')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="nom">Nom de famille</label>
                                    <input type="text" id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                                    @error('nom')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="numero_telephone">Téléphone</label>
                                    <input type="tel" id="numero_telephone" name="numero_telephone" value="{{ old('numero_telephone', $user->numero_telephone) }}">
                                    @error('numero_telephone')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="ville">Ville</label>
                                    <input type="text" id="ville" name="ville" value="{{ old('ville', $user->ville) }}">
                                    @error('ville')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="commune">Commune</label>
                                    <input type="text" id="commune" name="commune" value="{{ old('commune', $user->commune) }}">
                                    @error('commune')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="lieu_naissance">Lieu de naissance</label>
                                    <input type="text" id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance', $user->lieu_naissance) }}">
                                    @error('lieu_naissance')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="date_naissance">Date de naissance</label>
                                    <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance?->format('Y-m-d')) }}">
                                    @error('date_naissance')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="btn-icon">💾</i>
                                    Sauvegarder
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Changement de mot de passe -->
                <div class="profile-card">
                    <div class="card-header">
                        <h2>Sécurité</h2>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update-password') }}" class="profile-form">
                            @csrf
                            <div class="form-group">
                                <label for="current_password">Mot de passe actuel</label>
                                <input type="password" id="current_password" name="current_password" required>
                                @error('current_password')
                                    <span class="error-message">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="password">Nouveau mot de passe</label>
                                    <input type="password" id="password" name="password" required>
                                    @error('password')
                                        <span class="error-message">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirmer le nouveau mot de passe</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-red">
                                    <i class="btn-icon">🔒</i>
                                    Changer le mot de passe
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.profile-section {
    padding: 60px 0;
    background: var(--light-bg);
    min-height: 80vh;
}

.profile-header {
    background: white;
    border-radius: 16px;
    padding: 40px;
    margin-bottom: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    display: flex;
    align-items: center;
    gap: 30px;
}

.profile-avatar .avatar-circle {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 800;
    color: white;
}

.profile-info h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 8px;
}

.profile-subtitle {
    color: var(--text-light);
    font-size: 1rem;
    margin-bottom: 20px;
}

.profile-stats {
    display: flex;
    gap: 30px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    display: block;
    font-size: 1.5rem;
    font-weight: 800;
    color: var(--primary-color);
}

.stat-label {
    display: block;
    font-size: 0.875rem;
    color: var(--text-light);
    margin-top: 4px;
}

.profile-nav {
    display: flex;
    gap: 20px;
    margin-bottom: 30px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 20px;
    background: white;
    border-radius: 12px;
    text-decoration: none;
    color: var(--text-light);
    font-weight: 500;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.nav-item:hover,
.nav-item.active {
    background: var(--primary-color);
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.nav-icon {
    font-size: 1rem;
}

.profile-content {
    display: grid;
    gap: 30px;
}

.profile-cards {
    display: grid;
    gap: 30px;
}

.profile-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    padding: 30px 30px 20px;
    border-bottom: 1px solid #f0f0f0;
}

.card-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.card-body {
    padding: 30px;
}

.profile-form {
    display: grid;
    gap: 24px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    font-size: 0.9rem;
}

.form-group input {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    background: white;
}

.form-group input:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.error-message {
    color: var(--danger-color);
    font-size: 0.875rem;
    margin-top: 4px;
}

.form-actions {
    display: flex;
    justify-content: flex-start;
    gap: 15px;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border: none;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.95rem;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-color);
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.btn-red {
    background: var(--danger-color);
    color: white;
}

.btn-red:hover {
    background: #c53030;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(229, 62, 62, 0.3);
}

.btn-icon {
    font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .profile-section {
        padding: 40px 0;
    }

    .profile-header {
        flex-direction: column;
        text-align: center;
        padding: 30px 20px;
    }

    .profile-stats {
        justify-content: center;
    }

    .profile-nav {
        flex-direction: column;
        gap: 10px;
    }

    .nav-item {
        justify-content: center;
    }

    .card-header {
        padding: 20px 20px 15px;
    }

    .card-body {
        padding: 20px;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 15px;
    }

    .form-actions {
        justify-content: center;
    }
}
</style>
@endpush 