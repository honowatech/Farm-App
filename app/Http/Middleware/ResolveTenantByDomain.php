<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Models\TenantDomain;
use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenantByDomain
{
    public function __construct(private TenantContext $tenantContext) {}

    public function handle(Request $request, Closure $next): Response
    {
        $host = strtolower(rtrim($request->getHost(), '.'));
        $cacheKey = 'tenant_domain:'.$host;

        // Le mapping domaine → tenant est stable : on le met en cache brièvement.
        // L'état du tenant (actif, démo non expirée) est, lui, re-vérifié à chaque
        // requête pour ne jamais servir une entreprise désactivée ou expirée.
        $tenantId = Cache::remember($cacheKey, now()->addSeconds(60), function () use ($host) {
            return TenantDomain::query()
                ->where('domain', $host)
                ->whereNotNull('verified_at')
                ->value('tenant_id');
        });

        $tenant = $tenantId ? Tenant::find($tenantId) : null;

        $valid = $tenant
            && $tenant->status === 'active'
            && (! $tenant->is_demo || ! $tenant->demo_expires_at?->isPast());

        if (! $valid) {
            if ($tenantId) {
                Cache::forget($cacheKey);
            }

            return $this->notFound($request);
        }

        $this->tenantContext->set($tenant);
        $request->attributes->set('tenant', $tenant);

        try {
            return $next($request);
        } finally {
            $this->tenantContext->clear();
            $request->attributes->remove('tenant');
        }
    }

    private function notFound(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        abort(404);
    }
}
