<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Reminder</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #fbbf24;
        }
        .header h1 {
            color: #1f2937;
            margin: 0;
            font-size: 24px;
        }
        .reminder-icon {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .task-details {
            background-color: #fffbeb;
            border-left: 4px solid #fbbf24;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .task-title {
            font-size: 20px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 15px;
        }
        .detail-row {
            margin: 10px 0;
            display: flex;
            align-items: flex-start;
        }
        .detail-label {
            font-weight: 600;
            color: #6b7280;
            min-width: 100px;
        }
        .detail-value {
            color: #1f2937;
            flex: 1;
        }
        .message-box {
            background-color: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .message-box p {
            margin: 0;
            color: #4b5563;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #000000;
            color: #ffffff;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
            text-align: center;
            margin: 20px 0;
        }
        .btn:hover {
            background-color: #1f2937;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-todo { background-color: #e5e7eb; color: #1f2937; }
        .status-in-progress { background-color: #dbeafe; color: #1e40af; }
        .status-on-hold { background-color: #fef3c7; color: #92400e; }
        .status-awaiting-feedback { background-color: #fed7aa; color: #9a3412; }
        .status-staging { background-color: #e9d5ff; color: #6b21a8; }
        .status-live { background-color: #dbeafe; color: #1e40af; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
        .priority-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
        }
        .priority-critical { background-color: #fee2e2; color: #991b1b; }
        .priority-high { background-color: #fed7aa; color: #9a3412; }
        .priority-medium { background-color: #dbeafe; color: #1e40af; }
        .priority-low { background-color: #e5e7eb; color: #1f2937; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="reminder-icon">🔔</div>
            <h1>Task Reminder</h1>
        </div>

        <p>Hello <?php echo e($recipient ? ($reminder->recipient_type === 'employee' ? $recipient->first_name . ' ' . $recipient->last_name : $recipient->name) : 'there'); ?>,</p>

        <p>This is a reminder about the following task:</p>

        <div class="task-details">
            <div class="task-title"><?php echo e($task->title); ?></div>

            <div class="detail-row">
                <span class="detail-label">Project:</span>
                <span class="detail-value"><?php echo e($project->name); ?></span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge status-<?php echo e($task->status); ?>">
                        <?php echo e(ucwords(str_replace('-', ' ', $task->status))); ?>

                    </span>
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">Priority:</span>
                <span class="detail-value">
                    <span class="priority-badge priority-<?php echo e($task->priority); ?>">
                        <?php echo e(ucfirst($task->priority)); ?>

                    </span>
                </span>
            </div>

            <?php if($task->due_date): ?>
            <div class="detail-row">
                <span class="detail-label">Due Date:</span>
                <span class="detail-value"><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('F j, Y')); ?></span>
            </div>
            <?php endif; ?>

            <?php if($task->assignedTo): ?>
            <div class="detail-row">
                <span class="detail-label">Assigned To:</span>
                <span class="detail-value"><?php echo e($task->assignedTo->first_name); ?> <?php echo e($task->assignedTo->last_name); ?></span>
            </div>
            <?php endif; ?>
        </div>

        <?php if($reminder->message): ?>
        <div class="message-box">
            <p><strong>Note from <?php echo e($reminder->creator->first_name); ?> <?php echo e($reminder->creator->last_name); ?>:</strong></p>
            <p><?php echo e($reminder->message); ?></p>
        </div>
        <?php endif; ?>

        <div style="text-align: center;">
            <a href="<?php echo e(url('/projects/' . $project->id . '/tasks')); ?>" class="btn">
                View Task Details
            </a>
        </div>

        <div class="footer">
            <p>This reminder was set by <strong><?php echo e($reminder->creator->first_name); ?> <?php echo e($reminder->creator->last_name); ?></strong></p>
            <p><?php echo e(config('app.name')); ?> - Team Management System</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH F:\Project\salary\resources\views/emails/task-reminder.blade.php ENDPATH**/ ?>