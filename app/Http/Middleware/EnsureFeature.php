<?php

namespace App\Http\Middleware;

use App\Support\FeatureGate;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Vérifie que le forfait actif du tenant inclut la fonctionnalité requise par
 * la route courante.
 *
 * Ne fait rien lorsque le contexte tenant n'est pas résolu (espace central,
 * invité, ou routes métier non encore multi-tenant) : l'application de la
 * restriction est alors déléguée au middleware tenant.
 */
class EnsureFeature
{
    public function __construct(private TenantContext $tenantContext) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->tenantContext->hasTenant()) {
            return $next($request);
        }

        $tenant = $this->tenantContext->tenant();
        $feature = FeatureGate::forRoute($request->route()?->getName());

        if (! FeatureGate::allows($tenant, $feature)) {
            return $this->deny($request, 'Cette fonctionnalité n\'est pas incluse dans votre forfait.');
        }

        return $next($request);
    }

    private function deny(Request $request, string $message): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        $target = route('home');

        return redirect()->to($target)->with('error', $message);
    }
}
