<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentCategory extends Model
{
    use HasFactory;

    protected $table = 'investment_codes';

    protected $fillable = [
        'name',
    ];
}
