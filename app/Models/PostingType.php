<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostingType extends Model
{
    use HasFactory;
    protected $table = 'posting_type';

    // protected $fillable = [
    //     'code',
    //     'name',
    // ];
}
