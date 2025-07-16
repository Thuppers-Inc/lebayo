@extends('admin.layouts.master')

@section('title', 'Commande #' . $order->order_number)
@section('description', 'Détails de la commande #' . $order->order_number)

@section('content')
<div class="row">
  <div class="col-12">
    <!-- En-tête avec retour -->
    <div class="admin-title-card card rounded-3 mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div class="d-flex align-items-center gap-4">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-light btn-lg rounded-pill">
              <i class="bx bx-arrow-back me-2"></i> Retour
            </a>
            <div>
              <h2 class="text-white fw-bold mb-1">Commande #{{ $order->order_number }}</h2>
              <p class="text-white-50 mb-0">
                <i class="bx bx-calendar me-2"></i>{{ $order->created_at->format('d/m/Y à H:i') }}
              </p>
            </div>
          </div>
          <div class="d-flex gap-3 align-items-center">
            <div class="text-end me-3">
              <div class="text-white-50 small">Total</div>
              <div class="text-white h4 fw-bold">{{ $order->formatted_total }}</div>
            </div>
            <div class="d-flex flex-column gap-2">
              <span class="admin-badge px-3 py-2 rounded-pill
                @if($order->status === 'pending') admin-badge-warning
                @elseif($order->status === 'confirmed') admin-badge-primary
                @elseif($order->status === 'preparing') admin-badge-primary
                @elseif($order->status === 'ready') admin-badge-primary
                @elseif($order->status === 'out_for_delivery') admin-badge-primary
                @elseif($order->status === 'delivered') admin-badge-success
                @elseif($order->status === 'cancelled') admin-badge-danger
                @endif">
                {{ $order->status_label }}
              </span>
              <span class="admin-badge px-3 py-2 rounded-pill
                @if($order->payment_status === 'pending') admin-badge-warning
                @elseif($order->payment_status === 'paid') admin-badge-success
                @elseif($order->payment_status === 'failed') admin-badge-danger
                @elseif($order->payment_status === 'refunded') admin-badge-inactive
                @endif">
                {{ $order->payment_status_label }}
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="row">
      <!-- Informations client -->
      <div class="col-md-4">
        <div class="admin-card card rounded-3 mb-4">
          <div class="card-header-primary">
            <h5 class="mb-0 fw-bold">
              <i class="bx bx-user me-2"></i>Informations client
            </h5>
          </div>
          <div class="card-body p-4">
            <!-- Photo et nom principal -->
            <div class="d-flex align-items-center gap-4 mb-4 p-4 bg-theme-beige rounded-3 border">
              <div class="position-relative">
                <img src="{{ $order->user->photo_url }}" 
                     alt="{{ $order->user->full_name }}" 
                     class="rounded-circle border border-3 border-white shadow" 
                     width="80" 
                     height="80"
                     style="object-fit: cover;">
                <span class="position-absolute bottom-0 end-0 translate-middle badge rounded-pill 
                      @if($order->user->account_type->value === 'client') bg-theme-orange
                      @elseif($order->user->account_type->value === 'admin') bg-theme-dark-blue
                      @else bg-theme-red @endif">
                  <i class="bx bx-check fs-6"></i>
                </span>
              </div>
              <div class="flex-grow-1">
                <h5 class="mb-2 fw-bold text-theme-dark-blue">{{ $order->user->full_name }}</h5>
                <div class="d-flex gap-2 align-items-center">
                  <span class="admin-badge admin-badge-primary rounded-pill px-3 py-2">
                    {{ ucfirst($order->user->account_type->value) }}
                  </span>
                  <small class="text-muted">
                    <i class="bx bx-time me-1"></i>Client depuis {{ $order->user->created_at->diffForHumans() }}
                  </small>
                </div>
              </div>
            </div>

            <!-- Détails du client en cartes -->
            <div class="row g-3">
              <!-- Contact -->
              <div class="col-12">
                <div class="bg-light rounded-3 p-3 border border-2 border-primary border-opacity-25">
                  <h6 class="text-theme-dark-blue mb-3 fw-bold">Contact</h6>
                  <div class="row g-2">
                    <div class="col-md-6">
                      <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-envelope text-theme-orange fs-5"></i>
                        <div>
                          <small class="text-muted d-block">Email</small>
                          <span class="fw-semibold">{{ $order->user->email }}</span>
                        </div>
                      </div>
                    </div>
                    @if($order->user->formatted_phone)
                    <div class="col-md-6">
                      <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-phone text-theme-orange fs-5"></i>
                        <div>
                          <small class="text-muted d-block">Téléphone</small>
                          <span class="fw-semibold">{{ $order->user->formatted_phone }}</span>
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>

              <!-- Localisation -->
              @if($order->user->ville || $order->user->commune || $order->user->lieu_naissance)
              <div class="col-12">
                <div class="bg-light rounded-3 p-3 border border-2 border-warning border-opacity-25">
                  <h6 class="text-theme-dark-blue mb-3 fw-bold">Localisation</h6>
                  <div class="row g-2">
                    @if($order->user->ville || $order->user->commune)
                    <div class="col-md-6">
                      <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-current-location text-theme-yellow fs-5"></i>
                        <div>
                          <small class="text-muted d-block">Résidence</small>
                          <span class="fw-semibold">
                            @if($order->user->commune){{ $order->user->commune }}@endif
                            @if($order->user->ville && $order->user->commune), @endif
                            @if($order->user->ville){{ $order->user->ville }}@endif
                          </span>
                        </div>
                      </div>
                    </div>
                    @endif
                    @if($order->user->lieu_naissance)
                    <div class="col-md-6">
                      <div class="d-flex align-items-center gap-2">
                        <i class="bx bx-map-pin text-theme-yellow fs-5"></i>
                        <div>
                          <small class="text-muted d-block">Lieu de naissance</small>
                          <span class="fw-semibold">{{ $order->user->lieu_naissance }}</span>
                        </div>
                      </div>
                    </div>
                    @endif
                  </div>
                </div>
              </div>
              @endif

              <!-- Statistiques -->
              <div class="col-12">
                <div class="bg-light rounded-3 p-3 border border-2 border-success border-opacity-25">
                  <h6 class="text-theme-dark-blue mb-3 fw-bold">Statistiques</h6>
                  <div class="row g-3">
                    @if($order->user->formatted_age)
                    <div class="col-md-4">
                      <div class="text-center">
                        <i class="bx bx-cake text-theme-red fs-4 d-block mb-1"></i>
                        <div class="fw-bold fs-5 text-theme-dark-blue">{{ $order->user->formatted_age }}</div>
                        <small class="text-muted">ans</small>
                      </div>
                    </div>
                    @endif
                    <div class="col-md-4">
                      <div class="text-center">
                        <i class="bx bx-calendar text-theme-orange fs-4 d-block mb-1"></i>
                        <div class="fw-bold fs-5 text-theme-dark-blue">{{ $order->user->seniority_label }}</div>
                        <small class="text-muted">d'ancienneté</small>
                      </div>
                    </div>
                    <div class="col-md-4">
                      <div class="text-center">
                        <i class="bx bx-shopping-bag text-theme-yellow fs-4 d-block mb-1"></i>
                        <div class="fw-bold fs-5 text-theme-dark-blue">{{ $order->user->orders()->count() }}</div>
                        <small class="text-muted">commandes</small>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Adresse de livraison -->
        <div class="admin-card card rounded-3 mb-4">
          <div class="card-header-accent">
            <h5 class="mb-0 fw-bold">
              <i class="bx bx-map me-2"></i>Adresse de livraison
            </h5>
          </div>
          <div class="card-body p-4">
            <div class="bg-theme-beige rounded-3 p-4 border">
              <div class="d-flex align-items-start gap-3">
                <div class="bg-theme-orange rounded-circle p-3 flex-shrink-0">
                  <i class="bx bx-map-pin text-white fs-4"></i>
                </div>
                <div class="flex-grow-1">
                  <h6 class="fw-bold text-theme-dark-blue mb-2">{{ $order->deliveryAddress->name }}</h6>
                  <div class="mb-2">
                    <i class="bx bx-current-location text-theme-orange me-2"></i>
                    <span class="fw-semibold">{{ $order->deliveryAddress->street }}</span>
                  </div>
                  <div class="mb-2">
                    <i class="bx bx-globe text-theme-orange me-2"></i>
                    <span>{{ $order->deliveryAddress->city }}, {{ $order->deliveryAddress->country }}</span>
                  </div>
                  @if($order->deliveryAddress->phone)
                  <div class="mb-2">
                    <i class="bx bx-phone text-theme-orange me-2"></i>
                    <span>{{ $order->deliveryAddress->phone }}</span>
                  </div>
                  @endif
                  @if($order->deliveryAddress->additional_info)
                  <div class="mt-3 p-2 bg-white rounded border-start border-4 border-theme-orange">
                    <small class="text-muted fst-italic">{{ $order->deliveryAddress->additional_info }}</small>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Actions rapides -->
        <div class="admin-card card rounded-3">
          <div class="card-header-secondary">
            <h5 class="mb-0 fw-bold">
              <i class="bx bx-cog me-2"></i>Actions rapides
            </h5>
          </div>
          <div class="card-body p-4">
            <div class="d-grid gap-3">
              <button type="button" class="btn btn-admin-primary btn-lg rounded-pill shadow-sm" onclick="updateOrderStatus({{ $order->id }})">
                <i class="bx bx-refresh me-2"></i>Changer le statut
              </button>
              @if($order->payment_status !== 'paid')
              <button type="button" class="btn btn-accent-theme btn-lg rounded-pill shadow-sm" onclick="updatePaymentStatus({{ $order->id }})">
                <i class="bx bx-money me-2"></i>Marquer comme payé
              </button>
              @endif
              <hr class="my-2">
              <div class="text-center">
                <small class="text-muted">
                  <i class="bx bx-info-circle me-1"></i>
                  Actions disponibles selon le statut
                </small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Détails de la commande -->
      <div class="col-md-8">
        <!-- Commerçants impliqués -->
        <div class="admin-card card rounded-3 mb-4">
          <div class="card-header-primary">
            <h5 class="mb-0 fw-bold">
              <i class="bx bx-store me-2"></i>Commerçants impliqués
            </h5>
          </div>
          <div class="card-body p-4">
            @php
              $commerces = $order->items->map(function($item) {
                return $item->product && $item->product->commerce 
                  ? $item->product->commerce 
                  : null;
              })->filter()->unique('id');
            @endphp
            
            @if($commerces->count() > 0)
              <div class="row g-3">
                @foreach($commerces as $commerce)
                  <div class="col-md-6 mb-3">
                    <div class="position-relative bg-theme-beige rounded-3 p-4 border border-2 border-primary border-opacity-25 shadow-sm hover-lift">
                      <!-- Badge type de commerce -->
                      <div class="position-absolute top-0 end-0 translate-middle">
                        <span class="admin-badge admin-badge-warning rounded-pill px-3 py-2 shadow">
                          {{ $commerce->commerceType->full_name ?? 'N/A' }}
                        </span>
                      </div>
                      
                      <div class="d-flex align-items-start gap-3">
                        <div class="position-relative">
                          <img src="{{ $commerce->logo_url }}" 
                               alt="{{ $commerce->name }}" 
                               class="rounded-3 border border-3 border-white shadow" 
                               width="70" 
                               height="70"
                               style="object-fit: cover;">
                          <div class="position-absolute bottom-0 end-0 translate-middle badge rounded-pill bg-theme-orange">
                            <i class="bx bx-store-alt text-white"></i>
                          </div>
                        </div>
                        
                        <div class="flex-grow-1">
                          <h6 class="fw-bold text-theme-dark-blue mb-2">{{ $commerce->name }}</h6>
                          <p class="text-muted small mb-3">
                            <i class="bx bx-map-pin text-theme-orange me-1"></i>
                            {{ $commerce->full_address }}
                          </p>
                          
                          @php
                            $commerceItems = $order->items->filter(function($item) use ($commerce) {
                              return $item->product && $item->product->commerce_id === $commerce->id;
                            });
                            $itemsCount = $commerceItems->sum('quantity');
                            $commerceTotal = $commerceItems->sum('subtotal');
                          @endphp
                          
                          <!-- Statistiques du commerçant -->
                          <div class="row g-2">
                            <div class="col-6">
                              <div class="text-center bg-white rounded-2 p-2 border">
                                <div class="fw-bold text-theme-dark-blue">{{ $itemsCount }}</div>
                                <small class="text-muted">Article(s)</small>
                              </div>
                            </div>
                            <div class="col-6">
                              <div class="text-center bg-white rounded-2 p-2 border">
                                <div class="fw-bold text-theme-orange">{{ number_format($commerceTotal, 0, ',', ' ') }} F</div>
                                <small class="text-muted">Total</small>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            @else
              <div class="admin-empty-state">
                <i class="bx bx-store"></i>
                <h5>Aucun commerçant trouvé</h5>
                <p>Les commerçants apparaîtront ici une fois que les produits seront associés à leurs commerces respectifs</p>
              </div>
            @endif
          </div>
        </div>

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
                                          <th>Commerçant</th>
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
                    <td>
                      <div class="d-flex align-items-center gap-2">
                        <img src="{{ $item->commerce_logo }}" 
                             alt="{{ $item->commerce_name }}" 
                             class="rounded-circle" 
                             width="30" 
                             height="30"
                             style="object-fit: cover;">
                        <div>
                          <strong class="d-block">{{ $item->commerce_name }}</strong>
                          <small class="text-muted">{{ $item->commerce_type_name }}</small>
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
/* Import Google Fonts - Police élégante */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap');

