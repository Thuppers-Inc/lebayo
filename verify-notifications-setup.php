#!/usr/bin/env php
<?php

/**
 * Script de vérification de l'installation des notifications temps réel
 * 
 * Usage: php verify-notifications-setup.php
 */

echo "\n";
echo "╔══════════════════════════════════════════════════════════════╗\n";
echo "║  Vérification de l'installation des notifications temps réel ║\n";
echo "╚══════════════════════════════════════════════════════════════╝\n";
echo "\n";

$checks = [];
$errors = [];
$warnings = [];

// 1. Vérifier les fichiers backend
echo "📁 Vérification des fichiers backend...\n";

$backendFiles = [
    'config/broadcasting.php' => 'Configuration Broadcasting',
    'app/Events/NouvelleCommande.php' => 'Event NouvelleCommande',
];

foreach ($backendFiles as $file => $description) {
    if (file_exists($file)) {
        echo "  ✅ $description\n";
        $checks[] = $description;
    } else {
        echo "  ❌ $description - MANQUANT\n";
        $errors[] = "$description ($file) est manquant";
    }
}

echo "\n";

// 2. Vérifier les fichiers frontend
echo "🎨 Vérification des fichiers frontend...\n";

$frontendFiles = [
    'resources/js/echo-bootstrap.js' => 'Configuration Echo',
    'public/admin-assets/js/order-notifications.js' => 'Script notifications',
    'public/admin-assets/css/order-notifications.css' => 'Styles notifications',
];

foreach ($frontendFiles as $file => $description) {
    if (file_exists($file)) {
        echo "  ✅ $description\n";
        $checks[] = $description;
    } else {
        echo "  ❌ $description - MANQUANT\n";
        $errors[] = "$description ($file) est manquant";
    }
}

echo "\n";

// 3. Vérifier le dossier audio
echo "🔊 Vérification du fichier audio...\n";

if (file_exists('public/sounds')) {
    echo "  ✅ Dossier sounds/ existe\n";
    $checks[] = "Dossier sounds";
    
    if (file_exists('public/sounds/notification.mp3')) {
        echo "  ✅ Fichier notification.mp3 présent\n";
        $checks[] = "Fichier audio";
    } else {
        echo "  ⚠️  Fichier notification.mp3 manquant (à ajouter manuellement)\n";
        $warnings[] = "Ajouter le fichier public/sounds/notification.mp3";
    }
} else {
    echo "  ❌ Dossier sounds/ manquant\n";
    $errors[] = "Créer le dossier public/sounds/";
}

echo "\n";

// 4. Vérifier les dépendances NPM
echo "📦 Vérification des dépendances NPM...\n";

if (file_exists('package.json')) {
    $packageJson = json_decode(file_get_contents('package.json'), true);
    
    $npmDeps = ['laravel-echo', 'pusher-js'];
    foreach ($npmDeps as $dep) {
        if (isset($packageJson['dependencies'][$dep]) || isset($packageJson['devDependencies'][$dep])) {
            echo "  ✅ $dep installé\n";
            $checks[] = "NPM: $dep";
        } else {
            echo "  ❌ $dep manquant\n";
            $errors[] = "Installer $dep via npm";
        }
    }
} else {
    echo "  ❌ package.json introuvable\n";
    $errors[] = "package.json introuvable";
}

echo "\n";

// 5. Vérifier les dépendances Composer
echo "🎼 Vérification des dépendances Composer...\n";

if (file_exists('composer.json')) {
    $composerJson = json_decode(file_get_contents('composer.json'), true);
    
    if (isset($composerJson['require']['pusher/pusher-php-server'])) {
        echo "  ✅ pusher/pusher-php-server installé\n";
        $checks[] = "Composer: Pusher";
    } else {
        echo "  ❌ pusher/pusher-php-server manquant\n";
        $errors[] = "Installer pusher/pusher-php-server via composer";
    }
} else {
    echo "  ❌ composer.json introuvable\n";
    $errors[] = "composer.json introuvable";
}

echo "\n";

// 6. Vérifier la configuration .env
echo "⚙️  Vérification de la configuration .env...\n";

