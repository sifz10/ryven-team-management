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
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">AI Analysis Results</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Resumes analyzed for: <span class="font-semibold text-black dark:text-white"><?php echo e($job->title); ?></span></p>
                </div>
                <div class="flex gap-3">
                    <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['variant' => 'outline','href' => ''.e(route('admin.jobs.bulk-upload', $job)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('admin.jobs.bulk-upload', $job)).'']); ?>
                        Upload More
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783)): ?>
<?php $attributes = $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783; ?>
<?php unset($__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf9bc64087ea57ded106e8b72ce8d783)): ?>
<?php $component = $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783; ?>
<?php unset($__componentOriginaldf9bc64087ea57ded106e8b72ce8d783); ?>
<?php endif; ?>
                    <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.jobs.show', $job)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.jobs.show', $job)).'']); ?>
                        Back to Job
                     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783)): ?>
<?php $attributes = $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783; ?>
<?php unset($__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783); ?>
<?php endif; ?>
<?php if (isset($__componentOriginaldf9bc64087ea57ded106e8b72ce8d783)): ?>
<?php $component = $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783; ?>
<?php unset($__componentOriginaldf9bc64087ea57ded106e8b72ce8d783); ?>
<?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="max-w-7xl mx-auto">
                <!-- Summary Stats -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                    <!-- Total Processed -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Processed</p>
                                <p class="text-3xl font-bold text-black dark:text-white mt-1">
                                    <?php echo e(count($results['best_match']) + count($results['good_to_go']) + count($results['not_good_fit']) + count($results['errors'])); ?>

                                </p>
                            </div>
                            <svg class="w-12 h-12 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Best Match -->
                    <div class="bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-green-700 dark:text-green-400">Best Match</p>
                                <p class="text-3xl font-bold text-green-900 dark:text-green-100 mt-1">
                                    <?php echo e(count($results['best_match'])); ?>

                                </p>
                            </div>
                            <svg class="w-12 h-12 text-green-300 dark:text-green-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Good to Go -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-blue-700 dark:text-blue-400">Good to Go</p>
                                <p class="text-3xl font-bold text-blue-900 dark:text-blue-100 mt-1">
                                    <?php echo e(count($results['good_to_go'])); ?>

                                </p>
                            </div>
                            <svg class="w-12 h-12 text-blue-300 dark:text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Not a Good Fit -->
                    <div class="bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800 p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-red-700 dark:text-red-400">Not a Good Fit</p>
                                <p class="text-3xl font-bold text-red-900 dark:text-red-100 mt-1">
                                    <?php echo e(count($results['not_good_fit'])); ?>

                                </p>
                            </div>
                            <svg class="w-12 h-12 text-red-300 dark:text-red-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Errors Section -->
                <?php if(count($results['errors']) > 0): ?>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg p-6 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-yellow-900 dark:text-yellow-100 mb-2">
                                Processing Errors (<?php echo e(count($results['errors'])); ?>)
                            </h3>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $results['errors']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="text-sm text-yellow-800 dark:text-yellow-200 bg-yellow-100 dark:bg-yellow-900/30 rounded px-3 py-2">
                                    <span class="font-medium"><?php echo e($error['filename']); ?></span>: <?php echo e($error['error']); ?>

                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Best Match Section -->
                <?php if(count($results['best_match']) > 0): ?>
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-green-900 dark:text-green-100">Best Match</h2>
                        <span class="text-sm text-green-700 dark:text-green-400">(<?php echo e(count($results['best_match'])); ?> candidates)</span>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $results['best_match']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-green-500 dark:border-green-600 p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-black dark:text-white mb-1"><?php echo e($result['application']->first_name); ?> <?php echo e($result['application']->last_name); ?></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($result['application']->email); ?></p>
                                </div>
                                <div class="flex items-center gap-2 bg-green-100 dark:bg-green-900/30 px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-green-700 dark:text-green-300"><?php echo e($result['score']); ?>%</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">AI Analysis:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($result['analysis']); ?></p>
                            </div>

                            <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="<?php echo e(route('admin.applications.show', $result['application'])); ?>" class="flex-1 text-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-medium hover:scale-105 transition-transform">
                                    View Details
                                </a>
                                <a href="<?php echo e(asset('storage/' . $result['application']->resume_path)); ?>" target="_blank" class="px-4 py-2 border-2 border-black dark:border-white text-black dark:text-white rounded-full text-sm font-medium hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors">
                                    Download
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Good to Go Section -->
                <?php if(count($results['good_to_go']) > 0): ?>
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                        </svg>
                        <h2 class="text-xl font-bold text-blue-900 dark:text-blue-100">Good to Go</h2>
                        <span class="text-sm text-blue-700 dark:text-blue-400">(<?php echo e(count($results['good_to_go'])); ?> candidates)</span>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $results['good_to_go']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-blue-500 dark:border-blue-600 p-6 hover:shadow-lg transition-shadow">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-black dark:text-white mb-1"><?php echo e($result['application']->first_name); ?> <?php echo e($result['application']->last_name); ?></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($result['application']->email); ?></p>
                                </div>
                                <div class="flex items-center gap-2 bg-blue-100 dark:bg-blue-900/30 px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-blue-700 dark:text-blue-300"><?php echo e($result['score']); ?>%</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">AI Analysis:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($result['analysis']); ?></p>
                            </div>

                            <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="<?php echo e(route('admin.applications.show', $result['application'])); ?>" class="flex-1 text-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-medium hover:scale-105 transition-transform">
                                    View Details
                                </a>
                                <a href="<?php echo e(asset('storage/' . $result['application']->resume_path)); ?>" target="_blank" class="px-4 py-2 border-2 border-black dark:border-white text-black dark:text-white rounded-full text-sm font-medium hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors">
                                    Download
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Not a Good Fit Section -->
                <?php if(count($results['not_good_fit']) > 0): ?>
                <div class="mb-8">
                    <div class="flex items-center gap-2 mb-4">
                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-red-900 dark:text-red-100">Not a Good Fit</h2>
                        <span class="text-sm text-red-700 dark:text-red-400">(<?php echo e(count($results['not_good_fit'])); ?> candidates)</span>
                    </div>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <?php $__currentLoopData = $results['not_good_fit']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white dark:bg-gray-800 rounded-lg border-2 border-red-500 dark:border-red-600 p-6 hover:shadow-lg transition-shadow opacity-75">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex-1">
                                    <h3 class="text-lg font-bold text-black dark:text-white mb-1"><?php echo e($result['application']->first_name); ?> <?php echo e($result['application']->last_name); ?></h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($result['application']->email); ?></p>
                                </div>
                                <div class="flex items-center gap-2 bg-red-100 dark:bg-red-900/30 px-3 py-1.5 rounded-full">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm font-semibold text-red-700 dark:text-red-300"><?php echo e($result['score']); ?>%</span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-2">AI Analysis:</h4>
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($result['analysis']); ?></p>
                            </div>

                            <div class="flex items-center gap-2 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="<?php echo e(route('admin.applications.show', $result['application'])); ?>" class="flex-1 text-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-medium hover:scale-105 transition-transform">
                                    View Details
                                </a>
                                <a href="<?php echo e(asset('storage/' . $result['application']->resume_path)); ?>" target="_blank" class="px-4 py-2 border-2 border-black dark:border-white text-black dark:text-white rounded-full text-sm font-medium hover:bg-black hover:text-white dark:hover:bg-white dark:hover:text-black transition-colors">
                                    Download
                                </a>
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- What's Next Section -->
                <?php if(count($results['best_match']) > 0 || count($results['good_to_go']) > 0 || count($results['not_good_fit']) > 0): ?>
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mt-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">What's Next?</h3>
                            <ul class="space-y-2 text-sm text-blue-800 dark:text-blue-200">
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span><strong>All resumes have been saved</strong> to the applications list</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Click <strong>"View Details"</strong> on any card above to see full AI analysis</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Go to <strong>Applications</strong> page to manage all candidates</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <svg class="w-5 h-5 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Filter by <strong>"Best Match"</strong> or <strong>"Good to Go"</strong> to prioritize top candidates</span>
                                </li>
                            </ul>
                            <div class="mt-4 flex gap-3">
                                <a href="<?php echo e(route('admin.applications.index', ['job_post' => $job->id])); ?>" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                    </svg>
                                    View All Applications for This Job
                                </a>
                                <a href="<?php echo e(route('admin.applications.index', ['job_post' => $job->id, 'ai_status' => 'best_match'])); ?>" class="inline-flex items-center gap-2 px-4 py-2 border-2 border-blue-600 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg text-sm font-medium transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    View Best Matches Only
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>

                <!-- No Results Message -->
                <?php if(count($results['best_match']) === 0 && count($results['good_to_go']) === 0 && count($results['not_good_fit']) === 0 && count($results['errors']) === 0): ?>
                <div class="text-center py-12">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Results</h3>
                    <p class="text-gray-600 dark:text-gray-400">Upload resumes to see AI analysis results.</p>
                </div>
                <?php endif; ?>
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
<?php /**PATH F:\Project\salary\resources\views/admin/jobs/bulk-results.blade.php ENDPATH**/ ?>