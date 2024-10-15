<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use GeneaLabs\LaravelModelCaching\Traits\Cachable;

class ProductRentHouse extends Model
{
    use Cachable;   
    protected $table = 'product_rent_house';
    protected $fillable = [
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
        'district_code',
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
        'horizontal',
        'length',
        'cost',
        'cost_deposit',
        'rule_compensation',
    ];
}
