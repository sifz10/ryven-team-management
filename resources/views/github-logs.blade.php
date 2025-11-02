<x-app-layout x-data="prModalData" @open-pr-modal.window="openPrModal($event.detail)">
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-black rounded-xl">
                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight flex items-center gap-3">
                        GitHub Webhook Logs
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-medium">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                            </span>
                            Live
                        </span>
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        View and filter all GitHub webhook activities • Auto-refreshes every 30s
                    </p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Employees
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-2xl p-6 border border-gray-200 dark:border-gray-600 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide font-medium">Total Logs</div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ number_format($totalLogs) }}</div>
                        </div>
                        <div class="p-3 bg-white dark:bg-gray-900 rounded-xl shadow-sm">
                            <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-2xl p-6 border border-blue-200 dark:border-blue-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs text-blue-600 dark:text-blue-400 uppercase tracking-wide font-medium">Total Commits</div>
                            <div class="text-3xl font-bold text-blue-900 dark:text-blue-200 mt-2">{{ number_format($totalCommits) }}</div>
                        </div>
                        <div class="p-3 bg-blue-200 dark:bg-blue-900 rounded-xl">
                            <svg class="w-5 h-5 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-2xl p-6 border border-purple-200 dark:border-purple-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wide font-medium">Pull Requests</div>
                            <div class="text-3xl font-bold text-purple-900 dark:text-purple-200 mt-2">{{ number_format($prCount) }}</div>
                        </div>
                        <div class="p-3 bg-purple-200 dark:bg-purple-900 rounded-xl">
                            <svg class="w-5 h-5 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-2xl p-6 border border-green-200 dark:border-green-800 shadow-sm">
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wide font-medium">Push Events</div>
                            <div class="text-3xl font-bold text-green-900 dark:text-green-200 mt-2">{{ number_format($pushCount) }}</div>
                        </div>
                        <div class="p-3 bg-green-200 dark:bg-green-900 rounded-xl">
                            <svg class="w-5 h-5 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                <form method="GET" action="{{ route('github.logs') }}" class="space-y-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Filters
                        </h3>
                        @if($startDate || $endDate || $eventType || $repository || $employeeId)
                            <a href="{{ route('github.logs') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full hover:bg-red-200 dark:hover:bg-red-900/50 transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear All Filters
                            </a>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                From Date
                            </label>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                To Date
                            </label>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                Employee
                            </label>
                            <select name="employee_id" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg px-3 py-2 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition text-sm">
                                <option value="">All Employees</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" @selected($employeeId == $emp->id)>
                                        {{ $emp->first_name }} {{ $emp->last_name }}
                                    </option>
                                @endforeach
                            </select>
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
                                <option value="">All Repositories</option>
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
                                Apply Filters
                            </button>
                        </div>
                    </div>

                    @if($startDate || $endDate || $eventType || $repository || $employeeId)
                        <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                            <span class="text-xs text-gray-600 dark:text-gray-400">
                                <strong>Active Filters:</strong>
                                @if($startDate) From {{ \Carbon\Carbon::parse($startDate)->format('M d, Y') }} @endif
                                @if($endDate) to {{ \Carbon\Carbon::parse($endDate)->format('M d, Y') }} @endif
                                @if($eventType) • Event: {{ ucfirst(str_replace('_', ' ', $eventType)) }} @endif
                                @if($repository) • Repo: {{ $repository }} @endif
                                @if($employeeId) • Employee: {{ $employees->find($employeeId)->first_name ?? 'Unknown' }} {{ $employees->find($employeeId)->last_name ?? '' }} @endif
                            </span>
                        </div>
                    @endif
                </form>
            </div>

            <!-- Logs Cards -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        Webhook Logs
                        <span class="text-sm font-normal text-gray-600 dark:text-gray-400">({{ $logs->total() }} total)</span>
                    </h3>
                </div>

                @if($logs->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($logs as $log)
                            @php
                                $eventColors = [
                                    'push' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300 border-blue-200 dark:border-blue-800',
                                    'pull_request' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300 border-purple-200 dark:border-purple-800',
                                    'pull_request_review' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-300 border-indigo-200 dark:border-indigo-800',
                                    'issues' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300 border-green-200 dark:border-green-800',
                                    'create' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300 border-emerald-200 dark:border-emerald-800',
                                    'delete' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300 border-red-200 dark:border-red-800',
                                ];
                                $color = $eventColors[$log->event_type] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 border-gray-200 dark:border-gray-600';
                            @endphp
                            
                            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <!-- Left: Event Info -->
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-3 mb-2">
                                            <!-- Employee Avatar & Name -->
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                @if($log->author_avatar_url)
                                                    <img src="<?php echo $log->author_avatar_url; ?>" alt="<?php echo $log->author_username; ?>" class="w-7 h-7 rounded-full">
                                                @else
                                                    <div class="w-7 h-7 rounded-full bg-gray-300 dark:bg-gray-600 flex items-center justify-center">
                                                        <span class="text-[10px] font-medium text-gray-600 dark:text-gray-300">
                                                            <?php echo strtoupper(substr($log->author_username, 0, 2)); ?>
                                                        </span>
                                                    </div>
                                                @endif
                                                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    <?php echo $log->employee->first_name ?? 'Unknown'; ?> <?php echo $log->employee->last_name ?? ''; ?>
                                                </span>
                                            </div>

                                            <!-- Event Badge -->
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $color }} border">
                                                {{ ucfirst(str_replace('_', ' ', $log->event_type)) }}
                                                @if($log->action)
                                                    <span class="ml-1 opacity-75">• {{ $log->action }}</span>
                                                @endif
                                            </span>

                                            <!-- Time -->
                                            <span class="text-xs text-gray-500 dark:text-gray-400 flex-shrink-0">
                                                {{ $log->event_at->format('M d, h:i A') }}
                                            </span>
                                        </div>

                                        <!-- Repository & Branch -->
                                        <div class="flex items-center gap-2 mb-2">
                                            <svg class="w-3.5 h-3.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                                            </svg>
                                            <a href="{{ $log->repository_url }}" target="_blank" class="text-sm font-medium text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 truncate">
                                                {{ $log->repository_name }}
                                            </a>
                                            @if($log->branch)
                                                <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded text-xs font-mono">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $log->branch }}
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Details -->
                                        <div class="text-sm text-gray-600 dark:text-gray-400">
                                            @if($log->event_type === 'push')
                                                <div class="flex items-center gap-2">
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $log->commits_count }} {{ Str::plural('commit', $log->commits_count) }}</span>
                                                    @if($log->commit_message)
                                                        <span class="text-xs">• {{ Str::limit($log->commit_message, 80) }}</span>
                                                    @endif
                                                </div>
                                            @elseif($log->event_type === 'pull_request' || $log->event_type === 'issues')
                                                <div>
                                                    <span class="font-medium text-gray-900 dark:text-gray-100">#{{ $log->pr_number }}</span>
                                                    <span class="ml-2">{{ $log->pr_title }}</span>
                                                </div>
                                            @else
                                                {{ Str::limit($log->commit_message, 80) }}
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right: Actions -->
                                    <div class="flex items-center gap-2 flex-shrink-0">
                                        @if($log->event_type === 'pull_request')
                                            <button @click="$dispatch('open-pr-modal', {{ $log->id }})" class="inline-flex items-center gap-1.5 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-all text-xs font-medium shadow-sm">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                Review
                                            </button>
                                        @elseif($log->commit_url || $log->pr_url)
                                            <a href="{{ $log->commit_url ?? $log->pr_url }}" target="_blank" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-xs font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                </svg>
                                                View
                                            </a>
                                        @endif
                                        @if($log->employee)
                                            <a href="{{ route('employees.show', $log->employee) }}?tab=github" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all text-xs font-medium">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                Profile
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    @if($logs->hasPages())
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            {{ $logs->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-12 text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Logs Found</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            @if($startDate || $endDate || $eventType || $repository || $employeeId)
                                No webhook logs match your current filters. Try adjusting your search criteria.
                            @else
                                No webhook logs have been received yet. Once your GitHub webhooks are set up, activities will appear here.
                            @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
    // Alpine.js data for PR Modal
    if (window.Alpine) {
        Alpine.data('prModalData', () => ({
            showPrModal: false,
            prLoading: false,
            prData: null,
            prComment: '',
            submittingComment: false,
            currentLogId: null,
            prTab: 'description',
            
            openPrModal(logId) {
                this.currentLogId = logId;
                this.showPrModal = true;
                this.prLoading = true;
                this.prData = null;
                this.prTab = 'description';
                this.prComment = '';
                this.loadPrDetails(logId);
            },
            
            async loadPrDetails(logId) {
                try {
                    const response = await fetch(`/github/pr/${logId}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    });
                    
                    if (!response.ok) {
                        throw new Error('Failed to fetch PR details');
                    }
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.prData = data;
                    } else {
                        alert('Error: ' + (data.error || 'Failed to load PR details'));
                        this.showPrModal = false;
                    }
                } catch (error) {
                    console.error('Error loading PR details:', error);
                    alert('Failed to load PR details. Please try again.');
                    this.showPrModal = false;
                } finally {
                    this.prLoading = false;
                }
            },
            
            async submitPrComment() {
                if (!this.prComment.trim() || this.submittingComment) return;
                
                this.submittingComment = true;
                
                try {
                    const response = await fetch(`/github/pr/${this.currentLogId}/comment`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            body: this.prComment
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Add the new comment to the list
                        if (!this.prData.comments) {
                            this.prData.comments = [];
                        }
                        this.prData.comments.push(data.comment);
                        
                        // Clear the comment field
                        this.prComment = '';
                        
                        // Show success message
                        alert('Comment posted successfully!');
                    } else {
                        alert('Error: ' + (data.error || 'Failed to post comment'));
                    }
                } catch (error) {
                    console.error('Error posting comment:', error);
                    alert('Failed to post comment. Please try again.');
                } finally {
                    this.submittingComment = false;
                }
            },
            
            async submitPrReview(event) {
                if (!this.prComment.trim() || this.submittingComment) return;
                
                this.submittingComment = true;
                
                try {
                    const response = await fetch(`/github/pr/${this.currentLogId}/review`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            body: this.prComment,
                            event: event
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Clear the comment field
                        this.prComment = '';
                        
                        // Show success message
                        const eventText = event === 'APPROVE' ? 'approved' : 'requested changes for';
                        alert(`Successfully ${eventText} the pull request!`);
                        
                        // Reload PR details to show updated status
                        this.loadPrDetails(this.currentLogId);
                    } else {
                        alert('Error: ' + (data.error || 'Failed to post review'));
                    }
                } catch (error) {
                    console.error('Error posting review:', error);
                    alert('Failed to post review. Please try again.');
                } finally {
                    this.submittingComment = false;
                }
            }
        }));
    }

    // Realtime auto-refresh
    let refreshInterval;
    const REFRESH_INTERVAL = 30000; // 30 seconds

    function startAutoRefresh() {
        refreshInterval = setInterval(() => {
            // Only refresh if no filters are active (to avoid messing with user's filtered view)
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('start_date') && !urlParams.has('end_date') && 
                !urlParams.has('event_type') && !urlParams.has('repository') && 
                !urlParams.has('employee_id')) {
                
                console.log('Auto-refreshing GitHub logs...');
                location.reload();
            }
        }, REFRESH_INTERVAL);
        
        console.log('GitHub Logs: Auto-refresh enabled (every 30 seconds)');
    }

    function stopAutoRefresh() {
        if (refreshInterval) {
            clearInterval(refreshInterval);
            console.log('GitHub Logs: Auto-refresh disabled');
        }
    }

    // Start auto-refresh when page loads
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', startAutoRefresh);
    } else {
        startAutoRefresh();
    }

    // Stop auto-refresh when leaving page
    window.addEventListener('beforeunload', stopAutoRefresh);

    // Stop/start auto-refresh based on tab visibility
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            stopAutoRefresh();
        } else {
            startAutoRefresh();
        }
    });
    </script>

    <!-- Include PR Details Modal -->
    @include('employees.partials.github-pr-modal')
</x-app-layout>

