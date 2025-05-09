<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    
    public function run(): void
    {
        // Create roles if not already created
        $roles = ['Admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            ['name' => 'Admin User', 'password' => bcrypt('root')]
        );
        $admin->assignRole('Admin');

        // Create Customer User
        // $customer = User::firstOrCreate(
        //     ['email' => 'customer@gmail.com'],
        //     ['name' => 'Customer User', 'password' => bcrypt('root')]
        // );
        // $customer->assignRole('Customer');
    }
}
