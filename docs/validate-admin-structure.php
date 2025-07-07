<?php
/**
 * Script de validation de la structure modulaire du panel admin
 * Usage: php docs/validate-admin-structure.php
 */

echo "🔍 Validation de la structure modulaire du panel admin...\n\n";

// Fichiers requis
$requiredFiles = [
    'resources/views/admin/layouts/master.blade.php',
    'resources/views/admin/partials/sidebar.blade.php', 
    'resources/views/admin/partials/topbar.blade.php',
    'resources/views/admin/partials/footer.blade.php',
    'resources/views/admin/dashboard/index.blade.php',
];

// Dossiers requis
$requiredDirectories = [
    'public/admin-assets',
    'resources/views/admin',
    'resources/views/admin/layouts',
    'resources/views/admin/partials',
    'docs'
];

$errors = [];
$warnings = [];

// Vérification des dossiers
echo "📁 Vérification des dossiers...\n";
foreach ($requiredDirectories as $dir) {
    if (!is_dir($dir)) {
        $errors[] = "❌ Dossier manquant: $dir";
    } else {
        echo "✅ $dir\n";
    }
}

// Vérification des fichiers
echo "\n📄 Vérification des fichiers...\n";
foreach ($requiredFiles as $file) {
    if (!file_exists($file)) {
        $errors[] = "❌ Fichier manquant: $file";
    } else {
        echo "✅ $file\n";
        
        // Vérifications spécifiques
        $content = file_get_contents($file);
        
        if (strpos($file, 'master.blade.php') !== false) {
            if (strpos($content, '@yield(\'content\')') === false) {
                $warnings[] = "⚠️ Section @yield('content') manquante dans master.blade.php";
            }
            if (strpos($content, '@stack(\'scripts\')') === false) {
                $warnings[] = "⚠️ Section @stack('scripts') manquante dans master.blade.php";
            }
        }
        
        if (strpos($file, 'dashboard/index.blade.php') !== false) {
            if (strpos($content, '@extends(\'admin.layouts.master\')') === false) {
                $warnings[] = "⚠️ @extends manquant dans dashboard/index.blade.php";
            }
        }
    }
}

// Vérification de la route
echo "\n🛣️ Vérification de la route...\n";
$routeFile = 'routes/web.php';
if (file_exists($routeFile)) {
    $routes = file_get_contents($routeFile);
    if (strpos($routes, "'/admin'") !== false || strpos($routes, 'admin.dashboard') !== false) {
        echo "✅ Route /admin configurée\n";
    } else {
        $warnings[] = "⚠️ Route /admin peut-être manquante dans routes/web.php";
    }
} else {
    $errors[] = "❌ Fichier routes/web.php manquant";
}

// Vérification du conflit de dossier admin
echo "\n🔄 Vérification du conflit dossier admin...\n";
if (is_dir('public/admin')) {
    $errors[] = "❌ CONFLIT: Le dossier public/admin existe encore! Renommez-le en public/admin-assets";
} else {
    echo "✅ Pas de conflit avec public/admin\n";
}

// Vérification des assets
echo "\n🎨 Vérification des assets...\n";
$assetDirs = [
    'public/admin-assets/assets/css',
    'public/admin-assets/assets/js', 
    'public/admin-assets/assets/img',
    'public/admin-assets/assets/vendor'
];

foreach ($assetDirs as $dir) {
    if (is_dir($dir)) {
        echo "✅ $dir\n";
    } else {
        $warnings[] = "⚠️ Dossier d'assets manquant: $dir";
    }
}

// Affichage des résultats
echo "\n" . str_repeat("=", 50) . "\n";
echo "📊 RÉSULTATS DE LA VALIDATION\n";
echo str_repeat("=", 50) . "\n";

if (empty($errors)) {
    echo "🎉 SUCCÈS: Structure modulaire correctement mise en place!\n";
} else {
    echo "💥 ERREURS CRITIQUES:\n";
    foreach ($errors as $error) {
        echo "   $error\n";
    }
}

if (!empty($warnings)) {
    echo "\n⚠️ AVERTISSEMENTS:\n";
    foreach ($warnings as $warning) {
        echo "   $warning\n";
    }
}

echo "\n📋 CONSEILS:\n";
echo "• Testez la route /admin dans votre navigateur\n";
echo "• Vérifiez que les assets se chargent correctement\n";
echo "• Consultez la documentation: docs/admin-panel-structure.md\n";

if (empty($errors)) {
    echo "\n🚀 Votre panel d'administration est prêt à l'emploi!\n";
    exit(0);
} else {
    echo "\n🔧 Corrigez les erreurs critiques avant de continuer.\n";
    exit(1);
} 