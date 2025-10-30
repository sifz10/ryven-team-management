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
        $query = Employee::query();

        if ($request->filled('status')) {
            if ($request->string('status')->toString() === 'active') {
                $query->whereNull('discontinued_at');
            } elseif ($request->string('status')->toString() === 'discontinued') {
                $query->whereNotNull('discontinued_at');
            }
        }

        if ($request->filled('q')) {
            $q = $request->string('q')->toString();
            $query->where(function ($x) use ($q) {
                $x->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('position', 'like', "%{$q}%");
            });
        }

        $employees = $query->latest()->paginate(12)->withQueryString();
        return view('employees.index', compact('employees'));
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
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
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
            'phone' => ['nullable', 'string', 'max:50'],
            'position' => ['nullable', 'string', 'max:255'],
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
