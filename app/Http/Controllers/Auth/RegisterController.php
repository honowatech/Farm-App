<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class RegisterController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Inscription : crée l'utilisateur, son entreprise et le rattache en tant
     * qu'administrateur. Un abonnement d'essai est créé si un forfait le permet.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'company_name' => ['required', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $result = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'statut' => 1,
            ]);

            $tenant = Tenant::create([
                'name' => $data['company_name'],
                'slug' => $this->uniqueSlug($data['company_name']),
                'status' => 'active',
                'is_demo' => false,
            ]);

            $tenant->users()->attach($user->id, [
                'role_key' => 'admin',
                'status' => 'active',
                'accepted_at' => now(),
            ]);

            $this->createTrialSubscription($tenant);

            return $user;
        });

        Auth::login($result);

        return redirect()->route('home');
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'entreprise';
        $slug = $base;
        $i = 2;

        while (Tenant::where('slug', $slug)->exists()) {
            $slug = $base.'-'.$i;
            $i++;
        }

        return $slug;
    }

    private function createTrialSubscription(Tenant $tenant): void
    {
        $plan = Plan::query()
            ->where('is_active', true)
            ->where('trial_days', '>', 0)
            ->orderBy('trial_days')
            ->first();

        if (! $plan) {
            return;
        }

        Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'trialing',
            'trial_ends_at' => now()->addDays($plan->trial_days),
            'current_period_starts_at' => now(),
        ]);
    }
}
