# Team Management System - AI Coding Agent Instructions

## Project Overview
Laravel 12 team management platform with GitHub activity tracking, UAT system, employee management, attendance, and real-time notifications via Laravel Reverb. Multi-domain system supporting internal employees and external clients.

## Architecture & Key Components

### Core Domain Models
**Employee** is the central hub with `hasMany` relationships to all activity types:
- **Employee Management**: `Employee` (auth guard), `EmployeePayment`, `EmployeeBankAccount`, `EmployeeAccess`, `EmploymentContract`
  - Linked to GitHub via **exact email match** (not username) from Git commits
  - Multi-currency support (`currency` field: USD, BDT, etc.)
  - Dual auth guards: `web` (User) and `employee` (Employee model with separate login)
- **Attendance**: `Attendance`, `MonthlyAdjustment` - manual tracking with monthly corrections
- **Checklists**: `ChecklistTemplate`, `DailyChecklist`, `DailyChecklistItem` - token-based public email access
- **GitHub**: `GitHubLog` - webhook events (push, PR, review, issues) indexed by `event_at`
- **UAT**: `UatProject`, `UatTestCase`, `UatFeedback`, `UatUser` - dual-mode (employees + external via token)
- **Projects**: `Project`, `ProjectTask`, `ProjectFile`, `ProjectMember`, `ProjectDiscussion`, `ProjectExpense`, `ProjectTicket`
- **Invoices**: `Invoice` with dompdf PDF generation
- **Notes**: `PersonalNote`, `NoteRecipient`, `SavedEmail` - multi-type (text/password/code/link/file) with email reminders

### Real-Time Communication
- **Broadcasting**: Laravel Reverb (WebSocket) + Laravel Echo (frontend) for instant notifications
- **Channels**: Private channel `user.{userId}` (use `{auth()->id()}` not `{auth()->guard('employee')->id()}`)
- **Events**: `NewNotification` broadcasts; listen with `Echo.private('user.1').listen('.notification.new', ...)`
- **Setup**: Run `php artisan reverb:start` in separate terminal (REQUIRED for real-time)
- **Important**: Web users (admin) receive notifications on `user.{id}` where id is from `users` table, not employees

### GitHub Integration Workflow
1. **Webhook Endpoint**: `POST /webhook/github` - no auth, receives all events via GitHub webhook
2. **Email Matching**: Webhooks match to employees by **exact commit author email** (not username)
3. **Stored Events**: Pushes, PRs, reviews, issue comments indexed by `event_at` (GitHub timestamp, not created_at)
4. **API Client**: `GitHubApiService` wraps HTTP calls with `withoutVerifying()` in local env (Windows SSL workaround)
5. **PR Operations**: `GitHubPullRequestController` provides comment, review, merge, label management
6. **AI Review**: Analyzes PR diffs first 10 files (~2000 tokens) via OpenAI GPT-4o-mini if `OPENAI_API_KEY` set

### UAT System Architecture
- **Dual Access**: Internal users (full CRUD) vs External users (read + feedback only)
- **Public Routes**: Token-based authentication via `/uat/public/{token}` - no Laravel auth required
- **Test Cases**: Support priority levels (Critical/High/Medium/Low), step-by-step instructions, status tracking
- **Feedback System**: Users provide status (Passed/Failed/Blocked) and comments per test case

## Development Workflow

### Setup & Running
```bash
# One-time setup
composer run setup

# Development (runs 3 services: server, queue, vite)
composer run dev

# Real-time notifications (separate terminal - REQUIRED)
php artisan reverb:start
```

### Testing
```bash
composer test
```

### Database
- MySQL (`.env` configured)
- Migrations in chronological order: employees → attendance → contracts → checklists → invoices → GitHub → UAT
- Critical: Employee email must match Git commit author email for webhook matching

## Code Patterns & Conventions

