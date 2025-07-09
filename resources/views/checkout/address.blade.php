@extends('checkout.layout')

@section('checkout-title', 'Adresse de livraison')

@section('step-content')
<div class="address-step">
    <div class="step-header">
        <div class="step-icon">
            <i class="fas fa-map-marker-alt"></i>
        </div>
        <h2>Sélectionnez votre adresse de livraison</h2>
        <p>Choisissez où vous souhaitez recevoir votre commande</p>
    </div>

    <form action="{{ route('checkout.payment') }}" method="GET" id="addressForm">
        @csrf
        
        @if($addresses->count() > 0)
            <div class="addresses-list">
                <h3>Vos adresses enregistrées</h3>
                
                @foreach($addresses as $address)
                    <div class="address-card">
                        <input type="radio" 
                               name="address_id" 
                               value="{{ $address->id }}" 
                               id="address_{{ $address->id }}"
                               {{ $address->is_default ? 'checked' : '' }}>
                        
                        <label for="address_{{ $address->id }}" class="address-label">
                            <div class="address-header">
                                <div class="address-name">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ $address->name }}</span>
                                    @if($address->is_default)
                                        <span class="default-badge">Par défaut</span>
                                    @endif
                                </div>
                                <div class="address-radio">
                                    <div class="radio-circle"></div>
                                </div>
                            </div>
                            
                            <div class="address-details">
                                <p class="address-full">{{ $address->full_address }}</p>
                                <p class="address-phone">
                                    <i class="fas fa-phone"></i>
                                    {{ $address->phone }}
                                </p>
                                @if($address->additional_info)
                                    <p class="address-info">
                                        <i class="fas fa-info-circle"></i>
                                        {{ $address->additional_info }}
                                    </p>
                                @endif
                            </div>
                        </label>
                    </div>
                @endforeach
            </div>
        @else
            <div class="no-addresses">
                <div class="empty-state">
                    <i class="fas fa-map-marked-alt"></i>
                    <h3>Aucune adresse enregistrée</h3>
                    <p>Vous n'avez pas encore d'adresse enregistrée. Ajoutez-en une pour continuer.</p>
                </div>
            </div>
        @endif

        <div class="add-address-section">
            <button type="button" class="add-address-btn" id="addAddressBtn">
                <i class="fas fa-plus"></i>
                <span>Ajouter une nouvelle adresse</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('checkout-action')
<button type="submit" form="addressForm" class="checkout-btn" id="continueBtn" disabled>
    <span>Continuer vers le paiement</span>
    <i class="fas fa-arrow-right"></i>
</button>
@endsection

@section('content')
@parent

<!-- Modal pour ajouter une adresse - au niveau body -->
<div class="address-modal" id="addAddressModal">
    <div class="address-modal-backdrop"></div>
    <div class="address-modal-dialog">
        <div class="address-modal-content">
            <div class="address-modal-header">
                <h3>Ajouter une nouvelle adresse</h3>
                <button type="button" class="address-modal-close" id="closeModalBtn">&times;</button>
            </div>
            
            <form id="addAddressForm">
                @csrf
                <div class="address-modal-body">
                    <div class="form-group">
                        <label for="address_name">Nom de l'adresse *</label>
                        <input type="text" 
                               id="address_name" 
                               name="name" 
                               placeholder="ex: Maison, Bureau, Chez un ami..."
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address_street">Adresse complète *</label>
                        <input type="text" 
                               id="address_street" 
                               name="street" 
                               placeholder="Numéro, rue, quartier..."
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address_city">Ville *</label>
                        <input type="text" 
                               id="address_city" 
                               name="city" 
                               placeholder="ex: Abidjan, Bouaké..."
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address_phone">Numéro de téléphone *</label>
                        <input type="tel" 
                               id="address_phone" 
                               name="phone" 
                               placeholder="ex: +225 01 23 45 67 89"
                               required>
                    </div>
                    
                    <div class="form-group">
                        <label for="address_info">Informations supplémentaires</label>
                        <textarea id="address_info" 
                                  name="additional_info" 
                                  placeholder="Instructions de livraison, points de repère..."
                                  rows="3"></textarea>
                    </div>
                </div>
                
                <div class="address-modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelAddressBtn">Annuler</button>
                    <button type="submit" class="btn btn-primary" id="saveAddressBtn">
                        <span>Ajouter l'adresse</span>
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.address-step {
    text-align: center;
}

.step-header {
    margin-bottom: 2rem;
}

.step-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    font-size: 2rem;
    color: white;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
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

.addresses-list {
    text-align: left;
    margin-bottom: 2rem;
}

.addresses-list h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
}

.address-card {
    margin-bottom: 1rem;
    position: relative;
}

.address-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.address-label {
    display: block;
    background: white;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    padding: 1.5rem;
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.address-label:hover {
    border-color: var(--primary-color);
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.1);
}

.address-card input[type="radio"]:checked + .address-label {
    border-color: var(--primary-color);
    background: rgba(255, 107, 53, 0.05);
}

.address-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
}

.address-name {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-dark);
}

.address-name i {
    color: var(--primary-color);
}

.default-badge {
    background: var(--success-color);
    color: white;
    padding: 0.25rem 0.5rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
}

