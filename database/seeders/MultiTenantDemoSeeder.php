<?php

namespace Database\Seeders;

use App\Models\Plan;
use App\Services\DemoWorkspaceService;
use Illuminate\Database\Seeder;

/**
 * Crée les entreprises d'exemple et leurs données métier cloisonnées.
 */
class MultiTenantDemoSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(PlanSeeder::class);

        $plans = Plan::pluck('id', 'code');

        $count = app(DemoWorkspaceService::class)->seedPreconfiguredTenants($plans);

        $this->command?->info('MultiTenantDemoSeeder : '.count($plans).' forfaits, '.$count.' entreprises et un an de données créés.');
    }
}
