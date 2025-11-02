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
}

