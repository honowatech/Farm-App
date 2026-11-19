<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class SuperAdminTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createMinimalSchema();

        \Illuminate\Support\Facades\Route::middleware('role:superadmin')
            ->get('/admin/dashboard', fn () => response('central'))
            ->name('admin.dashboard');
    }

    protected function createMinimalSchema(): void
    {
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_domains');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('permission_type_user');
        Schema::dropIfExists('permissions');
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

        $response->assertRedirect(route('home'));

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

        $view = (new \App\Http\Controllers\Admin\RoleController())->index();

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
