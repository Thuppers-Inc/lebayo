<?php

namespace App\Http\Controllers;

use App\Models\CommerceType;
use App\Models\Commerce;
use Illuminate\Http\Request;

class CommerceTypeController extends Controller
{
    /**
     * Afficher tous les commerces d'un type spécifique
     */
    public function show(CommerceType $commerceType)
    {
        // Charger les commerces actifs de ce type avec leurs relations
        $commerces = Commerce::active()
            ->with(['commerceType', 'categories'])
            ->withCount('products')
            ->where('commerce_type_id', $commerceType->id)
            ->orderBy('products_count', 'desc')
            ->paginate(12);

        // Ajouter des images placeholder pour les commerces
        $commerceImages = [
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1481931098730-318b6f776db0?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1565299624946-b28f40a0ca4b?w=800&h=400&fit=crop',
        ];

        foreach ($commerces as $index => $commerce) {
            $commerce->placeholder_image = $commerceImages[$index % count($commerceImages)];
        }

        // Image d'entête pour le type de commerce
        $commerceTypeImages = [
            'Restaurants' => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1200&h=400&fit=crop',
            'Fast Food' => 'https://images.unsplash.com/photo-1571091655789-405eb7a3a3a8?w=1200&h=400&fit=crop',
            'Boutiques' => 'https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&h=400&fit=crop',
            'Pharmacies' => 'https://images.unsplash.com/photo-1576602976047-174e57a47881?w=1200&h=400&fit=crop',
            'Supermarchés' => 'https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=1200&h=400&fit=crop',
            'Électronique' => 'https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=1200&h=400&fit=crop',
            'Boulangeries' => 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=1200&h=400&fit=crop',
            'Cafés' => 'https://images.unsplash.com/photo-1554118811-1e0d58224f24?w=1200&h=400&fit=crop'
        ];

        $commerceType->header_image = $commerceTypeImages[$commerceType->name] ?? 'https://images.unsplash.com/photo-1556909114-f6e7ad7d3136?w=1200&h=400&fit=crop';

        // Statistiques
        $totalCommerces = $commerces->total();
        $activeCommerces = Commerce::active()->where('commerce_type_id', $commerceType->id)->count();

        return view('commerce-type.show', compact(
            'commerceType', 
            'commerces', 
            'totalCommerces', 
            'activeCommerces'
        ));
    }
} 