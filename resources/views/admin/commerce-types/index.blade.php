@extends('admin.layouts.master')

@section('title', 'Types de Commerce')
@section('description', 'Gestion des types de commerce disponibles sur la plateforme')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Section titre dans cadre blanc arrondi -->
    <div class="card rounded-3 shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="fw-bold mb-1">Types de Commerce</h4>
            <p class="text-muted mb-0">{{ $commerceTypes->total() }} type(s) au total</p>
          </div>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#commerceTypeModal" onclick="openCreateModal()">
            <i class="bx bx-plus"></i> Nouveau Type
          </button>
        </div>
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

        @if($commerceTypes->count() > 0)
          <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table table-hover mb-0">
              <thead class="gradient-header">
                <tr>
                  <th class="border-0 text-white fw-semibold">Type</th>
                  <th class="border-0 text-white fw-semibold">Description</th>
                  <th class="border-0 text-white fw-semibold">Statut</th>
                  <th class="border-0 text-white fw-semibold">Date de création</th>
                  <th class="border-0 text-white fw-semibold">Actions</th>
                </tr>
              </thead>
              <tbody id="commerceTypesTable">
                @foreach($commerceTypes as $type)
                  <tr id="row-{{ $type->id }}" class="table-row-hover">
                    <td class="py-3">
                      <div class="d-flex align-items-center">
                        <span class="fs-4 me-2">{{ $type->emoji }}</span>
                        <strong class="text-dark">{{ $type->name }}</strong>
                      </div>
                    </td>
                    <td class="py-3">
                      <span class="text-muted">{{ Str::limit($type->description, 50) }}</span>
                    </td>
                    <td class="py-3">
                      <span class="badge {{ $type->is_active ? 'bg-success' : 'bg-secondary' }} px-3 py-2" id="status-{{ $type->id }}">
                        {{ $type->is_active ? 'Actif' : 'Inactif' }}
                      </span>
                    </td>
                    <td class="py-3 text-muted">{{ $type->created_at->format('d/m/Y') }}</td>
                    <td class="py-3">
                      <div class="d-flex gap-1">
                        <!-- Bouton Modifier -->
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2" 
                                onclick="editCommerceType({{ $type->id }})" 
                                title="Modifier">
                          <i class="bx bx-edit-alt"></i>
                        </button>
                        
                        <!-- Bouton Toggle Status -->
                        <button type="button" class="btn btn-sm {{ $type->is_active ? 'btn-outline-warning' : 'btn-outline-success' }} rounded-pill px-2" 
                                onclick="toggleStatus({{ $type->id }})" 
                                title="{{ $type->is_active ? 'Désactiver' : 'Activer' }}">
                          <i class="bx {{ $type->is_active ? 'bx-toggle-right' : 'bx-toggle-left' }}"></i>
                        </button>
                        
                        <!-- Bouton Supprimer -->
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" 
                                onclick="deleteCommerceType({{ $type->id }})" 
                                title="Supprimer">
                          <i class="bx bx-trash"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          
          <!-- Pagination -->
          <div class="d-flex justify-content-center p-4 border-top">
            {{ $commerceTypes->links() }}
          </div>
        @else
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="bx bx-store display-2 text-muted"></i>
            </div>
            <h5 class="text-dark mb-2">Aucun type de commerce trouvé</h5>
            <p class="text-muted mb-4">Créez votre premier type de commerce pour commencer</p>
            <button type="button" class="btn btn-primary btn-lg rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#commerceTypeModal" onclick="openCreateModal()">
              <i class="bx bx-plus"></i> Créer un type
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal Créer/Modifier Type de Commerce -->
<div class="modal fade" id="commerceTypeModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Nouveau Type de Commerce</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="commerceTypeForm" method="POST">
        @csrf
        <input type="hidden" id="commerceTypeId" name="id" value="">
        <input type="hidden" id="methodField" name="_method" value="POST">
        
        <div class="modal-body">
          <div class="mb-3">
            <label for="name" class="form-label">Nom du type <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Ex: Restaurants" required>
            <div class="invalid-feedback" id="name-error"></div>
          </div>
          
          <div class="mb-3">
            <label for="emoji" class="form-label">Emoji <span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="emoji" name="emoji" placeholder="🍕" maxlength="10" required>
            <div class="form-text">Un emoji représentatif du type de commerce</div>
            <div class="invalid-feedback" id="emoji-error"></div>
          </div>
          
          <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description du type de commerce..."></textarea>
            <div class="invalid-feedback" id="description-error"></div>
          </div>
          
          <div class="form-check">
            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" checked>
            <label class="form-check-label" for="is_active">
              Actif
            </label>
          </div>
        </div>
        
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary" id="submitBtn">
            <span id="submitText">Créer</span>
            <span id="submitSpinner" class="spinner-border spinner-border-sm d-none" role="status"></span>
          </button>
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