.address-radio {
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

.address-card input[type="radio"]:checked + .address-label .radio-circle {
    border-color: var(--primary-color);
}

.address-card input[type="radio"]:checked + .address-label .radio-circle::after {
    opacity: 1;
}

.address-details p {
    margin: 0.5rem 0;
    color: var(--text-light);
    font-size: 0.875rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.address-details p:last-child {
    margin-bottom: 0;
}

.address-details i {
    color: var(--primary-color);
    width: 16px;
    text-align: center;
}

.address-full {
    font-weight: 500;
    color: var(--text-dark) !important;
}

.no-addresses {
    text-align: center;
    margin-bottom: 2rem;
}

.empty-state {
    background: #f8f9fa;
    border: 1px solid #e5e7eb;
    border-radius: 12px;
    padding: 3rem 2rem;
}

.empty-state i {
    font-size: 3rem;
    color: var(--text-light);
    margin-bottom: 1rem;
}

.empty-state h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-light);
    font-size: 1rem;
}

.add-address-section {
    text-align: center;
}

.add-address-btn {
    background: transparent;
    border: 2px dashed var(--primary-color);
    color: var(--primary-color);
    padding: 1rem 2rem;
    border-radius: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 0 auto;
}

.add-address-btn:hover {
    background: rgba(255, 107, 53, 0.1);
    border-color: var(--secondary-color);
}

/* Modal styles */
.address-modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    z-index: 99999 !important;
    display: none;
}

.address-modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100% !important;
    height: 100% !important;
    background: rgba(0, 0, 0, 0.6) !important;
    backdrop-filter: blur(3px);
}

.address-modal-dialog {
    position: fixed !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: 90% !important;
    max-width: 500px !important;
    max-height: 85vh !important;
    z-index: 100000 !important;
}

.address-modal-content {
    background: white !important;
    border-radius: 16px !important;
    width: 100% !important;
    max-height: 85vh !important;
    overflow-y: auto !important;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.25) !important;
    animation: addressModalSlideIn 0.3s ease-out;
}

@keyframes addressModalSlideIn {
    from {
        opacity: 0;
        transform: translate(-50%, -60%) scale(0.9);
    }
    to {
        opacity: 1;
        transform: translate(-50%, -50%) scale(1);
    }
}

/* Empêcher le défilement du body quand le modal est ouvert */
body.modal-open {
    overflow: hidden !important;
}

.address-modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.address-modal-header h3 {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
}

.address-modal-close {
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
    color: #6b7280;
    padding: 0.5rem;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}

.address-modal-close:hover {
    background: #f3f4f6;
    color: var(--text-dark);
}

.address-modal-body {
    padding: 1.5rem;
}

.form-group {
    margin-bottom: 1rem;
}

.form-group label {
    display: block;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.form-group input,
.form-group textarea {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.form-group textarea {
    resize: vertical;
    min-height: 60px;
}

.address-modal-footer {
    display: flex;
    gap: 1rem;
    padding: 1.5rem;
    border-top: 1px solid #e5e7eb;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-secondary {
    background: #f3f4f6;
    color: var(--text-dark);
}

.btn-secondary:hover {
    background: #e5e7eb;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
    flex: 1;
}

.btn-primary:hover {
    background: var(--secondary-color);
}

@media (max-width: 768px) {
    .address-modal-dialog {
        width: 95% !important;
        max-height: 90vh !important;
    }
    
    .address-modal-content {
        max-height: 90vh !important;
    }
    
    .address-modal-header,
    .address-modal-body,
    .address-modal-footer {
        padding: 1rem;
    }
    
    .address-modal-footer {
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
    const addressForm = document.getElementById('addressForm');
    const continueBtn = document.getElementById('continueBtn');
    const addAddressBtn = document.getElementById('addAddressBtn');
    const addAddressModal = document.getElementById('addAddressModal');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelAddressBtn = document.getElementById('cancelAddressBtn');
    const addAddressForm = document.getElementById('addAddressForm');
    const modalBackdrop = document.querySelector('.address-modal-backdrop');
    
    // Vérifier si une adresse est sélectionnée
    function checkAddressSelection() {
        const selectedAddress = document.querySelector('input[name="address_id"]:checked');
        continueBtn.disabled = !selectedAddress;
    }
    
    // Écouter les changements de sélection d'adresse
    document.querySelectorAll('input[name="address_id"]').forEach(radio => {
        radio.addEventListener('change', checkAddressSelection);
    });
    
    // Vérification initiale
    checkAddressSelection();
    
    // Ouvrir le modal
    addAddressBtn.addEventListener('click', function() {
        addAddressModal.style.display = 'block';
        document.body.classList.add('modal-open');
    });
    
    // Fermer le modal
    function closeModal() {
        addAddressModal.style.display = 'none';
        document.body.classList.remove('modal-open');
        addAddressForm.reset();
    }
    
    closeModalBtn.addEventListener('click', closeModal);
    cancelAddressBtn.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);
    
    // Fermer avec Escape
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && addAddressModal.style.display === 'block') {
            closeModal();
        }
    });
    
    // Soumettre le formulaire d'ajout d'adresse
    addAddressForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(addAddressForm);
        const saveBtn = document.getElementById('saveAddressBtn');
        
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<span>Ajout en cours...</span><i class="fas fa-spinner fa-spin"></i>';
        
        fetch('{{ route("checkout.address.store") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Recharger la page pour afficher la nouvelle adresse
                location.reload();
            } else {
                alert('Erreur lors de l\'ajout de l\'adresse');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        })
        .finally(() => {
            saveBtn.disabled = false;
            saveBtn.innerHTML = '<span>Ajouter l\'adresse</span><i class="fas fa-plus"></i>';
        });
    });
});
</script>
@endpush 