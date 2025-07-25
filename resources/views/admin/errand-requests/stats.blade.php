@extends('admin.layouts.master')

@section('title', 'Statistiques des demandes de course')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <!-- Statistiques générales -->
        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-semibold d-block mb-1">Total demandes</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ $totalRequests }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-label-primary rounded p-2">
                            <i class="bx bx-truck bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-semibold d-block mb-1">Utilisateurs actifs</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ $totalUsers }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-user bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-semibold d-block mb-1">En attente</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ $statusStats['pending']->count ?? 0 }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-label-warning rounded p-2">
                            <i class="bx bx-time bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-sm-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex align-items-start justify-content-between">
                        <div class="content-left">
                            <span class="fw-semibold d-block mb-1">Terminées</span>
                            <div class="d-flex align-items-end mt-2">
                                <h4 class="mb-0 me-2">{{ $statusStats['completed']->count ?? 0 }}</h4>
                            </div>
                        </div>
                        <span class="badge bg-label-success rounded p-2">
                            <i class="bx bx-check bx-sm"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Statistiques par statut -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Répartition par statut</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="bx bx-time"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">En attente</h6>
                                        <small class="text-muted">{{ $statusStats['pending']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($statusStats['pending']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="bx bx-check-circle"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Acceptées</h6>
                                        <small class="text-muted">{{ $statusStats['accepted']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($statusStats['accepted']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="bx bx-loader"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">En cours</h6>
                                        <small class="text-muted">{{ $statusStats['in_progress']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($statusStats['in_progress']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="bx bx-check"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Terminées</h6>
                                        <small class="text-muted">{{ $statusStats['completed']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($statusStats['completed']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class="bx bx-x"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Annulées</h6>
                                        <small class="text-muted">{{ $statusStats['cancelled']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($statusStats['cancelled']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistiques par urgence -->
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Répartition par urgence</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-success">
                                        <i class="bx bx-down-arrow"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Faible</h6>
                                        <small class="text-muted">{{ $urgencyStats['low']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($urgencyStats['low']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-info">
                                        <i class="bx bx-minus"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Moyenne</h6>
                                        <small class="text-muted">{{ $urgencyStats['medium']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($urgencyStats['medium']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="bx bx-up-arrow"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Élevée</h6>
                                        <small class="text-muted">{{ $urgencyStats['high']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($urgencyStats['high']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-sm me-3">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class="bx bx-up-arrow-alt"></i>
                                    </span>
                                </div>
                                <div class="d-flex w-100 flex-wrap justify-content-between">
                                    <div class="me-2">
                                        <h6 class="mb-0">Urgente</h6>
                                        <small class="text-muted">{{ $urgencyStats['urgent']->count ?? 0 }} demandes</small>
                                    </div>
                                    <div>
                                        <h6 class="mb-0">{{ $totalRequests > 0 ? round(($urgencyStats['urgent']->count ?? 0) / $totalRequests * 100, 1) : 0 }}%</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Évolution mensuelle -->
        <div class="col-lg-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Évolution des demandes (6 derniers mois)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Mois</th>
                                    <th>Nombre de demandes</th>
                                    <th>Pourcentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($monthlyStats as $stat)
                                <tr>
                                    <td>{{ \Carbon\Carbon::createFromFormat('Y-m', $stat->month)->format('F Y') }}</td>
                                    <td>{{ $stat->count }}</td>
                                    <td>{{ $totalRequests > 0 ? round($stat->count / $totalRequests * 100, 1) : 0 }}%</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Aucune donnée disponible</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top utilisateurs -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top utilisateurs</h5>
                </div>
                <div class="card-body">
                    @forelse($topUsers as $user)
                    <div class="d-flex align-items-center mb-3">
                        <div class="avatar avatar-sm me-3">
                            <span class="avatar-initial rounded bg-label-primary">
                                {{ substr($user->prenoms ?? 'U', 0, 1) }}
                            </span>
                        </div>
                        <div class="d-flex w-100 flex-wrap justify-content-between">
                            <div class="me-2">
                                <h6 class="mb-0">{{ $user->nom }} {{ $user->prenoms }}</h6>
                                <small class="text-muted">{{ $user->email }}</small>
                            </div>
                            <div>
                                <span class="badge bg-label-primary">{{ $user->errand_requests_count }} demandes</span>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center text-muted">
                        <i class="bx bx-user fs-1 mb-2"></i>
                        <p>Aucun utilisateur avec des demandes</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center">
                    <a href="{{ route('admin.errand-requests.index') }}" class="btn btn-primary me-2">
                        <i class="bx bx-list-ul me-1"></i>
                        Voir toutes les demandes
                    </a>
                    <a href="{{ route('admin.errand-requests.export') }}" class="btn btn-outline-success">
                        <i class="bx bx-download me-1"></i>
                        Exporter les données
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 