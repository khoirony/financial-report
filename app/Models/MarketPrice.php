<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MarketPrice extends Model
{
    use HasFactory;

    protected $table = 'market_prices';

    protected $fillable = [
        'investment_code_id',
        'current_price',
        'last_update',
    ];
}
