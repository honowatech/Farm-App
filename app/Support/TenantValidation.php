<?php

namespace App\Support;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Exists;

class TenantValidation
{
    public static function exists(string $table, string $column = 'id'): Exists
    {
        $rule = Rule::exists($table, $column);
        $context = app(TenantContext::class);

        if ($context->hasTenant()) {
            $rule->where('tenant_id', $context->id());
        }

        return $rule;
    }
}