/* Variables CSS pour un design minimaliste */
:root {
  --minimal-font: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
  --minimal-gray: #64748b;
  --minimal-dark: #0f172a;
  --minimal-light: #f8fafc;
  --minimal-border: #e2e8f0;
  --minimal-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
  --minimal-radius: 8px;
  --minimal-text: #334155;
  --minimal-text-muted: #64748b;
}

/* Application de la police globale */
body, .card, .btn, .badge, .table, .modal {
  font-family: var(--minimal-font) !important;
}

/* Réduction générale des tailles de texte */
.display-6 { font-size: 1.75rem !important; }
h2 { font-size: 1.5rem !important; }
h4 { font-size: 1.1rem !important; }
h5 { font-size: 1rem !important; }
h6 { font-size: 0.9rem !important; }
.fs-5 { font-size: 0.9rem !important; }
.fs-4 { font-size: 1rem !important; }

/* Style minimaliste pour les cartes */
.card {
  border: 1px solid var(--minimal-border);
  box-shadow: var(--minimal-shadow);
  border-radius: var(--minimal-radius);
  background: white;
}

.admin-card .card-body {
  padding: 1.25rem !important;
}

/* Header de page minimaliste */
.admin-title-card {
  background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
  border: none;
  margin-bottom: 1.5rem !important;
}

