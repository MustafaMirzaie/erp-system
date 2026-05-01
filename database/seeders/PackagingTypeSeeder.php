<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PackagingTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['گالن', 'کیسه', 'تانک IBC', 'بشکه', 'پاکت', 'جعبه', 'پالت', 'کارتن'];
        foreach ($types as $type) {
            DB::table('packaging_types')->insertOrIgnore(['name' => $type, 'is_active' => true]);
        }
    }
}
