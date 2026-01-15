<?php

namespace App\Http\Controllers;

use App\Models\Commerce;
use App\Models\Category;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
     * Afficher les détails d'un commerce et ses produits
     */
    public function show(Commerce $commerce)
    {
        // Vérifier que le commerce est actif
        if (!$commerce->is_active) {
            abort(404, 'Ce commerce n\'est pas disponible.');
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

        // Ajouter une image de fond stable pour le restaurant (basée sur l'ID pour rester constante)
        $headerImages = [
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?w=1200&h=400&fit=crop',
            'https://images.unsplash.com/photo-1481931098730-318b6f776db0?w=1200&h=400&fit=crop',
        ];
        
        // Sélection stable basée sur l'ID du commerce (reste la même à chaque chargement)
        $imageIndex = $commerce->id % count($headerImages);
        $commerce->header_image = $headerImages[$imageIndex];

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