# 🔍 Débogage Frontend - Dashboard Admin

## Le backend fonctionne, mais pas le frontend ?

Si `php test-pusher.php` a réussi mais vous ne voyez pas les notifications, le problème est côté JavaScript/Echo.

---

## ✅ ÉTAPE 1 : Ouvrir la console navigateur

1. **Aller sur le dashboard admin**
   ```
   http://localhost:8000/admin/dashboard
   ```

2. **Ouvrir la console** (F12 ou Cmd+Option+I sur Mac)
   - Onglet "Console"

3. **Chercher les erreurs** (texte rouge)

---

## 🔍 Erreurs courantes et solutions

### Erreur 1 : "Echo is not defined"

**Cause** : Laravel Echo n'est pas chargé

**Solution** :
1. Vérifier que le fichier master.blade.php contient les scripts
2. Faire un "hard refresh" : Ctrl+Shift+R (ou Cmd+Shift+R sur Mac)
3. Vider le cache du navigateur

**Vérifier** :
```javascript
// Dans la console, taper :
window.Echo
// Devrait retourner un objet Echo, pas "undefined"
```

---

### Erreur 2 : "Pusher key manquante"

**Cause** : Les meta tags ne sont pas présents dans le HTML

**Solution** :
1. Faire un "View Source" (Clic droit → Afficher le code source)
2. Chercher (Ctrl+F) : `pusher-key`
3. Vous devriez voir :
   ```html
   <meta name="pusher-key" content="6e614b...">
   <meta name="pusher-cluster" content="eu">
   ```

**Si absent** :
```bash
# Vider le cache des vues
php artisan view:clear

# Recharger la page
```

---

### Erreur 3 : "Failed to load module script"

**Cause** : Problème avec les imports ES6

**Solution** : Vérifier que les packages NPM sont installés
```bash
npm install
```

---

### Erreur 4 : "Cannot find module 'laravel-echo'"

**Cause** : Package NPM manquant

**Solution** :
```bash
npm install --save laravel-echo pusher-js
```

---

### Erreur 5 : Connection state = "unavailable" ou "failed"

**Cause** : Impossible de se connecter à Pusher

**Solution** : Vérifier la clé Pusher dans les meta tags

**Dans la console** :
```javascript
// Vérifier l'état de connexion
window.Echo.connector.pusher.connection.state
// Devrait être "connected"

// Activer les logs Pusher
Pusher.logToConsole = true

// Reconnecter
window.EchoTest.reconnect()
```

---

## ✅ ÉTAPE 2 : Tests dans la console

### Test 1 : Vérifier qu'Echo existe

```javascript
window.Echo
```

**Résultat attendu** : Un objet Echo
**Si undefined** : Echo n'est pas chargé → voir Erreur 1

---

### Test 2 : Vérifier la connexion

```javascript
window.testEcho()
```

**Résultat attendu** : "Echo status: Connected"
**Si "Not connected"** : Problème de connexion à Pusher

---

### Test 3 : Tester une notification manuelle

```javascript
window.OrderNotifications.test()
```

**Résultat attendu** : Une notification devrait apparaître en haut à droite
**Si rien** : Problème dans order-notifications.js

---

### Test 4 : Vérifier l'état de Pusher

```javascript
window.Echo.connector.pusher.connection.state
```

**Résultats possibles** :
- ✅ `"connected"` - Tout va bien
- ⚠️ `"connecting"` - En cours de connexion
- ❌ `"unavailable"` - Pusher indisponible (vérifier la clé)
- ❌ `"failed"` - Échec de connexion

---

### Test 5 : Activer les logs Pusher

```javascript
Pusher.logToConsole = true
```

Puis relancer le test :
```bash
php test-pusher.php
```

**Observer les logs** dans la console navigateur.

---

### Test 6 : Écouter manuellement le channel

```javascript
window.Echo.channel('commandes')
    .listen('.commande.nouvelle', function(data) {
        console.log('📦 Notification reçue:', data);
        alert('Notification reçue !');
    });
```

Puis envoyer un message test :
```bash
php test-pusher.php
```

**Si l'alert s'affiche** : Echo fonctionne, le problème est dans order-notifications.js
**Si rien** : Problème de connexion ou de channel

---

## ✅ ÉTAPE 3 : Vérifier le réseau

1. **Onglet Network** (F12 → Network)
2. **Filtrer** : "websocket" ou "ws"
3. **Recharger la page**

