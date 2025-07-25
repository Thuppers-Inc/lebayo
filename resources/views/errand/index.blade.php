@extends('layouts.app')

@section('title', 'Mes demandes de course - Lebayo')

@section('content')
<div class="errand-list-page">
    <div class="container">
        <!-- En-tête -->
        <div class="page-header">
            <div class="header-content">
                <h1 class="page-title">Mes demandes de course</h1>
                <p class="page-subtitle">Suivez l'état de vos demandes de course</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('errand.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i>
                    Nouvelle demande
                </a>
            </div>
        </div>

        <!-- Filtres et recherche -->
        <div class="filters-section">
            <div class="filters-row">
                <div class="filter-group">
                    <label for="status-filter" class="filter-label">Statut</label>
                    <select id="status-filter" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending">En attente</option>
                        <option value="accepted">Acceptée</option>
                        <option value="in_progress">En cours</option>
                        <option value="completed">Terminée</option>
                        <option value="cancelled">Annulée</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label for="urgency-filter" class="filter-label">Urgence</label>
                    <select id="urgency-filter" class="form-select">
                        <option value="">Tous les niveaux</option>
                        <option value="low">Faible</option>
                        <option value="medium">Moyenne</option>
                        <option value="high">Élevée</option>
                        <option value="urgent">Urgente</option>
                    </select>
                </div>
                <div class="search-group">
                    <input type="text" id="search-input" class="form-control" placeholder="Rechercher par titre...">
                </div>
            </div>
        </div>

        <!-- Liste des demandes -->
        <div class="errand-list">
            @if($errandRequests->count() > 0)
                <div class="errand-grid">
                    @foreach($errandRequests as $errand)
                    <div class="errand-card" data-status="{{ $errand->status }}" data-urgency="{{ $errand->urgency_level }}">
                        <!-- En-tête de la carte -->
                        <div class="card-header">
                            <div class="errand-number">
                                <span class="number-label">Demande #{{ $errand->id }}</span>
                            </div>
                            <div class="errand-status">
                                <span class="status-badge status-{{ $errand->status }}">
                                    {{ $errand->status_label }}
                                </span>
                            </div>
                        </div>

                        <!-- Contenu principal -->
                        <div class="card-content">
                            <h3 class="errand-title">{{ $errand->title }}</h3>
                            <p class="errand-description">{{ Str::limit($errand->description, 100) }}</p>
                            
                            <!-- Informations clés -->
                            <div class="errand-info">
                                <div class="info-item">
                                    <i class="fas fa-map-marker-alt"></i>
                                    <span>{{ Str::limit($errand->pickup_address, 30) }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-arrow-right"></i>
                                    <span>{{ Str::limit($errand->delivery_address, 30) }}</span>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span class="urgency-{{ $errand->urgency_level }}">
                                        {{ $errand->urgency_label }}
                                    </span>
                                </div>
                            </div>

                            <!-- Coût estimé si disponible -->
                            @if($errand->estimated_cost > 0)
                            <div class="cost-info">
                                <span class="cost-label">Coût estimé :</span>
                                <span class="cost-value">{{ $errand->formatted_estimated_cost }}</span>
                            </div>
                            @endif

                            <!-- Date de création -->
                            <div class="date-info">
                                <i class="fas fa-calendar"></i>
                                <span>{{ $errand->created_at->format('d/m/Y à H:i') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="card-actions">
                            <a href="{{ route('errand.show', $errand) }}" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i>
                                Voir détails
                            </a>
                            
                            @if(in_array($errand->status, ['pending', 'accepted']))
                            <form action="{{ route('errand.cancel', $errand) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                        onclick="return confirm('Êtes-vous sûr de vouloir annuler cette demande ?')">
                                    <i class="fas fa-times"></i>
                                    Annuler
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                @if($errandRequests->hasPages())
                <div class="pagination-container">
                    {{ $errandRequests->links() }}
                </div>
                @endif

            @else
                <!-- État vide -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <h3>Aucune demande de course</h3>
                    <p>Vous n'avez pas encore créé de demande de course.</p>
                    <a href="{{ route('errand.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i>
                        Créer ma première demande
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.errand-list-page {
    padding: 2rem 0;
    min-height: 70vh;
}

/* En-tête de page */
.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid #e5e7eb;
}

.header-content h1 {
    font-size: 2rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.header-content p {
    color: var(--text-light);
    margin: 0;
}

.header-actions .btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(255, 107, 53, 0.3);
    color: white;
}

/* Filtres */
.filters-section {
    background: white;
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 2rem;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.filters-row {
    display: flex;
    gap: 1rem;
    align-items: end;
}

.filter-group {
    flex: 1;
}

.filter-label {
    display: block;
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
    font-size: 0.875rem;
}

.form-select, .form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    padding: 0.5rem 0.75rem;
    font-size: 0.875rem;
    transition: all 0.3s ease;
}

.form-select:focus, .form-control:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
}

.search-group {
    flex: 2;
}

/* Grille des demandes */
.errand-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.errand-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    border: 1px solid #e5e7eb;
    transition: all 0.3s ease;
    overflow: hidden;
}

