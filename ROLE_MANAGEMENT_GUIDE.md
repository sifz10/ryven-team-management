# Role Management System - Quick Reference

## Overview
Complete role and permission management system for the Team Management CRM. Allows administrators to create roles, assign permissions, and manage employee access control.

## Features
✅ **CRUD Operations** for roles
✅ **Permission Assignment** with grouped modules
✅ **Employee Role Assignment** interface
✅ **62 Granular Permissions** across 12 modules
✅ **6 Predefined Roles** with smart defaults
✅ **Dark Mode Support** throughout UI
✅ **Super Admin Protection** (cannot be deleted)

## Access Points

### Main Routes
- **Role List**: `/roles` - View all roles with permission and employee counts
- **Create Role**: `/roles/create` - Create new role with permission selection
- **Edit Role**: `/roles/{id}/edit` - Edit role and update permissions
- **View Role**: `/roles/{id}` - View role details, permissions, and assigned employees
- **Assign Roles**: `/roles/assign` - Bulk assign roles to employees
- **Delete Role**: `DELETE /roles/{id}` - Delete role (except super-admin)

### Navigation
- Sidebar → Management section → "Roles & Permissions"
- Icon: Shield with checkmark

## Role Management Interface

### Role List (`/roles`)
Displays all roles in a table with:
- **Name**: Role name
- **Description**: Role description
- **Permissions**: Badge showing permission count
- **Employees**: Badge showing assigned employee count
- **Status**: Active/Inactive indicator
- **Actions**: View | Edit | Delete buttons

**Create Button**: Top-right corner to add new role

### Create Role (`/roles/create`)
Form fields:
- **Role Name** (required): Display name for the role
- **Description** (optional): Role description
- **Active Status** (checkbox): Whether role is active
- **Permissions**: Grouped by module with checkboxes

**Permissions Grouped By Module**:
- Dashboard (1 permission)
- Employees (10 permissions)
- Attendance (4 permissions)
- Projects (6 permissions)
- UAT (6 permissions)
- GitHub (3 permissions)
- Invoices (5 permissions)
- Contracts (5 permissions)
- Checklists (4 permissions)
- Performance Reviews (8 permissions)
- Personal Notes (3 permissions)
- Roles (4 permissions)
- Settings (3 permissions)

### Edit Role (`/roles/{id}/edit`)
Same as create form, but:
- Pre-filled with current role data
- Role slug auto-updated from name
- Cannot delete Super Admin role
- Permission checkboxes pre-selected based on current assignments

### View Role (`/roles/{id}`)
Read-only view showing:
- **Role Information**: Name, status, description
- **Permissions**: All assigned permissions grouped by module (with checkmarks)
- **Employees**: Table of employees with this role showing name, email, position, status

**Edit Button**: Top-right to modify role

### Assign Roles (`/roles/assign`)
Bulk role assignment interface:
- Shows all employees in expandable cards
- Each card displays:
  - Employee name, email, position
  - Checkboxes for all available roles
  - Current roles displayed as badges
  - Individual "Update Roles" button per employee

## Controller Methods

### RoleController

```php
// Display all roles
public function index()

// Show create form
public function create()

// Store new role
public function store(Request $request)
// Validates: name (required, unique), description, is_active, permissions array

// Display single role
public function show(Role $role)

// Show edit form
public function edit(Role $role)

// Update existing role
public function update(Request $request, Role $role)

// Delete role
public function destroy(Role $role)
// Protected: Cannot delete super-admin role

// Show role assignment form
public function assignForm()

// Assign roles to employee
public function assignRoles(Request $request, Employee $employee)
```

## Validation Rules

### Store/Update Role
```php
'name' => 'required|string|max:255|unique:roles,name',
'description' => 'nullable|string',
'is_active' => 'boolean',
'permissions' => 'array',
'permissions.*' => 'exists:permissions,id',
```

### Assign Roles
```php
'roles' => 'required|array',
'roles.*' => 'exists:roles,id',
```

## Database Structure

### Tables
- **roles**: id, name, slug, description, is_active, timestamps
- **permissions**: id, name, slug, module, description, timestamps
- **permission_role**: permission_id, role_id (pivot)
- **employee_role**: employee_id, role_id (pivot)

### Relationships
```php
// Role Model
public function permissions(): BelongsToMany
public function employees(): BelongsToMany

// Permission Model
public function roles(): BelongsToMany

// Employee Model
public function roles(): BelongsToMany
```

## Permission Checking

### In Controllers
```php
// Check single permission
if (auth()->user()->hasPermission('view-employees')) {
    // Allow access
}

// Check any permission
if (auth()->user()->hasAnyPermission(['view-employees', 'edit-employees'])) {
    // Allow access
}

// Check all permissions
if (auth()->user()->hasAllPermissions(['view-employees', 'edit-employees'])) {
    // Allow access
}
```

