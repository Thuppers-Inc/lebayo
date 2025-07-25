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

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

// Route pour afficher un restaurant spécifique
Route::get('/restaurant/{commerce}', [App\Http\Controllers\RestaurantController::class, 'show'])->name('restaurant.show');

// Route pour afficher les commerces d'un type spécifique
Route::get('/type/{commerceType}', [App\Http\Controllers\CommerceTypeController::class, 'show'])->name('commerce-type.show');

// Routes de recherche
Route::get('/search', [App\Http\Controllers\SearchController::class, 'search'])->name('search');
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
        
        // Consultation des clients (lecture seule pour modérateurs)
        Route::get('clients', [ClientController::class, 'index'])->name('clients.index');
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
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [App\Http\Controllers\CartController::class, 'index'])->name('index');
    Route::post('/add/{product}', [App\Http\Controllers\CartController::class, 'add'])->name('add');
    Route::patch('/update/{product}', [App\Http\Controllers\CartController::class, 'update'])->name('update');
    Route::delete('/remove/{product}', [App\Http\Controllers\CartController::class, 'remove'])->name('remove');
    Route::delete('/clear', [App\Http\Controllers\CartController::class, 'clear'])->name('clear');
    Route::get('/count', [App\Http\Controllers\CartController::class, 'count'])->name('count');
});

// Routes de géolocalisation (API publique)
Route::post('/api/reverse-geocode', [\App\Http\Controllers\LocationController::class, 'reverseGeocode'])->name('api.reverse-geocode');
Route::get('/api/location-by-ip', [\App\Http\Controllers\LocationController::class, 'getLocationByIp'])->name('api.location-by-ip');

// Routes du processus de checkout (protégées par authentification)
Route::prefix('checkout')->name('checkout.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CheckoutController::class, 'index'])->name('index');
    Route::get('/address', [App\Http\Controllers\CheckoutController::class, 'address'])->name('address');
    Route::get('/payment', [App\Http\Controllers\CheckoutController::class, 'payment'])->name('payment');
    Route::get('/confirm', [App\Http\Controllers\CheckoutController::class, 'confirm'])->name('confirm');
    Route::post('/store', [App\Http\Controllers\CheckoutController::class, 'store'])->name('store');
    Route::get('/success/{orderNumber}', [App\Http\Controllers\CheckoutController::class, 'success'])->name('success');
    Route::post('/address/store', [App\Http\Controllers\CheckoutController::class, 'storeAddress'])->name('address.store');
});

// Routes du profil utilisateur (protégées par authentification)
Route::prefix('profile')->name('profile.')->middleware(['auth'])->group(function () {
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
});

// Route de test pour vérifier les calculs du panier (à supprimer après test)
Route::get('/test-cart-calculations', function() {
    $user = Auth::user();
    if (!$user) {
        return response()->json(['error' => 'Utilisateur non connecté']);
    }
    
    $cart = \App\Models\Cart::getForUser($user->id);
    if (!$cart) {
        return response()->json(['error' => 'Panier vide']);
    }
    
    return response()->json([
        'cart_id' => $cart->id,
        'total_items' => $cart->total_items,
        'subtotal' => $cart->total_price,
        'formatted_subtotal' => $cart->formatted_total,
        'delivery_fee' => $cart->delivery_fee,
        'formatted_delivery_fee' => $cart->formatted_delivery_fee,
        'discount' => $cart->discount,
        'formatted_discount' => $cart->formatted_discount,
        'final_total' => $cart->final_total,
        'formatted_final_total' => $cart->formatted_final_total,
        'unique_commerces_count' => $cart->unique_commerces_count,
        'is_first_order' => !$user->orders()->exists(),
        'items' => $cart->items->map(function($item) {
            return [
                'product_name' => $item->product->name,
                'commerce_name' => $item->product->commerce->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'subtotal' => $item->quantity * $item->price
            ];
        })
    ]);
});
