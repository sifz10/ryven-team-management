<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Checklist - <?php echo e($checklist->template->title); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Daily Checklist</h1>
                <p class="text-gray-600"><?php echo e(now()->format('l, F j, Y')); ?></p>
                <p class="text-sm text-gray-500 mt-2">For: <?php echo e($checklist->employee->first_name); ?> <?php echo e($checklist->employee->last_name); ?></p>
            </div>

            <?php if(session('status')): ?>
                <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1"><?php echo e(session('status')); ?></span>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1"><?php echo e(session('error')); ?></span>
                </div>
            <?php endif; ?>

            <?php if($isExpired): ?>
                <div class="mb-6 px-4 py-3 bg-orange-50 border border-orange-200 rounded-xl text-orange-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1">⚠️ This checklist link has expired (valid for 12 hours). You can view it but cannot make changes.</span>
                </div>
            <?php endif; ?>

            <!-- Checklist Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <!-- Checklist Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 p-6">
                    <h2 class="text-2xl font-bold text-white mb-2"><?php echo e($checklist->template->title); ?></h2>
                    <?php if($checklist->template->description): ?>
                        <p class="text-gray-200 text-sm"><?php echo e($checklist->template->description); ?></p>
                    <?php endif; ?>
                </div>

                <!-- Progress Bar -->
                <?php
                    $completedCount = $checklist->items->where('is_completed', true)->count();
                    $totalCount = $checklist->items->count();
                    $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                ?>
                
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-2xl font-bold text-gray-900"><?php echo e($percentage); ?>%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-300" style="width: <?php echo e($percentage); ?>%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2"><?php echo e($completedCount); ?> of <?php echo e($totalCount); ?> items completed</p>
                </div>

                <!-- Checklist Items -->
                <div class="p-6">
                    <div class="space-y-3">
                        <?php $__currentLoopData = $checklist->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-start gap-4 p-4 rounded-xl border-2 transition-all hover:shadow-md <?php echo e($item->is_completed ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200'); ?>">
                                <div class="flex-shrink-0 mt-0.5">
                                    <?php if($item->is_completed): ?>
                                        <div class="w-6 h-6 rounded-md bg-green-500 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    <?php else: ?>
                                        <div class="w-6 h-6 rounded-md border-2 border-gray-300 bg-white"></div>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-base <?php echo e($item->is_completed ? 'line-through text-gray-600' : 'text-gray-900 font-medium'); ?>">
                                        <?php echo e($item->title); ?>

                                    </p>
                                    <?php if($item->completed_at): ?>
                                        <p class="text-sm text-green-600 mt-1">
                                            ✓ Completed at <?php echo e($item->completed_at->format('g:i A')); ?>

                                        </p>
                                    <?php endif; ?>
                                </div>

                                <div class="flex-shrink-0">
                                    <?php if(!$isExpired): ?>
                                        <form method="GET" action="<?php echo e(route('checklist.public.toggle', ['token' => $checklist->email_token, 'item' => $item->id])); ?>">
                                            <button type="submit" class="px-4 py-2 rounded-lg font-medium text-sm transition-all <?php echo e($item->is_completed ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-black text-white hover:bg-gray-800'); ?>">
                                                <?php echo e($item->is_completed ? 'Undo' : 'Complete'); ?>

                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg font-medium text-sm cursor-not-allowed">
                                            Expired
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-600">
                        This is your daily checklist. Check off items as you complete them!
                    </p>
                </div>
            </div>

            <!-- Daily Work Submissions Section -->
            <div class="mt-8 bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200" x-data="{ showForm: false }">
                <!-- Section Header -->
                <div class="bg-gradient-to-r from-blue-800 to-blue-700 p-6">
                    <h2 class="text-2xl font-bold text-white mb-2">💼 Daily Work Log</h2>
                    <p class="text-blue-100 text-sm">Track your work progress by project</p>
                </div>

                <!-- Work Submissions List -->
                <div class="p-6">
                    <?php if($checklist->workSubmissions->count() > 0): ?>
                        <div class="space-y-4 mb-6">
                            <?php $__currentLoopData = $checklist->workSubmissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $submission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border-2 border-gray-200 rounded-xl p-5 hover:shadow-md transition-all bg-gradient-to-r from-gray-50 to-white">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800">
                                                    📁 <?php echo e($submission->project ? $submission->project->name : $submission->project_name); ?>

                                                </span>
                                                <span class="text-xs text-gray-500">
                                                    <?php echo e($submission->created_at->format('g:i A')); ?>

                                                </span>
                                            </div>
                                            <p class="text-gray-700 leading-relaxed"><?php echo e($submission->work_description); ?></p>
                                        </div>
                                        <?php if(!$isExpired): ?>
                                            <form method="POST" action="<?php echo e(route('checklist.public.work.delete', ['token' => $checklist->email_token, 'submission' => $submission->id])); ?>" onsubmit="return confirm('Are you sure you want to delete this work entry?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="flex-shrink-0 p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all" title="Delete">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                    <?php else: ?>
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="font-medium">No work logged yet today</p>
                            <p class="text-sm mt-1">Add your first work entry below</p>
                        </div>
                    <?php endif; ?>

                    <!-- Add Work Button/Form -->
                    <?php if(!$isExpired): ?>
                        <div>
                            <button @click="showForm = !showForm" type="button" class="w-full py-3 px-4 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl transition-all flex items-center justify-center gap-2 shadow-sm">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!showForm">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span x-text="showForm ? 'Cancel' : 'Add Work Entry'"></span>
                            </button>

                            <!-- Work Submission Form -->
                            <div x-show="showForm" x-cloak x-transition class="mt-4 p-6 bg-gray-50 rounded-xl border-2 border-blue-200">
                                <form method="POST" action="<?php echo e(route('checklist.public.work.store', ['token' => $checklist->email_token])); ?>">
                                    <?php echo csrf_field(); ?>
                                    <div class="space-y-4">
                                        <div>
                                            <label for="project_id" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Select Project <span class="text-red-500">*</span>
                                            </label>
                                            <select 
                                                id="project_id" 
                                                name="project_id" 
                                                required 
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                                            >
                                                <option value="">-- Select a project --</option>
                                                <?php $__currentLoopData = $availableProjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $proj): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($proj->id); ?>" <?php echo e(old('project_id') == $proj->id ? 'selected' : ''); ?>>
                                                        <?php echo e($proj->name); ?>

                                                        <?php if($proj->client_name): ?> (<?php echo e($proj->client_name); ?>)<?php endif; ?>
                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                            <?php $__errorArgs = ['project_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>

                                        <div>
                                            <label for="work_description" class="block text-sm font-semibold text-gray-700 mb-2">
                                                Work Description <span class="text-red-500">*</span>
                                            </label>
                                            <textarea 
                                                id="work_description" 
                                                name="work_description" 
                                                required 
                                                rows="4"
                                                maxlength="1000"
                                                placeholder="Describe what you worked on today..."
                                                class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none"
                                            ><?php echo e(old('work_description')); ?></textarea>
                                            <?php $__errorArgs = ['work_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="mt-1 text-sm text-red-600"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                            <p class="mt-1 text-xs text-gray-500">Max 1000 characters</p>
                                        </div>

                                        <div class="flex gap-3">
                                            <button type="submit" class="flex-1 py-3 px-6 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-lg transition-all shadow-sm">
                                                Submit Work
                                            </button>
                                            <button type="button" @click="showForm = false" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-semibold rounded-lg transition-all">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <span class="inline-block px-6 py-3 bg-gray-200 text-gray-500 rounded-lg font-medium">
                                Link expired - Cannot add new work entries
                            </span>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Back to Email Notice -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    You can also check off items directly from your email.
                </p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</body>
</html>

<?php /**PATH F:\Project\salary\resources\views/checklist/public.blade.php ENDPATH**/ ?>