<?php

namespace Database\Seeders;

// use Faker\Factory;
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
       $faker = \Faker\Factory::create();
       $data = [
            [
                'id' => 1,
                'code' =>''.\Str::random(10).'',
                'name' => 'Tin Vip',
                'cost' => 15000,
                'rule_day' => json_encode('[1,3,7]'),
                'type' => 1,

            ],
            [
                'id' => 2,
                'code' =>  ''.\Str::random(10).'',
                'name' => 'Tin ưu tiên theo khung giờ (không áp dụng cho tin Vip)',
                'cost' => 30000,
                'rule_day' => null,
                'type' => 2,
            ],
            [
                'id' => 3,
                'code' =>  ''.\Str::random(10).'',
                'name' => 'Gói load tin (theo số lần)',
                'cost' => 30000,
                'rule_day' => null,
                'type' => 3,
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
        \DB::table('posting_type')->upsert($data,'code',['name','cost','rule_day','type']);
        foreach($rule_type_2 as $item){
            \DB::table('posting_data_action')->insert($item);
        }
        
    }
}