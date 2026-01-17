<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrokerSummary extends Model
{
    use HasFactory;

    protected $table = 'broker_summaries';

    protected $guarded = [
        'id',
    ];

    protected $casts = [
        'date' => 'date', // Ini akan mengubah string "2023-10-01" menjadi objek Carbon
        'buy_vol' => 'integer',
        'sell_vol' => 'integer',
        'net_vol' => 'integer',
    ];
}
