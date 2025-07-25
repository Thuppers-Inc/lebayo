@extends('layouts.app')

@section('title', 'Faire une course - Lebayo')

@section('content')
<div class="errand-page">
    <div class="container">
        <!-- En-tête -->
        <div class="errand-header">
            <div class="back-section">
                <a href="{{ url()->previous() }}" class="back-btn">
                    <span class="back-icon">←</span>
                    Retour
                </a>
            </div>
            
            <div class="errand-title-section">
                <h1 class="errand-title">Faire une course</h1>
                <p class="errand-subtitle">Décrivez votre demande et nous trouverons un livreur pour vous</p>
            </div>
        </div>

        <!-- Formulaire de demande -->
        <div class="errand-form-container">
            <div class="form-card">
                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('errand.store') }}" method="POST" enctype="multipart/form-data" id="errandForm">
                    @csrf
                    
                    <!-- Informations de base -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-info-circle"></i>
                            Informations de base
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title" class="form-label">
                                        Titre de la course <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('title') is-invalid @enderror" 
                                           id="title" 
                                           name="title" 
                                           value="{{ old('title') }}" 
                                           placeholder="Ex: Acheter des médicaments"
                                           required>
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="urgency_level" class="form-label">
                                        Niveau d'urgence <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('urgency_level') is-invalid @enderror" 
                                            id="urgency_level" 
                                            name="urgency_level" 
                                            required>
                                        <option value="">Sélectionner...</option>
                                        <option value="low" {{ old('urgency_level') == 'low' ? 'selected' : '' }}>Faible</option>
                                        <option value="medium" {{ old('urgency_level') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                        <option value="high" {{ old('urgency_level') == 'high' ? 'selected' : '' }}>Élevée</option>
                                        <option value="urgent" {{ old('urgency_level') == 'urgent' ? 'selected' : '' }}>Urgente</option>
                                    </select>
                                    @error('urgency_level')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="description" class="form-label">
                                Description détaillée <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Décrivez précisément ce que vous souhaitez faire..."
                                      required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Adresses -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-map-marker-alt"></i>
                            Adresses
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_address" class="form-label">
                                        Adresse de départ <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('pickup_address') is-invalid @enderror" 
                                              id="pickup_address" 
                                              name="pickup_address" 
                                              rows="3" 
                                              placeholder="Adresse où le livreur doit se rendre..."
                                              required>{{ old('pickup_address') }}</textarea>
                                    @error('pickup_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="delivery_address" class="form-label">
                                        Adresse de livraison <span class="text-danger">*</span>
                                    </label>
                                    <textarea class="form-control @error('delivery_address') is-invalid @enderror" 
                                              id="delivery_address" 
                                              name="delivery_address" 
                                              rows="3" 
                                              placeholder="Adresse où livrer..."
                                              required>{{ old('delivery_address') }}</textarea>
                                    @error('delivery_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations supplémentaires -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-plus-circle"></i>
                            Informations supplémentaires
                        </h3>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estimated_cost" class="form-label">
                                        Coût estimé (F)
                                    </label>
                                    <input type="number" 
                                           class="form-control @error('estimated_cost') is-invalid @enderror" 
                                           id="estimated_cost" 
                                           name="estimated_cost" 
                                           value="{{ old('estimated_cost') }}" 
                                           placeholder="0"
                                           min="0">
                                    @error('estimated_cost')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone" class="form-label">
                                        Téléphone de contact
                                    </label>
                                    <input type="tel" 
                                           class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" 
                                           name="contact_phone" 
                                           value="{{ old('contact_phone') }}" 
                                           placeholder="Votre numéro">
                                    @error('contact_phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="preferred_delivery_time" class="form-label">
                                Heure de livraison préférée
                            </label>
                            <input type="datetime-local" 
                                   class="form-control @error('preferred_delivery_time') is-invalid @enderror" 
                                   id="preferred_delivery_time" 
                                   name="preferred_delivery_time" 
                                   value="{{ old('preferred_delivery_time') }}">
                            @error('preferred_delivery_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="notes" class="form-label">
                                Notes supplémentaires
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="3" 
                                      placeholder="Informations supplémentaires pour le livreur...">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Photo (optionnelle) -->
                    <div class="form-section">
                        <h3 class="section-title">
                            <i class="fas fa-camera"></i>
                            Photo (optionnelle)
                        </h3>
                        
                        <div class="form-group">
                            <label for="photo" class="form-label">
                                Ajouter une photo
                            </label>
                            <input type="file" 
                                   class="form-control @error('photo') is-invalid @enderror" 
                                   id="photo" 
                                   name="photo" 
                                   accept="image/*">
                            <div class="form-text">
                                Formats acceptés : JPEG, PNG, JPG, GIF (max 2MB)
                            </div>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div id="photoPreview" class="photo-preview" style="display: none;">
                            <img id="previewImage" src="" alt="Aperçu" class="preview-image">
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="form-actions">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Envoyer la demande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.errand-page {
    padding: 2rem 0;
    min-height: 70vh;
}

.errand-header {
    margin-bottom: 2rem;
}

.errand-title-section {
    text-align: center;
    margin-top: 1rem;
}

.errand-title {
    font-size: 2.5rem;
    font-weight: 800;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.errand-subtitle {
    color: var(--text-light);
    font-size: 1.1rem;
}

.errand-form-container {
    max-width: 800px;
    margin: 0 auto;
}

.form-card {
    background: white;
    border-radius: var(--border-radius-lg);
    box-shadow: var(--box-shadow);
    padding: 2rem;
}

.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #eee;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-title {
    font-size: 1.3rem;
    font-weight: 700;
    color: var(--text-dark);
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.section-title i {
    color: var(--primary-color);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-label {
    font-weight: 600;
    color: var(--text-dark);
    margin-bottom: 0.5rem;
}

.form-control, .form-select {
    border-radius: var(--border-radius);
    border: 1px solid #ddd;
    padding: 0.75rem;
    transition: var(--transition);
}

.form-control:focus, .form-select:focus {
    border-color: var(--primary-color);
    box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
}

.form-text {
    font-size: 0.875rem;
    color: var(--text-light);
    margin-top: 0.25rem;
}

.photo-preview {
    margin-top: 1rem;
    text-align: center;
}

.preview-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: var(--border-radius);
    box-shadow: var(--box-shadow);
}

.form-actions {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 1px solid #eee;
}

.btn {
    padding: 0.75rem 1.5rem;
    border-radius: var(--border-radius);
    font-weight: 600;
    text-decoration: none;
    transition: var(--transition);
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: var(--box-shadow-lg);
    color: white;
}

.btn-secondary {
    background: #f8f9fa;
    color: var(--text-dark);
    border: 1px solid #ddd;
}

.btn-secondary:hover {
    background: #e9ecef;
    color: var(--text-dark);
}

@media (max-width: 768px) {
    .errand-title {
        font-size: 2rem;
    }
    
    .form-card {
        padding: 1.5rem;
    }
    
    .form-actions {
        flex-direction: column;
        gap: 1rem;
    }
    
    .btn {
        width: 100%;
        justify-content: center;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation de la photo
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                photoPreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            photoPreview.style.display = 'none';
        }
    });
    
    // Validation du formulaire
    const form = document.getElementById('errandForm');
    form.addEventListener('submit', function(e) {
        const title = document.getElementById('title').value.trim();
        const description = document.getElementById('description').value.trim();
        const pickupAddress = document.getElementById('pickup_address').value.trim();
        const deliveryAddress = document.getElementById('delivery_address').value.trim();
        const urgencyLevel = document.getElementById('urgency_level').value;
        
        if (!title || !description || !pickupAddress || !deliveryAddress || !urgencyLevel) {
            e.preventDefault();
            alert('Veuillez remplir tous les champs obligatoires.');
            return false;
        }
        
        if (description.length < 10) {
            e.preventDefault();
            alert('La description doit contenir au moins 10 caractères.');
            return false;
        }
        
        if (pickupAddress.length < 10 || deliveryAddress.length < 10) {
            e.preventDefault();
            alert('Les adresses doivent contenir au moins 10 caractères.');
            return false;
        }
    });
});
</script>
@endpush 