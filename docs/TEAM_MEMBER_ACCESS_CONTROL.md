# Team Member Access Control System

## Overview
This document describes the access control system for team members in the project management system. Team members have read-only access to projects they are assigned to, with clear visual indicators and backend protection.

## Access Levels

### Project Owner (Primary Client)
- ✅ Full CRUD access to projects
- ✅ Can add/remove team members
- ✅ Can edit project details
- ✅ Can delete projects (planning stage only)
- ✅ Can manage all project aspects

### Team Member (Assigned Client)
- ✅ Can view assigned projects
- ✅ Can see project details, tasks, and team
- ❌ Cannot edit project details
- ❌ Cannot delete projects
- ❌ Cannot add/remove team members
- ❌ Cannot change project settings

## Implementation Details

### Database Structure
```
clients table:
- id (primary key)
- name, email, password (auth fields)

client_team_members table:
- id (primary key)
- client_id (owner who invited)
- team_member_client_id (FK to clients.id)
- name, email, status

project_members table:
- id (primary key)
- project_id (FK to projects.id)
- client_team_member_id (FK to client_team_members.id)
- member_type (enum: 'internal', 'client', 'client_team')
```

### Backend Protection

**File:** `app/Http/Controllers/Client/ClientProjectController.php`

All modification methods check if the user is a team member:
```php
$teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();
if ($teamMember) {
    return redirect()->route('client.projects.show', $project)
        ->with('error', 'Team members cannot [action]. Please contact the project owner.');
}
```

Protected methods:
- ✅ `edit()` - Prevents accessing edit form
- ✅ `update()` - Prevents updating project data
- ✅ `destroy()` - Prevents deleting projects
- ✅ `addMember()` - Prevents adding team members
- ✅ `removeMember()` - Prevents removing team members

### Frontend UI

**File:** `resources/views/client/projects/show.blade.php`

#### Team Member Notice Banner
```blade
@if(isset($isTeamMember) && $isTeamMember)
<div class="mb-6 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-2xl p-4">
    <div class="flex items-start gap-3">
        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5">...</svg>
        <div class="text-sm text-purple-800 dark:text-purple-200">
            <p class="font-medium">Team Member - View Only Access</p>
            <p class="mt-1">You have been assigned to this project as a team member. You can view all project details but cannot make changes.</p>
        </div>
    </div>
</div>
@endif
```

#### Visual Indicators
1. **Purple Badge** next to project name
   ```blade
   @if(isset($isTeamMember) && $isTeamMember)
       <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-full">
           Team Member
       </span>
   @endif
   ```

2. **Hidden Action Buttons**
   ```blade
   @if(!isset($isTeamMember) || !$isTeamMember)
       <!-- Edit/Delete buttons -->
       <!-- Add Member button -->
       <!-- Remove Member buttons -->
   @endif
   ```

### User Flow

#### Team Member Login → View Project
1. Login as team member
2. Dashboard shows only assigned projects
3. Click project → See purple notice banner
4. See "Team Member" badge in header
5. No edit/delete buttons visible
6. Cannot add/remove team members
7. All modification URLs return error if accessed directly

#### Attempting Unauthorized Actions
1. **Try to access edit URL**: Redirected with error message
2. **Try to submit update form**: Blocked at controller level
3. **Try to delete project**: Blocked at controller level
4. **Try to add/remove members**: Blocked at controller level

## Testing Checklist

### As Team Member
- [ ] Login with team member credentials
- [ ] Verify dashboard shows only assigned projects
- [ ] Open assigned project
- [ ] Verify purple notice banner is visible
- [ ] Verify "Team Member" badge shows in header
- [ ] Verify Edit button is hidden
- [ ] Verify Delete button is hidden
- [ ] Verify "Add Member" button is hidden
- [ ] Verify "Remove" buttons next to members are hidden
- [ ] Try accessing edit URL directly: `/client/projects/{id}/edit`
  - Should redirect with error message
- [ ] Verify can view all project details
- [ ] Verify can see tasks, team, and other information

### As Project Owner
- [ ] Login with project owner credentials
- [ ] Open owned project
- [ ] Verify NO purple banner (only for team members)
- [ ] Verify NO "Team Member" badge
- [ ] Verify Edit button IS visible (if planning status)
- [ ] Verify Delete button IS visible (if planning status)
- [ ] Verify "Add Member" button IS visible
- [ ] Verify "Remove" buttons ARE visible for team members
- [ ] Can successfully edit project
- [ ] Can successfully add team members
- [ ] Can successfully remove team members

## Error Messages

All error messages are user-friendly:
- ✅ "Team members cannot edit projects. Please contact the project owner."
- ✅ "Team members cannot delete projects. Please contact the project owner."
- ✅ "Team members cannot add other members to projects."
- ✅ "Team members cannot remove members from projects."

## Color Scheme

Team member indicators use purple theme:
- **Background**: `bg-purple-50` (light) / `bg-purple-900/20` (dark)
- **Border**: `border-purple-200` (light) / `border-purple-800` (dark)
- **Text**: `text-purple-800` (light) / `text-purple-200` (dark)
- **Icon**: `text-purple-600` (light) / `text-purple-400` (dark)
- **Badge**: `bg-purple-100` (light) / `bg-purple-900/30` (dark)

## Security Notes

- ✅ Double protection: UI hidden + backend validation
- ✅ Direct URL access is blocked
- ✅ All modification endpoints check team member status
- ✅ Team member detection via `client_team_members.team_member_client_id`
- ✅ Project ownership verified before any action
- ✅ Consistent error handling across all endpoints

## Future Enhancements

Potential features to consider:
- [ ] Granular permissions (view, comment, task-only access)
- [ ] Role-based access within team members
- [ ] Activity log for team member actions
- [ ] Email notifications when team member tries unauthorized action
- [ ] Custom permission matrix per project
- [ ] Temporary access grants (time-limited)
