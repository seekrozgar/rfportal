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

        // Create permissions
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
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        $superAdmin = Role::create(['name' => 'superadmin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo([
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

        $author = Role::create(['name' => 'author']);
        $author->givePermissionTo([
            'manage jobs',
            'manage blog',
            'manage scholarships',
            'manage admissions',
            'manage news',
            'manage results',
        ]);

        Role::create(['name' => 'employer']);
        Role::create(['name' => 'seeker']);
    }
}
