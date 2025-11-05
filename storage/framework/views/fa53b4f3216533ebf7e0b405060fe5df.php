<?php
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
    
    $githubLogs = $logsQuery->orderBy('event_at', 'desc')->limit(15)->get();
    
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
?>

<div class="space-y-6" x-data="prModalData" @open-pr-modal.window="openPrModal($event.detail)">
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
            <form method="GET" action="<?php echo e(route('employees.show', $employee)); ?>" class="space-y-4" x-data="{ 
                startDate: '<?php echo e($startDate); ?>', 
                endDate: '<?php echo e($endDate); ?>',
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
                            <?php $__currentLoopData = $eventTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($type); ?>" <?php if($eventType === $type): echo 'selected'; endif; ?>>
                                    <?php echo e(ucfirst(str_replace('_', ' ', $type))); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                            <?php $__currentLoopData = $repositories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($repo); ?>" <?php if($repository === $repo): echo 'selected'; endif; ?>><?php echo e($repo); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                    <?php if($startDate || $endDate || $eventType || $repository): ?>
                        <a href="<?php echo e(route('employees.show', $employee)); ?>?tab=github" class="inline-flex items-center gap-2 px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-400 rounded-full hover:bg-red-200 dark:hover:bg-red-900/50 transition-all text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Clear Filters
                        </a>
                    <?php endif; ?>
                    <?php if($startDate || $endDate || $eventType || $repository): ?>
                        <span class="text-xs text-gray-600 dark:text-gray-400 ml-auto">
                            <strong>Filtered:</strong>
                            <?php if($startDate): ?> From <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d')); ?> <?php endif; ?>
                            <?php if($endDate): ?> to <?php echo e(\Carbon\Carbon::parse($endDate)->format('M d')); ?> <?php endif; ?>
                            <?php if($eventType): ?> • <?php echo e(ucfirst(str_replace('_', ' ', $eventType))); ?> <?php endif; ?>
                            <?php if($repository): ?> • <?php echo e($repository); ?> <?php endif; ?>
                        </span>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-600">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide font-medium">Total Activities</div>
                        <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo e($totalActivities); ?></div>
                    </div>
                    <div class="p-3 bg-white dark:bg-gray-900 rounded-xl shadow-sm">
                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/30 dark:to-blue-800/30 rounded-xl p-5 border border-blue-200 dark:border-blue-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-blue-600 dark:text-blue-400 uppercase tracking-wide font-medium">Total Commits</div>
                        <div class="text-3xl font-bold text-blue-900 dark:text-blue-200 mt-2"><?php echo e($totalCommits); ?></div>
                    </div>
                    <div class="p-3 bg-blue-200 dark:bg-blue-900 rounded-xl">
                        <svg class="w-5 h-5 text-blue-700 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/30 dark:to-purple-800/30 rounded-xl p-5 border border-purple-200 dark:border-purple-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-purple-600 dark:text-purple-400 uppercase tracking-wide font-medium">Pull Requests</div>
                        <div class="text-3xl font-bold text-purple-900 dark:text-purple-200 mt-2"><?php echo e($prCount); ?></div>
                    </div>
                    <div class="p-3 bg-purple-200 dark:bg-purple-900 rounded-xl">
                        <svg class="w-5 h-5 text-purple-700 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/30 dark:to-green-800/30 rounded-xl p-5 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-xs text-green-600 dark:text-green-400 uppercase tracking-wide font-medium">Repositories</div>
                        <div class="text-3xl font-bold text-green-900 dark:text-green-200 mt-2"><?php echo e($repositories->count()); ?></div>
                    </div>
                    <div class="p-3 bg-green-200 dark:bg-green-900 rounded-xl">
                        <svg class="w-5 h-5 text-green-700 dark:text-green-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <?php if($repositories->isNotEmpty()): ?>
        <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
            <div class="text-xs text-gray-600 dark:text-gray-400 uppercase tracking-wide font-medium mb-2">Active Repositories</div>
            <div class="flex flex-wrap gap-2">
                <?php $__currentLoopData = $repositories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repo): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <span class="inline-flex items-center px-3 py-1 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 text-xs rounded-full">
                        <svg class="w-3 h-3 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <?php echo e($repo); ?>

                    </span>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Activity Timeline -->
    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        Activity Timeline
                        <span id="refresh-indicator" class="hidden">
                            <svg class="animate-spin w-4 h-4 text-blue-600" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Recent GitHub activities • Auto-refreshes every 30s</p>
                </div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                    <span class="font-medium text-gray-900 dark:text-white" id="total-activities"><?php echo e($totalActivities); ?></span> activities
                    </div>
            </div>
        </div>

        <div id="activities-container">
            <?php $__empty_1 = true; $__currentLoopData = $githubLogs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $log): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php echo $__env->make('employees.partials.github-activity-item', ['githubLogs' => collect([$log]), 'employee' => $employee], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="p-12">
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-4">
                            <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                        <?php if($startDate || $endDate): ?>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Activity in Selected Date Range</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto mb-4">
                                No GitHub activities found between <?php echo e(\Carbon\Carbon::parse($startDate)->format('M d, Y')); ?> and <?php echo e(\Carbon\Carbon::parse($endDate)->format('M d, Y')); ?>.
                            </p>
                            <a href="<?php echo e(route('employees.show', ['employee' => $employee, 'tab' => 'github'])); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full hover:bg-gray-800 transition-all shadow text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                View All Activities
                            </a>
                        <?php else: ?>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No GitHub Activity Yet</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                                Once you set up the GitHub webhook and this employee starts pushing code or creating pull requests, their activity will appear here.
                            </p>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Setup Instructions Card -->
                    <div class="max-w-2xl mx-auto">
                        <div class="p-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl text-left">
                            <div class="flex items-start gap-3 mb-4">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/40 rounded-lg flex-shrink-0">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-3">Setup Instructions</h4>
                                    <ol class="list-decimal list-inside space-y-2 mb-4 text-sm text-gray-700 dark:text-gray-300">
                                        <li>Go to your GitHub repository settings</li>
                                        <li>Add a new webhook with URL: <code class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs"><?php echo e(url('/webhook/github')); ?></code></li>
                                        <li>Select events: Push, Pull Request, Issues</li>
                                    </ol>
                                    <h4 class="font-semibold text-gray-900 dark:text-white mb-2">Employee Matching</h4>
                                    <ul class="space-y-2 text-sm text-gray-700 dark:text-gray-300">
                                        <?php if($employee->github_username): ?>
                                            <li class="flex items-center gap-2">
                                                <span class="text-green-600 dark:text-green-400">✓</span>
                                                GitHub username set: <code class="px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded text-xs font-medium">{{ $employee->github_username }}</code>
                                            </li>
                                        <?php else: ?>
                                            <li class="flex items-center gap-2">
                                                <span class="text-yellow-600 dark:text-yellow-400">⚠</span>
                                                <span>Add GitHub username in <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="underline font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300">employee settings</a></span>
                                            </li>
                                        <?php endif; ?>
                                        <li class="flex items-center gap-2">
                                            <span class="text-blue-600 dark:text-blue-400">•</span>
                                            Email matching: <code class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded text-xs"><?php echo e($employee->email); ?></code>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Load More Button -->
        <?php if($githubLogs->count() >= 15): ?>
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900 text-center">
                <button type="button" id="load-more-btn" class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-all shadow-lg text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                    <span id="load-more-text">Load More Activities</span>
                    <svg id="load-more-spinner" class="hidden animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// GitHub Activities Manager
const GitHubActivities = {
    employeeId: <?php echo e($employee->id); ?>,
    currentPage: 1,
    hasMore: <?php echo e($githubLogs->count() >= 15 ? 'true' : 'false'); ?>,
    loading: false,
    polling: false,
    pollInterval: null,
    
    // Get current filters from URL
    getFilters() {
        const params = new URLSearchParams(window.location.search);
        return {
            start_date: params.get('start_date') || '',
            end_date: params.get('end_date') || '',
            event_type: params.get('event_type') || '',
            repository: params.get('repository') || ''
        };
    },
    
    // Get latest activity ID
    getLatestId() {
        const firstActivity = document.querySelector('.activity-item');
        return firstActivity ? parseInt(firstActivity.dataset.activityId) : 0;
    },
    
    // Load more activities
    async loadMore() {
        if (this.loading || !this.hasMore) return;
        
        this.loading = true;
        const btn = document.getElementById('load-more-btn');
        const text = document.getElementById('load-more-text');
        const spinner = document.getElementById('load-more-spinner');
        
        // Show loading state
        btn.disabled = true;
        text.textContent = 'Loading...';
        spinner.classList.remove('hidden');
        
        try {
            const filters = this.getFilters();
            const params = new URLSearchParams({
                ...filters,
                page: this.currentPage + 1
            });
            
            const response = await fetch(`/employees/${this.employeeId}/github/activities?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to load activities');
            
            const data = await response.json();
            
            if (data.success && data.html) {
                // Append new activities
                const container = document.getElementById('activities-container');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                
                while (tempDiv.firstChild) {
                    container.appendChild(tempDiv.firstChild);
                }
                
                this.currentPage = data.nextPage;
                this.hasMore = data.hasMore;
                
                // Hide button if no more activities
                if (!data.hasMore) {
                    btn.parentElement.innerHTML = `
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            All activities loaded
                        </div>
                    `;
                }
            }
        } catch (error) {
            console.error('Error loading activities:', error);
            text.textContent = 'Error loading. Try again?';
        } finally {
            this.loading = false;
            btn.disabled = false;
            text.textContent = 'Load More Activities';
            spinner.classList.add('hidden');
        }
    },

    // Check for new activities
    async checkNewActivities() {
        if (this.polling) return;
        
        this.polling = true;
        const indicator = document.getElementById('refresh-indicator');
        indicator.classList.remove('hidden');
        
        try {
            const filters = this.getFilters();
            const params = new URLSearchParams({
                ...filters,
                last_id: this.getLatestId()
            });
            
            const response = await fetch(`/employees/${this.employeeId}/github/check-new?${params}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (!response.ok) throw new Error('Failed to check new activities');
            
            const data = await response.json();
            
            if (data.success && data.hasNew) {
                // Prepend new activities with animation
                const container = document.getElementById('activities-container');
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = data.html;
                
                // Insert new activities at the top
                while (tempDiv.lastChild) {
                    const newActivity = tempDiv.lastChild;
                    newActivity.classList.add('new-activity');
                    container.insertBefore(newActivity, container.firstChild);
                }
                
                // Update total count
                const totalEl = document.getElementById('total-activities');
                if (totalEl) {
                    const currentTotal = parseInt(totalEl.textContent);
                    totalEl.textContent = currentTotal + data.count;
                }
                
                // Flash animation
                setTimeout(() => {
                    document.querySelectorAll('.new-activity').forEach(el => {
                        el.classList.remove('new-activity');
                    });
                }, 2000);
            }
        } catch (error) {
            console.error('Error checking new activities:', error);
        } finally {
            this.polling = false;
            indicator.classList.add('hidden');
        }
    },
    
    // Start auto-refresh
    startAutoRefresh() {
        // Check every 30 seconds
        this.pollInterval = setInterval(() => {
            this.checkNewActivities();
        }, 30000);
    },
    
    // Stop auto-refresh
    stopAutoRefresh() {
        if (this.pollInterval) {
            clearInterval(this.pollInterval);
            this.pollInterval = null;
        }
    },
    
    // Initialize
    init() {
        // Load more button
        const loadMoreBtn = document.getElementById('load-more-btn');
        if (loadMoreBtn) {
            loadMoreBtn.addEventListener('click', () => this.loadMore());
        }
        
        // Start auto-refresh
        this.startAutoRefresh();
        
        // Stop auto-refresh when leaving page
        window.addEventListener('beforeunload', () => this.stopAutoRefresh());
        
        // Stop auto-refresh when tab becomes hidden
        document.addEventListener('visibilitychange', () => {
            if (document.hidden) {
                this.stopAutoRefresh();
            } else {
                this.startAutoRefresh();
                // Check immediately when tab becomes visible
                this.checkNewActivities();
            }
        });
        
        console.log('GitHub Activities Manager initialized');
    }
};

// Initialize when DOM is ready
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', () => GitHubActivities.init());
} else {
    GitHubActivities.init();
}
</script>

<style>
.new-activity {
    animation: slideInDown 0.5s ease-out, highlightFade 2s ease-out;
}

@keyframes slideInDown {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes highlightFade {
    0%, 50% {
        background-color: rgba(59, 130, 246, 0.1);
    }
    100% {
        background-color: transparent;
    }
}
</style>

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
</script>

<!-- Include PR Details Modal AFTER Alpine.js data is registered -->
<?php echo $__env->make('employees.partials.github-pr-modal', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

<?php /**PATH F:\Project\salary\resources\views/employees/partials/github-activity.blade.php ENDPATH**/ ?>