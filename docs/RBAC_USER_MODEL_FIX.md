# RBAC User Model Fix

## Issue
The application was throwing `BadMethodCallException: Call to undefined method App\Models\User::hasPermission()` when accessing the dashboard as an admin user.

## Root Cause
The RBAC permission checking methods (`hasPermission()`, `hasRole()`, etc.) were only implemented on the `Employee` model, but the sidebar layout (`sidebar.blade.php`) calls these methods on `auth()->user()`, which returns a `User` model instance when logged in through the admin portal (web guard).

## Solution
Added the same RBAC methods to the `User` model with a **super admin by default** approach:

### Implementation
- All `User` model instances (admin portal users) automatically have **all permissions**
- This is appropriate because:
  - Admin portal users are system administrators
  - They need full access to manage the system
  - The RBAC system is primarily for `Employee` model users (employee portal)

### Methods Added to User Model

```php
/**
 * Check if user has a specific permission.
 * Users in the admin portal have all permissions by default.
 */
public function hasPermission(string $permission): bool
{
    // Admin users have all permissions
    return true;
}

/**
 * Check if user has any of the given permissions.
 * Users in the admin portal have all permissions by default.
 */
public function hasAnyPermission(array $permissions): bool
{
    // Admin users have all permissions
    return true;
}

/**
 * Check if user has all of the given permissions.
 * Users in the admin portal have all permissions by default.
 */
public function hasAllPermissions(array $permissions): bool
{
    // Admin users have all permissions
    return true;
}

/**
 * Check if user has a specific role.
 * Users in the admin portal are considered super admins.
 */
public function hasRole(string $role): bool
{
    // Admin users have super admin role
    return $role === 'super-admin';
}

/**
 * Check if user has any of the given roles.
 * Users in the admin portal are considered super admins.
 */
public function hasAnyRole(array $roles): bool
{
    // Admin users have super admin role
    return in_array('super-admin', $roles);
}

/**
 * Check if user has all of the given roles.
 * Users in the admin portal are considered super admins.
 */
public function hasAllRoles(array $roles): bool
{
    // Admin users have super admin role
    // Only return true if all requested roles are 'super-admin'
    foreach ($roles as $role) {
        if ($role !== 'super-admin') {
            return false;
        }
    }
    return true;
}
```

## System Architecture

### Two Authentication Systems

**1. Admin Portal (User Model - `web` guard)**
- Model: `App\Models\User`
- Login: `/login`
- Dashboard: `/dashboard`
- Permissions: **All permissions granted automatically**
- Use Case: System administrators, full access

**2. Employee Portal (Employee Model - `employee` guard)**
- Model: `App\Models\Employee`
- Login: `/employee/login`
- Dashboard: `/employee/dashboard`
- Permissions: **Role-based via employee_role pivot table**
- Use Case: Regular employees, restricted access based on assigned roles

### Permission Flow

```
Sidebar Permission Check:
auth()->user()->hasPermission('view-employees')
        ↓
User Model (Admin)?          Employee Model?
        ↓                            ↓
    Return TRUE              Check roles → permissions
    (Super Admin)                    ↓
                          Return based on assigned roles
```

## Alternative Approaches Considered

### Option 1: Create role_user Pivot Table ❌
- Would require additional migration
- Adds complexity for simple admin access
- Admins don't need granular permissions

### Option 2: Modify employee_role Table ❌
- Would require changing column names to be polymorphic
- More complex queries
- Breaks existing Employee model relationships

### Option 3: Super Admin by Default ✅ (Chosen)
- Simple and clean
- Appropriate for admin portal use case
- No database changes needed
- Easy to understand and maintain

## Testing

### Test Admin Portal
1. Login as admin: `http://127.0.0.1:8000/login`
2. Access dashboard - should work without errors
3. All sidebar items should be visible
4. All action buttons should be visible

### Test Employee Portal
1. Login as employee: `http://127.0.0.1:8000/employee/login`
2. Access employee dashboard
3. Sidebar items filtered by assigned roles
4. Action buttons filtered by permissions

## Files Modified

1. `app/Models/User.php` - Added RBAC methods with super admin logic

## Verification

Run this command to ensure no cached views cause issues:
```bash
php artisan view:clear
```

## Status
✅ **FIXED** - Admin users can now access all features without permission errors
