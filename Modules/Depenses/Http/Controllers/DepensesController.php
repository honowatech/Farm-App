<?php

namespace Modules\Depenses\Http\Controllers;

use App\Support\TenantValidation;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Modules\Depenses\Entities\Categorie;
use Modules\Depenses\Entities\Depense;
use Modules\GestionCaisse\Entities\Compte;
use Modules\GestionCaisse\Entities\Transaction;
use Modules\GestionCaisse\Facades\CompteServiceFacade;

class DepensesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $depenses = Depense::with('categorie_depense')->Where('date_depense', 'like', '%'.$request->input('recherche').'%')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));
        $types = Categorie::all();
        $fournisseurs = Depense::where('fournisseur', '!=', 'autre')
            ->distinct()
            ->pluck('fournisseur');

        return view('depenses::depense.index', compact('depenses', 'types', 'fournisseurs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $caisses = CompteServiceFacade::listComptes();
        $categories = Categorie::all();

        return view('depenses::depense.create', compact('categories', 'caisses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $validated = $this->validateExpense($request);

        DB::transaction(function () use ($validated) {
            $depense = new Depense;
            $depense->montant = $validated['prix_unitaire'];
            $depense->date_depense = $validated['date'];
            $depense->id_saver = auth()->id();
            $depense->id_categorie = $validated['id_type_depense'];
            $depense->motif_depense = $validated['nature_depense'];
            $depense->fournisseur = ($validated['fournisseur'] ?? null) ?: 'autre';
            $depense->description = ($validated['description'] ?? null) ?: 'Aucune description pour cette dépense';
            $depense->id_compte = $validated['id_caisse'] ?? null;
            $depense->save();

            if (
                $depense->id_compte
                && ! CompteServiceFacade::verifierSoldeSuffisant(
                    $depense->id_compte,
                    $depense->montant,
                    $depense->id
                )
            ) {
                throw ValidationException::withMessages([
                    'id_caisse' => 'Le solde de la caisse est insuffisant pour cette dépense.',
                ]);
            }
        });

        return redirect()->route('depenses.index')->with('message', 'Dépense enregistrée avec succès');
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show(Request $request, $id)
    {
        $depense = Depense::with('categorie_depense')->findOrFail($id);
        $view = $request->ajax() ? 'depenses::depense.show' : 'depenses::depense.show-page';

        return view($view, compact('depense'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $depense = Depense::findOrFail($id);
        $caisses = CompteServiceFacade::listComptes();
        $categories = Categorie::orderBy('nom')->get();

        return view('depenses::depense.edit', compact('depense', 'caisses', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $validated = $this->validateExpense($request);

        DB::transaction(function () use ($validated, $id) {
            $depense = Depense::query()->lockForUpdate()->findOrFail($id);
            $this->restoreCashMovement($depense);

            $depense->montant = $validated['prix_unitaire'];
            $depense->date_depense = $validated['date'];
            $depense->id_categorie = $validated['id_type_depense'];
            $depense->motif_depense = $validated['nature_depense'];
            $depense->fournisseur = ($validated['fournisseur'] ?? null) ?: 'autre';
            $depense->description = ($validated['description'] ?? null) ?: 'Aucune description pour cette dépense';
            $depense->id_compte = $validated['id_caisse'] ?? null;
            $depense->save();

            if (
                $depense->id_compte
                && ! CompteServiceFacade::verifierSoldeSuffisant(
                    $depense->id_compte,
                    $depense->montant,
                    $depense->id
                )
            ) {
                throw ValidationException::withMessages([
                    'id_caisse' => 'Le solde de la caisse est insuffisant pour cette dépense.',
                ]);
            }
        });

        return redirect()->route('depenses.index')->with('message', 'Dépense mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        DB::transaction(function () use ($id) {
            $depense = Depense::query()->lockForUpdate()->findOrFail($id);
            $this->restoreCashMovement($depense);
            $depense->delete();
        });

        return redirect()->route('depenses.index')->with('message', 'Dépense supprimée avec succès.');
    }

    public function search(Request $request)
    {
        $filters = $request->validate([
            'date_debut' => ['nullable', 'date'],
            'date_fin' => ['nullable', 'date', 'after_or_equal:date_debut'],
        ]);
        $query = Depense::query();

        if (! empty($filters['date_debut'])) {
            $query->where('date_depense', '>=', Carbon::parse($filters['date_debut'])->startOfMinute());
        }

        if (! empty($filters['date_fin'])) {
            $query->where('date_depense', '<=', Carbon::parse($filters['date_fin'])->endOfMinute());
        }

        if ($request->filled('id_type')) {
            $id_type = $request->input('id_type');
            $query->where('id_categorie', $id_type);
        }

        if ($request->filled('fournisseur')) {
            $fournisseur = $request->input('fournisseur');
            $query->where('fournisseur', $fournisseur);
        }

        $depenses = $query->with('categorie_depense')->orderByDesc('date_depense')->paginate(20);

        return view('depenses::depense.infos', compact('depenses'))->render();
    }

    public function getPrixUnitaire($id)
    {
        // Cherche la catégorie de dépense par son ID
        $categorie = Categorie::findOrFail($id);

        // Si la catégorie est trouvée, retourne son prix unitaire, sinon retourne 0
        return response()->json(['prix_unitaire' => $categorie->prix_unitaire ?? 0]);
    }

    private function validateExpense(Request $request): array
    {
        return $request->validate([
            'id_type_depense' => ['required', 'integer', TenantValidation::exists('categorie_depenses')],
            'nature_depense' => ['required', 'string', 'max:255'],
            'prix_unitaire' => ['required', 'integer', 'min:1'],
            'date' => ['required', 'date', 'before_or_equal:today'],
            'id_caisse' => ['nullable', 'integer', TenantValidation::exists('gestion_caisse_comptes')],
            'fournisseur' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:5000'],
        ], [
            'id_type_depense.required' => 'Veuillez choisir un type de dépense.',
            'nature_depense.required' => 'Veuillez indiquer le motif de la dépense.',
            'prix_unitaire.required' => 'Veuillez entrer le montant de la dépense.',
            'prix_unitaire.integer' => 'Le montant doit être un nombre entier.',
            'prix_unitaire.min' => 'Le montant doit être supérieur à zéro.',
            'date.before_or_equal' => 'La date de la dépense ne peut pas être postérieure à aujourd\'hui.',
        ]);
    }

    private function restoreCashMovement(Depense $depense): void
    {
        $transactions = Transaction::query()
            ->where('id_depense', $depense->getKey())
            ->where('transaction_type', 'sortie')
            ->lockForUpdate()
            ->get();

        if ($transactions->count() > 1 || ($depense->id_compte && $transactions->count() !== 1)) {
            throw ValidationException::withMessages([
                'error' => 'Cette dépense ne possède pas un mouvement de caisse cohérent; sa modification est bloquée pour protéger le solde.',
            ]);
        }

        if ($transactions->isEmpty()) {
            return;
        }

        $transaction = $transactions->first();

        if (
            ($depense->id_compte && (int) $depense->id_compte !== (int) $transaction->compte_id)
            || round((float) $depense->montant, 2) !== round((float) $transaction->montant, 2)
        ) {
            throw ValidationException::withMessages([
                'error' => 'Le mouvement de caisse ne correspond plus à cette dépense; sa modification est bloquée.',
            ]);
        }

        $compte = Compte::query()->lockForUpdate()->findOrFail($transaction->compte_id);
        $compte->current_balance += $transaction->montant;
        $compte->save();
        $transaction->delete();
    }
}
