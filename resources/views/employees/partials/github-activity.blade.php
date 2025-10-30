@php
    // Get filters from request - NO defaults, show all by default
    $startDate = request('start_date');
    $endDate = request('end_date');
    $eventType = request('event_type');
    $repository = request('repository');
    
    // Build query with filters
    $logsQuery = $employee->githubLogs();
    $statsQuery = $employee->githubLogs();
    $repoQuery = \App\Models\GitHubLog::where('employee_id', $employee->id);
    
    // Apply date filters only if provided
    if ($startDate) {
        $logsQuery->whereDate('event_at', '>=', $startDate);
        $statsQuery->whereDate('event_at', '>=', $startDate);
        $repoQuery->whereDate('event_at', '>=', $startDate);
    }
    
    if ($endDate) {
        $logsQuery->whereDate('event_at', '<=', $endDate);
        $statsQuery->whereDate('event_at', '<=', $endDate);
        $repoQuery->whereDate('event_at', '<=', $endDate);
    }
    
    // Apply event type filter
    if ($eventType) {
        $logsQuery->where('event_type', $eventType);
    }
    
    // Apply repository filter
    if ($repository) {
        $logsQuery->where('repository_name', $repository);
    }
    
    $githubLogs = $logsQuery->orderBy('event_at', 'desc')->paginate(20)->withQueryString();
    
    // Get statistics for the filtered date range
    $totalActivities = $statsQuery->count();
    $pushCount = (clone $statsQuery)->where('event_type', 'push')->count();
    $prCount = (clone $statsQuery)->where('event_type', 'pull_request')->count();
    $totalCommits = (clone $statsQuery)->where('event_type', 'push')->sum('commits_count') ?: 0;
    
    // Get unique repositories and event types
    $repositories = \App\Models\GitHubLog::where('employee_id', $employee->id)
        ->select('repository_name')
        ->groupBy('repository_name')
        ->pluck('repository_name');
    
    $eventTypes = \App\Models\GitHubLog::where('employee_id', $employee->id)
        ->select('event_type')
        ->groupBy('event_type')
        ->pluck('event_type');
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

        <!-- Date Filter -->
        <div class="mb-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
            <form method="GET" action="{{ route('employees.show', $employee) }}" class="space-y-4" x-data="{ 
                startDate: '{{ $startDate }}', 
                endDate: '{{ $endDate }}',
                setToday() {
                    const today = new Date().toISOString().split('T')[0];
                    this.startDate = today;
                    this.endDate = today;
                    this.$nextTick(() => {
                        this.$refs.form.submit();
                    });
                },
                setThisWeek() {
                    const today = new Date();
                    const monday = new Date(today);
                    monday.setDate(today.getDate() - today.getDay() + (today.getDay() === 0 ? -6 : 1));
                    const sunday = new Date(monday);
                    sunday.setDate(monday.getDate() + 6);
                    this.startDate = monday.toISOString().split('T')[0];
                    this.endDate = sunday.toISOString().split('T')[0];
                    this.$nextTick(() => {
                        this.$refs.form.submit();
                    });
                },
                setThisMonth() {
                    const today = new Date();
                    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                    const lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);
                    this.startDate = firstDay.toISOString().split('T')[0];
                    this.endDate = lastDay.toISOString().split('T')[0];
                    this.$nextTick(() => {
                        this.$refs.form.submit();
                    });
                }
            }" x-ref="form">
                <input type="hidden" name="tab" value="github">
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            From Date
                        </label>
                        <input type="date" name="start_date" x-model="startDate" 
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            To Date
                        </label>
                        <input type="date" name="end_date" x-model="endDate"
                            class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Event Type
                        </label>
                        <select name="event_type" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">All Events</option>
                            @foreach($eventTypes as $type)
                                <option value="{{ $type }}" @selected($eventType === $type)>
                                    {{ ucfirst(str_replace('_', ' ', $type)) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                            </svg>
                            Repository
                        </label>
                        <select name="repository" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                            <option value="">All Repos</option>
                            @foreach($repositories as $repo)
                                <option value="{{ $repo }}" @selected($repository === $repo)>{{ $repo }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-end">
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-5 py-2 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 transition-all font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filter
                        </button>
                    </div>
                </div>

                <!-- Quick Filter Buttons -->
                <div class="flex flex-wrap items-center gap-3 pt-2 border-t border-gray-200 dark:border-gray-700">
                    <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Quick Filters:</span>
                    <button type="button" @click="setToday()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm">
                        Today
                    </button>
                    <button type="button" @click="setThisWeek()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm">
                        This Week
                    </button>
                    <button type="button" @click="setThisMonth()" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-sm">
                        This Month
                    </button>
                    @if($startDate || $endDate || $eventType || $repository)
                        <a href="{{ route('employees.show', $employee) }}?tab=github" class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full hover:bg-red-200 dark:hover:bg-red-900/50 transition-all text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear Filters
                        </a>
                    @endif
                    @if($startDate || $endDate || $eventType || $repository)
                        <span class="text-xs text-gray-600 dark:text-gray-400 ml-auto">
                            <strong>Filtered:</strong>
                            @if($startDate) From {{ \Carbon\Carbon::parse($startDate)->format('M d') }} @endif
                            @if($endDate) to {{ \Carbon\Carbon::parse($endDate)->format('M d') }} @endif
                            @if($eventType) • {{ ucfirst(str_replace('_', ' ', $eventType)) }} @endif
                            @if($repository) • {{ $repository }} @endif
                        </span>
                    @endif
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Total Activities</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalActivities }}</p>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Total Commits</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $totalCommits }}</p>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Pull Requests</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $prCount }}</p>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Repositories</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $repositories->count() }}</p>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
        <div class="bg-gradient-to-r from-gray-800 to-black dark:from-gray-900 dark:to-black p-6 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-white/10 rounded-lg">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white">Activity Timeline</h3>
                    <p class="text-sm text-gray-300 mt-0.5">Showing {{ $githubLogs->count() }} recent activities</p>
                </div>
            </div>
        </div>

        <div class="divide-y divide-gray-200 dark:divide-gray-700">
            @forelse($githubLogs as $log)
                <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/30 transition-all duration-200">
                    <div class="flex items-start gap-4">
                        <!-- Event Icon -->
                        <div class="flex-shrink-0">
                            @if($log->event_type === 'push')
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black flex items-center justify-center shadow-lg border border-gray-700">
                                    <span class="text-2xl">📤</span>
                                </div>
                            @elseif($log->event_type === 'pull_request')
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black flex items-center justify-center shadow-lg border border-gray-700">
                                    <span class="text-2xl">🔀</span>
                                </div>
                            @elseif($log->event_type === 'pull_request_review')
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black flex items-center justify-center shadow-lg border border-gray-700">
                                    <span class="text-2xl">👀</span>
                                </div>
                            @elseif($log->event_type === 'issues')
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black flex items-center justify-center shadow-lg border border-gray-700">
                                    <span class="text-2xl">🐛</span>
                                </div>
                            @else
                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black flex items-center justify-center shadow-lg border border-gray-700">
                                    <span class="text-2xl">{{ $log->event_icon }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Event Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <!-- Event Type Badge -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-800 to-black text-white shadow-sm border border-gray-700">
                                            {{ $log->event_display_name }}
                                            @if($log->action)
                                                · {{ ucfirst($log->action) }}
                                            @endif
                                        </span>
                                        @if($log->branch)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                                                </svg>
                                                {{ $log->branch }}
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Repository Name -->
                                    <a href="{{ $log->repository_url }}" target="_blank" class="group inline-flex items-center gap-1.5 text-base font-bold text-gray-900 dark:text-white hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                        </svg>
                                        {{ $log->repository_name }}
                                        <svg class="w-3.5 h-3.5 opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                                                        <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-xs text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white font-medium mt-2 transition">
                                                            <span x-show="!expanded">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                                Show more
                                                            </span>
                                                            <span x-show="expanded" x-cloak>
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                                </svg>
                                                                Show less
                                                            </span>
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
                                                        <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-xs text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white font-medium mt-2 transition">
                                                            <span x-show="!expanded">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                                </svg>
                                                                Show more
                                                            </span>
                                                            <span x-show="expanded" x-cloak>
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                                </svg>
                                                                Show less
                                                            </span>
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
                                                <button @click="expanded = !expanded" class="inline-flex items-center gap-1 text-xs text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white font-medium mt-2 transition">
                                                    <span x-show="!expanded">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                        </svg>
                                                        Show more
                                                    </span>
                                                    <span x-show="expanded" x-cloak>
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                        </svg>
                                                        Show less
                                                    </span>
                                                </button>
                                            @endif
                                        </div>
                                    @endif
                                </div>

                                <!-- Timestamp -->
                                <div class="flex-shrink-0">
                                    <div class="bg-gray-100 dark:bg-gray-900 rounded-xl px-3 py-2 text-right border border-gray-200 dark:border-gray-700">
                                        <div class="text-xs font-semibold text-gray-900 dark:text-white">
                                            {{ $log->event_at->diffForHumans() }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            {{ $log->event_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ $log->event_at->format('g:i A') }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Author Info -->
                            @if($log->author_username)
                                <div class="flex items-center gap-2 mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="inline-flex items-center gap-2 px-3 py-1 bg-gray-100 dark:bg-gray-900 rounded-full text-xs font-medium text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-700">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <span class="font-semibold"><?php echo $log->author_username; ?></span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                        @if($startDate || $endDate)
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Activity in Selected Date Range</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-4">
                                No GitHub activities found between {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} and {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }}.
                            </p>
                            <a href="{{ route('employees.show', ['employee' => $employee, 'tab' => 'github']) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full hover:bg-gray-800 transition-all shadow text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                View All Activities
                            </a>
                        @else
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No GitHub Activity Yet</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                                Once you set up the GitHub webhook and this employee starts pushing code or creating pull requests, their activity will appear here.
                            </p>
                        @endif
                    </div>
                    
                    <!-- Setup Instructions Card -->
                    <div class="max-w-2xl mx-auto">
                        <div class="p-5 bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black border border-gray-700 dark:border-gray-800 rounded-2xl text-left shadow-lg">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-white/10 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-sm text-gray-100 flex-1">
                                    <div class="font-bold text-white mb-3">Setup Instructions:</div>
                                    <ol class="list-decimal list-inside space-y-2 mb-4">
                                        <li class="text-gray-200">Go to your GitHub repository settings</li>
                                        <li class="text-gray-200">Add a new webhook with URL: <code class="px-2 py-1 bg-black/50 border border-gray-700 rounded text-xs text-white">{{ url('/webhook/github') }}</code></li>
                                        <li class="text-gray-200">Select events: Push, Pull Request, Issues</li>
                                    </ol>
                                    <div class="font-bold text-white mb-2">Employee Matching:</div>
                                    <ul class="list-disc list-inside space-y-2">
                                        @if($employee->github_username)
                                            <li class="text-gray-200">✅ GitHub username set: <code class="px-2 py-1 bg-green-900/50 border border-green-700 text-green-300 rounded text-xs font-semibold">@{{ $employee->github_username }}</code></li>
                                        @else
                                            <li class="text-gray-200">⚠️ Add GitHub username in <a href="{{ route('employees.edit', $employee) }}" class="underline font-semibold text-white hover:text-gray-300">employee settings</a></li>
                                        @endif
                                        <li class="text-gray-200">Email matching: <code class="px-2 py-1 bg-black/50 border border-gray-700 rounded text-xs text-white">{{ $employee->email }}</code></li>
                                    </ul>
                                </div>
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

