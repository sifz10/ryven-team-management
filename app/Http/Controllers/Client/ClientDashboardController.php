<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $client = Auth::guard('client')->user();

        // Check if this client is a team member for another client
        $teamMember = \App\Models\ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            // This is a team member - get parent client and assigned projects
            $parentClient = $teamMember->client;

            // Get assigned projects through project_members table
            $projectIds = \App\Models\ProjectMember::where('client_team_member_id', $teamMember->id)
                ->pluck('project_id');

            if ($projectIds->isNotEmpty()) {
                // Team member with specific projects - only show assigned projects
                $projects = \App\Models\Project::whereIn('id', $projectIds)->get();
            } else {
                // Team member with no specific assignments - show all parent client's projects
                $projects = $parentClient->projects;
            }

            return view('client.dashboard', compact('client', 'projects', 'teamMember', 'parentClient'));
        } else {
            // This is a main client - show their own projects
            $projects = $client->projects;
            return view('client.dashboard', compact('client', 'projects'));
        }
    }
}
