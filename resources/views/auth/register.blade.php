@extends('layouts.app')

@section('title', 'Créer un compte - Lebayo')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Créer un compte</h1>
                    <p>Rejoignez LEBAYO et profitez de nos services de livraison</p>
                </div>
                
                <form class="auth-form" method="POST" action="{{ route('register') }}">
                    @csrf
                    
                    @if ($errors->any())
                        <div class="alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <div class="form-group">
                        <label for="name">Nom complet</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="email">Adresse email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password_confirmation">Confirmer le mot de passe</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required>
                    </div>
                    
                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="terms" required>
                            <span class="checkmark"></span>
                            J'accepte les <a href="#" class="text-red">conditions d'utilisation</a>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-red auth-btn">Créer mon compte</button>
                </form>
                
                <div class="auth-footer">
                    <p>Déjà un compte ? <a href="{{ route('login') }}" class="text-red">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection 