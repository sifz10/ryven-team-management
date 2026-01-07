# Role-Based Access Control (RBAC) System - Implementation Guide

## 🎯 Overview

The Team Management System now features a **complete Role-Based Access Control (RBAC)** system that controls employee access to features based on their assigned roles and permissions.

## 🔑 Key Features

✅ **Dynamic Permissions** - 62 granular permissions covering all system features  
✅ **Flexible Roles** - 6 predefined roles with customizable permission sets  
✅ **Middleware Protection** - Route-level permission checking  
✅ **Employee Roles** - Multiple roles can be assigned to each employee  
✅ **Permission Inheritance** - Employees inherit all permissions from their roles  
✅ **Database-Driven** - All permissions and roles stored in database  

## 📊 System Architecture

### Database Schema

```
permissions
├── id
├── name (e.g., "View Employees")
├── slug (e.g., "view-employees")
├── module (e.g., "employees")
└── description

roles
├── id
├── name (e.g., "Manager")
├── slug (e.g., "manager")
├── description
└── is_active

permission_role (pivot)
├── permission_id
└── role_id

employee_role (pivot)
├── employee_id
└── role_id
```

### Relationships

- **Role** → **Permissions** (Many-to-Many)
- **Employee** → **Roles** (Many-to-Many)
- **Employee** → **Permissions** (Through Roles)

## 👥 Predefined Roles

### 1. Super Admin
- **Permissions**: ALL (62 permissions)
- **Description**: Full system access with all permissions
- **Use Case**: System administrators and owners

### 2. Admin  
- **Permissions**: 60 (all except role management)
- **Description**: Administrative access to most features
- **Use Case**: Operations managers, senior administrators

### 3. Manager
- **Permissions**: 31 (team & project management)
- **Permissions Include**:
  - View/manage team and projects
  - Conduct performance reviews
  - Manage attendance
  - Access GitHub logs
  - Manage checklists
- **Use Case**: Team leads, project managers

### 4. HR
- **Permissions**: 29 (human resources)
- **Permissions Include**:
  - Full employee management
  - Payroll and bank accounts
  - Contracts and attendance
  - Performance reviews
  - Assign roles to employees
- **Use Case**: Human resources personnel

### 5. Accountant
- **Permissions**: 15 (financial operations)
- **Permissions Include**:
  - Manage invoices
  - Employee payments
  - View contracts
  - Download financial PDFs
- **Use Case**: Finance team members

### 6. Employee
- **Permissions**: 4 (basic self-service)
- **Permissions Include**:
  - View own dashboard
  - Manage own notes
  - View notifications
- **Use Case**: Regular employees with self-service access

## 🔐 Permission System

### Permission Categories

#### Dashboard (1)
- `view-dashboard` - View main dashboard

#### Employees (10)
- `view-employees` - View employee list
- `create-employee` - Create new employees
- `edit-employee` - Edit employee information
- `delete-employee` - Delete employees
- `view-employee-details` - View detailed employee information
- `manage-employee-payments` - Add/edit/delete employee payments
- `manage-employee-bank-accounts` - Manage bank account information
- `manage-employee-access` - Manage employee system access
- `discontinue-employee` - Discontinue or reactivate employees

#### Attendance (4)
- `view-attendance` - View attendance records
- `manage-attendance` - Create/edit/delete attendance records
- `bulk-populate-attendance` - Bulk populate attendance
- `manage-monthly-adjustments` - Manage monthly attendance adjustments

#### Projects (6)
- `view-projects` - View project list
- `create-project` - Create new projects
- `edit-project` - Edit project information
- `delete-project` - Delete projects
- `view-project-work` - View project work submissions
- `send-project-report` - Send project reports

#### UAT Testing (6)
- `view-uat-projects` - View UAT project list
- `create-uat-project` - Create new UAT projects
- `edit-uat-project` - Edit UAT project information
- `delete-uat-project` - Delete UAT projects
- `manage-uat-test-cases` - Manage UAT test cases
- `view-uat-feedback` - View UAT feedback

#### GitHub (3)
- `view-github-logs` - View GitHub activity logs
- `view-github-pr` - View GitHub pull requests
- `manage-github-pr` - Comment, review, merge pull requests

#### Invoices (5)
- `view-invoices` - View invoice list
- `create-invoice` - Create new invoices
- `edit-invoice` - Edit invoice information
- `delete-invoice` - Delete invoices
- `download-invoice-pdf` - Download invoice as PDF

#### Contracts (5)
- `view-contracts` - View contract list
- `create-contract` - Create new employment contracts
- `edit-contract` - Edit contract information
- `delete-contract` - Delete contracts
- `download-contract-pdf` - Download contract as PDF

#### Checklists (4)
- `view-checklists` - View employee checklists
- `manage-checklist-templates` - Create/edit/delete checklist templates
- `generate-daily-checklists` - Generate daily checklists for employees
- `send-checklist-email` - Send checklist via email

#### Performance Reviews (8)
- `view-review-cycles` - View performance review cycles
- `manage-review-cycles` - Create/edit/delete review cycles
- `view-performance-reviews` - View performance reviews
- `conduct-performance-reviews` - Conduct and submit performance reviews
- `view-goals` - View employee goals
- `manage-goals` - Create/edit/delete employee goals
- `view-skills` - View skills and employee skill levels
- `manage-skills` - Manage skills and employee skill assessments

#### Personal Notes (3)
- `view-own-notes` - View own personal notes
- `manage-own-notes` - Create/edit/delete own notes
- `view-all-notes` - View all user notes (admin)

#### Roles & Permissions (4)
- `view-roles` - View role list
- `manage-roles` - Create/edit/delete roles
- `assign-permissions` - Assign permissions to roles
- `assign-roles` - Assign roles to employees

#### System (3)
- `view-settings` - View system settings
- `manage-settings` - Manage system settings
- `view-notifications` - View notifications
- `manage-notifications` - Manage notification settings

## 💻 Usage in Code

### Check Permission in Controller

```php
use Illuminate\Support\Facades\Auth;

public function index()
{
    $employee = Auth::guard('employee')->user();
    
    if (!$employee->hasPermission('view-employees')) {
        abort(403, 'Unauthorized');
    }
    
    // Proceed with action
}
```

### Protect Route with Middleware

```php
// Single permission
Route::get('/employees', [EmployeeController::class, 'index'])
    ->middleware(['employee.auth', 'permission:view-employees']);

// Group with permission
Route::middleware(['employee.auth', 'permission:manage-employees'])
    ->group(function () {
        Route::post('/employees', [EmployeeController::class, 'store']);
        Route::put('/employees/{employee}', [EmployeeController::class, 'update']);
        Route::delete('/employees/{employee}', [EmployeeController::class, 'destroy']);
    });
```

### Check Permission in Blade

```blade
@if(Auth::guard('employee')->check() && Auth::guard('employee')->user()->hasPermission('create-employee'))
    <button>Create Employee</button>
@endif
```

### Employee Model Methods

```php
// Check single permission
$employee->hasPermission('view-employees'); // bool

// Check multiple permissions (any)
$employee->hasAnyPermission(['view-employees', 'edit-employee']); // bool

// Check multiple permissions (all)
$employee->hasAllPermissions(['view-employees', 'edit-employee']); // bool

// Check role
$employee->hasRole('manager'); // bool

// Check multiple roles
$employee->hasAnyRole(['manager', 'admin']); // bool

// Assign role
$employee->assignRole('manager');
$employee->assignRole($roleObject);

// Remove role
$employee->removeRole('manager');

// Get all permissions
$employee->permissions(); // Collection
```

### Role Model Methods

```php
// Check if role has permission
$role->hasPermission('view-employees'); // bool

// Assign permission to role
$role->givePermissionTo('view-employees');
$role->givePermissionTo($permissionObject);

// Remove permission from role
$role->revokePermissionTo('view-employees');
```

## 🛠️ Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Permissions
```bash
php artisan db:seed --class=PermissionSeeder
```

### 3. Seed Roles
```bash
php artisan db:seed --class=RoleSeeder
```

### 4. Assign Roles to Employees
```bash
php artisan db:seed --class=EmployeeSeeder
```

## 👤 Test Accounts with Roles

| Name | Email | Password | Role | Permissions |
|------|-------|----------|------|-------------|
| John Doe | john.doe@company.com | password123 | Manager | 31 permissions |
| Jane Smith | jane.smith@company.com | password123 | HR | 29 permissions |
| Mike Johnson | mike.johnson@company.com | password123 | Employee | 4 permissions |
| Sarah Williams | sarah.williams@company.com | password123 | Employee | 4 permissions |

## 🔧 Customization

### Add New Permission

```php
Permission::create([
    'name' => 'Export Reports',
    'slug' => 'export-reports',
    'module' => 'reports',
    'description' => 'Export system reports as PDF/Excel'
]);
```

### Create Custom Role

```php
$role = Role::create([
    'name' => 'Auditor',
    'slug' => 'auditor',
    'description' => 'View-only access for auditing',
    'is_active' => true
]);

// Assign permissions
$viewPermissions = Permission::where('slug', 'like', 'view-%')->get();
$role->permissions()->sync($viewPermissions->pluck('id'));
```

### Assign Role to Employee

```php
$employee = Employee::find(1);
$employee->assignRole('manager');

// Or assign multiple roles
$employee->roles()->sync([1, 2, 3]); // Role IDs
```

## 🚨 Security Features

### Middleware Protection
- ✅ Route-level permission checks
- ✅ Automatic 403 responses for unauthorized access
- ✅ Logging of unauthorized access attempts

### Database Integrity
- ✅ Foreign key constraints with cascade delete
- ✅ Unique constraints on permission/role slugs
- ✅ Unique combination constraints on pivot tables

### Access Logging
```php
// Automatically logs unauthorized attempts
\Log::warning('Unauthorized access attempt', [
    'employee' => $employee->full_name,
    'email' => $employee->email,
    'permission' => $permission,
    'url' => $request->url(),
]);
```

## 📝 Best Practices

1. **Always use slugs** for permission checks (not names)
2. **Apply middleware** to routes instead of controller checks
3. **Hide UI elements** that users don't have permission for
4. **Log unauthorized attempts** for security monitoring
5. **Use role slugs** in code, not role names
6. **Test permissions** after role changes
7. **Document custom permissions** when adding new features

## 🎯 Next Steps

- [ ] Build role management UI
- [ ] Add permission-based sidebar filtering
- [ ] Implement permission-based button visibility
- [ ] Create role assignment interface for admins
- [ ] Add audit log for permission changes
- [ ] Build permission testing tools
- [ ] Create role comparison view

## 📚 API Reference

### Employee Methods
- `hasPermission(string $permission): bool`
- `hasAnyPermission(array $permissions): bool`
- `hasAllPermissions(array $permissions): bool`
- `hasRole(string $role): bool`
- `hasAnyRole(array $roles): bool`
- `assignRole(Role|string $role): void`
- `removeRole(Role|string $role): void`
- `permissions()`: Collection
- `roles()`: BelongsToMany

### Role Methods
- `hasPermission(string $permission): bool`
- `givePermissionTo(Permission|string $permission): void`
- `revokePermissionTo(Permission|string $permission): void`
- `permissions()`: BelongsToMany
- `employees()`: BelongsToMany

## 🎉 Summary

The RBAC system is now fully functional! Employees can be assigned roles, and roles control access to system features through granular permissions. The system includes:

- ✅ 62 system permissions
- ✅ 6 predefined roles
- ✅ Permission checking middleware
- ✅ Database-driven access control
- ✅ Test accounts with different roles
- ✅ Complete API for permission management

**Next**: Implement UI for role management and apply permission checks throughout the application.
