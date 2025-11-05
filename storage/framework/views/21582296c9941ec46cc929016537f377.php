<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Checklist</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }
        .header p {
            margin: 8px 0 0 0;
            opacity: 0.9;
            font-size: 14px;
        }
        .content {
            padding: 30px 20px;
        }
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #1f2937;
        }
        .checklist-info {
            background-color: #f9fafb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #1f2937;
        }
        .checklist-info h2 {
            margin: 0 0 8px 0;
            font-size: 18px;
            color: #1f2937;
        }
        .checklist-info p {
            margin: 0;
            color: #6b7280;
            font-size: 14px;
        }
        .checklist-items {
            margin: 20px 0;
        }
        .checklist-item {
            background-color: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        .checklist-item.completed {
            background-color: #f0fdf4;
            border-color: #86efac;
        }
        .checkbox-container {
            flex-shrink: 0;
            margin-top: 2px;
        }
        .checkbox {
            width: 20px;
            height: 20px;
            border: 2px solid #d1d5db;
            border-radius: 4px;
            display: inline-block;
            background-color: white;
        }
        .checkbox.checked {
            background-color: #22c55e;
            border-color: #22c55e;
            position: relative;
        }
        .checkbox.checked::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
        }
        .item-content {
            flex: 1;
        }
        .item-title {
            font-size: 15px;
            color: #1f2937;
            margin: 0 0 4px 0;
        }
        .item-title.completed {
            text-decoration: line-through;
            color: #6b7280;
        }
        .item-timestamp {
            font-size: 12px;
            color: #16a34a;
            margin: 0;
        }
        .action-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #1f2937;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            margin-top: 8px;
        }
        .action-button:hover {
            background-color: #374151;
        }
        .completion-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #dbeafe;
            color: #1e40af;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 15px;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 13px;
            border-top: 1px solid #e5e7eb;
        }
        .footer a {
            color: #1f2937;
            text-decoration: none;
            font-weight: 500;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Daily Checklist</h1>
            <p><?php echo e(now()->format('l, F j, Y')); ?></p>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <?php echo e($employee->first_name); ?>!
            </div>
            
            <div class="checklist-info">
                <h2><?php echo e($dailyChecklist->template->title); ?></h2>
                <?php if($dailyChecklist->template->description): ?>
                    <p><?php echo e($dailyChecklist->template->description); ?></p>
                <?php endif; ?>
            </div>
            
            <?php
                $completedCount = $dailyChecklist->items->where('is_completed', true)->count();
                $totalCount = $dailyChecklist->items->count();
                $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
            ?>
            
            <div class="completion-badge">
                Progress: <?php echo e($completedCount); ?>/<?php echo e($totalCount); ?> (<?php echo e($percentage); ?>%)
            </div>
            
            <div class="checklist-items">
                <?php $__currentLoopData = $dailyChecklist->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="checklist-item <?php echo e($item->is_completed ? 'completed' : ''); ?>">
                        <div class="checkbox-container">
                            <div class="checkbox <?php echo e($item->is_completed ? 'checked' : ''); ?>"></div>
                        </div>
                        <div class="item-content">
                            <p class="item-title <?php echo e($item->is_completed ? 'completed' : ''); ?>">
                                <?php echo e($item->title); ?>

                            </p>
                            <?php if($item->completed_at): ?>
                                <p class="item-timestamp">
                                    ✓ Completed at <?php echo e($item->completed_at->format('g:i A')); ?>

                                </p>
                            <?php endif; ?>
                            <?php if(!$item->is_completed): ?>
                                <a href="<?php echo e(route('checklist.public.toggle', ['token' => $dailyChecklist->email_token, 'item' => $item->id])); ?>" 
                                   class="action-button">
                                    Mark as Complete
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
            
            <p style="margin-top: 30px; color: #6b7280; font-size: 14px;">
                You can check off items directly from this email by clicking the "Mark as Complete" buttons above.
            </p>
            <?php if($dailyChecklist->email_sent_at): ?>
            <div style="margin-top: 15px; padding: 12px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;">
                <p style="margin: 0; color: #92400e; font-size: 13px; font-weight: 500;">
                    ⏰ <strong>Important:</strong> This link will expire in 12 hours from the time it was sent. Complete your checklist before <?php echo e($dailyChecklist->email_sent_at->copy()->addHours(12)->format('M d, g:i A')); ?>.
                </p>
            </div>
            <?php else: ?>
            <div style="margin-top: 15px; padding: 12px; background-color: #fef3c7; border-left: 4px solid #f59e0b; border-radius: 6px;">
                <p style="margin: 0; color: #92400e; font-size: 13px; font-weight: 500;">
                    ⏰ <strong>Important:</strong> This link will expire in 12 hours from the time it was sent.
                </p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="footer">
            <p>This is an automated checklist from your employee management system.</p>
            <p>
                <a href="<?php echo e(route('employees.show', $employee)); ?>">View Full Profile</a>
            </p>
        </div>
    </div>
</body>
</html>

<?php /**PATH F:\Project\salary\resources\views/emails/daily-checklist.blade.php ENDPATH**/ ?>