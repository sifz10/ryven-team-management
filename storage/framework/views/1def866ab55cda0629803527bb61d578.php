<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; background-color: #f4f4f4; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 20px; border-radius: 10px 10px 0 0; margin: -30px -30px 20px -30px; }
        .header h1 { margin: 0; font-size: 24px; }
        .icon { font-size: 48px; margin-bottom: 10px; }
        .badge { display: inline-block; padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 15px; }
        .badge-link { background: #3b82f6; color: white; }
        .badge-password { background: #ef4444; color: white; }
        .badge-backup { background: #f59e0b; color: white; }
        .badge-text { background: #10b981; color: white; }
        .badge-file { background: #8b5cf6; color: white; }
        .note-title { font-size: 22px; font-weight: bold; color: #1f2937; margin-bottom: 15px; }
        .note-content { background: #f9fafb; padding: 15px; border-radius: 8px; border-left: 4px solid #667eea; margin: 15px 0; white-space: pre-wrap; }
        .url-box { background: #eff6ff; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .url-box a { color: #2563eb; text-decoration: none; word-break: break-all; }
        .url-box a:hover { text-decoration: underline; }
        .file-box { background: #f5f3ff; padding: 15px; border-radius: 8px; margin: 15px 0; }
        .file-box a { color: #7c3aed; text-decoration: none; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 2px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 14px; }
        .button { display: inline-block; padding: 12px 24px; background: #667eea; color: white; text-decoration: none; border-radius: 6px; margin: 15px 0; }
        .button:hover { background: #5a67d8; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">⏰</div>
            <h1>Note Reminder</h1>
        </div>

        <p style="color: #6b7280; margin-bottom: 20px;">
            This is a reminder about your personal note:
        </p>

        <!-- Type Badge -->
        <span class="badge 
            <?php if($note->type == 'website_link'): ?> badge-link
            <?php elseif($note->type == 'password'): ?> badge-password
            <?php elseif($note->type == 'backup_code'): ?> badge-backup
            <?php elseif($note->type == 'text'): ?> badge-text
            <?php else: ?> badge-file
            <?php endif; ?>">
            <?php if($note->type == 'website_link'): ?> 🔗 Website Link
            <?php elseif($note->type == 'password'): ?> 🔐 Password
            <?php elseif($note->type == 'backup_code'): ?> 🔑 Backup Code
            <?php elseif($note->type == 'text'): ?> 📝 Text Note
            <?php else: ?> 📎 File
            <?php endif; ?>
        </span>

        <!-- Title -->
        <div class="note-title"><?php echo e($note->title); ?></div>

        <!-- URL -->
        <?php if($note->url): ?>
            <div class="url-box">
                <strong style="display: block; margin-bottom: 8px; color: #1e40af;">🔗 Website URL:</strong>
                <a href="<?php echo e($note->url); ?>" target="_blank"><?php echo e($note->url); ?></a>
            </div>
        <?php endif; ?>

        <!-- File -->
        <?php if($note->file_path): ?>
            <div class="file-box">
                <strong style="display: block; margin-bottom: 8px; color: #6b21a8;">📎 Attached File:</strong>
                <a href="<?php echo e(url(Storage::url($note->file_path))); ?>" target="_blank">Click to view file</a>
            </div>
        <?php endif; ?>

        <!-- Content -->
        <?php if($note->content): ?>
            <div class="note-content">
                <strong style="display: block; margin-bottom: 8px; color: #374151;">Content:</strong>
                <?php echo e($note->content); ?>

            </div>
        <?php endif; ?>

        <!-- View Button -->
        <div style="text-align: center;">
            <a href="<?php echo e(url('/notes/' . $note->id)); ?>" class="button">
                View Full Note
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0;">
                <strong>Reminder Time:</strong> <?php echo e($note->reminder_time->format('M d, Y h:i A')); ?>

            </p>
            <p style="margin: 5px 0; font-size: 12px;">
                This is an automated reminder from your personal notes system.
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH F:\Project\salary\resources\views/emails/note-reminder.blade.php ENDPATH**/ ?>