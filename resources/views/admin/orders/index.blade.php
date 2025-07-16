@extends('admin.layouts.master')

@section('title', 'Gestion des Commandes')
@section('description', 'Suivi et gestion de toutes les commandes de la plateforme')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Section titre dans cadre blanc arrondi -->
    <div class="card rounded-3 shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="fw-bold mb-1">Gestion des Commandes</h4>
            <p class="text-muted mb-0">{{ $orders->total() }} commande(s) au total</p>
          </div>
          <div class="d-flex gap-2">
            <!-- Statistiques rapides -->
            <div class="d-flex gap-3">
              <div class="text-center">
                <div class="badge bg-warning fs-6 px-3 py-2">{{ $stats['pending'] }}</div>
                <small class="d-block text-muted mt-1">En attente</small>
              </div>
              <div class="text-center">
                <div class="badge bg-primary fs-6 px-3 py-2">{{ $stats['confirmed'] }}</div>
                <small class="d-block text-muted mt-1">Confirmées</small>
              </div>
              <div class="text-center">
                <div class="badge bg-success fs-6 px-3 py-2">{{ $stats['delivered'] }}</div>
                <small class="d-block text-muted mt-1">Livrées</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Filtres -->
    <div class="card rounded-3 shadow-sm mb-4">
      <div class="card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
          <div class="col-md-3">
            <label for="status" class="form-label">Statut de la commande</label>
            <select class="form-select" id="status" name="status">
              <option value="">Tous les statuts</option>
              <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
              <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
              <option value="preparing" {{ request('status') === 'preparing' ? 'selected' : '' }}>En préparation</option>
              <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Prête</option>
              <option value="out_for_delivery" {{ request('status') === 'out_for_delivery' ? 'selected' : '' }}>En livraison</option>
              <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Livrée</option>
              <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Annulée</option>
            </select>
          </div>
          <div class="col-md-3">
            <label for="payment_status" class="form-label">Statut de paiement</label>
            <select class="form-select" id="payment_status" name="payment_status">
              <option value="">Tous les statuts</option>
              <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>En attente</option>
              <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Payé</option>
              <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Échoué</option>
              <option value="refunded" {{ request('payment_status') === 'refunded' ? 'selected' : '' }}>Remboursé</option>
            </select>
          </div>
          <div class="col-md-4">
            <label for="search" class="form-label">Rechercher</label>
            <input type="text" class="form-control" id="search" name="search" 
                   value="{{ request('search') }}" 
                   placeholder="Numéro de commande, nom du client...">
          </div>
          <div class="col-md-2 d-flex align-items-end">
            <button type="submit" class="btn btn-primary me-2">
              <i class="bx bx-search"></i> Filtrer
            </button>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
              <i class="bx bx-refresh"></i>
            </a>
          </div>
        </form>
      </div>
    </div>

    <div class="card rounded-3 shadow-sm">
      <div class="card-body p-0">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible m-3 rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        @if($orders->count() > 0)
          <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table table-hover mb-0">
                              <thead class="gradient-header">
                <tr>
                  <th class="border-0 text-white fw-semibold">Commande</th>
                  <th class="border-0 text-white fw-semibold">Client</th>
                                      <th class="border-0 text-white fw-semibold">Commerçants</th>
                  <th class="border-0 text-white fw-semibold">Total</th>
                  <th class="border-0 text-white fw-semibold">Statut</th>
                  <th class="border-0 text-white fw-semibold">Paiement</th>
                  <th class="border-0 text-white fw-semibold">Date</th>
                  <th class="border-0 text-white fw-semibold">Actions</th>
                </tr>
              </thead>
              <tbody id="ordersTable">
                @foreach($orders as $order)
                  <tr id="row-{{ $order->id }}" class="table-row-hover">
                    <td class="py-3">
                      <div>
                        <strong class="text-dark">#{{ $order->order_number }}</strong>
                        <br>
                        <small class="text-muted">{{ $order->items->count() }} article(s)</small>
                      </div>
                    </td>
                    <td class="py-3">
                      <div class="d-flex align-items-center gap-2">
                        <img src="{{ $order->user->photo_url }}" 
                             alt="{{ $order->user->full_name }}" 
                             class="rounded-circle" 
                             width="32" 
                             height="32"
                             style="object-fit: cover;">
                        <div>
                          <strong class="text-dark">{{ $order->user->full_name }}</strong>
                          <br>
                          <small class="text-muted">{{ $order->user->email }}</small>
                          @if($order->user->formatted_phone)
                          <br>
                          <small class="text-muted">
                            <i class="bx bx-phone me-1"></i>{{ $order->user->formatted_phone }}
                          </small>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td class="py-3">
                      @php
                        $commerces = $order->items->map(function($item) {
                          return [
                            'name' => $item->commerce_name,
                            'logo' => $item->commerce_logo,
                            'id' => $item->product && $item->product->commerce ? $item->product->commerce->id : null
                          ];
                        })->filter(function($commerce) {
                          return $commerce['name'] !== 'Commerce supprimé';
                        })->unique('id');
                      @endphp
                      
                      @if($commerces->count() > 0)
                        <div class="d-flex flex-column gap-1">
                          @foreach($commerces->take(2) as $commerce)
                            <div class="d-flex align-items-center gap-2">
                              <img src="{{ $commerce['logo'] }}" 
                                   alt="{{ $commerce['name'] }}" 
                                   class="rounded-circle" 
                                   width="20" 
                                   height="20"
                                   style="object-fit: cover;">
                              <small class="text-truncate" style="max-width: 120px;">{{ $commerce['name'] }}</small>
                            </div>
                          @endforeach
                          @if($commerces->count() > 2)
                            <small class="text-muted">+{{ $commerces->count() - 2 }} autre(s)</small>
                          @endif
                        </div>
                      @else
                        <small class="text-muted">Aucun commerçant</small>
                      @endif
                    </td>
                    <td class="py-3">
                      <strong class="text-dark">{{ $order->formatted_total }}</strong>
                    </td>
                    <td class="py-3">
                      <span class="badge 
                        @if($order->status === 'pending') bg-warning
                        @elseif($order->status === 'confirmed') bg-info
                        @elseif($order->status === 'preparing') bg-primary
                        @elseif($order->status === 'ready') bg-secondary
                        @elseif($order->status === 'out_for_delivery') bg-dark
                        @elseif($order->status === 'delivered') bg-success
                        @elseif($order->status === 'cancelled') bg-danger
                        @endif px-3 py-2" id="status-{{ $order->id }}">
                        {{ $order->status_label }}
                      </span>
                    </td>
                    <td class="py-3">
                      <span class="badge 
                        @if($order->payment_status === 'pending') bg-warning
                        @elseif($order->payment_status === 'paid') bg-success
                        @elseif($order->payment_status === 'failed') bg-danger
                        @elseif($order->payment_status === 'refunded') bg-secondary
                        @endif px-3 py-2" id="payment-status-{{ $order->id }}">
                        {{ $order->payment_status_label }}
                      </span>
                    </td>
                    <td class="py-3 text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td class="py-3">
                      <div class="d-flex gap-1">
                        <!-- Bouton Voir détails -->
                        <a href="{{ route('admin.orders.show', $order) }}" 
                           class="btn btn-sm btn-outline-primary rounded-pill px-2" 
                           title="Voir les détails">
                          <i class="bx bx-show"></i>
                        </a>
                        
                        <!-- Bouton Changer statut -->
                        <button type="button" class="btn btn-sm btn-outline-success rounded-pill px-2" 
                                onclick="updateOrderStatus({{ $order->id }})" 
                                title="Changer le statut">
                          <i class="bx bx-refresh"></i>
                        </button>
                        
                        <!-- Bouton Statut paiement -->
                        @if($order->payment_status !== 'paid')
                        <button type="button" class="btn btn-sm btn-outline-warning rounded-pill px-2" 
                                onclick="updatePaymentStatus({{ $order->id }})" 
                                title="Marquer comme payé">
                          <i class="bx bx-money"></i>
                        </button>
                        @endif
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="d-flex justify-content-center p-4 border-top">
            {{ $orders->appends(request()->query())->links() }}
          </div>
        @else
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="bx bx-shopping-bag display-2 text-muted"></i>
            </div>
            <h5 class="text-dark mb-2">Aucune commande trouvée</h5>
            <p class="text-muted mb-4">Les commandes apparaîtront ici une fois que les clients commenceront à passer des commandes</p>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Changer Statut Commande -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mettre à jour le statut de la commande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateStatusForm" method="POST">
        @csrf
        <input type="hidden" id="orderIdStatus" name="order_id" value="">
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_status" class="form-label">Nouveau statut</label>
            <select class="form-select" id="new_status" name="status" required>
              <option value="pending">En attente</option>
              <option value="confirmed">Confirmée</option>
              <option value="preparing">En préparation</option>
              <option value="ready">Prête</option>
              <option value="out_for_delivery">En livraison</option>
              <option value="delivered">Livrée</option>
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

