<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Super Admin - Full Access
        $superAdmin = Role::updateOrCreate(
            ['slug' => 'super-admin'],
            [
                'name' => 'Super Admin',
                'description' => 'Full system access with all permissions',
                'is_active' => true,
            ]
        );
        // Assign ALL permissions to super admin
        $superAdmin->permissions()->sync(Permission::all()->pluck('id'));

        // Admin - Most Access
        $admin = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Admin',
                'description' => 'Administrative access to most features',
                'is_active' => true,
            ]
        );
        // Assign most permissions except role management
        $adminPermissions = Permission::whereNotIn('slug', [
            'manage-roles',
            'assign-permissions',
        ])->pluck('id');
        $admin->permissions()->sync($adminPermissions);

        // Manager - Team Management
        $manager = Role::updateOrCreate(
            ['slug' => 'manager'],
            [
                'name' => 'Manager',
                'description' => 'Manage team, projects, and performance reviews',
                'is_active' => true,
            ]
        );
        $managerPermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-employees',
            'view-employee-details',
            'view-attendance',
            'manage-attendance',
            'view-projects',
            'create-project',
            'edit-project',
            'view-project-work',
            'send-project-report',
            'view-uat-projects',
            'create-uat-project',
            'edit-uat-project',
            'manage-uat-test-cases',
            'view-uat-feedback',
            'view-github-logs',
            'view-github-pr',
            'view-review-cycles',
            'view-performance-reviews',
            'conduct-performance-reviews',
            'view-goals',
            'manage-goals',
            'view-skills',
            'manage-skills',
            'view-checklists',
            'manage-checklist-templates',
            'generate-daily-checklists',
            'send-checklist-email',
            'view-own-notes',
            'manage-own-notes',
            'view-notifications',
        ])->pluck('id');
        $manager->permissions()->sync($managerPermissions);

        // HR - Human Resources
        $hr = Role::updateOrCreate(
            ['slug' => 'hr'],
            [
                'name' => 'HR',
                'description' => 'Human resources management',
                'is_active' => true,
            ]
        );
        $hrPermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-employees',
            'create-employee',
            'edit-employee',
            'view-employee-details',
            'manage-employee-payments',
            'manage-employee-bank-accounts',
            'manage-employee-access',
            'discontinue-employee',
            'view-attendance',
            'manage-attendance',
            'bulk-populate-attendance',
            'manage-monthly-adjustments',
            'view-contracts',
            'create-contract',
            'edit-contract',
            'download-contract-pdf',
            'view-review-cycles',
            'manage-review-cycles',
            'view-performance-reviews',
            'conduct-performance-reviews',
            'view-goals',
            'manage-goals',
            'view-skills',
            'manage-skills',
            'view-own-notes',
            'manage-own-notes',
            'view-notifications',
            'assign-roles',
        ])->pluck('id');
        $hr->permissions()->sync($hrPermissions);

        // Accountant - Financial Management
        $accountant = Role::updateOrCreate(
            ['slug' => 'accountant'],
            [
                'name' => 'Accountant',
                'description' => 'Manage invoices, payments, and financial records',
                'is_active' => true,
            ]
        );
        $accountantPermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-employees',
            'view-employee-details',
            'manage-employee-payments',
            'manage-employee-bank-accounts',
            'view-invoices',
            'create-invoice',
            'edit-invoice',
            'delete-invoice',
            'download-invoice-pdf',
            'view-contracts',
            'download-contract-pdf',
            'view-own-notes',
            'manage-own-notes',
            'view-notifications',
        ])->pluck('id');
        $accountant->permissions()->sync($accountantPermissions);

        // Employee - Basic Access
        $employee = Role::updateOrCreate(
            ['slug' => 'employee'],
            [
                'name' => 'Employee',
                'description' => 'Basic employee access to own information',
                'is_active' => true,
            ]
        );
        $employeePermissions = Permission::whereIn('slug', [
            'view-dashboard',
            'view-own-notes',
            'manage-own-notes',
            'view-notifications',
        ])->pluck('id');
        $employee->permissions()->sync($employeePermissions);

        $this->command->info('✅ Roles created successfully with permissions assigned!');
        $this->command->info('   • Super Admin: ' . $superAdmin->permissions()->count() . ' permissions');
        $this->command->info('   • Admin: ' . $admin->permissions()->count() . ' permissions');
        $this->command->info('   • Manager: ' . $manager->permissions()->count() . ' permissions');
        $this->command->info('   • HR: ' . $hr->permissions()->count() . ' permissions');
        $this->command->info('   • Accountant: ' . $accountant->permissions()->count() . ' permissions');
        $this->command->info('   • Employee: ' . $employee->permissions()->count() . ' permissions');
    }
}
