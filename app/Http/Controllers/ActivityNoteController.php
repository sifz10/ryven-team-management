<?php

namespace App\Http\Controllers;

use App\Models\ActivityNote;
use App\Models\Employee;
use App\Models\EmployeePayment;
use Illuminate\Http\Request;

class ActivityNoteController extends Controller
{
    public function store(Request $request, Employee $employee, EmployeePayment $payment)
    {
        $validated = $request->validate([
            'note' => ['required', 'string', 'max:2000'],
        ]);

        $validated['employee_payment_id'] = $payment->id;
        $validated['user_id'] = auth()->id();

        $note = ActivityNote::create($validated);
        $note->load('user');

        // Return JSON for AJAX requests
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'note' => $note,
                'html' => view('employees.partials.activity-note', [
                    'note' => $note,
                    'employee' => $employee,
                    'payment' => $payment
                ])->render()
            ]);
        }

        return redirect()
            ->route('employees.show', ['employee' => $employee, 'tab' => 'timeline'])
            ->with('status', 'Note added successfully');
    }

    public function destroy(Employee $employee, EmployeePayment $payment, ActivityNote $note)
    {
        // Verify the note belongs to this payment
        abort_unless($note->employee_payment_id === $payment->id, 404);

        $note->delete();

        return redirect()
            ->route('employees.show', ['employee' => $employee, 'tab' => 'timeline'])
            ->with('status', 'Note deleted successfully');
    }
}
