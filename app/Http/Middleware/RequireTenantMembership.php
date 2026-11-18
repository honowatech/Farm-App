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

        $this->tenantContext->setMembership(
            (int) $user->getKey(),
            $membership->pivot->role_key
        );

        return $next($request);
    }
}
