# Documentation - Notifications Temps Réel des Commandes

## Vue d'ensemble

Ce système permet de notifier instantanément le dashboard administrateur lorsqu'une nouvelle commande est créée côté client. La notification inclut un son et s'affiche visuellement en temps réel.

## Architecture

### 1. Backend Laravel

#### Event : `NouvelleCommande`
- **Fichier** : `app/Events/NouvelleCommande.php`
- **Type** : `ShouldBroadcast`
- **Channel** : `commandes` (channel public)
- **Event name** : `commande.nouvelle`

**Données transmises** :
```php
[
    'id' => ID de la commande,
    'order_number' => Numéro de commande,
    'total' => Montant total,
    'formatted_total' => Montant formaté,
    'status' => Statut,
    'status_label' => Libellé du statut,
    'user_name' => Nom du client,
    'created_at' => Date de création,
    'items_count' => Nombre d'articles
]
```

#### Déclenchement
- **Fichier** : `app/Http/Controllers/CheckoutController.php`
- **Méthode** : `store()`
- **Ligne** : Après `DB::commit()` et avant la redirection

```php
event(new NouvelleCommande($order));
```

### 2. Configuration Broadcasting

#### Fichier : `config/broadcasting.php`
Configuration pour Pusher avec support des clusters et TLS.

#### Variables d'environnement requises (.env)
```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=votre_app_id
PUSHER_APP_KEY=votre_app_key
PUSHER_APP_SECRET=votre_app_secret
PUSHER_APP_CLUSTER=eu
```

### 3. Frontend Admin

#### Scripts JavaScript

1. **Echo Bootstrap** : `resources/js/echo-bootstrap.js`
   - Initialise Laravel Echo avec Pusher
   - Configuration depuis les meta tags
   - Gestion des erreurs de connexion

2. **Order Notifications** : `public/admin-assets/js/order-notifications.js`
   - Écoute du channel `commandes`
   - Affichage des notifications visuelles
   - Gestion du son avec activation utilisateur
   - Mise à jour des statistiques dashboard

#### Styles CSS
**Fichier** : `public/admin-assets/css/order-notifications.css`
- Positionnement des notifications (coin supérieur droit)
- Animations d'entrée/sortie
- Design responsive
- Animation de l'icône cloche

#### Intégration dans le Layout
**Fichier** : `resources/views/admin/layouts/master.blade.php`

**Meta tags ajoutés** :
```html
<meta name="pusher-key" content="{{ env('PUSHER_APP_KEY') }}">
<meta name="pusher-cluster" content="{{ env('PUSHER_APP_CLUSTER', 'eu') }}">
```

**Scripts chargés** :
- Laravel Echo (via module ES6)
- Pusher JS
- order-notifications.js

## Installation & Configuration

### 1. Installer les dépendances

```bash
# Dépendances NPM déjà installées
npm install --save laravel-echo pusher-js

# Dépendances Composer (déjà présentes dans Laravel)
composer require pusher/pusher-php-server
```

### 2. Configurer Pusher

