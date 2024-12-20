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
        // \DB::table('categories')->truncate();
       //parent category
    //    $categoriesParent = [
    //         [
    //             'parent_id' => null,
    //             'name' => 'Nhà ở',
    //             'icon' => 'fas fa-building',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => null,
    //             'name' => 'Đồ điện tử',
    //             'icon' => 'fas fa-tv',
    //             'type' => 2 ,
    //         ],
    //         [
    //             'parent_id' => null,
    //             'name' => 'Việc làm',
    //             'icon' => 'fas fa-briefcase',
    //             'type' => 3 ,
    //         ],
    //     ];
       
        
    //     $child = [
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Giấy tờ pháp lý',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Tình trạng nội thất',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Loại hình nhà ở',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Số phòng ngủ',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Số phòng vệ sinh',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Hướng ban công',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Hướng cửa chính',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 1,
    //             'name' => 'Hướng ban công',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 1 ,
    //         ],
    //         [
    //             'parent_id' => 2,
    //             'name' => 'Điện thoại',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 2 ,
    //         ],
    //         [
    //             'parent_id' => 2,
    //             'name' => 'Máy tính bảng',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 2 ,
    //         ],
    //         [
    //             'parent_id' => 2,
    //             'name' => 'Laptop',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 2 ,
    //         ],
    //         [
    //             'parent_id' => 2,
    //             'name' => 'Tivi,Âm thanh',
    //             // 'icon' => 'fas fa-briefcase',
    //             'type' => 2 ,
    //         ],
    //     ];





    //     foreach($categoriesParent as $item){
    //         $item['key'] = \Str::slug($item['name']);
    //         Categories::create($item);
           
    //     }
    //     foreach($child as $val){
    //         $val['key'] = \Str::slug($val['name']);
    //         $categoriesAppend = Categories::create($val);      
    //     } 

    $array_data = [
        [
            'parent_id' => 49,
            'name' => 'ASUS',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'APPLE',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'Dell',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'Gigabyte',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'Razer',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'MSI',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'Lenovo',
            'type' => 2 ,
        ],
        [
            'parent_id' => 49,
            'name' => 'HP',
            'type' => 2 ,
        ],
        [
            'parent_id' => 51,
            'name' => '8GB',
            'type' => 2 ,
        ],

        [
            'parent_id' => 51,
            'name' => '16GB',
            'type' => 2 ,
        ],

        [
            'parent_id' => 51,
            'name' => '32GB',
            'type' => 2 ,
        ],

        [
            'parent_id' => 51,
            'name' => '>32GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 51,
            'name' => '<1GB ',
            'type' => 2 ,
        ],
        [
            'parent_id' => 51,
            'name' => '4GB',
            'type' => 2 ,
        ],

        [
            'parent_id' => 51,
            'name' => '6GB',
            'type' => 2 ,
        ],

        [
            'parent_id' => 52,
            'name' => 'Việt Nam',
            'type' => 2 ,
        ],
        [
            'parent_id' => 52,
            'name' => 'Trung Quốc',
            'type' => 2 ,
        ],
        [
            'parent_id' => 52,
            'name' => 'Nhật Bản',
            'type' => 2 ,
        ],

        [
            'parent_id' => 52,
            'name' => 'Ấn độ',
            'type' => 2 ,
        ],

        [
            'parent_id' => 52,
            'name' => 'Hàn quốc',
            'type' => 2 ,
        ],

        [
            'parent_id' => 52,
            'name' => 'Thái Lan',
            'type' => 2 ,
        ],

        [
            'parent_id' => 52,
            'name' => 'Nước khác',
            'type' => 2 ,
        ],



        [
            'parent_id' => 54,
            'name' => 'APPLE',
            'type' => 2 ,
        ],

        [
            'parent_id' => 54,
            'name' => 'Nokia',
            'type' => 2 ,
        ],
        [
            'parent_id' => 54,
            'name' => 'Samsung',
            'type' => 2 ,
        ],
        [
            'parent_id' => 54,
            'name' => 'Huawei',
            'type' => 2 ,
        ],

        [
            'parent_id' => 54,
            'name' => 'LG',
            'type' => 2 ,
        ],

        [
            'parent_id' => 54,
            'name' => 'Xiaomi',
            'type' => 2 ,
        ],

        [
            'parent_id' => 54,
            'name' => 'Lenovo',
            'type' => 2 ,
        ],

        [
            'parent_id' => 54,
            'name' => 'Hãng khác',
            'type' => 2 ,
        ],



        [
            'parent_id' => 53,
            'name' => 'Bạc',
            'type' => 2 ,
        ],
        [
            'parent_id' => 53,
            'name' => 'Đỏ',
            'type' => 2 ,
        ],
        [
            'parent_id' => 53,
            'name' => 'Vàng',
            'type' => 2 ,
        ],
        [
            'parent_id' => 53,
            'name' => 'Xanh',
            'type' => 2 ,
        ],
        [
            'parent_id' => 53,
            'name' => 'Đen',
            'type' => 2 ,
        ],
        [
            'parent_id' => 53,
            'name' => 'Trắng',
            'type' => 2 ,
        ],
        [
            'parent_id' => 53,
            'name' => 'Khác',
            'type' => 2 ,
        ],


        [
            'parent_id' => 56,
            'name' => ' 8GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '16GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '32GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '64GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '128GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '256GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '512GB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '1TB',
            'type' => 2 ,
        ],
        [
            'parent_id' => 56,
            'name' => '2TB',
            'type' => 2 ,
        ],

    ];
    
    foreach($array_data as $item){
        $item['key'] = \Str::slug($item['name']);
        Categories::create($item);
        
    }
}
}