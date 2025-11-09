<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectTask;
use App\Models\Employee;
use App\Models\ClientTeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClientProjectController extends Controller
{
    /**
     * Display a listing of client's projects
     */
    public function index()
    {
        $client = Auth::guard('client')->user();

        // Check if this client is a team member
        $teamMember = \App\Models\ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            // Team member - show only assigned projects
            $projectIds = \App\Models\ProjectMember::where('client_team_member_id', $teamMember->id)
                ->pluck('project_id');

            if ($projectIds->isEmpty()) {
                // No projects assigned yet - show parent client's projects
                $projects = Project::where('client_id', $teamMember->client_id)
                    ->withCount(['tasks', 'members'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                // Show only assigned projects
                $projects = Project::whereIn('id', $projectIds)
                    ->withCount(['tasks', 'members'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            }
        } else {
            // Main client - show their own projects
            $projects = Project::where('client_id', $client->id)
                ->withCount(['tasks', 'members'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
        }

        $isTeamMember = $teamMember !== null;

        return view('client.projects.index', compact('projects', 'isTeamMember'));
    }

    /**
     * Show the form for creating a new project
     */
    public function create()
    {
        $client = Auth::guard('client')->user();
        $employees = Employee::whereNull('discontinued_at')->orderBy('first_name')->get();

        return view('client.projects.create', compact('client', 'employees'));
    }

    /**
     * Store a newly created project
     * Limitation: Client can only create projects for themselves, default status is 'planning'
     */
    public function store(Request $request)
    {
        $client = Auth::guard('client')->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        // Client limitations: always their own client_id, status starts as 'planning'
        $validated['client_id'] = $client->id;
        $validated['status'] = 'planning'; // Must be approved by admin
        $validated['progress'] = 0;
        $validated['priority'] = 3; // Default priority

        $project = Project::create($validated);

        return redirect()->route('client.projects.show', $project)->with('status', 'Project created successfully! It will be reviewed by the admin.');
    }

    /**
     * Display the specified project
     */
    public function show(Project $project)
    {
        $client = Auth::guard('client')->user();

        // Check if this client is a team member
        $teamMember = \App\Models\ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            // Team member - check if they have access to this project
            $hasAccess = \App\Models\ProjectMember::where('project_id', $project->id)
                ->where('client_team_member_id', $teamMember->id)
                ->exists();

            if (!$hasAccess && $project->client_id !== $teamMember->client_id) {
                abort(403, 'Unauthorized access to this project');
            }
        } else {
            // Main client - check if project belongs to them
            if ($project->client_id !== $client->id) {
                abort(403, 'Unauthorized access to this project');
            }
        }

        $project->load([
            'client',
            'members.employee',
            'members.clientTeamMember',
            'tasks' => function($query) {
                $query->with(['checklists', 'tags', 'assignedTo', 'files.uploader', 'comments.employee'])
                    ->orderBy('order')
                    ->orderBy('created_at', 'desc');
            },
        ]);

        // Determine if this user is a team member viewing the project
        $isTeamMember = $teamMember !== null;

        return view('client.projects.show', compact('project', 'client', 'isTeamMember'));
    }

    /**
     * Show the form for editing the specified project
     * Limitation: Cannot edit if status is 'completed' or 'cancelled'
     */
    public function edit(Project $project)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();
        if ($teamMember) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Team members cannot edit projects. Please contact the project owner.');
        }

        // Check if project belongs to this client
        if ($project->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this project');
        }

        // Limitation: Cannot edit completed or cancelled projects
        if (in_array($project->status, ['completed', 'cancelled'])) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Cannot edit projects that are completed or cancelled. Please contact admin.');
        }

        $employees = Employee::whereNull('discontinued_at')->orderBy('first_name')->get();

        return view('client.projects.edit', compact('project', 'client', 'employees'));
    }

    /**
     * Update the specified project
     * Limitation: Cannot change status, priority, or project_manager
     */
    public function update(Request $request, Project $project)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();
        if ($teamMember) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Team members cannot update projects. Please contact the project owner.');
        }

        // Check if project belongs to this client
        if ($project->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this project');
        }

        // Limitation: Cannot edit completed or cancelled projects
        if (in_array($project->status, ['completed', 'cancelled'])) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Cannot edit projects that are completed or cancelled.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
        ]);

        // Client limitations: Cannot change status, priority, or project_manager
        // These can only be changed by admin
        $project->update($validated);

        return redirect()->route('client.projects.show', $project)->with('status', 'Project updated successfully!');
    }

    /**
     * Remove the specified project
     * Limitation: Can only delete projects in 'planning' status
     */
    public function destroy(Project $project)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();
        if ($teamMember) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Team members cannot delete projects. Please contact the project owner.');
        }

        // Check if project belongs to this client
        if ($project->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this project');
        }

        // Limitation: Can only delete planning projects
        if ($project->status !== 'planning') {
            return redirect()->route('client.projects.index')
                ->with('error', 'Can only delete projects in planning stage. Please contact admin to cancel this project.');
        }

        $project->delete();

        return redirect()->route('client.projects.index')->with('status', 'Project deleted successfully!');
    }

    /**
     * Add team member to project
     * Clients can add their existing team members to projects
     */
    public function addMember(Request $request, Project $project)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();
        if ($teamMember) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Team members cannot add other members to projects.');
        }

        // Check if project belongs to this client
        if ($project->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this project');
        }

        // Validate input - expecting array of team member IDs
        $validated = $request->validate([
            'team_member_ids' => 'required|array|min:1',
            'team_member_ids.*' => 'exists:client_team_members,id',
        ]);

        $addedCount = 0;
        $skippedCount = 0;

        foreach ($validated['team_member_ids'] as $teamMemberId) {
            // Get the team member
            $teamMember = \App\Models\ClientTeamMember::find($teamMemberId);

            // Verify this team member belongs to the client
            if ($teamMember->client_id !== $client->id) {
                continue;
            }

            // Check if already added to project
            $existingMember = $project->members()
                ->where('member_type', 'client_team')
                ->where('client_team_member_id', $teamMemberId)
                ->first();

            if ($existingMember) {
                $skippedCount++;
                continue;
            }

            // Add team member to project
            $project->members()->create([
                'member_type' => 'client_team',
                'client_team_member_id' => $teamMemberId,
                'client_member_name' => $teamMember->name,
                'client_member_email' => $teamMember->email,
                'role' => $teamMember->role,
            ]);

            $addedCount++;
        }

        if ($addedCount > 0) {
            $message = $addedCount === 1
                ? 'Team member added successfully!'
                : "{$addedCount} team members added successfully!";

            if ($skippedCount > 0) {
                $message .= " ({$skippedCount} already in project)";
            }

            return back()->with('status', $message);
        }

        return back()->with('error', 'No new team members were added. They may already be in the project.');
    }

    /**
     * Remove team member from project
     */
    public function removeMember(Project $project, ProjectMember $member)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();
        if ($teamMember) {
            return redirect()->route('client.projects.show', $project)
                ->with('error', 'Team members cannot remove members from projects.');
        }

        // Check if project belongs to this client
        if ($project->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this project');
        }

        // Clients can only remove client-type or client_team members, not internal employees
        if (!in_array($member->member_type, ['client', 'client_team'])) {
            return back()->with('error', 'You can only remove your own team members, not internal employees.');
        }

        // Check if member belongs to this project
        if ($member->project_id !== $project->id) {
            abort(403, 'This member does not belong to this project');
        }

        $member->delete();

        return back()->with('status', 'Team member removed successfully!');
    }

    /**
     * Update team member details
     */
    public function updateMember(Request $request, Project $project, ProjectMember $member)
    {
        $client = Auth::guard('client')->user();

        // Check if project belongs to this client
        if ($project->client_id !== $client->id) {
            abort(403, 'Unauthorized access to this project');
        }

        // Clients can only update client-type members
        if ($member->member_type !== 'client') {
            return back()->with('error', 'You can only update your own team members.');
        }

        // Check if member belongs to this project
        if ($member->project_id !== $project->id) {
            abort(403, 'This member does not belong to this project');
        }

        // Validate input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'nullable|string|max:255',
            'responsibilities' => 'nullable|string',
        ]);

        // Check if email is being changed to one that already exists
        if ($validated['email'] !== $member->client_member_email) {
            $existingMember = $project->members()
                ->where('member_type', 'client')
                ->where('client_member_email', $validated['email'])
                ->where('id', '!=', $member->id)
                ->first();

            if ($existingMember) {
                return back()->with('error', 'Another team member with this email already exists in this project.');
            }
        }

        // Update member
        $member->update([
            'client_member_name' => $validated['name'],
            'client_member_email' => $validated['email'],
            'role' => $validated['role'] ?? null,
            'responsibilities' => $validated['responsibilities'] ?? null,
        ]);

        return back()->with('status', 'Team member updated successfully!');
    }
}
