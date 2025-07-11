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
// Script de débogage
console.log('=== DÉBUT DU SCRIPT DE DÉBOGAGE ===');
console.log('AdminComponents disponible:', typeof AdminComponents);
console.log('window.AdminComponents disponible:', typeof window.AdminComponents);
console.log('Bootstrap disponible:', typeof bootstrap);
console.log('jQuery disponible:', typeof $);

// Configuration des URLs
const COMMERCE_BASE_URL = '{{ route("admin.commerces.index") }}';

// Fonction pour attendre que l'instance AdminComponents soit prête
function waitForAdminComponentsInstance() {
    return new Promise((resolve) => {
        let attempts = 0;
        const maxAttempts = 50; // 5 secondes max
        
        function checkAdminComponents() {
            attempts++;
            console.log(`Tentative ${attempts}: Vérification AdminComponents...`);
            
            if (window.AdminComponents && 
                typeof window.AdminComponents.loadForEdit === 'function' && 
                typeof window.AdminComponents.toggleStatus === 'function' && 
                typeof window.AdminComponents.deleteItem === 'function') {
                console.log('✅ Instance AdminComponents est prête avec toutes ses méthodes');
                resolve(true);
            } else if (attempts >= maxAttempts) {
                console.warn('⚠️ Timeout: AdminComponents non disponible après 5 secondes');
                resolve(false);
            } else {
                console.log('⏳ AdminComponents pas encore prêt, attente... (tentative ' + attempts + ')');
                setTimeout(checkAdminComponents, 100);
            }
        }
        checkAdminComponents();
    });
}

// Fonctions de fallback (simples mais fonctionnelles)
function createFallbackFunctions() {
    console.log('🔧 Création des fonctions de fallback...');
    
    window.openCreateCommerceModal = function() {
        console.log('📝 openCreateCommerceModal (fallback) appelée');
        const modal = new bootstrap.Modal(document.getElementById('commerceModal'));
        
        // Réinitialiser le formulaire
        const form = document.getElementById('commerceForm');
        if (form) form.reset();
    
        // Réinitialiser les champs cachés
        document.getElementById('commerceId').value = '';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Nouveau Commerce';
        document.querySelector('[data-submit-text]').textContent = 'Créer';
        
        // Masquer le logo actuel
        const currentLogo = document.getElementById('currentLogo');
        if (currentLogo) currentLogo.classList.add('d-none');
        
        modal.show();
    };

    window.editCommerce = function(id) {
        console.log('✏️ editCommerce (fallback) appelée avec ID:', id);
        
        fetch(`${COMMERCE_BASE_URL}/${id}/edit`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            const commerce = data.commerce;
                
                // Remplir le formulaire
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
            
                // Ouvrir le modal
                const modal = new bootstrap.Modal(document.getElementById('commerceModal'));
                modal.show();
            } else {
                alert('Erreur lors du chargement des données du commerce');
}
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement: ' + error.message);
        });
    };

    window.toggleCommerceStatus = function(id) {
        console.log('🔄 toggleCommerceStatus (fallback) appelée avec ID:', id);
        
        if (!confirm('Êtes-vous sûr de vouloir changer le statut de ce commerce ?')) {
            return;
        }
        
        fetch(`${COMMERCE_BASE_URL}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
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
                
                alert(data.message);
            } else {
                alert('Erreur lors du changement de statut');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du changement de statut: ' + error.message);
        });
    };

    window.deleteCommerce = function(id) {
        console.log('🗑️ deleteCommerce (fallback) appelée avec ID:', id);
        
        if (!confirm('Êtes-vous sûr de vouloir supprimer définitivement ce commerce ? Cette action est irréversible.')) {
            return;
        }
        
        fetch(`${COMMERCE_BASE_URL}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
            // Supprimer la carte du DOM
            const card = document.getElementById(`commerce-card-${id}`);
            if (card) {
                card.remove();
            }
                
                alert(data.message);
                
                // Mettre à jour le compteur
                setTimeout(() => {
                    const remainingCards = document.querySelectorAll('.commerce-item').length;
                    const resultsCount = document.getElementById('resultsCount');
                    if (resultsCount) {
                        resultsCount.textContent = remainingCards;
                    }
                    
                    // Afficher le message d'état vide si plus de commerces
                    if (remainingCards === 0) {
                        const commercesGrid = document.getElementById('commercesGrid');
                        if (commercesGrid) {
                            commercesGrid.innerHTML = `
                                <div class="col-12">
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
                                </div>
                            `;
                        }
                    }
                }, 500);
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression: ' + error.message);
        });
    };

    window.viewCommerceProducts = function(id) {
        console.log('📦 viewCommerceProducts appelée avec ID:', id);
        window.location.href = `{{ route('admin.commerce.products.index', ['commerce' => '__ID__']) }}`.replace('__ID__', id);
    };
    
    console.log('✅ Fonctions de fallback créées');
}

