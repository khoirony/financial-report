<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_code_id')->index(); // Wajib isi
            $table->decimal('current_price', 20, 2)->nullable()->index();
            $table->timestamp('last_update')->nullable()->index();
            $table->timestamps();

            $table->foreign('investment_code_id')->references('id')->on('investment_codes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};
