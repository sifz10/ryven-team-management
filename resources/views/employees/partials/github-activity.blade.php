@php
    $githubLogs = $employee->githubLogs()
        ->orderBy('event_at', 'desc')
        ->paginate(20);
    
    // Get statistics
    $totalActivities = $employee->githubLogs()->count();
    $pushCount = $employee->githubLogs()->where('event_type', 'push')->count();
    $prCount = $employee->githubLogs()->where('event_type', 'pull_request')->count();
    $totalCommits = $employee->githubLogs()->where('event_type', 'push')->sum('commits_count') ?: 0;
    
    // Get unique repositories - use groupBy instead of distinct to avoid ORDER BY conflict
    $repositories = \App\Models\GitHubLog::where('employee_id', $employee->id)
        ->select('repository_name')
        ->groupBy('repository_name')
        ->pluck('repository_name');
@endphp

<div class="space-y-6">
    <!-- Header with Statistics -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    GitHub Activity
                </h3>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                    Track all GitHub activities including pushes, pull requests, and more
                </p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide font-medium">Total Activities</div>
                        <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $totalActivities }}</div>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-900 rounded-lg">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-blue-600 dark:text-blue-400 uppercase tracking-wide font-medium">Commits</div>
                        <div class="text-2xl font-bold text-blue-900 dark:text-blue-200 mt-1">{{ $totalCommits }}</div>
                    </div>
                    <div class="p-3 bg-blue-200 dark:bg-blue-900 rounded-lg">
                        <svg class="w-6 h-6 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wide font-medium">Pull Requests</div>
                        <div class="text-2xl font-bold text-purple-900 dark:text-purple-200 mt-1">{{ $prCount }}</div>
                    </div>
                    <div class="p-3 bg-purple-200 dark:bg-purple-900 rounded-lg">
                        <svg class="w-6 h-6 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-xl p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wide font-medium">Repositories</div>
                        <div class="text-2xl font-bold text-green-900 dark:text-green-200 mt-1">{{ $repositories->count() }}</div>
                    </div>
                    <div class="p-3 bg-green-200 dark:bg-green-900 rounded-lg">
                        <svg class="w-6 h-6 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        @if($repositories->isNotEmpty())
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide font-medium mb-2">Active Repositories</div>
            <div class="flex flex-wrap gap-2">
                @foreach($repositories as $repo)
                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-full">
                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $repo }}
                    </span>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Timeline</h3>
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Recent GitHub activities</p>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($githubLogs as $log)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                    <div class="flex items-start gap-4">
                        <!-- Event Icon -->
                        <div class="flex-shrink-0">
                            @if($log->event_type === 'push')
                                <div class="w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <span class="text-xl">📤</span>
                                </div>
                            @elseif($log->event_type === 'pull_request')
                                <div class="w-10 h-10 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <span class="text-xl">🔀</span>
                                </div>
                            @elseif($log->event_type === 'pull_request_review')
                                <div class="w-10 h-10 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                    <span class="text-xl">👀</span>
                                </div>
                            @elseif($log->event_type === 'issues')
                                <div class="w-10 h-10 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                    <span class="text-xl">🐛</span>
                                </div>
                            @else
                                <div class="w-10 h-10 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                                    <span class="text-xl">{{ $log->event_icon }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Event Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <!-- Event Type Badge -->
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-black text-white">
                                            {{ $log->event_display_name }}
                                            @if($log->action)
                                                · {{ ucfirst($log->action) }}
                                            @endif
                                        </span>
                                        @if($log->branch)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                </svg>
                                                {{ $log->branch }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Repository Name -->
                                    <a href="{{ $log->repository_url }}" target="_blank" class="inline-flex items-center text-sm font-semibold text-gray-900 dark:text-white hover:text-blue-600 dark:hover:text-blue-400 transition">
                                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $log->repository_name }}
                                        <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                        </svg>
                                    </a>

                                    @if($log->event_type === 'push')
                                        <div class="mt-2">
                                            @if($log->commits_count > 1)
                                                <p class="text-sm text-gray-700 dark:text-gray-300">
                                                    Pushed <span class="font-semibold">{{ $log->commits_count }} commits</span>
                                                </p>
                                            @endif
                                            @if($log->commit_message)
                                                <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700" x-data="{ expanded: false }">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 font-mono break-words whitespace-pre-wrap">
                                                        <span x-show="!expanded">{{ Str::limit($log->commit_message, 150) }}</span>
                                                        <span x-show="expanded" x-cloak>{{ $log->commit_message }}</span>
                                                    </p>
                                                    @if(strlen($log->commit_message) > 150)
                                                        <button @click="expanded = !expanded" class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-1 font-medium">
                                                            <span x-show="!expanded">Show more</span>
                                                            <span x-show="expanded" x-cloak>Show less</span>
                                                        </button>
                                                    @endif
                                                    @if($log->commit_sha)
                                                        <a href="{{ $log->commit_url }}" target="_blank" class="inline-flex items-center text-xs text-blue-600 dark:text-blue-400 hover:underline mt-2">
                                                            <code class="mr-1">{{ Str::limit($log->commit_sha, 8, '') }}</code>
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($log->event_type === 'pull_request')
                                        <div class="mt-2">
                                            <a href="{{ $log->pr_url }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                #{{ $log->pr_number }}: {{ $log->pr_title }}
                                            </a>
                                            @if($log->pr_description)
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($log->pr_description, 200) }}
                                                </p>
                                            @endif
                                            @if($log->pr_state)
                                                <div class="mt-2 flex items-center gap-2">
                                                    @if($log->pr_merged)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                                            🟣 Merged
                                                        </span>
                                                    @elseif($log->pr_state === 'open')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300">
                                                            🟢 Open
                                                        </span>
                                                    @elseif($log->pr_state === 'closed')
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300">
                                                            🔴 Closed
                                                        </span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @elseif(in_array($log->event_type, ['pull_request_review', 'pull_request_review_comment']))
                                        <div class="mt-2">
                                            @if($log->pr_title)
                                                <a href="{{ $log->pr_url }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                    #{{ $log->pr_number }}: {{ $log->pr_title }}
                                                </a>
                                            @endif
                                            @if($log->commit_message)
                                                <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700" x-data="{ expanded: false }">
                                                    <p class="text-sm text-gray-700 dark:text-gray-300 break-words whitespace-pre-wrap">
                                                        <span x-show="!expanded">{{ Str::limit($log->commit_message, 200) }}</span>
                                                        <span x-show="expanded" x-cloak>{{ $log->commit_message }}</span>
                                                    </p>
                                                    @if(strlen($log->commit_message) > 200)
                                                        <button @click="expanded = !expanded" class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-1 font-medium">
                                                            <span x-show="!expanded">Show more</span>
                                                            <span x-show="expanded" x-cloak>Show less</span>
                                                        </button>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @elseif($log->event_type === 'issues')
                                        <div class="mt-2">
                                            <a href="{{ $log->pr_url }}" target="_blank" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                #{{ $log->pr_number }}: {{ $log->pr_title }}
                                            </a>
                                            @if($log->pr_description)
                                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                                    {{ Str::limit($log->pr_description, 200) }}
                                                </p>
                                            @endif
                                        </div>
                                    @elseif($log->commit_message)
                                        <div class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700" x-data="{ expanded: false }">
                                            <p class="text-sm text-gray-700 dark:text-gray-300 break-words whitespace-pre-wrap">
                                                <span x-show="!expanded">{{ Str::limit($log->commit_message, 200) }}</span>
                                                <span x-show="expanded" x-cloak>{{ $log->commit_message }}</span>
                                            </p>
                                            @if(strlen($log->commit_message) > 200)
                                                <button @click="expanded = !expanded" class="text-xs text-blue-600 dark:text-blue-400 hover:underline mt-1 font-medium">
                                                    <span x-show="!expanded">Show more</span>
                                                    <span x-show="expanded" x-cloak>Show less</span>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Timestamp -->
                                <div class="flex-shrink-0 text-right">
                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                        {{ $log->event_at->diffForHumans() }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500 mt-1">
                                        {{ $log->event_at->format('M d, Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                        {{ $log->event_at->format('g:i A') }}
                                    </div>
                                </div>
                            </div>

                            <!-- Author Info -->
                            @if($log->author_username)
                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-100 dark:border-gray-700">
                                    <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                        <span class="font-medium"><?php echo $log->author_username; ?></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No GitHub Activity Yet</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                        Once you set up the GitHub webhook and this employee starts pushing code or creating pull requests, their activity will appear here.
                    </p>
                    <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg inline-block text-left">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="text-sm text-blue-700 dark:text-blue-300">
                                <div class="font-semibold mb-2">Setup Instructions:</div>
                                <ol class="list-decimal list-inside space-y-1 mb-3">
                                    <li>Go to your GitHub repository settings</li>
                                    <li>Add a new webhook with URL: <code class="px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/50 rounded text-xs">{{ url('/webhook/github') }}</code></li>
                                    <li>Select events: Push, Pull Request, Issues</li>
                                </ol>
                                <div class="font-semibold mb-1">Employee Matching:</div>
                                <ul class="list-disc list-inside space-y-1">
                                    @if($employee->github_username)
                                        <li>✅ GitHub username set: <code class="px-1.5 py-0.5 bg-green-100 dark:bg-green-900/50 text-green-800 dark:text-green-300 rounded text-xs font-semibold">@{{ $employee->github_username }}</code></li>
                                    @else
                                        <li>⚠️ Add GitHub username in <a href="{{ route('employees.edit', $employee) }}" class="underline font-semibold">employee settings</a></li>
                                    @endif
                                    <li>Email matching: <code class="px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/50 rounded text-xs">{{ $employee->email }}</code></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        @if($githubLogs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                {{ $githubLogs->links() }}
            </div>
        @endif
    </div>
</div>

