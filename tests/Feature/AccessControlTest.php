<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createMinimalSchema();

        // Routes de test protégées par les middlewares réels
        Route::middleware('role:admin')->get('/test-admin', fn () => response('ok'))->name('test.admin');
        Route::middleware('agent_access')->get('/test-nutrition', fn () => response('ok'))->name('nutrition.index');
        Route::middleware('agent_access')->get('/test-vente', fn () => response('ok'))->name('vente.index');
    }

    protected function createMinimalSchema(): void
    {
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
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreignId('id_type_user')->nullable();
            $table->boolean('statut')->default(1);
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
    }

    protected function role(string $libelle): Typeusers
    {
        return Typeusers::create(['libelle' => $libelle]);
    }

    protected function user(string $role, ?int $idTypeUser = null): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $role.'@example.com',
            'password' => bcrypt('password'),
            'id_type_user' => $idTypeUser ?? $this->role($role)->id,
        ]);
    }

    protected function grant(Typeusers $role, string $permission): void
    {
        $perm = Permission::firstOrCreate(['name' => $permission]);
        $role->permissions()->syncWithoutDetaching([$perm->id]);
    }

    public function test_guest_is_redirected_to_login_on_admin_route(): void
    {
        $this->get('/test-admin')->assertRedirect(route('login'));
    }

    public function test_admin_can_access_role_admin_route(): void
    {
        $this->actingAs($this->user('admin'))->get('/test-admin')->assertOk();
    }

    public function test_agent_is_forbidden_on_role_admin_route(): void
    {
        $this->actingAs($this->user('agent'))->get('/test-admin')->assertStatus(403);
    }

    public function test_client_is_forbidden_on_role_admin_route(): void
    {
        $this->actingAs($this->user('client'))->get('/test-admin')->assertStatus(403);
    }

    public function test_agent_can_access_whitelisted_route(): void
    {
        $agentRole = $this->role('agent');
        $this->grant($agentRole, 'nutrition.view');

        $this->actingAs($this->user('agent', $agentRole->id))
            ->get('/test-nutrition')
            ->assertOk();
    }

    public function test_agent_is_denied_non_whitelisted_route(): void
    {
        $this->actingAs($this->user('agent'))
            ->get('/test-vente')
            ->assertRedirect(route('home'))
            ->assertSessionHas('error');
    }

    public function test_denied_access_redirects_back_to_previous_page_with_toast(): void
    {
        $this->actingAs($this->user('agent'))
            ->from('/nutrition')
            ->get('/test-vente')
            ->assertRedirect(url('/nutrition'))
            ->assertSessionHas('error');
    }

    public function test_denied_json_access_returns_403(): void
    {
        $this->actingAs($this->user('agent'))
            ->getJson('/test-vente')
            ->assertStatus(403)
            ->assertJsonStructure(['message']);
    }

    public function test_user_without_role_is_denied(): void
    {
        $this->actingAs($this->user('orphan', null))->get('/test-admin')->assertStatus(403);
    }
}
