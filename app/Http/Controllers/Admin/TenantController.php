<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::withCount('users')->latest()->paginate(15);

        return view('superadmin.pages.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('superadmin.pages.tenants.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'slug' => ['required', 'string', 'max:100', 'regex:/^[a-z0-9-]+$/', 'unique:tenants,slug'],
            'status' => ['required', 'in:active,suspended,pending'],
            'is_demo' => ['sometimes', 'boolean'],
            'demo_expires_at' => ['nullable', 'date'],
        ]);

        $tenant = Tenant::create($data);

        return redirect()
            ->route('admin.tenants.index')
            ->with('message', 'Entreprise « '.$tenant->name.' » créée.');
    }

    public function toggleStatus(Tenant $tenant)
    {
        $tenant->status = $tenant->status === 'active' ? 'suspended' : 'active';
        $tenant->save();

        return back()->with('message', 'Statut de l\'entreprise mis à jour.');
    }
}
