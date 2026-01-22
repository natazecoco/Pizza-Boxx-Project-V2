<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class RoleAndUserSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Definisikan Role dan Guard
        $roles = [
            ['name' => 'super_admin', 'guard_name' => 'employee'],
            ['name' => 'branch_manager', 'guard_name' => 'employee'],
            ['name' => 'employee', 'guard_name' => 'employee'],
            ['name' => 'customer', 'guard_name' => 'web'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name'], 'guard_name' => $role['guard_name']]);
        }

        // 2. Contoh: Membuat/Update Super Admin (Pusat)
        $adminPusat = User::updateOrCreate(
            ['email' => 'admin@pizzaboxx.com'],
            [
                'name' => 'Admin Pusat',
                'password' => Hash::make('12345678'),
                'role' => 'super_admin',
                'location_id' => null, // Super Admin tidak terikat lokasi
            ]
        );
        $adminPusat->assignRole('super_admin', 'employee');

        // 3. Contoh: Membuat Admin Cabang Sukahati
        $adminSukahati = User::updateOrCreate(
            ['email' => 'sukahati@pizzaboxx.com'],
            [
                'name' => 'Manajer Cabang Sukahati',
                'password' => Hash::make('12345678'),
                'role' => 'branch_manager',
                'location_id' => 1, // Pastikan ID 1 adalah Depok
            ]
        );
        $adminSukahati->assignRole('branch_manager', 'employee');
    }
}