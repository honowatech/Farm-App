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
     * 1. Phase5DemoSeeder ne s'exécute que sur une base vide (référentiels de base).
     * 2. RolePermissionSeeder crée les 10 profils et leurs permissions.
     * 3. RoleUsersSeeder crée un compte de démonstration par profil.
     * 4. MonthActivitySeeder ajoute 30 jours d'activité, sans écraser l'existant.
     */
    public function run(): void
    {
        if (DB::table('bandes')->count() === 0) {
            $this->call(Phase5DemoSeeder::class);
        }

        $this->call([
            RolePermissionSeeder::class,
            RoleUsersSeeder::class,
            MonthActivitySeeder::class,
        ]);
    }
}
