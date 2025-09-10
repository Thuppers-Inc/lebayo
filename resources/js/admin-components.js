/**
 * =====================================
 * COMPOSANTS JS RÉUTILISABLES ADMIN
 * =====================================
 */

class AdminComponents {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        this.currentEditId = null;
    }

    /**
     * Affiche une alerte avec auto-dismiss
     */
    showAlert(message, type = 'success', duration = 5000) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} admin-alert alert-dismissible admin-fade-in`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;

        const container = document.querySelector('.card-body') || document.querySelector('.container-xxl');
        container.insertBefore(alertDiv, container.firstChild);

        setTimeout(() => {
            alertDiv.remove();
        }, duration);
    }

    /**
     * Nettoie les erreurs de validation
     */
    clearErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    /**
     * Affiche les erreurs de validation
     */
    showErrors(errors) {
        this.clearErrors();
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const errorDiv = document.getElementById(`${field}-error`);
            if (input && errorDiv) {
                input.classList.add('is-invalid');
                errorDiv.textContent = errors[field][0];
            }
        });
    }

    /**
     * Gestion générique des formulaires AJAX
     */
    handleFormSubmit(formId, {
        successCallback = null,
        errorCallback = null,
        beforeSubmit = null,
        afterSubmit = null
    } = {}) {
        const form = document.getElementById(formId);
        if (!form) return;

        form.addEventListener('submit', async (e) => {
            e.preventDefault();

            const submitBtn = form.querySelector('[type="submit"]');
            const submitText = submitBtn.querySelector('[data-submit-text]') || submitBtn;
            const submitSpinner = submitBtn.querySelector('.spinner-border');

            // Avant soumission
            if (beforeSubmit) beforeSubmit();

            // Désactiver le bouton
            submitBtn.disabled = true;
            if (submitSpinner) submitSpinner.classList.remove('d-none');

            try {
                const formData = new FormData(form);
                const response = await fetch(form.action, {
                    method: form.method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': this.csrfToken,
                        'Accept': 'application/json',
                    },
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    if (successCallback) {
                        successCallback(data);
                    } else {
                        this.showAlert(data.message, 'success');
                        setTimeout(() => window.location.reload(), 1000);
                    }
                } else {
                    if (errorCallback) {
                        errorCallback(data);
                    } else {
                        this.showErrors(data.errors);
                    }
                }
            } catch (error) {
                console.error('Erreur:', error);
                this.showAlert('Une erreur est survenue', 'danger');
            } finally {
                // Après soumission
                if (afterSubmit) afterSubmit();

                // Réactiver le bouton
                submitBtn.disabled = false;
                if (submitSpinner) submitSpinner.classList.add('d-none');
            }
        });
    }

    /**
     * Suppression générique avec confirmation
     */
    async deleteItem(id, url, {
        confirmMessage = 'Êtes-vous sûr de vouloir supprimer cet élément ?',
        successCallback = null,
        errorCallback = null
    } = {}) {
        if (!confirm(confirmMessage)) return;

        try {
            const response = await fetch(`${url}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                if (successCallback) {
                    successCallback(data, id);
                } else {
                    document.getElementById(`row-${id}`)?.remove();
                    this.showAlert(data.message, 'success');
                }
            } else {
                if (errorCallback) {
                    errorCallback(data);
                } else {
                    this.showAlert('Erreur lors de la suppression', 'danger');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showAlert('Erreur lors de la suppression', 'danger');
        }
    }

    /**
     * Toggle de statut générique
     */
    async toggleStatus(id, url, {
        confirmMessage = 'Êtes-vous sûr de vouloir changer le statut ?',
        successCallback = null,
        errorCallback = null
    } = {}) {
        if (!confirm(confirmMessage)) return;

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': this.csrfToken,
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success) {
                if (successCallback) {
                    successCallback(data, id);
                } else {
                    const statusBadge = document.getElementById(`status-${id}`);
                    if (statusBadge) {
                        statusBadge.textContent = data.user.deleted_at ? 'Inactif' : 'Actif';
                        statusBadge.className = `badge bg-${data.user.deleted_at ? 'danger' : 'success'}`;
                    }
                    this.showAlert(data.message, 'success');
                    setTimeout(() => window.location.reload(), 1000);
                }
            } else {
                if (errorCallback) {
                    errorCallback(data);
                } else {
                    this.showAlert('Erreur lors de la modification du statut', 'danger');
                }
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showAlert('Erreur lors de la modification du statut', 'danger');
        }
    }

    /**
     * Chargement de données pour édition
     */
    async loadForEdit(id, url, {
        successCallback = null,
        errorCallback = null
    } = {}) {
        try {
            const response = await fetch(`${url}/${id}/edit`, {
                method: 'GET',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                }
            });

            const data = await response.json();

            if (data.success && successCallback) {
                successCallback(data);
            } else if (errorCallback) {
                errorCallback(data);
            }
        } catch (error) {
            console.error('Erreur:', error);
            this.showAlert('Erreur lors du chargement des données', 'danger');
        }
    }

    /**
     * Initialise un modal de création
     */
    initCreateModal(modalId, {
        title = 'Nouveau',
        submitText = 'Créer',
        resetForm = true
    } = {}) {
        const modal = document.getElementById(modalId);
        if (!modal) return;

        const titleEl = modal.querySelector('.modal-title');
        const submitBtn = modal.querySelector('[data-submit-text]');
        const form = modal.querySelector('form');
        const idField = modal.querySelector('[name="id"]');
        const methodField = modal.querySelector('[name="_method"]');

        if (titleEl) titleEl.textContent = title;
        if (submitBtn) submitBtn.textContent = submitText;
        if (resetForm && form) form.reset();
        if (idField) idField.value = '';
        if (methodField) methodField.value = 'POST';

        this.clearErrors();
        this.currentEditId = null;
    }

    /**
     * Utilitaire pour créer des boutons d'action
     */
    createActionButton(type, onclick, title = '') {
        const icons = {
            edit: 'bx-edit-alt',
            delete: 'bx-trash',
            toggle: 'bx-toggle-right',
            view: 'bx-show'
        };

        const classes = {
            edit: 'admin-action-btn admin-btn-edit',
            delete: 'admin-action-btn admin-btn-delete',
            toggle: 'admin-action-btn admin-btn-toggle-active',
            view: 'admin-action-btn admin-btn-view'
        };

        return `
            <button type="button"
                    class="btn btn-sm ${classes[type] || ''}"
                    onclick="${onclick}"
                    title="${title}">
                <i class="bx ${icons[type] || ''}"></i>
            </button>
        `;
    }

    /**
     * Ajoute des animations aux éléments
     */
    addHoverAnimations() {
        // Animation pour les cartes
        document.querySelectorAll('.admin-card').forEach(card => {
            card.addEventListener('mouseenter', () => {
                card.style.transform = 'translateY(-2px)';
                card.style.boxShadow = '0 1rem 2rem rgba(0, 0, 0, 0.15)';
            });

            card.addEventListener('mouseleave', () => {
                card.style.transform = 'translateY(0)';
                card.style.boxShadow = '0 0.5rem 1rem rgba(0, 0, 0, 0.1)';
            });
        });
    }

    /**
     * Initialisation automatique
     */
    init() {
        this.addHoverAnimations();

        // Fermer automatiquement les alertes après 5 secondes
        document.querySelectorAll('.alert:not(.alert-dismissible)').forEach(alert => {
            setTimeout(() => alert.remove(), 5000);
        });
    }
}

// Instance globale
window.AdminComponents = new AdminComponents();

// Initialisation automatique
document.addEventListener('DOMContentLoaded', () => {
    window.AdminComponents.init();
});

// Fonctions globales pour compatibilité
window.showAlert = (message, type, duration) => window.AdminComponents.showAlert(message, type, duration);
window.clearErrors = () => window.AdminComponents.clearErrors();
window.showErrors = (errors) => window.AdminComponents.showErrors(errors);
