@extends('layouts.app')

@section('title', 'Mes Adresses')

@section('content')
<div class="profile-section">
    <div class="container">
        <!-- Header du profil -->
        <div class="profile-header">
            <div class="profile-avatar">
                <div class="avatar-circle">
                    {{ substr(auth()->user()->prenoms ?? 'U', 0, 1) }}
                </div>
            </div>
            <div class="profile-info">
                <h1>{{ auth()->user()->full_name }}</h1>
                <p class="profile-subtitle">Gestion de vos adresses de livraison</p>
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
            <a href="{{ route('profile.addresses') }}" class="nav-item active">
                <i class="nav-icon">📍</i>
                Mes adresses
            </a>
        </div>

        <!-- Contenu principal -->
        <div class="profile-content">
            <!-- Bouton d'ajout -->
            <div class="addresses-header">
                <h2>Mes adresses de livraison</h2>
                <button class="btn btn-primary" onclick="openAddressModal()">
                    <i class="btn-icon">➕</i>
                    Ajouter une adresse
                </button>
            </div>

            @if($addresses->count() > 0)
                <!-- Liste des adresses -->
                <div class="addresses-grid">
                    @foreach($addresses as $address)
                        <div class="address-card {{ $address->is_default ? 'default-address' : '' }}">
                            @if($address->is_default)
                                <div class="default-badge">
                                    <i class="badge-icon">⭐</i>
                                    Adresse par défaut
                                </div>
                            @endif

                            <div class="address-info">
                                <h3 class="address-name">{{ $address->name }}</h3>
                                <div class="address-details">
                                    <div class="address-line">
                                        <i class="address-icon">📍</i>
                                        {{ $address->street }}
                                    </div>
                                    <div class="address-line">
                                        <i class="address-icon">🏙️</i>
                                        {{ $address->city }}, {{ $address->country }}
                                    </div>
                                    <div class="address-line">
                                        <i class="address-icon">📞</i>
                                        {{ $address->phone }}
                                    </div>
                                    @if($address->additional_info)
                                        <div class="address-line">
                                            <i class="address-icon">ℹ️</i>
                                            {{ $address->additional_info }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="address-actions">
                                @if(!$address->is_default)
                                    <form method="POST" action="{{ route('profile.addresses.set-default', $address) }}" style="display: inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-primary">
                                            <i class="btn-icon">⭐</i>
                                            Définir par défaut
                                        </button>
                                    </form>
                                @endif

                                <button class="btn btn-outline" onclick="editAddress({{ $address->id }}, '{{ $address->name }}', '{{ $address->street }}', '{{ $address->city }}', '{{ $address->phone }}', '{{ $address->additional_info }}')">
                                    <i class="btn-icon">✏️</i>
                                    Modifier
                                </button>

                                @if(!$address->is_default && $addresses->count() > 1)
                                    <form method="POST" action="{{ route('profile.addresses.delete', $address) }}" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette adresse ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="btn-icon">🗑️</i>
                                            Supprimer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-icon">📍</div>
                    <h3>Aucune adresse</h3>
                    <p>Vous n'avez pas encore ajouté d'adresse de livraison.</p>
                    <button class="btn btn-primary" onclick="openAddressModal()">
                        <i class="btn-icon">➕</i>
                        Ajouter votre première adresse
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal d'ajout/modification d'adresse -->
<div class="modal-overlay" id="addressModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="modalTitle">Ajouter une adresse</h3>
            <button class="modal-close" onclick="closeAddressModal()">❌</button>
        </div>
        
        <form id="addressForm" method="POST" action="{{ route('profile.addresses.store') }}">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <label for="name">Nom de l'adresse</label>
                    <input type="text" id="name" name="name" placeholder="Ex: Maison, Bureau, Chez maman..." required>
                </div>

                <div class="form-group">
                    <label for="street">Adresse complète</label>
                    <input type="text" id="street" name="street" placeholder="Ex: Rue 12, Cocody, près de..." required>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="city">Ville</label>
                        <input type="text" id="city" name="city" placeholder="Ex: Abidjan" required>
                    </div>
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="tel" id="phone" name="phone" placeholder="Ex: +225 XX XX XX XX" required>
                    </div>
                </div>

                <div class="form-group">
                    <label for="additional_info">Informations supplémentaires (optionnel)</label>
                    <textarea id="additional_info" name="additional_info" placeholder="Indications pour le livreur..." rows="3"></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-outline" onclick="closeAddressModal()">Annuler</button>
                <button type="submit" class="btn btn-primary">
                    <i class="btn-icon">💾</i>
                    Sauvegarder
                </button>
            </div>
        </form>
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

.addresses-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    background: white;
    padding: 24px;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.addresses-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.addresses-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 20px;
}

