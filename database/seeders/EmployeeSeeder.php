<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
public function run(): void
    {
        // 1. Pastikan role 'employee' sudah ada di lemari 'employee'
        $role = Role::firstOrCreate([
            'name' => 'employee',
            'guard_name' => 'employee',
        ]);

        // 2. Buat user pegawai
        $user = User::firstOrCreate(
            ['email' => 'test_pegawai@gmail.com'],
            [
                'name' => 'Pegawai',
                'password' => Hash::make('12345678'),
                'role' => 'employee', // Label untuk kolom tabel users
            ]
        );

        // 3. Pasangkan kuncinya. 
        // Cukup gunakan syncRoles([$role]) karena di dalam variabel $role 
        // sudah ada informasi guard 'employee'-nya.
        $user->syncRoles([$role]);
    }
}
