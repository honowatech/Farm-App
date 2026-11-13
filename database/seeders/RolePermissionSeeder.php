<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Typeusers;
use Illuminate\Database\Seeder;

/**
 * Crée les 10 profils et leur affecte les permissions déclarées dans
 * config/permissions.php. Idempotent : peut être rejoué sans doublon.
 */
class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $catalogue = config('permissions.permissions', []);

        // 1. Catalogue des permissions
        foreach ($catalogue as $name => $meta) {
            Permission::updateOrCreate(
                ['name' => $name],
                [
                    'label' => $meta['label'] ?? $name,
                    'group' => $meta['group'] ?? null,
                ]
            );
        }

        // 2. Profils et affectations
        $roles = config('permissions.roles', []);

        foreach ($roles as $role => $permissions) {
            $typeUser = Typeusers::firstOrCreate(['libelle' => $role]);

            if (in_array('*', $permissions, true)) {
                $ids = Permission::pluck('id')->all();
            } else {
                $ids = Permission::whereIn('name', $permissions)->pluck('id')->all();
            }

            $typeUser->permissions()->sync($ids);
        }
    }
}
