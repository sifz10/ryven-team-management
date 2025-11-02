<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GitHubApiService
{
    protected string $token;
    protected string $baseUrl = 'https://api.github.com';

    public function __construct()
    {
        $this->token = config('services.github.token');
    }

    /**
     * Create HTTP client instance with proper configuration
     */
    protected function http()
    {
        $client = Http::withToken($this->token)
            ->accept('application/vnd.github.v3+json');
        
        // Disable SSL verification in local environment (Windows development)
        if (app()->environment('local')) {
            $client = $client->withoutVerifying();
        }
        
        return $client;
    }

    /**
     * Get Pull Request details
     */
    public function getPullRequest(string $owner, string $repo, int $prNumber): ?array
    {
        try {
            $response = $this->http()
                ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/pulls/{$prNumber}");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GitHub API: Failed to fetch PR', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception fetching PR', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get Pull Request files (diff)
     */
    public function getPullRequestFiles(string $owner, string $repo, int $prNumber): ?array
    {
        try {
            $response = $this->http()
                ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/pulls/{$prNumber}/files");

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GitHub API: Failed to fetch PR files', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception fetching PR files', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Get Pull Request comments
     */
    public function getPullRequestComments(string $owner, string $repo, int $prNumber): ?array
    {
        try {
            $response = $this->http()
                ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/comments");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception fetching PR comments', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create a comment on a Pull Request
     */
    public function createPullRequestComment(string $owner, string $repo, int $prNumber, string $body): ?array
    {
        try {
            $response = $this->http()
                ->post("{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/comments", [
                    'body' => $body,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GitHub API: Failed to create comment', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception creating comment', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Create a review on a Pull Request
     */
    public function createPullRequestReview(
        string $owner,
        string $repo,
        int $prNumber,
        string $body,
        string $event = 'COMMENT' // APPROVE, REQUEST_CHANGES, COMMENT
    ): ?array {
        try {
            $response = $this->http()
                ->post("{$this->baseUrl}/repos/{$owner}/{$repo}/pulls/{$prNumber}/reviews", [
                    'body' => $body,
                    'event' => $event,
                ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('GitHub API: Failed to create review', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception creating review', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Assign reviewers to a Pull Request
     */
    public function assignReviewers(string $owner, string $repo, int $prNumber, array $reviewers): array
    {
        try {
            $response = $this->http()
                ->post("{$this->baseUrl}/repos/{$owner}/{$repo}/pulls/{$prNumber}/requested_reviewers", [
                    'reviewers' => $reviewers,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Failed to assign reviewers';
            
            Log::error('GitHub API: Failed to assign reviewers', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception assigning reviewers', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    /**
     * Assign users to a Pull Request
     */
    public function assignUsers(string $owner, string $repo, int $prNumber, array $assignees): array
    {
        try {
            $response = $this->http()
                ->post("{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/assignees", [
                    'assignees' => $assignees,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Failed to assign users';

            Log::error('GitHub API: Failed to assign users', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception assigning users', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    /**
     * Get repository labels
     */
    public function getRepositoryLabels(string $owner, string $repo): ?array
    {
        try {
            $response = $this->http()
                ->get("{$this->baseUrl}/repos/{$owner}/{$repo}/labels");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception fetching labels', [
                'message' => $e->getMessage(),
            ]);
            return null;
        }
    }

    /**
     * Add labels to a Pull Request
     */
    public function addLabels(string $owner, string $repo, int $prNumber, array $labels): array
    {
        try {
            $response = $this->http()
                ->post("{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/labels", [
                    'labels' => $labels,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Failed to add labels';

            Log::error('GitHub API: Failed to add labels', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception adding labels', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    /**
     * Remove a label from a Pull Request
     */
    public function removeLabel(string $owner, string $repo, int $prNumber, string $label): array
    {
        try {
            $response = $this->http()
                ->delete("{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/labels/{$label}");

            if ($response->successful()) {
                return ['success' => true];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Failed to remove label';

            Log::error('GitHub API: Failed to remove label', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception removing label', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    /**
     * Remove reviewers from a Pull Request
     */
    public function removeReviewers(string $owner, string $repo, int $prNumber, array $reviewers): array
    {
        try {
            $response = $this->http()
                ->delete("{$this->baseUrl}/repos/{$owner}/{$repo}/pulls/{$prNumber}/requested_reviewers", [
                    'reviewers' => $reviewers,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Failed to remove reviewers';
            
            Log::error('GitHub API: Failed to remove reviewers', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception removing reviewers', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    /**
     * Remove assignees from a Pull Request
     */
    public function removeAssignees(string $owner, string $repo, int $prNumber, array $assignees): array
    {
        try {
            $response = $this->http()
                ->delete("{$this->baseUrl}/repos/{$owner}/{$repo}/issues/{$prNumber}/assignees", [
                    'assignees' => $assignees,
                ]);

            if ($response->successful()) {
                return ['success' => true, 'data' => $response->json()];
            }

            $errorBody = $response->json();
            $errorMessage = $errorBody['message'] ?? 'Failed to remove assignees';
            
            Log::error('GitHub API: Failed to remove assignees', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return ['success' => false, 'error' => $errorMessage];
        } catch (\Exception $e) {
            Log::error('GitHub API: Exception removing assignees', [
                'message' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => 'An unexpected error occurred'];
        }
    }

    /**
     * Parse repository owner and name from URL
     */
    public static function parseRepoUrl(string $url): ?array
    {
        // Example: https://github.com/owner/repo
        if (preg_match('/github\.com\/([^\/]+)\/([^\/]+)/', $url, $matches)) {
            return [
                'owner' => $matches[1],
                'repo' => $matches[2],
            ];
        }

        return null;
    }
}

