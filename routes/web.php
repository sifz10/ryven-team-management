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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Public checklist routes (no authentication required)
Route::get('/checklist/{token}', [ChecklistController::class, 'publicView'])->name('checklist.public.view');
Route::get('/checklist/{token}/item/{item}/toggle', [ChecklistController::class, 'publicToggleItem'])->name('checklist.public.toggle');

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
});

// SOP page (authenticated)
Route::get('/sop', function () {
    return view('sop');
})->middleware(['auth'])->name('sop');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/artisan/{command}', function (Request $request) {
        Artisan::call($request->command);
        return 'Executed artisan command boss!';
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
});

require __DIR__.'/auth.php';
