<?php

namespace App\Http\Controllers;

use App\Models\Commerce;
use App\Models\Product;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    /**
     * Effectuer une recherche dans les commerces et produits
     */
    public function search(Request $request)
    {
        $query = $request->input('q');

        // Validation
        if (empty($query) || strlen($query) < 2) {
            return redirect()->route('home')->with('error', 'Veuillez saisir au moins 2 caractères pour la recherche.');
        }

        // Recherche dans les commerces
        $commerces = Commerce::active()
            ->with(['commerceType', 'categories'])
            ->withCount('products')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('description', 'LIKE', '%' . $query . '%')
                  ->orWhere('city', 'LIKE', '%' . $query . '%')
                  ->orWhere('address', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit(10)
            ->get();

        // Recherche dans les produits
        $products = Product::available()
            ->with(['commerce', 'category'])
            ->withActiveCommerce()
            ->whereHas('commerce', function($q) {
                $q->where('is_active', true);
            })
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('description', 'LIKE', '%' . $query . '%')
                  ->orWhere('sku', 'LIKE', '%' . $query . '%');
            })
            ->orderBy('name')
            ->limit(20)
            ->get();

        // Ajouter des images placeholder pour les commerces
        $commerceImages = [
            'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1579952363873-27d3bfad9c0d?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1590846406792-0adc7f938f1d?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1481931098730-318b6f776db0?w=800&h=400&fit=crop',
            'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=800&h=400&fit=crop',
        ];

        foreach ($commerces as $index => $commerce) {
            $commerce->placeholder_image = $commerceImages[$index % count($commerceImages)];
        }

        // Compter les résultats
        $totalResults = $commerces->count() + $products->count();

        return view('search.results', compact('query', 'commerces', 'products', 'totalResults'));
    }

    /**
     * API de recherche pour l'autocomplétion
     */
    public function autocomplete(Request $request)
    {
        $query = $request->input('q');

        if (empty($query) || strlen($query) < 2) {
            return response()->json([]);
        }

        // Recherche dans les commerces (5 résultats max)
        $commerces = Commerce::active()
            ->select('id', 'name', 'city', 'commerce_type_id')
            ->with('commerceType:id,name')
            ->where(function($q) use ($query) {
                $q->where('name', 'LIKE', '%' . $query . '%')
                  ->orWhere('city', 'LIKE', '%' . $query . '%');
            })
            ->limit(5)
            ->get()
            ->map(function($commerce) {
                return [
                    'type' => 'commerce',
                    'id' => $commerce->id,
                    'name' => $commerce->name,
                    'subtitle' => $commerce->city . ' • ' . $commerce->commerceType->name,
                    'url' => route('commerce.show', $commerce),
                    'icon' => '🏪'
                ];
            });

        // Recherche dans les produits (5 résultats max)
        $products = Product::available()
            ->select('id', 'name', 'price', 'commerce_id')
            ->with('commerce:id,name')
            ->withActiveCommerce()
            ->whereHas('commerce', function($q) {
                $q->where('is_active', true);
            })
            ->where('name', 'LIKE', '%' . $query . '%')
            ->limit(5)
            ->get()
            ->map(function($product) {
                return [
                    'type' => 'product',
                    'id' => $product->id,
                    'name' => $product->name,
                    'subtitle' => $product->commerce->name . ' • ' . number_format($product->price) . 'F',
                    'url' => route('commerce.show', $product->commerce),
                    'icon' => '🛍️'
                ];
            });

        // Combiner et retourner
        $results = $commerces->concat($products)->take(8);

        return response()->json($results);
    }
}
