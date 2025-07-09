@extends('checkout.layout')

@section('checkout-title', 'Compte')

@section('step-content')
<div class="account-step">
    <div class="step-header">
        <div class="step-icon">
            <i class="fas fa-user-check"></i>
        </div>
        <h2>Compte vérifié</h2>
        <p>Vous êtes connecté et prêt à passer votre commande</p>
    </div>

    <div class="account-info">
        <div class="user-card">
            <div class="user-avatar">
                <span class="avatar-initials">{{ $user->initials }}</span>
            </div>
            <div class="user-details">
                <h3>{{ $user->full_name }}</h3>
                <p class="user-email">{{ $user->email }}</p>
                <p class="user-phone">{{ $user->formatted_phone }}</p>
            </div>
            <div class="verified-badge">
                <i class="fas fa-check-circle"></i>
                <span>Vérifié</span>
            </div>
        </div>

        <div class="account-benefits">
            <h4>Avantages de votre compte</h4>
            <ul>
                <li>
                    <i class="fas fa-shipping-fast"></i>
                    <span>Livraison rapide avec suivi en temps réel</span>
                </li>
                <li>
                    <i class="fas fa-heart"></i>
                    <span>Sauvegarde de vos adresses favorites</span>
                </li>
                <li>
                    <i class="fas fa-history"></i>
                    <span>Historique de vos commandes</span>
                </li>
                <li>
                    <i class="fas fa-star"></i>
                    <span>Programme de fidélité et récompenses</span>
                </li>
            </ul>
        </div>
    </div>

    <div class="step-actions">
        <div class="action-info">
            <p>Cette étape est automatiquement validée car vous êtes connecté</p>
        </div>
    </div>
</div>
@endsection

@section('checkout-action')
<a href="{{ route('checkout.address') }}" class="checkout-btn">
    <span>Continuer vers l'adresse</span>
    <i class="fas fa-arrow-right"></i>
</a>
@endsection

@push('styles')
<style>
.account-step {
    text-align: center;
}

.step-header {
    margin-bottom: 2rem;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--success-color), #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
}

.step-header h2 {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.step-header p {
    color: var(--text-light);
    font-size: 1rem;
}

.account-info {
    margin-bottom: 2rem;
}

.user-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 2rem;
    position: relative;
}

.user-avatar {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.user-details {
    flex: 1;
    text-align: left;
}

.user-details h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.user-email {
    color: var(--text-light);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.user-phone {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

.verified-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: var(--success-color);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
}

.account-benefits {
    text-align: left;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
}

.account-benefits h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.account-benefits ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.account-benefits li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: var(--text-dark);
}

.account-benefits li:last-child {
    margin-bottom: 0;
}

.account-benefits li i {
    color: var(--primary-color);
    font-size: 1.125rem;
}

.step-actions {
    text-align: center;
}

.action-info {
    background: #e7f3ff;
    border: 1px solid #b3d9ff;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.action-info p {
    color: #0056b3;
    font-size: 0.875rem;
    margin: 0;
}

@media (max-width: 768px) {
    .user-card {
        flex-direction: column;
        text-align: center;
    }
    
    .user-details {
        text-align: center;
    }
    
    .verified-badge {
        margin-top: 1rem;
    }
}
</style>
@endpush 