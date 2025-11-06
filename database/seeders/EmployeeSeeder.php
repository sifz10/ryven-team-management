<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $employees = [
            [
                'first_name' => 'John',
                'last_name' => 'Doe',
                'email' => 'john.doe@company.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567890',
                'position' => 'Senior Developer',
                'department' => 'Engineering',
                'salary' => 75000.00,
                'currency' => 'USD',
                'github_username' => 'johndoe',
                'hired_at' => now()->subYears(2),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Jane',
                'last_name' => 'Smith',
                'email' => 'jane.smith@company.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567891',
                'position' => 'Product Manager',
                'department' => 'Product',
                'salary' => 80000.00,
                'currency' => 'USD',
                'github_username' => 'janesmith',
                'hired_at' => now()->subYear(),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Mike',
                'last_name' => 'Johnson',
                'email' => 'mike.johnson@company.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567892',
                'position' => 'UI/UX Designer',
                'department' => 'Design',
                'salary' => 65000.00,
                'currency' => 'USD',
                'github_username' => 'mikejohnson',
                'hired_at' => now()->subMonths(6),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
            [
                'first_name' => 'Sarah',
                'last_name' => 'Williams',
                'email' => 'sarah.williams@company.com',
                'password' => Hash::make('password123'),
                'phone' => '+1234567893',
                'position' => 'QA Engineer',
                'department' => 'Engineering',
                'salary' => 60000.00,
                'currency' => 'USD',
                'github_username' => 'sarahwilliams',
                'hired_at' => now()->subMonths(3),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        ];

        // Get roles
        $managerRole = Role::where('slug', 'manager')->first();
        $hrRole = Role::where('slug', 'hr')->first();
        $employeeRole = Role::where('slug', 'employee')->first();

        foreach ($employees as $employeeData) {
            $employee = Employee::updateOrCreate(
                ['email' => $employeeData['email']],
                $employeeData
            );

            // Assign roles based on position
            if ($employee->email === 'john.doe@company.com') {
                // John: Manager role
                $employee->roles()->sync([$managerRole->id]);
            } elseif ($employee->email === 'jane.smith@company.com') {
                // Jane: HR role
                $employee->roles()->sync([$hrRole->id]);
            } else {
                // Others: Basic employee role
                $employee->roles()->sync([$employeeRole->id]);
            }
        }

        $this->command->info('✅ Test employees created successfully with roles assigned!');
        $this->command->info('📧 Login credentials:');
        $this->command->info('   Email: john.doe@company.com | Password: password123 | Role: Manager');
        $this->command->info('   Email: jane.smith@company.com | Password: password123 | Role: HR');
        $this->command->info('   Email: mike.johnson@company.com | Password: password123 | Role: Employee');
        $this->command->info('   Email: sarah.williams@company.com | Password: password123 | Role: Employee');
    }
}

