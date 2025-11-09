<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientTeamMember;
use App\Mail\ClientOtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class ClientAuthController extends Controller
{
    public function showLogin()
    {
        return view('client.auth.login');
    }

    public function sendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        // Check if client exists in clients table
        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'message' => 'No account found with this email address. Please check your email or contact support.'
            ], 404);
        }

        // Generate 6-digit OTP
        $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Save OTP with 10-minute expiration
        $client->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        Mail::to($client->email)->send(new ClientOtpMail($otpCode, $client->email));

        return response()->json([
            'success' => true,
            'message' => 'OTP sent successfully! Check your email.'
        ]);
    }

    public function login(Request $request)
    {
        $loginMethod = $request->input('login_method', 'password');

        if ($loginMethod === 'otp') {
            return $this->loginWithOtp($request);
        }

        return $this->loginWithPassword($request);
    }

    protected function loginWithOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|digits:6',
        ]);

        $client = Client::where('email', $request->email)->first();

        if (!$client) {
            return back()->withErrors([
                'email' => 'No account found with this email address.',
            ])->onlyInput('email');
        }

        // Check if OTP matches and is not expired
        if ($client->otp_code !== $request->otp) {
            return back()->withErrors([
                'otp' => 'Invalid OTP code. Please check and try again.',
            ])->withInput();
        }

        if (!$client->otp_expires_at || $client->otp_expires_at->isPast()) {
            return back()->withErrors([
                'otp' => 'OTP code has expired. Please request a new one.',
            ])->withInput();
        }

        // Clear OTP after successful login
        $client->update([
            'otp_code' => null,
            'otp_expires_at' => null,
            'last_login_at' => now(),
        ]);

        // Log the user in
        Auth::guard('client')->login($client);
        $request->session()->regenerate();

        // Check if this client is a team member and update status to active on first login
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)
            ->where('status', 'invited')
            ->first();

        if ($teamMember) {
            $teamMember->update([
                'status' => 'active',
                'joined_at' => now(),
            ]);
        }

        return redirect()->intended(route('client.dashboard'));
    }

    protected function loginWithPassword(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('client')->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            $client = Auth::guard('client')->user();
            $client->update(['last_login_at' => now()]);

            // Check if this client is a team member and update status to active on first login
            $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)
                ->where('status', 'invited')
                ->first();

            if ($teamMember) {
                $teamMember->update([
                    'status' => 'active',
                    'joined_at' => now(),
                ]);
            }

            // Check if password change is required
            if ($client->must_change_password) {
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
        $client = Auth::guard('client')->user();

        if (!$client->must_change_password) {
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

        $client = Auth::guard('client')->user();

        if (!Hash::check($request->current_password, $client->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $client->update([
            'password' => Hash::make($request->password),
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
