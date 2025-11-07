<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ config('app.name') }}</title>
</head>
<body style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #000000 0%, #434343 100%); color: white; padding: 30px; border-radius: 10px 10px 0 0; text-align: center;">
        <h1 style="margin: 0; font-size: 28px; font-weight: bold;">Welcome to {{ config('app.name') }}!</h1>
        <p style="margin: 10px 0 0 0; font-size: 16px; opacity: 0.9;">Your Client Portal Account</p>
    </div>

    <div style="background: #ffffff; padding: 30px; border: 1px solid #e5e7eb; border-top: none; border-radius: 0 0 10px 10px;">
        <p style="font-size: 16px; margin-bottom: 20px;">Hello <strong>{{ $client->name }}</strong>,</p>

        <p style="font-size: 15px; color: #555; margin-bottom: 25px;">
            Welcome to our team management platform! We're excited to have you on board. Your client portal account has been created and you can now access your projects, invoices, and collaborate with our team.
        </p>

        <div style="background: #f9fafb; border-left: 4px solid #000000; padding: 20px; margin: 25px 0; border-radius: 5px;">
            <h2 style="margin: 0 0 15px 0; font-size: 18px; color: #000;">Your Login Credentials</h2>
            <table style="width: 100%; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; font-weight: 600; color: #555; width: 100px;">Email:</td>
                    <td style="padding: 8px 0; font-family: 'Courier New', monospace; background: #fff; padding: 8px 12px; border-radius: 4px; border: 1px solid #e5e7eb;">{{ $client->email }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; font-weight: 600; color: #555;">Password:</td>
                    <td style="padding: 8px 0; font-family: 'Courier New', monospace; background: #fff; padding: 8px 12px; border-radius: 4px; border: 1px solid #e5e7eb; color: #dc2626; font-weight: bold;">{{ $password }}</td>
                </tr>
            </table>
        </div>

        <div style="background: #fef3c7; border: 1px solid #fbbf24; border-radius: 8px; padding: 15px; margin: 25px 0;">
            <p style="margin: 0; font-size: 14px; color: #92400e;">
                <strong>⚠️ Important Security Notice:</strong><br>
                For security reasons, you will be required to change your password upon first login. Please keep this email secure and delete it after changing your password.
            </p>
        </div>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" style="display: inline-block; background: #000000; color: white; text-decoration: none; padding: 14px 40px; border-radius: 8px; font-weight: 600; font-size: 16px;">
                Access Client Portal
            </a>
        </div>

        <div style="margin-top: 30px; padding-top: 25px; border-top: 1px solid #e5e7eb;">
            <h3 style="font-size: 16px; margin-bottom: 15px; color: #000;">What You Can Do:</h3>
            <ul style="margin: 0; padding-left: 20px; color: #555;">
                <li style="margin-bottom: 8px;">View and track all your projects</li>
                <li style="margin-bottom: 8px;">Access project files and documents</li>
                <li style="margin-bottom: 8px;">Participate in project discussions</li>
                <li style="margin-bottom: 8px;">Review and manage invoices</li>
                <li style="margin-bottom: 8px;">Submit tickets and feature requests</li>
            </ul>
        </div>

        <p style="font-size: 14px; color: #666; margin-top: 30px;">
            If you have any questions or need assistance, please don't hesitate to contact our support team.
        </p>

        <p style="font-size: 14px; color: #666; margin-top: 15px;">
            Best regards,<br>
            <strong>{{ config('app.name') }} Team</strong>
        </p>
    </div>

    <div style="text-align: center; padding: 20px; color: #999; font-size: 12px;">
        <p style="margin: 0;">© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        <p style="margin: 10px 0 0 0;">This is an automated message. Please do not reply to this email.</p>
    </div>
</body>
</html>
