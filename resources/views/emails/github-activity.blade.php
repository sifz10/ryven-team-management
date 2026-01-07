<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: 700;
        }
        .content {
            padding: 30px;
            color: #374151;
            line-height: 1.6;
        }
        .event-badge {
            display: inline-block;
            padding: 8px 16px;
            background-color: #dbeafe;
            color: #1e40af;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .details-box {
            background-color: #f9fafb;
            border-left: 4px solid #3b82f6;
            padding: 20px;
            margin: 20px 0;
            border-radius: 6px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #1f2937;
        }
        .detail-value {
            color: #6b7280;
            word-break: break-all;
        }
        .code-block {
            background-color: #1f2937;
            color: #e5e7eb;
            padding: 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 12px;
            overflow-x: auto;
            margin: 15px 0;
        }
        .action-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #ecfdf5;
            color: #065f46;
            border-radius: 6px;
            font-weight: 600;
            font-size: 12px;
            margin-top: 10px;
        }
        .action-badge.opened {
            background-color: #ecfdf5;
            color: #065f46;
        }
        .action-badge.closed {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .action-badge.merged {
            background-color: #f3e8ff;
            color: #6b21a8;
        }
        .repository-link {
            display: inline-block;
            color: #2563eb;
            text-decoration: none;
            margin-top: 15px;
            padding: 12px 24px;
            background-color: #eff6ff;
            border-radius: 6px;
            border: 1px solid #bfdbfe;
            font-weight: 600;
        }
        .repository-link:hover {
            background-color: #dbeafe;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚀 GitHub Activity Recorded</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Developer Activity Notification</p>
        </div>

        <div class="content">
            <span class="event-badge">
                @switch($activity->event_type)
                    @case('push')
                        📤 Push
                        @break
                    @case('pull_request')
                        🔀 Pull Request
                        @break
                    @case('pull_request_review')
                        🔍 PR Review
                        @break
                    @case('issues')
                        📋 Issue
                        @break
                    @case('issue_comment')
                        💬 Issue Comment
                        @break
                    @case('create')
                        ✨ Create
                        @break
                    @case('delete')
                        🗑️ Delete
                        @break
                    @default
                        📌 Activity
                @endswitch
            </span>

            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Repository:</span>
                    <span class="detail-value"><strong>{{ $activity->repository_name }}</strong></span>
                </div>

                @if($activity->event_type === 'push')
                    <div class="detail-row">
                        <span class="detail-label">Branch:</span>
                        <span class="detail-value">{{ $activity->branch }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Commits:</span>
                        <span class="detail-value">{{ $activity->commits_count }}</span>
                    </div>
                @endif

                @if($activity->event_type === 'pull_request')
                    <div class="detail-row">
                        <span class="detail-label">PR Number:</span>
                        <span class="detail-value">#{{ $activity->pr_number }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Title:</span>
                        <span class="detail-value">{{ $activity->pr_title }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Status:</span>
                        <span class="detail-value">
                            @if($activity->pr_merged)
                                <span class="action-badge merged">Merged</span>
                            @else
                                <span class="action-badge {{ strtolower($activity->pr_state) }}">{{ ucfirst($activity->pr_state) }}</span>
                            @endif
                        </span>
                    </div>
                @endif

                <div class="detail-row">
                    <span class="detail-label">Time:</span>
                    <span class="detail-value">{{ $activity->event_at->format('F j, Y \a\t g:i A') }}</span>
                </div>

                <div class="detail-row">
                    <span class="detail-label">Author:</span>
                    <span class="detail-value">{{ $activity->author_username }}</span>
                </div>
            </div>

            @if($activity->commit_message)
                <h3 style="margin: 20px 0 10px 0; color: #1f2937;">Commit Message:</h3>
                <div class="code-block">{{ $activity->commit_message }}</div>
            @endif

            @if($activity->pr_description)
                <h3 style="margin: 20px 0 10px 0; color: #1f2937;">Pull Request Description:</h3>
                <div style="background-color: #f9fafb; padding: 15px; border-left: 4px solid #3b82f6; border-radius: 6px; color: #374151;">
                    {{ substr($activity->pr_description, 0, 500) }}{{ strlen($activity->pr_description) > 500 ? '...' : '' }}
                </div>
            @endif

            @if($activity->repository_url)
                <div style="text-align: center; margin-top: 20px;">
                    <a href="{{ $activity->repository_url }}" class="repository-link">
                        View on GitHub →
                    </a>
                </div>
            @endif
        </div>

        <div class="footer">
            <p style="margin: 0;">This is an automated notification from the Team Management System.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
