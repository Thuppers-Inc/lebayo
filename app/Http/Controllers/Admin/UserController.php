<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AccountType;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of all users.
     */
    public function index(Request $request)
    {
        // Construire la requête de base
        $query = User::withCount(['orders', 'addresses'])
                    ->withSum('orders', 'total')
                    ->withAvg('orders', 'total')
                    ->withMax('orders', 'created_at');

        // Recherche par nom, prénom ou email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('numero_telephone', 'like', "%{$search}%");
            });
        }

        // Filtre par type de compte
        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        // Filtre par statut (actif/inactif)
        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        // Gérer les champs de tri spéciaux
        if ($sortBy === 'orders_count') {
            $query->orderBy('orders_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $users = $query->paginate(15)->appends($request->query());

        // Statistiques globales
        $stats = [
            'total_users' => User::count(),
            'admin_users' => User::where('account_type', AccountType::ADMIN)->count(),
            'moderator_users' => User::where('account_type', AccountType::ADMIN)->where('role', UserRole::MODERATOR)->count(),
            'client_users' => User::where('account_type', AccountType::CLIENT)->count(),
            'agent_users' => User::where('account_type', AccountType::AGENT)->count(),
            'active_users' => User::whereHas('orders')->count(),
            'total_orders' => \App\Models\Order::count(),
            'total_revenue' => \App\Models\Order::sum('total'),
            'avg_order_value' => \App\Models\Order::avg('total'),
        ];

        // Top utilisateurs par nombre de commandes
        $topUsersByOrders = User::withCount('orders')
                               ->withSum('orders', 'total')
                               ->orderBy('orders_count', 'desc')
                               ->take(10)
                               ->get();

        // Top utilisateurs par montant total dépensé
        $topUsersByRevenue = User::withCount('orders')
                                ->withSum('orders', 'total')
                                ->orderBy('orders_sum_total', 'desc')
                                ->take(10)
                                ->get();

        // Utilisateurs récents (inscrits ce mois)
        $recentUsers = User::where('created_at', '>=', now()->startOfMonth())
                          ->withCount('orders')
                          ->orderBy('created_at', 'desc')
                          ->take(10)
                          ->get();

        return view('admin.users.index', compact(
            'users',
            'stats',
            'topUsersByOrders',
            'topUsersByRevenue',
            'recentUsers'
        ));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user.
     */
    public function store(Request $request)
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'indicatif' => 'required|string|max:10',
            'numero_telephone' => 'required|string|max:20|unique:users',
            'date_naissance' => 'nullable|date|before:today',
            'lieu_naissance' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'numero_cni' => 'nullable|string|max:50|unique:users',
            'numero_passeport' => 'nullable|string|max:50|unique:users',
            'account_type' => 'required|in:' . AccountType::ADMIN->value . ',' . AccountType::CLIENT->value . ',' . AccountType::AGENT->value,
            'role' => 'nullable|in:' . UserRole::USER->value . ',' . UserRole::MODERATOR->value . ',' . UserRole::MANAGER->value . ',' . UserRole::DEVELOPER->value,
            'password' => 'required|string|min:8|confirmed',
        ];

        // Validation conditionnelle pour le rôle
        if ($request->account_type === AccountType::ADMIN->value) {
            $rules['role'] = 'required|in:' . UserRole::USER->value . ',' . UserRole::MODERATOR->value . ',' . UserRole::MANAGER->value . ',' . UserRole::DEVELOPER->value;
        }

        $validator = Validator::make($request->all(), $rules, [
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Les prénoms sont obligatoires',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'numero_telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
            'account_type.required' => 'Le type de compte est obligatoire',
            'password.required' => 'Le mot de passe est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'indicatif' => $request->indicatif,
            'numero_telephone' => $request->numero_telephone,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'ville' => $request->ville,
            'commune' => $request->commune,
            'numero_cni' => $request->numero_cni,
            'numero_passeport' => $request->numero_passeport,
            'account_type' => $request->account_type,
            'role' => $request->role,
            'password' => Hash::make($request->password),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès',
                'user' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur créé avec succès');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['orders.orderItems.product', 'addresses']);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        }

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'indicatif' => 'required|string|max:10',
            'numero_telephone' => 'required|string|max:20|unique:users,numero_telephone,' . $user->id,
            'date_naissance' => 'nullable|date|before:today',
            'lieu_naissance' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'numero_cni' => 'nullable|string|max:50|unique:users,numero_cni,' . $user->id,
            'numero_passeport' => 'nullable|string|max:50|unique:users,numero_passeport,' . $user->id,
            'account_type' => 'required|in:' . AccountType::ADMIN->value . ',' . AccountType::CLIENT->value . ',' . AccountType::AGENT->value,
            'role' => 'nullable|in:' . UserRole::USER->value . ',' . UserRole::MODERATOR->value . ',' . UserRole::MANAGER->value . ',' . UserRole::DEVELOPER->value,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        // Validation conditionnelle pour le rôle
        if ($request->account_type === AccountType::ADMIN->value) {
            $rules['role'] = 'required|in:' . UserRole::USER->value . ',' . UserRole::MODERATOR->value . ',' . UserRole::MANAGER->value . ',' . UserRole::DEVELOPER->value;
        }

        $validator = Validator::make($request->all(), $rules, [
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Les prénoms sont obligatoires',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'numero_telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
            'account_type.required' => 'Le type de compte est obligatoire',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ]);

        if ($validator->fails()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $updateData = [
            'nom' => $request->nom,
            'prenoms' => $request->prenoms,
            'email' => $request->email,
            'indicatif' => $request->indicatif,
            'numero_telephone' => $request->numero_telephone,
            'date_naissance' => $request->date_naissance,
            'lieu_naissance' => $request->lieu_naissance,
            'ville' => $request->ville,
            'commune' => $request->commune,
            'numero_cni' => $request->numero_cni,
            'numero_passeport' => $request->numero_passeport,
            'account_type' => $request->account_type,
            'role' => $request->role,
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur modifié avec succès',
                'user' => $user
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur modifié avec succès');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        $user->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès'
            ]);
        }

        return redirect()->route('admin.users.index')
                        ->with('success', 'Utilisateur supprimé avec succès');
    }

    /**
     * Toggle user status (activate/deactivate using soft deletes)
     */
    public function toggleStatus(User $user)
    {
        if ($user->deleted_at) {
            // Restaurer l'utilisateur (activer)
            $user->restore();
            $message = 'Utilisateur activé';
        } else {
            // Désactiver l'utilisateur (soft delete)
            $user->delete();
            $message = 'Utilisateur désactivé';
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'user' => $user->fresh()
            ]);
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Get user orders history.
     */
    public function orders(User $user)
    {
        $orders = $user->orders()
                      ->with(['orderItems.product', 'address'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);
        }

        return view('admin.users.orders', compact('user', 'orders'));
    }

    /**
     * Get user addresses.
     */
    public function addresses(User $user)
    {
        $addresses = $user->addresses()
                         ->orderBy('created_at', 'desc')
                         ->get();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'addresses' => $addresses
            ]);
        }

        return view('admin.users.addresses', compact('user', 'addresses'));
    }

    /**
     * Export users data to CSV
     */
    public function export(Request $request)
    {
        // Utiliser les mêmes filtres que la page index
        $query = User::withCount(['orders', 'addresses'])
                    ->withSum('orders', 'total')
                    ->withAvg('orders', 'total')
                    ->withMax('orders', 'created_at');

        // Appliquer les mêmes filtres que la recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenoms', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('numero_telephone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('deleted_at');
            } elseif ($request->status === 'inactive') {
                $query->whereNotNull('deleted_at');
            }
        }

        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');

        if ($sortBy === 'orders_count') {
            $query->orderBy('orders_count', $sortOrder);
        } else {
            $query->orderBy($sortBy, $sortOrder);
        }

        $users = $query->get();

        $filename = 'utilisateurs_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($users) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Type de Compte',
                'Ville',
                'Commune',
                'Date de naissance',
                'Nombre de commandes',
                'Total dépensé (FCFA)',
                'Moyenne par commande (FCFA)',
                'Dernière commande',
                'Statut',
                'Date d\'inscription'
            ]);

            // Données
            foreach ($users as $user) {
                fputcsv($file, [
                    $user->id,
                    $user->nom,
                    $user->prenoms,
                    $user->email,
                    $user->formatted_phone,
                    $user->account_type_label,
                    $user->ville,
                    $user->commune,
                    $user->date_naissance ? $user->date_naissance->format('d/m/Y') : '',
                    $user->orders_count,
                    $user->orders_sum_total ?? 0,
                    round($user->orders_avg_total ?? 0),
                    $user->formatted_last_order_date,
                    $user->deleted_at ? 'Inactif' : 'Actif',
                    $user->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
