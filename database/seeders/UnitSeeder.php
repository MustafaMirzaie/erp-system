<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = ['کیلوگرم', 'گرم', 'تن', 'لیتر', 'مترمکعب', 'عدد', 'متر', 'متر مربع'];
        foreach ($units as $unit) {
            DB::table('units')->insertOrIgnore(['name' => $unit, 'is_active' => true]);
        }
    }
}
