<?php

namespace App\Http\Controllers;

use App\Mail\SalaryAdjustmentNotification;
use App\Models\Employee;
use App\Models\SalaryReview;
use App\Models\SalaryAdjustmentHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class SalaryReviewController extends Controller
{
    /**
     * Show all salary reviews for dashboard
     */
    public function index()
    {
        $reviews = SalaryReview::with('employee', 'reviewer')
            ->orderBy('review_date')
            ->paginate(15);

        $pendingCount = SalaryReview::where('status', 'pending')->count();
        $completedCount = SalaryReview::where('status', 'completed')->count();

        return view('salary-reviews.index', compact('reviews', 'pendingCount', 'completedCount'));
    }

    /**
     * Show a specific salary review
     */
    public function show(SalaryReview $salaryReview)
    {
        $salaryReview->load('employee', 'reviewer', 'adjustmentHistory.adjustedBy');

        // Get salary history for this employee
        $salaryHistory = SalaryAdjustmentHistory::where('employee_id', $salaryReview->employee_id)
            ->orderByDesc('created_at')
            ->get();

        // Get employee's recent activities (payments, achievements, warnings)
        $activities = $salaryReview->employee->payments()
            ->where('paid_at', '>=', $salaryReview->employee->hired_at)
            ->orderByDesc('paid_at')
            ->limit(20)
            ->get();

        return view('salary-reviews.show', compact('salaryReview', 'salaryHistory', 'activities'));
    }

    /**
     * Show edit form for salary review
     */
    public function edit(SalaryReview $salaryReview)
    {
        abort_if($salaryReview->status === 'completed', 403, 'Cannot edit completed salary review');
        
        $salaryReview->load('employee');

        return view('salary-reviews.edit', compact('salaryReview'));
    }

    /**
     * Update and complete the salary review
     */
    public function update(Request $request, SalaryReview $salaryReview)
    {
        abort_if($salaryReview->status === 'completed', 403, 'Cannot edit completed salary review');

        $validated = $request->validate([
            'adjusted_salary' => ['required', 'numeric', 'min:0'],
            'adjustment_reason' => ['required', 'string', 'max:1000'],
            'performance_notes' => ['nullable', 'string', 'max:2000'],
            'performance_rating' => ['nullable', 'in:poor,below_average,average,good,excellent'],
        ]);

        $salaryReview->completeReview(
            newSalary: $validated['adjusted_salary'],
            reason: $validated['adjustment_reason'],
            reviewedBy: Auth::id()
        );

        $salaryReview->update([
            'performance_notes' => $validated['performance_notes'] ?? null,
            'performance_rating' => $validated['performance_rating'] ?? null,
        ]);

        return redirect()->route('salary-reviews.show', $salaryReview)
            ->with('success', 'Salary review completed successfully!');
    }

    /**
     * Show employee's salary adjustment history
     */
    public function employeeSalaryHistory(Employee $employee)
    {
        $adjustmentHistory = SalaryAdjustmentHistory::where('employee_id', $employee->id)
            ->with('adjustedBy')
            ->orderByDesc('created_at')
            ->paginate(15);

        $reviews = SalaryReview::where('employee_id', $employee->id)
            ->orderByDesc('review_date')
            ->get();

        return view('salary-reviews.employee-history', compact('employee', 'adjustmentHistory', 'reviews'));
    }

    /**
     * Manual salary adjustment (outside of reviews)
     */
    public function adjustSalary(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'new_salary' => ['required', 'numeric', 'min:0'],
            'reason' => ['required', 'string', 'max:1000'],
            'type' => ['required', 'in:promotion,demotion,adjustment,manual,bonus'],
        ]);

        $oldSalary = $employee->salary;
        $newSalary = $validated['new_salary'];

        $adjustment = SalaryAdjustmentHistory::create([
            'employee_id' => $employee->id,
            'old_salary' => $oldSalary,
            'new_salary' => $newSalary,
            'adjustment_amount' => $newSalary - $oldSalary,
            'type' => $validated['type'],
            'reason' => $validated['reason'],
            'adjusted_by' => Auth::id(),
            'currency' => $employee->currency ?? 'USD',
        ]);

        $employee->update(['salary' => $newSalary]);

        // Send email notification
        Mail::to('kazi.sifat1999@gmail.com')->send(new SalaryAdjustmentNotification($employee, $adjustment));

        // Return JSON for AJAX requests, redirect for form submissions
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Salary adjusted successfully!',
                'old_salary' => $oldSalary,
                'new_salary' => $newSalary,
                'adjustment_amount' => $newSalary - $oldSalary,
                'type' => $validated['type'],
            ]);
        }

        return back()->with('success', 'Salary adjusted successfully!');
    }
}
