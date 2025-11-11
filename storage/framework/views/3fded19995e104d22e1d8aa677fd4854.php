<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Already Submitted</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-24 h-24 bg-green-100 rounded-full mb-6">
                <svg class="w-12 h-12 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Test Already Submitted</h1>

            <!-- Message -->
            <p class="text-gray-600 mb-6 text-lg">
                Thank you! Your test has already been submitted successfully.
            </p>

            <!-- Test Info -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-3"><?php echo e($test->test_title); ?></h2>
                <div class="text-sm text-gray-600 space-y-2">
                    <p><strong>Candidate:</strong> <?php echo e($test->application->full_name); ?></p>
                    <p><strong>Submitted on:</strong> <?php echo e($test->submitted_at->format('F j, Y \a\t g:i A')); ?></p>
                    <p><strong>Status:</strong> <span class="inline-block px-3 py-1 bg-green-100 text-green-800 rounded-full text-xs font-semibold">Submitted</span></p>
                    <?php if($test->submission_original_name): ?>
                        <p><strong>File:</strong> <?php echo e($test->submission_original_name); ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Submission Details -->
            <?php if($test->submission_notes): ?>
                <div class="bg-blue-50 rounded-xl p-4 mb-6 text-left border border-blue-200">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">Your Submission Notes:</h3>
                    <p class="text-sm text-blue-800 whitespace-pre-wrap"><?php echo e($test->submission_notes); ?></p>
                </div>
            <?php endif; ?>

            <!-- Next Steps -->
            <div class="bg-gradient-to-r from-purple-50 to-pink-50 border border-purple-200 rounded-xl p-6">
                <h3 class="text-lg font-semibold text-purple-900 mb-2">What's Next?</h3>
                <p class="text-purple-700 text-sm">
                    Our team is reviewing your submission. We'll get back to you with feedback and next steps soon. Thank you for your patience!
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-gray-500 text-sm">
                <p>© <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?></p>
                <p class="mt-2">Questions? Contact our recruitment team.</p>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH F:\Project\salary\resources\views/test-submission/already-submitted.blade.php ENDPATH**/ ?>