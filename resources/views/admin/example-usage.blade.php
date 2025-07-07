{{-- 
=============================================================================
 EXEMPLE D'UTILISATION DES COMPOSANTS RÉUTILISABLES
=============================================================================

Ce fichier montre comment utiliser les composants créés pour créer rapidement
une page d'administration avec le même design et les mêmes fonctionnalités.

--}}

@extends('admin.layouts.master')

@section('title', 'Catégories')
@section('description', 'Gestion des catégories de produits')

@section('content')
{{-- 
    UTILISATION DU COMPOSANT DATA-TABLE 
    ====================================
    
    Plus besoin de réécrire tout le HTML !
    Il suffit de passer les bonnes données au composant.
--}}

@include('admin.components.data-table', [
    // Titre et description
    'title' => 'Catégories de Produits',
    'description' => 'Gérez les catégories disponibles pour vos produits',
    
    // Bouton de création avec modal
    'createRoute' => null, // Pas de route car on utilise un modal
    'modalTarget' => '#categoryModal',
    'createText' => 'Nouvelle Catégorie',
    'createCallback' => 'openCreateCategoryModal()',
    
    // Données et configuration des colonnes
    'items' => $categories ?? collect([
        (object)['id' => 1, 'name' => 'Électronique', 'icon' => '📱', 'description' => 'Appareils électroniques et gadgets', 'is_active' => true, 'created_at' => now()],
        (object)['id' => 2, 'name' => 'Vêtements', 'icon' => '👕', 'description' => 'Habits et accessoires', 'is_active' => false, 'created_at' => now()],
        (object)['id' => 3, 'name' => 'Maison', 'icon' => '🏠', 'description' => 'Articles pour la maison', 'is_active' => true, 'created_at' => now()],
    ]),
    
    'columns' => [
        [
            'key' => 'name', 
            'label' => 'Catégorie', 
            'type' => 'emoji-text',
            'emoji_key' => 'icon'
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
            <form id="categoryForm" method="POST" action="{{ route('admin.categories.store') ?? '#' }}">
                @csrf
                <input type="hidden" id="categoryId" name="id">
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nom de la catégorie <span class="text-danger">*</span></label>
                        <input type="text" class="form-control admin-form-control" id="name" name="name" required>
                        <div class="invalid-feedback" id="name-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icône <span class="text-danger">*</span></label>
                        <input type="text" class="form-control admin-form-control" id="icon" name="icon" placeholder="📱" required>
                        <div class="invalid-feedback" id="icon-error"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control admin-form-control" id="description" name="description" rows="3"></textarea>
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

{{-- 
    INCLUSION DES STYLES ET SCRIPTS RÉUTILISABLES
    ==============================================
--}}

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-components.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-components.js') }}"></script>
<script>
{{-- 
    JAVASCRIPT SIMPLIFIÉ
    =====================
    
    Grâce aux composants réutilisables, le code JS est beaucoup plus court !
--}}

// Configuration des URLs (à adapter selon vos routes)
const CATEGORY_BASE_URL = '/admin/categories'; // Remplacez par votre route

// Fonctions d'action simplifiées
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
            document.getElementById('name').value = category.name;
            document.getElementById('icon').value = category.icon;
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
        confirmMessage: 'Supprimer définitivement cette catégorie ?'
    });
}

// Gestion du formulaire
AdminComponents.handleFormSubmit('categoryForm', {
    successCallback: (data) => {
        const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
        modal.hide();
        AdminComponents.showAlert(data.message, 'success');
        setTimeout(() => window.location.reload(), 1000);
    },
    errorCallback: (data) => {
        AdminComponents.showErrors(data.errors);
    }
});
</script>
@endpush

{{--
=============================================================================
RÉSUMÉ DES AVANTAGES
=============================================================================

AVANT (code personnalisé) :
- ~450 lignes de code HTML/CSS/JS
- Copier-coller et adapter pour chaque page
- Risque d'incohérence de design
- Maintenance difficile

APRÈS (composants réutilisables) :
- ~100 lignes de configuration seulement
- Design automatiquement cohérent
- Fonctionnalités AJAX prêtes à l'emploi
- Maintenance centralisée

POUR CRÉER UNE NOUVELLE PAGE :
1. Copier ce fichier
2. Adapter les variables du data-table
3. Configurer les URLs dans le JS
4. Terminé ! 

La page aura automatiquement :
✅ Design harmonisé avec la palette moderne
✅ Animations et interactions fluides  
✅ Gestion AJAX complète
✅ Responsive design
✅ États de chargement
✅ Gestion d'erreurs
--}} 