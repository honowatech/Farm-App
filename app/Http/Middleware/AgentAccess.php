<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentAccess
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Vérifier si l'utilisateur est connecté
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Si l'utilisateur n'a pas de rôle défini, refuser l'accès
        if (! $user->type_user) {
            return redirect()->route('home')->with('error', 'Accès non autorisé.');
        }

        // Si l'utilisateur est un admin, autoriser tout
        if ($user->type_user->libelle === 'admin') {
            return $next($request);
        }

        // Si l'utilisateur est un agent
        if ($user->type_user->libelle === 'agent') {
            $currentRouteName = $request->route()->getName();

            // Vérifier si la route est dans la liste des routes autorisées
            if (in_array($currentRouteName, config('agent_access.allowed_routes'))) {
                return $next($request);
            }

            // Si la route n'est pas autorisée, rediriger ou renvoyer une erreur
            return redirect()->route('home')->withError('Vous n\'avez pas les permissions nécessaires.');
        }

        // Par défaut, rediriger vers la page d'accueil
        return redirect()->route('home')->with('error', 'Accès non autorisé.');
    }
}
