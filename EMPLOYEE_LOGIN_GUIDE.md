# Employee Login System - Implementation Guide

## Overview
The Team Management System now supports dual authentication: **Admin** and **Employee** login. Employees can access their own portal with limited functionality while admins retain full system access.

## 🔑 Key Features

### Employee Portal
- ✅ Secure authentication with email/password
- ✅ Personal dashboard with activity stats
- ✅ View payment history
- ✅ Track attendance records
- ✅ GitHub activity overview
- ✅ Daily checklist management
- ✅ Performance reviews & goals
- ✅ Profile management with password change

### Admin Portal
- ✅ Full access to all system features
- ✅ Employee management (CRUD)
- ✅ Attendance tracking
- ✅ Project management
- ✅ UAT testing
- ✅ GitHub integration
- ✅ Invoices & contracts
- ✅ Performance review system

## 🚀 Authentication System

### Multi-Guard Setup
The system uses Laravel's multi-authentication feature:

**Guards:**
- `web` - Admin authentication (User model)
- `employee` - Employee authentication (Employee model)

**Configuration:** `config/auth.php`

### Employee Model
The `Employee` model extends `Authenticatable` to support login functionality:

```php
class Employee extends Authenticatable
{
    use SoftDeletes, Notifiable;
    
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['password' => 'hashed'];
    
    public function canLogin(): bool {
        return $this->is_active && 
               !$this->discontinued_at && 
               !empty($this->password);
    }
}
```

## 📁 File Structure

### Controllers
```
app/Http/Controllers/Employee/
├── Auth/
│   └── LoginController.php          # Employee authentication
├── DashboardController.php          # Employee dashboard
└── ProfileController.php            # Employee profile management
```

### Views
```
resources/views/employee/
├── auth/
│   └── login.blade.php              # Employee login page
├── dashboard.blade.php              # Employee dashboard
└── profile/
    └── edit.blade.php               # Employee profile edit
```

### Middleware
```
app/Http/Middleware/
└── EmployeeAuth.php                 # Employee authentication middleware
```

### Routes
```php
// Employee Authentication
Route::prefix('employee')->name('employee.')->group(function () {
    // Guest routes
    Route::middleware('guest:employee')->group(function () {
        Route::get('/login', [LoginController::class, 'create']);
        Route::post('/login', [LoginController::class, 'store']);
    });

    // Authenticated routes
    Route::middleware('employee.auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'destroy']);
        Route::get('/dashboard', [DashboardController::class, 'index']);
        Route::get('/profile', [ProfileController::class, 'edit']);
        Route::patch('/profile', [ProfileController::class, 'update']);
    });
});
```

## 🔐 Database Schema

### Migration: `add_password_to_employees_table`
Adds authentication fields to employees table:

```php
$table->string('password')->nullable();
$table->rememberToken();
$table->timestamp('email_verified_at')->nullable();
$table->boolean('is_active')->default(true);
```

### Required Fields
- **email**: Unique identifier for login
- **password**: Hashed password
- **is_active**: Controls login access
- **discontinued_at**: Prevents login for discontinued employees

## 👤 Test Accounts

Four test employee accounts are created via `EmployeeSeeder`:

| Name | Email | Password | Role |
|------|-------|----------|------|
| John Doe | john.doe@company.com | password123 | Senior Developer |
| Jane Smith | jane.smith@company.com | password123 | Product Manager |
| Mike Johnson | mike.johnson@company.com | password123 | UI/UX Designer |
| Sarah Williams | sarah.williams@company.com | password123 | QA Engineer |

**To seed accounts:**
```bash
php artisan db:seed --class=EmployeeSeeder
```

## 🎨 UI/UX Features

### Responsive Design
- **Mobile**: Sidebar hidden, hamburger menu
- **Desktop**: Sidebar visible, collapsible

### Dark Mode Support
- Full dark mode compatibility
- Pure black/white active states
- Smooth transitions

### Differentiation
The system automatically detects user type and adapts:

**Sidebar:**
- Employees: Dashboard, Profile only
- Admins: Full menu with all features

**Topbar:**
- Shows account type badge (Employee/Admin)
- Context-aware navigation links
- Separate logout routes per guard

## 🔧 Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Test Accounts
```bash
php artisan db:seed --class=EmployeeSeeder
```

### 3. Clear Caches
```bash
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 4. Access Employee Portal
Navigate to: `https://yourdomain.com/employee/login`

## 🛡️ Security Features

### Authentication
- Password hashing using Laravel's Hash facade
- Remember Me functionality
- CSRF protection on all forms
- Session regeneration on login

### Authorization
- Middleware checks on all employee routes
- Active status validation
- Discontinued employee prevention
- Guard separation (admin/employee)

### Session Management
- Separate sessions per guard
- Automatic logout on account deactivation
- Session invalidation on logout

## 📊 Employee Dashboard Stats

The dashboard displays:

1. **Total Payments**: Lifetime payment count
2. **Attendance**: Current month percentage
3. **GitHub Activities**: Total contribution count
4. **Active Goals**: In-progress goals count

### Recent Activity Sections
- Last 5 payments with amount & date
- Last 5 GitHub activities
- Today's checklist (if available)

## 🔄 Future Enhancements

### Planned Features
- [ ] Password reset via email
- [ ] Two-factor authentication (2FA)
- [ ] Employee notifications
- [ ] Team collaboration features
- [ ] Time tracking integration
- [ ] Mobile app support
- [ ] Single Sign-On (SSO)

### Integration Points
- Link with attendance system for check-in/out
- Real-time notifications via Laravel Reverb
- GitHub webhook integration for activity tracking
- Performance review workflow

## 🐛 Troubleshooting

### Common Issues

**Issue: Employee can't login**
- Check `is_active` is true
- Verify `discontinued_at` is null
- Ensure password is set (not nullable)
- Clear config cache

**Issue: Sidebar shows wrong menu**
- Clear view cache: `php artisan view:clear`
- Check guard detection in layouts
- Verify middleware is applied

**Issue: Session conflicts**
- Each guard uses separate session keys
- Check `config/session.php` settings
- Verify guard names match in middleware

## 📝 Code Examples

### Check Employee Login Status
```php
if (Auth::guard('employee')->check()) {
    $employee = Auth::guard('employee')->user();
    echo "Welcome {$employee->full_name}!";
}
```

### Protect Route for Employees Only
```php
Route::middleware('employee.auth')->group(function () {
    // Employee-only routes
});
```

### Detect User Type in Blade
```blade
@if(Auth::guard('employee')->check())
    <!-- Employee content -->
@elseif(Auth::guard('web')->check())
    <!-- Admin content -->
@endif
```

## 🎯 Best Practices

1. **Always check guard explicitly** in shared layouts
2. **Use middleware** instead of manual checks
3. **Keep employee/admin routes separate** for clarity
4. **Validate employee status** on each request
5. **Log authentication events** for security auditing

## 📚 Additional Resources

- [Laravel Multi-Auth Documentation](https://laravel.com/docs/authentication#authenticating-users)
- [Middleware Documentation](https://laravel.com/docs/middleware)
- [Guards & Providers](https://laravel.com/docs/authentication#introduction)

---

## 🎉 Summary

The employee login system is now fully operational! Employees can:
- Log in securely with their email/password
- View their personalized dashboard
- Access their payment & attendance history
- Track their GitHub contributions
- Manage their profile & password

Admin functionality remains unchanged with full system access. The dual authentication system ensures proper separation of concerns while maintaining a seamless user experience.

**Need help?** Check the troubleshooting section or contact the development team.
