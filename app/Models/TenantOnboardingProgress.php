<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TenantOnboardingProgress extends Model
{
    protected $table = 'tenant_onboarding_progress';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'guide_key',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'completed_at' => 'datetime',
        ];
    }
}
