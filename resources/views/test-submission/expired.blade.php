<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Submission Expired</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 max-w-2xl">
        <div class="bg-white rounded-2xl shadow-2xl p-8 text-center">
            <!-- Icon -->
            <div class="inline-flex items-center justify-center w-24 h-24 bg-red-100 rounded-full mb-6">
                <svg class="w-12 h-12 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">Submission Deadline Passed</h1>

            <!-- Message -->
            <p class="text-gray-600 mb-6 text-lg">
                Unfortunately, the deadline for submitting this test has passed.
            </p>

            <!-- Test Info -->
            <div class="bg-gray-50 rounded-xl p-6 mb-6 border border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900 mb-3">{{ $test->test_title }}</h2>
                <div class="text-sm text-gray-600 space-y-2">
                    <p><strong>Candidate:</strong> {{ $test->application->full_name }}</p>
                    <p><strong>Deadline was:</strong> {{ $test->deadline->format('F j, Y \a\t g:i A') }}</p>
                    <p><strong>Expired:</strong> {{ $test->deadline->diffForHumans() }}</p>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg">
                <p class="text-blue-800 text-sm">
                    <strong>Need to submit anyway?</strong><br>
                    Please contact our recruitment team to request an extension or discuss alternative arrangements.
                </p>
            </div>

            <!-- Footer -->
            <div class="mt-8 text-gray-500 text-sm">
                <p>© {{ date('Y') }} {{ config('app.name') }}</p>
            </div>
        </div>
    </div>
</body>
</html>
