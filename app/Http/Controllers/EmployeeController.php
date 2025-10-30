<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Statistics
        $totalEmployees = Employee::count();
        $activeEmployees = Employee::whereNull('discontinued_at')->count();
        $discontinuedEmployees = Employee::whereNotNull('discontinued_at')->count();
        
        // Calculate total monthly payroll by currency
        $payrollByCurrency = Employee::whereNull('discontinued_at')
            ->whereNotNull('salary')
            ->selectRaw('currency, SUM(salary) as total')
            ->groupBy('currency')
            ->get();
        
        // Get all departments
        $departments = Employee::whereNotNull('department')
            ->distinct()
            ->pluck('department')
            ->sort();

        $query = Employee::query();

        if ($request->filled('status')) {
            if ($request->string('status')->toString() === 'active') {
                $query->whereNull('discontinued_at');
            } elseif ($request->string('status')->toString() === 'discontinued') {
                $query->whereNotNull('discontinued_at');
            }
        }

        if ($request->filled('department')) {
            $query->where('department', $request->string('department')->toString());
        }

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($x) use ($q) {
                $x->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('department', 'like', "%{$q}%")
                    ->orWhere('position', 'like', "%{$q}%");
            });
        }

        $employees = $query->with([
            'payments' => function($q) {
                $q->latest('paid_at')->limit(5);
            }
        ])->withCount([
            'payments as achievement_count' => function($q) {
                $q->where('activity_type', 'achievement');
            },
            'payments as warning_count' => function($q) {
                $q->where('activity_type', 'warning');
            },
            'payments as payment_count' => function($q) {
                $q->where('activity_type', 'payment');
            },
            'payments as note_count' => function($q) {
                $q->where('activity_type', 'note');
            }
        ])->latest()->paginate(12)->withQueryString();
        
        return view('employees.index', compact(
            'employees', 
            'totalEmployees', 
            'activeEmployees', 
            'discontinuedEmployees', 
            'payrollByCurrency',
            'departments'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email'],
            'github_username' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'hired_at' => ['nullable', 'date'],
        ]);

        $validated['user_id'] = auth()->id();
        Employee::create($validated);

        return redirect()->route('employees.index')->with('status', 'Employee created');
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        // Load payments with notes
        $employee->load(['payments.notes.user']);
        
        // Load checklist templates
        $checklistTemplates = $employee->checklistTemplates()->with('items')->get();
        
        // Load today's checklists
        $todayChecklists = $employee->dailyChecklists()
            ->with(['template.items', 'items'])
            ->where('date', now()->toDateString())
            ->get();

        // Load all sent checklists (history) - ordered by date descending
        $checklistHistory = $employee->dailyChecklists()
            ->with(['template.items', 'items'])
            ->whereNotNull('email_sent_at')
            ->orderByDesc('date')
            ->orderByDesc('email_sent_at')
            ->get();

        return view('employees.show', compact('employee', 'checklistTemplates', 'todayChecklists', 'checklistHistory'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:employees,email,'.$employee->id],
            'github_username' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'salary' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'size:3'],
            'hired_at' => ['nullable', 'date'],
            'discontinued_at' => ['nullable', 'date'],
        ]);

        $employee->update($validated);

        return redirect()->route('employees.index')->with('status', 'Employee updated');
    }

    public function discontinue(Request $request, Employee $employee)
    {
        $employee->update([
            'discontinued_at' => now(),
        ]);
        return redirect()->route('employees.show', $employee)->with('status', 'Employee discontinued');
    }

    public function reactivate(Request $request, Employee $employee)
    {
        $employee->update([
            'discontinued_at' => null,
        ]);
        return redirect()->route('employees.show', $employee)->with('status', 'Employee reactivated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        $employee->delete();
        return redirect()->route('employees.index')->with('status', 'Employee deleted');
    }
}
