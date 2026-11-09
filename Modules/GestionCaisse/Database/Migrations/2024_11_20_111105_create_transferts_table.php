<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('transferts');
         Schema::create('gestion_caisse_transferts', function ($table) {
            $table->id(); // id (PK)
            $table->decimal('amount', 15, 2); // montant du transfert
            $table->timestamp('transfer_date'); // date du transfert
            $table->string('description')->nullable(); // description (optionnel)
            $table->timestamps();
        });
        DB::statement("ALTER TABLE gestion_caisse_transferts ADD from_account_id INT(10) UNSIGNED  NULL");
        DB::statement("ALTER TABLE gestion_caisse_transferts ADD to_account_id INT(10) UNSIGNED  NULL");
        DB::statement("ALTER TABLE gestion_caisse_transferts ADD user_id BIGINT(20) UNSIGNED NULL");

        // Ajouter les clés étrangères avec DB::statement
        DB::statement("ALTER TABLE gestion_caisse_transferts ADD CONSTRAINT fk_from_account_id FOREIGN KEY (from_account_id) REFERENCES gestion_caisse_comptes(id) ON DELETE CASCADE");
        DB::statement("ALTER TABLE gestion_caisse_transferts ADD CONSTRAINT fk_to_account_id FOREIGN KEY (to_account_id) REFERENCES gestion_caisse_comptes(id) ON DELETE CASCADE");
        DB::statement("ALTER TABLE gestion_caisse_transferts ADD CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transferts', function ($table) {
            $table->dropForeign(['from_account_id']);
            $table->dropForeign(['to_account_id']);
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('transferts');
    }
};
