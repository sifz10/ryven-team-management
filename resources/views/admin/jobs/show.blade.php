<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Page Header -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-start gap-3">
                        <x-icon-button variant="black" href="{{ route('admin.jobs.index') }}" class="flex-shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                            </svg>
                        </x-icon-button>
                        <div class="flex-1 min-w-0">
                            <h1 class="text-xl sm:text-2xl font-bold text-black dark:text-white truncate">{{ $job->title }}</h1>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                                Created {{ $job->created_at->diffForHumans() }} by {{ $job->creator->first_name }} {{ $job->creator->last_name }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3">
                        <x-black-button variant="outline" href="{{ route('admin.jobs.edit', $job) }}" class="flex-1 sm:flex-initial">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            <span class="hidden sm:inline">Edit Job</span>
                            <span class="sm:hidden">Edit</span>
                        </x-black-button>
                        <x-black-button onclick="copyPublicUrl()" class="flex-1 sm:flex-initial">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/>
                            </svg>
                            <span class="hidden sm:inline">Copy Link</span>
                            <span class="sm:hidden">Copy</span>
                        </x-black-button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8 space-y-6">
            <!-- Status Banner -->
            @if($job->status === 'published')
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl p-4 sm:p-5 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-green-800 dark:text-green-200 text-sm sm:text-base">Job is Published</p>
                            <p class="text-xs sm:text-sm text-green-700 dark:text-green-300 mt-1">
                                Public URL: <a href="{{ $job->public_url }}" target="_blank" class="underline hover:no-underline break-all">{{ $job->public_url }}</a>
                            </p>
                        </div>
                    </div>
                </div>
            @elseif($job->status === 'draft')
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 sm:p-5 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-yellow-800 dark:text-yellow-200 text-sm sm:text-base">Job is in Draft</p>
                            <p class="text-xs sm:text-sm text-yellow-700 dark:text-yellow-300 mt-1">This job is not visible to the public yet</p>
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4 sm:p-5 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-gray-400 to-gray-600 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 dark:text-gray-200 text-sm sm:text-base">Job is Closed</p>
                            <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 mt-1">This job is no longer accepting applications</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Application Stats -->
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-3 sm:gap-4">
                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Total</p>
                            <p class="text-2xl sm:text-3xl font-bold text-black dark:text-white mt-1">{{ $applicationStats['total'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-green-200 dark:border-green-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-green-100 dark:hover:shadow-green-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Best Match</p>
                            <p class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $applicationStats['best_match'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-200 dark:shadow-green-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-blue-200 dark:border-blue-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-blue-100 dark:hover:shadow-blue-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Good to Go</p>
                            <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $applicationStats['good_to_go'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-blue-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-red-200 dark:border-red-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-red-100 dark:hover:shadow-red-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Not Good Fit</p>
                            <p class="text-2xl sm:text-3xl font-bold text-red-600 dark:text-red-400 mt-1">{{ $applicationStats['not_good_fit'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-200 dark:shadow-red-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-yellow-200 dark:border-yellow-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-yellow-100 dark:hover:shadow-yellow-900/20 hover:scale-105 transition-all duration-300 col-span-2 md:col-span-3 lg:col-span-1">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Pending</p>
                            <p class="text-2xl sm:text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $applicationStats['pending'] }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-200 dark:shadow-yellow-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Job Details -->
                <div class="lg:col-span-2 space-y-4 sm:space-y-6">
                    <!-- Job Information -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                        <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Job Information</h2>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Department</p>
                                <p class="font-medium text-black dark:text-white mt-1">{{ $job->department ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Job Type</p>
                                <p class="font-medium text-black dark:text-white mt-1">{{ ucfirst(str_replace('-', ' ', $job->job_type)) }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Location</p>
                                <p class="font-medium text-black dark:text-white mt-1">{{ $job->location ?? 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Experience Level</p>
                                <p class="font-medium text-black dark:text-white mt-1">{{ $job->experience_level ? ucfirst($job->experience_level) : 'Not specified' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Salary Range</p>
                                <p class="font-medium text-black dark:text-white mt-1">
                                    @if($job->salary_min || $job->salary_max)
                                        {{ number_format($job->salary_min, 0) }} - {{ number_format($job->salary_max, 0) }} {{ $job->salary_currency }}
                                    @else
                                        Not specified
                                    @endif
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Positions Available</p>
                                <p class="font-medium text-black dark:text-white mt-1">{{ $job->positions_available }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Application Deadline</p>
                                <p class="font-medium text-black dark:text-white mt-1">
                                    {{ $job->application_deadline ? $job->application_deadline->format('M d, Y') : 'No deadline' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Contact Email</p>
                                <p class="font-medium text-black dark:text-white mt-1">{{ $job->contact_email ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                        <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Description</h2>
                        <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-gray-700 dark:text-gray-300">
                            {!! nl2br(e($job->description)) !!}
                        </div>
                    </div>

                    <!-- Requirements -->
                    @if($job->requirements)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                            <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Requirements</h2>
                            <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($job->requirements)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Responsibilities -->
                    @if($job->responsibilities)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                            <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Responsibilities</h2>
                            <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($job->responsibilities)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Benefits -->
                    @if($job->benefits)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                            <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Benefits</h2>
                            <div class="prose dark:prose-invert max-w-none text-sm sm:text-base text-gray-700 dark:text-gray-300">
                                {!! nl2br(e($job->benefits)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Screening Questions -->
                    @if($job->questions->count() > 0)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                            <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Screening Questions ({{ $job->questions->count() }})</h2>
                            <div class="space-y-3 sm:space-y-4">
                                @foreach($job->questions as $question)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-3 sm:p-4">
                                        <div class="flex items-start gap-3">
                                            <span class="flex-shrink-0 w-6 h-6 bg-black dark:bg-white text-white dark:text-black rounded-full flex items-center justify-center text-xs font-semibold">
                                                {{ $loop->iteration }}
                                            </span>
                                            <div class="flex-1 min-w-0">
                                                <p class="font-medium text-sm sm:text-base text-black dark:text-white">{{ $question->question }}</p>
                                                <div class="flex items-center gap-2 mt-2 flex-wrap">
                                                    <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                        {{ ucfirst(str_replace('_', ' ', $question->type)) }}
                                                    </span>
                                                    @if($question->is_required)
                                                        <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-md bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400">
                                                            Required
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Quick Actions & AI Settings -->
                <div class="space-y-4 sm:space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                        <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Quick Actions</h2>
                        <div class="space-y-3">
                            <x-black-button href="{{ route('admin.applications.index', ['job_post' => $job->id]) }}" class="w-full justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <span>View Applications</span>
                            </x-black-button>
                            <x-black-button href="{{ route('admin.jobs.bulk-upload', $job) }}" class="w-full justify-center bg-purple-600 hover:bg-purple-700 dark:bg-purple-600 dark:hover:bg-purple-700">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <span>Bulk Upload Resumes</span>
                            </x-black-button>
                            <x-black-button variant="outline" onclick="window.open('{{ $job->public_url }}', '_blank')" class="w-full justify-center">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <span>Preview Job Page</span>
                            </x-black-button>
                        </div>
                    </div>

                    <!-- AI Screening -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                        <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">AI Screening</h2>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Status</span>
                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-md {{ $job->ai_screening_enabled ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400' }}">
                                    {{ $job->ai_screening_enabled ? 'Enabled' : 'Disabled' }}
                                </span>
                            </div>
                            @if($job->ai_screening_criteria)
                                <div>
                                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2">Criteria</p>
                                    <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300">{{ $job->ai_screening_criteria }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Metadata -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 shadow-sm">
                        <h2 class="text-base sm:text-lg font-semibold text-black dark:text-white mb-4">Metadata</h2>
                        <div class="space-y-3">
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Slug</p>
                                <p class="text-xs sm:text-sm font-mono text-gray-700 dark:text-gray-300 mt-1 break-all">{{ $job->slug }}</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Created</p>
                                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $job->created_at->format('M d, Y g:i A') }}</p>
                            </div>
                            <div>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">Last Updated</p>
                                <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 mt-1">{{ $job->updated_at->format('M d, Y g:i A') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="public-url" value="{{ $job->public_url }}">

    @push('scripts')
    <script>
        function copyPublicUrl() {
            const url = document.getElementById('public-url').value;
            navigator.clipboard.writeText(url).then(() => {
                alert('Public URL copied to clipboard!');
            });
        }
    </script>
    @endpush
</x-app-layout>
