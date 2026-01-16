<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashflowCategory extends Model
{
    use HasFactory;

    public const SALARY = 1;
    public const FOOD = 2;
    public const GROCERIES = 3;
    public const ITEMS = 4;
    public const ENTERTAINMENT = 5;
    public const OTHERS = 6;

    protected $table = 'cashflow_categories';

    protected $fillable = [
        'name',
        'cashflow_type_id',
    ];
    
    public function type()
    {
        return $this->belongsTo(CashflowType::class, 'cashflow_type_id');
    }
}
