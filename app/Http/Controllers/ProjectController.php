<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Client;
use App\Models\Employee;
use App\Models\ProjectMember;
use App\Models\ProjectTask;
use App\Models\ProjectTaskChecklist;
use App\Models\ProjectTaskTag;
use App\Models\ProjectTaskFile;
use App\Models\ProjectTaskComment;
use App\Models\ProjectTaskReminder;
use App\Models\ProjectFile;
use App\Models\ProjectDiscussion;
use App\Models\ProjectExpense;
use App\Models\ProjectTicket;
use App\Models\DailyWorkSubmission;
use App\Mail\ProjectUpdateMail;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ProjectController extends Controller
{
    // Display list of all projects
    public function index()
    {
        $projects = Project::with('client')->withCount(['tasks', 'members'])->latest()->paginate(20);
        return view('projects.index-new', compact('projects'));
    }

    // Show form to create new project
    public function create()
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $employees = Employee::whereNull('discontinued_at')->orderBy('first_name')->get();
        return view('projects.create', compact('clients', 'employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,active,in_progress,on_hold,completed,cancelled',
            'progress' => 'nullable|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_manager' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'priority' => 'required|integer|min:1|max:4',
        ]);

        $project = Project::create($validated);

        // Add team members if provided
        if ($request->has('team_members')) {
            foreach ($request->team_members as $memberId) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'employee_id' => $memberId,
                    'member_type' => 'internal',
                ]);
            }
        }

        return redirect()->route('projects.show', $project)->with('status', 'Project created successfully!');
    }

    // Show single project
    public function show(Project $project, Request $request)
    {
        $tab = $request->get('tab', 'overview');
        $subTab = $request->get('sub_tab', null);

        $project->load([
            'client',
            'members.employee',
            'tasks' => function($query) {
                $query->with(['checklists', 'tags', 'assignedTo', 'files.uploader', 'comments.employee'])->orderBy('order')->orderBy('created_at', 'desc');
            },
            'files' => function($query) {
                $query->latest();
            },
            'discussions' => function($query) {
                $query->whereNull('parent_id')->with('replies.user', 'user')->latest();
            },
            'expenses' => function($query) {
                $query->latest();
            },
            'tickets' => function($query) {
                $query->latest();
            }
        ]);

        $employees = Employee::whereNull('discontinued_at')->orderBy('first_name')->get();

        return view('projects.show-new', compact('project', 'tab', 'subTab', 'employees'));
    }

    // Show form to edit project
    public function edit(Project $project)
    {
        $clients = Client::where('status', 'active')->orderBy('name')->get();
        $employees = Employee::whereNull('discontinued_at')->orderBy('first_name')->get();
        $project->load('members');
        return view('projects.edit', compact('project', 'clients', 'employees'));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:planning,active,in_progress,on_hold,completed,cancelled',
            'progress' => 'nullable|integer|min:0|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'project_manager' => 'nullable|string|max:255',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'priority' => 'required|integer|min:1|max:4',
        ]);

        $project->update($validated);

        // Sync team members
        if ($request->has('team_members')) {
            // Remove existing internal members
            $project->members()->where('member_type', 'internal')->delete();

            // Add new members
            foreach ($request->team_members as $memberId) {
                ProjectMember::create([
                    'project_id' => $project->id,
                    'employee_id' => $memberId,
                    'member_type' => 'internal',
                ]);
            }
        } else {
            // If no members selected, remove all internal members
            $project->members()->where('member_type', 'internal')->delete();
        }

        return redirect()->route('projects.show', $project)->with('status', 'Project updated successfully!');
    }

    // Delete project
    public function destroy(Project $project)
    {
        $project->delete();
        return redirect()->route('projects.index')->with('status', 'Project deleted successfully!');
    }

    // Show today's work for a project
    public function todayWork(Project $project, Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $workSubmissions = $project->workSubmissions()
            ->with(['employee', 'dailyChecklist'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('projects.today-work', compact('project', 'workSubmissions', 'date'));
    }

    // Update work submission
    public function updateWorkSubmission(Request $request, Project $project, DailyWorkSubmission $submission)
    {
        $validated = $request->validate([
            'work_description' => 'required|string|max:1000',
        ]);

        $submission->update($validated);

        return redirect()->back()->with('status', 'Work entry updated successfully!');
    }

    // Delete work submission
    public function deleteWorkSubmission(Project $project, DailyWorkSubmission $submission)
    {
        $submission->delete();
        return redirect()->back()->with('status', 'Work entry deleted successfully!');
    }

    // Send report to client (Email & SMS)
    public function sendReport(Request $request, Project $project)
    {
        $date = $request->get('date', now()->toDateString());

        $workSubmissions = $project->workSubmissions()
            ->with(['employee'])
            ->whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $dateFormatted = Carbon::parse($date)->format('F d, Y');
        $emailSent = false;
        $smsSent = false;

        // Send Email
        if ($project->client_email) {
            try {
                Mail::to($project->client_email)->send(
                    new ProjectUpdateMail($project, $workSubmissions, $dateFormatted)
                );
                $emailSent = true;
            } catch (\Exception $e) {
                Log::error('Failed to send project update email: ' . $e->getMessage());
            }
        }

        // Send SMS
        if ($project->client_phone) {
            $smsService = new SmsService();
            $smsMessage = "Daily Update for {$project->name} ({$dateFormatted}): ";
            $smsMessage .= $workSubmissions->count() . " update(s) from your team. ";
            $smsMessage .= "Check your email for details. - Ryven";

            $smsSent = $smsService->send($project->client_phone, $smsMessage);
        }

        // Update last report sent timestamp
        $project->update(['last_report_sent_at' => now()]);

        $message = '';
        if ($emailSent && $smsSent) {
            $message = 'Report sent successfully via Email and SMS!';
        } elseif ($emailSent) {
            $message = 'Report sent successfully via Email!';
        } elseif ($smsSent) {
            $message = 'Report sent successfully via SMS!';
        } else {
            $message = 'No client contact information available. Report not sent.';
        }

        return redirect()->back()->with('status', $message);
    }

    // View all projects with today's work summary
    public function todaySummary()
    {
        $today = now()->toDateString();

        $projects = Project::where('status', 'active')
            ->withCount(['workSubmissions' => function($query) use ($today) {
                $query->whereDate('created_at', $today);
            }])
            ->orderByDesc('work_submissions_count')
            ->get();

        return view('projects.today-summary', compact('projects', 'today'));
    }

    // ============ PROJECT TASKS ============

    public function storeTasks(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,on-hold,in-progress,awaiting-feedback,staging,live,completed',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:0',
            'checklist' => 'nullable|array',
            'checklist.*.title' => 'required|string|max:255',
            'tags' => 'nullable|array',
            'tags.*' => 'required|string|max:50',
        ]);

        $validated['project_id'] = $project->id;
        $validated['created_by'] = null; // Set to null since we're using User auth, not Employee

        $task = ProjectTask::create(collect($validated)->except(['checklist', 'tags'])->toArray());

        // Handle checklist items
        if (!empty($validated['checklist'])) {
            foreach ($validated['checklist'] as $index => $checklistItem) {
                ProjectTaskChecklist::create([
                    'project_task_id' => $task->id,
                    'title' => $checklistItem['title'],
                    'is_completed' => false,
                    'order' => $index,
                ]);
            }
        }

        // Handle tags
        if (!empty($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagName) {
                $tag = ProjectTaskTag::firstOrCreate(
                    ['name' => trim($tagName)],
                    ['color' => $this->getRandomTagColor()]
                );
                $tagIds[] = $tag->id;
            }
            $task->tags()->sync($tagIds);
        }

        $subTab = $request->input('current_view', 'list');
        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'tasks', 'sub_tab' => $subTab])->with('status', 'Task created successfully!');
    }

    public function updateTask(Request $request, Project $project, ProjectTask $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,on-hold,in-progress,awaiting-feedback,staging,live,completed',
            'priority' => 'required|in:low,medium,high,critical',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date' => 'nullable|date',
            'estimated_hours' => 'nullable|integer|min:0',
            'actual_hours' => 'nullable|integer|min:0',
            'checklist' => 'nullable|array',
            'checklist.*.id' => 'nullable|exists:project_task_checklists,id',
            'checklist.*.title' => 'required|string|max:255',
            'checklist.*.is_completed' => 'nullable|boolean',
            'tags' => 'nullable|array',
            'tags.*' => 'required|string|max:50',
        ]);

        $task->update(collect($validated)->except(['checklist', 'tags'])->toArray());

        // Handle checklist items
        if (isset($validated['checklist'])) {
            $existingIds = [];
            foreach ($validated['checklist'] as $index => $checklistItem) {
                if (!empty($checklistItem['id'])) {
                    // Update existing
                    $checklist = ProjectTaskChecklist::find($checklistItem['id']);
                    if ($checklist && $checklist->project_task_id == $task->id) {
                        $checklist->update([
                            'title' => $checklistItem['title'],
                            'is_completed' => $checklistItem['is_completed'] ?? false,
                            'order' => $index,
                        ]);
                        $existingIds[] = $checklist->id;
                    }
                } else {
                    // Create new
                    $newChecklist = ProjectTaskChecklist::create([
                        'project_task_id' => $task->id,
                        'title' => $checklistItem['title'],
                        'is_completed' => $checklistItem['is_completed'] ?? false,
                        'order' => $index,
                    ]);
                    $existingIds[] = $newChecklist->id;
                }
            }
            // Delete removed items
            $task->checklists()->whereNotIn('id', $existingIds)->delete();
        }

        // Handle tags
        if (isset($validated['tags'])) {
            $tagIds = [];
            foreach ($validated['tags'] as $tagName) {
                $tag = ProjectTaskTag::firstOrCreate(
                    ['name' => trim($tagName)],
                    ['color' => $this->getRandomTagColor()]
                );
                $tagIds[] = $tag->id;
            }
            $task->tags()->sync($tagIds);
        }

        $subTab = $request->input('current_view', 'list');
        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'tasks', 'sub_tab' => $subTab])->with('status', 'Task updated successfully!');
    }

    public function destroyTask(Request $request, Project $project, ProjectTask $task)
    {
        $task->delete();
        $subTab = $request->input('current_view', 'list');
        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'tasks', 'sub_tab' => $subTab])->with('status', 'Task deleted successfully!');
    }

    public function updateTaskOrder(Request $request, Project $project)
    {
        $tasks = $request->input('tasks', []);

        foreach ($tasks as $index => $taskId) {
            ProjectTask::where('id', $taskId)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    // Get employees for @mention autocomplete
    public function getEmployeesForMention(Request $request, Project $project)
    {
        $search = $request->get('search', '');

        // Get all active employees
        $employees = Employee::where('is_active', true)
            ->when($search, function($query) use ($search) {
                $query->where(function($q) use ($search) {
                    $q->whereRaw('LOWER(first_name) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(last_name) LIKE ?', ['%' . strtolower($search) . '%'])
                      ->orWhereRaw('LOWER(email) LIKE ?', ['%' . strtolower($search) . '%']);
                });
            })
            ->select('id', 'first_name', 'last_name', 'email')
            ->limit(10)
            ->get()
            ->map(function($employee) {
                return [
                    'id' => $employee->id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                    'email' => $employee->email,
                    'mention' => strtolower(str_replace(' ', '', $employee->first_name . $employee->last_name)),
                ];
            });

        return response()->json([
            'success' => true,
            'employees' => $employees,
        ]);
    }

    public function moveTask(Request $request, Project $project, ProjectTask $task)
    {
        $validated = $request->validate([
            'status' => 'required|in:todo,on-hold,in-progress,awaiting-feedback,staging,live,completed',
            'order' => 'required|integer|min:0',
            'task_ids' => 'required|array',
        ]);

        // Update the task status
        $task->update([
            'status' => $validated['status'],
        ]);

        // Update order for all tasks in the column
        foreach ($validated['task_ids'] as $index => $taskId) {
            ProjectTask::where('id', $taskId)
                ->where('project_id', $project->id)
                ->update(['order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Task moved successfully'
        ]);
    }

    public function updateChecklist(Request $request, Project $project, ProjectTask $task)
    {
        $validated = $request->validate([
            'checklist' => 'required|array',
            'checklist.*.id' => 'required|exists:project_task_checklists,id',
            'checklist.*.is_completed' => 'required|boolean',
        ]);

        foreach ($validated['checklist'] as $item) {
            ProjectTaskChecklist::where('id', $item['id'])
                ->where('project_task_id', $task->id)
                ->update(['is_completed' => $item['is_completed']]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Checklist progress saved successfully'
        ]);
    }

    // ============ TASK FILES ============

    public function uploadTaskFile(Request $request, Project $project, ProjectTask $task)
    {
        try {
            // Check if file was uploaded
            if (!$request->hasFile('file')) {
                // Check if this is because the file was too large for PHP settings
                $contentLength = $request->header('Content-Length');
                $maxPostSize = $this->returnBytes(ini_get('post_max_size'));

                if ($contentLength && $contentLength > $maxPostSize) {
                    return response()->json([
                        'success' => false,
                        'message' => 'File is too large for server configuration. Current PHP limit is ' . ini_get('upload_max_filesize') . '. Please update php.ini or choose a smaller file.',
                    ], 413); // 413 Payload Too Large
                }

                return response()->json([
                    'success' => false,
                    'message' => 'No file was uploaded. Please try again.',
                ], 400);
            }

            // Check if file upload was successful
            if (!$request->file('file')->isValid()) {
                return response()->json([
                    'success' => false,
                    'message' => 'File upload failed. The file may be too large (max 100MB allowed by server).',
                ], 400);
            }

            $request->validate([
                'file' => 'required|file|max:102400', // 100MB max
            ], [
                'file.max' => 'The file size must not exceed 100MB.'
            ]);

            // Get the employee record for the authenticated user
            $employee = Employee::where('user_id', auth()->id())->first();

            if (!$employee) {
                return response()->json([
                    'success' => false,
                    'message' => 'No employee record found for this user',
                ], 403);
            }

            $file = $request->file('file');
            $originalName = $file->getClientOriginalName();
            $fileName = time() . '_' . str_replace(' ', '_', $originalName);
            $filePath = $file->storeAs('task-files', $fileName, 'public');

            $taskFile = $task->files()->create([
                'uploaded_by' => $employee->id,
                'original_name' => $originalName,
                'file_path' => $filePath,
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
            ]);

            $taskFile->load('uploader');

            return response()->json([
                'success' => true,
                'message' => 'File uploaded successfully',
                'file' => [
                    'id' => $taskFile->id,
                    'original_name' => $taskFile->original_name,
                    'file_size' => $taskFile->file_size_formatted,
                    'file_type' => $taskFile->file_type,
                    'created_at' => $taskFile->created_at->diffForHumans(),
                    'uploader' => [
                        'first_name' => $taskFile->uploader->first_name,
                        'last_name' => $taskFile->uploader->last_name,
                    ],
                    'download_url' => route('projects.tasks.files.download', [$project, $task, $taskFile]),
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['file'] ?? ['Invalid file']),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('File upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload file: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Convert PHP size shorthand notation to bytes
     */
    private function returnBytes($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        $val = (int) $val;

        switch($last) {
            case 'g':
                $val *= 1024;
            case 'm':
                $val *= 1024;
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    public function downloadTaskFile(Project $project, ProjectTask $task, ProjectTaskFile $file)
    {
        return response()->download(storage_path('app/public/' . $file->file_path), $file->original_name);
    }

    public function deleteTaskFile(Project $project, ProjectTask $task, ProjectTaskFile $file)
    {
        \Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return response()->json([
            'success' => true,
            'message' => 'File deleted successfully'
        ]);
    }

    // ============ TASK COMMENTS ============

    public function getTaskComments(Project $project, ProjectTask $task)
    {
        $comments = $task->comments()
            ->with(['employee', 'replies.employee', 'reactions.employee'])
            ->latest()
            ->get();

        return response()->json([
            'success' => true,
            'comments' => $comments->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'created_at' => $comment->created_at->diffForHumans(),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'created_at_iso' => $comment->created_at->toISOString(),
                    'employee' => [
                        'id' => $comment->employee->id,
                        'first_name' => $comment->employee->first_name,
                        'last_name' => $comment->employee->last_name,
                    ],
                    'replies' => $comment->replies->map(function ($reply) {
                        return [
                            'id' => $reply->id,
                            'reply' => $reply->reply,
                            'created_at' => $reply->created_at->diffForHumans(),
                            'created_at_human' => $reply->created_at->diffForHumans(),
                            'employee' => [
                                'id' => $reply->employee->id,
                                'first_name' => $reply->employee->first_name,
                                'last_name' => $reply->employee->last_name,
                            ],
                        ];
                    }),
                    'reactions' => $comment->reactions->map(function ($reaction) {
                        return [
                            'id' => $reaction->id,
                            'reaction_type' => $reaction->reaction_type,
                            'employee_id' => $reaction->employee_id,
                            'employee' => [
                                'id' => $reaction->employee->id,
                                'first_name' => $reaction->employee->first_name,
                                'last_name' => $reaction->employee->last_name,
                            ],
                        ];
                    }),
                ];
            })
        ]);
    }

    public function storeTaskComment(Request $request, Project $project, ProjectTask $task)
    {
        $validated = $request->validate([
            'comment' => 'required|string|max:2000',
        ]);

        // Get employee record for authenticated user
        $employee = Employee::where('user_id', auth()->id())->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'No employee record found for this user',
            ], 403);
        }

        $comment = $task->comments()->create([
            'employee_id' => $employee->id,
            'comment' => $validated['comment'],
        ]);

        $comment->load('employee');

        // Extract mentions from comment (e.g., @JohnDoe or @john.doe@example.com)
        preg_match_all('/@(\w+(?:\.\w+)?(?:@[\w.]+)?)/', $validated['comment'], $mentions);

        if (!empty($mentions[1])) {
            $currentEmployee = $employee;

            foreach ($mentions[1] as $mention) {
                // Try to find employee by email or name
                $mentionedEmployee = null;

                if (strpos($mention, '@') !== false) {
                    // It's an email
                    $mentionedEmployee = Employee::where('email', $mention)->first();
                } else {
                    // Try to find by first name, last name, or full name
                    $mentionedEmployee = Employee::where(function($query) use ($mention) {
                        $query->whereRaw('LOWER(first_name) = ?', [strtolower($mention)])
                              ->orWhereRaw('LOWER(last_name) = ?', [strtolower($mention)])
                              ->orWhereRaw('LOWER(CONCAT(first_name, last_name)) = ?', [strtolower(str_replace(' ', '', $mention))]);
                    })->first();
                }

                if ($mentionedEmployee && $mentionedEmployee->id !== $employee->id) {
                    $authorName = $currentEmployee ? $currentEmployee->first_name . ' ' . $currentEmployee->last_name : 'Someone';

                    // Create notification for mentioned user
                    \App\Models\Notification::create([
                        'user_id' => $mentionedEmployee->id,
                        'type' => 'task_mention',
                        'title' => 'You were mentioned in a comment',
                        'message' => $authorName . ' mentioned you in task: ' . $task->title,
                        'data' => json_encode([
                            'task_id' => $task->id,
                            'project_id' => $project->id,
                            'comment_id' => $comment->id,
                        ]),
                        'is_read' => false,
                    ]);

                    // Broadcast notification
                    event(new \App\Events\NewNotification($mentionedEmployee->id, [
                        'type' => 'task_mention',
                        'title' => 'You were mentioned in a comment',
                        'message' => $authorName . ' mentioned you in task: ' . $task->title,
                        'url' => route('projects.show', ['project' => $project->id, 'tab' => 'tasks']),
                    ]));
                }
            }
        }

        // Broadcast the comment event
        event(new \App\Events\TaskCommentAdded($comment));

        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully',
            'comment' => [
                'id' => $comment->id,
                'comment' => $comment->comment,
                'created_at' => $comment->created_at->diffForHumans(),
                'created_at_iso' => $comment->created_at->toISOString(),
                'employee' => [
                    'id' => $comment->employee->id,
                    'first_name' => $comment->employee->first_name,
                    'last_name' => $comment->employee->last_name,
                ],
            ]
        ]);
    }

    // ============ PROJECT FILES ============

    public function storeFile(Request $request, Project $project)
    {
        $validated = $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'description' => 'nullable|string',
            'category' => 'nullable|string|max:255',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        $file = $request->file('file');
        $path = $file->store('projects/' . $project->id . '/files', 'public');

        ProjectFile::create([
            'project_id' => $project->id,
            'name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'uploaded_by' => null, // Set to null since we're using User auth, not Employee
            'assigned_to' => $validated['assigned_to'] ?? null,
            'description' => $validated['description'] ?? null,
            'category' => $validated['category'] ?? null,
        ]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'files'])->with('status', 'File uploaded successfully!');
    }

    public function destroyFile(Project $project, ProjectFile $file)
    {
        Storage::disk('public')->delete($file->file_path);
        $file->delete();

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'files'])->with('status', 'File deleted successfully!');
    }

    // ============ PROJECT DISCUSSIONS ============

    public function storeDiscussion(Request $request, Project $project)
    {
        $validated = $request->validate([
            'message' => 'required|string',
            'parent_id' => 'nullable|exists:project_discussions,id',
            'mentions' => 'nullable|array',
            'attachments' => 'nullable|array',
        ]);

        $validated['project_id'] = $project->id;
        $validated['user_id'] = null; // Set to null since we're using User auth, not Employee

        ProjectDiscussion::create($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'discussion'])->with('status', 'Message posted successfully!');
    }

    public function togglePinDiscussion(Project $project, ProjectDiscussion $discussion)
    {
        $discussion->update(['is_pinned' => !$discussion->is_pinned]);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'discussion'])->with('status', $discussion->is_pinned ? 'Message pinned!' : 'Message unpinned!');
    }

    public function destroyDiscussion(Project $project, ProjectDiscussion $discussion)
    {
        $discussion->delete();
        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'discussion'])->with('status', 'Message deleted successfully!');
    }

    // ============ PROJECT EXPENSES ============

    public function storeExpense(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0',
            'currency' => 'required|string|max:10',
            'category' => 'required|in:hosting,software,hardware,service,other',
            'expense_date' => 'required|date',
            'receipt' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        if ($request->hasFile('receipt')) {
            $validated['receipt'] = $request->file('receipt')->store('projects/' . $project->id . '/receipts', 'public');
        }

        $validated['project_id'] = $project->id;
        $validated['recorded_by'] = null; // Set to null since we're using User auth, not Employee

        ProjectExpense::create($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'finance'])->with('status', 'Expense recorded successfully!');
    }

    public function updateExpense(Request $request, Project $project, ProjectExpense $expense)
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $expense->update($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'finance'])->with('status', 'Expense status updated!');
    }

    public function destroyExpense(Project $project, ProjectExpense $expense)
    {
        if ($expense->receipt) {
            Storage::disk('public')->delete($expense->receipt);
        }

        $expense->delete();
        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'finance'])->with('status', 'Expense deleted successfully!');
    }

    // ============ PROJECT TICKETS ============

    public function storeTicket(Request $request, Project $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in-progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'type' => 'required|in:bug,feature,enhancement,question',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        // Generate unique ticket number
        $latestTicket = ProjectTicket::latest('id')->first();
        $ticketNumber = 'TKT-' . str_pad(($latestTicket ? $latestTicket->id + 1 : 1), 6, '0', STR_PAD_LEFT);

        $validated['project_id'] = $project->id;
        $validated['ticket_number'] = $ticketNumber;
        $validated['reported_by'] = null; // Set to null since we're using User auth, not Employee

        ProjectTicket::create($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'tickets'])->with('status', 'Ticket created successfully!');
    }

    public function updateTicket(Request $request, Project $project, ProjectTicket $ticket)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'status' => 'required|in:open,in-progress,resolved,closed',
            'priority' => 'required|in:low,medium,high,critical',
            'type' => 'required|in:bug,feature,enhancement,question',
            'assigned_to' => 'nullable|exists:employees,id',
        ]);

        // Set resolved_at if status changed to resolved
        if ($validated['status'] === 'resolved' && $ticket->status !== 'resolved') {
            $validated['resolved_at'] = now();
        }

        $ticket->update($validated);

        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'tickets'])->with('status', 'Ticket updated successfully!');
    }

    public function destroyTicket(Project $project, ProjectTicket $ticket)
    {
        $ticket->delete();
        return redirect()->route('projects.show', ['project' => $project->id, 'tab' => 'tickets'])->with('status', 'Ticket deleted successfully!');
    }

    // ============ COMMENT REPLIES ============

    public function storeCommentReply(Request $request, Project $project, ProjectTask $task, ProjectTaskComment $comment)
    {
        $validated = $request->validate([
            'reply' => 'required|string|max:2000',
        ]);

        // Get employee record for authenticated user
        $employee = Employee::where('user_id', auth()->id())->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'No employee record found for this user',
            ], 403);
        }

        $reply = $comment->replies()->create([
            'employee_id' => $employee->id,
            'reply' => $validated['reply'],
        ]);

        $reply->load('employee');

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
                    'id' => $reply->employee->id,
                    'first_name' => $reply->employee->first_name,
                    'last_name' => $reply->employee->last_name,
                ],
            ]
        ]);
    }

    // ============ COMMENT REACTIONS ============

    public function toggleCommentReaction(Request $request, Project $project, ProjectTask $task, ProjectTaskComment $comment)
    {
        $validated = $request->validate([
            'reaction_type' => 'required|in:like,love,laugh,wow,sad,angry',
        ]);

        // Get employee record for authenticated user
        $employee = Employee::where('user_id', auth()->id())->first();

        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'No employee record found for this user',
            ], 403);
        }

        // Check if reaction already exists
        $reaction = $comment->reactions()
            ->where('employee_id', $employee->id)
            ->where('reaction_type', $validated['reaction_type'])
            ->first();

        if ($reaction) {
            // Remove reaction if it exists
            $reaction->delete();
            $action = 'removed';
        } else {
            // Add reaction
            $reaction = $comment->reactions()->create([
                'employee_id' => $employee->id,
                'reaction_type' => $validated['reaction_type'],
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

    /**
     * Get a random color for tags
     */
    private function getRandomTagColor(): string
    {
        $colors = ['blue', 'green', 'yellow', 'red', 'purple', 'pink', 'indigo', 'gray'];
        return $colors[array_rand($colors)];
    }

    // ============ TASK REMINDERS ============

    public function getTaskReminders(Project $project, ProjectTask $task)
    {
        $reminders = $task->reminders()
            ->with(['creator', 'task'])
            ->get();

        return response()->json([
            'success' => true,
            'reminders' => $reminders->map(function ($reminder) {
                $recipient = $reminder->recipient();
                $recipientName = $recipient
                    ? ($reminder->recipient_type === 'employee'
                        ? $recipient->first_name . ' ' . $recipient->last_name
                        : $recipient->name)
                    : 'Unknown';

                return [
                    'id' => $reminder->id,
                    'recipient_type' => $reminder->recipient_type,
                    'recipient_id' => $reminder->recipient_id,
                    'recipient_name' => $recipientName,
                    'remind_at' => $reminder->remind_at->format('Y-m-d H:i'),
                    'remind_at_human' => $reminder->remind_at->diffForHumans(),
                    'message' => $reminder->message,
                    'is_sent' => $reminder->is_sent,
                    'sent_at' => $reminder->sent_at?->diffForHumans(),
                    'created_by' => [
                        'id' => $reminder->creator->id,
                        'name' => $reminder->creator->first_name . ' ' . $reminder->creator->last_name,
                    ],
                    'created_at' => $reminder->created_at->diffForHumans(),
                ];
            }),
        ]);
    }

    public function storeTaskReminder(Request $request, Project $project, ProjectTask $task)
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 403);
        }

        $validated = $request->validate([
            'recipient_type' => 'required|in:employee,client',
            'recipient_id' => 'required|integer',
            'remind_at' => 'required|date|after:now',
            'message' => 'nullable|string|max:500',
        ]);

        // Verify recipient exists
        if ($validated['recipient_type'] === 'employee') {
            $recipient = Employee::find($validated['recipient_id']);
            if (!$recipient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }
        } elseif ($validated['recipient_type'] === 'client') {
            $recipient = \App\Models\UatUser::where('project_id', $project->id)
                ->where('id', $validated['recipient_id'])
                ->first();
            if (!$recipient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client user not found for this project'
                ], 404);
            }
        }

        $reminder = $task->reminders()->create([
            'created_by' => $employee->id,
            'recipient_type' => $validated['recipient_type'],
            'recipient_id' => $validated['recipient_id'],
            'remind_at' => $validated['remind_at'],
            'message' => $validated['message'],
        ]);

        $recipientName = $validated['recipient_type'] === 'employee'
            ? $recipient->first_name . ' ' . $recipient->last_name
            : $recipient->name;

        return response()->json([
            'success' => true,
            'message' => 'Reminder created successfully',
            'reminder' => [
                'id' => $reminder->id,
                'recipient_type' => $reminder->recipient_type,
                'recipient_id' => $reminder->recipient_id,
                'recipient_name' => $recipientName,
                'remind_at' => $reminder->remind_at->format('Y-m-d H:i'),
                'remind_at_human' => $reminder->remind_at->diffForHumans(),
                'message' => $reminder->message,
                'is_sent' => false,
                'created_by' => [
                    'id' => $employee->id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                ],
                'created_at' => $reminder->created_at->diffForHumans(),
            ],
        ]);
    }

    public function updateTaskReminder(Request $request, Project $project, ProjectTask $task, $reminderId)
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 403);
        }

        $reminder = $task->reminders()->findOrFail($reminderId);

        // Only creator can update
        if ($reminder->created_by !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only update your own reminders'
            ], 403);
        }

        // Can't update if already sent
        if ($reminder->is_sent) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot update a reminder that has already been sent'
            ], 400);
        }

        $validated = $request->validate([
            'recipient_type' => 'required|in:employee,client',
            'recipient_id' => 'required|integer',
            'remind_at' => 'required|date|after:now',
            'message' => 'nullable|string|max:500',
        ]);

        // Verify new recipient exists
        if ($validated['recipient_type'] === 'employee') {
            $recipient = Employee::find($validated['recipient_id']);
            if (!$recipient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Employee not found'
                ], 404);
            }
        } elseif ($validated['recipient_type'] === 'client') {
            $recipient = \App\Models\UatUser::where('project_id', $project->id)
                ->where('id', $validated['recipient_id'])
                ->first();
            if (!$recipient) {
                return response()->json([
                    'success' => false,
                    'message' => 'Client user not found for this project'
                ], 404);
            }
        }

        $reminder->update($validated);

        $recipientName = $validated['recipient_type'] === 'employee'
            ? $recipient->first_name . ' ' . $recipient->last_name
            : $recipient->name;

        return response()->json([
            'success' => true,
            'message' => 'Reminder updated successfully',
            'reminder' => [
                'id' => $reminder->id,
                'recipient_type' => $reminder->recipient_type,
                'recipient_id' => $reminder->recipient_id,
                'recipient_name' => $recipientName,
                'remind_at' => $reminder->remind_at->format('Y-m-d H:i'),
                'remind_at_human' => $reminder->remind_at->diffForHumans(),
                'message' => $reminder->message,
                'is_sent' => false,
                'created_by' => [
                    'id' => $employee->id,
                    'name' => $employee->first_name . ' ' . $employee->last_name,
                ],
                'created_at' => $reminder->created_at->diffForHumans(),
            ],
        ]);
    }

    public function destroyTaskReminder(Project $project, ProjectTask $task, $reminderId)
    {
        $employee = auth()->user()->employee;
        if (!$employee) {
            return response()->json([
                'success' => false,
                'message' => 'Employee record not found'
            ], 403);
        }

        $reminder = $task->reminders()->findOrFail($reminderId);

        // Only creator can delete
        if ($reminder->created_by !== $employee->id) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own reminders'
            ], 403);
        }

        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Reminder deleted successfully'
        ]);
    }

    public function getTaskRecipientsForReminders(Project $project, ProjectTask $task)
    {
        // Get all project members (employees)
        $employees = $project->members()
            ->with('employee')
            ->get()
            ->map(function ($member) {
                return [
                    'type' => 'employee',
                    'id' => $member->employee->id,
                    'name' => $member->employee->first_name . ' ' . $member->employee->last_name,
                    'email' => $member->employee->email,
                ];
            });

        // Get all client users from the project (UAT users)
        $clients = \App\Models\UatUser::where('project_id', $project->id)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'client',
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                ];
            });

        return response()->json([
            'success' => true,
            'recipients' => [
                'employees' => $employees,
                'clients' => $clients,
            ],
        ]);
    }
}


