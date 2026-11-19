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
        Schema::dropIfExists('produits');
        Schema::dropIfExists('clients');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_domains');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('plans');
        Schema::dropIfExists('tenants');
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

    public function test_client_can_view_catalogue(): void
    {
        $user = $this->clientUser();
        $tenant = $this->tenant();

        app(TenantContext::class)->set($tenant);
        Client::create(['nom' => 'Client Test', 'id_user' => $user->id]);
        Produit::create(['nom' => 'Poulet entier', 'prix_unitaire' => 3500, 'statut' => 1]);
        app(TenantContext::class)->clear();

        $this->actingAs($user)
            ->get(route('client.catalogue'))
            ->assertOk()
            ->assertSee('Poulet entier');
    }

    public function test_client_can_place_order(): void
    {
        $user = $this->clientUser();
        $tenant = $this->tenant();

        app(TenantContext::class)->set($tenant);
        $client = Client::create(['nom' => 'Client Test', 'id_user' => $user->id]);
        $produit = Produit::create(['nom' => 'Poulet entier', 'prix_unitaire' => 3500, 'statut' => 1]);
        app(TenantContext::class)->clear();

        $this->actingAs($user)
            ->post(route('client.commande.store'), [
                'quantites' => [$produit->id => 2],
            ])
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

        app(TenantContext::class)->set($tenant);
        $clientA = Client::create(['nom' => 'Client A', 'id_user' => $userA->id]);

        $userB = User::create([
            'name' => 'Client B',
            'email' => 'clientb@example.test',
            'password' => Hash::make('password'),
            'id_type_user' => Typeusers::firstOrCreate(['libelle' => 'client'])->id,
        ]);
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
