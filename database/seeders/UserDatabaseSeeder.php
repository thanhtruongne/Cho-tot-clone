<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Permissions;
use App\Models\Profile;
use App\Models\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        \DB::table('users')->truncate();
        $faker = Factory::create();
        /***** superadmin *******/
        $superadmin = User::firstOrNew(['username' => 'superadmin'],[
            'username' => 'superadmin',
            'password' => \Hash::make('123123'),
            'firstname' => 'Super',
            'lastname' => 'Admin',
            'email' => 'superadmin@gmail.com',
            'status'=> 1,
            'dob' => date('Y-m-d 00:00:00'),
            'date_range' => date('Y-m-d H:i:s'),
            // 'contract_signing_date'=> date('Y-m-d H:i:s'),
            'gender'=> 1,
        ]);
        $superadmin->save();
        $admin = User::firstOrNew(['username' => 'admin'],[
            'username' => 'admin',
            'password' => \Hash::make('123123'),
            'firstname' => 'admin',
            'lastname' => 'admin',
            'email' => 'admin@gmail.com',
            'status'=> 1,
            'dob' => date('Y-m-d 00:00:00'),
            'date_range' => date('Y-m-d H:i:s'),
            // 'contract_signing_date'=> date('Y-m-d H:i:s'),
            'gender'=> 1,
        ]);
        $admin->save();


    }
}
