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
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                <?php echo e(__('Dashboard')); ?>

            </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Welcome back! Here's what's happening today.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="<?php echo e(route('employees.create')); ?>" class="inline-flex items-center px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 hover:shadow-xl transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Add Employee
                </a>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <?php
        $employeesCount = \App\Models\Employee::whereNull('discontinued_at')->count();
        $discontinuedCount = \App\Models\Employee::whereNotNull('discontinued_at')->count();
        $monthlyByCurrency = \App\Models\Employee::whereNull('discontinued_at')
            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(salary) as total')
            ->groupBy('currency')->get();
        $paid30ByCurrency = \App\Models\EmployeePayment::where('paid_at', '>=', now()->subDays(30))
            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(amount) as total')
            ->groupBy('currency')->get();
        $paymentsMonthCount = \App\Models\EmployeePayment::whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $paymentsLastMonthCount = \App\Models\EmployeePayment::whereBetween('paid_at', [now()->subMonth()->startOfMonth(), now()->subMonth()->endOfMonth()])->count();
        $paymentsRecent = \App\Models\EmployeePayment::with('employee')->orderByDesc('paid_at')->limit(5)->get();
        $newHires = \App\Models\Employee::whereNull('discontinued_at')->whereNotNull('hired_at')->where('hired_at', '>=', now()->subDays(30))->count();
        $contractsCount = \App\Models\EmploymentContract::count();
        $activeContractsCount = \App\Models\EmploymentContract::where('status', 'active')->count();
        $draftContractsCount = \App\Models\EmploymentContract::where('status', 'draft')->count();
        $recentContracts = \App\Models\EmploymentContract::with('employee')->latest()->limit(5)->get();
        
        // Calculate payment trend
        $paymentTrend = $paymentsLastMonthCount > 0 ? round((($paymentsMonthCount - $paymentsLastMonthCount) / $paymentsLastMonthCount) * 100, 1) : 0;
        
        // Recent Activity Log (combining payments and contracts)
        $recentActivities = \App\Models\EmployeePayment::with('employee')
            ->latest('created_at')
            ->limit(10)
            ->get()
            ->map(function($payment) {
                return [
                    'type' => $payment->activity_type ?? 'payment',
                    'employee' => $payment->employee,
                    'description' => $payment->note ?? 'Payment recorded',
                    'amount' => $payment->amount,
                    'currency' => $payment->currency ?? ($payment->employee->currency ?? 'USD'),
                    'date' => $payment->created_at,
                    'icon' => match($payment->activity_type ?? 'payment') {
                        'achievement' => '🏆',
                        'warning' => '⚠️',
                        'payment' => '💰',
                        default => '📝'
                    }
                ];
            });
    ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Top KPI Cards with Icons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Employees -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Employees</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($employeesCount); ?></div>
                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full"><?php echo e($discontinuedCount); ?> discontinued</span>
                    </div>
                </div>

                <!-- Payments This Month -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <?php if($paymentTrend > 0): ?>
                            <span class="flex items-center text-xs font-medium text-green-600 dark:text-green-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                <?php echo e($paymentTrend); ?>%
                            </span>
                        <?php elseif($paymentTrend < 0): ?>
                            <span class="flex items-center text-xs font-medium text-red-600 dark:text-red-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                <?php echo e(abs($paymentTrend)); ?>%
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Payments This Month</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($paymentsMonthCount); ?></div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">vs <?php echo e($paymentsLastMonthCount); ?> last month</div>
                </div>

                <!-- New Hires -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">New Hires (30 days)</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($newHires); ?></div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Recent additions</div>
                </div>

                <!-- Contracts -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Contracts</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($contractsCount); ?></div>
                    <div class="mt-2 flex items-center gap-2 text-xs">
                        <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full"><?php echo e($activeContractsCount); ?> active</span>
                        <?php if($draftContractsCount > 0): ?>
                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full"><?php echo e($draftContractsCount); ?> draft</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Monthly Payroll Overview -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Monthly Payroll Overview</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Current monthly obligations by currency</p>
                            </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php $__empty_1 = true; $__currentLoopData = $monthlyByCurrency; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 bg-black text-white rounded-lg text-sm font-bold"><?php echo e($row->currency); ?></span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1"><?php echo e(number_format($row->total, 2)); ?></div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Monthly total</div>
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
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
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p>No salary data available</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Payments -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-black rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Payments</h3>
                            </div>
                            <a href="<?php echo e(route('employees.index')); ?>" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">View all →</a>
                        </div>
                    </div>
                    <div class="p-6">
                            <?php $__empty_1 = true; $__currentLoopData = $paymentsRecent; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $p): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-semibold text-sm">
                                        <?php echo e(strtoupper(substr(optional($p->employee)->first_name ?? 'U', 0, 1))); ?><?php echo e(strtoupper(substr(optional($p->employee)->last_name ?? 'N', 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white"><?php echo e(optional($p->employee)->first_name); ?> <?php echo e(optional($p->employee)->last_name); ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(\Carbon\Carbon::parse($p->paid_at)->format('M d, Y')); ?></div>
                                    </div>
                                    </div>
                                    <div class="text-right">
                                        <?php if($p->amount): ?>
                                        <div class="font-bold text-gray-900 dark:text-white"><?php echo e(number_format($p->amount, 2)); ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($p->currency ?? optional($p->employee)->currency ?? 'USD'); ?></div>
                                        <?php else: ?>
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-xs">Note</span>
                                        <?php endif; ?>
                                    </div>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm">No payments yet</p>
                            </div>
                            <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Contracts -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-black rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Contracts</h3>
                            </div>
                            <a href="<?php echo e(route('contracts.index')); ?>" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">View all →</a>
                        </div>
                    </div>
                    <div class="p-6">
                        <?php $__empty_1 = true; $__currentLoopData = $recentContracts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $contract): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-semibold text-sm">
                                        <?php echo e(strtoupper(substr(optional($contract->employee)->first_name ?? 'U', 0, 1))); ?><?php echo e(strtoupper(substr(optional($contract->employee)->last_name ?? 'N', 0, 1))); ?>

                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white"><?php echo e(optional($contract->employee)->first_name); ?> <?php echo e(optional($contract->employee)->last_name); ?></div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($contract->job_title); ?></div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <?php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            'active' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                            'terminated' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                            'expired' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                        ];
                                        $badgeColor = $statusColors[$contract->status] ?? $statusColors['draft'];
                                    ?>
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold <?php echo e($badgeColor); ?>">
                                        <?php echo e(ucwords($contract->status)); ?>

                                    </span>
                                    <a href="<?php echo e(route('contracts.pdf', $contract)); ?>" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                                    </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">No contracts yet</p>
                            </div>
                            <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Log -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity Log</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Latest employee activities and updates</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <?php $__empty_1 = true; $__currentLoopData = $recentActivities; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $activity): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="flex gap-4 py-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            <!-- Timeline Dot -->
                            <div class="flex flex-col items-center">
                                <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-semibold flex-shrink-0">
                                    <?php echo e($activity['icon']); ?>

                                </div>
                                <?php if(!$loop->last): ?>
                                    <div class="w-0.5 h-full bg-gray-200 dark:bg-gray-700 mt-2"></div>
                                <?php endif; ?>
                            </div>

                            <!-- Activity Content -->
                            <div class="flex-1 pt-1">
                                <div class="flex items-start justify-between mb-1">
                                    <div>
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-gray-900 dark:text-white">
                                                <?php echo e(optional($activity['employee'])->first_name); ?> <?php echo e(optional($activity['employee'])->last_name); ?>

                                            </span>
                                            <?php
                                                $typeColors = [
                                                    'achievement' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                    'warning' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                                    'payment' => 'bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300',
                                                    'note' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                ];
                                                $typeBadge = $typeColors[$activity['type']] ?? $typeColors['note'];
                                            ?>
                                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold <?php echo e($typeBadge); ?>">
                                                <?php echo e(ucwords($activity['type'])); ?>

                                            </span>
                                        </div>
                                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($activity['description']); ?></p>
                                        <?php if($activity['amount']): ?>
                                            <div class="mt-1 text-sm font-semibold text-gray-900 dark:text-white">
                                                <?php echo e(number_format($activity['amount'], 2)); ?> <?php echo e($activity['currency']); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 text-right flex-shrink-0 ml-4">
                                        <?php echo e($activity['date']->diffForHumans()); ?>

                                    </div>
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

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Common tasks and shortcuts</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="<?php echo e(route('employees.create')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">Add Employee</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Create new record</div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('attendance.index')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">Attendance</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Track hours</div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('contracts.index')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">View Contracts</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Manage documents</div>
                            </div>
                        </a>

                        <a href="<?php echo e(route('employees.index')); ?>" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
<?php /**PATH F:\Project\salary\resources\views/dashboard.blade.php ENDPATH**/ ?>