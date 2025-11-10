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
        <div class="sticky top-0 z-10 backdrop-blur-lg bg-white/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700 px-6 py-4 shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div class="flex items-start gap-3">
                    <a href="<?php echo e(route('admin.talent-pool.index')); ?>" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all hover:scale-105">
                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-1">
                            <h1 class="text-2xl font-bold text-black dark:text-white"><?php echo e($talentPool->full_name); ?></h1>
                            <?php if($talentPool->status === 'potential'): ?>
                                <span class="px-3 py-1 bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-200 rounded-md text-sm font-medium">
                                    Potential
                                </span>
                            <?php elseif($talentPool->status === 'contacted'): ?>
                                <span class="px-3 py-1 bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-200 rounded-md text-sm font-medium">
                                    Contacted
                                </span>
                            <?php elseif($talentPool->status === 'interested'): ?>
                                <span class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 text-teal-800 dark:text-teal-200 rounded-md text-sm font-medium">
                                    Interested
                                </span>
                            <?php elseif($talentPool->status === 'hired'): ?>
                                <span class="px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-200 rounded-md text-sm font-medium">
                                    Hired
                                </span>
                            <?php elseif($talentPool->status === 'not_interested'): ?>
                                <span class="px-3 py-1 bg-gradient-to-r from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 text-red-800 dark:text-red-200 rounded-md text-sm font-medium">
                                    Not Interested
                                </span>
                            <?php endif; ?>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Added <?php echo e($talentPool->created_at->diffForHumans()); ?>

                            <?php if($talentPool->application): ?>
                                from <a href="<?php echo e(route('admin.applications.show', $talentPool->application)); ?>" class="font-medium text-black dark:text-white hover:underline"><?php echo e($talentPool->application->jobPost->title); ?></a>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <?php if($talentPool->resume_path): ?>
                        <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['variant' => 'outline','href' => ''.e(route('admin.applications.download-resume', $talentPool->application)).'','target' => '_blank']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('admin.applications.download-resume', $talentPool->application)).'','target' => '_blank']); ?>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Resume
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
                    <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.talent-pool.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.talent-pool.index')).'']); ?>
                        Back to Pool
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

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - Details -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Contact Information -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Contact Information</h2>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Email</p>
                                </div>
                                <p class="font-medium text-black dark:text-white">
                                    <a href="mailto:<?php echo e($talentPool->email); ?>" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors"><?php echo e($talentPool->email); ?></a>
                                </p>
                            </div>

                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Phone</p>
                                </div>
                                <p class="font-medium text-black dark:text-white"><?php echo e($talentPool->phone); ?></p>
                            </div>

                            <?php if($talentPool->linkedin_url): ?>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                        </svg>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">LinkedIn</p>
                                    </div>
                                    <a href="<?php echo e($talentPool->linkedin_url); ?>" target="_blank" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">View Profile</a>
                                </div>
                            <?php endif; ?>

                            <?php if($talentPool->portfolio_url): ?>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <div class="flex items-center gap-2 mb-2">
                                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                                        </svg>
                                        <p class="text-xs font-medium text-gray-600 dark:text-gray-400">Portfolio</p>
                                    </div>
                                    <a href="<?php echo e($talentPool->portfolio_url); ?>" target="_blank" class="font-medium text-blue-600 dark:text-blue-400 hover:underline">View Portfolio</a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Skills -->
                    <?php if($talentPool->skills && count($talentPool->skills) > 0): ?>
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">Skills</h2>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <?php $__currentLoopData = $talentPool->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <span class="px-3 py-1.5 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-800 dark:text-indigo-200 rounded-md text-sm font-medium">
                                        <?php echo e($skill); ?>

                                    </span>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Notes -->
                    <?php if($talentPool->notes): ?>
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500 to-orange-600 flex items-center justify-center shadow-lg shadow-amber-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">Notes</h2>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-5">
                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap"><?php echo e($talentPool->notes); ?></p>
                            </div>
                        </div>
                    <?php endif; ?>

                    <!-- Original Application -->
                    <?php if($talentPool->application): ?>
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h2 class="text-lg font-bold text-black dark:text-white">Original Application</h2>
                            </div>
                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-5">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <p class="font-semibold text-black dark:text-white"><?php echo e($talentPool->application->jobPost->title); ?></p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Applied <?php echo e($talentPool->application->created_at->format('M d, Y')); ?></p>
                                    </div>
                                    <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.applications.show', $talentPool->application)).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.applications.show', $talentPool->application)).'']); ?>
                                        View Application
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
                    <?php endif; ?>
                </div>

                <!-- Right Column - Actions & Status -->
                <div class="space-y-6">
                    <!-- Update Status -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm sticky top-24">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-500 to-purple-600 flex items-center justify-center shadow-lg shadow-violet-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Update Status</h2>
                        </div>

                        <form action="<?php echo e(route('admin.talent-pool.update', $talentPool)); ?>" method="POST" class="space-y-4">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white">
                                    <option value="potential" <?php echo e($talentPool->status === 'potential' ? 'selected' : ''); ?>>Potential</option>
                                    <option value="contacted" <?php echo e($talentPool->status === 'contacted' ? 'selected' : ''); ?>>Contacted</option>
                                    <option value="interested" <?php echo e($talentPool->status === 'interested' ? 'selected' : ''); ?>>Interested</option>
                                    <option value="hired" <?php echo e($talentPool->status === 'hired' ? 'selected' : ''); ?>>Hired</option>
                                    <option value="not_interested" <?php echo e($talentPool->status === 'not_interested' ? 'selected' : ''); ?>>Not Interested</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience Level</label>
                                <select name="experience_level" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white">
                                    <option value="">Not Specified</option>
                                    <option value="entry" <?php echo e($talentPool->experience_level === 'entry' ? 'selected' : ''); ?>>Entry</option>
                                    <option value="junior" <?php echo e($talentPool->experience_level === 'junior' ? 'selected' : ''); ?>>Junior</option>
                                    <option value="mid" <?php echo e($talentPool->experience_level === 'mid' ? 'selected' : ''); ?>>Mid-Level</option>
                                    <option value="senior" <?php echo e($talentPool->experience_level === 'senior' ? 'selected' : ''); ?>>Senior</option>
                                    <option value="lead" <?php echo e($talentPool->experience_level === 'lead' ? 'selected' : ''); ?>>Lead</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notes</label>
                                <textarea name="notes" rows="5" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white"><?php echo e($talentPool->notes); ?></textarea>
                            </div>

                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="contacted" id="contacted" class="rounded border-gray-300 dark:border-gray-600">
                                <label for="contacted" class="text-sm text-gray-700 dark:text-gray-300">Mark as contacted today</label>
                            </div>

                            <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['type' => 'submit','class' => 'w-full justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'w-full justify-center']); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Update Talent
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
                        </form>
                    </div>

                    <!-- Info -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-5">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-black dark:text-white">Information</h2>
                        </div>
                        <div class="space-y-3">
                            <?php if($talentPool->experience_level): ?>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Experience Level</p>
                                    <p class="font-semibold text-black dark:text-white capitalize"><?php echo e($talentPool->experience_level); ?></p>
                                </div>
                            <?php endif; ?>

                            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Added To Pool</p>
                                <p class="font-semibold text-black dark:text-white"><?php echo e($talentPool->created_at->format('M d, Y')); ?></p>
                            </div>

                            <?php if($talentPool->last_contacted_at): ?>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Last Contacted</p>
                                    <p class="font-semibold text-black dark:text-white"><?php echo e($talentPool->last_contacted_at->format('M d, Y')); ?></p>
                                </div>
                            <?php endif; ?>

                            <?php if($talentPool->addedBy): ?>
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900/50 dark:to-gray-800/50 rounded-xl p-4">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Added By</p>
                                    <p class="font-semibold text-black dark:text-white"><?php echo e($talentPool->addedBy->full_name); ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Danger Zone -->
                    <div class="bg-red-50/80 dark:bg-red-900/20 backdrop-blur-sm rounded-xl border border-red-200 dark:border-red-800 p-6 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-red-500 to-orange-600 flex items-center justify-center shadow-lg shadow-red-500/20">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                            </div>
                            <h2 class="text-lg font-bold text-red-900 dark:text-red-200">Danger Zone</h2>
                        </div>
                        <p class="text-sm text-red-800 dark:text-red-300 mb-4">Removing from talent pool is permanent and cannot be undone.</p>
                        <form action="<?php echo e(route('admin.talent-pool.destroy', $talentPool)); ?>" method="POST" onsubmit="return confirm('Are you sure you want to remove this talent from the pool? This action cannot be undone.')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="w-full px-4 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-all">
                                Remove from Talent Pool
                            </button>
                        </form>
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
<?php /**PATH F:\Project\salary\resources\views/admin/talent-pool/show.blade.php ENDPATH**/ ?>