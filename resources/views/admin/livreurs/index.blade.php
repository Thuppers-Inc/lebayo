@extends('admin.layouts.master')

@section('title', 'Livreurs')

@section('content')
<div class="row">
  <div class="col-12">
    <!-- Section titre dans cadre blanc arrondi -->
    <div class="card rounded-3 shadow-sm mb-4">
      <div class="card-body">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <h4 class="fw-bold mb-1">
              <i class="bx bx-cycling me-2"></i>
              Gestion des Livreurs
            </h4>
            <p class="text-muted mb-0">{{ $livreurs->count() }} livreur(s) au total</p>
          </div>
          <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#livreurModal">
            <i class="bx bx-plus"></i> Nouveau Livreur
          </button>
        </div>
      </div>
    </div>

    <div class="card rounded-3 shadow-sm">
      <div class="card-body">
        @if(session('success'))
          <div class="alert alert-success alert-dismissible rounded-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif

        <!-- Filtres et recherche -->
        <div class="row mb-3">
          <div class="col-md-6">
            <div class="input-group">
              <span class="input-group-text"><i class="bx bx-search"></i></span>
              <input type="text" id="searchLivreur" class="form-control" placeholder="Rechercher un livreur...">
            </div>
          </div>
          <div class="col-md-3">
            <select id="statusFilter" class="form-select">
              <option value="">Tous les statuts</option>
              <option value="active">Actifs</option>
              <option value="inactive">Inactifs</option>
            </select>
          </div>
          <div class="col-md-3">
            <select id="sortBy" class="form-select">
              <option value="nom">Trier par nom</option>
              <option value="date">Trier par date</option>
              <option value="ville">Trier par ville</option>
            </select>
          </div>
        </div>

        @if($livreurs->count() > 0)
          <div class="table-responsive rounded-3 overflow-hidden">
            <table class="table table-hover mb-0">
              <thead class="gradient-header">
                <tr>
                  <th class="border-0 text-white fw-semibold">Livreur</th>
                  <th class="border-0 text-white fw-semibold">Contact</th>
                  <th class="border-0 text-white fw-semibold">Localisation</th>
                  <th class="border-0 text-white fw-semibold">Statut</th>
                  <th class="border-0 text-white fw-semibold">Date d'inscription</th>
                  <th class="border-0 text-white fw-semibold">Actions</th>
                </tr>
              </thead>
              <tbody id="livreursTable">
                @foreach($livreurs as $livreur)
                  <tr id="row-{{ $livreur->id }}" class="table-row-hover livreur-row" 
                      data-nom="{{ strtolower($livreur->nom . ' ' . $livreur->prenoms) }}"
                      data-ville="{{ strtolower($livreur->ville) }}"
                      data-status="{{ $livreur->trashed() ? 'inactive' : 'active' }}"
                      data-date="{{ $livreur->created_at->format('Y-m-d') }}">
                    <td class="py-3">
                      <div class="d-flex align-items-center">
                        <img src="{{ $livreur->photo_url }}" 
                             alt="{{ $livreur->full_name }}" 
                             class="rounded-circle me-3"
                             style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                          <strong class="text-dark">{{ $livreur->full_name }}</strong>
                          <br>
                          <small class="text-muted">{{ $livreur->numero_cni ?? 'CNI non renseignée' }}</small>
                        </div>
                      </div>
                    </td>
                    <td class="py-3">
                      <div>
                        <div class="mb-1">
                          <i class="bx bx-envelope me-1 text-muted"></i>
                          <span class="text-dark">{{ $livreur->email }}</span>
                        </div>
                        <div>
                          <i class="bx bx-phone me-1 text-muted"></i>
                          <span class="text-dark">{{ $livreur->formatted_phone }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="py-3">
                      <div>
                        <i class="bx bx-map me-1 text-muted"></i>
                        <span class="text-dark">{{ $livreur->ville }}</span>
                        @if($livreur->commune)
                          <br>
                          <small class="text-muted">{{ $livreur->commune }}</small>
                        @endif
                      </div>
                    </td>
                    <td class="py-3">
                      <span class="badge {{ $livreur->trashed() ? 'bg-secondary' : 'bg-success' }} px-3 py-2" id="status-{{ $livreur->id }}">
                        {{ $livreur->trashed() ? 'Inactif' : 'Actif' }}
                      </span>
                    </td>
                    <td class="py-3 text-muted">{{ $livreur->created_at->format('d/m/Y') }}</td>
                    <td class="py-3">
                      <div class="d-flex gap-1">
                        <!-- Bouton Voir -->
                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill px-2" 
                                onclick="showLivreur({{ $livreur->id }})" 
                                title="Voir les détails">
                          <i class="bx bx-show"></i>
                        </button>
                        
                        <!-- Bouton Modifier -->
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2" 
                                onclick="editLivreur({{ $livreur->id }})" 
                                title="Modifier">
                          <i class="bx bx-edit-alt"></i>
                        </button>
                        
                        <!-- Bouton Toggle Status -->
                        <button type="button" class="btn btn-sm {{ $livreur->trashed() ? 'btn-outline-success' : 'btn-outline-warning' }} rounded-pill px-2" 
                                onclick="toggleStatus({{ $livreur->id }})" 
                                title="{{ $livreur->trashed() ? 'Activer' : 'Désactiver' }}">
                          <i class="bx {{ $livreur->trashed() ? 'bx-toggle-left' : 'bx-toggle-right' }}"></i>
                        </button>
                        
                        <!-- Bouton Supprimer -->
                        <button type="button" class="btn btn-sm btn-outline-danger rounded-pill px-2" 
                                onclick="deleteLivreur({{ $livreur->id }})" 
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
        @else
          <div class="text-center py-5">
            <div class="mb-4">
              <i class="bx bx-cycling display-2 text-muted"></i>
            </div>
            <h5 class="text-dark mb-2">Aucun livreur trouvé</h5>
            <p class="text-muted mb-4">Ajoutez votre premier livreur pour commencer</p>
            <button type="button" class="btn btn-primary btn-lg rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#livreurModal">
              <i class="bx bx-plus"></i> Nouveau Livreur
            </button>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Modal pour créer/modifier un livreur -->
<div class="modal fade" id="livreurModal" tabindex="-1" aria-labelledby="livreurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="livreurModalLabel">Nouveau Livreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="livreurForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <!-- Photo -->
                        <div class="col-12 mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*">
                            <div class="form-text">Formats acceptés : JPEG, PNG, JPG, GIF. Taille max : 2MB</div>
                        </div>
                        
                        <!-- Nom et Prénoms -->
                        <div class="col-md-6 mb-3">
                            <label for="nom" class="form-label">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="prenoms" class="form-label">Prénoms *</label>
                            <input type="text" class="form-control" id="prenoms" name="prenoms" required>
                        </div>
                        
                        <!-- Email -->
                        <div class="col-12 mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <!-- Téléphone -->
                        <div class="col-md-4 mb-3">
                            <label for="indicatif" class="form-label">Indicatif *</label>
                            <select class="form-select" id="indicatif" name="indicatif" required>
                                <option value="+225">+225 (Côte d'Ivoire)</option>
                                <option value="+33">+33 (France)</option>
                                <option value="+226">+226 (Burkina Faso)</option>
                                <option value="+223">+223 (Mali)</option>
                                <option value="+221">+221 (Sénégal)</option>
                            </select>
                        </div>
                        <div class="col-md-8 mb-3">
                            <label for="numero_telephone" class="form-label">Numéro de téléphone *</label>
                            <input type="tel" class="form-control" id="numero_telephone" name="numero_telephone" required>
                        </div>
                        
                        <!-- Ville et Commune -->
                        <div class="col-md-6 mb-3">
                            <label for="ville" class="form-label">Ville *</label>
                            <input type="text" class="form-control" id="ville" name="ville" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="commune" class="form-label">Commune</label>
                            <input type="text" class="form-control" id="commune" name="commune">
                        </div>
                        
                        <!-- Date de naissance -->
                        <div class="col-md-6 mb-3">
                            <label for="date_naissance" class="form-label">Date de naissance</label>
                            <input type="date" class="form-control" id="date_naissance" name="date_naissance">
                        </div>
                        
                        <!-- Numéro CNI -->
                        <div class="col-md-6 mb-3">
                            <label for="numero_cni" class="form-label">Numéro CNI</label>
                            <input type="text" class="form-control" id="numero_cni" name="numero_cni">
                        </div>
                        
                        <!-- Mot de passe -->
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Mot de passe *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe *</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal pour voir les détails -->
<div class="modal fade" id="showLivreurModal" tabindex="-1" aria-labelledby="showLivreurModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="showLivreurModalLabel">Détails du Livreur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="livreurDetails">
                <!-- Contenu chargé dynamiquement -->
            </div>
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

.btn-outline-info:hover {
  background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
  border-color: #17a2b8;
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

/* Style pour les alertes */
.alert {
  border: none;
  box-shadow: 0 0.25rem 0.5rem rgba(0, 0, 0, 0.1);
}

/* Style pour les photos dans le tableau */
.table img {
  border: 2px solid #e9ecef;
  transition: border-color 0.2s ease;
}

.table img:hover {
  border-color: #F77F00;
}
</style>
@endpush

@section('page-script')
<script>
    let editingLivreurId = null;
    
    // Recherche en temps réel
    document.getElementById('searchLivreur').addEventListener('input', function() {
        filterLivreurs();
    });
    
    // Filtre par statut
    document.getElementById('statusFilter').addEventListener('change', function() {
        filterLivreurs();
    });
    
    // Tri
    document.getElementById('sortBy').addEventListener('change', function() {
        sortLivreurs();
    });
    
    function filterLivreurs() {
        const search = document.getElementById('searchLivreur').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const rows = document.querySelectorAll('.livreur-row');
        
        rows.forEach(row => {
            const nom = row.dataset.nom;
            const ville = row.dataset.ville;
            const status = row.dataset.status;
            
            const matchesSearch = nom.includes(search) || ville.includes(search);
            const matchesStatus = !statusFilter || status === statusFilter;
            
            if (matchesSearch && matchesStatus) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
    
    function sortLivreurs() {
        const sortBy = document.getElementById('sortBy').value;
        const tbody = document.getElementById('livreursTable');
        const rows = Array.from(tbody.children);
        
        rows.sort((a, b) => {
            let aValue, bValue;
            
            switch(sortBy) {
                case 'nom':
                    aValue = a.dataset.nom;
                    bValue = b.dataset.nom;
                    break;
                case 'date':
                    aValue = a.dataset.date;
                    bValue = b.dataset.date;
                    break;
                case 'ville':
                    aValue = a.dataset.ville;
                    bValue = b.dataset.ville;
                    break;
                default:
                    return 0;
            }
            
            return aValue.localeCompare(bValue);
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }
    
    // Gestion du formulaire
    document.getElementById('livreurForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const url = editingLivreurId ? 
            `{{ route('admin.livreurs.index') }}/${editingLivreurId}` : 
            `{{ route('admin.livreurs.store') }}`;
        
        if (editingLivreurId) {
            formData.append('_method', 'PUT');
        }
        
        fetch(url, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Une erreur est survenue');
        });
    });
    
    function editLivreur(id) {
        editingLivreurId = id;
        document.getElementById('livreurModalLabel').textContent = 'Modifier le Livreur';
        
        // Charger les données du livreur
        fetch(`{{ route('admin.livreurs.index') }}/${id}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const livreur = data.livreur;
                
                // Remplir le formulaire
                document.getElementById('nom').value = livreur.nom || '';
                document.getElementById('prenoms').value = livreur.prenoms || '';
                document.getElementById('email').value = livreur.email || '';
                document.getElementById('indicatif').value = livreur.indicatif || '+225';
                document.getElementById('numero_telephone').value = livreur.numero_telephone || '';
                document.getElementById('ville').value = livreur.ville || '';
                document.getElementById('commune').value = livreur.commune || '';
                document.getElementById('date_naissance').value = livreur.date_naissance || '';
                document.getElementById('numero_cni').value = livreur.numero_cni || '';
                
                // Rendre les mots de passe optionnels
                document.getElementById('password').required = false;
                document.getElementById('password_confirmation').required = false;
                
                // Afficher le modal
                new bootstrap.Modal(document.getElementById('livreurModal')).show();
            }
        });
    }
    
    function showLivreur(id) {
        // Implémentation pour afficher les détails
        const modal = new bootstrap.Modal(document.getElementById('showLivreurModal'));
        modal.show();
    }
    
    function toggleStatus(id) {
        if (confirm('Êtes-vous sûr de vouloir changer le statut de ce livreur ?')) {
            fetch(`{{ route('admin.livreurs.index') }}/${id}/toggle-status`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const statusBadge = document.getElementById(`status-${id}`);
                    statusBadge.textContent = data.is_active ? 'Actif' : 'Inactif';
                    statusBadge.className = `badge ${data.is_active ? 'bg-success' : 'bg-secondary'} px-3 py-2`;
                    
                    // Recharger pour mettre à jour les icônes des boutons
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                } else {
                    alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
                }
            });
        }
    }
    
    function deleteLivreur(id) {
        if (confirm('Êtes-vous sûr de vouloir supprimer ce livreur ? Cette action est irréversible.')) {
            fetch(`{{ route('admin.livreurs.index') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`row-${id}`).remove();
                    showAlert(data.message, 'success');
                } else {
                    alert('Erreur : ' + (data.message || 'Une erreur est survenue'));
                }
            });
        }
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible rounded-3`;
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
    
    // Réinitialiser le formulaire à la fermeture du modal
    document.getElementById('livreurModal').addEventListener('hidden.bs.modal', function () {
        editingLivreurId = null;
        document.getElementById('livreurModalLabel').textContent = 'Nouveau Livreur';
        document.getElementById('livreurForm').reset();
        document.getElementById('password').required = true;
        document.getElementById('password_confirmation').required = true;
    });
</script>
@endsection 