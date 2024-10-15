<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeOfHouse extends Model
{
    use HasFactory;
    protected $table = 'type_of_house';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'order'
    ];
}
