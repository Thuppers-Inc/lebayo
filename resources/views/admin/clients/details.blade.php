@extends('admin.layouts.master')

@section('title', 'Analyse Détaillée des Clients')
@section('description', 'Analyse complète et détaillée de tous les clients avec statistiques avancées')

@section('content')
<div class="container-fluid">
    <!-- En-tête de la page -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Analyse Détaillée des Clients</h1>
        <div class="btn-group" role="group">
            <a href="{{ route('admin.clients.index') }}" class="btn btn-secondary">
                <i class="bx bx-arrow-back"></i> Retour à la vue générale
            </a>
            <a href="{{ route('admin.clients.details.export') }}" class="btn btn-success">
                <i class="bx bx-download"></i> Exporter CSV Détaillé
            </a>
        </div>
    </div>

    <!-- Statistiques globales détaillées -->
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
                                Nouveaux ce Mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['new_clients_this_month']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-user-plus fa-2x text-gray-300"></i>
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
                                Moyenne Commandes/Client</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['avg_orders_per_client'], 1) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-bar-chart-alt-2 fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses détaillées -->
    <div class="row mb-4">
        <!-- Top clients par nombre de commandes (paginé) -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Clients par Commandes</h6>
                    <a href="{{ route('admin.clients.top.orders.export') }}" class="btn btn-success btn-sm">
                        <i class="bx bx-download"></i> Exporter
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Rang</th>
                                    <th>Client</th>
                                    <th>Commandes</th>
                                    <th>Total Dépensé</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topClientsByOrders as $index => $client)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ ($topClientsByOrders->currentPage() - 1) * $topClientsByOrders->perPage() + $index + 1 <= 3 ? 'warning' : 'secondary' }}">
                                            #{{ ($topClientsByOrders->currentPage() - 1) * $topClientsByOrders->perPage() + $index + 1 }}
                                        </span>
                                    </td>
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
                                    <td colspan="5" class="text-center">Aucun client avec des commandes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination pour top clients par commandes -->
                    @if($topClientsByOrders->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $topClientsByOrders->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top clients par montant dépensé (paginé) -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Top Clients par Dépenses</h6>
                    <a href="{{ route('admin.clients.top.revenue.export') }}" class="btn btn-success btn-sm">
                        <i class="bx bx-download"></i> Exporter
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Rang</th>
                                    <th>Client</th>
                                    <th>Total Dépensé</th>
                                    <th>Commandes</th>
                                    <th>Moyenne</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topClientsByRevenue as $index => $client)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ ($topClientsByRevenue->currentPage() - 1) * $topClientsByRevenue->perPage() + $index + 1 <= 3 ? 'warning' : 'secondary' }}">
                                            #{{ ($topClientsByRevenue->currentPage() - 1) * $topClientsByRevenue->perPage() + $index + 1 }}
                                        </span>
                                    </td>
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
                                    <td colspan="5" class="text-center">Aucun client avec des dépenses</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination pour top clients par dépenses -->
                    @if($topClientsByRevenue->hasPages())
                    <div class="d-flex justify-content-center mt-3">
                        {{ $topClientsByRevenue->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Analyses supplémentaires -->
    <div class="row mb-4">
        <!-- Clients récents -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-info">Nouveaux Clients ce Mois</h6>
                    <a href="{{ route('admin.clients.recent.export') }}" class="btn btn-success btn-sm">
                        <i class="bx bx-download"></i> Exporter
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Inscription</th>
                                    <th>Commandes</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($recentClients as $client)
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
                                        <div>{{ $client->created_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $client->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td><span class="badge bg-{{ $client->orders_count > 0 ? 'success' : 'secondary' }}">{{ $client->orders_count }}</span></td>
                                    <td><span class="badge bg-{{ $client->client_status_class }}">{{ $client->client_status }}</span></td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun nouveau client ce mois</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clients actifs récents -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-warning">Clients Actifs (30 derniers jours)</h6>
                    <a href="{{ route('admin.clients.active.export') }}" class="btn btn-success btn-sm">
                        <i class="bx bx-download"></i> Exporter
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Client</th>
                                    <th>Dépenses Récentes</th>
                                    <th>Commandes</th>
                                    <th>Dernière Activité</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($activeClients as $client)
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
                                    <td><span class="badge bg-primary">{{ $client->orders_count }}</span></td>
                                    <td>
                                        <div>{{ $client->formatted_last_order_date }}</div>
                                        <small class="text-muted">{{ $client->seniority_label }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun client actif récemment</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Liste complète des clients avec pagination -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste Complète des Clients ({{ $clients->total() }} clients)</h6>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.clients.details.export') }}" class="btn btn-success btn-sm">
                    <i class="bx bx-download"></i> Exporter CSV Détaillé
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="clientsDetailsTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Client</th>
                            <th>Contact</th>
                            <th>Localisation</th>
                            <th>Informations Personnelles</th>
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
                                <div class="small">
                                    @if($client->date_naissance)
                                        <div><strong>Né(e):</strong> {{ $client->date_naissance->format('d/m/Y') }}</div>
                                    @endif
                                    @if($client->lieu_naissance)
                                        <div><strong>Lieu:</strong> {{ $client->lieu_naissance }}</div>
                                    @endif
                                    @if($client->numero_cni)
                                        <div><strong>CNI:</strong> {{ $client->numero_cni }}</div>
                                    @endif
                                    @if($client->numero_passeport)
                                        <div><strong>Passeport:</strong> {{ $client->numero_passeport }}</div>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="row text-center">
                                    <div class="col-4">
                                        <div class="font-weight-bold text-primary">{{ $client->orders_count }}</div>
                                        <small class="text-muted">Commandes</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="font-weight-bold text-success">{{ $client->formatted_total_spent }}</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                    <div class="col-4">
                                        <div class="font-weight-bold text-info">{{ $client->addresses_count }}</div>
                                        <small class="text-muted">Adresses</small>
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
                                <div class="small text-muted">{{ $client->created_at->diffInDays(now()) }} jours</div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewClient({{ $client->id }})" title="Voir">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-info" onclick="viewClientOrders({{ $client->id }})" title="Commandes">
                                        <i class="bx bx-package"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
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
    $('#clientsDetailsTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[7, "desc"]], // Trier par date d'inscription
        "columnDefs": [
            { "orderable": false, "targets": [8] } // Désactiver le tri sur la colonne actions
        ]
    });
});

// Fonctions d'action
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

function viewClientOrders(id) {
    window.location.href = `${CLIENT_BASE_URL}/${id}/orders`;
}

function openClientOrders() {
    if (currentClientId) {
        window.location.href = `${CLIENT_BASE_URL}/${currentClientId}/orders`;
    }
}
</script>
@endpush
