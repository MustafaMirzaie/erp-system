<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\WorkflowStep;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        $steps = [
            ['name' => 'تایید مدیر فروش',  'step_order' => 1, 'role' => 'مدیر فروش'],
            ['name' => 'تایید مدیر مالی',  'step_order' => 2, 'role' => 'مدیر مالی'],
            ['name' => 'تایید مدیر عامل', 'step_order' => 3, 'role' => 'مدیر عامل'],
        ];

        foreach ($steps as $step) {
            $role = Role::where('name', $step['role'])->first();
            if ($role) {
                WorkflowStep::firstOrCreate(
                    ['step_order' => $step['step_order']],
                    ['name' => $step['name'], 'role_id' => $role->id]
                );
            }
        }
    }
}
