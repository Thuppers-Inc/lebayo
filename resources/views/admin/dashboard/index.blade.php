@extends('admin.layouts.master')

@section('title', 'Tableau de bord')
@section('description', 'Vue d\'ensemble de votre plateforme Lebayo')

@section('content')
<!-- Section de bienvenue -->
<div class="row">
  <div class="col-12">
    <div class="card card-accent-primary mb-4">
      <div class="card-header card-header-primary">
        <h4 class="mb-0 text-white">
          <i class="bx bx-tachometer me-2"></i>
          Tableau de bord Lebayo
        </h4>
      </div>
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-8">
            <h5 class="mb-3">Bienvenue sur votre plateforme de livraison</h5>
            <p class="text-muted mb-3">
              Votre plateforme compte actuellement <strong>{{ $stats['total_commerces'] }}</strong> commerces partenaires 
              et <strong>{{ $stats['total_products'] }}</strong> produits disponibles.
              <strong>{{ $stats['active_commerces'] }}</strong> commerces sont actuellement actifs.
            </p>
            <div class="d-flex gap-2 flex-wrap">
              <a href="{{ route('admin.commerces.index') }}" class="btn btn-primary">
                <i class="bx bx-store me-1"></i> Gérer les commerces
              </a>
              <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                <i class="bx bx-package me-1"></i> Gérer les produits
              </a>
            </div>
          </div>
          <div class="col-md-4 text-center">
            <div class="text-primary" style="font-size: 6rem; opacity: 0.1;">
              <i class="bx bx-cycling"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Statistiques principales -->
<div class="row">
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <div class="avatar-initial bg-primary rounded">
              <i class="bx bx-store-alt"></i>
            </div>
          </div>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="cardOpt3" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
              <a class="dropdown-item" href="{{ route('admin.commerces.index') }}">Voir tous</a>
              <a class="dropdown-item" href="{{ route('admin.commerces.create') }}">Ajouter nouveau</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Commerces</span>
        <h3 class="card-title mb-2">{{ $stats['total_commerces'] }}</h3>
        <small class="text-success fw-semibold">
          <i class="bx bx-up-arrow-alt"></i> 
          {{ $stats['active_commerces'] }} actifs
        </small>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <div class="avatar-initial bg-theme-orange rounded">
              <i class="bx bx-package"></i>
            </div>
          </div>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt1">
              <a class="dropdown-item" href="{{ route('admin.products.index') }}">Voir tous</a>
              <a class="dropdown-item" href="{{ route('admin.products.create') }}">Ajouter nouveau</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Produits</span>
        <h3 class="card-title mb-2">{{ $stats['total_products'] }}</h3>
        <small class="text-info fw-semibold">
          <i class="bx bx-star"></i> 
          {{ $stats['featured_products'] }} mis en avant
        </small>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <div class="avatar-initial bg-theme-yellow rounded">
              <i class="bx bx-category"></i>
            </div>
          </div>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="cardOpt2" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt2">
              <a class="dropdown-item" href="{{ route('admin.categories.index') }}">Voir toutes</a>
              <a class="dropdown-item" href="{{ route('admin.categories.create') }}">Ajouter nouvelle</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Catégories</span>
        <h3 class="card-title mb-2">{{ $stats['total_categories'] }}</h3>
        <small class="text-success fw-semibold">
          <i class="bx bx-check-circle"></i> 
          Toutes actives
        </small>
      </div>
    </div>
  </div>
  
  <div class="col-lg-3 col-md-6 col-sm-6 mb-4">
    <div class="card h-100">
      <div class="card-body">
        <div class="card-title d-flex align-items-start justify-content-between">
          <div class="avatar flex-shrink-0">
            <div class="avatar-initial bg-theme-red rounded">
              <i class="bx bx-user"></i>
            </div>
          </div>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="bx bx-dots-vertical-rounded"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
              <a class="dropdown-item" href="{{ route('admin.livreurs.index') }}">Voir tous</a>
              <a class="dropdown-item" href="{{ route('admin.livreurs.create') }}">Ajouter nouveau</a>
            </div>
          </div>
        </div>
        <span class="fw-semibold d-block mb-1">Utilisateurs</span>
        <h3 class="card-title mb-2">{{ $stats['total_users'] }}</h3>
        <small class="text-info fw-semibold">
          <i class="bx bx-trending-up"></i> 
          Inscrits
        </small>
      </div>
    </div>
  </div>
