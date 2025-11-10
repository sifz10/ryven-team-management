<?php

namespace App\Http\Controllers\AI;

use App\Http\Controllers\Controller;
use App\Services\AIJobManagementService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AIJobManagementController extends Controller
{
    protected AIJobManagementService $aiJobService;

    public function __construct(AIJobManagementService $aiJobService)
    {
        $this->aiJobService = $aiJobService;
    }

    /**
     * Get job posting analytics and reports
     */
    public function getAnalytics(Request $request): JsonResponse
    {
        $jobPostId = $request->input('job_post_id');
        $analytics = $this->aiJobService->getJobPostAnalytics($jobPostId);

        return response()->json([
            'success' => true,
            'data' => $analytics
        ]);
    }

    /**
     * Get all job posts
     */
    public function getAllJobPosts(Request $request): JsonResponse
    {
        $filters = $request->only(['status', 'department']);
        $jobPosts = $this->aiJobService->getAllJobPosts($filters);

        return response()->json([
            'success' => true,
            'data' => $jobPosts
        ]);
    }

    /**
     * Create a new job post
     */
    public function createJobPost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'department' => 'nullable|string',
            'location' => 'nullable|string',
            'job_type' => 'nullable|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|in:entry,junior,mid,senior,lead,executive',
            'salary_range' => 'nullable|string',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'status' => 'nullable|in:draft,active,closed',
            'deadline' => 'nullable|date',
            'questions' => 'nullable|array',
            'questions.*.question_text' => 'required|string',
            'questions.*.question_type' => 'nullable|in:text,textarea,video,file',
            'questions.*.is_required' => 'nullable|boolean'
        ]);

        $result = $this->aiJobService->createJobPost($validated);

        return response()->json($result);
    }

    /**
     * Update job post
     */
    public function updateJobPost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'required|exists:job_posts,id',
            'title' => 'nullable|string|max:255',
            'department' => 'nullable|string',
            'location' => 'nullable|string',
            'job_type' => 'nullable|in:full-time,part-time,contract,internship',
            'experience_level' => 'nullable|in:entry,junior,mid,senior,lead,executive',
            'salary_range' => 'nullable|string',
            'description' => 'nullable|string',
            'requirements' => 'nullable|string',
            'responsibilities' => 'nullable|string',
            'benefits' => 'nullable|string',
            'status' => 'nullable|in:draft,active,closed',
            'deadline' => 'nullable|date'
        ]);

        $jobPostId = $validated['job_post_id'];
        unset($validated['job_post_id']);

        $result = $this->aiJobService->updateJobPost($jobPostId, $validated);

        return response()->json($result);
    }

    /**
     * Delete job post
     */
    public function deleteJobPost(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'job_post_id' => 'required|exists:job_posts,id'
        ]);

        $result = $this->aiJobService->deleteJobPost($validated['job_post_id']);

        return response()->json($result);
    }

    /**
     * Search applications with filters
     */
    public function searchApplications(Request $request): JsonResponse
    {
        $filters = $request->only([
            'job_post_id',
            'status',
            'ai_status',
            'search',
            'min_experience',
            'per_page'
        ]);

        $applications = $this->aiJobService->searchApplications($filters);

        return response()->json([
            'success' => true,
            'data' => $applications
        ]);
    }

    /**
     * Get application details
     */
    public function getApplicationDetails(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:job_applications,id'
        ]);

        $details = $this->aiJobService->getApplicationDetails($validated['application_id']);

        return response()->json([
            'success' => true,
            'data' => $details
        ]);
    }

    /**
     * Update application status
     */
    public function updateApplicationStatus(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'status' => 'required|in:pending,reviewing,shortlisted,interview,offer,rejected,hired',
            'notes' => 'nullable|string'
        ]);

        $result = $this->aiJobService->updateApplicationStatus(
            $validated['application_id'],
            $validated['status'],
            $validated['notes'] ?? null
        );

        return response()->json($result);
    }

    /**
     * Delete application
     */
    public function deleteApplication(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:job_applications,id'
        ]);

        $result = $this->aiJobService->deleteApplication($validated['application_id']);

        return response()->json($result);
    }

    /**
     * Add candidate to talent pool
     */
    public function addToTalentPool(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'application_id' => 'required|exists:job_applications,id',
            'skills' => 'nullable|array',
            'experience_level' => 'nullable|in:entry,junior,mid,senior,lead,executive',
            'status' => 'nullable|in:potential,contacted,interested,hired',
            'notes' => 'nullable|string'
        ]);

        $applicationId = $validated['application_id'];
        unset($validated['application_id']);

        $result = $this->aiJobService->addToTalentPool($applicationId, $validated);

        return response()->json($result);
    }

    /**
     * Get talent pool candidates
     */
    public function getTalentPool(Request $request): JsonResponse
    {
        $filters = $request->only([
            'status',
            'experience_level',
            'skills',
            'search',
            'per_page'
        ]);

        $candidates = $this->aiJobService->getTalentPoolCandidates($filters);

        return response()->json([
            'success' => true,
            'data' => $candidates
        ]);
    }
}
