@extends('admin.layouts.master')

@section('title', isset($commerce) ? 'Produits de ' . $commerce->name : 'Tous les produits')
@section('description', isset($commerce) ? 'Gérez les produits de ' . $commerce->name : 'Gérez tous les produits de vos commerces')

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Section titre -->
        <div class="admin-title-card card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        @if(isset($commerce))
                            <nav aria-label="breadcrumb" class="mb-2">
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('admin.commerces.index') }}" class="text-decoration-none">
                                            <i class="bx bx-store me-1"></i>Commerces
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active">{{ $commerce->name }}</li>
                                </ol>
                            </nav>
                            <h4 class="fw-bold mb-1">Produits de {{ $commerce->name }}</h4>
                            <p class="text-muted mb-0">{{ $products->count() }} produit(s) - {{ $commerce->commerce_type_name }}</p>
                        @else
                            <h4 class="fw-bold mb-1">Tous les produits</h4>
                            <p class="text-muted mb-0">{{ $products->count() }} produit(s) au total</p>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        @if(isset($commerce))
                            <a href="{{ route('admin.commerces.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-arrow-back"></i> Retour aux commerces
                            </a>
                        @endif
                        <button type="button" class="btn btn-admin-primary" 
                                data-bs-toggle="modal" data-bs-target="#productModal"
                                onclick="openCreateProductModal()">
                            <i class="bx bx-plus"></i> Nouveau Produit
                        </button>
                    </div>
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
                                   placeholder="Rechercher un produit..."
                                   autocomplete="off">
                            <i class="bx bx-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        </div>
                    </div>
                    
                    <!-- Filtres -->
                    <div class="col-md-8">
                        <div class="row">
                            @if(!isset($commerce))
                                <div class="col-md-3 mb-2 mb-md-0">
                                    <select id="commerceFilter" class="form-select admin-form-control">
                                        <option value="">Tous les commerces</option>
                                        @foreach($commerces as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            @endif
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="categoryFilter" class="form-select admin-form-control">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->emoji }} {{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 mb-2 mb-md-0">
                                <select id="statusFilter" class="form-select admin-form-control">
                                    <option value="">Tous les statuts</option>
                                    <option value="available">Disponibles</option>
                                    <option value="unavailable">Non disponibles</option>
                                    <option value="featured">Mis en avant</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <select id="sortBy" class="form-select admin-form-control">
                                    <option value="name-asc">Nom A-Z</option>
                                    <option value="name-desc">Nom Z-A</option>
                                    <option value="price-asc">Prix croissant</option>
                                    <option value="price-desc">Prix décroissant</option>
                                    <option value="date-desc">Plus récents</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Compteur de résultats -->
                <div class="mt-3">
                    <small class="text-muted">
                        <span id="resultsCount">{{ $products->count() }}</span> produit(s) trouvé(s)
                    </small>
                </div>
            </div>
        </div>

        <!-- Grille de cartes des produits -->
        @if($products->count() > 0)
            <div class="row" id="productsGrid">
                @foreach($products as $product)
                    <div class="col-lg-4 col-md-6 mb-4 product-item" 
                         id="product-card-{{ $product->id }}"
                         data-name="{{ strtolower($product->name) }}"
                         data-category="{{ $product->category_id }}"
                         @if(!isset($commerce))
                         data-commerce="{{ $product->commerce_id }}"
                         @endif
                         data-price="{{ $product->price }}"
                         data-available="{{ $product->is_available ? 'available' : 'unavailable' }}"
                         data-featured="{{ $product->is_featured ? 'featured' : 'not-featured' }}"
                         data-date="{{ $product->created_at->timestamp }}">
                        <div class="card admin-card h-100 product-card">
                            <!-- Image du produit -->
                            <div class="position-relative">
                                @if($product->image_url)
                                    <img src="{{ $product->image_url }}" 
                                         alt="{{ $product->name }}" 
                                         class="card-img-top"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bx bx-package display-4 text-muted"></i>
                                    </div>
                                @endif
                                
                                <!-- Badges -->
                                <div class="position-absolute top-0 end-0 p-2">
                                    @if($product->is_featured)
                                        <span class="badge bg-warning text-dark">
                                            <i class="bx bx-star"></i> Vedette
                                        </span>
                                    @endif
                                    @if(!$product->is_available)
                                        <span class="badge bg-danger ms-1">Indisponible</span>
                                    @endif
                                </div>
                                
                                <!-- Prix avec réduction -->
                                <div class="position-absolute bottom-0 start-0 p-2">
                                    <div class="bg-white rounded-pill px-3 py-1 shadow-sm">
                                        @if($product->old_price)
                                            <small class="text-muted text-decoration-line-through me-2">{{ $product->formatted_old_price }}</small>
                                        @endif
                                        <span class="fw-bold text-primary">{{ $product->formatted_price }}</span>
                                        @if($product->discount_percentage)
                                            <small class="text-success ms-1">-{{ $product->discount_percentage }}%</small>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Contenu de la carte -->
                            <div class="card-body p-3">
                                <!-- Nom et catégorie -->
                                <div class="mb-2">
                                    <h6 class="mb-1 fw-bold">{{ $product->name }}</h6>
                                    @if($product->category)
                                        <small class="text-muted">
                                            {{ $product->category->emoji }} {{ $product->category->name }}
                                        </small>
                                    @endif
                                </div>

                                <!-- Commerce (si vue globale) -->
                                @if(!isset($commerce))
                                    <div class="mb-2">
                                        <i class="bx bx-store text-primary me-2"></i>
                                        <span class="fw-semibold">{{ $product->commerce->name }}</span>
                                    </div>
                                @endif

                                <!-- Description -->
                                @if($product->description)
                                    <div class="mb-2">
                                        <small class="text-muted">{{ Str::limit($product->description, 80) }}</small>
                                    </div>
                                @endif

                                <!-- Stock -->
                                @if($product->stock !== null)
                                    <div class="mb-2">
                                        <i class="bx bx-package text-info me-2"></i>
                                        <span class="small">Stock: {{ $product->stock }} {{ $product->unit ?? 'unités' }}</span>
                                    </div>
                                @endif
                            </div>

                            <!-- Footer avec actions -->
                            <div class="card-footer bg-transparent border-top p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <small class="text-muted">
                                        <i class="bx bx-time me-1"></i>
                                        {{ $product->created_at->format('d/m/Y') }}
                                    </small>
                                    <div class="d-flex gap-1">
                                        <!-- Modifier -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn admin-btn-edit" 
                                                onclick="editProduct({{ $product->id }})"
                                                title="Modifier">
                                            <i class="bx bx-edit-alt"></i>
                                        </button>
                                        
                                        <!-- Toggle Disponibilité -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn {{ $product->is_available ? 'admin-btn-toggle-active' : 'admin-btn-toggle-inactive' }}" 
                                                onclick="toggleProductAvailability({{ $product->id }})"
                                                title="{{ $product->is_available ? 'Rendre indisponible' : 'Rendre disponible' }}">
                                            <i class="bx {{ $product->is_available ? 'bx-check-circle' : 'bx-x-circle' }}"></i>
                                        </button>
                                        
                                        <!-- Toggle Vedette -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn {{ $product->is_featured ? 'admin-btn-warning' : 'admin-btn-secondary' }}" 
                                                onclick="toggleProductFeatured({{ $product->id }})"
                                                title="{{ $product->is_featured ? 'Retirer des vedettes' : 'Mettre en vedette' }}">
                                            <i class="bx {{ $product->is_featured ? 'bx-star' : 'bx-star' }}"></i>
                                        </button>
                                        
                                        <!-- Supprimer -->
                                        <button type="button" 
                                                class="btn btn-sm admin-action-btn admin-btn-delete" 
                                                onclick="deleteProduct({{ $product->id }})"
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
        @else
            <!-- État vide -->
            <div class="admin-card card">
                <div class="card-body">
                    <div class="admin-empty-state">
                        <div class="mb-4">
                            <i class="bx bx-package display-2 text-muted"></i>
                        </div>
                        <h5 class="text-dark mb-2">Aucun produit trouvé</h5>
                        <p class="text-muted mb-4">
                            @if(isset($commerce))
                                Ajoutez le premier produit de {{ $commerce->name }}
                            @else
                                Aucun produit n'a été ajouté pour le moment
                            @endif
                        </p>
                        <button type="button" 
                                class="btn btn-admin-primary btn-lg rounded-pill px-4" 
                                data-bs-toggle="modal" data-bs-target="#productModal"
                                onclick="openCreateProductModal()">
                            <i class="bx bx-plus"></i> Créer un produit
                        </button>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

{{-- Modal pour créer/éditer un produit --}}
<div class="modal fade admin-modal" id="productModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouveau Produit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="productForm" method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="productId" name="id">
                <input type="hidden" id="methodField" name="_method" value="POST">
                @if(isset($commerce))
                    <input type="hidden" name="commerce_id" value="{{ $commerce->id }}">
                @endif
                
                <div class="modal-body">
                    <div class="row">
                        <!-- Informations principales -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du produit <span class="text-danger">*</span></label>
                                <input type="text" class="form-control admin-form-control" id="name" name="name" placeholder="Ex: Pizza Margherita" required>
                                <div class="invalid-feedback" id="name-error"></div>
                            </div>
                            
                            @if(!isset($commerce))
                                <div class="mb-3">
                                    <label for="commerce_id" class="form-label">Commerce <span class="text-danger">*</span></label>
                                    <select class="form-select admin-form-control" id="commerce_id" name="commerce_id" required>
                                        <option value="">Sélectionnez un commerce</option>
                                        @foreach($commerces as $c)
                                            <option value="{{ $c->id }}">{{ $c->name }} - {{ $c->commerce_type_name }}</option>
                                        @endforeach
                                    </select>
                                    <div class="invalid-feedback" id="commerce_id-error"></div>
                                </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie</label>
                                <select class="form-select admin-form-control" id="category_id" name="category_id">
                                    <option value="">Aucune catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->emoji }} {{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="category_id-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="image" class="form-label">Image du produit</label>
                                <input type="file" class="form-control admin-form-control" id="image" name="image" accept="image/*">
                                <div class="form-text">PNG, JPG, GIF. Max 2MB</div>
                                <div class="invalid-feedback" id="image-error"></div>
                                <div id="currentImage" class="mt-2 d-none">
                                    <img id="imagePreview" src="" alt="Image actuelle" class="img-thumbnail" style="max-width: 200px;">
                                </div>
                            </div>
                        </div>
                        
                        <!-- Prix et stock -->
                        <div class="col-md-6">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Prix <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <input type="number" class="form-control admin-form-control" id="price" name="price" step="1" min="0" placeholder="0" required>
                                            <span class="input-group-text">FCFA</span>
                                        </div>
                                        <div class="invalid-feedback" id="price-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="old_price" class="form-label">Ancien prix</label>
                                        <div class="input-group">
                                            <input type="number" class="form-control admin-form-control" id="old_price" name="old_price" step="1" min="0" placeholder="0">
                                            <span class="input-group-text">FCFA</span>
                                        </div>
                                        <div class="form-text">Pour afficher une réduction</div>
                                        <div class="invalid-feedback" id="old_price-error"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="stock" class="form-label">Stock</label>
                                        <input type="number" class="form-control admin-form-control" id="stock" name="stock" min="0" placeholder="Illimité">
                                        <div class="invalid-feedback" id="stock-error"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unité</label>
                                        <input type="text" class="form-control admin-form-control" id="unit" name="unit" placeholder="Ex: pièces, kg, L">
                                        <div class="invalid-feedback" id="unit-error"></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="sku" class="form-label">Code produit (SKU)</label>
                                <input type="text" class="form-control admin-form-control" id="sku" name="sku" placeholder="Ex: PIZZA-MARG-001">
                                <div class="invalid-feedback" id="sku-error"></div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="preparation_time" class="form-label">Temps de préparation (minutes)</label>
                                <input type="number" class="form-control admin-form-control" id="preparation_time" name="preparation_time" min="0" placeholder="Ex: 15">
                                <div class="invalid-feedback" id="preparation_time-error"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control admin-form-control" id="description" name="description" rows="3" placeholder="Description du produit..."></textarea>
                        <div class="invalid-feedback" id="description-error"></div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="hidden" name="is_available" value="0">
                                <input class="form-check-input" type="checkbox" id="is_available" name="is_available" value="1" checked>
                                <label class="form-check-label" for="is_available">Produit disponible</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input type="hidden" name="is_featured" value="0">
                                <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1">
                                <label class="form-check-label" for="is_featured">Mettre en vedette</label>
                            </div>
                        </div>
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
const PRODUCT_BASE_URL = '{{ route("admin.products.index") }}';

// Fonctions d'action
function openCreateProductModal() {
    AdminComponents.initCreateModal('productModal', {
        title: 'Nouveau Produit',
        submitText: 'Créer'
    });
    
    // Réinitialiser l'image
    document.getElementById('currentImage').classList.add('d-none');
}

function editProduct(id) {
    AdminComponents.loadForEdit(id, PRODUCT_BASE_URL, {
        successCallback: (data) => {
            const product = data.product;
            document.getElementById('modalTitle').textContent = 'Modifier le Produit';
            document.querySelector('[data-submit-text]').textContent = 'Modifier';
            document.getElementById('productId').value = product.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('name').value = product.name;
            document.getElementById('price').value = product.price;
            document.getElementById('old_price').value = product.old_price || '';
            document.getElementById('stock').value = product.stock || '';
            document.getElementById('unit').value = product.unit || '';
            document.getElementById('sku').value = product.sku || '';
            document.getElementById('preparation_time').value = product.preparation_time || '';
            document.getElementById('description').value = product.description || '';
            document.getElementById('is_available').checked = product.is_available;
            document.getElementById('is_featured').checked = product.is_featured;
            
            if (document.getElementById('commerce_id')) {
                document.getElementById('commerce_id').value = product.commerce_id;
            }
            if (document.getElementById('category_id')) {
                document.getElementById('category_id').value = product.category_id || '';
            }
            
            // Afficher l'image actuelle
            if (product.image_url) {
                document.getElementById('imagePreview').src = product.image_url;
                document.getElementById('currentImage').classList.remove('d-none');
            }
            
            new bootstrap.Modal(document.getElementById('productModal')).show();
        }
    });
}

function toggleProductAvailability(id) {
    AdminComponents.toggleStatus(id, PRODUCT_BASE_URL + '/' + id + '/toggle-availability', {
        confirmMessage: 'Changer la disponibilité de ce produit ?',
        successCallback: (data) => {
            // Mettre à jour l'affichage
            const card = document.getElementById(`product-card-${id}`);
            if (card) {
                card.dataset.available = data.is_available ? 'available' : 'unavailable';
                
                // Mettre à jour le bouton
                const toggleBtn = document.querySelector(`[onclick="toggleProductAvailability(${id})"]`);
                if (toggleBtn) {
                    toggleBtn.className = `btn btn-sm admin-action-btn ${data.is_available ? 'admin-btn-toggle-active' : 'admin-btn-toggle-inactive'}`;
                    toggleBtn.title = data.is_available ? 'Rendre indisponible' : 'Rendre disponible';
                    toggleBtn.innerHTML = `<i class="bx ${data.is_available ? 'bx-check-circle' : 'bx-x-circle'}"></i>`;
                }
            }
        }
    });
}

function toggleProductFeatured(id) {
    AdminComponents.toggleStatus(id, PRODUCT_BASE_URL + '/' + id + '/toggle-featured', {
        confirmMessage: 'Changer le statut vedette de ce produit ?',
        successCallback: (data) => {
            // Mettre à jour l'affichage
            const card = document.getElementById(`product-card-${id}`);
            if (card) {
                card.dataset.featured = data.is_featured ? 'featured' : 'not-featured';
                
                // Mettre à jour le bouton
                const toggleBtn = document.querySelector(`[onclick="toggleProductFeatured(${id})"]`);
                if (toggleBtn) {
                    toggleBtn.className = `btn btn-sm admin-action-btn ${data.is_featured ? 'admin-btn-warning' : 'admin-btn-secondary'}`;
                    toggleBtn.title = data.is_featured ? 'Retirer des vedettes' : 'Mettre en vedette';
                }
            }
        }
    });
}

function deleteProduct(id) {
    AdminComponents.deleteItem(id, PRODUCT_BASE_URL, {
        confirmMessage: 'Supprimer définitivement ce produit ? Cette action est irréversible.',
        successCallback: () => {
            // Supprimer la carte du DOM
            const card = document.getElementById(`product-card-${id}`);
            if (card) {
                card.remove();
            }
        }
    });
}

// Gestion du formulaire avec upload d'image
document.getElementById('productForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('[type="submit"]');
    const submitText = submitBtn.querySelector('[data-submit-text]');
    const submitSpinner = submitBtn.querySelector('.spinner-border');
    
    // Désactiver le bouton
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    const id = document.getElementById('productId').value;
    
    let url = '{{ route("admin.products.store") }}';
    if (id) {
        url = `${PRODUCT_BASE_URL}/${id}`;
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
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

// Prévisualisation de l'image
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('imagePreview').src = e.target.result;
            document.getElementById('currentImage').classList.remove('d-none');
        };
        reader.readAsDataURL(file);
    }
});

