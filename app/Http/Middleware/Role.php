<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  string  ...$roles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('login');
        }

        // En contexte multi-tenant, roleName() renvoie le rôle de l'utilisateur
        // dans l'entreprise courante (tenant_user.role_key), sinon son profil global.
        $role = $user->roleName();

        if (! $role || ! in_array($role, $roles, true)) {
            abort(403, 'Accès non autorisé.');
        }

        return $next($request);
    }
}
