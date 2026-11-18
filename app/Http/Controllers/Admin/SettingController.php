<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PlatformSetting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private array $definitions = [
        'app_name' => ['label' => 'Nom de l\'application', 'type' => 'text', 'default' => 'Farm-App'],
        'support_email' => ['label' => 'Email de support', 'type' => 'email', 'default' => ''],
        'default_currency' => ['label' => 'Devise par défaut', 'type' => 'text', 'default' => 'XAF'],
        'enable_payments' => ['label' => 'Paiements Mobile Money', 'type' => 'boolean', 'default' => '1'],
        'enable_pwa' => ['label' => 'Application installable (PWA)', 'type' => 'boolean', 'default' => '1'],
    ];

    public function index()
    {
        $definitions = $this->definitions;

        $values = collect($definitions)
            ->mapWithKeys(fn ($meta, $key) => [$key => PlatformSetting::get($key, $meta['default'])]);

        return view('superadmin.pages.settings.index', compact('definitions', 'values'));
    }

    public function update(Request $request)
    {
        foreach ($this->definitions as $key => $meta) {
            if ($meta['type'] === 'boolean') {
                PlatformSetting::set($key, $request->boolean($key) ? '1' : '0');
            } else {
                PlatformSetting::set($key, (string) $request->input($key, ''));
            }
        }

        return back()->with('message', 'Paramètres de la plateforme enregistrés.');
    }
}
