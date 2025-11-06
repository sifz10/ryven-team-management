<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    /**
     * Display the employee's profile form.
     */
    public function edit(Request $request): View
    {
        $employee = Auth::guard('employee')->user();

        return view('employee.profile.edit', compact('employee'));
    }

    /**
     * Update the employee's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $employee = Auth::guard('employee')->user();

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'current_password' => ['nullable', 'required_with:password'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        // Update basic info
        $employee->first_name = $validated['first_name'];
        $employee->last_name = $validated['last_name'];
        $employee->phone = $validated['phone'] ?? $employee->phone;

        // Update password if provided
        if ($request->filled('password')) {
            // Verify current password
            if (!Hash::check($request->current_password, $employee->password)) {
                return back()->withErrors([
                    'current_password' => 'The provided password does not match your current password.'
                ]);
            }

            $employee->password = $validated['password'];
        }

        $employee->save();

        return back()->with('success', 'Profile updated successfully!');
    }
}
