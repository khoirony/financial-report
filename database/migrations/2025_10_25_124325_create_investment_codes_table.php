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
        Schema::create('investment_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investment_category_id')->nullable()->index();
            $table->string('name')->nullable()->index();
            $table->string('investment_code')->nullable()->index();
            $table->string('source')->nullable()->index();
            $table->string('currency')->nullable()->index();
            $table->timestamps();

            $table->foreign('investment_category_id')->references('id')->on('investment_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investment_codes');
    }
};
