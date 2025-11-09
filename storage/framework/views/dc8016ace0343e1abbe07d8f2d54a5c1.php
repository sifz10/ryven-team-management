<?php if (isset($component)) { $__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.client-app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('client-app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    <?php echo e(__('Dashboard')); ?>

                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Welcome back, <?php echo e($client->name); ?>!</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
        <?php if(session('status')): ?>
            <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <?php echo e(session('status')); ?>

            </div>
        <?php endif; ?>

                <!-- Client Info Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Your Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($client->email); ?></p>
                </div>
                <?php if($client->phone): ?>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($client->phone); ?></p>
                </div>
                <?php endif; ?>
                <?php if($client->company): ?>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Company</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($client->company); ?></p>
                </div>
                <?php endif; ?>
                <?php if($client->address): ?>
                <div>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Address</p>
                    <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($client->address); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Projects Section -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                <?php echo e(isset($teamMember) && $teamMember->projects()->count() > 0 ? 'Your Assigned Projects' : 'Your Projects'); ?>

            </h3>

                <?php if($projects->count() > 0): ?>
                    <div class="space-y-4">
                        <?php $__currentLoopData = $projects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $project): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 dark:text-white mb-1"><?php echo e($project->name); ?></h4>
                                        <?php if($project->description): ?>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2"><?php echo e(Str::limit($project->description, 150)); ?></p>
                                        <?php endif; ?>
                                        <div class="flex items-center gap-4 text-sm">
                                            <span class="text-gray-600 dark:text-gray-400">
                                                Status:
                                                <span class="font-semibold capitalize <?php echo e($project->status === 'active' ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400'); ?>">
                                                    <?php echo e($project->status); ?>

                                                </span>
                                            </span>
                                            <span class="text-gray-600 dark:text-gray-400">
                                                Progress: <span class="font-semibold"><?php echo e($project->progress); ?>%</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Projects Yet</h3>
                    <p class="text-gray-600 dark:text-gray-400">Projects will appear here once they are assigned to you.</p>
                </div>
                <?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc)): ?>
<?php $attributes = $__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc; ?>
<?php unset($__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc)): ?>
<?php $component = $__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc; ?>
<?php unset($__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc); ?>
<?php endif; ?>
<?php /**PATH F:\Project\salary\resources\views/client/dashboard.blade.php ENDPATH**/ ?>