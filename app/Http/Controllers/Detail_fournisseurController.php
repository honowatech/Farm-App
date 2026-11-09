<?php

namespace App\Http\Controllers;

use App\Models\Detail_fournisseur;
use App\Models\Fournisseur;
use Illuminate\Http\Request;

class Detail_fournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index() {}

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create() {}

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $ids_detais = explode(',', $request->input('id_details'));
        $id_fournisseur = $request->input('id_fournisseur');
        $validated = $request->validate([
            'id_details' => 'bail|required',
            'id_fournisseur' => 'bail|required|numeric|min:1',
        ]);
        // dd($ids_detais);
        // On enregistre le Fournisseur
        foreach ($ids_detais as $key => $id_matiere_premiere) {
            $detail = new Detail_fournisseur;
            $detail->id_fournisseur = $id_fournisseur;
            $detail->id_matiere_premiere = $id_matiere_premiere;
            $detail->save();
        }
        $message = "Matières affectées avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->back();
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $Detail_fournisseur = Detail_fournisseur::findOrFail($id);
        $nom = $Detail_fournisseur->matiere_premiere->nom_produit;
        $Detail_fournisseur->delete();
        $message = $nom." Enlevé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->back();
    }
}
