<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ Create superadmin role if not exists
        $role = Role::firstOrCreate(['name' => 'superadmin']);

        // ✅ Also create other roles if not exists
        $roles = ['admin', 'author', 'employer', 'seeker'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // ✅ Create superadmin user
        $superAdmin = User::firstOrCreate(
            ['email' => 'mhaseebashraf94@gmail.com'],
            [
                'name' => 'M Haseeb Ashraf',
                'password' => Hash::make('Moon@doll1'),
                'role' => 'superadmin',
            ]
        );

        // ✅ Assign superadmin role
        $superAdmin->syncRoles(['superadmin']);

        $this->command->info('✅ SuperAdmin created successfully!');
        $this->command->info('📧 Email: mhaseebashraf94@gmail.com');
        $this->command->info('🔑 Password: Moon@doll1');
    }
}
