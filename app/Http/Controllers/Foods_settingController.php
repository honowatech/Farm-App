<?php

namespace App\Http\Controllers;

use App\Models\Food_Setting;
use App\Models\FoodSettingVersion;
use App\Models\Matiere_premiere;
use App\Models\Type_animal;
use App\Models\Unite;
use App\Services\FoodSettingService;
use App\Support\TenantValidation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

// Utilise le nouveau modèle de version

class Foods_settingController extends Controller
{
    /**
     * Affiche la liste des types d'animaux ayant au moins un planning de nutrition.
     * Cette méthode reste fonctionnelle car elle vérifie l'existence d'une relation.
     */
    public function index(Request $request)
    {
        $search = $request->input('recherche');
        $types_annimals = Type_animal::where(function ($query) use ($search) {
            if ($search) {
                $query->where('nom', 'like', '%'.$search.'%')
                    ->orWhere('type_reproduction', 'like', '%'.$search.'%');
            }
        })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.setting.foods-setting.index', compact('types_annimals'));
    }

    /**
     * Affiche le formulaire de création. Inchangé.
     */
    public function create()
    {
        $types = Type_animal::select('id', 'nom')->get();
        $unites = Unite::select('id', 'abrege')->get();
        $aliments = Matiere_premiere::select('id', 'nom_produit', 'statut')->get();

        return view('admin.pages.setting.foods-setting.create', compact('types', 'unites', 'aliments'));
    }

