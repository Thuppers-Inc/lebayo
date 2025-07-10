@extends('admin.layouts.master')

@section('title', 'Commande #' . $order->order_number)
@section('description', 'Détails de la commande #' . $order->order_number)

@section('content')
<div class="row">
  <div class="col-12">
    <!-- En-tête avec retour -->
    <div class="card rounded-3 shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-3">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary btn-sm">
              <i class="bx bx-arrow-back"></i> Retour
            </a>
            <div>
              <h4 class="fw-bold mb-1">Commande #{{ $order->order_number }}</h4>
              <p class="text-muted mb-0">Passée le {{ $order->created_at->format('d/m/Y à H:i') }}</p>
            </div>
          </div>
          <div class="d-flex gap-2">
            <span class="badge 
              @if($order->status === 'pending') bg-warning
              @elseif($order->status === 'confirmed') bg-info
              @elseif($order->status === 'preparing') bg-primary
              @elseif($order->status === 'ready') bg-secondary
              @elseif($order->status === 'out_for_delivery') bg-dark
              @elseif($order->status === 'delivered') bg-success
              @elseif($order->status === 'cancelled') bg-danger
              @endif px-3 py-2 fs-6">
              {{ $order->status_label }}
            </span>
            <span class="badge 
              @if($order->payment_status === 'pending') bg-warning
              @elseif($order->payment_status === 'paid') bg-success
              @elseif($order->payment_status === 'failed') bg-danger
              @elseif($order->payment_status === 'refunded') bg-secondary
              @endif px-3 py-2 fs-6">
              {{ $order->payment_status_label }}
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Informations client -->
      <div class="col-md-4">
        <div class="card rounded-3 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-user me-2"></i>Informations client
            </h5>
            <div class="mb-3">
              <label class="form-label text-muted">Nom</label>
              <p class="mb-0 fw-semibold">{{ $order->user->name }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Email</label>
              <p class="mb-0">{{ $order->user->email }}</p>
            </div>
            <div class="mb-3">
              <label class="form-label text-muted">Téléphone</label>
              <p class="mb-0">{{ $order->user->phone ?? 'Non renseigné' }}</p>
            </div>
          </div>
        </div>

        @if($order->commerce)
        <!-- Informations du commerce -->
        <div class="card rounded-3 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-store me-2"></i>{{ $order->commerce->commerce_type_name }}
            </h5>
            <div class="d-flex align-items-center gap-3 mb-3">
              <img src="{{ $order->commerce->logo_url }}" 
                   alt="{{ $order->commerce->name }}" 
                   class="rounded"
                   width="60" 
                   height="60"
                   style="object-fit: cover;">
              <div>
                <h6 class="mb-1">{{ $order->commerce->name }}</h6>
                <p class="text-muted mb-0">{{ $order->commerce->commerce_type_name }}</p>
              </div>
            </div>
            <div class="mb-2">
              <i class="bx bx-map-pin me-2"></i>{{ $order->commerce->full_address }}
            </div>
            @if($order->commerce->phone)
            <div class="mb-2">
              <i class="bx bx-phone me-2"></i>{{ $order->commerce->phone }}
            </div>
            @endif
            @if($order->commerce->email)
            <div class="mb-2">
              <i class="bx bx-envelope me-2"></i>{{ $order->commerce->email }}
            </div>
            @endif
          </div>
        </div>
        @else
        <!-- Commerce non trouvé -->
        <div class="card rounded-3 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-store me-2"></i>Commerce
            </h5>
            <div class="alert alert-warning">
              <i class="bx bx-exclamation-triangle me-2"></i>
              Les informations du commerce ne sont pas disponibles pour cette commande.
            </div>
          </div>
        </div>
        @endif

        <!-- Adresse de livraison -->
        <div class="card rounded-3 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-map me-2"></i>Adresse de livraison
            </h5>
            <div class="mb-2">
              <strong>{{ $order->deliveryAddress->name }}</strong>
            </div>
            <div class="mb-2">
              {{ $order->deliveryAddress->street }}
            </div>
            <div class="mb-2">
              {{ $order->deliveryAddress->city }}, {{ $order->deliveryAddress->country }}
            </div>
            @if($order->deliveryAddress->phone)
            <div class="mb-2">
              <i class="bx bx-phone me-1"></i>{{ $order->deliveryAddress->phone }}
            </div>
            @endif
            @if($order->deliveryAddress->additional_info)
            <div class="text-muted">
              <small>{{ $order->deliveryAddress->additional_info }}</small>
            </div>
            @endif
          </div>
        </div>

        <!-- Actions rapides -->
        <div class="card rounded-3 shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-cog me-2"></i>Actions rapides
            </h5>
            <div class="d-grid gap-2">
              <button type="button" class="btn btn-outline-primary" onclick="updateOrderStatus({{ $order->id }})">
                <i class="bx bx-refresh me-1"></i>Changer le statut
              </button>
              @if($order->payment_status !== 'paid')
              <button type="button" class="btn btn-outline-success" onclick="updatePaymentStatus({{ $order->id }})">
                <i class="bx bx-money me-1"></i>Marquer comme payé
              </button>
              @endif
            </div>
          </div>
        </div>
      </div>

      <!-- Détails de la commande -->
      <div class="col-md-8">
        <!-- Articles commandés -->
        <div class="card rounded-3 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-shopping-bag me-2"></i>Articles commandés
            </h5>
            <div class="table-responsive">
              <table class="table table-hover">
                <thead class="table-light">
                  <tr>
                    <th>Produit</th>
                    <th>Prix unitaire</th>
                    <th>Quantité</th>
                    <th>Total</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach($order->items as $item)
                  <tr>
                    <td>
                      <div class="d-flex align-items-center gap-3">
                        <img src="{{ $item->display_image }}" 
                             alt="{{ $item->display_name }}" 
                             class="rounded" 
                             width="50" 
                             height="50"
                             style="object-fit: cover;">
                        <div>
                          <strong>{{ $item->display_name }}</strong>
                          @if($item->display_description)
                          <br>
                          <small class="text-muted">{{ Str::limit($item->display_description, 60) }}</small>
                          @endif
                        </div>
                      </div>
                    </td>
                    <td>{{ $item->formatted_price }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td><strong>{{ $item->formatted_subtotal }}</strong></td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- Résumé de la commande -->
        <div class="card rounded-3 shadow-sm mb-4">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-receipt me-2"></i>Résumé de la commande
            </h5>
            <div class="row">
              <div class="col-md-6">
                <table class="table table-borderless">
                  <tr>
                    <td>Sous-total :</td>
                    <td class="text-end">{{ $order->formatted_subtotal }}</td>
                  </tr>
                  <tr>
                    <td>Frais de livraison :</td>
                    <td class="text-end">{{ $order->formatted_delivery_fee }}</td>
                  </tr>
                  @if($order->discount > 0)
                  <tr>
                    <td>Réduction :</td>
                    <td class="text-end text-success">-{{ $order->formatted_discount }}</td>
                  </tr>
                  @endif
                  <tr class="border-top">
                    <td><strong>Total :</strong></td>
                    <td class="text-end"><strong>{{ $order->formatted_total }}</strong></td>
                  </tr>
                </table>
              </div>
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-muted">Méthode de paiement</label>
                  <p class="mb-0">{{ $order->payment_method_label }}</p>
                </div>
                <div class="mb-3">
                  <label class="form-label text-muted">Statut du paiement</label>
                  <p class="mb-0">
                    <span class="badge 
                      @if($order->payment_status === 'pending') bg-warning
                      @elseif($order->payment_status === 'paid') bg-success
                      @elseif($order->payment_status === 'failed') bg-danger
                      @elseif($order->payment_status === 'refunded') bg-secondary
                      @endif">
                      {{ $order->payment_status_label }}
                    </span>
                  </p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Informations de livraison -->
        <div class="card rounded-3 shadow-sm">
          <div class="card-body">
            <h5 class="card-title mb-3">
              <i class="bx bx-truck me-2"></i>Informations de livraison
            </h5>
            <div class="row">
              <div class="col-md-6">
                <div class="mb-3">
                  <label class="form-label text-muted">Heure estimée de livraison</label>
                  <p class="mb-0">
                    @if($order->estimated_delivery_time)
                      {{ $order->estimated_delivery_time->format('d/m/Y à H:i') }}
                    @else
                      Non définie
                    @endif
                  </p>
                </div>
                @if($order->actual_delivery_time)
                <div class="mb-3">
                  <label class="form-label text-muted">Heure réelle de livraison</label>
                  <p class="mb-0 text-success">
                    {{ $order->actual_delivery_time->format('d/m/Y à H:i') }}
                  </p>
                </div>
                @endif
              </div>
              <div class="col-md-6">
                @if($order->notes)
                <div class="mb-3">
                  <label class="form-label text-muted">Notes de la commande</label>
                  <p class="mb-0">{{ $order->notes }}</p>
                </div>
                @endif
                @if($order->delivery_notes)
                <div class="mb-3">
                  <label class="form-label text-muted">Notes de livraison</label>
                  <p class="mb-0">{{ $order->delivery_notes }}</p>
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

<!-- Modal Changer Statut Commande -->
<div class="modal fade" id="updateStatusModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Mettre à jour le statut de la commande</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="updateStatusForm" method="POST" action="{{ route('admin.orders.update-status', $order) }}">
        @csrf
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_status" class="form-label">Nouveau statut</label>
            <select class="form-select" id="new_status" name="status" required>
              <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
              <option value="confirmed" {{ $order->status === 'confirmed' ? 'selected' : '' }}>Confirmée</option>
              <option value="preparing" {{ $order->status === 'preparing' ? 'selected' : '' }}>En préparation</option>
              <option value="ready" {{ $order->status === 'ready' ? 'selected' : '' }}>Prête</option>
              <option value="out_for_delivery" {{ $order->status === 'out_for_delivery' ? 'selected' : '' }}>En livraison</option>
              <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Livrée</option>
              <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
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
      <form id="updatePaymentForm" method="POST" action="{{ route('admin.orders.update-payment-status', $order) }}">
        @csrf
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="new_payment_status" class="form-label">Nouveau statut de paiement</label>
            <select class="form-select" id="new_payment_status" name="payment_status" required>
              <option value="pending" {{ $order->payment_status === 'pending' ? 'selected' : '' }}>En attente</option>
              <option value="paid" {{ $order->payment_status === 'paid' ? 'selected' : '' }}>Payé</option>
              <option value="failed" {{ $order->payment_status === 'failed' ? 'selected' : '' }}>Échoué</option>
              <option value="refunded" {{ $order->payment_status === 'refunded' ? 'selected' : '' }}>Remboursé</option>
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

/* Style pour les badges */
.badge {
  font-size: 0.75rem;
  font-weight: 500;
  letter-spacing: 0.3px;
}

/* Style pour les tableaux */
.table-hover tbody tr:hover {
  background-color: rgba(247, 127, 0, 0.05);
}

/* Style pour les images des produits */
.rounded {
  border-radius: 8px !important;
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
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function updatePaymentStatus(orderId) {
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
    const firstCard = document.querySelector('.card .card-body');
    firstCard.insertBefore(alertDiv, firstCard.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
}

// Gestion des formulaires
document.getElementById('updateStatusForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
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

document.getElementById('updatePaymentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
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