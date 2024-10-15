<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRentHouseComment extends Model
{
    use HasFactory;
    protected $table = 'managerment_user_comment';
    protected $fillable = [
        'user_id',
        'content',
        'failed',

    ];
}