### Controller Organization
- Resource controllers for main entities: `EmployeeController`, `UatProjectController`, `InvoiceController`
- Specialized controllers for operations: `GitHubWebhookController` (webhook receiver), `GitHubPullRequestController` (API operations)
- Public controllers for token-based access: `UatPublicController`, `ChecklistController::publicView`
- Multiple admin/employee namespaced controllers under `Admin/`, `Employee/`, `Client/`

### Service Layer
- `GitHubApiService`: Centralized GitHub API with SSL verification disabled in local env (Windows workaround)
- Services wrap HTTP clients and add error handling/logging
- Call `withoutVerifying()` only in `app()->environment('local')` contexts

### Frontend Stack
- **Alpine.js**: Primary interactivity (x-data, x-show, x-transition) - store state in Alpine data objects
- **Tailwind CSS**: Utility-first, dark mode with `dark:` variants
- **Vite**: Asset bundling - reference as `@vite(['resources/css/app.css', 'resources/js/app.js'])`
- **Laravel Echo**: Real-time events - listen on private channels: `Echo.private('user.{id}').listen('.notification.new', ...)`

### Email & PDF
- `laravel-dompdf` for PDF generation (invoices, contracts)
- Mail classes: `InvoiceMail`, `DailyChecklistMail`, `UatInvitation`, `NoteReminderMail`
- Scheduled reminders: `notes:send-reminders` runs every minute via scheduler
- Configure `MAIL_*` in `.env`

### Event Broadcasting
- Events in `app/Events`: `NewNotification`, `GitHubActivityReceived`, `TaskCommentAdded`, `NewEmailReceived`
- Implement `ShouldBroadcast` interface for real-time events
- Channels defined in `routes/channels.php`

## Important Conventions

### Employee-GitHub Linking
- Match by **exact email** - employee email in DB must match Git commit author email
- Webhook events without matching employee email are ignored
- GitHub username stored separately but not used for matching

### Currency Handling
- Employees have `currency` field (BDT, USD, etc.)
- Payments are currency-specific - no automatic conversion

### Token-Based Public Access
- Checklists: `email_token` field for public sharing via email
- UAT Projects: `access_token` field for external user access
- Authentication via email verification on first access

### Naming Patterns
- Controllers: `{Entity}Controller` for resources, `{Entity}{Action}Controller` for specialized
- Models: Singular nouns (`Employee`, not `Employees`)
- Routes: Resource names in plural (`/employees`, `/uat`, `/invoices`)
- Migrations: Include timestamp prefix, descriptive names with table name

## External Dependencies

### Required Environment Variables
```env
# Core Laravel
APP_URL=https://team.ryven.co
DB_CONNECTION=mysql

# Real-time (required for notifications)
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=
REVERB_APP_KEY=
REVERB_APP_SECRET=
REVERB_PORT=8080

# GitHub Integration
GITHUB_API_TOKEN=  # Personal access token with repo scope

# AI Features
OPENAI_API_KEY=    # For PR review generation

# Email (for checklist/UAT/invoice sending)
MAIL_MAILER=smtp
```

### Key Packages
- `laravel/breeze`: Authentication scaffolding (Blade + Alpine)
- `laravel/reverb`: WebSocket server for broadcasting
- `barryvdh/laravel-dompdf`: PDF generation
- `laravel/tinker`: REPL for debugging
- `webklex/php-imap`: IMAP email sync for real-time inbox
- `smalot/pdfparser`: Resume parsing for job applications

## Database Relationships Quick Reference

```
Employee (central hub)
├─ hasMany: EmployeePayment, EmployeeBankAccount, EmployeeAccess
├─ hasMany: Attendance, MonthlyAdjustment
├─ hasMany: EmploymentContract
├─ hasMany: ChecklistTemplate, DailyChecklist
├─ hasMany: GitHubLog (event_at indexed, not created_at)
├─ hasMany: PerformanceReview, SalaryReview, Goal
├─ hasMany: ProjectMember, ProjectTask (assigned/created)
└─ belongsToMany: Skill (with proficiency pivot data)

Project (client work)
├─ belongsTo: Client
├─ hasMany: ProjectTask, ProjectFile, ProjectMember
├─ hasMany: ProjectDiscussion, ProjectExpense, ProjectTicket
└─ hasManyThrough: ProjectTaskComment (via ProjectTask)

UatProject (external testing)
├─ hasMany: UatTestCase
├─ hasMany: UatUser (employees + external)
└─ hasMany: UatFeedback (via UatTestCase)
```

