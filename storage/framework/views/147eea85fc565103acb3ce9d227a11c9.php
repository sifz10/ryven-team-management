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
        <div class="flex items-center gap-4">
            <a href="<?php echo e(route('projects.show', $project)); ?>" class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-full transition-all shadow-md" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </a>
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    📊 <?php echo e($project->name); ?> - Today's Work
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo e(\Carbon\Carbon::parse($date)->format('l, F d, Y')); ?></p>
            </div>
            <div class="flex items-center gap-3">
                <input type="date" id="date-picker" value="<?php echo e($date); ?>" max="<?php echo e(now()->toDateString()); ?>"
                    class="px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-full border-2 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-500 transition-all">
                <button onclick="sendReport()" class="inline-flex items-center gap-2 px-6 py-2.5 text-white rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all font-semibold" style="background-color: #000000; border: 2px solid #000000;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send to Client
                </button>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <?php if(session('status')): ?>
                <div class="bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium"><?php echo e(session('status')); ?></span>
                </div>
            <?php endif; ?>

            <!-- Project Info Card -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border-2 border-gray-200 dark:border-gray-700 shadow-xl">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2"><?php echo e($project->name); ?></h3>
                        <?php if($project->description): ?>
                            <p class="text-gray-600 dark:text-gray-400 mb-4"><?php echo e($project->description); ?></p>
                        <?php endif; ?>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold text-white" style="background-color: #000000; border: 1px solid #333333;">
                                <?php echo e(ucfirst($project->status)); ?>

                            </span>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold text-white" style="background-color: #000000; border: 1px solid #333333;">
                                Priority: <?php echo e($project->priority_label); ?>

                            </span>
                        </div>
                    </div>
                    <?php if($project->client_name): ?>
                        <div class="bg-white/50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-300 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Client</p>
                            <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($project->client_name); ?></p>
                            <?php if($project->client_company): ?>
                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($project->client_company); ?></p>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Work Log Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border-2 border-gray-200 dark:border-gray-700 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Work Submissions (<?php echo e($workSubmissions->count()); ?>)
                    </h3>
                </div>

                <?php $__empty_1 = true; $__currentLoopData = $workSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="mb-4 last:mb-0" x-data="{ editing: false, description: '<?php echo e(addslashes($submission->work_description)); ?>' }">
                        <div class="bg-gray-50 dark:bg-gray-700/50 border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-5 hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: #000000; border: 2px solid #333333;">
                                        <?php echo e(substr($submission->employee->first_name, 0, 1)); ?><?php echo e(substr($submission->employee->last_name, 0, 1)); ?>

                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white"><?php echo e($submission->employee->first_name); ?> <?php echo e($submission->employee->last_name); ?></p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($submission->created_at->format('g:i A')); ?></p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="editing = !editing" class="inline-flex items-center gap-1.5 px-4 py-2 text-white rounded-full text-sm font-medium transition-all shadow-lg" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                                    </button>
                                    <form action="<?php echo e(route('projects.work.delete', [$project, $submission])); ?>" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this work entry?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-white rounded-full text-sm font-medium transition-all shadow-lg" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- View Mode -->
                            <div x-show="!editing" class="text-gray-700 dark:text-gray-300 leading-relaxed pl-13">
                                <?php echo e($submission->work_description); ?>

                            </div>
                            
                            <!-- Edit Mode -->
                            <div x-show="editing" x-cloak class="pl-13">
                                <form action="<?php echo e(route('projects.work.update', [$project, $submission])); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <textarea x-model="description" name="work_description" rows="4" required
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-500 transition-all resize-none"></textarea>
                                    <div class="flex items-center gap-2 mt-3">
                                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 text-white rounded-full text-sm font-semibold transition-all shadow-lg" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Save Changes
                                        </button>
                                        <button type="button" @click="editing = false; description = '<?php echo e(addslashes($submission->work_description)); ?>'" class="px-5 py-2 text-white rounded-full text-sm font-semibold transition-all" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 font-medium text-lg">No work logged for this date</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Your team hasn't submitted any updates yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Date picker navigation
        document.getElementById('date-picker').addEventListener('change', function() {
            const selectedDate = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('date', selectedDate);
            window.location.href = url.toString();
        });

        // Send report function
        function sendReport() {
            if (confirm('Send this report to the client via Email and SMS?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '<?php echo e(route('projects.send-report', $project)); ?>';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '<?php echo e(csrf_token()); ?>';
                form.appendChild(csrfToken);
                
                const dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'date';
                dateInput.value = '<?php echo e($date); ?>';
                form.appendChild(dateInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    <?php $__env->stopPush(); ?>
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
<?php /**PATH F:\Project\salary\resources\views/projects/today-work.blade.php ENDPATH**/ ?>