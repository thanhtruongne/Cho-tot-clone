<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductRentHouseLike extends Model
{
    use HasFactory;
    protected $table = 'management_user_like';
    protected $fillable = [
        'user_id',
        'comment_id',
        'star',
        'like',
        'dislike',
    ];
}
