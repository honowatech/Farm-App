<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc';

        $allowed = ['name', 'type', 'statut', 'entreprise'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'name';
        }

        $query = User::with(['type_user', 'tenants'])->withCount('tenants');

        if ($sort === 'type') {
            // Tri par profil global (type_users.libelle), sans perdre les colonnes users.
            $query->leftJoin('type_users', 'users.id_type_user', '=', 'type_users.id')
                ->select('users.*')
                ->orderBy('type_users.libelle', $direction)
                ->orderBy('users.name', 'asc');
        } elseif ($sort === 'statut') {
            $query->orderBy('users.statut', $direction)->orderBy('users.name', 'asc');
        } elseif ($sort === 'entreprise') {
            // Tri par nom d'entreprise (première entreprise du membre, par ordre alpha).
            $query->orderBy(
                Tenant::select('name')
                    ->join('tenant_user', 'tenants.id', '=', 'tenant_user.tenant_id')
                    ->whereColumn('tenant_user.user_id', 'users.id')
                    ->orderBy('tenants.name')
                    ->limit(1),
                $direction
            )->orderBy('users.name', 'asc');
        } else {
            $query->orderBy('users.name', $direction);
        }

        $users = $query->paginate(20)->withQueryString();

        return view('superadmin.pages.users.index', compact('users', 'sort', 'direction'));
    }

    public function toggleStatus(User $user)
    {
        $user->statut = $user->statut ? 0 : 1;
        $user->save();

        return back()->with('message', 'Statut du compte « '.$user->name.' » mis à jour.');
    }
}
