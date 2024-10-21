<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainDoorHouse extends Model
{
    use HasFactory;
    protected $table = 'main_door_house';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'order' 
    ];
}
