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
        // Create Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin User', 
                'password' => bcrypt('admin123')
            ]
        );
        $admin->assignRole('Admin');

        // Create Customer User
        $customer = User::firstOrCreate(
            ['email' => 'customer@gmail.com'],
            [
                'name' => 'John Doe', 
                'password' => bcrypt('customer123')
            ]
        );
        $customer->assignRole('Customer');

        // Create more test customers
        $customers = [
            ['email' => 'customer2@gmail.com', 'name' => 'Jane Smith'],
            ['email' => 'customer3@gmail.com', 'name' => 'Michael Johnson'],
        ];

        foreach ($customers as $customerData) {
            $user = User::firstOrCreate(
                ['email' => $customerData['email']],
                [
                    'name' => $customerData['name'],
                    'password' => bcrypt('password123')
                ]
            );
            $user->assignRole('Customer');
        }
    }
}