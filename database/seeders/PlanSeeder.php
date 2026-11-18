<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

/**
 * Crée les forfaits (plans) de la plateforme.
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
                'description' => 'Pour démarrer un petit élevage : bandes, nutrition, ramassage, ventes de base.',
                'price' => 15000,
                'currency' => 'XAF',
                'billing_interval' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'nutrition' => true,
                    'ramassage' => true,
                    'mortalite' => true,
                    'vaccination' => true,
                    'vente' => true,
                    'achat' => true,
                    'client' => true,
                    'fournisseur' => true,
                    'depense' => false,
                    'salarie' => false,
                    'caisse' => false,
                    'rentabilite' => false,
                    'paiement_mobile' => false,
                ],
                'limits' => [
                    'max_users' => 3,
                    'max_bandes' => 5,
                    'max_batiments' => 5,
                    'max_produits' => 20,
                ],
                'is_active' => true,
            ],
            [
                'code' => 'pro',
                'name' => 'Pro',
                'description' => 'Pour un élevage en croissance : stocks, finances, salaires, paie.',
                'price' => 35000,
                'currency' => 'XAF',
                'billing_interval' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'nutrition' => true,
                    'ramassage' => true,
                    'mortalite' => true,
                    'vaccination' => true,
                    'vente' => true,
                    'achat' => true,
                    'client' => true,
                    'fournisseur' => true,
                    'depense' => true,
                    'salarie' => true,
                    'caisse' => true,
                    'rentabilite' => true,
                    'paiement_mobile' => false,
                ],
                'limits' => [
                    'max_users' => 10,
                    'max_bandes' => 20,
                    'max_batiments' => 20,
                    'max_produits' => 100,
                ],
                'is_active' => true,
            ],
            [
                'code' => 'entreprise',
                'name' => 'Entreprise',
                'description' => 'Pour les grands élevages multi-sites : tout illimité et Mobile Money.',
                'price' => 75000,
                'currency' => 'XAF',
                'billing_interval' => 'monthly',
                'trial_days' => 14,
                'features' => [
                    'nutrition' => true,
                    'ramassage' => true,
                    'mortalite' => true,
                    'vaccination' => true,
                    'vente' => true,
                    'achat' => true,
                    'client' => true,
                    'fournisseur' => true,
                    'depense' => true,
                    'salarie' => true,
                    'caisse' => true,
                    'rentabilite' => true,
                    'paiement_mobile' => true,
                ],
                'limits' => [
                    'max_users' => null,
                    'max_bandes' => null,
                    'max_batiments' => null,
                    'max_produits' => null,
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
