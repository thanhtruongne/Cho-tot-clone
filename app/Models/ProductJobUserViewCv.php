<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductJobUserViewCv extends Model
{
    use HasFactory;
    protected $table = 'product_jobs_user_view_cv';
    protected $fillable = [
        'product_id',
        'user_id',
        'order',
        'name',
        'year_born_id',
        'gender',
        'phone',
        'edu_level_id',
        'certificate_id',
        'portrait_photo',
        'content',
    ];
}
