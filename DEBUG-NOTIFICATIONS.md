# 🔍 Guide de Débogage - Notifications Temps Réel

## Diagnostic Étape par Étape

### ✅ ÉTAPE 1 : Vérifier la configuration .env

```bash
# Lire la configuration
cat .env | grep -E "(BROADCAST|PUSHER)"
```

**Ce que vous devez voir :**
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=123456
PUSHER_APP_KEY=abc123def456
PUSHER_APP_SECRET=xyz789secret
PUSHER_APP_CLUSTER=eu
```

**❌ Si ces lignes sont absentes ou vides :**
1. Ouvrir le fichier `.env`
2. Ajouter les variables Pusher (voir ENV-EXAMPLE-PUSHER.md)
3. Relancer `php artisan config:clear`

---

### ✅ ÉTAPE 2 : Vérifier que le Queue Worker tourne

```bash
# Vérifier les processus
ps aux | grep "queue:work"
```

**Ce que vous devez voir :**
```
user  12345  php artisan queue:work
```

**❌ Si aucun processus n'apparaît :**
```bash
# Lancer le queue worker (ESSENTIEL !)
php artisan queue:work
```

⚠️ **TRÈS IMPORTANT** : Sans queue worker, les events ne sont **jamais diffusés** !

---

### ✅ ÉTAPE 3 : Vérifier les logs Laravel

```bash
# Voir les dernières lignes du log
tail -f storage/logs/laravel.log
```

**Créer une commande test et observer les logs**

**Ce que vous devriez voir :**
```
[2026-01-21 14:30:00] local.INFO: Commande créée avec succès
```

**❌ Erreurs courantes :**

1. **"Class 'Pusher\Pusher' not found"**
   ```bash
   composer require pusher/pusher-php-server
   ```

2. **"Invalid Pusher credentials"**
   - Vérifier les credentials dans .env
   - Vérifier sur le Pusher Dashboard

3. **"Event not found"**
   ```bash
   composer dump-autoload
   php artisan config:clear
   ```

---

### ✅ ÉTAPE 4 : Tester l'event manuellement

Créer un fichier de test :

```bash
# Créer test-broadcast.php à la racine
cat > test-broadcast.php << 'EOF'
<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Créer une commande de test
$order = App\Models\Order::latest()->first();

if (!$order) {
    echo "❌ Aucune commande trouvée. Créez une commande d'abord.\n";
    exit(1);
}

echo "📦 Commande trouvée : {$order->order_number}\n";
echo "🚀 Déclenchement de l'event...\n";

event(new App\Events\NouvelleCommande($order));

echo "✅ Event déclenché !\n";
echo "👀 Vérifiez le dashboard admin maintenant.\n";
EOF
```

```bash
# Exécuter le test
php test-broadcast.php
```

**Observer :**
1. Le message "Event déclenché"
2. Le queue worker doit traiter l'event
3. La notification doit apparaître sur le dashboard

---

### ✅ ÉTAPE 5 : Tester Pusher directement

```bash
# Créer test-pusher.php
cat > test-pusher.php << 'EOF'
<?php

require __DIR__.'/vendor/autoload.php';

$pusher = new Pusher\Pusher(
    env('PUSHER_APP_KEY', ''),
    env('PUSHER_APP_SECRET', ''),
    env('PUSHER_APP_ID', ''),
    [
        'cluster' => env('PUSHER_APP_CLUSTER', 'eu'),
        'useTLS' => true
    ]
);

echo "🔌 Test de connexion Pusher...\n";

try {
    $pusher->trigger('commandes', 'commande.nouvelle', [
        'order' => [
            'id' => 999,
            'order_number' => 'TEST-999',
            'user_name' => 'Test Client',
            'items_count' => 1,
            'formatted_total' => '1 000 F',
            'total' => 1000
        ]
    ]);
    
    echo "✅ Message envoyé avec succès à Pusher !\n";
    echo "👀 Vérifiez le dashboard admin maintenant.\n";
    
} catch (Exception $e) {
    echo "❌ Erreur : " . $e->getMessage() . "\n";
}
EOF
```

```bash
# Exécuter le test
php test-pusher.php
```

**❌ Si erreur "Invalid credentials" :**
- Vérifier les credentials sur dashboard.pusher.com
- Copier/coller soigneusement dans .env
- Relancer `php artisan config:clear`

---

### ✅ ÉTAPE 6 : Vérifier la console navigateur

1. **Ouvrir le dashboard admin**
   - http://localhost:8000/admin/dashboard

2. **Ouvrir la console navigateur (F12)**
   - Onglet "Console"

3. **Chercher les messages Echo**

**Ce que vous devriez voir :**
```javascript
[Echo] Initialisé avec succès
[Echo] Connecté à Pusher
```

**❌ Erreurs courantes :**

1. **"Echo is not defined"**
   - Vérifier que les scripts sont chargés dans master.blade.php
   - Recharger la page (Ctrl+F5)

2. **"Pusher key manquante"**
   - Vérifier les meta tags dans le HTML (view source)
   ```html
   <meta name="pusher-key" content="votre_key">
   ```

3. **"Failed to connect to Pusher"**
   - Vérifier la clé Pusher dans .env
   - Vérifier le cluster (eu, us2, ap1, etc.)

---

### ✅ ÉTAPE 7 : Tests JavaScript dans la console

Ouvrir la console navigateur et taper :

```javascript
// 1. Vérifier qu'Echo existe
window.Echo
// Devrait retourner un objet Echo

