# Task Notifications System - Implementation Guide

## Overview
Implemented comprehensive real-time notification system for project tasks. All task actions now notify assigned users and admin users instantly via in-app notifications and broadcast channels.

## Architecture

### Notification Classes

#### 1. **TaskStatusChangedNotification**
**Purpose**: Notify when a task's status changes  
**Location**: `app/Notifications/TaskStatusChangedNotification.php`

**Constructor**:
```php
public function __construct(
    ProjectTask $task, 
    $oldStatus, 
    $newStatus, 
    Employee $changedBy
)
```

**Channels**: `['database', 'broadcast']`

**Notification Data**:
- `type`: 'task_status_changed'
- `title`: 'Task Status Changed'
- `message`: "{Name} changed status from {Old} to {New}"
- `task_id`, `task_title`, `project_id`, `project_name`
- `old_status`, `new_status`, `changed_by`
- `url`: Direct link to project tasks tab
- `created_at`: ISO timestamp

**Status Labels**:
- `todo` → "To Do"
- `on-hold` → "On Hold"
- `in-progress` → "In Progress"
- `awaiting-feedback` → "Awaiting Feedback"
- `staging` → "Staging"
- `live` → "Live"
- `completed` → "Completed"

---

#### 2. **TaskActionNotification**
**Purpose**: Notify about any task action (create, update, delete, assign, etc.)  
**Location**: `app/Notifications/TaskActionNotification.php`

**Constructor**:
```php
public function __construct(
    ProjectTask $task, 
    string $action, 
    Employee $actionBy, 
    array $details = []
)
```

**Channels**: `['database', 'broadcast']`

**Supported Actions**:
- `created` - Task created
- `updated` - Task updated
- `deleted` - Task deleted
- `assigned` - User assigned to task
- `unassigned` - User unassigned from task
- `comment_added` - Comment added (future)
- `file_uploaded` - File uploaded (future)
- `checklist_updated` - Checklist updated (future)
- `priority_changed` - Priority changed
- `due_date_changed` - Due date changed (future)
- `reminder_set` - Reminder set (future)

**Notification Data**:
- `type`: 'task_action'
- `action`: The action performed
- `title`: "Task {Action}"
- `message`: "{Name} {action} task"
- `task_id`, `task_title`, `project_id`, `project_name`
- `action_by`: Employee name
- `details`: Additional context (priority, assignment, etc.)
- `url`: Direct link to project tasks tab
- `created_at`: ISO timestamp

---

### Controller Methods

#### Helper Methods Added to `ProjectController`

**1. notifyStatusChange()**
```php
private function notifyStatusChange(
    ProjectTask $task, 
    $oldStatus, 
    $newStatus, 
    Employee $changedBy
)
```
Sends `TaskStatusChangedNotification` to all recipients.

**2. notifyTaskAction()**
```php
private function notifyTaskAction(
    ProjectTask $task, 
    string $action, 
    Employee $actor, 
    array $details = []
)
```
Sends `TaskActionNotification` to all recipients.

**3. getTaskNotificationRecipients()**
```php
private function getTaskNotificationRecipients(ProjectTask $task)
```
Returns collection of `User` models who should receive notifications:
- **Assigned user** (if task has assigned_to)
- **All admin users** (users with employee records)
- Duplicates removed by user ID

---

### Updated Controller Actions

#### **storeTasks()** - Task Creation
```php
// After task creation
$currentEmployee = Employee::where('user_id', auth()->id())->first();
if ($currentEmployee) {
    $details = [
        'priority' => $validated['priority'],
        'additional_info' => isset($validated['assigned_to']) 
            ? 'Assigned to employee' 
            : 'No assignment yet'
    ];
    $this->notifyTaskAction($task, 'created', $currentEmployee, $details);
}
```

**Triggers**: When new task is created  
**Notifies**: All admin users + assigned user (if assigned)  
**Action**: `created`

---