## Common Debugging Tips

### WebSocket Issues
- Ensure Reverb server is running: `php artisan reverb:start`
- Check browser console for Echo connection errors
- Verify `VITE_REVERB_*` env vars are set and `npm run dev` was restarted after changes
- Test with: `Echo.private('user.1').listen('.notification.new', console.log)`

### GitHub Webhook Not Working
- Verify webhook URL is accessible: `https://team.ryven.co/webhook/github`
- Check "Recent Deliveries" in GitHub webhook settings for error responses
- Ensure employee email matches Git commit author email exactly
- Webhook endpoint has no auth - it's at `POST /webhook/github`

### AI Review Failures
- Check `OPENAI_API_KEY` is set in `.env`
- Run `php artisan config:clear` after adding API key
- API costs apply per request (~$0.15-0.60 per 1M tokens)
- Reviews analyze first 10 files of PR diffs

### Real-Time Email Sync
- `FetchEmails` job runs via queue listener
- Requires `MAIL_*` config for IMAP connection
- Uses `webklex/php-imap` for email sync
- Queue worker must be running: `php artisan queue:listen`

## File Locations Reference

- **Controllers**: `app/Http/Controllers/` (grouped by domain)
- **Models**: `app/Models/` (all Eloquent models)
- **Views**: `resources/views/` (Blade templates, organized by entity)
- **Routes**: `routes/web.php` (all application routes)
- **Migrations**: `database/migrations/` (chronological order)
- **Assets**: `resources/js/app.js`, `resources/css/app.css` (compiled by Vite)

## Security Notes

- Public endpoints (`/webhook/github`, `/uat/public/*`, `/checklist/*`) have no authentication
- Token-based access for UAT/checklists - tokens are UUIDs stored in database
- GitHub webhook should use Secret in production (currently not implemented)
- SSL verification disabled in local environment only (`GitHubApiService::withoutVerifying()`) - re-enable for production
- Employee auth guard uses separate `employees` table - don't confuse with `users` (web guard)

## Button Components Style Guide
- `<x-black-button>`: Primary action button with pure black background, white text, round and hover effect
- `<x-icon-button>`: Icon-only button with hover background change, used for sidebar icons
- Variants: `variant="outline"` for outline style, `variant="black"` for solid black
- Use these components for consistency across the application UI

```blade<!-- Icon Button -->
<x-icon-button variant="black">
    <svg>...</svg>
</x-icon-button>
```
### Border Radius
- Small: `rounded-md` (4px)
- Default: `rounded-lg` (8px)
- Large: `rounded-xl` (12px)
- Circle: `rounded-full`
## 📱 Responsive Breakpoints
- Mobile: `< 1024px` - Sidebar hidden, hamburger menu
- Desktop: `≥ 1024px` - Sidebar visible, collapsible
## 🔧 Alpine.js State
```javascript
document.addEventListener('alpine:init', () => {
    Alpine.data('sidebar', () => ({
        open: false,
        toggle() {
            this.open = !this.open;
        }
    }));
});
```
## 📂 File Structure
```
resources/views/layouts/
├── app.blade.php           # Main wrapper
├── sidebar.blade.php       # Left navigation
└── topbar.blade.php        # Top header bar
resources/views/components/
├── black-button.blade.php  # Primary button
└── icon-button.blade.php   # Icon-only button
```
## ✨ Key Features
✅ Pure black/white active states
✅ Smooth transitions (200ms)
✅ Dark mode optimized
✅ Mobile responsive
✅ Persistent collapse state
✅ Icon + text navigation
✅ Grouped sections
## 🚀 After Making Changes
- Run `php artisan view:clear` to clear cached views
- Rebuild assets with `npm run build` for production