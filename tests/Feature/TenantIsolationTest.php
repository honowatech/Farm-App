<?php

namespace Tests\Feature;

use App\Http\Controllers\UniteController;
use App\Models\Tenant;
use App\Models\Unite;
use App\Support\TenantContext;
use App\Support\TenantValidation;
use Illuminate\Http\Request;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use LogicException;
use Tests\TestCase;

class TenantIsolationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createMinimalSchema();
    }

    protected function createMinimalSchema(): void
    {
        Schema::dropIfExists('unite');
        Schema::dropIfExists('subscriptions');
        Schema::dropIfExists('tenant_user');
        Schema::dropIfExists('tenant_domains');
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
            $table->timestamps();
        });

        $migration = require database_path('migrations/2026_11_16_150000_create_tenant_control_plane_tables.php');
        $migration->up();

        // Table métier minimale pour tester le cloisonnement.
        Schema::create('unite', function (Blueprint $table) {
            $table->id();
            $table->string('libelle');
            $table->string('abrege')->nullable();
            $table->string('tenant_id', 26)->nullable()->index();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    private function tenant(string $slug): Tenant
    {
        return Tenant::create([
            'name' => ucfirst($slug),
            'slug' => $slug,
            'status' => 'active',
        ]);
    }

    public function test_creating_a_record_auto_fills_the_tenant_id(): void
    {
        $tenant = $this->tenant('ferme-a');
        app(TenantContext::class)->set($tenant);

        $unite = Unite::create(['libelle' => 'Kilogramme', 'abrege' => 'kg']);

        $this->assertSame($tenant->id, $unite->tenant_id);
    }

    public function test_queries_are_scoped_to_the_current_tenant(): void
    {
        $tenantA = $this->tenant('ferme-a');
        $tenantB = $this->tenant('ferme-b');
        $context = app(TenantContext::class);

        $context->set($tenantA);
        Unite::create(['libelle' => 'A - kg', 'abrege' => 'kg']);

        $context->set($tenantB);
        Unite::create(['libelle' => 'B - litre', 'abrege' => 'l']);

        $context->set($tenantA);
        $this->assertSame(1, Unite::count());
        $this->assertSame('A - kg', Unite::first()->libelle);

        $context->set($tenantB);
        $this->assertSame(1, Unite::count());
        $this->assertSame('B - litre', Unite::first()->libelle);
    }

    public function test_tenant_a_cannot_read_tenant_b_record_by_id(): void
    {
        $tenantA = $this->tenant('ferme-a');
        $tenantB = $this->tenant('ferme-b');
        $context = app(TenantContext::class);

        $context->set($tenantB);
        $uniteB = Unite::create(['libelle' => 'B - kg', 'abrege' => 'kg']);

        $context->set($tenantA);

        $this->assertNull(Unite::find($uniteB->id));
    }

    public function test_tenant_exists_validation_rejects_another_tenants_record(): void
    {
        $tenantA = $this->tenant('ferme-a');
        $tenantB = $this->tenant('ferme-b');
        $context = app(TenantContext::class);
        $context->set($tenantB);
        $uniteB = Unite::create(['libelle' => 'B - kg']);

        $context->set($tenantA);
        $validator = Validator::make(
            ['unite' => $uniteB->id],
            ['unite' => ['required', TenantValidation::exists('unite')]]
        );

        $this->assertTrue($validator->fails());
    }

    public function test_enforce_throws_without_tenant_context(): void
    {
        config(['multitenancy.enforce' => true]);
        app(TenantContext::class)->clear();

        $this->expectException(LogicException::class);

        Unite::all();
    }

    public function test_unit_index_renders_a_full_page_for_direct_navigation_and_a_fragment_for_ajax(): void
    {
        $tenant = $this->tenant('ferme-unites');
        app(TenantContext::class)->set($tenant);
        Unite::create(['libelle' => 'Kilogramme', 'abrege' => 'kg']);
        $controller = app(UniteController::class);

        $page = $controller->index(Request::create('/unite', 'GET'));
        $fragment = $controller->index(Request::create('/unite', 'GET', [], [], [], [
            'HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest',
        ]));

        $this->assertSame('admin.pages.setting.unites.page', $page->name());
        $this->assertSame('admin.pages.setting.unites.index', $fragment->name());
    }

    public function test_unit_management_validates_duplicates_per_tenant_and_allows_self_updates(): void
    {
        $tenantA = $this->tenant('ferme-unites-a');
        $tenantB = $this->tenant('ferme-unites-b');
        $context = app(TenantContext::class);
        $context->set($tenantA);
        $controller = app(UniteController::class);
        $ajaxServer = ['HTTP_X_REQUESTED_WITH' => 'XMLHttpRequest'];

        $created = $controller->store(Request::create('/unite', 'POST', [
            'table_data' => ['Kilogramme', 'kg'],
        ], [], [], $ajaxServer));
        $unit = Unite::firstOrFail();
        $updated = $controller->update(Request::create('/unite/'.$unit->id, 'PATCH', [
            'table_data' => ['kilogramme', 'KG'],
        ], [], [], $ajaxServer), $unit->id);
        $duplicate = $controller->store(Request::create('/unite', 'POST', [
            'table_data' => [' KILOGRAMME ', ' kg '],
        ], [], [], $ajaxServer));

        $this->assertStringContainsString('Effectué', $created);
        $this->assertStringContainsString('Effectué', $updated);
        $this->assertStringContainsString('danger', $duplicate);
        $this->assertSame(1, Unite::count());

        $context->set($tenantB);
        $sameLabelForAnotherTenant = $controller->store(Request::create('/unite', 'POST', [
            'table_data' => ['Kilogramme', 'kg'],
        ], [], [], $ajaxServer));

        $this->assertStringContainsString('Effectué', $sameLabelForAnotherTenant);
        $this->assertSame(1, Unite::count());
    }

    public function test_finance_and_salary_module_routes_require_tenant_and_feature_middleware(): void
    {
        foreach ([
            'depenses.index',
            'categorie.index',
            'compte.index',
            'devise.index',
            'transfert.index',
            'salarie.index',
            'paiement_salaire.index',
            'gestion-caisse.index',
        ] as $routeName) {
            $route = Route::getRoutes()->getByName($routeName);
            $this->assertNotNull($route, "Missing route {$routeName}");
            $middleware = $route->middleware();

            $this->assertContains('tenant.resolve', $middleware, $routeName);
            $this->assertContains('tenant.member', $middleware, $routeName);
            $this->assertContains('feature', $middleware, $routeName);
        }
    }
}
