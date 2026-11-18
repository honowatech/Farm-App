<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;

class PurgeExpiredDemoTenants extends Command
{
    protected $signature = 'tenants:purge-expired-demos';

    protected $description = 'Désactive les espaces de démonstration dont la date d\'expiration est dépassée.';

    public function handle(): int
    {
        $expired = Tenant::query()
            ->where('is_demo', true)
            ->where('status', 'active')
            ->where('demo_expires_at', '<', now())
            ->update(['status' => 'expired']);

        $this->info($expired.' espace(s) de démonstration expiré(s) désactivé(s).');

        return self::SUCCESS;
    }
}
