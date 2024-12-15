<?php

namespace Database\Seeders;

// use Faker\Factory;

use App\Models\PostingType;
use Illuminate\Database\Seeder;

class PostingTypeDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       \DB::table('posting_type')->truncate();
       \DB::table('posting_data_action')->truncate();
       $data = [
            [
                'id' => 1,
                'code' =>''.\Str::random(10).'',
                'name' => 'Tin thường',
                'benefits' => 'Tin đăng thường, hiển thị với kích thước tiêu chuẩn',
                'cost' => 15000,
                'rule_day' => null,
                'type' => 1,

            ],
            // [
            //     'id' => 2,
            //     'code' =>  ''.\Str::random(10).'',
            //     'name' => 'Tin ưu tiên theo khung giờ (không áp dụng cho tin Vip)',
            //     'benefits' => 'Áp dụng cho tin đăng thường',
            //     'cost' => 30000,
            //     'rule_day' => null,
            //     'type' => 2,
            // ],
            [
                'id' => 2,
                'code' =>  ''.\Str::random(10).'',
                'name' => 'Tin Vip',
                'benefits' => 'Tin đăng Vip hiển thị ưu tiên',
                'cost' => 50000,
                'rule_day' => null,
                'type' => 3,
            ],
            [
                'id' => 3,
                'code' =>  ''.\Str::random(10).'',
                'name' => 'Nút đăng tin',
                'benefits' => 'Giúp tin của bạn có vị trí thuận lợi',
                'cost' => 30000,
                'rule_day' => null,
                'type' => 4,
            ]
        ];

        $rule_type_2 = [
            [
                'post_id' => 2,
                'name' => '8h - 10h',
                'time_1' => '8h',
                'time_2' => '10h',
                'val_1' => 8,
                'val_2' => 10,
            ],
            [
                'post_id' => 2,
                 'name' => '10h - 12h',
                'time_1' => '10h',
                'time_2' => '12h',
                'val_1' => 10,
                'val_2' => 12,
            ],
            [
                'post_id' => 2,
                 'name' => '12h - 14h',
                'time_1' => '12h',
                'time_2' => '14h',
                'val_1' => 12,
                'val_2' => 14,
            ],
            [
                'post_id' => 2,
                 'name' => '14h - 16h',
                'time_1' => '14h',
                'time_2' => '16h',
                'val_1' => 14,
                'val_2' => 16,
            ],
            [
                'post_id' => 2,
                 'name' => '16h - 18h',
                'time_1' => '16h',
                'time_2' => '18h',
                'val_1' => 16,
                'val_2' => 18,
            ],
        ];
        foreach($data as $value){
            PostingType::create($value);
        }
        foreach($rule_type_2 as $item){
            \DB::table('posting_data_action')->insert($item);
        }

    }
}
