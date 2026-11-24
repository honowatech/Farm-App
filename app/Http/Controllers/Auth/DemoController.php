<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\DemoWorkspaceService;
use App\Support\TenantContext;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class DemoController extends Controller
{
    private const ACCESS_COOKIE = 'farmapp_demo_access';

    /**
     * Connecte l'utilisateur à la ferme de démonstration partagée.
     */
    public function login(Request $request, DemoWorkspaceService $workspaces): RedirectResponse
    {
        if (
            Auth::check()
            && (int) $request->session()->get('demo_workspace_user_id') !== (int) $request->user()->getKey()
        ) {
            return redirect()->route('home');
        }

        // Démo partagée : on se connecte à la ferme de démonstration commune
        // (données déjà seedées) au lieu de générer une copie isolée par visiteur.
        $workspace = $workspaces->ensureSharedDemo();
        $tenant = $workspace['tenant'];
        $user = $workspace['user'];

        Auth::login($user);
        $request->session()->regenerate();
        $request->session()->put([
            'demo_workspace_tenant_id' => (string) $tenant->id,
            'demo_workspace_user_id' => (int) $user->id,
            'demo_workspace_profile' => 'admin',
        ]);

        return redirect()->route('home');
    }

    public function prepareSubscription(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            if ((int) $request->session()->get('demo_workspace_user_id') !== (int) $request->user()->id) {
                return redirect()->route('home');
            }

            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('register', ['from_demo' => 1])
            ->withCookie($this->forgetCookie(self::ACCESS_COOKIE))
            ->withCookie($this->forgetCookie('farmapp_demo_recovery'));
    }

    public function switchProfile(Request $request, TenantContext $tenantContext): RedirectResponse
    {
        abort_unless($tenantContext->hasTenant(), 404);
        $tenant = $tenantContext->tenant();

        abort_unless(
            $tenant->is_demo
                && (string) $request->session()->get('demo_workspace_tenant_id') === (string) $tenant->id
                && (int) $request->session()->get('demo_workspace_user_id') === (int) $request->user()->id,
            404
        );

        $roles = array_values(array_diff(array_keys(config('permissions.roles', [])), ['superadmin']));
        $validated = $request->validate([
            'profile' => ['required', Rule::in($roles)],
        ]);

        abort_unless(
            $tenant->users()
                ->wherePivot('role_key', $validated['profile'])
                ->wherePivot('status', 'active')
                ->exists(),
            422
        );

        $request->session()->put('demo_workspace_profile', $validated['profile']);

        return redirect()->route('home');
    }

    private function forgetCookie(string $name)
    {
        return cookie(
            $name,
            '',
            -1,
            '/',
            config('session.domain'),
            config('session.secure'),
            true,
            false,
            'lax'
        );
    }
}