.admin-title-card .card-body {
  padding: 1.5rem !important;
}

.admin-title-card h2 {
  color: #ffffff !important;
  font-weight: 700 !important;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
}

.admin-title-card .text-white {
  color: #ffffff !important;
  font-weight: 600 !important;
}

.admin-title-card .text-white-50 {
  color: rgba(255, 255, 255, 0.8) !important;
  font-weight: 500 !important;
}

/* Badges minimalistes */
.admin-badge {
  font-size: 0.75rem !important;
  font-weight: 600;
  padding: 0.4rem 0.8rem !important;
  border-radius: 20px;
  letter-spacing: 0.02em;
  border: 1px solid transparent;
}

.admin-badge-primary {
  background-color: #3b82f6 !important;
  color: white !important;
}

.admin-badge-success {
  background-color: #10b981 !important;
  color: white !important;
}

.admin-badge-warning {
  background-color: #f59e0b !important;
  color: white !important;
}

.admin-badge-danger {
  background-color: #ef4444 !important;
  color: white !important;
}

/* Boutons minimalistes */
.btn {
  font-size: 0.85rem;
  font-weight: 500;
  padding: 0.5rem 1rem;
  border-radius: var(--minimal-radius);
  letter-spacing: 0.01em;
  transition: all 0.2s ease;
}

