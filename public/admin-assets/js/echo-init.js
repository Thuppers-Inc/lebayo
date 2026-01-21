/**
 * Initialisation de Laravel Echo pour le dashboard admin
 * 
 * Ce script configure Laravel Echo avec Pusher pour permettre
 * les communications temps réel entre le serveur et le dashboard
 * 
 * Note de sécurité : Les credentials Pusher sont lus depuis les meta tags
 * et ne sont jamais exposés directement dans le code
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
        const config = getEchoConfig();

        // Valider la configuration
        if (!validateConfig(config)) {
            console.error('[Echo] Configuration invalide - Broadcasting désactivé');
            return;
        }

        // Créer l'instance Echo
        window.Echo = new Echo({
            broadcaster: 'pusher',
            key: config.key,
            cluster: config.cluster,
            forceTLS: config.forceTLS,
            encrypted: config.encrypted,
            
            // Options Pusher supplémentaires
            enabledTransports: ['ws', 'wss'],
            
            // Configuration pour le développement
            disableStats: true,
            
            // Callbacks de connexion
            wsHost: config.wsHost,
            wsPort: config.wsPort,
            wssPort: config.wssPort,
            
            // Headers CSRF
            auth: {
                headers: {
                    'X-CSRF-TOKEN': config.csrfToken
                }
            }
        });

        console.info('[Echo] Laravel Echo initialisé avec succès');

        // Gérer les events de connexion Pusher
        setupConnectionHandlers();

    } catch (error) {
        console.error('[Echo] Erreur lors de l\'initialisation:', error);
    }
}

/**
 * Récupérer la configuration depuis les meta tags
 * 
 * @return {Object}
 */
function getEchoConfig() {
    return {
        key: getMeta('pusher-key'),
        cluster: getMeta('pusher-cluster') || 'eu',
        forceTLS: getMeta('pusher-force-tls') === 'true',
        encrypted: getMeta('pusher-encrypted') === 'true',
        wsHost: getMeta('pusher-ws-host'),
        wsPort: parseInt(getMeta('pusher-ws-port')) || 80,
        wssPort: parseInt(getMeta('pusher-wss-port')) || 443,
        csrfToken: getMeta('csrf-token')
    };
}

/**
 * Récupérer une valeur depuis un meta tag
 * 
 * @param {string} name
 * @return {string|null}
 */
function getMeta(name) {
    const meta = document.querySelector(`meta[name="${name}"]`);
    return meta ? meta.getAttribute('content') : null;
}

/**
 * Valider la configuration Echo
 * 
 * @param {Object} config
 * @return {boolean}
 */
function validateConfig(config) {
    if (!config.key) {
        console.warn('[Echo] Pusher key manquante');
        return false;
    }

    if (!config.csrfToken) {
        console.warn('[Echo] CSRF token manquant');
        return false;
    }

    return true;
}

/**
 * Configurer les handlers de connexion
 */
function setupConnectionHandlers() {
    if (!window.Echo || !window.Echo.connector) {
        return;
    }

    const pusher = window.Echo.connector.pusher;

    // Connexion établie
    pusher.connection.bind('connected', function() {
        console.info('[Echo] Connecté à Pusher');
        showConnectionStatus('connected');
    });

    // Connexion en cours
    pusher.connection.bind('connecting', function() {
        console.info('[Echo] Connexion à Pusher en cours...');
        showConnectionStatus('connecting');
    });

    // Connexion échouée
    pusher.connection.bind('failed', function() {
        console.error('[Echo] Échec de connexion à Pusher');
        showConnectionStatus('failed');
    });

    // Connexion perdue
    pusher.connection.bind('disconnected', function() {
        console.warn('[Echo] Déconnecté de Pusher');
        showConnectionStatus('disconnected');
    });

    // Erreur
    pusher.connection.bind('error', function(error) {
        console.error('[Echo] Erreur Pusher:', error);
        showConnectionStatus('error');
    });
}

/**
 * Afficher le statut de connexion (optionnel - pour debug)
 * 
 * @param {string} status
 */
function showConnectionStatus(status) {
    // Créer ou mettre à jour un indicateur de statut (optionnel)
    let indicator = document.getElementById('echo-status-indicator');
    
    if (!indicator) {
        indicator = document.createElement('div');
        indicator.id = 'echo-status-indicator';
        indicator.className = 'position-fixed';
        indicator.style.bottom = '10px';
        indicator.style.left = '10px';
        indicator.style.padding = '5px 10px';
        indicator.style.borderRadius = '4px';
        indicator.style.fontSize = '11px';
        indicator.style.zIndex = '9999';
        indicator.style.display = 'none'; // Masqué par défaut
        document.body.appendChild(indicator);
    }

    // Mettre à jour le statut
    const statusConfig = {
        connected: { text: 'En ligne', color: '#28a745', display: 'none' },
        connecting: { text: 'Connexion...', color: '#ffc107', display: 'block' },
        failed: { text: 'Échec', color: '#dc3545', display: 'block' },
        disconnected: { text: 'Déconnecté', color: '#6c757d', display: 'block' },
        error: { text: 'Erreur', color: '#dc3545', display: 'block' }
    };

    const config = statusConfig[status] || statusConfig.disconnected;
    indicator.textContent = `📡 ${config.text}`;
    indicator.style.backgroundColor = config.color;
    indicator.style.color = '#fff';
    indicator.style.display = config.display;

    // Auto-masquer après 3 secondes pour les états transitoires
    if (status === 'connecting') {
        setTimeout(() => {
            if (indicator.textContent.includes('Connexion')) {
                indicator.style.display = 'none';
            }
        }, 3000);
    }
}

/**
 * Fonction de test pour vérifier la configuration
 */
function testEcho() {
    if (!window.Echo) {
        console.error('[Echo] Echo n\'est pas initialisé');
        return false;
    }

    console.info('[Echo] Configuration actuelle:', {
        socketId: window.Echo.socketId(),
        connector: window.Echo.connector.name
    });

    return true;
}

// Initialiser au chargement du DOM
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initializeEcho);
} else {
    initializeEcho();
}

// Exposer les fonctions de test
window.EchoTest = {
    status: testEcho,
    reconnect: function() {
        if (window.Echo && window.Echo.connector) {
            window.Echo.connector.pusher.disconnect();
            window.Echo.connector.pusher.connect();
        }
    }
};
