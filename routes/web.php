<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CommerceTypeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CommerceController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\LivreurController;
use App\Http\Controllers\Admin\DashboardController;

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
Route::prefix('admin')->name('admin.')->middleware(['admin'])->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Types de commerce
    Route::resource('commerce-types', CommerceTypeController::class);
    
    // Catégories
    Route::resource('categories', CategoryController::class);
    
    // Commerces
    Route::resource('commerces', CommerceController::class);
    
    // Produits
    Route::resource('products', ProductController::class);
    Route::get('commerces/{commerce}/products', [ProductController::class, 'index'])->name('commerces.products.index');
    Route::post('products/{product}/toggle-availability', [ProductController::class, 'toggleAvailability'])->name('products.toggle-availability');
    Route::post('products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    
    // Livreurs
    Route::resource('livreurs', LivreurController::class);
    Route::post('livreurs/{livreur}/toggle-status', [LivreurController::class, 'toggleStatus'])->name('livreurs.toggle-status');
    
    // Page vide pour tests
    Route::get('/blank', function () {
        return view('admin.blank');
    })->name('blank');
    
    // Démonstration du thème
    Route::get('/theme-demo', function () {
        return view('admin.theme-demo');
    })->name('theme.demo');
});

// Routes pour les types de commerce
Route::resource('admin/commerce-types', App\Http\Controllers\Admin\CommerceTypeController::class)
    ->names([
        'index' => 'admin.commerce-types.index',
        'create' => 'admin.commerce-types.create',
        'store' => 'admin.commerce-types.store',
        'show' => 'admin.commerce-types.show',
        'edit' => 'admin.commerce-types.edit',
        'update' => 'admin.commerce-types.update',
        'destroy' => 'admin.commerce-types.destroy',
    ]);

// Route pour changer le statut d'un type de commerce
Route::post('admin/commerce-types/{commerceType}/toggle-status', [App\Http\Controllers\Admin\CommerceTypeController::class, 'toggleStatus'])
    ->name('admin.commerce-types.toggle-status');

// Routes pour les catégories
Route::resource('admin/categories', App\Http\Controllers\Admin\CategoryController::class)
    ->names([
        'index' => 'admin.categories.index',
        'create' => 'admin.categories.create',
        'store' => 'admin.categories.store',
        'show' => 'admin.categories.show',
        'edit' => 'admin.categories.edit',
        'update' => 'admin.categories.update',
        'destroy' => 'admin.categories.destroy',
    ]);

// Route pour changer le statut d'une catégorie
Route::post('admin/categories/{category}/toggle-status', [App\Http\Controllers\Admin\CategoryController::class, 'toggleStatus'])
    ->name('admin.categories.toggle-status');

// Routes pour les commerces
Route::resource('admin/commerces', App\Http\Controllers\Admin\CommerceController::class)
    ->names([
        'index' => 'admin.commerces.index',
        'create' => 'admin.commerces.create',
        'store' => 'admin.commerces.store',
        'show' => 'admin.commerces.show',
        'edit' => 'admin.commerces.edit',
        'update' => 'admin.commerces.update',
        'destroy' => 'admin.commerces.destroy',
    ]);

// Route pour changer le statut d'un commerce
Route::post('admin/commerces/{commerce}/toggle-status', [App\Http\Controllers\Admin\CommerceController::class, 'toggleStatus'])
    ->name('admin.commerces.toggle-status');

// Routes pour les produits
Route::resource('admin/products', App\Http\Controllers\Admin\ProductController::class)
    ->names([
        'index' => 'admin.products.index',
        'create' => 'admin.products.create',
        'store' => 'admin.products.store',
        'show' => 'admin.products.show',
        'edit' => 'admin.products.edit',
        'update' => 'admin.products.update',
        'destroy' => 'admin.products.destroy',
    ]);

// Routes spécifiques pour les produits d'un commerce
Route::get('admin/commerces/{commerce}/products', [App\Http\Controllers\Admin\ProductController::class, 'index'])
    ->name('admin.commerce.products.index');
Route::get('admin/commerces/{commerce}/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create'])
    ->name('admin.commerce.products.create');

// Routes pour changer les statuts des produits
Route::post('admin/products/{product}/toggle-availability', [App\Http\Controllers\Admin\ProductController::class, 'toggleAvailability'])
    ->name('admin.products.toggle-availability');
Route::post('admin/products/{product}/toggle-featured', [App\Http\Controllers\Admin\ProductController::class, 'toggleFeatured'])
    ->name('admin.products.toggle-featured');

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
