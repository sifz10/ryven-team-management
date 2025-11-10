<?php

namespace App\Http\Controllers;

use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\ApplicationAnswer;
use App\Models\ApplicationTest;
use App\Services\AIScreeningService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PublicJobController extends Controller
{
    protected $aiScreeningService;

    public function __construct(AIScreeningService $aiScreeningService)
    {
        $this->aiScreeningService = $aiScreeningService;
    }

    /**
     * Display list of published jobs
     */
    public function index(Request $request)
    {
        $query = JobPost::published()
            ->with('creator')
            ->withCount('applications');

        // Filter by job type
        if ($request->filled('job_type')) {
            $query->where('job_type', $request->job_type);
        }

        // Filter by location
        if ($request->filled('location')) {
            $query->where('location', 'like', '%' . $request->location . '%');
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest()->paginate(12);

        return view('jobs.index', compact('jobs'));
    }

    /**
     * Show job details and application form
     */
    public function show($slug)
    {
        $job = JobPost::where('slug', $slug)
            ->with('questions')
            ->firstOrFail();

        if (!$job->canAcceptApplications()) {
            return view('jobs.closed', compact('job'));
        }

        return view('jobs.show', compact('job'));
    }

    /**
     * Submit job application
     */
    public function apply(Request $request, $slug)
    {
        $job = JobPost::where('slug', $slug)->firstOrFail();

        if (!$job->canAcceptApplications()) {
            return back()->with('error', 'This job is no longer accepting applications.');
        }

        // Validate application
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'linkedin_url' => 'nullable|url|max:255',
            'portfolio_url' => 'nullable|url|max:255',
            'cover_letter' => 'nullable|string|max:5000',
            'resume' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
            'answers' => 'nullable|array',
            'answers.*.answer_text' => 'nullable|string',
            'answers.*.answer_file' => 'nullable|file|max:102400', // 100MB for videos
        ]);

        // Validate required questions
        foreach ($job->questions as $question) {
            if ($question->is_required) {
                $answer = $request->input("answers.{$question->id}.answer_text");
                $answerFile = $request->file("answers.{$question->id}.answer_file");

                if (empty($answer) && !$answerFile) {
                    $validator->after(function ($validator) use ($question) {
                        $validator->errors()->add("answers.{$question->id}", "The question '{$question->question}' is required.");
                    });
                }
            }
        }

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check for duplicate application
        $exists = JobApplication::where('job_post_id', $job->id)
            ->where('email', $request->email)
            ->exists();

        if ($exists) {
            return back()->with('error', 'You have already applied for this position.');
        }

        // Upload resume
        $resumePath = $request->file('resume')->store('resumes', 'public');
        $resumeOriginalName = $request->file('resume')->getClientOriginalName();

        // Create application
        $application = JobApplication::create([
            'job_post_id' => $job->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'linkedin_url' => $request->linkedin_url,
            'portfolio_url' => $request->portfolio_url,
            'cover_letter' => $request->cover_letter,
            'resume_path' => $resumePath,
            'resume_original_name' => $resumeOriginalName,
            'ai_status' => 'pending',
            'application_status' => 'new',
        ]);

        // Save answers
        if ($request->has('answers')) {
            foreach ($request->input('answers', []) as $questionId => $answerData) {
                $answerFilePath = null;
                $answerFileType = null;

                // Handle file upload (video/document)
                if ($request->hasFile("answers.{$questionId}.answer_file")) {
                    $file = $request->file("answers.{$questionId}.answer_file");
                    $answerFilePath = $file->store('application_answers', 'public');
                    $answerFileType = $file->getMimeType();
                }

                ApplicationAnswer::create([
                    'job_application_id' => $application->id,
                    'job_question_id' => $questionId,
                    'answer_text' => $answerData['answer_text'] ?? null,
                    'answer_file_path' => $answerFilePath,
                    'answer_file_type' => $answerFileType,
                ]);
            }
        }

        // Run AI screening if enabled
        if ($job->ai_screening_enabled) {
            try {
                $this->aiScreeningService->screenApplication($application);
            } catch (\Exception $e) {
                // Log error but don't fail the application
                \Log::error('AI Screening failed for application ' . $application->id . ': ' . $e->getMessage());
            }
        }

        return redirect()->route('jobs.success', $application->id)
            ->with('success', 'Application submitted successfully!');
    }

    /**
     * Application success page
     */
    public function success($applicationId)
    {
        $application = JobApplication::with('jobPost')->findOrFail($applicationId);

        return view('jobs.success', compact('application'));
    }

    /**
     * View test for candidate
     */
    public function viewTest($testId, Request $request)
    {
        $test = ApplicationTest::with('application.jobPost')->findOrFail($testId);

        // Simple security check - require email parameter
        if ($request->email !== $test->application->email) {
            abort(403, 'Unauthorized access');
        }

        return view('jobs.test', compact('test'));
    }

    /**
     * Submit test
     */
    public function submitTest(Request $request, $testId)
    {
        $test = ApplicationTest::with('application')->findOrFail($testId);

        // Verify email
        if ($request->email !== $test->application->email) {
            abort(403, 'Unauthorized access');
        }

        if ($test->status !== 'sent') {
            return back()->with('error', 'This test has already been submitted.');
        }

        $validated = $request->validate([
            'submission_file' => 'required|file|max:51200', // 50MB
        ]);

        $submissionPath = $request->file('submission_file')->store('test_submissions', 'public');
        $submissionOriginalName = $request->file('submission_file')->getClientOriginalName();

        $test->update([
            'submission_file_path' => $submissionPath,
            'submission_original_name' => $submissionOriginalName,
            'status' => 'submitted',
            'submitted_at' => now(),
        ]);

        return view('jobs.test-submitted', compact('test'));
    }
}
