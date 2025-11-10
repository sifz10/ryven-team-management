<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobApplication;
use App\Models\JobPost;
use App\Models\TalentPool;
use App\Models\ApplicationTest;
use App\Services\AIScreeningService;
use App\Mail\InterviewInvitationMail;
use App\Mail\TestAssignmentMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class JobApplicationController extends Controller
{
    protected $aiScreeningService;

    public function __construct(AIScreeningService $aiScreeningService)
    {
        $this->aiScreeningService = $aiScreeningService;
    }

    public function index(Request $request)
    {
        $query = JobApplication::with(['jobPost', 'reviewer'])
            ->latest();

        // Filter by job post
        if ($request->filled('job_post_id')) {
            $query->where('job_post_id', $request->job_post_id);
        }

        // Filter by AI status
        if ($request->filled('ai_status')) {
            $query->where('ai_status', $request->ai_status);
        }

        // Filter by application status
        if ($request->filled('application_status')) {
            $query->where('application_status', $request->application_status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->paginate(20);
        $jobPosts = JobPost::active()->latest()->get();

        return view('admin.applications.index', compact('applications', 'jobPosts'));
    }

    public function show(JobApplication $application)
    {
        $application->load(['jobPost', 'answers.question', 'tests', 'reviewer', 'talentPoolEntry']);

        return view('admin.applications.show', compact('application'));
    }

    public function updateStatus(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'application_status' => 'required|in:new,screening,interview,test_sent,rejected,hired',
            'admin_notes' => 'nullable|string',
        ]);

        $employee = Auth::user()->employee;

        $application->update([
            'application_status' => $validated['application_status'],
            'admin_notes' => $validated['admin_notes'] ?? $application->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => $employee ? $employee->id : null,
        ]);

        return back()->with('success', 'Application status updated successfully!');
    }

    public function runAIScreening(JobApplication $application)
    {
        if (!$application->jobPost->ai_screening_enabled) {
            return back()->with('error', 'AI screening is not enabled for this job post.');
        }

        $this->aiScreeningService->screenApplication($application);

        return back()->with('success', 'AI screening completed successfully!');
    }

    public function batchAIScreening(Request $request)
    {
        $validated = $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:job_applications,id',
        ]);

        $this->aiScreeningService->batchScreen($validated['application_ids']);

        return back()->with('success', 'Batch AI screening completed!');
    }

    public function addToTalentPool(JobApplication $application)
    {
        if ($application->added_to_talent_pool) {
            return back()->with('error', 'Already added to talent pool.');
        }

        // Get the authenticated user's employee ID
        $employee = Auth::user()->employee;
        if (!$employee) {
            return back()->with('error', 'You must be linked to an employee record to add talents to the pool.');
        }

        TalentPool::create([
            'job_application_id' => $application->id,
            'first_name' => $application->first_name,
            'last_name' => $application->last_name,
            'email' => $application->email,
            'phone' => $application->phone,
            'linkedin_url' => $application->linkedin_url,
            'portfolio_url' => $application->portfolio_url,
            'skills' => $application->ai_analysis['strengths'] ?? [],
            'experience_level' => $application->jobPost->experience_level ?? 'mid',
            'resume_path' => $application->resume_path,
            'source' => 'job_application',
            'added_by' => $employee->id,
        ]);

        $application->update(['added_to_talent_pool' => true]);

        return back()->with('success', 'Added to talent pool successfully!');
    }

    public function sendInterview(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'interview_message' => 'required|string',
            'interview_date' => 'nullable|date',
            'interview_time' => 'nullable|string',
            'interview_location' => 'nullable|string',
        ]);

        // Send email
        try {
            Mail::to($application->email)->send(
                new InterviewInvitationMail($application, $validated)
            );

            $application->update([
                'application_status' => 'interview',
                'admin_notes' => ($application->admin_notes ?? '') . "\n\nInterview invitation sent on " . now()->format('Y-m-d H:i'),
            ]);

            return back()->with('success', 'Interview invitation sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send interview invitation: ' . $e->getMessage());
        }
    }

    public function sendTest(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'test_title' => 'required|string|max:255',
            'test_description' => 'nullable|string',
            'test_instructions' => 'nullable|string',
            'test_file' => 'nullable|file|max:10240', // 10MB
            'deadline' => 'nullable|date',
        ]);

        $testFilePath = null;
        if ($request->hasFile('test_file')) {
            $testFilePath = $request->file('test_file')->store('tests', 'public');
        }

        $employee = Auth::user()->employee;

        $test = ApplicationTest::create([
            'job_application_id' => $application->id,
            'test_title' => $validated['test_title'],
            'test_description' => $validated['test_description'] ?? null,
            'test_instructions' => $validated['test_instructions'] ?? null,
            'test_file_path' => $testFilePath,
            'deadline' => $validated['deadline'] ?? null,
            'status' => 'sent',
            'sent_at' => now(),
            'sent_by' => $employee ? $employee->id : null,
        ]);

        // Send email
        try {
            Mail::to($application->email)->send(
                new TestAssignmentMail($application, $test)
            );

            $application->update([
                'application_status' => 'test_sent',
            ]);

            return back()->with('success', 'Test sent successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send test: ' . $e->getMessage());
        }
    }

    public function retryAIScreening(JobApplication $application)
    {
        try {
            // Reset AI status
            $application->update([
                'ai_status' => 'pending',
                'ai_match_score' => null,
                'ai_analysis' => null,
                'resume_text' => null, // Force re-extraction
            ]);

            // Retry screening
            $result = $this->aiScreeningService->screenApplication($application);

            if ($result['status'] === 'pending' && isset($result['details']['error'])) {
                return back()->with('warning', 'AI screening failed again: ' . $result['details']['error']);
            }

            return back()->with('success', 'AI screening completed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to retry AI screening: ' . $e->getMessage());
        }
    }

    public function viewResume(JobApplication $application)
    {
        if (!Storage::exists($application->resume_path)) {
            abort(404, 'Resume file not found.');
        }

        $file = Storage::get($application->resume_path);
        $mimeType = Storage::mimeType($application->resume_path);

        return response($file, 200)
            ->header('Content-Type', $mimeType)
            ->header('Content-Disposition', 'inline; filename="' . $application->resume_original_name . '"');
    }

    public function downloadResume(JobApplication $application)
    {
        if (!Storage::exists($application->resume_path)) {
            return back()->with('error', 'Resume file not found.');
        }

        return Storage::download($application->resume_path, $application->resume_original_name);
    }

    public function destroy(JobApplication $application)
    {
        $jobPostId = $application->job_post_id;

        // Delete resume file if exists
        if ($application->resume_path && Storage::exists($application->resume_path)) {
            Storage::delete($application->resume_path);
        }

        // Delete answer video/file attachments if they exist
        foreach ($application->answers as $answer) {
            if ($answer->video_path && Storage::exists($answer->video_path)) {
                Storage::delete($answer->video_path);
            }
            if ($answer->file_path && Storage::exists($answer->file_path)) {
                Storage::delete($answer->file_path);
            }
        }

        // Delete test files if they exist
        foreach ($application->tests as $test) {
            if ($test->test_file_path && Storage::exists($test->test_file_path)) {
                Storage::delete($test->test_file_path);
            }
            if ($test->submission_file_path && Storage::exists($test->submission_file_path)) {
                Storage::delete($test->submission_file_path);
            }
        }

        // Delete related talent pool entry if exists
        if ($application->added_to_talent_pool) {
            TalentPool::where('job_application_id', $application->id)->delete();
        }

        // Store applicant name for success message
        $applicantName = $application->first_name . ' ' . $application->last_name;

        // Delete the application (cascade will delete answers and tests)
        $application->delete();

        return redirect()->route('admin.applications.index', ['job_post' => $jobPostId])
            ->with('success', "Application from {$applicantName} has been permanently deleted.");
    }
}
