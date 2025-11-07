# Task Reminder System - Complete Implementation Guide

## 📋 Overview
The task reminder system allows users to set reminders for project tasks. Users can create reminders for themselves or others (team members and client users). The system automatically sends email and in-app notifications when reminders are due.

## 🗄️ Database Schema

### Table: `project_task_reminders`
```sql
- id (bigint, primary key)
- project_task_id (foreign key → project_tasks.id, cascade delete)
- created_by (foreign key → employees.id, cascade delete)
- recipient_type (string: 'employee' or 'client')
- recipient_id (bigint: Employee ID or UatUser ID)
- remind_at (datetime: when to send the reminder)
- message (text, nullable: optional custom message)
- is_sent (boolean, default false)
- sent_at (timestamp, nullable)
- created_at, updated_at
- Index on (remind_at, is_sent) for efficient querying
```

## 🔧 Backend Components

### Models

**ProjectTaskReminder** (`app/Models/ProjectTaskReminder.php`)
- **Relationships:**
  - `task()` → belongsTo ProjectTask
  - `creator()` → belongsTo Employee
  - `recipient()` → Polymorphic (returns Employee or UatUser based on recipient_type)
  
- **Scopes:**
  - `pending()` → Reminders not sent and due now or past due
  - `upcoming()` → Reminders not sent and scheduled for future

**ProjectTask** (`app/Models/ProjectTask.php`)
- Added: `reminders()` → hasMany ProjectTaskReminder

### Controllers

**ProjectController** - Reminder Methods:

1. **`getTaskReminders(Project $project, ProjectTask $task)`**
   - **Route:** `GET /projects/{project}/tasks/{task}/reminders`
   - **Permission:** `permission:view-projects`
   - **Returns:** List of all reminders for a task with recipient details

2. **`storeTaskReminder(Request $request, Project $project, ProjectTask $task)`**
   - **Route:** `POST /projects/{project}/tasks/{task}/reminders`
   - **Permission:** `permission:edit-projects`
   - **Validation:**
     ```php
     recipient_type => required|in:employee,client
     recipient_id => required|integer
     remind_at => required|date|after:now
     message => nullable|string|max:500
     ```
   - **Returns:** Created reminder with recipient details

3. **`updateTaskReminder(Request $request, Project $project, ProjectTask $task, $reminderId)`**
   - **Route:** `PUT /projects/{project}/tasks/{task}/reminders/{reminder}`
   - **Permission:** `permission:edit-projects`
   - **Authorization:** Only creator can update their own reminders
   - **Restriction:** Cannot update already-sent reminders
   - **Returns:** Updated reminder

4. **`destroyTaskReminder(Project $project, ProjectTask $task, $reminderId)`**
   - **Route:** `DELETE /projects/{project}/tasks/{task}/reminders/{reminder}`
   - **Permission:** `permission:delete-projects`
   - **Authorization:** Only creator can delete their own reminders
   - **Returns:** Success message

5. **`getTaskRecipientsForReminders(Project $project, ProjectTask $task)`**
   - **Route:** `GET /projects/{project}/tasks/{task}/reminder-recipients`
   - **Permission:** `permission:view-projects`
   - **Returns:**
     ```json
     {
       "employees": [{ "type": "employee", "id": 1, "name": "...", "email": "..." }],
       "clients": [{ "type": "client", "id": 2, "name": "...", "email": "..." }]
     }
     ```

### Mail & Notifications

**TaskReminderMail** (`app/Mail/TaskReminderMail.php`)
- Email template: `resources/views/emails/task-reminder.blade.php`
- Subject: "Task Reminder: {task title}"
- Includes: Task details, project info, status, priority, due date, custom message

**TaskReminderNotification** (`app/Notifications/TaskReminderNotification.php`)
- Channel: `database` (in-app notifications)
- Stores: task_id, project_id, creator_name, message, URL

### Scheduled Command

**SendTaskReminders** (`app/Console/Commands/SendTaskReminders.php`)
- **Command:** `php artisan reminders:send-task-reminders`
- **Schedule:** Runs every 5 minutes (configured in `routes/console.php`)
- **Process:**
  1. Fetch all pending reminders (is_sent=false, remind_at<=now)
  2. For each reminder:
     - Send email to recipient
     - Send in-app notification (if employee with user account)
     - Mark as sent (is_sent=true, sent_at=now)
  3. Log errors for failed sends

**Schedule Configuration** (`routes/console.php`):
```php
Schedule::command('reminders:send-task-reminders')->everyFiveMinutes();
```

## 🎨 Frontend Components

### UI Location
Task detail modal in `resources/views/projects/tabs/tasks.blade.php`

### Alpine.js State Variables
```javascript
// Reminders state
taskReminders: [],
reminderRecipients: { employees: [], clients: [] },
showAddReminderForm: false,
editingReminder: null,
reminderForm: {
    recipient: '',        // Format: 'employee-123' or 'client-456'
    remind_at: '',        // datetime-local format
    message: ''
}
```