.btn-lg {
  font-size: 0.9rem;
  padding: 0.6rem 1.2rem;
}

.btn-admin-primary {
  background: #1e293b;
  border: 1px solid #1e293b;
  color: white !important;
  font-weight: 600;
}

.btn-admin-primary:hover {
  background: #334155;
  border-color: #334155;
  color: white !important;
  transform: translateY(-1px);
  box-shadow: 0 4px 12px rgba(30, 41, 59, 0.25);
}

.btn-outline-light {
  border-color: rgba(255, 255, 255, 0.3) !important;
  color: white !important;
  font-weight: 600;
}

.btn-outline-light:hover {
  background: rgba(255, 255, 255, 0.1) !important;
  border-color: rgba(255, 255, 255, 0.5) !important;
  color: white !important;
}

/* Sections d'information minimalistes */
.bg-theme-beige, .bg-light {
  background-color: var(--minimal-light) !important;
  border: 1px solid var(--minimal-border) !important;
  border-radius: var(--minimal-radius) !important;
}

/* Textes et couleurs minimalistes */
.text-theme-dark-blue, .fw-bold {
  color: var(--minimal-text) !important;
  font-weight: 600 !important;
}

.text-muted, .small {
  color: var(--minimal-text-muted) !important;
  font-size: 0.8rem !important;
}

