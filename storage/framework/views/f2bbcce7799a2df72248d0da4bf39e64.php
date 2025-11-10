<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Login Code</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #000000 0%, #1f2937 100%); padding: 40px; text-align: center;">
                            <img src="<?php echo e(asset('black-logo.png')); ?>" alt="<?php echo e(config('app.name')); ?>" style="height: 48px; margin-bottom: 16px; filter: invert(1);">
                            <h1 style="color: #ffffff; font-size: 28px; font-weight: bold; margin: 0;">Your Login Code</h1>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 24px 0;">
                                Hello,
                            </p>
                            <p style="color: #374151; font-size: 16px; line-height: 1.6; margin: 0 0 32px 0;">
                                You requested to sign in to your <?php echo e(config('app.name')); ?> client portal. Use the code below to complete your login:
                            </p>

                            <!-- OTP Code Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #f9fafb; border: 2px solid #e5e7eb; border-radius: 12px; padding: 32px; text-align: center;">
                                        <div style="color: #6b7280; font-size: 14px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px;">
                                            Your OTP Code
                                        </div>
                                        <div style="color: #000000; font-size: 48px; font-weight: bold; letter-spacing: 0.25em; font-family: 'Courier New', monospace;">
                                            <?php echo e($otpCode); ?>

                                        </div>
                                    </td>
                                </tr>
                            </table>

                            <!-- Important Info Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 0 0 32px 0;">
                                <tr>
                                    <td style="background-color: #fef3c7; border-left: 4px solid #f59e0b; padding: 16px; border-radius: 8px;">
                                        <p style="color: #92400e; font-size: 14px; line-height: 1.6; margin: 0;">
                                            <strong>⏰ This code expires in 10 minutes</strong><br>
                                            If you didn't request this code, please ignore this email or contact our support team.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #6b7280; font-size: 14px; line-height: 1.6; margin: 0;">
                                For your security, never share this code with anyone. Our team will never ask for your OTP code.
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 32px; text-align: center; border-top: 1px solid #e5e7eb;">
                            <p style="color: #6b7280; font-size: 14px; margin: 0 0 8px 0;">
                                Need help? <a href="mailto:<?php echo e(config('mail.from.address')); ?>" style="color: #000000; font-weight: 600; text-decoration: none;">Contact Support</a>
                            </p>
                            <p style="color: #9ca3af; font-size: 12px; margin: 0;">
                                © <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
<?php /**PATH F:\Project\salary\resources\views/emails/client-otp.blade.php ENDPATH**/ ?>