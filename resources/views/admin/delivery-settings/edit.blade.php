@extends('admin.layouts.master')

@section('title', 'Modifier les paramètres de livraison')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier les paramètres de livraison</h5>
                </div>
                
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('admin.delivery-settings.update', $deliverySetting) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="delivery_fee_per_commerce" class="form-label">
                                        Frais de livraison par boutique <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('delivery_fee_per_commerce') is-invalid @enderror" 
                                               id="delivery_fee_per_commerce" 
                                               name="delivery_fee_per_commerce" 
                                               value="{{ old('delivery_fee_per_commerce', $deliverySetting->delivery_fee_per_commerce) }}" 
                                               min="0" 
                                               step="50" 
                                               required>
                                        <span class="input-group-text">F</span>
                                    </div>
                                    <div class="form-text">
                                        Montant facturé pour chaque boutique différente dans une commande
                                    </div>
                                    @error('delivery_fee_per_commerce')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="first_order_discount" class="form-label">
                                        Remise première commande <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('first_order_discount') is-invalid @enderror" 
                                               id="first_order_discount" 
                                               name="first_order_discount" 
                                               value="{{ old('first_order_discount', $deliverySetting->first_order_discount) }}" 
                                               min="0" 
                                               step="50" 
                                               required>
                                        <span class="input-group-text">F</span>
                                    </div>
                                    <div class="form-text">
                                        Remise appliquée sur la première commande de chaque utilisateur
                                    </div>
                                    @error('first_order_discount')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="free_delivery_threshold" class="form-label">
                                        Seuil de livraison gratuite
                                    </label>
                                    <div class="input-group">
                                        <input type="number" 
                                               class="form-control @error('free_delivery_threshold') is-invalid @enderror" 
                                               id="free_delivery_threshold" 
                                               name="free_delivery_threshold" 
                                               value="{{ old('free_delivery_threshold', $deliverySetting->free_delivery_threshold) }}" 
                                               min="0" 
                                               step="1000">
                                        <span class="input-group-text">F</span>
                                    </div>
                                    <div class="form-text">
                                        Montant minimum pour bénéficier de la livraison gratuite (0 = désactivé)
                                    </div>
                                    @error('free_delivery_threshold')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" 
                                               type="checkbox" 
                                               id="is_active" 
                                               name="is_active" 
                                               value="1" 
                                               {{ old('is_active', $deliverySetting->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Activer ces paramètres
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Si activé, ces paramètres remplaceront les paramètres actuellement actifs
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="bx bx-info-circle"></i>
                                Informations importantes
                            </h6>
                            <ul class="mb-0">
                                <li>Les frais de livraison sont calculés par boutique différente dans une commande</li>
                                <li>La remise première commande s'applique automatiquement aux nouveaux utilisateurs</li>
                                <li>Le seuil de livraison gratuite est optionnel et peut être désactivé</li>
                                <li>Seuls les paramètres actifs sont utilisés pour les calculs</li>
                            </ul>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.delivery-settings.index') }}" class="btn btn-secondary">
                                <i class="bx bx-arrow-back"></i>
                                Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bx bx-save"></i>
                                Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 