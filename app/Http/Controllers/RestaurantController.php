<?php

namespace App\Http\Controllers;

use App\Models\Commerce;
use App\Models\Category;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Afficher les détails d'un restaurant et ses produits
     */
    public function show(Commerce $commerce)
    {
        // Vérifier que le commerce est actif
        if (!$commerce->is_active) {
            abort(404, 'Ce restaurant n\'est pas disponible.');
        }

        // Charger les relations nécessaires
        $commerce->load([
            'commerceType',
            'categories',
            'products' => function($query) {
                $query->available()->with('category')->orderBy('category_id')->orderBy('name');
            }
        ]);

        // Grouper les produits par catégorie
        $productsByCategory = $commerce->products->groupBy('category.name');

        // Ajouter une image de fond pour le restaurant
        $headerImages = [
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1481931098730-318b6f776db0?w=1200&h=400&fit=crop',
        ];
        
        $commerce->header_image = $headerImages[array_rand($headerImages)];

        // Calculer quelques statistiques
        $totalProducts = $commerce->products->count();
        $avgPrice = $commerce->products->avg('price');
        $featuredProducts = $commerce->products->where('is_featured', true);

        return view('restaurant.show', compact(
            'commerce', 
            'productsByCategory', 
            'totalProducts', 
            'avgPrice', 
            'featuredProducts'
        ));
    }
} 