<?php

namespace App\Models;

use App\Traits\FindsValidFoodSettings;
use Carbon\Carbon;
// Ajout du Trait que nous avons créé
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Bande extends Model
{
    use FindsValidFoodSettings, HasFactory, LogsActivity, SoftDeletes; // On inclut le Trait ici !

    protected $table = 'bandes';

    public $timestamps = true;

    protected $fillable = ['nom', 'date_fin_bande', 'date_lancement_bande', 'prix_acquisition', 'unite_age_depart', 'nbre_sujet', 'age_depart', 'statut_bande', 'id_type_animal', 'id_batiment'];

    protected $visible = ['nom', 'date_fin_bande', 'date_lancement_bande', 'prix_acquisition', 'unite_age_depart', 'nbre_sujet', 'age_depart', 'statut_bande', 'id_type_animal', 'id_batiment'];

    protected function casts(): array
    {
        return [
            'deleted_at' => 'datetime',
            'date_lancement_bande' => 'datetime', // On dit à Laravel de traiter ce champ comme un objet Carbon
            'date_fin_bande' => 'datetime',
        ];
    }

    // --- Relations (inchangées) ---
    public function animal()
    {
        return $this->belongsTo(Type_animal::class, 'id_type_animal');
    }

    public function batiment()
    {
        return $this->belongsTo(Batiment::class, 'id_batiment');
    }

    public function animaux()
    {
        return $this->hasMany('App\Models\Animal', 'id_bande');
    }

    public function mortalites()
    {
        return $this->hasMany(Mortalite::class, 'id_bande');
    }

    // --- Fonctions de calcul d'âge ---

    /**
     * Calcule l'âge de la bande en jours à une date spécifique.
     * C'est la fonction de base pour tous les calculs d'âge.
     *
     * @param  Carbon|null  $date  La date pour laquelle calculer l'âge. Si null, utilise la date actuelle.
     */
    public function getAgeOnDate(?Carbon $date = null): int
    {
        // Si aucune date n'est fournie, on utilise la date et l'heure actuelles
        $targetDate = $date ? $date->copy()->startOfDay() : now()->startOfDay();

        return (int) $this->date_lancement_bande->diffInDays($targetDate) + (int) $this->age_depart;
    }

    /**
     * ACCESSEUR : Retourne l'âge actuel en jours.
     * Appelé avec : $bande->age_en_jours
     */
    public function getAgeEnJoursAttribute(): int
    {
        return $this->getAgeOnDate(now());
    }

    /**
     * ACCESSEUR : Retourne l'âge actuel formaté pour l'affichage.
     * Appelé avec : $bande->age_formate
     */
    public function getAgeFormateAttribute(): string
    {
        CarbonInterval::setLocale('fr');

        return CarbonInterval::days($this->age_en_jours)->cascade()->forHumans(['parts' => 2, 'join' => true]);
    }

    // --- Logique de Planning de Nutrition (entièrement révisée) ---

    /**
     * Récupère les paramètres de nutrition valides pour l'âge et la date actuels.
     * Utilise le Trait pour une logique centralisée et historiquement correcte.
     */
    public function foodSettingsForCurrentAge()
    {
        // On utilise la méthode du Trait en lui passant l'âge et la date d'aujourd'hui
        return $this->getValidFoodSettings($this->id_type_animal, $this->age_en_jours, now());
    }

    /**
     * ACCESSEUR : Calcule la fréquence totale de nutrition pour AUJOURD'HUI.
     * Appelé avec : $bande->total_frequency_today
     */
    public function getTotalFrequencyTodayAttribute()
    {
        return $this->foodSettingsForCurrentAge()->sum('frequency');
    }

    /**
     * ACCESSEUR : Vérifie si un plan de nutrition est défini pour AUJOURD'HUI.
     * Appelé avec : $bande->has_nutrition_plan_for_today
     */
    public function getHasNutritionPlanForTodayAttribute(): bool
    {
        // Si la fréquence totale est supérieure à 0, alors un plan existe.
        return $this->total_frequency_today > 0;
    }

    // --- Accesseurs liés aux enregistrements de nutrition (révisés pour utiliser la nouvelle logique) ---

    /**
     * ACCESSEUR : Récupère la dernière nutrition enregistrée. Inchangé.
     * Appelé avec : $bande->derniere_nutrition
     */
    public function getDerniereNutritionAttribute()
    {
        return Nutrition::where('id_bande', $this->id)
            ->orderBy('date', 'desc') // Il est plus fiable de trier par la date de nutrition
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * ACCESSEUR : Donne le statut de nutrition pour aujourd'hui.
     * Appelé avec : $bande->statut_nutrition_aujourdhui
     */
    public function getStatutNutritionAujourdhuiAttribute(): string
    {
        $passages_faits = Nutrition::where('id_bande', $this->id)
            ->whereDate('date', today())
            ->count();

        if ($passages_faits === 0) {
            return 'Aucune';
        }

        $frequence_attendue = $this->total_frequency_today;

        if ($frequence_attendue === 0) {
            return 'Plan non défini'; // Cas où on a nourri mais qu'aucun plan n'était prévu
        }

        return $passages_faits >= $frequence_attendue ? 'Complet' : 'Incomplet';
    }

    /**
     * ACCESSEUR : Donne le statut de nutrition pour hier.
     * Appelé avec : $bande->statut_nutrition_hier
     */
    public function getStatutNutritionHierAttribute(): string
    {
        $date_hier = today()->subDay();
        $age_hier = $this->getAgeOnDate($date_hier);

        // Récupérer le planning qui était valide HIER
        $settings_hier = $this->getValidFoodSettings($this->id_type_animal, $age_hier, $date_hier);
        $frequence_attendue_hier = $settings_hier->sum('frequency');

        // Compter les nutritions enregistrées pour hier
        $passages_faits_hier = Nutrition::where('id_bande', $this->id)
            ->whereDate('date', $date_hier)
            ->count();

        if ($passages_faits_hier === 0) {
            return 'Aucune';
        }

        if ($frequence_attendue_hier === 0) {
            return 'Plan non défini';
        }

        return $passages_faits_hier >= $frequence_attendue_hier ? 'Complet' : 'Incomplet';
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    /**
     * ACCESSEUR : Effectif vivant (initial - mortalité cumulée).
     */
    public function getEffectifVivantAttribute(): int
    {
        $morts = (int) $this->mortalites()->sum('nombre_morts');

        return max(0, $this->nbre_sujet - $morts);
    }

    /**
     * ACCESSEUR : Taux de mortalité en pourcentage.
     */
    public function getTauxMortaliteAttribute(): float
    {
        if ($this->nbre_sujet <= 0) {
            return 0.0;
        }

        $morts = (int) $this->mortalites()->sum('nombre_morts');

        return round(($morts / $this->nbre_sujet) * 100, 2);
    }
}
