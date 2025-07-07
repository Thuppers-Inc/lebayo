<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes d'authentification
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/logout', function () {
    auth()->logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/');
})->name('logout');

// Routes d'administration
Route::get('/admin', function () {
    return view('admin.dashboard.index');
})->name('admin.dashboard');

Route::get('/admin/blank', function () {
    return view('admin.blank');
})->name('admin.blank');

Route::get('/admin/blank-minimal', function () {
    return view('admin.blank-minimal');
})->name('admin.blank.minimal');

Route::get('/admin/theme-demo', function () {
    return view('admin.theme-demo');
})->name('admin.theme.demo');

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
