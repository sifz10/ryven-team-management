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

        return view('github.pr-details', [
            'log' => $log,
            'pr' => $prDetails,
            'files' => $prFiles ?? [],
            'comments' => $prComments ?? [],
            'repo' => $repo,
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
}

