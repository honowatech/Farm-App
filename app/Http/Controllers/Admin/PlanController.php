<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::withCount('subscriptions')->latest()->paginate(15);

        return view('superadmin.pages.plans.index', compact('plans'));
    }

    public function create()
    {
        $plan = new Plan([
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'trial_days' => 14,
            'features' => [],
            'limits' => [],
        ]);

        $featureCatalog = config('features.features', []);
        $limitCatalog = config('features.limits', []);

        return view('superadmin.pages.plans.create', compact('plan', 'featureCatalog', 'limitCatalog'));
    }

    public function store(Request $request)
    {
        $data = $this->validatePlan($request);

        $data['is_active'] = $request->boolean('is_active');
        $data['features'] = $this->normalizeFeatures($request->input('features', []));
        $data['limits'] = $this->normalizeLimits($request->input('limits', []));

        $plan = Plan::create($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('message', 'Forfait « '.$plan->name.' » créé.');
    }

    public function edit(Plan $plan)
    {
        $featureCatalog = config('features.features', []);
        $limitCatalog = config('features.limits', []);

        return view('superadmin.pages.plans.edit', compact('plan', 'featureCatalog', 'limitCatalog'));
    }

    public function update(Request $request, Plan $plan)
    {
        $data = $this->validatePlan($request, $plan);

        $data['is_active'] = $request->boolean('is_active');
        $data['features'] = $this->normalizeFeatures($request->input('features', []));
        $data['limits'] = $this->normalizeLimits($request->input('limits', []));

        $plan->update($data);

        return redirect()
            ->route('admin.plans.index')
            ->with('message', 'Forfait « '.$plan->name.' » mis à jour.');
    }

    private function validatePlan(Request $request, ?Plan $plan = null): array
    {
        return $request->validate([
            'code' => ['required', 'string', 'max:80', 'unique:plans,code'.($plan ? ','.$plan->id : '')],
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'currency' => ['required', 'string', 'size:3'],
            'billing_interval' => ['required', 'in:monthly,annually'],
            'trial_days' => ['nullable', 'integer', 'min:0', 'max:365'],
            'features' => ['sometimes', 'array'],
            'limits' => ['sometimes', 'array'],
        ]);
    }

    /**
     * Normalise les fonctionnalités en tableau associatif clé → booléen,
     * en couvrant tout le catalogue (même les entrées non cochées).
     */
    private function normalizeFeatures(array $selected): array
    {
        $result = [];

        foreach (array_keys(config('features.features', [])) as $key) {
            $result[$key] = in_array($key, $selected, true);
        }

        return $result;
    }

    /**
     * Normalise les limites en entiers bornés (min/max du catalogue),
     * puis les quotas par profil (stockés sous la clé imbriquée `profiles`).
     */
    private function normalizeLimits(array $input): array
    {
        $result = [];

        foreach (config('features.limits', []) as $key => $meta) {
            $value = (int) ($input[$key] ?? $meta['default'] ?? 0);
            $value = max((int) ($meta['min'] ?? 0), min((int) ($meta['max'] ?? PHP_INT_MAX), $value));
            $result[$key] = $value;
        }

        $profiles = [];

        foreach (config('features.profiles', []) as $key => $meta) {
            $value = (int) ($input['profiles'][$key] ?? $meta['default'] ?? 0);
            $value = max(0, min(10000, $value));
            $profiles[$key] = $value;
        }

        $result['profiles'] = $profiles;

        return $result;
    }
}
