<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <?php echo e(__('Dashboard')); ?>

                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Quick performance overview and team insights</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-2">
                <a href="<?php echo e(route('employees.create')); ?>" class="inline-flex items-center justify-center px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-sm">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="hidden sm:inline">Add Employee</span>
                    <span class="sm:hidden">Add</span>
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <!-- ====================================== -->
        <!-- QUICK PERFORMANCE METRICS -->
        <!-- ====================================== -->
        <div>
            <div class="flex items-center gap-2 mb-4">
                <div class="p-1.5 bg-black dark:bg-white rounded-lg">
                    <svg class="w-4 h-4 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick Performance Metrics</h3>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Active Employees -->
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-2xl border-2 border-blue-200 dark:border-blue-700 p-5 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-blue-600 dark:bg-blue-500 rounded-xl shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-blue-900 dark:text-blue-100 mb-1"><?php echo e($employeesCount); ?></div>
                    <div class="text-sm font-medium text-blue-700 dark:text-blue-300">Active Employees</div>
                    <?php if($discontinuedCount > 0): ?>
                        <div class="mt-3 pt-3 border-t border-blue-300 dark:border-blue-700 text-xs text-blue-600 dark:text-blue-400">
                            <?php echo e($discontinuedCount); ?> discontinued
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Attendance Rate -->
                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-2xl border-2 border-green-200 dark:border-green-700 p-5 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-green-600 dark:bg-green-500 rounded-xl shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-green-900 dark:text-green-100 mb-1"><?php echo e($attendanceStats['attendance_rate']); ?>%</div>
                    <div class="text-sm font-medium text-green-700 dark:text-green-300">Attendance Rate</div>
                    <div class="mt-3 pt-3 border-t border-green-300 dark:border-green-700 text-xs text-green-600 dark:text-green-400">
                        Last 30 days • <?php echo e($attendanceStats['present']); ?> present
                    </div>
                </div>

                <!-- GitHub Activity -->
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-2xl border-2 border-purple-200 dark:border-purple-700 p-5 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-purple-600 dark:bg-purple-500 rounded-xl shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.430.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-purple-900 dark:text-purple-100 mb-1"><?php echo e($githubStats['total_commits']); ?></div>
                    <div class="text-sm font-medium text-purple-700 dark:text-purple-300">Total Commits</div>
                    <div class="mt-3 pt-3 border-t border-purple-300 dark:border-purple-700 text-xs text-purple-600 dark:text-purple-400">
                        <?php echo e($githubStats['pull_requests']); ?> PRs • <?php echo e($githubStats['reviews']); ?> Reviews
                    </div>
                </div>

                <!-- Payments This Month -->
                <div class="bg-gradient-to-br from-orange-50 to-orange-100 dark:from-orange-900/20 dark:to-orange-800/20 rounded-2xl border-2 border-orange-200 dark:border-orange-700 p-5 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2.5 bg-orange-600 dark:bg-orange-500 rounded-xl shadow-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-orange-900 dark:text-orange-100 mb-1"><?php echo e($paymentsMonthCount); ?></div>
                    <div class="text-sm font-medium text-orange-700 dark:text-orange-300">Payments Processed</div>
                    <?php if($paymentTrend != 0): ?>
                        <div class="mt-3 pt-3 border-t border-orange-300 dark:border-orange-700 flex items-center text-xs">
                            <?php if($paymentTrend > 0): ?>
                                <svg class="w-3 h-3 text-green-600 dark:text-green-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                <span class="text-green-600 dark:text-green-400 font-semibold">+<?php echo e($paymentTrend); ?>%</span>
                            <?php else: ?>
                                <svg class="w-3 h-3 text-red-600 dark:text-red-400 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                <span class="text-red-600 dark:text-red-400 font-semibold"><?php echo e($paymentTrend); ?>%</span>
                            <?php endif; ?>
                            <span class="ml-1 text-orange-600 dark:text-orange-400">vs last month</span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ====================================== -->
        <!-- TOP PERFORMERS & QUICK ACTIONS -->
        <!-- ====================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Top Performers (2 columns on lg) -->
            <div class="lg:col-span-2 bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-lg shadow-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">🏆 Top Performers</h3>
                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Most achievements in last 3 months</p>
                            </div>
                        </div>
                        <a href="<?php echo e(route('employees.index')); ?>" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">
                            View all →
                        </a>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php $__empty_1 = true; $__currentLoopData = $topPerformers->take(4); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $performer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <a href="<?php echo e(route('employees.show', $performer)); ?>" class="flex items-center gap-3 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                                <div class="flex-shrink-0">
                                    <?php
                                        $rankColors = [
                                            0 => 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white shadow-lg',
                                            1 => 'bg-gradient-to-br from-gray-300 to-gray-500 text-white shadow-lg',
                                            2 => 'bg-gradient-to-br from-orange-400 to-orange-600 text-white shadow-lg',
                                        ];
                                        $color = $rankColors[$index] ?? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                    ?>
                                    <div class="w-12 h-12 rounded-full <?php echo e($color); ?> flex items-center justify-center font-bold text-lg group-hover:scale-110 transition-transform">
                                        #<?php echo e($index + 1); ?>

                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <?php if($performer): ?>
                                        <div class="font-bold text-gray-900 dark:text-white truncate group-hover:text-black dark:group-hover:text-gray-200">
                                            <?php echo e($performer->first_name); ?> <?php echo e($performer->last_name); ?>

                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo e($performer->position ?? 'No position'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="text-right flex-shrink-0">
                                    <div class="text-2xl font-bold text-green-600 dark:text-green-400"><?php echo e($performer->achievement_count); ?></div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">wins</div>
                                </div>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-2 text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                                <p class="text-sm">No achievement data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Quick Stats (1 column on lg) -->
            <div class="space-y-4">
                <!-- New Hires -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-black dark:bg-white rounded-lg">
                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1"><?php echo e($newHires); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400">New hires (30 days)</div>
                    <a href="<?php echo e(route('employees.index')); ?>" class="mt-3 inline-flex items-center text-xs font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">
                        View all employees →
                    </a>
                </div>

                <!-- Contracts -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5">
                    <div class="flex items-center justify-between mb-3">
                        <div class="p-2 bg-black dark:bg-white rounded-lg">
                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1"><?php echo e($contractsCount); ?></div>
                    <div class="text-sm text-gray-600 dark:text-gray-400 mb-3">Total contracts</div>
                    <div class="pt-3 border-t border-gray-200 dark:border-gray-700 flex justify-between text-xs">
                        <span class="text-green-600 dark:text-green-400 font-semibold">✓ <?php echo e($activeContractsCount); ?> Active</span>
                        <span class="text-gray-600 dark:text-gray-400 font-semibold">📝 <?php echo e($draftContractsCount); ?> Draft</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================== -->
        <!-- DEPARTMENT ANALYTICS & PERFORMANCE TRENDS -->
        <!-- ====================================== -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Department Analytics -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black dark:bg-white rounded-lg">
                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Department Analytics</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Performance by department</p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="space-y-3 max-h-96 overflow-y-auto">
                        <?php $__empty_1 = true; $__currentLoopData = $departmentAnalytics; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $dept): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                                <div class="flex items-center justify-between mb-3">
                                    <div class="font-bold text-gray-900 dark:text-white"><?php echo e($dept['department']); ?></div>
                                    <span class="px-2.5 py-1 bg-black dark:bg-white text-white dark:text-black rounded-full text-xs font-semibold"><?php echo e($dept['employee_count']); ?> employees</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-xs">
                                    <div class="text-center p-2 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                        <div class="text-gray-600 dark:text-gray-400">Avg Salary</div>
                                        <div class="font-bold text-gray-900 dark:text-white mt-1">$<?php echo e(number_format($dept['avg_salary'])); ?></div>
                                    </div>
                                    <div class="text-center p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                        <div class="text-gray-600 dark:text-gray-400">Achievements</div>
                                        <div class="font-bold text-green-600 dark:text-green-400 mt-1"><?php echo e($dept['achievements']); ?></div>
                                    </div>
                                    <div class="text-center p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                        <div class="text-gray-600 dark:text-gray-400">Warnings</div>
                                        <div class="font-bold text-red-600 dark:text-red-400 mt-1"><?php echo e($dept['warnings']); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <p class="text-sm">No department data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Performance Trends -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black dark:bg-white rounded-lg">
                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Performance Trends</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Achievements vs Warnings (last 6 months)</p>
                        </div>
                    </div>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <?php $__currentLoopData = $performanceTrends; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $trend): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3">
                                <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-2"><?php echo e($trend['month']); ?></div>
                                <div class="space-y-1.5">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">🟢 Achievements</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400"><?php echo e($trend['achievements']); ?></span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">🔴 Warnings</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400"><?php echo e($trend['warnings']); ?></span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- ====================================== -->
        <!-- MONTHLY PAYROLL OVERVIEW -->
        <!-- ====================================== -->
        <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-black dark:bg-white rounded-lg">
                        <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Monthly Payroll Overview</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Current monthly obligations by currency</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <?php $__empty_1 = true; $__currentLoopData = $monthlyByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition-shadow">
                            <div class="flex items-center justify-between mb-3">
                                <span class="px-3 py-1.5 bg-black dark:bg-white text-white dark:text-black rounded-lg text-sm font-bold shadow-sm"><?php echo e($row->currency); ?></span>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1"><?php echo e(number_format($row->total, 2)); ?></div>
                            <div class="text-xs text-gray-500 dark:text-gray-400 mb-3">Monthly total</div>
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700 text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                <div class="flex justify-between">
                                    <span>Quarterly:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($row->total * 3, 0)); ?></span>
                                </div>
                                <div class="flex justify-between">
                                    <span>Annually:</span>
                                    <span class="font-semibold text-gray-900 dark:text-white"><?php echo e(number_format($row->total * 12, 0)); ?></span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-sm">No salary data available</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ====================================== -->
        <!-- RECENT ACTIVITY TIMELINE -->
        <!-- ====================================== -->
        <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black dark:bg-white rounded-lg">
                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity</h3>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Latest employee activities and updates</p>
                        </div>
                    </div>
                    <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                        <?php echo e($recentActivities->count()); ?> activities
                    </span>
                </div>
            </div>
            <div class="divide-y divide-gray-200 dark:divide-gray-700 max-h-96 overflow-y-auto">
                <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                        <div class="flex gap-3">
                            <?php
                                $dotColors = [
                                    'achievement' => 'bg-green-500 text-white border-green-200 dark:border-green-900',
                                    'warning' => 'bg-red-500 text-white border-red-200 dark:border-red-900',
                                    'payment' => 'bg-blue-500 text-white border-blue-200 dark:border-blue-900',
                                    'note' => 'bg-gray-500 text-white border-gray-200 dark:border-gray-700',
                                ];
                                $dotColor = $dotColors[$activity['type']] ?? $dotColors['note'];
                            ?>
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full <?php echo e($dotColor); ?> flex items-center justify-center font-semibold shadow-md text-base border-2 group-hover:scale-110 transition-transform">
                                    <?php echo e($activity['icon']); ?>

                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-3 mb-1.5 flex-wrap">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <?php
                                            $typeColors = [
                                                'achievement' => 'bg-green-50 text-green-700 dark:bg-green-500/20 dark:text-green-300 border-green-300 dark:border-green-500/30',
                                                'warning' => 'bg-red-50 text-red-700 dark:bg-red-500/20 dark:text-red-300 border-red-300 dark:border-red-500/30',
                                                'payment' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 border-blue-300 dark:border-blue-500/30',
                                                'note' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-300 border-gray-300 dark:border-gray-500/30',
                                            ];
                                            $typeBadge = $typeColors[$activity['type']] ?? $typeColors['note'];
                                        ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border <?php echo e($typeBadge); ?>">
                                            <?php echo e(strtoupper($activity['type'])); ?>

                                        </span>
                                        <?php if($activity['employee']): ?>
                                            <a href="<?php echo e(route('employees.show', ['employee' => $activity['employee'], 'tab' => 'timeline'])); ?>" 
                                               class="font-semibold text-gray-900 dark:text-white hover:text-black dark:hover:text-gray-200 transition">
                                                <?php echo e($activity['employee']->first_name); ?> <?php echo e($activity['employee']->last_name); ?>

                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                        <?php echo e($activity['date']->diffForHumans()); ?>

                                    </div>
                                </div>
                                <?php if($activity['description']): ?>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2"><?php echo e($activity['description']); ?></p>
                                <?php endif; ?>
                                <?php if($activity['amount']): ?>
                                    <div class="inline-flex items-center px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-semibold text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600">
                                        <svg class="w-3.5 h-3.5 mr-1.5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        <?php echo e(number_format($activity['amount'], 2)); ?> <?php echo e($activity['currency']); ?>

                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <p class="text-sm">No activities yet</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ====================================== -->
        <!-- QUICK ACTIONS -->
        <!-- ====================================== -->
        <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
            <div class="p-5 border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-black dark:bg-white rounded-lg">
                        <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Common tasks and shortcuts</p>
                    </div>
                </div>
            </div>
            <div class="p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="<?php echo e(route('employees.create')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                        <div class="p-3 bg-black dark:bg-white group-hover:bg-gray-800 dark:group-hover:bg-gray-200 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">Add Employee</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Create new record</div>
                        </div>
                    </a>

                    <a href="<?php echo e(route('attendance.index')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                        <div class="p-3 bg-black dark:bg-white group-hover:bg-gray-800 dark:group-hover:bg-gray-200 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">Attendance</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Track hours</div>
                        </div>
                    </a>

                    <a href="<?php echo e(route('contracts.index')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                        <div class="p-3 bg-black dark:bg-white group-hover:bg-gray-800 dark:group-hover:bg-gray-200 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">Contracts</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Manage documents</div>
                        </div>
                    </a>

                    <a href="<?php echo e(route('employees.index')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                        <div class="p-3 bg-black dark:bg-white group-hover:bg-gray-800 dark:group-hover:bg-gray-200 rounded-lg transition-colors">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 dark:text-white">All Employees</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Browse team</div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH D:\Ryven Works\ryven-team-management\resources\views/dashboard.blade.php ENDPATH**/ ?>