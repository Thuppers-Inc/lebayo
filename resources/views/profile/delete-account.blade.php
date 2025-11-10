@extends('layouts.app')

@section('title', 'Supprimer mon compte')

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
                <p class="profile-subtitle">Gestion de votre compte</p>
            </div>
        </div>

        <!-- Navigation du profil -->
        <div class="profile-nav">
            <a href="{{ route('profile.index') }}" class="nav-item">
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
            <div class="profile-card">
                <div class="card-header">
                    <h2>
                        <i class="header-icon">⚠️</i>
                        Supprimer mon compte
                    </h2>
                </div>
                <div class="card-body">
                    <!-- Avertissement -->
                    <div class="warning-box">
                        <div class="warning-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <div class="warning-content">
                            <h3>Attention ! Cette action est irréversible</h3>
                            <p>La suppression de votre compte entraînera :</p>
                            <ul class="warning-list">
                                <li>La suppression définitive de toutes vos données personnelles</li>
                                <li>La perte de l'accès à votre historique de commandes</li>
                                <li>La suppression de toutes vos adresses enregistrées</li>
                                <li>L'impossibilité de récupérer votre compte par la suite</li>
                            </ul>
                        </div>
                    </div>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Formulaire de suppression -->
                    <form action="{{ route('profile.delete-account.destroy') }}" method="POST" id="deleteAccountForm" class="delete-form">
                        @csrf
                        @method('DELETE')

                        <div class="form-group">
                            <label for="password">
                                <i class="label-icon">🔒</i>
                                Mot de passe <span class="required">*</span>
                            </label>
                            <input type="password" 
                                   id="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror"
                                   placeholder="Entrez votre mot de passe pour confirmer"
                                   required>
                            @error('password')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                            <small class="form-hint">Pour des raisons de sécurité, vous devez entrer votre mot de passe actuel.</small>
                        </div>

                        <div class="form-group">
                            <label class="checkbox-label">
                                <input type="checkbox" 
                                       id="confirm_delete" 
                                       name="confirm_delete" 
                                       value="1"
                                       required
                                       class="confirm-checkbox">
                                <span class="checkbox-text">
                                    Je comprends que cette action est irréversible et je confirme vouloir supprimer mon compte définitivement.
                                </span>
                            </label>
                            @error('confirm_delete')
                                <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                <i class="btn-icon">←</i>
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-danger" id="submitBtn" disabled>
                                <i class="btn-icon">🗑️</i>
                                Supprimer définitivement mon compte
                            </button>
                        </div>
                    </form>
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
    margin-bottom: 0;
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

.nav-item:hover {
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

.profile-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.card-header {
    padding: 30px 30px 20px;
    border-bottom: 1px solid #f0f0f0;
    background: linear-gradient(135deg, #fee 0%, #fdd 100%);
}

.card-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #c53030;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 10px;
}

.header-icon {
    font-size: 1.5rem;
}

.card-body {
    padding: 30px;
}

.warning-box {
    background: #fff3cd;
    border: 2px solid #ffc107;
    border-radius: 12px;
    padding: 20px;
    margin-bottom: 30px;
    display: flex;
    gap: 15px;
    align-items: flex-start;
}

.warning-icon {
    font-size: 2rem;
    color: #ff9800;
    flex-shrink: 0;
}

.warning-content h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #856404;
    margin-bottom: 10px;
}

.warning-content p {
    color: #856404;
    margin-bottom: 10px;
    font-weight: 600;
}

.warning-list {
    margin: 0;
    padding-left: 20px;
    color: #856404;
}

.warning-list li {
    margin-bottom: 8px;
}

.alert {
    background: #fee;
    border-left: 4px solid #dc3545;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.alert-danger {
    background: #fee;
    color: #721c24;
}

.alert-danger ul {
    margin: 0;
    padding-left: 20px;
}

.delete-form {
    display: grid;
    gap: 24px;
}

.form-group {
    display: flex;
    flex-direction: column;
}

.form-group label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 8px;
    font-size: 0.95rem;
}

.label-icon {
    font-size: 1rem;
}

.required {
    color: #dc3545;
    font-weight: 700;
}

.form-control {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    background: white;
}

.form-control:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.form-control.is-invalid {
    border-color: #dc3545;
}

.form-hint {
    display: block;
    color: var(--text-light);
    font-size: 0.875rem;
    margin-top: 6px;
}

.checkbox-label {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    cursor: pointer;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 12px;
    border: 2px solid #e2e8f0;
    transition: all 0.3s ease;
}

.checkbox-label:hover {
    background: #f0f0f0;
    border-color: #d0d0d0;
}

.confirm-checkbox {
    width: 20px;
    height: 20px;
    margin-top: 2px;
    flex-shrink: 0;
    cursor: pointer;
}

.checkbox-text {
    flex: 1;
    color: var(--text-dark);
    font-size: 0.95rem;
    line-height: 1.5;
}

.error-message {
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 6px;
}

.form-actions {
    display: flex;
    justify-content: space-between;
    gap: 15px;
    margin-top: 10px;
    padding-top: 20px;
    border-top: 2px solid #f0f0f0;
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

.btn-secondary {
    background: #f8f9fa;
    color: var(--text-dark);
    border: 2px solid #e2e8f0;
}

.btn-secondary:hover {
    background: #e9ecef;
    border-color: #d0d0d0;
    transform: translateY(-2px);
}

.btn-danger {
    background: #dc3545;
    color: white;
}

.btn-danger:hover:not(:disabled) {
    background: #c82333;
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(220, 53, 69, 0.3);
}

.btn-danger:disabled {
    background: #ccc;
    cursor: not-allowed;
    opacity: 0.6;
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

    .warning-box {
        flex-direction: column;
        padding: 15px;
    }

    .form-actions {
        flex-direction: column;
    }

    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmCheckbox = document.getElementById('confirm_delete');
    const submitBtn = document.getElementById('submitBtn');
    const form = document.getElementById('deleteAccountForm');

    // Activer/désactiver le bouton selon la checkbox
    confirmCheckbox.addEventListener('change', function() {
        submitBtn.disabled = !this.checked;
    });

    // Confirmation avant soumission
    form.addEventListener('submit', function(e) {
        if (!confirm('Êtes-vous absolument sûr de vouloir supprimer votre compte ? Cette action est irréversible.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
@endpush

