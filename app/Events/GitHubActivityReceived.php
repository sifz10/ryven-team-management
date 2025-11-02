<?php

namespace App\Events;

use App\Models\GitHubLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GitHubActivityReceived implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public GitHubLog $log;

    /**
     * Create a new event instance.
     */
    public function __construct(GitHubLog $log)
    {
        $this->log = $log;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('github-activities'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'activity.received';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'log' => [
                'id' => $this->log->id,
                'event_type' => $this->log->event_type,
                'action' => $this->log->action,
                'repository_name' => $this->log->repository_name,
                'repository_url' => $this->log->repository_url,
                'branch' => $this->log->branch,
                'commit_message' => $this->log->commit_message,
                'commits_count' => $this->log->commits_count,
                'pr_number' => $this->log->pr_number,
                'pr_title' => $this->log->pr_title,
                'pr_state' => $this->log->pr_state,
                'pr_merged' => $this->log->pr_merged,
                'author_username' => $this->log->author_username,
                'author_avatar_url' => $this->log->author_avatar_url,
                'event_at' => $this->log->event_at->toIso8601String(),
                'employee' => [
                    'id' => $this->log->employee->id ?? null,
                    'first_name' => $this->log->employee->first_name ?? 'Unknown',
                    'last_name' => $this->log->employee->last_name ?? '',
                ],
            ],
        ];
    }
}
