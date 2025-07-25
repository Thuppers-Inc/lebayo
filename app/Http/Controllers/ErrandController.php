<?php

namespace App\Http\Controllers;

use App\Models\ErrandRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ErrandController extends Controller
{
    /**
     * Afficher le formulaire de demande de course
     */
    public function create()
    {
        return view('errand.create');
    }

    /**
     * Enregistrer une nouvelle demande de course
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'pickup_address' => 'required|string|min:10',
            'delivery_address' => 'required|string|min:10',
            'estimated_cost' => 'nullable|numeric|min:0',
            'urgency_level' => 'required|in:low,medium,high,urgent',
            'contact_phone' => 'nullable|string|max:20',
            'preferred_delivery_time' => 'nullable|date|after:now',
            'notes' => 'nullable|string|max:1000',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->all();
            $data['user_id'] = Auth::id();

            // Gérer l'upload de photo si fournie
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('errand-photos', 'public');
                $data['photo_path'] = $photoPath;
            }

            $errandRequest = ErrandRequest::create($data);

            return redirect()->route('errand.success', $errandRequest)
                ->with('success', 'Votre demande de course a été envoyée avec succès !');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de l\'envoi de la demande : ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Afficher la page de succès
     */
    public function success(ErrandRequest $errandRequest)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la demande
        if ($errandRequest->user_id !== Auth::id()) {
            abort(403);
        }

        return view('errand.success', compact('errandRequest'));
    }

    /**
     * Afficher la liste des demandes de l'utilisateur
     */
    public function index()
    {
        $errandRequests = ErrandRequest::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('errand.index', compact('errandRequests'));
    }

    /**
     * Afficher les détails d'une demande
     */
    public function show(ErrandRequest $errandRequest)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la demande
        if ($errandRequest->user_id !== Auth::id()) {
            abort(403);
        }

        return view('errand.show', compact('errandRequest'));
    }

    /**
     * Annuler une demande
     */
    public function cancel(ErrandRequest $errandRequest)
    {
        // Vérifier que l'utilisateur est bien le propriétaire de la demande
        if ($errandRequest->user_id !== Auth::id()) {
            abort(403);
        }

        // Vérifier que la demande peut être annulée
        if (!in_array($errandRequest->status, [ErrandRequest::STATUS_PENDING, ErrandRequest::STATUS_ACCEPTED])) {
            return redirect()->back()
                ->with('error', 'Cette demande ne peut plus être annulée.');
        }

        $errandRequest->update(['status' => ErrandRequest::STATUS_CANCELLED]);

        return redirect()->route('errand.index')
            ->with('success', 'Demande annulée avec succès !');
    }
}
