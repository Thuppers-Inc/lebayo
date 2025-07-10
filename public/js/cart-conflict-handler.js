/**
 * Gestionnaire de conflits de commerce dans le panier
 * 
 * Ce script gère les situations où un utilisateur tente d'ajouter un produit
 * d'un commerce différent de ceux déjà présents dans son panier.
 */

class CartConflictHandler {
    constructor() {
        this.modal = null;
        this.createModal();
        this.bindEvents();
    }

    /**
     * Créer la modal pour gérer les conflits
     */
    createModal() {
        const modalHtml = `
            <div id="cart-conflict-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="cartConflictModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="cartConflictModalLabel">
                                <i class="fas fa-exclamation-triangle text-warning"></i>
                                Conflit de commerce détecté
                            </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Fermer">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                <strong>Information :</strong> Pour optimiser la livraison, votre panier ne peut contenir que des produits d'un seul commerce à la fois.
                            </div>
                            
                            <div class="conflict-details">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="commerce-info current-commerce">
                                            <h6><i class="fas fa-shopping-cart"></i> Panier actuel</h6>
                                            <div class="commerce-name"></div>
                                            <div class="commerce-items"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="commerce-info new-commerce">
                                            <h6><i class="fas fa-plus"></i> Nouveau produit</h6>
                                            <div class="commerce-name"></div>
                                            <div class="product-name"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="conflict-message"></div>
                            
                            <div class="options-section">
                                <h6>Que souhaitez-vous faire ?</h6>
                                <div class="option-buttons">
                                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">
                                        <i class="fas fa-times"></i> Annuler
                                    </button>
                                    <button type="button" class="btn btn-warning" id="replace-cart-btn">
                                        <i class="fas fa-sync-alt"></i> Vider le panier et ajouter ce produit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        // Ajouter la modal au DOM
        document.body.insertAdjacentHTML('beforeend', modalHtml);
        this.modal = document.getElementById('cart-conflict-modal');
    }

    /**
     * Lier les événements
     */
    bindEvents() {
        // Intercepter les soumissions de formulaire d'ajout au panier
        document.addEventListener('submit', (e) => {
            if (e.target.matches('form[action*="/cart/add/"]')) {
                e.preventDefault();
                this.handleAddToCart(e.target);
            }
        });

        // Gérer les clics sur les boutons "Ajouter au panier"
        document.addEventListener('click', (e) => {
            if (e.target.matches('button[data-action="add-to-cart"], .add-to-cart-btn')) {
                e.preventDefault();
                this.handleAddToCartButton(e.target);
            }
        });

        // Gérer le bouton de remplacement
        document.addEventListener('click', (e) => {
            if (e.target.matches('#replace-cart-btn')) {
                this.handleReplaceCart();
            }
        });
    }

    /**
     * Gérer la soumission du formulaire d'ajout au panier
     */
    async handleAddToCart(form) {
        try {
            const formData = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok) {
                this.showSuccessMessage(data.message);
                this.updateCartDisplay(data.cart);
            } else if (data.different_commerce) {
                this.showConflictModal(data);
            } else {
                this.showErrorMessage(data.message);
            }
        } catch (error) {
            console.error('Erreur lors de l\'ajout au panier:', error);
            this.showErrorMessage('Une erreur est survenue lors de l\'ajout au panier.');
        }
    }

    /**
     * Gérer les boutons d'ajout au panier
     */
    handleAddToCartButton(button) {
        const productId = button.dataset.productId || button.getAttribute('data-product-id');
        const quantity = button.dataset.quantity || 1;
        
        if (!productId) {
            console.error('ID du produit non trouvé');
            return;
        }

        const form = document.createElement('form');
        form.action = `/cart/add/${productId}`;
        form.method = 'POST';
        
        const quantityInput = document.createElement('input');
        quantityInput.type = 'hidden';
        quantityInput.name = 'quantity';
        quantityInput.value = quantity;
        form.appendChild(quantityInput);

        this.handleAddToCart(form);
    }

    /**
     * Afficher la modal de conflit
     */
    showConflictModal(data) {
        // Remplir les détails du conflit
        this.modal.querySelector('.current-commerce .commerce-name').textContent = data.current_commerce;
        this.modal.querySelector('.current-commerce .commerce-items').textContent = 
            `${data.cart_info.total_items} article(s) - ${data.cart_info.formatted_total}`;
        
        this.modal.querySelector('.new-commerce .commerce-name').textContent = data.new_commerce;
        this.modal.querySelector('.conflict-message').textContent = data.message;

        // Stocker les données pour le remplacement
        this.modal.dataset.replaceUrl = data.actions.replace.url;
        this.modal.dataset.quantity = data.quantity || 1;

        // Afficher la modal
        $(this.modal).modal('show');
    }

    /**
     * Gérer le remplacement du panier
     */
    async handleReplaceCart() {
        try {
            const replaceUrl = this.modal.dataset.replaceUrl;
            const quantity = this.modal.dataset.quantity;

            const formData = new FormData();
            formData.append('quantity', quantity);

            const response = await fetch(replaceUrl, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            const data = await response.json();

            if (response.ok) {
                $(this.modal).modal('hide');
                this.showSuccessMessage(data.message);
                this.updateCartDisplay(data.cart);
            } else {
                this.showErrorMessage(data.message);
            }
        } catch (error) {
            console.error('Erreur lors du remplacement du panier:', error);
            this.showErrorMessage('Une erreur est survenue lors du remplacement du panier.');
        }
    }

    /**
     * Afficher un message de succès
     */
    showSuccessMessage(message) {
        // Vous pouvez adapter cette méthode selon votre système de notifications
        if (window.toastr) {
            toastr.success(message);
        } else {
            alert(message);
        }
    }

    /**
     * Afficher un message d'erreur
     */
    showErrorMessage(message) {
        // Vous pouvez adapter cette méthode selon votre système de notifications
        if (window.toastr) {
            toastr.error(message);
        } else {
            alert(message);
        }
    }

    /**
     * Mettre à jour l'affichage du panier
     */
    updateCartDisplay(cartData) {
        // Mettre à jour le compteur du panier
        const cartCountElements = document.querySelectorAll('.cart-count, [data-cart-count]');
        cartCountElements.forEach(element => {
            element.textContent = cartData.total_items;
        });

        // Mettre à jour le total du panier
        const cartTotalElements = document.querySelectorAll('.cart-total, [data-cart-total]');
        cartTotalElements.forEach(element => {
            element.textContent = cartData.formatted_total;
        });

        // Déclencher un événement personnalisé pour d'autres scripts
        document.dispatchEvent(new CustomEvent('cartUpdated', { detail: cartData }));
    }
}

// Initialiser le gestionnaire de conflits au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    new CartConflictHandler();
});

// Styles CSS pour la modal
const styles = `
<style>
#cart-conflict-modal .modal-content {
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
}

#cart-conflict-modal .modal-header {
    background: linear-gradient(135deg, #ff6b35, #f093fb);
    color: white;
    border-radius: 15px 15px 0 0;
}

#cart-conflict-modal .modal-header .close {
    color: white;
    opacity: 0.8;
}

