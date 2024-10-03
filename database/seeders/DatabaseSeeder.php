<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Modules\Permission\Database\Seeders\PermissionDatabaseSeeder;
use Modules\Role\Database\Seeders\RoleDatabaseSeeder;
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UserDatabaseSeeder::class,
            PermissionDatabaseSeeder::class,
            RoleDatabaseSeeder::class,
        ]);
    }
}
