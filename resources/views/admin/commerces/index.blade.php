@extends('admin.layouts.master')

@section('title', 'Commerces')
@section('description', 'Gestion des commerces partenaires')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Section titre -->
        <div class="admin-title-card card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">Commerces Partenaires</h4>
                        <p class="text-muted mb-0">Gérez les commerces inscrits sur votre plateforme</p>
                        <p class="text-muted mb-0">{{ $commerces->count() }} commerce(s) au total</p>
                    </div>
                    <button type="button" class="btn btn-admin-primary" 
                            data-bs-toggle="modal" data-bs-target="#commerceModal"
                            onclick="openCreateCommerceModal()">
                        <i class="bx bx-plus"></i> Nouveau Commerce
                    </button>
                </div>
            </div>
        </div>

        <!-- Zone d'alertes -->
        @if(session('success'))
            <div class="admin-alert alert alert-success alert-dismissible" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="admin-alert alert alert-danger alert-dismissible" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Barre de recherche et filtres -->
        <div class="admin-card card mb-4">
            <div class="card-body">
                <div class="row align-items-center">
                    <!-- Recherche -->
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="position-relative">
                            <input type="text" 
                                   id="searchInput" 
                                   class="form-control admin-form-control ps-5" 
                                   placeholder="Rechercher un commerce..."
                                   autocomplete="off">
                            <i class="bx bx-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                            <button type="button" 
                                    id="clearSearch" 
                                    class="btn position-absolute top-50 end-0 translate-middle-y me-2 d-none"
                                    style="border: none; background: none; padding: 0; width: 20px; height: 20px;">
                                <i class="bx bx-x text-muted"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="typeFilter" class="form-select admin-form-control">
                                    <option value="">Tous les types</option>
                                    @foreach($commerceTypes as $type)
                                        <option value="{{ $type->name }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="cityFilter" class="form-select admin-form-control">
                                    <option value="">Toutes les villes</option>
                                    @foreach($commerces->pluck('city')->unique()->sort() as $city)
                                        <option value="{{ $city }}">{{ $city }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="statusFilter" class="form-select admin-form-control">
                                    <option value="">Tous les statuts</option>
                                    <option value="active">Actifs</option>
                                    <option value="inactive">Inactifs</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="sortBy" class="form-select admin-form-control">
                                    <option value="name-asc">Nom A-Z</option>
                                    <option value="name-desc">Nom Z-A</option>
                                    <option value="date-desc">Plus récents</option>
                                    <option value="date-asc">Plus anciens</option>
                                    <option value="city-asc">Ville A-Z</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Compteur de résultats -->
                <div class="mt-3 d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        <span id="resultsCount">{{ $commerces->count() }}</span> commerce(s) trouvé(s)
                        <span id="filteredText" class="d-none">sur {{ $commerces->count() }} au total</span>
                    </small>
                    <button type="button" id="resetFilters" class="btn btn-sm btn-outline-secondary d-none">
                        <i class="bx bx-refresh me-1"></i> Réinitialiser
                    </button>
                </div>
            </div>
        </div>

        <!-- Grille de cartes des commerces -->
        @if($commerces->count() > 0)
            <div class="row" id="commercesGrid">
                @foreach($commerces as $commerce)
                    <div class="col-lg-4 col-md-6 mb-4 commerce-item" 
                         id="commerce-card-{{ $commerce->id }}"
                         data-name="{{ strtolower($commerce->name) }}"
                         data-type="{{ strtolower($commerce->commerce_type_name) }}"
                         data-city="{{ strtolower($commerce->city) }}"
                         data-contact="{{ strtolower($commerce->contact) }}"
                         data-status="{{ $commerce->is_active ? 'active' : 'inactive' }}"
                         data-date="{{ $commerce->created_at->timestamp }}">
                        <div class="card admin-card h-100 commerce-card">
                            <!-- Header avec logo et statut -->
                            <div class="card-header bg-transparent border-bottom-0 p-3">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="d-flex align-items-center">
                                        <div class="commerce-logo me-3">
                                            @if($commerce->logo_url)
                                                <img src="{{ $commerce->logo_url }}" 
                                                     alt="Logo {{ $commerce->name }}" 
                                                     class="rounded-circle"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-gradient-primary rounded-circle d-flex align-items-center justify-content-center text-white fw-bold"
                                                     style="width: 50px; height: 50px; font-size: 1.2rem;">
                                                    {{ substr($commerce->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        <div>
                                            <h6 class="mb-1 fw-bold">{{ $commerce->name }}</h6>
                                            <small class="text-muted">{{ $commerce->commerce_type_name }}</small>
                                        </div>
                                    </div>
                                    <span class="admin-badge {{ $commerce->is_active ? 'admin-badge-success' : 'admin-badge-inactive' }}" 
                                          id="status-{{ $commerce->id }}">
                                        {{ $commerce->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                            </div>

                            <!-- Contenu de la carte -->
                            <div class="card-body p-3">
                                <!-- Localisation -->
                                <div class="mb-2">
                                    <i class="bx bx-map text-primary me-2"></i>
                                    <span class="fw-semibold">{{ $commerce->city }}</span>
                                </div>

                                <!-- Adresse -->
                                <div class="mb-2">
                                    <i class="bx bx-current-location text-muted me-2"></i>
                                    <small class="text-muted">{{ Str::limit($commerce->address, 40) }}</small>
                                </div>

                                <!-- Contact -->
                                <div class="mb-2">
                                    <i class="bx bx-user text-info me-2"></i>
                                    <span>{{ $commerce->contact }}</span>
                                </div>

                                <!-- Téléphone -->
                                @if($commerce->phone)
                                    <div class="mb-2">
                                        <i class="bx bx-phone text-success me-2"></i>
                                        <a href="tel:{{ $commerce->phone }}" class="text-decoration-none">{{ $commerce->phone }}</a>
                                    </div>
                                @endif

                                <!-- Email -->
                                @if($commerce->email)
                                    <div class="mb-2">
                                        <i class="bx bx-envelope text-warning me-2"></i>
                                        <a href="mailto:{{ $commerce->email }}" class="text-decoration-none">{{ $commerce->email }}</a>
                                    </div>
                                @endif

                                <!-- Description -->
                                @if($commerce->description)
                                    <div class="mt-3">
                                        <small class="text-muted">{{ Str::limit($commerce->description, 80) }}</small>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer avec actions et date -->
                            <div class="card-footer bg-transparent border-top p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        {{ $commerce->created_at->format('d/m/Y') }}
                                    </small>
                                    <div class="d-flex gap-1">
                                        <!-- Voir les produits -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn admin-btn-info" 
                                                onclick="viewCommerceProducts({{ $commerce->id }})"
                                                title="Voir les produits">
                                            <i class="bx bx-package"></i>
                                        </button>
                                        
                                        <!-- Modifier -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn admin-btn-edit" 
                                                onclick="editCommerce({{ $commerce->id }})"
                                                title="Modifier">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        
                                        <!-- Toggle Statut -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn {{ $commerce->is_active ? 'admin-btn-toggle-active' : 'admin-btn-toggle-inactive' }}" 
                                                onclick="toggleCommerceStatus({{ $commerce->id }})"
                                                title="{{ $commerce->is_active ? 'Désactiver' : 'Activer' }}">
                                            <i class="bx {{ $commerce->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }}"></i>
                                        </button>
                                        
                                        <!-- Supprimer -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn admin-btn-delete" 
                                                onclick="deleteCommerce({{ $commerce->id }})"
                                                title="Supprimer">
                                            <i class="bx bx-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Message quand aucun résultat après filtrage -->
            <div id="noResults" class="admin-card card d-none">
                <div class="card-body">
                    <div class="admin-empty-state">
                        <div class="mb-4">
                            <i class="bx bx-search-alt display-2 text-muted"></i>
                        </div>
                        <h5 class="text-dark mb-2">Aucun commerce trouvé</h5>
                        <p class="text-muted mb-4">Aucun commerce ne correspond à vos critères de recherche.</p>
                        <button type="button" class="btn btn-admin-primary" onclick="resetAllFilters()">
                            <i class="bx bx-refresh me-1"></i> Réinitialiser les filtres
                        </button>
                    </div>
                </div>
            </div>
        @else
            <!-- État vide -->
            <div class="admin-card card">
                <div class="card-body">
                    <div class="admin-empty-state">
                        <div class="mb-4">
                            <i class="bx bx-store display-2 text-muted"></i>
                        </div>
                        <h5 class="text-dark mb-2">Aucun commerce trouvé</h5>
                        <p class="text-muted mb-4">Ajoutez votre premier commerce partenaire pour commencer</p>
                        <button type="button" 
                                class="btn btn-admin-primary btn-lg rounded-pill px-4" 
                                data-bs-toggle="modal" data-bs-target="#commerceModal"
                                onclick="openCreateCommerceModal()">
                            <i class="bx bx-plus"></i> Créer un commerce
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal pour créer/éditer un commerce --}}
<div class="modal fade admin-modal" id="commerceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouveau Commerce</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="commerceForm" method="POST" action="{{ route('admin.commerces.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="commerceId" name="id">
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Informations principales -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du commerce <span class="text-danger">*</span></label>
                                <input type="text" class="form-control admin-form-control" id="name" name="name" placeholder="Ex: Pizza Mario" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="commerce_type_id" class="form-label">Type de commerce <span class="text-danger">*</span></label>
                                <select class="form-select admin-form-control" id="commerce_type_id" name="commerce_type_id" required>
                                    <option value="">Sélectionnez un type</option>
                                    @foreach($commerceTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->full_name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="commerce_type_id-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="logo" class="form-label">Logo</label>
                                <input type="file" class="form-control admin-form-control" id="logo" name="logo" accept="image/*">
                                <div class="form-text">PNG, JPG, GIF. Max 2MB</div>
                                <div class="invalid-feedback" id="logo-error"></div>
                                <div id="currentLogo" class="mt-2 d-none">
                                    <img id="logoPreview" src="" alt="Logo actuel" class="img-thumbnail" style="max-width: 100px;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Localisation -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="city" class="form-label">Ville <span class="text-danger">*</span></label>
                                <input type="text" class="form-control admin-form-control" id="city" name="city" placeholder="Ex: Paris" required>
                                <div class="invalid-feedback" id="city-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">Adresse complète <span class="text-danger">*</span></label>
                                <textarea class="form-control admin-form-control" id="address" name="address" rows="3" placeholder="Ex: 123 Rue de la Paix, 75001 Paris" required></textarea>
                                <div class="invalid-feedback" id="address-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <!-- Contact -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="contact" class="form-label">Nom du contact <span class="text-danger">*</span></label>
                                <input type="text" class="form-control admin-form-control" id="contact" name="contact" placeholder="Ex: Jean Dupont" required>
                                <div class="invalid-feedback" id="contact-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="phone" class="form-label">Téléphone</label>
                                <input type="tel" class="form-control admin-form-control" id="phone" name="phone" placeholder="Ex: +33 1 23 45 67 89">
                                <div class="invalid-feedback" id="phone-error"></div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control admin-form-control" id="email" name="email" placeholder="Ex: contact@pizzamario.com">
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>
                            
                            <div class="form-check mt-4">
                                <input type="hidden" name="is_active" value="0">
                                <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" checked>
                                <label class="form-check-label" for="is_active">Commerce actif</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control admin-form-control" id="description" name="description" rows="3" placeholder="Description du commerce..."></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-admin-primary">
                        <span data-submit-text>Créer</span>
                        <span class="spinner-border spinner-border-sm d-none"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Configuration des URLs
const COMMERCE_BASE_URL = '{{ route("admin.commerces.index") }}';

// Fonctions d'action
function openCreateCommerceModal() {
    AdminComponents.initCreateModal('commerceModal', {
        title: 'Nouveau Commerce',
        submitText: 'Créer'
    });
    
    // Réinitialiser l'image
    document.getElementById('currentLogo').classList.add('d-none');
}

function editCommerce(id) {
    AdminComponents.loadForEdit(id, COMMERCE_BASE_URL, {
        successCallback: (data) => {
            const commerce = data.commerce;
            document.getElementById('modalTitle').textContent = 'Modifier le Commerce';
            document.querySelector('[data-submit-text]').textContent = 'Modifier';
            document.getElementById('commerceId').value = commerce.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('name').value = commerce.name;
            document.getElementById('commerce_type_id').value = commerce.commerce_type_id;
            document.getElementById('city').value = commerce.city;
            document.getElementById('address').value = commerce.address;
            document.getElementById('contact').value = commerce.contact;
            document.getElementById('phone').value = commerce.phone || '';
            document.getElementById('email').value = commerce.email || '';
            document.getElementById('description').value = commerce.description || '';
            document.getElementById('is_active').checked = commerce.is_active;
            
            // Afficher le logo actuel
            if (commerce.logo_url) {
                document.getElementById('logoPreview').src = commerce.logo_url;
                document.getElementById('currentLogo').classList.remove('d-none');
            }
            
            new bootstrap.Modal(document.getElementById('commerceModal')).show();
        }
    });
}

function toggleCommerceStatus(id) {
    AdminComponents.toggleStatus(id, COMMERCE_BASE_URL, {
        confirmMessage: 'Changer le statut de ce commerce ?',
        successCallback: (data) => {
            // Mettre à jour le badge de statut
            const statusBadge = document.getElementById(`status-${id}`);
            if (statusBadge) {
                statusBadge.className = `admin-badge ${data.is_active ? 'admin-badge-success' : 'admin-badge-inactive'}`;
                statusBadge.textContent = data.is_active ? 'Actif' : 'Inactif';
            }
            
            // Mettre à jour le bouton toggle
            const toggleBtn = document.querySelector(`[onclick="toggleCommerceStatus(${id})"]`);
            if (toggleBtn) {
                toggleBtn.className = `btn btn-sm admin-action-btn ${data.is_active ? 'admin-btn-toggle-active' : 'admin-btn-toggle-inactive'}`;
                toggleBtn.title = data.is_active ? 'Désactiver' : 'Activer';
                toggleBtn.innerHTML = `<i class="bx ${data.is_active ? 'bx-toggle-right' : 'bx-toggle-left'}"></i>`;
            }
        }
    });
}

function deleteCommerce(id) {
    AdminComponents.deleteItem(id, COMMERCE_BASE_URL, {
        confirmMessage: 'Supprimer définitivement ce commerce ? Cette action est irréversible.',
        successCallback: () => {
            // Supprimer la carte du DOM
            const card = document.getElementById(`commerce-card-${id}`);
            if (card) {
                card.remove();
            }
        }
    });
}

function viewCommerceProducts(id) {
    window.location.href = `{{ route('admin.commerce.products.index', ['commerce' => '__ID__']) }}`.replace('__ID__', id);
}

// Gestion du formulaire avec upload d'image
document.getElementById('commerceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('[type="submit"]');
    const submitText = submitBtn.querySelector('[data-submit-text]');
    const submitSpinner = submitBtn.querySelector('.spinner-border');
    
    // Désactiver le bouton
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    const id = document.getElementById('commerceId').value;
    
    let url = '{{ route("admin.commerces.store") }}';
    if (id) {
        url = `${COMMERCE_BASE_URL}/${id}`;
    }
    
    fetch(url, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('commerceModal'));
            modal.hide();
            AdminComponents.showAlert(data.message, 'success');
            setTimeout(() => window.location.reload(), 1000);
        } else {
            AdminComponents.showErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        AdminComponents.showAlert('Une erreur est survenue', 'danger');
    })
    .finally(() => {
        // Réactiver le bouton
        submitBtn.disabled = false;
        submitSpinner.classList.add('d-none');
    });
});

// Prévisualisation du logo
document.getElementById('logo').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logoPreview').src = e.target.result;
            document.getElementById('currentLogo').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// ===== SYSTÈME DE RECHERCHE ET FILTRAGE =====

// Variables globales
const searchInput = document.getElementById('searchInput');
const typeFilter = document.getElementById('typeFilter');
const cityFilter = document.getElementById('cityFilter');
const statusFilter = document.getElementById('statusFilter');
const sortBy = document.getElementById('sortBy');
const clearSearchBtn = document.getElementById('clearSearch');
const resetFiltersBtn = document.getElementById('resetFilters');
const resultsCount = document.getElementById('resultsCount');
const filteredText = document.getElementById('filteredText');
const commercesGrid = document.getElementById('commercesGrid');
const noResults = document.getElementById('noResults');

const totalCommerces = {{ $commerces->count() }};
let commerceItems = Array.from(document.querySelectorAll('.commerce-item'));

// Fonction de filtrage principal
function filterCommerces() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const selectedType = typeFilter.value.toLowerCase();
    const selectedCity = cityFilter.value.toLowerCase();
    const selectedStatus = statusFilter.value;
    
    let visibleCount = 0;
    
    commerceItems.forEach(item => {
        const name = item.dataset.name;
        const contact = item.dataset.contact;
        const type = item.dataset.type;
        const city = item.dataset.city;
        const status = item.dataset.status;
        
        // Critères de recherche
        const matchesSearch = !searchTerm || 
            name.includes(searchTerm) || 
            contact.includes(searchTerm);
        
        const matchesType = !selectedType || type === selectedType;
        const matchesCity = !selectedCity || city === selectedCity;
        const matchesStatus = !selectedStatus || status === selectedStatus;
        
        // Afficher/masquer la carte
        if (matchesSearch && matchesType && matchesCity && matchesStatus) {
            item.style.display = 'block';
            item.style.animation = 'fadeInUp 0.3s ease';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Trier les commerces visibles
    sortCommerces();
    
    // Mettre à jour le compteur
    updateResultsCount(visibleCount);
    
    // Afficher/masquer le message "aucun résultat"
    if (visibleCount === 0) {
        commercesGrid.classList.add('d-none');
        noResults.classList.remove('d-none');
    } else {
        commercesGrid.classList.remove('d-none');
        noResults.classList.add('d-none');
    }
    
    // Gérer les boutons de reset
    const hasFilters = searchTerm || selectedType || selectedCity || selectedStatus;
    toggleResetButtons(hasFilters);
}

// Fonction de tri
function sortCommerces() {
    const sortValue = sortBy.value;
    const visibleItems = commerceItems.filter(item => item.style.display !== 'none');
    
    visibleItems.sort((a, b) => {
        switch (sortValue) {
            case 'name-asc':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'name-desc':
                return b.dataset.name.localeCompare(a.dataset.name);
            case 'date-desc':
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
            case 'date-asc':
                return parseInt(a.dataset.date) - parseInt(b.dataset.date);
            case 'city-asc':
                return a.dataset.city.localeCompare(b.dataset.city);
            default:
                return 0;
        }
    });
    
    // Réorganiser les éléments dans le DOM
    visibleItems.forEach(item => {
        commercesGrid.appendChild(item);
    });
}

// Mettre à jour le compteur de résultats
function updateResultsCount(count) {
    resultsCount.textContent = count;
    
    if (count < totalCommerces) {
        filteredText.classList.remove('d-none');
    } else {
        filteredText.classList.add('d-none');
    }
}

// Gérer les boutons de reset
function toggleResetButtons(show) {
    if (show) {
        resetFiltersBtn.classList.remove('d-none');
        if (searchInput.value) {
            clearSearchBtn.classList.remove('d-none');
        }
    } else {
        resetFiltersBtn.classList.add('d-none');
        clearSearchBtn.classList.add('d-none');
    }
}

// Réinitialiser tous les filtres
function resetAllFilters() {
    searchInput.value = '';
    typeFilter.value = '';
    cityFilter.value = '';
    statusFilter.value = '';
    sortBy.value = 'name-asc';
    filterCommerces();
}

// Event listeners
searchInput.addEventListener('input', function() {
    filterCommerces();
    
    if (this.value) {
        clearSearchBtn.classList.remove('d-none');
    } else {
        clearSearchBtn.classList.add('d-none');
    }
});

clearSearchBtn.addEventListener('click', function() {
    searchInput.value = '';
    this.classList.add('d-none');
    filterCommerces();
    searchInput.focus();
});

typeFilter.addEventListener('change', filterCommerces);
cityFilter.addEventListener('change', filterCommerces);
statusFilter.addEventListener('change', filterCommerces);
sortBy.addEventListener('change', filterCommerces);
resetFiltersBtn.addEventListener('click', resetAllFilters);

// Recherche par raccourci clavier (Ctrl+F ou Cmd+F)
document.addEventListener('keydown', function(e) {
    if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
        e.preventDefault();
        searchInput.focus();
        searchInput.select();
    }
    
    // Escape pour vider la recherche
    if (e.key === 'Escape' && searchInput === document.activeElement) {
        resetAllFilters();
        searchInput.blur();
    }
});

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Tri initial
    sortCommerces();
});
</script>
@endpush 