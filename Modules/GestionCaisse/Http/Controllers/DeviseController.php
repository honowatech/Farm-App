<?php

namespace Modules\GestionCaisse\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\GestionCaisse\Facades\CompteServiceFacade;

class DeviseController extends Controller
{
    public function index()
    {
        $devises = CompteServiceFacade::listDevises();

        return view('gestioncaisse::devise.index', compact('devises'));
    }

    public function create()
    {
        return view('gestioncaisse::devise.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:gestion_caisse_devises,code',
            'symbole' => 'required|string|max:5',
            'nom' => 'required|string|max:255',
        ]);

        try {
            CompteServiceFacade::createDevise($validatedData);

            return redirect()->route('devise.index')->with('message', 'Devise créée avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la création de la devise : '.$e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $devise = CompteServiceFacade::findDevise($id);

            return view('gestioncaisse::devise.show', compact('devise'));
        } catch (\Exception $e) {
            return redirect()->route('devise.index')->with('error', 'Erreur lors de l\'affichage de la devise : '.$e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $devise = CompteServiceFacade::findDevise($id);

            return view('gestioncaisse::devise.edit', compact('devise'));
        } catch (\Exception $e) {
            return redirect()->route('devise.index')->with('error', 'Erreur lors de l\'édition de la devise : '.$e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'code' => 'required|string|max:10|unique:gestion_caisse_devises,code,'.$id,
            'symbole' => 'required|string|max:5',
            'nom' => 'required|string|max:255',
        ]);

        try {
            CompteServiceFacade::updateDevise($id, $validatedData);

            return redirect()->route('devise.index')->with('message', 'Devise mise à jour avec succès');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de la mise à jour de la devise : '.$e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            CompteServiceFacade::deleteDevise($id);

            return redirect()->route('devise.index')->with('message', 'Devise supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->route('devise.index')->with('error', 'Erreur lors de la suppression de la devise : '.$e->getMessage());
        }
    }
}
