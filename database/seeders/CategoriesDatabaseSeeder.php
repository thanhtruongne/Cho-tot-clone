<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Categories;
use App\Models\Permissions;
use App\Models\Profile;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class CategoriesDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \DB::table('categories')->truncate();
       //parent category
       $categoriesParent = [
            [
                'parent_id' => null,
                'name' => 'Nhà ở',
                'icon' => 'fas fa-building',
                'type' => 1 ,
            ],
            [
                'parent_id' => null,
                'name' => 'Đồ điện tử',
                'icon' => 'fas fa-tv',
                'type' => 2 ,
            ],
            [
                'parent_id' => null,
                'name' => 'Việc làm',
                'icon' => 'fas fa-briefcase',
                'type' => 3 ,
            ],
        ];
       
        
        $child = [
            [
                'parent_id' => 1,
                'name' => 'Giấy tờ pháp lý',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Tình trạng nội thất',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Loại hình nhà ở',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Số phòng ngủ',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Số phòng vệ sinh',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Hướng ban công',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Hướng cửa chính',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Hướng ban công',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 2,
                'name' => 'Điện thoại',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
            [
                'parent_id' => 2,
                'name' => 'Máy tính bảng',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
            [
                'parent_id' => 2,
                'name' => 'Laptop',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
            [
                'parent_id' => 2,
                'name' => 'Tivi,Âm thanh',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
        ];





        foreach($categoriesParent as $item){
            $item['key'] = \Str::slug($item['name']);
            Categories::create($item);
           
        }
        foreach($child as $val){
            $val['key'] = \Str::slug($val['name']);
            $categoriesAppend = Categories::create($val);      
        } 
    
}
}