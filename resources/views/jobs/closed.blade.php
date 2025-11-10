<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->title }} - Job Closed</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900">
    <div class="min-h-screen flex items-center justify-center p-6">
        <div class="max-w-2xl w-full bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8 text-center">
            <!-- Icon -->
            <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>

            <!-- Title -->
            <h1 class="text-3xl font-bold text-black dark:text-white mb-2">{{ $job->title }}</h1>

            <!-- Message -->
            <div class="mb-6">
                @if($job->status === 'closed')
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-2">This Job Position is Closed</p>
                    <p class="text-gray-500 dark:text-gray-400">We are no longer accepting applications for this position.</p>
                @elseif($job->application_deadline && $job->application_deadline->isPast())
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-2">Application Deadline Passed</p>
                    <p class="text-gray-500 dark:text-gray-400">
                        The deadline for this position was {{ $job->application_deadline->format('M d, Y') }}.
                    </p>
                @else
                    <p class="text-xl text-gray-600 dark:text-gray-400 mb-2">Applications Not Available</p>
                    <p class="text-gray-500 dark:text-gray-400">This job is currently not accepting applications.</p>
                @endif
            </div>

            <!-- Job Details -->
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-6 mb-6">
                <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Job Details</h2>
                <div class="grid grid-cols-2 gap-4 text-left">
                    @if($job->department)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Department</p>
                            <p class="font-medium text-black dark:text-white">{{ $job->department }}</p>
                        </div>
                    @endif
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Job Type</p>
                        <p class="font-medium text-black dark:text-white">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</p>
                    </div>
                    @if($job->location)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Location</p>
                            <p class="font-medium text-black dark:text-white">{{ $job->location }}</p>
                        </div>
                    @endif
                    @if($job->experience_level)
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Experience</p>
                            <p class="font-medium text-black dark:text-white">{{ ucfirst($job->experience_level) }} Level</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Description -->
            <div class="text-left mb-6">
                <h2 class="text-lg font-semibold text-black dark:text-white mb-3">About the Position</h2>
                <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 text-sm">
                    {{ Str::limit($job->description, 300) }}
                </div>
            </div>

            <!-- Contact Info -->
            @if($job->contact_email)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">For inquiries about this position:</p>
                    <a href="mailto:{{ $job->contact_email }}" class="text-black dark:text-white font-semibold hover:underline">
                        {{ $job->contact_email }}
                    </a>
                </div>
            @endif
        </div>
    </div>
</body>
</html>
