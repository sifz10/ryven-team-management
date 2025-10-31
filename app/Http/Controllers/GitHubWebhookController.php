<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\GitHubLog;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GitHubWebhookController extends Controller
{
    /**
     * Load GitHub activities for an employee (AJAX endpoint)
     */
    public function loadActivities(Request $request, Employee $employee)
    {
        $perPage = 15;
        $page = $request->input('page', 1);
        
        // Build query with filters
        $query = $employee->githubLogs();
        
        // Apply date filters
        if ($request->filled('start_date')) {
            $query->whereDate('event_at', '>=', $request->input('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('event_at', '<=', $request->input('end_date'));
        }
        
        // Apply event type filter
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }
        
        // Apply repository filter
        if ($request->filled('repository')) {
            $query->where('repository_name', $request->input('repository'));
        }
        
        // Get paginated results
        $logs = $query->orderBy('event_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
        
        // Render HTML for activities
        $html = view('employees.partials.github-activity-item', [
            'githubLogs' => $logs,
            'employee' => $employee
        ])->render();
        
        return response()->json([
            'success' => true,
            'html' => $html,
            'hasMore' => $logs->hasMorePages(),
            'nextPage' => $logs->currentPage() + 1,
            'total' => $logs->total()
        ]);
    }

    /**
     * Check for new activities (real-time polling)
     */
    public function checkNew(Request $request, Employee $employee)
    {
        $lastId = $request->input('last_id', 0);
        
        // Build query with filters
        $query = $employee->githubLogs()->where('id', '>', $lastId);
        
        // Apply same filters as main view
        if ($request->filled('start_date')) {
            $query->whereDate('event_at', '>=', $request->input('start_date'));
        }
        
        if ($request->filled('end_date')) {
            $query->whereDate('event_at', '<=', $request->input('end_date'));
        }
        
        if ($request->filled('event_type')) {
            $query->where('event_type', $request->input('event_type'));
        }
        
        if ($request->filled('repository')) {
            $query->where('repository_name', $request->input('repository'));
        }
        
        $newLogs = $query->orderBy('event_at', 'desc')->get();
        
        if ($newLogs->isEmpty()) {
            return response()->json([
                'success' => true,
                'hasNew' => false,
                'count' => 0
            ]);
        }
        
        // Render HTML for new activities
        $html = view('employees.partials.github-activity-item', [
            'githubLogs' => $newLogs,
            'employee' => $employee
        ])->render();
        
        return response()->json([
            'success' => true,
            'hasNew' => true,
            'count' => $newLogs->count(),
            'html' => $html,
            'latestId' => $newLogs->first()->id
        ]);
    }

    /**
     * Display all GitHub webhook logs with filters
     */
    public function logs(Request $request)
    {
        // Get filters from request
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $eventType = $request->input('event_type');
        $repository = $request->input('repository');
        $employeeId = $request->input('employee_id');
        
        // Build query
        $logsQuery = GitHubLog::with('employee');
        $statsQuery = GitHubLog::query();
        
        // Apply filters
        if ($startDate) {
            $logsQuery->whereDate('event_at', '>=', $startDate);
            $statsQuery->whereDate('event_at', '>=', $startDate);
        }
        
        if ($endDate) {
            $logsQuery->whereDate('event_at', '<=', $endDate);
            $statsQuery->whereDate('event_at', '<=', $endDate);
        }
        
        if ($eventType) {
            $logsQuery->where('event_type', $eventType);
        }
        
        if ($repository) {
            $logsQuery->where('repository_name', $repository);
        }
        
        if ($employeeId) {
            $logsQuery->where('employee_id', $employeeId);
            $statsQuery->where('employee_id', $employeeId);
        }
        
        // Get paginated logs
        $logs = $logsQuery->orderBy('event_at', 'desc')->paginate(50);
        
        // Get statistics
        $totalLogs = (clone $statsQuery)->count();
        $pushCount = (clone $statsQuery)->where('event_type', 'push')->count();
        $prCount = (clone $statsQuery)->where('event_type', 'pull_request')->count();
        $totalCommits = (clone $statsQuery)->where('event_type', 'push')->sum('commits_count') ?: 0;
        
        // Get unique values for filters
        $repositories = GitHubLog::select('repository_name')
            ->groupBy('repository_name')
            ->orderBy('repository_name')
            ->pluck('repository_name');
        
        $eventTypes = GitHubLog::select('event_type')
            ->groupBy('event_type')
            ->orderBy('event_type')
            ->pluck('event_type');
        
        $employees = Employee::orderBy('first_name')->get();
        
        return view('github-logs', compact(
            'logs',
            'totalLogs',
            'pushCount',
            'prCount',
            'totalCommits',
            'repositories',
            'eventTypes',
            'employees',
            'startDate',
            'endDate',
            'eventType',
            'repository',
            'employeeId'
        ));
    }

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
                
                $log = GitHubLog::create([
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

                // Create notification
                $this->createNotification($log);
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
            $log = GitHubLog::create([
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

            // Create notification
            $this->createNotification($log);
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

    /**
     * Create notification for GitHub log
     */
    protected function createNotification(GitHubLog $log): void
    {
        // Get all users to notify (you can customize this logic)
        $users = User::all();

        $title = $this->getNotificationTitle($log);
        $message = $this->getNotificationMessage($log);

        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'github_log',
                'title' => $title,
                'message' => $message,
                'icon' => 'github',
                'data' => [
                    'log_id' => $log->id,
                    'employee_id' => $log->employee_id,
                    'event_type' => $log->event_type,
                    'repository_name' => $log->repository_name,
                    'url' => $log->commit_url ?? $log->pr_url ?? $log->repository_url,
                ],
            ]);
        }
    }

    /**
     * Get notification title based on event type
     */
    protected function getNotificationTitle(GitHubLog $log): string
    {
        $employee = $log->employee;
        $name = $employee ? "{$employee->first_name} {$employee->last_name}" : $log->author_username;

        return match($log->event_type) {
            'push' => "🚀 New Push by {$name}",
            'pull_request' => "🔀 New Pull Request by {$name}",
            'pull_request_review' => "👀 New PR Review by {$name}",
            'issues' => "🐛 New Issue by {$name}",
            'create' => "✨ Branch/Tag Created by {$name}",
            'delete' => "🗑️ Branch/Tag Deleted by {$name}",
            default => "📝 New GitHub Activity by {$name}",
        };
    }

    /**
     * Get notification message based on event type
     */
    protected function getNotificationMessage(GitHubLog $log): string
    {
        $repo = $log->repository_name;
        
        return match($log->event_type) {
            'push' => "{$log->commits_count} commit(s) pushed to {$repo}",
            'pull_request' => "PR #{$log->pr_number}: {$log->pr_title} in {$repo}",
            'pull_request_review' => "Reviewed PR #{$log->pr_number} in {$repo}",
            'issues' => "Issue #{$log->pr_number}: {$log->pr_title} in {$repo}",
            'create' => "{$log->action} '{$log->ref}' in {$repo}",
            'delete' => "{$log->action} '{$log->ref}' in {$repo}",
            default => "Activity in {$repo}",
        };
    }
}
