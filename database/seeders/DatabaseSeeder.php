<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        $accessDevPermission = Permission::firstOrCreate(['name' => 'dev']);

        $adminRole->givePermissionTo($accessDevPermission);

        $adminUser = User::first(); // 获取第一个用户

        if ($adminUser) {
            if (!$adminUser->hasRole('admin')) {
                $adminUser->assignRole('admin');
            }
        } else {
            echo "No user\n";
        }
    }
}
