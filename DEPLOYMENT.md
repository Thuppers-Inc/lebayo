# Commandes de déploiement en production

## Commandes à exécuter après le déploiement

### 1. Migrations de base de données
```bash
php artisan migrate
```
Cette commande ajoutera les colonnes `slug` aux tables `commerce_types` et `commerces`.

### 2. Génération des slugs pour les données existantes
```bash
php artisan generate:slugs
```
Cette commande génère les slugs pour tous les commerces et types de commerce existants qui n'en ont pas encore.

### 3. Compilation des assets (CSS/JS)
```bash
npm run build
```
Cette commande compile les fichiers CSS et JS avec toutes les modifications responsive.

### 4. Optimisation et cache (optionnel mais recommandé)
```bash
# Vider tous les caches
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# Recréer les caches pour la production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Permissions (si nécessaire)
```bash
# S'assurer que les permissions sont correctes
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Résumé rapide (toutes les commandes en une fois)
```bash
# 1. Migrations
php artisan migrate

# 2. Génération des slugs
php artisan generate:slugs

# 3. Compilation des assets
npm run build

# 4. Optimisation
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Notes importantes

- ⚠️ **Avant de mettre en production**, testez d'abord en local que tout fonctionne
- ⚠️ **Sauvegardez votre base de données** avant d'exécuter les migrations
- ⚠️ Si vous utilisez un serveur de production, assurez-vous que `npm` est installé pour compiler les assets
- ⚠️ Après le déploiement, testez les URLs avec slugs : `/restaurants`, `/commerce/nom-du-commerce`

## Vérification post-déploiement

1. Vérifier que les slugs sont générés :
```bash
php artisan tinker
>>> App\Models\Commerce::whereNotNull('slug')->count()
>>> App\Models\CommerceType::whereNotNull('slug')->count()
```

2. Tester une URL avec slug :
- `/restaurants` (ou le slug de votre type de commerce)
- `/commerce/nom-du-commerce` (avec un slug réel)

3. Vérifier que les pages sont responsive sur mobile/webview
