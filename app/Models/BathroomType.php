<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BathroomType extends Model
{
    use HasFactory;

   protected $table = 'bathroom_type';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'order' 
    ];
}
