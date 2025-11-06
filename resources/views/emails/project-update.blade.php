<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Project Update - {{ $project->name }}</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #0f172a;
            color: #e2e8f0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #1e293b;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .header {
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            padding: 40px 30px;
            text-align: center;
            border-bottom: 3px solid #334155;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
            color: #ffffff;
        }
        .header p {
            margin: 10px 0 0 0;
            font-size: 14px;
            color: #94a3b8;
        }
        .content {
            padding: 30px;
        }
        .project-info {
            background-color: #334155;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 25px;
        }
        .project-info h2 {
            margin: 0 0 15px 0;
            font-size: 20px;
            color: #ffffff;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #475569;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #94a3b8;
            font-size: 14px;
        }
        .info-value {
            color: #e2e8f0;
            font-size: 14px;
        }
        .work-section {
            margin-top: 30px;
        }
        .work-section h3 {
            font-size: 18px;
            color: #ffffff;
            margin: 0 0 20px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #475569;
        }
        .work-item {
            background-color: #334155;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            border-left: 4px solid #3b82f6;
        }
        .work-item:last-child {
            margin-bottom: 0;
        }
        .work-meta {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .employee-name {
            font-weight: 600;
            color: #ffffff;
            font-size: 15px;
        }
        .work-time {
            font-size: 13px;
            color: #94a3b8;
        }
        .work-description {
            color: #cbd5e1;
            line-height: 1.6;
            font-size: 14px;
        }
        .footer {
            background-color: #0f172a;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #334155;
        }
        .footer p {
            margin: 0;
            font-size: 13px;
            color: #64748b;
        }
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
        }
        .badge-active {
            background-color: #10b981;
            color: #ffffff;
        }
        .badge-priority {
            background-color: #f59e0b;
            color: #ffffff;
        }
        .no-work {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>📊 Daily Project Update</h1>
            <p>{{ $date }}</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Project Info -->
            <div class="project-info">
                <h2>{{ $project->name }}</h2>
                @if($project->description)
                    <p style="color: #94a3b8; margin: 10px 0 15px 0; font-size: 14px;">{{ $project->description }}</p>
                @endif
                <div class="info-row">
                    <span class="info-label">Status</span>
                    <span class="info-value">
                        <span class="badge badge-active">{{ ucfirst($project->status) }}</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Priority</span>
                    <span class="info-value">
                        <span class="badge badge-priority">{{ $project->priority_label }}</span>
                    </span>
                </div>
                @if($project->budget)
                <div class="info-row">
                    <span class="info-label">Budget</span>
                    <span class="info-value">{{ $project->currency }} {{ number_format($project->budget, 2) }}</span>
                </div>
                @endif
            </div>

            <!-- Work Updates -->
            <div class="work-section">
                <h3>🔨 Today's Progress ({{ $workSubmissions->count() }} {{ $workSubmissions->count() === 1 ? 'update' : 'updates' }})</h3>
                
                @forelse($workSubmissions as $submission)
                    <div class="work-item">
                        <div class="work-meta">
                            <span class="employee-name">👤 {{ $submission->employee->first_name }} {{ $submission->employee->last_name }}</span>
                            <span class="work-time">⏰ {{ $submission->created_at->format('g:i A') }}</span>
                        </div>
                        <div class="work-description">
                            {{ $submission->work_description }}
                        </div>
                    </div>
                @empty
                    <div class="no-work">
                        <p style="font-size: 16px; margin-bottom: 8px;">📭 No work logged today</p>
                        <p style="font-size: 14px;">Your team hasn't submitted any updates for this project yet.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>This is an automated report from Ryven Team Management System</p>
            <p style="margin-top: 8px;">© {{ date('Y') }} Ryven. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
