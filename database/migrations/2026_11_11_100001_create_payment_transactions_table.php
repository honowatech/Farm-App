<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('payable');
            $table->unsignedInteger('amount');
            $table->string('phone_number');
            $table->string('external_reference')->unique();
            $table->string('gateway_reference')->nullable();
            $table->string('provider_slug')->nullable();
            $table->string('status')->default('initiated');
            $table->string('client_name')->nullable();
            $table->string('receipt_url')->nullable();
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
    }
};
