<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashflowType extends Model
{
    use HasFactory;

    public const INCOME = 1;
    public const SPENDING = 2;
    public const INVESTMENT = 3;

    protected $table = 'cashflow_types';

    protected $fillable = [
        'name',
    ];
}
