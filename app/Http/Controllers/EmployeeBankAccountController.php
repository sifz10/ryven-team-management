<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\EmployeeBankAccount;
use Illuminate\Http\Request;

class EmployeeBankAccountController extends Controller
{
	public function store(Request $request, Employee $employee)
	{
		$validated = $request->validate([
			'title' => ['required', 'string', 'max:255'],
			'details_markdown' => ['required', 'string'],
		]);

		$validated['employee_id'] = $employee->id;
		EmployeeBankAccount::create($validated);

		return redirect()->route('employees.show', $employee)->with('status', 'Bank account added');
	}

	public function update(Request $request, Employee $employee, EmployeeBankAccount $account)
	{
		abort_unless($account->employee_id === $employee->id, 404);

		$validated = $request->validate([
			'title' => ['required', 'string', 'max:255'],
			'details_markdown' => ['required', 'string'],
		]);

		$account->update($validated);

		return redirect()->route('employees.show', $employee)->with('status', 'Bank account updated');
	}

	public function destroy(Employee $employee, EmployeeBankAccount $account)
	{
		abort_unless($account->employee_id === $employee->id, 404);
		$account->delete();
		return redirect()->route('employees.show', $employee)->with('status', 'Bank account removed');
	}
}


