<?php

namespace App\Http\Controllers;

use App\Models\Commerce;
use App\Models\CommerceType;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Afficher la page d'accueil
     */
    public function index()
    {
        // Récupérer les restaurants populaires (commerces actifs avec le plus de produits)
        $popularRestaurants = Commerce::active()
            ->with(['commerceType', 'products'])
            ->withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(6)
            ->get();

        // Ajouter des images de placeholder pour les restaurants
        $foodImages = [
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1567620905732-2d1ec7ab7445?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=400&h=300&fit=crop',
            'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=400&h=300&fit=crop',
        ];

        // Assigner des images aux restaurants
        foreach ($popularRestaurants as $index => $restaurant) {
            $restaurant->placeholder_image = $foodImages[$index % count($foodImages)];
        }



        // Récupérer les types de commerce actifs avec leurs commerces pour le module de navigation
        $commerceTypes = CommerceType::active()
            ->with(['commerces' => function($query) {
                $query->active()->withCount('products')->take(4);
            }])
            ->whereHas('commerces', function($query) {
                $query->where('is_active', true);
            })
            ->orderBy('name')
            ->get();

        // Ajouter des images pour chaque type de commerce
        $commerceTypeImages = [
            'Restaurants' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=600&h=400&fit=crop',
            'Fast Food' => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=600&h=400&fit=crop',
            'Boutiques' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=600&h=400&fit=crop',
            'Pharmacies' => 'https://images.unsplash.com/photo-1576602976047-174e57a47881?w=600&h=400&fit=crop',
            'Supermarchés' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=600&h=400&fit=crop',
            'Électronique' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=600&h=400&fit=crop',
            'Boulangeries' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=600&h=400&fit=crop',
            'Cafés' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=600&h=400&fit=crop'
        ];

        foreach ($commerceTypes as $type) {
            $type->header_image = $commerceTypeImages[$type->name] ?? 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=600&h=400&fit=crop';

            // Ajouter des images aux commerces de chaque type
            foreach ($type->commerces as $index => $commerce) {
                $commerce->placeholder_image = $foodImages[$index % count($foodImages)];
            }
        }

        return view('welcome', compact('popularRestaurants', 'commerceTypes'));
    }
}
