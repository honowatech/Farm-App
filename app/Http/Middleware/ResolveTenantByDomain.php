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
    public function __construct(
        private TenantContext $tenantContext,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (
            $request->hasSession()
            && $request->user()
            && (int) $request->session()->get('demo_workspace_user_id') === (int) $request->user()->getKey()
        ) {
            $demoTenant = Tenant::query()
                ->whereKey($request->session()->get('demo_workspace_tenant_id'))
                ->where('is_demo', true)
                ->first();

            if (
                $demoTenant
                && (
                    $demoTenant->status !== 'active'
                    || ! $demoTenant->demo_expires_at
                    || $demoTenant->demo_expires_at->isPast()
                )
                && $demoTenant->users()
                    ->where('users.id', $request->user()->getKey())
                    ->wherePivot('role_key', 'admin')
                    ->wherePivot('status', 'active')
                    ->exists()
            ) {
                return redirect()->to(
                    rtrim((string) config('app.url'), '/').route('demo.subscribe', [], false)
                );
            }
        }

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
                // Domaine mappé mais entreprise désactivée/expirée : 404.
                Cache::forget($cacheKey);

                return $this->notFound($request);
            }

            // En développement local ou sur le domaine central configuré,
            // une appartenance unique permet de retrouver l'espace utilisateur.
            if ($this->isLocalHost($host)) {
                $tenant = $this->resolveFromUser($request);
            } elseif ($this->isCentralHost($host)) {
                $tenant = $this->resolveFromUser($request);
            } else {
                return $this->notFound($request);
            }
        }

        // Aucun tenant résolu (utilisateur sans entreprise en dev local) :
        // on continue en mode legacy, sans contexte tenant.
        if (! $tenant) {
            return $next($request);
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

    /**
     * Résout le tenant depuis l'unique appartenance active de l'utilisateur.
     */
    private function resolveFromUser(Request $request): ?Tenant
    {
        $user = $request->user();

        if (! $user) {
            return null;
        }

        $tenants = $user->tenants()
            ->wherePivot('status', 'active')
            ->where('tenants.status', 'active')
            ->where(function ($query) {
                $query->where('tenants.is_demo', false)
                    ->orWhereNull('tenants.demo_expires_at')
                    ->orWhere('tenants.demo_expires_at', '>', now());
            })
            ->get();

        return $tenants->count() === 1 ? $tenants->first() : null;
    }

    private function isLocalHost(string $host): bool
    {
        return app()->environment('local')
            && (in_array($host, ['localhost', '127.0.0.1', '::1'], true)
                || str_ends_with($host, '.localhost')
                || str_ends_with($host, '.local'));
    }

    private function isCentralHost(string $host): bool
    {
        $centralHost = parse_url((string) config('app.url'), PHP_URL_HOST);

        return is_string($centralHost)
            && strtolower(rtrim($centralHost, '.')) === $host;
    }

    private function notFound(Request $request): Response
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => 'Not found.'], 404);
        }

        abort(404);
    }
}