// Fonctions optimisées avec AdminComponents
function createOptimizedFunctions() {
    console.log('⚡ Création des fonctions optimisées avec AdminComponents...');
    
    window.openCreateCommerceModal = function() {
        console.log('📝 openCreateCommerceModal (optimisée) appelée');
        try {
            window.AdminComponents.initCreateModal('commerceModal', {
                title: 'Nouveau Commerce',
                submitText: 'Créer'
            });
            
            const currentLogo = document.getElementById('currentLogo');
            if (currentLogo) currentLogo.classList.add('d-none');
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            // Fallback
            const modal = new bootstrap.Modal(document.getElementById('commerceModal'));
            modal.show();
        }
    };

    window.editCommerce = function(id) {
        console.log('✏️ editCommerce (optimisée) appelée avec ID:', id);
        try {
            window.AdminComponents.loadForEdit(id, COMMERCE_BASE_URL, {
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
                    
                    if (commerce.logo_url) {
                        document.getElementById('logoPreview').src = commerce.logo_url;
                        document.getElementById('currentLogo').classList.remove('d-none');
                    }
                    
                    new bootstrap.Modal(document.getElementById('commerceModal')).show();
                },
                errorCallback: (data) => {
                    window.AdminComponents.showAlert('Erreur lors du chargement des données', 'danger');
                }
            });
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            // Appeler la version fallback
            createFallbackFunctions();
            window.editCommerce(id);
        }
    };

    // Similar pattern for other functions...
    window.toggleCommerceStatus = function(id) {
        try {
            window.AdminComponents.toggleStatus(id, COMMERCE_BASE_URL, {
                confirmMessage: 'Êtes-vous sûr de vouloir changer le statut de ce commerce ?',
                successCallback: (data) => {
                    const statusBadge = document.getElementById(`status-${id}`);
                    if (statusBadge) {
                        statusBadge.className = `admin-badge ${data.is_active ? 'admin-badge-success' : 'admin-badge-inactive'}`;
                        statusBadge.textContent = data.is_active ? 'Actif' : 'Inactif';
                    }
                    
                    const toggleBtn = document.querySelector(`[onclick="toggleCommerceStatus(${id})"]`);
                    if (toggleBtn) {
                        toggleBtn.className = `btn btn-sm admin-action-btn ${data.is_active ? 'admin-btn-toggle-active' : 'admin-btn-toggle-inactive'}`;
                        toggleBtn.title = data.is_active ? 'Désactiver' : 'Activer';
                        toggleBtn.innerHTML = `<i class="bx ${data.is_active ? 'bx-toggle-right' : 'bx-toggle-left'}"></i>`;
                    }
                    
                    window.AdminComponents.showAlert(data.message, 'success');
                }
            });
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            createFallbackFunctions();
            window.toggleCommerceStatus(id);
        }
    };

    window.deleteCommerce = function(id) {
        try {
            window.AdminComponents.deleteItem(id, COMMERCE_BASE_URL, {
                confirmMessage: 'Êtes-vous sûr de vouloir supprimer définitivement ce commerce ?',
                successCallback: (data) => {
                    const card = document.getElementById(`commerce-card-${id}`);
                    if (card) card.remove();
                    window.AdminComponents.showAlert(data.message, 'success');
                }
            });
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            createFallbackFunctions();
            window.deleteCommerce(id);
        }
    };

    window.viewCommerceProducts = function(id) {
    window.location.href = `{{ route('admin.commerce.products.index', ['commerce' => '__ID__']) }}`.replace('__ID__', id);
    };
}

// Initialisation principale
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🚀 DOM chargé, initialisation...');
    
    // Créer immédiatement les fonctions de fallback pour éviter les erreurs
    createFallbackFunctions();
    
    // Essayer d'utiliser AdminComponents en arrière-plan
    const adminComponentsReady = await waitForAdminComponentsInstance();
    
    if (adminComponentsReady) {
        console.log('🎉 AdminComponents disponible, création des fonctions optimisées');
        createOptimizedFunctions();
    } else {
        console.log('⚠️ AdminComponents non disponible, utilisation des fonctions de fallback');
    }

    // Reste du code (formulaire, recherche, etc.)
    
    // Gestion du formulaire
    const commerceForm = document.getElementById('commerceForm');
    if (commerceForm) {
        commerceForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('[type="submit"]');
    const submitSpinner = submitBtn.querySelector('.spinner-border');
    
    submitBtn.disabled = true;
            if (submitSpinner) submitSpinner.classList.remove('d-none');
    
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
                    alert(data.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
                    alert('Erreurs de validation: ' + JSON.stringify(data.errors));
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
                alert('Une erreur est survenue: ' + error.message);
    })
    .finally(() => {
        submitBtn.disabled = false;
                if (submitSpinner) submitSpinner.classList.add('d-none');
    });
});
    }

// Prévisualisation du logo
    const logoInput = document.getElementById('logo');
    if (logoInput) {
        logoInput.addEventListener('change', function(e) {
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
    }

    console.log('✅ Initialisation terminée');
});

// Test des fonctions
setTimeout(() => {
    console.log('=== TEST FINAL DES FONCTIONS ===');
    console.log('openCreateCommerceModal:', typeof window.openCreateCommerceModal);
    console.log('editCommerce:', typeof window.editCommerce);
    console.log('toggleCommerceStatus:', typeof window.toggleCommerceStatus);
    console.log('deleteCommerce:', typeof window.deleteCommerce);
    console.log('viewCommerceProducts:', typeof window.viewCommerceProducts);
}, 1000);
</script>
@endpush 