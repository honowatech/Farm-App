<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * L'ordre est important :
     * 1. RolePermissionSeeder crée les profils et leurs permissions.
     * 2. RoleUsersSeeder rattache les comptes de démonstration au tenant démo.
     * 3. Phase5DemoSeeder crée les référentiels du tenant démo sur une base vide.
     * 4. MultiTenantDemoSeeder ajoute des entreprises de démonstration isolées.
     */
    public function run(): void
    {
        // 1. Profils, permissions et utilisateurs d'abord : certains référentiels
        //    (Phase5DemoSeeder) enregistrent des `id_saver` qui référencent des
        //    utilisateurs existants.
        $this->call([
            RolePermissionSeeder::class,
            RoleUsersSeeder::class,
        ]);

        // 2. Référentiels de base (uniquement sur base vide), dans le tenant démo.
        if (DB::table('bandes')->count() === 0) {
            $this->call(Phase5DemoSeeder::class);
        }

        // 3. Forfaits et entreprises de démonstration isolées.
        $this->call([
            PlanSeeder::class,
            MultiTenantDemoSeeder::class,
        ]);
    }
}
