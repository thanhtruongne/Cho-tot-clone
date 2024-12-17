<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class ProductRentHouse extends Model
{
    use Cachable;
    protected $table = 'product_rent_house';
    protected $primaryKey = 'id';

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
        'status',
        'horizontal',
        'length',
        'cost',
        'cost_deposit',
        'rule_compensation',
        'district_code',
        'type_user',
        'load_btn_post'
    ];

    protected $casts = [
        'images' => 'json'
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

    public function getLinkPlay() {
        $storage = \Storage::disk('local');
        $file = encrypt_array([
            'path' => $storage->path('/' . $this->video),
        ]);
        // dd($storage->url(  $this->video),$storage->path('/'.$this->video),$this->video);
       
        return route('fe.video-streaming', [$file]);
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
        return $this->belongsToMany(PostingDataAction::class,'product_posting_expect','product_id','posting_data_action_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id','id')
        ->select(['firstname','lastname','email','phone','address','gender','avatar']);
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
