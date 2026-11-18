<?php

namespace App\Scopes;

use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use LogicException;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        $context = app(TenantContext::class);

        if ($context->hasTenant()) {
            $builder->where($model->getTable().'.tenant_id', $context->id());

            return;
        }

        if (config('multitenancy.enforce', false)) {
            throw new LogicException(
                'Contexte tenant requis pour requêter '.class_basename($model).'.'
            );
        }
    }
}
