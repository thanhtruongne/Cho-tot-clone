<?php

namespace App\Models;

use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Database\Eloquent\Model;

class ProductVehicle extends Model
{
    use Cachable;   
    protected $table = 'product_vehicle';

    protected $fillable = [
        'title',
        'content',
        'user_id',
        'code',
        'images',
        'video',
        'category_id',
        'type_posting_id',
        'approved',
        'address',
        'status',
        'province_code',
        'district_code',
        'ward_code',
        'condition_used',
        'cost',
        'brand_id',
        'color_id',
        'load_btn_post',
        'origin_from_id',
        'company'
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->code = self::generateUniqueCode();
        });
    }

    protected static function generateUniqueCode()
    {
        do {
            $code = 'VEHICLE_'.now().'_'.rand(1000, 9999);
        } while (self::where('code', $code)->exists());
        return $code;
    }

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('/' . $this->video),
        ]);     
        return route('fe.video-streaming', [$file]);
    }


    public static function getAttributeName(){
        return [
            'name' => 'Tên sản phẩm',
            'title' => 'Tiêu đề sản phẩm',
            'content' => 'Nội dung',
            'type_product' => 'Loại sản phẩm', // 1 là nhà ở, 2 là phòng trọ
            'images' => 'Hình ảnh',
            'video' => 'Video',
            'province_code' => 'Thành phố/ Tỉnh',
            'district_code' => 'Quận/Huyện',
            'ward_code' => 'Phường/Xã',
            'category_id' => 'Danh mục sản phẩm',
            'condition_used' => 'Tình trạng sử dụng',
            'cost' => 'Giá tiền',
            'brand_id' => 'Thương hiệu',
            'color_id' => 'Màu sản phẩm',
        ];
    }


    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }


    public function products(){
        return $this->belongsTo(Products::class,'product_id','id');
    }

    public function province(){
        return $this->belongsTo(Provinces::class,'province_code','code')->select('full_name');
    }

    public function district(){
        return $this->belongsTo(Districts::class,'district_code','code')->select('full_name');
    }

    public function ward(){
        return $this->belongsTo(Wards::class,'ward_code','code')->select('full_name');
    }
}
