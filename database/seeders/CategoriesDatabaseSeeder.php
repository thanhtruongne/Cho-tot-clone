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
                'name' => 'Bất động sản',
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
                'name' => 'Nhà ở',
                // 'icon' => 'fas fa-briefcase',
                'type' => 1 ,
            ],
            [
                'parent_id' => 1,
                'name' => 'Phòng trọ',
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
            Categories::create($item);
           
        }
        $test = [
            [
                'parent_id' => 4,
                'name' => 'sdsdadasasdasdasd',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
            [
                'parent_id' => 3,
                'name' => 'Tivi,897979789879879 thanh',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
            [
                'parent_id' => 3,
                'name' => 'Okesadad thanh',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
            [

                'parent_id' => 4,
                'name' => 'Tivi,asdasdasdasdasdasdasdasdasddasdasdasdÂm thanh',
                // 'icon' => 'fas fa-briefcase',
                'type' => 2 ,
            ],
        ];
        foreach($child as $val){
            $categoriesAppend = Categories::create($val);
            // if($categories->type)
           
        }  
        foreach($test as $ss){
            $categoriesAppend = Categories::create($ss);
        }
       

        // $ss = Categories::find(4);
        // $categoriesParent = Categories::find(1);
        // $categoriesParent->appendNode($ss);
}
}