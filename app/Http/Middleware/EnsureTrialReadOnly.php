<?php

namespace App\Http\Middleware;

use App\Support\TenantContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTrialReadOnly
{
    public function __construct(private TenantContext $tenantContext) {}

    public function handle(Request $request, Closure $next): Response
    {
        if (
            ! $this->tenantContext->hasTenant()
            || ! $this->tenantContext->tenant()->expiredTrialSubscription()
            || $request->isMethodSafe()
            || $request->routeIs('lang.switch')
        ) {
            return $next($request);
        }

        $message = 'Votre période d’essai est terminée. Vos données restent accessibles en lecture seule jusqu’à la souscription.';

        if ($request->expectsJson()) {
            return response()->json(['message' => $message], 403);
        }

        return redirect()->route('home')->with('error', $message);
    }
}
