{{-- 
    Composant Tableau de Données Réutilisable
    
    Usage:
    @include('admin.components.data-table', [
        'title' => 'Titre du tableau',
        'description' => 'Description',
        'createRoute' => 'route.name',
        'createText' => 'Nouveau Item',
        'items' => $collection,
        'columns' => [
            ['key' => 'name', 'label' => 'Nom', 'type' => 'text'],
            ['key' => 'status', 'label' => 'Statut', 'type' => 'badge'],
            ['key' => 'created_at', 'label' => 'Date', 'type' => 'date']
        ],
        'actions' => ['edit', 'toggle', 'delete'],
        'emptyIcon' => 'bx-store',
        'emptyMessage' => 'Aucun élément trouvé'
    ])
--}}

@php
    $hasItems = $items && $items->count() > 0;
    $showPagination = $items && method_exists($items, 'links');
@endphp

<div class="row">
    <div class="col-12">
        <!-- Section titre -->
        <div class="admin-title-card card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="fw-bold mb-1">{{ $title }}</h4>
                        @if(isset($description))
                            <p class="text-muted mb-0">{{ $description }}</p>
                        @endif
                        @if($showPagination)
                            <p class="text-muted mb-0">{{ $items->total() }} élément(s) au total</p>
                        @endif
                    </div>
                    @if(isset($createRoute))
                        <button type="button" class="btn btn-admin-primary" 
                                @if(isset($modalTarget))
                                    data-bs-toggle="modal" data-bs-target="{{ $modalTarget }}"
                                    onclick="{{ $createCallback ?? 'AdminComponents.initCreateModal(\'' . $modalTarget . '\')' }}"
                                @else
                                    onclick="window.location.href='{{ route($createRoute) }}'"
                                @endif>
                            <i class="bx bx-plus"></i> {{ $createText ?? 'Nouveau' }}
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tableau principal -->
        <div class="admin-card card">
            <div class="card-body p-0">
                <!-- Zone d'alertes -->
                @if(session('success'))
                    <div class="admin-alert alert alert-success alert-dismissible m-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="admin-alert alert alert-danger alert-dismissible m-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($hasItems)
                    <!-- Tableau avec données -->
                    <div class="admin-table table-responsive rounded-3 overflow-hidden">
                        <table class="table table-hover mb-0">
                            <thead class="admin-table-header">
                                <tr>
                                    @foreach($columns as $column)
                                        <th class="border-0 text-white fw-semibold">
                                            {{ $column['label'] }}
                                        </th>
                                    @endforeach
                                    @if(!empty($actions))
                                        <th class="border-0 text-white fw-semibold">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($items as $item)
                                    <tr id="row-{{ $item->id }}" class="admin-table-row">
                                        @foreach($columns as $column)
                                            <td class="py-3">
                                                @switch($column['type'])
                                                    @case('text')
                                                        {{ data_get($item, $column['key']) }}
                                                        @break
                                                    
                                                    @case('badge')
                                                        @if($column['key'] === 'status' || $column['key'] === 'is_active')
                                                            <span class="admin-badge {{ data_get($item, $column['key']) ? 'admin-badge-success' : 'admin-badge-inactive' }}" 
                                                                  id="status-{{ $item->id }}">
                                                                {{ data_get($item, $column['key']) ? 'Actif' : 'Inactif' }}
                                                            </span>
                                                        @else
                                                            <span class="admin-badge admin-badge-success">
                                                                {{ data_get($item, $column['key']) }}
                                                            </span>
                                                        @endif
                                                        @break
                                                    
                                                    @case('date')
                                                        <span class="text-muted">
                                                            {{ optional(data_get($item, $column['key']))->format('d/m/Y') }}
                                                        </span>
                                                        @break
                                                    
                                                    @case('emoji-text')
                                                        <div class="d-flex align-items-center">
                                                            @if(isset($column['emoji_key']))
                                                                <span class="fs-4 me-2">{{ data_get($item, $column['emoji_key']) }}</span>
                                                            @endif
                                                            <strong class="text-dark">{{ data_get($item, $column['key']) }}</strong>
                                                        </div>
                                                        @break
                                                    
                                                    @case('truncate')
                                                        <span class="text-muted">
                                                            {{ Str::limit(data_get($item, $column['key']), $column['limit'] ?? 50) }}
                                                        </span>
                                                        @break
                                                    
                                                    @case('custom')
                                                        @if(isset($column['callback']))
                                                            {!! call_user_func($column['callback'], $item) !!}
                                                        @endif
                                                        @break
                                                    
                                                    @default
                                                        {{ data_get($item, $column['key']) }}
                                                @endswitch
                                            </td>
                                        @endforeach
                                        
                                        @if(!empty($actions))
                                            <td class="py-3">
                                                <div class="d-flex gap-1">
                                                    @foreach($actions as $action)
                                                        @switch($action)
                                                            @case('edit')
                                                                <button type="button" 
                                                                        class="btn btn-sm admin-action-btn admin-btn-edit" 
                                                                        onclick="{{ $editCallback ?? 'editItem' }}({{ $item->id }})"
                                                                        title="Modifier">
                                                                    <i class="bx bx-edit-alt"></i>
                                                                </button>
                                                                @break
                                                            
                                                            @case('toggle')
                                                                @php
                                                                    $isActive = $item->is_active ?? $item->status ?? false;
                                                                @endphp
                                                                <button type="button" 
                                                                        class="btn btn-sm admin-action-btn {{ $isActive ? 'admin-btn-toggle-active' : 'admin-btn-toggle-inactive' }}" 
                                                                        onclick="{{ $toggleCallback ?? 'toggleStatus' }}({{ $item->id }})"
                                                                        title="{{ $isActive ? 'Désactiver' : 'Activer' }}">
                                                                    <i class="bx {{ $isActive ? 'bx-toggle-right' : 'bx-toggle-left' }}"></i>
                                                                </button>
                                                                @break
                                                            
                                                            @case('delete')
                                                                <button type="button" 
                                                                        class="btn btn-sm admin-action-btn admin-btn-delete" 
                                                                        onclick="{{ $deleteCallback ?? 'deleteItem' }}({{ $item->id }})"
                                                                        title="Supprimer">
                                                                    <i class="bx bx-trash"></i>
                                                                </button>
                                                                @break
                                                            
                                                            @case('view')
                                                                <button type="button" 
                                                                        class="btn btn-sm admin-action-btn admin-btn-view" 
                                                                        onclick="{{ $viewCallback ?? 'viewItem' }}({{ $item->id }})"
                                                                        title="Voir">
                                                                    <i class="bx bx-show"></i>
                                                                </button>
                                                                @break
                                                                
                                                            @default
                                                                @if(is_array($action) && isset($action['type']))
                                                                    <button type="button" 
                                                                            class="btn btn-sm admin-action-btn {{ $action['class'] ?? '' }}" 
                                                                            onclick="{{ $action['callback'] }}({{ $item->id }})"
                                                                            title="{{ $action['title'] ?? '' }}">
                                                                        <i class="bx {{ $action['icon'] ?? 'bx-cog' }}"></i>
                                                                    </button>
                                                                @endif
                                                        @endswitch
                                                    @endforeach
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($showPagination)
                        <div class="d-flex justify-content-center p-4 border-top">
                            {{ $items->links() }}
                        </div>
                    @endif
                @else
                    <!-- État vide -->
                    <div class="admin-empty-state">
                        <div class="mb-4">
                            <i class="bx {{ $emptyIcon ?? 'bx-data' }} display-2 text-muted"></i>
                        </div>
                        <h5 class="text-dark mb-2">{{ $emptyTitle ?? 'Aucun élément trouvé' }}</h5>
                        <p class="text-muted mb-4">{{ $emptyMessage ?? 'Créez votre premier élément pour commencer' }}</p>
                        @if(isset($createRoute))
                            <button type="button" 
                                    class="btn btn-admin-primary btn-lg rounded-pill px-4" 
                                    @if(isset($modalTarget))
                                        data-bs-toggle="modal" data-bs-target="{{ $modalTarget }}"
                                        onclick="{{ $createCallback ?? 'AdminComponents.initCreateModal(\'' . $modalTarget . '\')' }}"
                                    @else
                                        onclick="window.location.href='{{ route($createRoute) }}'"
                                    @endif>
                                <i class="bx bx-plus"></i> {{ $createText ?? 'Créer un élément' }}
                            </button>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div> 