### Via Middleware
```php
// In routes/web.php
Route::get('/employees', [EmployeeController::class, 'index'])
    ->middleware(['employee.auth', 'permission:view-employees']);
```

### In Blade Views
```blade
@if(auth()->user()->hasPermission('create-employees'))
    <a href="{{ route('employees.create') }}" class="btn">Create Employee</a>
@endif
```

## Predefined Roles

### 1. Super Admin
- **Permissions**: All 62 permissions
- **Purpose**: Full system access
- **Protected**: Cannot be deleted

### 2. Admin
- **Permissions**: 60 permissions (all except manage-roles, assign-permissions)
- **Purpose**: Day-to-day administration

### 3. Manager
- **Permissions**: 31 permissions
- **Focus**: Team/project management, attendance, invoices
- **Can**: View employees, manage projects, approve attendance

### 4. HR
- **Permissions**: 29 permissions
- **Focus**: Employee management, contracts, performance reviews
- **Can**: Full employee CRUD, manage contracts, conduct reviews

### 5. Accountant
- **Permissions**: 15 permissions
- **Focus**: Financial operations
- **Can**: Manage invoices, employee payments, bank accounts

### 6. Employee
- **Permissions**: 4 permissions
- **Focus**: Self-service only
- **Can**: View dashboard, own profile, own goals, own notes

## Security Features

1. **Super Admin Protection**: Cannot delete super-admin role
2. **Permission Validation**: All permissions validated against database
3. **Role Validation**: Role assignments validated on update
4. **Middleware Protection**: Routes protected at middleware level
5. **View-Level Checks**: UI elements hidden based on permissions
6. **Unique Constraints**: Ensures no duplicate role-permission or employee-role assignments

## UI Components

### Colors & Styling
- **Primary Action**: Black button with white text (dark: inverted)
- **Status Badges**:
  - Active: Green
  - Inactive: Red
  - Permissions Count: Blue
  - Employees Count: Purple
- **Hover Effects**: 200ms transition on all interactive elements
- **Dark Mode**: Full support with inverted colors

### Responsive Design
- **Mobile**: Single column layout
- **Tablet**: 2-column permission grid
- **Desktop**: 3-column permission grid

## Next Steps

### Implementation Checklist
1. ✅ RoleController implementation
2. ✅ Role management views
3. ✅ Routes registration
4. ✅ Sidebar navigation
5. ⏳ Apply permission middleware to all routes
6. ⏳ Update sidebar with permission checks
7. ⏳ Update action buttons with permission checks

### Route Protection
Apply middleware to existing routes:
```php
// Example: Employees routes
Route::resource('employees', EmployeeController::class)
    ->middleware([
        'index' => 'permission:view-employees',
        'create' => 'permission:create-employees',
        'store' => 'permission:create-employees',
        'show' => 'permission:view-employees',
        'edit' => 'permission:edit-employees',
        'update' => 'permission:edit-employees',
        'destroy' => 'permission:delete-employees',
    ]);
```

### View Updates
```blade
<!-- Sidebar: Show menu item only if user has permission -->
@if(auth()->user()->hasPermission('view-employees'))
    <a href="{{ route('employees.index') }}">Employees</a>
@endif

<!-- Action button: Show only if user can create -->
@if(auth()->user()->hasPermission('create-employees'))
    <a href="{{ route('employees.create') }}" class="btn">Create Employee</a>
@endif
```

## Testing

### Test Role Creation
1. Navigate to `/roles`
2. Click "Create Role"
3. Fill in name, description
4. Select permissions from various modules
5. Click "Create Role"
6. Verify role appears in list with correct permission count

### Test Permission Assignment
1. Navigate to `/roles/{id}/edit`
2. Check/uncheck permissions
3. Click "Update Role"
4. View role to confirm permissions updated

### Test Employee Role Assignment
1. Navigate to `/roles/assign`
2. Select roles for an employee
3. Click "Update Roles"
4. Verify employee has correct roles in role detail view

## Troubleshooting

### Role not appearing after creation
- Check validation errors in form
- Ensure role name is unique
- Verify permissions exist in database

### Permission check failing
- Verify employee has role assigned
- Confirm role has permission
- Check permission slug matches exactly (kebab-case)
- Clear cache: `php artisan cache:clear`

### Cannot delete role
- Super Admin role is protected by design
- Check if role has assigned employees (safe to delete with employees)
- Verify user has 'delete-roles' permission

## Related Documentation
- `RBAC_SYSTEM_GUIDE.md` - Complete RBAC implementation guide
- `EMPLOYEE_LOGIN_GUIDE.md` - Employee authentication system
- Database seeders: `database/seeders/RoleSeeder.php`, `database/seeders/PermissionSeeder.php`
