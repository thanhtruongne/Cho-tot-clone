<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;
use Illuminate\Support\Str;



class ProductRentHouse extends Model
{
    // use Cachable;   
    protected $table = 'product_rent_house';
    protected $fillable = [
        'day_package_expirition',
        'payment',
        'remaining_days',
        'title',
        'content',
        'user_id',
        'code',
        'type_product',
        'images',
        'video',
        'type_posting_id',
        'approved',
        'type_rental',
        'province_code',
        'time_exipred',
        // 'district_code',
        'ward_code',
        'category_id',
        'subdivision_code',
        'floor',
        'bedroom_id',
        'bathroom_id',
        'main_door_id',
        'legal_id',
        'condition_interior',
        'car_alley',
        'back_house',
        'blooming_house',
        'not_completed_yet',
        'land_not_changed_yet',
        'planning_or_road',
        'diff_situation',
        'land_area',
        'usable_area',
        // 'status',
        'horizontal',
        'length',
        'cost',
        'cost_deposit',
        'rule_compensation',
        'type_user',
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
            $code = 'RENT_'.now().'_'.rand(1000, 9999);
        } while (self::where('code', $code)->exists()); 
        return $code;
    }


    public static function getAttributeName(){
        return [
            'name' => 'Tên sản phẩm',
            'title' => 'Tiêu đề sản phẩm',
            'content' => 'Nội dung',
            'type_product' => 'Loại sản phẩm', // 1 là nhà ở, 2 là phòng trọ
            'images' => 'Hình ảnh', // Có thể thay đổi thành 'array' nếu là mảng ảnh
            'video' => 'Video',
            'province_code' => 'Thành phố/ Tỉnh',
            'district_code' => 'Quận/Huyện',
            'ward_code' => 'Phường/Xã',
            'category_id' => 'Danh mục sản phẩm',
            'land_area' => 'Diện tích đất',
            'usable_area' => 'Diện tích đất sử dụng',
            'cost' => 'Giá tiền'
        ];
    }

    public function posting_product_expect(){
        return $this->belongToMany(PostingDataAction::class,'product_posting_expect','product_id','posting_data_action_id');
    }
}