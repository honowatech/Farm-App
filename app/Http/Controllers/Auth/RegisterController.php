<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use App\Services\MamoniService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class RegisterController extends Controller
{
    public function __construct(
        private MamoniService $mamoni,
    ) {
        $this->middleware('guest');
    }

    public function showRegistrationForm(Request $request)
    {
        $plans = Plan::where('is_active', true)->orderBy('price')->get();
        $fromDemo = $request->boolean('from_demo');

        $plansData = $plans->map(fn ($plan) => [
            'id' => $plan->id,
            'name' => $plan->name,
            'price' => (int) round((float) $plan->price),
            'trial_days' => (int) $plan->trial_days,
            'billing_interval' => $plan->billing_interval,
        ])->values();

        return view('auth.register', compact('plans', 'plansData', 'fromDemo'));
    }

    /**
     * Inscription multi-tenant : choix du forfait, essai gratuit ou paiement
     * Mobile Money (MaMoNi), création de l'entreprise et connexion.
     */
    public function register(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'company_name' => ['required', 'string', 'max:150'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'billing_mode' => ['required', 'in:trial,payment'],
            'payment_transaction_id' => ['nullable', 'integer'],
            'payment_registration_token' => ['required_if:billing_mode,payment', 'nullable', 'string'],
        ]);

        $plan = Plan::where('is_active', true)->findOrFail($data['plan_id']);

        if ($data['billing_mode'] === 'trial') {
            $this->assertTrialAllowed($plan);
        }

        $result = DB::transaction(function () use ($data, $plan) {
            $transaction = $data['billing_mode'] === 'payment'
                ? $this->verifyPayment(
                    $plan,
                    $data['payment_transaction_id'] ?? null,
                    $data['payment_registration_token'] ?? null
                )
                : null;

            // Le créateur d'une entreprise en devient l'administrateur.
            $typeAdmin = Typeusers::firstOrCreate(['libelle' => 'admin']);

            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'id_type_user' => $typeAdmin->id,
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

            if ($data['billing_mode'] === 'trial') {
                $this->createTrialSubscription($tenant, $plan);
            } else {
                $this->createPaidSubscription($tenant, $plan, $transaction);
            }

            return $user;
        });

        Auth::login($result);
        $request->session()->forget([
            'demo_workspace_tenant_id',
            'demo_workspace_user_id',
            'demo_workspace_profile',
            'demo_workspace_access_token',
            'demo_workspace_claim_token',
        ]);
        $request->session()->regenerate();

        return redirect()
            ->route('onboarding.index')
            ->with('message', 'Bienvenue ! Votre espace « '.$data['company_name'].' » est prêt.')
            ->withCookie($this->forgetDemoCookie('farmapp_demo_access'))
            ->withCookie($this->forgetDemoCookie('farmapp_demo_recovery'));
    }

    /**
     * Initie un paiement d'inscription (montant recalculé côté serveur).
     */
    public function initiatePayment(Request $request)
    {
        $data = $request->validate([
            'plan_id' => ['required', 'integer', 'exists:plans,id'],
            'phone_number' => ['required', 'string'],
            'client_name' => ['nullable', 'string', 'max:255'],
        ]);

        $plan = Plan::where('is_active', true)->findOrFail($data['plan_id']);

        $amount = (int) round((float) $plan->price);

        if ($amount <= 0) {
            return response()->json([
                'ok' => false,
                'message' => 'Ce forfait est gratuit : aucun paiement requis.',
            ], 422);
        }

        $phone = $this->normalizePhone($data['phone_number']);

        if (! preg_match('/^2376\d{8}$/', $phone)) {
            return response()->json([
                'ok' => false,
                'message' => 'Numéro invalide. Format attendu : 2376XXXXXXXX (12 chiffres).',
            ], 422);
        }

        $externalRef = 'REG-'.date('Ymd').'-'.strtoupper(Str::random(6));
        $registrationToken = Str::random(64);

        $result = $this->mamoni->initPayment(
            $phone,
            $amount,
            $externalRef,
            $data['client_name'] ?? null
        );

        if (! $result['ok']) {
            return response()->json([
                'ok' => false,
                'message' => $result['message'] ?? 'Impossible d\'initier le paiement.',
            ], 422);
        }

        $transaction = PaymentTransaction::create([
            'amount' => $amount,
            'phone_number' => $phone,
            'external_reference' => $externalRef,
            'gateway_reference' => $result['data']['gateway_reference'] ?? null,
            'provider_slug' => $result['data']['payment_provider_slug'] ?? null,
            'status' => 'pending',
            'client_name' => $data['client_name'] ?? null,
            'response' => json_encode($result),
            'registration_token_hash' => hash('sha256', $registrationToken),
        ]);

        return response()->json([
            'ok' => true,
            'transaction_id' => $transaction->id,
            'gateway_reference' => $transaction->gateway_reference,
            'provider_slug' => $transaction->provider_slug,
            'external_reference' => $transaction->external_reference,
            'amount' => $amount,
            'registration_token' => $registrationToken,
        ]);
    }

    /**
     * Vérifie le statut d'un paiement d'inscription (polling).
     */
    public function paymentStatus(PaymentTransaction $transaction, Request $request)
    {
        $token = $request->input('registration_token');

        abort_unless(
            is_string($token)
                && $transaction->registration_token_hash
                && hash_equals($transaction->registration_token_hash, hash('sha256', $token)),
            404
        );

        if (! $transaction->gateway_reference) {
            return response()->json([
                'final' => true,
                'payment_status' => $transaction->status,
                'receipt_url' => $transaction->receipt_url,
                'provider_slug' => $transaction->provider_slug,
                'external_reference' => $transaction->external_reference,
            ]);
        }

        $result = $this->mamoni->verify($transaction->gateway_reference);

        if ($result['final']) {
            $status = $result['payment_status'] ?? 'failed';
            $transaction->update([
                'status' => $status,
                'receipt_url' => $result['data']['receipt']['receipt_url'] ?? null,
                'response' => json_encode($result['data']),
            ]);
        }

        return response()->json([
            'final' => $result['final'],
            'payment_status' => $result['final'] ? ($result['payment_status'] ?? 'failed') : 'pending',
            'receipt_url' => $transaction->receipt_url,
            'provider_slug' => $transaction->provider_slug,
            'external_reference' => $transaction->external_reference,
        ]);
    }

    private function assertTrialAllowed(Plan $plan): void
    {
        if ((int) round((float) $plan->price) <= 0) {
            return;
        }

        if ((int) $plan->trial_days <= 0) {
            throw ValidationException::withMessages([
                'billing_mode' => 'Ce forfait ne propose pas d\'essai gratuit : un paiement est requis.',
            ]);
        }
    }

    private function verifyPayment(Plan $plan, ?int $transactionId, ?string $token): PaymentTransaction
    {
        if (! $transactionId || ! $token) {
            throw ValidationException::withMessages(['payment' => 'Le paiement est requis pour ce forfait.']);
        }

        $transaction = PaymentTransaction::withoutGlobalScopes()
            ->whereKey($transactionId)
            ->lockForUpdate()
            ->first();

        if (! $transaction) {
            throw ValidationException::withMessages(['payment' => 'Transaction de paiement introuvable.']);
        }

        if (! $transaction->registration_token_hash
            || ! hash_equals($transaction->registration_token_hash, hash('sha256', $token))) {
            throw ValidationException::withMessages(['payment' => 'Jeton de paiement invalide.']);
        }

        if ($transaction->status !== 'successful') {
            throw ValidationException::withMessages(['payment' => 'Le paiement n\'a pas encore été confirmé.']);
        }

        if ($transaction->payable_id !== null
            || $transaction->payable_type !== null
            || ! str_starts_with($transaction->external_reference, 'REG-')) {
            throw ValidationException::withMessages(['payment' => 'Cette transaction ne peut pas être utilisée pour une inscription.']);
        }

        if ((int) $transaction->amount !== (int) round((float) $plan->price)) {
            throw ValidationException::withMessages(['payment' => 'Le montant payé ne correspond pas au forfait choisi.']);
        }

        return $transaction;
    }

    private function createTrialSubscription(Tenant $tenant, Plan $plan): Subscription
    {
        return Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'trialing',
            'trial_ends_at' => now()->addDays((int) $plan->trial_days),
            'current_period_starts_at' => now(),
        ]);
    }

    private function createPaidSubscription(Tenant $tenant, Plan $plan, PaymentTransaction $transaction): Subscription
    {
        $subscription = Subscription::create([
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'current_period_starts_at' => now(),
            'current_period_ends_at' => $this->periodEnd($plan),
            'provider' => 'mamoni',
            'provider_reference' => $transaction->gateway_reference,
        ]);

        $transaction->payable()->associate($subscription);
        $transaction->save();

        return $subscription;
    }

    private function forgetDemoCookie(string $name)
    {
        return cookie(
            $name,
            '',
            -1,
            '/',
            config('session.domain'),
            config('session.secure'),
            true,
            false,
            'lax'
        );
    }

    private function periodEnd(Plan $plan): Carbon
    {
        return $plan->billing_interval === 'annually'
            ? now()->addYear()
            : now()->addMonth();
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

    private function normalizePhone(string $phone): string
    {
        $phone = preg_replace('/\D/', '', $phone);

        if (str_starts_with($phone, '237')) {
            return $phone;
        }

        if (str_starts_with($phone, '6') && strlen($phone) === 9) {
            return '237'.$phone;
        }

        return $phone;
    }
}
