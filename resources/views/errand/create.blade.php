@extends('layouts.app')

@section('title', 'Faire une course - Lebayo')

@section('content')
<div class="errand-page">
    <!-- En-tête mobile-first -->
    <div class="errand-header">
        <div class="container">
            <a href="{{ url()->previous() }}" class="back-btn">
                <i class="fas fa-arrow-left"></i>
                <span>Retour</span>
            </a>
            <div class="errand-title-section">
                <h1 class="errand-title">Faire une course</h1>
                <p class="errand-subtitle">Décrivez votre demande et nous trouverons un livreur pour vous</p>
            </div>
        </div>
    </div>

    <div class="container">

        <!-- Formulaire de demande -->
        <div class="errand-form-container">
            @if($errors->any())
                <div class="alert alert-danger">
                    <div class="alert-icon">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="alert-content">
                        <strong>Erreurs détectées :</strong>
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <div class="form-card">

                <form action="{{ route('errand.store') }}" method="POST" enctype="multipart/form-data" id="errandForm">
                    @csrf

                    <!-- Informations de base -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h3 class="section-title">Informations de base</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="title" class="form-label">
                                    <i class="fas fa-heading"></i>
                                    Titre de la course <span class="required">*</span>
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

                            <div class="form-group">
                                <label for="urgency_level" class="form-label">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Niveau d'urgence <span class="required">*</span>
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

                        <div class="form-group">
                            <label for="description" class="form-label">
                                <i class="fas fa-align-left"></i>
                                Description détaillée <span class="required">*</span>
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
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <h3 class="section-title">Adresses</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="pickup_address" class="form-label">
                                    <i class="fas fa-location-dot"></i>
                                    Adresse de départ <span class="required">*</span>
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

                            <div class="form-group">
                                <label for="delivery_address" class="form-label">
                                    <i class="fas fa-truck"></i>
                                    Adresse de livraison <span class="required">*</span>
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

                    <!-- Informations supplémentaires -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-plus-circle"></i>
                            </div>
                            <h3 class="section-title">Informations supplémentaires</h3>
                        </div>

                        <div class="form-grid">
                            <div class="form-group">
                                <label for="estimated_cost" class="form-label">
                                    <i class="fas fa-money-bill-wave"></i>
                                    Coût estimé (FCFA)
                                </label>
                                <div class="input-wrapper">
                                    <input type="number"
                                           class="form-control @error('estimated_cost') is-invalid @enderror"
                                           id="estimated_cost"
                                           name="estimated_cost"
                                           value="{{ old('estimated_cost') }}"
                                           placeholder="0"
                                           min="0">
                                </div>
                                <div id="urgent-cost-info" class="urgent-cost-badge" style="display: none;">
                                    <i class="fas fa-info-circle"></i>
                                    <span>Montant fixe pour les courses urgentes : <strong>1000 FCFA</strong></span>
                                </div>
                                @error('estimated_cost')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="contact_phone" class="form-label">
                                    <i class="fas fa-phone"></i>
                                    Téléphone de contact
                                </label>
                                <input type="tel"
                                       class="form-control @error('contact_phone') is-invalid @enderror"
                                       id="contact_phone"
                                       name="contact_phone"
                                       value="{{ old('contact_phone') }}"
                                       placeholder="+225 XX XX XX XX XX">
                                @error('contact_phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="preferred_delivery_time" class="form-label">
                                <i class="fas fa-clock"></i>
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
                                <i class="fas fa-sticky-note"></i>
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
                        <div class="section-header">
                            <div class="section-icon">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h3 class="section-title">Photo (optionnelle)</h3>
                        </div>

                        <div class="form-group">
                            <label for="photo" class="file-upload-label">
                                <div class="file-upload-area" id="fileUploadArea">
                                    <i class="fas fa-cloud-upload-alt"></i>
                                    <span class="file-upload-text">Cliquez pour ajouter une photo</span>
                                    <span class="file-upload-hint">ou glissez-déposez ici</span>
                                    <span class="file-upload-formats">JPEG, PNG, JPG, GIF (max 2MB)</span>
                                </div>
                                <input type="file"
                                       class="file-input @error('photo') is-invalid @enderror"
                                       id="photo"
                                       name="photo"
                                       accept="image/*"
                                       hidden>
                            </label>
                            @error('photo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="photoPreview" class="photo-preview" style="display: none;">
                            <div class="photo-preview-wrapper">
                                <img id="previewImage" src="" alt="Aperçu" class="preview-image">
                                <button type="button" class="remove-photo-btn" id="removePhoto">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="form-actions">
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i>
                            <span>Annuler</span>
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            <span>Envoyer la demande</span>
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
/* ===== MOBILE-FIRST BASE STYLES ===== */
.errand-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f5f7fa 0%, #e8ecf1 100%);
    padding-bottom: 2rem;
}

/* ===== HEADER MOBILE-FIRST ===== */
.errand-header {
    background: white;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 100;
    margin-bottom: 1.5rem;
}

.errand-header .container {
    padding: 1rem 1.25rem;
}

.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--text-dark, #333);
    text-decoration: none;
    font-weight: 500;
    padding: 0.5rem 0;
    margin-bottom: 1rem;
    transition: all 0.3s ease;
}

