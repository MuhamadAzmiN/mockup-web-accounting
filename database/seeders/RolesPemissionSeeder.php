<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesPemissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
        public function run(): void
        {
            // Buat Role
            $superAdmin = Role::firstOrCreate([
                'name' => 'super-admin',
                'guard_name' => 'web'
            ]);

            $admin = Role::firstOrCreate([
                'name' => 'admin',
                'guard_name' => 'web'
            ]);

            $finance = Role::firstOrCreate([
                'name' => 'finance',
                'guard_name' => 'web'
            ]);

            $customerService = Role::firstOrCreate([
                'name' => 'customer service',
                'guard_name' => 'web'
            ]);

            // Hanya 2 permission yang dibuat
            $permissions = [
                'manage_dashboard',
                'manage_finance',
                'manage_product',
                'manage_roles_permissions',
                'manage_product_procurement',
                'manage_product_sales',
                'manage_operational',
            ];

            foreach ($permissions as $permName) {
                Permission::firstOrCreate([
                    'name' => $permName,
                    'guard_name' => 'web'
                ]);
            }

            // Assign semua permission ke super-admin
            $superAdmin->syncPermissions(Permission::all());

            // Role lain belum punya permission karena hanya super-admin yang diberikan
            $admin->syncPermissions([]);
            $finance->syncPermissions([]);
            $customerService->syncPermissions([]);

            // Assign super-admin ke user pertama
            $user = User::first();
            if ($user && !$user->hasRole('super-admin')) {
                $user->assignRole('super-admin');
            }
        }

}
