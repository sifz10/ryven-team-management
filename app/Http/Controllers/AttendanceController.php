<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use App\Models\MonthlyAdjustment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::whereNull('discontinued_at')
            ->orderBy('first_name')
            ->get();

        $selectedEmployeeId = $request->get('employee_id');
        $year = $request->get('year', now()->year);
        $month = $request->get('month', now()->month);

        $selectedEmployee = null;
        $attendances = collect();
        $monthlySummary = null;
        $monthlyAdjustment = null;

        if ($selectedEmployeeId) {
            $selectedEmployee = Employee::findOrFail($selectedEmployeeId);
            
            // Get all attendances for the selected month
            $attendances = Attendance::where('employee_id', $selectedEmployeeId)
                ->whereYear('date', $year)
                ->whereMonth('date', $month)
                ->get()
                ->keyBy(function ($item) {
                    return $item->date->format('Y-m-d');
                });

            // Get monthly adjustment for this month
            $monthlyAdjustment = MonthlyAdjustment::where('employee_id', $selectedEmployeeId)
                ->where('year', $year)
                ->where('month', $month)
                ->first();

            // Calculate monthly summary
            $monthlySummary = $this->calculateMonthlySummary($selectedEmployee, $attendances, $year, $month, $monthlyAdjustment);
        }

        return view('attendance.index', compact(
            'employees',
            'selectedEmployee',
            'attendances',
            'year',
            'month',
            'monthlySummary',
            'monthlyAdjustment'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'status' => 'required|in:present,absent,half_day,leave,holiday',
            'hours_worked' => 'nullable|integer|min:0|max:24',
            'minutes_worked' => 'nullable|integer|min:0|max:59',
            'bonus' => 'nullable|numeric|min:0',
            'penalty' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['penalty'] = $validated['penalty'] ?? 0;
        $validated['hours_worked'] = $validated['hours_worked'] ?? null;
        $validated['minutes_worked'] = $validated['minutes_worked'] ?? null;

        // Calculate payment based on hours worked
        if ($validated['hours_worked'] !== null || $validated['minutes_worked'] !== null) {
            $employee = Employee::findOrFail($validated['employee_id']);
            $validated['calculated_payment'] = $this->calculatePaymentForHours(
                $employee,
                $validated['hours_worked'] ?? 0,
                $validated['minutes_worked'] ?? 0
            );
        }

        Attendance::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'date' => $validated['date'],
            ],
            $validated
        );

        return redirect()
            ->back()
            ->with('success', 'Attendance recorded successfully!');
    }

    public function update(Request $request, Attendance $attendance)
    {
        $validated = $request->validate([
            'status' => 'required|in:present,absent,half_day,leave,holiday',
            'hours_worked' => 'nullable|integer|min:0|max:24',
            'minutes_worked' => 'nullable|integer|min:0|max:59',
            'bonus' => 'nullable|numeric|min:0',
            'penalty' => 'nullable|numeric|min:0',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['penalty'] = $validated['penalty'] ?? 0;
        $validated['hours_worked'] = $validated['hours_worked'] ?? null;
        $validated['minutes_worked'] = $validated['minutes_worked'] ?? null;

        // Calculate payment based on hours worked
        if ($validated['hours_worked'] !== null || $validated['minutes_worked'] !== null) {
            $validated['calculated_payment'] = $this->calculatePaymentForHours(
                $attendance->employee,
                $validated['hours_worked'] ?? 0,
                $validated['minutes_worked'] ?? 0
            );
        } else {
            $validated['calculated_payment'] = null;
        }

        $attendance->update($validated);

        return redirect()
            ->back()
            ->with('success', 'Attendance updated successfully!');
    }

    public function destroy(Attendance $attendance)
    {
        $attendance->delete();

        return redirect()
            ->back()
            ->with('success', 'Attendance deleted successfully!');
    }

    public function bulkPopulate(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer|min:2020|max:2100',
            'month' => 'required|integer|min:1|max:12',
            'total_hours' => 'required|numeric|min:0',
            'working_days' => 'required|integer|min:1|max:31',
            'exclude_saturdays' => 'nullable|boolean',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        $year = $validated['year'];
        $month = $validated['month'];
        $totalHours = (float)$validated['total_hours'];
        $workingDays = (int)$validated['working_days'];
        $excludeSaturdays = $validated['exclude_saturdays'] ?? true;

        // Calculate average hours per day
        $avgHoursPerDay = $totalHours / $workingDays;
        $avgHours = floor($avgHoursPerDay);
        $avgMinutes = round(($avgHoursPerDay - $avgHours) * 60);

        $date = Carbon::create($year, $month, 1);
        $daysInMonth = $date->daysInMonth;
        $populatedDays = 0;

        // Populate attendance for each day
        for ($day = 1; $day <= $daysInMonth; $day++) {
            $currentDate = Carbon::create($year, $month, $day);
            
            // Skip Saturdays if requested (dayOfWeek: 0=Sunday, 6=Saturday)
            if ($excludeSaturdays && $currentDate->dayOfWeek === 6) {
                continue;
            }

            // Stop if we've reached the specified working days
            if ($populatedDays >= $workingDays) {
                break;
            }

            $calculatedPayment = $this->calculatePaymentForHours($employee, (int)$avgHours, (int)$avgMinutes);

            Attendance::updateOrCreate(
                [
                    'employee_id' => $employee->id,
                    'date' => $currentDate->format('Y-m-d'),
                ],
                [
                    'status' => 'present',
                    'hours_worked' => (int)$avgHours,
                    'minutes_worked' => (int)$avgMinutes,
                    'calculated_payment' => $calculatedPayment,
                    'bonus' => 0,
                    'penalty' => 0,
                    'description' => 'Auto-populated from monthly hours',
                ]
            );

            $populatedDays++;
        }

        return redirect()
            ->back()
            ->with('success', "Successfully populated {$populatedDays} days with average of {$avgHours}h {$avgMinutes}m per day!");
    }

    public function saveMonthlyAdjustment(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'bonus' => 'nullable|numeric|min:0',
            'penalty' => 'nullable|numeric|min:0',
            'bonus_description' => 'nullable|string|max:500',
            'penalty_description' => 'nullable|string|max:500',
        ]);

        $validated['bonus'] = $validated['bonus'] ?? 0;
        $validated['penalty'] = $validated['penalty'] ?? 0;

        MonthlyAdjustment::updateOrCreate(
            [
                'employee_id' => $validated['employee_id'],
                'year' => $validated['year'],
                'month' => $validated['month'],
            ],
            [
                'bonus' => $validated['bonus'],
                'penalty' => $validated['penalty'],
                'bonus_description' => $validated['bonus_description'],
                'penalty_description' => $validated['penalty_description'],
            ]
        );

        return redirect()
            ->back()
            ->with('success', 'Monthly adjustment saved successfully!');
    }

    /**
     * Calculate payment for hours worked
     * Based on: 8 hours/day × 6 days/week = 48 hours/week
     * 52 weeks/year ÷ 12 months = 4.33 weeks/month
     * 48 × 4.33 = 208 hours/month (or 26 working days × 8 hours)
     */
    private function calculatePaymentForHours(Employee $employee, int $hours, int $minutes): float
    {
        $baseSalary = (float)$employee->salary;
        
        // 8 hours per day × 6 days per week × 52 weeks per year ÷ 12 months = 208 hours per month
        $standardHoursPerMonth = 208; // (6 days/week × 52 weeks/year ÷ 12 months) × 8 hours/day
        $hourlyRate = $baseSalary / $standardHoursPerMonth;
        
        // Convert to total hours (e.g., 7 hours 39 minutes = 7.65 hours)
        $totalHours = $hours + ($minutes / 60);
        
        return $hourlyRate * $totalHours;
    }

    private function calculateMonthlySummary(Employee $employee, $attendances, $year, $month, $monthlyAdjustment = null)
    {
        $baseSalary = (float)$employee->salary;
        $daysInMonth = Carbon::create($year, $month)->daysInMonth;
        $dailyRate = $baseSalary / $daysInMonth;
        
        // Calculate hourly rate: 8 hours/day × 6 days/week × 52 weeks/year ÷ 12 months = 208 hours/month
        $standardHoursPerMonth = 208; // 26 working days per month × 8 hours per day
        $hourlyRate = $baseSalary / $standardHoursPerMonth;

        $presentDays = 0;
        $absentDays = 0;
        $halfDays = 0;
        $leaveDays = 0;
        $holidays = 0;
        $totalBonus = 0;
        $totalPenalty = 0;
        $totalHoursWorked = 0;
        $totalCalculatedPayment = 0;

        foreach ($attendances as $attendance) {
            switch ($attendance->status) {
                case 'present':
                    $presentDays++;
                    break;
                case 'absent':
                    $absentDays++;
                    break;
                case 'half_day':
                    $halfDays++;
                    break;
                case 'leave':
                    $leaveDays++;
                    break;
                case 'holiday':
                    $holidays++;
                    break;
            }
            
            // Add up hours worked
            if ($attendance->hours_worked !== null || $attendance->minutes_worked !== null) {
                $hours = (float)($attendance->hours_worked ?? 0);
                $minutes = (float)($attendance->minutes_worked ?? 0);
                $totalHoursWorked += $hours + ($minutes / 60);
                $totalCalculatedPayment += (float)($attendance->calculated_payment ?? 0);
            }
            
            $totalBonus += (float)$attendance->bonus;
            $totalPenalty += (float)$attendance->penalty;
        }

        // Calculate final salary
        // If hours are tracked, use calculated payments; otherwise use day-based calculation
        if ($totalHoursWorked > 0) {
            $calculatedSalary = $totalCalculatedPayment + $totalBonus - $totalPenalty;
        } else {
            // Fallback to day-based calculation
            $workingDays = $presentDays + ($halfDays * 0.5);
            $calculatedSalary = ($workingDays * $dailyRate) + $totalBonus - $totalPenalty;
        }

        // Add monthly adjustments (bonus/penalty applied to final balance before tax)
        $monthlyBonus = 0;
        $monthlyPenalty = 0;
        if ($monthlyAdjustment) {
            $monthlyBonus = (float)$monthlyAdjustment->bonus;
            $monthlyPenalty = (float)$monthlyAdjustment->penalty;
            $calculatedSalary += $monthlyBonus - $monthlyPenalty;
        }

        // Ensure salary is non-negative before tax
        $salaryBeforeTax = max(0, $calculatedSalary);
        
        // Calculate 7.5% tax
        $taxAmount = $salaryBeforeTax * 0.075;
        $salaryAfterTax = $salaryBeforeTax - $taxAmount;

        return [
            'base_salary' => $baseSalary,
            'daily_rate' => $dailyRate,
            'hourly_rate' => $hourlyRate,
            'days_in_month' => $daysInMonth,
            'present_days' => $presentDays,
            'absent_days' => $absentDays,
            'half_days' => $halfDays,
            'leave_days' => $leaveDays,
            'holidays' => $holidays,
            'total_hours_worked' => $totalHoursWorked,
            'total_calculated_payment' => $totalCalculatedPayment,
            'total_bonus' => $totalBonus,
            'total_penalty' => $totalPenalty,
            'monthly_bonus' => $monthlyBonus,
            'monthly_penalty' => $monthlyPenalty,
            'salary_before_tax' => $salaryBeforeTax,
            'tax_amount' => $taxAmount,
            'tax_percentage' => 7.5,
            'calculated_salary' => $salaryAfterTax, // Final salary after tax
            'currency' => $employee->currency ?? 'USD',
        ];
    }
}

