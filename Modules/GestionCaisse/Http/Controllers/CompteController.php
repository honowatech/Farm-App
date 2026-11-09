<?php

namespace Modules\GestionCaisse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GestionCaisse\Entities\Compte;
use Modules\GestionCaisse\Entities\Devise;
use Modules\GestionCaisse\Facades\CompteServiceFacade;

class CompteController extends Controller
{
    public function index()
    {
        $comptes = CompteServiceFacade::listComptes();

        return view('gestioncaisse::compte.index', compact('comptes'));
    }

    public function create()
    {
        $devises = CompteServiceFacade::listDevises();

        return view('gestioncaisse::compte.create', compact('devises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type_account' => 'required|string|in:caisse,banque',
            'currency_id' => 'required|numeric',
        ]);

        if ($request->type_account === 'caisse') {
            $request->validate([
                'nom_caisse' => 'required|string|unique:gestion_caisse_Comptes,nom|max:255',
            ]);
        }

        if ($request->type_account === 'banque') {
            $request->validate([
                'nom_banque' => 'required|string|max:255',
                'num_compte' => 'required|string|unique:gestion_caisse_Comptes,nom|max:255',
            ]);
        }

        try {
            CompteServiceFacade::createCompte($request->all());

            return redirect()->route('compte.index')->with('message', 'Compte créé avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création du compte : '.$e->getMessage());
        }
    }

    public function show($id)
    {
        // Implementation not provided
    }

    public function edit($id)
    {
        $compte = Compte::findOrFail($id);
        $devises = Devise::all();

        return view('gestioncaisse::compte.edit', compact('compte', 'devises'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'type_account' => 'required|string',
            'nom' => 'nullable|required_if:type_account,caisse|string|max:255',
            'numero_compte' => 'nullable|required_if:type_account,banque|string|max:255',
            'nom_banque' => 'nullable|required_if:type_account,banque|string|max:255',
            'devise' => 'required|exists:gestion_caisse_devises,id',
        ]);

        try {
            CompteServiceFacade::updateCompte($id, $validatedData);

            return redirect()->route('compte.index')->with('message', 'Compte mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour : '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            CompteServiceFacade::deleteCompte($id);

            return redirect()->route('compte.index')->with('message', 'Compte supprimé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la suppression : '.$e->getMessage());
        }
    }
}
