<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        // $user1 = User::factory()->create([
        //     'name' => 'Aadmin',
        //     'email' => 'aadmin@gmail.com',
        //     'color' => '#8a1b1b',
        //     'role' => 'ADMIN',
        //     'password' => 'Aadmin123'
        // ]);

        // User::factory()->create([
        //     'name' => 'Test',
        //     'email' => 'test@gmail.com',
        //     'color' => '#8a1b1b',
        //     'role' => 'USER',
        //     'password' => 'test123'
        // ]);

        // $role = Role::create(['name' => 'Admin']);
        // $user2->assignRole($role);
        
        // $permission = Permission::create(['name' => 'edit articles']);
    }
}