.back-btn i {
    font-size: 1.1rem;
}

.back-btn:hover {
    color: var(--primary-color, #ff6b35);
    transform: translateX(-3px);
}

.errand-title-section {
    text-align: center;
}

.errand-title {
    font-size: 1.75rem;
    font-weight: 800;
    color: var(--text-dark, #1a1a1a);
    margin-bottom: 0.5rem;
    line-height: 1.2;
}

.errand-subtitle {
    color: var(--text-light, #666);
    font-size: 0.95rem;
    line-height: 1.5;
}

/* ===== FORM CONTAINER ===== */
.errand-form-container {
    max-width: 100%;
    margin: 0 auto;
    padding: 0 1rem;
}

/* ===== ALERT ===== */
.alert {
    background: #fee;
    border-left: 4px solid #dc3545;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
}

.alert-icon {
    color: #dc3545;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.alert-content {
    flex: 1;
}

.alert-content strong {
    display: block;
    margin-bottom: 0.5rem;
    color: #721c24;
}

.alert-content ul {
    margin: 0;
    padding-left: 1.25rem;
    color: #721c24;
}

/* ===== FORM CARD ===== */
.form-card {
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    padding: 1.5rem;
    margin-bottom: 1.5rem;
}

/* ===== FORM SECTIONS ===== */
.form-section {
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #f0f0f0;
}

.form-section:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.section-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.section-icon {
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, var(--primary-color, #ff6b35), var(--secondary-color, #f7931e));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.1rem;
    flex-shrink: 0;
}

.section-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--text-dark, #1a1a1a);
    margin: 0;
}

/* ===== FORM GRID (MOBILE-FIRST) ===== */
.form-grid {
    display: grid;
    grid-template-columns: 1fr;
    gap: 1.25rem;
}

/* ===== FORM GROUPS ===== */
.form-group {
    margin-bottom: 1.25rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-weight: 600;
    color: var(--text-dark, #333);
    margin-bottom: 0.5rem;
    font-size: 0.95rem;
}

.form-label i {
    color: var(--primary-color, #ff6b35);
    font-size: 0.9rem;
}

.required {
    color: #dc3545;
    font-weight: 700;
}

/* ===== FORM CONTROLS ===== */
.form-control,
.form-select {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e0e0e0;
    border-radius: 10px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    font-family: inherit;
}

.form-control:focus,
.form-select:focus {
    outline: none;
    border-color: var(--primary-color, #ff6b35);
    box-shadow: 0 0 0 3px rgba(255, 107, 53, 0.1);
}

.form-control:disabled,
.form-control[readonly],
.form-control.disabled {
    background: #f8f9fa;
    cursor: not-allowed;
    opacity: 0.7;
}

textarea.form-control {
    resize: vertical;
    min-height: 100px;
}

/* ===== URGENT COST BADGE ===== */
.urgent-cost-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    background: linear-gradient(135deg, #fff3e0, #ffe0b2);
    border: 2px solid #ff9800;
    border-radius: 10px;
    padding: 0.75rem 1rem;
    margin-top: 0.75rem;
    color: #e65100;
    font-size: 0.9rem;
}

.urgent-cost-badge i {
    color: #ff9800;
    font-size: 1.1rem;
}

/* ===== FILE UPLOAD ===== */
.file-upload-label {
    display: block;
    cursor: pointer;
}

.file-upload-area {
    border: 2px dashed #d0d0d0;
    border-radius: 12px;
    padding: 2rem 1.5rem;
    text-align: center;
    transition: all 0.3s ease;
    background: #fafafa;
}

.file-upload-area:hover {
    border-color: var(--primary-color, #ff6b35);
    background: #fff5f0;
}

.file-upload-area i {
    font-size: 2.5rem;
    color: var(--primary-color, #ff6b35);
    margin-bottom: 0.75rem;
    display: block;
}

.file-upload-text {
    display: block;
    font-weight: 600;
    color: var(--text-dark, #333);
    margin-bottom: 0.25rem;
    font-size: 1rem;
}

.file-upload-hint {
    display: block;
    color: var(--text-light, #666);
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.file-upload-formats {
    display: block;
    color: var(--text-light, #999);
    font-size: 0.8rem;
}

.file-upload-area.dragover {
    border-color: var(--primary-color, #ff6b35);
    background: #fff5f0;
    transform: scale(1.02);
}

/* ===== PHOTO PREVIEW ===== */
.photo-preview {
    margin-top: 1.25rem;
}

.photo-preview-wrapper {
    position: relative;
    display: inline-block;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

.preview-image {
    display: block;
    max-width: 100%;
    max-height: 300px;
    width: auto;
    height: auto;
}

.remove-photo-btn {
    position: absolute;
    top: 0.5rem;
    right: 0.5rem;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
}

.remove-photo-btn:hover {
    background: #dc3545;
    transform: scale(1.1);
}

/* ===== FORM ACTIONS ===== */
.form-actions {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    margin-top: 2rem;
    padding-top: 2rem;
    border-top: 2px solid #f0f0f0;
}

.btn {
    padding: 1rem 1.5rem;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    border: none;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    font-size: 1rem;
    width: 100%;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary-color, #ff6b35), var(--secondary-color, #f7931e));
    color: white;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 16px rgba(255, 107, 53, 0.4);
    color: white;
}

.btn-primary:active {
    transform: translateY(0);
}

.btn-secondary {
    background: white;
    color: var(--text-dark, #333);
    border: 2px solid #e0e0e0;
}

.btn-secondary:hover {
    background: #f8f9fa;
    border-color: #d0d0d0;
    color: var(--text-dark, #333);
}

/* ===== INVALID FEEDBACK ===== */
.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 0.5rem;
}

.is-invalid {
    border-color: #dc3545;
}

/* ===== TABLET STYLES (768px+) ===== */
@media (min-width: 768px) {
    .errand-header .container {
        padding: 1.5rem 2rem;
    }

    .errand-title {
        font-size: 2.25rem;
    }

    .errand-subtitle {
        font-size: 1.1rem;
    }

    .errand-form-container {
        max-width: 700px;
        padding: 0 2rem;
    }

    .form-card {
        padding: 2rem;
    }

    .form-grid {
        grid-template-columns: repeat(2, 1fr);
    }

    .form-actions {
        flex-direction: row;
        justify-content: space-between;
    }

    .btn {
        width: auto;
        min-width: 180px;
    }

    .section-title {
        font-size: 1.4rem;
    }
}

/* ===== DESKTOP STYLES (1024px+) ===== */
@media (min-width: 1024px) {
    .errand-form-container {
        max-width: 850px;
    }

    .form-card {
        padding: 2.5rem;
    }

    .errand-title {
        font-size: 2.5rem;
    }

    .section-icon {
        width: 48px;
        height: 48px;
        font-size: 1.25rem;
    }
}

/* ===== LARGE DESKTOP (1440px+) ===== */
@media (min-width: 1440px) {
    .errand-form-container {
        max-width: 950px;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Prévisualisation de la photo avec drag & drop
    const photoInput = document.getElementById('photo');
    const photoPreview = document.getElementById('photoPreview');
    const previewImage = document.getElementById('previewImage');
    const fileUploadArea = document.getElementById('fileUploadArea');
    const removePhotoBtn = document.getElementById('removePhoto');

    function handleFile(file) {
        if (file && file.type.startsWith('image/')) {
            // Vérifier la taille (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Le fichier est trop volumineux. Taille maximale : 2MB');
                return;
            }

            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                photoPreview.style.display = 'block';
                fileUploadArea.style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            alert('Veuillez sélectionner une image valide.');
        }
    }

    // Gestion du changement de fichier
    photoInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            handleFile(file);
        }
    });

    // Drag & Drop
    fileUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        fileUploadArea.classList.add('dragover');
    });

    fileUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
    });

    fileUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        fileUploadArea.classList.remove('dragover');
        const file = e.dataTransfer.files[0];
        if (file) {
            // Créer un nouveau DataTransfer pour assigner le fichier
            const dataTransfer = new DataTransfer();
            dataTransfer.items.add(file);
            photoInput.files = dataTransfer.files;
            handleFile(file);
        }
    });

    // Clic sur la zone d'upload
    fileUploadArea.addEventListener('click', function() {
        photoInput.click();
    });

    // Supprimer la photo
    removePhotoBtn.addEventListener('click', function() {
        photoInput.value = '';
        photoPreview.style.display = 'none';
        fileUploadArea.style.display = 'block';
    });

    // Gestion du montant fixe pour les courses urgentes
    const urgencyLevelSelect = document.getElementById('urgency_level');
    const estimatedCostInput = document.getElementById('estimated_cost');
    const urgentCostInfo = document.getElementById('urgent-cost-info');

    function handleUrgencyLevelChange() {
        const urgencyLevel = urgencyLevelSelect.value;

        if (urgencyLevel === 'urgent') {
            // Fixer le montant à 1000 FCFA pour les courses urgentes
            estimatedCostInput.value = 1000;
            estimatedCostInput.readOnly = true;
            estimatedCostInput.classList.add('disabled');
            urgentCostInfo.style.display = 'flex';
        } else {
            // Réactiver le champ pour les autres niveaux d'urgence
            estimatedCostInput.readOnly = false;
            estimatedCostInput.classList.remove('disabled');
            urgentCostInfo.style.display = 'none';

            // Si le montant était à 1000 (fixé précédemment), le réinitialiser
            if (estimatedCostInput.value === '1000' && !urgencyLevelSelect.dataset.initialUrgent) {
                estimatedCostInput.value = '';
            }
        }
    }

    // Vérifier l'état initial au chargement de la page
    if (urgencyLevelSelect.value === 'urgent') {
        handleUrgencyLevelChange();
    }

    // Écouter les changements du niveau d'urgence
    urgencyLevelSelect.addEventListener('change', handleUrgencyLevelChange);

    // Empêcher la modification manuelle du montant si l'urgence est urgente
    estimatedCostInput.addEventListener('input', function() {
        if (urgencyLevelSelect.value === 'urgent') {
            this.value = 1000;
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

        // S'assurer que le montant est fixé à 1000 pour les courses urgentes
        if (urgencyLevel === 'urgent') {
            estimatedCostInput.value = 1000;
        }
    });
});
</script>
@endpush
