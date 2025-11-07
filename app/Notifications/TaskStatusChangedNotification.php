<?php

namespace App\Notifications;

use App\Models\ProjectTask;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskStatusChangedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $oldStatus;
    public $newStatus;
    public $changedBy;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProjectTask $task, $oldStatus, $newStatus, Employee $changedBy)
    {
        $this->task = $task;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $statusLabels = [
            'todo' => 'To Do',
            'on-hold' => 'On Hold',
            'in-progress' => 'In Progress',
            'awaiting-feedback' => 'Awaiting Feedback',
            'staging' => 'Staging',
            'live' => 'Live',
            'completed' => 'Completed',
        ];

        $oldStatusLabel = $statusLabels[$this->oldStatus] ?? $this->oldStatus;
        $newStatusLabel = $statusLabels[$this->newStatus] ?? $this->newStatus;

        return [
            'type' => 'task_status_changed',
            'title' => 'Task Status Changed',
            'message' => "{$this->changedBy->first_name} {$this->changedBy->last_name} changed status from {$oldStatusLabel} to {$newStatusLabel}",
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'project_name' => $this->task->project->name,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'changed_by' => $this->changedBy->first_name . ' ' . $this->changedBy->last_name,
            'url' => url('/projects/' . $this->task->project_id . '?tab=tasks'),
            'created_at' => now()->toISOString(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
