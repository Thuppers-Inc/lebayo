@extends('layouts.app')

@section('title', 'Se connecter - Lebayo')

@section('content')
<section class="auth-section">
    <div class="container">
        <div class="auth-container">
            <div class="auth-card">
                <div class="auth-header">
                    <h1>Se connecter</h1>
                    <p>Connectez-vous à votre compte LEBAYO</p>
                </div>

                <form class="auth-form" method="POST" action="{{ route('login') }}">
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
                        <label for="identifier">Email ou numéro de téléphone</label>
                        <input type="text" id="identifier" name="identifier" value="{{ old('identifier') }}" placeholder="exemple@email.com ou 77 123 45 67" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="password">Mot de passe</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group checkbox-group">
                        <label>
                            <input type="checkbox" name="remember">
                            <span class="checkmark"></span>
                            Se souvenir de moi
                        </label>
                    </div>

                    <button type="submit" class="btn btn-red auth-btn">Se connecter</button>

                    <div class="auth-links">
                        <a href="#" class="forgot-password">Mot de passe oublié ?</a>
                    </div>
                </form>

                <div class="auth-footer">
                    <p>Pas encore de compte ? <a href="{{ route('register') }}" class="text-red">Créer un compte</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
