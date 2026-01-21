# Guide d'installation - Notifications Temps Réel

## ✅ Ce qui a été fait

### 1. Backend Laravel

- ✅ **Event créé** : `app/Events/NouvelleCommande.php`
  - Implémente `ShouldBroadcast`
  - Diffuse sur le channel `commandes`
  - Event nommé `commande.nouvelle`

- ✅ **Event déclenché** : Dans `CheckoutController::store()`
  - Après la création réussie d'une commande
  - Après le commit de la transaction

- ✅ **Configuration** : `config/broadcasting.php` créé
  - Configuration Pusher complète
  - Support TLS et clusters

- ✅ **Dépendance Composer** : `pusher/pusher-php-server` installé

### 2. Frontend Admin

- ✅ **Scripts JavaScript créés** :
  - `resources/js/echo-bootstrap.js` - Configuration Echo
  - `public/admin-assets/js/order-notifications.js` - Logique notifications

- ✅ **Styles CSS** : `public/admin-assets/css/order-notifications.css`
  - Design des notifications
  - Animations
  - Responsive

- ✅ **Intégration Layout** : `resources/views/admin/layouts/master.blade.php`
  - Meta tags Pusher ajoutés
  - Scripts Echo chargés
  - CSS notifications inclus

- ✅ **Dépendances NPM installées** :
  - `laravel-echo`
  - `pusher-js`

### 3. Documentation

- ✅ **Documentation complète** : `NOTIFICATIONS-TEMPS-REEL.md`
- ✅ **Guide configuration** : `ENV-EXAMPLE-PUSHER.md`
- ✅ **Guide installation** : Ce fichier

---

## 🚀 Étapes pour activer le système

### Étape 1 : Configurer Pusher

1. **Créer un compte Pusher**
   - Aller sur https://pusher.com/
   - S'inscrire (gratuit jusqu'à 100 connexions simultanées)

2. **Créer une App**
   - Dans le dashboard Pusher, cliquer sur "Create App"
   - Nom : `Lebayo` (ou autre)
   - Cluster : Choisir `eu` (Europe) ou le plus proche
   - Frontend tech : `Vanilla JS`
   - Backend tech : `Laravel`

3. **Copier les credentials**
   - Dans votre app Pusher, aller dans "App Keys"
   - Copier : app_id, key, secret, cluster

### Étape 2 : Configurer .env

Ajouter ces lignes dans `/Users/traoreismaeljunior/works/lebayo/.env` :

```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=123456
PUSHER_APP_KEY=abcdef123456
PUSHER_APP_SECRET=xyz789secretkey
PUSHER_APP_CLUSTER=eu
```

⚠️ **Remplacer les valeurs par vos vraies credentials Pusher**

### Étape 3 : Ajouter le fichier audio

1. **Télécharger un son de notification**
   - Recommandé : https://mixkit.co/free-sound-effects/notification/
   - Ou : https://freesound.org/search/?q=notification

2. **Préparer le fichier**
   - Format : MP3
   - Durée : 1-2 secondes
   - Nom : Exactement `notification.mp3`

3. **Placer le fichier**
   ```bash
   # Le placer ici :
   /Users/traoreismaeljunior/works/lebayo/public/sounds/notification.mp3
   ```

### Étape 4 : Activer le BroadcastServiceProvider

Vérifier que le provider est activé dans `config/app.php` :

```php
'providers' => ServiceProvider::defaultProviders()->merge([
    // ...
    App\Providers\BroadcastServiceProvider::class,
])->toArray(),
```

Si le fichier n'existe pas, le créer :

```bash
php artisan make:provider BroadcastServiceProvider
```

### Étape 5 : Démarrer les services

```bash
# Terminal 1 : Serveur Laravel
php artisan serve

# Terminal 2 : Queue Worker (IMPORTANT pour les broadcasts)
php artisan queue:work

# Terminal 3 : Vite (si nécessaire pour le frontend)
npm run dev
```

⚠️ **Le queue worker est OBLIGATOIRE** pour que les events soient diffusés !

### Étape 6 : Tester le système

1. **Accéder au dashboard admin**
   ```
   http://localhost:8000/admin/dashboard
   ```

2. **Activer le son**
   - Cliquer sur le bouton "Activer le son des notifications" (en bas à droite)

3. **Créer une commande test**
   - Dans une autre fenêtre/onglet, aller sur le site client
   - Se connecter comme utilisateur client
   - Ajouter des produits au panier
   - Finaliser une commande

4. **Vérifier**
   - ✅ Une notification doit apparaître en haut à droite du dashboard admin
   - ✅ Un son doit se jouer
   - ✅ La notification doit disparaître après 5 secondes

---

## 🔧 Dépannage

### Problème : Notifications ne s'affichent pas

**Console navigateur** (F12) :
```javascript
// Vérifier qu'Echo est initialisé
window.Echo

// Tester la connexion
window.testEcho()

// Tester une notification manuelle
window.OrderNotifications.test()
```

