<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileImport extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'file_imports';

    protected $fillable = [
        'user_id',
        'filename',
        'size',
        'path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
