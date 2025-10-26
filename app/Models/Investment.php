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
        'investment_category_id',
        'name',
        'buying_price',
        'current_price',
        'broker',
    ];

    public function category()
    {
        return $this->belongsTo(CashflowCategory::class, 'cashflow_category_id');
    }
}
