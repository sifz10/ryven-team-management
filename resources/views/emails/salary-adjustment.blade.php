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
        .greeting {
            font-size: 16px;
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
        }
        .type-badge {
            display: inline-block;
            padding: 6px 12px;
            background-color: #dbeafe;
            color: #1e40af;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            margin-top: 10px;
        }
        .adjustment-amount {
            font-size: 18px;
            font-weight: 700;
            color: #059669;
            padding: 15px 0;
        }
        .adjustment-amount.negative {
            color: #dc2626;
        }
        .reason {
            background-color: #eff6ff;
            border-left: 4px solid #0284c7;
            padding: 15px;
            margin: 15px 0;
            border-radius: 4px;
            font-style: italic;
            color: #0c4a6e;
        }
        .footer {
            background-color: #f9fafb;
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            border-top: 1px solid #e5e7eb;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>💼 Salary Updated</h1>
            <p style="margin: 10px 0 0 0; font-size: 14px; opacity: 0.9;">Employee Salary Adjustment Notification</p>
        </div>

        <div class="content">
            <div class="greeting">
                Hello,<br><br>
                A salary adjustment has been recorded for <strong>{{ $employee->first_name }} {{ $employee->last_name }}</strong>.
            </div>

            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Employee:</span>
                    <span class="detail-value">{{ $employee->first_name }} {{ $employee->last_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Position:</span>
                    <span class="detail-value">{{ $employee->position ?? 'N/A' }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Old Salary:</span>
                    <span class="detail-value">{{ $adjustment->currency }} {{ number_format($adjustment->old_salary, 2) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">New Salary:</span>
                    <span class="detail-value"><strong>{{ $adjustment->currency }} {{ number_format($adjustment->new_salary, 2) }}</strong></span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Type:</span>
                    <span class="detail-value">{{ ucfirst($adjustment->type) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Date:</span>
                    <span class="detail-value">{{ $adjustment->created_at->format('F j, Y \a\t g:i A') }}</span>
                </div>
            </div>

            @if($adjustment->adjustment_amount > 0)
                <div class="adjustment-amount">
                    ✅ Increase: +{{ $adjustment->currency }} {{ number_format($adjustment->adjustment_amount, 2) }}
                </div>
            @else
                <div class="adjustment-amount negative">
                    ⚠️ Decrease: {{ $adjustment->currency }} {{ number_format($adjustment->adjustment_amount, 2) }}
                </div>
            @endif

            @if($adjustment->reason)
                <div class="reason">
                    <strong>Reason:</strong><br>
                    {{ $adjustment->reason }}
                </div>
            @endif

            <p style="margin-top: 20px; color: #6b7280;">
                This notification confirms that the salary adjustment has been processed and recorded in the system.
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0;">This is an automated notification from the Team Management System.<br>
            Please do not reply to this email.</p>
        </div>
    </div>
</body>
</html>
