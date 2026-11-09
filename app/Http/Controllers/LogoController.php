<?php

namespace App\Http\Controllers;
use App\Models\Logo;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class LogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.pages.setting.logo.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pages.setting.logo.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
{
    $file = $request->file('logo');
    $fileName = Str::random(40). '.' . $file->getClientOriginalExtension();
////////////// Vérifier si un fichier de logo a été téléchargé /////////////
    if ($request->hasFile('logo')) {
////////////// Récupérer le fichier de logo depuis la requête //////////////
            $validExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp', 'svg'];
            if ($file->isValid() && in_array($file->getClientOriginalExtension(), $validExtensions) ) {
            $path = $file->move(public_path('app-assets/images/avatars'), $fileName);
            $logo  = New Logo;
            $logo->path =  'app-assets/images/avatars/' . $fileName;
            $message= 'Logo modifié avec <span class="text-success">succès</span>';
            $logo->save();
////////////// Retourner une redirection avec un message de succès //////////////
            session()->flash('message', $message);
            return redirect()->back();
        } else {
////////////// Retourner une redirection avec un message d'erreur si le fichier n'est pas valide //////////////
            $modal= 'Veuillez selectionner une image';
            session()->put('modal', $modal);
            return redirect()->back();
        }
    } else {
////////////// Retourner une redirection avec un message d'erreur si aucun fichier de logo n'a été trouvé //////////////
        $modal= 'Aucun fichier trouvé';
        session()->put('modal', $modal);
        return redirect()->back();
    }
}

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
          
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
       
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
