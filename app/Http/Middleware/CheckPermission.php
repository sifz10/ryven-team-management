<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $permission
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        // Check if employee is authenticated
        if (!Auth::guard('employee')->check()) {
            abort(403, 'Unauthorized access - Not authenticated');
        }

        $employee = Auth::guard('employee')->user();

        // Check if employee has the required permission
        if (!$employee->hasPermission($permission)) {
            // Log unauthorized access attempt
            \Log::warning('Unauthorized access attempt', [
                'employee' => $employee->full_name,
                'email' => $employee->email,
                'permission' => $permission,
                'url' => $request->url(),
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'You do not have permission to perform this action.',
                    'required_permission' => $permission
                ], 403);
            }

            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
