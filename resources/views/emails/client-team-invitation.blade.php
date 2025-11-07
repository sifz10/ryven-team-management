<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6;">
    <table role="presentation" style="width: 100%; border-collapse: collapse;">
        <tr>
            <td align="center" style="padding: 40px 0;">
                <table role="presentation" style="width: 600px; border-collapse: collapse; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 30px; background: linear-gradient(135deg, #000000 0%, #434343 100%); border-radius: 8px 8px 0 0; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">Team Invitation</h1>
                            <p style="margin: 10px 0 0; color: #e5e7eb; font-size: 16px;">Join {{ $client->name }}'s Client Portal</p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; color: #374151; font-size: 16px; line-height: 24px;">
                                Hello <strong>{{ $teamMember->name }}</strong>,
                            </p>

                            <p style="margin: 0 0 20px; color: #374151; font-size: 16px; line-height: 24px;">
                                You have been invited to join the <strong>{{ $client->name }}</strong> team on our client portal. You'll be able to view projects, collaborate with the team, and access important documents.
                            </p>

                            <!-- Login Credentials Box -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0; background-color: #f9fafb; border: 2px solid #e5e7eb; border-radius: 8px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <h3 style="margin: 0 0 16px; color: #111827; font-size: 18px; font-weight: 600;">Your Login Credentials</h3>
                                        <table role="presentation" style="width: 100%; border-collapse: collapse;">
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px; font-weight: 500;">Email:</td>
                                                <td style="padding: 8px 0; color: #111827; font-size: 14px; font-family: 'Courier New', monospace; font-weight: 600;">{{ $teamMember->email }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px; font-weight: 500;">Password:</td>
                                                <td style="padding: 8px 0; color: #111827; font-size: 14px; font-family: 'Courier New', monospace; font-weight: 600;">{{ $password }}</td>
                                            </tr>
                                            <tr>
                                                <td style="padding: 8px 0; color: #6b7280; font-size: 14px; font-weight: 500;">Role:</td>
                                                <td style="padding: 8px 0; color: #111827; font-size: 14px; font-weight: 600;">{{ $teamMember->role ?? 'Team Member' }}</td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Notice -->
                            <div style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; margin: 20px 0; border-radius: 4px;">
                                <p style="margin: 0; color: #92400e; font-size: 14px; line-height: 20px;">
                                    <strong>🔒 Security Notice:</strong> For your security, you will be required to change your password when you first login.
                                </p>
                            </div>

                            <!-- CTA Button -->
                            <table role="presentation" style="width: 100%; border-collapse: collapse; margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ $acceptUrl }}" style="display: inline-block; padding: 14px 32px; background-color: #000000; color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px;">Accept Invitation & Login</a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin: 20px 0 0; color: #6b7280; font-size: 14px; line-height: 20px;">
                                If the button doesn't work, copy and paste this link into your browser:<br>
                                <a href="{{ $acceptUrl }}" style="color: #2563eb; text-decoration: none;">{{ $acceptUrl }}</a>
                            </p>

                            <!-- What You Can Do -->
                            <div style="margin-top: 30px; padding-top: 30px; border-top: 1px solid #e5e7eb;">
                                <h3 style="margin: 0 0 16px; color: #111827; font-size: 16px; font-weight: 600;">What you can do:</h3>
                                <ul style="margin: 0; padding-left: 20px; color: #374151; font-size: 14px; line-height: 24px;">
                                    <li>View assigned projects and their progress</li>
                                    <li>Access project files and documents</li>
                                    <li>Participate in discussions</li>
                                    <li>View invoices and tickets</li>
                                    <li>Update your profile and preferences</li>
                                </ul>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding: 30px; background-color: #f9fafb; border-radius: 0 0 8px 8px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 20px;">
                                This invitation was sent by <strong>{{ $client->name }}</strong><br>
                                If you believe you received this email in error, please contact the sender.
                            </p>
                            <p style="margin: 16px 0 0; color: #9ca3af; font-size: 12px;">
                                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
