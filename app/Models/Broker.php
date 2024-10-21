<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    use HasFactory;

    protected $table = 'broker';
    protected $fillable = [
        'user_id',
        'product_rent_id',
        'status',
        'percent_commission',
    ];
}
