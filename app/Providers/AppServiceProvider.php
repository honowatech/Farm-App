<?php

namespace App\Providers;

use App\Support\TenantContext;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(TenantContext::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Le thème (Vuexy) est basé sur Bootstrap 4 : on aligne les liens de pagination.
        Paginator::useBootstrapFour();

        Blade::if('canroute', function (?string $routeName): bool {
            return auth()->check() && auth()->user()->canAccessRoute($routeName);
        });

        Blade::if('cananyroute', function (array $routeNames): bool {
            if (! auth()->check()) {
                return false;
            }

            foreach ($routeNames as $routeName) {
                if (auth()->user()->canAccessRoute($routeName)) {
                    return true;
                }
            }

            return false;
        });

        Blade::if('canpermission', function (string $permission): bool {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        Blade::if('cananypermission', function (array $permissions): bool {
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });

        Blade::if('feature', function (string $feature): bool {
            $context = app(TenantContext::class);

            if (! $context->hasTenant()) {
                return true;
            }

            return $context->tenant()->hasFeature($feature);
        });

        Blade::if('anyfeature', function (array $features): bool {
            $context = app(TenantContext::class);

            if (! $context->hasTenant()) {
                return true;
            }

            foreach ($features as $feature) {
                if ($context->tenant()->hasFeature($feature)) {
                    return true;
                }
            }

            return false;
        });
    }
}
