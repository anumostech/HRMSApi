<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RBACSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create Modules
        $modules = [
            ['name' => 'Dashboard', 'slug' => 'dashboard', 'route' => '/dashboard', 'icon' => 'dashboard'],
            ['name' => 'Employees', 'slug' => 'employees', 'route' => '/employees', 'icon' => 'people'],
            ['name' => 'Attendance', 'slug' => 'attendance', 'route' => '/attendance', 'icon' => 'event'],
            ['name' => 'Payroll', 'slug' => 'payroll', 'route' => '/payroll', 'icon' => 'payments'],
            ['name' => 'Leave', 'slug' => 'leave', 'route' => '/leave', 'icon' => 'holiday_village'],
            ['name' => 'Reports', 'slug' => 'reports', 'route' => '/reports', 'icon' => 'assessment'],
            ['name' => 'Settings', 'slug' => 'settings', 'route' => '/settings', 'icon' => 'settings'],
        ];

        foreach ($modules as $module) {
            \App\Models\Module::updateOrCreate(['slug' => $module['slug']], $module);
        }

        // 2. Create Roles
        $roles = [
            ['name' => 'Admin', 'description' => 'Full system access'],
            ['name' => 'HR Manager', 'description' => 'Manage employees and attendance'],
            ['name' => 'Employee', 'description' => 'Regular employee access'],
            ['name' => 'Accountant', 'description' => 'Manage payroll and financial reports'],
            ['name' => 'Team Lead', 'description' => 'Manage team attendance and leaves'],
        ];

        foreach ($roles as $roleData) {
            $role = \App\Models\Role::updateOrCreate(['name' => $roleData['name']], $roleData);

            // Give Admin all permissions by default
            if ($role->name === 'Admin') {
                $allModules = \App\Models\Module::all();
                foreach ($allModules as $module) {
                    \App\Models\RolePermission::updateOrCreate(
                        ['role_id' => $role->id, 'module_id' => $module->id],
                        ['can_read' => true, 'can_edit' => true, 'can_delete' => true]
                    );
                }
            }
        }
    }
}
