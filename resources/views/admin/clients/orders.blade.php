@extends('admin.layouts.master')

@section('title', 'Commandes de ' . $client->full_name)
@section('description', 'Historique des commandes du client')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card admin-card admin-title-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title m-0">Commandes de {{ $client->full_name }}</h5>
                        <p class="text-muted m-0">Historique complet des commandes</p>
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
                    <div class="row text-center">
                        <div class="col-4">
                            <h6>{{ $orders->total() }}</h6>
                            <small class="text-muted">Commandes</small>
                        </div>
                        <div class="col-4">
                            <h6>{{ $orders->where('status', 'delivered')->count() }}</h6>
                            <small class="text-muted">Livrées</small>
                        </div>
                        <div class="col-4">
                            <h6>{{ number_format($orders->sum('total_amount'), 0, ',', ' ') }}</h6>
                            <small class="text-muted">FCFA</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-9 col-md-8">
            <div class="card admin-card">
                <div class="card-header">
                    <h6 class="card-title">Historique des Commandes</h6>
                </div>
                <div class="card-body">
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-borderless">
                                <thead class="admin-table-header">
                                    <tr>
                                        <th>Commande</th>
                                        <th>Date</th>
                                        <th>Articles</th>
                                        <th>Total</th>
                                        <th>Statut</th>
                                        <th>Paiement</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($orders as $order)
                                    <tr class="admin-table-row">
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-wrapper">
                                                    <div class="avatar avatar-sm me-3">
                                                        <span class="avatar-initial rounded-circle bg-label-primary">
                                                            <i class="bx bx-package"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0">#{{ $order->order_number }}</h6>
                                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                        <td>
                                            <span class="admin-badge admin-badge-primary">
                                                {{ $order->orderItems->count() }}
                                            </span>
                                        </td>
                                        <td class="fw-bold">{{ number_format($order->total_amount, 0, ',', ' ') }} FCFA</td>
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
                                                $statusText = match($order->status) {
                                                    'pending' => 'En attente',
                                                    'confirmed' => 'Confirmée',
                                                    'preparing' => 'Préparation',
                                                    'ready' => 'Prête',
                                                    'delivered' => 'Livrée',
                                                    'cancelled' => 'Annulée',
                                                    default => ucfirst($order->status)
                                                };
                                            @endphp
                                            <span class="admin-badge {{ $statusClass }}">{{ $statusText }}</span>
                                        </td>
                                        <td>
                                            @php
                                                $paymentClass = match($order->payment_status) {
                                                    'pending' => 'admin-badge-warning',
                                                    'paid' => 'admin-badge-success',
                                                    'failed' => 'admin-badge-danger',
                                                    'refunded' => 'admin-badge-info',
                                                    default => 'admin-badge-secondary'
                                                };
                                                $paymentText = match($order->payment_status) {
                                                    'pending' => 'En attente',
                                                    'paid' => 'Payé',
                                                    'failed' => 'Échoué',
                                                    'refunded' => 'Remboursé',
                                                    default => ucfirst($order->payment_status)
                                                };
                                            @endphp
                                            <span class="admin-badge {{ $paymentClass }}">{{ $paymentText }}</span>
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

                        {{-- Pagination --}}
                        <div class="d-flex justify-content-center">
                            {{ $orders->links() }}
                        </div>
                    @else
                        <div class="admin-empty-state">
                            <div class="empty-icon">
                                <i class="bx bx-package"></i>
                            </div>
                            <h3>Aucune commande</h3>
                            <p>Ce client n'a encore passé aucune commande.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 