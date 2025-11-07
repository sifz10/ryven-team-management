<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientDashboardController extends Controller
{
    public function index()
    {
        $clientUser = Auth::guard('client')->user();
        $client = $clientUser->client()->with('projects')->first();

        // Check if user is a team member with specific project assignments
        $teamMember = $client->teamMembers()->where('client_user_id', $clientUser->id)->first();

        if ($teamMember && $teamMember->projects()->count() > 0) {
            // Team member with specific projects - only show assigned projects
            $projects = $teamMember->projects;
        } else {
            // Main client or team member with all access - show all projects
            $projects = $client->projects;
        }

        return view('client.dashboard', compact('client', 'projects', 'teamMember'));
    }
}
