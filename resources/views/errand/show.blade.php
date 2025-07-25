@extends('layouts.app')

@section('title', 'Détails de la demande - Lebayo')

@section('content')
<div class="errand-detail-page">
    <div class="container">
        <!-- En-tête avec navigation -->
        <div class="page-header">
            <div class="back-section">
                <a href="{{ route('errand.index') }}" class="back-btn">
                    <span class="back-icon">←</span>
                    Retour à mes demandes
                </a>
            </div>
            
            <div class="header-content">
                <div class="errand-number">
                    <span class="number-label">Demande #{{ $errandRequest->id }}</span>
                </div>
                <h1 class="page-title">{{ $errandRequest->title }}</h1>
                <div class="status-section">
                    <span class="status-badge status-{{ $errandRequest->status }}">
                        {{ $errandRequest->status_label }}
                    </span>
                    <span class="urgency-badge urgency-{{ $errandRequest->urgency_level }}">
                        {{ $errandRequest->urgency_label }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Contenu principal -->
        <div class="errand-detail-content">
            <div class="detail-grid">
                <!-- Informations principales -->
                <div class="detail-card main-info">
                    <div class="card-header">
                        <h3><i class="fas fa-info-circle"></i> Informations générales</h3>
                    </div>
                    <div class="card-content">
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="info-label">Titre</span>
                                <span class="info-value">{{ $errandRequest->title }}</span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Statut</span>
                                <span class="info-value status-{{ $errandRequest->status }}">
                                    {{ $errandRequest->status_label }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Niveau d'urgence</span>
                                <span class="info-value urgency-{{ $errandRequest->urgency_level }}">
                                    {{ $errandRequest->urgency_label }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Date de création</span>
                                <span class="info-value">{{ $errandRequest->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                            @if($errandRequest->estimated_cost > 0)
                            <div class="info-item">
                                <span class="info-label">Coût estimé</span>
                                <span class="info-value cost-value">{{ $errandRequest->formatted_estimated_cost }}</span>
                            </div>
                            @endif
                            @if($errandRequest->contact_phone)
                            <div class="info-item">
                                <span class="info-label">Téléphone</span>
                                <span class="info-value">{{ $errandRequest->contact_phone }}</span>
                            </div>
                            @endif
                            @if($errandRequest->preferred_delivery_time)
                            <div class="info-item">
                                <span class="info-label">Heure préférée</span>
                                <span class="info-value">{{ $errandRequest->preferred_delivery_time->format('d/m/Y à H:i') }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div class="detail-card description-card">
                    <div class="card-header">
                        <h3><i class="fas fa-file-alt"></i> Description</h3>
                    </div>
                    <div class="card-content">
                        <p class="description-text">{{ $errandRequest->description }}</p>
                    </div>
                </div>

                <!-- Adresses -->
                <div class="detail-card addresses-card">
                    <div class="card-header">
                        <h3><i class="fas fa-map-marker-alt"></i> Adresses</h3>
                    </div>
                    <div class="card-content">
                        <div class="address-section">
                            <h4>Adresse de départ</h4>
                            <p class="address-text">{{ $errandRequest->pickup_address }}</p>
                        </div>
                        <div class="address-section">
                            <h4>Adresse de livraison</h4>
                            <p class="address-text">{{ $errandRequest->delivery_address }}</p>
                        </div>
                    </div>
                </div>

                <!-- Notes supplémentaires -->
                @if($errandRequest->notes)
                <div class="detail-card notes-card">
                    <div class="card-header">
                        <h3><i class="fas fa-sticky-note"></i> Notes supplémentaires</h3>
                    </div>
                    <div class="card-content">
                        <p class="notes-text">{{ $errandRequest->notes }}</p>
                    </div>
                </div>
                @endif

                <!-- Photo si fournie -->
                @if($errandRequest->photo_path)
                <div class="detail-card photo-card">
                    <div class="card-header">
                        <h3><i class="fas fa-camera"></i> Photo jointe</h3>
                    </div>
                    <div class="card-content">
                        <div class="photo-container">
                            <img src="{{ $errandRequest->photo_url }}" alt="Photo de la demande" class="errand-photo">
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Actions -->
            <div class="actions-section">
                <div class="actions-grid">
                    <a href="{{ route('errand.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i>
                        Retour à la liste
                    </a>
                    
                    @if(in_array($errandRequest->status, ['pending', 'accepted']))
                    <form action="{{ route('errand.cancel', $errandRequest) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger" 
                                onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                            <i class="fas fa-times"></i>
                            Annuler la demande
                        </button>
                    </form>
                    @endif
                    
                    <a href="{{ route('errand.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Nouvelle demande
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.errand-detail-page {
    padding: 2rem 0;
    min-height: 70vh;
}

/* En-tête de page */
.page-header {
    margin-bottom: 2rem;
}

.back-section {
    margin-bottom: 1rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-light);
    text-decoration: none;
    font-weight: 500;
    transition: color 0.3s ease;
}

.back-btn:hover {
    color: var(--primary-color);
}

.back-icon {
    font-size: 1.25rem;
}

.header-content {
    text-align: center;
}

.errand-number {
    margin-bottom: 0.5rem;
}

.number-label {
    font-size: 0.875rem;
    color: var(--text-light);
    font-weight: 500;
}

.page-title {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.status-section {
    display: flex;
    justify-content: center;
    gap: 1rem;
    flex-wrap: wrap;
}

.status-badge, .urgency-badge {
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pending {
    background: #fef3c7;
    color: #d97706;
}

.status-accepted {
    background: #dbeafe;
    color: #2563eb;
}

.status-in_progress {
    background: #e0e7ff;
    color: #7c3aed;
}

.status-completed {
    background: #d1fae5;
    color: #059669;
}

.status-cancelled {
    background: #fee2e2;
    color: #dc2626;
}

.urgency-low {
    background: #d1fae5;
    color: #059669;
}

.urgency-medium {
    background: #dbeafe;
    color: #2563eb;
}

.urgency-high {
    background: #fef3c7;
    color: #d97706;
}

.urgency-urgent {
    background: #fee2e2;
    color: #dc2626;
}

/* Grille de détails */
.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    overflow: hidden;
}

.card-header {
    background: #f8f9fa;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.card-header i {
    color: var(--primary-color);
}

.card-content {
    padding: 1.5rem;
}

/* Grille d'informations */
.info-grid {
    display: grid;
    gap: 1rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid #f1f5f9;
}

.info-item:last-child {
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: var(--text-dark);
    font-size: 0.875rem;
}

.info-value {
    color: var(--text-dark);
    font-size: 0.875rem;
}

.cost-value {
    color: var(--primary-color);
    font-weight: 600;
}

/* Description */
.description-text {
    color: var(--text-dark);
    line-height: 1.6;
    margin: 0;
    font-size: 0.875rem;
}

/* Adresses */
.address-section {
    margin-bottom: 1.5rem;
}

.address-section:last-child {
    margin-bottom: 0;
}

.address-section h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.address-text {
    color: var(--text-dark);
    margin: 0;
    font-size: 0.875rem;
    line-height: 1.5;
    padding: 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    border-left: 3px solid var(--primary-color);
}

/* Notes */
.notes-text {
    color: var(--text-dark);
    line-height: 1.6;
    margin: 0;
    font-size: 0.875rem;
    font-style: italic;
}

/* Photo */
.photo-container {
    text-align: center;
}

.errand-photo {
    max-width: 100%;
    max-height: 300px;
    border-radius: 8px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Actions */
.actions-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
}

.actions-grid {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    color: white;
}

.btn-secondary {
    background: #f8f9fa;
    color: var(--text-dark);
    border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
    background: #e9ecef;
    color: var(--text-dark);
}

.btn-danger {
    background: var(--danger-color);
    color: white;
}

.btn-danger:hover {
    background: #dc2626;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(220, 38, 38, 0.3);
}

/* Responsive */
@media (max-width: 768px) {
    .page-title {
        font-size: 1.5rem;
    }
    
    .status-section {
        flex-direction: column;
        align-items: center;
    }
    
    .detail-grid {
        grid-template-columns: 1fr;
    }
    
    .actions-grid {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
}
</style>
@endpush 