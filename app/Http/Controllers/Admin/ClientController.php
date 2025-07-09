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
        $clients = User::where('account_type', AccountType::CLIENT)
                      ->withCount(['orders', 'addresses'])
                      ->orderBy('created_at', 'desc')
                      ->paginate(15);

        return view('admin.clients.index', compact('clients'));
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
} 