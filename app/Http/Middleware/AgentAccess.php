<?php

namespace App\Http\Middleware;

use App\Support\RoutePermission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentAccess
{
    /**
     * Vérifie que le rôle de l'utilisateur possède la permission requise
     * par la route courante (ACL native).
     *
     * En cas de refus, l'utilisateur est renvoyé à la page précédente
     * (ou au tableau de bord) avec un toast d'erreur, sauf pour les
     * requêtes AJAX/JSON qui reçoivent une réponse 403.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Un utilisateur sans rôle est refusé par défaut.
        if (! $user->type_user) {
            return $this->deny($request, 'Votre compte n\'est associé à aucun profil.');
        }

        $routeName = $request->route()?->getName();

        if (RoutePermission::isAlwaysAllowed($routeName)) {
            return $next($request);
        }

        $permission = RoutePermission::resolve($routeName);

        // Route non cartographiée : refus par défaut (l'admin passe).
        if (blank($permission)) {
            return $this->deny($request, 'Accès non autorisé.');
        }

        if (! $user->hasPermission($permission)) {
            return $this->deny($request, 'Vous n\'avez pas les permissions nécessaires.');
        }

        return $next($request);
    }

    /**
     * Refuse l'accès : réponse JSON pour les appels AJAX, sinon redirection
     * vers la page précédente (ou le tableau de bord) avec un toast.
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    private function deny(Request $request, string $message)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        $previous = $request->headers->get('referer');

        if (! $previous || parse_url($previous, PHP_URL_HOST) !== $request->getHost()) {
            $previous = url()->previous();
        }

        $target = $previous
            && $previous !== $request->fullUrl()
            && parse_url($previous, PHP_URL_HOST) === $request->getHost()
                ? $previous
                : route('home');

        return redirect()->to($target)->with('error', $message);
    }
}
