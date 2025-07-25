@extends('admin.layouts.master')

@section('title', 'Demandes de course')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-12 mb-4 order-0">
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Demandes de course</h5>
                            <p class="mb-4">Gérez toutes les demandes de course des utilisateurs</p>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <div class="row">
                                <div class="col-4">
                                    <div class="d-flex">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-primary">
                                                <i class="bx bx-truck fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center ms-3">
                                            <h6 class="mb-0 fw-semibold">{{ $stats['total'] }}</h6>
                                            <small class="text-muted">Total</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-warning">
                                                <i class="bx bx-time fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center ms-3">
                                            <h6 class="mb-0 fw-semibold">{{ $stats['pending'] }}</h6>
                                            <small class="text-muted">En attente</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-4">
                                    <div class="d-flex">
                                        <div class="avatar avatar-md">
                                            <span class="avatar-initial rounded bg-label-success">
                                                <i class="bx bx-check fs-4"></i>
                                            </span>
                                        </div>
                                        <div class="d-flex flex-column justify-content-center ms-3">
                                            <h6 class="mb-0 fw-semibold">{{ $stats['completed'] }}</h6>
                                            <small class="text-muted">Terminées</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres et actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Liste des demandes</h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.errand-requests.stats') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-bar-chart-2"></i>
                            Statistiques
                        </a>
                        <a href="{{ route('admin.errand-requests.export') }}" class="btn btn-outline-success btn-sm">
                            <i class="bx bx-download"></i>
                            Exporter CSV
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filtres -->
                    <form method="GET" action="{{ route('admin.errand-requests.index') }}" class="row g-3 mb-4">
                        <div class="col-md-3">
                            <label for="search" class="form-label">Recherche</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="{{ request('search') }}" placeholder="Titre, description, utilisateur...">
                        </div>
                        <div class="col-md-2">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tous</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="accepted" {{ request('status') == 'accepted' ? 'selected' : '' }}>Acceptée</option>
                                <option value="in_progress" {{ request('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Terminée</option>
                                <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="urgency" class="form-label">Urgence</label>
                            <select class="form-select" id="urgency" name="urgency">
                                <option value="">Tous</option>
                                <option value="low" {{ request('urgency') == 'low' ? 'selected' : '' }}>Faible</option>
                                <option value="medium" {{ request('urgency') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ request('urgency') == 'high' ? 'selected' : '' }}>Élevée</option>
                                <option value="urgent" {{ request('urgency') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort_by" class="form-label">Trier par</label>
                            <select class="form-select" id="sort_by" name="sort_by">
                                <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Date création</option>
                                <option value="title" {{ request('sort_by') == 'title' ? 'selected' : '' }}>Titre</option>
                                <option value="urgency_level" {{ request('sort_by') == 'urgency_level' ? 'selected' : '' }}>Urgence</option>
                                <option value="status" {{ request('sort_by') == 'status' ? 'selected' : '' }}>Statut</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label for="sort_order" class="form-label">Ordre</label>
                            <select class="form-select" id="sort_order" name="sort_order">
                                <option value="desc" {{ request('sort_order') == 'desc' ? 'selected' : '' }}>Décroissant</option>
                                <option value="asc" {{ request('sort_order') == 'asc' ? 'selected' : '' }}>Croissant</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">&nbsp;</label>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bx bx-search"></i>
                            </button>
                        </div>
                    </form>

                    <!-- Tableau des demandes -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Utilisateur</th>
                                    <th>Titre</th>
                                    <th>Adresses</th>
                                    <th>Urgence</th>
                                    <th>Statut</th>
                                    <th>Coût estimé</th>
                                    <th>Date création</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($errandRequests as $request)
                                <tr>
                                    <td>
                                        <span class="fw-semibold">#{{ $request->id }}</span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar avatar-sm me-2">
                                                <span class="avatar-initial rounded bg-label-primary">
                                                    {{ substr($request->user->prenoms ?? 'U', 0, 1) }}
                                                </span>
                                            </div>
                                            <div>
                                                <div class="fw-semibold">{{ $request->user->nom }} {{ $request->user->prenoms }}</div>
                                                <small class="text-muted">{{ $request->user->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ Str::limit($request->title, 30) }}</div>
                                        <small class="text-muted">{{ Str::limit($request->description, 50) }}</small>
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div><strong>De:</strong> {{ Str::limit($request->pickup_address, 25) }}</div>
                                            <div><strong>À:</strong> {{ Str::limit($request->delivery_address, 25) }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->urgency_level == 'low' ? 'success' : ($request->urgency_level == 'medium' ? 'info' : ($request->urgency_level == 'high' ? 'warning' : 'danger')) }}">
                                            {{ $request->urgency_label }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-label-{{ $request->status == 'pending' ? 'warning' : ($request->status == 'accepted' ? 'info' : ($request->status == 'in_progress' ? 'primary' : ($request->status == 'completed' ? 'success' : 'danger'))) }}">
                                            {{ $request->status_label }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($request->estimated_cost > 0)
                                            <span class="fw-semibold text-success">{{ number_format($request->estimated_cost, 0, ',', ' ') }} F</span>
                                        @else
                                            <span class="text-muted">Non spécifié</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="small">
                                            <div>{{ $request->created_at->format('d/m/Y') }}</div>
                                            <div class="text-muted">{{ $request->created_at->format('H:i') }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bx bx-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="{{ route('admin.errand-requests.show', $request) }}">
                                                        <i class="bx bx-show me-1"></i>
                                                        Voir détails
                                                    </a>
                                                </li>
                                                <li>
                                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#updateStatusModal" 
                                                       data-request-id="{{ $request->id }}" data-current-status="{{ $request->status }}">
                                                        <i class="bx bx-edit me-1"></i>
                                                        Changer statut
                                                    </a>
                                                </li>
                                                <li>
                                                    <hr class="dropdown-divider">
                                                </li>
                                                <li>
                                                    <form action="{{ route('admin.errand-requests.destroy', $request) }}" method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="dropdown-item text-danger" 
                                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                                            <i class="bx bx-trash me-1"></i>
                                                            Supprimer
                                                        </button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-truck fs-1 mb-3"></i>
                                            <p>Aucune demande de course trouvée</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($errandRequests->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $errandRequests->appends(request()->query())->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour changer le statut -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="updateStatusForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="new_status" class="form-label">Nouveau statut</label>
                        <select class="form-select" id="new_status" name="status" required>
                            <option value="pending">En attente</option>
                            <option value="accepted">Acceptée</option>
                            <option value="in_progress">En cours</option>
                            <option value="completed">Terminée</option>
                            <option value="cancelled">Annulée</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion du modal de changement de statut
    const updateStatusModal = document.getElementById('updateStatusModal');
    const updateStatusForm = document.getElementById('updateStatusForm');
    const newStatusSelect = document.getElementById('new_status');

    updateStatusModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const requestId = button.getAttribute('data-request-id');
        const currentStatus = button.getAttribute('data-current-status');
        
        // Mettre à jour l'action du formulaire
        updateStatusForm.action = `/admin/errand-requests/${requestId}/update-status`;
        
        // Sélectionner le statut actuel
        newStatusSelect.value = currentStatus;
    });
});
</script>
@endpush 