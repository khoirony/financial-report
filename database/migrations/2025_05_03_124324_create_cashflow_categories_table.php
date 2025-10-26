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
        Schema::create('cashflow_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->index();
            $table->unsignedBigInteger('cashflow_type_id')->nullable()->index();
            $table->timestamps();

            $table->foreign('cashflow_type_id')->references('id')->on('cashflow_types')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflow_categories');
    }
};
