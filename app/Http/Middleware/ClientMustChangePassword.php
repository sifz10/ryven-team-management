<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ClientMustChangePassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $clientUser = auth()->guard('client')->user();

        // Check if client must change password
        if ($clientUser && $clientUser->must_change_password) {
            // Allow access to change password routes
            if (!$request->routeIs('client.password.change') && !$request->routeIs('client.password.change.post') && !$request->routeIs('client.logout')) {
                return redirect()->route('client.password.change');
            }
        }

        return $next($request);
    }
}
