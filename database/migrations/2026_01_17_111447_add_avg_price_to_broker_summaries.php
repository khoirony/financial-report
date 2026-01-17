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
        Schema::table('broker_summaries', function (Blueprint $table) {
            $table->bigInteger('buy_avg')->default(0)->after('buy_val');
            $table->bigInteger('sell_avg')->default(0)->after('sell_val');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('broker_summaries', function (Blueprint $table) {
            $table->dropColumn(['buy_avg', 'sell_avg']);
        });
    }
};
