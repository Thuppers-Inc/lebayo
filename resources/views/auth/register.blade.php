@extends('layouts.app')

@section('title', 'Créer un compte - Lebayo')

@push('styles')
<style>
    .multi-step-form {
        background: linear-gradient(135deg, #FF6B35 0%, #FF8C42 20%, #FFB830 40%, #FFD93D 60%, #FF6B35 80%, #FF4500 100%);
        background-size: 400% 400%;
        animation: gradientShift 15s ease infinite;
        min-height: 100vh;
        padding: 2rem 0;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        overflow: hidden;
    }

    /* Animation du gradient de fond */
    @keyframes gradientShift {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
    }

    /* Formes géométriques animées en arrière-plan */
    .multi-step-form::before,
    .multi-step-form::after {
        content: '';
        position: absolute;
        width: 500px;
        height: 500px;
        border-radius: 50%;
        filter: blur(80px);
        animation: float 20s ease-in-out infinite;
    }

    .multi-step-form::before {
        top: -250px;
        left: -250px;
        background: rgba(255, 107, 53, 0.3);
        animation-delay: 0s;
    }

    .multi-step-form::after {
        bottom: -250px;
        right: -250px;
        background: rgba(255, 184, 48, 0.3);
        animation-delay: 10s;
    }

    @keyframes float {
        0%, 100% {
            transform: translate(0, 0) scale(1);
        }
        33% {
            transform: translate(50px, 50px) scale(1.1);
        }
        66% {
            transform: translate(-50px, -50px) scale(0.9);
        }
    }

    /* Particules flottantes */
    .background-particles {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: 0;
    }

    .particle {
        position: absolute;
        width: 4px;
        height: 4px;
        border-radius: 50%;
        animation: particleFloat 15s infinite ease-in-out;
        box-shadow: 0 0 6px currentColor;
    }

    .particle:nth-child(1) { left: 10%; background: rgba(255, 107, 53, 0.8); color: #FF6B35; animation-delay: 0s; }
    .particle:nth-child(2) { left: 20%; background: rgba(255, 184, 48, 0.8); color: #FFB830; animation-delay: 2s; }
    .particle:nth-child(3) { left: 30%; background: rgba(255, 217, 61, 0.8); color: #FFD93D; animation-delay: 4s; }
    .particle:nth-child(4) { left: 40%; background: rgba(255, 140, 66, 0.8); color: #FF8C42; animation-delay: 6s; }
    .particle:nth-child(5) { left: 50%; background: rgba(255, 69, 0, 0.8); color: #FF4500; animation-delay: 8s; }
    .particle:nth-child(6) { left: 60%; background: rgba(255, 107, 53, 0.8); color: #FF6B35; animation-delay: 10s; }
    .particle:nth-child(7) { left: 70%; background: rgba(255, 184, 48, 0.8); color: #FFB830; animation-delay: 12s; }
    .particle:nth-child(8) { left: 80%; background: rgba(255, 217, 61, 0.8); color: #FFD93D; animation-delay: 14s; }
    .particle:nth-child(9) { left: 90%; background: rgba(255, 140, 66, 0.8); color: #FF8C42; animation-delay: 16s; }

    @keyframes particleFloat {
        0% {
            transform: translateY(100vh) translateX(0) rotate(0deg);
            opacity: 0;
        }
        10% {
            opacity: 1;
        }
        90% {
            opacity: 1;
        }
        100% {
            transform: translateY(-100px) translateX(100px) rotate(360deg);
            opacity: 0;
        }
    }

    .form-container {
        max-width: 500px;
        width: 100%;
        margin: 0 auto;
        padding: 0 1rem;
        position: relative;
        z-index: 10;
    }

    .form-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(255, 255, 255, 0.2);
        overflow: hidden;
        position: relative;
        animation: cardFloat 6s ease-in-out infinite;
    }

    @keyframes cardFloat {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-10px);
        }
    }

    .form-header {
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 50%, #FF6B35 100%);
        background-size: 200% 200%;
        animation: headerGradient 8s ease infinite;
        color: white;
        padding: 2rem 1.5rem;
        text-align: center;
        position: relative;
        overflow: hidden;
    }

    .form-header::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.1) 0%, transparent 70%);
        animation: headerShine 10s linear infinite;
    }

    @keyframes headerGradient {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }

    @keyframes headerShine {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }

    .form-header h1 {
        font-size: 1.75rem;
        font-weight: 800;
        margin: 0 0 0.5rem 0;
        position: relative;
        z-index: 1;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        animation: titlePulse 3s ease-in-out infinite;
    }

    @keyframes titlePulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.02);
        }
    }

    .form-header p {
        font-size: 0.95rem;
        opacity: 0.95;
        margin: 0;
        position: relative;
        z-index: 1;
        animation: fadeInUp 0.8s ease;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 0.95;
            transform: translateY(0);
        }
    }

    .step-indicator {
        display: flex;
        justify-content: center;
        gap: 0.5rem;
        padding: 1.5rem 1.5rem 1rem;
        background: white;
    }

    .step-dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #e0e0e0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        position: relative;
        cursor: pointer;
    }

    .step-dot.active {
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 100%);
        width: 32px;
        border-radius: 6px;
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        animation: pulse 2s ease-in-out infinite;
    }

    @keyframes pulse {
        0%, 100% {
            box-shadow: 0 4px 12px rgba(255, 107, 53, 0.3);
        }
        50% {
            box-shadow: 0 4px 20px rgba(255, 107, 53, 0.5);
        }
    }

    .step-dot.completed {
        background: linear-gradient(135deg, #28A745 0%, #20C997 100%);
        animation: checkmark 0.5s ease;
    }

    @keyframes checkmark {
        0% {
            transform: scale(0.8);
        }
        50% {
            transform: scale(1.1);
        }
        100% {
            transform: scale(1);
        }
    }

    .step-dot.completed::after {
        content: '✓';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 8px;
        font-weight: bold;
    }

    .form-body {
        padding: 2rem 1.5rem;
    }

    .step-content {
        display: none;
        opacity: 0;
        transform: translateX(20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }

    .step-content.active {
        display: block;
        opacity: 1;
        transform: translateX(0);
        animation: slideIn 0.5s ease;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(20px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        font-weight: 600;
        color: #333;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 0.875rem 1rem;
        border: 2px solid #e0e0e0;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: white;
        box-sizing: border-box;
        position: relative;
    }

    .form-group input:hover,
    .form-group select:hover {
        border-color: #FF6B35;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(255, 107, 53, 0.15);
    }

    .form-group input:focus,
    .form-group select:focus {
        outline: none;
        border-color: #FF6B35;
        box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1), 0 4px 12px rgba(255, 107, 53, 0.2);
        transform: translateY(-2px);
    }

    .phone-input-group {
        display: flex;
        gap: 0.5rem;
    }

    .phone-input-group select,
    .phone-input-group input[disabled] {
        flex: 0 0 200px;
        padding: 0.75rem;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 0.95rem;
    }

    .phone-input-group input[disabled] {
        background-color: #f5f5f5;
        cursor: not-allowed;
        color: #666;
    }

    .phone-input-group input[type="tel"] {
        flex: 1;
    }

    .form-actions {
        display: flex;
        gap: 0.75rem;
        margin-top: 2rem;
    }

    .btn-step {
        flex: 1;
        padding: 0.875rem 1.5rem;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-step-primary {
        background: linear-gradient(135deg, #FF6B35 0%, #FFB830 50%, #FF6B35 100%);
        background-size: 200% 200%;
        animation: buttonGradient 3s ease infinite;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .btn-step-primary::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
    }

    .btn-step-primary:hover::before {
        left: 100%;
    }

    @keyframes buttonGradient {
        0%, 100% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
    }

    .btn-step-primary:hover {
        transform: translateY(-3px) scale(1.02);
        box-shadow: 0 6px 20px rgba(255, 107, 53, 0.4);
    }

    .btn-step-primary:active {
        transform: translateY(-1px) scale(0.98);
    }

    .btn-step-secondary {
        background: #f8f9fa;
        color: #666;
        border: 2px solid #e0e0e0;
    }

    .btn-step-secondary:hover {
        background: #e9ecef;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .btn-step:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .checkbox-group {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
        margin-top: 1.5rem;
    }

    .checkbox-group input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-top: 2px;
        flex-shrink: 0;
        accent-color: #FF6B35;
    }

    .checkbox-group label {
        font-size: 0.85rem;
        color: #666;
        line-height: 1.5;
        margin: 0;
    }

    .checkbox-group a {
        color: #FF6B35;
        text-decoration: none;
        font-weight: 600;
    }

    .checkbox-group a:hover {
        text-decoration: underline;
    }

    .form-footer {
        text-align: center;
        padding: 1.5rem;
        border-top: 1px solid #f0f0f0;
        background: #fafafa;
    }

    .form-footer p {
        margin: 0;
        color: #666;
        font-size: 0.9rem;
    }

    .form-footer a {
        color: #FF6B35;
        text-decoration: none;
        font-weight: 600;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }

    .error-message {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.5rem;
        display: block;
    }

    .alert-error {
        background: #f8d7da;
        color: #721c24;
        padding: 1rem;
        border-radius: 12px;
        margin-bottom: 1.5rem;
        border: 1px solid #f5c6cb;
    }

    .alert-error ul {
        margin: 0;
        padding-left: 1.25rem;
    }

    @media (max-width: 768px) {
        .multi-step-form {
            padding: 1rem 0;
        }

        .form-container {
            padding: 0 0.75rem;
        }

        .form-header {
            padding: 1.5rem 1rem;
        }

        .form-header h1 {
            font-size: 1.5rem;
        }

        .form-body {
            padding: 1.5rem 1rem;
        }

        .form-actions {
            flex-direction: column;
        }

        .btn-step {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .form-header {
            padding: 1.25rem 0.75rem;
        }

        .form-header h1 {
            font-size: 1.25rem;
        }

        .form-body {
            padding: 1.25rem 0.75rem;
        }

        .phone-input-group {
            flex-direction: column;
        }

        .phone-input-group select,
        .phone-input-group input[disabled] {
            flex: 1;
            width: 100%;
        }

        .phone-input-group input[type="tel"] {
            flex: 1;
            width: 100%;
        }
    }
</style>
@endpush

@section('content')
<section class="multi-step-form">
    <!-- Particules animées en arrière-plan -->
    <div class="background-particles">
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
        <div class="particle"></div>
    </div>
    <div class="form-container">
        <div class="form-card">
            <div class="form-header">
                    <h1>Créer un compte</h1>
                <p>Rejoignez LEBAYO en quelques étapes simples</p>
            </div>

            <div class="step-indicator">
                <div class="step-dot active" id="stepDot1"></div>
                <div class="step-dot" id="stepDot2"></div>
                <div class="step-dot" id="stepDot3"></div>
                </div>

            <form id="registerForm" method="POST" action="{{ route('register') }}">
                    @csrf

                <div class="form-body">
                    @if ($errors->any())
                        <div class="alert-error">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Étape 1: Informations personnelles -->
                    <div class="step-content active" id="step1">
                        <div class="form-group">
                            <label for="nom">Nom *</label>
                            <input type="text" id="nom" name="nom" value="{{ old('nom') }}" required>
                            @error('nom')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="prenoms">Prénoms *</label>
                            <input type="text" id="prenoms" name="prenoms" value="{{ old('prenoms') }}" required>
                            @error('prenoms')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="commune">Quartier *</label>
                            <input type="text" id="commune" name="commune" value="{{ old('commune') }}" list="quartiers-list" placeholder="Saisissez ou sélectionnez un quartier" required autocomplete="off">
                            <datalist id="quartiers-list">
                                <!-- Quartiers originels (villages des descendants de Dalo) -->
                                <option value="Dalolabia">
                                <option value="Lobia">
                                <option value="Tazibouo">
                                <option value="Gbeuliville">

                                <!-- Quartier colonial/commercial -->
                                <option value="Commerce">

                                <!-- Quartiers résidentiels -->
                                <option value="Evêché">
                                <option value="Huberson">

                                <!-- Quartiers communautaires -->
                                <option value="Baoulé">
                                <option value="Dioulabougou">
                                <option value="Wolof">
                                <option value="Segou">
                                <option value="Cissoko">
                                <option value="Mossibougou">

                                <!-- Quartiers industriels -->
                                <option value="Kennedy">
                                <option value="Soleil">
                                <option value="Marais">
                            </datalist>
                            <small style="color: #666; font-size: 0.8rem; display: block; margin-top: 0.25rem;">Vous pouvez saisir un quartier librement ou choisir parmi les suggestions</small>
                            @error('commune')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-step btn-step-primary" onclick="nextStep()">
                                Suivant <span>→</span>
                            </button>
                        </div>
                    </div>

                    <!-- Étape 2: Contact -->
                    <div class="step-content" id="step2">
                        <div class="form-group">
                            <label for="indicatif">Indicatif pays *</label>
                            <div class="phone-input-group">
                                <input type="text" id="indicatif" value="🇨🇮 +225 (Côte d'Ivoire)" disabled>
                                <input type="hidden" name="indicatif" value="+225">
                                <input type="tel" id="numero_telephone" name="numero_telephone" value="{{ old('numero_telephone') }}" placeholder="77 123 45 67" required pattern="[0-9\s]+">
                            </div>
                            @error('numero_telephone')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                            @error('indicatif')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="email">Adresse email (optionnel)</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="exemple@email.com" autocomplete="email">
                            <small style="color: #666; font-size: 0.8rem; display: block; margin-top: 0.25rem;">Vous pouvez vous connecter avec votre email ou votre numéro de téléphone</small>
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-actions">
                            <button type="button" class="btn-step btn-step-secondary" onclick="prevStep()">
                                <span>←</span> Précédent
                            </button>
                            <button type="button" class="btn-step btn-step-primary" onclick="nextStep()">
                                Suivant <span>→</span>
                            </button>
                        </div>
                    </div>

                    <!-- Étape 3: Sécurité -->
                    <div class="step-content" id="step3">
                        <div class="form-group">
                            <label for="password">Mot de passe *</label>
                            <input type="password" id="password" name="password" required minlength="8">
                            <small style="color: #666; font-size: 0.8rem; display: block; margin-top: 0.25rem;">Minimum 8 caractères</small>
                            @error('password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation">Confirmer le mot de passe *</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" required minlength="8">
                            @error('password_confirmation')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="checkbox-group">
                            <input type="checkbox" id="terms" name="terms" required>
                            <label for="terms">
                                J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> et la <a href="#" target="_blank">politique de confidentialité</a>
                        </label>
                    </div>

                        <div class="form-actions">
                            <button type="button" class="btn-step btn-step-secondary" onclick="prevStep()">
                                <span>←</span> Précédent
                            </button>
                            <button type="submit" class="btn-step btn-step-primary">
                                Créer mon compte <span>✓</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="form-footer">
                <p>Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>
            </div>
        </div>
    </div>
</section>

<script>
let currentStep = 1;
const totalSteps = 3;

function updateStepIndicator() {
    for (let i = 1; i <= totalSteps; i++) {
        const dot = document.getElementById(`stepDot${i}`);
        dot.classList.remove('active', 'completed');

        if (i < currentStep) {
            dot.classList.add('completed');
        } else if (i === currentStep) {
            dot.classList.add('active');
        }
    }
}

function showStep(step) {
    // Masquer toutes les étapes
    for (let i = 1; i <= totalSteps; i++) {
        document.getElementById(`step${i}`).classList.remove('active');
    }

    // Afficher l'étape actuelle
    document.getElementById(`step${step}`).classList.add('active');
    updateStepIndicator();
}

function validateStep(step) {
    const stepContent = document.getElementById(`step${step}`);
    const inputs = stepContent.querySelectorAll('input[required], select[required]');

    for (let input of inputs) {
        if (!input.value.trim()) {
            input.focus();
            input.style.borderColor = '#dc3545';
            setTimeout(() => {
                input.style.borderColor = '';
            }, 3000);
            return false;
        }

        // Validation spéciale pour le mot de passe
        if (input.id === 'password' && input.value.length < 8) {
            input.focus();
            input.style.borderColor = '#dc3545';
            return false;
        }

        // Validation pour la confirmation du mot de passe
        if (input.id === 'password_confirmation') {
            const password = document.getElementById('password').value;
            if (input.value !== password) {
                input.focus();
                input.style.borderColor = '#dc3545';
                return false;
            }
        }
    }

    return true;
}

function nextStep() {
    if (validateStep(currentStep)) {
        if (currentStep < totalSteps) {
            currentStep++;
            showStep(currentStep);
        }
    }
}

function prevStep() {
    if (currentStep > 1) {
        currentStep--;
        showStep(currentStep);
    }
}

// Formatage du numéro de téléphone
document.getElementById('numero_telephone')?.addEventListener('input', function(e) {
    let value = e.target.value.replace(/\D/g, '');
    if (value.length > 0) {
        // Format: XX XXX XX XX
        let formatted = value.match(/.{1,2}/g);
        if (formatted) {
            e.target.value = formatted.join(' ');
        } else {
            e.target.value = value;
        }
    }
});

// Validation en temps réel du mot de passe
document.getElementById('password_confirmation')?.addEventListener('input', function(e) {
    const password = document.getElementById('password').value;
    if (e.target.value && e.target.value !== password) {
        e.target.style.borderColor = '#dc3545';
    } else {
        e.target.style.borderColor = '';
    }
});

// Initialisation
updateStepIndicator();
</script>
@endsection
