@extends('admin.layouts.master')

@section('title', 'Analyse des Clients')
@section('description', 'Analyse détaillée des clients et de leurs comportements d\'achat')

@section('content')
<div class="container-fluid">
    <!-- Statistiques globales -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Clients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_clients']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Clients Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_clients']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Commandes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_orders']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-package fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Chiffre d'Affaires</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_revenue']) }} FCFA</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-money fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses détaillées -->
    <div class="row mb-4">
        <!-- Top clients par nombre de commandes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Clients par Commandes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Commandes</th>
                                    <th>Total Dépensé</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topClientsByOrders as $client)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $client->photo_url }}" alt="{{ $client->full_name }}" class="admin-logo-sm rounded-circle me-2">
                                            <div>
                                                <div class="font-weight-bold">{{ $client->full_name }}</div>
                                                <small class="text-muted">{{ $client->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-primary">{{ $client->orders_count }}</span></td>
                                    <td>{{ $client->formatted_total_spent }}</td>
                                    <td><span class="badge bg-{{ $client->client_status_class }}">{{ $client->client_status }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun client avec des commandes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top clients par montant dépensé -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Top Clients par Dépenses</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Total Dépensé</th>
                                    <th>Commandes</th>
                                    <th>Moyenne</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topClientsByRevenue as $client)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $client->photo_url }}" alt="{{ $client->full_name }}" class="admin-logo-sm rounded-circle me-2">
                                            <div>
                                                <div class="font-weight-bold">{{ $client->full_name }}</div>
                                                <small class="text-muted">{{ $client->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="font-weight-bold text-success">{{ $client->formatted_total_spent }}</span></td>
                                    <td>{{ $client->orders_count }}</td>
                                    <td>{{ $client->formatted_average_order_value }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun client avec des dépenses</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Datatable principal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste Complète des Clients</h6>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.clients.export') }}" class="btn btn-success btn-sm">
                    <i class="bx bx-download"></i> Exporter CSV
                </a>
                <button class="btn btn-primary btn-sm" onclick="openCreateClientModal()">
                    <i class="bx bx-plus"></i> Nouveau Client
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="clientsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Localisation</th>
                            <th>Statistiques</th>
                            <th>Statut</th>
                            <th>Dernière Commande</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($clients as $client)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $client->photo_url }}" alt="{{ $client->full_name }}" class="admin-logo-sm rounded-circle me-2">
                                    <div>
                                        <div class="font-weight-bold">{{ $client->full_name }}</div>
                                        <small class="text-muted">{{ $client->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $client->formatted_phone }}</div>
                                <small class="text-muted">{{ $client->email }}</small>
                            </td>
                            <td>
                                @if($client->ville)
                                    <div>{{ $client->ville }}</div>
                                    @if($client->commune)
                                        <small class="text-muted">{{ $client->commune }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                            <td>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="font-weight-bold text-primary">{{ $client->orders_count }}</div>
                                        <small class="text-muted">Commandes</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="font-weight-bold text-success">{{ $client->formatted_total_spent }}</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                @if($client->orders_count > 0)
                                <div class="text-center mt-1">
                                    <small class="text-muted">Moy: {{ $client->formatted_average_order_value }}</small>
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $client->client_status_class }}">{{ $client->client_status }}</span>
                            </td>
                            <td>
                                @if($client->orders_count > 0)
                                    <div>{{ $client->formatted_last_order_date }}</div>
                                    <small class="text-muted">{{ $client->seniority_label }}</small>
                                @else
                                    <span class="text-muted">Aucune commande</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $client->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $client->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewClient({{ $client->id }})" title="Voir">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editClient({{ $client->id }})" title="Modifier">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteClient({{ $client->id }})" title="Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">
                                <div class="py-4">
                                    <i class="bx bx-user fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucun client trouvé</h5>
                                    <p class="text-muted">Aucun client n'est encore inscrit sur la plateforme</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($clients->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $clients->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

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

// Initialisation du datatable
$(document).ready(function() {
    $('#clientsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[6, "desc"]], // Trier par date d'inscription
        "columnDefs": [
            { "orderable": false, "targets": [7] } // Désactiver le tri sur la colonne actions
        ]
    });
});

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
                            <span class="badge bg-${client.client_status_class}">${client.client_status}</span>
                        </div>
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h4>${client.orders_count || 0}</h4>
                                            <small>Commandes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h4>${client.formatted_total_spent || '0 FCFA'}</h4>
                                            <small>Total Dépensé</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <tr><td><strong>Téléphone:</strong></td><td>${client.formatted_phone}</td></tr>
                                <tr><td><strong>Date de naissance:</strong></td><td>${client.date_naissance || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Lieu de naissance:</strong></td><td>${client.lieu_naissance || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Ville:</strong></td><td>${client.ville || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Commune:</strong></td><td>${client.commune || 'Non renseignée'}</td></tr>
                                <tr><td><strong>CNI:</strong></td><td>${client.numero_cni || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Passeport:</strong></td><td>${client.numero_passeport || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Adresses:</strong></td><td><span class="admin-badge admin-badge-success">${client.addresses_count || 0}</span></td></tr>
                                <tr><td><strong>Dernière commande:</strong></td><td>${client.formatted_last_order_date || 'Aucune'}</td></tr>
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
