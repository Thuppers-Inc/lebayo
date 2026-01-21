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
