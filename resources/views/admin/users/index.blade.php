@extends('admin.layouts.master')

@section('title', 'Gestion des Utilisateurs')
@section('description', 'Gestion complète de tous les utilisateurs de la plateforme')

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
                                Total Utilisateurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['total_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-group fa-2x text-gray-300"></i>
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
                                Administrateurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['admin_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-crown fa-2x text-gray-300"></i>
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
                                Modérateurs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['moderator_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-shield-check fa-2x text-gray-300"></i>
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
                                Clients</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['client_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-user fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Deuxième ligne de statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Agents</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['agent_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-cycling fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-dark shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-dark text-uppercase mb-1">
                                Utilisateurs Actifs</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($stats['active_users']) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bx bx-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
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
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
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
        <!-- Top utilisateurs par nombre de commandes -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Top Utilisateurs par Commandes</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Type</th>
                                    <th>Commandes</th>
                                    <th>Total Dépensé</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topUsersByOrders as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->photo_url }}" alt="{{ $user->full_name }}" class="admin-logo-sm rounded-circle me-2">
                                            <div>
                                                <div class="font-weight-bold">{{ $user->full_name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-{{ $user->account_type_class }}">{{ $user->account_type_label }}</span></td>
                                    <td><span class="badge bg-primary">{{ $user->orders_count }}</span></td>
                                    <td>{{ $user->formatted_total_spent }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun utilisateur avec des commandes</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top utilisateurs par montant dépensé -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-success">Top Utilisateurs par Dépenses</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Utilisateur</th>
                                    <th>Type</th>
                                    <th>Total Dépensé</th>
                                    <th>Commandes</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topUsersByRevenue as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ $user->photo_url }}" alt="{{ $user->full_name }}" class="admin-logo-sm rounded-circle me-2">
                                            <div>
                                                <div class="font-weight-bold">{{ $user->full_name }}</div>
                                                <small class="text-muted">{{ $user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-{{ $user->account_type_class }}">{{ $user->account_type_label }}</span></td>
                                    <td><span class="font-weight-bold text-success">{{ $user->formatted_total_spent }}</span></td>
                                    <td>{{ $user->orders_count }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Aucun utilisateur avec des dépenses</td>
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
            <h6 class="m-0 font-weight-bold text-primary">Liste Complète des Utilisateurs</h6>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.users.export') }}" class="btn btn-success btn-sm">
                    <i class="bx bx-download"></i> Exporter CSV
                </a>
                <button class="btn btn-primary btn-sm" onclick="openCreateUserModal()">
                    <i class="bx bx-plus"></i> Nouvel Utilisateur
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="usersTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Utilisateur</th>
                            <th>Contact</th>
                            <th>Type de Compte</th>
                            <th>Localisation</th>
                            <th>Statistiques</th>
                            <th>Statut</th>
                            <th>Dernière Commande</th>
                            <th>Inscription</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ $user->photo_url }}" alt="{{ $user->full_name }}" class="admin-logo-sm rounded-circle me-2">
                                    <div>
                                        <div class="font-weight-bold">{{ $user->full_name }}</div>
                                        <small class="text-muted">{{ $user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>{{ $user->formatted_phone }}</div>
                                <small class="text-muted">{{ $user->email }}</small>
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->account_type_class }}">{{ $user->account_type_label }}</span>
                            </td>
                            <td>
                                @if($user->ville)
                                    <div>{{ $user->ville }}</div>
                                    @if($user->commune)
                                        <small class="text-muted">{{ $user->commune }}</small>
                                    @endif
                                @else
                                    <span class="text-muted">Non renseigné</span>
                                @endif
                            </td>
                            <td>
                                <div class="row text-center">
                                    <div class="col-6">
                                        <div class="font-weight-bold text-primary">{{ $user->orders_count }}</div>
                                        <small class="text-muted">Commandes</small>
                                    </div>
                                    <div class="col-6">
                                        <div class="font-weight-bold text-success">{{ $user->formatted_total_spent }}</div>
                                        <small class="text-muted">Total</small>
                                    </div>
                                </div>
                                @if($user->orders_count > 0)
                                <div class="text-center mt-1">
                                    <small class="text-muted">Moy: {{ $user->formatted_average_order_value }}</small>
                                </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $user->deleted_at ? 'danger' : 'success' }}">
                                    {{ $user->deleted_at ? 'Inactif' : 'Actif' }}
                                </span>
                            </td>
                            <td>
                                @if($user->orders_count > 0)
                                    <div>{{ $user->formatted_last_order_date }}</div>
                                    <small class="text-muted">{{ $user->seniority_label }}</small>
                                @else
                                    <span class="text-muted">Aucune commande</span>
                                @endif
                            </td>
                            <td>
                                <div>{{ $user->created_at->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $user->created_at->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="viewUser({{ $user->id }})" title="Voir">
                                        <i class="bx bx-show"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-warning" onclick="editUser({{ $user->id }})" title="Modifier">
                                        <i class="bx bx-edit"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-{{ $user->deleted_at ? 'success' : 'danger' }}" onclick="toggleUserStatus({{ $user->id }})" title="{{ $user->deleted_at ? 'Activer' : 'Désactiver' }}">
                                        <i class="bx bx-{{ $user->deleted_at ? 'check' : 'x' }}"></i>
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteUser({{ $user->id }})" title="Supprimer">
                                        <i class="bx bx-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="9" class="text-center">
                                <div class="py-4">
                                    <i class="bx bx-group fa-3x text-muted mb-3"></i>
                                    <h5 class="text-muted">Aucun utilisateur trouvé</h5>
                                    <p class="text-muted">Aucun utilisateur n'est encore inscrit sur la plateforme</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($users->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal pour créer/éditer un utilisateur --}}
<div class="modal fade admin-modal" id="userModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">Nouvel Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="userForm" method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <input type="hidden" id="userId" name="id">
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
                            <input type="email" class="form-control admin-form-control" id="email" name="email" placeholder="user@exemple.com" required>
                            <div class="invalid-feedback" id="email-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="account_type" class="form-label">Type de Compte <span class="text-danger">*</span></label>
                            <select class="form-select admin-form-control" id="account_type" name="account_type" required>
                                <option value="">Sélectionner un type</option>
                                <option value="admin">Administrateur</option>
                                <option value="client">Client</option>
                                <option value="agent">Agent</option>
                            </select>
                            <div class="invalid-feedback" id="account_type-error"></div>
                        </div>
                    </div>

                    <div class="row" id="roleField" style="display: none;">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rôle (pour Admin) <span class="text-danger">*</span></label>
                            <select class="form-select admin-form-control" id="role" name="role">
                                <option value="">Sélectionner un rôle</option>
                                <option value="developer">Développeur</option>
                                <option value="manager">Manager</option>
                                <option value="moderator">Modérateur</option>
                                <option value="user">Utilisateur</option>
                            </select>
                            <div class="invalid-feedback" id="role-error"></div>
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
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control admin-form-control" id="date_naissance" name="date_naissance">
                            <div class="invalid-feedback" id="date_naissance-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="lieu_naissance" class="form-label">Lieu de naissance</label>
                            <input type="text" class="form-control admin-form-control" id="lieu_naissance" name="lieu_naissance" placeholder="Ex: Abidjan">
                            <div class="invalid-feedback" id="lieu_naissance-error"></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="numero_cni" class="form-label">Numéro CNI</label>
                            <input type="text" class="form-control admin-form-control" id="numero_cni" name="numero_cni" placeholder="Ex: CI0123456789">
                            <div class="invalid-feedback" id="numero_cni-error"></div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="numero_passeport" class="form-label">Numéro de passeport</label>
                            <input type="text" class="form-control admin-form-control" id="numero_passeport" name="numero_passeport" placeholder="Ex: PS0123456">
                            <div class="invalid-feedback" id="numero_passeport-error"></div>
                        </div>
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

{{-- Modal pour voir les détails d'un utilisateur --}}
<div class="modal fade admin-modal" id="userViewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'Utilisateur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="userViewContent">
                <!-- Contenu dynamique -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-admin-primary" onclick="openUserOrders()">
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
const USER_BASE_URL = '{{ route("admin.users.index") }}';
let currentUserId = null;

// Initialisation du datatable
$(document).ready(function() {
    $('#usersTable').DataTable({
        "language": {
            "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
        },
        "pageLength": 25,
        "order": [[7, "desc"]], // Trier par date d'inscription
        "columnDefs": [
            { "orderable": false, "targets": [8] } // Désactiver le tri sur la colonne actions
        ]
    });

    // Gestion de l'affichage du champ rôle
    $('#account_type').on('change', function() {
        const roleField = $('#roleField');
        const roleSelect = $('#role');

        if ($(this).val() === 'admin') {
            roleField.show();
            roleSelect.prop('required', true);
        } else {
            roleField.hide();
            roleSelect.prop('required', false);
            roleSelect.val('');
        }
    });
});

// Fonctions d'action
function openCreateUserModal() {
    AdminComponents.initCreateModal('userModal', {
        title: 'Nouvel Utilisateur',
        submitText: 'Créer'
    });

    // Réinitialiser les champs de mot de passe comme requis
    document.getElementById('passwordRequired').style.display = 'inline';
    document.getElementById('passwordConfirmRequired').style.display = 'inline';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
}

function viewUser(id) {
    currentUserId = id;
    fetch(`${USER_BASE_URL}/${id}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const user = data.user;
                document.getElementById('userViewContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${user.photo_url}" alt="${user.full_name}" class="admin-logo-lg rounded-circle mb-3">
                            <h5>${user.full_name}</h5>
                            <p class="text-muted">${user.email}</p>
                            <span class="badge bg-${user.account_type_class}">${user.account_type_label}</span>
                            <br>
                            <span class="badge bg-${user.deleted_at ? 'danger' : 'success'} mt-2">
                                ${user.deleted_at ? 'Inactif' : 'Actif'}
                            </span>
                        </div>
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="card bg-primary text-white">
                                        <div class="card-body text-center">
                                            <h4>${user.orders_count || 0}</h4>
                                            <small>Commandes</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="card bg-success text-white">
                                        <div class="card-body text-center">
                                            <h4>${user.formatted_total_spent || '0 FCFA'}</h4>
                                            <small>Total Dépensé</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table table-borderless">
                                <tr><td><strong>Téléphone:</strong></td><td>${user.formatted_phone}</td></tr>
                                <tr><td><strong>Type de compte:</strong></td><td><span class="badge bg-${user.account_type_class}">${user.account_type_label}</span></td></tr>
                                <tr><td><strong>Date de naissance:</strong></td><td>${user.date_naissance || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Lieu de naissance:</strong></td><td>${user.lieu_naissance || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Ville:</strong></td><td>${user.ville || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Commune:</strong></td><td>${user.commune || 'Non renseignée'}</td></tr>
                                <tr><td><strong>CNI:</strong></td><td>${user.numero_cni || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Passeport:</strong></td><td>${user.numero_passeport || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Adresses:</strong></td><td><span class="admin-badge admin-badge-success">${user.addresses_count || 0}</span></td></tr>
                                <tr><td><strong>Dernière commande:</strong></td><td>${user.formatted_last_order_date || 'Aucune'}</td></tr>
                                <tr><td><strong>Inscription:</strong></td><td>${new Date(user.created_at).toLocaleDateString('fr-FR')}</td></tr>
                            </table>
                        </div>
                    </div>
                `;
                new bootstrap.Modal(document.getElementById('userViewModal')).show();
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            AdminComponents.showAlert('Erreur lors du chargement des détails', 'danger');
        });
}

function editUser(id) {
    AdminComponents.loadForEdit(id, USER_BASE_URL, {
        successCallback: (data) => {
            const user = data.user;
            document.getElementById('modalTitle').textContent = 'Modifier l\'Utilisateur';
            document.querySelector('[data-submit-text]').textContent = 'Modifier';
            document.getElementById('userId').value = user.id;
            document.getElementById('methodField').value = 'PUT';

            // Remplir les champs
            document.getElementById('nom').value = user.nom || '';
            document.getElementById('prenoms').value = user.prenoms || '';
            document.getElementById('email').value = user.email || '';
            document.getElementById('account_type').value = user.account_type || '';
            document.getElementById('role').value = user.role || '';
            document.getElementById('date_naissance').value = user.date_naissance || '';
            document.getElementById('indicatif').value = user.indicatif || '+225';
            document.getElementById('numero_telephone').value = user.numero_telephone || '';
            document.getElementById('ville').value = user.ville || '';
            document.getElementById('commune').value = user.commune || '';
            document.getElementById('lieu_naissance').value = user.lieu_naissance || '';
            document.getElementById('numero_cni').value = user.numero_cni || '';
            document.getElementById('numero_passeport').value = user.numero_passeport || '';

            // Gérer l'affichage du champ rôle
            if (user.account_type === 'admin') {
                document.getElementById('roleField').style.display = 'block';
                document.getElementById('role').required = true;
            } else {
                document.getElementById('roleField').style.display = 'none';
                document.getElementById('role').required = false;
            }

            // Mot de passe optionnel en modification
            document.getElementById('passwordRequired').style.display = 'none';
            document.getElementById('passwordConfirmRequired').style.display = 'none';
            document.getElementById('password').required = false;
            document.getElementById('password_confirmation').required = false;
            document.getElementById('password').placeholder = 'Laisser vide pour ne pas changer';
            document.getElementById('password_confirmation').placeholder = 'Laisser vide pour ne pas changer';

            new bootstrap.Modal(document.getElementById('userModal')).show();
        }
    });
}

function toggleUserStatus(id) {
    AdminComponents.toggleStatus(id, `${USER_BASE_URL}/${id}/toggle-status`, {
        confirmMessage: 'Changer le statut de cet utilisateur ?'
    });
}

function deleteUser(id) {
    AdminComponents.deleteItem(id, USER_BASE_URL, {
        confirmMessage: 'Supprimer définitivement cet utilisateur ? Cette action est irréversible et supprimera aussi toutes ses commandes et adresses.'
    });
}

function openUserOrders() {
    if (currentUserId) {
        window.location.href = `${USER_BASE_URL}/${currentUserId}/orders`;
    }
}

// Gestion du formulaire avec URL dynamique
document.getElementById('userForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const submitBtn = this.querySelector('[type="submit"]');
    const submitText = submitBtn.querySelector('[data-submit-text]');
    const submitSpinner = submitBtn.querySelector('.spinner-border');

    // Désactiver le bouton
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');

    const formData = new FormData(this);
    const id = document.getElementById('userId').value;
    const method = document.getElementById('methodField').value;

    let url = '{{ route("admin.users.store") }}';
    if (id) {
        url = `${USER_BASE_URL}/${id}`;
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('userModal'));
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
