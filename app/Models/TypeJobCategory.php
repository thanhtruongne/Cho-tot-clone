<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TypeJobCategory extends Model
{
    use HasFactory;
    protected $table = 'type_job_categories';
    protected $fillable = [
        'code',
        'name',
        'status',
        'created_by',
        'updated_by',
        'order'
    ];
}
