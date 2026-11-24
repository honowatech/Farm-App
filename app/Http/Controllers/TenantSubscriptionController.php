<?php

namespace App\Http\Controllers;

use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Services\MamoniService;
use App\Support\TenantContext;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantSubscriptionController extends Controller
{
    public function index(TenantContext $tenantContext)
    {
        $tenant = $tenantContext->tenant();
        $expiredTrial = $tenant->recoverableTrialSubscription();
        abort_unless($expiredTrial, 404);

        $plans = Plan::query()
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        $pendingSubscription = $tenant->subscriptions()
            ->where('status', 'pending')
            ->latest()
            ->first();
        $pendingPayment = $pendingSubscription?->paymentTransactions()
            ->where('status', 'pending')
            ->latest()
            ->first();

        return view('subscription.checkout', compact('plans', 'pendingPayment', 'expiredTrial'));
    }

    public function initiate(Request $request, TenantContext $tenantContext, MamoniService $mamoni): JsonResponse
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'phone_number' => ['nullable', 'string', 'max:30'],
        ]);
        $plan = Plan::query()->where('is_active', true)->findOrFail($data['plan_id']);
        $tenantId = $tenantContext->id();
        $amount = (int) round((float) $plan->price);

        if ($amount <= 0) {
            $this->activateFreePlan($tenantId, $plan);

            return response()->json(['ok' => true, 'activated' => true]);
        }

        $phone = $this->normalizePhone($data['phone_number'] ?? '');

        if (! preg_match('/^2376\d{8}$/', $phone)) {
            return response()->json([
                'ok' => false,
                'message' => 'Numéro invalide. Format attendu : 2376XXXXXXXX (12 chiffres).',
            ], 422);
        }

        $subscription = $this->reserveSubscription($tenantId, $plan);

        if ($subscription instanceof PaymentTransaction) {
            return response()->json([
                'ok' => true,
                'pending' => true,
                'transaction_id' => $subscription->id,
                'message' => 'Un paiement est déjà en cours. Terminez-le sur votre téléphone.',
            ]);
        }

        $externalReference = 'SUB-'.now()->format('Ymd').'-'.strtoupper(Str::random(8));

        try {
            $result = $mamoni->initPayment(
                $phone,
                $amount,
                $externalReference,
                $request->user()->name
            );
        } catch (ConnectionException $exception) {
            $this->cancelPendingSubscription($subscription);

            return response()->json([
                'ok' => false,
                'message' => 'MaMoNi est temporairement injoignable. Réessayez dans quelques instants.',
            ], 503);
        }

        $gatewayReference = $result['data']['gateway_reference'] ?? null;

        if (! ($result['ok'] ?? false) || ! is_string($gatewayReference) || $gatewayReference === '') {
            $this->cancelPendingSubscription($subscription);

            return response()->json([
                'ok' => false,
                'message' => $result['message'] ?? 'MaMoNi n’a pas fourni de référence de paiement.',
            ], 422);
        }

        $transaction = DB::transaction(function () use ($subscription, $amount, $phone, $externalReference, $gatewayReference, $result, $request) {
            $lockedSubscription = Subscription::query()
                ->lockForUpdate()
                ->findOrFail($subscription->getKey());

            abort_unless($lockedSubscription->status === 'pending', 409);

            $transaction = $lockedSubscription->paymentTransactions()->create([
                'amount' => $amount,
                'phone_number' => $phone,
                'external_reference' => $externalReference,
                'gateway_reference' => $gatewayReference,
                'provider_slug' => $result['data']['payment_provider_slug'] ?? null,
                'status' => 'pending',
                'client_name' => $request->user()->name,
                'response' => json_encode($result),
            ]);

            $lockedSubscription->provider = 'mamoni';
            $lockedSubscription->provider_reference = $gatewayReference;
            $lockedSubscription->save();

            return $transaction;
        });

        return response()->json([
            'ok' => true,
            'transaction_id' => $transaction->id,
            'gateway_reference' => $transaction->gateway_reference,
            'provider_slug' => $transaction->provider_slug,
            'external_reference' => $transaction->external_reference,
            'amount' => $transaction->amount,
        ]);
    }

    public function status(
        PaymentTransaction $transaction,
        TenantContext $tenantContext,
        MamoniService $mamoni
    ): JsonResponse {
        $transaction->load('payable');
        $subscription = $transaction->payable;

        abort_unless(
            $subscription instanceof Subscription
                && (string) $subscription->tenant_id === (string) $tenantContext->id(),
            404
        );

        if ($transaction->status !== 'pending') {
            return $this->statusResponse($transaction);
        }

        if (! $transaction->gateway_reference) {
            return response()->json([
                'final' => true,
                'payment_status' => 'failed',
                'message' => 'La référence de paiement MaMoNi est introuvable.',
            ]);
        }

        try {
            $result = $mamoni->verify($transaction->gateway_reference);
        } catch (ConnectionException $exception) {
            return response()->json([
                'final' => false,
                'payment_status' => 'pending',
                'message' => 'MaMoNi est temporairement injoignable. Le suivi reprendra automatiquement.',
            ], 503);
        }

        if (! ($result['final'] ?? false)) {
            return response()->json([
                'final' => false,
                'payment_status' => 'pending',
                'message' => 'Paiement en attente de confirmation.',
            ]);
        }

        $this->finalizePayment($transaction, $subscription, $result);
        $transaction->refresh();

        return $this->statusResponse($transaction);
    }

    private function reserveSubscription(string $tenantId, Plan $plan): Subscription|PaymentTransaction
    {
        return DB::transaction(function () use ($tenantId, $plan) {
            $tenant = Tenant::query()->lockForUpdate()->findOrFail($tenantId);
            abort_unless($tenant->recoverableTrialSubscription(), 409, 'La période de récupération des données de l’essai est terminée.');
            abort_if($tenant->activeSubscription(), 409, 'Un abonnement est déjà actif.');

            $pendingSubscription = $tenant->subscriptions()
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($pendingSubscription) {
                $pendingPayment = $pendingSubscription->paymentTransactions()
                    ->where('status', 'pending')
                    ->latest()
                    ->first();

                if ($pendingPayment) {
                    return $pendingPayment;
                }

                abort_if($pendingSubscription->created_at->isAfter(now()->subMinutes(5)), 409, 'Une souscription est déjà en cours de préparation.');
                $pendingSubscription->update(['status' => 'canceled', 'canceled_at' => now()]);
            }

            return $tenant->subscriptions()->create([
                'plan_id' => $plan->id,
                'status' => 'pending',
            ]);
        });
    }

    private function activateFreePlan(string $tenantId, Plan $plan): void
    {
        DB::transaction(function () use ($tenantId, $plan) {
            $tenant = Tenant::query()->lockForUpdate()->findOrFail($tenantId);
            abort_unless($tenant->recoverableTrialSubscription(), 409, 'La période de récupération des données de l’essai est terminée.');
            abort_if($tenant->activeSubscription(), 409, 'Un abonnement est déjà actif.');

            $pendingSubscription = $tenant->subscriptions()
                ->where('status', 'pending')
                ->latest()
                ->first();

            if ($pendingSubscription) {
                abort_if(
                    $pendingSubscription->paymentTransactions()->where('status', 'pending')->exists()
                        || $pendingSubscription->created_at->isAfter(now()->subMinutes(5)),
                    409,
                    'Une souscription est déjà en cours.'
                );
                $pendingSubscription->update(['status' => 'canceled', 'canceled_at' => now()]);
            }

            $this->cancelPreviousSubscriptions($tenant);

            $tenant->subscriptions()->create([
                'plan_id' => $plan->id,
                'status' => 'active',
                'starts_at' => now(),
                'current_period_starts_at' => now(),
                'current_period_ends_at' => $this->periodEnd($plan),
            ]);
        });
    }

    private function cancelPendingSubscription(Subscription $subscription): void
    {
        $subscription->update([
            'status' => 'canceled',
            'canceled_at' => now(),
        ]);
    }

    private function finalizePayment(PaymentTransaction $transaction, Subscription $subscription, array $result): void
    {
        DB::transaction(function () use ($transaction, $subscription, $result) {
            $lockedTransaction = PaymentTransaction::query()
                ->lockForUpdate()
                ->findOrFail($transaction->getKey());
            $lockedSubscription = Subscription::query()
                ->lockForUpdate()
                ->findOrFail($subscription->getKey());

            if ($lockedTransaction->status !== 'pending') {
                return;
            }

            $status = $result['payment_status'] ?? 'failed';
            if (! in_array($status, ['successful', 'failed', 'cancelled', 'expired'], true)) {
                $status = 'failed';
            }
            $lockedTransaction->update([
                'status' => $status,
                'receipt_url' => $result['data']['receipt']['receipt_url'] ?? null,
                'response' => json_encode($result['data'] ?? $result),
            ]);

            if ($status !== 'successful') {
                $lockedSubscription->update([
                    'status' => 'canceled',
                    'canceled_at' => now(),
                ]);

                return;
            }

            $tenant = Tenant::query()->lockForUpdate()->findOrFail($lockedSubscription->tenant_id);
            $trial = $tenant->expiredTrialSubscription();
            $deadline = $trial?->trial_ends_at?->copy()->addDays(Tenant::TRIAL_RECOVERY_DAYS);
            $paymentStartedInTime = $deadline
                && $lockedSubscription->created_at
                && ! $lockedSubscription->created_at->isAfter($deadline);

            abort_unless(
                $tenant->recoverableTrialSubscription() || $paymentStartedInTime,
                409,
                'La période de récupération des données de l’essai est terminée.'
            );

            $this->cancelPreviousSubscriptions($tenant);
            $plan = $lockedSubscription->plan()->firstOrFail();
            $lockedSubscription->update([
                'status' => 'active',
                'starts_at' => now(),
                'trial_ends_at' => null,
                'current_period_starts_at' => now(),
                'current_period_ends_at' => $this->periodEnd($plan),
                'canceled_at' => null,
            ]);
        });
    }

    private function cancelPreviousSubscriptions(Tenant $tenant): void
    {
        $tenant->subscriptions()
            ->whereIn('status', ['trialing', 'pending'])
            ->update([
                'status' => 'canceled',
                'canceled_at' => now(),
                'updated_at' => now(),
            ]);
    }

    private function statusResponse(PaymentTransaction $transaction): JsonResponse
    {
        return response()->json([
            'final' => true,
            'payment_status' => $transaction->status,
            'receipt_url' => $transaction->receipt_url,
            'provider_slug' => $transaction->provider_slug,
            'external_reference' => $transaction->external_reference,
        ]);
    }

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '237')) {
            return $phone;
        }

        return str_starts_with($phone, '6') && strlen($phone) === 9
            ? '237'.$phone
            : $phone;
    }

    private function periodEnd(Plan $plan): Carbon
    {
        return $plan->billing_interval === 'annually'
            ? now()->addYear()
            : now()->addMonth();
    }
}
