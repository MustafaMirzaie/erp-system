<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FreightTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            ['name' => 'پس‌کرایه',  'description' => 'کرایه توسط گیرنده پرداخت می‌شود'],
            ['name' => 'پیش‌کرایه', 'description' => 'کرایه توسط فرستنده پرداخت می‌شود'],
            ['name' => 'توافقی',    'description' => 'کرایه به صورت توافقی تعیین می‌شود'],
            ['name' => 'رایگان',    'description' => 'بدون کرایه'],
        ];
        foreach ($types as $type) {
            DB::table('freight_types')->insertOrIgnore(array_merge($type, ['is_active' => true]));
        }
    }
}
