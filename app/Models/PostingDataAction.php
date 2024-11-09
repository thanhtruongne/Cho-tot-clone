<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Support\Str;



class PostingDataAction extends Model
{  

    use Cachable;   
    protected $table = 'posting_data_action';
    protected $fillable = [
       'post_id',
       'name',
       'time_1',
       'time_2',
       'val_1',
       'val_2',
       'content',
    ];

    public function posting_product_rent_house(){
        return $this->belongToMany(ProductRentHouse::class,'product_posting_expect','posting_data_action_id','product_id');
    }
}
