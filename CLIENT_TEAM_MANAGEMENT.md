# Client Team Member Management Feature

## Overview
Clients can now create their own teams and invite team members via email. When a team member is invited, they automatically receive an email with login credentials and instructions to access the client portal.

## Key Features

### 1. **Team Member Invitation System**
- Clients can invite team members by providing:
  - Full Name (required)
  - Email Address (required, must be unique)
  - Role (optional - e.g., Project Manager, Developer, Designer)

### 2. **Automatic Credential Generation**
- System automatically generates:
  - Temporary password (12 characters)
  - Invitation token for secure acceptance
  - ClientUser account in database

### 3. **Email Notification**
- Professional invitation email sent immediately with:
  - Login credentials (email + temporary password)
  - Direct login link to client portal
  - Security notice about password change requirement
  - List of available permissions
  - Company/client information

### 4. **Team Management Dashboard**
- View all team members with:
  - Name, email, role
  - Status badges (Invited, Active, etc.)
  - Project count per member
  - Join/invitation date
- Actions available:
  - Resend invitation (for pending members)
  - Remove team member

## File Structure

### Controller
```
app/Http/Controllers/Client/ClientTeamController.php
```
Handles all team member operations:
- `index()` - List all team members
- `create()` - Show invitation form
- `store()` - Process invitation and send email
- `resendInvitation()` - Resend invitation email with new password
- `destroy()` - Remove team member and their account

### Routes
```php
// Team Members Management
Route::get('/team', 'ClientTeamController@index')->name('client.team.index');
Route::get('/team/create', 'ClientTeamController@create')->name('client.team.create');
Route::post('/team', 'ClientTeamController@store')->name('client.team.store');
Route::post('/team/{teamMember}/resend', 'ClientTeamController@resendInvitation')->name('client.team.resend');
Route::delete('/team/{teamMember}', 'ClientTeamController@destroy')->name('client.team.destroy');
```

### Views
```
resources/views/client/team/index.blade.php   - Team members list
resources/views/client/team/create.blade.php  - Invitation form
```

### Email Template
```
resources/views/emails/client-team-invitation.blade.php
```
Professional email with:
- Welcome message
- Login credentials in highlighted box
- Security notice
- Direct login button
- Feature list
- Company branding

### Navigation
Updated `resources/views/layouts/client-sidebar.blade.php` to include "Team" menu item with team icon.

## Workflow

### 1. Client Invites Team Member
1. Client navigates to "Team" section
2. Clicks "Invite Team Member"
3. Fills form with name, email, and optional role
4. Submits invitation

### 2. System Processing
1. Validates email is unique (not used by other team members or client users)
2. Generates random 12-character password
3. Creates `ClientUser` record with:
   - `client_id` (links to client)
   - `email`
   - `password` (hashed)
   - `must_change_password = true`
4. Creates `ClientTeamMember` record with:
   - `client_id`
   - `client_user_id`
   - `name`, `email`, `role`
   - `status = 'invited'`
   - `invitation_token` (64-character random string)
   - `invitation_sent_at` (current timestamp)
5. Sends invitation email to team member

### 3. Team Member Receives Email
Email contains:
- Their login credentials
- Direct link to accept invitation: `/client/team/accept/{token}`
- Login URL: `{{ route('client.login') }}`
- Security notice about required password change

### 4. Team Member First Login
1. Visits acceptance link or logs in directly at `/login`
2. Uses provided email + temporary password
3. System forces password change on first login (via `must_change_password` flag)
4. After password change, gains access to client portal

### 5. Client Can Manage Team
- **View all members** with status and project assignments
- **Resend invitation** if member hasn't joined (generates new password)
- **Remove member** (deletes both ClientUser and ClientTeamMember records)

## Security Features

### 1. **Password Security**
- Temporary passwords are randomly generated (12 chars)
- Passwords are properly hashed before storage
- Force password change on first login

### 2. **Email Uniqueness**
- Validation ensures email isn't already used by:
  - Other team members in same client
  - Other client users in the system

### 3. **Ownership Verification**
- All operations verify `client_id` matches authenticated client
- Prevents unauthorized access to other clients' team members

### 4. **Invitation Tokens**
- 64-character random tokens for secure invitation acceptance
- Tokens stored in database and validated on acceptance

## Team Member Permissions

Team members (once logged in) can:
- ✅ View projects assigned to them
- ✅ Access project files and documents
- ✅ Participate in discussions and updates
- ✅ View invoices and support tickets
- ✅ Update their profile and preferences

Team members CANNOT:
- ❌ Create or delete projects (only main client can)
- ❌ Invite other team members (only main client can)
- ❌ Change project ownership or main settings

## Database Structure

### ClientUser Table
```php
- id
- client_id (FK to clients)
- email (unique)
- password (hashed)
- must_change_password (boolean)
- otp_code (nullable)
- otp_expires_at (nullable)
- timestamps
```

### ClientTeamMember Table
```php
- id
- client_id (FK to clients)
- client_user_id (FK to client_users)
- name
- email
- role (nullable)
- status (invited/active/inactive)
- invitation_token (unique, 64 chars)
- invitation_sent_at (datetime)
- joined_at (datetime, nullable)
- timestamps
```

## Email Configuration

Ensure `.env` has proper mail settings:
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

## Usage Example

### For Clients:
```
1. Login to client portal at /login
2. Navigate to "Team" in sidebar
3. Click "Invite Team Member"
4. Enter member details:
   - Name: John Doe
   - Email: john@example.com
   - Role: Frontend Developer
5. Click "Send Invitation"
6. Team member receives email instantly
```

### For Team Members:
```
1. Check email inbox for invitation
2. Note the temporary password
3. Click "Accept Invitation & Login" button
4. Or manually visit the login page and use credentials
5. System prompts to change password
6. Create new secure password
7. Access client portal dashboard
```

## Testing

### Test Invitation Flow:
```bash
# Login as a client
# Navigate to /client/team/create
# Fill form and submit
# Check email inbox (or logs if using log driver)
```

### Test Resend Invitation:
```bash
# From team list, click "Resend Invitation"
# New password is generated
# New email is sent
```

### Test Team Member Removal:
```bash
# From team list, click "Remove"
# Confirm deletion
# Both ClientUser and ClientTeamMember records are deleted
```

## Error Handling

The system handles:
- **Duplicate Emails**: Validation error shown to user
- **Mail Failures**: Logged but doesn't prevent member creation
- **Unauthorized Access**: 403 errors for cross-client operations
- **Missing Data**: Form validation with clear error messages

## Future Enhancements

Potential additions (not yet implemented):
- Email verification for team members
- Role-based permissions (different access levels)
- Project assignment from team management page
- Team member activity tracking
- Bulk invitation import (CSV)
- Team member profile pages
- Team collaboration tools

## Support

For issues or questions:
1. Check email logs if invitations not received
2. Verify MAIL_* configuration in `.env`
3. Check Laravel logs at `storage/logs/laravel.log`
4. Ensure queue worker is running if using queued emails

---

**Created:** November 9, 2025  
**Feature:** Client Team Member Invitation System  
**Version:** 1.0
