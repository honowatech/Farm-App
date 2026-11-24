<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Support\TenantContext;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Modules\Depenses\Entities\Categorie;
use Modules\Depenses\Entities\Depense;
use Modules\Depenses\Http\Controllers\CategorieController;
use Modules\Depenses\Http\Controllers\DepensesController;
use Modules\GestionCaisse\Entities\Compte;
use Modules\GestionCaisse\Entities\Transaction;
use Tests\TestCase;

class ExpenseWorkflowTest extends TestCase
{
    private Tenant $tenant;

    private Categorie $category;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        Schema::disableForeignKeyConstraints();
        foreach ([
            'gestion_caisse_transactions',
            'gestion_caisse_comptes',
            'depenses',
            'categorie_depenses',
            'subscriptions',
            'tenant_user',
            'tenant_domains',
            'plans',
            'tenants',
            'users',
            'type_users',
        ] as $table) {
            Schema::dropIfExists($table);
        }
        Schema::enableForeignKeyConstraints();

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
            $table->rememberToken();
            $table->timestamps();
        });

        $migration = require database_path('migrations/2026_11_16_150000_create_tenant_control_plane_tables.php');
        $migration->up();

        Schema::create('categorie_depenses', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('type')->default('ponctuel');
            $table->string('frequence')->nullable();
            $table->string('description')->nullable();
            $table->decimal('prix_unitaire', 10, 2)->nullable();
            $table->string('tenant_id', 26)->nullable();
            $table->timestamps();
        });
        Schema::create('depenses', function (Blueprint $table) {
            $table->id();
            $table->softDeletes();
            $table->float('montant');
            $table->text('description');
            $table->string('motif_depense');
            $table->dateTime('date_depense');
            $table->unsignedBigInteger('id_saver');
            $table->unsignedBigInteger('id_categorie');
            $table->string('fournisseur')->nullable();
            $table->unsignedInteger('id_compte')->nullable();
            $table->string('tenant_id', 26)->nullable();
            $table->timestamps();
        });
        Schema::create('gestion_caisse_comptes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nom')->nullable();
            $table->integer('current_balance')->nullable();
            $table->string('tenant_id', 26)->nullable();
            $table->timestamps();
        });
        Schema::create('gestion_caisse_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('compte_id');
            $table->string('transaction_type');
            $table->decimal('montant', 10, 2);
            $table->date('transaction_date');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('id_depense')->nullable();
            $table->string('tenant_id', 26)->nullable();
            $table->timestamps();
        });

        $this->tenant = Tenant::create([
            'name' => 'Ferme test',
            'slug' => 'ferme-test',
            'status' => 'active',
        ]);
        app(TenantContext::class)->set($this->tenant);
        $this->user = User::create([
            'name' => 'Comptable',
            'email' => 'comptable@example.test',
            'password' => bcrypt('secret-password'),
        ]);
        Auth::login($this->user);
        $this->category = Categorie::create([
            'nom' => 'Alimentation',
            'type' => 'ponctuel',
        ]);
    }

    protected function tearDown(): void
    {
        app(TenantContext::class)->clear();
        Auth::logout();

        parent::tearDown();
    }

    public function test_expense_and_cash_transaction_are_created_atomically_with_a_real_reference(): void
    {
        $compte = Compte::create(['nom' => 'Caisse principale', 'current_balance' => 1000]);
        $response = app(DepensesController::class)->store($this->expenseRequest($compte->id, 600));

        $depense = Depense::firstOrFail();
        $transaction = Transaction::firstOrFail();

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(1, Depense::count());
        $this->assertSame(1, Transaction::count());
        $this->assertSame((int) $depense->id, (int) $transaction->id_depense);
        $this->assertSame(400, (int) $compte->fresh()->current_balance);
    }

    public function test_insufficient_cash_rolls_back_the_expense_and_keeps_the_balance_unchanged(): void
    {
        $compte = Compte::create(['nom' => 'Petite caisse', 'current_balance' => 50]);

        try {
            app(DepensesController::class)->store($this->expenseRequest($compte->id, 60));
            $this->fail('Une dépense supérieure au solde ne doit pas être enregistrée.');
        } catch (ValidationException $exception) {
            $this->assertArrayHasKey('id_caisse', $exception->errors());
        }

        $this->assertSame(0, Depense::count());
        $this->assertSame(0, Transaction::count());
        $this->assertSame(50, (int) $compte->fresh()->current_balance);
    }

    public function test_updating_and_deleting_an_expense_restore_and_reapply_the_cash_movement(): void
    {
        $compte = Compte::create(['nom' => 'Caisse principale', 'current_balance' => 1000]);
        app(DepensesController::class)->store($this->expenseRequest($compte->id, 600));
        $depense = Depense::firstOrFail();

        app(DepensesController::class)->update(
            $this->expenseRequest($compte->id, 200),
            $depense->id
        );

        $depense->refresh();
        $transaction = Transaction::firstOrFail();
        $this->assertSame(200.0, (float) $depense->montant);
        $this->assertSame((int) $depense->id, (int) $transaction->id_depense);
        $this->assertSame(800, (int) $compte->fresh()->current_balance);

        app(DepensesController::class)->destroy($depense->id);

        $this->assertSame(0, Depense::count());
        $this->assertSame(1, Depense::withTrashed()->count());
        $this->assertSame(0, Transaction::count());
        $this->assertSame(1000, (int) $compte->fresh()->current_balance);
    }

    public function test_one_off_categories_do_not_require_recurring_fields_and_duplicate_names_are_rejected(): void
    {
        $controller = app(CategorieController::class);
        $response = $controller->store(Request::create('/categorie/store', 'POST', [
            'id_type_depense' => 'ponctuel',
            'nom' => 'Transport',
        ]));

        $this->assertSame(302, $response->getStatusCode());
        $this->assertSame(2, Categorie::count());

        $duplicate = $controller->store(Request::create('/categorie/store', 'POST', [
            'id_type_depense' => 'ponctuel',
            'nom' => ' transport ',
        ]));

        $this->assertSame(302, $duplicate->getStatusCode());
        $this->assertSame(2, Categorie::count());
    }

    public function test_search_includes_the_selected_end_minute_and_excludes_later_expenses(): void
    {
        $date = now()->startOfDay();
        Depense::create([
            'montant' => 100,
            'description' => 'Dépense à inclure',
            'motif_depense' => 'Dans la minute sélectionnée',
            'date_depense' => $date->copy()->setTime(14, 30, 59),
            'id_saver' => $this->user->getKey(),
            'id_categorie' => $this->category->getKey(),
            'fournisseur' => 'autre',
        ]);
        Depense::create([
            'montant' => 100,
            'description' => 'Dépense à exclure',
            'motif_depense' => 'Après la minute sélectionnée',
            'date_depense' => $date->copy()->setTime(14, 31),
            'id_saver' => $this->user->getKey(),
            'id_categorie' => $this->category->getKey(),
            'fournisseur' => 'autre',
        ]);

        $content = app(DepensesController::class)->search(Request::create('/search-depenses', 'GET', [
            'date_fin' => $date->copy()->setTime(14, 30)->format('Y-m-d H:i'),
        ]));

        $this->assertStringContainsString('Dans la minute sélectionnée', $content);
        $this->assertStringNotContainsString('Après la minute sélectionnée', $content);
    }

    private function expenseRequest(int $compteId, int $amount): Request
    {
        return Request::create('/depenses/store', 'POST', [
            'id_type_depense' => $this->category->getKey(),
            'nature_depense' => 'Achat de provende',
            'prix_unitaire' => $amount,
            'date' => now()->toDateString(),
            'id_caisse' => $compteId,
        ]);
    }
}
