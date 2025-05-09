<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Clear cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions
        $permissions = [
            'manage users',      // Admin-specific permissions
            'manage roles',      // Admin-specific permissions
            'general dashboard',    // Common permission
            'view reports',
            'general settings',
            'profile settings',
            'customer dashboard',
            'delete account'
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Define roles and assign permissions
        $roles = [
            'Admin' => [
                'manage users',
                'manage roles',
                'general dashboard',
                'view reports',
                'delete account'
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }

        // Optionally: Assign roles to specific users (if users exist in the database)
        $adminUser = \App\Models\User::find(1); // Replace 1 with the actual Admin user ID
        if ($adminUser) {
            $adminUser->assignRole('Admin');
        }

        // $customerUser = \App\Models\User::find(2); // Replace 2 with the actual Customer user ID
        // if ($customerUser) {
        //     $customerUser->assignRole('Customer');
        // }
    }
}