    /**
     * Enregistre un NOUVEAU planning de nutrition avec sa première version.
     */
    public function store(Request $request, FoodSettingService $foodSettingService)
    {
        // 1. Validation des données du formulaire
        $validator = Validator::make($request->all(), [
            'type' => ['required', TenantValidation::exists('type_animals')],
            'aliment' => ['required', TenantValidation::exists('matiere_premieres')],
            'invoice' => 'required|array|min:1',
            'invoice.*.age_min' => 'required|integer|min:0',
            'invoice.*.age_max' => 'required|integer|min:0|gte:invoice.*.age_min',
            'invoice.*.qte' => 'required|numeric|min:0.01',
            'invoice.*.unite' => ['required', TenantValidation::exists('unite')],
            'invoice.*.frequency' => 'required|integer|min:1',
            'invoice.*.period' => 'required|string',
        ], [
            'type.required' => 'Le type d\'animal est requis.',
            'aliment.required' => 'L\'aliment est requis.',
            'invoice.*.age_max.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $type = $request->input('type');
        $aliment = $request->input('aliment');
        $invoices = $request->input('invoice');

        try {
            $foodSettingService->creer($type, $aliment, $invoices);
        } catch (\RuntimeException $e) {
            return redirect()->back()->withErrors(['aliment' => $e->getMessage()])->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['database' => 'Une erreur est survenue lors de l\'enregistrement.'])->withInput();
        }

        $animal_name = Type_animal::findOrFail($type)->nom;
        $message = 'Le planning de nutrition pour '.$animal_name.' a été créé avec succès.';

        return redirect()->route('foods-setting.index')->with('message', $message);
    }

    /**
     * Affiche les détails du planning ACTIF pour un type d'animal.
     */
    public function show(string $id_type_animal)
    {
        // On récupère toutes les versions ACTIVES pour ce type d'animal
        $nutritions = FoodSettingVersion::whereNull('ended_at')
            ->whereHas('foodSetting', function ($query) use ($id_type_animal) {
                $query->where('id_type_animal', $id_type_animal);
            })
            ->with(['foodSetting.animalType', 'foodSetting.alimentName', 'unite'])
            ->orderBy('age_min')
            ->get();

        // Renommer la relation pour la vue pour qu'elle corresponde à l'ancienne logique
        // Cela évite de devoir modifier la vue `show` immédiatement
        $nutritions->each(function ($item) {
            $item->planning = $item->foodSetting->animalType;
            $item->alimentName = $item->foodSetting->alimentName;
        });

        return view('admin.pages.setting.foods-setting.show', compact('nutritions'));
    }

    /**
     * Récupère les aliments pour lesquels un planning de nutrition a été défini
     * pour un type d'animal donné.
     * Utilisé par AJAX pour la modale de sélection de planning à éditer.
     *
     * @return JsonResponse
     */
    public function getFoodsForAnimalType(string $id_type_animal)
    {
        // 1. On cherche tous les plannings "parents" (FoodSetting) pour ce type d'animal.
        $plannings = Food_Setting::where('id_type_animal', $id_type_animal)
            // 2. On charge en avance la relation `alimentName` pour éviter les requêtes N+1.
            //    On sélectionne uniquement les colonnes nécessaires (id, nom_produit, statut).
            ->with('alimentName:id,nom_produit,statut')
            ->get();

        // 3. On transforme la collection pour ne garder que les informations utiles.
        $aliments = $plannings
            ->map(function ($planning) {
                // Si la relation `alimentName` a bien été chargée et que son statut est actif...
                if ($planning->alimentName && $planning->alimentName->statut == 1) {
                    // ...on retourne un tableau propre pour le JSON.
                    return [
                        'id' => $planning->alimentName->id,
                        'nom_produit' => $planning->alimentName->nom_produit,
                    ];
                }

                // Sinon, on retourne null pour cet élément.
                return null;
            })
            // 4. On utilise `filter()` pour retirer toutes les entrées nulles
            //    (celles dont l'aliment était inactif ou introuvable).
            ->filter();

        // 5. On retourne la réponse JSON. `values()` ré-indexe le tableau pour qu'il soit propre
        //    (indices 0, 1, 2...) au lieu d'avoir des trous.
        return response()->json(['aliments' => $aliments->values()]);
    }

    /**
     * Affiche le formulaire pour modifier un planning spécifique (animal + aliment).
     */
    public function edit(string $id_type_animal, string $id_aliment)
    {
        // 1. Trouver le planning parent
        $foodSettingParent = Food_Setting::where('id_type_animal', $id_type_animal)
            ->where('aliment', $id_aliment)
            ->firstOrFail();

        // 2. Récupérer uniquement les versions ACTIVES de ce planning
        $nutritions = $foodSettingParent->activeVersionsDetails()
            ->orderBy('age_min')
            ->get();

        if ($nutritions->isEmpty()) {
            return redirect()->route('foods-setting.index')->with('error', 'Aucun détail de planning actif trouvé pour cette sélection.');
        }

        // 3. Charger les données nécessaires pour le formulaire
        $types = Type_animal::select('id', 'nom')->get();
        $aliments = Matiere_premiere::select('id', 'nom_produit', 'statut')->get();
        $unites = Unite::select('id', 'abrege')->get();

        // On passe les versions actives à la vue. La vue les traitera comme l'ancienne variable `$nutritions`.
        return view('admin.pages.setting.foods-setting.create', compact('nutritions', 'types', 'aliments', 'unites'));
    }

    /**
     * Met à jour un planning en archivant l'ancienne version et en créant une nouvelle.
     * C'est le cœur de la logique de versioning.
     */
    public function update(Request $request, string $id_type_animal, string $id_aliment, FoodSettingService $foodSettingService)
    {
        // 1. Validation complète des données du formulaire
        $rules = [
            'invoice' => 'required|array|min:1',
            // L'ID doit être optionnel (pour les nouvelles lignes) mais s'il est présent, il doit exister.
            'invoice.*.id' => ['nullable', 'integer', TenantValidation::exists('food_setting_versions')],
            'invoice.*.age_min' => 'required|integer|min:0',
            'invoice.*.age_max' => 'required|integer|min:0|gte:invoice.*.age_min',
            'invoice.*.qte' => 'required|numeric|min:0.01',
            'invoice.*.unite' => ['required', TenantValidation::exists('unite')],
            'invoice.*.frequency' => 'required|integer|min:1',
            'invoice.*.period' => 'required|string',
        ];

        $messages = [
            'invoice.required' => 'Vous devez fournir au moins une ligne de planning.',
            'invoice.*.age_min.required' => 'L\'âge minimum est requis pour chaque ligne.',
            'invoice.*.age_max.required' => 'L\'âge maximum est requis pour chaque ligne.',
            'invoice.*.age_max.gte' => 'L\'âge maximum doit être supérieur ou égal à l\'âge minimum.',
            'invoice.*.qte.required' => 'La quantité d\'aliment est requise.',
            'invoice.*.qte.min' => 'La quantité doit être positive.',
            'invoice.*.unite.required' => 'L\'unité est requise.',
            'invoice.*.frequency.min' => 'La fréquence doit être d\'au moins 1.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $invoices = $request->input('invoice');

        // 2. Vérification des chevauchements DANS les données soumises elles-mêmes
        $errors = new MessageBag;
        for ($i = 0; $i < count($invoices); $i++) {
            for ($j = $i + 1; $j < count($invoices); $j++) {
                $invoiceA = $invoices[$i];
                $invoiceB = $invoices[$j];

                // Vérifier si la plage de A chevauche la plage de B
                if ($invoiceA['age_min'] <= $invoiceB['age_max'] && $invoiceA['age_max'] >= $invoiceB['age_min']) {
                    $errorMessage = "La tranche d'âge [{$invoiceA['age_min']} - {$invoiceA['age_max']}] est en conflit avec la tranche [{$invoiceB['age_min']} - {$invoiceB['age_max']}] dans votre soumission.";
                    // On ajoute l'erreur aux deux champs pour qu'ils soient bien visibles
                    $errors->add('invoice.'.$i.'.age_min', $errorMessage);
                    $errors->add('invoice.'.$j.'.age_min', $errorMessage);
                }
            }
        }

        if ($errors->any()) {
            return redirect()->back()->withErrors($errors)->withInput();
        }

        // 3. Exécution de la mise à jour dans une transaction sécurisée
        try {
            $foodSettingService->mettreAJour($id_type_animal, $id_aliment, $invoices);
        } catch (\Exception $e) {
            \Log::error('Erreur de mise à jour du planning de nutrition: '.$e->getMessage());

            return redirect()->back()
                ->withErrors(['database' => 'Une erreur inattendue est survenue lors de la mise à jour. Veuillez contacter le support.'])
                ->withInput();
        }

        // 4. Redirection avec un message de succès
        $animal_name = Type_animal::findOrFail($id_type_animal)->nom;
        $aliment_name = Matiere_premiere::findOrFail($id_aliment)->nom_produit;
        $message = 'Le planning de nutrition pour '.$animal_name.' ('.$aliment_name.') a été mis à jour avec succès.';

        return redirect()->route('foods-setting.index')->with('message', $message);
    }

    /**
     * Supprime un planning de nutrition complet (parent et toutes ses versions).
     */
    public function destroy(string $id_type_animal)
    {
        // La suppression se fait maintenant sur le modèle parent.
        // La contrainte de clé étrangère `onDelete('cascade')` s'occupera de supprimer
        // toutes les versions associées dans la table `food_setting_versions`.
        Food_Setting::where('id_type_animal', $id_type_animal)->delete();

        $message = 'Tous les plannings alimentaires pour ce type d\'animal ont été supprimés.';

        return redirect()->route('foods-setting.index')->with('message', $message);
    }
}