#cart-conflict-modal .modal-header .close:hover {
    opacity: 1;
}

#cart-conflict-modal .conflict-details {
    margin: 1rem 0;
}

#cart-conflict-modal .commerce-info {
    text-align: center;
    padding: 1rem;
    border-radius: 10px;
    margin-bottom: 1rem;
}

#cart-conflict-modal .current-commerce {
    background: #f8f9fa;
    border: 2px solid #dee2e6;
}

#cart-conflict-modal .new-commerce {
    background: #fff3cd;
    border: 2px solid #ffeaa7;
}

#cart-conflict-modal .commerce-info h6 {
    color: #495057;
    margin-bottom: 0.5rem;
}

#cart-conflict-modal .commerce-name {
    font-weight: bold;
    color: #ff6b35;
    margin-bottom: 0.5rem;
}

#cart-conflict-modal .options-section {
    margin-top: 1.5rem;
}

#cart-conflict-modal .option-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 1rem;
}

#cart-conflict-modal .option-buttons .btn {
    min-width: 180px;
    padding: 0.75rem 1.5rem;
    border-radius: 8px;
    font-weight: 600;
}

#cart-conflict-modal .option-buttons .btn-warning {
    background: linear-gradient(135deg, #ffc107, #ff8c00);
    border: none;
    color: white;
}

#cart-conflict-modal .option-buttons .btn-warning:hover {
    background: linear-gradient(135deg, #ff8c00, #ff6b35);
    transform: translateY(-2px);
}

@media (max-width: 768px) {
    #cart-conflict-modal .option-buttons {
        flex-direction: column;
        align-items: center;
    }
    
    #cart-conflict-modal .option-buttons .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
`;

// Ajouter les styles au document
document.head.insertAdjacentHTML('beforeend', styles); 