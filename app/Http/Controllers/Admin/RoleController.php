<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Typeusers;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Typeusers::withCount('permissions')
            ->withCount('users')
            ->orderBy('libelle')
            ->get();

        return view('superadmin.pages.roles.index', compact('roles'));
    }

    public function show(Typeusers $typeUser)
    {
        $permissions = $typeUser->permissions()
            ->orderBy('group')
            ->orderBy('name')
            ->get()
            ->groupBy(fn ($permission) => $permission->group ?? 'Autre');

        return view('superadmin.pages.roles.show', compact('typeUser', 'permissions'));
    }
}
