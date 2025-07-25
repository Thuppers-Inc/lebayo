@extends('admin.layouts.master')

@section('title', 'Paramètres de livraison')

@section('content')
<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Paramètres de livraison</h5>
                    <a href="{{ route('admin.delivery-settings.create') }}" class="btn btn-primary">
                        <i class="bx bx-plus"></i>
                        Nouveaux paramètres
                    </a>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Paramètres actifs -->
                    @if($activeSettings)
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bx bx-check-circle"></i>
                            Paramètres actifs
                        </h6>
                        <div class="row mt-2">
                            <div class="col-md-3">
                                <strong>Frais par boutique :</strong><br>
                                {{ $activeSettings->formatted_delivery_fee }}
                            </div>
                            <div class="col-md-3">
                                <strong>Remise première commande :</strong><br>
                                {{ $activeSettings->formatted_discount }}
                            </div>
                            <div class="col-md-3">
                                <strong>Seuil livraison gratuite :</strong><br>
                                {{ $activeSettings->formatted_threshold }}
                            </div>
                            <div class="col-md-3">
                                <strong>Statut :</strong><br>
                                <span class="badge bg-success">Actif</span>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Liste des paramètres -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Frais par boutique</th>
                                    <th>Remise première commande</th>
                                    <th>Seuil livraison gratuite</th>
                                    <th>Statut</th>
                                    <th>Créé le</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($settings as $setting)
                                <tr>
                                    <td>
                                        <strong>{{ $setting->formatted_delivery_fee }}</strong>
                                    </td>
                                    <td>
                                        <strong>{{ $setting->formatted_discount }}</strong>
                                    </td>
                                    <td>
                                        {{ $setting->formatted_threshold }}
                                    </td>
                                    <td>
                                        @if($setting->is_active)
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-secondary">Inactif</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $setting->created_at->format('d/m/Y H:i') }}
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            @if(!$setting->is_active)
                                                <form action="{{ route('admin.delivery-settings.activate', $setting) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" 
                                                            onclick="return confirm('Activer ces paramètres ?')">
                                                        <i class="bx bx-check"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="{{ route('admin.delivery-settings.edit', $setting) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            
                                            @if($settings->count() > 1)
                                                <form action="{{ route('admin.delivery-settings.destroy', $setting) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" 
                                                            onclick="return confirm('Supprimer ces paramètres ?')">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">
                                        <p class="text-muted">Aucun paramètre de livraison configuré</p>
                                        <a href="{{ route('admin.delivery-settings.create') }}" class="btn btn-primary">
                                            Créer les premiers paramètres
                                        </a>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 