<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|null  $permission  Optionnel: permission spécifique requise
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez vous connecter pour accéder à cette section.');
        }

        $user = Auth::user();
        
        // Vérification de base : l'utilisateur doit être admin
        if (!$user->isAdmin()) {
            // Log de l'tentative d'accès non autorisée
            \Log::warning('Tentative d\'accès non autorisée au panel admin', [
                'user_id' => $user->id,
                'email' => $user->email,
                'account_type' => $user->account_type->value,
                'role' => $user->role ? $user->role->value : 'null',
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);
            
            abort(403, 'Accès non autorisé. Vous devez être administrateur (Manager ou Developer) pour accéder à cette section.');
        }
        
        // Vérifications de permissions spécifiques si demandées
        if ($permission) {
            $hasPermission = match ($permission) {
                'manage_users' => $user->canManageUsers(),
                'manage_commerces' => $user->canManageCommerces(),
                'view_full_stats' => $user->canViewFullStats(),
                'moderate' => $user->canModerate(),
                'super_admin' => $user->isSuperAdmin(),
                default => false,
            };
            
            if (!$hasPermission) {
                \Log::warning('Tentative d\'accès avec permission insuffisante', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'required_permission' => $permission,
                    'url' => $request->url(),
                ]);
                
                abort(403, "Accès non autorisé. Permission '$permission' requise.");
            }
        }

        return $next($request);
    }
} 