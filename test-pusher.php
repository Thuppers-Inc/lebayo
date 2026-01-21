#!/usr/bin/env php
<?php

/**
 * Script de test Pusher
 * 
 * Ce script teste directement la connexion à Pusher
 * sans passer par Laravel Broadcasting
 */

require __DIR__.'/vendor/autoload.php';

echo "\n";
echo "🔌 Test de connexion Pusher\n";
echo "═══════════════════════════\n\n";

// Charger les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Récupérer les credentials
$appId = $_ENV['PUSHER_APP_ID'] ?? '';
$appKey = $_ENV['PUSHER_APP_KEY'] ?? '';
$appSecret = $_ENV['PUSHER_APP_SECRET'] ?? '';
$appCluster = $_ENV['PUSHER_APP_CLUSTER'] ?? 'eu';

// Vérifier que les credentials sont présents
echo "📋 Vérification des credentials...\n";
$errors = [];

if (empty($appId)) {
    echo "  ❌ PUSHER_APP_ID manquant\n";
    $errors[] = 'PUSHER_APP_ID';
}
if (empty($appKey)) {
    echo "  ❌ PUSHER_APP_KEY manquant\n";
    $errors[] = 'PUSHER_APP_KEY';
}
if (empty($appSecret)) {
    echo "  ❌ PUSHER_APP_SECRET manquant\n";
    $errors[] = 'PUSHER_APP_SECRET';
}

if (!empty($errors)) {
    echo "\n❌ Credentials Pusher manquants dans .env\n";
    echo "Ajoutez les variables suivantes dans votre fichier .env :\n\n";
    foreach ($errors as $var) {
        echo "  $var=votre_valeur\n";
    }
    echo "\nConsultez ENV-EXAMPLE-PUSHER.md pour plus d'infos.\n\n";
    exit(1);
}

echo "  ✅ APP_ID: " . substr($appId, 0, 4) . "***\n";
echo "  ✅ APP_KEY: " . substr($appKey, 0, 6) . "***\n";
echo "  ✅ APP_SECRET: ***\n";
echo "  ✅ CLUSTER: $appCluster\n";
echo "\n";

// Créer l'instance Pusher
try {
    $pusher = new Pusher\Pusher(
        $appKey,
        $appSecret,
        $appId,
        [
            'cluster' => $appCluster,
            'useTLS' => true
        ]
    );
    
    echo "✅ Instance Pusher créée\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur création Pusher : " . $e->getMessage() . "\n\n";
    exit(1);
}

// Préparer le message de test
$testData = [
    'order' => [
        'id' => 999,
        'order_number' => 'TEST-' . date('His'),
        'user_name' => 'Client Test',
        'items_count' => 1,
        'formatted_total' => '1 000 F',
        'total' => 1000,
        'created_at' => date('d/m/Y H:i:s')
    ]
];

echo "📤 Envoi d'un message de test à Pusher...\n";
echo "  Channel: commandes\n";
echo "  Event: commande.nouvelle\n";
echo "  Data: " . json_encode($testData['order']) . "\n\n";

// Envoyer le message
try {
    $result = $pusher->trigger('commandes', 'commande.nouvelle', $testData);
    
    echo "✅ Message envoyé avec succès à Pusher !\n\n";
    
    echo "👀 Maintenant :\n";
    echo "  1. Ouvrez le dashboard admin dans votre navigateur\n";
    echo "  2. Ouvrez la console (F12)\n";
    echo "  3. Vous devriez voir la notification apparaître\n\n";
    
    echo "🔍 Pour vérifier sur Pusher :\n";
    echo "  1. Allez sur https://dashboard.pusher.com/\n";
    echo "  2. Sélectionnez votre app\n";
    echo "  3. Onglet 'Debug Console'\n";
    echo "  4. Vous devriez voir l'event qui vient d'être envoyé\n\n";
    
    echo "✅ Test terminé avec succès !\n\n";
    
} catch (Exception $e) {
    echo "❌ Erreur lors de l'envoi : " . $e->getMessage() . "\n\n";
    
    echo "💡 Causes possibles :\n";
    echo "  - Credentials invalides (vérifier sur dashboard.pusher.com)\n";
    echo "  - Mauvais cluster (vérifier PUSHER_APP_CLUSTER)\n";
    echo "  - Pas de connexion internet\n";
    echo "  - Plan Pusher expiré ou quota dépassé\n\n";
    
    exit(1);
}
