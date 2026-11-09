<?php

namespace Tests\Feature;

use App\Models\Stock_produit;
use App\Services\StockService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class StockServiceTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Schema::dropIfExists('stock_produits');
        Schema::create('stock_produits', function (Blueprint $table) {
            $table->id();
            $table->integer('id_produit')->nullable();
            $table->integer('id_derive_produit')->nullable();
            $table->string('type_mouvement')->nullable();
            $table->float('qte')->nullable();
            $table->float('dernier_stock')->nullable();
            $table->float('nouveau_stock')->nullable();
            $table->datetime('date_entree')->nullable();
            $table->datetime('date_sortie')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function test_stock_produit_retourne_le_dernier_nouveau_stock(): void
    {
        Stock_produit::create([
            'id_produit' => 1,
            'type_mouvement' => 'approvisionnement',
            'qte' => 100,
            'dernier_stock' => 0,
            'nouveau_stock' => 100,
        ]);

        $service = new StockService();

        $this->assertEquals(100.0, $service->stockProduit(1));
    }

    public function test_sortie_produit_decremente_le_stock(): void
    {
        Stock_produit::create([
            'id_produit' => 1,
            'type_mouvement' => 'approvisionnement',
            'qte' => 100,
            'dernier_stock' => 0,
            'nouveau_stock' => 100,
        ]);

        $service = new StockService();
        $mouvement = $service->sortieProduit(1, 30, now()->toDateTimeString());

        $this->assertEquals(70.0, $mouvement->nouveau_stock);
        $this->assertEquals(70.0, $service->stockProduit(1));
    }
}
