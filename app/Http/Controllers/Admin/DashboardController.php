<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Commerce;
use App\Models\Product;
use App\Models\Category;
use App\Models\CommerceType;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Affiche le tableau de bord administrateur
     */
    public function index()
    {
        // Récupération des statistiques
        $stats = [
            'total_commerces' => Commerce::count(),
            'total_products' => Product::withActiveCommerce()->count(),
            'total_categories' => Category::count(),
            'total_commerce_types' => CommerceType::count(),
            'total_users' => User::count(),
            'active_commerces' => Commerce::active()->count(),
            'available_products' => Product::available()->withActiveCommerce()->count(),
            'featured_products' => Product::featured()->withActiveCommerce()->count(),
        ];

        // Récupération des données récentes
        $recent_commerces = Commerce::with('commerceType')
            ->latest()
            ->take(5)
            ->get();

        $recent_products = Product::with('commerce')
            ->withActiveCommerce()
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact('stats', 'recent_commerces', 'recent_products'));
    }
} 