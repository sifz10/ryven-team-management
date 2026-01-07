# Task Mentions & File Upload Feature

## Summary
Added **file upload** (drag & drop), **real-time comments**, and **@mention functionality** with notifications to the project task management system.

## Features Implemented

### 1. File Upload (Drag & Drop)
✅ Drag and drop files onto task detail modal
✅ Browse button for traditional file selection
✅ File list with download and delete buttons
✅ File metadata (name, size, upload date, uploader)
✅ 10MB file size limit
✅ Files stored in `storage/app/public/task-files/`

### 2. Real-Time Comments
✅ Add comments to tasks
✅ Real-time synchronization via Laravel Reverb
✅ Comments display with user avatars
✅ Keyboard shortcut (Ctrl+Enter) to submit
✅ Automatic broadcast to all viewers

### 3. @Mention Functionality
✅ Type `@` in comment textarea to trigger autocomplete
✅ Dropdown shows matching employees as you type
✅ Click to insert mention
✅ Mentions detected by backend regex pattern
✅ Notifications created for mentioned users
✅ Real-time notification broadcast to mentioned users

## Database Structure

### `project_task_files` Table
```sql
id - bigint (primary key)
project_task_id - bigint (foreign key to project_tasks)
uploaded_by - bigint (foreign key to employees)
original_name - varchar
file_path - varchar
file_type - varchar
file_size - bigint
created_at, updated_at - timestamps
```

### `project_task_comments` Table
```sql
id - bigint (primary key)
project_task_id - bigint (foreign key to project_tasks)
employee_id - bigint (foreign key to employees)
comment - text
created_at, updated_at - timestamps
```

## Backend Implementation

### Models
**`ProjectTaskFile.php`**
- Relationships: `task()`, `uploader()`
- Helper: `getFileSizeFormattedAttribute()` - formats bytes to KB/MB/GB

**`ProjectTaskComment.php`**
- Relationships: `task()`, `employee()`
- Eager loads: `employee` with each comment

**`ProjectTask.php`** (updated)
- Added `files()` hasMany relationship
- Added `comments()` hasMany relationship

### Controller Methods (`ProjectController.php`)

**File Operations:**
```php
uploadTaskFile() - POST /projects/{project}/tasks/{task}/files
downloadTaskFile() - GET /projects/{project}/tasks/{task}/files/{file}/download
deleteTaskFile() - DELETE /projects/{project}/tasks/{task}/files/{file}
```

**Comment Operations:**
```php
getTaskComments() - GET /projects/{project}/tasks/{task}/comments
storeTaskComment() - POST /projects/{project}/tasks/{task}/comments
```

**Mention Autocomplete:**
```php
getEmployeesForMention() - GET /projects/{project}/employees/mention?search=
```

### Mention Detection Logic
**Pattern:** `/@(\w+(?:\.\w+)?(?:@[\w.]+)?)/`

Matches:
- `@JohnDoe` - by name
- `@john.doe@example.com` - by email

**Lookup Strategy:**
1. Check if mention contains `@` → search by email
2. Otherwise → search by first name, last name, or concatenated full name (case-insensitive)

**Notification Creation:**
```php
// Create notification record
Notification::create([
    'user_id' => $mentionedEmployee->id,
    'type' => 'task_mention',
    'title' => 'You were mentioned in a comment',
    'message' => '{Author} mentioned you in task: {TaskTitle}',
    'data' => json_encode([
        'task_id' => $task->id,
        'project_id' => $project->id,
        'comment_id' => $comment->id,
    ]),
]);

// Broadcast real-time notification
event(new NewNotification($mentionedEmployee->id, [...]));
```

### Event Broadcasting

**`TaskCommentAdded` Event:**
- Channel: `task.{taskId}` (public)
- Event name: `.comment.added`
- Payload: comment data with employee info

**`NewNotification` Event:**
- Channel: `user.{userId}` (private)
- Event name: `.notification.new`
- Payload: notification details

## Frontend Implementation

### Alpine.js Component State (taskManager)
```javascript
// File upload
isDragging: false,
taskFiles: [],

// Comments
comments: [],
newComment: '',

// Mentions
showMentionDropdown: false,
mentionSearch: '',
mentionEmployees: [],
mentionPosition: 0,
```

### Key Methods

**File Upload:**
```javascript
uploadFile(file) - Upload file to server
deleteFile(fileId) - Delete file with confirmation
loadFiles() - Load files from viewingTask
```

**Comments:**
```javascript
loadComments() - Fetch comments from API
addComment() - Post new comment
subscribeToComments() - Subscribe to real-time updates via Echo
```

**Mentions:**
```javascript
handleCommentInput(event) - Detect @ symbol and trigger autocomplete
selectMention(employee) - Insert mention at cursor position
```

### Mention Autocomplete Flow
1. User types `@` in comment textarea
2. `handleCommentInput()` detects `@` before cursor
3. Extracts text after `@` (until space or end)
4. Fetches matching employees from `/projects/{id}/employees/mention?search={text}`
5. Displays dropdown with employee names and emails
6. User clicks employee → mention inserted as `@firstname lastname`

