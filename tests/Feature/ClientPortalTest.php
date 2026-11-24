<?php

namespace Tests\Feature;

use App\Models\Client;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\Tenant;
use App\Models\Typeusers;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class ClientPortalTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createMinimalSchema();
    }

    protected function createMinimalSchema(): void
    {
        Schema::dropIfExists('ligne_commandes');
        Schema::dropIfExists('commandes');
        Schema::dropIfExists('factures');
        Schema::dropIfExists('produits');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_domains');
        Schema::dropIfExists('subscriptions');
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

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->foreignId('id_type_user')->nullable();
            $table->boolean('statut')->default(1);
            $table->timestamps();
        });

        $migration = require database_path('migrations/2026_11_16_150000_create_tenant_control_plane_tables.php');
        $migration->up();

        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->string('telephone')->nullable();
            $table->string('adresse')->nullable();
            $table->string('email')->nullable();
            $table->unsignedBigInteger('id_user')->nullable();
            $table->string('tenant_id', 26)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('produits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom');
            $table->string('description')->nullable();
            $table->decimal('prix_unitaire', 12, 2)->default(0);
            $table->boolean('statut')->default(1);
            $table->string('tenant_id', 26)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule', 40)->unique();
            $table->unsignedInteger('id_client');
            $table->string('statut', 30)->default('brouillon');
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->unsignedBigInteger('id_saver')->nullable();
            $table->string('date_commande')->nullable();
            $table->string('tenant_id', 26)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('factures', function (Blueprint $table) {
            $table->increments('id');
            $table->string('matricule', 80)->nullable();
            $table->unsignedInteger('id_client');
            $table->date('date_facturation')->nullable();
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);
            $table->string('tenant_id', 26)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('ligne_commandes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('id_commande');
            $table->unsignedInteger('id_produit');
            $table->unsignedInteger('qte');
            $table->decimal('prix_unitaire', 12, 2);
            $table->decimal('total_ligne', 12, 2);
            $table->string('tenant_id', 26)->nullable()->index();
            $table->timestamps();
        });

        Schema::create('logo', function (Blueprint $table) {
            $table->id();
            $table->string('path')->nullable();
            $table->string('tenant_id', 26)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    private function clientUser(): User
    {
        $type = Typeusers::firstOrCreate(['libelle' => 'client']);

        return User::create([
            'name' => 'Client Test',
            'email' => 'client@example.test',
            'password' => Hash::make('password'),
            'id_type_user' => $type->id,
        ]);
    }

    private function tenant(): Tenant
    {
        return Tenant::create(['name' => 'Ferme Test', 'slug' => 'ferme-test', 'status' => 'active']);
    }

    private function addClientMembership(Tenant $tenant, User $user): void
    {
        $tenant->users()->attach($user->id, [
            'role_key' => 'client',
            'status' => 'active',
            'accepted_at' => now(),
        ]);
    }

    public function test_client_can_view_catalogue(): void
    {
        $user = $this->clientUser();
        $tenant = $this->tenant();
        $this->addClientMembership($tenant, $user);

        app(TenantContext::class)->set($tenant);
        Client::create(['nom' => 'Client Test', 'id_user' => $user->id]);
        Produit::create(['nom' => 'Poulet entier', 'prix_unitaire' => 3500, 'statut' => 1]);
        app(TenantContext::class)->clear();

        $this->actingAs($user)
            ->get(route('client.catalogue'))
            ->assertOk()
            ->assertSee('Poulet entier');
    }

    public function test_demo_admin_switching_to_client_profile_can_access_dashboard(): void
    {
        $tenant = Tenant::create([
            'name' => 'Espace démo',
            'slug' => 'espace-demo',
            'status' => 'active',
            'is_demo' => true,
            'demo_expires_at' => now()->addDay(),
        ]);
        $tenant->domains()->create([
            'domain' => 'espace-demo.example.test',
            'verified_at' => now(),
        ]);

        $admin = User::create([
            'name' => 'Admin démo',
            'email' => 'demo-portal-admin@example.test',
            'password' => Hash::make('password'),
        ]);
        $clientUser = User::create([
            'name' => 'Client démo',
            'email' => 'client@espace-demo.test',
            'password' => Hash::make('password'),
        ]);
        $tenant->users()->attach($admin->id, [
            'role_key' => 'admin',
            'status' => 'active',
        ]);
        $tenant->users()->attach($clientUser->id, [
            'role_key' => 'client',
            'status' => 'active',
        ]);

        app(TenantContext::class)->set($tenant);
        Client::create(['nom' => 'Client Démo', 'id_user' => $clientUser->id]);
        app(TenantContext::class)->clear();

        $this->actingAs($admin)
            ->withSession([
                'demo_workspace_tenant_id' => $tenant->id,
                'demo_workspace_user_id' => $admin->id,
                'demo_workspace_profile' => 'client',
            ])
            ->get(route('client.dashboard'))
            ->assertOk()
            ->assertSee('Mes dernières commandes');

        // La page profil s'affiche en mode démo, mais la modification est désactivée.
        $this->actingAs($admin)
            ->withSession([
                'demo_workspace_tenant_id' => $tenant->id,
                'demo_workspace_user_id' => $admin->id,
                'demo_workspace_profile' => 'client',
            ])
            ->get(route('client.profil'))
            ->assertOk()
            ->assertSee('modification du profil est désactivée');

        $this->actingAs($admin)
            ->withSession([
                'demo_workspace_tenant_id' => $tenant->id,
                'demo_workspace_user_id' => $admin->id,
                'demo_workspace_profile' => 'client',
            ])
            ->post(route('client.profil.update'), [
                'name' => 'Nom modifié',
                'email' => 'demo-portal-admin@example.test',
            ])
            ->assertForbidden();
    }

    public function test_nonmember_client_cannot_access_the_portal(): void
    {
        $user = $this->clientUser();
        $this->tenant();

        $this->actingAs($user)
            ->get(route('client.catalogue'))
            ->assertNotFound();
    }

    public function test_client_can_place_order(): void
    {
        $user = $this->clientUser();
        $tenant = $this->tenant();
        $this->addClientMembership($tenant, $user);

        app(TenantContext::class)->set($tenant);
        $client = Client::create(['nom' => 'Client Test', 'id_user' => $user->id]);
        $produit = Produit::create(['nom' => 'Poulet entier', 'prix_unitaire' => 3500, 'statut' => 1]);
        app(TenantContext::class)->clear();

        $this->actingAs($user)
            ->withSession(['cart' => [$produit->id => 2]])
            ->post(route('client.commande.store'))
            ->assertRedirect();

        $commande = Commande::where('id_client', $client->id)->first();

        $this->assertNotNull($commande);
        $this->assertSame(Commande::STATUT_CONFIRMEE, $commande->statut);
        $this->assertEquals(7000, (float) $commande->total_ttc);
        $this->assertSame(1, $commande->lignes()->count());
    }

    public function test_client_cannot_view_another_clients_order(): void
    {
        $userA = $this->clientUser();
        $tenant = $this->tenant();
        $this->addClientMembership($tenant, $userA);

        app(TenantContext::class)->set($tenant);
        $clientA = Client::create(['nom' => 'Client A', 'id_user' => $userA->id]);

        $userB = User::create([
            'name' => 'Client B',
            'email' => 'clientb@example.test',
            'password' => Hash::make('password'),
            'id_type_user' => Typeusers::firstOrCreate(['libelle' => 'client'])->id,
        ]);
        $this->addClientMembership($tenant, $userB);
        $clientB = Client::create(['nom' => 'Client B', 'id_user' => $userB->id]);

        $commandeB = Commande::create([
            'matricule' => 'CMD-TEST-1',
            'id_client' => $clientB->id,
            'statut' => Commande::STATUT_CONFIRMEE,
            'total_ttc' => 1000,
        ]);
        app(TenantContext::class)->clear();

        $this->actingAs($userA)
            ->get(route('client.commandes.show', $commandeB))
            ->assertForbidden();
    }
}