</div>

<!-- Activité récente -->
<div class="row">
  <!-- Commerces récents -->
  <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Commerces Récents</h5>
        </div>
        <a href="{{ route('admin.commerces.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $stats['total_commerces'] }}</h2>
            <span>Total</span>
          </div>
          <div id="commerceChart"></div>
        </div>
        <ul class="p-0 m-0">
          @forelse($recent_commerces as $commerce)
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                @if($commerce->logo_url)
                  <img src="{{ $commerce->logo_url }}" alt="{{ $commerce->name }}" class="rounded-circle" />
                @else
                  <div class="avatar-initial bg-label-primary rounded-circle">
                    {{ substr($commerce->name, 0, 1) }}
                  </div>
                @endif
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-1">{{ $commerce->name }}</h6>
                  <small class="text-muted d-block mb-1">{{ $commerce->commerceType->name ?? 'Type non défini' }}</small>
                  <small class="text-muted">{{ $commerce->city }}</small>
                </div>
                <div class="user-progress">
                  @if($commerce->is_active)
                    <span class="badge bg-label-success">Actif</span>
                  @else
                    <span class="badge bg-label-secondary">Inactif</span>
                  @endif
                </div>
              </div>
            </li>
          @empty
            <li class="text-center text-muted py-4">
              <i class="bx bx-store bx-lg d-block mb-2"></i>
              Aucun commerce disponible
            </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
  
  <!-- Produits récents -->
  <div class="col-md-6 col-lg-4 order-1 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between pb-0">
        <div class="card-title mb-0">
          <h5 class="m-0 me-2">Produits Récents</h5>
        </div>
        <a href="{{ route('admin.products.index') }}" class="btn btn-sm btn-outline-primary">Voir tous</a>
      </div>
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
          <div class="d-flex flex-column align-items-center gap-1">
            <h2 class="mb-2">{{ $stats['total_products'] }}</h2>
            <span>Total</span>
          </div>
          <div id="productChart"></div>
        </div>
        <ul class="p-0 m-0">
          @forelse($recent_products as $product)
            <li class="d-flex mb-4 pb-1">
              <div class="avatar flex-shrink-0 me-3">
                @if($product->image_url)
                  <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded" />
                @else
                  <div class="avatar-initial bg-label-warning rounded">
                    <i class="bx bx-package"></i>
                  </div>
                @endif
              </div>
              <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                <div class="me-2">
                  <h6 class="mb-1">{{ $product->name }}</h6>
                  <small class="text-muted d-block mb-1">{{ $product->commerce->name ?? 'Commerce non défini' }}</small>
                  @if($product->is_featured)
                    <small class="text-warning">
                      <i class="bx bx-star"></i> Mis en avant
                    </small>
                  @endif
                </div>
                <div class="user-progress">
                  <span class="fw-semibold">{{ $product->formatted_price }}</span>
                </div>
              </div>
            </li>
          @empty
            <li class="text-center text-muted py-4">
              <i class="bx bx-package bx-lg d-block mb-2"></i>
              Aucun produit disponible
            </li>
          @endforelse
        </ul>
      </div>
    </div>
  </div>
  
  <!-- Graphique des statistiques -->
  <div class="col-lg-4 col-md-12 order-2 mb-4">
    <div class="card h-100">
      <div class="card-header d-flex align-items-center justify-content-between">
        <h5 class="card-title m-0 me-2">Statistiques</h5>
        <div class="dropdown">
          <button class="btn p-0" type="button" id="statsDropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="bx bx-dots-vertical-rounded"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-end" aria-labelledby="statsDropdown">
            <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
            <a class="dropdown-item" href="javascript:void(0);">Partager</a>
          </div>
        </div>
      </div>
      <div class="card-body">
        <div class="d-flex align-items-center mb-3">
          <div class="avatar flex-shrink-0 me-3">
            <div class="avatar-initial bg-primary rounded-circle d-flex align-items-center justify-content-center">
              <i class="bx bx-trending-up"></i>
            </div>
          </div>
          <div>
            <h6 class="mb-1">Vue d'ensemble</h6>
            <small class="text-muted">Données en temps réel</small>
          </div>
        </div>
        <div id="statsChart"></div>
        <div class="pt-3">
          <div class="d-flex justify-content-between mb-2">
            <span>Commerces actifs</span>
            <span class="fw-semibold">{{ $stats['active_commerces'] }}/{{ $stats['total_commerces'] }}</span>
          </div>
          <div class="d-flex justify-content-between mb-2">
            <span>Produits disponibles</span>
            <span class="fw-semibold">{{ $stats['available_products'] }}/{{ $stats['total_products'] }}</span>
          </div>
          <div class="d-flex justify-content-between">
            <span>Types de commerce</span>
            <span class="fw-semibold">{{ $stats['total_commerce_types'] }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Actions rapides -->
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header">
        <h5 class="card-title mb-0">Actions Rapides</h5>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-md-2 col-sm-4 col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="avatar me-3">
                <div class="avatar-initial bg-label-primary rounded">
                  <i class="bx bx-plus"></i>
                </div>
              </div>
              <div>
                <a href="{{ route('admin.commerces.create') }}" class="stretched-link"></a>
                <h6 class="mb-0">Nouveau Commerce</h6>
                <small class="text-muted">Ajouter un partenaire</small>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-4 col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="avatar me-3">
                <div class="avatar-initial bg-label-warning rounded">
                  <i class="bx bx-package"></i>
                </div>
              </div>
              <div>
                <a href="{{ route('admin.products.create') }}" class="stretched-link"></a>
                <h6 class="mb-0">Nouveau Produit</h6>
                <small class="text-muted">Créer un produit</small>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-4 col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="avatar me-3">
                <div class="avatar-initial bg-label-info rounded">
                  <i class="bx bx-category"></i>
                </div>
              </div>
              <div>
                <a href="{{ route('admin.categories.create') }}" class="stretched-link"></a>
                <h6 class="mb-0">Nouvelle Catégorie</h6>
                <small class="text-muted">Organiser les produits</small>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-4 col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="avatar me-3">
                <div class="avatar-initial bg-label-success rounded">
                  <i class="bx bx-cycling"></i>
                </div>
              </div>
              <div>
                <a href="{{ route('admin.livreurs.create') }}" class="stretched-link"></a>
                <h6 class="mb-0">Nouveau Livreur</h6>
                <small class="text-muted">Inscrire un livreur</small>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-4 col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="avatar me-3">
                <div class="avatar-initial bg-label-secondary rounded">
                  <i class="bx bx-grid-alt"></i>
                </div>
              </div>
              <div>
                <a href="{{ route('admin.commerce-types.create') }}" class="stretched-link"></a>
                <h6 class="mb-0">Type de Commerce</h6>
                <small class="text-muted">Définir un type</small>
              </div>
            </div>
          </div>
          <div class="col-md-2 col-sm-4 col-6 mb-3">
            <div class="d-flex align-items-center">
              <div class="avatar me-3">
                <div class="avatar-initial bg-label-dark rounded">
                  <i class="bx bx-cog"></i>
                </div>
              </div>
              <div>
                <a href="#" class="stretched-link"></a>
                <h6 class="mb-0">Paramètres</h6>
                <small class="text-muted">Configuration</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
// Données pour les graphiques
window.commerceData = [{{ $stats['active_commerces'] }}, {{ $stats['total_commerces'] - $stats['active_commerces'] }}];
window.productData = [{{ $stats['available_products'] }}, {{ $stats['total_products'] - $stats['available_products'] }}];
window.statsData = [{{ $stats['total_commerces'] }}, {{ $stats['total_products'] }}, {{ $stats['total_categories'] }}, {{ $stats['total_users'] }}];

// Données supplémentaires pour le dashboard
window.dashboardData = {
  stats: @json($stats),
  recent_commerces: @json($recent_commerces),
  recent_products: @json($recent_products)
};
</script>
@endpush