.errand-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e5e7eb;
}

.errand-number .number-label {
    font-size: 0.875rem;
    color: var(--text-light);
    font-weight: 500;
}

.status-badge {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
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

.card-content {
    padding: 1.5rem;
}

.errand-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.errand-description {
    color: var(--text-light);
    font-size: 0.875rem;
    line-height: 1.5;
    margin-bottom: 1rem;
}

.errand-info {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
}

.info-item i {
    color: var(--primary-color);
    width: 16px;
}

.info-item span {
    color: var(--text-dark);
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

.cost-info {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0.75rem;
    background: #f8f9fa;
    border-radius: 6px;
    margin-bottom: 1rem;
}

.cost-label {
    font-size: 0.875rem;
    color: var(--text-light);
}

.cost-value {
    font-weight: 600;
    color: var(--primary-color);
}

.date-info {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.75rem;
    color: var(--text-light);
}

.date-info i {
    color: var(--text-light);
}

.card-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e5e7eb;
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 6px;
    display: flex;
    align-items: center;
    gap: 0.25rem;
    text-decoration: none;
    transition: all 0.3s ease;
    border: 1px solid;
}

.btn-outline-primary {
    color: var(--primary-color);
    border-color: var(--primary-color);
    background: transparent;
}

.btn-outline-primary:hover {
    background: var(--primary-color);
    color: white;
}

.btn-outline-danger {
    color: var(--danger-color);
    border-color: var(--danger-color);
    background: transparent;
}

.btn-outline-danger:hover {
    background: var(--danger-color);
    color: white;
}

/* État vide */
.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
}

.empty-icon {
    width: 80px;
    height: 80px;
    background: #f8f9fa;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    font-size: 2rem;
    color: var(--text-light);
}

.empty-state h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--text-light);
    margin-bottom: 2rem;
}

/* Pagination */
.pagination-container {
    display: flex;
    justify-content: center;
    margin-top: 2rem;
}

/* Responsive */
@media (max-width: 768px) {
    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .filters-row {
        flex-direction: column;
        gap: 1rem;
    }
    
    .errand-grid {
        grid-template-columns: 1fr;
    }
    
    .card-actions {
        flex-direction: column;
    }
    
    .btn-sm {
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusFilter = document.getElementById('status-filter');
    const urgencyFilter = document.getElementById('urgency-filter');
    const searchInput = document.getElementById('search-input');
    const errandCards = document.querySelectorAll('.errand-card');
    
    function filterErrands() {
        const statusValue = statusFilter.value;
        const urgencyValue = urgencyFilter.value;
        const searchValue = searchInput.value.toLowerCase();
        
        errandCards.forEach(card => {
            const status = card.dataset.status;
            const urgency = card.dataset.urgency;
            const title = card.querySelector('.errand-title').textContent.toLowerCase();
            const description = card.querySelector('.errand-description').textContent.toLowerCase();
            
            const statusMatch = !statusValue || status === statusValue;
            const urgencyMatch = !urgencyValue || urgency === urgencyValue;
            const searchMatch = !searchValue || title.includes(searchValue) || description.includes(searchValue);
            
            if (statusMatch && urgencyMatch && searchMatch) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
    }
    
    statusFilter.addEventListener('change', filterErrands);
    urgencyFilter.addEventListener('change', filterErrands);
    searchInput.addEventListener('input', filterErrands);
});
</script>
@endpush 