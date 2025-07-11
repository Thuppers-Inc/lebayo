<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class ModeratorMiddleware
{
    /**
     * Handle an incoming request.
     * Les modérateurs ont un accès limité au panel admin
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Vous devez vous connecter pour accéder à cette section.');
        }

        $user = Auth::user();
        
        // Vérifier si l'utilisateur peut modérer (admin complet ou modérateur)
        if (!$user->canModerate()) {
            \Log::warning('Tentative d\'accès non autorisée à la modération', [
                'user_id' => $user->id,
                'email' => $user->email,
                'account_type' => $user->account_type->value,
                'role' => $user->role ? $user->role->value : 'null',
                'url' => $request->url(),
                'ip' => $request->ip(),
            ]);
            
            abort(403, 'Accès non autorisé. Vous devez avoir des droits de modération pour accéder à cette section.');
        }

        return $next($request);
    }
} 