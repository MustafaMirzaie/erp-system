<?php

namespace Database\Seeders;

use App\Models\Company;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'شرکت اصلی',        'alias_name' => 'Main'],
            ['name' => 'شرکت بازرگانی',    'alias_name' => 'Trading'],
            ['name' => 'شرکت تولیدی',      'alias_name' => 'Production'],
        ];

        foreach ($companies as $company) {
            Company::firstOrCreate(
                ['name' => $company['name']],
                ['alias_name' => $company['alias_name']]
            );
        }
    }
}
