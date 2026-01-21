#!/bin/bash

# Script de vérification rapide
# Usage: ./quick-check.sh

echo ""
echo "🔍 Vérification rapide du système de notifications"
echo "═══════════════════════════════════════════════════"
echo ""

# Couleurs
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# 1. Vérifier .env
echo "📋 Configuration .env :"
if grep -q "BROADCAST_DRIVER=pusher" .env 2>/dev/null; then
    echo -e "  ${GREEN}✅${NC} BROADCAST_DRIVER=pusher"
else
    echo -e "  ${RED}❌${NC} BROADCAST_DRIVER non configuré"
fi

if grep -q "PUSHER_APP_KEY=" .env 2>/dev/null && [ -n "$(grep PUSHER_APP_KEY= .env | cut -d= -f2)" ]; then
    echo -e "  ${GREEN}✅${NC} PUSHER_APP_KEY configuré"
else
    echo -e "  ${RED}❌${NC} PUSHER_APP_KEY manquant"
fi

echo ""

# 2. Vérifier Queue Worker
echo "⚙️  Queue Worker :"
if pgrep -f "queue:work" > /dev/null; then
    echo -e "  ${GREEN}✅${NC} Queue worker en cours d'exécution"
    echo "     PID: $(pgrep -f 'queue:work')"
else
    echo -e "  ${RED}❌${NC} Queue worker NON lancé"
    echo "     Lancez: php artisan queue:work"
fi

echo ""

# 3. Vérifier Serveur Laravel
echo "🌐 Serveur Laravel :"
if pgrep -f "artisan serve" > /dev/null; then
    echo -e "  ${GREEN}✅${NC} Serveur en cours d'exécution"
else
    echo -e "  ${YELLOW}⚠️${NC}  Serveur pas détecté"
    echo "     Lancez: php artisan serve"
fi

echo ""

# 4. Vérifier les fichiers
echo "📁 Fichiers essentiels :"
files=(
    "app/Events/NouvelleCommande.php"
    "config/broadcasting.php"
    "public/admin-assets/js/order-notifications.js"
)

for file in "${files[@]}"; do
    if [ -f "$file" ]; then
        echo -e "  ${GREEN}✅${NC} $file"
    else
        echo -e "  ${RED}❌${NC} $file"
    fi
done

echo ""

# 5. Vérifier le fichier audio
echo "🔊 Fichier audio :"
if [ -f "public/sounds/notification.mp3" ]; then
    echo -e "  ${GREEN}✅${NC} notification.mp3 présent"
else
    echo -e "  ${YELLOW}⚠️${NC}  notification.mp3 manquant"
    echo "     Ajoutez le fichier dans public/sounds/"
fi

echo ""

# 6. Dernières lignes du log
echo "📝 Derniers logs Laravel :"
if [ -f "storage/logs/laravel.log" ]; then
    echo "$(tail -3 storage/logs/laravel.log | sed 's/^/     /')"
else
    echo -e "  ${YELLOW}⚠️${NC}  Aucun log trouvé"
fi

echo ""
echo "═══════════════════════════════════════════════════"

# Résumé
errors=0
warnings=0

if ! grep -q "BROADCAST_DRIVER=pusher" .env 2>/dev/null; then
    ((errors++))
fi

if ! pgrep -f "queue:work" > /dev/null; then
    ((errors++))
fi

if [ $errors -eq 0 ]; then
    echo -e "${GREEN}✅ Tout semble bon !${NC}"
    echo ""
    echo "Pour tester :"
    echo "  1. php test-pusher.php"
    echo "  2. Créer une commande sur le site"
    echo ""
else
    echo -e "${RED}❌ $errors problème(s) détecté(s)${NC}"
    echo ""
    echo "Consultez DEBUG-NOTIFICATIONS.md pour plus d'aide"
    echo ""
fi
