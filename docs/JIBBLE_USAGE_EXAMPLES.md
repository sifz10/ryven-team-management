# Jibble Integration - Usage Examples

## Command Line Usage

### Sync Employees
```bash
# Sync all employees from Jibble
php artisan jibble:sync-employees

# Output example:
# Starting employee sync from Jibble...
# ✓ Created employee: John Doe
# ✓ Updated employee: Jane Smith
# ✅ Sync completed! Synced: 15, Failed: 0
```

### Sync Time Entries
```bash
# Sync last 7 days of time entries
php artisan jibble:sync-time-entries --days=7

# Sync last 30 days
php artisan jibble:sync-time-entries --days=30

# Output example:
# Syncing time entries from last 7 days...
# ✓ Created time entry for John Doe
# ✓ Created time entry for Jane Smith
# ✅ Time entries sync completed! Synced: 42, Failed: 0
```

### Sync Leave Requests
```bash
# Sync all leave requests
php artisan jibble:sync-leave-requests

# Sync only pending requests
php artisan jibble:sync-leave-requests --status=pending

# Sync only approved requests
php artisan jibble:sync-leave-requests --status=approved

# Output example:
# Starting leave requests sync from Jibble...
# ✓ Created leave request for John Doe
# ✓ Updated leave request for Jane Smith
# ✅ Leave requests sync completed! Synced: 8, Failed: 0
```

## Programmatic Usage

### Using JibbleApiService

```php
<?php

use App\Services\JibbleApiService;

$jibble = new JibbleApiService();

// Test connection
if ($jibble->testConnection()) {
    echo "Connected to Jibble!";
}

// Get all members
$members = $jibble->getMembers();
foreach ($members as $member) {
    echo $member['firstName'] . ' ' . $member['lastName'];
}

// Get time entries for date range
$entries = $jibble->getTimeEntries('2024-01-01', '2024-01-31');
foreach ($entries as $entry) {
    $clockIn = $entry['clockInTime'];
    $clockOut = $entry['clockOutTime'];
    echo "Clocked in at: $clockIn, out at: $clockOut";
}

// Get leave requests
$requests = $jibble->getTimeOffRequests(['status' => 'pending']);
foreach ($requests as $request) {
    echo $request['startDate'] . ' to ' . $request['endDate'];
}
```

## Database Queries

### Get Employee with Jibble ID
```php
use App\Models\Employee;

// Find by Jibble ID
$employee = Employee::where('jibble_id', 'jibble-123')->first();

// Get all synced employees
$synced = Employee::whereNotNull('jibble_id')->get();

// Get employees synced in last 24 hours
$recent = Employee::where('jibble_synced_at', '>=', now()->subDay())->get();
```

### Get Time Entries
```php
use App\Models\JibbleTimeEntry;

// Get all time entries
$entries = JibbleTimeEntry::all();

// Get time entries for specific employee
$entries = JibbleTimeEntry::where('employee_id', 1)->get();

// Get time entries for date range
$entries = JibbleTimeEntry::forDateRange('2024-01-01', '2024-01-31')->get();

// Get time entries for employee in date range
$entries = JibbleTimeEntry::forEmployeeInRange(1, '2024-01-01', '2024-01-31')->get();

// Get time entries with hours calculated
foreach ($entries as $entry) {
    echo $entry->hours_worked . " hours";
}

// Sum hours for employee in month
$totalHours = JibbleTimeEntry::where('employee_id', 1)
    ->forDateRange('2024-01-01', '2024-01-31')
    ->sum('duration_minutes') / 60;
```

### Get Leave Requests
```php
use App\Models\JibbleLeaveRequest;

// Get all leave requests
$requests = JibbleLeaveRequest::all();

// Get pending requests
$pending = JibbleLeaveRequest::pending()->get();

// Get approved requests
$approved = JibbleLeaveRequest::approved()->get();

// Get leave requests for specific employee
$requests = JibbleLeaveRequest::forEmployee(1)->get();

// Get leave requests for date range
$requests = JibbleLeaveRequest::forDateRange('2024-01-01', '2024-01-31')->get();

// Get leave requests for employee with filters
$requests = JibbleLeaveRequest::forEmployee(1)
    ->where('status', 'approved')
    ->forDateRange('2024-01-01', '2024-01-31')
    ->get();

// Count days taken
$daysOff = JibbleLeaveRequest::where('employee_id', 1)
    ->approved()
    ->sum('days_count');
```

### Get Sync Logs
```php
use App\Models\JibbleSyncLog;

// Get all syncs
$logs = JibbleSyncLog::all();

// Get recent syncs
$recent = JibbleSyncLog::recent()->get();

// Get latest sync for specific type
$lastEmployeeSync = JibbleSyncLog::where('sync_type', 'employees')->latest()->first();
$lastTimeSync = JibbleSyncLog::where('sync_type', 'time_entries')->latest()->first();
$lastLeaveSync = JibbleSyncLog::where('sync_type', 'time_off')->latest()->first();

// Get failed syncs
$failed = JibbleSyncLog::where('status', 'failed')->get();

// Check last sync status
$lastSync = JibbleSyncLog::where('sync_type', 'employees')->latest()->first();
if ($lastSync->status === 'completed') {
    echo "Last sync completed successfully";
} else {
    echo "Last sync failed: " . $lastSync->error_message;
}
```

## HTTP API Usage

