# Configuration .env pour les notifications temps réel

Ajouter ces lignes dans votre fichier `.env` :

```env
# Laravel Echo / Pusher Configuration
# Pour les notifications temps réel des commandes
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=votre_app_id
PUSHER_APP_KEY=votre_app_key
PUSHER_APP_SECRET=votre_app_secret
PUSHER_APP_CLUSTER=eu

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

## Comment obtenir les credentials Pusher

1. Aller sur https://pusher.com/
2. Créer un compte gratuit
3. Créer une nouvelle App
4. Choisir le cluster le plus proche (par défaut: `eu`)
5. Copier les credentials (App ID, Key, Secret, Cluster)
6. Les coller dans votre fichier `.env`

## Note de sécurité

- ⚠️ Ne JAMAIS commiter le fichier `.env` dans Git
- ⚠️ Le `PUSHER_APP_SECRET` ne doit JAMAIS être exposé côté client
- ✅ Seule la `PUSHER_APP_KEY` (clé publique) est exposée dans les meta tags
