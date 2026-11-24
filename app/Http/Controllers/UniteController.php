<?php

namespace App\Http\Controllers;

use App\Models\Unite;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UniteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $unites = Unite::orderBy('libelle')->paginate(10, ['*'], 'unite_page');
        $view = $request->ajax()
            ? 'admin.pages.setting.unites.index'
            : 'admin.pages.setting.unites.page';

        return view($view, compact('unites'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $view = $request->ajax()
            ? 'admin.pages.setting.unites.create'
            : 'admin.pages.setting.unites.create-page';

        return view($view);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        [$libelle, $abrege] = $this->validatedUnitData($request);

        if ($this->unitExists($libelle, $abrege)) {
            if (! $request->ajax()) {
                return back()->withInput()->withErrors(['table_data.0' => 'Cette unité existe déjà.']);
            }

            return '<span class="text-danger my-1"> Échec ! </span> Cette unité existe déjà';
        }

        $unite = new Unite;
        $unite->libelle = $libelle;
        $unite->abrege = $abrege;
        $unite->save();

        if ($request->ajax()) {
            return '<b class="text-success my-1">Effectué</b>';
        }

        return redirect()->route('unite.index')->with('message', 'Unité créée avec succès.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $unite = Unite::findOrFail($id);

        return view('admin.pages.setting.unites.show', compact('unite'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
        $unite = Unite::findOrFail($id);
        $view = $request->ajax()
            ? 'admin.pages.setting.unites.edit'
            : 'admin.pages.setting.unites.edit-page';

        return view($view, compact('unite'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        [$libelle, $abrege] = $this->validatedUnitData($request);
        $unite = Unite::findOrFail($id);

        if ($this->unitExists($libelle, $abrege, $unite->getKey())) {
            if (! $request->ajax()) {
                return back()->withInput()->withErrors(['table_data.0' => 'Cette unité existe déjà.']);
            }

            return '<span class="text-danger my-1"> Échec ! </span> Cette unité existe déjà';
        }

        $unite->libelle = $libelle;
        $unite->abrege = $abrege;
        $unite->save();

        if ($request->ajax()) {
            return '<b class="text-success my-1">Effectué</b>';
        }

        return redirect()->route('unite.index')->with('message', 'Unité mise à jour avec succès.');
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

        if (
            $unite->derives()->exists()
            || $unite->nutritions()->exists()
            || $unite->foodSettingVersions()->exists()
        ) {
            return redirect()->back()->with('error', 'Cette unité est déjà utilisée et ne peut pas être supprimée.');
        }

        $unite->delete();
        session()->flash('message', 'Unité '.ucfirst($unite->libelle).' supprimée avec succès.');

        return redirect()->back();
    }

    /**
     * @return array{string, string}
     */
    private function validatedUnitData(Request $request): array
    {
        $validated = $request->validate([
            'table_data' => ['required', 'array', 'size:2'],
            'table_data.0' => ['required', 'string', 'max:255'],
            'table_data.1' => ['required', 'string', 'max:255'],
        ]);

        return [
            trim($validated['table_data'][0]),
            trim($validated['table_data'][1]),
        ];
    }

    private function unitExists(string $libelle, string $abrege, ?int $ignoreId = null): bool
    {
        $query = Unite::query()
            ->whereRaw('LOWER(TRIM(libelle)) = ?', [Str::lower($libelle)])
            ->whereRaw('LOWER(TRIM(abrege)) = ?', [Str::lower($abrege)]);

        if ($ignoreId !== null) {
            $query->where('id', '<>', $ignoreId);
        }

        return $query->exists();
    }
}
