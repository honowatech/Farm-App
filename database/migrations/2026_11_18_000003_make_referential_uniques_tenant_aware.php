<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Rend les contraintes d'unicité des référentiels métier « par entreprise ».
 *
 * Avant la multi-location, `nom` était unique globalement (ex.
 * `type_animals_nom_unique`). Cela empêche deux entreprises d'avoir le même
 * libellé de référentiel (« Poulet de chair », « Kilogramme »…).
 *
 * On remplace ces contraintes par une unicité composite `(tenant_id, nom)`.
 * Les lignes historiques dont `tenant_id` est NULL restent compatibles.
 */
return new class extends Migration
{
    /**
     * @var array<string, string>  table => colonne
     */
    private array $tables = [
        'type_animals' => 'nom',
        'produits' => 'nom',
        'bandes' => 'nom',
    ];

    public function up(): void
    {
        foreach ($this->tables as $table => $column) {
            $this->makeTenantAware($table, $column);
        }
    }

    public function down(): void
    {
        foreach ($this->tables as $table => $column) {
            $this->restoreGlobalUnique($table, $column);
        }
    }

    private function makeTenantAware(string $table, string $column): void
    {
        if (! Schema::hasTable($table) || ! Schema::hasColumn($table, 'tenant_id')) {
            return;
        }

        $legacy = $table.'_'.$column.'_unique';
        $composite = $table.'_tenant_'.$column.'_unique';
        $indexes = $this->indexNames($table);

        Schema::table($table, function (Blueprint $blueprint) use ($legacy, $composite, $column, $indexes) {
            if (in_array($legacy, $indexes, true)) {
                $blueprint->dropUnique($legacy);
            }

            if (! in_array($composite, $indexes, true)) {
                $blueprint->unique(['tenant_id', $column], $composite);
            }
        });
    }

    private function restoreGlobalUnique(string $table, string $column): void
    {
        if (! Schema::hasTable($table)) {
            return;
        }

        $legacy = $table.'_'.$column.'_unique';
        $composite = $table.'_tenant_'.$column.'_unique';
        $indexes = $this->indexNames($table);

        Schema::table($table, function (Blueprint $blueprint) use ($legacy, $composite, $column, $indexes) {
            if (in_array($composite, $indexes, true)) {
                $blueprint->dropUnique($composite);
            }

            if (! in_array($legacy, $indexes, true)) {
                $blueprint->unique($column, $legacy);
            }
        });
    }

    /**
     * @return array<int, string>
     */
    private function indexNames(string $table): array
    {
        $names = [];

        foreach (Schema::getIndexes($table) as $index) {
            $names[] = $index['name'];
        }

        return $names;
    }
};
