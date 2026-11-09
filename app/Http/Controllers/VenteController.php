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
use Illuminate\Http\Request;
use App\Models\Type_produit;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
      
    // Récupérer les données JSON
    $data = json_decode($request->input('tableau_donnees'), true);

    // Calculer les totaux pour la facture
    $total_ht = 0;
    $total_ttc = 0;
    foreach ($data as $item) {
       $total_cleaned = str_replace(['FCFA', ' ', ','], '', $item['total']);
        $total_ht += (float) $total_cleaned;
        $total_ttc +=  (float) $total_cleaned;
    }
    $tva = $total_ttc * 0.2; // Exemple de TVA à 20%

    $facture = Facture::create([
      'date_facturation' => $data[0]['date'], // Utiliser la date du premier article
      'total_ht' => $total_ht,
      'tva' => $tva,
      'id_client' => $data[0]['id_client'], // Utiliser l'id_client du premier article
      'total_ttc' => $total_ttc,
      'matricule' => 'FACT-' . uniqid() // Générer un matricule unique pour la facture
    ]);
        Vente::create([
            'id_saver' => auth()->user()->id,
            'id_facture' => $facture->id,
            'date_vente' => $data[0]['date'],
        ]);
    // Traiter chaque ligne de facture
    foreach ($data as $item) {
        // Récupérer l'id_stock_produit
        $id_stock_produit = Stock_produit::where('id_produit', $item['id_produit'])->value('id');
        // Récupérer le dernier stock pour le produit
        $dernier_stock = Stock_produit::where('id_produit', $item['id_produit'])
                            ->orderBy('id', 'desc')
                            ->value('nouveau_stock') ?? 0;
        //nom du produit
        $nom_produit = Produit::where('id', $item['id_produit'])->value('nom');
        //check de la quantitée en Stock
        if($dernier_stock < (int) $item['qte'] || $dernier_stock <= 0){
            $message = "Quantité de {$nom_produit} insuffisante pour la transaction. Il n'en reste que {$dernier_stock}.";
            session()->flash('error', $message);
            return redirect()->back();
        }
        // Calculer le nouveau stock
        $nouveau_stock = $dernier_stock - (int) $item['qte'];

        // Créer une nouvelle ligne dans Stock_produit
        Stock_produit::create([
            'id_produit' => $item['id_produit'],
            'type_mouvement' => 'destockage',
            'qte' => (int) $item['qte'],
            'dernier_stock' => $dernier_stock,
            'nouveau_stock' => $nouveau_stock,
            'date_sortie' => $item['date'],
        ]);

        // Créer la ligne de facture
        Ligne_facture::create([
            'mode_paiement' =>$item['mode_paiement'],
            'id_facture' => $facture->id,
            'id_produit' => $item['id_produit'],
            'type_vente' => $item['type'],
            'id_stock_produit' => $id_stock_produit,
            'id_bande' => null,
            'qte' => $item['qte'],
            'prix_unitaire' => (float) str_replace(['FCFA', ' ',','], '', $item['prix_unitaire']),
            'total_ligne' => (float) str_replace(['FCFA', ' ', ','], '', $item['total']),
        ]);
    }
    $message = "Vente enregistré avec succès";
    session()->flash('message',$message);

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
                'prenom' => 'bail|required|max:255',
                'email' => 'bail|required|unique:clients|unique:users|max:255',
                'password' => 'bail|required|min:8',
                'adresse' => 'bail|required|min:4',
            ]);

            // Récupérer l'ID du type utilisateur (client)
            $id_type_user = TypeUsers::whereIn('libelle', ['client', 'clients'])->first()->id;

            // Création d'un nouvel utilisateur
            $user = new User;
            $user->name = $validated['nom'] . ' ' . $validated['prenom'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);
            $user->id_type_user = $id_type_user;
            $user->save();

            // Enregistrement du client
            $client = new Client;
            $client->nom = $validated['nom'];
            $client->prenom = $validated['prenom'];
            $client->telephone = str_replace(' ', '', $validated['telephone']);  // Retirer les espaces
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
                'old' => $request->all() // Retourner les anciennes valeurs pour remplir les inputs
            ], 422);
        } catch (\Exception $e) {
            // Gestion des autres erreurs potentielles
            return response()->json([
                'success' => false,
                'message' => 'Une erreur s\'est produite lors de l\'enregistrement du client.',
                'error' => $e->getMessage()
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