<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Typeusers;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Typeusers::with('permissions')->orderBy('libelle')->get();

        $groups = collect(config('permissions.permissions'))
            ->groupBy('group')
            ->map(fn ($items) => collect($items)->sortBy('label')->all());

        return view('superadmin.pages.roles.index', compact('roles', 'groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'libelle' => ['required', 'string', 'max:80', 'regex:/^[a-z_]+$/', 'unique:type_users,libelle'],
        ]);

        Typeusers::create(['libelle' => $data['libelle']]);

        return back()->with('message', 'Profil « '.$data['libelle'].' » créé. Pensez à lui affecter des permissions.');
    }

    /**
     * Bascule une permission pour un profil (appel AJAX depuis la matrice).
     */
    public function togglePermission(Request $request, Typeusers $typeUser)
    {
        if ($typeUser->libelle === 'superadmin') {
            return response()->json(['ok' => false, 'message' => 'Le super administrateur possède toutes les permissions.'], 403);
        }

        $permission = $request->input('permission');

        if (! is_string($permission) || ! array_key_exists($permission, config('permissions.permissions', []))) {
            return response()->json(['ok' => false, 'message' => 'Permission inconnue.'], 422);
        }

        $perm = Permission::firstOrCreate(
            ['name' => $permission],
            [
                'label' => config('permissions.permissions')[$permission]['label'],
                'group' => config('permissions.permissions')[$permission]['group'],
            ]
        );

        $granted = $typeUser->permissions()->where('permissions.id', $perm->id)->exists();

        if ($granted) {
            $typeUser->permissions()->detach($perm->id);
        } else {
            $typeUser->permissions()->attach($perm->id);
        }

        return response()->json(['ok' => true, 'granted' => ! $granted]);
    }

    public function destroy(Typeusers $typeUser)
    {
        if (in_array($typeUser->libelle, ['admin', 'superadmin'], true)) {
            return back()->with('error', 'Ce profil est protégé et ne peut pas être supprimé.');
        }

        if ($typeUser->users()->count() > 0) {
            return back()->with('error', 'Impossible de supprimer un profil associé à des utilisateurs.');
        }

        $typeUser->delete();

        return back()->with('message', 'Profil supprimé.');
    }
}
