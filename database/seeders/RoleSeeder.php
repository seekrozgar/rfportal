<?php
// database/seeders/RoleSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions safely
        $permissions = [
            'manage jobs',
            'manage companies',
            'manage users',
            'manage packages',
            'manage payments',
            'manage blog',
            'manage testimonials',
            'manage faq',
            'manage settings',
            'manage scholarships',
            'manage admissions',
            'manage news',
            'manage results',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create roles safely and assign permissions
        $superAdmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions(Permission::all()); // syncPermissions duplicate entries se bachata hai

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions([
            'manage jobs',
            'manage companies',
            'manage users',
            'manage blog',
            'manage testimonials',
            'manage faq',
            'manage scholarships',
            'manage admissions',
            'manage news',
            'manage results',
        ]);

        $author = Role::firstOrCreate(['name' => 'author', 'guard_name' => 'web']);
        $author->syncPermissions([
            'manage jobs',
            'manage blog',
            'manage scholarships',
            'manage admissions',
            'manage news',
            'manage results',
        ]);

        // Seeker aur Employer roles safely create karein
        Role::firstOrCreate(['name' => 'employer', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'seeker', 'guard_name' => 'web']);
    }
}
