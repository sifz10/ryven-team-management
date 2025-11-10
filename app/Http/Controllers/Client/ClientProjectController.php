<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectTask;
use App\Models\ProjectFile;
use App\Models\Employee;
use App\Models\ClientTeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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
            'files.uploader',
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

    // ============ PROJECT FILES ============

    /**
     * Store file(s) to project
     */
    public function storeFile(Request $request, Project $project)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            // Team member - check if they have access to this project
            $hasAccess = ProjectMember::where('project_id', $project->id)
                ->where('client_team_member_id', $teamMember->id)
                ->exists();

            if (!$hasAccess && $project->client_id !== $teamMember->client_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this project'
                ], 403);
            }
        } else {
            // Main client - check if project belongs to them
            if ($project->client_id !== $client->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this project'
                ], 403);
            }
        }

        $validated = $request->validate([
            'files' => 'required|array|max:10', // Max 10 files
            'files.*' => 'required|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,csv,zip,rar,7z,jpg,jpeg,png,gif,svg,webp,bmp,ico,mp3,mp4,avi,mov,wmv,flv,mkv,webm,wav,ogg,psd,ai,eps,indd,sketch,fig,json,xml,html,css,js,php,py,java,cpp,c,h,md,log', // 50MB max per file
            'description' => 'nullable|string',
            'category' => 'required|string|max:255',
            'tags' => 'nullable|string', // JSON string
        ]);

        $uploadedCount = 0;

        foreach ($request->file('files') as $file) {
            $path = $file->store('projects/' . $project->id . '/files', 'public');

            ProjectFile::create([
                'project_id' => $project->id,
                'name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'uploaded_by' => null, // Clients don't have employee_id
                'assigned_to' => null, // Clients can't assign to employees
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'],
                'tags' => $validated['tags'] ?? null,
            ]);

            $uploadedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => $uploadedCount . ' file' . ($uploadedCount > 1 ? 's' : '') . ' uploaded successfully!'
        ]);
    }

    /**
     * Delete a project file
     */
    public function destroyFile(Project $project, ProjectFile $file)
    {
        $client = Auth::guard('client')->user();

        // Check if user is a team member
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            // Team member - check if they have access to this project
            $hasAccess = ProjectMember::where('project_id', $project->id)
                ->where('client_team_member_id', $teamMember->id)
                ->exists();

            if (!$hasAccess && $project->client_id !== $teamMember->client_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this project'
                ], 403);
            }
        } else {
            // Main client - check if project belongs to them
            if ($project->client_id !== $client->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access to this project'
                ], 403);
            }
        }

        // Verify the file belongs to this project
        if ($file->project_id !== $project->id) {
            return response()->json([
                'success' => false,
                'message' => 'File not found in this project'
            ], 404);
        }

        // Delete the physical file
        Storage::disk('public')->delete($file->file_path);

        // Delete the database record
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully!'
        ]);
    }

    // Disabled: Clients can only view tasks, not modify them
    // public function updateTaskStatus(Request $request, Project $project, $taskId)
    // {
    //     // Method removed - clients have read-only access to tasks
    // }

    public function showTask(Project $project, $taskId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        if ($project->client_id !== $client->id &&
            !$project->members()->where('client_id', $client->id)->exists()) {
            abort(403, 'Unauthorized access to this project.');
        }

        $task = \App\Models\ProjectTask::with([
            'assignee',
            'tags',
            'checklists',
            'files',
            'comments.employee',
            'reminders'
        ])->findOrFail($taskId);

        return view('client.projects.tasks.show', compact('project', 'task'));
    }

    public function getTaskFiles(Project $project, $taskId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $files = $task->files()->get()->map(function($file) {
            return [
                'id' => $file->id,
                'original_name' => $file->original_name,
                'extension' => pathinfo($file->original_name, PATHINFO_EXTENSION),
                'size' => $file->file_size,
                'size_formatted' => $file->file_size_formatted,
                'url' => Storage::url($file->file_path),
                'uploaded_at' => $file->created_at->diffForHumans(),
            ];
        });

        return response()->json(['success' => true, 'files' => $files]);
    }

    public function getTaskComments(Project $project, $taskId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $comments = $task->comments()
            ->with(['employee:id,first_name,last_name,email', 'replies.employee', 'reactions.employee'])
            ->latest()
            ->get()
            ->map(function($comment) {
                $replies = $comment->replies->map(function($reply) {
                    return [
                        'id' => $reply->id,
                        'reply' => $reply->reply,
                        'employee' => [
                            'id' => $reply->employee->id,
                            'first_name' => $reply->replied_by_client ? $reply->client_name : $reply->employee->first_name,
                            'last_name' => $reply->replied_by_client ? '' : $reply->employee->last_name,
                        ],
                        'is_client' => $reply->replied_by_client ?? false,
                        'created_at' => $reply->created_at->toDateTimeString(),
                        'created_at_human' => $reply->created_at->diffForHumans(),
                    ];
                });

                // Group reactions by type and count
                $reactions = $comment->reactions->groupBy('reaction_type')->map(function($items, $type) {
                    return [
                        'type' => $type,
                        'count' => $items->count(),
                        'users' => $items->map(function($reaction) {
                            return [
                                'id' => $reaction->employee->id,
                                'name' => $reaction->employee->first_name . ' ' . $reaction->employee->last_name,
                            ];
                        }),
                    ];
                })->values();

                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'employee' => [
                        'id' => $comment->employee->id,
                        'first_name' => $comment->commented_by_client ? $comment->client_name : $comment->employee->first_name,
                        'last_name' => $comment->commented_by_client ? '' : $comment->employee->last_name,
                        'email' => $comment->employee->email,
                    ],
                    'is_client' => $comment->commented_by_client ?? false,
                    'replies' => $replies,
                    'reactions' => $reactions,
                    'showReplyInput' => false,
                    'replyText' => '',
                    'showReplyMentionDropdown' => false,
                    'replyMentionEmployees' => [],
                    'created_at' => $comment->created_at->toDateTimeString(),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                ];
            });

        return response()->json(['success' => true, 'comments' => $comments]);
    }

    public function storeTaskComment(Request $request, Project $project, $taskId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $validated = $request->validate([
            'comment' => 'required|string|max:2000'
        ]);

        // Get the first employee (admin) to attribute the comment to
        // In a real scenario, you might want to create a ClientComment model instead
        $firstEmployee = \App\Models\Employee::first();

        if (!$firstEmployee) {
            return response()->json(['success' => false, 'message' => 'Unable to create comment'], 500);
        }

        $comment = $task->comments()->create([
            'comment' => $validated['comment'],
            'employee_id' => $firstEmployee->id,
            'commented_by_client' => true, // Add this column to track client comments
            'client_name' => $client->name ?? 'Client',
        ]);

        $comment->load('employee:id,first_name,last_name,email');

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'employee' => [
                    'first_name' => $client->name ?? 'Client',
                    'last_name' => '',
                    'email' => $client->email ?? '',
                ],
                'created_at' => $comment->created_at->toDateTimeString(),
                'created_at_human' => 'Just now',
            ]
        ]);
    }

    public function uploadTaskFile(Request $request, Project $project, $taskId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $request->validate([
            'file' => 'required|file|max:102400' // 100MB
        ]);

        try {
            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $size = $file->getSize();

            // Store file
            $path = $file->store('task-files', 'public');

            // For client uploads, use the first employee or create a system placeholder
            // This is required because uploaded_by has a foreign key constraint
            $systemEmployee = \App\Models\Employee::first();
            if (!$systemEmployee) {
                return response()->json([
                    'success' => false,
                    'message' => 'System configuration error: No employee records found',
                ], 500);
            }

            // Create file record
            $taskFile = $task->files()->create([
                'original_name' => $originalName,
                'file_path' => $path,
                'file_type' => $file->getMimeType(),
                'file_size' => $size,
                'uploaded_by' => $systemEmployee->id, // Use system employee for client uploads
            ]);

            return response()->json([
                'success' => true,
                'file' => [
                    'id' => $taskFile->id,
                    'original_name' => $taskFile->original_name,
                    'extension' => $extension,
                    'size' => $taskFile->file_size,
                    'size_formatted' => $this->formatBytes($taskFile->file_size),
                    'url' => Storage::url($taskFile->file_path),
                    'uploaded_at' => $taskFile->created_at->diffForHumans(),
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTaskReminders(Project $project, $taskId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $reminders = $task->reminders()
            ->with('recipients')
            ->where('remind_at', '>=', now())
            ->orderBy('remind_at')
            ->get()
            ->map(function($reminder) {
                return [
                    'id' => $reminder->id,
                    'message' => $reminder->message,
                    'remind_at' => $reminder->remind_at->toDateTimeString(),
                    'recipients_count' => $reminder->recipients->count(),
                ];
            });

        return response()->json(['success' => true, 'reminders' => $reminders]);
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public function toggleChecklist($taskId, $checklistId)
    {
        $client = Auth::guard('client')->user();

        // Get the task with project relationship
        $task = \App\Models\ProjectTask::with('project')->findOrFail($taskId);

        // Verify access to the project
        if ($task->project->client_id !== $client->id &&
            !$task->project->members()->where('client_id', $client->id)->exists()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Find and toggle the checklist item
        $checklist = \App\Models\ProjectTaskChecklist::where('project_task_id', $taskId)
            ->where('id', $checklistId)
            ->firstOrFail();

        $checklist->is_completed = !$checklist->is_completed;
        $checklist->save();

        return response()->json([
            'success' => true,
            'is_completed' => $checklist->is_completed
        ]);
    }

    public function deleteTaskFile(Project $project, $taskId, $fileId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        if ($project->client_id !== $client->id &&
            !$project->members()->where('client_id', $client->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'Access denied'], 403);
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $file = $task->files()->find($fileId);
        if (!$file) {
            return response()->json(['success' => false, 'message' => 'File not found'], 404);
        }

        // Delete file from storage
        \Storage::disk('public')->delete($file->file_path);

        // Delete database record
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    // ============ COMMENT REPLIES ============

    public function storeCommentReply(Request $request, Project $project, $taskId, $commentId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $comment = $task->comments()->find($commentId);
        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
        }

        $validated = $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        // Use system employee for client replies
        $systemEmployee = \App\Models\Employee::first();
        if (!$systemEmployee) {
            return response()->json(['success' => false, 'message' => 'System configuration error'], 500);
        }

        $reply = $comment->replies()->create([
            'employee_id' => $systemEmployee->id,
            'reply' => $validated['reply'],
            'replied_by_client' => true,
            'client_name' => $client->name ?? 'Client',
        ]);

        // Broadcast the reply event
        event(new \App\Events\TaskCommentAdded($comment));

        return response()->json([
            'success' => true,
            'message' => 'Reply added successfully',
            'reply' => [
                'id' => $reply->id,
                'reply' => $reply->reply,
                'created_at' => $reply->created_at->diffForHumans(),
                'created_at_iso' => $reply->created_at->toISOString(),
                'employee' => [
                    'id' => $systemEmployee->id,
                    'first_name' => $client->name ?? 'Client',
                    'last_name' => '',
                ],
                'is_client' => true,
            ]
        ]);
    }

    // ============ COMMENT REACTIONS ============

    public function toggleCommentReaction(Request $request, Project $project, $taskId, $commentId)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $task = $project->tasks()->find($taskId);
        if (!$task) {
            return response()->json(['success' => false, 'message' => 'Task not found'], 404);
        }

        $comment = $task->comments()->find($commentId);
        if (!$comment) {
            return response()->json(['success' => false, 'message' => 'Comment not found'], 404);
        }

        $validated = $request->validate([
            'reaction_type' => 'required|in:like,love,laugh,wow,sad,angry',
        ]);

        // Use system employee for client reactions
        $systemEmployee = \App\Models\Employee::first();
        if (!$systemEmployee) {
            return response()->json(['success' => false, 'message' => 'System configuration error'], 500);
        }

        // Check if reaction already exists (using client identifier)
        $reaction = $comment->reactions()
            ->where('employee_id', $systemEmployee->id)
            ->where('reaction_type', $validated['reaction_type'])
            ->where('reacted_by_client_id', $client->id)
            ->first();

        if ($reaction) {
            // Remove reaction if it exists
            $reaction->delete();
            $action = 'removed';
        } else {
            // Add reaction
            $reaction = $comment->reactions()->create([
                'employee_id' => $systemEmployee->id,
                'reaction_type' => $validated['reaction_type'],
                'reacted_by_client' => true,
                'reacted_by_client_id' => $client->id,
            ]);
            $action = 'added';
        }

        // Get updated reaction counts
        $reactionCounts = $comment->reactions()
            ->selectRaw('reaction_type, COUNT(*) as count')
            ->groupBy('reaction_type')
            ->pluck('count', 'reaction_type')
            ->toArray();

        // Broadcast the reaction update
        event(new \App\Events\TaskCommentAdded($comment));

        return response()->json([
            'success' => true,
            'message' => "Reaction {$action} successfully",
            'action' => $action,
            'reaction_type' => $validated['reaction_type'],
            'reaction_counts' => $reactionCounts,
        ]);
    }

    // ============ MENTION AUTOCOMPLETE ============

    public function getEmployeesForMention(Request $request, Project $project)
    {
        $client = Auth::guard('client')->user();

        // Verify access
        $teamMember = ClientTeamMember::where('team_member_client_id', $client->id)->first();

        if ($teamMember) {
            $isMember = $project->members()->where('client_team_member_id', $teamMember->id)->exists();
            if (!$isMember) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        } else {
            if ($project->client_id !== $client->id) {
                return response()->json(['success' => false, 'message' => 'Access denied'], 403);
            }
        }

        $search = $request->get('search', '');

        // Get all project members (employees)
        $employees = $project->members()
            ->with('employee')
            ->get()
            ->pluck('employee')
            ->filter() // Remove nulls
            ->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $employee->email,
                ];
            });

        // Filter by search term if provided
        if ($search) {
            $employees = $employees->filter(function($employee) use ($search) {
                return stripos($employee['name'], $search) !== false ||
                       stripos($employee['email'], $search) !== false;
            });
        }

        return response()->json([
            'success' => true,
            'employees' => $employees->values()->take(10) // Limit to 10 results
        ]);
    }
}
