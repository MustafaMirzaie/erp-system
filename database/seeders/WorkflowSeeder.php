<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\WorkflowStep;
use Illuminate\Database\Seeder;

class WorkflowSeeder extends Seeder
{
    public function run(): void
    {
        WorkflowStep::truncate();

        $steps = [
            ['name' => 'تایید کارشناس فروش',      'step_order' => 1, 'role' => 'کارشناس فروش'],
            ['name' => 'تایید مدیر مالی',          'step_order' => 2, 'role' => 'مدیر مالی'],
            ['name' => 'تایید مدیر فروش',          'step_order' => 3, 'role' => 'مدیر فروش'],
            ['name' => 'تایید مدیر عامل',          'step_order' => 4, 'role' => 'مدیر عامل'],
            ['name' => 'تایید سرپرست کارخانه',     'step_order' => 5, 'role' => 'سرپرست کارخانه'],
            ['name' => 'تایید سرپرست تولید',       'step_order' => 6, 'role' => 'سرپرست تولید'],
            ['name' => 'تایید مدیر کنترل کیفی',    'step_order' => 7, 'role' => 'مدیر کنترل کیفی'],
            ['name' => 'صدور بارنامه - انتظامات',  'step_order' => 8, 'role' => 'انتظامات'],
        ];

        foreach ($steps as $step) {
            $role = Role::where('name', $step['role'])->first();
            if ($role) {
                WorkflowStep::create([
                    'name'       => $step['name'],
                    'step_order' => $step['step_order'],
                    'role_id'    => $role->id,
                ]);
            }
        }
    }
}
