<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobPost;
use App\Models\JobQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class JobPostController extends Controller
{
    public function index(Request $request)
    {
        $query = JobPost::with('creator', 'applications')
            ->withCount('applications');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%');
            });
        }

        $jobs = $query->latest()->paginate(15);

        return view('admin.jobs.index', compact('jobs'));
    }

    public function create()
    {
        return view('admin.jobs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'experience_level' => 'nullable|in:entry,mid,senior',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_currency' => 'nullable|string|max:10',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'contact_email' => 'nullable|email',
            'department' => 'nullable|string|max:255',
            'positions_available' => 'required|integer|min:1',
            'ai_screening_enabled' => 'boolean',
            'ai_screening_criteria' => 'nullable|string',
            'status' => 'required|in:draft,published,closed',
            'questions' => 'nullable|array',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:text,textarea,video,file,multiple_choice',
            'questions.*.options' => 'nullable|string',
            'questions.*.is_required' => 'nullable',
        ]);

        // Handle checkbox values
        $validated['ai_screening_enabled'] = $request->has('ai_screening_enabled');

        // Get employee ID - create if doesn't exist
        $user = Auth::user();
        if (!$user->employee) {
            // Split name into first and last name
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            // Create employee record for this user
            $employee = \App\Models\Employee::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email,
                'hired_at' => now(),
                'user_id' => $user->id,
            ]);
            $validated['created_by'] = $employee->id;
        } else {
            $validated['created_by'] = $user->employee->id;
        }

        // Generate unique slug
        $validated['slug'] = Str::slug($validated['title']) . '-' . Str::random(6);

        $jobPost = JobPost::create($validated);

        // Create questions if provided
        if ($request->has('questions')) {
            foreach ($request->questions as $index => $questionData) {
                // Convert options string to array if it's multiple choice
                $options = null;
                if (isset($questionData['options']) && $questionData['type'] === 'multiple_choice') {
                    if (is_string($questionData['options'])) {
                        // Split by newlines and filter empty
                        $options = array_filter(array_map('trim', explode("\n", $questionData['options'])));
                    } else {
                        $options = $questionData['options'];
                    }
                }

                JobQuestion::create([
                    'job_post_id' => $jobPost->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => $options,
                    'is_required' => isset($questionData['is_required']) && $questionData['is_required'],
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.jobs.show', $jobPost)
            ->with('success', 'Job post created successfully!');
    }

    public function show(JobPost $job)
    {
        $job->load('creator', 'questions', 'applications.answers');

        $applicationStats = [
            'total' => $job->applications()->count(),
            'best_match' => $job->applications()->where('ai_status', 'best_match')->count(),
            'good_to_go' => $job->applications()->where('ai_status', 'good_to_go')->count(),
            'not_good_fit' => $job->applications()->where('ai_status', 'not_good_fit')->count(),
            'pending' => $job->applications()->where('ai_status', 'pending')->count(),
        ];

        return view('admin.jobs.show', compact('job', 'applicationStats'));
    }

    public function edit(JobPost $job)
    {
        $job->load('questions');
        return view('admin.jobs.edit', compact('job'));
    }

    public function update(Request $request, JobPost $job)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|in:full-time,part-time,contract,remote',
            'experience_level' => 'nullable|in:entry,mid,senior',
            'salary_min' => 'nullable|numeric|min:0',
            'salary_max' => 'nullable|numeric|min:0',
            'salary_currency' => 'nullable|string|max:10',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'application_deadline' => 'nullable|date',
            'contact_email' => 'nullable|email',
            'department' => 'nullable|string|max:255',
            'positions_available' => 'required|integer|min:1',
            'ai_screening_enabled' => 'boolean',
            'ai_screening_criteria' => 'nullable|array',
            'status' => 'required|in:draft,published,closed',
            'questions' => 'nullable|array',
            'questions.*.question' => 'required|string',
            'questions.*.type' => 'required|in:text,textarea,video,file,multiple_choice',
            'questions.*.options' => 'nullable|array',
            'questions.*.is_required' => 'boolean',
        ]);

        $job->update($validated);

        // Update questions
        if ($request->has('questions')) {
            // Delete existing questions
            $job->questions()->delete();

            // Create new questions
            foreach ($request->questions as $index => $questionData) {
                JobQuestion::create([
                    'job_post_id' => $job->id,
                    'question' => $questionData['question'],
                    'type' => $questionData['type'],
                    'options' => $questionData['options'] ?? null,
                    'is_required' => $questionData['is_required'] ?? false,
                    'order' => $index,
                ]);
            }
        }

        return redirect()->route('admin.jobs.show', $job)
            ->with('success', 'Job post updated successfully!');
    }

    public function destroy(JobPost $job)
    {
        $job->delete();

        return redirect()->route('admin.jobs.index')
            ->with('success', 'Job post deleted successfully!');
    }

    public function duplicate(JobPost $job)
    {
        $newJob = $job->replicate();
        $newJob->title = $job->title . ' (Copy)';
        $newJob->slug = Str::slug($newJob->title) . '-' . Str::random(6);
        $newJob->status = 'draft';

        $user = Auth::user();
        if (!$user->employee) {
            // Split name into first and last name
            $nameParts = explode(' ', $user->name, 2);
            $firstName = $nameParts[0];
            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';

            $employee = \App\Models\Employee::create([
                'first_name' => $firstName,
                'last_name' => $lastName,
                'email' => $user->email,
                'hired_at' => now(),
                'user_id' => $user->id,
            ]);
            $newJob->created_by = $employee->id;
        } else {
            $newJob->created_by = $user->employee->id;
        }

        $newJob->save();

        // Copy questions
        foreach ($job->questions as $question) {
            $newQuestion = $question->replicate();
            $newQuestion->job_post_id = $newJob->id;
            $newQuestion->save();
        }

        return redirect()->route('admin.jobs.edit', $newJob)
            ->with('success', 'Job post duplicated successfully!');
    }

    public function bulkUpload(JobPost $job)
    {
        return view('admin.jobs.bulk-upload', compact('job'));
    }

    public function processBulkUpload(Request $request, JobPost $job)
    {
        $request->validate([
            'resumes' => 'required|array|min:1',
            'resumes.*' => 'required|file|mimes:pdf,doc,docx|max:10240',
        ]);

        $results = [
            'best_match' => [],
            'good_to_go' => [],
            'not_good_fit' => [],
            'errors' => [],
        ];

        $aiScreeningService = app(\App\Services\AIScreeningService::class);
        $resumeParserService = app(\App\Services\ResumeParserService::class);

        foreach ($request->file('resumes') as $resume) {
            try {
                $fileName = $resume->getClientOriginalName();

                // Store resume in private storage (same as regular applications)
                $path = $resume->store('resumes');

                // Extract text from resume for parsing
                $resumeText = null;
                try {
                    // Extract text directly using PDF parser
                    if (Storage::exists($path)) {
                        $fullPath = Storage::path($path);
                        $extension = strtolower(pathinfo($fullPath, PATHINFO_EXTENSION));

                        if ($extension === 'pdf') {
                            $parser = new \Smalot\PdfParser\Parser();
                            $pdf = $parser->parseFile($fullPath);
                            $resumeText = $pdf->getText();
                        }
                    }
                } catch (\Exception $e) {
                    // If text extraction fails, continue with filename-based info
                    Log::info("Failed to extract text from resume: " . $e->getMessage());
                }

                // Extract contact info from resume text
                $contactInfo = ['email' => null, 'phone' => null];
                if ($resumeText) {
                    $contactInfo = $resumeParserService->extractContactInfo($resumeText);
                }

                // Extract name from filename as fallback
                $nameFromFile = pathinfo($fileName, PATHINFO_FILENAME);
                $nameParts = explode(' ', str_replace(['_', '-'], ' ', $nameFromFile), 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : 'N/A';

                // Try to extract name from resume text if available
                if ($resumeText) {
                    $extractedName = $resumeParserService->extractName($resumeText);
                    if ($extractedName['first_name']) {
                        $firstName = $extractedName['first_name'];
                        $lastName = $extractedName['last_name'] ?? 'N/A';
                    }
                }

                // Use extracted email or generate temp email
                $email = $contactInfo['email'] ?? 'bulk-upload-' . Str::random(10) . '@temp.com';

                // Use extracted phone or N/A
                $phone = $contactInfo['phone'] ?? 'N/A';

                // Create application record
                $application = \App\Models\JobApplication::create([
                    'job_post_id' => $job->id,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $email,
                    'phone' => $phone,
                    'resume_path' => $path,
                    'resume_original_name' => $fileName,
                    'resume_text' => $resumeText, // Store extracted text
                    'application_status' => 'pending',
                    'ai_status' => 'pending',
                ]);

                // AI Screening
                if ($job->ai_screening_enabled) {
                    $screening = $aiScreeningService->screenApplication($application);

                    // Format analysis text from details
                    $analysisText = isset($screening['details']['summary'])
                        ? $screening['details']['summary']
                        : json_encode($screening['details']);

                    $results[$screening['status']][] = [
                        'application' => $application,
                        'score' => $screening['score'],
                        'analysis' => $analysisText,
                    ];
                } else {
                    $results['good_to_go'][] = [
                        'application' => $application,
                        'score' => null,
                        'analysis' => 'AI screening disabled for this job',
                    ];
                }

            } catch (\Exception $e) {
                $results['errors'][] = [
                    'filename' => $fileName ?? 'Unknown',
                    'error' => $e->getMessage(),
                ];
            }
        }

        return view('admin.jobs.bulk-results', compact('job', 'results'));
    }
}
