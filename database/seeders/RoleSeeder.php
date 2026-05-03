<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            'مدیر سیستم',
            'مدیر عامل',
            'مدیر فروش',
            'مدیر مالی',
            'سرپرست کارخانه',
            'سرپرست تولید',
            'مدیر کنترل کیفی',
            'انتظامات',
            'کارشناس هماهنگی فروش',
            'کارشناس فروش',
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }
    }
}
