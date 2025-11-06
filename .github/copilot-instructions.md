# Team Management System - AI Coding Agent Instructions

## Project Overview
This is a Laravel 12 team management application with integrated GitHub activity tracking, UAT (User Acceptance Testing) system, employee management, attendance tracking, and real-time notifications via Laravel Reverb.

## Architecture & Key Components

### Core Domain Models
- **Employee Management**: `Employee`, `EmployeePayment`, `EmployeeBankAccount`, `EmployeeAccess`, `EmploymentContract`
  - Employees linked to GitHub via `email` and `github_username` fields
  - Supports multi-currency salary tracking and discontinuation workflow
- **Attendance System**: `Attendance`, `MonthlyAdjustment` - manual tracking with monthly adjustments
- **Checklist System**: `ChecklistTemplate`, `DailyChecklist` with items - supports email-based public access via tokens
- **GitHub Integration**: `GitHubLog` - stores all GitHub webhook events (pushes, PRs, reviews) linked to employees
- **UAT Testing**: `UatProject`, `UatTestCase`, `UatFeedback`, `UatUser` - dual-mode (internal employees + external clients)
- **Invoice Management**: `Invoice` with PDF generation via dompdf
- **Personal Notes**: `PersonalNote`, `NoteRecipient`, `SavedEmail` - multi-type notes (text, password, backup code, website link, file) with email reminders and autocomplete

### Real-Time Communication
- **Broadcasting**: Uses Laravel Reverb (WebSocket server) for instant notifications
- **Frontend**: Laravel Echo + Pusher.js for WebSocket connections
- **Channel**: Private channel `user.{userId}` for per-user notifications
- **Event**: `NewNotification` broadcasts to trigger UI updates
- **Setup**: Run `php artisan reverb:start` in separate terminal (required for real-time features)

### GitHub Integration Workflow
1. **Webhook Endpoint**: `POST /webhook/github` - no auth required, receives all GitHub events
2. **Event Processing**: `GitHubWebhookController` matches events to employees by email
3. **Stored Events**: push, pull_request, pull_request_review, issue comments, branch creation/deletion
4. **PR Management**: `GitHubPullRequestController` + `GitHubApiService` for full PR operations (comment, review, merge, labels)
5. **AI Code Review**: OpenAI GPT-4o-mini integration analyzes PR diffs (first 10 files, ~2000 tokens) - requires `OPENAI_API_KEY`
6. **API Token**: Configure `GITHUB_API_TOKEN` in `.env` for GitHub API calls

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

# Development (runs all three services concurrently)
composer run dev
# This starts: Laravel server (8000), Queue worker, Vite dev server

# Real-time features (run in separate terminal)
php artisan reverb:start  # WebSocket server on port 8080
```

### Testing
```bash
composer test  # Runs PHPUnit test suite
```

### Database
- MySQL-based (configured via `.env`)
- Migrations in chronological order - employee system → attendance → contracts → checklists → invoices → GitHub → UAT
- Key relationship: `Employee` is the central hub with hasMany to all activity types

## Code Patterns & Conventions

### Controller Organization
- Resource controllers for main entities: `EmployeeController`, `UatProjectController`, `InvoiceController`
- Specialized controllers for GitHub: `GitHubWebhookController` (webhook receiver), `GitHubPullRequestController` (API operations)
- Public controllers for token-based access: `UatPublicController`, `ChecklistController::publicView`

### Service Layer
- `GitHubApiService`: Centralized GitHub API client with SSL verification disabled for local dev
- HTTP client uses `withoutVerifying()` in local environment only (Windows development workaround)

### Frontend Stack
- **Alpine.js**: Primary interactivity (modals, forms, toggles) - use `x-data` for component state
- **Tailwind CSS**: Utility-first styling with dark mode support (`dark:` variants)
- **Vite**: Asset bundling - use `@vite(['resources/css/app.css', 'resources/js/app.js'])` in layouts
- **Laravel Echo**: Real-time event listening - example: `Echo.private('user.1').listen('.notification.new', ...)`

### Email System
- Uses `laravel-dompdf` for PDF generation (invoices, contracts)
- Mail classes: `InvoiceMail`, `DailyChecklistMail`, `UatInvitation`, `NoteReminderMail`
- Scheduled reminders: `notes:send-reminders` command runs every minute via Laravel Scheduler
- Configure `MAIL_*` settings in `.env`

### Event Broadcasting
- Events in `app/Events`: `NewNotification`, `GitHubActivityReceived`
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

## Common Debugging Tips

### WebSocket Issues
- Ensure Reverb server is running: `php artisan reverb:start`
- Check browser console for Echo connection errors
- Verify `VITE_REVERB_*` env vars are set and `npm run dev` was restarted after changes

### GitHub Webhook Not Working
- Verify webhook URL is accessible: `https://team.ryven.co/webhook/github`
- Check "Recent Deliveries" in GitHub webhook settings for error responses
- Ensure employee email matches Git commit author email exactly

### AI Review Failures
- Check `OPENAI_API_KEY` is set in `.env`
- Run `php artisan config:clear` after adding API key
- API costs apply per request (~$0.15-0.60 per 1M tokens)

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
- SSL verification disabled in local environment only - re-enable for production

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