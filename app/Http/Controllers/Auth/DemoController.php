<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DemoController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Connecte l'utilisateur à l'espace de démonstration pré-rempli.
     */
    public function login()
    {
        $tenant = Tenant::firstOrCreate(
            ['slug' => 'demo'],
            [
                'name' => 'Ferme de démonstration',
                'status' => 'active',
                'is_demo' => true,
                'demo_expires_at' => now()->addDays(30),
            ]
        );

        $typeAdmin = Typeusers::firstOrCreate(['libelle' => 'admin']);

        $user = User::firstOrNew(['email' => 'demo@farmapp.test']);
        $user->name = 'Démo FarmApp';
        $user->id_type_user = $typeAdmin->id;
        $user->statut = 1;

        if (! $user->exists) {
            $user->password = Hash::make('11111111');
        }

        $user->save();

        $tenant->users()->syncWithoutDetaching([
            $user->id => [
                'role_key' => 'admin',
                'status' => 'active',
                'accepted_at' => now(),
            ],
        ]);

        Auth::login($user);

        return redirect()->route('home');
    }
}
