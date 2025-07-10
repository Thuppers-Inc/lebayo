<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Cart;
use App\Models\Address;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Démarrer le processus de checkout
     */
    public function index()
    {
        $user = Auth::user();
        $cart = Cart::getForUser($user->id);

        // Vérifier si le panier n'est pas vide
        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        // Calculer les totaux
        $cartItems = $cart->items;
        $subtotal = $cart->total_price;
        $deliveryFee = 0; // Gratuit pour l'instant
        $discount = 0;
        $total = $subtotal + $deliveryFee - $discount;

        // Données pour le checkout
        $checkoutData = [
            'cart' => $cart,
            'cartItems' => $cartItems,
            'subtotal' => $subtotal,
            'deliveryFee' => $deliveryFee,
            'discount' => $discount,
            'total' => $total,
            'totalItems' => $cart->total_items,
            'currentStep' => 'account',
            'steps' => $this->getSteps(),
            'user' => $user
        ];

        return view('checkout.account', $checkoutData);
    }

    /**
     * Étape 2: Sélection de l'adresse
     */
    public function address()
    {
        $user = Auth::user();
        $cart = Cart::getForUser($user->id);

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $addresses = $user->addresses()->get();
        $defaultAddress = $user->default_address;

        $checkoutData = [
            'cart' => $cart,
            'cartItems' => $cart->items,
            'subtotal' => $cart->total_price,
            'deliveryFee' => 0,
            'discount' => 0,
            'total' => $cart->total_price,
            'totalItems' => $cart->total_items,
            'currentStep' => 'address',
            'steps' => $this->getSteps(),
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'user' => $user
        ];

        return view('checkout.address', $checkoutData);
    }

    /**
     * Étape 3: Sélection du mode de paiement
     */
    public function payment(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::getForUser($user->id);

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $selectedAddressId = $request->get('address_id');
        $selectedAddress = null;

        if ($selectedAddressId) {
            $selectedAddress = Address::where('id', $selectedAddressId)
                ->where('user_id', $user->id)
                ->first();
        }

        if (!$selectedAddress) {
            return redirect()->route('checkout.address')->with('error', 'Veuillez sélectionner une adresse de livraison');
        }

        $checkoutData = [
            'cart' => $cart,
            'cartItems' => $cart->items,
            'subtotal' => $cart->total_price,
            'deliveryFee' => 0,
            'discount' => 0,
            'total' => $cart->total_price,
            'totalItems' => $cart->total_items,
            'currentStep' => 'payment',
            'steps' => $this->getSteps(),
            'selectedAddress' => $selectedAddress,
            'user' => $user
        ];

        return view('checkout.payment', $checkoutData);
    }

    /**
     * Étape 4: Confirmation de la commande
     */
    public function confirm(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::getForUser($user->id);

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $selectedAddressId = $request->get('address_id');
        $paymentMethod = $request->get('payment_method', 'cash_on_delivery');

        $selectedAddress = Address::where('id', $selectedAddressId)
            ->where('user_id', $user->id)
            ->first();

        if (!$selectedAddress) {
            return redirect()->route('checkout.address')->with('error', 'Veuillez sélectionner une adresse de livraison');
        }

        $checkoutData = [
            'cart' => $cart,
            'cartItems' => $cart->items,
            'subtotal' => $cart->total_price,
            'deliveryFee' => 0,
            'discount' => 0,
            'total' => $cart->total_price,
            'totalItems' => $cart->total_items,
            'currentStep' => 'confirm',
            'steps' => $this->getSteps(),
            'selectedAddress' => $selectedAddress,
            'paymentMethod' => $paymentMethod,
            'user' => $user
        ];

        return view('checkout.confirm', $checkoutData);
    }

    /**
     * Finaliser la commande
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $cart = Cart::getForUser($user->id);

        if (!$cart || $cart->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Votre panier est vide');
        }

        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cash_on_delivery,card,mobile_money'
        ]);

        $selectedAddress = Address::where('id', $request->address_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$selectedAddress) {
            return redirect()->route('checkout.address')->with('error', 'Adresse de livraison invalide');
        }

        try {
            DB::beginTransaction();

            // Grouper les articles du panier par commerce
            $cartItemsByCommerce = $cart->items()->with('product.commerce')->get()->groupBy('product.commerce_id');
            
            $createdOrders = [];
            $totalOrdersAmount = 0;

            foreach ($cartItemsByCommerce as $commerceId => $items) {
                // Calculer le sous-total pour ce commerce
                $commerceSubtotal = $items->sum(function ($item) {
                    return $item->quantity * $item->product->price;
                });
                
                $totalOrdersAmount += $commerceSubtotal;

                // Créer une commande pour ce commerce
                $order = Order::create([
                    'user_id' => $user->id,
                    'commerce_id' => $commerceId,
                    'order_number' => Order::generateOrderNumber(),
                    'delivery_address_id' => $selectedAddress->id,
                    'payment_method' => $request->payment_method,
                    'status' => Order::STATUS_PENDING,
                    'subtotal' => $commerceSubtotal,
                    'delivery_fee' => 0,
                    'discount' => 0,
                    'total' => $commerceSubtotal,
                    'payment_status' => Order::PAYMENT_STATUS_PENDING,
                    'notes' => $request->notes,
                    'estimated_delivery_time' => now()->addMinutes(30) // 30 minutes par défaut
                ]);

                // Créer les articles de commande pour ce commerce
                foreach ($items as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                        'product_name' => $cartItem->product->name,
                        'product_image' => $cartItem->product->image_url,
                        'product_description' => $cartItem->product->description
                    ]);
                }

                $createdOrders[] = $order;

                Log::info('Commande créée pour un commerce', [
                    'order_id' => $order->id,
                    'order_number' => $order->order_number,
                    'user_id' => $user->id,
                    'commerce_id' => $commerceId,
                    'commerce_name' => $items->first()->product->commerce->name,
                    'total' => $order->total,
                    'items_count' => $items->count()
                ]);
            }

            // Vider le panier après avoir créé toutes les commandes
            $cart->items()->delete();

            DB::commit();

            Log::info('Toutes les commandes créées avec succès', [
                'user_id' => $user->id,
                'total_orders' => count($createdOrders),
                'total_amount' => $totalOrdersAmount,
                'order_numbers' => collect($createdOrders)->pluck('order_number')->toArray()
            ]);

            // Rediriger vers la page de succès avec le numéro de la première commande
            // Ou créer une page de succès multiple si nécessaire
            $firstOrder = $createdOrders[0];
            $successMessage = count($createdOrders) > 1 
                ? "Vos " . count($createdOrders) . " commandes ont été passées avec succès !" 
                : "Votre commande a été passée avec succès !";

            return redirect()->route('checkout.success', $firstOrder->order_number)
                ->with('success', $successMessage)
                ->with('multiple_orders', count($createdOrders) > 1)
                ->with('all_orders', collect($createdOrders)->pluck('order_number')->toArray());

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création des commandes', [
                'error' => $e->getMessage(),
                'user_id' => $user->id,
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de la création de votre commande. Veuillez réessayer.');
        }
    }

    /**
     * Page de succès
     */
    public function success($orderNumber)
    {
        $user = Auth::user();
        $order = Order::where('order_number', $orderNumber)
            ->where('user_id', $user->id)
            ->with(['items.product.commerce', 'deliveryAddress', 'commerce'])
            ->firstOrFail();

        // Vérifier s'il y a plusieurs commandes créées récemment (dans les 10 dernières minutes)
        $recentOrders = Order::where('user_id', $user->id)
            ->where('created_at', '>=', now()->subMinutes(10))
            ->where('delivery_address_id', $order->delivery_address_id)
            ->with(['items.product.commerce', 'commerce'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Si une seule commande récente, utiliser la logique normale
        if ($recentOrders->count() <= 1) {
            return view('checkout.success', compact('order'));
        }

        // Si plusieurs commandes, les passer toutes à la vue
        return view('checkout.success', [
            'order' => $order,
            'allOrders' => $recentOrders,
            'isMultipleOrders' => true,
            'totalAmount' => $recentOrders->sum('total'),
            'totalItems' => $recentOrders->sum(function($order) {
                return $order->items->sum('quantity');
            })
        ]);
    }

    /**
     * Créer une nouvelle adresse depuis le checkout
     */
    public function storeAddress(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'additional_info' => 'nullable|string|max:500'
        ]);

        $user = Auth::user();

        $address = Address::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'street' => $request->street,
            'city' => $request->city,
            'country' => 'Côte d\'Ivoire', // Valeur par défaut pour Lebayo
            'phone' => $request->phone,
            'additional_info' => $request->additional_info,
            'is_default' => $user->addresses()->count() === 0 // Premier adresse = défaut
        ]);

        return response()->json([
            'success' => true,
            'address' => $address,
            'message' => 'Adresse ajoutée avec succès'
        ]);
    }

    /**
     * Obtenir les étapes du checkout
     */
    private function getSteps()
    {
        return [
            'account' => [
                'title' => 'Compte',
                'icon' => 'fas fa-user',
                'completed' => true // Toujours complété si utilisateur connecté
            ],
            'address' => [
                'title' => 'Adresse',
                'icon' => 'fas fa-map-marker-alt',
                'completed' => false
            ],
            'payment' => [
                'title' => 'Paiement',
                'icon' => 'fas fa-credit-card',
                'completed' => false
            ],
            'confirm' => [
                'title' => 'Confirmation',
                'icon' => 'fas fa-check',
                'completed' => false
            ]
        ];
    }
} 