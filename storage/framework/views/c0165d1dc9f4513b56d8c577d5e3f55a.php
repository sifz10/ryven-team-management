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
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Page Header -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-xl sm:text-2xl font-bold text-black dark:text-white">Job Applications</h1>
                        <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-0.5">Review and manage candidate applications</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Info Banner for Pending Applications -->
            <?php if(request('ai_status') === 'pending' && $applications->count() > 0): ?>
            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-xl p-4 sm:p-5 mb-6 shadow-sm">
                <div class="flex items-start gap-3 sm:gap-4">
                    <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                        <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-sm sm:text-base font-semibold text-blue-900 dark:text-blue-100 mb-1">About Pending Applications</h3>
                        <p class="text-xs sm:text-sm text-blue-800 dark:text-blue-200">
                            These applications are marked as "Pending" because:
                        </p>
                        <ul class="mt-2 space-y-1 text-xs sm:text-sm text-blue-700 dark:text-blue-300">
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                AI screening was disabled for the job post, or
                            </li>
                            <li class="flex items-start gap-2">
                                <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                AI analysis failed (PDF extraction error, API error, etc.)
                            </li>
                        </ul>
                        <p class="mt-2 text-xs sm:text-sm text-blue-800 dark:text-blue-200">
                            💡 <strong>Tip:</strong> Click "View Details" to see the full application and any error messages.
                        </p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- No AI Screening Banner -->
            <?php if(request('job_post_id') && $applications->count() > 0): ?>
                <?php
                    $selectedJob = $jobPosts->firstWhere('id', request('job_post_id'));
                ?>
                <?php if($selectedJob && !$selectedJob->ai_screening_enabled): ?>
                <div class="bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-xl p-4 sm:p-5 mb-6 shadow-sm">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm sm:text-base font-semibold text-yellow-900 dark:text-yellow-100 mb-1">AI Screening Disabled</h3>
                            <p class="text-xs sm:text-sm text-yellow-800 dark:text-yellow-200">
                                Applications for <strong>"<?php echo e($selectedJob->title); ?>"</strong> are not being analyzed by AI.
                                <a href="<?php echo e(route('admin.jobs.edit', $selectedJob)); ?>" class="underline hover:no-underline font-medium">Enable AI screening</a> to automatically evaluate future applications.
                            </p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Total Applications</p>
                            <p class="text-2xl sm:text-3xl font-bold text-black dark:text-white mt-1"><?php echo e($applications->total()); ?></p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-green-200 dark:border-green-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-green-100 dark:hover:shadow-green-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Best Match</p>
                            <p class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400 mt-1"><?php echo e($applications->where('ai_status', 'best_match')->count()); ?></p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-green-200 dark:shadow-green-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-blue-200 dark:border-blue-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-blue-100 dark:hover:shadow-blue-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Good to Go</p>
                            <p class="text-2xl sm:text-3xl font-bold text-blue-600 dark:text-blue-400 mt-1"><?php echo e($applications->where('ai_status', 'good_to_go')->count()); ?></p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-blue-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-200 dark:shadow-blue-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-yellow-200 dark:border-yellow-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-yellow-100 dark:hover:shadow-yellow-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Pending Review</p>
                            <p class="text-2xl sm:text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1"><?php echo e($applications->where('ai_status', 'pending')->count()); ?></p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-200 dark:shadow-yellow-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8 shadow-sm">
                <form method="GET" class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text"
                                       name="search"
                                       value="<?php echo e(request('search')); ?>"
                                       placeholder="Search by name or email..."
                                       class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent dark:bg-gray-700 dark:text-white transition-all text-sm sm:text-base">
                            </div>
                        </div>

                        <!-- Job Post Select -->
                        <div class="w-full sm:w-56">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <select name="job_post_id" class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none transition-all text-sm sm:text-base">
                                    <option value="">All Jobs</option>
                                    <?php $__currentLoopData = $jobPosts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <option value="<?php echo e($job->id); ?>" <?php echo e(request('job_post_id') == $job->id ? 'selected' : ''); ?>><?php echo e($job->title); ?></option>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>

                        <!-- AI Status Select -->
                        <div class="w-full sm:w-56">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                                <select name="ai_status" class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none transition-all text-sm sm:text-base">
                                    <option value="">All AI Status</option>
                                    <option value="best_match" <?php echo e(request('ai_status') === 'best_match' ? 'selected' : ''); ?>>Best Match</option>
                                    <option value="good_to_go" <?php echo e(request('ai_status') === 'good_to_go' ? 'selected' : ''); ?>>Good to Go</option>
                                    <option value="not_good_fit" <?php echo e(request('ai_status') === 'not_good_fit' ? 'selected' : ''); ?>>Not a Good Fit</option>
                                    <option value="pending" <?php echo e(request('ai_status') === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['type' => 'submit','class' => 'flex-1 sm:flex-initial justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'flex-1 sm:flex-initial justify-center']); ?>
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                            </svg>
                            <span>Apply Filters</span>
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
                        <?php if(request()->hasAny(['search', 'job_post_id', 'ai_status'])): ?>
                            <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['variant' => 'outline','href' => ''.e(route('admin.applications.index')).'','class' => 'flex-1 sm:flex-initial justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('admin.applications.index')).'','class' => 'flex-1 sm:flex-initial justify-center']); ?>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Clear Filters</span>
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
                        <?php endif; ?>
                    </div>
                </form>
            </div>

            <!-- Applications List -->
            <div class="space-y-4">
                <?php if($applications->count() > 0): ?>
                    <?php $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-xl hover:scale-[1.01] transition-all duration-300">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                <!-- Applicant Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 flex items-center justify-center text-white dark:text-black font-bold text-lg shadow-lg group-hover:scale-110 transition-transform">
                                            <?php echo e(strtoupper(substr($application->first_name, 0, 1) . substr($application->last_name, 0, 1))); ?>

                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="<?php echo e(route('admin.applications.show', $application)); ?>" class="text-base sm:text-lg font-bold text-black dark:text-white hover:text-gray-700 dark:hover:text-gray-300 transition-colors block truncate">
                                                <?php echo e($application->full_name); ?>

                                            </a>
                                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 truncate"><?php echo e($application->email); ?></p>

                                            <!-- Mobile Job & Date -->
                                            <div class="flex flex-col gap-1 mt-2 lg:hidden">
                                                <p class="text-xs text-gray-600 dark:text-gray-400">
                                                    Applied for: <strong class="text-black dark:text-white"><?php echo e($application->jobPost->title); ?></strong>
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-500"><?php echo e($application->created_at->diffForHumans()); ?></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Metadata -->
                                <div class="hidden lg:flex items-center gap-6">
                                    <!-- Job Title -->
                                    <div class="text-center min-w-[150px]">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Applied For</p>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 truncate"><?php echo e($application->jobPost->title); ?></p>
                                    </div>

                                    <!-- Date -->
                                    <div class="text-center min-w-[120px]">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Applied</p>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300"><?php echo e($application->created_at->diffForHumans()); ?></p>
                                    </div>
                                </div>

                                <!-- Status & Action -->
                                <div class="flex flex-col items-stretch lg:items-end gap-2 pt-3 lg:pt-0 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 lg:pl-6">
                                    <?php if($application->ai_status === 'best_match'): ?>
                                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                            <span>Best Match (<?php echo e($application->ai_match_score); ?>%)</span>
                                        </span>
                                    <?php elseif($application->ai_status === 'good_to_go'): ?>
                                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>Good to Go (<?php echo e($application->ai_match_score); ?>%)</span>
                                        </span>
                                    <?php elseif($application->ai_status === 'pending'): ?>
                                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            <span>
                                                <?php if($application->jobPost->ai_screening_enabled): ?>
                                                    Pending AI Review
                                                <?php else: ?>
                                                    Manual Review
                                                <?php endif; ?>
                                            </span>
                                        </span>
                                    <?php else: ?>
                                        <span class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                            <span>Not a Good Fit (<?php echo e($application->ai_match_score); ?>%)</span>
                                        </span>
                                    <?php endif; ?>
                                    <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.applications.show', $application)).'','class' => 'w-full justify-center text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.applications.show', $application)).'','class' => 'w-full justify-center text-xs']); ?>
                                        <span>View Details</span>
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
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
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <!-- Pagination -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 px-6 py-4">
                        <?php echo e($applications->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No applications found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Applications will appear here when candidates apply for your job posts</p>
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
<?php /**PATH F:\Project\salary\resources\views/admin/applications/index.blade.php ENDPATH**/ ?>