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
use App\Http\Controllers\UatProjectController;
use App\Http\Controllers\UatPublicController;
use App\Http\Controllers\PersonalNoteController;
use App\Http\Controllers\ReviewCycleController;
use App\Http\Controllers\PerformanceReviewController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public checklist routes (no authentication required)
Route::get('/checklist/{token}', [ChecklistController::class, 'publicView'])->name('checklist.public.view');
Route::get('/checklist/{token}/item/{item}/toggle', [ChecklistController::class, 'publicToggleItem'])->name('checklist.public.toggle');
Route::post('/checklist/{token}/work', [ChecklistController::class, 'storeWorkSubmission'])->name('checklist.public.work.store');
Route::delete('/checklist/{token}/work/{submission}', [ChecklistController::class, 'deleteWorkSubmission'])->name('checklist.public.work.delete');

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

// GitHub Activities API (for AJAX load more)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/employees/{employee}/github/activities', [GitHubWebhookController::class, 'loadActivities'])->name('github.activities.load');
    Route::get('/employees/{employee}/github/check-new', [GitHubWebhookController::class, 'checkNew'])->name('github.activities.checkNew');

    // GitHub Logs Page
    Route::get('/github-logs', [GitHubWebhookController::class, 'logs'])->name('github.logs');

    // GitHub Pull Request routes
    Route::get('/github/pr/{log}', [GitHubPullRequestController::class, 'show'])->name('github.pr.show');
    Route::get('/github/pr/{log}/details', [GitHubPullRequestController::class, 'details'])->name('github.pr.details');
    Route::post('/github/pr/{log}/comment', [GitHubPullRequestController::class, 'comment'])->name('github.pr.comment');
    Route::post('/github/pr/{log}/review', [GitHubPullRequestController::class, 'review'])->name('github.pr.review');
    Route::post('/github/pr/{log}/assign', [GitHubPullRequestController::class, 'assign'])->name('github.pr.assign');
    Route::delete('/github/pr/{log}/assign/reviewer/{username}', [GitHubPullRequestController::class, 'removeReviewer'])->name('github.pr.removeReviewer');
    Route::delete('/github/pr/{log}/assign/assignee/{username}', [GitHubPullRequestController::class, 'removeAssignee'])->name('github.pr.removeAssignee');
    Route::post('/github/pr/{log}/labels', [GitHubPullRequestController::class, 'addLabel'])->name('github.pr.addLabel');
    Route::delete('/github/pr/{log}/labels/{label}', [GitHubPullRequestController::class, 'removeLabel'])->name('github.pr.removeLabel');
    Route::post('/github/pr/{log}/merge', [GitHubPullRequestController::class, 'merge'])->name('github.pr.merge');
    Route::post('/github/pr/{log}/close', [GitHubPullRequestController::class, 'close'])->name('github.pr.close');
    Route::post('/github/pr/{log}/ai-review', [GitHubPullRequestController::class, 'generateAIReview'])->name('github.pr.aiReview');

    // Project Management routes
    Route::resource('projects', ProjectController::class);
    Route::get('/projects/{project}/today-work', [ProjectController::class, 'todayWork'])->name('projects.today-work');
    Route::put('/projects/{project}/work/{submission}', [ProjectController::class, 'updateWorkSubmission'])->name('projects.work.update');
    Route::delete('/projects/{project}/work/{submission}', [ProjectController::class, 'deleteWorkSubmission'])->name('projects.work.delete');
    Route::post('/projects/{project}/send-report', [ProjectController::class, 'sendReport'])->name('projects.send-report');
    Route::get('/projects-today-summary', [ProjectController::class, 'todaySummary'])->name('projects.today-summary');
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

    Route::resource('employees', EmployeeController::class);
    Route::get('employees-deleted', [EmployeeController::class, 'deleted'])->name('employees.deleted');
    Route::post('employees/{id}/restore', [EmployeeController::class, 'restore'])->name('employees.restore');
    Route::delete('employees/{id}/force-delete', [EmployeeController::class, 'forceDelete'])->name('employees.force-delete');
    Route::post('employees/{employee}/discontinue', [EmployeeController::class, 'discontinue'])->name('employees.discontinue');
    Route::post('employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])->name('employees.reactivate');

    Route::post('employees/{employee}/payments', [EmployeePaymentController::class, 'store'])->name('employees.payments.store');
    Route::put('employees/{employee}/payments/{payment}', [EmployeePaymentController::class, 'update'])->name('employees.payments.update');
    Route::delete('employees/{employee}/payments/{payment}', [EmployeePaymentController::class, 'destroy'])->name('employees.payments.destroy');

    Route::post('employees/{employee}/payments/{payment}/notes', [App\Http\Controllers\ActivityNoteController::class, 'store'])->name('employees.payments.notes.store');
    Route::delete('employees/{employee}/payments/{payment}/notes/{note}', [App\Http\Controllers\ActivityNoteController::class, 'destroy'])->name('employees.payments.notes.destroy');

    Route::post('employees/{employee}/bank-accounts', [EmployeeBankAccountController::class, 'store'])->name('employees.bank-accounts.store');
    Route::put('employees/{employee}/bank-accounts/{account}', [EmployeeBankAccountController::class, 'update'])->name('employees.bank-accounts.update');
    Route::delete('employees/{employee}/bank-accounts/{account}', [EmployeeBankAccountController::class, 'destroy'])->name('employees.bank-accounts.destroy');

    Route::post('employees/{employee}/accesses', [EmployeeAccessController::class, 'store'])->name('employees.accesses.store');
    Route::put('employees/{employee}/accesses/{access}', [EmployeeAccessController::class, 'update'])->name('employees.accesses.update');
    Route::delete('employees/{employee}/accesses/{access}', [EmployeeAccessController::class, 'destroy'])->name('employees.accesses.destroy');

    // Employment Contract routes
    Route::get('contracts', [EmploymentContractController::class, 'index'])->name('contracts.index');
    Route::get('employees/{employee}/contracts/create', [EmploymentContractController::class, 'create'])->name('contracts.create');
    Route::post('employees/{employee}/contracts', [EmploymentContractController::class, 'store'])->name('contracts.store');
    Route::get('contracts/{contract}', [EmploymentContractController::class, 'show'])->name('contracts.show');
    Route::get('contracts/{contract}/edit', [EmploymentContractController::class, 'edit'])->name('contracts.edit');
    Route::put('contracts/{contract}', [EmploymentContractController::class, 'update'])->name('contracts.update');
    Route::delete('contracts/{contract}', [EmploymentContractController::class, 'destroy'])->name('contracts.destroy');
    Route::get('contracts/{contract}/pdf', [EmploymentContractController::class, 'downloadPdf'])->name('contracts.pdf');

    // Attendance routes
    Route::get('attendance', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::post('attendance', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::put('attendance/{attendance}', [AttendanceController::class, 'update'])->name('attendance.update');
    Route::delete('attendance/{attendance}', [AttendanceController::class, 'destroy'])->name('attendance.destroy');
    Route::post('attendance/bulk-populate', [AttendanceController::class, 'bulkPopulate'])->name('attendance.bulk-populate');
    Route::post('attendance/monthly-adjustment', [AttendanceController::class, 'saveMonthlyAdjustment'])->name('attendance.monthly-adjustment');

    // Checklist routes
    Route::post('employees/{employee}/checklists/templates', [ChecklistController::class, 'storeTemplate'])->name('employees.checklists.templates.store');
    Route::put('employees/{employee}/checklists/templates/{template}', [ChecklistController::class, 'updateTemplate'])->name('employees.checklists.templates.update');
    Route::delete('employees/{employee}/checklists/templates/{template}', [ChecklistController::class, 'destroyTemplate'])->name('employees.checklists.templates.destroy');
    Route::post('employees/{employee}/checklists/items/{item}/toggle', [ChecklistController::class, 'toggleItem'])->name('employees.checklists.items.toggle');
    Route::post('employees/{employee}/checklists/generate-today', [ChecklistController::class, 'generateTodayChecklists'])->name('employees.checklists.generate-today');
    Route::post('employees/{employee}/checklists/{checklist}/send-email', [ChecklistController::class, 'sendChecklistEmail'])->name('employees.checklists.send-email');

    // Invoice routes
    Route::resource('invoices', InvoiceController::class);
    Route::get('invoices/{invoice}/pdf', [InvoiceController::class, 'downloadPdf'])->name('invoices.pdf');
    Route::get('invoices/{invoice}/preview', [InvoiceController::class, 'previewPdf'])->name('invoices.preview');
    Route::post('invoices/{invoice}/send-email', [InvoiceController::class, 'sendEmail'])->name('invoices.send-email');

    // UAT routes
    Route::resource('uat', UatProjectController::class)->parameters(['uat' => 'project']);
    Route::post('uat/{project}/test-cases', [UatProjectController::class, 'storeTestCase'])->name('uat.test-cases.store');
    Route::get('uat/{project}/test-cases/{testCase}/edit', [UatProjectController::class, 'editTestCase'])->name('uat.test-cases.edit');
    Route::put('uat/{project}/test-cases/{testCase}', [UatProjectController::class, 'updateTestCase'])->name('uat.test-cases.update');
    Route::delete('uat/{project}/test-cases/{testCase}', [UatProjectController::class, 'destroyTestCase'])->name('uat.test-cases.destroy');
    Route::post('uat/{project}/users', [UatProjectController::class, 'addUser'])->name('uat.users.add');
    Route::delete('uat/{project}/users/{user}', [UatProjectController::class, 'removeUser'])->name('uat.users.remove');

    // Personal Notes routes
    Route::get('notes/emails/search', [PersonalNoteController::class, 'searchEmails'])->name('notes.emails.search');
    Route::resource('notes', PersonalNoteController::class);

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
        Route::resource('review-cycles', App\Http\Controllers\ReviewCycleController::class);
        Route::post('review-cycles/{reviewCycle}/activate', [App\Http\Controllers\ReviewCycleController::class, 'activate'])->name('review-cycles.activate');
        Route::post('review-cycles/{reviewCycle}/complete', [App\Http\Controllers\ReviewCycleController::class, 'complete'])->name('review-cycles.complete');

        // Performance Reviews
        Route::resource('reviews', App\Http\Controllers\PerformanceReviewController::class);
        Route::post('reviews/{review}/submit', [App\Http\Controllers\PerformanceReviewController::class, 'submit'])->name('reviews.submit');
        Route::post('reviews/{review}/approve', [App\Http\Controllers\PerformanceReviewController::class, 'approve'])->name('reviews.approve');
        Route::get('reviews/{review}/pdf', [App\Http\Controllers\PerformanceReviewController::class, 'downloadPdf'])->name('reviews.pdf');

        // 360 Feedback
        Route::get('reviews/{review}/feedback', [App\Http\Controllers\PerformanceReviewController::class, 'feedbackForm'])->name('reviews.feedback.form');
        Route::post('reviews/{review}/feedback', [App\Http\Controllers\PerformanceReviewController::class, 'submitFeedback'])->name('reviews.feedback.submit');

        // Goals & OKRs
        Route::resource('goals', App\Http\Controllers\GoalController::class);
        Route::post('goals/{goal}/update-progress', [App\Http\Controllers\GoalController::class, 'updateProgress'])->name('goals.update-progress');
        Route::post('goals/{goal}/complete', [App\Http\Controllers\GoalController::class, 'complete'])->name('goals.complete');

        // Skills
        Route::resource('skills', App\Http\Controllers\SkillController::class);
        Route::post('skills/bulk-create', [App\Http\Controllers\SkillController::class, 'bulkCreate'])->name('skills.bulk-create');

        // Employee Skills Assessment
        Route::get('employees/{employee}/skills', [App\Http\Controllers\EmployeeController::class, 'skills'])->name('employees.skills');
        Route::post('employees/{employee}/skills', [App\Http\Controllers\EmployeeController::class, 'assessSkill'])->name('employees.skills.assess');
        Route::put('employees/{employee}/skills/{employeeSkill}', [App\Http\Controllers\EmployeeController::class, 'updateSkillAssessment'])->name('employees.skills.update');
        Route::delete('employees/{employee}/skills/{employeeSkill}', [App\Http\Controllers\EmployeeController::class, 'removeSkill'])->name('employees.skills.remove');

        // AI Agent
        Route::get('ai-agent', [App\Http\Controllers\AIAgentController::class, 'index'])->name('ai-agent.index');
        Route::post('ai-agent/command', [App\Http\Controllers\AIAgentController::class, 'processCommand'])->name('ai-agent.command');
        Route::get('ai-agent/history', [App\Http\Controllers\AIAgentController::class, 'getConversationHistory'])->name('ai-agent.history');
        Route::delete('ai-agent/conversation', [App\Http\Controllers\AIAgentController::class, 'clearConversation'])->name('ai-agent.clear');
    });
});

require __DIR__.'/auth.php';
