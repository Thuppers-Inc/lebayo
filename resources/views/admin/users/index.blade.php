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

    <!-- Barre de recherche et filtres -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Recherche et Filtres</h6>
        </div>
        <div class="card-body">
            <form id="searchForm" method="GET" action="{{ route('admin.users.index') }}">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}"
                               placeholder="Nom, prénom, email...">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="account_type" class="form-label">Type de compte</label>
                        <select class="form-select" id="account_type" name="account_type">
                            <option value="">Tous</option>
                            <option value="admin" {{ request('account_type') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="client" {{ request('account_type') == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="agent" {{ request('account_type') == 'agent' ? 'selected' : '' }}>Agent</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actif</option>
                            <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort_by" class="form-label">Trier par</label>
                        <select class="form-select" id="sort_by" name="sort_by">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date d'inscription</option>
                            <option value="nom" {{ request('sort_by') == 'nom' ? 'selected' : '' }}>Nom</option>
                            <option value="email" {{ request('sort_by') == 'email' ? 'selected' : '' }}>Email</option>
                            <option value="orders_count" {{ request('sort_by') == 'orders_count' ? 'selected' : '' }}>Nombre de commandes</option>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="sort_order" class="form-label">Ordre</label>
                        <select class="form-select" id="sort_order" name="sort_order">
                            <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                            <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-search"></i> Rechercher
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                            <i class="bx bx-x"></i> Effacer
                        </a>
                        @if(request()->hasAny(['search', 'account_type', 'status', 'sort_by', 'sort_order']))
                        <span class="badge bg-info ms-2">
                            {{ $users->total() }} résultat(s) trouvé(s)
                        </span>
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Datatable principal -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">Liste Complète des Utilisateurs</h6>
            <div class="btn-group" role="group">
                <a href="{{ route('admin.users.export', request()->query()) }}" class="btn btn-success btn-sm" id="exportBtn">
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
                            <label for="modal_account_type" class="form-label">Type de Compte <span class="text-danger">*</span></label>
                            <select class="form-select admin-form-control" id="modal_account_type" name="account_type" required>
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

@push('styles')
<style>
    /* Styles pour la recherche */
    .search-highlight {
        background-color: #fff3cd;
        padding: 2px 4px;
        border-radius: 3px;
    }

    mark {
        background-color: #ffeb3b;
        padding: 1px 3px;
        border-radius: 2px;
    }

    /* Animation pour les filtres */
    .form-control:focus, .form-select:focus {
        border-color: #007bff;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    /* Badge pour les résultats */
    .badge.bg-info {
        font-size: 0.875em;
        padding: 0.5em 0.75em;
    }

    /* Responsive pour les filtres */
    @media (max-width: 768px) {
        .col-md-2, .col-md-4 {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
// Configuration des URLs
const USER_BASE_URL = '{{ route("admin.users.index") }}';
let currentUserId = null;

// Initialisation du datatable
$(document).ready(function() {
    // Désactiver DataTables pour utiliser la pagination Laravel
    // $('#usersTable').DataTable({
    //     "language": {
    //         "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/French.json"
    //     },
    //     "pageLength": 25,
    //     "order": [[7, "desc"]], // Trier par date d'inscription
    //     "columnDefs": [
    //         { "orderable": false, "targets": [8] } // Désactiver le tri sur la colonne actions
    //     ]
    // });

    // Gestion de l'affichage du champ rôle dans le modal
    $('#modal_account_type').on('change', function() {
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

    // Recherche en temps réel
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        const searchValue = $(this).val();

        searchTimeout = setTimeout(function() {
            if (searchValue.length >= 2 || searchValue.length === 0) {
                showLoading();
                $('#searchForm').submit();
            }
        }, 500); // Attendre 500ms après la dernière frappe
    });

    // Soumission automatique des filtres
    $('#account_type, #status, #sort_by, #sort_order').on('change', function() {
        showLoading();
        $('#searchForm').submit();
    });

    // Mettre à jour le lien d'export quand les filtres changent
    function updateExportLink() {
        const formData = new FormData(document.getElementById('searchForm'));
        const params = new URLSearchParams();

        for (let [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }

        const exportUrl = '{{ route("admin.users.export") }}' + (params.toString() ? '?' + params.toString() : '');
        $('#exportBtn').attr('href', exportUrl);
    }

    // Mettre à jour le lien d'export au chargement et lors des changements
    updateExportLink();
    $('#search, #account_type, #status, #sort_by, #sort_order').on('input change', updateExportLink);

    // Fonction pour afficher le chargement
    function showLoading() {
        const loadingHtml = `
            <div class="d-flex justify-content-center align-items-center" style="min-height: 200px;">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Chargement...</span>
                </div>
                <span class="ms-2">Recherche en cours...</span>
            </div>
        `;
        $('.table-responsive').html(loadingHtml);
    }

    // Mise en évidence des termes de recherche
    const searchTerm = '{{ request("search") }}';
    if (searchTerm) {
        $('td').each(function() {
            const text = $(this).text();
            if (text.toLowerCase().includes(searchTerm.toLowerCase())) {
                $(this).html(text.replace(
                    new RegExp(searchTerm, 'gi'),
                    '<mark>$&</mark>'
                ));
            }
        });
    }
});

// Fonctions d'action
function openCreateUserModal() {
    console.log('Ouverture du modal de création');

    // Réinitialiser le formulaire
    document.getElementById('userForm').reset();
    document.getElementById('modalTitle').textContent = 'Nouvel Utilisateur';
    document.querySelector('[data-submit-text]').textContent = 'Créer';
    document.getElementById('userId').value = '';
    document.getElementById('methodField').value = 'POST';

    // Réinitialiser les champs de mot de passe comme requis
    document.getElementById('passwordRequired').style.display = 'inline';
    document.getElementById('passwordConfirmRequired').style.display = 'inline';
    document.getElementById('password').required = true;
    document.getElementById('password_confirmation').required = true;
    document.getElementById('password').placeholder = '••••••••';
    document.getElementById('password_confirmation').placeholder = '••••••••';

    // Cacher le champ rôle par défaut
    document.getElementById('roleField').style.display = 'none';
    document.getElementById('role').required = false;
    document.getElementById('role').value = '';

    // Afficher le modal
    const modal = new bootstrap.Modal(document.getElementById('userModal'));
    modal.show();
    console.log('Modal de création affiché');
}

function viewUser(id) {
    currentUserId = id;
    console.log('Chargement des détails pour l\'utilisateur:', id);

    fetch(`${USER_BASE_URL}/${id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => {
            console.log('Réponse reçue:', response);
            return response.json();
        })
        .then(data => {
            console.log('Données reçues:', data);
            if (data.success) {
                const user = data.user;
                document.getElementById('userViewContent').innerHTML = `
                    <div class="row">
                        <div class="col-md-4 text-center">
                            <img src="${user.photo_url || '/images/default-avatar.png'}" alt="${user.full_name}" class="admin-logo-lg rounded-circle mb-3">
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
                                <tr><td><strong>Téléphone:</strong></td><td>${user.formatted_phone || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Type de compte:</strong></td><td><span class="badge bg-${user.account_type_class}">${user.account_type_label}</span></td></tr>
                                <tr><td><strong>Date de naissance:</strong></td><td>${user.date_naissance || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Lieu de naissance:</strong></td><td>${user.lieu_naissance || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Ville:</strong></td><td>${user.ville || 'Non renseignée'}</td></tr>
                                <tr><td><strong>Commune:</strong></td><td>${user.commune || 'Non renseignée'}</td></tr>
                                <tr><td><strong>CNI:</strong></td><td>${user.numero_cni || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Passeport:</strong></td><td>${user.numero_passeport || 'Non renseigné'}</td></tr>
                                <tr><td><strong>Adresses:</strong></td><td><span class="badge bg-success">${user.addresses_count || 0}</span></td></tr>
                                <tr><td><strong>Dernière commande:</strong></td><td>${user.formatted_last_order_date || 'Aucune'}</td></tr>
                                <tr><td><strong>Inscription:</strong></td><td>${new Date(user.created_at).toLocaleDateString('fr-FR')}</td></tr>
                            </table>
                        </div>
                    </div>
                `;

                // Afficher le modal
                const modal = new bootstrap.Modal(document.getElementById('userViewModal'));
                modal.show();
                console.log('Modal affiché');
            } else {
                console.error('Erreur dans la réponse:', data);
                alert('Erreur lors du chargement des détails');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des détails: ' + error.message);
        });
}

function editUser(id) {
    console.log('Chargement des données pour modification:', id);

    fetch(`${USER_BASE_URL}/${id}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Données d\'édition reçues:', data);
            if (data.success) {
                const user = data.user;
                document.getElementById('modalTitle').textContent = 'Modifier l\'Utilisateur';
                document.querySelector('[data-submit-text]').textContent = 'Modifier';
                document.getElementById('userId').value = user.id;
                document.getElementById('methodField').value = 'PUT';

                // Remplir les champs
                document.getElementById('nom').value = user.nom || '';
                document.getElementById('prenoms').value = user.prenoms || '';
                document.getElementById('email').value = user.email || '';
                document.getElementById('modal_account_type').value = user.account_type || '';
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

                // Afficher le modal
                const modal = new bootstrap.Modal(document.getElementById('userModal'));
                modal.show();
                console.log('Modal d\'édition affiché');
            } else {
                console.error('Erreur dans la réponse:', data);
                alert('Erreur lors du chargement des données');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors du chargement des données: ' + error.message);
        });
}

function toggleUserStatus(id) {
    console.log('Changement de statut pour l\'utilisateur:', id);

    if (!confirm('Changer le statut de cet utilisateur ?')) {
        return;
    }

    fetch(`${USER_BASE_URL}/${id}/toggle-status`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Réponse toggle status:', data);
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Erreur lors de la modification du statut');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la modification du statut: ' + error.message);
        });
}

function deleteUser(id) {
    console.log('Suppression de l\'utilisateur:', id);

    if (!confirm('Supprimer définitivement cet utilisateur ? Cette action est irréversible et supprimera aussi toutes ses commandes et adresses.')) {
        return;
    }

    fetch(`${USER_BASE_URL}/${id}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        }
    })
        .then(response => response.json())
        .then(data => {
            console.log('Réponse suppression:', data);
            if (data.success) {
                alert(data.message);
                window.location.reload();
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression: ' + error.message);
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
