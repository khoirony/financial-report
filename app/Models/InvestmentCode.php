<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentCode extends Model
{
    use HasFactory;

    protected $table = 'investment_codes';

    protected $fillable = [
        'investment_category_id',
        'name',
        'investment_code',
        'source',
        'currency',
    ];
}
