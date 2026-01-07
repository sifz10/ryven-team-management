# Jibble API Integration - Phase 1 Setup Guide

## Overview
Complete Jibble API integration for Phase 1 including:
- ✅ Employee sync from Jibble
- ✅ Time tracking/clock entries integration  
- ✅ Leave management sync

## Files Created

### Services
- `app/Services/JibbleApiService.php` - Main Jibble API client with methods for:
  - Getting members (employees)
  - Getting time entries (clock in/out)
  - Getting leave requests
  - Getting holidays
  - Testing API connection

### Configuration
- `config/jibble.php` - Jibble configuration file

### Database Migrations
- `database/migrations/2026_01_07_000001_create_jibble_sync_logs_table.php` - Track sync history
- `database/migrations/2026_01_07_000002_add_jibble_fields_to_employees_table.php` - Add Jibble columns to employees
- `database/migrations/2026_01_07_000003_create_jibble_time_entries_table.php` - Store time entries
- `database/migrations/2026_01_07_000004_create_jibble_leave_requests_table.php` - Store leave requests

### Models
- `app/Models/JibbleSyncLog.php` - Track sync operations
- `app/Models/JibbleTimeEntry.php` - Time entries from Jibble
- `app/Models/JibbleLeaveRequest.php` - Leave requests from Jibble

### Console Commands
- `app/Console/Commands/SyncJibbleEmployees.php` - Sync employees from Jibble
- `app/Console/Commands/SyncJibbleTimeEntries.php` - Sync clock in/out entries
- `app/Console/Commands/SyncJibbleLeaveRequests.php` - Sync leave requests

### Controller
- `app/Http/Controllers/JibbleController.php` - UI endpoints for Jibble management

## Setup Instructions

### Step 1: Get Jibble Credentials

1. Go to Jibble.io and create an account/login
2. Get your **Access Token** and **Organization ID**

### Step 2: Configure Environment

Add to your `.env` file:

```env
JIBBLE_ENABLED=true
JIBBLE_ACCESS_TOKEN=your_access_token_here
JIBBLE_ORGANIZATION_ID=your_organization_id_here
JIBBLE_SYNC_EMPLOYEES=true
JIBBLE_SYNC_TIME_ENTRIES=true
JIBBLE_SYNC_TIME_OFF=true
```

### Step 3: Run Migrations

```bash
php artisan migrate
```

This creates:
- `jibble_sync_logs` table - for tracking syncs
- `jibble_time_entries` table - for storing time entries
- `jibble_leave_requests` table - for storing leave requests
- Adds columns to `employees` table for Jibble data

### Step 4: Test Connection

```bash
# Via artisan
php artisan tinker
$service = new App\Services\JibbleApiService();
$service->testConnection(); // Should return true
```

Or via HTTP:
```bash
POST /jibble/test-connection
```

### Step 5: Run Initial Sync

```bash
# Sync employees from Jibble
php artisan jibble:sync-employees

# Sync time entries (last 7 days)
php artisan jibble:sync-time-entries --days=7

# Sync leave requests
php artisan jibble:sync-leave-requests
```

## Available Commands

### Sync Employees
```bash
php artisan jibble:sync-employees
```
Syncs employees from Jibble into your system. Creates new employees or updates existing ones based on email match.

### Sync Time Entries
```bash
php artisan jibble:sync-time-entries --days=7
```
Syncs clock in/out entries. Automatically creates Attendance records for each time entry.
- `--days=7` - Number of days to sync (default: 7)

### Sync Leave Requests
```bash
php artisan jibble:sync-leave-requests --status=all
```
Syncs leave/time off requests.
- `--status=all` - Filter by status (all, pending, approved)

## Routes (Add to routes/web.php)

```php
Route::middleware(['auth', 'verified'])->prefix('jibble')->group(function () {
    Route::get('dashboard', [JibbleController::class, 'dashboard'])->name('jibble.dashboard');
    Route::get('sync-history', [JibbleController::class, 'syncHistory'])->name('jibble.sync-history');
    Route::get('time-entries', [JibbleController::class, 'timeEntries'])->name('jibble.time-entries');
    Route::get('leave-requests', [JibbleController::class, 'leaveRequests'])->name('jibble.leave-requests');
    
    Route::post('test-connection', [JibbleController::class, 'testConnection'])->name('jibble.test-connection');
    Route::post('sync-employees', [JibbleController::class, 'syncEmployees'])->name('jibble.sync-employees');
    Route::post('sync-time-entries', [JibbleController::class, 'syncTimeEntries'])->name('jibble.sync-time-entries');
    Route::post('sync-leave-requests', [JibbleController::class, 'syncLeaveRequests'])->name('jibble.sync-leave-requests');
});
```

## Scheduled Syncs (Optional)

Add to `app/Console/Kernel.php`:

```php
protected function schedule(Schedule $schedule)
{
    // Sync employees daily
    $schedule->command('jibble:sync-employees')
        ->daily()
        ->onSuccess(function () {
            // Log success
        });

    // Sync time entries hourly
    $schedule->command('jibble:sync-time-entries')
        ->hourly();

    // Sync leave requests daily
    $schedule->command('jibble:sync-leave-requests')
        ->daily();
}
```

## API Service Methods

### Get Members (Employees)
```php
$service = new JibbleApiService();
$members = $service->getMembers();
```

### Get Time Entries
```php
$entries = $service->getTimeEntries('2024-01-01', '2024-01-31', memberId: '123');
```

### Get Leave Requests
```php
$requests = $service->getTimeOffRequests(['status' => 'pending']);
```

### Get Holidays
```php
$holidays = $service->getHolidays();
```

## Data Flow

### Employee Sync
```
Jibble Members API 
    ↓
JibbleApiService::getMembers()
    ↓
SyncJibbleEmployees Command
    ↓
employees table (with jibble_id, jibble_data)
```

### Time Entry Sync
```
Jibble Time Entries API
    ↓
JibbleApiService::getTimeEntries()
    ↓
SyncJibbleTimeEntries Command
    ↓
jibble_time_entries table
    ↓
attendance table (auto-synced)
```

### Leave Request Sync
```
Jibble Time Off Requests API
    ↓
JibbleApiService::getTimeOffRequests()
    ↓
SyncJibbleLeaveRequests Command
    ↓
jibble_leave_requests table
```

## Error Handling

All sync operations:
- Create `JibbleSyncLog` records tracking status
- Log errors to Laravel logs
- Return detailed error messages
- Support retry via re-running commands

View sync history:
```php
JibbleSyncLog::latest()->get();
JibbleSyncLog::where('sync_type', 'employees')->latest()->first();
```

## Next Steps (Phase 2)

- Add Timesheet approval workflows
- Implement automatic payroll report generation
- Add project time tracking
- Integrate with invoice system

## Troubleshooting

### "Jibble integration is not enabled"
```env
JIBBLE_ENABLED=true
```

### "Employee with Jibble ID not found"
- First run employee sync: `php artisan jibble:sync-employees`
- Verify Jibble member has matching email

### "Failed to connect to Jibble API"
- Check `JIBBLE_ACCESS_TOKEN` is correct
- Verify `JIBBLE_ORGANIZATION_ID` exists
- Check internet connection

### "No members found from Jibble API"
- Verify your Jibble account has members added
- Check API token has proper permissions