### Real-Time Echo Subscription
```javascript
window.Echo.channel(`task.${taskId}`)
    .listen('.comment.added', (event) => {
        if (!comments.find(c => c.id === event.comment.id)) {
            comments.unshift(event.comment);
        }
    });
```

## Routes

```php
// Task Files
POST   /projects/{project}/tasks/{task}/files
GET    /projects/{project}/tasks/{task}/files/{file}/download
DELETE /projects/{project}/tasks/{task}/files/{file}

// Task Comments
GET    /projects/{project}/tasks/{task}/comments
POST   /projects/{project}/tasks/{task}/comments

// Mention Autocomplete
GET    /projects/{project}/employees/mention?search={query}
```

All routes protected by permission middleware (`permission:view-projects`, `permission:edit-projects`, etc.)

## UI Components

### File Drop Zone
- Border changes on drag-over (black/white)
- Visual feedback with upload icon
- Hidden file input triggered by "browse" link
- Supports multiple files via drag & drop

### File List
- Shows file icon, name, size, date, uploader
- Download button (arrow icon)
- Delete button (trash icon) with confirmation
- Empty state when no files

### Comment Textarea
- `@` triggers autocomplete dropdown
- `Ctrl+Enter` submits comment
- Placeholder: "Add a comment... (Use @ to mention someone, Ctrl+Enter to submit)"

### Mention Dropdown
- Positioned absolutely below textarea
- Shows employee avatar (initials), name, and email
- Hover effect on each item
- Auto-closes when mention selected

### Comments List
- User avatar (circular gradient with initials)
- Name and timestamp header
- Comment text with preserved whitespace
- Empty state with icon when no comments

## Testing Checklist

### File Upload
- [ ] Drag files onto drop zone (visual feedback)
- [ ] Click "browse" to select file
- [ ] File appears in list immediately
- [ ] Download button works
- [ ] Delete button prompts confirmation
- [ ] File size limit enforced (10MB)

### Comments
- [ ] Add comment via button
- [ ] Add comment via Ctrl+Enter
- [ ] Comment appears immediately
- [ ] Other users see comment in real-time (test with 2 browsers)
- [ ] Empty state shows when no comments

### Mentions
- [ ] Type `@` to trigger dropdown
- [ ] Dropdown filters as you type
- [ ] Click employee to insert mention
- [ ] Mentioned user receives notification
- [ ] Notification appears in real-time
- [ ] Notification includes task link
- [ ] Can mention multiple users in one comment

## Prerequisites

### Environment Setup
1. **Laravel Reverb Running:**
   ```bash
   php artisan reverb:start
   ```

2. **Queue Worker Running (for notifications):**
   ```bash
   php artisan queue:work
   ```

3. **Vite Dev Server:**
   ```bash
   npm run dev
   ```

4. **Storage Link:**
   ```bash
   php artisan storage:link
   ```

### Broadcasting Configuration
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_PORT=8080
```

## Known Limitations

1. **File Types:** No file type restrictions (all types allowed)
2. **Mention Format:** Only detects `@word` or `@email` patterns (no spaces in names)
3. **Edit Comments:** Comments cannot be edited or deleted after posting
4. **File Preview:** No inline preview for images/PDFs
5. **Mention Validation:** No client-side validation for invalid mentions

## Future Enhancements

- [ ] Edit/delete own comments
- [ ] File type restrictions (images, PDFs only)
- [ ] Image/PDF preview in modal
- [ ] Rich text editor for comments (bold, italic, code blocks)
- [ ] Comment reactions (like, emoji)
- [ ] File virus scanning
- [ ] Bulk file upload progress bar
- [ ] Mention notifications via email
- [ ] Task activity log (all changes)
- [ ] @mention highlighting in comment text

## Troubleshooting

### Files not uploading:
- Check `storage/app/public/task-files/` directory exists and is writable
- Verify `php artisan storage:link` was run
- Check PHP `upload_max_filesize` and `post_max_size` in `php.ini`

### Comments not appearing:
- Ensure Reverb server is running (`php artisan reverb:start`)
- Check browser console for Echo connection errors
- Verify `VITE_REVERB_*` env vars match server config

### Mentions not working:
- Check employee email matches exactly
- Verify notification table has records
- Ensure queue worker is running for notification broadcast

### Autocomplete not showing:
- Check `/projects/{id}/employees/mention` route is accessible
- Verify employee records exist with `is_active = 1`
- Check browser console for API errors

## Migration Files

Run migrations in order:
```bash
php artisan migrate
```

Files:
- `2025_11_07_182402_create_project_task_files_table.php`
- `2025_11_07_182501_create_project_task_comments_table.php`

## Code Review Notes

### Security Considerations
✅ CSRF token validation on all POST requests
✅ File size limit enforced (10MB)
✅ Permission middleware on all routes
✅ Foreign key constraints prevent orphaned records
✅ SQL injection protection via Eloquent ORM

### Performance Optimizations
✅ Eager loading (`with('employee')`) for comments
✅ Limit autocomplete results to 10 employees
✅ Index on foreign keys for fast lookups
✅ Broadcasting only to relevant channels (task-specific)

### Best Practices Followed
✅ RESTful API design
✅ Single responsibility per method
✅ Descriptive variable names
✅ Consistent error handling
✅ Real-time updates via WebSockets (not polling)