**Vous devriez voir** :
- Une connexion WebSocket à Pusher
- Statut : 101 Switching Protocols

**Si erreur 4xx/5xx** :
- Vérifier les credentials Pusher
- Vérifier le cluster (eu, us2, etc.)

---

## ✅ ÉTAPE 4 : Vérifier les scripts chargés

**Dans la console** :
```javascript
// Vérifier que les scripts sont chargés
typeof Pusher        // devrait être "function"
typeof Echo          // devrait être "function"
window.OrderNotifications  // devrait être un objet
```

---

## 🔧 Solutions rapides

### Solution 1 : Vider tous les caches

```bash
# Laravel
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Navigateur : Hard refresh
# Chrome/Firefox : Ctrl+Shift+R
# Safari : Cmd+Option+R
```

### Solution 2 : Réinstaller les dépendances

```bash
# NPM
rm -rf node_modules package-lock.json
npm install

# Si besoin
npm install --save laravel-echo pusher-js
```

### Solution 3 : Vérifier les fichiers

```bash
# Vérifier que les scripts existent
ls -la public/admin-assets/js/order-notifications.js
ls -la resources/js/echo-bootstrap.js

# Vérifier le contenu
head -20 public/admin-assets/js/order-notifications.js
```

---

## 📊 Checklist de diagnostic Frontend

Cocher chaque point dans la console navigateur (F12) :

```
[ ] window.Echo est défini (pas undefined)
[ ] window.Echo.connector.pusher.connection.state === "connected"
[ ] window.testEcho() retourne "Connected"
[ ] window.OrderNotifications est défini
[ ] window.OrderNotifications.test() affiche une notification
[ ] Pusher.logToConsole = true montre des logs
[ ] Onglet Network montre une connexion WebSocket
[ ] Aucune erreur rouge dans la console
```

---

## 🎯 Test complet

Suivez ces étapes dans l'ordre :

```javascript
// 1. Vérifier Echo
console.log('Echo:', window.Echo);

// 2. Vérifier Pusher
console.log('Pusher state:', window.Echo?.connector?.pusher?.connection?.state);

// 3. Activer les logs
Pusher.logToConsole = true;

// 4. Tester une notification
window.OrderNotifications.test();

// 5. Écouter le channel manuellement
window.Echo.channel('commandes').listen('.commande.nouvelle', function(data) {
    console.log('✅ Notification reçue:', data);
});
```

**Puis dans le terminal** :
```bash
php test-pusher.php
```

**Résultat attendu** : 
- Logs dans la console navigateur
- Message "✅ Notification reçue" avec les données
- Notification visuelle qui s'affiche

---

## 🆘 Si rien ne fonctionne

### Option 1 : Recréer les scripts

```bash
# Supprimer et recréer
rm public/admin-assets/js/order-notifications.js
# Puis recréer le fichier depuis la documentation
```

### Option 2 : Mode debug complet

**Ajouter temporairement dans master.blade.php** (après les scripts) :

```html
<script>
console.log('=== DEBUG NOTIFICATIONS ===');
console.log('1. Echo:', typeof window.Echo);
console.log('2. Pusher:', typeof Pusher);
console.log('3. OrderNotifications:', typeof window.OrderNotifications);

if (window.Echo) {
    console.log('4. Echo state:', window.Echo.connector.pusher.connection.state);
    
    // Écouter tous les events
    window.Echo.channel('commandes').listen('.commande.nouvelle', function(data) {
        console.log('🔔 EVENT REÇU:', data);
        alert('EVENT REÇU ! Vérifiez la console');
    });
} else {
    console.error('❌ Echo non chargé !');
}
console.log('=== FIN DEBUG ===');
</script>
```

**Recharger la page** et observer la console.

---

## 📱 Test sur un autre navigateur

Parfois, les extensions de navigateur bloquent les WebSockets.

**Essayer** :
1. Mode navigation privée
2. Autre navigateur (Chrome → Firefox ou vice-versa)
3. Désactiver les extensions (ad-blockers, privacy tools)

---

## 📞 Informations à fournir si le problème persiste

**Screenshot de la console navigateur** montrant :
1. Les erreurs (texte rouge)
2. Le résultat de `window.Echo`
3. Le résultat de `window.Echo.connector.pusher.connection.state`
4. Le résultat de `window.OrderNotifications.test()`

**View Source** (Ctrl+U) :
- Copier les lignes contenant `pusher-key` et `pusher-cluster`
- Copier la section des scripts Echo

---

**Bon débogage ! 🔍**
