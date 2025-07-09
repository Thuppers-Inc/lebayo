@extends('admin.layouts.master')

@section('title', 'Adresses de ' . $client->full_name)
@section('description', 'Adresses de livraison du client')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card admin-card admin-title-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title m-0">Adresses de {{ $client->full_name }}</h5>
                        <p class="text-muted m-0">Toutes les adresses de livraison</p>
                    </div>
                    <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-admin-secondary">
                        <i class="bx bx-arrow-back"></i> Retour au profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-3 col-md-4 mb-4">
            <div class="card admin-card">
                <div class="card-body text-center">
                    <img src="{{ $client->photo_url }}" alt="{{ $client->full_name }}" class="admin-logo-lg rounded-circle mb-3">
                    <h5>{{ $client->full_name }}</h5>
                    <p class="text-muted">{{ $client->email }}</p>
                    <p class="text-muted">{{ $client->formatted_phone }}</p>
                    <hr>
                    <div class="text-center">
                        <h6>{{ $addresses->count() }}</h6>
                        <small class="text-muted">Adresses enregistrées</small>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            @if($addresses->count() > 0)
                <div class="row">
                    @foreach($addresses as $address)
                    <div class="col-md-6 col-lg-4 mb-4">
                        <div class="card admin-card h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start mb-3">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-wrapper">
                                            <div class="avatar avatar-sm me-3">
                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                    <i class="bx bx-map-pin"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $address->label }}</h6>
                                            @if($address->is_default)
                                                <span class="admin-badge admin-badge-success">Par défaut</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown">
                                        <button class="btn p-0" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if(!$address->is_default)
                                                <a class="dropdown-item" href="#" onclick="setDefaultAddress({{ $address->id }})">
                                                    <i class="bx bx-check me-1"></i> Définir par défaut
                                                </a>
                                            @endif
                                            <a class="dropdown-item text-danger" href="#" onclick="deleteAddress({{ $address->id }})">
                                                <i class="bx bx-trash me-1"></i> Supprimer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="address-details">
                                    <p class="mb-1">{{ $address->address_line_1 }}</p>
                                    @if($address->address_line_2)
                                        <p class="mb-1">{{ $address->address_line_2 }}</p>
                                    @endif
                                    <p class="mb-1">{{ $address->city }}</p>
                                    @if($address->postal_code)
                                        <p class="mb-1">{{ $address->postal_code }}</p>
                                    @endif
                                    <p class="mb-1">{{ $address->country }}</p>
                                    
                                    @if($address->phone)
                                        <hr>
                                        <p class="mb-0"><i class="bx bx-phone me-1"></i> {{ $address->phone }}</p>
                                    @endif
                                    
                                    <hr>
                                    <small class="text-muted">
                                        <i class="bx bx-calendar me-1"></i>
                                        Ajoutée le {{ $address->created_at->format('d/m/Y') }}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="card admin-card">
                    <div class="card-body">
                        <div class="admin-empty-state">
                            <div class="empty-icon">
                                <i class="bx bx-map"></i>
                            </div>
                            <h3>Aucune adresse</h3>
                            <p>Ce client n'a encore enregistré aucune adresse de livraison.</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function setDefaultAddress(addressId) {
    if (confirm('Définir cette adresse comme adresse par défaut ?')) {
        fetch(`/admin/clients/addresses/${addressId}/set-default`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                AdminComponents.showAlert('Adresse définie par défaut', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                AdminComponents.showAlert('Erreur lors de la mise à jour', 'danger');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            AdminComponents.showAlert('Erreur lors de la mise à jour', 'danger');
        });
    }
}

function deleteAddress(addressId) {
    if (confirm('Supprimer définitivement cette adresse ?')) {
        fetch(`/admin/clients/addresses/${addressId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                AdminComponents.showAlert('Adresse supprimée', 'success');
                setTimeout(() => window.location.reload(), 1000);
            } else {
                AdminComponents.showAlert('Erreur lors de la suppression', 'danger');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            AdminComponents.showAlert('Erreur lors de la suppression', 'danger');
        });
    }
}
</script>
@endpush 