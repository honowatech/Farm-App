<?php

namespace App\Http\Controllers;

use App\Models\Unite;
use Illuminate\Http\Request;

class UniteController extends Controller
{

  /**
   * Display a listing of the resource.
   *
   * @return Response
   */
  public function index(Request $request)
  {
    $unites = Unite::orderBy('libelle')->get();
    return view('admin.pages.setting.unites.index',compact('unites'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return Response
   */
  public function create(Request $request)
  {
    return view($request->input('table_data')[0]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @return Response
   */
  public function store(Request $request)
  {
    $datas = $request->input('table_data');
    $libelle = $datas[0];
    $abrege = $datas[1];
    $count_same = Unite::where(strtoupper('libelle'),strtoupper($libelle))->where(strtoupper('abrege'),strtoupper($abrege))->get()->count();
    if ($count_same != 0) {
      return '<span class="text-danger my-1"> Echec ! </span> Cet Unité existe déjà'; 
    }
    $unite = new Unite;
    $unite->libelle = $libelle;
    $unite->abrege = $abrege;
    $unite->save();
    return '<b class="text-success my-1">Effectué</b>'; 
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function show($id,Request $request)
  {
    
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return Response
   */
  public function edit($id,Request $request)
  {
    $unite = Unite::findOrFail($id);
    return view($request->input('table_data')[0],compact('unite'));
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function update($id,Request $request)
  {
    $datas = $request->input('table_data');
    $libelle = $datas[0];
    $abrege = $datas[1];
    $count_same = Unite::where(strtoupper('libelle'),strtoupper($libelle))
                        ->where(strtoupper('abrege'),strtoupper($abrege))
                        ->whereNotIn('id',$id)->get()->count();
    if ($count_same != 0) {
      return '<span class="text-danger my-1"> Echec ! </span> Cet Unité existe déjà'; 
    }
    $unite = Unite::findOrFail($id);
    $unite->libelle = $libelle;
    $unite->abrege = $abrege;
    $unite->save();
    return '<b class="text-success my-1">Effectué</b>'; 
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return Response
   */
  public function destroy($id)
  {
    $unite = Unite::findOrFail($id);
    $message = "Unité ". ucfirst($unite->libelle)." Supprimée avec <b class='text-success'> Succès.</b>";
    $unite->delete();
    session()->flash('message',$message);
    return redirect()->back();
  }
  
}
