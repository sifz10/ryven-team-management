<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\ClientTeamMember;
use App\Models\Client;
use App\Mail\ClientTeamMemberInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class ClientTeamController extends Controller
{
    /**
     * Display list of team members for the authenticated client
     */
    public function index()
    {
        $client = auth('client')->user();

        $teamMembers = ClientTeamMember::where('client_id', $client->id)
            ->with(['teamMemberClient', 'projects'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('client.team.index', compact('teamMembers', 'client'));
    }

    /**
     * Show form to invite a new team member
     */
    public function create()
    {
        $client = auth('client')->user();
        return view('client.team.create', compact('client'));
    }

    /**
     * Invite a new team member
     */
    public function store(Request $request)
    {
        $client = auth('client')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'role' => 'nullable|string|max:255',
        ]);

        // Generate temporary password and invitation token
        $password = Str::random(12);
        $invitationToken = Str::random(64);

        // Create team member as a Client
        $teamMemberClient = Client::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'must_change_password' => true,
            'status' => 'active',
        ]);

        // Create team member record
        $teamMember = ClientTeamMember::create([
            'client_id' => $client->id,
            'team_member_client_id' => $teamMemberClient->id,
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'status' => 'invited',
            'invitation_token' => $invitationToken,
            'invitation_sent_at' => now(),
        ]);

        // Send invitation email with login instructions
        try {
            Mail::to($teamMember->email)->send(new ClientTeamMemberInvitation($teamMember, $client, $password));
        } catch (\Exception $e) {
            // Log error but don't fail
            \Illuminate\Support\Facades\Log::error('Failed to send team member invitation: ' . $e->getMessage());
        }

        return redirect()->route('client.team.index')
            ->with('success', 'Team member invited successfully! They will receive an email with login instructions.');
    }

    /**
     * Resend invitation email to a team member
     */
    public function resendInvitation(ClientTeamMember $teamMember)
    {
        // Eager load the team member's client relationship
        $teamMember->load('teamMemberClient');

        $client = auth('client')->user();

        // Verify ownership
        if ($teamMember->client_id !== $client->id) {
            abort(403, 'Unauthorized action.');
        }

        // Check if team member's Client account exists
        if (!$teamMember->teamMemberClient) {
            return redirect()->back()
                ->with('error', 'Team member account not found. Please remove and re-invite this member.');
        }

        // Generate new password
        $password = Str::random(12);

        // Update team member's Client password
        $teamMember->teamMemberClient->update([
            'password' => Hash::make($password),
            'must_change_password' => true,
        ]);

        // Update invitation timestamp
        $teamMember->update([
            'invitation_sent_at' => now(),
        ]);

        // Send invitation email
        try {
            Mail::to($teamMember->email)->send(new ClientTeamMemberInvitation($teamMember, $client, $password));

            return redirect()->back()
                ->with('success', 'Invitation resent successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to send invitation email.');
        }
    }

    /**
     * Remove a team member
     */
    public function destroy(ClientTeamMember $teamMember)
    {
        $client = auth('client')->user();

        // Verify ownership
        if ($teamMember->client_id !== $client->id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete associated team member's Client account and team member record
        $teamMemberClient = $teamMember->teamMemberClient;
        $teamMember->delete();

        if ($teamMemberClient) {
            $teamMemberClient->delete();
        }

        return redirect()->route('client.team.index')
            ->with('success', 'Team member removed successfully.');
    }
}
