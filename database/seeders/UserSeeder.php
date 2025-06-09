<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
  public function run(): void
{
    // Buat role jika belum ada
        DB::table('users')->truncate();

        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $financeRole = Role::firstOrCreate(['name' => 'finance']);
        $superAdminRole = Role::firstOrCreate(['name' => 'super-admin']);


        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => Hash::make('12345678'), // ganti dengan password yang kuat
        ]);



    $superAdmin->assignRole($superAdminRole);

    // Buat user admin
    $admin = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => Hash::make('password'), // ganti dengan password yang kuat
    ]);
    $admin->assignRole($adminRole);

    // Buat user finance
    $financeCompany1 = User::create([
        'name' => 'Finance  PT Satu Maju',
        'email' => 'finance@example.com',
        'company_id' => 1, // Ganti dengan ID perusahaan yang sesuai
        'password' => Hash::make('password'),
    ]);
    
    $financeCompany1->assignRole($financeRole);

    $financeCompany2 = User::create([
        'name' => 'Finance  PT Dua Maju',
        'email' => 'finance2@example.com',
        'company_id' => 2, // Ganti dengan ID perusahaan yang sesuai
        'password' => Hash::make('password'),
    ]);

    $financeCompany2->assignRole($financeRole);
}

}


