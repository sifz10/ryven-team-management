<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePaymentController;
use App\Http\Controllers\EmployeeBankAccountController;
use App\Http\Controllers\EmployeeAccessController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\EmploymentContractController;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\GitHubWebhookController;
use App\Http\Controllers\GitHubPullRequestController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\UatProjectController;
use App\Http\Controllers\UatPublicController;
use App\Http\Controllers\PersonalNoteController;
use App\Http\Controllers\ReviewCycleController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\SalaryReviewController;
use App\Http\Controllers\ChatbotApiController;
use App\Http\Controllers\Admin\ChatbotController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('client.login');
});

// Employee Authentication Routes
Route::prefix('employee')->name('employee.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest:employee')->group(function () {
        Route::get('/login', [App\Http\Controllers\Employee\Auth\LoginController::class, 'create'])
            ->name('login');
        Route::post('/login', [App\Http\Controllers\Employee\Auth\LoginController::class, 'store'])
            ->name('login.store');
    });

    // Authenticated employee routes
    Route::middleware('employee.auth')->group(function () {
        Route::post('/logout', [App\Http\Controllers\Employee\Auth\LoginController::class, 'destroy'])
            ->name('logout');
        Route::get('/dashboard', [App\Http\Controllers\Employee\DashboardController::class, 'index'])
            ->name('dashboard');

        // Employee profile and settings
        Route::get('/profile', [App\Http\Controllers\Employee\ProfileController::class, 'edit'])
            ->name('profile.edit');
        Route::patch('/profile', [App\Http\Controllers\Employee\ProfileController::class, 'update'])
            ->name('profile.update');
    });
});

// Public checklist routes (no authentication required)
Route::get('/checklist/{token}', [ChecklistController::class, 'publicView'])->name('checklist.public.view');
Route::get('/checklist/{token}/item/{item}/toggle', [ChecklistController::class, 'publicToggleItem'])->name('checklist.public.toggle');
Route::post('/checklist/{token}/work', [ChecklistController::class, 'storeWorkSubmission'])->name('checklist.public.work.store');
Route::delete('/checklist/{token}/work/{submission}', [ChecklistController::class, 'deleteWorkSubmission'])->name('checklist.public.work.delete');

// Chatbot Widget API (no authentication - uses token)
Route::post('/api/chatbot/init', [ChatbotApiController::class, 'initWidget']);
Route::post('/api/chatbot/message', [ChatbotApiController::class, 'sendMessage']);
Route::get('/api/chatbot/conversation/{conversation}', [ChatbotApiController::class, 'getConversation']);

// Public UAT routes (no authentication required)
Route::get('/uat/public/{token}', [UatPublicController::class, 'view'])->name('uat.public.view');
Route::get('/uat/public/{token}/updates', [UatPublicController::class, 'getUpdates'])->name('uat.public.updates');
Route::post('/uat/public/{token}/authenticate', [UatPublicController::class, 'authenticate'])->name('uat.public.authenticate');
Route::post('/uat/public/{token}/users', [UatPublicController::class, 'addUser'])->name('uat.public.users.add');
Route::post('/uat/public/{token}/test-cases', [UatPublicController::class, 'storeTestCase'])->name('uat.public.test-cases.store');
Route::delete('/uat/public/{token}/test-cases/{testCase}', [UatPublicController::class, 'destroyTestCase'])->name('uat.public.test-cases.destroy');
Route::post('/uat/public/{token}/test-cases/{testCase}/feedback', [UatPublicController::class, 'submitFeedback'])->name('uat.public.feedback');
Route::post('/uat/public/{token}/test-cases/{testCase}/status', [UatPublicController::class, 'updateStatus'])->name('uat.public.status');

// GitHub Webhook (no authentication required)
Route::post('/webhook/github', [GitHubWebhookController::class, 'handle'])->name('webhook.github');

// Test Submission Routes (no authentication required)
Route::prefix('test-submission')->name('test.submission.')->group(function () {
    Route::get('/{token}', [App\Http\Controllers\TestSubmissionController::class, 'show'])->name('show');
    Route::post('/{token}', [App\Http\Controllers\TestSubmissionController::class, 'submit'])->name('submit');
    Route::get('/{token}/download', [App\Http\Controllers\TestSubmissionController::class, 'downloadTest'])->name('download');
});

