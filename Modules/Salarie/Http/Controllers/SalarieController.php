<?php

namespace Modules\Salarie\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Salarie\Entities\PaiementSalaires;
use Modules\Salarie\Entities\Salaries;

class SalarieController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Renderable
     */
    public function index(Request $request)
    {
        $types_periode = [
            'jour' => 'Jour(s)',
            'semaine' => 'Semaine(s)',
            'heure' => 'Heure(s)',
            'mois' => 'Mois',
            'annee' => 'Année(s)',
        ];
        $salaries = Salaries::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('adresse', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('telephone', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('telephone_secondaire', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('salarie::salarie.index', compact('salaries', 'types_periode'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        $types_periode = [
            'jour' => 'Jour(s)',
            'semaine' => 'Semaine(s)',
            'heure' => 'Heure(s)',
            'mois' => 'Mois',
            'annee' => 'Année(s)',
        ];

        return view('salarie::salarie.create', compact('types_periode'));
    }

    public function ajax(Request $request)
    {
        $datas = $request->input('table_data');
        $entree = true;
        $telephone_secondaire = $datas['telephone_secondaire'] == null ? '' : ' / '.$datas['telephone_secondaire'];
        echo '<h4>';
        echo '</p> Vous allez enregistrer <b>'.name($datas['nom']).'</b> en tant que Salarié.</p>';
        echo '<p><b>'.name($datas['nom']).'</b> touchera une somme de <b>'.str_replace(',', ' ', $datas['salaire']).'</b> FCFA chaque <b>'.' 1 '.' '.'mois'.'</b>.</p>';
        echo '<p>Date de début de contrat le <b>'.Ladate($datas['date_debut_contrat']).' à '.Heure($datas['date_debut_contrat']).'</b>.</p>';
        echo '<p>Numéro de CNI : <b>'.$datas['cni'].'</b></p>';
        echo '<p>Contact : <b>'.$datas['telephone'].$telephone_secondaire.'</b>.</p>';
        echo '<p>Adresse : <b>'.$datas['adresse'].'</b>.</p>';
        echo '</h4>';
    }

    public function ajax_edit(Request $request)
    {
        $datas = $request->input('table_data');
        $entree = true;
        $telephone_secondaire = $datas['telephone_secondaire'] == null ? '' : ' / '.$datas['telephone_secondaire'];
        echo '<h4>';
        echo '</p> Vous allez enregistrer <b>'.name($datas['nom']).'</b> en tant que Salarié.</p>';
        echo '<p><b>'.name($datas['nom']).'</b> touchera une somme de <b>'.str_replace(',', ' ', $datas['salaire']).'</b> FCFA chaque <b>'.$datas['accumulateur'].' '.$datas['type_periode'].'</b>.</p>';
        echo '<p>Numéro de CNI : <b>'.$datas['cni'].'</b></p>';
        echo '<p>Contact : <b>'.$datas['telephone'].$telephone_secondaire.'</b>.</p>';
        echo '<p>Adresse : <b>'.$datas['adresse'].'</b>.</p>';
        echo '</h4>';
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        $nom = $request->input('nom');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $telephone_secondaire = str_replace(' ', '', $request->input('telephone_secondaire'));
        $adresse = $request->input('adresse');
        $salaire = $request->input('salaire');
        $accumulateur = 1;
        $type_periode = 'mois';
        $date_debut_contrat = $request->input('date_debut_contrat');
        $cni = $request->input('cni');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|unique:salaries|regex:/^[6,2][0-9]{8}$/',
            'adresse' => 'bail|required|min:4',
            'cni' => 'bail|required|min:9',
            'date_debut_contrat' => 'bail|required|date',
            //'accumulateur' => 'bail|required|numeric|min:1',
            'salaire' => 'bail|required|numeric|min:25',
            //'type_periode' => 'bail|required',
        ]);
        if ($telephone_secondaire != null) {
            $validated = $request->validate([
                'telephone' => 'bail|unique:salaries|regex:/^[6,2][0-9]{8}$/',
            ]);
        } else {
            $telephone_secondaire = null;
        }
        // dd($nom,$telephone,$telephone_secondaire,$salaire,$accumulateur,$type_periode,$date_debut_contrat,$cni);
        // On enregistre le salaries
        $salaries = new Salaries;
        $salaries->nom = $nom;
        $salaries->telephone_secondaire = $telephone_secondaire;
        $salaries->telephone = $telephone;
        $salaries->adresse = $adresse;
        $salaries->cni = $cni;
        $salaries->id_saver = auth()->user()->id;
        $salaries->date_debut_contrat = $date_debut_contrat;
        $salaries->date_modif = $date_debut_contrat;
        $salaries->date_dernier_paiement = prochaine_date_paie($type_periode, $accumulateur, $date_debut_contrat, 1);
        $salaries->accumulateur = $accumulateur;
        $salaries->salaire = $salaire;
        $salaries->type_periode = $type_periode;
        $salaries->statut = 1;
        $salaries->save();
        $message = 'Salarié '.name($nom)."créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('salarie.show', $salaries->id);
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function show(Request $request, $id)
    {
        $types_periode = [
            'jour' => 'Jour(s)',
            'semaine' => 'Semaine(s)',
            'mois' => 'Mois',
            'annee' => 'Année(s)',
        ];
        $types_Paiement = [
            'avance' => 'Avance',
            'paiement_complet' => 'Complet',
        ];
        $salarie = Salaries::findOrFail($id);
        $paiements = PaiementSalaires::where('id_salarie', [$salarie->id])
            ->Where('type', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('id', 'desc')->paginate(10);
        $montant = PaiementSalaires::where('id_salarie', [$salarie->id])
            ->where('date_paiement', '>=', $salarie->date_modif)
            ->get()->sum('montant');
        $pas_avance = montant_restant($montant, $salarie->salaire);
        session()->flash('search', $request->input('recherche'));

        return view('salarie::salarie.info', compact('salarie', 'types_periode', 'pas_avance', 'paiements', 'types_Paiement'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function edit($id)
    {
        $salarie = Salaries::findOrFail($id);
        $types_periode = [
            'jour' => 'Jour(s)',
            'semaine' => 'Semaine(s)',
            'mois' => 'Mois',
            'annee' => 'Année(s)',
        ];

        return view('salarie::salarie.edit', compact('salarie', 'types_periode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        $nom = $request->input('nom');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $telephone_secondaire = str_replace(' ', '', $request->input('telephone_secondaire'));
        $adresse = $request->input('adresse');
        $salaire = $request->input('salaire');
        $accumulateur = $request->input('accumulateur');
        $type_periode = $request->input('type_periode');
        $cni = $request->input('cni');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|regex:/^[6,2][0-9]{8}$/',
            'adresse' => 'bail|required|min:4',
            'cni' => 'bail|required|min:9',
            'accumulateur' => 'bail|required|numeric|min:1',
            'salaire' => 'bail|required|numeric|min:25',
            'type_periode' => 'bail|required',
        ]);
        if ($telephone_secondaire != null) {
            $validated = $request->validate([
                'telephone' => 'bail|regex:/^[6,2][0-9]{8}$/',
            ]);
            if (salaries::where('telephone_secondaire', $telephone_secondaire)->whereNotIn('id', [$id])->exists()) {
                $validated = $request->validate([
                    'telephone_secondaire' => 'unique:salaries',
                ]);
            }
        } else {
            $telephone_secondaire = null;
        }
        if (salaries::where('telephone', $telephone)->whereNotIn('id', [$id])->exists()) {
            $validated = $request->validate([
                'telephone' => 'unique:salaries',
            ]);
        }
        // dd($nom,$telephone,$telephone_secondaire,$salaire,$accumulateur,$type_periode,$date_debut_contrat,$cni);
        // On enregistre le salaries
        $salaries = salaries::findOrFail($id);
        $salaries->nom = $nom;
        $salaries->telephone_secondaire = $telephone_secondaire;
        $salaries->telephone = $telephone;
        $salaries->adresse = $adresse;
        $salaries->cni = $cni;
        $salaries->id_saver = auth()->user()->id;
        if ($salaries->salaire > $salaire) {
            $salaries->date_modif = now();
        }
        $salaries->accumulateur = $accumulateur;
        $salaries->salaire = $salaire;
        $salaries->type_periode = $type_periode;
        $salaries->statut = 1;
        $salaries->save();
        $message = 'Salarié'.' '.name($nom)."créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('salarie.show', $salaries->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Renderable
     */
    public function destroy($id)
    {
        $salarie = Salaries::findOrFail($id);
        if ($salarie->statut == 0) {
            $salarie->statut = 1;
            if (strtotime(now()) >= strtotime($salarie->date_dernier_paiement)) {
                $salarie->date_modif = now();
            }
            $salarie->date_dernier_paiement = prochaine_date_paie($salarie->type_periode, $salarie->accumulateur, now(), 1);
            $message = 'Salarié '.name($salarie->nom)." Activé avec <b class='text-success'> Succès.</b>";
        } else {
            $salarie->statut = 0;
            $message = 'Salarié '.name($salarie->nom)." Desactivé avec <b class='text-success'> Succès.</b>";
        }
        $salarie->save();
        session()->flash('message', $message);

        return redirect()->back();
    }

    public function search(Request $request, $id)
    {
        $query = PaiementSalaires::where('id_salarie', $id); // Appliquer directement le filtre par id_salarie

        $types_periode = [
            'jour' => 'Jour(s)',
            'semaine' => 'Semaine(s)',
            'mois' => 'Mois',
            'annee' => 'Année(s)',
        ];

        $types_Paiement = [
            'avance' => 'Avance',
            'paiement_complet' => 'Complet',
        ];

        // Filtrage par dates
        if ($request->filled('date_debut')) {
            $query->where('date_paiement', '>=', $request->input('date_debut'));
        }

        if ($request->filled('date_fin')) {
            $query->where('date_paiement', '<=', $request->input('date_fin'));
        }

        // Filtrage par type de paiement
        if ($request->filled('recherche')) {
            $recherche = trim($request->input('recherche'));

            if ($recherche === 'avance' || $recherche === 'paiement_complet') {
                $query->where('type', $recherche); // Vérifie si la recherche est 'avance' ou 'paiement_complet'
            }
            // Si recherche est 'tout', aucun filtre par type n'est appliqué
        }

        // Exécution de la requête et récupération des résultats
        $paiements = $query->paginate(30);

        return view('salarie::salarie.details', compact('paiements', 'types_periode', 'types_Paiement'))->render();
    }
}
