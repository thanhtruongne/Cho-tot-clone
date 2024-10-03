<?php

namespace Modules\Role\Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Role\Entities\Role;

class RoleDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            ['Admin','Admin','Vai trò có quyền cao nhất', 1,1],
        ];
        foreach ($roles as $key => $value) {
            Role::updateOrCreate(
                [
                    'code' => $value[0]
                ],
                [
                    'code' => $value[0],
                    'name' => $value[1],
                    'description'=>$value[2],
                    'type' => $value[3],
                    'guard_name' => 'web',
                    'created_by'=>2,
                    'updated_by'=>2,
                    'unit_by' => 1,
                    'status'=>$value[4]
                ]
            );
        }
        User::find(1)->assignRole('Admin');
        User::find(2)->assignRole('Admin');
    }
}
