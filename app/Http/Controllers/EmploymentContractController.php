<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmploymentContract;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class EmploymentContractController extends Controller
{
    public function index()
    {
        $contracts = EmploymentContract::with('employee')->latest()->paginate(15);
        return view('contracts.index', compact('contracts'));
    }

    public function create(Employee $employee)
    {
        return view('contracts.create', compact('employee'));
    }

    public function store(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'contract_type' => 'required|in:permanent,fixed_term,part_time',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'job_title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'job_description' => 'nullable|string',
            'salary' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'payment_frequency' => 'required|in:monthly,bi-weekly,weekly',
            'working_hours_per_week' => 'required|integer|min:1|max:168',
            'work_location' => 'nullable|string|max:255',
            'work_schedule' => 'nullable|string|max:255',
            'probation_period_days' => 'required|integer|min:0',
            'notice_period_days' => 'required|integer|min:0',
            'benefits' => 'nullable|string',
            'annual_leave_days' => 'required|integer|min:0',
            'sick_leave_days' => 'required|integer|min:0',
            'responsibilities' => 'nullable|string',
            'additional_terms' => 'nullable|string',
        ]);

        $validated['employee_id'] = $employee->id;
        $validated['status'] = 'draft';

        $contract = EmploymentContract::create($validated);

        return redirect()->route('employees.show', $employee)
            ->with('status', 'Employment contract created successfully!');
    }

    public function show(EmploymentContract $contract)
    {
        $contract->load('employee');
        return view('contracts.show', compact('contract'));
    }

    public function edit(EmploymentContract $contract)
    {
        $contract->load('employee');
        return view('contracts.edit', compact('contract'));
    }

    public function update(Request $request, EmploymentContract $contract)
    {
        $validated = $request->validate([
            'contract_type' => 'required|in:permanent,fixed_term,part_time',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after:start_date',
            'job_title' => 'required|string|max:255',
            'department' => 'nullable|string|max:255',
            'job_description' => 'nullable|string',
            'salary' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'payment_frequency' => 'required|in:monthly,bi-weekly,weekly',
            'working_hours_per_week' => 'required|integer|min:1|max:168',
            'work_location' => 'nullable|string|max:255',
            'work_schedule' => 'nullable|string|max:255',
            'probation_period_days' => 'required|integer|min:0',
            'notice_period_days' => 'required|integer|min:0',
            'benefits' => 'nullable|string',
            'annual_leave_days' => 'required|integer|min:0',
            'sick_leave_days' => 'required|integer|min:0',
            'responsibilities' => 'nullable|string',
            'additional_terms' => 'nullable|string',
            'status' => 'required|in:draft,active,terminated,expired',
        ]);

        $contract->update($validated);

        return redirect()->route('contracts.show', $contract)
            ->with('status', 'Contract updated successfully!');
    }

    public function destroy(EmploymentContract $contract)
    {
        $employee = $contract->employee;
        $contract->delete();

        return redirect()->route('employees.show', $employee)
            ->with('status', 'Contract deleted successfully!');
    }

    public function downloadPdf(EmploymentContract $contract)
    {
        $contract->load('employee');
        
        $pdf = Pdf::loadView('contracts.pdf', compact('contract'))
            ->setPaper('a4', 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        $filename = 'Employment_Contract_' . $contract->employee->first_name . '_' . $contract->employee->last_name . '_' . $contract->start_date->format('Y-m-d') . '.pdf';

        return $pdf->download($filename);
    }
}
