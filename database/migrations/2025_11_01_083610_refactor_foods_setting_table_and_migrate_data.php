<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log; // Utile pour le débogage en cas d'erreur

return new class extends Migration
{
    /**
     * Exécute la migration.
     * ATTENTION : Sauvegardez votre base de données avant d'exécuter cette migration.
     */
    public function up(): void
    {
        // === Étape 1 : Préparation du Schéma Temporaire ===
        // Rendre les colonnes temporairement NULLABLE pour que l'INSERT ne plante pas.
        Schema::table('foods_setting', function (Blueprint $table) {
            $table->decimal('quantite_aliment', 8, 2)->nullable()->change();
            // Optionnel mais plus sûr : rendre aussi les autres colonnes déplacées nullable
            $table->unsignedInteger('age_min')->nullable()->change();
            $table->unsignedInteger('age_max')->nullable()->change();
            $table->unsignedInteger('frequency')->nullable()->change();
            $table->string('period', 20)->nullable()->change();
            $table->unsignedBigInteger('id_unite')->nullable()->change();
        });

        // On encapsule les manipulations de données dans une transaction
        DB::transaction(function () {
            // === Étape 2 : Migration des données existantes (Votre code) ===

            $oldSettings = DB::table('foods_setting')->orderBy('created_at')->get();
            $createdParents = [];

            foreach ($oldSettings as $setting) {
                $parentKey = $setting->id_type_animal . '-' . $setting->aliment;
                $parentId = null;

                // 1. Gérer le parent (Insertion dans l'ancienne structure, maintenant nullable)
                if (!isset($createdParents[$parentKey])) {
                    $parentId = DB::table('foods_setting')->insertGetId([
                        // Seules les colonnes qui resteront sont insérées
                        'id_type_animal' => $setting->id_type_animal,
                        'aliment'        => $setting->aliment,
                        'created_at'     => $setting->created_at,
                        'updated_at'     => $setting->updated_at,
                        // Les colonnes manquantes (quantite_aliment, etc.) seront NULL ici
                    ]);
                    $createdParents[$parentKey] = $parentId;
                } else {
                    $parentId = $createdParents[$parentKey];
                }
                
                // 2. Supprimer l'ancienne ligne
                // L'ancienne ligne doit être supprimée APRÈS la création de la nouvelle ligne de parent 
                // ou de la version, pour s'assurer que les données ne sont pas perdues en cas d'erreur.
                DB::table('foods_setting')->where('id', $setting->id)->delete();


                // 3. Créer la version liée au parent
                DB::table('food_setting_versions')->insert([
                    'food_setting_id'  => $parentId,
                    'age_min'          => $setting->age_min,
                    'age_max'          => $setting->age_max,
                    'quantite_aliment' => $setting->quantite_aliment,
                    'id_unite'         => $setting->id_unite,
                    'frequency'        => $setting->frequency,
                    'period'           => $setting->period,
                    'activated_at'     => $setting->created_at,
                    'ended_at'         => null,
                    'created_at'       => $setting->created_at,
                    'updated_at'       => $setting->updated_at,
                ]);
            }
        });

        // === Étape 3 : Finalisation du Schéma ===
        
        // Modification du schéma APRÈS la transaction de données.
        Schema::table('foods_setting', function (Blueprint $table) {
            // Supprimer les colonnes qui ont été déplacées
            $table->dropColumn([
                'age_min', 'age_max', 'quantite_aliment',
                'frequency', 'period', 'id_unite'
            ]);

            // Ajouter la contrainte d'unicité.
            $table->unique(['id_type_animal', 'aliment'], 'foods_setting_animal_aliment_unique');
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        // L'opération `down` est destructive et ne restaurera pas les données.
        // Elle se contente de restaurer la structure de la table pour permettre de relancer `up`.
        Schema::table('foods_setting', function (Blueprint $table) {
            $table->dropUnique('foods_setting_animal_aliment_unique');
            
            // On vérifie si les colonnes existent avant de les ajouter pour éviter les erreurs
            if (!Schema::hasColumn('foods_setting', 'age_min')) {
                $table->unsignedInteger('age_min')->after('aliment');
                $table->unsignedInteger('age_max')->after('age_min');
                $table->decimal('quantite_aliment', 8, 2)->after('age_max');
                $table->foreignId('id_unite')->nullable()->after('quantite_aliment'); // Nullable pour faciliter
                $table->unsignedInteger('frequency')->default(1)->after('id_unite');
                $table->string('period', 20)->default('day')->after('frequency');
            }
        });
        
        // On vide la table des versions car elle ne serait plus valide
        DB::table('food_setting_versions')->truncate();
    }
};
