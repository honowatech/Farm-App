<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permission_type_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->foreignId('type_user_id')->constrained('type_users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['permission_id', 'type_user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permission_type_user');
    }
};
