<?php

namespace App\Notifications;

use App\Models\ProjectTaskReminder;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskReminderNotification extends Notification
{
    use Queueable;

    public $reminder;

    /**
     * Create a new notification instance.
     */
    public function __construct(ProjectTaskReminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $task = $this->reminder->task;
        $project = $task->project;
        $creator = $this->reminder->creator;

        return [
            'type' => 'task_reminder',
            'title' => 'Task Reminder: ' . $task->title,
            'message' => $this->reminder->message ?? 'You have a reminder for this task',
            'task_id' => $task->id,
            'task_title' => $task->title,
            'project_id' => $project->id,
            'project_name' => $project->name,
            'creator_name' => $creator->first_name . ' ' . $creator->last_name,
            'url' => url('/projects/' . $project->id . '/tasks'),
        ];
    }
}
