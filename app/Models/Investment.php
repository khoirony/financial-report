<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $table = 'investments';

    protected $fillable = [
        'user_id',
        'investment_code_id',
        'average_buying_price',
        'amount',
        'broker',
    ];

    public function investmentCode()
    {
        return $this->belongsTo(investmentCode::class, 'investment_code_id');
    }

    public function marketPrice()
    {
        return $this->hasOneThrough(
            MarketPrice::class,           // Model tujuan
            InvestmentCode::class,        // Model perantara
            'id',                         // Foreign key di InvestmentCode
            'investment_code_id',         // Foreign key di MarketPrice
            'investment_code_id',         // Foreign key di Investment
            'id'                          // Local key di InvestmentCode
        );
    }

    public function latestMarketPrice()
    {
        return $this->hasOneThrough(
            MarketPrice::class,
            InvestmentCode::class,
            'id',                   // InvestmentCode.id
            'investment_code_id',   // MarketPrice.investment_code_id
            'investment_code_id',   // Investment.investment_code_id
            'id'                    // InvestmentCode.id
        )->latest('created_at');
    }
}