### Key Functions

**`loadReminders()`**
- Fetches all reminders for current task
- Called when task detail modal opens

**`loadReminderRecipients()`**
- Fetches available recipients (project members + client users)
- Populates dropdown options

**`saveReminder()`**
- Creates new reminder or updates existing one
- Validates form data
- Parses recipient (splits 'employee-123' to type + id)
- Sends POST or PUT request
- Refreshes reminder list on success

**`editReminder(reminder)`**
- Loads reminder data into form
- Sets editingReminder flag
- Shows form

**`deleteReminder(reminderId)`**
- Confirms deletion with user
- Sends DELETE request
- Refreshes reminder list

**`cancelReminderForm()`**
- Hides form
- Resets form data
- Clears editing state

### UI Sections

1. **Header**
   - Shows reminder count badge
   - "Add Reminder" button

2. **Add/Edit Form** (shown when `showAddReminderForm` or `editingReminder`)
   - Recipient dropdown (grouped: Team Members / Client Users)
   - Datetime picker
   - Optional message textarea
   - Save/Cancel buttons

3. **Reminders List**
   - Each reminder shows:
     - Recipient name + type badge (Team/Client)
     - Remind time (human-readable + exact datetime)
     - Custom message (if provided)
     - Creator info + creation time
     - "Sent" badge (for completed reminders)
   - Hover actions (edit/delete) for creator's own pending reminders

4. **Empty State**
   - Shown when no reminders exist
   - Encourages creating first reminder

## 🚀 Usage Workflow

### Creating a Reminder

1. User opens task detail modal
2. Clicks "Add Reminder" button
3. Selects recipient from dropdown (team member or client user)
4. Sets date/time using datetime picker
5. Optionally adds custom message
6. Clicks "Save Reminder"
7. Reminder appears in list immediately

### Editing a Reminder

