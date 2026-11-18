<?php

namespace App\Console\Commands;

use App\Models\Bande;
use App\Models\Food_Setting;
use App\Models\Nutrition;
use App\Models\Tenant;
use App\Support\TenantContext;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DailyNutritionCheck extends Command
{
    // Nom de la commande
    protected $signature = 'nutrition:daily-check';

    // Description de la commande
    protected $description = 'Vérifie chaque jour si les bandes ont été nourries correctement et gère les cas manqués.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): int
    {
        $context = app(TenantContext::class);

        // En l'absence de tenant (application non encore cloisonnée), on conserve
        // l'ancien comportement : traitement de l'ensemble des bandes.
        $tenants = Tenant::query()->where('status', 'active')->get();

        if ($tenants->isEmpty()) {
            $this->processBandes();
            $this->info('Vérification quotidienne de la nutrition terminée.');

            return self::SUCCESS;
        }

        // En mode multi-tenant, on traite chaque entreprise dans son propre contexte.
        foreach ($tenants as $tenant) {
            $context->set($tenant);

            try {
                $this->processBandes();
            } finally {
                $context->clear();
            }
        }

        $this->info('Vérification quotidienne de la nutrition terminée pour '.$tenants->count().' entreprise(s).');

        return self::SUCCESS;
    }

    private function processBandes(): void
    {
        $bandes = Bande::all();
        $date_actuelle = Carbon::now()->startOfDay();

        foreach ($bandes as $bande) {
            // Récupérer la dernière nutrition pour cette bande
            $derniere_nutrition = Nutrition::where('id_bande', $bande->id)
                ->orderBy('derniere_nutrition', 'desc')
                ->first();

            // Obtenir la fréquence totale requise pour cette bande
            $animal_type = $bande->id_type_animal;
            $age = $bande->created_at->diffInDays(Carbon::now()) + $bande->age_depart;

            // Récupérer les paramètres de nutrition
            $food_settings = Food_Setting::where('id_type_animal', $animal_type)
                ->where('age_min', '<=', $age)
                ->where('age_max', '>=', $age)
                ->get();

            $total_frequency = $food_settings->sum('frequency');

            // Vérifier si la bande a été nourrie aujourd'hui ou si le compteur est insuffisant
            if (! $derniere_nutrition || Carbon::parse($derniere_nutrition->derniere_nutrition)->lt($date_actuelle) || $derniere_nutrition->compteur_nutrition < $total_frequency) {
                // Pas de nutrition pour aujourd'hui ou nutrition insuffisante, ajustement de la prochaine nutrition
                $prochaine_nutrition = $date_actuelle->copy()->addDay();

                // Créer une nouvelle entrée de nutrition avec statut à 0 (non nourrie ou nutrition insuffisante)
                Nutrition::create([
                    'id_bande' => $bande->id,
                    'id_aliment' => null, // Pas d'aliment spécifique car non nourrie
                    'qte' => 0,           // Quantité zéro
                    'date' => $date_actuelle,
                    'id_unite' => null,   // Unité nulle
                    'nombre_de_passages' => 0,
                    'derniere_nutrition' => $date_actuelle,
                    'compteur_nutrition' => $derniere_nutrition->compteur_nutrition ?? 0,
                    'prochaine_nutrition' => $prochaine_nutrition,
                    'statut_nutrition' => 0, // Statut nutrition non nourrie
                ]);
            }
        }
    }
}
