<?php

namespace App\Services;

use App\Models\JobPost;
use App\Models\JobApplication;
use App\Models\TalentPool;
use App\Models\JobQuestion;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AIJobManagementService
{
    /**
     * Get comprehensive job posting analytics and statistics
     */
    public function getJobPostAnalytics(?int $jobPostId = null): array
    {
        $query = JobPost::with(['applications', 'questions']);

        if ($jobPostId) {
            $query->where('id', $jobPostId);
        }

        $jobPosts = $query->get();

        $analytics = [
            'total_job_posts' => $jobPosts->count(),
            'active_job_posts' => $jobPosts->where('status', 'active')->count(),
            'closed_job_posts' => $jobPosts->where('status', 'closed')->count(),
            'draft_job_posts' => $jobPosts->where('status', 'draft')->count(),
            'total_applications' => 0,
            'applications_by_status' => [
                'pending' => 0,
                'reviewing' => 0,
                'shortlisted' => 0,
                'interview' => 0,
                'offer' => 0,
                'rejected' => 0,
                'hired' => 0
            ],
            'ai_screening_stats' => [
                'best_match' => 0,
                'good_to_go' => 0,
                'not_good_fit' => 0,
                'pending' => 0
            ],
            'talent_pool_count' => TalentPool::count(),
            'job_posts' => []
        ];

        foreach ($jobPosts as $jobPost) {
            $applications = $jobPost->applications;
            $analytics['total_applications'] += $applications->count();

            // Count applications by status
            foreach ($applications as $application) {
                if (isset($analytics['applications_by_status'][$application->status])) {
                    $analytics['applications_by_status'][$application->status]++;
                }

                if (isset($analytics['ai_screening_stats'][$application->ai_status])) {
                    $analytics['ai_screening_stats'][$application->ai_status]++;
                }
            }

            // Individual job post stats
            $analytics['job_posts'][] = [
                'id' => $jobPost->id,
                'title' => $jobPost->title,
                'department' => $jobPost->department,
                'status' => $jobPost->status,
                'total_applications' => $applications->count(),
                'best_match_count' => $applications->where('ai_status', 'best_match')->count(),
                'good_to_go_count' => $applications->where('ai_status', 'good_to_go')->count(),
                'shortlisted_count' => $applications->where('status', 'shortlisted')->count(),
                'interview_count' => $applications->where('status', 'interview')->count(),
                'hired_count' => $applications->where('status', 'hired')->count(),
                'screening_questions_count' => $jobPost->questions->count(),
                'created_at' => $jobPost->created_at->format('Y-m-d H:i:s'),
                'deadline' => $jobPost->deadline ? $jobPost->deadline->format('Y-m-d') : null
            ];
        }

        return $analytics;
    }

    /**
     * Get detailed application information
     */
    public function getApplicationDetails(int $applicationId): array
    {
        $application = JobApplication::with([
            'jobPost',
            'answers.question',
            'tests',
            'talentPool'
        ])->findOrFail($applicationId);

        return [
            'id' => $application->id,
            'applicant_name' => $application->first_name . ' ' . $application->last_name,
            'email' => $application->email,
            'phone' => $application->phone,
            'job_title' => $application->jobPost->title,
            'status' => $application->status,
            'ai_status' => $application->ai_status,
            'ai_match_score' => $application->ai_match_score,
            'ai_analysis' => $application->ai_analysis,
            'years_of_experience' => $application->years_of_experience,
            'current_salary' => $application->current_salary,
            'expected_salary' => $application->expected_salary,
            'notice_period' => $application->notice_period,
            'portfolio_url' => $application->portfolio_url,
            'linkedin_url' => $application->linkedin_url,
            'resume_path' => $application->resume_path ? Storage::url($application->resume_path) : null,
            'screening_answers' => $application->answers->map(fn($answer) => [
                'question' => $answer->question->question_text,
                'answer' => $answer->answer_text,
                'video_path' => $answer->video_path,
                'file_path' => $answer->file_path
            ])->toArray(),
            'tests' => $application->tests->map(fn($test) => [
                'id' => $test->id,
                'status' => $test->status,
                'assigned_at' => $test->assigned_at?->format('Y-m-d H:i:s'),
                'submitted_at' => $test->submitted_at?->format('Y-m-d H:i:s'),
                'score' => $test->score
            ])->toArray(),
            'in_talent_pool' => $application->added_to_talent_pool,
            'admin_notes' => $application->admin_notes,
            'applied_at' => $application->created_at->format('Y-m-d H:i:s')
        ];
    }

    /**
     * Create a new job post
     */
    public function createJobPost(array $data): array
    {
        DB::beginTransaction();
        try {
            $jobPost = JobPost::create([
                'title' => $data['title'],
                'department' => $data['department'] ?? null,
                'location' => $data['location'] ?? null,
                'job_type' => $data['job_type'] ?? 'full-time',
                'experience_level' => $data['experience_level'] ?? null,
                'salary_range' => $data['salary_range'] ?? null,
                'description' => $data['description'],
                'requirements' => $data['requirements'] ?? null,
                'responsibilities' => $data['responsibilities'] ?? null,
                'benefits' => $data['benefits'] ?? null,
                'status' => $data['status'] ?? 'draft',
                'deadline' => $data['deadline'] ?? null,
                'posted_by' => Auth::id() ?? 1
            ]);

            // Add screening questions if provided
            if (isset($data['questions']) && is_array($data['questions'])) {
                foreach ($data['questions'] as $questionData) {
                    JobQuestion::create([
                        'job_post_id' => $jobPost->id,
                        'question_text' => $questionData['question_text'],
                        'question_type' => $questionData['question_type'] ?? 'text',
                        'is_required' => $questionData['is_required'] ?? true,
                        'order' => $questionData['order'] ?? 0
                    ]);
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Job post '{$jobPost->title}' created successfully",
                'job_post_id' => $jobPost->id,
                'job_post' => [
                    'id' => $jobPost->id,
                    'title' => $jobPost->title,
                    'department' => $jobPost->department,
                    'status' => $jobPost->status,
                    'created_at' => $jobPost->created_at->format('Y-m-d H:i:s')
                ]
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to create job post: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update job post
     */
    public function updateJobPost(int $jobPostId, array $data): array
    {
        try {
            $jobPost = JobPost::findOrFail($jobPostId);
            $jobPost->update(array_filter($data));

            return [
                'success' => true,
                'message' => "Job post '{$jobPost->title}' updated successfully",
                'job_post' => [
                    'id' => $jobPost->id,
                    'title' => $jobPost->title,
                    'status' => $jobPost->status
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update job post: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete job post
     */
    public function deleteJobPost(int $jobPostId): array
    {
        try {
            $jobPost = JobPost::findOrFail($jobPostId);
            $title = $jobPost->title;
            $applicationsCount = $jobPost->applications()->count();

            // Delete all related data
            foreach ($jobPost->applications as $application) {
                // Delete files
                if ($application->resume_path && Storage::exists($application->resume_path)) {
                    Storage::delete($application->resume_path);
                }

                foreach ($application->answers as $answer) {
                    if ($answer->video_path && Storage::exists($answer->video_path)) {
                        Storage::delete($answer->video_path);
                    }
                    if ($answer->file_path && Storage::exists($answer->file_path)) {
                        Storage::delete($answer->file_path);
                    }
                }
            }

            $jobPost->delete();

            return [
                'success' => true,
                'message' => "Job post '{$title}' and {$applicationsCount} applications deleted successfully"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete job post: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(int $applicationId, string $status, ?string $notes = null): array
    {
        try {
            $application = JobApplication::findOrFail($applicationId);
            $applicantName = $application->first_name . ' ' . $application->last_name;

            $application->update([
                'status' => $status,
                'admin_notes' => $notes ?? $application->admin_notes
            ]);

            return [
                'success' => true,
                'message' => "Application status for {$applicantName} updated to '{$status}'",
                'application' => [
                    'id' => $application->id,
                    'applicant_name' => $applicantName,
                    'status' => $application->status
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update application status: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Add candidate to talent pool
     */
    public function addToTalentPool(int $applicationId, array $data = []): array
    {
        try {
            $application = JobApplication::findOrFail($applicationId);

            if ($application->added_to_talent_pool) {
                return [
                    'success' => false,
                    'message' => 'Candidate is already in the talent pool'
                ];
            }

            $talentPool = TalentPool::create([
                'job_application_id' => $application->id,
                'first_name' => $application->first_name,
                'last_name' => $application->last_name,
                'email' => $application->email,
                'phone' => $application->phone,
                'resume_path' => $application->resume_path,
                'skills' => $data['skills'] ?? null,
                'experience_level' => $data['experience_level'] ?? $application->jobPost->experience_level,
                'status' => $data['status'] ?? 'potential',
                'notes' => $data['notes'] ?? "Added from application for {$application->jobPost->title}",
                'added_by' => Auth::id() ?? 1
            ]);

            $application->update(['added_to_talent_pool' => true]);

            return [
                'success' => true,
                'message' => "Candidate {$application->first_name} {$application->last_name} added to talent pool",
                'talent_pool_id' => $talentPool->id
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to add to talent pool: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Delete application
     */
    public function deleteApplication(int $applicationId): array
    {
        try {
            $application = JobApplication::with(['answers', 'tests'])->findOrFail($applicationId);
            $applicantName = $application->first_name . ' ' . $application->last_name;

            // Delete resume file
            if ($application->resume_path && Storage::exists($application->resume_path)) {
                Storage::delete($application->resume_path);
            }

            // Delete answer attachments
            foreach ($application->answers as $answer) {
                if ($answer->video_path && Storage::exists($answer->video_path)) {
                    Storage::delete($answer->video_path);
                }
                if ($answer->file_path && Storage::exists($answer->file_path)) {
                    Storage::delete($answer->file_path);
                }
            }

            // Delete test files
            foreach ($application->tests as $test) {
                if ($test->test_file_path && Storage::exists($test->test_file_path)) {
                    Storage::delete($test->test_file_path);
                }
                if ($test->submission_file_path && Storage::exists($test->submission_file_path)) {
                    Storage::delete($test->submission_file_path);
                }
            }

            // Delete talent pool entry
            if ($application->added_to_talent_pool) {
                TalentPool::where('job_application_id', $application->id)->delete();
            }

            $application->delete();

            return [
                'success' => true,
                'message' => "Application from {$applicantName} has been permanently deleted"
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete application: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get talent pool candidates
     */
    public function getTalentPoolCandidates(array $filters = []): array
    {
        $query = TalentPool::with(['application.jobPost', 'addedBy']);

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['experience_level'])) {
            $query->where('experience_level', $filters['experience_level']);
        }

        if (isset($filters['skills'])) {
            $query->whereJsonContains('skills', $filters['skills']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $candidates = $query->paginate($filters['per_page'] ?? 20);

        return [
            'total' => $candidates->total(),
            'per_page' => $candidates->perPage(),
            'current_page' => $candidates->currentPage(),
            'last_page' => $candidates->lastPage(),
            'candidates' => $candidates->items()->map(fn($candidate) => [
                'id' => $candidate->id,
                'name' => $candidate->first_name . ' ' . $candidate->last_name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
                'experience_level' => $candidate->experience_level,
                'status' => $candidate->status,
                'skills' => $candidate->skills,
                'notes' => $candidate->notes,
                'original_job_title' => $candidate->application?->jobPost?->title,
                'added_at' => $candidate->created_at->format('Y-m-d H:i:s'),
                'last_contacted' => $candidate->last_contacted_at?->format('Y-m-d H:i:s')
            ])->toArray()
        ];
    }

    /**
     * Search applications with filters
     */
    public function searchApplications(array $filters = []): array
    {
        $query = JobApplication::with(['jobPost']);

        if (isset($filters['job_post_id'])) {
            $query->where('job_post_id', $filters['job_post_id']);
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['ai_status'])) {
            $query->where('ai_status', $filters['ai_status']);
        }

        if (isset($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if (isset($filters['min_experience'])) {
            $query->where('years_of_experience', '>=', $filters['min_experience']);
        }

        $applications = $query->latest()->paginate($filters['per_page'] ?? 20);

        return [
            'total' => $applications->total(),
            'per_page' => $applications->perPage(),
            'current_page' => $applications->currentPage(),
            'last_page' => $applications->lastPage(),
            'applications' => $applications->items()->map(fn($app) => [
                'id' => $app->id,
                'applicant_name' => $app->first_name . ' ' . $app->last_name,
                'email' => $app->email,
                'job_title' => $app->jobPost->title,
                'status' => $app->status,
                'ai_status' => $app->ai_status,
                'ai_match_score' => $app->ai_match_score,
                'years_of_experience' => $app->years_of_experience,
                'in_talent_pool' => $app->added_to_talent_pool,
                'applied_at' => $app->created_at->format('Y-m-d H:i:s')
            ])->toArray()
        ];
    }

    /**
     * Get all job posts with details
     */
    public function getAllJobPosts(array $filters = []): array
    {
        $query = JobPost::withCount('applications');

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (isset($filters['department'])) {
            $query->where('department', $filters['department']);
        }

        $jobPosts = $query->latest()->get();

        return [
            'total' => $jobPosts->count(),
            'job_posts' => $jobPosts->map(fn($job) => [
                'id' => $job->id,
                'title' => $job->title,
                'department' => $job->department,
                'location' => $job->location,
                'job_type' => $job->job_type,
                'experience_level' => $job->experience_level,
                'status' => $job->status,
                'applications_count' => $job->applications_count,
                'deadline' => $job->deadline?->format('Y-m-d'),
                'created_at' => $job->created_at->format('Y-m-d H:i:s')
            ])->toArray()
        ];
    }
}