// 2. Vérifier la connexion
window.testEcho()
// Devrait afficher "Connected"

// 3. Tester une notification manuelle
window.OrderNotifications.test()
// Une notification de test devrait apparaître

// 4. Activer les logs Pusher
Pusher.logToConsole = true

// 5. Vérifier l'état de connexion
window.Echo.connector.pusher.connection.state
// Devrait retourner "connected"
```

---

### ✅ ÉTAPE 8 : Vérifier le Pusher Dashboard

1. Aller sur https://dashboard.pusher.com/
2. Sélectionner votre App
3. Onglet "Debug Console"
4. Observer les events en temps réel

**Test :**
- Créer une commande
- Observer si l'event arrive sur Pusher
- Si l'event n'arrive pas → problème backend
- Si l'event arrive mais pas de notification → problème frontend

---

### ✅ ÉTAPE 9 : Vérifier que l'event est bien déclenché

```bash
# Vérifier le code dans CheckoutController
grep -n "event(new NouvelleCommande" app/Http/Controllers/CheckoutController.php
```

**Ce que vous devez voir :**
```
241:            event(new NouvelleCommande($order));
```

**❌ Si aucun résultat :**
- L'event n'est pas déclenché
- Ajouter le code manuellement (voir INSTALLATION-NOTIFICATIONS.md)

---

### ✅ ÉTAPE 10 : Tester le workflow complet

```bash
# Terminal 1 : Logs Laravel
tail -f storage/logs/laravel.log

# Terminal 2 : Queue Worker avec logs
php artisan queue:work --verbose

# Terminal 3 : Serveur Laravel
php artisan serve
```

**Puis :**
1. Ouvrir dashboard admin + console navigateur (F12)
2. Créer une commande côté client
3. Observer les 3 terminaux

**Ce que vous devez voir :**
- Terminal 1 : "Commande créée avec succès"
- Terminal 2 : Processing event...
- Dashboard : Notification apparaît

---

## 🔍 Checklist de diagnostic rapide

Cocher chaque point :

```
[ ] .env contient les variables PUSHER correctement remplies
[ ] BROADCAST_DRIVER=pusher dans .env
[ ] composer require pusher/pusher-php-server installé
[ ] npm install laravel-echo pusher-js installé
[ ] Queue worker lancé (php artisan queue:work)
[ ] Serveur Laravel lancé (php artisan serve)
[ ] Dashboard admin accessible
[ ] Console navigateur : aucune erreur JavaScript
[ ] Console navigateur : window.Echo défini
[ ] Console navigateur : window.testEcho() retourne "Connected"
[ ] CheckoutController déclenche l'event
[ ] Pusher Dashboard reçoit les events
```

---

## 🆘 Solutions aux problèmes courants

### Problème : "Rien ne se passe du tout"

**Solution :**
```bash
# 1. Nettoyer le cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# 2. Relancer le queue worker
php artisan queue:restart

# 3. Tester avec le script de test
php test-pusher.php
```

### Problème : "Event déclenché mais pas de notification"

**Causes possibles :**
1. Queue worker pas lancé → `php artisan queue:work`
2. Credentials Pusher invalides → vérifier sur dashboard.pusher.com
3. Echo pas chargé côté frontend → vérifier console navigateur

### Problème : "Notification apparaît mais pas de son"

**Solutions :**
1. Cliquer sur "Activer le son des notifications"
2. Vérifier que `/public/sounds/notification.mp3` existe
3. Tester : `window.OrderNotifications.enable()`

### Problème : "Error 401 Unauthorized"

**Solution :**
```bash
# Vérifier le token CSRF
php artisan config:clear

# Vérifier que le token est dans les meta tags
curl -s http://localhost:8000/admin/dashboard | grep csrf-token
```

---

## 📊 Script de diagnostic automatique

```bash
# Exécuter le script de vérification
php verify-notifications-setup.php
```

Ce script vérifie automatiquement :
- Fichiers présents
- Dépendances installées
- Configuration .env
- Modifications du code

---

## 💡 Mode debug avancé

```bash
# 1. Activer les logs Broadcasting
# Dans .env :
LOG_LEVEL=debug

# 2. Observer tous les logs
tail -f storage/logs/laravel.log | grep -i broadcast

# 3. Tester avec Tinker
php artisan tinker
>>> $order = App\Models\Order::first();
>>> event(new App\Events\NouvelleCommande($order));
>>> exit
```

---

## 📞 Contactez-moi avec ces informations

Si le problème persiste, fournissez :

1. **Sortie de :**
   ```bash
   php verify-notifications-setup.php
   ```

2. **Variables .env (sans les secrets) :**
   ```bash
   cat .env | grep BROADCAST
   cat .env | grep PUSHER | sed 's/=.*/=***/'
   ```

3. **Dernières lignes des logs :**
   ```bash
   tail -20 storage/logs/laravel.log
   ```

4. **Console navigateur :**
   - Screenshot des erreurs (F12 → Console)

5. **Pusher Dashboard :**
   - Screenshot de l'onglet "Debug Console"

---

**Bon débogage ! 🔍**
