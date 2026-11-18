<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            ['label' => 'Entreprises', 'value' => Tenant::count(), 'icon' => 'grid', 'color' => 'primary'],
            ['label' => 'Entreprises actives', 'value' => Tenant::where('status', 'active')->count(), 'icon' => 'check-circle', 'color' => 'success'],
            ['label' => 'Démonstrations', 'value' => Tenant::where('is_demo', true)->count(), 'icon' => 'play', 'color' => 'warning'],
            ['label' => 'Forfaits actifs', 'value' => Plan::where('is_active', true)->count(), 'icon' => 'layers', 'color' => 'info'],
            ['label' => 'Utilisateurs', 'value' => User::count(), 'icon' => 'users', 'color' => 'dark'],
        ];

        $tenants = Tenant::withCount('users')->latest()->take(5)->get();

        return view('superadmin.pages.dashboard', compact('stats', 'tenants'));
    }
}
