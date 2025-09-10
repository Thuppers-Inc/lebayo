<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AccountType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    /**
     * Display a listing of clients.
     */
    public function index()
    {
        // Récupérer les clients avec des statistiques avancées
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount(['orders', 'addresses'])
                      ->withSum('orders', 'total')
                      ->withAvg('orders', 'total')
                      ->withMax('orders', 'created_at')
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        // Statistiques globales
        $stats = [
            'total_clients' => User::where('account_type', AccountType::CLIENT)->count(),
            'active_clients' => User::where('account_type', AccountType::CLIENT)
                                   ->whereHas('orders')
                                   ->count(),
            'total_orders' => \App\Models\Order::whereHas('user', function($q) {
                                   $q->where('account_type', AccountType::CLIENT);
                               })->count(),
            'total_revenue' => \App\Models\Order::whereHas('user', function($q) {
                                   $q->where('account_type', AccountType::CLIENT);
                               })->sum('total'),
            'avg_order_value' => \App\Models\Order::whereHas('user', function($q) {
                                   $q->where('account_type', AccountType::CLIENT);
                               })->avg('total'),
        ];

        // Top clients par nombre de commandes
        $topClientsByOrders = User::where('account_type', AccountType::CLIENT)
                                  ->withCount('orders')
                                  ->withSum('orders', 'total')
                                  ->orderBy('orders_count', 'desc')
                                  ->take(5)
                                  ->get();

        // Top clients par montant total dépensé
        $topClientsByRevenue = User::where('account_type', AccountType::CLIENT)
                                   ->withCount('orders')
                                   ->withSum('orders', 'total')
                                   ->orderBy('orders_sum_total', 'desc')
                                   ->take(5)
                                   ->get();

        // Clients récents (inscrits ce mois)
        $recentClients = User::where('account_type', AccountType::CLIENT)
                             ->where('created_at', '>=', now()->startOfMonth())
                             ->withCount('orders')
                             ->orderBy('created_at', 'desc')
                             ->take(5)
                             ->get();

        return view('admin.clients.index', compact(
            'clients',
            'stats',
            'topClientsByOrders',
            'topClientsByRevenue',
            'recentClients'
        ));
    }

    /**
     * Show the form for creating a new client.
     */
    public function create()
    {
        return view('admin.clients.create');
    }

    /**
     * Store a newly created client.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
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
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Les prénoms sont obligatoires',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'numero_telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
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

        $client = User::create([
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
            'password' => Hash::make($request->password),
            'account_type' => AccountType::CLIENT,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Client créé avec succès',
                'client' => $client
            ]);
        }

        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client créé avec succès');
    }

    /**
     * Display the specified client.
     */
    public function show(User $client)
    {
        if ($client->account_type !== AccountType::CLIENT) {
            abort(404);
        }

        $client->load(['orders.orderItems.product', 'addresses']);

        return view('admin.clients.show', compact('client'));
    }

    /**
     * Show the form for editing the specified client.
     */
    public function edit(User $client)
    {
        if ($client->account_type !== AccountType::CLIENT) {
            abort(404);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'client' => $client
            ]);
        }

        return view('admin.clients.edit', compact('client'));
    }

    /**
     * Update the specified client.
     */
    public function update(Request $request, User $client)
    {
        if ($client->account_type !== AccountType::CLIENT) {
            abort(404);
        }

        $validator = Validator::make($request->all(), [
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $client->id,
            'indicatif' => 'required|string|max:10',
            'numero_telephone' => 'required|string|max:20|unique:users,numero_telephone,' . $client->id,
            'date_naissance' => 'nullable|date|before:today',
            'lieu_naissance' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:255',
            'commune' => 'nullable|string|max:255',
            'numero_cni' => 'nullable|string|max:50|unique:users,numero_cni,' . $client->id,
            'numero_passeport' => 'nullable|string|max:50|unique:users,numero_passeport,' . $client->id,
            'password' => 'nullable|string|min:8|confirmed',
        ], [
            'nom.required' => 'Le nom est obligatoire',
            'prenoms.required' => 'Les prénoms sont obligatoires',
            'email.required' => 'L\'email est obligatoire',
            'email.unique' => 'Cet email est déjà utilisé',
            'numero_telephone.required' => 'Le numéro de téléphone est obligatoire',
            'numero_telephone.unique' => 'Ce numéro de téléphone est déjà utilisé',
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
        ];

        // Mettre à jour le mot de passe seulement s'il est fourni
        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $client->update($updateData);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Client modifié avec succès',
                'client' => $client
            ]);
        }

        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client modifié avec succès');
    }

    /**
     * Remove the specified client.
     */
    public function destroy(User $client)
    {
        if ($client->account_type !== AccountType::CLIENT) {
            abort(404);
        }

        $client->delete();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Client supprimé avec succès'
            ]);
        }

        return redirect()->route('admin.clients.index')
                        ->with('success', 'Client supprimé avec succès');
    }

    /**
     * Get client orders history.
     */
    public function orders(User $client)
    {
        if ($client->account_type !== AccountType::CLIENT) {
            abort(404);
        }

        $orders = $client->orders()
                         ->with(['orderItems.product', 'address'])
                         ->orderBy('created_at', 'desc')
                         ->paginate(10);

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'orders' => $orders
            ]);
        }

        return view('admin.clients.orders', compact('client', 'orders'));
    }

    /**
     * Get client addresses.
     */
    public function addresses(User $client)
    {
        if ($client->account_type !== AccountType::CLIENT) {
            abort(404);
        }

        $addresses = $client->addresses()
                           ->orderBy('created_at', 'desc')
                           ->get();

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'addresses' => $addresses
            ]);
        }

        return view('admin.clients.addresses', compact('client', 'addresses'));
    }

    /**
     * Display detailed clients page with complete list and advanced analytics
     */
    public function details()
    {
        // Récupérer tous les clients avec des statistiques avancées
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount(['orders', 'addresses'])
                      ->withSum('orders', 'total')
                      ->withAvg('orders', 'total')
                      ->withMax('orders', 'created_at')
                      ->orderBy('created_at', 'desc')
                      ->paginate(20);

        // Statistiques globales détaillées
        $stats = [
            'total_clients' => User::where('account_type', AccountType::CLIENT)->count(),
            'active_clients' => User::where('account_type', AccountType::CLIENT)
                                   ->whereHas('orders')
                                   ->count(),
            'new_clients_this_month' => User::where('account_type', AccountType::CLIENT)
                                           ->where('created_at', '>=', now()->startOfMonth())
                                           ->count(),
            'total_orders' => \App\Models\Order::whereHas('user', function($q) {
                                   $q->where('account_type', AccountType::CLIENT);
                               })->count(),
            'total_revenue' => \App\Models\Order::whereHas('user', function($q) {
                                   $q->where('account_type', AccountType::CLIENT);
                               })->sum('total'),
            'avg_order_value' => \App\Models\Order::whereHas('user', function($q) {
                                   $q->where('account_type', AccountType::CLIENT);
                               })->avg('total'),
            'avg_orders_per_client' => User::where('account_type', AccountType::CLIENT)
                                          ->whereHas('orders')
                                          ->withCount('orders')
                                          ->get()
                                          ->avg('orders_count'),
        ];

        // Top clients par nombre de commandes (paginé)
        $topClientsByOrders = User::where('account_type', AccountType::CLIENT)
                                  ->withCount('orders')
                                  ->withSum('orders', 'total')
                                  ->orderBy('orders_count', 'desc')
                                  ->paginate(15, ['*'], 'orders_page');

        // Top clients par montant total dépensé (paginé)
        $topClientsByRevenue = User::where('account_type', AccountType::CLIENT)
                                   ->withCount('orders')
                                   ->withSum('orders', 'total')
                                   ->orderBy('orders_sum_total', 'desc')
                                   ->paginate(15, ['*'], 'revenue_page');

        // Clients récents (inscrits ce mois)
        $recentClients = User::where('account_type', AccountType::CLIENT)
                             ->where('created_at', '>=', now()->startOfMonth())
                             ->withCount('orders')
                             ->orderBy('created_at', 'desc')
                             ->take(10)
                             ->get();

        // Clients les plus actifs (dernière commande dans les 30 derniers jours)
        $activeClients = User::where('account_type', AccountType::CLIENT)
                             ->whereHas('orders', function($q) {
                                 $q->where('created_at', '>=', now()->subDays(30));
                             })
                             ->withCount('orders')
                             ->withSum('orders', 'total')
                             ->orderBy('orders_sum_total', 'desc')
                             ->take(10)
                             ->get();

        return view('admin.clients.details', compact(
            'clients',
            'stats',
            'topClientsByOrders',
            'topClientsByRevenue',
            'recentClients',
            'activeClients'
        ));
    }

    /**
     * Export detailed clients data to CSV
     */
    public function exportDetails()
    {
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount(['orders', 'addresses'])
                      ->withSum('orders', 'total')
                      ->withAvg('orders', 'total')
                      ->withMax('orders', 'created_at')
                      ->orderBy('created_at', 'desc')
                      ->get();

        $filename = 'clients_details_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV détaillés
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Date de naissance',
                'Lieu de naissance',
                'Ville',
                'Commune',
                'Numéro CNI',
                'Numéro Passeport',
                'Nombre de commandes',
                'Total dépensé (FCFA)',
                'Moyenne par commande (FCFA)',
                'Dernière commande',
                'Nombre d\'adresses',
                'Statut client',
                'Date d\'inscription',
                'Ancienneté (jours)'
            ]);

            // Données détaillées
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->nom,
                    $client->prenoms,
                    $client->email,
                    $client->formatted_phone,
                    $client->date_naissance ? $client->date_naissance->format('d/m/Y') : '',
                    $client->lieu_naissance ?? '',
                    $client->ville ?? '',
                    $client->commune ?? '',
                    $client->numero_cni ?? '',
                    $client->numero_passeport ?? '',
                    $client->orders_count,
                    $client->orders_sum_total ?? 0,
                    round($client->orders_avg_total ?? 0),
                    $client->formatted_last_order_date ?? 'Aucune',
                    $client->addresses_count,
                    $client->client_status,
                    $client->created_at->format('d/m/Y H:i'),
                    $client->created_at->diffInDays(now())
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export clients data to CSV
     */
    public function export()
    {
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount(['orders', 'addresses'])
                      ->withSum('orders', 'total')
                      ->withAvg('orders', 'total')
                      ->withMax('orders', 'created_at')
                      ->orderBy('created_at', 'desc')
                      ->get();

        $filename = 'clients_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Ville',
                'Commune',
                'Date de naissance',
                'Nombre de commandes',
                'Total dépensé (FCFA)',
                'Moyenne par commande (FCFA)',
                'Dernière commande',
                'Statut client',
                'Date d\'inscription'
            ]);

            // Données
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->nom,
                    $client->prenoms,
                    $client->email,
                    $client->formatted_phone,
                    $client->ville,
                    $client->commune,
                    $client->date_naissance ? $client->date_naissance->format('d/m/Y') : '',
                    $client->orders_count,
                    $client->orders_sum_total ?? 0,
                    round($client->orders_avg_total ?? 0),
                    $client->formatted_last_order_date,
                    $client->client_status,
                    $client->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export top clients by orders to CSV
     */
    public function exportTopByOrders()
    {
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount('orders')
                      ->withSum('orders', 'total')
                      ->orderBy('orders_count', 'desc')
                      ->get();

        $filename = 'top_clients_par_commandes_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Rang',
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Ville',
                'Commune',
                'Nombre de commandes',
                'Total dépensé (FCFA)',
                'Moyenne par commande (FCFA)',
                'Dernière commande',
                'Statut client',
                'Date d\'inscription'
            ]);

            // Données avec rang
            $rank = 1;
            foreach ($clients as $client) {
                fputcsv($file, [
                    $rank++,
                    $client->id,
                    $client->nom,
                    $client->prenoms,
                    $client->email,
                    $client->formatted_phone,
                    $client->ville,
                    $client->commune,
                    $client->orders_count,
                    $client->orders_sum_total ?? 0,
                    round($client->orders_avg_total ?? 0),
                    $client->formatted_last_order_date ?? 'Aucune',
                    $client->client_status,
                    $client->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export top clients by revenue to CSV
     */
    public function exportTopByRevenue()
    {
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount('orders')
                      ->withSum('orders', 'total')
                      ->orderBy('orders_sum_total', 'desc')
                      ->get();

        $filename = 'top_clients_par_depenses_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'Rang',
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Ville',
                'Commune',
                'Total dépensé (FCFA)',
                'Nombre de commandes',
                'Moyenne par commande (FCFA)',
                'Dernière commande',
                'Statut client',
                'Date d\'inscription'
            ]);

            // Données avec rang
            $rank = 1;
            foreach ($clients as $client) {
                fputcsv($file, [
                    $rank++,
                    $client->id,
                    $client->nom,
                    $client->prenoms,
                    $client->email,
                    $client->formatted_phone,
                    $client->ville,
                    $client->commune,
                    $client->orders_sum_total ?? 0,
                    $client->orders_count,
                    round($client->orders_avg_total ?? 0),
                    $client->formatted_last_order_date ?? 'Aucune',
                    $client->client_status,
                    $client->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export recent clients to CSV
     */
    public function exportRecentClients()
    {
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->where('created_at', '>=', now()->startOfMonth())
                      ->withCount('orders')
                      ->orderBy('created_at', 'desc')
                      ->get();

        $filename = 'nouveaux_clients_ce_mois_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Ville',
                'Commune',
                'Date d\'inscription',
                'Nombre de commandes',
                'Statut client'
            ]);

            // Données
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->nom,
                    $client->prenoms,
                    $client->email,
                    $client->formatted_phone,
                    $client->ville,
                    $client->commune,
                    $client->created_at->format('d/m/Y H:i'),
                    $client->orders_count,
                    $client->client_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Export active clients to CSV
     */
    public function exportActiveClients()
    {
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->whereHas('orders', function($q) {
                          $q->where('created_at', '>=', now()->subDays(30));
                      })
                      ->withCount('orders')
                      ->withSum('orders', 'total')
                      ->orderBy('orders_sum_total', 'desc')
                      ->get();

        $filename = 'clients_actifs_30_jours_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($clients) {
            $file = fopen('php://output', 'w');

            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Nom',
                'Prénoms',
                'Email',
                'Téléphone',
                'Ville',
                'Commune',
                'Dépenses récentes (FCFA)',
                'Nombre de commandes',
                'Dernière commande',
                'Statut client'
            ]);

            // Données
            foreach ($clients as $client) {
                fputcsv($file, [
                    $client->id,
                    $client->nom,
                    $client->prenoms,
                    $client->email,
                    $client->formatted_phone,
                    $client->ville,
                    $client->commune,
                    $client->orders_sum_total ?? 0,
                    $client->orders_count,
                    $client->formatted_last_order_date ?? 'Aucune',
                    $client->client_status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
