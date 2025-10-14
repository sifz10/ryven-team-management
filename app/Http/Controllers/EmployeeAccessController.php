<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeAccess;
use Illuminate\Http\Request;

class EmployeeAccessController extends Controller
{
    public function store(Request $request, Employee $employee)
	{
        $validated = $request->validate([
			'title' => ['required', 'string', 'max:255'],
			'note_markdown' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
		]);
		$validated['employee_id'] = $employee->id;
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('employee-access', 'public');
            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $request->file('attachment')->getClientOriginalName();
        }
        EmployeeAccess::create($validated);
		return redirect()->route('employees.show', $employee)->with('status', 'Access added');
	}

    public function update(Request $request, Employee $employee, EmployeeAccess $access)
	{
		abort_unless($access->employee_id === $employee->id, 404);
        $validated = $request->validate([
			'title' => ['required', 'string', 'max:255'],
			'note_markdown' => ['nullable', 'string'],
            'attachment' => ['nullable', 'file', 'max:10240'],
		]);
        if ($request->hasFile('attachment')) {
            $path = $request->file('attachment')->store('employee-access', 'public');
            $validated['attachment_path'] = $path;
            $validated['attachment_name'] = $request->file('attachment')->getClientOriginalName();
        }
        $access->update($validated);
		return redirect()->route('employees.show', $employee)->with('status', 'Access updated');
	}

	public function destroy(Employee $employee, EmployeeAccess $access)
	{
		abort_unless($access->employee_id === $employee->id, 404);
		$access->delete();
		return redirect()->route('employees.show', $employee)->with('status', 'Access removed');
	}
}


