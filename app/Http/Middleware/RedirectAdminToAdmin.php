<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\AccountType;

class RedirectAdminToAdmin
{
    /**
     * Handle an incoming request.
     * Redirige automatiquement les utilisateurs admin vers l'espace admin
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Si l'utilisateur est admin et tente d'accéder au site client
            if ($user->account_type === AccountType::ADMIN && !$request->is('admin*')) {
                return redirect()->route('admin.dashboard')->with('info', 'Vous avez été redirigé vers votre espace d\'administration.');
            }
        }

        return $next($request);
    }
}
