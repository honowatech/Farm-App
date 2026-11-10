<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVenteRequest;
use App\Models\Client;
use App\Models\Facture;
use App\Models\Ligne_facture;
use App\Models\Produit;
use App\Models\Type_produit;
use App\Models\Typeusers;
use App\Models\User;
use App\Models\Vente;
use App\Services\VenteService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $ventes = Vente::Where('date_vente', 'like', '%'.$request->input('recherche').'%')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));
        $produits = Produit::select('id', 'nom')->orderBy('nom')->get();
        $clients = Client::select('id', 'nom')->orderBy('nom')->get();

        return view('admin.pages.vente.index', compact('ventes', 'produits', 'clients'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $types_produits = Type_produit::All();
        $produits = Produit::All();
        $clients = Client::All();

        return view('admin.pages.vente.create', compact('produits', 'clients', 'types_produits'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(StoreVenteRequest $request, VenteService $ventes)
    {
        $lignes = $request->lignes();

        if (empty($lignes)) {
            return redirect()->back()->with('error', 'Aucun article dans la vente.');
        }

        $produits = Produit::whereIn('id', array_column($lignes, 'id_produit'))
            ->get()
            ->keyBy('id');

        try {
            $ventes->creerVente($lignes, $produits);
        } catch (\RuntimeException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        } catch (\Throwable $e) {
            report($e);

            return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la vente.');
        }

        session()->flash('message', 'Vente enregistrée avec succès');

        return redirect()->route('vente.index');
    }

    public function get_formulaire()
    {
        return view('admin.pages.vente.create_client');
    }

    public function get_client()
    {
        $clients = Client::All();

        return view('admin.pages.vente.ajax', compact('clients'));
    }

    public function save_client(Request $request)
    {
        try {
            // Validation des données du formulaire
            $validated = $request->validate([
                'nom' => 'bail|required|max:255',
                'telephone' => 'bail|required|unique:clients|regex:/^[6,2][0-9]{8}$/',
                'email' => 'bail|required|email|unique:clients|unique:users|max:255',
                'password' => 'bail|required|min:8',
                'adresse' => 'bail|required|min:4',
            ]);

            // Récupérer l'ID du type utilisateur (client)
            $id_type_user = Typeusers::whereIn('libelle', ['client', 'clients'])->first()->id;

            // Création d'un nouvel utilisateur
            $user = new User;
            $user->name = $validated['nom'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->id_type_user = $id_type_user;
            $user->save();

            // Enregistrement du client
            $client = new Client;
            $client->nom = $validated['nom'];
            $client->telephone = str_replace(' ', '', $validated['telephone']);
            $client->adresse = $validated['adresse'];
            $client->email = $validated['email'];
            $client->id_user = $user->id;
            $client->save();

            // Retourner la réponse JSON en cas de succès
            return response()->json([
                'success' => true,
                'message' => 'Client enregistré avec succès',
                'user' => $client,
            ]);
        } catch (ValidationException $e) {
            // Gestion des erreurs de validation, retour JSON
            return response()->json([
                'success' => false,
                'message' => 'Erreur de validation',
                'errors' => $e->errors(),
                'old' => $request->except('password'),
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres erreurs potentielles
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'enregistrement du client.',
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id) {}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id) {}

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id) {}

    public function get_prix(Request $request)
    {
        $table_data = $request->input('table_data');
        $id_produit = $table_data[0];
        $details = Produit::where('id', $id_produit)->get();

        return view('admin.pages.vente.ajax', compact('details'));
    }

    public function get_facture(Request $request, $id)
    {
        $id_facture = Vente::where('id', $id)->value('id_facture');
        $id_vendeur = Vente::where('id', $id)->value('id_saver');
        $vendeur = User::findOrFail($id_vendeur);
        $facture = Facture::findOrFail($id_facture);
        $date = Carbon::now()->format('d/m/Y');
        $lignes = Ligne_facture::where('id_facture', $id_facture)->get();

        return view('admin.pages.vente.facture', compact('facture', 'date', 'vendeur', 'lignes'));
    }

    public function get_animal(Request $request)
    {
        $table_data = $request->input('table_data');
        $id_type_produit = $table_data[0];
        $produits = Produit::where('id_type_produit', $id_type_produit)->get();

        return view('admin.pages.vente.animal', compact('produits'));
    }

    public function get_animal_pondeur(Request $request)
    {
        $type_animal = Type_produit::where('nom', 'animal')->firstOrFail();
        $id_animal = $type_animal->id;
        $produits = Produit::where('id_type_produit', $id_animal)->get();

        return view('admin.pages.vente.oeuf', compact('produits'));
    }

    public function get_fiente(Request $request)
    {

        try {
            // Essaye de trouver le produit
            $produit = Produit::where('nom', 'fiente')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // Attrape l'exception ModelNotFoundException avant qu'elle ne soit convertie en NotFoundHttpException
            $produit = null;

            // Log l'exception ou fais quelque chose d'autre si nécessaire
            \Log::error('Produit non trouvé : '.$e->getMessage());

            return view('admin.pages.vente.fiente', compact('produit'));
            // Flash un message d'erreur pour l'utilisateur
        } catch (NotFoundHttpException $e) {
            // Si tu souhaites capturer la NotFoundHttpException aussi
            \Log::error('Erreur HTTP 404 : '.$e->getMessage());

            return response()->view('errors.404', [], 404);
        }

        // Si tout est correct, retourner la vue
        return view('admin.pages.vente.fiente', compact('produit'));

    }

    public function search(Request $request)
    {
        $ventes = $this->applyVenteFilters($request, Vente::query())->paginate(30);

        return view('admin.pages.vente.result_ajax', compact('ventes'))->render();
    }

    /**
     * Exporte les ventes filtrées au format CSV (sans dépendance tierce).
     */
    public function export(Request $request)
    {
        $ventes = $this->applyVenteFilters($request, Vente::query())
            ->with(['facture.client_facture', 'facture.lignes_facture.produit'])
            ->orderBy('date_vente', 'desc')
            ->get();

        $csv = $this->buildVentesCsv($ventes);

        return response("\xEF\xBB\xBF".$csv, 200, [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ventes_'.now()->format('Y-m-d').'.csv"',
        ]);
    }

    /**
     * Applique les filtres communs (dates, produit, client) à la requête de ventes.
     */
    private function applyVenteFilters(Request $request, Builder $query): Builder
    {
        if ($request->filled('date_debut')) {
            $query->where('date_vente', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date_vente', '<=', $request->input('date_fin'));
        }

        if ($request->filled('id_produit')) {
            $id_produit = $request->input('id_produit');
            $query->whereHas('facture.lignes_facture.produit', function ($q) use ($id_produit) {
                $q->where('id_produit', $id_produit);
            });
        }

        if ($request->filled('id_client')) {
            $id_client = $request->input('id_client');
            $query->whereHas('facture.client_facture', function ($q) use ($id_client) {
                $q->where('id_client', $id_client);
            });
        }

        return $query;
    }

    /**
     * Construit le contenu CSV à partir d'une collection de ventes.
     */
    private function buildVentesCsv($ventes)
    {
        $handle = fopen('php://temp', 'r+');
        fputcsv($handle, ['Matricule', 'Client', 'Date', 'Produits', 'Total HT (FCFA)', 'Total TTC (FCFA)']);

        foreach ($ventes as $vente) {
            $facture = $vente->facture;
            $client = $facture ? $facture->client_facture : null;
            $produits = $facture
                ? $facture->lignes_facture->map(fn ($ligne) => $ligne->produit?->nom)->filter()->unique()->implode('; ')
                : '';

            fputcsv($handle, [
                $facture ? $facture->matricule : '',
                $client ? $client->nom : '',
                $vente->date_vente,
                $produits,
                $facture ? $facture->total_ht : '',
                $facture ? $facture->total_ttc : '',
            ]);
        }

        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return $csv;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id) {}
}
