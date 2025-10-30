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
        Schema::create('investments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // Wajib isi
            $table->unsignedBigInteger('investment_code_id')->nullable()->index();
            $table->decimal('average_buying_price', 20, 2)->nullable()->index();
            $table->decimal('amount', 20, 4)->nullable()->index();
            $table->string('broker')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('investment_code_id')->references('id')->on('investment_codes')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investments');
    }
};
