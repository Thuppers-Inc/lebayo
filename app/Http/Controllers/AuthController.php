<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\AccountType;
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
        $request->validate([
            'identifier' => ['required', 'string'],
            'password' => ['required'],
        ]);

        // Récupérer l'ID de session AVANT la tentative de connexion
        $sessionId = $request->session()->getId();
        $identifier = $request->identifier;

        \Log::info('Login attempt', [
            'identifier' => $identifier,
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

        // Déterminer si c'est un email ou un numéro de téléphone
        $isEmail = filter_var($identifier, FILTER_VALIDATE_EMAIL);
        $user = null;
        $loginSuccessful = false;

        // Chercher l'utilisateur par email ou téléphone
        if ($isEmail) {
            $user = User::where('email', $identifier)->first();
            if ($user && $user->email) {
                // Utiliser Auth::attempt avec l'email
                $loginSuccessful = Auth::attempt(['email' => $identifier, 'password' => $request->password], $request->boolean('remember'));
            }
        } else {
            // Nettoyer le numéro de téléphone
            $cleanedPhone = preg_replace('/[^0-9]/', '', $identifier);
            $user = User::where('numero_telephone', $cleanedPhone)->first();

            // Si l'utilisateur existe, vérifier le mot de passe
            if ($user) {
                if ($user->email) {
                    // Si l'utilisateur a un email, utiliser Auth::attempt
                    $loginSuccessful = Auth::attempt(['email' => $user->email, 'password' => $request->password], $request->boolean('remember'));
                } else {
                    // Si pas d'email, vérifier le mot de passe manuellement
                    if (Hash::check($request->password, $user->password)) {
                        // Connecter l'utilisateur manuellement
                        Auth::login($user, $request->boolean('remember'));
                        $loginSuccessful = true;
                    }
                }
            }
        }

        if ($loginSuccessful) {
            $user = Auth::user();
            \Log::info('Login successful', [
                'user_id' => $user->id,
                'identifier' => $identifier,
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

            // Rediriger selon le type de compte de l'utilisateur
            if ($user->account_type === AccountType::ADMIN) {
                return redirect()->intended('/admin/dashboard')->with('success', $welcomeMessage);
            }

            return redirect()->intended('/')->with('success', $welcomeMessage);
        }

        throw ValidationException::withMessages([
            'identifier' => 'Ces identifiants ne correspondent pas à nos enregistrements.',
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
        // Nettoyer le numéro de téléphone d'abord pour la validation
        $cleanedPhone = preg_replace('/[^0-9]/', '', $request->numero_telephone);

        $request->validate([
            'nom' => ['required', 'string', 'max:255'],
            'prenoms' => ['required', 'string', 'max:255'],
            'commune' => ['required', 'string', 'max:255'],
            'indicatif' => ['required', 'string', 'max:10'],
            'numero_telephone' => ['required', 'string', 'max:20'],
            'email' => ['nullable', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'terms' => ['required', 'accepted'],
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Les prénoms sont obligatoires',
            'commune.required' => 'Le quartier est obligatoire',
            'indicatif.required' => 'L\'indicatif est obligatoire',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'email.email' => 'L\'adresse email doit être valide',
            'email.unique' => 'Cette adresse email est déjà utilisée',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
            'terms.accepted' => 'Vous devez accepter les conditions d\'utilisation',
        ]);

        // Vérifier l'unicité du numéro de téléphone nettoyé
        $existingUser = User::where('numero_telephone', $cleanedPhone)->first();
        if ($existingUser) {
            return redirect()->back()->withErrors([
                'numero_telephone' => 'Ce numéro de téléphone est déjà utilisé.'
            ])->withInput();
        }

        // Récupérer l'ID de session avant de créer l'utilisateur
        $sessionId = $request->session()->getId();

        $user = User::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email ?? null, // Email optionnel
            'password' => Hash::make($request->password),
            'account_type' => 'client', // Par défaut, les nouvelles inscriptions sont des clients
            'is_super_admin' => false,
            'indicatif' => $request->indicatif,
            'numero_telephone' => $cleanedPhone,
            'commune' => $request->commune,
            'ville' => '',
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
