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
                'totalPrice' => 0,
                'deliveryFee' => 0,
                'discount' => 0,
                'finalTotal' => 0,
                'isFirstOrder' => false
            ]);
        }

        $cartItems = $cart->items()
            ->with(['product' => function($query) {
                $query->with('commerce');
            }])
            ->get();

        // Calculer les frais de livraison (500 F par commerce différent)
        $uniqueCommerces = $cartItems->pluck('product.commerce.id')->unique();
        $deliveryFee = $uniqueCommerces->count() * 500;

        // Vérifier si c'est la première commande de l'utilisateur
        $user = Auth::user();
        $isFirstOrder = false;
        $discount = 0;
        
        if ($user) {
            $isFirstOrder = !$user->orders()->exists();
            $discount = $isFirstOrder ? 500 : 0;
        }

        $subtotal = $cart->total_price;
        $finalTotal = $subtotal + $deliveryFee - $discount;

        return view('cart.index', [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'totalItems' => $cart->total_items,
            'totalPrice' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'finalTotal' => $finalTotal,
            'isFirstOrder' => $isFirstOrder,
            'uniqueCommercesCount' => $uniqueCommerces->count()
        ]);
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
        $cart->addProduct($product, $request->quantity);

        if ($this->isAjaxRequest($request)) {
            $cart->load(['items.product.commerce']);
            return response()->json([
                'success' => true,
                'message' => 'Produit ajouté au panier !',
                'cart' => [
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'formatted_subtotal' => $cart->formatted_total,
                    'delivery_fee' => $cart->delivery_fee,
                    'formatted_delivery_fee' => $cart->formatted_delivery_fee,
                    'discount' => $cart->discount,
                    'formatted_discount' => $cart->formatted_discount,
                    'final_total' => $cart->final_total,
                    'formatted_final_total' => $cart->formatted_final_total,
                    'unique_commerces_count' => $cart->unique_commerces_count
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
            $cart->load(['items.product.commerce']);
            return response()->json([
                'success' => true,
                'message' => $request->quantity > 0 ? 'Quantité mise à jour !' : 'Produit supprimé du panier !',
                'cart' => [
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'formatted_subtotal' => $cart->formatted_total,
                    'delivery_fee' => $cart->delivery_fee,
                    'formatted_delivery_fee' => $cart->formatted_delivery_fee,
                    'discount' => $cart->discount,
                    'formatted_discount' => $cart->formatted_discount,
                    'final_total' => $cart->final_total,
                    'formatted_final_total' => $cart->formatted_final_total,
                    'unique_commerces_count' => $cart->unique_commerces_count
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
            $cart->load(['items.product.commerce']);
            return response()->json([
                'success' => true,
                'message' => 'Produit supprimé du panier !',
                'cart' => [
                    'total_items' => $cart->total_items,
                    'total_price' => $cart->total_price,
                    'formatted_subtotal' => $cart->formatted_total,
                    'delivery_fee' => $cart->delivery_fee,
                    'formatted_delivery_fee' => $cart->formatted_delivery_fee,
                    'discount' => $cart->discount,
                    'formatted_discount' => $cart->formatted_discount,
                    'final_total' => $cart->final_total,
                    'formatted_final_total' => $cart->formatted_final_total,
                    'unique_commerces_count' => $cart->unique_commerces_count
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
                    'formatted_subtotal' => '0 F',
                    'delivery_fee' => 0,
                    'formatted_delivery_fee' => '0 F',
                    'discount' => 0,
                    'formatted_discount' => '0 F',
                    'final_total' => 0,
                    'formatted_final_total' => '0 F',
                    'unique_commerces_count' => 0
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
            'formatted_subtotal' => $cart ? $cart->formatted_total : '0 F',
            'delivery_fee' => $cart ? $cart->delivery_fee : 0,
            'formatted_delivery_fee' => $cart ? $cart->formatted_delivery_fee : '0 F',
            'discount' => $cart ? $cart->discount : 0,
            'formatted_discount' => $cart ? $cart->formatted_discount : '0 F',
            'final_total' => $cart ? $cart->final_total : 0,
            'formatted_final_total' => $cart ? $cart->formatted_final_total : '0 F',
            'unique_commerces_count' => $cart ? $cart->unique_commerces_count : 0
        ]);
    }

    /**
     * Obtenir le panier actuel de l'utilisateur ou de la session
     */
    private function getCurrentCart()
    {
        if (Auth::check()) {
            return Cart::where('user_id', Auth::id())->with(['items.product.commerce', 'user'])->first();
        }

        $sessionId = session()->getId();
        return Cart::where('session_id', $sessionId)->with(['items.product.commerce', 'user'])->first();
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