// ===== SYSTÈME DE RECHERCHE ET FILTRAGE =====

// Variables globales
const searchInput = document.getElementById('searchInput');
const categoryFilter = document.getElementById('categoryFilter');
const statusFilter = document.getElementById('statusFilter');
const sortBy = document.getElementById('sortBy');
const commerceFilter = document.getElementById('commerceFilter');
const resultsCount = document.getElementById('resultsCount');
const productsGrid = document.getElementById('productsGrid');

const totalProducts = {{ $products->count() }};
let productItems = Array.from(document.querySelectorAll('.product-item'));

// Fonction de filtrage principal
function filterProducts() {
    const searchTerm = searchInput.value.toLowerCase().trim();
    const selectedCategory = categoryFilter.value;
    const selectedStatus = statusFilter.value;
    const selectedCommerce = commerceFilter ? commerceFilter.value : '';
    
    let visibleCount = 0;
    
    productItems.forEach(item => {
        const name = item.dataset.name;
        const category = item.dataset.category;
        const available = item.dataset.available;
        const featured = item.dataset.featured;
        const commerce = item.dataset.commerce || '';
        
        // Critères de recherche
        const matchesSearch = !searchTerm || name.includes(searchTerm);
        const matchesCategory = !selectedCategory || category === selectedCategory;
        const matchesCommerce = !selectedCommerce || commerce === selectedCommerce;
        
        let matchesStatus = true;
        if (selectedStatus === 'available') {
            matchesStatus = available === 'available';
        } else if (selectedStatus === 'unavailable') {
            matchesStatus = available === 'unavailable';
        } else if (selectedStatus === 'featured') {
            matchesStatus = featured === 'featured';
        }
        
        // Afficher/masquer la carte
        if (matchesSearch && matchesCategory && matchesStatus && matchesCommerce) {
            item.style.display = 'block';
            item.style.animation = 'fadeInUp 0.3s ease';
            visibleCount++;
        } else {
            item.style.display = 'none';
        }
    });
    
    // Trier les produits visibles
    sortProducts();
    
    // Mettre à jour le compteur
    resultsCount.textContent = visibleCount;
}

// Fonction de tri
function sortProducts() {
    const sortValue = sortBy.value;
    const visibleItems = productItems.filter(item => item.style.display !== 'none');
    
    visibleItems.sort((a, b) => {
        switch (sortValue) {
            case 'name-asc':
                return a.dataset.name.localeCompare(b.dataset.name);
            case 'name-desc':
                return b.dataset.name.localeCompare(a.dataset.name);
            case 'price-asc':
                return parseFloat(a.dataset.price) - parseFloat(b.dataset.price);
            case 'price-desc':
                return parseFloat(b.dataset.price) - parseFloat(a.dataset.price);
            case 'date-desc':
                return parseInt(b.dataset.date) - parseInt(a.dataset.date);
            default:
                return 0;
        }
    });
    
    // Réorganiser les éléments dans le DOM
    visibleItems.forEach(item => {
        productsGrid.appendChild(item);
    });
}

// Event listeners
searchInput.addEventListener('input', filterProducts);
categoryFilter.addEventListener('change', filterProducts);
statusFilter.addEventListener('change', filterProducts);
sortBy.addEventListener('change', filterProducts);
if (commerceFilter) {
    commerceFilter.addEventListener('change', filterProducts);
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Tri initial
    sortProducts();
});
</script>
@endpush 