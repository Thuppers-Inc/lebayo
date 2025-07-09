<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AccountType;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class LivreurController extends Controller
{
    /**
     * Afficher la liste des livreurs
     */
    public function index()
    {
        $livreurs = User::where('account_type', AccountType::AGENT)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('admin.livreurs.index', compact('livreurs'));
    }

    /**
     * Afficher le formulaire de création d'un livreur
     */
    public function create()
    {
        return view('admin.livreurs.create');
    }

    /**
     * Enregistrer un nouveau livreur
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'indicatif' => 'required|string|max:10',
            'numero_telephone' => 'required|string|max:20|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
            'ville' => 'required|string|max:255',
            'commune' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'numero_cni' => 'nullable|string|max:50|unique:users',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/users', $photoName);
            $validated['photo'] = 'users/' . $photoName;
        }

        // Données spécifiques aux livreurs
        $validated['account_type'] = AccountType::AGENT;
        $validated['role'] = UserRole::USER;
        $validated['password'] = Hash::make($validated['password']);

        $livreur = User::create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Livreur créé avec succès !',
                'livreur' => $livreur
            ]);
        }

        return redirect()->route('admin.livreurs.index')
            ->with('success', 'Livreur créé avec succès !');
    }

    /**
     * Afficher un livreur
     */
    public function show(User $livreur)
    {
        // Vérifier que c'est bien un agent/livreur
        if ($livreur->account_type !== AccountType::AGENT) {
            abort(404);
        }

        return view('admin.livreurs.show', compact('livreur'));
    }

    /**
     * Afficher le formulaire d'édition
     */
    public function edit(User $livreur)
    {
        // Vérifier que c'est bien un agent/livreur
        if ($livreur->account_type !== AccountType::AGENT) {
            abort(404);
        }

        if (request()->ajax()) {
            return response()->json([
                'success' => true,
                'livreur' => $livreur
            ]);
        }

        return view('admin.livreurs.edit', compact('livreur'));
    }

    /**
     * Mettre à jour un livreur
     */
    public function update(Request $request, User $livreur)
    {
        // Vérifier que c'est bien un agent/livreur
        if ($livreur->account_type !== AccountType::AGENT) {
            abort(404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $livreur->id,
            'indicatif' => 'required|string|max:10',
            'numero_telephone' => 'required|string|max:20|unique:users,numero_telephone,' . $livreur->id,
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'ville' => 'required|string|max:255',
            'commune' => 'nullable|string|max:255',
            'date_naissance' => 'nullable|date|before:today',
            'numero_cni' => 'nullable|string|max:50|unique:users,numero_cni,' . $livreur->id,
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Gestion de la photo
        if ($request->hasFile('photo')) {
            // Supprimer l'ancienne photo si elle existe
            if ($livreur->photo) {
                $oldPhotoPath = storage_path('app/public/' . $livreur->photo);
                if (file_exists($oldPhotoPath)) {
                    unlink($oldPhotoPath);
                }
            }

            $photo = $request->file('photo');
            $photoName = time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photo->storeAs('public/users', $photoName);
            $validated['photo'] = 'users/' . $photoName;
        }

        // Mettre à jour le mot de passe seulement s'il est fourni
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $livreur->update($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Livreur modifié avec succès !',
                'livreur' => $livreur->fresh()
            ]);
        }

        return redirect()->route('admin.livreurs.index')
            ->with('success', 'Livreur modifié avec succès !');
    }

    /**
     * Supprimer un livreur
     */
    public function destroy(User $livreur)
    {
        try {
            // Vérifier que c'est bien un agent/livreur
            if ($livreur->account_type !== AccountType::AGENT) {
                abort(404);
            }

            // Supprimer la photo
            if ($livreur->photo) {
                $photoPath = storage_path('app/public/' . $livreur->photo);
                if (file_exists($photoPath)) {
                    unlink($photoPath);
                }
            }

            $livreur->delete();

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Livreur supprimé avec succès !'
                ]);
            }

            return redirect()->route('admin.livreurs.index')
                ->with('success', 'Livreur supprimé avec succès !');

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la suppression du livreur.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression du livreur.');
        }
    }

    /**
     * Activer/Désactiver un livreur
     */
    public function toggleStatus(User $livreur)
    {
        try {
            // Vérifier que c'est bien un agent/livreur
            if ($livreur->account_type !== AccountType::AGENT) {
                abort(404);
            }

            // Pour l'instant on utilise deleted_at comme statut actif/inactif
            if ($livreur->trashed()) {
                $livreur->restore();
                $status = 'activé';
            } else {
                $livreur->delete();
                $status = 'désactivé';
            }

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Livreur {$status} avec succès !",
                    'is_active' => !$livreur->trashed()
                ]);
            }

            return redirect()->back()
                ->with('success', "Livreur {$status} avec succès !");

        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors du changement de statut.'
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Erreur lors du changement de statut.');
        }
    }
}
