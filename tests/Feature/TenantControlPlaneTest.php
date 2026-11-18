<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Subscription;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Database\QueryException;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class TenantControlPlaneTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_domains');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenants');

        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->foreignId('id_type_user')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('type_users')) {
            Schema::create('type_users', function (Blueprint $table) {
                $table->id();
                $table->string('libelle');
                $table->timestamps();
            });
        }

        $migration = require database_path('migrations/2026_11_16_150000_create_tenant_control_plane_tables.php');
        $migration->up();

        Route::middleware('tenant.resolve')
            ->get('/test-tenant-context', fn (TenantContext $context) => response($context->id()));
        Route::middleware(['tenant.resolve', 'tenant.member'])
            ->get('/test-tenant-membership', fn () => response('member'));
        Route::middleware(['tenant.resolve', 'tenant.member', 'agent_access'])
            ->get('/test-tenant-nutrition', fn () => response('nutrition'))
            ->name('nutrition.index');
        Route::middleware(['tenant.resolve', 'tenant.member', 'agent_access'])
            ->get('/test-tenant-sales', fn () => response('sales'))
            ->name('vente.index');
    }

    public function test_tenant_membership_keeps_the_role_and_status_per_company(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme Demo',
            'slug' => 'ferme-demo',
            'status' => 'active',
        ]);
        $user = User::create([
            'name' => 'Responsable',
            'email' => 'responsable@example.test',
            'password' => 'secret-password',
        ]);
        $otherTenant = Tenant::create([
            'name' => 'Ferme B',
            'slug' => 'ferme-b',
            'status' => 'active',
        ]);

        $tenant->users()->attach($user->id, [
            'role_key' => 'admin',
            'status' => 'active',
            'accepted_at' => now(),
        ]);
        $otherTenant->users()->attach($user->id, [
            'role_key' => 'gestionnaire',
            'status' => 'active',
        ]);

        $this->assertSame(26, strlen($tenant->id));
        $this->assertSame('admin', $user->tenants()->first()->pivot->role_key);
        $this->assertSame(
            'gestionnaire',
            $user->tenants()->where('tenants.id', $otherTenant->id)->first()->pivot->role_key
        );
        $this->assertSame('active', $tenant->users()->first()->pivot->status);
    }

    public function test_tenant_domain_must_be_unique_across_companies(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme A',
            'slug' => 'ferme-a',
        ]);
        $otherTenant = Tenant::create([
            'name' => 'Ferme B',
            'slug' => 'ferme-b',
        ]);

        $tenant->domains()->create([
            'domain' => 'FERME-A.EXAMPLE.TEST.',
            'is_primary' => true,
            'verified_at' => now(),
        ]);
        $this->assertSame('ferme-a.example.test', $tenant->domains()->first()->domain);

        $this->expectException(QueryException::class);

        $otherTenant->domains()->create([
            'domain' => 'ferme-a.example.test',
        ]);
    }

    public function test_subscription_relations_retain_plan_features_and_period_dates(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme A',
            'slug' => 'ferme-a',
        ]);
        $plan = Plan::create([
            'code' => 'starter',
            'name' => 'Starter',
            'price' => 10000,
            'currency' => 'XAF',
            'features' => ['nutrition' => true, 'payments' => false],
            'limits' => ['users' => 5],
        ]);
        $subscription = $tenant->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => 'trialing',
            'trial_ends_at' => now()->addDays(14),
            'current_period_starts_at' => now(),
        ]);

        $this->assertInstanceOf(Subscription::class, $tenant->subscriptions()->first());
        $this->assertSame($tenant->id, $subscription->tenant->id);
        $this->assertSame('starter', $subscription->plan->code);
        $this->assertTrue($subscription->plan->features['nutrition']);
        $this->assertFalse($subscription->plan->features['payments']);
        $this->assertSame(5, $subscription->plan->limits['users']);
        $this->assertNotNull($subscription->trial_ends_at);
    }

    public function test_verified_active_domain_resolves_tenant_only_for_the_request(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme A',
            'slug' => 'ferme-a',
            'status' => 'active',
        ]);
        $tenant->domains()->create([
            'domain' => 'ferme-a.example.test',
            'verified_at' => now(),
        ]);

        $this->get('http://FERME-A.EXAMPLE.TEST/test-tenant-context')
            ->assertOk()
            ->assertSeeText($tenant->id);

        $this->assertFalse(app(TenantContext::class)->hasTenant());
    }

    public function test_unknown_unverified_and_expired_demo_domains_are_not_resolved(): void
    {
        $tenant = Tenant::create([
            'name' => 'Démo expirée',
            'slug' => 'demo-expiree',
            'status' => 'active',
            'is_demo' => true,
            'demo_expires_at' => now()->subMinute(),
        ]);
        $tenant->domains()->create([
            'domain' => 'demo-expiree.example.test',
            'verified_at' => now(),
        ]);
        $unverifiedTenant = Tenant::create([
            'name' => 'Domaine non vérifié',
            'slug' => 'domaine-non-verifie',
            'status' => 'active',
        ]);
        $unverifiedTenant->domains()->create([
            'domain' => 'non-verifie.example.test',
        ]);

        $this->get('http://demo-expiree.example.test/test-tenant-context')
            ->assertNotFound();

        $this->get('http://non-verifie.example.test/test-tenant-context')
            ->assertNotFound();

        $this->get('http://inconnu.example.test/test-tenant-context')
            ->assertNotFound();

        $this->assertFalse(app(TenantContext::class)->hasTenant());
    }

    public function test_only_active_members_can_access_a_tenant_route(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme A',
            'slug' => 'ferme-a',
            'status' => 'active',
        ]);
        $tenant->domains()->create([
            'domain' => 'ferme-a.example.test',
            'verified_at' => now(),
        ]);
        $member = User::create([
            'name' => 'Membre',
            'email' => 'membre@example.test',
            'password' => 'secret-password',
        ]);
        $nonMember = User::create([
            'name' => 'Externe',
            'email' => 'externe@example.test',
            'password' => 'secret-password',
        ]);
        $inactiveMember = User::create([
            'name' => 'Ancien membre',
            'email' => 'ancien@example.test',
            'password' => 'secret-password',
        ]);
        $tenant->users()->attach($member->id, [
            'role_key' => 'admin',
            'status' => 'active',
            'accepted_at' => now(),
        ]);
        $tenant->users()->attach($inactiveMember->id, [
            'role_key' => 'gestionnaire',
            'status' => 'suspended',
        ]);

        $this->actingAs($member)
            ->get('http://ferme-a.example.test/test-tenant-membership')
            ->assertOk()
            ->assertSeeText('member');

        $this->actingAs($nonMember)
            ->get('http://ferme-a.example.test/test-tenant-membership')
            ->assertForbidden();

        $this->actingAs($inactiveMember)
            ->get('http://ferme-a.example.test/test-tenant-membership')
            ->assertForbidden();

        $this->assertFalse(app(TenantContext::class)->hasTenant());
    }

    public function test_tenant_role_controls_permissions_without_using_the_global_user_role(): void
    {
        $tenant = Tenant::create([
            'name' => 'Ferme A',
            'slug' => 'ferme-a',
            'status' => 'active',
        ]);
        $tenant->domains()->create([
            'domain' => 'ferme-a.example.test',
            'verified_at' => now(),
        ]);
        $agent = User::create([
            'name' => 'Agent',
            'email' => 'agent-tenant@example.test',
            'password' => 'secret-password',
            'id_type_user' => Typeusers::create(['libelle' => 'admin'])->id,
        ]);
        $tenant->users()->attach($agent->id, [
            'role_key' => 'agent',
            'status' => 'active',
            'accepted_at' => now(),
        ]);

        $this->actingAs($agent)
            ->get('http://ferme-a.example.test/test-tenant-nutrition')
            ->assertOk()
            ->assertSeeText('nutrition');

        $this->actingAs($agent)
            ->getJson('http://ferme-a.example.test/test-tenant-sales')
            ->assertForbidden();
    }
}
