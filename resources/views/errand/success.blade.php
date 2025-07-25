@extends('layouts.app')

@section('title', 'Demande envoyée - Lebayo')

@section('content')
<div class="success-page">
    <div class="container">
        <div class="success-content">
            <!-- Animation et message de succès -->
            <div class="success-animation">
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <div class="success-message">
                    <h1>Demande envoyée !</h1>
                    <p>Votre demande de course a été transmise avec succès</p>
                </div>
            </div>

            <!-- Détails de la demande -->
            <div class="errand-details">
                <div class="errand-header">
                    <h2>Détails de votre demande</h2>
                    <div class="errand-number">
                        <span>Numéro de demande</span>
                        <strong>#{{ $errandRequest->id }}</strong>
                    </div>
                </div>

                <div class="errand-info-grid">
                    <!-- Informations générales -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-info-circle"></i>
                            <h3>Informations générales</h3>
                        </div>
                        <div class="info-content">
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
                                <span class="info-label">Urgence</span>
                                <span class="info-value urgency-{{ $errandRequest->urgency_level }}">
                                    {{ $errandRequest->urgency_label }}
                                </span>
                            </div>
                            <div class="info-item">
                                <span class="info-label">Date de création</span>
                                <span class="info-value">{{ $errandRequest->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Adresses -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-map-marker-alt"></i>
                            <h3>Adresses</h3>
                        </div>
                        <div class="info-content">
                            <div class="address-section">
                                <h4>Adresse de départ</h4>
                                <p>{{ $errandRequest->pickup_address }}</p>
                            </div>
                            <div class="address-section">
                                <h4>Adresse de livraison</h4>
                                <p>{{ $errandRequest->delivery_address }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-file-alt"></i>
                            <h3>Description</h3>
                        </div>
                        <div class="info-content">
                            <p class="description-text">{{ $errandRequest->description }}</p>
                        </div>
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="info-card">
                        <div class="info-header">
                            <i class="fas fa-plus-circle"></i>
                            <h3>Informations supplémentaires</h3>
                        </div>
                        <div class="info-content">
                            @if($errandRequest->estimated_cost > 0)
                                <div class="info-item">
                                    <span class="info-label">Coût estimé</span>
                                    <span class="info-value">{{ $errandRequest->formatted_estimated_cost }}</span>
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
                            
                            @if($errandRequest->notes)
                                <div class="info-item">
                                    <span class="info-label">Notes</span>
                                    <span class="info-value">{{ $errandRequest->notes }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Photo si fournie -->
                @if($errandRequest->photo_path)
                <div class="photo-section">
                    <h3>Photo jointe</h3>
                    <div class="photo-container">
                        <img src="{{ $errandRequest->photo_url }}" alt="Photo de la demande" class="errand-photo">
                    </div>
                </div>
                @endif
            </div>

            <!-- Prochaines étapes -->
            <div class="next-steps">
                <h3>Prochaines étapes</h3>
                <div class="steps-list">
                    <div class="step-item">
                        <div class="step-icon">
                            <i class="fas fa-search"></i>
                        </div>
                        <div class="step-content">
                            <h4>Recherche de livreur</h4>
                            <p>Nous recherchons un livreur disponible dans votre zone</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="step-content">
                            <h4>Contact</h4>
                            <p>Un livreur vous contactera pour confirmer les détails</p>
                        </div>
                    </div>
                    <div class="step-item">
                        <div class="step-icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <div class="step-content">
                            <h4>Exécution</h4>
                            <p>Le livreur exécute votre demande et vous livre</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="success-actions">
                <a href="{{ route('home') }}" class="btn btn-primary">
                    <i class="fas fa-home"></i>
                    <span>Retour à l'accueil</span>
                </a>
                <a href="{{ route('errand.index') }}" class="btn btn-secondary">
                    <i class="fas fa-list"></i>
                    <span>Mes demandes</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.success-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem 0;
}

.success-content {
    max-width: 1000px;
    margin: 0 auto;
}

/* Animation et message de succès */
.success-animation {
    text-align: center;
    margin-bottom: 3rem;
}

.success-icon {
    width: 120px;
    height: 120px;
    background: linear-gradient(135deg, var(--success-color), #20c997);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 3rem;
    color: white;
    box-shadow: 0 10px 30px rgba(40, 167, 69, 0.3);
    animation: successPulse 2s ease-in-out infinite;
}

@keyframes successPulse {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.05); }
}

.success-message h1 {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.success-message p {
    font-size: 1.25rem;
    color: var(--text-light);
    margin: 0;
}

/* Détails de la demande */
.errand-details {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    margin-bottom: 3rem;
}

.errand-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.errand-header h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.errand-number {
    text-align: right;
}

.errand-number span {
    display: block;
    font-size: 0.875rem;
    color: var(--text-light);
    margin-bottom: 0.25rem;
}

.errand-number strong {
    font-size: 1.25rem;
    color: var(--primary-color);
}

/* Grille d'informations */
.errand-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.info-card {
    background: #f8f9fa;
    border-radius: 12px;
    padding: 1.5rem;
    border: 1px solid #e5e7eb;
}

.info-header {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.info-header i {
    color: var(--primary-color);
    font-size: 1.25rem;
}

.info-header h3 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin: 0;
}

.info-content {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.info-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.info-label {
    color: var(--text-light);
    font-size: 0.875rem;
}

.info-value {
    font-weight: 600;
    color: var(--text-dark);
}

.status-pending {
    color: #f59e0b;
}

.status-accepted {
    color: var(--primary-color);
}

.status-in_progress {
    color: var(--primary-color);
}

.status-completed {
    color: var(--success-color);
}

.status-cancelled {
    color: var(--danger-color);
}

.urgency-low {
    color: var(--success-color);
}

.urgency-medium {
    color: var(--primary-color);
}

.urgency-high {
    color: #f59e0b;
}

.urgency-urgent {
    color: var(--danger-color);
}

/* Adresses */
.address-section {
    margin-bottom: 1rem;
}

.address-section h4 {
    font-size: 0.875rem;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.25rem;
}

.address-section p {
    color: var(--text-dark);
    margin: 0;
    font-size: 0.875rem;
}

/* Description */
.description-text {
    color: var(--text-dark);
    line-height: 1.6;
    margin: 0;
}

/* Photo */
.photo-section {
    margin-top: 2rem;
    text-align: center;
}

.photo-section h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1rem;
}

.photo-container {
    display: inline-block;
}

.errand-photo {
    max-width: 300px;
    max-height: 300px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

/* Prochaines étapes */
.next-steps {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 2rem;
    text-align: center;
    margin-bottom: 3rem;
}

.next-steps h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 2rem;
}

.steps-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 2rem;
}

.step-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
}

.step-item .step-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: white;
    margin-bottom: 1rem;
    box-shadow: 0 4px 15px rgba(255, 107, 53, 0.3);
}

.step-item .step-content h4 {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.step-item .step-content p {
    color: var(--text-light);
    font-size: 0.875rem;
    margin: 0;
}

/* Actions */
.success-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
}

.btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.875rem 1.5rem;
    border-radius: 12px;
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
    background: white;
    color: var(--text-dark);
    border: 1px solid #e5e7eb;
}

.btn-secondary:hover {
    background: #f8f9fa;
    color: var(--text-dark);
}

/* Responsive */
@media (max-width: 768px) {
    .success-icon {
        width: 80px;
        height: 80px;
        font-size: 2rem;
    }
    
    .success-message h1 {
        font-size: 1.75rem;
    }
    
    .errand-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .errand-number {
        text-align: center;
    }
    
    .errand-info-grid {
        grid-template-columns: 1fr;
    }
    
    .success-actions {
        flex-direction: column;
        align-items: center;
    }
    
    .btn {
        width: 100%;
        max-width: 300px;
        justify-content: center;
    }
    
    .steps-list {
        grid-template-columns: 1fr;
    }
}
</style>
@endpush 