<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            WorkflowSeeder::class,
            CompanySeeder::class,
            AdminSeeder::class,
            PackagingTypeSeeder::class,
            UnitSeeder::class,
            FreightTypeSeeder::class,
        ]);
    }
}
