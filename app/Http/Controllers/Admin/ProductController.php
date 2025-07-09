<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Commerce;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * Afficher les produits d'un commerce
     */
    public function index(Request $request, $commerceId = null)
    {
        if ($commerceId) {
            // Afficher les produits d'un commerce spécifique
            $commerce = Commerce::with(['commerceType'])->findOrFail($commerceId);
            $products = Product::with(['category'])
                ->byCommerce($commerceId)
                ->latest()
                ->get();
                
            // Récupérer toutes les catégories pour les filtres
            $categories = Category::active()->get();
            
            return view('admin.products.index', compact('products', 'commerce', 'categories'));
        } else {
            // Afficher tous les produits
            $products = Product::with(['commerce', 'category'])
                ->latest()
                ->get();
                
            $commerces = Commerce::active()->get();
            $categories = Category::active()->get();
                
            return view('admin.products.index', compact('products', 'commerces', 'categories'));
        }
    }

    /**
     * Afficher le formulaire de création
     */
    public function create($commerceId = null)
    {
        $commerce = null;
        $commerces = Commerce::active()->get();
        $categories = Category::active()->get();
        
        if ($commerceId) {
            $commerce = Commerce::findOrFail($commerceId);
            // Filtrer les catégories selon le type de commerce
            $categories = Category::where('commerce_type_id', $commerce->commerce_type_id)->active()->get();
        }
        
        return view('admin.products.create', compact('commerce', 'commerces', 'categories'));
    }

    /**
     * Enregistrer un nouveau produit
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'commerce_id' => 'required|exists:commerces,id',
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'old_price' => 'nullable|integer|min:0|gt:price',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sku' => 'nullable|string|max:50|unique:products,sku',
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:20',
            'preparation_time' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'specifications' => 'nullable|string'
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            // Créer le répertoire s'il n'existe pas
            $directory = public_path('products');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Déplacer le fichier vers public/products
            $image->move($directory, $filename);
            $validated['image'] = $filename;
        }

        // Traitement des tags et specifications
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }
        
        if ($request->filled('specifications')) {
            $specifications = [];
            foreach (explode("\n", $request->specifications) as $spec) {
                if (trim($spec)) {
                    $parts = explode(':', $spec, 2);
                    if (count($parts) === 2) {
                        $specifications[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }
            $validated['specifications'] = $specifications;
        }

        $product = Product::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès !',
                'product' => $product->load(['commerce', 'category'])
            ]);
        }

        return redirect()->route('admin.products.index', $product->commerce_id)
            ->with('success', 'Produit créé avec succès !');
    }

    /**
     * Afficher un produit
     */
    public function show(Product $product)
    {
        $product->load(['commerce', 'category']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(Product $product)
    {
        $commerce = $product->commerce;
        $categories = Category::where('commerce_type_id', $commerce->commerce_type_id)->active()->get();
        
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'product' => $product,
                'categories' => $categories
            ]);
        }

        return view('admin.products.edit', compact('product', 'commerce', 'categories'));
    }

    /**
     * Mettre à jour un produit
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'old_price' => 'nullable|integer|min:0|gt:price',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sku' => 'nullable|string|max:50|unique:products,sku,' . $product->id,
            'stock' => 'required|integer|min:0',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'weight' => 'nullable|numeric|min:0',
            'unit' => 'required|string|max:20',
            'preparation_time' => 'nullable|integer|min:0',
            'tags' => 'nullable|string',
            'specifications' => 'nullable|string'
        ]);

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image
            if ($product->image) {
                $oldImagePath = public_path('products/' . $product->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $image = $request->file('image');
            $filename = Str::uuid() . '.' . $image->getClientOriginalExtension();
            
            // Créer le répertoire s'il n'existe pas
            $directory = public_path('products');
            if (!file_exists($directory)) {
                mkdir($directory, 0755, true);
            }
            
            // Déplacer le fichier vers public/products
            $image->move($directory, $filename);
            $validated['image'] = $filename;
        }

        // Traitement des tags et specifications
        if ($request->filled('tags')) {
            $validated['tags'] = array_map('trim', explode(',', $request->tags));
        }
        
        if ($request->filled('specifications')) {
            $specifications = [];
            foreach (explode("\n", $request->specifications) as $spec) {
                if (trim($spec)) {
                    $parts = explode(':', $spec, 2);
                    if (count($parts) === 2) {
                        $specifications[trim($parts[0])] = trim($parts[1]);
                    }
                }
            }
            $validated['specifications'] = $specifications;
        }

        $product->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Produit modifié avec succès !',
                'product' => $product->fresh()->load(['commerce', 'category'])
            ]);
        }

        return redirect()->route('admin.products.index', $product->commerce_id)
            ->with('success', 'Produit modifié avec succès !');
    }

    /**
     * Supprimer un produit
     */
    public function destroy(Product $product)
    {
        try {
            // Supprimer l'image
            if ($product->image) {
                $imagePath = public_path('products/' . $product->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $commerceId = $product->commerce_id;
            $product->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Produit supprimé avec succès !'
                ]);
            }

            return redirect()->route('admin.products.index', $commerceId)
                ->with('success', 'Produit supprimé avec succès !');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du produit.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du produit.');
        }
    }

    /**
     * Changer le statut de disponibilité d'un produit
     */
    public function toggleAvailability(Product $product)
    {
        try {
            $product->update([
                'is_available' => !$product->is_available
            ]);

            $status = $product->is_available ? 'disponible' : 'indisponible';

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Produit marqué comme {$status} !",
                    'is_available' => $product->is_available
                ]);
            }

            return redirect()->back()
                ->with('success', "Produit marqué comme {$status} !");
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du changement de statut.');
        }
    }

    /**
     * Mettre en avant un produit
     */
    public function toggleFeatured(Product $product)
    {
        try {
            $product->update([
                'is_featured' => !$product->is_featured
            ]);

            $status = $product->is_featured ? 'mis en avant' : 'retiré de la mise en avant';

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Produit {$status} !",
                    'is_featured' => $product->is_featured
                ]);
            }

            return redirect()->back()
                ->with('success', "Produit {$status} !");
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du changement de statut.');
        }
    }
}
