<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\GitHubLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    /**
     * Handle incoming GitHub webhook
     */
    public function handle(Request $request)
    {
        // Get event type from header
        $eventType = $request->header('X-GitHub-Event');
        $payload = $request->all();

        Log::info('GitHub Webhook Received', [
            'event_type' => $eventType,
            'action' => $payload['action'] ?? null,
        ]);

        // Handle different event types
        try {
            switch ($eventType) {
                case 'push':
                    $this->handlePushEvent($payload);
                    break;
                case 'pull_request':
                    $this->handlePullRequestEvent($payload);
                    break;
                case 'pull_request_review':
                    $this->handlePullRequestReviewEvent($payload);
                    break;
                case 'pull_request_review_comment':
                    $this->handlePullRequestReviewCommentEvent($payload);
                    break;
                case 'issues':
                    $this->handleIssuesEvent($payload);
                    break;
                case 'issue_comment':
                    $this->handleIssueCommentEvent($payload);
                    break;
                case 'create':
                case 'delete':
                    $this->handleBranchTagEvent($eventType, $payload);
                    break;
                default:
                    Log::info('Unhandled GitHub event type: ' . $eventType);
            }

            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error('GitHub Webhook Error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Handle push events
     */
    protected function handlePushEvent(array $payload)
    {
        $commits = $payload['commits'] ?? [];
        $repository = $payload['repository'];
        $pusher = $payload['pusher'] ?? $payload['sender'];
        $ref = $payload['ref'] ?? '';
        $branch = str_replace('refs/heads/', '', $ref);

        // Group commits by author
        $commitsByAuthor = [];
        foreach ($commits as $commit) {
            $authorEmail = $commit['author']['email'] ?? '';
            $authorUsername = $commit['author']['username'] ?? $commit['committer']['username'] ?? '';
            
            if (!isset($commitsByAuthor[$authorEmail])) {
                $commitsByAuthor[$authorEmail] = [
                    'username' => $authorUsername,
                    'commits' => [],
                    'email' => $authorEmail,
                ];
            }
            $commitsByAuthor[$authorEmail]['commits'][] = $commit;
        }

        // Create log entries for each author
        foreach ($commitsByAuthor as $authorEmail => $data) {
            $employee = $this->findEmployeeByEmailOrUsername($authorEmail, $data['username']);
            
            if ($employee) {
                // Get the last commit message as the main message
                $lastCommit = end($data['commits']);
                
                GitHubLog::create([
                    'employee_id' => $employee->id,
                    'event_type' => 'push',
                    'action' => null,
                    'repository_name' => $repository['full_name'],
                    'repository_url' => $repository['html_url'],
                    'branch' => $branch,
                    'ref' => $ref,
                    'commit_message' => $lastCommit['message'] ?? '',
                    'commit_sha' => $lastCommit['id'] ?? '',
                    'commit_url' => $lastCommit['url'] ?? '',
                    'commits_count' => count($data['commits']),
                    'author_username' => $data['username'] ?: ($pusher['name'] ?? 'unknown'),
                    'author_avatar_url' => $payload['sender']['avatar_url'] ?? null,
                    'payload' => $payload,
                    'event_at' => now(),
                ]);
            }
        }
    }

    /**
     * Handle pull request events
     */
    protected function handlePullRequestEvent(array $payload)
    {
        $action = $payload['action'];
        $pullRequest = $payload['pull_request'];
        $repository = $payload['repository'];
        $sender = $payload['sender'];

        // Find employee by PR author
        $authorEmail = $pullRequest['user']['email'] ?? '';
        $authorUsername = $pullRequest['user']['login'] ?? '';
        
        $employee = $this->findEmployeeByEmailOrUsername($authorEmail, $authorUsername);

        if ($employee) {
            GitHubLog::create([
                'employee_id' => $employee->id,
                'event_type' => 'pull_request',
                'action' => $action,
                'repository_name' => $repository['full_name'],
                'repository_url' => $repository['html_url'],
                'branch' => $pullRequest['head']['ref'] ?? '',
                'pr_number' => (string) $pullRequest['number'],
                'pr_title' => $pullRequest['title'],
                'pr_description' => $pullRequest['body'],
                'pr_url' => $pullRequest['html_url'],
                'pr_state' => $pullRequest['state'],
                'pr_merged' => $pullRequest['merged'] ?? false,
                'author_username' => $authorUsername,
                'author_avatar_url' => $sender['avatar_url'] ?? null,
                'payload' => $payload,
                'event_at' => now(),
            ]);
        }
    }

    /**
     * Handle pull request review events
     */
    protected function handlePullRequestReviewEvent(array $payload)
    {
        $action = $payload['action'];
        $review = $payload['review'];
        $pullRequest = $payload['pull_request'];
        $repository = $payload['repository'];

        // Find employee by reviewer
        $reviewerUsername = $review['user']['login'] ?? '';
        
        $employee = $this->findEmployeeByEmailOrUsername('', $reviewerUsername);

        if ($employee) {
            GitHubLog::create([
                'employee_id' => $employee->id,
                'event_type' => 'pull_request_review',
                'action' => $action,
                'repository_name' => $repository['full_name'],
                'repository_url' => $repository['html_url'],
                'branch' => $pullRequest['head']['ref'] ?? '',
                'pr_number' => (string) $pullRequest['number'],
                'pr_title' => $pullRequest['title'],
                'pr_url' => $pullRequest['html_url'],
                'pr_state' => $review['state'],
                'commit_message' => $review['body'] ?? '',
                'author_username' => $reviewerUsername,
                'author_avatar_url' => $review['user']['avatar_url'] ?? null,
                'payload' => $payload,
                'event_at' => now(),
            ]);
        }
    }

    /**
     * Handle pull request review comment events
     */
    protected function handlePullRequestReviewCommentEvent(array $payload)
    {
        $action = $payload['action'];
        $comment = $payload['comment'];
        $pullRequest = $payload['pull_request'];
        $repository = $payload['repository'];

        // Find employee by commenter
        $commenterUsername = $comment['user']['login'] ?? '';
        
        $employee = $this->findEmployeeByEmailOrUsername('', $commenterUsername);

        if ($employee) {
            GitHubLog::create([
                'employee_id' => $employee->id,
                'event_type' => 'pull_request_review_comment',
                'action' => $action,
                'repository_name' => $repository['full_name'],
                'repository_url' => $repository['html_url'],
                'pr_number' => (string) $pullRequest['number'],
                'pr_title' => $pullRequest['title'],
                'pr_url' => $pullRequest['html_url'],
                'commit_message' => $comment['body'] ?? '',
                'author_username' => $commenterUsername,
                'author_avatar_url' => $comment['user']['avatar_url'] ?? null,
                'payload' => $payload,
                'event_at' => now(),
            ]);
        }
    }

    /**
     * Handle issues events
     */
    protected function handleIssuesEvent(array $payload)
    {
        $action = $payload['action'];
        $issue = $payload['issue'];
        $repository = $payload['repository'];

        // Find employee by issue author
        $authorUsername = $issue['user']['login'] ?? '';
        
        $employee = $this->findEmployeeByEmailOrUsername('', $authorUsername);

        if ($employee) {
            GitHubLog::create([
                'employee_id' => $employee->id,
                'event_type' => 'issues',
                'action' => $action,
                'repository_name' => $repository['full_name'],
                'repository_url' => $repository['html_url'],
                'pr_number' => (string) $issue['number'],
                'pr_title' => $issue['title'],
                'pr_description' => $issue['body'],
                'pr_url' => $issue['html_url'],
                'pr_state' => $issue['state'],
                'author_username' => $authorUsername,
                'author_avatar_url' => $issue['user']['avatar_url'] ?? null,
                'payload' => $payload,
                'event_at' => now(),
            ]);
        }
    }

    /**
     * Handle issue comment events
     */
    protected function handleIssueCommentEvent(array $payload)
    {
        $action = $payload['action'];
        $comment = $payload['comment'];
        $issue = $payload['issue'];
        $repository = $payload['repository'];

        // Find employee by commenter
        $commenterUsername = $comment['user']['login'] ?? '';
        
        $employee = $this->findEmployeeByEmailOrUsername('', $commenterUsername);

        if ($employee) {
            GitHubLog::create([
                'employee_id' => $employee->id,
                'event_type' => 'issue_comment',
                'action' => $action,
                'repository_name' => $repository['full_name'],
                'repository_url' => $repository['html_url'],
                'pr_number' => (string) $issue['number'],
                'pr_title' => $issue['title'],
                'pr_url' => $issue['html_url'],
                'commit_message' => $comment['body'] ?? '',
                'author_username' => $commenterUsername,
                'author_avatar_url' => $comment['user']['avatar_url'] ?? null,
                'payload' => $payload,
                'event_at' => now(),
            ]);
        }
    }

    /**
     * Handle branch/tag creation and deletion
     */
    protected function handleBranchTagEvent(string $eventType, array $payload)
    {
        $repository = $payload['repository'];
        $sender = $payload['sender'];
        $ref = $payload['ref'];
        $refType = $payload['ref_type'];

        $senderUsername = $sender['login'] ?? '';
        
        $employee = $this->findEmployeeByEmailOrUsername('', $senderUsername);

        if ($employee) {
            GitHubLog::create([
                'employee_id' => $employee->id,
                'event_type' => $eventType,
                'action' => $refType,
                'repository_name' => $repository['full_name'],
                'repository_url' => $repository['html_url'],
                'branch' => $refType === 'branch' ? $ref : null,
                'ref' => $ref,
                'commit_message' => ucfirst($eventType) . ' ' . $refType . ': ' . $ref,
                'author_username' => $senderUsername,
                'author_avatar_url' => $sender['avatar_url'] ?? null,
                'payload' => $payload,
                'event_at' => now(),
            ]);
        }
    }

    /**
     * Find employee by email or GitHub username
     */
    protected function findEmployeeByEmailOrUsername(string $email, string $username): ?Employee
    {
        // First try to find by email
        if ($email) {
            $employee = Employee::where('email', $email)->first();
            if ($employee) {
                return $employee;
            }
        }

        // Try to find by GitHub username
        if ($username) {
            $employee = Employee::where('github_username', $username)->first();
            if ($employee) {
                return $employee;
            }
        }
        
        return null;
    }
}
