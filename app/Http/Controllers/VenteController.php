<?php 

namespace App\Http\Controllers;
use App\Models\Produit;
use App\Models\User;
use Carbon\Carbon;
use App\Models\Facture;
use App\Models\Vente;
use App\Models\Stock_produit;
use App\Models\Ligne_facture;
use App\Models\Client;
use App\Models\Typeusers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Type_produit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class VenteController extends Controller 
{
  /**
   * Taux de TVA appliqué aux factures (0 = prix déjà TTC).
   * À déplacer vers un paramètre (settings) lors de la phase dédiée.
   */
  const TAUX_TVA = 0.0;

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $ventes = Vente::Where('date_vente', 'like', '%' . $request->input('recherche') . '%')
        ->paginate(20);
      session()->flash('search',$request->input('recherche'));
    $produits = Produit::all();
    $clients = Client::all();
    return view('admin.pages.vente.index',compact('ventes','produits','clients'));
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
    $clients  = Client::All();
    return view('admin.pages.vente.create', compact('produits','clients','types_produits'));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    // Décoder et valider la liste des articles
    $data = json_decode($request->input('tableau_donnees'), true);

    if (! is_array($data) || empty($data)) {
        return redirect()->back()->with('error', 'Aucun article dans la vente.');
    }

    $lignes = [];
    foreach ($data as $item) {
        $qte = (float) ($item['qte'] ?? 0);
        if (empty($item['id_produit']) || $qte <= 0) {
            return redirect()->back()->with('error', 'Ligne de vente invalide.');
        }
        $lignes[] = [
            'id_produit' => (int) $item['id_produit'],
            'qte' => $qte,
            'type' => $item['type'] ?? null,
            'mode_paiement' => $item['mode_paiement'] ?? null,
            'date' => $item['date'] ?? now()->toDateTimeString(),
            'id_client' => ! empty($item['id_client']) ? (int) $item['id_client'] : null,
        ];
    }

    $produits = Produit::whereIn('id', array_column($lignes, 'id_produit'))
        ->get()
        ->keyBy('id');

    try {
        DB::transaction(function () use ($lignes, $produits) {
            $factureDate = $lignes[0]['date'];
            $idClient = $lignes[0]['id_client'];
            $totalHt = 0;
            $mouvements = [];

            // 1) Vérifier les stocks et calculer les montants AVANT toute écriture
            foreach ($lignes as $ligne) {
                $produit = $produits->get($ligne['id_produit']);
                if (! $produit) {
                    throw new \RuntimeException('Produit introuvable.');
                }

                $dernierStock = (float) (Stock_produit::where('id_produit', $ligne['id_produit'])
                    ->orderBy('id', 'desc')
                    ->value('nouveau_stock') ?? 0);

                if ($dernierStock < $ligne['qte'] || $dernierStock <= 0) {
                    throw new \RuntimeException("Quantité de {$produit->nom} insuffisante pour la transaction. Il n'en reste que {$dernierStock}.");
                }

                $totalLigne = round((float) $produit->prix_unitaire * $ligne['qte'], 2);
                $totalHt += $totalLigne;

                $mouvements[] = [
                    'produit' => $produit,
                    'ligne' => $ligne,
                    'dernier_stock' => $dernierStock,
                    'nouveau_stock' => $dernierStock - $ligne['qte'],
                    'total_ligne' => $totalLigne,
                ];
            }

            $tva = round($totalHt * self::TAUX_TVA, 2);
            $totalTtc = $totalHt + $tva;

            // 2) Écrire la facture, la vente puis les mouvements/lignes
            $facture = Facture::create([
                'date_facturation' => $factureDate,
                'total_ht' => $totalHt,
                'tva' => $tva,
                'id_client' => $idClient,
                'total_ttc' => $totalTtc,
                'matricule' => 'FACT-TMP-' . uniqid(),
            ]);

            // Numérotation séquentielle stable, dérivée de l'identifiant auto-incrémenté
            $facture->matricule = sprintf('FACT-%s-%06d', date('Y'), $facture->id);
            $facture->save();

            Vente::create([
                'id_saver' => auth()->user()->id,
                'id_facture' => $facture->id,
                'date_vente' => $factureDate,
            ]);

            foreach ($mouvements as $m) {
                $mouvement = Stock_produit::create([
                    'id_produit' => $m['ligne']['id_produit'],
                    'type_mouvement' => 'destockage',
                    'qte' => $m['ligne']['qte'],
                    'dernier_stock' => $m['dernier_stock'],
                    'nouveau_stock' => $m['nouveau_stock'],
                    'date_sortie' => $m['ligne']['date'],
                ]);

                Ligne_facture::create([
                    'mode_paiement' => $m['ligne']['mode_paiement'],
                    'id_facture' => $facture->id,
                    'id_produit' => $m['ligne']['id_produit'],
                    'type_vente' => $m['ligne']['type'],
                    'id_stock_produit' => $mouvement->id,
                    'id_bande' => null,
                    'qte' => $m['ligne']['qte'],
                    'prix_unitaire' => (float) $m['produit']->prix_unitaire,
                    'total_ligne' => $m['total_ligne'],
                ]);
            }
        });
    } catch (\RuntimeException $e) {
        return redirect()->back()->with('error', $e->getMessage());
    } catch (\Throwable $e) {
        report($e);
        return redirect()->back()->with('error', 'Une erreur est survenue lors de l\'enregistrement de la vente.');
    }

    session()->flash('message', 'Vente enregistrée avec succès');

    return redirect()->route('vente.index');
  }
   public function get_formulaire(){
    return view('admin.pages.vente.create_client');
   }
   public function get_client(){
    $clients  = Client::All();
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
        } catch (\Illuminate\Validation\ValidationException $e) {
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
  public function show($id)
  {
    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id)
  {
    
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id)
  {
    
  }

  public function get_prix(Request $request){
    $table_data = $request->input('table_data');
    $id_produit = $table_data[0];
    $details = Produit::where('id',$id_produit)->get();
     return view('admin.pages.vente.ajax',compact('details'));
  }
  public function get_facture(Request $request, $id){
    $id_facture = Vente::where('id', $id)->value('id_facture');
    $id_vendeur = Vente::where('id', $id)->value('id_saver');
    $vendeur = User::findOrFail($id_vendeur);
    $facture = Facture::findOrFail($id_facture);
    $date = Carbon::now()->format('d/m/Y');
    $lignes  = Ligne_facture::where('id_facture',$id_facture)->get();
     return view('admin.pages.vente.facture',compact('facture','date','vendeur','lignes'));
  }
  public function get_animal(Request $request){
    $table_data = $request->input('table_data');
    $id_type_produit = $table_data[0];
    $produits = Produit::where('id_type_produit',$id_type_produit)->get();
    return view('admin.pages.vente.animal',compact('produits'));
  }
  public function get_animal_pondeur(Request $request){
    $type_animal = Type_produit::where('nom','animal')->firstOrFail();
    $id_animal = $type_animal->id;
    $produits = Produit::where('id_type_produit',$id_animal)->get();
     return view('admin.pages.vente.oeuf',compact('produits'));
  }
  public function get_fiente(Request $request){
   
        try {
            // Essaye de trouver le produit
            $produit = Produit::where('nom', 'fiente')->firstOrFail();
        } catch (ModelNotFoundException $e) {
            // Attrape l'exception ModelNotFoundException avant qu'elle ne soit convertie en NotFoundHttpException
            $produit = null;
            
            // Log l'exception ou fais quelque chose d'autre si nécessaire
            \Log::error("Produit non trouvé : " . $e->getMessage());
            return view('admin.pages.vente.fiente', compact('produit'));
            // Flash un message d'erreur pour l'utilisateur
        } catch (NotFoundHttpException $e) {
            // Si tu souhaites capturer la NotFoundHttpException aussi
            \Log::error("Erreur HTTP 404 : " . $e->getMessage());
            return response()->view('errors.404', [], 404);
        }

        // Si tout est correct, retourner la vue
        return view('admin.pages.vente.fiente', compact('produit'));
  
}
public function search(Request $request)
    {
        $query = Vente::query();

        // Filtrage par dates
        if ($request->filled('date_debut')) {
            $query->where('date_vente', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date_vente', '<=', $request->input('date_fin'));
        }

        // Filtrage par produit
        if ($request->filled('id_produit')) {
            $id_produit = $request->input('id_produit');
             $query->whereHas('facture.lignes_facture.produit', function ($q) use ($id_produit) {
            $q->where('id_produit', $id_produit);
        });
        }

        // Filtrage par clients
        if ($request->filled('id_client')) {
                $id_client = $request->input('id_client');
                 $query->whereHas('facture.client_facture', function ($q) use ($id_client) {
                $q->where('id_client',$id_client);
            });
        }

        // Exécution de la requête et récupération des résultats
        $ventes = $query->paginate(30);

        return view('admin.pages.vente.result_ajax',compact('ventes'))->render();
    
    }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    
  }
}

?>