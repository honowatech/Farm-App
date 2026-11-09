<?php

namespace Tests\Feature;

use App\Models\Typeusers;
use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
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
    }

    protected function role(string $libelle): Typeusers
    {
        return Typeusers::create(['libelle' => $libelle]);
    }

    protected function user(string $role, ?int $idTypeUser = null): User
    {
        return User::create([
            'name' => ucfirst($role),
            'email' => $role . '@example.com',
            'password' => bcrypt('password'),
            'id_type_user' => $idTypeUser ?? $this->role($role)->id,
        ]);
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
        $this->actingAs($this->user('agent'))->get('/test-nutrition')->assertOk();
    }

    public function test_agent_is_denied_non_whitelisted_route(): void
    {
        $this->actingAs($this->user('agent'))->get('/test-vente')->assertRedirect(route('home'));
    }

    public function test_user_without_role_is_denied(): void
    {
        $this->actingAs($this->user('orphan', null))->get('/test-admin')->assertStatus(403);
    }
}
