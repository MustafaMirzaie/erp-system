<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        $role = Role::where('name', 'مدیر سیستم')->first();

        if ($role) {
            User::firstOrCreate(
                ['username' => 'admin'],
                [
                    'full_name' => 'مدیر سیستم',
                    'password'  => Hash::make('admin123'),
                    'role_id'   => $role->id,
                    'status'    => 'active',
                ]
            );
        }
    }
}