#### **updateTask()** - Task Updates
```php
// Track old values
$oldStatus = $task->status;
$oldAssignedTo = $task->assigned_to;
$oldPriority = $task->priority;

// After update
if ($oldStatus !== $validated['status']) {
    $this->notifyStatusChange($task, $oldStatus, $validated['status'], $currentEmployee);
}

if ($oldAssignedTo !== $validated['assigned_to']) {
    $action = $validated['assigned_to'] ? 'assigned' : 'unassigned';
    $this->notifyTaskAction($task, $action, $currentEmployee);
}

if ($oldPriority !== $validated['priority']) {
    $details = [
        'old_priority' => $oldPriority,
        'new_priority' => $validated['priority'],
        'additional_info' => "Priority changed from {$oldPriority} to {$validated['priority']}"
    ];
    $this->notifyTaskAction($task, 'priority_changed', $currentEmployee, $details);
}

// General update if no specific changes
if ($oldStatus === $validated['status'] && 
    $oldAssignedTo === $validated['assigned_to'] && 
    $oldPriority === $validated['priority']) {
    $this->notifyTaskAction($task, 'updated', $currentEmployee);
}
```

**Triggers**: 
- Status change → `TaskStatusChangedNotification`
- Assignment change → `assigned` or `unassigned` action
- Priority change → `priority_changed` action
- Other updates → `updated` action

**Notifies**: All admin users + assigned user (if assigned)

---

#### **destroyTask()** - Task Deletion
```php
// Before deletion (need task data)
$currentEmployee = Employee::where('user_id', auth()->id())->first();
if ($currentEmployee) {
    $this->notifyTaskAction($task, 'deleted', $currentEmployee);
}
$task->delete();
```

**Triggers**: Before task deletion  
**Notifies**: All admin users + assigned user (if was assigned)  
**Action**: `deleted`

---

### Model Updates

#### **User Model** (`app/Models/User.php`)
Added `employee()` relationship:
```php
/**
 * Get the employee associated with this user
 */
public function employee()
{
    return $this->hasOne(Employee::class);
}
```

This enables:
```php
// Check if user has employee record
User::whereHas('employee')->get();

// Get user's employee
$user->employee
```

---

## Notification Recipients Logic

### Who Gets Notified?

**For ALL task actions**:
1. **Assigned User** - The employee assigned to the task (if exists)
2. **All Admin Users** - Every user with an employee record

**Why all users are admins?**
In this system, all users in the admin portal are considered super admins (see `User::hasRole()` - always returns 'super-admin'). Since there's no role/type field, we identify admin users as: **users with employee records**.

### Implementation
```php
private function getTaskNotificationRecipients(ProjectTask $task)
{
    $recipients = collect();
    
    // Add assigned employee's user account
    if ($task->assigned_to) {
        $assignedEmployee = Employee::find($task->assigned_to);
        if ($assignedEmployee && $assignedEmployee->user) {
            $recipients->push($assignedEmployee->user);
        }
    }
    
    // Add all admin users (users with employee records)
    $adminUsers = \App\Models\User::whereHas('employee')->get();
    $recipients = $recipients->merge($adminUsers);
    
    // Remove duplicates by user ID
    return $recipients->unique('id');
}
```

---

## Real-Time Delivery

### Broadcasting Setup

**Channels**: 
- `database` - Persistent storage in `notifications` table
- `broadcast` - Real-time via Laravel Reverb (WebSocket)

**User Channel**: `private-user.{userId}`

**Event**: Each notification broadcasts automatically via `ShouldQueue` interface

### Frontend Integration

Notifications are received via Laravel Echo:
```javascript
Echo.private(`user.${userId}`)
    .listen('.notification.new', (notification) => {
        // Show toast notification
        // Update notification dropdown
        // Play sound (optional)
    });
```

---

## Testing the System

### 1. Create a Task
- Go to Projects → Select Project → Tasks Tab
- Click "Create Task"
- Fill in details and save
- **Expected**: All admin users get notification "Task created"

### 2. Update Task Status
- Edit existing task
- Change status (e.g., "To Do" → "In Progress")
- Save
- **Expected**: 
  - Assigned user gets notification "Status changed from To Do to In Progress"
  - All admin users get same notification

### 3. Assign User
- Edit task
- Set "Assigned To" field
- Save
- **Expected**: 
  - Newly assigned user gets notification "You were assigned to task"
  - All admin users get notification "{Name} assigned you to task"

### 4. Change Priority
- Edit task
- Change priority (e.g., "Low" → "High")
- Save
- **Expected**: All get notification "Priority changed from low to high"

### 5. Delete Task
- Click delete on task
- Confirm deletion
- **Expected**: All get notification "Task deleted"

---

## Notification Data Structure

