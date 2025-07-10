@extends('admin.layouts.master')

@section('title', 'Catégories')
@section('description', 'Gestion des catégories de produits par type de commerce')

@section('content')
{{-- Utilisation du composant data-table réutilisable --}}
@include('admin.components.data-table', [
    // Titre et description
    'title' => 'Catégories de Produits',
    'description' => 'Gérez les catégories disponibles pour chaque type de commerce',
    
    // Bouton de création avec modal
    'modalTarget' => '#categoryModal',
    'createText' => 'Nouvelle Catégorie',
    'createCallback' => 'openCreateCategoryModal()',
    
    // Données et configuration des colonnes
    'items' => $categories,
    
    'columns' => [
        [
            'key' => 'name', 
            'label' => 'Catégorie', 
            'type' => 'emoji-text',
            'emoji_key' => 'emoji'
        ],
        [
            'key' => 'commerce_type_name', 
            'label' => 'Type de Commerce', 
            'type' => 'text'
        ],
        [
            'key' => 'description', 
            'label' => 'Description', 
            'type' => 'truncate',
            'limit' => 60
        ],
        [
            'key' => 'is_active', 
            'label' => 'Statut', 
            'type' => 'badge'
        ],
        [
            'key' => 'created_at', 
            'label' => 'Date de création', 
            'type' => 'date'
        ]
    ],
    
    // Actions disponibles
    'actions' => ['edit', 'toggle', 'delete'],
    'editCallback' => 'editCategory',
    'toggleCallback' => 'toggleCategoryStatus', 
    'deleteCallback' => 'deleteCategory',
    
    // État vide
    'emptyIcon' => 'bx-category',
    'emptyTitle' => 'Aucune catégorie trouvée',
    'emptyMessage' => 'Créez votre première catégorie pour organiser vos produits'
])

{{-- Modal pour créer/éditer une catégorie --}}
<div class="modal fade admin-modal" id="categoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouvelle Catégorie</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') }}">
                @csrf
                <input type="hidden" id="categoryId" name="id">
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="commerce_type_id" class="form-label">Type de Commerce <span class="text-danger">*</span></label>
                        <select class="form-select admin-form-control" id="commerce_type_id" name="commerce_type_id" required>
                            <option value="">Sélectionnez un type de commerce</option>
                            @foreach($commerceTypes as $type)
                                <option value="{{ $type->id }}">{{ $type->full_name }}</option>
                            @endforeach
                        </select>
                        <div class="invalid-feedback" id="commerce_type_id-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control admin-form-control" id="name" name="name" placeholder="Ex: Pizza" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="emoji" class="form-label">Emoji <span class="text-danger">*</span></label>
                        <input type="text" class="form-control admin-form-control" id="emoji" name="emoji" placeholder="🍕" maxlength="10" required>
                        <div class="form-text">Un emoji représentatif de la catégorie</div>
                        <div class="invalid-feedback" id="emoji-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control admin-form-control" id="description" name="description" rows="3" placeholder="Description de la catégorie..."></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                    
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
                        <label class="form-check-label" for="is_active">Actif</label>
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
console.log('=== SCRIPT CATÉGORIES ===');
console.log('AdminComponents disponible:', typeof AdminComponents);
console.log('window.AdminComponents disponible:', typeof window.AdminComponents);

