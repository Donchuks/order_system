<?php

namespace Database\Seeders;

use App\Enum\RoleName;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'OKAFOR',
            'last_name' => 'PONTIAN',
            'gender' => 'male',
            'username' => 'chuksdev',
            'email' => 'chuksdeveloper@gmail.com',
            'email_verified_at' => null,
            'password' => bcrypt('manager.chuksdev'), // password
            'remember_token' => null,
        ]);

        $role = Role::findOrCreate(RoleName::MANAGEMENT, 'web');
        Role::findOrCreate(RoleName::SHIPPING, 'web');
        Role::findOrCreate(RoleName::PICKING, 'web');

        $findRole = Role::findById($role->id);
        $permission = Permission::query()->insert([
            ['name' => 'add_order', 'guard_name' => 'web'],
            ['name' => 'edit_order', 'guard_name' => 'web'],
            ['name' => 'view_order', 'guard_name' => 'web'],
            ['name' => 'cancel_order', 'guard_name' => 'web'],
            ['name' => 'product_mgt', 'guard_name' => 'web'],
            ['name' => 'access_mgt', 'guard_name' => 'web']
        ]);

        $permissions = Permission::query()->where('name', 'view_order')->get();
        $role->syncPermissions($permissions);
        $user->assignRole($findRole);
//         \App\Models\User::factory(2)->create();
//        \App\Models\Product::factory(2)->create();
    }
}