// GitHub Activities API (for AJAX load more)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/employees/{employee}/github/activities', [GitHubWebhookController::class, 'loadActivities'])->name('github.activities.load');
    Route::get('/employees/{employee}/github/check-new', [GitHubWebhookController::class, 'checkNew'])->name('github.activities.checkNew');

    // GitHub Logs Page
    Route::get('/github-logs', [GitHubWebhookController::class, 'logs'])->name('github.logs')->middleware('permission:view-github-logs');

    // GitHub Pull Request routes
    Route::get('/github/pr/{log}', [GitHubPullRequestController::class, 'show'])->name('github.pr.show')->middleware('permission:view-github-logs');
    Route::get('/github/pr/{log}/details', [GitHubPullRequestController::class, 'details'])->name('github.pr.details')->middleware('permission:view-github-logs');
    Route::post('/github/pr/{log}/comment', [GitHubPullRequestController::class, 'comment'])->name('github.pr.comment')->middleware('permission:manage-github-logs');
    Route::post('/github/pr/{log}/review', [GitHubPullRequestController::class, 'review'])->name('github.pr.review')->middleware('permission:manage-github-logs');
    Route::post('/github/pr/{log}/assign', [GitHubPullRequestController::class, 'assign'])->name('github.pr.assign')->middleware('permission:manage-github-logs');
    Route::delete('/github/pr/{log}/assign/reviewer/{username}', [GitHubPullRequestController::class, 'removeReviewer'])->name('github.pr.removeReviewer')->middleware('permission:manage-github-logs');
    Route::delete('/github/pr/{log}/assign/assignee/{username}', [GitHubPullRequestController::class, 'removeAssignee'])->name('github.pr.removeAssignee')->middleware('permission:manage-github-logs');
    Route::post('/github/pr/{log}/labels', [GitHubPullRequestController::class, 'addLabel'])->name('github.pr.addLabel')->middleware('permission:manage-github-logs');
    Route::delete('/github/pr/{log}/labels/{label}', [GitHubPullRequestController::class, 'removeLabel'])->name('github.pr.removeLabel')->middleware('permission:manage-github-logs');
    Route::post('/github/pr/{log}/merge', [GitHubPullRequestController::class, 'merge'])->name('github.pr.merge')->middleware('permission:manage-github-logs');
    Route::post('/github/pr/{log}/close', [GitHubPullRequestController::class, 'close'])->name('github.pr.close')->middleware('permission:manage-github-logs');
    Route::post('/github/pr/{log}/ai-review', [GitHubPullRequestController::class, 'generateAIReview'])->name('github.pr.aiReview')->middleware('permission:view-github-logs');

    // Client Management routes
    Route::resource('clients', ClientController::class)->middleware([
        'index' => 'permission:view-clients',
        'show' => 'permission:view-clients',
        'create' => 'permission:create-clients',
        'store' => 'permission:create-clients',
        'edit' => 'permission:edit-clients',
        'update' => 'permission:edit-clients',
        'destroy' => 'permission:delete-clients',
    ]);

    // Client Team Members routes
    Route::post('clients/{client}/team-members', [App\Http\Controllers\ClientTeamMemberController::class, 'store'])->name('clients.team-members.store')->middleware('permission:edit-clients');
    Route::delete('clients/{client}/team-members/{teamMember}', [App\Http\Controllers\ClientTeamMemberController::class, 'destroy'])->name('clients.team-members.destroy')->middleware('permission:edit-clients');
    Route::post('clients/{client}/team-members/{teamMember}/resend', [App\Http\Controllers\ClientTeamMemberController::class, 'resendInvitation'])->name('clients.team-members.resend')->middleware('permission:edit-clients');
    Route::post('clients/{client}/team-members/{teamMember}/projects', [App\Http\Controllers\ClientTeamMemberController::class, 'updateProjects'])->name('clients.team-members.update-projects')->middleware('permission:edit-clients');

    // Project Management routes
    Route::resource('projects', ProjectController::class)->middleware([
        'index' => 'permission:view-projects',
        'show' => 'permission:view-projects',
        'create' => 'permission:create-projects',
        'store' => 'permission:create-projects',
        'edit' => 'permission:edit-projects',
        'update' => 'permission:edit-projects',
        'destroy' => 'permission:delete-projects',
    ]);

    // Project Tasks
    Route::post('/projects/{project}/tasks', [ProjectController::class, 'storeTasks'])->name('projects.tasks.store')->middleware('permission:create-projects');
    Route::put('/projects/{project}/tasks/{task}', [ProjectController::class, 'updateTask'])->name('projects.tasks.update')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/tasks/{task}', [ProjectController::class, 'destroyTask'])->name('projects.tasks.destroy')->middleware('permission:delete-projects');
    Route::post('/projects/{project}/tasks/order', [ProjectController::class, 'updateTaskOrder'])->name('projects.tasks.order')->middleware('permission:edit-projects');
    Route::put('/projects/{project}/tasks/{task}/move', [ProjectController::class, 'moveTask'])->name('projects.tasks.move')->middleware('permission:edit-projects');
    Route::post('/projects/{project}/tasks/{task}/checklist', [ProjectController::class, 'updateChecklist'])->name('projects.tasks.checklist.update')->middleware('permission:edit-projects');

    // Task Files
    Route::post('/projects/{project}/tasks/{task}/files', [ProjectController::class, 'uploadTaskFile'])->name('projects.tasks.files.upload')->middleware('permission:edit-projects');
    Route::get('/projects/{project}/tasks/{task}/files/{file}/download', [ProjectController::class, 'downloadTaskFile'])->name('projects.tasks.files.download')->middleware('permission:view-projects');
    Route::delete('/projects/{project}/tasks/{task}/files/{file}', [ProjectController::class, 'deleteTaskFile'])->name('projects.tasks.files.delete')->middleware('permission:delete-projects');

    // Task Comments
    Route::get('/projects/{project}/tasks/{task}/comments', [ProjectController::class, 'getTaskComments'])->name('projects.tasks.comments.index')->middleware('permission:view-projects');
    Route::post('/projects/{project}/tasks/{task}/comments', [ProjectController::class, 'storeTaskComment'])->name('projects.tasks.comments.store')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/tasks/{task}/comments/{comment}', [ProjectController::class, 'destroyTaskComment'])->name('projects.tasks.comments.destroy')->middleware('permission:view-projects');

    // Comment Replies
    Route::post('/projects/{project}/tasks/{task}/comments/{comment}/replies', [ProjectController::class, 'storeCommentReply'])->name('projects.tasks.comments.replies.store')->middleware('permission:edit-projects');

    // Comment Reactions
    Route::post('/projects/{project}/tasks/{task}/comments/{comment}/reactions', [ProjectController::class, 'toggleCommentReaction'])->name('projects.tasks.comments.reactions.toggle')->middleware('permission:edit-projects');

    // Task Reminders
    Route::get('/projects/{project}/tasks/{task}/reminders', [ProjectController::class, 'getTaskReminders'])->name('projects.tasks.reminders.index')->middleware('permission:view-projects');
    Route::post('/projects/{project}/tasks/{task}/reminders', [ProjectController::class, 'storeTaskReminder'])->name('projects.tasks.reminders.store')->middleware('permission:edit-projects');
    Route::put('/projects/{project}/tasks/{task}/reminders/{reminder}', [ProjectController::class, 'updateTaskReminder'])->name('projects.tasks.reminders.update')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/tasks/{task}/reminders/{reminder}', [ProjectController::class, 'destroyTaskReminder'])->name('projects.tasks.reminders.destroy')->middleware('permission:delete-projects');
    Route::get('/projects/{project}/tasks/{task}/reminder-recipients', [ProjectController::class, 'getTaskRecipientsForReminders'])->name('projects.tasks.reminder.recipients')->middleware('permission:view-projects');

    // Employee autocomplete for mentions
    Route::get('/projects/{project}/employees/mention', [ProjectController::class, 'getEmployeesForMention'])->name('projects.employees.mention')->middleware('permission:view-projects');

    // Project Files
    Route::post('/projects/{project}/files', [ProjectController::class, 'storeFile'])->name('projects.files.store')->middleware('permission:create-projects');
    Route::delete('/projects/{project}/files/{file}', [ProjectController::class, 'destroyFile'])->name('projects.files.destroy')->middleware('permission:delete-projects');

    // Project Discussions
    Route::post('/projects/{project}/discussions', [ProjectController::class, 'storeDiscussion'])->name('projects.discussions.store')->middleware('permission:create-projects');
    Route::post('/projects/{project}/discussions/{discussion}/toggle-pin', [ProjectController::class, 'togglePinDiscussion'])->name('projects.discussions.toggle-pin')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/discussions/{discussion}', [ProjectController::class, 'destroyDiscussion'])->name('projects.discussions.destroy')->middleware('permission:delete-projects');

    // Project Expenses
    Route::post('/projects/{project}/expenses', [ProjectController::class, 'storeExpense'])->name('projects.expenses.store')->middleware('permission:create-projects');
    Route::put('/projects/{project}/expenses/{expense}', [ProjectController::class, 'updateExpense'])->name('projects.expenses.update')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/expenses/{expense}', [ProjectController::class, 'destroyExpense'])->name('projects.expenses.destroy')->middleware('permission:delete-projects');

    // Project Tickets
    Route::post('/projects/{project}/tickets', [ProjectController::class, 'storeTicket'])->name('projects.tickets.store')->middleware('permission:create-projects');
    Route::put('/projects/{project}/tickets/{ticket}', [ProjectController::class, 'updateTicket'])->name('projects.tickets.update')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/tickets/{ticket}', [ProjectController::class, 'destroyTicket'])->name('projects.tickets.destroy')->middleware('permission:delete-projects');

    // Standalone Ticket Management System
    Route::get('/tickets', [App\Http\Controllers\TicketController::class, 'index'])->name('tickets.index')->middleware('permission:view-projects');
    Route::get('/tickets/create', [App\Http\Controllers\TicketController::class, 'create'])->name('tickets.create')->middleware('permission:create-projects');
    Route::post('/tickets', [App\Http\Controllers\TicketController::class, 'store'])->name('tickets.store')->middleware('permission:create-projects');
    Route::get('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'show'])->name('tickets.show')->middleware('permission:view-projects');
    Route::get('/tickets/{ticket}/comments', [App\Http\Controllers\TicketController::class, 'getComments'])->name('tickets.comments.index')->middleware('permission:view-projects');
    Route::post('/tickets/{ticket}/comments', [App\Http\Controllers\TicketController::class, 'storeComment'])->name('tickets.comments.store')->middleware('permission:view-projects');
    Route::put('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'update'])->name('tickets.update')->middleware('permission:edit-projects');
    Route::delete('/tickets/{ticket}', [App\Http\Controllers\TicketController::class, 'destroy'])->name('tickets.destroy')->middleware('permission:delete-projects');

    // Ticket Notifications
    Route::get('/tickets/notifications/unread', [App\Http\Controllers\TicketController::class, 'getUnreadNotifications'])->name('tickets.notifications.unread');
    Route::post('/tickets/notifications/{notification}/read', [App\Http\Controllers\TicketController::class, 'markNotificationRead'])->name('tickets.notifications.read');
    Route::post('/tickets/notifications/mark-all-read', [App\Http\Controllers\TicketController::class, 'markAllNotificationsRead'])->name('tickets.notifications.mark-all-read');

    // Legacy project routes
    Route::get('/projects/{project}/today-work', [ProjectController::class, 'todayWork'])->name('projects.today-work')->middleware('permission:view-projects');
    Route::put('/projects/{project}/work/{submission}', [ProjectController::class, 'updateWorkSubmission'])->name('projects.work.update')->middleware('permission:edit-projects');
    Route::delete('/projects/{project}/work/{submission}', [ProjectController::class, 'deleteWorkSubmission'])->name('projects.work.delete')->middleware('permission:edit-projects');
    Route::post('/projects/{project}/send-report', [ProjectController::class, 'sendReport'])->name('projects.send-report')->middleware('permission:edit-projects');
    Route::get('/projects-today-summary', [ProjectController::class, 'todaySummary'])->name('projects.today-summary')->middleware('permission:view-projects');
});

