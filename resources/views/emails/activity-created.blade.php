<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Activity Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse; background-color: #f3f4f6;">
        <tr>
            <td style="padding: 40px 20px;">
                <table role="presentation" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    
                    <!-- Header with Logo and Branding -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #000000 0%, #1f2937 100%); padding: 40px 30px; text-align: center;">
                            <img src="{{ asset('favicon.png') }}" alt="Ryven Logo" style="width: 60px; height: 60px; margin-bottom: 16px; border-radius: 50%; border: 3px solid #ffffff;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: bold; letter-spacing: -0.5px;">
                                Activity Notification
                            </h1>
                            <p style="margin: 8px 0 0; color: #d1d5db; font-size: 14px;">
                                New activity added to employee timeline
                            </p>
                        </td>
                    </tr>

                    <!-- Activity Type Badge -->
                    <tr>
                        <td style="padding: 30px 30px 0;">
                            @php
                                $badgeStyles = [
                                    'achievement' => 'background-color: #dcfce7; color: #15803d; border: 2px solid #86efac;',
                                    'warning' => 'background-color: #fee2e2; color: #b91c1c; border: 2px solid #fca5a5;',
                                    'payment' => 'background-color: #dbeafe; color: #1e40af; border: 2px solid #93c5fd;',
                                    'note' => 'background-color: #f3f4f6; color: #374151; border: 2px solid #d1d5db;',
                                ];
                                $badgeEmoji = [
                                    'achievement' => '🟢',
                                    'warning' => '🔴',
                                    'payment' => '🔵',
                                    'note' => '⚪',
                                ];
                                $badgeStyle = $badgeStyles[$activity->activity_type] ?? $badgeStyles['note'];
                                $emoji = $badgeEmoji[$activity->activity_type] ?? '⚪';
                            @endphp
                            <div style="text-align: center; margin-bottom: 20px;">
                                <span style="{{ $badgeStyle }} display: inline-block; padding: 10px 20px; border-radius: 50px; font-size: 14px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                    {{ $emoji }} {{ $activity->activity_type }}
                                </span>
                            </div>
                        </td>
                    </tr>

                    <!-- Employee Information -->
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <div style="background: linear-gradient(135deg, #f9fafb 0%, #f3f4f6 100%); border-radius: 12px; padding: 20px; border-left: 4px solid #000000;">
                                <h2 style="margin: 0 0 12px; color: #111827; font-size: 20px; font-weight: 700;">
                                    {{ $employee->first_name }} {{ $employee->last_name }}
                                </h2>
                                <div style="display: flex; flex-direction: column; gap: 8px;">
                                    @if($employee->position)
                                        <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                            <strong style="color: #374151;">Position:</strong> {{ $employee->position }}
                                        </p>
                                    @endif
                                    @if($employee->department)
                                        <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                            <strong style="color: #374151;">Department:</strong> {{ $employee->department }}
                                        </p>
                                    @endif
                                    <p style="margin: 0; color: #6b7280; font-size: 14px;">
                                        <strong style="color: #374151;">Employee ID:</strong> #{{ $employee->id }}
                                    </p>
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Activity Details -->
                    <tr>
                        <td style="padding: 0 30px 20px;">
                            <h3 style="margin: 0 0 16px; color: #111827; font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">
                                Activity Details
                            </h3>
                            <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                                        <strong style="color: #374151; font-size: 14px;">Date:</strong>
                                    </td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                                        <span style="color: #6b7280; font-size: 14px;">
                                            {{ \Carbon\Carbon::parse($activity->paid_at)->format('F j, Y \a\t g:i A') }}
                                        </span>
                                    </td>
                                </tr>
                                @if($activity->amount)
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb;">
                                        <strong style="color: #374151; font-size: 14px;">Amount:</strong>
                                    </td>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e7eb; text-align: right;">
                                        <span style="color: #059669; font-size: 16px; font-weight: 700;">
                                            {{ number_format($activity->amount, 2) }} {{ $activity->currency }}
                                        </span>
                                    </td>
                                </tr>
                                @endif
                                @if($activity->note)
                                <tr>
                                    <td colspan="2" style="padding: 16px 0;">
                                        <strong style="color: #374151; font-size: 14px; display: block; margin-bottom: 8px;">Note:</strong>
                                        <div style="background-color: #f9fafb; border-radius: 8px; padding: 16px; color: #4b5563; font-size: 14px; line-height: 1.6; border-left: 3px solid #d1d5db;">
                                            {{ $activity->note }}
                                        </div>
                                    </td>
                                </tr>
                                @endif
                            </table>
                        </td>
                    </tr>

                    <!-- Call to Action Button -->
                    <tr>
                        <td style="padding: 0 30px 30px; text-align: center;">
                            <a href="{{ route('employees.show', ['employee' => $employee, 'tab' => 'timeline']) }}" 
                               style="display: inline-block; background-color: #000000; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 50px; font-size: 14px; font-weight: 700; letter-spacing: 0.5px; transition: all 0.3s ease; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.2);">
                                📋 View Full Timeline
                            </a>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0 0 8px; color: #6b7280; font-size: 12px;">
                                This is an automated notification from <strong style="color: #111827;">Ryven Salary Management System</strong>
                            </p>
                            <p style="margin: 0; color: #9ca3af; font-size: 11px;">
                                &copy; {{ date('Y') }} Ryven. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>

