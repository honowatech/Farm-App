<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    /**
     * Affiche l'historique des actions (journal d'audit).
     */
    public function index(Request $request)
    {
        $activities = Activity::with('causer')
            ->when($request->filled('evenement'), function ($query) use ($request) {
                $query->where('event', $request->input('evenement'));
            })
            ->latest()
            ->paginate(25)
            ->withQueryString();

        return view('admin.pages.audit.index', compact('activities'));
    }
}
