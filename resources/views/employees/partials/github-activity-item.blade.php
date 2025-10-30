@foreach($githubLogs as $log)
    <div class="activity-item p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200 border-b border-gray-200 dark:border-gray-700 last:border-b-0" data-activity-id="{{ $log->id }}">
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
                            <span class="text-xs font-semibold text-gray-900 dark:text-white">
                                {{ $log->event_display_name }}
                                @if($log->action)
                                    <span class="text-gray-500 dark:text-gray-400">· {{ ucfirst($log->action) }}</span>
                                @endif
                            </span>
                            @if($log->branch)
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                    </svg>
                                    {{ $log->branch }}
                                </span>
                            @endif
                        </div>

                        <!-- Repository Name -->
                        <a href="{{ $log->repository_url }}" target="_blank" class="group inline-flex items-center gap-2 mb-1">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white group-hover:text-gray-600 dark:group-hover:text-gray-300 transition-colors">{{ $log->repository_name }}</span>
                            <svg class="w-3 h-3 text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                    <div class="mt-2" x-data="{ expanded: false }">
                                        <div class="p-2.5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed" style="word-break: break-word;">
                                                <span x-show="!expanded">{{ Str::limit(trim($log->commit_message), 120) }}</span>
                                                <span x-show="expanded" x-cloak class="whitespace-pre-wrap">{{ trim($log->commit_message) }}</span>
                                            </p>
                                        </div>
                                        <div class="flex items-center gap-3 mt-1.5">
                                            @if(strlen($log->commit_message) > 120)
                                                <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium">
                                                    <span x-show="!expanded">Show more</span>
                                                    <span x-show="expanded" x-cloak>Show less</span>
                                                </button>
                                            @endif
                                            @if($log->commit_sha)
                                                <a href="{{ $log->commit_url }}" target="_blank" class="inline-flex items-center gap-1 text-xs text-gray-500 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition">
                                                    <code class="text-xs">{{ Str::limit($log->commit_sha, 7, '') }}</code>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                    </svg>
                                                </a>
                                            @endif
                                        </div>
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
                                    <div class="mt-2" x-data="{ expanded: false }">
                                        <div class="p-2.5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                            <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed" style="word-break: break-word;">
                                                <span x-show="!expanded">{{ Str::limit(trim($log->commit_message), 120) }}</span>
                                                <span x-show="expanded" x-cloak class="whitespace-pre-wrap">{{ trim($log->commit_message) }}</span>
                                            </p>
                                        </div>
                                        @if(strlen($log->commit_message) > 120)
                                            <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium mt-1.5">
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
                            <div class="mt-2" x-data="{ expanded: false }">
                                <div class="p-2.5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-700 dark:text-gray-300 leading-relaxed" style="word-break: break-word;">
                                        <span x-show="!expanded">{{ Str::limit(trim($log->commit_message), 120) }}</span>
                                        <span x-show="expanded" x-cloak class="whitespace-pre-wrap">{{ trim($log->commit_message) }}</span>
                                    </p>
                                </div>
                                @if(strlen($log->commit_message) > 120)
                                    <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-xs text-blue-600 dark:text-blue-400 hover:underline font-medium mt-1.5">
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
                            {{ $log->event_at->format('M d, g:i A') }}
                        </div>
                    </div>
                </div>

                <!-- Author Info -->
                @if($log->author_username)
                    <div class="flex items-center gap-2 mt-3">
                        <span class="text-xs text-gray-500 dark:text-gray-400">by</span>
                        <span class="text-xs font-medium text-gray-700 dark:text-gray-300">{{ $log->author_username }}</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

