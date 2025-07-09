@extends('admin.layouts.master')

@section('title', 'Clients')
@section('description', 'Gestion des clients de la plateforme')

@section('content')
{{-- Utilisation du composant data-table réutilisable --}}
@include('admin.components.data-table', [
    // Titre et description
    'title' => 'Liste des Clients',
    'description' => 'Gérez tous les clients inscrits sur la plateforme',
    
    // Bouton de création avec modal
    'modalTarget' => '#clientModal',
    'createText' => 'Nouveau Client',
    'createCallback' => 'openCreateClientModal()',
    
    // Données et configuration des colonnes
    'items' => $clients,
    
    'columns' => [
        [
            'key' => 'full_name', 
            'label' => 'Client', 
            'type' => 'logo-text',
            'logo_key' => 'photo_url'
        ],
        [
            'key' => 'email', 
            'label' => 'Email', 
            'type' => 'text'
        ],
        [
            'key' => 'formatted_phone', 
            'label' => 'Téléphone', 
            'type' => 'text'
        ],
        [
            'key' => 'ville', 
            'label' => 'Ville', 
            'type' => 'text'
        ],
        [
            'key' => 'orders_count', 
            'label' => 'Commandes', 
            'type' => 'badge'
        ],
        [
            'key' => 'created_at', 
            'label' => 'Inscription', 
            'type' => 'date'
        ]
    ],
    
    // Actions disponibles
    'actions' => ['view', 'edit', 'delete'],
    'viewCallback' => 'viewClient',
    'editCallback' => 'editClient',
    'deleteCallback' => 'deleteClient',
    
    // État vide
    'emptyIcon' => 'bx-user',
    'emptyTitle' => 'Aucun client trouvé',
    'emptyMessage' => 'Aucun client n\'est encore inscrit sur la plateforme'
])

{{-- Modal pour créer/éditer un client --}}
<div class="modal fade admin-modal" id="clientModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouveau Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="clientForm" method="POST" action="{{ route('admin.clients.store') }}">
                @csrf
                <input type="hidden" id="clientId" name="id">
                <input type="hidden" id="methodField" name="_method" value="POST">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom <span class="text-danger">*</span></label>
                            <input type="text" class="form-control admin-form-control" id="nom" name="nom" placeholder="Ex: Traoré" required>
                            <div class="invalid-feedback" id="nom-error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="prenoms" class="form-label">Prénoms <span class="text-danger">*</span></label>
                            <input type="text" class="form-control admin-form-control" id="prenoms" name="prenoms" placeholder="Ex: Ismaël Junior" required>
                            <div class="invalid-feedback" id="prenoms-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control admin-form-control" id="email" name="email" placeholder="client@exemple.com" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control admin-form-control" id="date_naissance" name="date_naissance">
                            <div class="invalid-feedback" id="date_naissance-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="indicatif" class="form-label">Indicatif <span class="text-danger">*</span></label>
                            <select class="form-select admin-form-control" id="indicatif" name="indicatif" required>
                                <option value="+225">+225 (CI)</option>
                                <option value="+226">+226 (BF)</option>
                                <option value="+221">+221 (SN)</option>
                                <option value="+223">+223 (ML)</option>
                                <option value="+227">+227 (NE)</option>
                                <option value="+33">+33 (FR)</option>
                            </select>
                            <div class="invalid-feedback" id="indicatif-error"></div>
                        </div>
                        
                        <div class="col-md-9 mb-3">
                            <label for="numero_telephone" class="form-label">Téléphone <span class="text-danger">*</span></label>
                            <input type="tel" class="form-control admin-form-control" id="numero_telephone" name="numero_telephone" placeholder="0102030405" required>
                            <div class="invalid-feedback" id="numero_telephone-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control admin-form-control" id="ville" name="ville" placeholder="Ex: Abidjan">
                            <div class="invalid-feedback" id="ville-error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="commune" class="form-label">Commune</label>
                            <input type="text" class="form-control admin-form-control" id="commune" name="commune" placeholder="Ex: Cocody">
                            <div class="invalid-feedback" id="commune-error"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                            <input type="text" class="form-control admin-form-control" id="lieu_naissance" name="lieu_naissance" placeholder="Ex: Abidjan">
                            <div class="invalid-feedback" id="lieu_naissance-error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="numero_cni" class="form-label">Numéro CNI</label>
                            <input type="text" class="form-control admin-form-control" id="numero_cni" name="numero_cni" placeholder="Ex: CI0123456789">
                            <div class="invalid-feedback" id="numero_cni-error"></div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="numero_passeport" class="form-label">Numéro de passeport</label>
                        <input type="text" class="form-control admin-form-control" id="numero_passeport" name="numero_passeport" placeholder="Ex: PS0123456">
                        <div class="invalid-feedback" id="numero_passeport-error"></div>
                    </div>
                    
                    <div class="row" id="passwordFields">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe <span class="text-danger" id="passwordRequired">*</span></label>
                            <input type="password" class="form-control admin-form-control" id="password" name="password" placeholder="••••••••">
                            <div class="invalid-feedback" id="password-error"></div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer <span class="text-danger" id="passwordConfirmRequired">*</span></label>
                            <input type="password" class="form-control admin-form-control" id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                            <div class="invalid-feedback" id="password_confirmation-error"></div>
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

