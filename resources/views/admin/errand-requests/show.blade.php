@extends('admin.layouts.master')

@section('title', 'Détails de la demande')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        Demande #{{ $errandRequest->id }} - {{ $errandRequest->title }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.errand-requests.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i>
                            Retour à la liste
                        </a>
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                            <i class="bx bx-edit"></i>
                            Changer statut
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Informations principales -->
                        <div class="col-lg-8">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card bg-light-primary">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bx bx-user me-2"></i>
                                                Informations client
                                            </h6>
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar avatar-md me-3">
                                                    <span class="avatar-initial rounded bg-label-primary">
                                                        {{ substr($errandRequest->user->prenoms ?? 'U', 0, 1) }}
                                                    </span>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">{{ $errandRequest->user->nom }} {{ $errandRequest->user->prenoms }}</h6>
                                                    <small class="text-muted">{{ $errandRequest->user->email }}</small>
                                                </div>
                                            </div>
                                            @if($errandRequest->contact_phone)
                                            <div class="mb-2">
                                                <strong>Téléphone:</strong> {{ $errandRequest->contact_phone }}
                                            </div>
                                            @endif
                                            <div class="mb-2">
                                                <strong>Date de création:</strong> {{ $errandRequest->created_at->format('d/m/Y à H:i') }}
                                            </div>
                                            @if($errandRequest->preferred_delivery_time)
                                            <div class="mb-2">
                                                <strong>Heure préférée:</strong> {{ $errandRequest->preferred_delivery_time->format('d/m/Y à H:i') }}
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="card bg-light-info">
                                        <div class="card-body">
                                            <h6 class="card-title">
                                                <i class="bx bx-info-circle me-2"></i>
                                                Détails de la demande
                                            </h6>
                                            <div class="mb-3">
                                                <span class="badge bg-label-{{ $errandRequest->status == 'pending' ? 'warning' : ($errandRequest->status == 'accepted' ? 'info' : ($errandRequest->status == 'in_progress' ? 'primary' : ($errandRequest->status == 'completed' ? 'success' : 'danger'))) }} me-2">
                                                    {{ $errandRequest->status_label }}
                                                </span>
                                                <span class="badge bg-label-{{ $errandRequest->urgency_level == 'low' ? 'success' : ($errandRequest->urgency_level == 'medium' ? 'info' : ($errandRequest->urgency_level == 'high' ? 'warning' : 'danger')) }}">
                                                    {{ $errandRequest->urgency_label }}
                                                </span>
                                            </div>
                                            @if($errandRequest->estimated_cost > 0)
                                            <div class="mb-2">
                                                <strong>Coût estimé:</strong> 
                                                <span class="text-success fw-semibold">{{ number_format($errandRequest->estimated_cost, 0, ',', ' ') }} F</span>
                                            </div>
                                            @endif
                                            <div class="mb-2">
                                                <strong>Titre:</strong> {{ $errandRequest->title }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-file me-2"></i>
                                        Description
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $errandRequest->description }}</p>
                                </div>
                            </div>

                            <!-- Adresses -->
                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bx bx-map-pin me-2"></i>
                                                Adresse de départ
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ $errandRequest->pickup_address }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h6 class="card-title mb-0">
                                                <i class="bx bx-map me-2"></i>
                                                Adresse de livraison
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <p class="mb-0">{{ $errandRequest->delivery_address }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Notes supplémentaires -->
                            @if($errandRequest->notes)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-note me-2"></i>
                                        Notes supplémentaires
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">{{ $errandRequest->notes }}</p>
                                </div>
                            </div>
                            @endif

                            <!-- Photo si fournie -->
                            @if($errandRequest->photo_path)
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-camera me-2"></i>
                                        Photo jointe
                                    </h6>
                                </div>
                                <div class="card-body text-center">
                                    <img src="{{ $errandRequest->photo_url }}" alt="Photo de la demande" 
                                         class="img-fluid rounded" style="max-height: 300px;">
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Actions et statut -->
                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-cog me-2"></i>
                                        Actions
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#updateStatusModal">
                                            <i class="bx bx-edit me-1"></i>
                                            Changer le statut
                                        </button>
                                        
                                        <a href="{{ route('admin.errand-requests.logs', $errandRequest) }}" class="btn btn-outline-info">
                                            <i class="bx bx-history me-1"></i>
                                            Voir les logs
                                        </a>
                                        
                                        @if($errandRequest->user->phone)
                                        <a href="tel:{{ $errandRequest->user->phone }}" class="btn btn-outline-primary">
                                            <i class="bx bx-phone me-1"></i>
                                            Appeler le client
                                        </a>
                                        @endif
                                        
                                        <a href="mailto:{{ $errandRequest->user->email }}" class="btn btn-outline-info">
                                            <i class="bx bx-envelope me-1"></i>
                                            Envoyer un email
                                        </a>
                                        
                                        <hr>
                                        
                                        <form action="{{ route('admin.errand-requests.destroy', $errandRequest) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger w-100" 
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette demande ?')">
                                                <i class="bx bx-trash me-1"></i>
                                                Supprimer la demande
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Historique des statuts -->
                            <div class="card mt-3">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="bx bx-history me-2"></i>
                                        Historique
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="timeline">
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-primary"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Demande créée</h6>
                                                <p class="timeline-text">{{ $errandRequest->created_at->format('d/m/Y à H:i') }}</p>
                                            </div>
                                        </div>
                                        @if($errandRequest->status != 'pending')
                                        <div class="timeline-item">
                                            <div class="timeline-marker bg-{{ $errandRequest->status == 'completed' ? 'success' : 'info' }}"></div>
                                            <div class="timeline-content">
                                                <h6 class="timeline-title">Statut: {{ $errandRequest->status_label }}</h6>
                                                <p class="timeline-text">Mis à jour le {{ $errandRequest->updated_at->format('d/m/Y à H:i') }}</p>
                                            </div>
                                        </div>
                                        @endif
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

<!-- Modal pour changer le statut -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Changer le statut de la demande #{{ $errandRequest->id }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('admin.errand-requests.update-status', $errandRequest) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="status" class="form-label">Nouveau statut</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="pending" {{ $errandRequest->status == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="accepted" {{ $errandRequest->status == 'accepted' ? 'selected' : '' }}>Acceptée</option>
                            <option value="in_progress" {{ $errandRequest->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                            <option value="completed" {{ $errandRequest->status == 'completed' ? 'selected' : '' }}>Terminée</option>
                            <option value="cancelled" {{ $errandRequest->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (optionnel)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" 
                                  placeholder="Ajouter des notes sur ce changement de statut..."></textarea>
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

@push('styles')
<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e5e7eb;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
}

.timeline-content {
    background: #f8f9fa;
    padding: 10px;
    border-radius: 6px;
    margin-left: 10px;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 5px;
}

.timeline-text {
    font-size: 0.75rem;
    color: #6c757d;
    margin-bottom: 0;
}
</style>
@endpush 