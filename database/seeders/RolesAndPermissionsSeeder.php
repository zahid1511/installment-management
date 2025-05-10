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
            // Admin permissions
            'manage users',
            'manage roles',
            'manage permissions',
            'manage customers',
            'manage products',
            'manage purchases',
            'manage installments',
            'manage guarantors',
            
            // General permissions
            'view dashboard',
            'view reports',
            'view settings',
            'view profile',
            'edit profile',
            
            // Customer permissions
            'view own purchases',
            'view own payments',
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
                'manage permissions',
                'manage customers',
                'manage products',
                'manage purchases',
                'manage installments',
                'manage guarantors',
                'view dashboard',
                'view reports',
                'view settings',
                'view profile',
                'edit profile',
            ],
            'Customer' => [
                'view dashboard',
                'view own purchases',
                'view own payments',
                'view profile',
                'edit profile',
            ]
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}