**Vérifications** :
1. ✅ Variables `.env` configurées ?
2. ✅ `BROADCAST_DRIVER=pusher` ?
3. ✅ Queue worker lancé ?
4. ✅ Erreurs dans la console navigateur ?
5. ✅ Erreurs dans `storage/logs/laravel.log` ?

### Problème : Le son ne se joue pas

**Vérifications** :
1. ✅ Fichier `public/sounds/notification.mp3` existe ?
2. ✅ Bouton "Activer le son" cliqué ?
3. ✅ Volume du navigateur activé ?
4. ✅ Erreur dans la console navigateur ?

**Test manuel** :
```javascript
// Activer le son
window.OrderNotifications.enable()

// Tester le son
window.OrderNotifications.test()
```

### Problème : "Echo is not defined"

**Solution** : Vérifier que les meta tags sont présents dans le HTML :

```html
<meta name="pusher-key" content="votre_key">
<meta name="pusher-cluster" content="eu">
```

**Vérifier** : View source sur la page admin

### Problème : Queue worker non lancé

**Symptômes** : Aucune notification, pas d'erreur

**Solution** :
```bash
# Lancer le queue worker
php artisan queue:work

# Ou avec restart automatique
php artisan queue:listen
```

### Logs Pusher

**Activer les logs dans la console** :
```javascript
Pusher.logToConsole = true;
```

**Vérifier dans le Pusher Dashboard** :
- Aller sur https://dashboard.pusher.com/
- Sélectionner votre app
- Onglet "Debug Console"
- Vérifier que les events arrivent

---

## 📊 Vérifier que tout fonctionne

### Checklist finale

- [ ] Pusher configuré dans `.env`
- [ ] `BROADCAST_DRIVER=pusher` dans `.env`
- [ ] Fichier `notification.mp3` présent dans `/public/sounds/`
- [ ] `composer require pusher/pusher-php-server` installé
- [ ] `npm install laravel-echo pusher-js` installé
- [ ] Queue worker lancé (`php artisan queue:work`)
- [ ] Dashboard admin accessible
- [ ] Bouton "Activer le son" fonctionnel
- [ ] Test de création de commande effectué
- [ ] Notification reçue et son joué

### Test complet

```bash
# 1. Vérifier la configuration
php artisan config:clear
php artisan cache:clear

# 2. Lancer les services
php artisan serve &
php artisan queue:work &

# 3. Accéder au dashboard
# http://localhost:8000/admin/dashboard

# 4. Ouvrir la console navigateur (F12)
# 5. Taper : window.OrderNotifications.test()
# 6. Vérifier qu'une notification s'affiche

# 7. Créer une vraie commande côté client
# 8. Vérifier que la notification arrive sur le dashboard
```

---

## 🎯 Résumé des fichiers

### Nouveaux fichiers créés

```
config/broadcasting.php                          # Config Pusher
app/Events/NouvelleCommande.php                  # Event Laravel
resources/js/echo-bootstrap.js                   # Config Echo
public/admin-assets/js/order-notifications.js    # Logique notifications
public/admin-assets/css/order-notifications.css  # Styles
public/sounds/.gitkeep                           # Placeholder
NOTIFICATIONS-TEMPS-REEL.md                      # Documentation
ENV-EXAMPLE-PUSHER.md                            # Config .env
INSTALLATION-NOTIFICATIONS.md                    # Ce fichier
```

### Fichiers modifiés

```
app/Http/Controllers/CheckoutController.php      # + event()
resources/views/admin/layouts/master.blade.php   # + scripts + meta tags
package.json                                     # + dependencies
composer.json                                    # + pusher package
```

---

## 📝 Notes importantes

### Performance

- **Plan gratuit Pusher** : 100 connexions simultanées, 200k messages/jour
- **Channel public** : Pas d'overhead d'authentification
- **Audio préchargé** : Pas de délai lors de la lecture

### Sécurité

- ⚠️ Channel actuellement **public** : tous les admins connectés reçoivent les notifications
- 🔒 Pour plus de sécurité : migrer vers `PrivateChannel` avec autorisation
- ✅ Le secret Pusher n'est **jamais exposé** côté client

### Évolution future

- [ ] Notifications persistantes en base de données
- [ ] Badge compteur de nouvelles commandes
- [ ] Filtrage par type de notification
- [ ] Préférences utilisateur (activer/désactiver)
- [ ] Migration vers channel privé avec autorisation

---

## 🆘 Support

En cas de problème :

1. **Logs Laravel** : `storage/logs/laravel.log`
2. **Console navigateur** : F12 → Console
3. **Pusher Dashboard** : https://dashboard.pusher.com/
4. **Documentation complète** : `NOTIFICATIONS-TEMPS-REEL.md`

---

**Installation réalisée le 21/01/2026 pour Lebayo**

Bon développement ! 🚀
