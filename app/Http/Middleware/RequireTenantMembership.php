<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireTenantMembership
{
    public function __construct(private TenantContext $tenantContext) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (! $this->tenantContext->hasTenant()) {
            abort(404);
        }

        $user = $request->user();

        if (! $user) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Unauthenticated.'], 401)
                : redirect()->route('login');
        }

        $membership = $user->tenants()
            ->where('tenants.id', $this->tenantContext->id())
            ->wherePivot('status', 'active')
            ->first();

        if (! $membership) {
            abort(403);
        }

        $tenant = $this->tenantContext->tenant();
        $role = $membership->pivot->role_key;
        $demoSession = $request->hasSession() ? $request->session() : null;
        $demoTenantId = $demoSession?->get('demo_workspace_tenant_id');
        $demoUserId = $demoSession?->get('demo_workspace_user_id');
        $demoRole = $demoSession?->get('demo_workspace_profile');
        $demoRoles = array_keys(config('permissions.roles', []));

        if (
            $tenant->is_demo
            && (string) $demoTenantId === (string) $tenant->id
            && (int) $demoUserId === (int) $user->getKey()
            && is_string($demoRole)
            && $demoRole !== 'superadmin'
            && in_array($demoRole, $demoRoles, true)
            && $tenant->users()->wherePivot('role_key', $demoRole)->wherePivot('status', 'active')->exists()
        ) {
            $role = $demoRole;
        }

        $this->tenantContext->setMembership((int) $user->getKey(), $role);

        return $next($request);
    }
}
