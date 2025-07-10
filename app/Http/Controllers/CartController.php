<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Afficher le panier
     */
    public function index()
    {
        $cart = $this->getCurrentCart();
        
        if (!$cart || $cart->isEmpty()) {
            return view('cart.index', [
                'cart' => null,
                'cartItems' => collect(),
                'totalItems' => 0,
                'totalPrice' => 0
            ]);
        }

        $cartItems = $cart->items()
            ->with(['product' => function($query) {
                $query->with('commerce');
            }])
            ->get();

        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'totalItems' => $cart->total_items,
            'totalPrice' => $cart->total_price
        ]);
    }

    /**
     * Remplacer le panier par un nouveau produit (vider et ajouter)
     */
    public function replace(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        // Vérifier que le produit est disponible
        if (!$product->is_available) {
            if ($this->isAjaxRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit n\'est pas disponible.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce produit n\'est pas disponible.');
        }

        $cart = $this->getOrCreateCart();

        try {
            // Vider le panier
            $cart->clear();
            
            // Ajouter le nouveau produit
            $cart->addProduct($product, $request->quantity);

            if ($this->isAjaxRequest($request)) {
                $cart->load(['items.product.commerce']);
                return response()->json([
                    'success' => true,
                    'message' => 'Panier remplacé ! Produit ajouté avec succès.',
                    'cart' => [
                        'total_items' => $cart->total_items,
                        'total_price' => $cart->total_price,
                        'formatted_total' => $cart->formatted_total,
                        'commerce_name' => $cart->getCommerceName()
                    ]
                ]);
            }

            return redirect()->back()->with('success', 'Panier remplacé ! Produit ajouté avec succès.');

        } catch (\Exception $e) {
            if ($this->isAjaxRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du remplacement du panier : ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Erreur lors du remplacement du panier.');
        }
    }

    /**
     * Ajouter un produit au panier
     */
    public function add(Request $request, Product $product)
    {
        // Log pour débogage
        \Log::info('CartController::add called', [
            'product_id' => $product->id,
            'is_ajax' => $this->isAjaxRequest($request),
            'method' => $request->method(),
            'headers' => $request->headers->all(),
            'user_id' => Auth::id(),
            'session_id' => session()->getId()
        ]);

        $request->validate([
            'quantity' => 'required|integer|min:1|max:99'
        ]);

        // Vérifier que le produit est disponible
        if (!$product->is_available) {
            if ($this->isAjaxRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ce produit n\'est pas disponible.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Ce produit n\'est pas disponible.');
        }

        $cart = $this->getOrCreateCart();

        try {
            $cart->addProduct($product, $request->quantity);
        } catch (\Exception $e) {
            // Gérer l'exception pour commerce différent
            if ($this->isAjaxRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'different_commerce' => true,
                    'current_commerce' => $cart->getCommerceName(),
                    'new_commerce' => $product->commerce->name,
                    'actions' => [
                        'replace' => [
                            'url' => route('cart.replace', $product->id),
                            'label' => 'Vider le panier et ajouter ce produit',
                            'method' => 'POST'
                        ],
                        'cancel' => [
                            'label' => 'Annuler'
                        ]
                    ],
                    'cart_info' => [
                        'total_items' => $cart->total_items,
                        'total_price' => $cart->total_price,
                        'formatted_total' => $cart->formatted_total
                    ]
                ], 400);
            }
            return redirect()->back()->with('error', $e->getMessage());
        }

        if ($this->isAjaxRequest($request)) {
            $cart->load(['items.product']);
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier !',
                'cart' => [
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'formatted_total' => $cart->formatted_total
                ]
            ]);
        }

        return redirect()->back()->with('success', 'Produit ajouté au panier !');
    }

    /**
     * Mettre à jour la quantité d'un produit dans le panier
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0|max:99'
        ]);

        $cart = $this->getCurrentCart();

        if (!$cart) {
            if ($this->isAjaxRequest($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panier introuvable.'
                ], 404);
            }
            return redirect()->route('cart.index')->with('error', 'Panier introuvable.');
        }

        $cart->updateProductQuantity($product->id, $request->quantity);

        if ($this->isAjaxRequest($request)) {
            $cart->load(['items.product']);
            return response()->json([
                'success' => true,
                'message' => $request->quantity > 0 ? 'Quantité mise à jour !' : 'Produit supprimé du panier !',
                'cart' => [
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'formatted_total' => $cart->formatted_total
                ]
            ]);
        }

        $message = $request->quantity > 0 ? 'Quantité mise à jour !' : 'Produit supprimé du panier !';
        return redirect()->route('cart.index')->with('success', $message);
    }

    /**
     * Supprimer un produit du panier
     */
    public function remove(Product $product)
    {
        $cart = $this->getCurrentCart();

        if (!$cart) {
            if ($this->isAjaxRequest(request())) {
                return response()->json([
                    'success' => false,
                    'message' => 'Panier introuvable.'
                ], 404);
            }
            return redirect()->route('cart.index')->with('error', 'Panier introuvable.');
        }

        $cart->removeProduct($product->id);

        if ($this->isAjaxRequest(request())) {
            $cart->load(['items.product']);
            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé du panier !',
                'cart' => [
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'formatted_total' => $cart->formatted_total
                ]
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Produit supprimé du panier !');
    }

    /**
     * Vider complètement le panier
     */
    public function clear()
    {
        $cart = $this->getCurrentCart();

        if ($cart) {
            $cart->clear();
        }

        if ($this->isAjaxRequest(request())) {
            return response()->json([
                'success' => true,
                'message' => 'Panier vidé !',
                'cart' => [
                    'total_items' => 0,
                    'total_price' => 0,
                    'formatted_total' => '0 F'
                ]
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Panier vidé !');
    }

    /**
     * Obtenir le nombre d'articles dans le panier (pour AJAX)
     */
    public function count()
    {
        $cart = $this->getCurrentCart();
        $count = $cart ? $cart->total_items : 0;

        return response()->json([
            'count' => $count,
            'total_price' => $cart ? $cart->total_price : 0,
            'formatted_total' => $cart ? $cart->formatted_total : '0 F'
        ]);
    }

    /**
     * Obtenir le panier actuel de l'utilisateur ou de la session
     */
    private function getCurrentCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with('items.product')->first();
        }

        $sessionId = session()->getId();
        return Cart::where('session_id', $sessionId)->with('items.product')->first();
    }

    /**
     * Obtenir ou créer un panier pour l'utilisateur actuel
     */
    private function getOrCreateCart()
    {
        if (Auth::check()) {
            return Cart::getOrCreateForUser(Auth::id());
        }

        $sessionId = session()->getId();
        return Cart::getOrCreateForSession($sessionId);
    }

    /**
     * Vérifier si c'est une requête AJAX
     */
    private function isAjaxRequest($request)
    {
        return $request->ajax() || 
               $request->wantsJson() || 
               $request->expectsJson() ||
               $request->header('Accept') === 'application/json' ||
               $request->header('Content-Type') === 'application/json';
    }
}
