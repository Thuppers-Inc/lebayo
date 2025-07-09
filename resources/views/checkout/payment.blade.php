@extends('checkout.layout')

@section('checkout-title', 'Mode de paiement')

@section('step-content')
<div class="payment-step">
    <div class="step-header">
        <div class="step-icon">
            <i class="fas fa-credit-card"></i>
        </div>
        <h2>Choisissez votre mode de paiement</h2>
        <p>Sélectionnez comment vous souhaitez payer votre commande</p>
    </div>

    <form action="{{ route('checkout.confirm') }}" method="GET" id="paymentForm">
        <input type="hidden" name="address_id" value="{{ $selectedAddress->id }}">
        
        <div class="payment-methods">
            <h3>Modes de paiement disponibles</h3>
            
            <!-- Paiement à la livraison -->
            <div class="payment-method-card">
                <input type="radio" 
                       name="payment_method" 
                       value="cash_on_delivery" 
                       id="cash_on_delivery"
                       checked>
                
                <label for="cash_on_delivery" class="payment-method-label">
                    <div class="payment-method-header">
                        <div class="payment-method-info">
                            <div class="payment-method-icon">
                                <i class="fas fa-money-bill-wave"></i>
                            </div>
                            <div class="payment-method-details">
                                <h4>Paiement à la livraison</h4>
                                <p>Payez en espèces à la réception de votre commande</p>
                            </div>
                        </div>
                        <div class="payment-method-radio">
                            <div class="radio-circle"></div>
                        </div>
                    </div>
                    
                    <div class="payment-method-features">
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Paiement sécurisé</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Aucun frais supplémentaire</span>
                        </div>
                        <div class="feature-item">
                            <i class="fas fa-check"></i>
                            <span>Simple et rapide</span>
                        </div>
                    </div>
                </label>
            </div>

            <!-- Autres modes de paiement (désactivés pour l'instant) -->
            <div class="payment-method-card disabled">
                <input type="radio" 
                       name="payment_method" 
                       value="card" 
                       id="card"
                       disabled>
                
                <label for="card" class="payment-method-label">
                    <div class="payment-method-header">
                        <div class="payment-method-info">
                            <div class="payment-method-icon">
                                <i class="fas fa-credit-card"></i>
                            </div>
                            <div class="payment-method-details">
                                <h4>Carte bancaire</h4>
                                <p>Payez avec votre carte Visa ou Mastercard</p>
                            </div>
                        </div>
                        <div class="coming-soon-badge">
                            Bientôt disponible
                        </div>
                    </div>
                </label>
            </div>

            <div class="payment-method-card disabled">
                <input type="radio" 
                       name="payment_method" 
                       value="mobile_money" 
                       id="mobile_money"
                       disabled>
                
                <label for="mobile_money" class="payment-method-label">
                    <div class="payment-method-header">
                        <div class="payment-method-info">
                            <div class="payment-method-icon">
                                <i class="fas fa-mobile-alt"></i>
                            </div>
                            <div class="payment-method-details">
                                <h4>Mobile Money</h4>
                                <p>Payez avec Orange Money, MTN Money ou Moov Money</p>
                            </div>
                        </div>
                        <div class="coming-soon-badge">
                            Bientôt disponible
                        </div>
                    </div>
                </label>
            </div>
        </div>

        <div class="payment-info">
            <div class="info-card">
                <h4>Informations importantes</h4>
                <ul>
                    <li>
                        <i class="fas fa-shield-alt"></i>
                        <span>Vos informations de paiement sont sécurisées</span>
                    </li>
                    <li>
                        <i class="fas fa-clock"></i>
                        <span>Le paiement est effectué uniquement à la livraison</span>
                    </li>
                    <li>
                        <i class="fas fa-undo"></i>
                        <span>Vous pouvez annuler votre commande avant la livraison</span>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>
@endsection

@section('checkout-action')
<button type="submit" form="paymentForm" class="checkout-btn">
    <span>Continuer vers la confirmation</span>
    <i class="fas fa-arrow-right"></i>
</button>
@endsection

@push('styles')
<style>
.payment-step {
    text-align: center;
}

.step-header {
    margin-bottom: 2rem;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #17a2b8, #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 4px 15px rgba(23, 162, 184, 0.3);
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

.payment-methods {
    text-align: left;
    margin-bottom: 2rem;
}

.payment-methods h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
}

.payment-method-card {
    margin-bottom: 1rem;
    position: relative;
}

.payment-method-card.disabled {
    opacity: 0.6;
}

.payment-method-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.payment-method-label {
    display: block;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.payment-method-card:not(.disabled) .payment-method-label:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.1);
}

.payment-method-card input[type="radio"]:checked + .payment-method-label {
    border-color: var(--primary-color);
    background: rgba(255, 107, 53, 0.05);
}

.payment-method-card.disabled .payment-method-label {
    cursor: not-allowed;
    background: #f8f9fa;
}

.payment-method-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.payment-method-info {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.payment-method-icon {
    width: 50px;
    height: 50px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.payment-method-card.disabled .payment-method-icon {
    background: #6b7280;
}

.payment-method-details h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.payment-method-details p {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

.payment-method-radio {
    position: relative;
}

.radio-circle {
    width: 20px;
    height: 20px;
    border: 2px solid #e5e7eb;
    border-radius: 50%;
    position: relative;
    transition: all 0.3s ease;
}

.radio-circle::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 8px;
    height: 8px;
    background: var(--primary-color);
    border-radius: 50%;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.payment-method-card input[type="radio"]:checked + .payment-method-label .radio-circle {
    border-color: var(--primary-color);
}

.payment-method-card input[type="radio"]:checked + .payment-method-label .radio-circle::after {
    opacity: 1;
}

.coming-soon-badge {
    background: #fbbf24;
    color: #92400e;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.payment-method-features {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    font-size: 0.875rem;
}

.feature-item i {
    color: var(--success-color);
    font-size: 1rem;
}

.payment-info {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    text-align: left;
}

.info-card h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.info-card ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.info-card li {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 0.75rem;
    color: var(--text-dark);
}

.info-card li:last-child {
    margin-bottom: 0;
}

.info-card li i {
    color: var(--primary-color);
    font-size: 1.125rem;
    width: 20px;
    text-align: center;
}

@media (max-width: 768px) {
    .payment-method-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .payment-method-info {
        flex-direction: column;
        text-align: center;
    }
    
    .payment-method-features {
        grid-template-columns: 1fr;
    }
    
    .feature-item {
        justify-content: center;
    }
}
</style>
@endpush 