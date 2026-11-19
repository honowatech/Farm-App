<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $data = $this->buildQuery($request);

        $types = Typeusers::orderBy('libelle')->get();
        $tenants = Tenant::orderBy('name')->get();

        return view('superadmin.pages.users.index', array_merge($data, compact('types', 'tenants')));
    }

    /**
     * Retourne le tableau filtré/trié en HTML (appel AJAX).
     */
    public function ajax(Request $request)
    {
        $data = $this->buildQuery($request);

        return view('superadmin.pages.users._table', $data);
    }

    public function toggleStatus(User $user)
    {
        $user->statut = $user->statut ? 0 : 1;
        $user->save();

        return back()->with('message', 'Statut du compte « '.$user->name.' » mis à jour.');
    }

    private function buildQuery(Request $request): array
    {
        $sort = $request->query('sort', 'name');
        $direction = $request->query('direction', 'asc');
        $direction = in_array($direction, ['asc', 'desc'], true) ? $direction : 'asc';

        $allowed = ['name', 'type', 'statut', 'entreprise'];
        if (! in_array($sort, $allowed, true)) {
            $sort = 'name';
        }

        $query = User::with(['type_user', 'tenants'])->withCount('tenants');

        // --- Filtres ---
        if ($request->filled('nom')) {
            $term = $request->input('nom');
            // Le champ « Nom » recherche à la fois le nom et l'email.
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', '%'.$term.'%')
                  ->orWhere('email', 'like', '%'.$term.'%');
            });
        }

        if ($request->filled('type')) {
            $query->where('id_type_user', $request->input('type'));
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->filled('entreprise')) {
            $query->whereHas('tenants', function ($q) use ($request) {
                $q->where('tenants.id', $request->input('entreprise'));
            });
        }

        // --- Tri ---
        if ($sort === 'type') {
            $query->leftJoin('type_users', 'users.id_type_user', '=', 'type_users.id')
                ->select('users.*')
                ->orderBy('type_users.libelle', $direction)
                ->orderBy('users.name', 'asc');
        } elseif ($sort === 'statut') {
            $query->orderBy('users.statut', $direction)->orderBy('users.name', 'asc');
        } elseif ($sort === 'entreprise') {
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
        // Les liens de pagination de la partial AJAX doivent pointer vers la
        // page index (et non vers /ajax), tout en conservant filtres + tri.
        $users->withPath(route('admin.users.index'));

        return compact('users', 'sort', 'direction');
    }
}
