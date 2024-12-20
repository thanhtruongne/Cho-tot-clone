<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use Cachable;
    protected $table = 'products';
    protected $fillable = [
        'user_id',
        'product_id',
        'type',
    ];
}