{{-- Modal pour voir les détails d'un client --}}
<div class="modal fade admin-modal" id="clientViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails du Client</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="clientViewContent">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-admin-primary" onclick="openClientOrders()">
                    <i class="bx bx-package"></i> Voir les Commandes
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Configuration des URLs
const CLIENT_BASE_URL = '{{ route("admin.clients.index") }}';
let currentClientId = null;

// Fonctions d'action
function openCreateClientModal() {
    AdminComponents.initCreateModal('clientModal', {
        title: 'Nouveau Client',
        submitText: 'Créer'
    });
    
    // Réinitialiser les champs de mot de passe comme requis
    document.getElementById('passwordRequired').style.display = 'inline';
    document.getElementById('passwordConfirmRequired').style.display = 'inline';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
}

function viewClient(id) {
    currentClientId = id;
    fetch(`${CLIENT_BASE_URL}/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const client = data.client;
                document.getElementById('clientViewContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${client.photo_url}" alt="${client.full_name}" class="admin-logo-lg rounded-circle mb-3">
                            <h5>${client.full_name}</h5>
                            <p class="text-muted">${client.email}</p>
                        </div>
                        <div class="col-md-8">
                            <table class="table table-borderless">
                                <tr><td><strong>Téléphone:</strong></td><td>${client.formatted_phone}</td></tr>
                                <tr><td><strong>Date de naissance:</strong></td><td>${client.date_naissance || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Lieu de naissance:</strong></td><td>${client.lieu_naissance || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Ville:</strong></td><td>${client.ville || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Commune:</strong></td><td>${client.commune || 'Non renseignée'}</td></tr>
                                <tr><td><strong>CNI:</strong></td><td>${client.numero_cni || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Passeport:</strong></td><td>${client.numero_passeport || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Commandes:</strong></td><td><span class="admin-badge admin-badge-primary">${client.orders_count || 0}</span></td></tr>
                                <tr><td><strong>Adresses:</strong></td><td><span class="admin-badge admin-badge-success">${client.addresses_count || 0}</span></td></tr>
                                <tr><td><strong>Inscription:</strong></td><td>${new Date(client.created_at).toLocaleDateString('fr-FR')}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('clientViewModal')).show();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            AdminComponents.showAlert('Erreur lors du chargement des détails', 'danger');
        });
}

function editClient(id) {
    AdminComponents.loadForEdit(id, CLIENT_BASE_URL, {
        successCallback: (data) => {
            const client = data.client;
            document.getElementById('modalTitle').textContent = 'Modifier le Client';
            document.querySelector('[data-submit-text]').textContent = 'Modifier';
            document.getElementById('clientId').value = client.id;
            document.getElementById('methodField').value = 'PUT';
            
            // Remplir les champs
            document.getElementById('nom').value = client.nom || '';
            document.getElementById('prenoms').value = client.prenoms || '';
            document.getElementById('email').value = client.email || '';
            document.getElementById('date_naissance').value = client.date_naissance || '';
            document.getElementById('indicatif').value = client.indicatif || '+225';
            document.getElementById('numero_telephone').value = client.numero_telephone || '';
            document.getElementById('ville').value = client.ville || '';
            document.getElementById('commune').value = client.commune || '';
            document.getElementById('lieu_naissance').value = client.lieu_naissance || '';
            document.getElementById('numero_cni').value = client.numero_cni || '';
            document.getElementById('numero_passeport').value = client.numero_passeport || '';
            
            // Mot de passe optionnel en modification
            document.getElementById('passwordRequired').style.display = 'none';
            document.getElementById('passwordConfirmRequired').style.display = 'none';
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('password').placeholder = 'Laisser vide pour ne pas changer';
            document.getElementById('password_confirmation').placeholder = 'Laisser vide pour ne pas changer';
            
            new bootstrap.Modal(document.getElementById('clientModal')).show();
        }
    });
}

function deleteClient(id) {
    AdminComponents.deleteItem(id, CLIENT_BASE_URL, {
        confirmMessage: 'Supprimer définitivement ce client ? Cette action est irréversible et supprimera aussi toutes ses commandes et adresses.'
    });
}

function openClientOrders() {
    if (currentClientId) {
        window.location.href = `${CLIENT_BASE_URL}/${currentClientId}/orders`;
    }
}

// Gestion du formulaire avec URL dynamique
document.getElementById('clientForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = this.querySelector('[type="submit"]');
    const submitText = submitBtn.querySelector('[data-submit-text]');
    const submitSpinner = submitBtn.querySelector('.spinner-border');
    
    // Désactiver le bouton
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    const id = document.getElementById('clientId').value;
    const method = document.getElementById('methodField').value;
    
    let url = '{{ route("admin.clients.store") }}';
    if (id) {
        url = `${CLIENT_BASE_URL}/${id}`;
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('clientModal'));
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