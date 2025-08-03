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
        Schema::create('cashflow', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index(); // Wajib isi
            $table->unsignedBigInteger('category_id')->nullable()->index();
            $table->unsignedBigInteger('type_id')->nullable()->index();
            $table->datetime('transaction_date')->nullable()->index();
            $table->string('description')->nullable()->index();
            $table->string('source_account')->nullable()->index();
            $table->string('destination_account')->index(); // Wajib isi
            $table->integer('amount')->index(); // Wajib isi
            $table->timestamps();
        
            // Relasi
            $table->foreign('category_id')->references('id')->on('category');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('type_id')->references('id')->on('type');
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflow');
    }
};
