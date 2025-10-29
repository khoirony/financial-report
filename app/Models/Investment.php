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
        'name',
        'average_buying_price',
        'current_price',
        'amount',
        'broker',
    ];

    public function category()
    {
        return $this->belongsTo(CashflowCategory::class, 'cashflow_category_id');
    }
}
