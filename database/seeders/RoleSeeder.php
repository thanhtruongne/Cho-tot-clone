<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    public function run()
    {
        Role::create([
            'code' => 'admin',
            'name' => 'Administrator',
            'type' => 1,  
            'guard_name' => 'web',
            'description' => 'Quản trị viên hệ thống',
            'created_by' => 1, 
            'updated_by' => 1, 
            'unit_by' => null,
            'status' => 1, 
        ]);

        Role::create([
            'code' => 'editor',
            'name' => 'Editor',
            'type' => 2, 
            'guard_name' => 'web',
            'description' => 'Biên tập viên',
            'created_by' => 1,
            'updated_by' => 1,
            'unit_by' => null,
            'status' => 1,
        ]);
    }
}
