<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #000000 0%, #434343 100%);
            color: white;
            padding: 30px;
            border-radius: 12px 12px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-top: none;
            padding: 30px;
            border-radius: 0 0 12px 12px;
        }
        .ticket-badge {
            display: inline-block;
            padding: 8px 16px;
            background: #f3f4f6;
            border-radius: 20px;
            font-weight: bold;
            font-size: 14px;
            margin: 10px 0;
        }
        .notification-box {
            background: #f9fafb;
            border-left: 4px solid #000000;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .comment-box {
            background: #f3f4f6;
            padding: 15px;
            margin: 15px 0;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .status-open { background: #dbeafe; color: #1e40af; }
        .status-in-progress { background: #fef3c7; color: #92400e; }
        .status-resolved { background: #d1fae5; color: #065f46; }
        .status-closed { background: #f3f4f6; color: #4b5563; }
        .priority-critical { background: #fee2e2; color: #991b1b; }
        .priority-high { background: #fed7aa; color: #9a3412; }
        .priority-medium { background: #dbeafe; color: #1e40af; }
        .priority-low { background: #f3f4f6; color: #4b5563; }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: #000000;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background: #434343;
        }
        .footer {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
        }
        .info-row {
            margin: 10px 0;
            padding: 8px 0;
        }
        .info-label {
            font-weight: 600;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .info-value {
            margin-top: 4px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎫 Ticket Notification</h1>
    </div>

    <div class="content">
        <div class="notification-box">
            <strong>{{ $message }}</strong>
        </div>

        <div class="ticket-badge">
            {{ $ticket->ticket_number }}
        </div>

        <h2 style="margin: 20px 0 10px 0;">{{ $ticket->title }}</h2>

        <div style="margin: 15px 0;">
            <span class="status-badge status-{{ $ticket->status }}">{{ ucfirst(str_replace('-', ' ', $ticket->status)) }}</span>
            <span class="priority-badge priority-{{ $ticket->priority }}">{{ ucfirst($ticket->priority) }} Priority</span>
        </div>

        <div class="info-row">
            <div class="info-label">Project</div>
            <div class="info-value">{{ $ticket->project->name }}</div>
        </div>

        @if($ticket->assignedTo)
        <div class="info-row">
            <div class="info-label">Assigned To</div>
            <div class="info-value">{{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}</div>
        </div>
        @endif

        @if($triggeredBy)
        <div class="info-row">
            <div class="info-label">
                @if($notificationType === 'created')
                    Created By
                @elseif($notificationType === 'replied')
                    Replied By
                @else
                    Updated By
                @endif
            </div>
            <div class="info-value">{{ $triggeredBy->first_name }} {{ $triggeredBy->last_name }}</div>
        </div>
        @endif

        @if($commentText)
        <div class="info-row">
            <div class="info-label">Comment</div>
            <div class="comment-box">
                {!! nl2br(e($commentText)) !!}
            </div>
        </div>
        @endif

        <div class="info-row">
            <div class="info-label">Description</div>
            <div class="info-value" style="color: #6b7280;">
                {{ Str::limit($ticket->description, 200) }}
            </div>
        </div>

        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('tickets.show', $ticket) }}" class="button">
                View Ticket Details
            </a>
        </div>
    </div>

    <div class="footer">
        <p>This is an automated notification from {{ config('app.name') }}.</p>
        <p>You received this email because you are involved with ticket {{ $ticket->ticket_number }}.</p>
    </div>
</body>
</html>
