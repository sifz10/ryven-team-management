<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientTeamMember;
use App\Models\ClientUser;
use App\Models\Project;
use App\Mail\ClientTeamMemberInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class ClientTeamMemberController extends Controller
{
    public function store(Request $request, Client $client)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:client_team_members,email',
            'role' => 'nullable|string|max:255',
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        // Generate random password
        $password = Str::random(12);

        // Generate invitation token
        $invitationToken = Str::random(32);

        // Create team member
        $teamMember = $client->teamMembers()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'] ?? null,
            'status' => 'pending',
            'invitation_token' => $invitationToken,
            'invitation_sent_at' => now(),
        ]);

        // Create ClientUser for authentication
        $clientUser = ClientUser::create([
            'client_id' => $client->id,
            'email' => $validated['email'],
            'password' => $password,
            'must_change_password' => true,
        ]);

        // Link team member to client user
        $teamMember->update(['client_user_id' => $clientUser->id]);

        // Assign projects if provided
        if (!empty($validated['project_ids'])) {
            $teamMember->projects()->attach($validated['project_ids']);
        }

        // Send invitation email
        try {
            Mail::to($teamMember->email)->send(new ClientTeamMemberInvitation($teamMember, $client, $password));
        } catch (\Exception $e) {
            Log::error('Failed to send team member invitation email: ' . $e->getMessage());
        }

        return back()->with('success', "Team member invited successfully! Invitation email sent to {$teamMember->email}");
    }

    public function destroy(Client $client, ClientTeamMember $teamMember)
    {
        // Delete associated ClientUser if exists
        if ($teamMember->clientUser) {
            $teamMember->clientUser->delete();
        }

        $teamMember->delete();

        return back()->with('success', 'Team member removed successfully!');
    }

    public function updateProjects(Request $request, Client $client, ClientTeamMember $teamMember)
    {
        $validated = $request->validate([
            'project_ids' => 'nullable|array',
            'project_ids.*' => 'exists:projects,id',
        ]);

        $teamMember->projects()->sync($validated['project_ids'] ?? []);

        return back()->with('success', 'Team member projects updated successfully!');
    }

    public function resendInvitation(Client $client, ClientTeamMember $teamMember)
    {
        // Generate new password
        $password = Str::random(12);

        // Update ClientUser password
        if ($teamMember->clientUser) {
            $teamMember->clientUser->update([
                'password' => $password,
                'must_change_password' => true,
            ]);
        }

        // Update invitation sent time
        $teamMember->update([
            'invitation_sent_at' => now(),
        ]);

        // Resend email
        try {
            Mail::to($teamMember->email)->send(new ClientTeamMemberInvitation($teamMember, $client, $password));
            return back()->with('success', "Invitation resent to {$teamMember->email}");
        } catch (\Exception $e) {
            Log::error('Failed to resend team member invitation: ' . $e->getMessage());
            return back()->with('error', 'Failed to send invitation email');
        }
    }
}
