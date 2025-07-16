<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Order;
use App\Models\Address;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Afficher le profil utilisateur
     */
    public function index()
    {
        $user = Auth::user();
        
        // Obtenir quelques statistiques utilisateur
        $stats = [
            'total_orders' => $user->orders()->count(),
            'total_spent' => $user->orders()->where('payment_status', 'paid')->sum('total'),
            'addresses_count' => $user->addresses()->count(),
            'member_since' => $user->created_at->diffForHumans()
        ];

        return view('profile.index', compact('user', 'stats'));
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'prenoms' => 'required|string|max:255',
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'numero_telephone' => 'nullable|string|max:20',
            'ville' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'lieu_naissance' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date|before:today'
        ]);

        $user->update($request->only([
            'prenoms', 'nom', 'email', 'numero_telephone', 
            'ville', 'commune', 'lieu_naissance', 'date_naissance'
        ]));

        return redirect()->route('profile.index')->with('success', 'Profil mis à jour avec succès !');
    }

    /**
     * Mettre à jour le mot de passe
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();

        // Vérifier le mot de passe actuel
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.index')->with('success', 'Mot de passe mis à jour avec succès !');
    }

    /**
     * Afficher les commandes de l'utilisateur
     */
    public function orders()
    {
        $user = Auth::user();
        
        $orders = $user->orders()
            ->with(['items.product.commerce', 'deliveryAddress'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('profile.orders', compact('orders'));
    }

    /**
     * Afficher une commande spécifique
     */
    public function showOrder(Order $order)
    {
        $user = Auth::user();

        // Vérifier que la commande appartient à l'utilisateur
        if ($order->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à voir cette commande.');
        }

        $order->load(['items.product.commerce', 'deliveryAddress']);

        return view('profile.order-detail', compact('order'));
    }

    /**
     * Afficher les adresses de l'utilisateur
     */
    public function addresses()
    {
        $user = Auth::user();
        $addresses = $user->addresses()->orderBy('is_default', 'desc')->orderBy('created_at', 'desc')->get();

        return view('profile.addresses', compact('addresses'));
    }

    /**
     * Créer une nouvelle adresse
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
            'country' => 'Côte d\'Ivoire',
            'phone' => $request->phone,
            'additional_info' => $request->additional_info,
            'is_default' => $user->addresses()->count() === 0 // Premier adresse = défaut
        ]);

        return redirect()->route('profile.addresses')->with('success', 'Adresse ajoutée avec succès !');
    }

    /**
     * Mettre à jour une adresse
     */
    public function updateAddress(Request $request, Address $address)
    {
        $user = Auth::user();

        // Vérifier que l'adresse appartient à l'utilisateur
        if ($address->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette adresse.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'additional_info' => 'nullable|string|max:500'
        ]);

        $address->update($request->only([
            'name', 'street', 'city', 'phone', 'additional_info'
        ]));

        return redirect()->route('profile.addresses')->with('success', 'Adresse mise à jour avec succès !');
    }

    /**
     * Supprimer une adresse
     */
    public function deleteAddress(Address $address)
    {
        $user = Auth::user();

        // Vérifier que l'adresse appartient à l'utilisateur
        if ($address->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à supprimer cette adresse.');
        }

        // Empêcher la suppression si c'est la seule adresse ou l'adresse par défaut
        if ($user->addresses()->count() === 1) {
            return redirect()->route('profile.addresses')->with('error', 'Vous ne pouvez pas supprimer votre dernière adresse.');
        }

        if ($address->is_default) {
            return redirect()->route('profile.addresses')->with('error', 'Vous ne pouvez pas supprimer votre adresse par défaut. Définissez d\'abord une autre adresse comme défaut.');
        }

        $address->delete();

        return redirect()->route('profile.addresses')->with('success', 'Adresse supprimée avec succès !');
    }

    /**
     * Définir une adresse comme défaut
     */
    public function setDefaultAddress(Address $address)
    {
        $user = Auth::user();

        // Vérifier que l'adresse appartient à l'utilisateur
        if ($address->user_id !== $user->id) {
            abort(403, 'Vous n\'êtes pas autorisé à modifier cette adresse.');
        }

        $address->markAsDefault();

        return redirect()->route('profile.addresses')->with('success', 'Adresse définie comme défaut !');
    }
} 