<?php

namespace App\Http\Controllers\Employee\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /**
     * Display the employee login view.
     */
    public function create(): View
    {
        return view('employee.auth.login');
    }

    /**
     * Handle an incoming employee authentication request.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        // Attempt to authenticate with employee guard
        if (Auth::guard('employee')->attempt($credentials, $remember)) {
            $employee = Auth::guard('employee')->user();

            // Check if employee can login
            if (!$employee->canLogin()) {
                Auth::guard('employee')->logout();
                return back()->withErrors([
                    'email' => 'Your account is not active or has been discontinued.',
                ])->onlyInput('email');
            }

            $request->session()->regenerate();

            return redirect()->intended(route('employee.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Destroy an authenticated employee session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('employee')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('employee.login');
    }
}
