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
                    <h1 class="text-2xl font-bold text-black dark:text-white">Job Postings</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage job posts and track applications</p>
                </div>
                <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.jobs.create')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.jobs.create')).'']); ?>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Job Post
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

        <!-- Content -->
        <div class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Jobs</p>
                            <p class="text-2xl font-bold text-black dark:text-white mt-1"><?php echo e($jobs->total()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-black dark:bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Published</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1"><?php echo e($jobs->where('status', 'published')->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Draft</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1"><?php echo e($jobs->where('status', 'draft')->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Closed</p>
                            <p class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-1"><?php echo e($jobs->where('status', 'closed')->count()); ?></p>
                        </div>
                        <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" class="flex gap-4 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text"
                               name="search"
                               value="<?php echo e(request('search')); ?>"
                               placeholder="Search jobs..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-white dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="w-48">
                        <select name="status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-white dark:bg-gray-700 dark:text-white">
                            <option value="">All Status</option>
                            <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Draft</option>
                            <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>>Published</option>
                            <option value="closed" <?php echo e(request('status') === 'closed' ? 'selected' : ''); ?>>Closed</option>
                        </select>
                    </div>
                    <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['type' => 'submit']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['type' => 'submit']); ?>
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
                    <?php if(request()->hasAny(['search', 'status'])): ?>
                        <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['variant' => 'outline','href' => ''.e(route('admin.jobs.index')).'']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'outline','href' => ''.e(route('admin.jobs.index')).'']); ?>
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
                </form>
            </div>

            <!-- Jobs List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <?php if($jobs->count() > 0): ?>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50 dark:bg-gray-900/50 border-b border-gray-200 dark:border-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Job Title</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Location</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Applications</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Created</th>
                                    <th class="px-6 py-3 text-right text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                <?php $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4">
                                            <div>
                                                <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="text-sm font-semibold text-black dark:text-white hover:underline"><?php echo e($job->title); ?></a>
                                                <?php if($job->department): ?>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1"><?php echo e($job->department); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                <?php echo e(ucfirst(str_replace('-', ' ', $job->job_type))); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                            <?php echo e($job->location ?? 'Not specified'); ?>

                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center gap-1.5 text-sm font-semibold text-black dark:text-white">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                                </svg>
                                                <?php echo e($job->applications_count); ?>

                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <?php if($job->status === 'published'): ?>
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                    Published
                                                </span>
                                            <?php elseif($job->status === 'draft'): ?>
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                                    Draft
                                                </span>
                                            <?php else: ?>
                                                <span class="inline-flex px-2.5 py-1 text-xs font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                                    Closed
                                                </span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                            <?php echo e($job->created_at->format('M d, Y')); ?>

                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2">
                                                <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="p-2 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="View">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                <a href="<?php echo e(route('admin.jobs.edit', $job)); ?>" class="p-2 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <?php echo e($jobs->links()); ?>

                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No job posts found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Create your first job post to start recruiting</p>
                        <?php if (isset($component)) { $__componentOriginaldf9bc64087ea57ded106e8b72ce8d783 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginaldf9bc64087ea57ded106e8b72ce8d783 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.black-button','data' => ['href' => ''.e(route('admin.jobs.create')).'','class' => 'mt-4']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('black-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['href' => ''.e(route('admin.jobs.create')).'','class' => 'mt-4']); ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Job Post
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
<?php /**PATH F:\Project\salary\resources\views/admin/jobs/index.blade.php ENDPATH**/ ?>