<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($test->test_title); ?></title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
        }
        .email-container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            padding: 40px 30px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0 0 10px 0;
            font-size: 28px;
            font-weight: 700;
        }
        .header p {
            margin: 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 18px;
            color: #111827;
            margin-bottom: 20px;
        }
        .test-info-card {
            background-color: #f9fafb;
            border-left: 4px solid #2563eb;
            padding: 20px;
            margin: 25px 0;
            border-radius: 8px;
        }
        .test-info-card h3 {
            margin: 0 0 12px 0;
            color: #1f2937;
            font-size: 16px;
            font-weight: 600;
        }
        .test-info-card p {
            margin: 8px 0;
            color: #4b5563;
            line-height: 1.6;
        }
        .deadline-badge {
            display: inline-block;
            background-color: #fef3c7;
            color: #92400e;
            padding: 8px 16px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin: 15px 0;
        }
        .instructions-box {
            background-color: #eff6ff;
            border: 1px solid #bfdbfe;
            padding: 20px;
            border-radius: 8px;
            margin: 25px 0;
        }
        .instructions-box h3 {
            margin: 0 0 12px 0;
            color: #1e40af;
            font-size: 16px;
            font-weight: 600;
        }
        .instructions-box p {
            color: #1e3a8a;
            line-height: 1.8;
            margin: 0;
            white-space: pre-wrap;
        }
        .button-container {
            text-align: center;
            margin: 35px 0;
        }
        .btn {
            display: inline-block;
            background: linear-gradient(135deg, #2563eb 0%, #4f46e5 100%);
            color: white;
            padding: 14px 40px;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            box-shadow: 0 4px 6px rgba(37, 99, 235, 0.3);
        }
        .footer {
            background-color: #f9fafb;
            padding: 25px 30px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            margin: 5px 0;
        }
        .icon-box {
            background-color: rgba(255, 255, 255, 0.2);
            width: 60px;
            height: 60px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        @media only screen and (max-width: 600px) {
            .email-container {
                margin: 20px;
                border-radius: 12px;
            }
            .content {
                padding: 30px 20px;
            }
            .header {
                padding: 30px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <div class="icon-box">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2">
                    <path d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1><?php echo e($test->test_title); ?></h1>
            <p>Test Assignment for <?php echo e($application->jobPost->title); ?></p>
        </div>

        <!-- Content -->
        <div class="content">
            <p class="greeting">Hi <?php echo e($application->first_name); ?>,</p>

            <p style="color: #374151; line-height: 1.7; margin-bottom: 20px;">
                Thank you for your application! We're impressed with your profile and would like to move forward with the next step in our hiring process.
            </p>

            <p style="color: #374151; line-height: 1.7; margin-bottom: 20px;">
                We've prepared a test assignment to better understand your skills and approach to problem-solving. Please review the details below:
            </p>

            <!-- Test Info Card -->
            <div class="test-info-card">
                <h3>📋 Test Details</h3>
                <?php if($test->test_description): ?>
                    <p><strong>Description:</strong> <?php echo e($test->test_description); ?></p>
                <?php endif; ?>
                <p><strong>Position:</strong> <?php echo e($application->jobPost->title); ?></p>
                <?php if($test->test_file_path): ?>
                    <p><strong>📎 Test file attached</strong> - Please check the attachments</p>
                <?php endif; ?>
            </div>

            <!-- Deadline Badge -->
            <div style="text-align: center;">
                <span class="deadline-badge">
                    ⏰ Deadline: <?php echo e($test->deadline->format('F j, Y \a\t g:i A')); ?>

                </span>
            </div>

            <!-- Instructions -->
            <div class="instructions-box">
                <h3>📝 Instructions</h3>
                <p><?php echo e($test->test_instructions); ?></p>
            </div>

            <p style="color: #374151; line-height: 1.7; margin-top: 25px;">
                <strong>Important:</strong> Please complete and submit your test before the deadline. If you have any questions or need clarification, don't hesitate to reply to this email.
            </p>

            <!-- Submission Instructions -->
            <div style="background-color: #f0fdf4; border-left: 4px solid #10b981; padding: 20px; border-radius: 8px; margin: 25px 0;">
                <h3 style="margin: 0 0 12px 0; color: #065f46; font-size: 16px;">📤 How to Submit Your Test</h3>
                <p style="color: #065f46; margin: 0 0 15px 0;">
                    Click the button below to access your personal submission portal. You can download the test materials, review instructions, and upload your completed work.
                </p>
                <p style="color: #dc2626; margin: 0; font-weight: 600; font-size: 14px;">
                    ⚠️ This link will expire on <?php echo e($test->deadline->format('F j, Y \a\t g:i A')); ?>

                </p>
            </div>

            <!-- Submit Button -->
            <div class="button-container">
                <a href="<?php echo e(route('test.submission.show', $test->submission_token)); ?>" class="btn">
                    Access Submission Portal
                </a>
            </div>

            <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 15px;">
                Submission Link: <a href="<?php echo e(route('test.submission.show', $test->submission_token)); ?>" style="color: #2563eb;"><?php echo e(route('test.submission.show', $test->submission_token)); ?></a>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong><?php echo e(config('app.name')); ?> - Recruitment Team</strong></p>
            <p>This is an automated email. Please do not reply directly unless instructed above.</p>
            <p style="margin-top: 15px; font-size: 12px;">
                If you have any concerns, please contact our HR department.
            </p>
        </div>
    </div>
</body>
</html>
<?php /**PATH F:\Project\salary\resources\views/emails/test-invitation.blade.php ENDPATH**/ ?>