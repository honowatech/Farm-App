<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;
use App\Support\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

/**
 * Applique le cloisonnement par entreprise (colonne `tenant_id`).
 *
 * - Lecture : un scope global filtre automatiquement par le tenant courant.
 * - Écriture : `tenant_id` est renseigné automatiquement à la création.
 */
trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function (Model $model) {
            $context = app(TenantContext::class);

            if ($context->hasTenant()) {
                $model->setAttribute('tenant_id', $context->id());

                return;
            }

            if (config('multitenancy.enforce', false)) {
                throw new LogicException(
                    'Contexte tenant requis pour créer '.class_basename($model).'.'
                );
            }
        });
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class, 'tenant_id');
    }
}
