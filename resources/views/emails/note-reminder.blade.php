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
            @if($note->type == 'website_link') badge-link
            @elseif($note->type == 'password') badge-password
            @elseif($note->type == 'backup_code') badge-backup
            @elseif($note->type == 'text') badge-text
            @else badge-file
            @endif">
            @if($note->type == 'website_link') 🔗 Website Link
            @elseif($note->type == 'password') 🔐 Password
            @elseif($note->type == 'backup_code') 🔑 Backup Code
            @elseif($note->type == 'text') 📝 Text Note
            @else 📎 File
            @endif
        </span>

        <!-- Title -->
        <div class="note-title">{{ $note->title }}</div>

        <!-- URL -->
        @if($note->url)
            <div class="url-box">
                <strong style="display: block; margin-bottom: 8px; color: #1e40af;">🔗 Website URL:</strong>
                <a href="{{ $note->url }}" target="_blank">{{ $note->url }}</a>
            </div>
        @endif

        <!-- File -->
        @if($note->file_path)
            <div class="file-box">
                <strong style="display: block; margin-bottom: 8px; color: #6b21a8;">📎 Attached File:</strong>
                <a href="{{ url(Storage::url($note->file_path)) }}" target="_blank">Click to view file</a>
            </div>
        @endif

        <!-- Content -->
        @if($note->content)
            <div class="note-content">
                <strong style="display: block; margin-bottom: 8px; color: #374151;">Content:</strong>
                {{ $note->content }}
            </div>
        @endif

        <!-- View Button -->
        <div style="text-align: center;">
            <a href="{{ url('/notes/' . $note->id) }}" class="button">
                View Full Note
            </a>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p style="margin: 5px 0;">
                <strong>Reminder Time:</strong> {{ $note->reminder_time->format('M d, Y h:i A') }}
            </p>
            <p style="margin: 5px 0; font-size: 12px;">
                This is an automated reminder from your personal notes system.
            </p>
        </div>
    </div>
</body>
</html>