### Test Connection
```bash
curl -X POST http://localhost:8000/jibble/test-connection \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Response:
# {
#   "success": true,
#   "message": "✅ Successfully connected to Jibble API"
# }
```

### Sync Employees
```bash
curl -X POST http://localhost:8000/jibble/sync-employees \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Response:
# {
#   "success": true,
#   "message": "Employee sync started successfully",
#   "output": "..."
# }
```

### Sync Time Entries
```bash
curl -X POST http://localhost:8000/jibble/sync-time-entries \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"days": 7}'

# Response:
# {
#   "success": true,
#   "message": "Time entries sync started successfully",
#   "output": "..."
# }
```

### View Time Entries
```bash
# Get all time entries
curl http://localhost:8000/jibble/time-entries \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Get time entries for specific employee
curl "http://localhost:8000/jibble/time-entries?employee_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Get time entries for date range
curl "http://localhost:8000/jibble/time-entries?start_date=2024-01-01&end_date=2024-01-31" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

### View Leave Requests
```bash
# Get all leave requests
curl http://localhost:8000/jibble/leave-requests \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Get pending leave requests
curl "http://localhost:8000/jibble/leave-requests?status=pending" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"

# Get leave requests for employee
curl "http://localhost:8000/jibble/leave-requests?employee_id=1" \
  -H "Authorization: Bearer YOUR_TOKEN" \
  -H "Accept: application/json"
```

## Real-World Examples

### Calculate Employee Hours Worked This Month
```php
<?php

use App\Models\JibbleTimeEntry;
use Carbon\Carbon;

$employeeId = 1;
$year = 2024;
$month = 1;

$startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
$endDate = $startDate->copy()->endOfMonth();

$totalMinutes = JibbleTimeEntry::where('employee_id', $employeeId)
    ->forDateRange(
        $startDate->format('Y-m-d'),
        $endDate->format('Y-m-d')
    )
    ->sum('duration_minutes');

$totalHours = $totalMinutes / 60;

echo "Employee worked $totalHours hours in " . $startDate->format('F Y');
```

### Get Leave Balance for Employee
```php
<?php

use App\Models\JibbleLeaveRequest;
use Carbon\Carbon;

$employeeId = 1;
$year = 2024;

$startDate = Carbon::createFromDate($year, 1, 1);
$endDate = Carbon::createFromDate($year, 12, 31);

$daysOff = JibbleLeaveRequest::where('employee_id', $employeeId)
    ->approved()
    ->forDateRange(
        $startDate->format('Y-m-d'),
        $endDate->format('Y-m-d')
    )
    ->sum('days_count') ?? 0;

$allocationDays = 20; // Annual allocation
$balance = $allocationDays - $daysOff;

echo "Leave balance: $balance days";
```

### Get Sync Status
```php
<?php

use App\Models\JibbleSyncLog;

$latestSyncs = [
    'employees' => JibbleSyncLog::where('sync_type', 'employees')->latest()->first(),
    'time_entries' => JibbleSyncLog::where('sync_type', 'time_entries')->latest()->first(),
    'time_off' => JibbleSyncLog::where('sync_type', 'time_off')->latest()->first(),
];

foreach ($latestSyncs as $type => $sync) {
    if ($sync) {
        echo "$type: ";
        echo "Status: {$sync->status} | ";
        echo "Synced: {$sync->records_synced} | ";
        echo "Failed: {$sync->records_failed} | ";
        echo "Last: " . $sync->completed_at?->diffForHumans();
    }
}
```

## Blade Templates

### Display Time Entries
```blade
@foreach($entries as $entry)
    <div class="card">
        <div class="card-body">
            <h5>{{ $entry->employee->first_name }} {{ $entry->employee->last_name }}</h5>
            <p>Clock In: {{ $entry->clock_in_time->format('H:i') }}</p>
            <p>Clock Out: {{ $entry->clock_out_time?->format('H:i') ?? 'Still working' }}</p>
            <p>Duration: {{ $entry->hours_worked }} hours</p>
        </div>
    </div>
@endforeach
```

### Display Leave Requests
```blade
@foreach($requests as $request)
    <div class="card">
        <div class="card-body">
            <h5>{{ $request->employee->first_name }} {{ $request->employee->last_name }}</h5>
            <p>From: {{ $request->start_date->format('M d, Y') }}</p>
            <p>To: {{ $request->end_date->format('M d, Y') }}</p>
            <p>Type: {{ $request->leave_type }}</p>
            <p>Status: <span class="badge bg-{{ $request->status === 'approved' ? 'success' : 'warning' }}">
                {{ ucfirst($request->status) }}
            </span></p>
        </div>
    </div>
@endforeach
```

## Debugging

### Check API Connection in Tinker
```php
php artisan tinker
>>> $jibble = new App\Services\JibbleApiService();
>>> $org = $jibble->getOrganization();
>>> dd($org); // View organization data
```

### View Sync Logs
```php
php artisan tinker
>>> App\Models\JibbleSyncLog::latest()->limit(10)->get()->each(function($log) {
    echo "Type: {$log->sync_type} | Status: {$log->status} | Synced: {$log->records_synced}\n";
});
```

### Check Employee Sync Status
```php
php artisan tinker
>>> App\Models\Employee::whereNotNull('jibble_id')->count(); // Total synced
>>> App\Models\Employee::where('jibble_synced_at', '>=', now()->subDay())->count(); // Synced today
```
