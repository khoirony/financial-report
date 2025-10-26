<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    public const SALARY = 1;
    public const INVESTMENT = 2;
    public const FOOD = 3;
    public const GROCERIES = 4;
    public const ITEMS = 5;
    public const ENTERTAINMENT = 6;

    protected $table = 'category';

    protected $fillable = [
        'name',
        'type_id',
    ];
    
    public function type()
    {
        return $this->belongsTo(Category::class, 'type_id');
    }
}
