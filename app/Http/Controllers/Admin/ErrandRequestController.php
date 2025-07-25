<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ErrandRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ErrandRequestController extends Controller
{
    /**
     * Afficher la liste des demandes de course
     */
    public function index(Request $request)
    {
        $query = ErrandRequest::with('user');

        // Filtres
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('pickup_address', 'like', "%{$search}%")
                  ->orWhere('delivery_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('nom', 'like', "%{$search}%")
                               ->orWhere('prenoms', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $errandRequests = $query->paginate(15);

        // Statistiques
        $stats = [
            'total' => ErrandRequest::count(),
            'pending' => ErrandRequest::where('status', 'pending')->count(),
            'accepted' => ErrandRequest::where('status', 'accepted')->count(),
            'in_progress' => ErrandRequest::where('status', 'in_progress')->count(),
            'completed' => ErrandRequest::where('status', 'completed')->count(),
            'cancelled' => ErrandRequest::where('status', 'cancelled')->count(),
        ];

        return view('admin.errand-requests.index', compact('errandRequests', 'stats'));
    }

    /**
     * Afficher les détails d'une demande
     */
    public function show(ErrandRequest $errandRequest)
    {
        $errandRequest->load('user');
        return view('admin.errand-requests.show', compact('errandRequest'));
    }

    /**
     * Mettre à jour le statut d'une demande
     */
    public function updateStatus(Request $request, ErrandRequest $errandRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,accepted,in_progress,completed,cancelled'
        ]);

        $oldStatus = $errandRequest->status;
        $newStatus = $request->status;

        $errandRequest->update(['status' => $newStatus]);

        // Log de l'activité
        activity()
            ->performedOn($errandRequest)
            ->log("Statut de la demande #{$errandRequest->id} changé de {$oldStatus} à {$newStatus}");

        return redirect()->back()
            ->with('success', "Le statut de la demande #{$errandRequest->id} a été mis à jour avec succès.");
    }

    /**
     * Supprimer une demande (soft delete)
     */
    public function destroy(ErrandRequest $errandRequest)
    {
        try {
            // Log de l'activité avant suppression
            activity()
                ->performedOn($errandRequest)
                ->log("Demande #{$errandRequest->id} supprimée par l'administrateur");

            $errandRequest->delete();
            return redirect()->route('admin.errand-requests.index')
                ->with('success', "La demande #{$errandRequest->id} a été supprimée avec succès.");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Erreur lors de la suppression de la demande.');
        }
    }

    /**
     * Afficher les statistiques des demandes
     */
    public function stats()
    {
        // Statistiques générales
        $totalRequests = ErrandRequest::count();
        $totalUsers = User::whereHas('errandRequests')->count();
        
        // Statistiques par statut
        $statusStats = DB::table('errand_requests')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        // Statistiques par urgence
        $urgencyStats = DB::table('errand_requests')
            ->select('urgency_level', DB::raw('count(*) as count'))
            ->groupBy('urgency_level')
            ->get()
            ->keyBy('urgency_level');

        // Statistiques par mois (derniers 6 mois)
        $monthlyStats = DB::table('errand_requests')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Top utilisateurs
        $topUsers = User::withCount('errandRequests')
            ->having('errand_requests_count', '>', 0)
            ->orderBy('errand_requests_count', 'desc')
            ->take(10)
            ->get();

        return view('admin.errand-requests.stats', compact(
            'totalRequests',
            'totalUsers',
            'statusStats',
            'urgencyStats',
            'monthlyStats',
            'topUsers'
        ));
    }

    /**
     * Exporter les demandes en CSV
     */
    public function export(Request $request)
    {
        $query = ErrandRequest::with('user');

        // Appliquer les mêmes filtres que dans index
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('urgency')) {
            $query->where('urgency_level', $request->urgency);
        }

        $errandRequests = $query->get();

        $filename = 'demandes_course_' . date('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($errandRequests) {
            $file = fopen('php://output', 'w');
            
            // En-têtes
            fputcsv($file, [
                'ID', 'Utilisateur', 'Titre', 'Description', 'Adresse départ', 
                'Adresse livraison', 'Coût estimé', 'Urgence', 'Statut', 
                'Téléphone', 'Notes', 'Date création', 'Date préférée'
            ]);

            // Données
            foreach ($errandRequests as $request) {
                fputcsv($file, [
                    $request->id,
                    $request->user->nom . ' ' . $request->user->prenoms,
                    $request->title,
                    $request->description,
                    $request->pickup_address,
                    $request->delivery_address,
                    $request->estimated_cost,
                    $request->urgency_label,
                    $request->status_label,
                    $request->contact_phone,
                    $request->notes,
                    $request->created_at->format('d/m/Y H:i'),
                    $request->preferred_delivery_time ? $request->preferred_delivery_time->format('d/m/Y H:i') : ''
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Afficher les logs d'activité d'une demande
     */
    public function logs(ErrandRequest $errandRequest)
    {
        $activities = $errandRequest->activities()->latest()->get();
        return view('admin.errand-requests.logs', compact('errandRequest', 'activities'));
    }
}
