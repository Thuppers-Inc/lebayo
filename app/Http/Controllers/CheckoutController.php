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

            // Créer la commande
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => Order::generateOrderNumber(),
                'delivery_address_id' => $selectedAddress->id,
                'payment_method' => $request->payment_method,
                'status' => Order::STATUS_PENDING,
                'subtotal' => $cart->total_price,
                'delivery_fee' => 0,
                'discount' => 0,
                'total' => $cart->total_price,
                'payment_status' => Order::PAYMENT_STATUS_PENDING,
                'notes' => $request->notes,
                'estimated_delivery_time' => now()->addMinutes(30) // 30 minutes par défaut
            ]);

            // Créer les articles de commande
            foreach ($cart->items as $cartItem) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'price' => $cartItem->product->price,
                    'product_name' => $cartItem->product->name,
                    'product_image' => $cartItem->product->image_url,
                    'product_description' => $cartItem->product->description
                ]);
            }

            // Vider le panier
            $cart->items()->delete();

            DB::commit();

            Log::info('Commande créée avec succès', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'user_id' => $user->id,
                'total' => $order->total
            ]);

            return redirect()->route('checkout.success', $order->order_number)
                ->with('success', 'Votre commande a été passée avec succès !');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Erreur lors de la création de la commande', [
                'error' => $e->getMessage(),
                'user_id' => $user->id
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
            ->with(['items.product', 'deliveryAddress'])
            ->firstOrFail();

        return view('checkout.success', compact('order'));
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