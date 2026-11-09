<?php

namespace Modules\Salarie\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Modules\Salarie\Entities\PaiementSalaires;
use Modules\Salarie\Entities\Salaries;
use Illuminate\Routing\Controller;
use Carbon\Carbon;
class PaiementSalariesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('salarie::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('salarie::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $types_periode = [
        'jour' => 'Jour(s)', 
        'semaine' => 'Semaine(s)', 
        'mois' => 'Mois', 
        'annee' => 'Année(s)'
      ];
    $type = $request->input('type');
    $id_salarie = $request->input('id_salarie');
    $salarie = Salaries::findOrFail($id_salarie);
    $montant_all = PaiementSalaires::where('id_salarie',$salarie->id)
                ->whereDate('date_paiement', '>=', $salarie->date_modif)
                ->sum('montant');
    $pas_avance = montant_restant($montant_all,$salarie->salaire);
    if (!in_array($type, ['paiement_complet','avance'])) {
      $message = "<b class='text-danger'> Erreur Fatale!</b>";
      session()->flash('message',$message);
      return redirect()->back();
    }
    if ($type == 'paiement_complet') {
      $nbre_fois = $request->input('nbre_fois');
      $montant = $nbre_fois * $salarie->salaire;
      $date_debut = $salarie->date_dernier_paiement;
      $description = "<b> ".$salarie->nom." </b> a reçu un paiement de Salaire de <b> ".$montant." </b> FCFA  le <b> ".Ladate(now())." à ".Heure(now())." </b> pour une période de <b> ".$nbre_fois."x".$salarie->accumulateur." </b> (".$types_periode[$salarie->type_periode].")";
      $date_fin = prochaine_date_paie($salarie->type_periode, $salarie->accumulateur, $salarie->date_dernier_paiement, $nbre_fois);
      // dd('paiement complet' , $montant , 'Salarié_'.$id_salarie , $type , $description , $nbre_fois , $date_debut , $date_fin , $pas_avance);
    }else if($type == 'avance' && $pas_avance['statut'] == true){
      $validated = $request->validate([
        'montant' => 'bail|required|numeric|max:'.$salarie->salaire.'|min:25',
      ]);
      $type = 'avance';
      $nbre_fois = 0;
      $montant = intval($request->input('montant'));
      $date_debut = $salarie->date_dernier_paiement;
      $description = "<b> ".$salarie->nom." </b> a reçu une avance de paiement de Salaire d'un montant de <b> ".$montant." </b> FCFA le <b> ".Ladate(now())." à ".Heure(now())." <b>";
      $date_fin = prochaine_date_paie($salarie->type_periode, $salarie->accumulateur, $salarie->date_dernier_paiement, $nbre_fois, $type);
      // dd('Avance paiement' , $montant , 'Salarié_'.$id_salarie , $type , $description , $nbre_fois , $date_debut , $date_fin , $pas_avance);
    }else{
      $type = 'avance';
      $nbre_fois = 1;
      $date_fin = prochaine_date_paie($salarie->type_periode, $salarie->accumulateur, $salarie->date_dernier_paiement, $nbre_fois);

      $montant = $pas_avance['montant'];
      $date_debut = $salarie->date_dernier_paiement;
      $description = "<b> ".$salarie->nom." </b> a reçu un paiement de Salaire d'un montant de <b> ".$montant." </b> FCFA le <b> ".Ladate(now())." à ".Heure(now())." </b> pour combler l'avance faite et avancer la prochaine date de paie jusqu'au ".Ladate($date_fin);
      // dd('Avance paiement' , $montant , 'Salarié_'.$id_salarie , $type , $description , $nbre_fois , $date_debut , $date_fin , $pas_avance);
    }
    $status_dernier_paiement = PaiementSalaires::where('id_salarie',$id_salarie)->orderBy('id','desc')->first();
    $transaction = new PaiementSalaires;
    $transaction->date_paiement = now();
    $transaction->id_salarie = $id_salarie;
    $transaction->montant = $montant;
    $transaction->date_debut = $date_debut;
    $transaction->type = $type;
    $transaction->date_fin = $date_fin;
    $salarie->date_dernier_paiement = $date_fin;
    if ($status_dernier_paiement && $status_dernier_paiement->type == 'avance' ) {
         $transaction->date_fin = Carbon::parse($status_dernier_paiement->date_fin)->addMonth();
        $salarie->date_dernier_paiement = Carbon::parse($status_dernier_paiement->date_fin)->addMonth();
        $transaction->type = 'paiement_complet';
    }
    $transaction->description = $description;
    $salarie->save();
    $transaction->save();
      $message = name($salarie->nom)."a été payé avec succès<b class='text-success'> Succès.</b>";
      session()->flash('message',$message);
    return redirect()->back();

    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        $paiement = PaiementSalaires::findOrFail($id);
    return $paiement->description;
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('salarie::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
