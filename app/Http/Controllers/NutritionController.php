<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Bande;
use App\Models\Matiere_premiere;
use App\Models\Nutrition;
use App\Models\Stock_matiere_premiere;
use App\Models\Type_animal;
use App\Traits\FindsValidFoodSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NutritionController extends Controller
{
    use FindsValidFoodSettings;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $nutritions = Nutrition::where('date', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')->paginate(20);
        $bandes = Bande::all();
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.nutrition.index', compact('nutritions', 'bandes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $animals = Type_animal::All();
        $bandes = Bande::all();

        return view('admin.pages.nutrition.create', compact('animals', 'bandes'));
    }

    /**
     * Enregistre une nouvelle entrée de nutrition pour une bande, en gérant la conversion des unités pour le stock.
     */
    public function store(Request $request)
    {
        // 1. Validation des entrées
        // On attend maintenant 'unite_string' au lieu de 'id_unite' pour la validation.
        $validator = Validator::make($request->all(), [
            'bande' => 'required|integer|exists:bandes,id',
            'id_aliment' => 'required|integer|exists:matiere_premieres,id',
            'qte' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'id_unite' => 'required|integer|exists:unite,id',
            'unite_string' => 'required|string|in:kg,g,sac', // Ajout pour la logique de conversion
        ], [
            'bande.required' => 'La sélection d\'une bande est obligatoire.',
            'id_aliment.required' => 'La sélection d\'un aliment est obligatoire.',
            'qte.required' => 'La quantité est obligatoire.',
            'date.required' => 'La date est obligatoire.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $bande = Bande::findOrFail($request->input('bande'));
        $date_nutrition = Carbon::parse($request->input('date'));
        $id_aliment = $request->input('id_aliment');

        // 2. Calcul de l'âge et récupération du planning historique
        $age = $bande->getAgeOnDate($date_nutrition);
        $food_settings_valides = $this->getValidFoodSettings($bande->id_type_animal, $age, $date_nutrition);
        $food_setting_choisi = $food_settings_valides->firstWhere('aliment', $id_aliment);

        if ($food_settings_valides->isEmpty() || is_null($food_setting_choisi)) {
            return redirect()->back()->with('error', 'Aucun planning de nutrition valide n\'a été trouvé pour cet âge à cette date.')->withInput();
        }

        // 3. Conversion de la quantité en KG pour la gestion du stock
        $quantiteDonnee = $request->input('qte');
        $uniteString = strtolower($request->input('unite_string'));
        $quantiteEnKg = 0;

        switch ($uniteString) {
            case 'g':
                $quantiteEnKg = $quantiteDonnee / 1000;
                break;
            case 'sac':
                // On recherche la dernière valeur de conversion pour ce sac lors d'un approvisionnement
                $dernierAchat = Stock_matiere_premiere::where('id_matiere_premiere', $id_aliment)
                    ->where('type_mouvement', 'approvisionnement')
                    ->whereNotNull('conversion_sac')
                    ->latest('date_entree')
                    ->first();

                if (! $dernierAchat || ! $dernierAchat->conversion_sac) {
                    return redirect()->back()->with('error', 'Impossible de trouver le poids de conversion pour un sac de cet aliment. Veuillez vérifier les achats.')->withInput();
                }
                $quantiteEnKg = $quantiteDonnee * $dernierAchat->conversion_sac;
                break;
            default: // 'kg'
                $quantiteEnKg = $quantiteDonnee;
                break;
        }

        // 4. Vérification du stock disponible en KG
        $stock_disponible = Stock_matiere_premiere::where('id_matiere_premiere', $id_aliment)
            ->sum(DB::raw('quantite * (CASE WHEN type_mouvement = "approvisionnement" THEN 1 ELSE -1 END)'));

        if ($stock_disponible < $quantiteEnKg) {
            return redirect()->back()->with('error', 'Stock insuffisant. Stock actuel : '.$stock_disponible.' Kg, Requis : '.$quantiteEnKg.' Kg.')->withInput();
        }

        // 5. Vérification des fréquences (logique inchangée)
        $total_frequency_attendue = $food_settings_valides->sum('frequency');
        $passages_deja_faits_ce_jour = Nutrition::where('id_bande', $bande->id)
            ->whereDate('date', $date_nutrition)
            ->count();
        $passages_aliment_deja_faits_ce_jour = Nutrition::where('id_bande', $bande->id)
            ->where('id_aliment', $id_aliment)
            ->whereDate('date', $date_nutrition)
            ->count();
        if ($passages_deja_faits_ce_jour >= $total_frequency_attendue) {
            return redirect()->back()->with('error', 'Cette bande a déjà atteint le nombre total de repas prévus pour cette date.')->withInput();
        }
        if ($passages_aliment_deja_faits_ce_jour >= $food_setting_choisi->frequency) {
            return redirect()->back()->with('error', 'Cet aliment a déjà été donné le nombre de fois requis pour cette date.')->withInput();
        }

        // 6. Enregistrement de la nutrition et du mouvement de stock
        try {
            DB::transaction(function () use ($request, $bande, $quantiteEnKg, $stock_disponible, $id_aliment) {
                // Enregistre la nutrition avec la quantité et l'unité données par l'utilisateur
                Nutrition::create([
                    'id_bande' => $bande->id,
                    'id_aliment' => $id_aliment,
                    'qte' => $request->input('qte'),
                    'date' => $request->input('date'),
                    'id_unite' => $request->input('id_unite'),
                ]);

                // Enregistre le mouvement de stock avec la quantité convertie en KG
                Stock_matiere_premiere::create([
                    'id_matiere_premiere' => $id_aliment,
                    'type_mouvement' => 'destockage',
                    'dernier_stock' => $stock_disponible,
                    'quantite' => $quantiteEnKg, // On utilise la quantité en KG
                    'nouveau_stock' => $stock_disponible - $quantiteEnKg,
                    'date_sortie' => $request->input('date'),
                    'motif' => 'Nutrition des animaux (Bande: '.$bande->nom.')',
                ]);
            });
        } catch (\Exception $e) {
            \Log::error("Erreur lors de l'enregistrement de la nutrition : ".$e->getMessage());

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement.')->withInput();
        }

        return redirect()->route('bande.show', $bande->id)->with('message', 'Nutrition enregistrée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $nutrition = Nutrition::findOrFail($id);

        return view('admin.pages.nutrition.infos', compact('nutrition'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Affiche le formulaire pré-rempli pour nourrir une bande.
     */
    public function take_form(Request $request)
    {
        $id_bande = $request->input('table_data')[0] ?? null;
        if (! $id_bande) {
            return response()->json('<div class="mt-1 mb-1 text-center col-12 text-danger"><strong>Veuillez sélectionner une bande!!</strong></div>');
        }

        $bande = Bande::findOrFail($id_bande);
        $date_actuelle = Carbon::now();
        $age = $bande->getAgeOnDate($date_actuelle);
        $animal_name = $bande->animal->nom ?? 'N/A';
        $nbre_sujet = $bande->nbre_sujet;

        // On utilise notre Trait pour trouver le planning valide AUJOURD'HUI
        $nutritions_prevues = $this->getValidFoodSettings($bande->id_type_animal, $age, $date_actuelle);

        if ($nutritions_prevues->isEmpty()) {
            return response()->json('<div class="mt-1 mb-1 text-center col-12 text-warning"><strong>Aucun planning de nutrition défini pour l\'âge actuel de cette bande.</strong></div>');
        }

        $aliments_concernes = [];
        foreach ($nutritions_prevues as $planning) {
            $passages_faits_aujourdhui = Nutrition::where('id_bande', $bande->id)
                ->where('id_aliment', $planning->aliment)
                ->whereDate('date', $date_actuelle->toDateString())
                ->count();

            $aliments_concernes[] = [
                'aliment' => $planning->foodSetting->alimentName,
                'passages' => $passages_faits_aujourdhui,
                'unite' => $planning->unite->abrege ?? '',
            ];
        }

        return view('admin.pages.nutrition.form', compact('nutritions_prevues', 'age', 'nbre_sujet', 'animal_name', 'aliments_concernes', 'bande'));
    }

    /**
     * Vérifie le statut de nutrition pour un aliment donné aujourd'hui (utilisé par AJAX).
     */
    public function checkNutrition(Request $request)
    {
        $bande = Bande::findOrFail($request->input('id_bande'));
        $id_aliment = $request->input('id_aliment');
        $date_actuelle = Carbon::now();
        $age = $bande->getAgeOnDate($date_actuelle);

        // On utilise le Trait pour trouver le paramètre valide AUJOURD'HUI
        $food_settings_valides = $this->getValidFoodSettings($bande->id_type_animal, $age, $date_actuelle);
        $food_setting_choisi = $food_settings_valides->firstWhere('aliment', $id_aliment);

        if (! $food_setting_choisi) {
            return response()->json(['message' => 'Aucun paramètre valide trouvé pour cet aliment aujourd\'hui.'], 404);
        }

        $nombre_passages = Nutrition::where('id_bande', $bande->id)
            ->where('id_aliment', $id_aliment)
            ->whereDate('date', $date_actuelle->toDateString())
            ->count();

        $aliment = Matiere_premiere::findOrFail($id_aliment);
        $frequence_max = $food_setting_choisi->frequency;

        return response()->json([
            'nom_aliment' => $aliment->nom_produit,
            'message' => "{$aliment->nom_produit} : {$nombre_passages}/{$frequence_max} effectué(s)",
        ]);
    }

    public function search(Request $request)
    {
        $query = Nutrition::query();

        // Filtrage par dates
        if ($request->filled('date_debut')) {
            $query->where('date', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date', '<=', $request->input('date_fin'));
        }

        // Filtrage par catégorie (id_type)
        if ($request->filled('id_bande')) {
            $id_bande = $request->input('id_bande');
            $query->where('id_bande', $id_bande);
        }

        // Exécution de la requête et récupération des résultats
        $nutritions = $query->paginate(30);

        return view('admin.pages.nutrition.details', compact('nutritions'))->render();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
     * Génère les données pour le calendrier de nutrition.
     */
    public function getNutritionCalendar($bandeId)
    {
        $bande = Bande::findOrFail($bandeId);
        $nbre_animal = $bande->nbre_sujet; // Plus direct
        $dateLancement = Carbon::parse($bande->date_lancement_bande)->startOfDay();
        $today = Carbon::now()->startOfDay();

        $allNutritionsEnregistrees = Nutrition::where('id_bande', $bandeId)
            ->whereDate('date', '>=', $dateLancement)
            ->with(['aliment_name:id,nom_produit', 'unite:id,abrege'])
            ->get()
            ->groupBy(fn ($n) => Carbon::parse($n->date)->toDateString());

        $events = [];
        $currentDate = $dateLancement->copy();

        while ($currentDate->lte($today)) {
            $dateString = $currentDate->toDateString();
            $ageOnDate = $bande->getAgeOnDate($currentDate);

            // On utilise le Trait pour trouver le planning valide pour CHAQUE jour du passé
            $settingsForDate = $this->getValidFoodSettings($bande->id_type_animal, $ageOnDate, $currentDate);
            $totalFrequencyForDate = $settingsForDate->sum('frequency');

            if (isset($allNutritionsEnregistrees[$dateString])) {
                $nutritionForDate = $allNutritionsEnregistrees[$dateString];
                $totalPassages = $nutritionForDate->count();

                $details = $settingsForDate->map(function ($setting) use ($nutritionForDate, $nbre_animal) {
                    $passagesForThisAliment = $nutritionForDate->where('id_aliment', $setting->aliment);

                    return [
                        'aliment' => $setting->foodSetting->alimentName->nom_produit ?? 'N/A',
                        'score' => $passagesForThisAliment->count().' / '.$setting->frequency,
                        'est_complet' => $passagesForThisAliment->count() >= $setting->frequency,
                        'quantite_donnee' => $passagesForThisAliment->sum('qte'),
                        'unite' => $setting->unite->abrege ?? '',
                        'quantite_attendue' => ($setting->quantite_aliment ?? 0) * $nbre_animal,
                    ];
                })->values();

                $isComplete = $totalPassages >= $totalFrequencyForDate && $totalFrequencyForDate > 0;
                $events[] = ['title' => $isComplete ? 'Nutrition effectuée' : 'Nutrition incomplète', 'start' => $dateString, 'color' => $isComplete ? '#28a745' : '#ff9f43', 'extendedProps' => ['details' => $details]];

            } elseif ($currentDate->lt($today)) { // Gérer les jours passés sans nutrition
                if ($totalFrequencyForDate > 0) {
                    $details = $settingsForDate->map(function ($setting) use ($nbre_animal) {
                        return [
                            'aliment' => $setting->foodSetting->alimentName->nom_produit ?? 'N/A',
                            'score' => "0 / {$setting->frequency}",
                            'est_complet' => false,
                            'unite' => $setting->unite->abrege ?? '',
                            'quantite_attendue' => ($setting->quantite_aliment ?? 0) * $nbre_animal,
                        ];
                    })->values();
                    $events[] = ['title' => 'Nutrition manquée', 'start' => $dateString, 'color' => '#EA5455', 'extendedProps' => ['details' => $details]];
                } else {
                    $events[] = ['title' => 'Plan de nutrition manquant', 'start' => $dateString, 'color' => '#6c757d', 'extendedProps' => ['details' => ['type' => 'plan_manquant']]];
                }
            }
            $currentDate->addDay();
        }

        return response()->json($events);
    }

    public function showCalendar($bande)
    {
        // Transmettez l'ID de la bande à la vue
        return view('admin.pages.bande.calendrier', ['id_bande' => $bande]);
    }
}