.btn-outline-danger:hover {
  background: linear-gradient(135deg, #D62828 0%, #dc3545 100%);
  border-color: #D62828;
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

/* Style pour les emojis */
.fs-4 {
  filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
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
let currentEditId = null;

function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Nouveau Type de Commerce';
    document.getElementById('submitText').textContent = 'Créer';
    document.getElementById('commerceTypeForm').reset();
    document.getElementById('commerceTypeId').value = '';
    document.getElementById('methodField').value = 'POST';
    document.getElementById('is_active').checked = true;
    currentEditId = null;
    clearErrors();
}

function editCommerceType(id) {
    fetch(`{{ route('admin.commerce-types.index') }}/${id}/edit`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const type = data.commerce_type;
            document.getElementById('modalTitle').textContent = 'Modifier le Type de Commerce';
            document.getElementById('submitText').textContent = 'Modifier';
            document.getElementById('commerceTypeId').value = type.id;
            document.getElementById('methodField').value = 'PUT';
            document.getElementById('name').value = type.name;
            document.getElementById('emoji').value = type.emoji;
            document.getElementById('description').value = type.description || '';
            document.getElementById('is_active').checked = type.is_active;
            currentEditId = id;
            clearErrors();
            
            const modal = new bootstrap.Modal(document.getElementById('commerceTypeModal'));
            modal.show();
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('Erreur lors du chargement des données', 'danger');
    });
}

function toggleStatus(id) {
    if (confirm('Êtes-vous sûr de vouloir changer le statut de ce type de commerce ?')) {
        fetch(`{{ route('admin.commerce-types.index') }}/${id}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const statusBadge = document.getElementById(`status-${id}`);
                statusBadge.textContent = data.is_active ? 'Actif' : 'Inactif';
                statusBadge.className = `badge ${data.is_active ? 'bg-success' : 'bg-secondary'}`;
                showAlert(data.message, 'success');
                
                // Recharger la page pour mettre à jour les actions
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Erreur lors de la modification du statut', 'danger');
        });
    }
}

function deleteCommerceType(id) {
    if (confirm('Êtes-vous sûr de vouloir supprimer ce type de commerce ? Cette action est irréversible.')) {
        fetch(`{{ route('admin.commerce-types.index') }}/${id}`, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById(`row-${id}`).remove();
                showAlert(data.message, 'success');
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showAlert('Erreur lors de la suppression', 'danger');
        });
    }
}

function clearErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
}

function showErrors(errors) {
    clearErrors();
    Object.keys(errors).forEach(field => {
        const input = document.getElementById(field);
        const errorDiv = document.getElementById(`${field}-error`);
        if (input && errorDiv) {
            input.classList.add('is-invalid');
            errorDiv.textContent = errors[field][0];
        }
    });
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

// Gestion du formulaire
document.getElementById('commerceTypeForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const submitSpinner = document.getElementById('submitSpinner');
    
    // Afficher le spinner
    submitBtn.disabled = true;
    submitSpinner.classList.remove('d-none');
    
    const formData = new FormData(this);
    const id = document.getElementById('commerceTypeId').value;
    const method = document.getElementById('methodField').value;
    
    let url = '{{ route("admin.commerce-types.store") }}';
    if (id) {
        url = `{{ route('admin.commerce-types.index') }}/${id}`;
    }
    
    fetch(url, {
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
            const modal = bootstrap.Modal.getInstance(document.getElementById('commerceTypeModal'));
            modal.hide();
            showAlert(data.message, 'success');
            
            // Recharger la page pour afficher les changements
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            showErrors(data.errors);
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('Une erreur est survenue', 'danger');
    })
    .finally(() => {
        // Masquer le spinner
        submitBtn.disabled = false;
        submitSpinner.classList.add('d-none');
    });
});
</script>
@endpush 