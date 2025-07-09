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
// Configuration des URLs
const CATEGORY_BASE_URL = '{{ route("admin.categories.index") }}';

// Fonctions d'action
function openCreateCategoryModal() {
    AdminComponents.initCreateModal('categoryModal', {
        title: 'Nouvelle Catégorie',
        submitText: 'Créer'
    });
}

function editCategory(id) {
    AdminComponents.loadForEdit(id, CATEGORY_BASE_URL, {
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
        }
    });
}

function toggleCategoryStatus(id) {
    AdminComponents.toggleStatus(id, CATEGORY_BASE_URL, {
        confirmMessage: 'Changer le statut de cette catégorie ?'
    });
}

function deleteCategory(id) {
    AdminComponents.deleteItem(id, CATEGORY_BASE_URL, {
        confirmMessage: 'Supprimer définitivement cette catégorie ? Cette action est irréversible.'
    });
}

// Gestion du formulaire avec URL dynamique
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('[type="submit"]');
    const submitText = submitBtn.querySelector('[data-submit-text]');
    const submitSpinner = submitBtn.querySelector('.spinner-border');
    
    // Désactiver le bouton
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    const id = document.getElementById('categoryId').value;
    const method = document.getElementById('methodField').value;
    
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
</script>
@endpush 