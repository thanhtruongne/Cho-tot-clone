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


    public function posting_data_type(){
        return $this->hasMany(PostingDataAction::class,'post_id','id');
    }
}