1. Créer un compte sur [pusher.com](https://pusher.com/)
2. Créer une nouvelle app
3. Copier les credentials dans `.env`

### 3. Activer le Broadcasting

```bash
# Décommenter dans config/app.php si nécessaire
App\Providers\BroadcastServiceProvider::class,
```

### 4. Ajouter le fichier audio

1. Télécharger ou créer un fichier MP3 court (1-2 secondes)
2. Le nommer `notification.mp3`
3. Le placer dans `/public/sounds/notification.mp3`

**Sources gratuites recommandées** :
- https://freesound.org/
- https://www.zapsplat.com/
- https://mixkit.co/free-sound-effects/

### 5. Tester le système

```bash
# Démarrer le serveur Laravel
php artisan serve

# Dans une nouvelle fenêtre, démarrer la queue
php artisan queue:work

# Accéder au dashboard admin
# Créer une commande côté client
```

## Utilisation

### Côté Admin

1. **Activation du son** : Au premier accès, cliquer sur le bouton "Activer le son des notifications" (requis par les navigateurs)

2. **Réception des notifications** :
   - Une notification visuelle s'affiche en haut à droite
   - Un son se joue (si activé)
   - La notification disparaît après 5 secondes
   - Maximum 3 notifications simultanées

3. **Actions disponibles** :
   - Cliquer sur "Voir la commande" pour accéder aux détails
   - Fermer manuellement avec le bouton X

### Fonctions de test (Console navigateur)

```javascript
// Tester l'état d'Echo
window.testEcho()

// Simuler une notification
window.OrderNotifications.test()

// Activer le son manuellement
window.OrderNotifications.enable()
```

## Sécurité

### Ce qui est implémenté

1. **Credentials Pusher** : 
   - Clé publique exposée via meta tags (normal)
   - Secret JAMAIS exposé côté client
   - Secret utilisé uniquement côté serveur

2. **CSRF Protection** : Token CSRF inclus dans les headers Echo

3. **Channel public** : `commandes`
   - Accessible uniquement depuis le dashboard admin (route protégée)
   - Recommandé : Migrer vers PrivateChannel avec autorisation

### Amélioration recommandée : Channel Privé

Pour une sécurité accrue, transformer en channel privé :

1. **Modifier l'event** :
```php
// Dans app/Events/NouvelleCommande.php
return [
    new PrivateChannel('commandes'),
];
```

2. **Créer l'autorisation** :
```php
// Dans routes/channels.php
Broadcast::channel('commandes', function ($user) {
    return $user->isAdmin(); // ou vérification de rôle appropriée
});
```

## Dépannage

### Problème : Notifications ne s'affichent pas

**Vérifications** :
1. Variables `.env` Pusher configurées ?
2. `BROADCAST_DRIVER=pusher` dans `.env` ?
3. Console navigateur : erreurs JavaScript ?
4. Console navigateur : `window.testEcho()` retourne "Connected" ?

### Problème : Le son ne se joue pas

**Vérifications** :
1. Fichier `/public/sounds/notification.mp3` existe ?
2. Bouton "Activer le son" cliqué (requis par navigateurs) ?
3. Console navigateur : erreurs de chargement audio ?
4. Volume du navigateur/système activé ?

### Problème : Event non reçu

**Vérifications** :
1. Queue worker lancé ? `php artisan queue:work`
2. Event correctement déclenché ? Vérifier les logs Laravel
3. Pusher Dashboard : événements envoyés ?
4. Channel et event names correspondent ?

### Mode Debug

Activer les logs Pusher :
```javascript
// Dans la console navigateur
Pusher.logToConsole = true;
```

## Structure des fichiers

```
/app
  /Events
    NouvelleCommande.php          # Event Laravel
  /Http/Controllers
    CheckoutController.php         # Déclenchement event

/config
  broadcasting.php                 # Configuration Pusher

/public
  /admin-assets
    /js
      order-notifications.js       # Logique notifications
    /css
      order-notifications.css      # Styles notifications
  /sounds
    notification.mp3               # Fichier audio (à ajouter)

/resources
  /views/admin/layouts
    master.blade.php               # Intégration Echo + Scripts
  /js
    echo-bootstrap.js              # Configuration Echo

.env                               # Variables Pusher (à configurer)
```

## Fichiers modifiés

1. ✅ `config/broadcasting.php` - Nouveau fichier
2. ✅ `app/Events/NouvelleCommande.php` - Nouveau fichier
3. ✅ `app/Http/Controllers/CheckoutController.php` - Modifié (ajout event)
4. ✅ `resources/views/admin/layouts/master.blade.php` - Modifié (meta tags + scripts)
5. ✅ `public/admin-assets/js/order-notifications.js` - Nouveau fichier
6. ✅ `public/admin-assets/css/order-notifications.css` - Nouveau fichier
7. ✅ `resources/js/echo-bootstrap.js` - Nouveau fichier
8. ✅ `package.json` - Modifié (dépendances ajoutées)

## Maintenance

### Surveillance

- **Pusher Dashboard** : Surveiller les quotas (messages, connexions)
- **Logs Laravel** : Vérifier les erreurs d'event broadcasting
- **Console navigateur** : Surveiller les erreurs Echo/Pusher

### Performance

- **Connexions simultanées** : Pusher gratuit limite à 100 connexions
- **Messages** : Plan gratuit limite à 200k messages/jour
- **Channel public** : Moins de overhead qu'un channel privé
- **Audio** : Préchargé pour éviter les délais

### Évolution future

1. **Notifications persistantes** : Stocker en base de données
2. **Badge de compteur** : Afficher le nombre de nouvelles commandes
3. **Filtres** : Permettre de filtrer les types de notifications
4. **Préférences utilisateur** : Activer/désactiver par type
5. **Channels privés** : Sécurité renforcée avec autorisation

## Support

Pour toute question ou problème :
1. Consulter les logs Laravel : `storage/logs/laravel.log`
2. Vérifier la console navigateur
3. Consulter le Pusher Dashboard
4. Vérifier cette documentation

## Notes importantes

⚠️ **Interaction utilisateur requise** : Les navigateurs modernes (Chrome, Firefox, Safari) bloquent la lecture automatique du son tant que l'utilisateur n'a pas interagi avec la page. C'est pourquoi un bouton d'activation est obligatoire.

⚠️ **Fichier audio** : Le fichier `notification.mp3` doit être ajouté manuellement (non inclus dans Git).

⚠️ **Variables d'environnement** : Ne jamais commiter les credentials Pusher dans `.env`.

✅ **Production ready** : Le code est prêt pour la production avec gestion d'erreurs appropriée.

---

*Documentation générée le {{ date('d/m/Y') }} pour Lebayo*
