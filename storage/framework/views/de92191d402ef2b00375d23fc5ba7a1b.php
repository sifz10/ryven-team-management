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
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Salary History
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?>

                </p>
            </div>
            <a href="<?php echo e(route('employees.show', $employee)); ?>" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Profile
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <!-- Page Content -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="w-full space-y-8">
                
                <!-- Current Salary Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Current Salary</p>
                                <p class="text-5xl font-bold text-gray-900 dark:text-white">
                                    <?php echo e(number_format($employee->salary, 2)); ?>

                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2"><?php echo e($employee->currency ?? 'USD'); ?></p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-3">Position</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white"><?php echo e($employee->position ?? 'N/A'); ?></p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Hired: <?php echo e($employee->hired_at->format('M d, Y')); ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary Reviews Section -->
                <?php if($reviews->count() > 0): ?>
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6-Month Salary Reviews</h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <a href="<?php echo e(route('salary-reviews.show', $review)); ?>" class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 border-l-4 <?php if($review->status === 'completed'): ?> border-l-green-500 <?php elseif($review->status === 'in_progress'): ?> border-l-blue-500 <?php else: ?> border-l-yellow-500 <?php endif; ?>">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Review Date: <?php echo e($review->review_date->format('M d, Y')); ?>

                                        </h4>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            <?php if($review->status === 'pending'): ?> bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            <?php elseif($review->status === 'in_progress'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            <?php elseif($review->status === 'completed'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            <?php endif; ?>
                                        ">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $review->status))); ?>

                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Previous Salary</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                <?php echo e(number_format($review->previous_salary, 2)); ?>

                                            </p>
                                        </div>
                                        <?php if($review->adjusted_salary): ?>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">New Salary</p>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    <?php echo e(number_format($review->adjusted_salary, 2)); ?>

                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Adjustment</p>
                                                <p class="text-lg font-semibold <?php if($review->adjustment_amount > 0): ?> text-green-600 dark:text-green-400 <?php elseif($review->adjustment_amount < 0): ?> text-red-600 dark:text-red-400 <?php else: ?> text-gray-600 dark:text-gray-400 <?php endif; ?>">
                                                    <?php echo e($review->adjustment_amount > 0 ? '+' : ''); ?><?php echo e(number_format($review->adjustment_amount, 2)); ?>

                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Performance</p>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    <?php if($review->performance_rating): ?>
                                                        <?php
                                                            $ratings = [
                                                                'poor' => '🔴',
                                                                'below_average' => '🟠',
                                                                'average' => '🟡',
                                                                'good' => '🟢',
                                                                'excellent' => '💚'
                                                            ];
                                                        ?>
                                                        <?php echo e($ratings[$review->performance_rating] ?? ''); ?> <?php echo e(ucfirst(str_replace('_', ' ', $review->performance_rating))); ?>

                                                    <?php else: ?>
                                                        —
                                                    <?php endif; ?>
                                                </p>
                                            </div>
                                        <?php else: ?>
                                            <div class="col-span-3">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 italic">Pending review completion...</p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($review->adjustment_reason): ?>
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Reason</p>
                                            <p class="text-gray-900 dark:text-white"><?php echo e($review->adjustment_reason); ?></p>
                                        </div>
                                    <?php endif; ?>
                                </a>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Adjustment History Section -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Complete Adjustment History</h3>
                    
                    <?php if($adjustmentHistory->count() > 0): ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Date</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Type</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Old Salary</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">New Salary</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Adjustment</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Reason</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">By</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    <?php $__currentLoopData = $adjustmentHistory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $adjustment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                <?php echo e($adjustment->created_at->format('M d, Y H:i')); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    <?php if($adjustment->type === 'review'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    <?php elseif($adjustment->type === 'promotion'): ?> bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    <?php elseif($adjustment->type === 'demotion'): ?> bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    <?php endif; ?>
                                                ">
                                                    <?php echo e(ucfirst($adjustment->type)); ?>

                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                                <?php echo e(number_format($adjustment->old_salary, 2)); ?> <?php echo e($adjustment->currency); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                                <?php echo e(number_format($adjustment->new_salary, 2)); ?> <?php echo e($adjustment->currency); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold
                                                <?php if($adjustment->adjustment_amount > 0): ?> text-green-600 dark:text-green-400
                                                <?php elseif($adjustment->adjustment_amount < 0): ?> text-red-600 dark:text-red-400
                                                <?php else: ?> text-gray-600 dark:text-gray-400
                                                <?php endif; ?>
                                            ">
                                                <?php echo e($adjustment->adjustment_amount > 0 ? '+' : ''); ?><?php echo e(number_format($adjustment->adjustment_amount, 2)); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                <?php echo e($adjustment->reason ?? '—'); ?>

                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                <?php if($adjustment->adjustedBy): ?>
                                                    <?php echo e($adjustment->adjustedBy->name); ?>

                                                <?php else: ?>
                                                    —
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            <?php if($adjustmentHistory->hasPages()): ?>
                                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                                    <?php echo e($adjustmentHistory->links()); ?>

                                </div>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No salary adjustments yet</p>
                        </div>
                    <?php endif; ?>
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
<?php /**PATH D:\Ryven Works\ryven-team-management\resources\views/salary-reviews/employee-history.blade.php ENDPATH**/ ?>