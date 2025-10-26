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
            $table->unsignedBigInteger('investment_category_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->integer('buying_price')->nullable()->index();
            $table->integer('current_price')->nullable()->index();
            $table->string('broker')->nullable()->index();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('investment_category_id')->references('id')->on('investment_categories')->nullOnDelete();
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
