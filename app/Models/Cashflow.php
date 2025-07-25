<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cashflow extends Model
{
    use HasFactory;

    protected $table = 'cashflow';

    protected $fillable = [
        'user_id',
        'category_id',
        'type_id',
        'transaction_date',
        'description',
        'source_account',
        'destination_account',
        'amount',
    ];

    protected $casts = [
        'transaction_date' => 'date:Y-m-d',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
