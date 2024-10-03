<?php

namespace Modules\Permission\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Permission\Entities\Permission;

class PermissionDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $permissions = [
            ['role','Quản lý vai trò','roles',null, 1],
            ['role-create','Thêm vai trò',null,'role', 1],
            ['role-edit','Chỉnh sửa vai trò',null,'role', 1],
            ['role-permission','Cấp quyền vai trò',null,'role', 1],
            ['role-create-user','Thêm user vào vai trò',null,'role', 1],
            ['role-delete-user','Xoá user vào vai trò',null,'role', 1],
            ['role-delete', 'Xoá vai trò',null,'role', 1],
            ['role-export', 'Export vai trò',null,'role', 1],


            ['product-rent','Quản lý thuê căn hộ/phòng','product_rent_house',null, 2],
            ['product-rent-edit','Chỉnh sửa bài đăng',null,'product-rent', 2],
            ['product-rent-remove','Xóa bài đăng',null,'product-rent', 2],
            ['product-rent-approved','Phê duyệt bài đăng',null,'product-rent', 2],
            ['product-rent-status', 'Bật tắt bài đăng',null,'product-rent', 2],
            ['product-rent-export', 'Export bài đăng',null,'product-rent', 2],

            ['product-electric','Quản lý buốn bán sản phẩm','product_electronics',null, 3],
            ['product-electric-edit','Chỉnh sửa bài đăng sản phẩm',null,'product-electric', 3],
            ['product-electric-remove','Xóa bài đăng sản phẩm',null,'product-electric', 3],
            ['product-electric-approved','Phê duyệt bài đăng sản phẩm',null,'product-electric', 3],
            ['product-electric-status', 'Bật tắt bài đăng sản phẩm',null,'product-electric', 3],
            ['product-electric-export', 'Export bài đăng sản phẩm',null,'product-electric', 3],

            ['product-job','Quản lý việc làm','product_jobs',null, 4],
            ['product-job-edit','Chỉnh sửa bài đăng',null,'product-job', 4],
            ['product-job-remove','Xóa bài đăng',null,'product-job', 4],
            ['product-job-approved','Phê duyệt bài đăng',null,'product-job', 4],
            ['product-job-status', 'Bật tắt bài đăng',null,'product-job', 4],
            ['product-job-export', 'Export bài đăng',null,'product-job', 4],
       ];
       foreach ($permissions as $key => $value) {
        $extend = isset($value[5]) ? $value[5] : null;
        $permission = Permission::query()->updateOrCreate(
            [
                'name' => $value[0],
            ],
            [
                'name' => $value[0],
                'guard_name' =>'web',
                'description' => $value[1],
                'model' => $value[2],
                'parent' => $value[3],
                'group' => $value[4],
                'extend' => $extend,
            ]
        );
        \DB::table('role_has_permissions')->updateOrInsert(
            [
                'permission_id' => $permission->id,
                'role_id' => 1,
            ],
            [
                'permission_id' => $permission->id,
                'role_id' => 1,
            ]
        );
        // if (is_null($value[3])) {
        //     \DB::table('role_permission_type')->updateOrInsert(
        //         [
        //             'permission_id' => $permission->id,
        //             'role_id' => 6,
        //             'permission_type_id' => 6,
        //         ],
        //         [
        //             'permission_id' => $permission->id,
        //             'role_id' => 6,
        //             'permission_type_id' => 6,
        //         ]
        //     );
        // }
    }
    }
}
