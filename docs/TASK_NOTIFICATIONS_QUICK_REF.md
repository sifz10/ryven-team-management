# Task Notifications - Quick Reference

## ✅ What Was Implemented

### 🔔 Two Notification Types

**1. TaskStatusChangedNotification** - Status changes only
- Tracks old → new status
- Shows who made the change
- Includes status labels ("To Do", "In Progress", etc.)

**2. TaskActionNotification** - All other actions
- Created, updated, deleted
- Assigned, unassigned
- Priority changed
- Future: comments, files, checklist updates

### 📨 Delivery Channels

- **Database**: Persistent storage in `notifications` table
- **Broadcast**: Real-time via Laravel Reverb (WebSocket)

### 👥 Who Gets Notified?

**Every task action notifies**:
1. Assigned user (if task has assigned_to)
2. All admin users (users with employee records)

Duplicates automatically removed.

---

## 🎯 Trigger Points

### ✨ Task Created
**File**: `ProjectController::storeTasks()`
**Action**: `created`
**Notification**: TaskActionNotification
**Message**: "{Name} created a new task"

### 📝 Task Updated
**File**: `ProjectController::updateTask()`

**Multiple checks**:

1. **Status Changed**:
   - **Notification**: TaskStatusChangedNotification
   - **Message**: "{Name} changed status from {Old} to {New}"

2. **Assignment Changed**:
   - **Action**: `assigned` or `unassigned`
   - **Notification**: TaskActionNotification
   - **Message**: "{Name} assigned you to task" or "unassigned you from task"

3. **Priority Changed**:
   - **Action**: `priority_changed`
   - **Notification**: TaskActionNotification
   - **Message**: "{Name} changed priority of task"
   - **Details**: Old and new priority values

4. **General Update** (if none of the above):
   - **Action**: `updated`
   - **Notification**: TaskActionNotification
   - **Message**: "{Name} updated task"

### 🗑️ Task Deleted
**File**: `ProjectController::destroyTask()`
**Action**: `deleted`
**Notification**: TaskActionNotification
**Message**: "{Name} deleted task"

---

## 📋 Notification Data

```javascript
{
  "type": "task_status_changed" | "task_action",
  "title": "Task Status Changed" | "Task Created",
  "message": "John Doe changed status from To Do to In Progress",
  "task_id": 123,
  "task_title": "Fix login bug",
  "project_id": 45,
  "project_name": "Website Redesign",
  "url": "https://team.ryven.co/projects/45?tab=tasks",
  "created_at": "2024-01-15T10:30:00.000000Z",
  
  // TaskStatusChangedNotification specific:
  "old_status": "todo",
  "new_status": "in-progress",
  "changed_by": "John Doe",
  
  // TaskActionNotification specific:
  "action": "created",
  "action_by": "John Doe",
  "details": {
    "priority": "high",
    "additional_info": "Assigned to employee"
  }
}
```

---

## 🛠️ Files Modified

### New Files
- `app/Notifications/TaskStatusChangedNotification.php`
- `app/Notifications/TaskActionNotification.php`

### Updated Files
- `app/Http/Controllers/ProjectController.php`
  - Added 3 helper methods
  - Updated `storeTasks()` method
  - Updated `updateTask()` method
  - Updated `destroyTask()` method

- `app/Models/User.php`
  - Added `employee()` relationship

---

## 🧪 Testing

### Manual Test Steps

1. **Create Task**:
   ```
   Projects → Select Project → Tasks → Create Task
   ✓ All admins notified
   ✓ Assigned user notified (if assigned)
   ```

2. **Change Status**:
   ```
   Edit Task → Change Status → Save
   ✓ TaskStatusChangedNotification sent
   ✓ Shows old and new status
   ```

3. **Assign User**:
   ```
   Edit Task → Set Assigned To → Save
   ✓ TaskActionNotification (assigned) sent
   ✓ Assigned user notified
   ```

4. **Change Priority**:
   ```
   Edit Task → Change Priority → Save
   ✓ TaskActionNotification (priority_changed) sent
   ✓ Details include old/new priority
   ```

5. **Delete Task**:
   ```
   Delete Task → Confirm
   ✓ TaskActionNotification (deleted) sent
   ✓ Sent before deletion (while task data exists)
   ```

