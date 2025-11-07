<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientUser;
use App\Models\ClientTeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ClientAuthController extends Controller
{
    public function showLogin()
    {
        return view('client.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('client')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $clientUser = Auth::guard('client')->user();
            $clientUser->update(['last_login_at' => now()]);

            // Check if password change is required
            if ($clientUser->must_change_password) {
                return redirect()->route('client.password.change');
            }

            return redirect()->intended(route('client.dashboard'));
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('client.login');
    }

    public function showChangePassword()
    {
        $clientUser = Auth::guard('client')->user();

        if (!$clientUser->must_change_password) {
            return redirect()->route('client.dashboard');
        }

        return view('client.auth.change-password');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $clientUser = Auth::guard('client')->user();

        if (!Hash::check($request->current_password, $clientUser->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $clientUser->update([
            'password' => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('client.dashboard')->with('status', 'Password changed successfully!');
    }

    public function acceptInvitation($token)
    {
        $teamMember = ClientTeamMember::where('invitation_token', $token)->firstOrFail();

        if ($teamMember->status === 'active') {
            return redirect()->route('client.login')->with('info', 'You have already accepted this invitation. Please login.');
        }

        // Mark team member as active and joined
        $teamMember->update([
            'status' => 'active',
            'joined_at' => now(),
        ]);

        return redirect()->route('client.login')->with('success', 'Invitation accepted! Please login with the credentials sent to your email.');
    }
}
