<?php

namespace App\Http\Controllers;

use App\Models\GitHubLog;
use App\Services\GitHubApiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class GitHubPullRequestController extends Controller
{
    protected GitHubApiService $github;

    public function __construct(GitHubApiService $github)
    {
        $this->github = $github;
    }

    /**
     * Get Pull Request details
     */
    public function show(GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Fetch PR details
        $prDetails = $this->github->getPullRequest($repo['owner'], $repo['repo'], (int) $log->pr_number);
        if (!$prDetails) {
            return response()->json([
                'error' => 'Failed to fetch pull request details from GitHub',
            ], 500);
        }

        // Fetch PR files (diff)
        $prFiles = $this->github->getPullRequestFiles($repo['owner'], $repo['repo'], (int) $log->pr_number);
        if (!$prFiles) {
            return response()->json([
                'error' => 'Failed to fetch pull request files from GitHub',
            ], 500);
        }

        // Fetch PR comments
        $prComments = $this->github->getPullRequestComments($repo['owner'], $repo['repo'], (int) $log->pr_number);

        return response()->json([
            'success' => true,
            'pr' => $prDetails,
            'files' => $prFiles,
            'comments' => $prComments ?? [],
            'repo' => $repo,
        ]);
    }

    /**
     * Show Pull Request details page
     */
    public function details(GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            abort(404, 'Not a pull request');
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            abort(500, 'Invalid repository URL');
        }

        // Fetch PR details
        $prDetails = $this->github->getPullRequest($repo['owner'], $repo['repo'], (int) $log->pr_number);
        if (!$prDetails) {
            abort(500, 'Failed to fetch pull request details from GitHub');
        }

        // Fetch PR files (diff)
        $prFiles = $this->github->getPullRequestFiles($repo['owner'], $repo['repo'], (int) $log->pr_number);
        
        // Fetch PR comments
        $prComments = $this->github->getPullRequestComments($repo['owner'], $repo['repo'], (int) $log->pr_number);

        // Get employees with GitHub usernames for assignment
        $employees = \App\Models\Employee::whereNotNull('github_username')
            ->where('github_username', '!=', '')
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'github_username']);

        // Get repository labels
        $repoLabels = $this->github->getRepositoryLabels($repo['owner'], $repo['repo']) ?? [];

        return view('github.pr-details', [
            'log' => $log,
            'pr' => $prDetails,
            'files' => $prFiles ?? [],
            'comments' => $prComments ?? [],
            'repo' => $repo,
            'employees' => $employees,
            'repoLabels' => $repoLabels,
        ]);
    }

    /**
     * Post a comment on a Pull Request
     */
    public function comment(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'min:1'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Comment body is required',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Create comment on GitHub
        $comment = $this->github->createPullRequestComment(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            $request->input('body')
        );

        if (!$comment) {
            return response()->json([
                'error' => 'Failed to post comment to GitHub',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'comment' => $comment,
            'message' => 'Comment posted successfully',
        ]);
    }

    /**
     * Post a review on a Pull Request
     */
    public function review(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'body' => ['required', 'string', 'min:1'],
            'event' => ['required', 'in:APPROVE,REQUEST_CHANGES,COMMENT'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid review data',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Create review on GitHub
        $review = $this->github->createPullRequestReview(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            $request->input('body'),
            $request->input('event')
        );

        if (!$review) {
            return response()->json([
                'error' => 'Failed to post review to GitHub',
            ], 500);
        }

        return response()->json([
            'success' => true,
            'review' => $review,
            'message' => 'Review posted successfully',
        ]);
    }

    /**
     * Assign a Pull Request to a user
     */
    public function assign(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'github_username' => ['required', 'string'],
            'type' => ['required', 'in:assignee,reviewer'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Invalid assignment data',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        $githubUsername = $request->input('github_username');
        $type = $request->input('type');

        // Assign to GitHub
        if ($type === 'reviewer') {
            $result = $this->github->assignReviewers(
                $repo['owner'],
                $repo['repo'],
                (int) $log->pr_number,
                [$githubUsername]
            );
        } else {
            $result = $this->github->assignUsers(
                $repo['owner'],
                $repo['repo'],
                (int) $log->pr_number,
                [$githubUsername]
            );
        }

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => ucfirst($type) . ' assigned successfully',
        ]);
    }

    /**
     * Remove a reviewer from a Pull Request
     */
    public function removeReviewer(Request $request, GitHubLog $log, string $username)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Remove reviewer from GitHub
        $result = $this->github->removeReviewers(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            [$username]
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Reviewer removed successfully',
        ]);
    }

    /**
     * Remove an assignee from a Pull Request
     */
    public function removeAssignee(Request $request, GitHubLog $log, string $username)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Remove assignee from GitHub
        $result = $this->github->removeAssignees(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            [$username]
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Assignee removed successfully',
        ]);
    }

    /**
     * Add a label to a Pull Request
     */
    public function addLabel(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Validate input
        $validator = Validator::make($request->all(), [
            'label' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Label is required',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        $label = $request->input('label');

        // Add label to GitHub
        $result = $this->github->addLabels(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            [$label]
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Label added successfully',
        ]);
    }

    /**
     * Remove a label from a Pull Request
     */
    public function removeLabel(Request $request, GitHubLog $log, string $label)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Remove label from GitHub
        $result = $this->github->removeLabel(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            $label
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Label removed successfully',
        ]);
    }

    /**
     * Merge a Pull Request
     */
    public function merge(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Get merge method from request (default: merge)
        $mergeMethod = $request->input('merge_method', 'merge');

        // Merge PR on GitHub
        $result = $this->github->mergePullRequest(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number,
            null,
            null,
            $mergeMethod
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pull request merged successfully',
        ]);
    }

    /**
     * Close a Pull Request
     */
    public function close(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        // Close PR on GitHub
        $result = $this->github->closePullRequest(
            $repo['owner'],
            $repo['repo'],
            (int) $log->pr_number
        );

        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['error'],
            ], 400);
        }

        return response()->json([
            'success' => true,
            'message' => 'Pull request closed successfully',
        ]);
    }

    /**
     * Generate AI code review for a Pull Request
     */
    public function generateAIReview(Request $request, GitHubLog $log)
    {
        // Only allow for pull request events
        if ($log->event_type !== 'pull_request') {
            return response()->json([
                'error' => 'This is not a pull request event',
            ], 400);
        }

        // Parse repository URL
        $repo = GitHubApiService::parseRepoUrl($log->repository_url);
        if (!$repo) {
            return response()->json([
                'error' => 'Invalid repository URL',
            ], 400);
        }

        try {
            // Fetch PR details and files
            $prDetails = $this->github->getPullRequest($repo['owner'], $repo['repo'], (int) $log->pr_number);
            $prFiles = $this->github->getPullRequestFiles($repo['owner'], $repo['repo'], (int) $log->pr_number);
            
            if (!$prDetails || !$prFiles) {
                return response()->json([
                    'error' => 'Failed to fetch PR details from GitHub',
                ], 500);
            }

            // Prepare context for AI
            $context = $this->prepareAIContext($prDetails, $prFiles);

            // Call OpenAI API
            $review = $this->callOpenAI($context);

            if (!$review) {
                return response()->json([
                    'error' => 'Failed to generate AI review',
                ], 500);
            }

            return response()->json([
                'success' => true,
                'review' => $review,
            ]);

        } catch (\Exception $e) {
            \Log::error('AI Review Generation Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'error' => 'An error occurred while generating the review: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Prepare context for AI review
     */
    private function prepareAIContext($prDetails, $prFiles)
    {
        $context = "## Pull Request Details\n\n";
        $context .= "**Title:** " . $prDetails['title'] . "\n";
        $context .= "**Description:** " . ($prDetails['body'] ?? 'No description provided') . "\n";
        $context .= "**Branch:** " . $prDetails['head']['ref'] . " → " . $prDetails['base']['ref'] . "\n";
        $context .= "**Files Changed:** " . count($prFiles) . "\n\n";

        $context .= "## Code Changes\n\n";

        foreach ($prFiles as $index => $file) {
            if ($index >= 10) break; // Limit to first 10 files to stay within token limits
            
            $context .= "### File: " . $file['filename'] . "\n";
            $context .= "**Status:** " . $file['status'] . "\n";
            $context .= "**Changes:** +" . $file['additions'] . " / -" . $file['deletions'] . "\n";
            
            if (isset($file['patch'])) {
                $context .= "```diff\n" . $file['patch'] . "\n```\n\n";
            }
        }

        return $context;
    }

    /**
     * Call OpenAI API for code review
     */
    private function callOpenAI($context)
    {
        $apiKey = config('services.openai.api_key');
        
        if (!$apiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $client = \Illuminate\Support\Facades\Http::timeout(60);
        
        // Disable SSL verification in local environment
        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }

        $response = $client->withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://api.openai.com/v1/chat/completions', [
            'model' => 'gpt-4o-mini',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert code reviewer. Analyze the pull request and provide constructive feedback focusing on:
                    1. Code quality and best practices
                    2. Potential bugs or issues
                    3. Performance considerations
                    4. Security concerns
                    5. Readability and maintainability
                    6. Suggestions for improvement

                    Provide your review in a well-structured format with clear sections. Be specific and actionable. Use markdown formatting for better readability.'
                ],
                [
                    'role' => 'user',
                    'content' => $context
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2000,
        ]);

        if (!$response->successful()) {
            \Log::error('OpenAI API Error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            throw new \Exception('OpenAI API request failed: ' . $response->status());
        }

        $data = $response->json();
        
        return $data['choices'][0]['message']['content'] ?? null;
    }
}

