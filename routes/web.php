<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CommerceTypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommerceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LivreurController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\DeliverySettingsController;

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('redirect.admin');

// Routes de recherche
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search')->middleware('redirect.admin');
Route::get('/api/search/autocomplete', [App\Http\Controllers\SearchController::class, 'autocomplete'])->name('search.autocomplete');

// Routes d'authentification
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);

Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Routes d'administration (protégées par authentification admin)
Route::prefix('admin')->name('admin.')->group(function () {
    // Point d'entrée commun pour modérateurs et admins
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    })->middleware(['moderator']);

    // Dashboard accessible aux modérateurs (vue limitée) et admins (vue complète)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware(['moderator']);

    // ===== ROUTES POUR MODÉRATEURS (accès limité) =====
    Route::middleware(['moderator'])->group(function () {
        // Gestion des commandes (modération)
        Route::resource('orders', OrderController::class)->only(['index', 'show']);
        Route::post('orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::post('orders/{order}/update-payment-status', [OrderController::class, 'updatePaymentStatus'])->name('orders.update-payment-status');

        // Gestion des demandes de course (modération)
        Route::resource('errand-requests', \App\Http\Controllers\Admin\ErrandRequestController::class)->only(['index', 'show']);
        Route::post('errand-requests/{errandRequest}/update-status', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'updateStatus'])->name('errand-requests.update-status');
        Route::get('errand-requests/{errandRequest}/logs', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'logs'])->name('errand-requests.logs');
        Route::get('errand-requests-stats', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'stats'])->name('errand-requests.stats');
        Route::get('errand-requests-export', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'export'])->name('errand-requests.export');

        // Consultation des clients (lecture seule pour modérateurs)
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
        Route::get('clients/details', [ClientController::class, 'details'])->name('clients.details');
        Route::get('clients/{client}', [ClientController::class, 'show'])->name('clients.show');
        Route::get('clients/{client}/orders', [ClientController::class, 'orders'])->name('clients.orders');
        Route::get('clients/{client}/addresses', [ClientController::class, 'addresses'])->name('clients.addresses');

        // Gestion limitée des produits (toggle disponibilité seulement)
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('commerces/{commerce}/products', [ProductController::class, 'index'])->name('commerce.products.index');
        Route::post('products/{product}/toggle-availability', [ProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
    });

    // ===== ROUTES POUR ADMINISTRATEURS COMPLETS SEULEMENT =====
    Route::middleware(['admin'])->group(function () {
        // Gestion des types de commerce (admin seulement)
        Route::resource('commerce-types', CommerceTypeController::class);
        Route::post('commerce-types/{commerceType}/toggle-status', [CommerceTypeController::class, 'toggleStatus'])
            ->name('commerce-types.toggle-status');

        // Gestion des catégories (admin seulement)
        Route::resource('categories', CategoryController::class);
        Route::post('categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])
            ->name('categories.toggle-status');

        // Gestion complète des commerces (admin seulement)
        Route::resource('commerces', CommerceController::class);
        Route::post('commerces/{commerce}/toggle-status', [CommerceController::class, 'toggleStatus'])
            ->name('commerces.toggle-status');

        // Gestion complète des produits (admin seulement)
        Route::resource('products', ProductController::class)->except(['index']); // index déjà défini pour modérateurs
        Route::post('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');

        // Routes spécifiques pour les produits d'un commerce
        Route::get('commerces/{commerce}/products/create', [ProductController::class, 'create'])
            ->name('commerce.products.create');

        // Gestion des livreurs (admin seulement)
        Route::resource('livreurs', LivreurController::class);
        Route::post('livreurs/{livreur}/toggle-status', [LivreurController::class, 'toggleStatus'])->name('livreurs.toggle-status');

        // Gestion complète des clients (admin seulement)
        Route::post('clients', [ClientController::class, 'store'])->name('clients.store');
        Route::get('clients/create', [ClientController::class, 'create'])->name('clients.create');
        Route::get('clients/{client}/edit', [ClientController::class, 'edit'])->name('clients.edit');
        Route::put('clients/{client}', [ClientController::class, 'update'])->name('clients.update');
        Route::delete('clients/{client}', [ClientController::class, 'destroy'])->name('clients.destroy');
        Route::get('clients-export', [ClientController::class, 'export'])->name('clients.export');
        Route::get('clients-details-export', [ClientController::class, 'exportDetails'])->name('clients.details.export');
        Route::get('clients-top-orders-export', [ClientController::class, 'exportTopByOrders'])->name('clients.top.orders.export');
        Route::get('clients-top-revenue-export', [ClientController::class, 'exportTopByRevenue'])->name('clients.top.revenue.export');
        Route::get('clients-recent-export', [ClientController::class, 'exportRecentClients'])->name('clients.recent.export');
        Route::get('clients-active-export', [ClientController::class, 'exportActiveClients'])->name('clients.active.export');

        // Gestion complète des utilisateurs (admin seulement)
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::get('users/{user}/orders', [UserController::class, 'orders'])->name('users.orders');
        Route::get('users/{user}/addresses', [UserController::class, 'addresses'])->name('users.addresses');
        Route::get('users-export', [UserController::class, 'export'])->name('users.export');

        // Gestion des paramètres de livraison (admin seulement)
        Route::resource('delivery-settings', DeliverySettingsController::class);
        Route::post('delivery-settings/{deliverySetting}/activate', [DeliverySettingsController::class, 'activate'])->name('delivery-settings.activate');

        // Gestion complète des demandes de course (admin seulement - création, modification, suppression)
        Route::resource('errand-requests', \App\Http\Controllers\Admin\ErrandRequestController::class)->except(['index', 'show', 'update-status', 'logs', 'stats', 'export']);
        Route::post('errand-requests/{errandRequest}/create', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'create'])->name('errand-requests.create');
        Route::post('errand-requests', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'store'])->name('errand-requests.store');
        Route::get('errand-requests/{errandRequest}/edit', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'edit'])->name('errand-requests.edit');
        Route::put('errand-requests/{errandRequest}', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'update'])->name('errand-requests.update');
        Route::delete('errand-requests/{errandRequest}', [\App\Http\Controllers\Admin\ErrandRequestController::class, 'destroy'])->name('errand-requests.destroy');

        // Gestion du profil admin
        Route::get('profile', [\App\Http\Controllers\Admin\AdminProfileController::class, 'index'])->name('profile.index');
        Route::post('profile/update', [\App\Http\Controllers\Admin\AdminProfileController::class, 'update'])->name('profile.update');
        Route::post('profile/update-password', [\App\Http\Controllers\Admin\AdminProfileController::class, 'updatePassword'])->name('profile.update-password');
    });

    // ===== ROUTES POUR SUPER ADMINS SEULEMENT =====
    Route::middleware(['admin:super_admin'])->group(function () {
        // Fonctionnalités sensibles réservées aux super admins
        // (à ajouter selon les besoins)
    });

    // Pages utilitaires (accessible à tous les niveaux)
    Route::middleware(['moderator'])->group(function () {
        Route::get('/blank', function () {
            return view('admin.blank');
        })->name('blank');

        Route::get('/theme-demo', function () {
            return view('admin.theme-demo');
        })->name('theme.demo');
    });
});