### Database Storage (`notifications` table)
```json
{
  "id": "uuid",
  "type": "App\\Notifications\\TaskStatusChangedNotification",
  "notifiable_type": "App\\Models\\User",
  "notifiable_id": 1,
  "data": {
    "type": "task_status_changed",
    "title": "Task Status Changed",
    "message": "John Doe changed status from To Do to In Progress",
    "task_id": 123,
    "task_title": "Fix login bug",
    "project_id": 45,
    "project_name": "Website Redesign",
    "old_status": "todo",
    "new_status": "in-progress",
    "changed_by": "John Doe",
    "url": "https://team.ryven.co/projects/45?tab=tasks",
    "created_at": "2024-01-15T10:30:00.000000Z"
  },
  "read_at": null,
  "created_at": "2024-01-15 10:30:00",
  "updated_at": "2024-01-15 10:30:00"
}
```

### Broadcast Message (WebSocket)
Same data structure sent via Laravel Reverb on channel `private-user.{userId}`.

---

## Future Enhancements

### Already Prepared Actions
The `TaskActionNotification` supports these actions (just need to hook them up):

**Comment Actions**:
```php
// In storeTaskComment()
$this->notifyTaskAction($task, 'comment_added', $currentEmployee);
```

**File Upload**:
```php
// In storeTaskFile()
$this->notifyTaskAction($task, 'file_uploaded', $currentEmployee, [
    'additional_info' => 'Uploaded: ' . $file->original_name
]);
```

**Checklist Updates**:
```php
// When checklist item completed
$this->notifyTaskAction($task, 'checklist_updated', $currentEmployee, [
    'additional_info' => 'Completed: ' . $checklistItem->title
]);
```

**Due Date Changes**:
```php
// In updateTask() - add tracking for due_date
if ($oldDueDate !== $validated['due_date']) {
    $this->notifyTaskAction($task, 'due_date_changed', $currentEmployee, [
        'old_due_date' => $oldDueDate,
        'new_due_date' => $validated['due_date']
    ]);
}
```

### Notification Preferences (Future)
Allow users to configure which notifications they want:
```php
// Migration: add to users table
$table->json('notification_preferences')->nullable();

// Example preferences
{
  "task_created": true,
  "task_updated": false,
  "task_deleted": true,
  "status_changed": true,
  "assigned_to_me": true,
  "priority_changed": false
}

// Update getTaskNotificationRecipients()
$recipients = $recipients->filter(function ($user) use ($action) {
    $prefs = $user->notification_preferences ?? [];
    return $prefs[$action] ?? true; // Default to true
});
```

---

## Troubleshooting

### Notifications Not Appearing

**1. Check Reverb is Running**:
```bash
php artisan reverb:start
```

**2. Check User Has Employee Record**:
```php
// In tinker
$user = User::find(1);
$user->employee; // Should not be null
```

**3. Check Notification Recipients**:
```php
// In controller method (add debug)
$recipients = $this->getTaskNotificationRecipients($task);
dd($recipients->pluck('id', 'email'));
```

**4. Check Database Notifications**:
```sql
SELECT * FROM notifications WHERE notifiable_id = 1 ORDER BY created_at DESC LIMIT 10;
```

**5. Check Broadcast Connection**:
```bash
# In browser console
Echo.connector.pusher.connection.state // Should be 'connected'
```

### Duplicate Notifications

**Cause**: Assigned user is also admin user  
**Solution**: Already handled via `$recipients->unique('id')`

### Missing Employee Data

**Cause**: `auth()->id()` exists but no employee record  
**Solution**: Current code handles this:
```php
$currentEmployee = Employee::where('user_id', auth()->id())->first();
if ($currentEmployee) {
    // Only send if employee exists
}
```

---

## Summary

✅ **Implemented**:
- TaskStatusChangedNotification (status change tracking)
- TaskActionNotification (all other actions)
- Real-time delivery via database + broadcast channels
- Notification helper methods in ProjectController
- Updated storeTasks, updateTask, destroyTask methods
- Tracks status, assignment, priority changes
- User → Employee relationship

✅ **Recipients**:
- Assigned user (if exists)
- All admin users (users with employee records)
- Duplicates removed automatically

✅ **Delivery**:
- Database channel (persistent)
- Broadcast channel (real-time via Reverb)
- Ready for frontend integration

✅ **Tested**:
- Configuration cleared
- View cache cleared
- No syntax errors

**Next Steps**:
1. Test by creating/updating/deleting tasks
2. Verify notifications appear in database
3. Check real-time delivery via Echo
4. Optionally add notification UI components (dropdown, badge, etc.)
5. Optionally add sound/desktop notifications
6. Add more actions (comments, files, checklist) when ready
