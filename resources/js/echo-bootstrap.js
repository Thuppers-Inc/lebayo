/**
 * Configuration de Laravel Echo pour les notifications temps réel
 * 
 * Ce fichier est chargé uniquement sur les pages admin qui nécessitent
 * des notifications en temps réel (dashboard, gestion commandes)
 */

import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Exposer Pusher globalement (requis par Laravel Echo)
window.Pusher = Pusher;

/**
 * Initialiser Laravel Echo avec la configuration Pusher
 */
function initializeEcho() {
    try {
        // Récupérer la configuration depuis les meta tags
        const pusherKey = document.querySelector('meta[name="pusher-key"]')?.content;
        const pusherCluster = document.querySelector('meta[name="pusher-cluster"]')?.content || 'eu';
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

        // Valider la configuration
        if (!pusherKey) {
            console.warn('[Echo] Pusher key manquante - Broadcasting désactivé');
            return;
        }

        // Créer l'instance Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: pusherKey,
            cluster: pusherCluster,
            forceTLS: true,
            encrypted: true,
            
            // Configuration pour le développement
            disableStats: true,
            enabledTransports: ['ws', 'wss'],
            
            // Headers CSRF pour les channels privés
            auth: {
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            }
        });

        console.info('[Echo] Laravel Echo initialisé avec succès');

        // Gérer les events de connexion
        if (window.Echo.connector && window.Echo.connector.pusher) {
            const pusher = window.Echo.connector.pusher;

            pusher.connection.bind('connected', function() {
                console.info('[Echo] Connecté à Pusher');
            });

            pusher.connection.bind('error', function(error) {
                console.error('[Echo] Erreur Pusher:', error);
            });
        }

    } catch (error) {
        console.error('[Echo] Erreur lors de l\'initialisation:', error);
    }
}

// Initialiser au chargement
initializeEcho();

// Exposer une fonction de test
window.testEcho = function() {
    if (!window.Echo) {
        console.error('Echo n\'est pas initialisé');
        return;
    }
    console.log('Echo status:', window.Echo.socketId() ? 'Connected' : 'Not connected');
};
