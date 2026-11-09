<?php

namespace Modules\GestionCaisse\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GestionCaisse\Entities\Compte;
use Modules\GestionCaisse\Entities\Transfert;
use Modules\GestionCaisse\Facades\CompteServiceFacade;


class TransfertController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        $transactions = CompteServiceFacade::listTransferts();
        return view('gestioncaisse::transfert.index',compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
         $caisses = CompteServiceFacade::listComptes();
        return view('gestioncaisse::transfert.create',compact('caisses'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        // validation des données du formulaire
        $request->validate([
            'caisse_source' => 'required|exists:gestion_caisse_Comptes,id',
            'destination_caisse' => 'required|exists:gestion_caisse_Comptes,id|different:caisse_source',
            'montant' => 'required|numeric|min:0.01',
            'date' => 'required|date',
            'description' => 'nullable|string|max:255',
        ]);

        try {
            // Création du transfert via le service
            CompteServiceFacade::createTransfert($request->all());

            // Message de succès
            return redirect()->route('transfert.index')->with('message', "Transfert effectué avec succès.");
        } catch (\Exception $e) {
            // Message d'erreur
            return redirect()->back()->with('error', "Une erreur s'est produite lors du transfert : " . $e->getMessage());
        }
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('gestioncaisse::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('gestioncaisse::edit');
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
        try {
            // Suppression du transfert via le service
            CompteServiceFacade::deleteTransfert($id);

            // Message de succès
            session()->flash('message', 'Transaction supprimée avec succès.');
        } catch (\Exception $e) {
            // Message d'erreur
            session()->flash('error', 'Une erreur s\'est produite lors de la suppression de la transaction : ' . $e->getMessage());
        }

        // Rediriger vers la liste des transactions
        return redirect()->route('transfert.index');
    }
}