// SOP page (authenticated)
Route::get('/sop', function () {
    return view('sop');
})->middleware(['auth'])->name('sop');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/artisan/{command}', function (Request $request) {
        Artisan::call($request->command);
        return 'Executed artisan command boss!';
    });

    Route::get('/fix-cache', function () {
        $output = [];

        // Ensure directories exist
        $dirs = [
            storage_path('framework/views'),
            storage_path('framework/cache'),
            storage_path('framework/cache/data'),
            storage_path('framework/sessions'),
        ];

        foreach ($dirs as $dir) {
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
                $output[] = "Created directory: $dir";
            } else {
                $output[] = "Directory exists: $dir";
            }
        }

        // Clear caches
        Artisan::call('config:clear');
        $output[] = 'Config cleared';

        Artisan::call('cache:clear');
        $output[] = 'Cache cleared';

        Artisan::call('view:clear');
        $output[] = 'View cache cleared';

        Artisan::call('route:clear');
        $output[] = 'Route cache cleared';

        // Recache for production
        Artisan::call('config:cache');
        $output[] = 'Config cached';

        return response()->json([
            'success' => true,
            'message' => 'All caches fixed!',
            'details' => $output
        ]);
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notification routes
    Route::get('/notifications/page', [App\Http\Controllers\NotificationController::class, 'page'])->name('notifications.page');
    Route::get('/notifications', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::get('/notifications/unread-count', [App\Http\Controllers\NotificationController::class, 'unreadCount'])->name('notifications.unread-count');
    Route::post('/notifications/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/unread', [App\Http\Controllers\NotificationController::class, 'markAsUnread'])->name('notifications.unread');
    Route::post('/notifications/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::post('/notifications/clear-read', [App\Http\Controllers\NotificationController::class, 'clearRead'])->name('notifications.clear-read');
    Route::delete('/notifications/{notification}', [App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    // Employee Management routes
    Route::resource('employees', EmployeeController::class)->middleware([
        'index' => 'permission:view-employees',
        'show' => 'permission:view-employees',
        'create' => 'permission:create-employees',
        'store' => 'permission:create-employees',
        'edit' => 'permission:edit-employees',
        'update' => 'permission:edit-employees',
        'destroy' => 'permission:delete-employees',
    ]);
    Route::get('employees-deleted', [EmployeeController::class, 'deleted'])->name('employees.deleted')->middleware('permission:view-employees');
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore')->middleware('permission:edit-employees');
    Route::delete('employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete')->middleware('permission:delete-employees');
    Route::post('employees/{employee}/discontinue', [EmployeeController::class, 'discontinue'])->name('employees.discontinue')->middleware('permission:discontinue-employees');
    Route::post('employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])->name('employees.reactivate')->middleware('permission:edit-employees');

    // Employee Payments routes
    Route::post('employees/{employee}/payments', [EmployeePaymentController::class, 'store'])->name('employees.payments.store')->middleware('permission:manage-payments');
    Route::put('employees/{employee}/payments/{payment}', [EmployeePaymentController::class, 'update'])->name('employees.payments.update')->middleware('permission:manage-payments');
    Route::delete('employees/{employee}/payments/{payment}', [EmployeePaymentController::class, 'destroy'])->name('employees.payments.destroy')->middleware('permission:manage-payments');

    // Payment Notes routes
    Route::post('employees/{employee}/payments/{payment}/notes', [App\Http\Controllers\ActivityNoteController::class, 'store'])->name('employees.payments.notes.store')->middleware('permission:manage-payments');
    Route::delete('employees/{employee}/payments/{payment}/notes/{note}', [App\Http\Controllers\ActivityNoteController::class, 'destroy'])->name('employees.payments.notes.destroy')->middleware('permission:manage-payments');

    // Bank Accounts routes
    Route::post('employees/{employee}/bank-accounts', [EmployeeBankAccountController::class, 'store'])->name('employees.bank-accounts.store')->middleware('permission:manage-bank-accounts');
    Route::put('employees/{employee}/bank-accounts/{account}', [EmployeeBankAccountController::class, 'update'])->name('employees.bank-accounts.update')->middleware('permission:manage-bank-accounts');
    Route::delete('employees/{employee}/bank-accounts/{account}', [EmployeeBankAccountController::class, 'destroy'])->name('employees.bank-accounts.destroy')->middleware('permission:manage-bank-accounts');

    // Employee Accesses routes
    Route::post('employees/{employee}/accesses', [EmployeeAccessController::class, 'store'])->name('employees.accesses.store')->middleware('permission:manage-accesses');
    Route::put('employees/{employee}/accesses/{access}', [EmployeeAccessController::class, 'update'])->name('employees.accesses.update')->middleware('permission:manage-accesses');
    Route::delete('employees/{employee}/accesses/{access}', [EmployeeAccessController::class, 'destroy'])->name('employees.accesses.destroy')->middleware('permission:manage-accesses');

    // Employment Contract routes
    Route::get('contracts', [EmploymentContractController::class, 'index'])->name('contracts.index')->middleware('permission:view-contracts');
    Route::get('employees/{employee}/contracts/create', [EmploymentContractController::class, 'create'])->name('contracts.create')->middleware('permission:create-contracts');
    Route::post('employees/{employee}/contracts', [EmploymentContractController::class, 'store'])->name('contracts.store')->middleware('permission:create-contracts');
    Route::get('contracts/{contract}', [EmploymentContractController::class, 'show'])->name('contracts.show')->middleware('permission:view-contracts');
    Route::get('contracts/{contract}/edit', [EmploymentContractController::class, 'edit'])->name('contracts.edit')->middleware('permission:edit-contracts');
    Route::put('contracts/{contract}', [EmploymentContractController::class, 'update'])->name('contracts.update')->middleware('permission:edit-contracts');
    Route::delete('contracts/{contract}', [EmploymentContractController::class, 'destroy'])->name('contracts.destroy')->middleware('permission:delete-contracts');
    Route::get('contracts/{contract}/pdf', [EmploymentContractController::class, 'downloadPdf'])->name('contracts.pdf')->middleware('permission:view-contracts');

    // Attendance routes
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index')->middleware('permission:view-attendance');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store')->middleware('permission:manage-attendance');
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update')->middleware('permission:manage-attendance');
    Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy')->middleware('permission:manage-attendance');
    Route::post('attendance/bulk-populate', [AttendanceController::class, 'bulkPopulate'])->name('attendance.bulk-populate')->middleware('permission:approve-attendance');
    Route::post('attendance/monthly-adjustment', [AttendanceController::class, 'saveMonthlyAdjustment'])->name('attendance.monthly-adjustment')->middleware('permission:approve-attendance');

    // Salary Review routes
    Route::resource('salary-reviews', SalaryReviewController::class)->middleware([
        'index' => 'permission:view-employees',
        'show' => 'permission:view-employees',
        'edit' => 'permission:manage-employees',
        'update' => 'permission:manage-employees',
    ]);
    Route::get('employees/{employee}/salary-history', [SalaryReviewController::class, 'employeeSalaryHistory'])->name('employees.salary-history')->middleware('permission:view-employees');
    Route::post('employees/{employee}/adjust-salary', [SalaryReviewController::class, 'adjustSalary'])->name('employees.adjust-salary')->middleware('permission:manage-employees');

    // Checklist routes
    Route::post('employees/{employee}/checklists/templates', [ChecklistController::class, 'storeTemplate'])->name('employees.checklists.templates.store')->middleware('permission:create-checklists');
    Route::put('employees/{employee}/checklists/templates/{template}', [ChecklistController::class, 'updateTemplate'])->name('employees.checklists.templates.update')->middleware('permission:edit-checklists');
    Route::delete('employees/{employee}/checklists/templates/{template}', [ChecklistController::class, 'destroyTemplate'])->name('employees.checklists.templates.destroy')->middleware('permission:delete-checklists');
    Route::post('employees/{employee}/checklists/items/{item}/toggle', [ChecklistController::class, 'toggleItem'])->name('employees.checklists.items.toggle')->middleware('permission:view-checklists');
    Route::post('employees/{employee}/checklists/generate-today', [ChecklistController::class, 'generateTodayChecklists'])->name('employees.checklists.generate-today')->middleware('permission:create-checklists');
    Route::post('employees/{employee}/checklists/{checklist}/send-email', [ChecklistController::class, 'sendChecklistEmail'])->name('employees.checklists.send-email')->middleware('permission:view-checklists');

    // Invoice routes
    Route::resource('invoices', InvoiceController::class)->middleware([
        'index' => 'permission:view-invoices',
        'show' => 'permission:view-invoices',
        'create' => 'permission:create-invoices',
        'store' => 'permission:create-invoices',
        'edit' => 'permission:edit-invoices',
        'update' => 'permission:edit-invoices',
        'destroy' => 'permission:delete-invoices',
    ]);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf')->middleware('permission:view-invoices');
    Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'previewPdf'])->name('invoices.preview')->middleware('permission:view-invoices');
    Route::post('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email')->middleware('permission:edit-invoices');

    // UAT routes
    Route::resource('uat', UatProjectController::class)->parameters(['uat' => 'project'])->middleware([
        'index' => 'permission:view-uat-projects',
        'show' => 'permission:view-uat-projects',
        'create' => 'permission:create-uat-projects',
        'store' => 'permission:create-uat-projects',
        'edit' => 'permission:edit-uat-projects',
        'update' => 'permission:edit-uat-projects',
        'destroy' => 'permission:delete-uat-projects',
    ]);
    Route::post('uat/{project}/test-cases', [UatProjectController::class, 'storeTestCase'])->name('uat.test-cases.store')->middleware('permission:manage-test-cases');
    Route::get('uat/{project}/test-cases/{testCase}/edit', [UatProjectController::class, 'editTestCase'])->name('uat.test-cases.edit')->middleware('permission:manage-test-cases');
    Route::put('uat/{project}/test-cases/{testCase}', [UatProjectController::class, 'updateTestCase'])->name('uat.test-cases.update')->middleware('permission:manage-test-cases');
    Route::delete('uat/{project}/test-cases/{testCase}', [UatProjectController::class, 'destroyTestCase'])->name('uat.test-cases.destroy')->middleware('permission:manage-test-cases');
    Route::post('uat/{project}/users', [UatProjectController::class, 'addUser'])->name('uat.users.add')->middleware('permission:manage-uat-users');
    Route::delete('uat/{project}/users/{user}', [UatProjectController::class, 'removeUser'])->name('uat.users.remove')->middleware('permission:manage-uat-users');

    // Personal Notes routes
    Route::get('notes/emails/search', [PersonalNoteController::class, 'searchEmails'])->name('notes.emails.search')->middleware('permission:manage-notes');
    Route::resource('notes', PersonalNoteController::class)->middleware([
        'index' => 'permission:view-notes',
        'show' => 'permission:view-notes',
        'create' => 'permission:manage-notes',
        'store' => 'permission:manage-notes',
        'edit' => 'permission:manage-notes',
        'update' => 'permission:manage-notes',
        'destroy' => 'permission:manage-notes',
    ]);

    // Role Management routes
    Route::get('roles/assign', [App\Http\Controllers\RoleController::class, 'assignForm'])->name('roles.assign-form')->middleware('permission:assign-permissions');
    Route::post('roles/assign/{employee}', [App\Http\Controllers\RoleController::class, 'assignRoles'])->name('roles.assign-roles')->middleware('permission:assign-permissions');
    Route::resource('roles', App\Http\Controllers\RoleController::class)->middleware([
        'index' => 'permission:view-roles',
        'show' => 'permission:view-roles',
        'create' => 'permission:manage-roles',
        'store' => 'permission:manage-roles',
        'edit' => 'permission:manage-roles',
        'update' => 'permission:manage-roles',
        'destroy' => 'permission:manage-roles',
    ]);

    // Content Calendar & Social Media routes
    Route::get('social/calendar', [App\Http\Controllers\ContentCalendarController::class, 'index'])->name('social.calendar');
    Route::get('social/calendar/posts', [App\Http\Controllers\ContentCalendarController::class, 'getPostsForDate'])->name('social.calendar.posts');
    Route::get('social/calendar/month-data', [App\Http\Controllers\ContentCalendarController::class, 'getMonthData'])->name('social.calendar.month-data');

    // Social Accounts routes
    Route::get('social/accounts/connect/linkedin', [App\Http\Controllers\SocialAccountController::class, 'connectLinkedIn'])->name('social.connect.linkedin');
    Route::get('social/accounts/connect/facebook', [App\Http\Controllers\SocialAccountController::class, 'connectFacebook'])->name('social.connect.facebook');
    Route::get('social/accounts/connect/twitter', [App\Http\Controllers\SocialAccountController::class, 'connectTwitter'])->name('social.connect.twitter');
    Route::get('social/callback/linkedin', [App\Http\Controllers\SocialOAuthCallbackController::class, 'linkedin'])->name('social.callback.linkedin');
    Route::get('social/callback/facebook', [App\Http\Controllers\SocialOAuthCallbackController::class, 'facebook'])->name('social.callback.facebook');
    Route::get('social/callback/twitter', [App\Http\Controllers\SocialOAuthCallbackController::class, 'twitter'])->name('social.callback.twitter');
    Route::get('social/debug/linkedin', function() {
        return response()->json([
            'config' => [
                'client_id' => config('services.linkedin.client_id'),
                'redirect' => config('services.linkedin.redirect'),
                'has_secret' => !empty(config('services.linkedin.client_secret'))
            ]
        ]);
    })->name('social.debug.linkedin');
    Route::post('social/accounts/{socialAccount}/test', [App\Http\Controllers\SocialAccountController::class, 'testConnection'])->name('social.accounts.test');
    Route::resource('social/accounts', App\Http\Controllers\SocialAccountController::class)->names('social.accounts');

    // Social Posts routes
    Route::post('social/posts/{socialPost}/generate', [App\Http\Controllers\SocialPostController::class, 'generate'])->name('social.posts.generate');
    Route::post('social/posts/{socialPost}/select-generation', [App\Http\Controllers\SocialPostController::class, 'selectGeneration'])->name('social.posts.select-generation');
    Route::post('social/posts/{socialPost}/publish', [App\Http\Controllers\SocialPostController::class, 'publish'])->name('social.posts.publish');
    Route::resource('social/posts', App\Http\Controllers\SocialPostController::class)->parameters(['posts' => 'socialPost'])->names('social.posts');

    // Email System routes
    Route::prefix('email')->group(function () {
        // Email Account Management
        Route::get('accounts', [App\Http\Controllers\EmailAccountController::class, 'index'])->name('email.accounts.index');
        Route::get('accounts/create', [App\Http\Controllers\EmailAccountController::class, 'create'])->name('email.accounts.create');
        Route::post('accounts', [App\Http\Controllers\EmailAccountController::class, 'store'])->name('email.accounts.store');
        Route::get('accounts/{account}/edit', [App\Http\Controllers\EmailAccountController::class, 'edit'])->name('email.accounts.edit');
        Route::put('accounts/{account}', [App\Http\Controllers\EmailAccountController::class, 'update'])->name('email.accounts.update');
        Route::delete('accounts/{account}', [App\Http\Controllers\EmailAccountController::class, 'destroy'])->name('email.accounts.destroy');
        Route::post('accounts/{account}/sync', [App\Http\Controllers\EmailAccountController::class, 'sync'])->name('email.accounts.sync');
        Route::post('accounts/{account}/test', [App\Http\Controllers\EmailAccountController::class, 'testConnection'])->name('email.accounts.test');
        Route::post('accounts/{account}/toggle', [App\Http\Controllers\EmailAccountController::class, 'toggleActive'])->name('email.accounts.toggle');

        // Email Inbox
        Route::get('inbox', [App\Http\Controllers\EmailInboxController::class, 'index'])->name('email.inbox.index');
        Route::get('inbox/unread-count', [App\Http\Controllers\EmailInboxController::class, 'unreadCount'])->name('email.inbox.unread-count');
        Route::get('inbox/{message}', [App\Http\Controllers\EmailInboxController::class, 'show'])->name('email.inbox.show');
        Route::get('compose', [App\Http\Controllers\EmailInboxController::class, 'compose'])->name('email.inbox.compose');
        Route::post('send', [App\Http\Controllers\EmailInboxController::class, 'send'])->name('email.inbox.send');
        Route::get('inbox/{message}/reply', [App\Http\Controllers\EmailInboxController::class, 'reply'])->name('email.inbox.reply');
        Route::post('inbox/{message}/reply', [App\Http\Controllers\EmailInboxController::class, 'sendReply'])->name('email.inbox.send-reply');
        Route::post('inbox/{message}/read', [App\Http\Controllers\EmailInboxController::class, 'markAsRead'])->name('email.inbox.read');
        Route::post('inbox/{message}/unread', [App\Http\Controllers\EmailInboxController::class, 'markAsUnread'])->name('email.inbox.unread');
        Route::post('inbox/{message}/star', [App\Http\Controllers\EmailInboxController::class, 'toggleStar'])->name('email.inbox.star');
        Route::post('inbox/{message}/trash', [App\Http\Controllers\EmailInboxController::class, 'trash'])->name('email.inbox.trash');
        Route::delete('inbox/{message}', [App\Http\Controllers\EmailInboxController::class, 'destroy'])->name('email.inbox.destroy');
        Route::post('inbox/{message}/restore', [App\Http\Controllers\EmailInboxController::class, 'restore'])->name('email.inbox.restore');
        Route::post('inbox/bulk-action', [App\Http\Controllers\EmailInboxController::class, 'bulkAction'])->name('email.inbox.bulk-action');
        Route::get('attachments/{id}/download', [App\Http\Controllers\EmailInboxController::class, 'downloadAttachment'])->name('email.attachments.download');
    });

    // Performance Review System routes
    Route::prefix('performance')->group(function () {
        // Review Cycles
        Route::resource('review-cycles', App\Http\Controllers\ReviewCycleController::class)->middleware([
            'index' => 'permission:view-review-cycles',
            'show' => 'permission:view-review-cycles',
            'create' => 'permission:create-review-cycles',
            'store' => 'permission:create-review-cycles',
            'edit' => 'permission:edit-review-cycles',
            'update' => 'permission:edit-review-cycles',
            'destroy' => 'permission:delete-review-cycles',
        ]);
        Route::post('review-cycles/{reviewCycle}/activate', [App\Http\Controllers\ReviewCycleController::class, 'activate'])->name('review-cycles.activate')->middleware('permission:edit-review-cycles');
        Route::post('review-cycles/{reviewCycle}/complete', [App\Http\Controllers\ReviewCycleController::class, 'complete'])->name('review-cycles.complete')->middleware('permission:edit-review-cycles');

        // Performance Reviews
        Route::resource('reviews', App\Http\Controllers\PerformanceReviewController::class)->middleware([
            'index' => 'permission:view-reviews',
            'show' => 'permission:view-reviews',
            'create' => 'permission:conduct-reviews',
            'store' => 'permission:conduct-reviews',
            'edit' => 'permission:conduct-reviews',
            'update' => 'permission:conduct-reviews',
            'destroy' => 'permission:conduct-reviews',
        ]);
        Route::post('reviews/{review}/submit', [App\Http\Controllers\PerformanceReviewController::class, 'submit'])->name('reviews.submit')->middleware('permission:conduct-reviews');
        Route::post('reviews/{review}/approve', [App\Http\Controllers\PerformanceReviewController::class, 'approve'])->name('reviews.approve')->middleware('permission:approve-reviews');
        Route::get('reviews/{review}/pdf', [App\Http\Controllers\PerformanceReviewController::class, 'downloadPdf'])->name('reviews.pdf')->middleware('permission:view-reviews');

        // 360 Feedback
        Route::get('reviews/{review}/feedback', [App\Http\Controllers\PerformanceReviewController::class, 'feedbackForm'])->name('reviews.feedback.form')->middleware('permission:provide-feedback');
        Route::post('reviews/{review}/feedback', [App\Http\Controllers\PerformanceReviewController::class, 'submitFeedback'])->name('reviews.feedback.submit')->middleware('permission:provide-feedback');

        // Goals & OKRs
        Route::resource('goals', App\Http\Controllers\GoalController::class)->middleware([
            'index' => 'permission:view-goals',
            'show' => 'permission:view-goals',
            'create' => 'permission:manage-goals',
            'store' => 'permission:manage-goals',
            'edit' => 'permission:manage-goals',
            'update' => 'permission:manage-goals',
            'destroy' => 'permission:manage-goals',
        ]);
        Route::post('goals/{goal}/update-progress', [App\Http\Controllers\GoalController::class, 'updateProgress'])->name('goals.update-progress')->middleware('permission:manage-goals');
        Route::post('goals/{goal}/complete', [App\Http\Controllers\GoalController::class, 'complete'])->name('goals.complete')->middleware('permission:manage-goals');

        // Skills
        Route::resource('skills', App\Http\Controllers\SkillController::class)->middleware([
            'index' => 'permission:view-skills',
            'show' => 'permission:view-skills',
            'create' => 'permission:manage-skills',
            'store' => 'permission:manage-skills',
            'edit' => 'permission:manage-skills',
            'update' => 'permission:manage-skills',
            'destroy' => 'permission:manage-skills',
        ]);
        Route::post('skills/bulk-create', [App\Http\Controllers\SkillController::class, 'bulkCreate'])->name('skills.bulk-create')->middleware('permission:manage-skills');

        // Employee Skills Assessment
        Route::get('employees/{employee}/skills', [App\Http\Controllers\EmployeeController::class, 'skills'])->name('employees.skills')->middleware('permission:view-employees');
        Route::post('employees/{employee}/skills', [App\Http\Controllers\EmployeeController::class, 'assessSkill'])->name('employees.skills.assess')->middleware('permission:edit-employees');
        Route::put('employees/{employee}/skills/{employeeSkill}', [App\Http\Controllers\EmployeeController::class, 'updateSkillAssessment'])->name('employees.skills.update')->middleware('permission:edit-employees');
        Route::delete('employees/{employee}/skills/{employeeSkill}', [App\Http\Controllers\EmployeeController::class, 'removeSkill'])->name('employees.skills.remove')->middleware('permission:edit-employees');

        // AI Agent
        Route::get('ai-agent', [App\Http\Controllers\AIAgentController::class, 'index'])->name('ai-agent.index');
        Route::post('ai-agent/command', [App\Http\Controllers\AIAgentController::class, 'processCommand'])->name('ai-agent.command');
        Route::get('ai-agent/history', [App\Http\Controllers\AIAgentController::class, 'getConversationHistory'])->name('ai-agent.history');
        Route::delete('ai-agent/conversation', [App\Http\Controllers\AIAgentController::class, 'clearConversation'])->name('ai-agent.clear');
    });
});

// Client Portal Routes (Main Login at /login)
Route::middleware('guest:client')->group(function () {
    Route::get('/login', [App\Http\Controllers\Client\ClientAuthController::class, 'showLogin'])
        ->name('client.login');
    Route::post('/login', [App\Http\Controllers\Client\ClientAuthController::class, 'login'])
        ->name('client.login.post');
    Route::post('/login/send-otp', [App\Http\Controllers\Client\ClientAuthController::class, 'sendOtp'])
        ->name('client.login.send-otp');
});

Route::prefix('client')->name('client.')->group(function () {
    // Guest routes (not authenticated)
    Route::middleware('guest:client')->group(function () {

        // Team member invitation acceptance
        Route::get('/team/accept/{token}', [App\Http\Controllers\Client\ClientAuthController::class, 'acceptInvitation'])
            ->name('team.accept');
    });

    // Authenticated client routes
    Route::middleware(['auth:client', 'client.must.change.password'])->group(function () {
        Route::post('/logout', [App\Http\Controllers\Client\ClientAuthController::class, 'logout'])
            ->name('logout');

        Route::get('/password/change', [App\Http\Controllers\Client\ClientAuthController::class, 'showChangePassword'])
            ->name('password.change');
        Route::post('/password/change', [App\Http\Controllers\Client\ClientAuthController::class, 'changePassword'])
            ->name('password.change.post');

        Route::get('/dashboard', [App\Http\Controllers\Client\ClientDashboardController::class, 'index'])
            ->name('dashboard');

        // Projects - Full CRUD with limitations
        Route::resource('projects', App\Http\Controllers\Client\ClientProjectController::class);

        // Project Team Members
        Route::post('/projects/{project}/members', [App\Http\Controllers\Client\ClientProjectController::class, 'addMember'])
            ->name('projects.members.add');
        Route::put('/projects/{project}/members/{member}', [App\Http\Controllers\Client\ClientProjectController::class, 'updateMember'])
            ->name('projects.members.update');
        Route::delete('/projects/{project}/members/{member}', [App\Http\Controllers\Client\ClientProjectController::class, 'removeMember'])
            ->name('projects.members.remove');

        // Project Files
        Route::post('/projects/{project}/files', [App\Http\Controllers\Client\ClientProjectController::class, 'storeFile'])
            ->name('projects.files.store');
        Route::delete('/projects/{project}/files/{file}', [App\Http\Controllers\Client\ClientProjectController::class, 'destroyFile'])
            ->name('projects.files.destroy');

        // Project Task Details
        Route::get('/projects/{project}/tasks/{task}', [App\Http\Controllers\Client\ClientProjectController::class, 'showTask'])
            ->name('projects.tasks.show');
        Route::get('/projects/{project}/tasks/{task}/files', [App\Http\Controllers\Client\ClientProjectController::class, 'getTaskFiles'])
            ->name('projects.tasks.files');
        Route::post('/projects/{project}/tasks/{task}/files', [App\Http\Controllers\Client\ClientProjectController::class, 'uploadTaskFile'])
            ->name('projects.tasks.files.upload');
        Route::delete('/projects/{project}/tasks/{task}/files/{file}', [App\Http\Controllers\Client\ClientProjectController::class, 'deleteTaskFile'])
            ->name('projects.tasks.files.delete');
        Route::get('/projects/{project}/tasks/{task}/comments', [App\Http\Controllers\Client\ClientProjectController::class, 'getTaskComments'])
            ->name('projects.tasks.comments');
        Route::post('/projects/{project}/tasks/{task}/comments', [App\Http\Controllers\Client\ClientProjectController::class, 'storeTaskComment'])
            ->name('projects.tasks.comments.store');

        // Comment Replies
        Route::post('/projects/{project}/tasks/{task}/comments/{comment}/replies', [App\Http\Controllers\Client\ClientProjectController::class, 'storeCommentReply'])
            ->name('projects.tasks.comments.replies.store');

        // Comment Reactions
        Route::post('/projects/{project}/tasks/{task}/comments/{comment}/reactions', [App\Http\Controllers\Client\ClientProjectController::class, 'toggleCommentReaction'])
            ->name('projects.tasks.comments.reactions.toggle');

        // Mention Autocomplete
        Route::get('/projects/{project}/employees/mention', [App\Http\Controllers\Client\ClientProjectController::class, 'getEmployeesForMention'])
            ->name('projects.employees.mention');

        Route::get('/projects/{project}/tasks/{task}/reminders', [App\Http\Controllers\Client\ClientProjectController::class, 'getTaskReminders'])
            ->name('projects.tasks.reminders');
        Route::post('/tasks/{task}/checklist/{checklist}/toggle', [App\Http\Controllers\Client\ClientProjectController::class, 'toggleChecklist'])
            ->name('tasks.checklist.toggle');        // Team Members Management
        Route::get('/team', [App\Http\Controllers\Client\ClientTeamController::class, 'index'])
            ->name('team.index');
        Route::get('/team/create', [App\Http\Controllers\Client\ClientTeamController::class, 'create'])
            ->name('team.create');
        Route::post('/team', [App\Http\Controllers\Client\ClientTeamController::class, 'store'])
            ->name('team.store');
        Route::post('/team/{teamMember}/resend', [App\Http\Controllers\Client\ClientTeamController::class, 'resendInvitation'])
            ->name('team.resend');
        Route::delete('/team/{teamMember}', [App\Http\Controllers\Client\ClientTeamController::class, 'destroy'])
            ->name('team.destroy');

        // Invoices
        Route::get('/invoices', function () {
            return view('client.invoices.index');
        })->name('invoices.index');

        // Tickets/Support
        Route::get('/tickets', function () {
            return view('client.tickets.index');
        })->name('tickets.index');

        // Profile
        Route::get('/profile', function () {
            return view('client.profile');
        })->name('profile');
    });
});

// Public Job Board Routes (no authentication required)
Route::prefix('jobs')->name('jobs.')->group(function () {
    Route::get('/', [App\Http\Controllers\PublicJobController::class, 'index'])->name('index');
    Route::get('/{slug}', [App\Http\Controllers\PublicJobController::class, 'show'])->name('show');
    Route::post('/{slug}/apply', [App\Http\Controllers\PublicJobController::class, 'apply'])->name('apply');
    Route::get('/application/{applicationId}/success', [App\Http\Controllers\PublicJobController::class, 'success'])->name('success');
    Route::get('/test/{testId}', [App\Http\Controllers\PublicJobController::class, 'viewTest'])->name('test.view');
    Route::post('/test/{testId}/submit', [App\Http\Controllers\PublicJobController::class, 'submitTest'])->name('test.submit');
});

// Admin Job Management Routes (require admin authentication)
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Job Posts
    Route::resource('jobs', App\Http\Controllers\Admin\JobPostController::class);
    Route::post('/jobs/{job}/duplicate', [App\Http\Controllers\Admin\JobPostController::class, 'duplicate'])->name('jobs.duplicate');
    Route::get('/jobs/{job}/bulk-upload', [App\Http\Controllers\Admin\JobPostController::class, 'bulkUpload'])->name('jobs.bulk-upload');
    Route::post('/jobs/{job}/bulk-upload', [App\Http\Controllers\Admin\JobPostController::class, 'processBulkUpload'])->name('jobs.bulk-upload.process');

    // Job Applications
    Route::resource('applications', App\Http\Controllers\Admin\JobApplicationController::class)->only(['index', 'show', 'destroy']);
    Route::put('/applications/{application}/status', [App\Http\Controllers\Admin\JobApplicationController::class, 'updateStatus'])->name('applications.update-status');
    Route::post('/applications/{application}/ai-screening', [App\Http\Controllers\Admin\JobApplicationController::class, 'runAIScreening'])->name('applications.ai-screening');
    Route::post('/applications/{application}/retry-ai-screening', [App\Http\Controllers\Admin\JobApplicationController::class, 'retryAIScreening'])->name('applications.retry-ai-screening');
    Route::post('/applications/batch-ai-screening', [App\Http\Controllers\Admin\JobApplicationController::class, 'batchAIScreening'])->name('applications.batch-ai-screening');
    Route::post('/applications/{application}/talent-pool', [App\Http\Controllers\Admin\JobApplicationController::class, 'addToTalentPool'])->name('applications.talent-pool');
    Route::post('/applications/{application}/interview', [App\Http\Controllers\Admin\JobApplicationController::class, 'sendInterview'])->name('applications.send-interview');
    Route::post('/applications/{application}/test', [App\Http\Controllers\Admin\JobApplicationController::class, 'sendTest'])->name('applications.send-test');
    Route::get('/applications/{application}/resume/view', [App\Http\Controllers\Admin\JobApplicationController::class, 'viewResume'])->name('applications.view-resume');
    Route::get('/applications/{application}/resume/download', [App\Http\Controllers\Admin\JobApplicationController::class, 'downloadResume'])->name('applications.download-resume');

    // Application Tests
    Route::post('/applications/{application}/tests/generate', [App\Http\Controllers\Admin\ApplicationTestController::class, 'generateWithAI'])->name('applications.tests.generate');
    Route::post('/applications/{application}/tests', [App\Http\Controllers\Admin\ApplicationTestController::class, 'store'])->name('applications.tests.store');
    Route::put('/applications/{application}/tests/{test}', [App\Http\Controllers\Admin\ApplicationTestController::class, 'update'])->name('applications.tests.update');
    Route::post('/applications/{application}/tests/{test}/send', [App\Http\Controllers\Admin\ApplicationTestController::class, 'sendEmail'])->name('applications.tests.send');

    // Test Analytics
    Route::get('/analytics/tests', [App\Http\Controllers\Admin\TestAnalyticsController::class, 'index'])->name('analytics.tests');
    Route::get('/analytics/tests/export', [App\Http\Controllers\Admin\TestAnalyticsController::class, 'export'])->name('analytics.tests.export');

    // Talent Pool
    Route::get('/talent-pool', [App\Http\Controllers\Admin\TalentPoolController::class, 'index'])->name('talent-pool.index');
    Route::get('/talent-pool/{talentPool}', [App\Http\Controllers\Admin\TalentPoolController::class, 'show'])->name('talent-pool.show');
    Route::put('/talent-pool/{talentPool}', [App\Http\Controllers\Admin\TalentPoolController::class, 'update'])->name('talent-pool.update');
    Route::delete('/talent-pool/{talentPool}', [App\Http\Controllers\Admin\TalentPoolController::class, 'destroy'])->name('talent-pool.destroy');

    // Chatbot Management
    Route::get('/chatbot', [ChatbotController::class, 'index'])->name('chatbot.index');
    Route::get('/chatbot/guide', [ChatbotController::class, 'guide'])->name('chatbot.guide');
    Route::get('/chatbot/{conversation}', [ChatbotController::class, 'show'])->name('chatbot.show');
    Route::post('/chatbot/{conversation}/reply', [ChatbotController::class, 'sendReply'])->name('chatbot.send-reply');
    Route::post('/chatbot/{conversation}/assign', [ChatbotController::class, 'assign'])->name('chatbot.assign');
    Route::post('/chatbot/{conversation}/close', [ChatbotController::class, 'close'])->name('chatbot.close');
    Route::delete('/chatbot/{conversation}', [ChatbotController::class, 'destroy'])->name('chatbot.destroy');

    // Chatbot Widgets Management
    Route::get('/chatbot-widgets', [ChatbotController::class, 'widgetsIndex'])->name('chatbot.widgets.index');
    Route::get('/chatbot-widgets/create', [ChatbotController::class, 'widgetsCreate'])->name('chatbot.widgets.create');
    Route::post('/chatbot-widgets', [ChatbotController::class, 'widgetsStore'])->name('chatbot.widgets.store');
    Route::get('/chatbot-widgets/{widget}', [ChatbotController::class, 'widgetsShow'])->name('chatbot.widgets.show');
    Route::get('/chatbot-widgets/{widget}/edit', [ChatbotController::class, 'widgetsEdit'])->name('chatbot.widgets.edit');
    Route::put('/chatbot-widgets/{widget}', [ChatbotController::class, 'widgetsUpdate'])->name('chatbot.widgets.update');
    Route::post('/chatbot-widgets/{widget}/rotate-token', [ChatbotController::class, 'widgetsRotateToken'])->name('chatbot.widgets.rotate-token');
    Route::delete('/chatbot-widgets/{widget}', [ChatbotController::class, 'widgetsDestroy'])->name('chatbot.widgets.destroy');
});

// Jibble Integration Routes
Route::middleware(['auth', 'verified'])->prefix('jibble')->name('jibble.')->group(function () {
    Route::get('dashboard', [App\Http\Controllers\JibbleController::class, 'dashboard'])->name('dashboard');
    Route::get('sync-history', [App\Http\Controllers\JibbleController::class, 'syncHistory'])->name('sync-history');
    Route::get('time-entries', [App\Http\Controllers\JibbleController::class, 'timeEntries'])->name('time-entries');
    Route::get('leave-requests', [App\Http\Controllers\JibbleController::class, 'leaveRequests'])->name('leave-requests');
    
    Route::post('test-connection', [App\Http\Controllers\JibbleController::class, 'testConnection'])->name('test-connection');
    Route::post('sync-employees', [App\Http\Controllers\JibbleController::class, 'syncEmployees'])->name('sync-employees');
    Route::post('sync-time-entries', [App\Http\Controllers\JibbleController::class, 'syncTimeEntries'])->name('sync-time-entries');
    Route::post('sync-leave-requests', [App\Http\Controllers\JibbleController::class, 'syncLeaveRequests'])->name('sync-leave-requests');
});

require __DIR__.'/auth.php';
