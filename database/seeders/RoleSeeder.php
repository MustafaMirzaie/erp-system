<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            ['name' => 'مدیر سیستم'],
            ['name' => 'مدیر فروش'],
            ['name' => 'کارشناس فروش'],
            ['name' => 'مدیر مالی'],
            ['name' => 'مدیر عامل'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']]);
        }
    }
}
