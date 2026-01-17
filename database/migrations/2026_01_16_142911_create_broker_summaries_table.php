<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('broker_summaries', function (Blueprint $table) {
            $table->id();
            $table->string('ticker', 4)->index(); // Contoh: BBCA
            $table->date('date')->index();
            $table->string('broker_code', 2); // Contoh: YP, PD
            $table->bigInteger('buy_vol')->default(0);
            $table->bigInteger('buy_val')->default(0); // Value dalam Rupiah
            $table->bigInteger('sell_vol')->default(0);
            $table->bigInteger('sell_val')->default(0);
            $table->integer('net_vol')->virtualAs('buy_vol - sell_vol'); // Generated column
            $table->timestamps();
            
            // Mencegah duplikasi data untuk broker yang sama di saham & tanggal yang sama
            $table->unique(['ticker', 'date', 'broker_code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('broker_summaries');
    }
};
