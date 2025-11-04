<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Note Reminder</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6; 
            color: #e5e7eb; 
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            margin: 0; 
            padding: 40px 20px;
        }
        .container { 
            max-width: 600px; 
            margin: 0 auto; 
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .header { 
            background: linear-gradient(135deg, #3b82f6 0%, #8b5cf6 50%, #ec4899 100%);
            color: white; 
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }
        .header::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, #3b82f6, #8b5cf6, #ec4899);
        }
        .header h1 { 
            margin: 15px 0 0 0; 
            font-size: 28px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }
        .icon { 
            font-size: 64px;
            filter: drop-shadow(0 4px 12px rgba(0, 0, 0, 0.3));
        }
        .content-body {
            padding: 40px 30px;
        }
        .intro-text {
            color: #94a3b8;
            margin-bottom: 25px;
            font-size: 15px;
        }
        .badge { 
            display: inline-block; 
            padding: 8px 16px; 
            border-radius: 50px;
            font-size: 13px; 
            font-weight: 600;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }
        .badge-link { background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%); color: white; }
        .badge-password { background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%); color: white; }
        .badge-backup { background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); color: white; }
        .badge-text { background: linear-gradient(135deg, #10b981 0%, #059669 100%); color: white; }
        .badge-file { background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%); color: white; }
        .note-title { 
            font-size: 26px;
            font-weight: 700;
            color: #f1f5f9;
            margin-bottom: 20px;
            letter-spacing: -0.5px;
            line-height: 1.3;
        }
        .note-content { 
            background: rgba(15, 23, 42, 0.6);
            padding: 20px;
            border-radius: 16px;
            border-left: 4px solid #3b82f6;
            margin: 20px 0;
            white-space: pre-wrap;
            backdrop-filter: blur(10px);
            box-shadow: inset 0 2px 8px rgba(0, 0, 0, 0.3);
        }
        .note-content strong {
            color: #f1f5f9;
            display: block;
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .url-box { 
            background: rgba(59, 130, 246, 0.15);
            padding: 20px;
            border-radius: 16px;
            margin: 20px 0;
            border: 1px solid rgba(59, 130, 246, 0.3);
            backdrop-filter: blur(10px);
        }
        .url-box strong {
            display: block;
            margin-bottom: 10px;
            color: #60a5fa;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .url-box a { 
            color: #93c5fd;
            text-decoration: none;
            word-break: break-all;
            font-weight: 500;
        }
        .url-box a:hover { 
            color: #bfdbfe;
            text-decoration: underline;
        }
        .file-box { 
            background: rgba(139, 92, 246, 0.15);
            padding: 20px;
            border-radius: 16px;
            margin: 20px 0;
            border: 1px solid rgba(139, 92, 246, 0.3);
            backdrop-filter: blur(10px);
        }
        .file-box strong {
            display: block;
            margin-bottom: 10px;
            color: #a78bfa;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .file-box a { 
            color: #c4b5fd;
            text-decoration: none;
            font-weight: 500;
        }
        .file-box a:hover {
            color: #ddd6fe;
            text-decoration: underline;
        }
        .footer { 
            margin-top: 40px;
            padding: 30px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            color: #64748b;
            font-size: 14px;
            background: rgba(15, 23, 42, 0.4);
        }
        .footer p {
            margin: 8px 0;
        }
        .footer strong {
            color: #94a3b8;
        }
        .reminder-time {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(59, 130, 246, 0.2);
            padding: 12px 20px;
            border-radius: 12px;
            margin: 10px 0;
            font-weight: 600;
            color: #93c5fd;
            border: 1px solid rgba(59, 130, 246, 0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="icon">⏰</div>
            <h1>Note Reminder</h1>
        </div>

        <div class="content-body">
            <p class="intro-text">
                📬 This is a reminder about your personal note:
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
                    <strong>🔗 Website URL:</strong>
                    <a href="{{ $note->url }}" target="_blank">{{ $note->url }}</a>
                </div>
            @endif

            <!-- File -->
            @if($note->file_path)
                <div class="file-box">
                    <strong>📎 Attached File:</strong>
                    <a href="{{ url(Storage::url($note->file_path)) }}" target="_blank">Click to view file</a>
                </div>
            @endif

            <!-- Content -->
            @if($note->content)
                <div class="note-content">
                    <strong>📄 Content:</strong>
                    {{ $note->content }}
                </div>
            @endif
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="reminder-time">
                ⏰ {{ $note->reminder_time->format('M d, Y h:i A') }}
            </div>
            <p style="margin-top: 15px; font-size: 13px;">
                This is an automated reminder from your personal notes system.
            </p>
        </div>
    </div>
</body>
</html>