if (file_exists('.env')) {
    $envContent = file_get_contents('.env');
    
    $envVars = [
        'BROADCAST_DRIVER' => 'Driver de broadcast',
        'PUSHER_APP_ID' => 'Pusher App ID',
        'PUSHER_APP_KEY' => 'Pusher Key',
        'PUSHER_APP_SECRET' => 'Pusher Secret',
        'PUSHER_APP_CLUSTER' => 'Pusher Cluster',
    ];
    
    foreach ($envVars as $var => $description) {
        if (strpos($envContent, $var) !== false) {
            // Vérifier si la valeur n'est pas vide
            preg_match("/$var=(.*)/", $envContent, $matches);
            if (isset($matches[1]) && trim($matches[1]) !== '') {
                echo "  ✅ $description configuré\n";
                $checks[] = "ENV: $description";
            } else {
                echo "  ⚠️  $description présent mais vide\n";
                $warnings[] = "Configurer $var dans .env";
            }
        } else {
            echo "  ⚠️  $description manquant\n";
            $warnings[] = "Ajouter $var dans .env";
        }
    }
} else {
    echo "  ❌ Fichier .env introuvable\n";
    $errors[] = "Créer le fichier .env";
}

echo "\n";

// 7. Vérifier les modifications dans CheckoutController
echo "🔧 Vérification des modifications code...\n";

if (file_exists('app/Http/Controllers/CheckoutController.php')) {
    $checkoutContent = file_get_contents('app/Http/Controllers/CheckoutController.php');
    
    if (strpos($checkoutContent, 'use App\Events\NouvelleCommande') !== false) {
        echo "  ✅ Import NouvelleCommande présent\n";
        $checks[] = "Import event";
    } else {
        echo "  ❌ Import NouvelleCommande manquant\n";
        $errors[] = "Ajouter 'use App\Events\NouvelleCommande;' dans CheckoutController";
    }
    
    if (strpos($checkoutContent, 'event(new NouvelleCommande') !== false) {
        echo "  ✅ Déclenchement event présent\n";
        $checks[] = "Déclenchement event";
    } else {
        echo "  ❌ Déclenchement event manquant\n";
        $errors[] = "Ajouter 'event(new NouvelleCommande(\$order));' dans la méthode store()";
    }
} else {
    echo "  ❌ CheckoutController introuvable\n";
    $errors[] = "app/Http/Controllers/CheckoutController.php introuvable";
}

echo "\n";

// 8. Vérifier la documentation
echo "📚 Vérification de la documentation...\n";

$docFiles = [
    'NOTIFICATIONS-TEMPS-REEL.md' => 'Documentation technique',
    'INSTALLATION-NOTIFICATIONS.md' => 'Guide installation',
    'RESUME-IMPLEMENTATION.md' => 'Résumé implémentation',
];

foreach ($docFiles as $file => $description) {
    if (file_exists($file)) {
        echo "  ✅ $description\n";
        $checks[] = "Doc: $description";
    } else {
        echo "  ⚠️  $description manquant\n";
        $warnings[] = "$description ($file) manquant";
    }
}

echo "\n";
echo "══════════════════════════════════════════════════════════════\n";
echo "\n";

// Résumé
$totalChecks = count($checks);
$totalErrors = count($errors);
$totalWarnings = count($warnings);

echo "📊 RÉSUMÉ\n";
echo "─────────\n";
echo "✅ Vérifications réussies : $totalChecks\n";
echo "❌ Erreurs critiques     : $totalErrors\n";
echo "⚠️  Avertissements        : $totalWarnings\n";
echo "\n";

if ($totalErrors > 0) {
    echo "❌ ERREURS À CORRIGER :\n";
    foreach ($errors as $i => $error) {
        echo "   " . ($i + 1) . ". $error\n";
    }
    echo "\n";
}

if ($totalWarnings > 0) {
    echo "⚠️  ACTIONS RECOMMANDÉES :\n";
    foreach ($warnings as $i => $warning) {
        echo "   " . ($i + 1) . ". $warning\n";
    }
    echo "\n";
}

if ($totalErrors === 0 && $totalWarnings === 0) {
    echo "🎉 PARFAIT ! Tout est en place.\n";
    echo "\n";
    echo "Prochaines étapes :\n";
    echo "1. Configurer Pusher dans .env (si pas déjà fait)\n";
    echo "2. Ajouter le fichier notification.mp3\n";
    echo "3. Lancer 'php artisan queue:work'\n";
    echo "4. Tester en créant une commande\n";
    echo "\n";
} elseif ($totalErrors === 0) {
    echo "✅ Installation complète mais configuration requise.\n";
    echo "   Consultez les avertissements ci-dessus.\n";
    echo "\n";
} else {
    echo "❌ Installation incomplète.\n";
    echo "   Corrigez les erreurs ci-dessus avant de continuer.\n";
    echo "\n";
}

echo "📖 Pour plus d'aide : consultez INSTALLATION-NOTIFICATIONS.md\n";
echo "\n";

// Code de sortie
exit($totalErrors > 0 ? 1 : 0);
