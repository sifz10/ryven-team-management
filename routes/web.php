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
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Public checklist routes (no authentication required)
Route::get('/checklist/{token}', [ChecklistController::class, 'publicView'])->name('checklist.public.view');
Route::get('/checklist/{token}/item/{item}/toggle', [ChecklistController::class, 'publicToggleItem'])->name('checklist.public.toggle');

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

    Route::resource('employees', EmployeeController::class);
    Route::post('employees/{employee}/discontinue', [EmployeeController::class, 'discontinue'])->name('employees.discontinue');
    Route::post('employees/{employee}/reactivate', [EmployeeController::class, 'reactivate'])->name('employees.reactivate');

    Route::post('employees/{employee}/payments', [EmployeePaymentController::class, 'store'])->name('employees.payments.store');
    Route::put('employees/{employee}/payments/{payment}', [EmployeePaymentController::class, 'update'])->name('employees.payments.update');
    Route::delete('employees/{employee}/payments/{payment}', [EmployeePaymentController::class, 'destroy'])->name('employees.payments.destroy');

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