.address-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    transition: transform 0.3s ease;
    position: relative;
}

.address-card:hover {
    transform: translateY(-2px);
}

.default-address {
    border: 2px solid var(--primary-color);
}

.default-badge {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    padding: 8px 16px;
    font-size: 0.875rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 6px;
}

.badge-icon {
    font-size: 1rem;
}

.address-info {
    padding: 24px;
}

.address-name {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 16px;
}

.address-details {
    display: grid;
    gap: 8px;
}

.address-line {
    display: flex;
    align-items: center;
    gap: 8px;
    color: var(--text-light);
    font-size: 0.9rem;
}

.address-icon {
    font-size: 1rem;
    width: 20px;
    flex-shrink: 0;
}

.address-actions {
    display: flex;
    gap: 8px;
    padding: 16px 24px;
    background: #f8f9fa;
    border-top: 1px solid #f0f0f0;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 12px;
    border: none;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
    font-size: 0.875rem;
    white-space: nowrap;
}

.btn-primary {
    background: var(--primary-color);
    color: white;
}

.btn-primary:hover {
    background: var(--accent-color);
}

.btn-outline {
    background: white;
    color: var(--text-dark);
    border: 1px solid #e2e8f0;
}

.btn-outline:hover {
    background: var(--text-dark);
    color: white;
}

.btn-outline-primary {
    background: white;
    color: var(--primary-color);
    border: 1px solid var(--primary-color);
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background: #c53030;
}

.btn-icon {
    font-size: 0.875rem;
}

.empty-state {
    text-align: center;
    padding: 80px 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
}

.empty-icon {
    font-size: 4rem;
    margin-bottom: 20px;
    opacity: 0.5;
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 12px;
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 30px;
    font-size: 1rem;
}

/* Modal */
.modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 1000;
    display: none;
    align-items: center;
    justify-content: center;
    backdrop-filter: blur(4px);
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    border-radius: 16px;
    width: 90%;
    max-width: 500px;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 24px 24px 16px;
    border-bottom: 1px solid #f0f0f0;
}

.modal-header h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.modal-close {
    background: none;
    border: none;
    font-size: 1rem;
    cursor: pointer;
    padding: 4px;
    border-radius: 4px;
    transition: background 0.3s ease;
}

.modal-close:hover {
    background: #f0f0f0;
}

.modal-body {
    padding: 24px;
    display: grid;
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

.form-group input,
.form-group textarea {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    font-size: 1rem;
    transition: border-color 0.3s ease;
    background: white;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--primary-color);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 12px;
    padding: 16px 24px 24px;
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

    .addresses-header {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .addresses-grid {
        grid-template-columns: 1fr;
    }

    .address-actions {
        flex-direction: column;
        gap: 8px;
    }

    .btn {
        justify-content: center;
    }

    .form-row {
        grid-template-columns: 1fr;
    }

    .modal-footer {
        flex-direction: column;
        gap: 8px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let isEditMode = false;
let editAddressId = null;

function openAddressModal() {
    isEditMode = false;
    editAddressId = null;
    document.getElementById('modalTitle').textContent = 'Ajouter une adresse';
    document.getElementById('addressForm').action = '{{ route("profile.addresses.store") }}';
    document.getElementById('addressForm').method = 'POST';
    
    // Clear form
    document.getElementById('addressForm').reset();
    
    // Remove method input if exists
    const methodInput = document.querySelector('input[name="_method"]');
    if (methodInput) {
        methodInput.remove();
    }
    
    document.getElementById('addressModal').classList.add('active');
}

function editAddress(id, name, street, city, phone, additionalInfo) {
    isEditMode = true;
    editAddressId = id;
    document.getElementById('modalTitle').textContent = 'Modifier l\'adresse';
    document.getElementById('addressForm').action = `{{ route("profile.addresses.update", ":id") }}`.replace(':id', id);
    
    // Add method input for PUT
    let methodInput = document.querySelector('input[name="_method"]');
    if (!methodInput) {
        methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        document.getElementById('addressForm').appendChild(methodInput);
    }
    methodInput.value = 'PUT';
    
    // Fill form with existing data
    document.getElementById('name').value = name;
    document.getElementById('street').value = street;
    document.getElementById('city').value = city;
    document.getElementById('phone').value = phone;
    document.getElementById('additional_info').value = additionalInfo || '';
    
    document.getElementById('addressModal').classList.add('active');
}

function closeAddressModal() {
    document.getElementById('addressModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('addressModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddressModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeAddressModal();
    }
});
</script>
@endpush 