// Configuration des URLs
const CATEGORY_BASE_URL = '{{ route("admin.categories.index") }}';

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
    console.log('🔧 Création des fonctions de fallback pour les catégories...');
    
    window.openCreateCategoryModal = function() {
        console.log('📝 openCreateCategoryModal (fallback) appelée');
        const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
        
        // Réinitialiser le formulaire
        const form = document.getElementById('categoryForm');
        if (form) form.reset();
        
        // Réinitialiser les champs cachés
        document.getElementById('categoryId').value = '';
        document.getElementById('methodField').value = 'POST';
        document.getElementById('modalTitle').textContent = 'Nouvelle Catégorie';
        document.querySelector('[data-submit-text]').textContent = 'Créer';
        
        modal.show();
    };

    window.editCategory = function(id) {
        console.log('✏️ editCategory (fallback) appelée avec ID:', id);
        
        fetch(`${CATEGORY_BASE_URL}/${id}/edit`, {
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
                const category = data.category;
                
                // Remplir le formulaire
                document.getElementById('modalTitle').textContent = 'Modifier la Catégorie';
                document.querySelector('[data-submit-text]').textContent = 'Modifier';
                document.getElementById('categoryId').value = category.id;
                document.getElementById('methodField').value = 'PUT';
                document.getElementById('commerce_type_id').value = category.commerce_type_id;
                document.getElementById('name').value = category.name;
                document.getElementById('emoji').value = category.emoji;
                document.getElementById('description').value = category.description || '';
                document.getElementById('is_active').checked = category.is_active;
                
                // Ouvrir le modal
                const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
                modal.show();
            } else {
                alert('Erreur lors du chargement des données de la catégorie');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement: ' + error.message);
        });
    };

    window.toggleCategoryStatus = function(id) {
        console.log('🔄 toggleCategoryStatus (fallback) appelée avec ID:', id);
        
        if (!confirm('Êtes-vous sûr de vouloir changer le statut de cette catégorie ?')) {
            return;
        }
        
        fetch(`${CATEGORY_BASE_URL}/${id}/toggle-status`, {
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
                const toggleBtn = document.querySelector(`[onclick="toggleCategoryStatus(${id})"]`);
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

    window.deleteCategory = function(id) {
        console.log('🗑️ deleteCategory (fallback) appelée avec ID:', id);
        
        if (!confirm('Êtes-vous sûr de vouloir supprimer définitivement cette catégorie ? Cette action est irréversible.')) {
            return;
        }
        
        fetch(`${CATEGORY_BASE_URL}/${id}`, {
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
                // Supprimer la ligne du tableau
                const row = document.getElementById(`row-${id}`);
                if (row) {
                    row.remove();
                }
                
                alert(data.message);
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression: ' + error.message);
        });
    };
    
    console.log('✅ Fonctions de fallback créées pour les catégories');
}

// Fonctions optimisées avec AdminComponents
function createOptimizedFunctions() {
    console.log('⚡ Création des fonctions optimisées avec AdminComponents...');
    
    window.openCreateCategoryModal = function() {
        console.log('📝 openCreateCategoryModal (optimisée) appelée');
        try {
            window.AdminComponents.initCreateModal('categoryModal', {
                title: 'Nouvelle Catégorie',
                submitText: 'Créer'
            });
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            // Fallback
            const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
            modal.show();
        }
    };

    window.editCategory = function(id) {
        console.log('✏️ editCategory (optimisée) appelée avec ID:', id);
        try {
            window.AdminComponents.loadForEdit(id, CATEGORY_BASE_URL, {
                successCallback: (data) => {
                    const category = data.category;
                    document.getElementById('modalTitle').textContent = 'Modifier la Catégorie';
                    document.querySelector('[data-submit-text]').textContent = 'Modifier';
                    document.getElementById('categoryId').value = category.id;
                    document.getElementById('methodField').value = 'PUT';
                    document.getElementById('commerce_type_id').value = category.commerce_type_id;
                    document.getElementById('name').value = category.name;
                    document.getElementById('emoji').value = category.emoji;
                    document.getElementById('description').value = category.description || '';
                    document.getElementById('is_active').checked = category.is_active;
                    
                    new bootstrap.Modal(document.getElementById('categoryModal')).show();
                },
                errorCallback: (data) => {
                    window.AdminComponents.showAlert('Erreur lors du chargement des données', 'danger');
                }
            });
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            createFallbackFunctions();
            window.editCategory(id);
        }
    };

    window.toggleCategoryStatus = function(id) {
        try {
            window.AdminComponents.toggleStatus(id, CATEGORY_BASE_URL, {
                confirmMessage: 'Êtes-vous sûr de vouloir changer le statut de cette catégorie ?',
                successCallback: (data) => {
                    const statusBadge = document.getElementById(`status-${id}`);
                    if (statusBadge) {
                        statusBadge.className = `admin-badge ${data.is_active ? 'admin-badge-success' : 'admin-badge-inactive'}`;
                        statusBadge.textContent = data.is_active ? 'Actif' : 'Inactif';
                    }
                    
                    const toggleBtn = document.querySelector(`[onclick="toggleCategoryStatus(${id})"]`);
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
            window.toggleCategoryStatus(id);
        }
    };

    window.deleteCategory = function(id) {
        try {
            window.AdminComponents.deleteItem(id, CATEGORY_BASE_URL, {
                confirmMessage: 'Êtes-vous sûr de vouloir supprimer définitivement cette catégorie ?',
                successCallback: (data) => {
                    const row = document.getElementById(`row-${id}`);
                    if (row) row.remove();
                    window.AdminComponents.showAlert(data.message, 'success');
                }
            });
        } catch (error) {
            console.error('Erreur avec AdminComponents, fallback...', error);
            createFallbackFunctions();
            window.deleteCategory(id);
        }
    };
}

// Initialisation principale
document.addEventListener('DOMContentLoaded', async function() {
    console.log('🚀 DOM chargé, initialisation des catégories...');
    
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

    // Gestion du formulaire
    const categoryForm = document.getElementById('categoryForm');
    if (categoryForm) {
        categoryForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitBtn = this.querySelector('[type="submit"]');
            const submitSpinner = submitBtn.querySelector('.spinner-border');
            
            submitBtn.disabled = true;
            if (submitSpinner) submitSpinner.classList.remove('d-none');
            
            const formData = new FormData(this);
            const id = document.getElementById('categoryId').value;
            
            let url = '{{ route("admin.categories.store") }}';
            if (id) {
                url = `${CATEGORY_BASE_URL}/${id}`;
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
                    const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
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

    console.log('✅ Initialisation des catégories terminée');
});

// Test des fonctions
setTimeout(() => {
    console.log('=== TEST FINAL DES FONCTIONS CATÉGORIES ===');
    console.log('openCreateCategoryModal:', typeof window.openCreateCategoryModal);
    console.log('editCategory:', typeof window.editCategory);
    console.log('toggleCategoryStatus:', typeof window.toggleCategoryStatus);
    console.log('deleteCategory:', typeof window.deleteCategory);
}, 1000);
</script>
@endpush 