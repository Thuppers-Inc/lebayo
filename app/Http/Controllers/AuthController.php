<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Cart;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * Traiter la connexion
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Récupérer l'ID de session AVANT la tentative de connexion
        $sessionId = $request->session()->getId();
        \Log::info('Login attempt', [
            'email' => $credentials['email'],
            'session_id' => $sessionId
        ]);

        // Vérifier si il y a un panier de session
        $sessionCart = Cart::where('session_id', $sessionId)->first();
        $sessionCartItems = $sessionCart ? $sessionCart->total_items : 0;
        \Log::info('Session cart before login', [
            'session_id' => $sessionId,
            'cart_exists' => $sessionCart !== null,
            'items_count' => $sessionCartItems
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = Auth::user();
            \Log::info('Login successful', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'session_id' => $sessionId
            ]);
            
            // Migrer le panier de session vers l'utilisateur connecté
            $migratedCart = Cart::migrateSessionCartToUser($user->id, $sessionId);
            \Log::info('Cart migration result', [
                'user_id' => $user->id,
                'session_id' => $sessionId,
                'migrated_cart_id' => $migratedCart ? $migratedCart->id : null,
                'migrated_items' => $migratedCart ? $migratedCart->total_items : 0
            ]);
            
            // Régénérer la session APRÈS la migration
            $request->session()->regenerate();
            
            // Message de bienvenue avec info sur le panier
            $welcomeMessage = "Bon retour, {$user->prenoms} !";
            if ($migratedCart && $migratedCart->total_items > 0) {
                $welcomeMessage .= " Votre panier a été restauré ({$migratedCart->total_items} " . 
                                 ($migratedCart->total_items > 1 ? 'articles' : 'article') . ").";
            }
            
            // Rediriger selon le rôle de l'utilisateur
            if ($user->isAdmin()) {
                return redirect()->intended('/admin/dashboard')->with('success', $welcomeMessage);
            }
            
            return redirect()->intended('/')->with('success', $welcomeMessage);
        }

        throw ValidationException::withMessages([
            'email' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
        ]);
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Traiter l'inscription
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ]);

        // Récupérer l'ID de session avant de créer l'utilisateur
        $sessionId = $request->session()->getId();

        // Séparer le nom complet en nom et prénoms
        $nameparts = explode(' ', $request->name, 2);
        $prenoms = $nameparts[0];
        $nom = $nameparts[1] ?? '';

        $user = User::create([
            'nom' => $nom,
            'prenoms' => $prenoms,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'account_type' => 'client', // Par défaut, les nouvelles inscriptions sont des clients
            'is_super_admin' => false,
            'indicatif' => '+221',
            'numero_telephone' => '',
            'ville' => '',
            'commune' => '',
        ]);

        Auth::login($user);

        // Migrer le panier de session vers le nouvel utilisateur
        $migratedCart = Cart::migrateSessionCartToUser($user->id, $sessionId);

        // Message de bienvenue avec info sur le panier
        $welcomeMessage = "Bienvenue sur Lebayo, {$user->prenoms} ! Votre compte a été créé avec succès.";
        if ($migratedCart && $migratedCart->total_items > 0) {
            $welcomeMessage .= " Votre panier a été sauvegardé ({$migratedCart->total_items} " . 
                             ($migratedCart->total_items > 1 ? 'articles' : 'article') . ").";
        }

        return redirect('/')->with('success', $welcomeMessage);
    }

    /**
     * Déconnecter l'utilisateur
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
} 