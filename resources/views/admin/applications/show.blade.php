<x-app-layout>
    <div x-data="{ showDeleteModal: false, showTestModal: false }" class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Page Header -->
        <div class="sticky top-0 z-10 backdrop-blur-lg bg-white/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 px-6 py-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <a href="{{ route('admin.applications.index', ['job_post' => $application->job_post_id]) }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all hover:scale-105">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-black dark:text-white">{{ $application->first_name }} {{ $application->last_name }}</h1>
                            @if($application->ai_status === 'best_match')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-200 rounded-md text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Best Match
                                </span>
                            @elseif($application->ai_status === 'good_to_go')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-200 rounded-md text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    Good to Go
                                </span>
                            @elseif($application->ai_status === 'not_good_fit')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 text-red-800 dark:text-red-200 rounded-md text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    Not a Good Fit
                                </span>
                            @elseif($application->ai_status === 'pending')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-200 rounded-md text-xs font-medium">
                                    <svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Pending
                                </span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Applied {{ $application->created_at->diffForHumans() }} for
                            <a href="{{ route('admin.jobs.show', $application->jobPost) }}" class="font-medium text-black dark:text-white hover:underline">{{ $application->jobPost->title }}</a>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-3">
                    @if($application->resume_path)
                        <x-black-button variant="outline" href="{{ route('admin.applications.download-resume', $application) }}" target="_blank">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            <span class="hidden sm:inline">Download Resume</span>
                            <span class="sm:hidden">Resume</span>
                        </x-black-button>
                    @endif
                    <x-black-button href="{{ route('admin.applications.index', ['job_post' => $application->job_post_id]) }}">
                        <span class="hidden sm:inline">Back to Applications</span>
                        <span class="sm:hidden">Back</span>
                    </x-black-button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6 space-y-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Application Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- AI Screening Status Banner (for pending) -->
                    @if($application->ai_status === 'pending')
                        <div class="bg-gradient-to-br from-yellow-50 via-amber-50 to-orange-50 dark:from-yellow-900/20 dark:via-amber-900/20 dark:to-orange-900/20 border border-yellow-200/50 dark:border-yellow-800/50 rounded-xl p-6 shadow-sm">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-base font-bold text-yellow-900 dark:text-yellow-100 mb-2">AI Screening Status: Pending</h3>
                                    @if(!$application->jobPost->ai_screening_enabled)
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-2">
                                            This application has not been analyzed by AI because <strong>AI screening is disabled</strong> for this job posting.
                                        </p>
                                        <p class="text-sm text-yellow-800 dark:text-yellow-200">
                                            To enable AI screening for future applications,
                                            <a href="{{ route('admin.jobs.edit', $application->jobPost) }}" class="underline hover:no-underline font-medium">edit the job post</a>
                                            and enable AI screening with screening criteria.
                                        </p>
                                    @else
                                        @if($application->ai_analysis && isset($application->ai_analysis['error']))
                                            <div class="mb-3">
                                                <p class="text-sm font-medium text-yellow-900 dark:text-yellow-100 mb-2">
                                                    🔍 Error Details:
                                                </p>
                                                <div class="bg-yellow-100 dark:bg-yellow-900/40 rounded-lg px-4 py-3 text-sm">
                                                    <p class="text-yellow-900 dark:text-yellow-100 font-medium mb-1">
                                                        {{ $application->ai_analysis['error'] }}
                                                    </p>
                                                    @if(isset($application->ai_analysis['error_type']))
                                                        <p class="text-yellow-800 dark:text-yellow-200 text-xs mt-2">
                                                            @switch($application->ai_analysis['error_type'])
                                                                @case('file_not_found')
                                                                    ⚠️ The resume file could not be located in storage
                                                                    @break
                                                                @case('extraction_failed')
                                                                    📄 The PDF appears to be image-only or corrupted
                                                                    @break
                                                                @case('api_not_configured')
                                                                    🔑 OpenAI API key is not configured in .env
                                                                    @break
                                                                @case('api_error')
                                                                    🌐 OpenAI API request failed
                                                                    @break
                                                                @case('unsupported_format')
                                                                    📎 Only PDF files are supported
                                                                    @break
                                                                @default
                                                                    ❓ Unexpected error occurred
                                                            @endswitch
                                                        </p>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="border-t border-yellow-200 dark:border-yellow-800 pt-3 mt-3">
                                                <p class="text-sm font-medium text-yellow-800 dark:text-yellow-200 mb-2">
                                                    What you can do:
                                                </p>
                                                <ul class="space-y-1.5 text-sm text-yellow-700 dark:text-yellow-300">
                                                    @if(isset($application->ai_analysis['error_type']) && $application->ai_analysis['error_type'] === 'api_not_configured')
                                                        <li class="flex items-start gap-2">
                                                            <span class="text-yellow-500 mt-0.5">•</span>
                                                            Add <code class="px-1.5 py-0.5 bg-yellow-200 dark:bg-yellow-800 rounded text-xs font-mono">OPENAI_API_KEY</code> to .env
                                                        </li>
                                                    @endif
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-yellow-500 mt-0.5">•</span>
                                                        Review the resume manually below
                                                    </li>
                                                    <li class="flex items-start gap-2">
                                                        <span class="text-yellow-500 mt-0.5">•</span>
                                                        Check screening questions for insights
                                                    </li>
                                                </ul>
                                            </div>

                                            <!-- Retry Button -->
                                            <div class="mt-4 pt-4 border-t border-yellow-200/50 dark:border-yellow-800/50">
                                                <form action="{{ route('admin.applications.retry-ai-screening', $application) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-yellow-600 to-amber-600 hover:from-yellow-700 hover:to-amber-700 text-white rounded-xl text-sm font-medium transition-all shadow-md hover:shadow-lg hover:scale-105">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                        </svg>
                                                        Retry AI Screening
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <p class="text-sm text-yellow-800 dark:text-yellow-200 mb-2">
                                                AI analysis is pending or incomplete.
                                            </p>
                                            <p class="text-sm text-yellow-700 dark:text-yellow-300">
                                                💡 Please review this application manually
                                            </p>
                                        @endif
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- AI Screening Results -->
                    @if($application->ai_status !== 'pending')
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-6">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">AI Screening Results</h2>
                            </div>

                            <div class="space-y-5">
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Match Score</span>
                                        <span class="text-2xl font-bold text-black dark:text-white">{{ $application->ai_match_score ?? 0 }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                                        <div class="h-3 rounded-full transition-all duration-500 {{ $application->ai_match_score >= 80 ? 'bg-gradient-to-r from-green-500 to-emerald-600' : ($application->ai_match_score >= 60 ? 'bg-gradient-to-r from-blue-500 to-cyan-600' : 'bg-gradient-to-r from-red-500 to-orange-600') }}" style="width: {{ $application->ai_match_score ?? 0 }}%"></div>
                                    </div>
                                </div>

                                <div>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 block">AI Verdict</span>
                                    <div>
                                        @if($application->ai_status === 'best_match')
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-200 rounded-md text-sm font-medium">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Best Match
                                            </span>
                                        @elseif($application->ai_status === 'good_to_go')
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-200 rounded-md text-sm font-medium">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                </svg>
                                                Good to Go
                                            </span>
                                        @elseif($application->ai_status === 'not_good_fit')
                                            <span class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 text-red-800 dark:text-red-200 rounded-md text-sm font-medium">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                </svg>
                                                Not a Good Fit
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if($application->ai_analysis)
                                    <div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-3 block">Detailed Analysis</span>
                                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-5 space-y-4">
                                            @if(is_array($application->ai_analysis))
                                                @if(isset($application->ai_analysis['summary']))
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $application->ai_analysis['summary'] }}</p>
                                                @endif

                                                @if(isset($application->ai_analysis['strengths']) && count($application->ai_analysis['strengths']) > 0)
                                                    <div class="bg-green-50/50 dark:bg-green-900/10 rounded-lg p-4">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <p class="text-sm font-bold text-green-700 dark:text-green-400">Strengths</p>
                                                        </div>
                                                        <ul class="space-y-2">
                                                            @foreach($application->ai_analysis['strengths'] as $strength)
                                                                <li class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                                    <span class="text-green-500 mt-0.5">•</span>
                                                                    <span>{{ $strength }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if(isset($application->ai_analysis['concerns']) && count($application->ai_analysis['concerns']) > 0)
                                                    <div class="bg-red-50/50 dark:bg-red-900/10 rounded-lg p-4">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <p class="text-sm font-bold text-red-700 dark:text-red-400">Concerns</p>
                                                        </div>
                                                        <ul class="space-y-2">
                                                            @foreach($application->ai_analysis['concerns'] as $concern)
                                                                <li class="flex items-start gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                                    <span class="text-red-500 mt-0.5">•</span>
                                                                    <span>{{ $concern }}</span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endif

                                                @if(isset($application->ai_analysis['next_steps']))
                                                    <div class="bg-blue-50/50 dark:bg-blue-900/10 rounded-lg p-4">
                                                        <div class="flex items-center gap-2 mb-2">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <p class="text-sm font-bold text-blue-700 dark:text-blue-400">Recommended Next Steps</p>
                                                        </div>
                                                        <p class="text-sm text-gray-700 dark:text-gray-300">{{ $application->ai_analysis['next_steps'] }}</p>
                                                    </div>
                                                @endif

                                                @if(isset($application->ai_analysis['error']))
                                                    <p class="text-sm text-red-600 dark:text-red-400">{{ $application->ai_analysis['error'] }}</p>
                                                @endif
                                            @else
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $application->ai_analysis }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Contact Information -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Contact Information</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Email</p>
                                </div>
                                <p class="font-medium text-black dark:text-white">
                                    <a href="mailto:{{ $application->email }}" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">{{ $application->email }}</a>
                                </p>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Phone</p>
                                </div>
                                <p class="font-medium text-black dark:text-white">{{ $application->phone }}</p>
                            </div>
                            @if($application->linkedin_url)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">LinkedIn</p>
                                    </div>
                                    <p class="font-medium text-black dark:text-white">
                                        <a href="{{ $application->linkedin_url }}" target="_blank" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">View Profile</a>
                                    </p>
                                </div>
                            @endif
                            @if($application->portfolio_url)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Portfolio</p>
                                    </div>
                                    <p class="font-medium text-black dark:text-white">
                                        <a href="{{ $application->portfolio_url }}" target="_blank" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">View Portfolio</a>
                                    </p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Cover Letter -->
                    @if($application->cover_letter)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">Cover Letter</h2>
                            </div>
                            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-5 leading-relaxed">
                                {!! nl2br(e($application->cover_letter)) !!}
                            </div>
                        </div>
                    @endif

                    <!-- Screening Answers -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Screening Answers</h2>
                        </div>
                        @if($application->answers->count() > 0)
                            <div class="space-y-5">
                                @foreach($application->answers as $answer)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-5 last:mb-0">
                                        <div class="flex items-start gap-3 mb-3">
                                            <div class="flex-shrink-0 w-7 h-7 rounded-lg bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-xs font-bold text-gray-700 dark:text-gray-300">
                                                {{ $loop->iteration }}
                                            </div>
                                            <p class="text-sm font-semibold text-gray-900 dark:text-white flex-1">{{ $answer->question->question }}</p>
                                        </div>
                                        @if($answer->answer_text)
                                            <p class="text-sm text-gray-700 dark:text-gray-300 ml-10 leading-relaxed">{{ $answer->answer_text }}</p>
                                        @endif
                                        @if($answer->answer_file_path)
                                            <div class="ml-10 mt-3">
                                                @if($answer->answer_file_type === 'video')
                                                    <video controls class="w-full max-w-md rounded-xl shadow-lg">
                                                        <source src="{{ asset('storage/' . $answer->answer_file_path) }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <a href="{{ asset('storage/' . $answer->answer_file_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                        </svg>
                                                        Download File
                                                    </a>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                </div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-1">No Screening Answers</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">The applicant has not provided any screening answers yet.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Tests & Submissions -->
                    @if($application->tests->count() > 0)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">Tests & Submissions</h2>
                            </div>
                            <div class="space-y-4">
                                @foreach($application->tests as $test)
                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-5 border border-gray-200 dark:border-gray-700">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h3 class="font-semibold text-gray-900 dark:text-white mb-1">{{ $test->test_title }}</h3>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Sent {{ $test->sent_at->diffForHumans() }}</p>
                                            </div>
                                            @if($test->status === 'sent')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400">
                                                    Pending
                                                </span>
                                            @elseif($test->status === 'submitted')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                                                    Submitted
                                                </span>
                                            @elseif($test->status === 'reviewed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400">
                                                    Reviewed
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-2 gap-2 text-xs text-gray-600 dark:text-gray-400 mb-3">
                                            <div>
                                                <span class="font-medium">Deadline:</span> {{ $test->deadline->format('M j, g:i A') }}
                                            </div>
                                            @if($test->submitted_at)
                                                <div>
                                                    <span class="font-medium">Submitted:</span> {{ $test->submitted_at->format('M j, g:i A') }}
                                                </div>
                                            @endif
                                        </div>

                                        @if($test->submission_file_path)
                                            <div class="space-y-2">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    <span class="text-xs text-gray-700 dark:text-gray-300 font-medium">{{ $test->submission_original_name }}</span>
                                                </div>
                                                <a href="{{ asset('storage/' . $test->submission_file_path) }}" download="{{ $test->submission_original_name }}"
                                                   class="inline-flex items-center gap-2 px-3 py-1.5 bg-black hover:bg-gray-800 text-white rounded-full text-xs font-medium transition-all">
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                    </svg>
                                                    Download Submission
                                                </a>
                                                @if($test->submission_notes)
                                                    <div class="mt-2 p-2 bg-blue-50 dark:bg-blue-900/20 rounded text-xs text-blue-900 dark:text-blue-300">
                                                        <strong>Notes:</strong> {{ $test->submission_notes }}
                                                    </div>
                                                @endif
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2 text-xs text-gray-500 dark:text-gray-500">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Waiting for submission...</span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Resume Preview -->
                    @if($application->resume_path)
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-orange-500 to-red-600 flex items-center justify-center shadow-lg shadow-orange-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">Resume</h2>
                            </div>
                            <div class="aspect-[8.5/11] bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-900 dark:to-gray-800 rounded-xl overflow-hidden shadow-inner">
                                <iframe src="{{ route('admin.applications.view-resume', $application) }}" class="w-full h-full"></iframe>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Right Column - Actions & Status -->
                <div class="space-y-6">
                    <!-- Quick Actions -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow sticky top-24">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-lg shadow-emerald-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Quick Actions</h2>
                        </div>
                        <div class="space-y-3">
                            <x-black-button class="w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                Schedule Interview
                            </x-black-button>
                            <x-black-button variant="outline" class="w-full justify-center" @click="showTestModal = true">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Generate & Send Test
                            </x-black-button>
                            @if($application->added_to_talent_pool)
                                <x-black-button variant="outline" href="{{ route('admin.talent-pool.index') }}" class="w-full justify-center">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    In Talent Pool
                                </x-black-button>
                            @else
                                <form action="{{ route('admin.applications.talent-pool', $application) }}" method="POST" class="w-full">
                                    @csrf
                                    <x-black-button type="submit" variant="outline" class="w-full justify-center">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        Add to Talent Pool
                                    </x-black-button>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Application Status -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Status</h2>
                        </div>
                        <div class="space-y-4">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Current Status</p>
                                </div>
                                <p class="font-semibold text-black dark:text-white capitalize">{{ str_replace('_', ' ', $application->application_status) }}</p>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Applied On</p>
                                </div>
                                <p class="font-semibold text-black dark:text-white">{{ $application->created_at->format('M d, Y') }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $application->created_at->format('g:i A') }}</p>
                            </div>
                            @if($application->reviewed_at)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Reviewed On</p>
                                    </div>
                                    <p class="font-semibold text-black dark:text-white">{{ $application->reviewed_at->format('M d, Y') }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $application->reviewed_at->format('g:i A') }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Admin Notes -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Admin Notes</h2>
                        </div>
                        <form action="#" method="POST">
                            @csrf
                            <textarea name="admin_notes" rows="5" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white transition-shadow" placeholder="Add notes about this candidate...">{{ $application->admin_notes }}</textarea>
                            <x-black-button type="submit" class="mt-4 w-full justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/>
                                </svg>
                                Save Notes
                            </x-black-button>
                        </form>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-50/80 dark:bg-red-900/20 backdrop-blur-sm rounded-xl border border-red-200 dark:border-red-800 p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-red-900 dark:text-red-200">Danger Zone</h2>
                        </div>
                        <p class="text-sm text-red-800 dark:text-red-300 mb-4">
                            Deleting this application is permanent and cannot be undone. All associated data including resume, answers, and AI analysis will be removed.
                        </p>
                        <button @click="showDeleteModal = true" type="button" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete Application
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal"
             x-cloak
             @keydown.escape.window="showDeleteModal = false"
             class="fixed inset-0 z-50 overflow-y-auto"
             style="display: none;">
            <!-- Backdrop -->
            <div x-show="showDeleteModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"
                 @click="showDeleteModal = false"></div>

            <!-- Modal -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 shadow-2xl transition-all w-full max-w-lg">

                    <!-- Modal Header -->
                    <div class="bg-gradient-to-br from-red-500 to-orange-600 px-6 py-5">
                        <div class="flex items-center gap-4">
                            <div class="flex-shrink-0 w-14 h-14 rounded-xl bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <h3 class="text-xl font-bold text-white">Delete Application</h3>
                                <p class="text-sm text-red-100 mt-1">This action cannot be undone</p>
                            </div>
                            <button @click="showDeleteModal = false" class="flex-shrink-0 text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <div class="px-6 py-6">
                        <!-- Warning Message -->
                        <div class="mb-6">
                            <p class="text-gray-700 dark:text-gray-300 mb-4">
                                You are about to permanently delete the application from <strong class="text-black dark:text-white">{{ $application->first_name }} {{ $application->last_name }}</strong>.
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                The following data will be permanently removed:
                            </p>
                        </div>

                        <!-- Items to be deleted -->
                        <div class="space-y-3 mb-6">
                            <div class="flex items-start gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-900 dark:text-red-100">Resume & Application Details</p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">All personal information and uploaded resume</p>
                                </div>
                            </div>

                            @if($application->answers->count() > 0)
                            <div class="flex items-start gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-900 dark:text-red-100">Screening Answers</p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">{{ $application->answers->count() }} screening question(s) and any video/file attachments</p>
                                </div>
                            </div>
                            @endif

                            @if($application->ai_status !== 'pending')
                            <div class="flex items-start gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-900 dark:text-red-100">AI Screening Results</p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">Match score and detailed AI analysis</p>
                                </div>
                            </div>
                            @endif

                            @if($application->admin_notes)
                            <div class="flex items-start gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-900 dark:text-red-100">Admin Notes</p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">All internal notes and comments</p>
                                </div>
                            </div>
                            @endif

                            @if($application->added_to_talent_pool)
                            <div class="flex items-start gap-3 p-3 bg-red-50 dark:bg-red-900/20 rounded-xl">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-red-900 dark:text-red-100">Talent Pool Entry</p>
                                    <p class="text-xs text-red-700 dark:text-red-300 mt-1">Will be removed from talent pool</p>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Warning Box -->
                        <div class="bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-400 p-4 rounded-lg mb-6">
                            <div class="flex gap-3">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 font-medium">
                                    This action is irreversible. Once deleted, this data cannot be recovered.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 px-6 py-4 flex items-center justify-end gap-3">
                        <button @click="showDeleteModal = false" type="button" class="px-5 py-2.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 rounded-xl font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                            Cancel
                        </button>
                        <form action="{{ route('admin.applications.destroy', $application) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white rounded-xl font-medium transition-all shadow-lg shadow-red-500/30 hover:shadow-xl flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Yes, Delete Application
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate Test Modal -->
        <div x-show="showTestModal"
             x-cloak
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             @keydown.escape.window="showTestModal = false"
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">

            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-sm" @click="showTestModal = false"></div>

            <!-- Modal Content -->
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="showTestModal"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="transition ease-in duration-150"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full overflow-hidden">

                    <!-- Modal Header -->
                    <div class="bg-black dark:bg-black px-8 py-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-white">Generate & Send Test</h3>
                                    <p class="text-gray-300 text-sm mt-1">For {{ $application->full_name }}</p>
                                </div>
                            </div>
                            <button @click="showTestModal = false" class="text-white/80 hover:text-white transition-colors">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Modal Body -->
                    <form action="{{ route('admin.applications.tests.store', $application) }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6" x-data="testGenerator()">
                        @csrf

                        <!-- AI Generation Section -->
                        <div class="bg-black dark:bg-black rounded-xl p-4 border border-white">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-white mb-2">AI-Powered Test Generation</h4>
                                    <p class="text-xs text-gray-300 mb-3">Let AI create a comprehensive test based on the job requirements</p>
                                    <button type="button" @click="generateTest()" :disabled="generating"
                                            class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-black text-white hover:bg-gray-800 disabled:bg-gray-600 disabled:opacity-50 rounded-full text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black">
                                        <svg x-show="!generating" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <svg x-show="generating" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        <span x-text="generating ? 'Generating...' : 'Generate Test with AI'"></span>
                                    </button>
                                    <p x-show="error" x-text="error" class="text-xs text-red-300 mt-2"></p>
                                </div>
                            </div>
                        </div>

                        <!-- Test Title -->
                        <div>
                            <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                                Test Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="test_title" x-model="title" required
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-black dark:text-white focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                                   placeholder="e.g., React Development Assignment">
                        </div>

                        <!-- Test Description -->
                        <div>
                            <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                                Description
                            </label>
                            <textarea name="test_description" x-model="description" rows="3"
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-black dark:text-white focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                                      placeholder="Brief overview of what the test covers"></textarea>
                        </div>

                        <!-- Test Instructions -->
                        <div>
                            <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                                Instructions <span class="text-red-500">*</span>
                            </label>
                            <textarea name="test_instructions" x-model="instructions" rows="8" required
                                      class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-black dark:text-white focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                                      placeholder="Detailed instructions for the candidate on how to complete the test"></textarea>
                        </div>

                        <!-- Deadline -->
                        <div>
                            <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                                Deadline <span class="text-red-500">*</span>
                            </label>
                            <input type="datetime-local" name="deadline" required
                                   min="{{ now()->format('Y-m-d\TH:i') }}"
                                   class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-black dark:text-white focus:border-blue-500 dark:focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all">
                        </div>

                        <!-- Test File (Optional) -->
                        <div>
                            <label class="block text-sm font-semibold text-black dark:text-white mb-2">
                                Test File (Optional)
                            </label>
                            <div class="relative">
                                <input type="file" name="test_file" id="test_file" accept=".pdf,.doc,.docx,.zip"
                                       class="hidden"
                                       onchange="document.getElementById('test-file-name').textContent = this.files[0]?.name || 'No file chosen'">
                                <label for="test_file" class="flex items-center gap-3 px-4 py-3 bg-gray-50 dark:bg-gray-900 border-2 border-gray-300 dark:border-gray-600 rounded-xl cursor-pointer hover:border-blue-500 dark:hover:border-blue-500 transition-all group">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">Click to upload test document</p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">PDF, DOC, DOCX, ZIP (Max 10MB)</p>
                                        <p id="test-file-name" class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-semibold">No file chosen</p>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 pt-4">
                            <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-xl font-semibold shadow-lg shadow-blue-500/30 hover:shadow-xl transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Send Test to Candidate
                            </button>
                            <button type="button" @click="showTestModal = false" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-300 rounded-xl font-semibold transition-all">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function testGenerator() {
            return {
                title: '',
                description: '',
                instructions: '',
                generating: false,
                error: '',

                async generateTest() {
                    this.generating = true;
                    this.error = '';

                    try {
                        // Get CSRF token
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
                        if (!csrfToken) {
                            console.error('CSRF token not found');
                            this.error = 'Security token missing. Please refresh the page.';
                            this.generating = false;
                            return;
                        }

                        const response = await fetch('{{ route('admin.applications.tests.generate', $application) }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                test_type: 'Technical Assessment'
                            })
                        });

                        // Check if response is ok
                        if (!response.ok) {
                            const errorData = await response.json().catch(() => ({ message: 'Server error' }));
                            throw new Error(errorData.message || `HTTP error! status: ${response.status}`);
                        }

                        const data = await response.json();

                        if (data.success) {
                            this.title = data.data.title;
                            this.description = data.data.description;
                            this.instructions = data.data.instructions;

                            // Show success feedback
                            this.showNotification('Test generated successfully!', 'success');
                        } else {
                            this.error = data.message || 'Failed to generate test';
                        }
                    } catch (error) {
                        console.error('Error generating test:', error);
                        this.error = error.message || 'An error occurred while generating the test';
                    } finally {
                        this.generating = false;
                    }
                },

                showNotification(message, type) {
                    // Simple notification - you can enhance this
                    const notification = document.createElement('div');
                    notification.className = `fixed bottom-6 right-6 px-6 py-3 rounded-lg shadow-lg z-50 ${
                        type === 'success' ? 'bg-green-500' : 'bg-red-500'
                    } text-white`;
                    notification.textContent = message;
                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.remove();
                    }, 3000);
                }
            };
        }
    </script>
    @endpush
</x-app-layout>
