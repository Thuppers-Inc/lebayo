<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class AdminProfileController extends Controller
{
    /**
     * Afficher le profil de l'administrateur
     */
    public function index()
    {
        $user = Auth::user();
        
        return view('admin.profile.index', compact('user'));
    }

    /**
     * Mettre à jour les informations du profil
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

        return redirect()->route('admin.profile.index')->with('success', 'Profil mis à jour avec succès !');
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

        return redirect()->route('admin.profile.index')->with('success', 'Mot de passe mis à jour avec succès !');
    }
} 