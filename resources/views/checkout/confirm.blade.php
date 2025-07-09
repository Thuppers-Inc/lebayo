@extends('checkout.layout')

@section('checkout-title', 'Confirmer votre commande')

@section('step-content')
<div class="confirm-step">
    <div class="step-header">
        <div class="step-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Vérifiez et confirmez votre commande</h2>
        <p>Vérifiez tous les détails avant de finaliser votre commande</p>
    </div>

    <form action="{{ route('checkout.store') }}" method="POST" id="confirmForm">
        @csrf
        <input type="hidden" name="address_id" value="{{ $selectedAddress->id }}">
        <input type="hidden" name="payment_method" value="{{ $paymentMethod }}">
        
        <div class="order-summary">
            <!-- Adresse de livraison -->
            <div class="summary-section">
                <div class="section-header">
                    <h3>
                        <i class="fas fa-map-marker-alt"></i>
                        Adresse de livraison
                    </h3>
                    <a href="{{ route('checkout.address') }}" class="edit-link">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                </div>
                <div class="section-content">
                    <div class="address-info">
                        <div class="address-name">{{ $selectedAddress->name }}</div>
                        <div class="address-details">{{ $selectedAddress->full_address }}</div>
                        <div class="address-phone">{{ $selectedAddress->phone }}</div>
                        @if($selectedAddress->additional_info)
                            <div class="address-additional">{{ $selectedAddress->additional_info }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Mode de paiement -->
            <div class="summary-section">
                <div class="section-header">
                    <h3>
                        <i class="fas fa-credit-card"></i>
                        Mode de paiement
                    </h3>
                    <a href="{{ route('checkout.payment') }}?address_id={{ $selectedAddress->id }}" class="edit-link">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                </div>
                <div class="section-content">
                    <div class="payment-method-info">
                        <div class="payment-method-icon">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                        <div class="payment-method-details">
                            <div class="payment-method-name">Paiement à la livraison</div>
                            <div class="payment-method-description">Payez en espèces à la réception</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles de la commande -->
            <div class="summary-section">
                <div class="section-header">
                    <h3>
                        <i class="fas fa-shopping-bag"></i>
                        Vos articles ({{ $totalItems }})
                    </h3>
                    <a href="{{ route('cart.index') }}" class="edit-link">
                        <i class="fas fa-edit"></i>
                        Modifier
                    </a>
                </div>
                <div class="section-content">
                    <div class="order-items-list">
                        @foreach($cartItems as $item)
                            <div class="order-item-detail">
                                <div class="item-image">
                                    <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                </div>
                                <div class="item-info">
                                    <div class="item-name">{{ $item->product->name }}</div>
                                    <div class="item-commerce">{{ $item->product->commerce->name }}</div>
                                    <div class="item-quantity">Quantité: {{ $item->quantity }}</div>
                                </div>
                                <div class="item-price">{{ $item->formatted_subtotal }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Temps de livraison estimé -->
            <div class="summary-section">
                <div class="section-header">
                    <h3>
                        <i class="fas fa-clock"></i>
                        Temps de livraison estimé
                    </h3>
                </div>
                <div class="section-content">
                    <div class="delivery-estimate">
                        <div class="estimate-time">
                            <span class="time-range">25-35 minutes</span>
                            <span class="time-description">à partir de la confirmation</span>
                        </div>
                        <div class="estimate-note">
                            <i class="fas fa-info-circle"></i>
                            <span>Le temps peut varier selon la distance et le trafic</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Notes spéciales -->
            <div class="summary-section">
                <div class="section-header">
                    <h3>
                        <i class="fas fa-sticky-note"></i>
                        Notes spéciales (optionnel)
                    </h3>
                </div>
                <div class="section-content">
                    <textarea name="notes" 
                              id="order_notes" 
                              placeholder="Instructions spéciales pour la livraison (étage, code d'accès, etc.)..."
                              rows="3"></textarea>
                </div>
            </div>
        </div>

        <div class="order-terms">
            <label class="terms-checkbox">
                <input type="checkbox" name="terms_accepted" required>
                <span class="checkmark"></span>
                <span class="terms-text">
                    J'accepte les 
                    <a href="#" target="_blank">conditions générales</a> 
                    et la 
                    <a href="#" target="_blank">politique de confidentialité</a>
                </span>
            </label>
        </div>
    </form>
</div>
@endsection

@section('checkout-action')
<button type="submit" form="confirmForm" class="checkout-btn" id="confirmOrderBtn">
    <span>Confirmer ma commande</span>
    <i class="fas fa-check"></i>
</button>
@endsection

@push('styles')
<style>
.confirm-step {
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

.order-summary {
    text-align: left;
    margin-bottom: 2rem;
}

.summary-section {
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    margin-bottom: 1.5rem;
    overflow: hidden;
}

.section-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.section-header h3 {
    margin: 0;
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-header i {
    color: var(--primary-color);
}

.edit-link {
    color: var(--primary-color);
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    transition: color 0.3s ease;
}

.edit-link:hover {
    color: var(--secondary-color);
}

.section-content {
    padding: 1.5rem;
}

/* Adresse de livraison */
.address-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.address-name {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.125rem;
}

.address-details {
    color: var(--text-dark);
    font-size: 1rem;
}

.address-phone {
    color: var(--text-light);
    font-size: 0.875rem;
}

.address-additional {
    color: var(--text-light);
    font-size: 0.875rem;
    font-style: italic;
}

/* Mode de paiement */
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

.payment-method-name {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.125rem;
}

.payment-method-description {
    color: var(--text-light);
    font-size: 0.875rem;
}

/* Articles de la commande */
.order-items-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.order-item-detail {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f8f9fa;
    border-radius: 8px;
}

.order-item-detail .item-image {
    width: 60px;
    height: 60px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.order-item-detail .item-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.order-item-detail .item-info {
    flex: 1;
}

.order-item-detail .item-name {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.order-item-detail .item-commerce {
    color: var(--primary-color);
    font-size: 0.875rem;
    margin-bottom: 0.25rem;
}

.order-item-detail .item-quantity {
    color: var(--text-light);
    font-size: 0.875rem;
}

.order-item-detail .item-price {
    font-weight: 700;
    color: var(--text-dark);
    font-size: 1.125rem;
}

/* Temps de livraison estimé */
.delivery-estimate {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.estimate-time {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.time-range {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--success-color);
}

.time-description {
    color: var(--text-light);
    font-size: 0.875rem;
}

.estimate-note {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    font-size: 0.875rem;
}

.estimate-note i {
    color: var(--primary-color);
}

/* Notes spéciales */
#order_notes {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-family: inherit;
    font-size: 1rem;
    resize: vertical;
    min-height: 80px;
    transition: border-color 0.3s ease;
}

#order_notes:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

#order_notes::placeholder {
    color: #9ca3af;
}

/* Conditions générales */
.order-terms {
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    text-align: left;
}

.terms-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    cursor: pointer;
    font-size: 0.875rem;
    line-height: 1.5;
}

.terms-checkbox input[type="checkbox"] {
    display: none;
}

.checkmark {
    width: 20px;
    height: 20px;
    border: 2px solid #d1d5db;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.3s ease;
    margin-top: 2px;
}

.checkmark::after {
    content: '';
    width: 6px;
    height: 10px;
    border: solid white;
    border-width: 0 2px 2px 0;
    transform: rotate(45deg);
    opacity: 0;
    transition: opacity 0.3s ease;
}

.terms-checkbox input[type="checkbox"]:checked + .checkmark {
    background: var(--primary-color);
    border-color: var(--primary-color);
}

.terms-checkbox input[type="checkbox"]:checked + .checkmark::after {
    opacity: 1;
}

.terms-text {
    color: var(--text-dark);
}

.terms-text a {
    color: var(--primary-color);
    text-decoration: none;
    font-weight: 600;
}

.terms-text a:hover {
    text-decoration: underline;
}

@media (max-width: 768px) {
    .section-header {
        flex-direction: column;
        gap: 0.5rem;
        text-align: center;
    }
    
    .order-item-detail {
        flex-direction: column;
        text-align: center;
    }
    
    .order-item-detail .item-info {
        text-align: center;
    }
    
    .payment-method-info {
        flex-direction: column;
        text-align: center;
    }
    
    .estimate-time {
        flex-direction: column;
        text-align: center;
    }
    
    .terms-checkbox {
        flex-direction: column;
        text-align: center;
    }
    
    .checkmark {
        align-self: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const confirmForm = document.getElementById('confirmForm');
    const confirmBtn = document.getElementById('confirmOrderBtn');
    const termsCheckbox = document.querySelector('input[name="terms_accepted"]');
    
    // Vérifier les conditions générales
    function checkTerms() {
        confirmBtn.disabled = !termsCheckbox.checked;
    }
    
    termsCheckbox.addEventListener('change', checkTerms);
    
    // Vérification initiale
    checkTerms();
    
    // Soumettre le formulaire
    confirmForm.addEventListener('submit', function(e) {
        if (!termsCheckbox.checked) {
            e.preventDefault();
            alert('Vous devez accepter les conditions générales pour continuer');
            return;
        }
        
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<span>Traitement en cours...</span><i class="fas fa-spinner fa-spin"></i>';
    });
});
</script>
@endpush 