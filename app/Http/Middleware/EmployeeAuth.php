<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EmployeeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('employee')->check()) {
            return redirect()->route('employee.login');
        }

        $employee = Auth::guard('employee')->user();

        // Check if employee is active and not discontinued
        if (!$employee->canLogin()) {
            Auth::guard('employee')->logout();
            return redirect()->route('employee.login')
                ->withErrors(['email' => 'Your account is not active or has been discontinued.']);
        }

        return $next($request);
    }
}
