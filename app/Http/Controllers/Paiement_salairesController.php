<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Salaries;
use App\Models\Paiement_salaires;

class Paiement_salairesController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index()
  {
    
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create()
  {
    
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
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
    $montant_all = Paiement_salaires::where('id_salarie',[$salarie->id])
                ->whereDate('date_paiement', '>=', $salarie->date_modif)
                ->get()->sum('montant');
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
    }else if($type == 'avance' && $pas_avance['statut']){
      $validated = $request->validate([
        'montant' => 'bail|required|numeric|max:'.$salarie->salaire.'|min:25',
      ]);
      $nbre_fois = 0;
      $montant = intval($request->input('montant'));
      $date_debut = $salarie->date_dernier_paiement;
      $description = "<b> ".$salarie->nom." </b> a reçu une avance de paiement de Salaire d'un montant de <b> ".$montant." </b> FCFA le <b> ".Ladate(now())." à ".Heure(now())." <b>";
      $date_fin = prochaine_date_paie($salarie->type_periode, $salarie->accumulateur, $salarie->date_dernier_paiement, $nbre_fois);
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
    $transaction = new Paiement_salaires;
    $transaction->type = $type;
    $transaction->date_paiement = now();
    $transaction->id_salarie = $id_salarie;
    $transaction->montant = $montant;
    $transaction->date_debut = $date_debut;
    $transaction->date_fin = $date_fin;
    $transaction->description = $description;
    $salarie->date_dernier_paiement = $date_fin;
    $salarie->save();
    $transaction->save();
      $message = name($salarie->nom)."a été payé avec succès<b class='text-success'> Succès.</b>";
      session()->flash('message',$message);
    return redirect()->back();

  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id)
  {
    $paiement = Paiement_salaires::findOrFail($id);
    return $paiement->description;
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
