<?php

namespace Tests\Feature;

use App\Http\Controllers\Admin\RoleController;
use App\Models\PaymentTransaction;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use App\Services\AlerteService;
use App\Services\DemoWorkspaceService;
use App\Services\MamoniService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createMinimalSchema();

        Route::middleware('role:superadmin')
            ->get('/admin/dashboard', fn () => response('central'))
            ->name('admin.dashboard');
    }

    protected function createMinimalSchema(): void
    {
        Schema::dropIfExists('tenant_onboarding_progress');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_domains');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('permission_type_user');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('users');
        Schema::dropIfExists('type_users');

        Schema::create('type_users', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->timestamps();
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('id_type_user')->nullable();
            $table->boolean('statut')->default(1);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('label')->nullable();
            $table->string('group')->nullable();
            $table->timestamps();
        });

        Schema::create('permission_type_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id');
            $table->foreignId('type_user_id');
            $table->timestamps();
            $table->unique(['permission_id', 'type_user_id']);
        });

        $migration = require database_path('migrations/2026_11_16_150000_create_tenant_control_plane_tables.php');
        $migration->up();
        $demoClaimMigration = require database_path('migrations/2026_11_20_000001_add_demo_claim_token_hash_to_tenants.php');
        $demoClaimMigration->up();

        $paymentMigration = require database_path('migrations/2026_11_11_100001_create_payment_transactions_table.php');
        $paymentMigration->up();
        $tokenMigration = require database_path('migrations/2026_11_20_000000_add_registration_token_hash_to_payment_transactions.php');
        $tokenMigration->up();
        $onboardingMigration = require database_path('migrations/2026_11_23_000000_create_tenant_onboarding_progress_table.php');
        $onboardingMigration->up();
    }

    private function user(string $role): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $role.'@example.test',
            'password' => Hash::make('password'),
            'id_type_user' => Typeusers::firstOrCreate(['libelle' => $role])->id,
        ]);
    }

    public function test_super_admin_can_access_central_dashboard(): void
    {
        $this->actingAs($this->user('superadmin'))
            ->get('/admin/dashboard')
            ->assertOk()
            ->assertSeeText('central');
    }

    public function test_regular_admin_is_forbidden_from_central_dashboard(): void
    {
        $this->actingAs($this->user('admin'))
            ->get('/admin/dashboard')
            ->assertStatus(403);
    }

    public function test_registration_creates_user_tenant_and_admin_membership(): void
    {
        $plan = Plan::create([
            'code' => 'starter',
            'name' => 'Starter',
            'price' => 0,
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'trial_days' => 14,
            'is_active' => true,
        ]);

        $response = $this->post('/register', [
            'plan_id' => $plan->id,
            'company_name' => 'Ma Ferme Test',
            'name' => 'Jean Dupont',
            'email' => 'jean@example.test',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'billing_mode' => 'trial',
        ]);

        $response->assertRedirect(route('onboarding.index'));

        $user = User::where('email', 'jean@example.test')->first();
        $this->assertNotNull($user);

        $tenant = Tenant::where('name', 'Ma Ferme Test')->first();
        $this->assertNotNull($tenant);

        $this->assertSame(
            'admin',
            $tenant->users()->first()->pivot->role_key
        );

        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'plan_id' => $plan->id,
            'status' => 'trialing',
        ]);
    }

    public function test_subscribing_from_demo_creates_a_new_company_without_demo_data(): void
    {
        $plan = Plan::create([
            'code' => 'clean-trial',
            'name' => 'Essai 14 jours',
            'price' => 0,
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'trial_days' => 14,
            'is_active' => true,
        ]);
        $token = str_repeat('c', 64);
        $demoTenant = Tenant::create([
            'name' => 'Espace démo existant',
            'slug' => 'demo-a-conserver',
            'status' => 'expired',
            'is_demo' => true,
            'demo_expires_at' => now()->subDay(),
            'demo_claim_token_hash' => hash('sha256', $token),
        ]);
        $demoAdmin = $this->user('demo-admin');
        $demoTenant->users()->attach($demoAdmin->id, [
            'role_key' => 'admin',
            'status' => 'active',
        ]);
        Schema::create('bandes', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id');
            $table->string('nom');
            $table->timestamps();
        });
        DB::table('bandes')->insert([
            ['tenant_id' => $demoTenant->id, 'nom' => 'Bande chair', 'created_at' => now(), 'updated_at' => now()],
            ['tenant_id' => $demoTenant->id, 'nom' => 'Bande ponte', 'created_at' => now(), 'updated_at' => now()],
        ]);

        $this->actingAs($demoAdmin)
            ->withSession([
                'demo_workspace_tenant_id' => $demoTenant->id,
                'demo_workspace_user_id' => $demoAdmin->id,
                'demo_workspace_access_token' => $token,
            ])
            ->get('/demo/subscribe')
            ->assertRedirect(route('register', ['from_demo' => 1]));
        $this->assertGuest();

        $this->withCookie('farmapp_demo_recovery', $token)
            ->post('/register', [
                'plan_id' => $plan->id,
                'company_name' => 'Nouvelle entreprise vide',
                'name' => 'Nouvel administrateur',
                'email' => 'nouvelle-entreprise@example.test',
                'password' => 'secret1234',
                'password_confirmation' => 'secret1234',
                'billing_mode' => 'trial',
                'preserve_demo' => '1',
            ])
            ->assertRedirect(route('onboarding.index'));

        $newTenant = Tenant::where('name', 'Nouvelle entreprise vide')->firstOrFail();

        $this->assertFalse($newTenant->is_demo);
        $this->assertSame(2, Tenant::count());
        $this->assertTrue($demoTenant->fresh()->is_demo);
        $this->assertSame(2, DB::table('bandes')->where('tenant_id', $demoTenant->id)->count());
        $this->assertSame(0, DB::table('bandes')->where('tenant_id', $newTenant->id)->count());
    }

    public function test_expired_demo_member_is_sent_to_a_fresh_company_registration(): void
    {
        $token = str_repeat('d', 64);
        $tenant = Tenant::create([
            'name' => 'Démo expirée',
            'slug' => 'demo-expiree',
            'status' => 'expired',
            'is_demo' => true,
            'demo_expires_at' => now()->subDay(),
            'demo_claim_token_hash' => hash('sha256', $token),
        ]);
        $user = $this->user('expired-owner');
        $tenant->users()->attach($user->id, [
            'role_key' => 'admin',
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->withSession([
                'demo_workspace_tenant_id' => $tenant->id,
                'demo_workspace_user_id' => $user->id,
                'demo_workspace_claim_token' => $token,
            ])
            ->get('/home')
            ->assertRedirect(route('demo.subscribe'));

        $this->get('/demo/subscribe')
            ->assertRedirect(route('register', ['from_demo' => 1]));
        $this->assertGuest();
    }

    public function test_demo_login_connects_to_the_shared_demo_workspace(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme de démonstration',
            'slug' => 'demo',
            'status' => 'active',
            'is_demo' => true,
            'demo_expires_at' => now()->addYear(),
        ]);
        $user = $this->user('demo-admin');
        $tenant->users()->attach($user->id, [
            'role_key' => 'admin',
            'status' => 'active',
        ]);

        $workspaces = $this->mock(DemoWorkspaceService::class);
        $workspaces->shouldReceive('ensureSharedDemo')
            ->once()
            ->andReturn([
                'tenant' => $tenant,
                'user' => $user,
            ]);

        $this->get('/demo/login')
            ->assertRedirect(route('home'))
            ->assertSessionHas('demo_workspace_tenant_id', $tenant->id)
            ->assertSessionHas('demo_workspace_user_id', $user->id)
            ->assertSessionHas('demo_workspace_profile', 'admin');

        $this->assertAuthenticatedAs($user);
    }

    public function test_regular_authenticated_users_cannot_be_switched_into_a_demo_account(): void
    {
        $user = $this->user('regular-owner');

        $this->actingAs($user)
            ->get('/demo/login')
            ->assertRedirect(route('home'));

        $this->assertAuthenticatedAs($user);
    }

    private function createExpiredTrialTenant(string $prefix): array
    {
        $trialPlan = Plan::create([
            'code' => $prefix.'-trial',
            'name' => 'Essai actuel',
            'price' => 1000,
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'features' => ['elevage' => true],
            'is_active' => true,
        ]);
        $paidPlan = Plan::create([
            'code' => $prefix.'-paid',
            'name' => 'Forfait Pro',
            'price' => 5000,
            'currency' => 'XAF',
            'billing_interval' => 'annually',
            'features' => ['elevage' => true, 'commerce' => true],
            'is_active' => true,
        ]);
        $tenant = Tenant::create([
            'name' => ucfirst($prefix),
            'slug' => $prefix,
            'status' => 'active',
        ]);
        $tenant->domains()->create([
            'domain' => $prefix.'.example.test',
            'verified_at' => now(),
        ]);
        $tenant->subscriptions()->create([
            'plan_id' => $trialPlan->id,
            'status' => 'trialing',
            'trial_ends_at' => now()->subMinute(),
        ]);
        $admin = $this->user($prefix.'-admin');
        $tenant->users()->attach($admin->id, [
            'role_key' => 'admin',
            'status' => 'active',
        ]);

        return [$tenant, $admin, $paidPlan];
    }

    public function test_new_tenant_admin_can_follow_and_resume_the_interactive_onboarding(): void
    {
        [$tenant, $admin] = $this->createExpiredTrialTenant('ferme-guide');
        $this->mock(AlerteService::class)
            ->shouldReceive('count')
            ->andReturn(0);
        Schema::create('logo', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->nullable();
            $table->string('path')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        $host = 'http://'.$tenant->slug.'.example.test';

        $this->actingAs($admin)
            ->get($host.'/guide')
            ->assertOk()
            ->assertSeeText('Découvrez FarmApp à votre rythme')
            ->assertSeeText('Vos opérations réelles restent dans votre espace habituel.');

        $this->get($host.'/guide/exemple/elevage')
            ->assertOk()
            ->assertSeeText('500 poussins')
            ->assertSeeText('ne crée ni ne modifie aucune donnée');

        foreach (['depenses', 'stock', 'ventes', 'tresorerie'] as $module) {
            $this->get($host.'/guide/exemple/'.$module)->assertOk();
        }

        $this->assertSame(0, DB::table('tenant_onboarding_progress')->count());

        $this->postJson($host.'/guide/progression/scenario-elevage')
            ->assertOk()
            ->assertJsonPath('completed', true);

        $this->get($host.'/guide')
            ->assertOk()
            ->assertSeeText('1 parcours terminé(s)');

        $this->assertDatabaseHas('tenant_onboarding_progress', [
            'tenant_id' => $tenant->id,
            'user_id' => $admin->id,
            'guide_key' => 'scenario-elevage',
        ]);
    }

    public function test_onboarding_progress_is_scoped_to_the_tenant_membership(): void
    {
        [$tenant, $admin] = $this->createExpiredTrialTenant('ferme-guide-prive');
        [$otherTenant] = $this->createExpiredTrialTenant('autre-guide-prive');

        $this->actingAs($admin)
            ->postJson('http://'.$tenant->slug.'.example.test/guide/progression/welcome')
            ->assertOk();

        $this->assertDatabaseHas('tenant_onboarding_progress', [
            'tenant_id' => $tenant->id,
            'user_id' => $admin->id,
            'guide_key' => 'welcome',
        ]);
        $this->assertDatabaseMissing('tenant_onboarding_progress', [
            'tenant_id' => $otherTenant->id,
            'user_id' => $admin->id,
            'guide_key' => 'welcome',
        ]);
    }

    public function test_tenant_admin_can_subscribe_after_trial_and_keep_the_same_tenant(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-en-essai');

        $mamoni = $this->mock(MamoniService::class);
        $mamoni->shouldReceive('initPayment')->once()->andReturn([
            'ok' => true,
            'data' => [
                'gateway_reference' => 'GATEWAY-SUB-123',
                'payment_provider_slug' => 'orange-money',
            ],
        ]);
        $mamoni->shouldReceive('verify')->once()->andReturn([
            'final' => true,
            'payment_status' => 'successful',
            'data' => [
                'status' => 'successful',
                'receipt' => ['receipt_url' => 'https://receipt.example.test/123'],
            ],
        ]);

        $host = 'http://'.$tenant->slug.'.example.test';
        $response = $this->actingAs($admin)
            ->postJson($host.'/abonnement/paiement', [
                'plan_id' => $paidPlan->id,
                'phone_number' => '690000000',
            ])
            ->assertOk()
            ->assertJsonPath('ok', true);
        $transaction = PaymentTransaction::findOrFail($response->json('transaction_id'));
        $this->assertSame((string) $tenant->id, (string) $transaction->payable->tenant_id);

        $this->postJson($host.'/abonnement/paiement/'.$transaction->id.'/statut')
            ->assertOk()
            ->assertJsonPath('payment_status', 'successful');
        $this->postJson($host.'/abonnement/paiement/'.$transaction->id.'/statut')
            ->assertOk()
            ->assertJsonPath('payment_status', 'successful');

        $tenant->refresh();
        $this->assertSame(1, Tenant::count());
        $this->assertSame('active', $tenant->activeSubscription()->status);
        $this->assertSame($paidPlan->id, $tenant->activeSubscription()->plan_id);
        $this->assertSame(1, $tenant->subscriptions()->where('status', 'active')->count());
        $this->assertSame(1, $tenant->subscriptions()->where('status', 'canceled')->count());
    }

    public function test_trial_subscription_recovery_is_closed_after_thirty_days(): void
    {
        [$tenant, $admin] = $this->createExpiredTrialTenant('ferme-hors-delai');
        $trial = $tenant->subscriptions()->where('status', 'trialing');
        $trial->update([
            'trial_ends_at' => now()->subDays(30),
        ]);
        $this->assertNotNull($tenant->fresh()->recoverableTrialSubscription());

        $trial->update([
            'trial_ends_at' => now()->subDays(31),
        ]);

        $this->assertNotNull($tenant->fresh()->expiredTrialSubscription());
        $this->assertNull($tenant->fresh()->recoverableTrialSubscription());

        $this->actingAs($admin)
            ->get('http://'.$tenant->slug.'.example.test/abonnement')
            ->assertNotFound();
    }

    public function test_pending_trial_payment_started_within_thirty_days_can_complete_after_cutoff(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-paiement-limite');
        $mamoni = $this->mock(MamoniService::class);
        $mamoni->shouldReceive('initPayment')
            ->once()
            ->andReturn(['ok' => true, 'data' => ['gateway_reference' => 'GATEWAY-LATE']]);
        $mamoni->shouldReceive('verify')
            ->once()
            ->andReturn([
                'final' => true,
                'payment_status' => 'successful',
                'data' => ['status' => 'successful'],
            ]);

        $host = 'http://'.$tenant->slug.'.example.test';
        $response = $this->actingAs($admin)
            ->postJson($host.'/abonnement/paiement', [
                'plan_id' => $paidPlan->id,
                'phone_number' => '690000000',
            ])
            ->assertOk();
        $transaction = PaymentTransaction::findOrFail($response->json('transaction_id'));

        $this->travel(31)->days();
        $this->postJson($host.'/abonnement/paiement/'.$transaction->id.'/statut')
            ->assertOk()
            ->assertJsonPath('payment_status', 'successful');
        $this->travelBack();

        $this->assertSame('active', $tenant->fresh()->activeSubscription()->status);
    }

    public function test_free_plan_reactivates_an_expired_trial_without_a_payment(): void
    {
        [$tenant, $admin] = $this->createExpiredTrialTenant('ferme-gratuite');
        $freePlan = Plan::create([
            'code' => 'free-next',
            'name' => 'Forfait gratuit',
            'price' => 0,
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'features' => ['elevage' => true],
            'is_active' => true,
        ]);

        $this->actingAs($admin)
            ->postJson('http://'.$tenant->slug.'.example.test/abonnement/paiement', [
                'plan_id' => $freePlan->id,
            ])
            ->assertOk()
            ->assertJsonPath('activated', true);

        $this->assertSame(1, Tenant::count());
        $this->assertSame($freePlan->id, $tenant->fresh()->activeSubscription()->plan_id);
        $this->assertSame(0, PaymentTransaction::count());
    }

    public function test_failed_payment_initiation_cancels_the_pending_subscription(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-initiation-echec');

        $this->mock(MamoniService::class)
            ->shouldReceive('initPayment')
            ->once()
            ->andReturn(['ok' => false, 'message' => 'Paiement indisponible']);

        $this->actingAs($admin)
            ->postJson('http://'.$tenant->slug.'.example.test/abonnement/paiement', [
                'plan_id' => $paidPlan->id,
                'phone_number' => '690000000',
            ])
            ->assertStatus(422)
            ->assertJsonPath('ok', false);

        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'status' => 'canceled',
        ]);
        $this->assertSame(0, PaymentTransaction::count());
    }

    public function test_unavailable_mamoni_cancels_the_pending_subscription(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-mamoni-indisponible');
        $this->mock(MamoniService::class)
            ->shouldReceive('initPayment')
            ->once()
            ->andThrow(new ConnectionException('network unavailable'));

        $this->actingAs($admin)
            ->postJson('http://'.$tenant->slug.'.example.test/abonnement/paiement', [
                'plan_id' => $paidPlan->id,
                'phone_number' => '690000000',
            ])
            ->assertStatus(503)
            ->assertJsonPath('ok', false);

        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'status' => 'canceled',
        ]);
        $this->assertSame(0, PaymentTransaction::count());
    }

    public function test_repeated_subscription_initiation_reuses_the_existing_pending_payment(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-paiement-en-attente');
        $this->mock(MamoniService::class)
            ->shouldReceive('initPayment')
            ->once()
            ->andReturn([
                'ok' => true,
                'data' => ['gateway_reference' => 'GATEWAY-REUSED'],
            ]);

        $host = 'http://'.$tenant->slug.'.example.test';
        $payload = ['plan_id' => $paidPlan->id, 'phone_number' => '690000000'];
        $first = $this->actingAs($admin)
            ->postJson($host.'/abonnement/paiement', $payload)
            ->assertOk();
        $second = $this->postJson($host.'/abonnement/paiement', $payload)
            ->assertOk()
            ->assertJsonPath('pending', true);

        $this->assertSame($first->json('transaction_id'), $second->json('transaction_id'));
        $this->assertSame(1, PaymentTransaction::count());
        $this->assertSame(1, $tenant->subscriptions()->where('status', 'pending')->count());
    }

    public function test_payment_polling_keeps_the_subscription_pending_when_mamoni_is_unavailable(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-polling-interrompu');
        $mamoni = $this->mock(MamoniService::class);
        $mamoni->shouldReceive('initPayment')->once()->andReturn([
            'ok' => true,
            'data' => ['gateway_reference' => 'GATEWAY-PENDING'],
        ]);
        $mamoni->shouldReceive('verify')
            ->once()
            ->andThrow(new ConnectionException('network unavailable'));

        $host = 'http://'.$tenant->slug.'.example.test';
        $initiation = $this->actingAs($admin)
            ->postJson($host.'/abonnement/paiement', [
                'plan_id' => $paidPlan->id,
                'phone_number' => '690000000',
            ])
            ->assertOk();
        $transactionId = $initiation->json('transaction_id');

        $this->postJson($host.'/abonnement/paiement/'.$transactionId.'/statut')
            ->assertStatus(503)
            ->assertJsonPath('final', false)
            ->assertJsonPath('payment_status', 'pending');

        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transactionId,
            'status' => 'pending',
        ]);
        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'status' => 'pending',
        ]);
    }

    public function test_tenant_admin_cannot_check_another_tenants_payment(): void
    {
        [$ownerTenant, , $ownerPlan] = $this->createExpiredTrialTenant('ferme-paiement-prive');
        [$otherTenant, $otherAdmin] = $this->createExpiredTrialTenant('ferme-autre-client');
        $subscription = $ownerTenant->subscriptions()->create([
            'plan_id' => $ownerPlan->id,
            'status' => 'pending',
        ]);
        $transaction = $subscription->paymentTransactions()->create([
            'amount' => 5000,
            'phone_number' => '237690000000',
            'external_reference' => 'SUB-PRIVATE-123',
            'gateway_reference' => 'GATEWAY-PRIVATE',
            'status' => 'pending',
        ]);

        $this->mock(MamoniService::class)
            ->shouldNotReceive('verify');

        $this->actingAs($otherAdmin)
            ->postJson('http://'.$otherTenant->slug.'.example.test/abonnement/paiement/'.$transaction->id.'/statut')
            ->assertNotFound();

        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transaction->id,
            'status' => 'pending',
        ]);
    }

    public function test_failed_payment_cancels_the_pending_subscription(): void
    {
        [$tenant, $admin, $paidPlan] = $this->createExpiredTrialTenant('ferme-paiement-echec');
        $mamoni = $this->mock(MamoniService::class);
        $mamoni->shouldReceive('initPayment')->once()->andReturn([
            'ok' => true,
            'data' => ['gateway_reference' => 'GATEWAY-FAILED'],
        ]);
        $mamoni->shouldReceive('verify')->once()->andReturn([
            'final' => true,
            'payment_status' => 'failed',
            'data' => ['status' => 'failed'],
        ]);

        $host = 'http://'.$tenant->slug.'.example.test';
        $initiation = $this->actingAs($admin)
            ->postJson($host.'/abonnement/paiement', [
                'plan_id' => $paidPlan->id,
                'phone_number' => '690000000',
            ])
            ->assertOk();
        $transactionId = $initiation->json('transaction_id');

        $this->postJson($host.'/abonnement/paiement/'.$transactionId.'/statut')
            ->assertOk()
            ->assertJsonPath('payment_status', 'failed');

        $this->assertDatabaseHas('payment_transactions', [
            'id' => $transactionId,
            'status' => 'failed',
        ]);
        $this->assertDatabaseHas('subscriptions', [
            'tenant_id' => $tenant->id,
            'status' => 'canceled',
        ]);
    }

    public function test_successful_registration_payment_cannot_be_reused(): void
    {
        $plan = Plan::create([
            'code' => 'paid',
            'name' => 'Paid',
            'price' => 1000,
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'is_active' => true,
        ]);
        $token = 'registration-secret-token';
        $transactionId = DB::table('payment_transactions')->insertGetId([
            'amount' => 1000,
            'phone_number' => '237690000000',
            'external_reference' => 'REG-TEST-1',
            'status' => 'successful',
            'registration_token_hash' => hash('sha256', $token),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->post('/register', [
            'plan_id' => $plan->id,
            'company_name' => 'Ferme payée',
            'name' => 'Jean Dupont',
            'email' => 'jean-paid@example.test',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'billing_mode' => 'payment',
            'payment_transaction_id' => $transactionId,
            'payment_registration_token' => $token,
        ])->assertRedirect(route('onboarding.index'));

        Auth::logout();

        $this->from('/register')->post('/register', [
            'plan_id' => $plan->id,
            'company_name' => 'Seconde ferme',
            'name' => 'Marie Dupont',
            'email' => 'marie-paid@example.test',
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
            'billing_mode' => 'payment',
            'payment_transaction_id' => $transactionId,
            'payment_registration_token' => $token,
        ])->assertSessionHasErrors('payment');

        $this->assertSame(1, Tenant::count());
    }

    public function test_registration_payment_status_requires_the_secret_token(): void
    {
        $token = 'registration-secret-token';
        $transactionId = DB::table('payment_transactions')->insertGetId([
            'amount' => 1000,
            'phone_number' => '237690000000',
            'external_reference' => 'REG-TEST-2',
            'status' => 'pending',
            'registration_token_hash' => hash('sha256', $token),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->postJson('/register/pay/status/'.$transactionId, ['registration_token' => 'wrong-token'])
            ->assertNotFound();

        $this->postJson('/register/pay/status/'.$transactionId, ['registration_token' => $token])
            ->assertOk()
            ->assertJson(['final' => true, 'payment_status' => 'pending']);
    }

    public function test_role_listing_counts_users(): void
    {
        $role = Typeusers::firstOrCreate(['libelle' => 'superadmin']);
        $this->user('superadmin');

        $roles = Typeusers::withCount('users')->get();

        $this->assertSame(1, $roles->firstWhere('libelle', 'superadmin')->users_count);
    }

    public function test_toggle_permission_endpoint_grants_and_revokes(): void
    {
        $superadmin = $this->user('superadmin');
        $agentRole = Typeusers::create(['libelle' => 'agent']);

        $this->actingAs($superadmin)
            ->postJson('/admin/roles/'.$agentRole->id.'/toggle-permission', ['permission' => 'achat.view'])
            ->assertOk()
            ->assertJson(['ok' => true, 'granted' => true]);

        $this->assertTrue($agentRole->permissions()->where('name', 'achat.view')->exists());

        $this->actingAs($superadmin)
            ->postJson('/admin/roles/'.$agentRole->id.'/toggle-permission', ['permission' => 'achat.view'])
            ->assertOk()
            ->assertJson(['ok' => true, 'granted' => false]);

        $this->assertFalse($agentRole->permissions()->where('name', 'achat.view')->exists());
    }

    public function test_roles_index_groups_permissions_by_string_keys(): void
    {
        $this->user('superadmin');

        $view = (new RoleController)->index();

        $groups = $view->getData()['groups'];

        $this->assertNotEmpty($groups);

        foreach ($groups as $group => $permissions) {
            foreach ($permissions as $name => $meta) {
                $this->assertIsString($name, 'La clé de permission doit être une chaîne (ex: achat.view), pas un index numérique.');
                $this->assertArrayHasKey($name, config('permissions.permissions'), 'Clé inconnue: '.$name);
            }
        }
    }
}