1. User hovers over their own pending reminder
2. Clicks edit icon (only visible for creator's unsent reminders)
3. Form populates with existing data
4. User modifies and clicks "Update Reminder"
5. Changes saved and list refreshes

### Deleting a Reminder

1. User hovers over their own pending reminder
2. Clicks delete icon
3. Confirms deletion in browser prompt
4. Reminder removed from list

### Automatic Sending

1. Scheduler runs `reminders:send-task-reminders` every 5 minutes
2. Command fetches all reminders where `remind_at <= now` and `is_sent = false`
3. For each pending reminder:
   - Email sent to recipient using TaskReminderMail
   - In-app notification created (if employee)
   - Reminder marked as sent
4. User receives:
   - Email with task details, project info, custom message
   - In-app notification (employees only)
   - Bell icon badge increments (if using notification system)

## 🔒 Security & Permissions

### Route Permissions
- View reminders: `permission:view-projects`
- Create reminders: `permission:edit-projects`
- Update reminders: `permission:edit-projects` + must be creator
- Delete reminders: `permission:delete-projects` + must be creator

### Authorization Rules
- Only reminder creator can edit/delete
- Cannot edit/delete already-sent reminders
- Recipient must exist (Employee or UatUser in project)
- Remind time must be in the future when creating

### Data Access
- Employees can create reminders for any project member
- Employees can create reminders for any client user in the project
- Client users currently cannot create reminders (employee-only feature)

## 📧 Email Template

**File:** `resources/views/emails/task-reminder.blade.php`

**Includes:**
- 🔔 Bell icon header
- Personalized greeting
- Task title (large, bold)
- Project name
- Status badge (color-coded)
- Priority badge (color-coded)
- Due date (if set)
- Assigned person (if set)
- Custom message from creator (if provided)
- "View Task Details" button (links to project tasks page)
- Footer with creator name and system branding

**Styling:**
- Responsive design
- Color-coded status/priority badges
- Clean, professional layout
- Mobile-friendly

## 🛠️ Testing

### Manual Testing Steps

1. **Create Reminder:**
   ```
   - Open any task
   - Click "Add Reminder"
   - Select yourself as recipient
   - Set time 2 minutes from now
   - Add message: "Test reminder"
   - Save
   - Verify appears in list
   ```

2. **Edit Reminder:**
   ```
   - Hover over reminder
   - Click edit icon
   - Change time to 5 minutes from now
   - Click "Update Reminder"
   - Verify time updated
   ```

3. **Delete Reminder:**
   ```
   - Hover over reminder
   - Click delete icon
   - Confirm
   - Verify removed from list
   ```

4. **Test Scheduled Sending:**
   ```
   - Create reminder for 1 minute from now
   - Wait 1-2 minutes
   - Run: php artisan reminders:send-task-reminders
   - Check email inbox
   - Check in-app notifications
   - Verify reminder marked as "Sent"
   ```

### Command Testing
```bash
# Test the scheduled command
php artisan reminders:send-task-reminders

# Expected output:
# Checking for pending task reminders...
# Found X pending reminder(s).
# ✓ Sent reminder ID 1 to user@example.com
# Completed: X sent, 0 errors.
```

### Database Queries
```sql
-- View all pending reminders
SELECT * FROM project_task_reminders 
WHERE is_sent = 0 AND remind_at <= NOW();

-- View sent reminders
SELECT * FROM project_task_reminders 
WHERE is_sent = 1 
ORDER BY sent_at DESC;

-- Check reminder recipients
SELECT 
    ptr.*,
    CASE 
        WHEN ptr.recipient_type = 'employee' THEN e.first_name
        WHEN ptr.recipient_type = 'client' THEN uu.name
    END as recipient_name
FROM project_task_reminders ptr
LEFT JOIN employees e ON ptr.recipient_type = 'employee' AND ptr.recipient_id = e.id
LEFT JOIN uat_users uu ON ptr.recipient_type = 'client' AND ptr.recipient_id = uu.id;
```

## 📊 API Response Examples

### Get Reminders
```json
{
  "success": true,
  "reminders": [
    {
      "id": 1,
      "recipient_type": "employee",
      "recipient_id": 5,
      "recipient_name": "John Doe",
      "remind_at": "2025-11-08 10:00",
      "remind_at_human": "in 2 hours",
      "message": "Don't forget to review this!",
      "is_sent": false,
      "sent_at": null,
      "created_by": {
        "id": 3,
        "name": "Jane Smith"
      },
      "created_at": "2 hours ago"
    }
  ]
}
```

### Get Recipients
```json
{
  "success": true,
  "recipients": {
    "employees": [
      {
        "type": "employee",
        "id": 5,
        "name": "John Doe",
        "email": "john@example.com"
      }
    ],
    "clients": [
      {
        "type": "client",
        "id": 2,
        "name": "Client Name",
        "email": "client@example.com"
      }
    ]
  }
}
```

## 🔄 Future Enhancements

### Potential Additions
1. **Recurring Reminders:** Daily/weekly reminders for ongoing tasks
2. **SMS Notifications:** Send reminders via SMS for critical tasks
3. **Snooze Functionality:** Allow recipients to snooze reminders
4. **Reminder Templates:** Pre-defined messages for common scenarios
5. **Bulk Reminders:** Set reminders for multiple tasks at once
6. **Smart Scheduling:** Suggest optimal reminder times based on due dates
7. **Reminder History:** View all past reminders for a task
8. **Notification Preferences:** Let users choose email vs in-app

### Performance Optimizations
1. Queue email sending for large volumes
2. Cache recipient lists for frequently accessed projects
3. Batch reminder processing instead of individual loops
4. Add database indexes for common queries

## 📝 Troubleshooting

### Reminders Not Sending

**Issue:** Scheduled command runs but no emails sent

**Solutions:**
1. Check scheduler is running: `php artisan schedule:work` (dev) or cron job (production)
2. Verify email configuration in `.env`
3. Check logs: `storage/logs/laravel.log`
4. Test mail manually: `php artisan reminders:send-task-reminders`
5. Verify recipient email addresses exist

### Reminders Not Appearing in UI

**Issue:** Created reminder doesn't show in list

**Solutions:**
1. Check browser console for JavaScript errors
2. Verify API endpoint returns data: `/projects/{id}/tasks/{id}/reminders`
3. Check `loadReminders()` is called on modal open
4. Verify `taskReminders` array is reactive

### Permission Errors

**Issue:** "You can only update your own reminders"

**Solutions:**
1. Verify logged-in user's employee ID matches `created_by`
2. Check `auth()->user()->employee` exists
3. Ensure user has `permission:edit-projects`

### Recipient Not Found

**Issue:** "Recipient not found for this project"

**Solutions:**
1. For employees: Check they are a project member
2. For clients: Verify UatUser exists for the project
3. Check `recipient_id` matches actual employee/client ID

## 🎯 Key Features Summary

✅ Set reminders for yourself and others
✅ Support for employees and client users
✅ Optional custom messages
✅ Email notifications with task details
✅ In-app notifications (for employees)
✅ Edit/delete own pending reminders
✅ Visual "Sent" badge for completed reminders
✅ Automatic sending via Laravel scheduler
✅ Permission-based access control
✅ Real-time UI updates
✅ Mobile-responsive design
✅ Color-coded status/priority badges
✅ Human-readable time display

## 📚 Related Documentation

- [Laravel Scheduler](https://laravel.com/docs/12.x/scheduling)
- [Laravel Mail](https://laravel.com/docs/12.x/mail)
- [Laravel Notifications](https://laravel.com/docs/12.x/notifications)
- [Alpine.js Documentation](https://alpinejs.dev/)
- [Tailwind CSS](https://tailwindcss.com/docs)

---

**Implementation Date:** November 7, 2025
**Laravel Version:** 12.33.0
**PHP Version:** 8.4.13