### Database Check
```sql
-- View latest notifications
SELECT * FROM notifications 
WHERE notifiable_type = 'App\\Models\\User'
ORDER BY created_at DESC 
LIMIT 10;

-- View notifications for specific user
SELECT data->>'$.message' as message, created_at 
FROM notifications 
WHERE notifiable_id = 1 
ORDER BY created_at DESC;
```

### Tinker Check
```php
// Check user's notifications
$user = User::find(1);
$user->notifications->take(5);

// Check unread count
$user->unreadNotifications->count();

// Mark as read
$user->unreadNotifications->markAsRead();
```

---

## 🚀 Real-Time Setup

### Backend (Already Done)
```php
// Notifications implement ShouldQueue + broadcast channel
public function via(object $notifiable): array
{
    return ['database', 'broadcast'];
}
```

### Frontend (Next Step)
```javascript
// Listen for new notifications
Echo.private(`user.${userId}`)
    .listen('.notification.new', (notification) => {
        console.log('New notification:', notification);
        
        // Show toast
        window.showToast('info', notification.message);
        
        // Update badge count
        updateNotificationBadge();
        
        // Play sound (optional)
        playNotificationSound();
    });
```

---

## 📊 Supported Actions

| Action | Code | Message |
|--------|------|---------|
| Created | `created` | "created a new task" |
| Updated | `updated` | "updated task" |
| Deleted | `deleted` | "deleted task" |
| Assigned | `assigned` | "assigned you to task" |
| Unassigned | `unassigned` | "unassigned you from task" |
| Priority Changed | `priority_changed` | "changed priority of task" |
| Status Changed | N/A (separate notification) | "changed status from X to Y" |

### Future Actions (Prepared)
- `comment_added`
- `file_uploaded`
- `checklist_updated`
- `due_date_changed`
- `reminder_set`

---

## 🔧 Helper Methods

### notifyStatusChange()
```php
$this->notifyStatusChange($task, 'todo', 'in-progress', $currentEmployee);
```

### notifyTaskAction()
```php
$this->notifyTaskAction($task, 'created', $currentEmployee, [
    'priority' => 'high',
    'additional_info' => 'Extra context here'
]);
```

### getTaskNotificationRecipients()
```php
$recipients = $this->getTaskNotificationRecipients($task);
// Returns Collection of User models
```

---

## ⚡ Performance Notes

- **Queued**: Notifications implement `ShouldQueue` (run in background)
- **Unique Recipients**: Duplicates removed automatically
- **Batch Operations**: One query for admins, one for assigned user
- **Broadcasting**: Real-time delivery via Reverb (no polling)

---

## 🎨 Status Labels

```php
'todo' => 'To Do'
'on-hold' => 'On Hold'
'in-progress' => 'In Progress'
'awaiting-feedback' => 'Awaiting Feedback'
'staging' => 'Staging'
'live' => 'Live'
'completed' => 'Completed'
```

---

## 🐛 Troubleshooting

**No notifications appearing?**
1. Check Reverb is running: `php artisan reverb:start`
2. Check user has employee record: `User::find(1)->employee`
3. Check database: `SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5`

**Notifications sent twice?**
- Already handled via `$recipients->unique('id')`

**Employee is null?**
- Only users with employee records trigger notifications
- Code handles this: `if ($currentEmployee) { ... }`

---

## 📌 Key Points

✅ **All admin users** get notified of every task action  
✅ **Assigned user** gets notified of their task changes  
✅ **Real-time** via database + broadcast channels  
✅ **Queued** processing (doesn't slow down requests)  
✅ **Extensible** - easy to add new action types  
✅ **Context-aware** - includes task, project, actor details  

---

## 🎯 Next Steps

1. **Test notifications** by creating/editing/deleting tasks
2. **Add frontend UI** for notification dropdown
3. **Add badge counter** for unread notifications
4. **Add sound/desktop notifications** (optional)
5. **Add notification preferences** (allow users to opt-out)
6. **Extend to comments/files** when ready

---

## 📖 Related Documentation

- Main Guide: `TASK_NOTIFICATIONS_GUIDE.md`
- Reverb Setup: `REALTIME_SETUP.md`
- Toast System: `IMPLEMENTATION_SUMMARY.md` (reminders section)
