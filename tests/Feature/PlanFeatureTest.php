<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use App\Support\FeatureGate;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class PlanFeatureTest extends TestCase
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

        Route::middleware(['tenant.resolve', 'tenant.member', 'feature'])
            ->get('/test-feature-nutrition', fn () => response('nutrition'))
            ->name('nutrition.index');
        Route::middleware(['tenant.resolve', 'tenant.member', 'feature'])
            ->get('/test-feature-sales', fn () => response('sales'))
            ->name('vente.index');
    }

    private function plan(array $features, array $limits = []): Plan
    {
        return Plan::create([
            'code' => 'plan-'.uniqid(),
            'name' => 'Plan',
            'price' => 10000,
            'currency' => 'XAF',
            'billing_interval' => 'monthly',
            'features' => $features,
            'limits' => $limits,
            'is_active' => true,
        ]);
    }

    private function tenantWithActiveSubscription(Plan $plan, ?User $user = null): Tenant
    {
        $tenant = Tenant::create([
            'name' => 'Ferme '.uniqid(),
            'slug' => 'ferme-'.uniqid(),
            'status' => 'active',
        ]);

        $tenant->domains()->create([
            'domain' => $tenant->slug.'.example.test',
            'verified_at' => now(),
        ]);

        $tenant->subscriptions()->create([
            'plan_id' => $plan->id,
            'status' => 'active',
            'starts_at' => now(),
            'current_period_starts_at' => now(),
            'current_period_ends_at' => now()->addMonth(),
        ]);

        if ($user) {
            $tenant->users()->attach($user->id, [
                'role_key' => 'admin',
                'status' => 'active',
                'accepted_at' => now(),
            ]);
        }

        return $tenant;
    }

    private function userOfType(string $profile): User
    {
        return User::create([
            'name' => ucfirst($profile).' '.uniqid(),
            'email' => strtolower($profile).'-'.uniqid().'@example.test',
            'password' => 'secret',
            'id_type_user' => Typeusers::firstOrCreate(['libelle' => $profile])->id,
        ]);
    }

    public function test_plan_has_feature_reads_associative_format(): void
    {
        $plan = $this->plan([
            'elevage' => true,
            'commerce' => false,
        ]);

        $this->assertTrue($plan->hasFeature('elevage'));
        $this->assertFalse($plan->hasFeature('commerce'));
        $this->assertFalse($plan->hasFeature('finances'));
    }

    public function test_tenant_has_feature_delegates_to_active_subscription(): void
    {
        $plan = $this->plan(['elevage' => true, 'commerce' => false], ['utilisateurs' => 7]);
        $tenant = $this->tenantWithActiveSubscription($plan);

        $this->assertTrue($tenant->hasFeature('elevage'));
        $this->assertFalse($tenant->hasFeature('commerce'));
        $this->assertSame(7, $tenant->limit('utilisateurs', 999));
        $this->assertSame(999, $tenant->limit('inexistante', 999));
    }

    public function test_feature_gate_maps_routes_to_features(): void
    {
        $this->assertSame('elevage', FeatureGate::forRoute('nutrition.index'));
        $this->assertSame('commerce', FeatureGate::forRoute('vente.index'));
        $this->assertNull(FeatureGate::forRoute('home'));
        $this->assertNull(FeatureGate::forRoute(null));
    }

    public function test_plan_supports_nested_profile_quotas(): void
    {
        $plan = $this->plan([], ['profiles' => ['agent' => 3, 'commercial' => 0]]);

        $this->assertSame(3, $plan->limit('profiles.agent', 0));
        $this->assertSame(0, $plan->limit('profiles.commercial', 99));
        $this->assertSame(99, $plan->limit('profiles.inexistant', 99));
    }

    public function test_feature_middleware_blocks_route_when_feature_missing(): void
    {
        $plan = $this->plan(['commerce' => true]); // pas d'élevage
        $user = User::create([
            'name' => 'Admin ferme',
            'email' => 'admin-ferme@example.test',
            'password' => 'secret',
            'id_type_user' => Typeusers::firstOrCreate(['libelle' => 'admin'])->id,
        ]);
        $tenant = $this->tenantWithActiveSubscription($plan, $user);

        $this->actingAs($user)
            ->get('http://'.$tenant->slug.'.example.test/test-feature-nutrition')
            ->assertRedirect(route('home'))
            ->assertSessionHas('error');

        $this->actingAs($user)
            ->get('http://'.$tenant->slug.'.example.test/test-feature-sales')
            ->assertOk()
            ->assertSeeText('sales');
    }

    public function test_feature_middleware_allows_route_when_feature_included(): void
    {
        $plan = $this->plan(['elevage' => true]);
        $user = User::create([
            'name' => 'Admin ferme',
            'email' => 'admin-elevage@example.test',
            'password' => 'secret',
            'id_type_user' => Typeusers::firstOrCreate(['libelle' => 'admin'])->id,
        ]);
        $tenant = $this->tenantWithActiveSubscription($plan, $user);

        $this->actingAs($user)
            ->get('http://'.$tenant->slug.'.example.test/test-feature-nutrition')
            ->assertOk()
            ->assertSeeText('nutrition');
    }

    public function test_profile_quota_blocks_creation_when_reached(): void
    {
        $plan = $this->plan([], ['profiles' => ['agent' => 2]]);
        $admin = $this->userOfType('admin');
        $tenant = $this->tenantWithActiveSubscription($plan, $admin);

        $tenant->users()->attach($this->userOfType('agent')->id, ['role_key' => 'agent', 'status' => 'active', 'accepted_at' => now()]);
        $tenant->users()->attach($this->userOfType('agent')->id, ['role_key' => 'agent', 'status' => 'active', 'accepted_at' => now()]);

        $this->actingAs($admin);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        app(\App\Support\TenantQuota::class)->assertProfileWithinLimit('agent');
    }

    public function test_profile_quota_allows_when_under_limit(): void
    {
        $plan = $this->plan([], ['profiles' => ['agent' => 5]]);
        $admin = $this->userOfType('admin');
        $tenant = $this->tenantWithActiveSubscription($plan, $admin);

        $tenant->users()->attach($this->userOfType('agent')->id, ['role_key' => 'agent', 'status' => 'active', 'accepted_at' => now()]);

        $this->actingAs($admin);

        app(\App\Support\TenantQuota::class)->assertProfileWithinLimit('agent');

        $this->addToAssertionCount(1);
    }
}
