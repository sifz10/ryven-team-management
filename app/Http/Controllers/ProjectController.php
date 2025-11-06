<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\DailyWorkSubmission;
use App\Mail\ProjectUpdateMail;
use App\Services\SmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProjectController extends Controller
{
    // Display list of all projects
    public function index()
    {
        $projects = Project::latest()->paginate(20);
        return view('projects.index', compact('projects'));
    }

    // Show form to create new project
    public function create()
    {
        return view('projects.create');
    }

    // Store new project
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on-hold,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_company' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'priority' => 'required|integer|min:1|max:4',
        ]);

        Project::create($validated);

        return redirect()->route('projects.index')->with('status', 'Project created successfully!');
    }

    // Show single project
    public function show(Project $project)
    {
        $project->load(['workSubmissions.employee', 'workSubmissions.dailyChecklist']);
        return view('projects.show', compact('project'));
    }

    // Show form to edit project
    public function edit(Project $project)
    {
        return view('projects.edit', compact('project'));
    }

    // Update project
    public function update(Request $request, Project $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,on-hold,cancelled',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'client_name' => 'nullable|string|max:255',
            'client_email' => 'nullable|email|max:255',
            'client_phone' => 'nullable|string|max:50',
            'client_company' => 'nullable|string|max:255',
            'client_address' => 'nullable|string',
            'budget' => 'nullable|numeric|min:0',
            'currency' => 'required|string|max:10',
            'priority' => 'required|integer|min:1|max:4',
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('status', 'Project updated successfully!');
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
}
