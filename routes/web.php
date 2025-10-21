<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\EmployeePaymentController;
use App\Http\Controllers\EmployeeBankAccountController;
use App\Http\Controllers\EmployeeAccessController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
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
});

require __DIR__.'/auth.php';
