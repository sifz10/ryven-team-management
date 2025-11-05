<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAT Invitation</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f3f4f6;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff;">
        <!-- Header -->
        <div style="background: linear-gradient(135deg, #1f2937 0%, #111827 100%); padding: 40px 30px; text-align: center;">
            <div style="width: 60px; height: 60px; background-color: #ffffff; border-radius: 12px; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#1f2937" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">You're Invited to UAT!</h1>
            <p style="margin: 10px 0 0 0; color: #d1d5db; font-size: 16px;"><?php echo e($project->name); ?></p>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">
            <!-- Greeting -->
            <p style="margin: 0 0 20px 0; color: #374151; font-size: 16px; line-height: 1.6;">
                Hello <strong><?php echo e($uatUser->name); ?></strong>,
            </p>

            <p style="margin: 0 0 25px 0; color: #374151; font-size: 16px; line-height: 1.6;">
                <strong><?php echo e($invitedBy->name); ?></strong> has invited you to participate in the User Acceptance Testing (UAT) for <strong><?php echo e($project->name); ?></strong>.
            </p>

            <!-- Role Badge -->
            <div style="margin: 0 0 30px 0; padding: 15px; background-color: <?php echo e($uatUser->role === 'internal' ? '#dbeafe' : '#f3f4f6'); ?>; border-left: 4px solid <?php echo e($uatUser->role === 'internal' ? '#3b82f6' : '#6b7280'); ?>; border-radius: 6px;">
                <p style="margin: 0; color: #1f2937; font-size: 14px; font-weight: 600;">
                    Your Role: 
                    <span style="color: <?php echo e($uatUser->role === 'internal' ? '#1e40af' : '#4b5563'); ?>;">
                        <?php echo e($uatUser->role === 'internal' ? '👔 Employee' : '👤 Client'); ?>

                    </span>
                </p>
            </div>

            <?php if($project->description): ?>
                <!-- Project Description -->
                <div style="margin: 0 0 30px 0; padding: 20px; background-color: #f9fafb; border-radius: 8px;">
                    <h3 style="margin: 0 0 10px 0; color: #1f2937; font-size: 16px; font-weight: 600;">Project Overview</h3>
                    <p style="margin: 0; color: #4b5563; font-size: 14px; line-height: 1.6;"><?php echo e($project->description); ?></p>
                </div>
            <?php endif; ?>

            <!-- Access Button -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="<?php echo e($project->public_url); ?>" 
                   style="display: inline-block; padding: 16px 40px; background-color: #1f2937; color: #ffffff; text-decoration: none; border-radius: 50px; font-size: 16px; font-weight: 600; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);">
                    Access UAT Project →
                </a>
            </div>

            <!-- Instructions -->
            <div style="margin: 30px 0 0 0; padding: 25px; background-color: #fef3c7; border-radius: 8px; border: 1px solid #fbbf24;">
                <h3 style="margin: 0 0 15px 0; color: #92400e; font-size: 16px; font-weight: 600; display: flex; align-items: center;">
                    <span style="margin-right: 8px;">📋</span> How to Get Started
                </h3>
                
                <ol style="margin: 0; padding-left: 20px; color: #78350f; font-size: 14px; line-height: 1.8;">
                    <li style="margin-bottom: 10px;">
                        <strong>Click the button above</strong> or copy this link to your browser:<br>
                        <code style="background-color: #ffffff; padding: 4px 8px; border-radius: 4px; font-size: 12px; word-break: break-all;"><?php echo e($project->public_url); ?></code>
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Enter your email address</strong> (<?php echo e($uatUser->email); ?>) to authenticate
                    </li>
                    <li style="margin-bottom: 10px;">
                        <strong>Review the test cases</strong> provided by the team
                    </li>
                    <?php if($uatUser->role === 'internal'): ?>
                        <li style="margin-bottom: 10px;">
                            <strong>As an employee</strong>, you can:
                            <ul style="margin-top: 5px;">
                                <li>Create new test cases</li>
                                <li>Invite additional users</li>
                                <li>View feedback from all team members</li>
                                <li>Change status and leave detailed feedback</li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li style="margin-bottom: 10px;">
                            <strong>As a client</strong>, you can:
                            <ul style="margin-top: 5px;">
                                <li>Test each feature according to the steps provided</li>
                                <li>Mark tests as Passed, Failed, Blocked, or Pending</li>
                                <li>Leave comments and feedback</li>
                                <li>Upload screenshots or documents</li>
                            </ul>
                        </li>
                    <?php endif; ?>
                    <li>
                        <strong>Use quick status buttons</strong> for fast updates or add detailed feedback with comments and attachments
                    </li>
                </ol>
            </div>

            <!-- Status Options -->
            <div style="margin: 25px 0 0 0;">
                <h3 style="margin: 0 0 15px 0; color: #1f2937; font-size: 16px; font-weight: 600;">Status Options</h3>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px;">
                    <div style="padding: 12px; background-color: #dcfce7; border-radius: 6px; border-left: 3px solid #16a34a;">
                        <div style="font-weight: 600; color: #166534; font-size: 14px; margin-bottom: 3px;">✅ Passed</div>
                        <div style="font-size: 12px; color: #15803d;">Test completed successfully</div>
                    </div>
                    <div style="padding: 12px; background-color: #fee2e2; border-radius: 6px; border-left: 3px solid #dc2626;">
                        <div style="font-weight: 600; color: #991b1b; font-size: 14px; margin-bottom: 3px;">❌ Failed</div>
                        <div style="font-size: 12px; color: #b91c1c;">Issues found, needs attention</div>
                    </div>
                    <div style="padding: 12px; background-color: #fed7aa; border-radius: 6px; border-left: 3px solid #ea580c;">
                        <div style="font-weight: 600; color: #9a3412; font-size: 14px; margin-bottom: 3px;">🚫 Blocked</div>
                        <div style="font-size: 12px; color: #c2410c;">Cannot test due to blockers</div>
                    </div>
                    <div style="padding: 12px; background-color: #f3f4f6; border-radius: 6px; border-left: 3px solid #6b7280;">
                        <div style="font-weight: 600; color: #374151; font-size: 14px; margin-bottom: 3px;">⏳ Pending</div>
                        <div style="font-size: 12px; color: #4b5563;">Not yet tested</div>
                    </div>
                </div>
            </div>

            <?php if($project->deadline): ?>
                <!-- Deadline -->
                <div style="margin: 25px 0 0 0; padding: 15px; background-color: #fef2f2; border-radius: 6px; border: 1px solid #fecaca;">
                    <p style="margin: 0; color: #991b1b; font-size: 14px;">
                        <strong>⏰ Deadline:</strong> <?php echo e($project->deadline->format('F d, Y \a\t g:i A')); ?>

                    </p>
                </div>
            <?php endif; ?>

            <!-- Support -->
            <div style="margin: 30px 0 0 0; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="margin: 0; color: #6b7280; font-size: 14px; line-height: 1.6;">
                    If you have any questions or need assistance, please contact <strong><?php echo e($invitedBy->name); ?></strong> at <a href="mailto:<?php echo e($invitedBy->email); ?>" style="color: #3b82f6; text-decoration: none;"><?php echo e($invitedBy->email); ?></a> or our support team at <a href="mailto:support@ryven.co" style="color: #3b82f6; text-decoration: none;">support@ryven.co</a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f9fafb; padding: 30px; text-align: center; border-top: 1px solid #e5e7eb;">
            <p style="margin: 0 0 10px 0; color: #6b7280; font-size: 14px;">
                This is an automated invitation from the UAT System
            </p>
            <p style="margin: 0; color: #9ca3af; font-size: 12px;">
                © <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>

<?php /**PATH F:\Project\salary\resources\views/emails/uat-invitation.blade.php ENDPATH**/ ?>