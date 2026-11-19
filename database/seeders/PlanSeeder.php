<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Crée les forfaits (plans) de la plateforme, au format du constructeur de
 * forfait (config/features.php) : fonctionnalités + limites + quotas par profil.
 * Idempotent : rejouable sans doublon.
 */
class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'code' => 'essentiel',
                'name' => 'Essentiel',
                'description' => 'Pour démarrer un petit élevage : élevage, production et commerce.',
                'price' => 15000,
                'currency' => 'XAF',
                'billing_interval' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'elevage' => true,
                    'production' => true,
                    'achats' => false,
                    'commerce' => true,
                    'finances' => false,
                    'parametres' => false,
                ],
                'limits' => [
                    'utilisateurs' => 5,
                    'bandes' => 5,
                    'produits' => 50,
                    'clients' => 100,
                    'profiles' => [
                        'gestionnaire' => 1,
                        'agent' => 3,
                        'veterinaire' => 1,
                        'magasinier' => 0,
                        'commercial' => 1,
                        'caissier' => 0,
                        'client' => 20,
                        'comptable' => 0,
                        'rh' => 0,
                    ],
                ],
                'is_active' => true,
            ],
            [
                'code' => 'pro',
                'name' => 'Pro',
                'description' => 'Pour un élevage en croissance : achats, stocks, finances et paie.',
                'price' => 35000,
                'currency' => 'XAF',
                'billing_interval' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'elevage' => true,
                    'production' => true,
                    'achats' => true,
                    'commerce' => true,
                    'finances' => true,
                    'parametres' => false,
                ],
                'limits' => [
                    'utilisateurs' => 15,
                    'bandes' => 20,
                    'produits' => 200,
                    'clients' => 500,
                    'profiles' => [
                        'gestionnaire' => 2,
                        'agent' => 5,
                        'veterinaire' => 2,
                        'magasinier' => 2,
                        'commercial' => 3,
                        'caissier' => 2,
                        'client' => 100,
                        'comptable' => 2,
                        'rh' => 1,
                    ],
                ],
                'is_active' => true,
            ],
            [
                'code' => 'entreprise',
                'name' => 'Entreprise',
                'description' => 'Pour les grands élevages multi-sites : toutes les fonctionnalités.',
                'price' => 75000,
                'currency' => 'XAF',
                'billing_interval' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'elevage' => true,
                    'production' => true,
                    'achats' => true,
                    'commerce' => true,
                    'finances' => true,
                    'parametres' => true,
                ],
                'limits' => [
                    'utilisateurs' => 100,
                    'bandes' => 100,
                    'produits' => 10000,
                    'clients' => 10000,
                    'profiles' => [
                        'gestionnaire' => 5,
                        'agent' => 20,
                        'veterinaire' => 5,
                        'magasinier' => 5,
                        'commercial' => 10,
                        'caissier' => 5,
                        'client' => 1000,
                        'comptable' => 5,
                        'rh' => 5,
                    ],
                ],
                'is_active' => true,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(['code' => $plan['code']], $plan);
        }

        $this->command?->info('PlanSeeder : '.count($plans).' forfaits créés.');
    }
}
