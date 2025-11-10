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
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center shadow-lg shadow-purple-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold text-black dark:text-white">Talent Pool</h1>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 ml-[52px]">Saved candidates for future opportunities</p>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="p-6">
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg hover:scale-105 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-blue-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-black dark:text-white"><?php echo e($stats['total']); ?></p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Total</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg hover:scale-105 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-yellow-500 to-amber-600 flex items-center justify-center shadow-lg shadow-yellow-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-black dark:text-white"><?php echo e($stats['potential']); ?></p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Potential</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg hover:scale-105 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center shadow-lg shadow-indigo-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-black dark:text-white"><?php echo e($stats['contacted']); ?></p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Contacted</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg hover:scale-105 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-teal-500 to-cyan-600 flex items-center justify-center shadow-lg shadow-teal-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-black dark:text-white"><?php echo e($stats['interested']); ?></p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Interested</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg hover:scale-105 transition-all">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-emerald-600 flex items-center justify-center shadow-lg shadow-green-500/20">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-black dark:text-white"><?php echo e($stats['hired']); ?></p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Hired</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-5 mb-6 shadow-sm">
                <form method="GET" action="<?php echo e(route('admin.talent-pool.index')); ?>" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                    </svg>
                                </div>
                                <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Name or email..." class="pl-10 w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white transition-shadow">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white transition-shadow">
                                <option value="">All Statuses</option>
                                <option value="potential" <?php echo e(request('status') == 'potential' ? 'selected' : ''); ?>>Potential</option>
                                <option value="contacted" <?php echo e(request('status') == 'contacted' ? 'selected' : ''); ?>>Contacted</option>
                                <option value="interested" <?php echo e(request('status') == 'interested' ? 'selected' : ''); ?>>Interested</option>
                                <option value="hired" <?php echo e(request('status') == 'hired' ? 'selected' : ''); ?>>Hired</option>
                                <option value="not_interested" <?php echo e(request('status') == 'not_interested' ? 'selected' : ''); ?>>Not Interested</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Experience Level</label>
                            <select name="experience_level" class="w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700/50 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white transition-shadow">
                                <option value="">All Levels</option>
                                <option value="entry" <?php echo e(request('experience_level') == 'entry' ? 'selected' : ''); ?>>Entry</option>
                                <option value="junior" <?php echo e(request('experience_level') == 'junior' ? 'selected' : ''); ?>>Junior</option>
                                <option value="mid" <?php echo e(request('experience_level') == 'mid' ? 'selected' : ''); ?>>Mid-Level</option>
                                <option value="senior" <?php echo e(request('experience_level') == 'senior' ? 'selected' : ''); ?>>Senior</option>
                                <option value="lead" <?php echo e(request('experience_level') == 'lead' ? 'selected' : ''); ?>>Lead</option>
                            </select>
                        </div>

                        <div class="flex items-end gap-2">
                            <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['type' => 'submit','class' => 'flex-1 justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit','class' => 'flex-1 justify-center']); ?>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                Filter
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
                            <?php if(request()->hasAny(['search', 'status', 'experience_level'])): ?>
                                <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['variant' => 'outline','href' => ''.e(route('admin.talent-pool.index')).'','class' => 'justify-center']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('admin.talent-pool.index')).'','class' => 'justify-center']); ?>
                                    Clear
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
                    </div>
                </form>
            </div>

            <!-- Talent List -->
            <?php if($talents->count() > 0): ?>
                <div class="space-y-4">
                    <?php $__currentLoopData = $talents; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $talent): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition-all">
                            <div class="flex flex-col md:flex-row md:items-center gap-4">
                                <!-- Avatar & Name -->
                                <div class="flex items-center gap-4 flex-1">
                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white text-xl font-bold shadow-lg shadow-purple-500/20">
                                        <?php echo e(substr($talent->first_name, 0, 1)); ?><?php echo e(substr($talent->last_name, 0, 1)); ?>

                                    </div>
                                    <div class="flex-1">
                                        <h3 class="font-bold text-black dark:text-white text-lg"><?php echo e($talent->full_name); ?></h3>
                                        <div class="flex flex-wrap items-center gap-3 mt-1">
                                            <a href="mailto:<?php echo e($talent->email); ?>" class="text-sm text-gray-600 dark:text-gray-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                                                <?php echo e($talent->email); ?>

                                            </a>
                                            <?php if($talent->phone): ?>
                                                <span class="text-sm text-gray-400">•</span>
                                                <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($talent->phone); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Status & Experience -->
                                <div class="flex items-center gap-3">
                                    <?php if($talent->experience_level): ?>
                                        <span class="px-3 py-1 bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 rounded-md text-xs font-medium capitalize">
                                            <?php echo e($talent->experience_level); ?>

                                        </span>
                                    <?php endif; ?>

                                    <?php if($talent->status === 'potential'): ?>
                                        <span class="px-3 py-1 bg-gradient-to-r from-yellow-100 to-amber-100 dark:from-yellow-900/30 dark:to-amber-900/30 text-yellow-800 dark:text-yellow-200 rounded-md text-xs font-medium">
                                            Potential
                                        </span>
                                    <?php elseif($talent->status === 'contacted'): ?>
                                        <span class="px-3 py-1 bg-gradient-to-r from-blue-100 to-cyan-100 dark:from-blue-900/30 dark:to-cyan-900/30 text-blue-800 dark:text-blue-200 rounded-md text-xs font-medium">
                                            Contacted
                                        </span>
                                    <?php elseif($talent->status === 'interested'): ?>
                                        <span class="px-3 py-1 bg-gradient-to-r from-teal-100 to-cyan-100 dark:from-teal-900/30 dark:to-cyan-900/30 text-teal-800 dark:text-teal-200 rounded-md text-xs font-medium">
                                            Interested
                                        </span>
                                    <?php elseif($talent->status === 'hired'): ?>
                                        <span class="px-3 py-1 bg-gradient-to-r from-green-100 to-emerald-100 dark:from-green-900/30 dark:to-emerald-900/30 text-green-800 dark:text-green-200 rounded-md text-xs font-medium">
                                            Hired
                                        </span>
                                    <?php elseif($talent->status === 'not_interested'): ?>
                                        <span class="px-3 py-1 bg-gradient-to-r from-red-100 to-orange-100 dark:from-red-900/30 dark:to-orange-900/30 text-red-800 dark:text-red-200 rounded-md text-xs font-medium">
                                            Not Interested
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    <?php if($talent->application): ?>
                                        <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['variant' => 'outline','href' => ''.e(route('admin.applications.show', $talent->application)).'','class' => 'text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('admin.applications.show', $talent->application)).'','class' => 'text-xs']); ?>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            Application
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
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.talent-pool.show', $talent)).'','class' => 'text-xs']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.talent-pool.show', $talent)).'','class' => 'text-xs']); ?>
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
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

                            <!-- Skills -->
                            <?php if($talent->skills && count($talent->skills) > 0): ?>
                                <div class="mt-4 flex flex-wrap gap-2">
                                    <?php $__currentLoopData = $talent->skills; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $skill): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <span class="px-2.5 py-1 bg-gradient-to-r from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 text-indigo-800 dark:text-indigo-200 rounded-md text-xs font-medium">
                                            <?php echo e($skill); ?>

                                        </span>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    <?php echo e($talents->links()); ?>

                </div>
            <?php else: ?>
                <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-12">
                    <div class="text-center">
                        <div class="w-20 h-20 mx-auto rounded-xl bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center mb-4">
                            <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Talents Found</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <?php if(request()->hasAny(['search', 'status', 'experience_level'])): ?>
                                Try adjusting your filters to find more candidates.
                            <?php else: ?>
                                Start adding promising candidates to your talent pool from job applications.
                            <?php endif; ?>
                        </p>
                    </div>
                </div>
            <?php endif; ?>
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
<?php /**PATH F:\Project\salary\resources\views/admin/talent-pool/index.blade.php ENDPATH**/ ?>