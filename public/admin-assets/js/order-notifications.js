/**
 * Système de notifications temps réel pour les nouvelles commandes
 *
 * Ce script gère l'écoute des events de nouvelles commandes via Laravel Echo/Pusher
 * et déclenche une notification visuelle + sonore côté dashboard admin
 */

(function() {
    'use strict';

    // Configuration
    const CONFIG = {
        soundPath: '/sounds/notification.mp3',
        notificationDuration: 10000, // 10 secondes (au lieu de 5)
        maxNotifications: 3, // Nombre max de notifications à afficher simultanément
        localStorageKey: 'lebayo_notifications_sound_enabled' // Clé pour mémoriser le choix
    };

    // État de l'application
    let audioEnabled = false;
    let notificationCount = 0;
    let notificationAudio = null;

    /**
     * Initialiser le système de notifications
     */
    function initNotificationSystem() {
        // Vérifier que Echo est disponible
        if (typeof window.Echo === 'undefined') {
            console.error('[Order Notifications] Laravel Echo n\'est pas chargé');
            return;
        }

        // Précharger le fichier audio
        prepareAudio();

        // Vérifier si le son était déjà activé (localStorage)
        checkSavedSoundPreference();

        // Écouter le channel des commandes
        listenToOrderChannel();

        // Ajouter un bouton pour activer le son (requis par les navigateurs)
        // Seulement si pas déjà activé
        if (!audioEnabled) {
            addSoundActivationButton();
        }

        console.info('[Order Notifications] Système de notifications initialisé');
    }

    /**
     * Précharger le fichier audio de notification
     */
    function prepareAudio() {
        notificationAudio = new Audio(CONFIG.soundPath);
        notificationAudio.preload = 'auto';

        // Gérer les erreurs de chargement
        notificationAudio.addEventListener('error', function(e) {
            console.error('[Order Notifications] Erreur de chargement du son:', e);
        });
    }

    /**
     * Écouter le channel des commandes
     */
    function listenToOrderChannel() {
        window.Echo.channel('commandes')
            .listen('.commande.nouvelle', function(data) {
                console.log('[Order Notifications] Nouvelle commande reçue:', data);
                handleNewOrder(data.order);
            });
    }

    /**
     * Gérer la réception d'une nouvelle commande
     *
     * @param {Object} order Données de la commande
     */
    function handleNewOrder(order) {
        // Afficher la notification visuelle
        showNotification(order);

        // Jouer le son si activé
        if (audioEnabled) {
            playNotificationSound();
        }

        // Mettre à jour les statistiques du dashboard (si présentes)
        updateDashboardStats();
    }

    /**
     * Afficher une notification visuelle
     *
     * @param {Object} order Données de la commande
     */
    function showNotification(order) {
        // Limiter le nombre de notifications affichées
        if (notificationCount >= CONFIG.maxNotifications) {
            return;
        }

        notificationCount++;

        // Créer l'élément de notification
        const notification = createNotificationElement(order);

        // Ajouter au DOM
        const container = getNotificationContainer();
        container.appendChild(notification);

        // Animation d'entrée
        setTimeout(() => {
            notification.classList.add('show');
        }, 10);

        // Auto-suppression après le délai
        setTimeout(() => {
            removeNotification(notification);
        }, CONFIG.notificationDuration);
    }

    /**
     * Créer l'élément HTML de notification
     *
     * @param {Object} order Données de la commande
     * @return {HTMLElement}
     */
    function createNotificationElement(order) {
        const div = document.createElement('div');
        div.className = 'order-notification alert alert-primary alert-dismissible fade';
        div.setAttribute('role', 'alert');
        div.style.cssText = 'background-color: #ffffff !important; border: 2px solid #696cff !important; box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3) !important;';

        div.innerHTML = `
            <div class="d-flex align-items-start" style="background-color: #ffffff;">
                <div class="avatar flex-shrink-0 me-3">
                    <span class="avatar-initial rounded-circle" style="background-color: #696cff; color: white; width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                        <i class="bx bx-cart" style="font-size: 20px;"></i>
                    </span>
                </div>
                <div class="flex-grow-1">
                    <h6 class="mb-1" style="color: #1a1a1a; font-weight: 700; font-size: 16px;">
                        <i class="bx bx-bell bx-tada me-1" style="color: #696cff;"></i>
                        Nouvelle commande !
                    </h6>
                    <div class="mb-1">
                        <strong style="color: #2c3e50; font-size: 15px;">N° ${order.order_number}</strong>
                    </div>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between align-items-center">
                            <span style="color: #2c3e50; font-size: 14px; font-weight: 600;">
                                👤 ${order.user_name}
                            </span>
                            <strong style="color: #696cff; font-size: 18px; font-weight: 700;">${order.formatted_total}</strong>
                        </div>
                        ${order.user_email ? `<small style="color: #666; font-size: 12px;">📧 ${order.user_email}</small><br>` : ''}
                        <small style="color: #555555; font-size: 13px;">
                            🛒 ${order.items_count} article(s)
                        </small>
                    </div>
                    <div class="mt-2">
                        <a href="/admin/orders/${order.id}" class="btn btn-sm btn-primary" style="background-color: #696cff; border-color: #696cff; font-weight: 600;">
                            Voir la commande
                        </a>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" style="opacity: 1; filter: invert(1);"></button>
            </div>
        `;

        // Gérer la fermeture manuelle
        const closeBtn = div.querySelector('.btn-close');
        closeBtn.addEventListener('click', function() {
            removeNotification(div);
        });

        return div;
    }

    /**
     * Obtenir ou créer le conteneur de notifications
     *
     * @return {HTMLElement}
     */
    function getNotificationContainer() {
        let container = document.getElementById('order-notifications-container');

        if (!container) {
            container = document.createElement('div');
            container.id = 'order-notifications-container';
            container.className = 'position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            container.style.maxWidth = '400px';
            document.body.appendChild(container);
        }

        return container;
    }

    /**
     * Supprimer une notification
     *
     * @param {HTMLElement} notification
     */
    function removeNotification(notification) {
        notification.classList.remove('show');

        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
                notificationCount--;
            }
        }, 300);
    }

    /**
     * Jouer le son de notification
     */
    function playNotificationSound() {
        if (notificationAudio) {
            // Réinitialiser la lecture si déjà en cours
            notificationAudio.currentTime = 0;

            // Jouer le son
            notificationAudio.play().catch(function(error) {
                console.warn('[Order Notifications] Impossible de jouer le son:', error);
            });
        }
    }

    /**
     * Ajouter un bouton pour activer le son
     * (Requis par les navigateurs modernes - interaction utilisateur nécessaire)
     */
    function addSoundActivationButton() {
        // Vérifier si le bouton existe déjà
        if (document.getElementById('enable-notification-sound')) {
            return;
        }

        // Créer le bouton flottant
        const button = document.createElement('button');
        button.id = 'enable-notification-sound';
        button.className = 'btn btn-warning btn-sm position-fixed';
        button.style.bottom = '20px';
        button.style.right = '20px';
        button.style.zIndex = '9998';
        button.innerHTML = `
            <i class="bx bx-volume-mute me-1"></i>
            Activer le son des notifications
        `;

        // Gérer le clic
        button.addEventListener('click', function() {
            enableNotificationSound();
            button.remove();
        });

        // Ajouter au DOM
        document.body.appendChild(button);
    }

    /**
     * Vérifier si le son était déjà activé (localStorage)
     */
    function checkSavedSoundPreference() {
        const savedPreference = localStorage.getItem(CONFIG.localStorageKey);
        if (savedPreference === 'true') {
            audioEnabled = true;
            console.info('[Order Notifications] Son déjà activé (préférence sauvegardée)');
        }
    }

    /**
     * Activer le son des notifications
     */
    function enableNotificationSound() {
        audioEnabled = true;

        // Sauvegarder la préférence dans localStorage
        localStorage.setItem(CONFIG.localStorageKey, 'true');

        // Jouer un son de test
        if (notificationAudio) {
            notificationAudio.play()
                .then(() => {
                    console.info('[Order Notifications] Son activé avec succès');
                    showSuccessMessage('Son des notifications activé ! Ce choix sera mémorisé.');
                })
                .catch((error) => {
                    console.error('[Order Notifications] Erreur d\'activation du son:', error);
                    showErrorMessage('Impossible d\'activer le son');
                });
        }
    }

    /**
     * Mettre à jour les statistiques du dashboard
     */
    function updateDashboardStats() {
        // Incrémenter le compteur de commandes en attente (si présent)
        const pendingBadge = document.querySelector('[data-stat="pending-orders"]');
        if (pendingBadge) {
            const currentValue = parseInt(pendingBadge.textContent) || 0;
            pendingBadge.textContent = currentValue + 1;
        }

        // Ajouter une animation au compteur total
        const totalBadge = document.querySelector('[data-stat="total-orders"]');
        if (totalBadge) {
            totalBadge.classList.add('animate__animated', 'animate__pulse');
            setTimeout(() => {
                totalBadge.classList.remove('animate__animated', 'animate__pulse');
            }, 1000);
        }
    }

    /**
     * Afficher un message de succès
     *
     * @param {string} message
     */
    function showSuccessMessage(message) {
        showToast(message, 'success');
    }

    /**
     * Afficher un message d'erreur
     *
     * @param {string} message
     */
    function showErrorMessage(message) {
        showToast(message, 'danger');
    }

    /**
     * Afficher un toast Bootstrap
     *
     * @param {string} message
     * @param {string} type (success, danger, warning, info)
     */
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');

        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        // Ajouter au container de toasts ou créer
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '9999';
            document.body.appendChild(container);
        }

        container.appendChild(toast);

        // Initialiser le toast Bootstrap
        if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
        }
    }

    // Initialiser le système au chargement du DOM
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initNotificationSystem);
    } else {
        initNotificationSystem();
    }

    // Exposer certaines fonctions globalement pour usage externe
    window.OrderNotifications = {
        enable: enableNotificationSound,
        test: function() {
            handleNewOrder({
                id: 1,
                order_number: 'TEST-001',
                user_name: 'Client Test',
                items_count: 2,
                formatted_total: '5 000 F',
                total: 5000
            });
        }
    };

})();
