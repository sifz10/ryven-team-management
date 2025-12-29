<?php

namespace App\Http\Controllers;

use App\Mail\ActivityCreated;
use App\Models\Employee;
use App\Models\EmployeePayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmployeePaymentController extends Controller
{
	public function store(Request $request, Employee $employee)
	{
		$validated = $request->validate([
			'activity_type' => ['required', 'string', 'in:payment,achievement,warning,note'],
			'paid_at' => ['required', 'date'],
			'amount' => ['nullable', 'numeric', 'min:0'],
			'currency' => ['nullable', 'string', 'size:3'],
			'note' => ['nullable', 'string', 'max:2000'],
		]);

		$validated['employee_id'] = $employee->id;
		$validated['currency'] = $validated['currency'] ?? ($employee->currency ?? 'USD');

		$activity = EmployeePayment::create($validated);
		$activity->refresh(); // Refresh to ensure all casts are applied

		// Send email notification for warnings and achievements
		if (in_array($activity->activity_type, ['warning', 'achievement']) && $employee->email) {
			try {
				Mail::to($employee->email)->send(new ActivityCreated($employee, $activity));
			} catch (\Exception $e) {
				// Log the error but don't fail the request
				logger()->error('Failed to send activity notification email: ' . $e->getMessage());
			}
		}

		return redirect()->route('employees.show', ['employee' => $employee, 'tab' => 'timeline'])->with('status', 'Activity added successfully');
	}

    public function update(Request $request, Employee $employee, EmployeePayment $payment)
    {
        abort_unless($payment->employee_id === $employee->id, 404);

        $validated = $request->validate([
            'activity_type' => ['required', 'string', 'in:payment,achievement,warning,note'],
            'paid_at' => ['required', 'date'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'note' => ['nullable', 'string', 'max:2000'],
        ]);

        $validated['currency'] = $validated['currency'] ?? ($employee->currency ?? 'USD');

        $payment->update($validated);

        return redirect()->route('employees.show', ['employee' => $employee, 'tab' => 'timeline'])->with('status', 'Activity updated successfully');
    }
	public function destroy(Employee $employee, EmployeePayment $payment)
	{
		abort_unless($payment->employee_id === $employee->id, 404);
		$payment->delete();
		return redirect()->route('employees.show', ['employee' => $employee, 'tab' => 'timeline'])->with('status', 'Activity removed successfully');
	}
}


