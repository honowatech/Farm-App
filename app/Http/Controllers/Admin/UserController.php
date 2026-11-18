<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['type_user', 'tenants'])
            ->withCount('tenants')
            ->latest()
            ->paginate(20);

        return view('superadmin.pages.users.index', compact('users'));
    }

    public function toggleStatus(User $user)
    {
        $user->statut = $user->statut ? 0 : 1;
        $user->save();

        return back()->with('message', 'Statut du compte « '.$user->name.' » mis à jour.');
    }
}
