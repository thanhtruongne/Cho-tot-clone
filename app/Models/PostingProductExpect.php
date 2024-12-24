<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Support\Str;



class PostingProductExpect extends Model
{  
    use Cachable;   
    protected $table = 'product_posting_expect';
    protected $fillable = [
       'product_id',
       'posting_data_action_id',
       'cron_completed',
    ];

    public function product_rents(){
        return $this->belongsTo(ProductRentHouse::class,'product_id','id');
    }
}