<!-- Modal Changer Statut Paiement -->
<div class="modal fade" id="updatePaymentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mettre à jour le statut de paiement</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updatePaymentForm" method="POST">
        @csrf
        <input type="hidden" id="orderIdPayment" name="order_id" value="">
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_payment_status" class="form-label">Nouveau statut de paiement</label>
            <select class="form-select" id="new_payment_status" name="payment_status" required>
              <option value="pending">En attente</option>
              <option value="paid">Payé</option>
              <option value="failed">Échoué</option>
              <option value="refunded">Remboursé</option>
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

@push('styles')
<style>
/* Gradient orange-rouge pour le header du tableau */
.gradient-header {
  background: linear-gradient(135deg, #F77F00 0%, #D62828 100%);
}

/* Style pour les lignes du tableau */
.table-row-hover:hover {
  background-color: rgba(247, 127, 0, 0.05);
  transition: background-color 0.2s ease;
}

/* Amélioration des badges */
.badge {
  font-size: 0.75rem;
  font-weight: 500;
  letter-spacing: 0.3px;
}

/* Style pour la carte */
.card {
  border: none;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
}

/* Style pour le bouton principal */
.btn-primary {
  background: linear-gradient(135deg, #003049 0%, #D62828 100%);
  border: none;
  transition: all 0.3s ease;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #D62828 0%, #F77F00 100%);
  transform: translateY(-2px);
  box-shadow: 0 0.5rem 1rem rgba(214, 40, 40, 0.3);
}

/* Animation pour les boutons d'actions */
.btn-outline-primary:hover {
  background: linear-gradient(135deg, #003049 0%, #0066CC 100%);
  border-color: #003049;
  transform: scale(1.05);
  transition: all 0.2s ease;
}

.btn-outline-success:hover {
  background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
  border-color: #28a745;
  transform: scale(1.05);
  transition: all 0.2s ease;
}

.btn-outline-warning:hover {
  background: linear-gradient(135deg, #F77F00 0%, #ffc107 100%);
  border-color: #F77F00;
  transform: scale(1.05);
  transition: all 0.2s ease;
}

/* Style général pour les boutons d'action */
.btn-sm {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.btn-sm:hover {
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.2);
}

/* Style pour les alertes */
.alert {
  border: none;
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}
</style>
@endpush

@push('scripts')
<script>
function updateOrderStatus(orderId) {
    document.getElementById('orderIdStatus').value = orderId;
    document.getElementById('updateStatusForm').action = `/admin/orders/${orderId}/update-status`;
    
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function updatePaymentStatus(orderId) {
    document.getElementById('orderIdPayment').value = orderId;
    document.getElementById('updatePaymentForm').action = `/admin/orders/${orderId}/update-payment-status`;
    
    const modal = new bootstrap.Modal(document.getElementById('updatePaymentModal'));
    modal.show();
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible m-3 rounded-3`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    const cardBody = document.querySelector('.card-body');
    cardBody.insertBefore(alertDiv, cardBody.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Gestion du formulaire de mise à jour du statut
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = document.getElementById('orderIdStatus').value;
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
            modal.hide();
            showAlert(data.message, 'success');
            
            // Mettre à jour le badge du statut
            const statusBadge = document.getElementById(`status-${orderId}`);
            statusBadge.textContent = data.status_label;
            
            // Recharger la page pour mettre à jour toutes les données
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('Erreur lors de la mise à jour', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('Une erreur est survenue', 'danger');
    });
});

// Gestion du formulaire de mise à jour du statut de paiement
document.getElementById('updatePaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const orderId = document.getElementById('orderIdPayment').value;
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const modal = bootstrap.Modal.getInstance(document.getElementById('updatePaymentModal'));
            modal.hide();
            showAlert(data.message, 'success');
            
            // Mettre à jour le badge du statut de paiement
            const paymentBadge = document.getElementById(`payment-status-${orderId}`);
            paymentBadge.textContent = data.payment_status_label;
            
            // Recharger la page pour mettre à jour toutes les données
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showAlert('Erreur lors de la mise à jour', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('Une erreur est survenue', 'danger');
    });
});
</script>
@endpush 