/* Amélioration de la lisibilité */
.card-body h5, .card-body h6 {
  color: var(--minimal-text) !important;
}

.table th {
  color: var(--minimal-text) !important;
}

.table td {
  color: var(--minimal-text) !important;
}

/* Statistiques minimalistes */
.bg-white {
  border: 1px solid var(--minimal-border) !important;
  border-radius: 6px !important;
  padding: 0.75rem !important;
}

/* Tableau minimaliste */
.table {
  font-size: 0.85rem;
  margin-bottom: 0;
}

.table th {
  font-weight: 600;
  color: var(--minimal-dark);
  border-bottom: 2px solid var(--minimal-border);
  padding: 0.75rem;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.table td {
  padding: 0.75rem;
  border-bottom: 1px solid var(--minimal-border);
  vertical-align: middle;
}

.table-hover tbody tr:hover {
  background-color: var(--minimal-light);
}

/* Images minimalistes */
.rounded, .rounded-circle, .rounded-3 {
  border-radius: var(--minimal-radius) !important;
}

.rounded-circle {
  border-radius: 50% !important;
}

/* Headers de cartes minimalistes */
.card-header-primary, .card-header-accent, .card-header-secondary {
  background: var(--minimal-light) !important;
  border-bottom: 1px solid var(--minimal-border) !important;
  padding: 1rem 1.25rem !important;
  font-size: 0.9rem !important;
}

/* Icônes plus discrètes */
.bx {
  font-size: 1rem !important;
}

.fs-4.bx {
  font-size: 1.1rem !important;
}

/* Suppression des ombres excessives */
.shadow, .shadow-sm {
  box-shadow: var(--minimal-shadow) !important;
}

/* Modals minimalistes */
.modal-content {
  border: 1px solid var(--minimal-border);
  border-radius: var(--minimal-radius);
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.modal-header {
  border-bottom: 1px solid var(--minimal-border);
  padding: 1rem 1.25rem;
}

.modal-title {
  font-size: 1rem;
  font-weight: 600;
  color: var(--minimal-dark);
}

/* Espacement plus compact */
.mb-4 { margin-bottom: 1.5rem !important; }
.mb-3 { margin-bottom: 1rem !important; }
.p-4 { padding: 1.25rem !important; }
.gap-3 { gap: 0.75rem !important; }

/* Responsive minimaliste */
@media (max-width: 768px) {
  .display-6 { font-size: 1.4rem !important; }
  .admin-title-card .card-body { padding: 1rem !important; }
  .card-body { padding: 1rem !important; }
}

/* Animation subtile pour hover */
.hover-lift:hover {
  transform: translateY(-2px);
  transition: transform 0.2s ease;
}

/* État empty plus discret */
.admin-empty-state {
  text-align: center;
  padding: 2rem 1rem;
  color: var(--minimal-gray);
}

.admin-empty-state i {
  font-size: 2rem;
  margin-bottom: 0.5rem;
  opacity: 0.5;
}

.admin-empty-state h5 {
  font-size: 1rem;
  font-weight: 600;
  margin-bottom: 0.5rem;
}

.admin-empty-state p {
  font-size: 0.8rem;
  margin-bottom: 0;
}

/* Améliorations spécifiques pour la lisibilité */
.table strong {
  color: var(--minimal-text) !important;
  font-weight: 700;
}

.bg-theme-orange {
  color: white !important;
  font-weight: 600;
}

.text-theme-orange {
  color: #ea580c !important;
  font-weight: 600;
}

/* Prix et montants */
.h4 {
  color: white !important;
  font-weight: 700 !important;
  font-size: 1.4rem !important;
}

/* Amélioration des contrastes dans les statistiques */
.bg-white .fw-bold {
  color: var(--minimal-text) !important;
  font-weight: 700;
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