@extends('admin.layouts.master')

@section('title', 'Logs d\'activité - Demande #' . $errandRequest->id)

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bx bx-history me-2"></i>
                        Logs d'activité - Demande #{{ $errandRequest->id }}
                    </h5>
                    <div class="d-flex gap-2">
                        <a href="{{ route('admin.errand-requests.show', $errandRequest) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="bx bx-arrow-back"></i>
                            Retour aux détails
                        </a>
                        <a href="{{ route('admin.errand-requests.index') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bx bx-list-ul"></i>
                            Liste des demandes
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Informations de la demande -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card bg-light-primary">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bx bx-info-circle me-2"></i>
                                        Informations de la demande
                                    </h6>
                                    <div class="mb-2">
                                        <strong>Titre:</strong> {{ $errandRequest->title }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Client:</strong> {{ $errandRequest->user->nom }} {{ $errandRequest->user->prenoms }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Statut actuel:</strong> 
                                        <span class="badge bg-label-{{ $errandRequest->status == 'pending' ? 'warning' : ($errandRequest->status == 'accepted' ? 'info' : ($errandRequest->status == 'in_progress' ? 'primary' : ($errandRequest->status == 'completed' ? 'success' : 'danger'))) }}">
                                            {{ $errandRequest->status_label }}
                                        </span>
                                    </div>
                                    <div class="mb-2">
                                        <strong>Urgence:</strong> 
                                        <span class="badge bg-label-{{ $errandRequest->urgency_level == 'low' ? 'success' : ($errandRequest->urgency_level == 'medium' ? 'info' : ($errandRequest->urgency_level == 'high' ? 'warning' : 'danger')) }}">
                                            {{ $errandRequest->urgency_label }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card bg-light-info">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bx bx-calendar me-2"></i>
                                        Dates importantes
                                    </h6>
                                    <div class="mb-2">
                                        <strong>Créée le:</strong> {{ $errandRequest->created_at->format('d/m/Y à H:i') }}
                                    </div>
                                    <div class="mb-2">
                                        <strong>Dernière modification:</strong> {{ $errandRequest->updated_at->format('d/m/Y à H:i') }}
                                    </div>
                                    @if($errandRequest->preferred_delivery_time)
                                    <div class="mb-2">
                                        <strong>Heure préférée:</strong> {{ $errandRequest->preferred_delivery_time->format('d/m/Y à H:i') }}
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline des activités -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="bx bx-timeline me-2"></i>
                                Historique des activités ({{ $activities->count() }} entrées)
                            </h6>
                        </div>
                        <div class="card-body">
                            @if($activities->count() > 0)
                            <div class="timeline">
                                @foreach($activities as $activity)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-{{ $activity->event == 'created' ? 'success' : ($activity->event == 'updated' ? 'primary' : 'danger') }}"></div>
                                    <div class="timeline-content">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="timeline-title mb-1">
                                                    @if($activity->event == 'created')
                                                        <i class="bx bx-plus-circle text-success me-1"></i>
                                                        Demande créée
                                                    @elseif($activity->event == 'updated')
                                                        <i class="bx bx-edit text-primary me-1"></i>
                                                        Demande modifiée
                                                    @elseif($activity->event == 'deleted')
                                                        <i class="bx bx-trash text-danger me-1"></i>
                                                        Demande supprimée
                                                    @else
                                                        <i class="bx bx-info-circle text-info me-1"></i>
                                                        Activité enregistrée
                                                    @endif
                                                </h6>
                                                <p class="timeline-text mb-2">{{ $activity->description }}</p>
                                                
                                                @if($activity->properties->count() > 0)
                                                <div class="small">
                                                    <strong>Changements:</strong>
                                                    <ul class="list-unstyled mt-1">
                                                        @foreach($activity->properties as $key => $value)
                                                        <li>
                                                            <span class="text-muted">{{ ucfirst($key) }}:</span>
                                                            @if(is_array($value))
                                                                @if(isset($value['old']) && isset($value['new']))
                                                                    <span class="text-danger">{{ $value['old'] }}</span>
                                                                    <i class="bx bx-right-arrow-alt mx-1"></i>
                                                                    <span class="text-success">{{ $value['new'] }}</span>
                                                                @else
                                                                    <span class="text-info">{{ json_encode($value) }}</span>
                                                                @endif
                                                            @else
                                                                <span class="text-info">{{ $value }}</span>
                                                            @endif
                                                        </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                @endif
                                            </div>
                                            <div class="text-end">
                                                <small class="text-muted">
                                                    {{ $activity->created_at->format('d/m/Y H:i') }}
                                                </small>
                                                @if($activity->causer)
                                                <div class="small text-muted">
                                                    par {{ $activity->causer->nom ?? 'Admin' }}
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="bx bx-history fs-1 text-muted mb-3"></i>
                                <p class="text-muted">Aucune activité enregistrée pour cette demande.</p>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="row mt-4">
                        <div class="col-12 text-center">
                            <a href="{{ route('admin.errand-requests.show', $errandRequest) }}" class="btn btn-primary me-2">
                                <i class="bx bx-show me-1"></i>
                                Voir les détails
                            </a>
                            <a href="{{ route('admin.errand-requests.index') }}" class="btn btn-outline-secondary">
                                <i class="bx bx-list-ul me-1"></i>
                                Retour à la liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
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
    margin-bottom: 30px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 0;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    margin-left: 10px;
    border-left: 3px solid #dee2e6;
}

.timeline-title {
    font-size: 0.875rem;
    font-weight: 600;
    margin-bottom: 8px;
}

.timeline-text {
    font-size: 0.875rem;
    color: #495057;
    margin-bottom: 10px;
}

.timeline-content ul {
    margin-bottom: 0;
}

.timeline-content li {
    font-size: 0.75rem;
    margin-bottom: 2px;
}
</style>
@endpush 