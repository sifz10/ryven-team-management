<?php

namespace App\Notifications;

use App\Models\ProjectTask;
use App\Models\Employee;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\BroadcastMessage;

class TaskActionNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $task;
    public $action;
    public $actionBy;
    public $details;

    /**
     * Create a new notification instance.
     *
     * @param ProjectTask $task
     * @param string $action (created, updated, deleted, assigned, comment_added, file_uploaded, checklist_updated, etc.)
     * @param Employee $actionBy
     * @param array $details Additional details about the action
     */
    public function __construct(ProjectTask $task, string $action, Employee $actionBy, array $details = [])
    {
        $this->task = $task;
        $this->action = $action;
        $this->actionBy = $actionBy;
        $this->details = $details;
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
        $actionMessages = [
            'created' => 'created a new task',
            'updated' => 'updated task',
            'deleted' => 'deleted task',
            'assigned' => 'assigned you to task',
            'unassigned' => 'unassigned you from task',
            'comment_added' => 'commented on task',
            'file_uploaded' => 'uploaded a file to task',
            'checklist_updated' => 'updated checklist in task',
            'priority_changed' => 'changed priority of task',
            'due_date_changed' => 'changed due date of task',
            'reminder_set' => 'set a reminder for task',
        ];

        $message = "{$this->actionBy->first_name} {$this->actionBy->last_name} " . ($actionMessages[$this->action] ?? $this->action);

        if (!empty($this->details['additional_info'])) {
            $message .= ' - ' . $this->details['additional_info'];
        }

        return [
            'type' => 'task_action',
            'action' => $this->action,
            'title' => 'Task ' . ucfirst(str_replace('_', ' ', $this->action)),
            'message' => $message,
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'project_id' => $this->task->project_id,
            'project_name' => $this->task->project->name,
            'action_by' => $this->actionBy->first_name . ' ' . $this->actionBy->last_name,
            'details' => $this->details,
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
