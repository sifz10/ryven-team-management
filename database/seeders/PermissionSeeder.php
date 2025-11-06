<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view-dashboard', 'module' => 'dashboard', 'description' => 'View main dashboard'],

            // Employees
            ['name' => 'View Employees', 'slug' => 'view-employees', 'module' => 'employees', 'description' => 'View employee list'],
            ['name' => 'Create Employee', 'slug' => 'create-employee', 'module' => 'employees', 'description' => 'Create new employees'],
            ['name' => 'Edit Employee', 'slug' => 'edit-employee', 'module' => 'employees', 'description' => 'Edit employee information'],
            ['name' => 'Delete Employee', 'slug' => 'delete-employee', 'module' => 'employees', 'description' => 'Delete employees'],
            ['name' => 'View Employee Details', 'slug' => 'view-employee-details', 'module' => 'employees', 'description' => 'View detailed employee information'],
            ['name' => 'Manage Employee Payments', 'slug' => 'manage-employee-payments', 'module' => 'employees', 'description' => 'Add/edit/delete employee payments'],
            ['name' => 'Manage Employee Bank Accounts', 'slug' => 'manage-employee-bank-accounts', 'module' => 'employees', 'description' => 'Manage employee bank account information'],
            ['name' => 'Manage Employee Access', 'slug' => 'manage-employee-access', 'module' => 'employees', 'description' => 'Manage employee system access'],
            ['name' => 'Discontinue Employee', 'slug' => 'discontinue-employee', 'module' => 'employees', 'description' => 'Discontinue or reactivate employees'],

            // Attendance
            ['name' => 'View Attendance', 'slug' => 'view-attendance', 'module' => 'attendance', 'description' => 'View attendance records'],
            ['name' => 'Manage Attendance', 'slug' => 'manage-attendance', 'module' => 'attendance', 'description' => 'Create/edit/delete attendance records'],
            ['name' => 'Bulk Populate Attendance', 'slug' => 'bulk-populate-attendance', 'module' => 'attendance', 'description' => 'Bulk populate attendance for employees'],
            ['name' => 'Manage Monthly Adjustments', 'slug' => 'manage-monthly-adjustments', 'module' => 'attendance', 'description' => 'Manage monthly attendance adjustments'],

            // Projects
            ['name' => 'View Projects', 'slug' => 'view-projects', 'module' => 'projects', 'description' => 'View project list'],
            ['name' => 'Create Project', 'slug' => 'create-project', 'module' => 'projects', 'description' => 'Create new projects'],
            ['name' => 'Edit Project', 'slug' => 'edit-project', 'module' => 'projects', 'description' => 'Edit project information'],
            ['name' => 'Delete Project', 'slug' => 'delete-project', 'module' => 'projects', 'description' => 'Delete projects'],
            ['name' => 'View Project Work', 'slug' => 'view-project-work', 'module' => 'projects', 'description' => 'View project work submissions'],
            ['name' => 'Send Project Report', 'slug' => 'send-project-report', 'module' => 'projects', 'description' => 'Send project reports'],

            // UAT Testing
            ['name' => 'View UAT Projects', 'slug' => 'view-uat-projects', 'module' => 'uat', 'description' => 'View UAT project list'],
            ['name' => 'Create UAT Project', 'slug' => 'create-uat-project', 'module' => 'uat', 'description' => 'Create new UAT projects'],
            ['name' => 'Edit UAT Project', 'slug' => 'edit-uat-project', 'module' => 'uat', 'description' => 'Edit UAT project information'],
            ['name' => 'Delete UAT Project', 'slug' => 'delete-uat-project', 'module' => 'uat', 'description' => 'Delete UAT projects'],
            ['name' => 'Manage UAT Test Cases', 'slug' => 'manage-uat-test-cases', 'module' => 'uat', 'description' => 'Manage UAT test cases'],
            ['name' => 'View UAT Feedback', 'slug' => 'view-uat-feedback', 'module' => 'uat', 'description' => 'View UAT feedback'],

            // GitHub
            ['name' => 'View GitHub Logs', 'slug' => 'view-github-logs', 'module' => 'github', 'description' => 'View GitHub activity logs'],
            ['name' => 'View GitHub Pull Requests', 'slug' => 'view-github-pr', 'module' => 'github', 'description' => 'View GitHub pull requests'],
            ['name' => 'Manage GitHub Pull Requests', 'slug' => 'manage-github-pr', 'module' => 'github', 'description' => 'Comment, review, merge pull requests'],

            // Invoices
            ['name' => 'View Invoices', 'slug' => 'view-invoices', 'module' => 'invoices', 'description' => 'View invoice list'],
            ['name' => 'Create Invoice', 'slug' => 'create-invoice', 'module' => 'invoices', 'description' => 'Create new invoices'],
            ['name' => 'Edit Invoice', 'slug' => 'edit-invoice', 'module' => 'invoices', 'description' => 'Edit invoice information'],
            ['name' => 'Delete Invoice', 'slug' => 'delete-invoice', 'module' => 'invoices', 'description' => 'Delete invoices'],
            ['name' => 'Download Invoice PDF', 'slug' => 'download-invoice-pdf', 'module' => 'invoices', 'description' => 'Download invoice as PDF'],

            // Contracts
            ['name' => 'View Contracts', 'slug' => 'view-contracts', 'module' => 'contracts', 'description' => 'View contract list'],
            ['name' => 'Create Contract', 'slug' => 'create-contract', 'module' => 'contracts', 'description' => 'Create new employment contracts'],
            ['name' => 'Edit Contract', 'slug' => 'edit-contract', 'module' => 'contracts', 'description' => 'Edit contract information'],
            ['name' => 'Delete Contract', 'slug' => 'delete-contract', 'module' => 'contracts', 'description' => 'Delete contracts'],
            ['name' => 'Download Contract PDF', 'slug' => 'download-contract-pdf', 'module' => 'contracts', 'description' => 'Download contract as PDF'],

            // Checklists
            ['name' => 'View Checklists', 'slug' => 'view-checklists', 'module' => 'checklists', 'description' => 'View employee checklists'],
            ['name' => 'Manage Checklist Templates', 'slug' => 'manage-checklist-templates', 'module' => 'checklists', 'description' => 'Create/edit/delete checklist templates'],
            ['name' => 'Generate Daily Checklists', 'slug' => 'generate-daily-checklists', 'module' => 'checklists', 'description' => 'Generate daily checklists for employees'],
            ['name' => 'Send Checklist Email', 'slug' => 'send-checklist-email', 'module' => 'checklists', 'description' => 'Send checklist via email'],

            // Performance Reviews
            ['name' => 'View Review Cycles', 'slug' => 'view-review-cycles', 'module' => 'performance', 'description' => 'View performance review cycles'],
            ['name' => 'Manage Review Cycles', 'slug' => 'manage-review-cycles', 'module' => 'performance', 'description' => 'Create/edit/delete review cycles'],
            ['name' => 'View Performance Reviews', 'slug' => 'view-performance-reviews', 'module' => 'performance', 'description' => 'View performance reviews'],
            ['name' => 'Conduct Performance Reviews', 'slug' => 'conduct-performance-reviews', 'module' => 'performance', 'description' => 'Conduct and submit performance reviews'],
            ['name' => 'View Goals', 'slug' => 'view-goals', 'module' => 'performance', 'description' => 'View employee goals'],
            ['name' => 'Manage Goals', 'slug' => 'manage-goals', 'module' => 'performance', 'description' => 'Create/edit/delete employee goals'],
            ['name' => 'View Skills', 'slug' => 'view-skills', 'module' => 'performance', 'description' => 'View skills and employee skill levels'],
            ['name' => 'Manage Skills', 'slug' => 'manage-skills', 'module' => 'performance', 'description' => 'Manage skills and employee skill assessments'],

            // Personal Notes
            ['name' => 'View Own Notes', 'slug' => 'view-own-notes', 'module' => 'notes', 'description' => 'View own personal notes'],
            ['name' => 'Manage Own Notes', 'slug' => 'manage-own-notes', 'module' => 'notes', 'description' => 'Create/edit/delete own notes'],
            ['name' => 'View All Notes', 'slug' => 'view-all-notes', 'module' => 'notes', 'description' => 'View all user notes (admin)'],

            // Roles & Permissions
            ['name' => 'View Roles', 'slug' => 'view-roles', 'module' => 'roles', 'description' => 'View role list'],
            ['name' => 'Manage Roles', 'slug' => 'manage-roles', 'module' => 'roles', 'description' => 'Create/edit/delete roles'],
            ['name' => 'Assign Permissions', 'slug' => 'assign-permissions', 'module' => 'roles', 'description' => 'Assign permissions to roles'],
            ['name' => 'Assign Roles to Employees', 'slug' => 'assign-roles', 'module' => 'roles', 'description' => 'Assign roles to employees'],

            // System Settings
            ['name' => 'View Settings', 'slug' => 'view-settings', 'module' => 'settings', 'description' => 'View system settings'],
            ['name' => 'Manage Settings', 'slug' => 'manage-settings', 'module' => 'settings', 'description' => 'Manage system settings'],
            ['name' => 'View Notifications', 'slug' => 'view-notifications', 'module' => 'notifications', 'description' => 'View notifications'],
            ['name' => 'Manage Notifications', 'slug' => 'manage-notifications', 'module' => 'notifications', 'description' => 'Manage notification settings'],
        ];

        foreach ($permissions as $permission) {
            Permission::updateOrCreate(
                ['slug' => $permission['slug']],
                $permission
            );
        }

        $this->command->info('✅ ' . count($permissions) . ' permissions created successfully!');
    }
}
