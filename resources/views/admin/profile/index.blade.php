@extends('admin.layouts.master')

@section('title', 'Mon Profil')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Mon Profil Administrateur</h4>
                <p class="card-text">Gérez vos informations personnelles et votre mot de passe</p>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Informations personnelles -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Informations personnelles</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="prenoms" class="form-label">Prénoms</label>
                            <input type="text" class="form-control @error('prenoms') is-invalid @enderror" 
                                   id="prenoms" name="prenoms" value="{{ old('prenoms', $user->prenoms) }}" required>
                            @error('prenoms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom de famille</label>
                            <input type="text" class="form-control @error('nom') is-invalid @enderror" 
                                   id="nom" name="nom" value="{{ old('nom', $user->nom) }}" required>
                            @error('nom')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" 
                               id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="numero_telephone" class="form-label">Numéro de téléphone</label>
                        <input type="text" class="form-control @error('numero_telephone') is-invalid @enderror" 
                               id="numero_telephone" name="numero_telephone" value="{{ old('numero_telephone', $user->numero_telephone) }}">
                        @error('numero_telephone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control @error('ville') is-invalid @enderror" 
                                   id="ville" name="ville" value="{{ old('ville', $user->ville) }}">
                            @error('ville')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="commune" class="form-label">Commune</label>
                            <input type="text" class="form-control @error('commune') is-invalid @enderror" 
                                   id="commune" name="commune" value="{{ old('commune', $user->commune) }}">
                            @error('commune')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                            <input type="text" class="form-control @error('lieu_naissance') is-invalid @enderror" 
                                   id="lieu_naissance" name="lieu_naissance" value="{{ old('lieu_naissance', $user->lieu_naissance) }}">
                            @error('lieu_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control @error('date_naissance') is-invalid @enderror" 
                                   id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $user->date_naissance ? $user->date_naissance->format('Y-m-d') : '') }}">
                            @error('date_naissance')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-1"></i>
                            Mettre à jour
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Changement de mot de passe -->
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Changer le mot de passe</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.profile.update-password') }}">
                    @csrf
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mot de passe actuel</label>
                        <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                               id="current_password" name="current_password" required>
                        @error('current_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Nouveau mot de passe</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" 
                               id="password" name="password" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" class="form-control" 
                               id="password_confirmation" name="password_confirmation" required>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-warning">
                            <i class="bx bx-lock me-1"></i>
                            Changer le mot de passe
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Informations du compte -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title">Informations du compte</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Type de compte :</strong> {{ ucfirst($user->account_type->value) }}</p>
                        <p><strong>Rôle :</strong> {{ ucfirst($user->role->value ?? 'Non défini') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Membre depuis :</strong> {{ $user->created_at->format('d/m/Y') }}</p>
                        <p><strong>Dernière connexion :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 