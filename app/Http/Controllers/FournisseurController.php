<?php

namespace App\Http\Controllers;

use App\Models\Fournisseur;
use App\Models\Matiere_premiere;
use Illuminate\Http\Request;

class FournisseurController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $fournisseurs = Fournisseur::where('nom', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('adresse', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('telephone', 'like', '%'.$request->input('recherche').'%')
            ->orWhere('telephone_secondaire', 'like', '%'.$request->input('recherche').'%')
            ->orderBy('updated_at', 'desc')
            ->paginate(20);
        session()->flash('search', $request->input('recherche'));

        return view('admin.pages.fournisseur.index', compact('fournisseurs'));
    }

    /**
     * Exporte les fournisseurs au format CSV.
     */
    public function export(Request $request)
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        $rows = $fournisseurs->map(fn ($f) => [
            $f->nom,
            $f->telephone,
            $f->telephone_secondaire,
            $f->adresse,
            $f->statut == 1 ? 'Actif' : 'Inactif',
        ])->toArray();

        return $this->downloadCsv(['Nom', 'Téléphone', 'Téléphone secondaire', 'Adresse', 'Statut'], $rows, 'fournisseurs_'.now()->format('Y-m-d'));
    }

    /**
     * Exporte les fournisseurs au format PDF.
     */
    public function exportPdf(Request $request)
    {
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return $this->downloadPdfView('admin.pages.fournisseur.liste_pdf', compact('fournisseurs'), 'fournisseurs_'.now()->format('Y-m-d'), 'landscape');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('admin.pages.fournisseur.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $nom = $request->input('nom');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $telephone_secondaire = str_replace(' ', '', $request->input('telephone_secondaire'));
        $adresse = $request->input('adresse');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|unique:fournisseurs|regex:/^[6,2][0-9]{8}$/',
            'adresse' => 'bail|required|min:4',
        ]);
        if ($telephone_secondaire != null) {
            $validated = $request->validate([
                'telephone' => 'bail|unique:fournisseurs|regex:/^[6,2][0-9]{8}$/',
            ]);
        }
        // dd($nom,$telephone,$telephone_secondaire,$adresse);
        // On enregistre le Fournisseur
        $fournisseur = new Fournisseur;
        $fournisseur->nom = $nom;
        $fournisseur->telephone_secondaire = $telephone_secondaire;
        $fournisseur->telephone = $telephone;
        $fournisseur->adresse = $adresse;
        $fournisseur->statut = 1;
        $fournisseur->save();
        $message = 'Fournisseur'.' '.name($nom)."créé avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('fournisseur.show', $fournisseur->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        $matieres_premieres = Matiere_premiere::orderBy('created_at', 'desc')->get();

        return view('admin.pages.fournisseur.info', compact('fournisseur', 'matieres_premieres'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);

        return view('admin.pages.fournisseur.edit', compact('fournisseur'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {

        $nom = $request->input('nom');
        $telephone = str_replace(' ', '', $request->input('telephone'));
        $telephone_secondaire = str_replace(' ', '', $request->input('telephone_secondaire'));
        $adresse = $request->input('adresse');
        $validated = $request->validate([
            'nom' => 'bail|required|max:255',
            'telephone' => 'bail|required|regex:/^[6,2][0-9]{8}$/',
            'adresse' => 'bail|required|min:4',
        ]);
        if ($telephone_secondaire != null) {
            $validated = $request->validate([
                'telephone_secondaire' => 'bail|regex:/^[6,2][0-9]{8}$/',
            ]);
            if (Fournisseur::where('telephone_secondaire', $telephone_secondaire)->whereNotIn('id', [$id])->exists()) {
                $validated = $request->validate([
                    'telephone_secondaire' => 'unique:fournisseurs',
                ]);
            }
        }
        if (Fournisseur::where('telephone', $telephone)->whereNotIn('id', [$id])->exists()) {
            $validated = $request->validate([
                'telephone' => 'unique:fournisseurs',
            ]);
        }
        // dd($nom,$telephone,$telephone_secondaire,$adresse);
        // On édite lefournisseur
        $fournisseur = Fournisseur::findOrFail($id);
        $fournisseur->nom = $nom;
        $fournisseur->telephone = $telephone;
        $fournisseur->telephone_secondaire = $telephone_secondaire;
        $fournisseur->adresse = $adresse;
        $fournisseur->save();
        $message = 'Fournisseur'.' '.name($nom)."mis à jour avec <b class='text-success'> Succès.</b>";
        session()->flash('message', $message);

        return redirect()->route('fournisseur.show', $fournisseur->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $fournisseur = Fournisseur::findOrFail($id);
        if ($fournisseur->statut == 0) {
            $fournisseur->statut = 1;
            $message = 'Client '.name($fournisseur->nom)." Activé(e) avec <b class='text-success'> Succès.</b>";
        } else {
            $fournisseur->statut = 0;
            $message = 'Client '.name($fournisseur->nom)." Desactivé(e) avec <b class='text-success'> Succès.</b>";
        }
        $fournisseur->save();
        session()->flash('message', $message);

        return redirect()->back();
    }
}
