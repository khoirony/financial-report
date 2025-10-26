<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentCategory extends Model
{
    use HasFactory;

    public const STOCK = 1;
    public const CRYPTO = 2;
    public const INDEX = 3;
    public const REKSADANA = 4;
    public const EMAS = 5;

    protected $table = 'investment_categories';

    protected $fillable = [
        'name',
    ];
}
