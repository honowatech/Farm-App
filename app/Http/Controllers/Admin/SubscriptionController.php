<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Subscription;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['tenant', 'plan'])
            ->latest()
            ->paginate(20);

        $plans = Plan::orderBy('name')->get();

        return view('superadmin.pages.subscriptions.index', compact('subscriptions', 'plans'));
    }

    /**
     * Change le statut d'un abonnement (activation, suspension, reprise, résiliation).
     */
    public function changeStatus(Request $request, Subscription $subscription)
    {
        $request->validate([
            'action' => ['required', 'in:activate,suspend,resume,cancel'],
        ]);

        switch ($request->input('action')) {
            case 'activate':
                $subscription->status = 'active';
                $subscription->starts_at = $subscription->starts_at ?? now();
                $subscription->current_period_starts_at = $subscription->current_period_starts_at ?? now();
                $subscription->current_period_ends_at = $subscription->current_period_ends_at ?? now()->addMonth();
                break;

            case 'suspend':
                $subscription->status = 'suspended';
                break;

            case 'resume':
                $subscription->status = 'active';
                break;

            case 'cancel':
                $subscription->status = 'canceled';
                $subscription->canceled_at = now();
                break;
        }

        $subscription->save();

        return $this->respond($request, 'Abonnement mis à jour.');
    }

    /**
     * Prolonge la période courante de l'abonnement.
     */
    public function extend(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'days' => ['required', 'integer', 'min:1', 'max:3650'],
        ]);

        $base = ($subscription->current_period_ends_at && $subscription->current_period_ends_at->isFuture())
            ? $subscription->current_period_ends_at
            : now();

        $subscription->current_period_ends_at = $base->copy()->addDays((int) $data['days']);

        if (in_array($subscription->status, ['active', 'suspended'], true)) {
            $subscription->status = 'active';
        }

        $subscription->save();

        return $this->respond($request, 'Abonnement prolongé de '.$data['days'].' jour(s).');
    }

    /**
     * Change le forfait associé à l'abonnement.
     */
    public function changePlan(Request $request, Subscription $subscription)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
        ]);

        $subscription->plan_id = $data['plan_id'];
        $subscription->save();

        return $this->respond($request, 'Forfait de l\'abonnement modifié.');
    }

    private function respond(Request $request, string $message)
    {
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['ok' => true, 'message' => $message]);
        }

        return back()->with('message', $message);
    }
}