// ===== ROUTES ADMIN SUPPRIMÉES =====
// Toutes les routes admin sont maintenant dans le groupe prefix('admin') protégé par middleware
// Ces routes dupliquées ont été supprimées pour éviter les contournements de sécurité

// Routes du panier
Route::prefix('cart')->name('cart.')->middleware('redirect.admin')->group(function () {
    Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('add');
    Route::patch('/update/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('remove');
    Route::delete('/remove-item/{cartItem}', [App\Http\Controllers\CartController::class, 'removeItem'])->name('remove-item');
    Route::delete('/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('clear');
    Route::get('/count', [App\Http\Controllers\CartController::class, 'count'])->name('count');
});

// Routes de géolocalisation (API publique)
Route::post('/api/reverse-geocode', [\App\Http\Controllers\LocationController::class, 'reverseGeocode'])->name('api.reverse-geocode');
Route::get('/api/location-by-ip', [\App\Http\Controllers\LocationController::class, 'getLocationByIp'])->name('api.location-by-ip');

// Routes du processus de checkout (protégées par authentification)
Route::prefix('checkout')->name('checkout.')->middleware(['auth', 'redirect.admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\CheckoutController::class, 'index'])->name('index');
    Route::get('/address', [App\Http\Controllers\CheckoutController::class, 'address'])->name('address');
    Route::get('/payment', [App\Http\Controllers\CheckoutController::class, 'payment'])->name('payment');
    Route::get('/confirm', [App\Http\Controllers\CheckoutController::class, 'confirm'])->name('confirm');
    Route::post('/store', [App\Http\Controllers\CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{orderNumber}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('success');
    Route::post('/address/store', [App\Http\Controllers\CheckoutController::class, 'storeAddress'])->name('address.store');
});

// Routes du profil utilisateur (protégées par authentification)
Route::prefix('profile')->name('profile.')->middleware(['auth', 'redirect.admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\ProfileController::class, 'index'])->name('index');
    Route::post('/update', [App\Http\Controllers\ProfileController::class, 'update'])->name('update');
    Route::post('/update-password', [App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('update-password');

    // Gestion des commandes
    Route::get('/orders', [App\Http\Controllers\ProfileController::class, 'orders'])->name('orders');
    Route::get('/orders/{order}', [App\Http\Controllers\ProfileController::class, 'showOrder'])->name('orders.show');

    // Gestion des adresses
    Route::get('/addresses', [App\Http\Controllers\ProfileController::class, 'addresses'])->name('addresses');
    Route::post('/addresses', [App\Http\Controllers\ProfileController::class, 'storeAddress'])->name('addresses.store');
    Route::put('/addresses/{address}', [App\Http\Controllers\ProfileController::class, 'updateAddress'])->name('addresses.update');
    Route::delete('/addresses/{address}', [App\Http\Controllers\ProfileController::class, 'deleteAddress'])->name('addresses.delete');
    Route::post('/addresses/{address}/set-default', [App\Http\Controllers\ProfileController::class, 'setDefaultAddress'])->name('addresses.set-default');

    // Suppression de compte
    Route::get('/delete-account', [App\Http\Controllers\ProfileController::class, 'showDeleteAccount'])->name('delete-account');
    Route::delete('/delete-account', [App\Http\Controllers\ProfileController::class, 'deleteAccount'])->name('delete-account.destroy');
});

// Routes pour les demandes de course
Route::middleware(['auth'])->group(function () {
    Route::get('/errand/create', [App\Http\Controllers\ErrandController::class, 'create'])->name('errand.create');
    Route::post('/errand', [App\Http\Controllers\ErrandController::class, 'store'])->name('errand.store');
    Route::get('/errand/{errandRequest}/success', [App\Http\Controllers\ErrandController::class, 'success'])->name('errand.success');
    Route::get('/errand', [App\Http\Controllers\ErrandController::class, 'index'])->name('errand.index');
    Route::get('/errand/{errandRequest}', [App\Http\Controllers\ErrandController::class, 'show'])->name('errand.show');
    Route::post('/errand/{errandRequest}/cancel', [App\Http\Controllers\ErrandController::class, 'cancel'])->name('errand.cancel');
});

// Route pour la politique de confidentialité
Route::get('/privacy-policy', function () {
    return view('policy-privacy');
})->name('privacy-policy')->middleware('redirect.admin');

// Route pour le téléchargement de l'application mobile
Route::get('/download', function () {
    // Calculer les statistiques dynamiques
    $deliveredOrders = \App\Models\Order::where('status', \App\Models\Order::STATUS_DELIVERED)->count();
    $completedErrands = \App\Models\ErrandRequest::where('status', \App\Models\ErrandRequest::STATUS_COMPLETED)->count();

    $stats = [
        'total_commerces' => \App\Models\Commerce::active()->count(),
        'active_users' => \App\Models\User::where('account_type', \App\Models\AccountType::CLIENT)
            ->whereHas('orders')
            ->count(),
        'delivered_orders' => $deliveredOrders + $completedErrands, // Commandes livrées + Errands effectués
        'rating' => '4.8★' // Note statique
    ];

    return view('download', compact('stats'));
})->name('download')->middleware('redirect.admin');

// Route pour afficher un commerce spécifique (doit être AVANT les types pour éviter les conflits)
// Exemple: /commerce/chawama-du-grand-carrefour-commerce
Route::get('/commerce/{commerce}', [App\Http\Controllers\RestaurantController::class, 'show'])
    ->name('commerce.show')
    ->middleware('redirect.admin');

// Routes dynamiques pour les types de commerce (doivent être en dernier pour éviter les conflits)
// Exemple: /restaurants, /magasins, etc.
Route::get('/{commerceType}', [App\Http\Controllers\CommerceTypeController::class, 'show'])
    ->where('commerceType', '^(?!admin|login|register|search|cart|checkout|profile|errand|privacy-policy|download|api|commerce).+')
    ->name('commerce-type.show')
    ->middleware('redirect.admin');

