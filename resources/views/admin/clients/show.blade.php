@extends('admin.layouts.master')

@section('title', 'Détails du Client')
@section('description', 'Informations détaillées sur le client')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-lg-4 col-md-4 order-1">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-6 mb-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <img src="{{ $client->photo_url }}" alt="{{ $client->full_name }}" class="admin-logo-lg rounded-circle">
                                </div>
                                <div class="dropdown">
                                    <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="bx bx-dots-vertical-rounded"></i>
                                    </button>
                                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                                        <a class="dropdown-item" href="#" onclick="editClient({{ $client->id }})">
                                            <i class="bx bx-edit-alt me-1"></i> Modifier
                                        </a>
                                        <a class="dropdown-item text-danger" href="#" onclick="deleteClient({{ $client->id }})">
                                            <i class="bx bx-trash me-1"></i> Supprimer
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <h3 class="card-title mb-2">{{ $client->full_name }}</h3>
                            <p class="mb-1">{{ $client->email }}</p>
                            <p class="mb-1">{{ $client->formatted_phone }}</p>
                            <small class="text-muted">Client depuis {{ $client->created_at->format('d/m/Y') }}</small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-6 mb-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="bx bx-package rounded"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Commandes</span>
                            <h3 class="card-title mb-2">{{ $client->orders->count() }}</h3>
                            <small class="text-success fw-semibold">
                                <i class="bx bx-up-arrow-alt"></i> 
                                {{ $client->orders->where('status', 'completed')->count() }} complétées
                            </small>
                        </div>
                    </div>
                </div>
                
                <div class="col-lg-12 col-md-12 col-6 mb-4">
                    <div class="card admin-card">
                        <div class="card-body">
                            <div class="card-title d-flex align-items-start justify-content-between">
                                <div class="avatar flex-shrink-0">
                                    <i class="bx bx-map rounded"></i>
                                </div>
                            </div>
                            <span class="fw-semibold d-block mb-1">Adresses</span>
                            <h3 class="card-title mb-2">{{ $client->addresses->count() }}</h3>
                            <small class="text-info fw-semibold">
                                <i class="bx bx-map-pin"></i> 
                                {{ $client->addresses->where('is_default', true)->count() }} par défaut
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8 col-md-8 order-0 mb-4">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Informations Personnelles</h5>
                    <button class="btn btn-sm btn-admin-primary" onclick="editClient({{ $client->id }})">
                        <i class="bx bx-edit-alt"></i> Modifier
                    </button>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nom complet</label>
                                <div class="form-control-plaintext">{{ $client->full_name }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <div class="form-control-plaintext">{{ $client->email }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <div class="form-control-plaintext">{{ $client->formatted_phone }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date de naissance</label>
                                <div class="form-control-plaintext">{{ $client->date_naissance ? $client->date_naissance->format('d/m/Y') : 'Non renseignée' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Lieu de naissance</label>
                                <div class="form-control-plaintext">{{ $client->lieu_naissance ?: 'Non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Ville</label>
                                <div class="form-control-plaintext">{{ $client->ville ?: 'Non renseignée' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Commune</label>
                                <div class="form-control-plaintext">{{ $client->commune ?: 'Non renseignée' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">CNI</label>
                                <div class="form-control-plaintext">{{ $client->numero_cni ?: 'Non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Passeport</label>
                                <div class="form-control-plaintext">{{ $client->numero_passeport ?: 'Non renseigné' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Inscription</label>
                                <div class="form-control-plaintext">{{ $client->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if($client->orders->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Commandes Récentes</h5>
                    <a href="{{ route('admin.clients.orders', $client) }}" class="btn btn-sm btn-admin-primary">
                        <i class="bx bx-package"></i> Voir Tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead class="admin-table-header">
                                <tr>
                                    <th>Commande</th>
                                    <th>Date</th>
                                    <th>Total</th>
                                    <th>Articles</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($client->orders->take(5) as $order)
                                <tr class="admin-table-row">
                                    <td>
                                        <strong>#{{ $order->order_number }}</strong>
                                    </td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
                                    <td>
                                        <span class="admin-badge admin-badge-primary">{{ $order->orderItems->count() }}</span>
                                    </td>
                                    <td>
                                        @php
                                            $statusClass = match($order->status) {
                                                'pending' => 'admin-badge-warning',
                                                'confirmed' => 'admin-badge-info',
                                                'preparing' => 'admin-badge-primary',
                                                'ready' => 'admin-badge-success',
                                                'delivered' => 'admin-badge-success',
                                                'cancelled' => 'admin-badge-danger',
                                                default => 'admin-badge-secondary'
                                            };
                                        @endphp
                                        <span class="admin-badge {{ $statusClass }}">{{ ucfirst($order->status) }}</span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-admin-primary">
                                            <i class="bx bx-show"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
    
    @if($client->addresses->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card admin-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title m-0">Adresses</h5>
                    <a href="{{ route('admin.clients.addresses', $client) }}" class="btn btn-sm btn-admin-primary">
                        <i class="bx bx-map"></i> Voir Tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($client->addresses as $address)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title">{{ $address->label }}</h6>
                                        @if($address->is_default)
                                            <span class="admin-badge admin-badge-success">Par défaut</span>
                                        @endif
                                    </div>
                                    <p class="text-muted mb-0">{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p class="text-muted mb-0">{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p class="text-muted mb-0">{{ $address->city }}, {{ $address->postal_code }}</p>
                                    <p class="text-muted">{{ $address->country }}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Inclure le modal d'édition --}}
@include('admin.clients.partials.edit-modal')
@endsection

@push('scripts')
<script>
function editClient(id) {
    // Logique pour éditer le client
    console.log('Éditer client:', id);
}

function deleteClient(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce client ?')) {
        fetch(`{{ route('admin.clients.index') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = '{{ route('admin.clients.index') }}';
            } else {
                alert('Erreur lors de la suppression');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la suppression');
        });
    }
}
</script>
@endpush 