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
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('uat.index')); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        <?php echo e($project->name); ?>

                    </h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            <?php echo e($project->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ''); ?>

                            <?php echo e($project->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : ''); ?>

                            <?php echo e($project->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ''); ?>

                        ">
                            <?php echo e(ucfirst($project->status)); ?>

                        </span>
                        <?php if($project->deadline): ?>
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Due: <?php echo e($project->deadline->format('M d, Y')); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2" x-data="{ showDeleteConfirm: false }">
                <button onclick="copyUrl('<?php echo e($project->public_url); ?>')" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy UAT Link
                </button>
                <a href="<?php echo e(route('uat.edit', $project)); ?>" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Project
                </a>
                <button @click="showDeleteConfirm = true" type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>

                <!-- Confirmation Dialog -->
                <div x-show="showDeleteConfirm" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click.away="showDeleteConfirm = false"
                    class="fixed inset-0 z-50 overflow-y-auto" 
                    style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                        
                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete UAT Project</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Are you sure you want to delete <strong>"<?php echo e($project->name); ?>"</strong>? This will permanently delete all test cases, feedback, and user data. This action cannot be undone.
                                    </p>
                                    <div class="flex gap-3 justify-end">
                                        <button @click="showDeleteConfirm = false" type="button"
                                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all">
                                            Cancel
                                        </button>
                                        <form action="<?php echo e(route('uat.destroy', $project)); ?>" method="POST" class="inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                                                Delete Project
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Project Info -->
            <?php if($project->description): ?>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-700 dark:text-gray-300"><?php echo e($project->description); ?></p>
                </div>
            <?php endif; ?>

            <!-- Users Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Users (<?php echo e($project->users->count()); ?>)</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <?php $__currentLoopData = $project->users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full <?php echo e($user->role === 'internal' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-200 dark:bg-gray-600'); ?> flex items-center justify-center">
                                        <span class="text-sm font-medium <?php echo e($user->role === 'internal' ? 'text-blue-800 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300'); ?>">
                                            <?php echo e(strtoupper(substr($user->name, 0, 2))); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e($user->name); ?></div>
                                        
                                        <div class="text-xs">
                                            <span class="px-2 py-0.5 rounded <?php echo e($user->role === 'internal' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200'); ?>">
                                                <?php echo e(ucfirst($user->role)); ?>

                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Test Cases Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" x-data="{ showCreateForm: false, editingTestCase: null }">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Test Cases (<?php echo e($project->testCases->count()); ?>)</h3>
                        <button @click="showCreateForm = !showCreateForm" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full shadow-lg hover:bg-gray-800 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Test Case
                        </button>
                    </div>

                    <!-- Create Test Case Form -->
                    <div x-show="showCreateForm" x-collapse class="mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create New Test Case</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fill in the details below</p>
                                    </div>
                                    <button type="button" @click="showCreateForm = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <form action="<?php echo e(route('uat.test-cases.store', $project)); ?>" method="POST" 
                                x-data="{ 
                                    steps: [{ text: '' }],
                                    addStep() { this.steps.push({ text: '' }); },
                                    removeStep(index) { if (this.steps.length > 1) this.steps.splice(index, 1); }
                                }" 
                                class="p-6 space-y-6">
                                <?php echo csrf_field(); ?>

                                <!-- Title & Priority Row -->
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                            Title <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="title" required 
                                            placeholder="e.g., User Login with Valid Credentials"
                                            class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                            Priority <span class="text-red-500">*</span>
                                        </label>
                                        <select name="priority" required 
                                            class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all">
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" rows="2" 
                                        placeholder="Brief description of what this test case covers..."
                                        class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all"></textarea>
                                </div>

                                <!-- Steps to Test -->
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">
                                            Testing Steps
                                        </label>
                                        <button type="button" @click="addStep()" 
                                            class="inline-flex items-center gap-1 px-3 py-1 bg-black dark:bg-white text-white dark:text-black text-xs font-medium rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                            </svg>
                                            Add Step
                                        </button>
                                    </div>
                                    
                                    <div class="space-y-2">
                                        <template x-for="(step, index) in steps" :key="index">
                                            <div class="flex items-center gap-2">
                                                <span class="flex-shrink-0 w-6 h-6 rounded-full bg-black dark:bg-white text-white dark:text-black text-xs font-bold flex items-center justify-center" x-text="index + 1"></span>
                                                <input type="text" :name="'step_' + (index + 1)" 
                                                    x-model="step.text"
                                                    :placeholder="'Step ' + (index + 1)"
                                                    class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all">
                                                <button type="button" @click="removeStep(index)" 
                                                    x-show="steps.length > 1"
                                                    class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <input type="hidden" name="steps" x-bind:value="steps.map((s, i) => (i + 1) + '. ' + s.text).filter(s => s.length > 3).join('\n')">
                                </div>

                                <!-- Expected Result -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                        Expected Result
                                    </label>
                                    <textarea name="expected_result" rows="2" 
                                        placeholder="What should happen when the test is performed correctly..."
                                        class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all"></textarea>
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" @click="showCreateForm = false" 
                                        class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full font-medium hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Create Test Case
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Test Cases List -->
                    <?php if($project->testCases->count() > 0): ?>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $project->testCases; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $testCase): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all">
                                    <!-- Header -->
                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-black dark:bg-white flex items-center justify-center">
                                                <span class="text-sm font-bold text-white dark:text-black">#<?php echo e($index + 1); ?></span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 flex-wrap mb-1">
                                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white"><?php echo e($testCase->title); ?></h4>
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                                        <?php echo e($testCase->priority === 'critical' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ''); ?>

                                                        <?php echo e($testCase->priority === 'high' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : ''); ?>

                                                        <?php echo e($testCase->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : ''); ?>

                                                        <?php echo e($testCase->priority === 'low' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : ''); ?>

                                                    ">
                                                        <?php echo e(ucfirst($testCase->priority)); ?>

                                                    </span>
                                                    <?php
                                                        $clientFeedbackCount = $testCase->feedbacks->where('user.role', 'external')->count();
                                                    ?>
                                                    <?php if($clientFeedbackCount > 0): ?>
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-600 text-white">
                                                            💬 <?php echo e($clientFeedbackCount); ?> Client <?php echo e($clientFeedbackCount === 1 ? 'Reply' : 'Replies'); ?>

                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                                <?php if($testCase->description): ?>
                                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($testCase->description); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6 space-y-5">
                                        <?php if($testCase->steps): ?>
                                            <?php
                                                $stepsList = array_filter(explode("\n", $testCase->steps));
                                            ?>
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">Steps to Test</h5>
                                                <ol class="space-y-3">
                                                    <?php $__currentLoopData = $stepsList; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stepIndex => $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li class="flex gap-3">
                                                            <span class="flex-shrink-0 w-6 h-6 rounded-full bg-black dark:bg-white flex items-center justify-center text-xs font-bold text-white dark:text-black">
                                                                <?php echo e($stepIndex + 1); ?>

                                                            </span>
                                                            <span class="flex-1 text-gray-700 dark:text-gray-300 leading-relaxed pt-0.5">
                                                                <?php echo e(trim(preg_replace('/^\d+\.\s*/', '', $step))); ?>

                                                            </span>
                                                        </li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ol>
                                            </div>
                                        <?php endif; ?>

                                        <?php if($testCase->expected_result): ?>
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wide">Expected Result</h5>
                                                <p class="text-gray-700 dark:text-gray-300 leading-relaxed"><?php echo e($testCase->expected_result); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <!-- Feedback Summary -->
                                        <?php if($testCase->feedbacks->count() > 0): ?>
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">Feedback Summary</h5>
                                                
                                                <!-- Status Badges -->
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    <?php
                                                        $feedbackCounts = $testCase->feedbacks->groupBy('status')->map->count();
                                                    ?>
                                                    <?php if($feedbackCounts->get('passed', 0) > 0): ?>
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            ✅ <?php echo e($feedbackCounts->get('passed')); ?> Passed
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($feedbackCounts->get('failed', 0) > 0): ?>
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            ❌ <?php echo e($feedbackCounts->get('failed')); ?> Failed
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($feedbackCounts->get('blocked', 0) > 0): ?>
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                            🚫 <?php echo e($feedbackCounts->get('blocked')); ?> Blocked
                                                        </span>
                                                    <?php endif; ?>
                                                    <?php if($feedbackCounts->get('pending', 0) > 0): ?>
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                            ⏳ <?php echo e($feedbackCounts->get('pending')); ?> Pending
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                                <!-- Detailed Feedback -->
                                                <div class="space-y-3">
                                                    <?php $__empty_1 = true; $__currentLoopData = $testCase->feedbacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feedback): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-2 <?php echo e($feedback->user->role === 'external' ? 'border-blue-200 dark:border-blue-900 bg-blue-50/30 dark:bg-blue-900/10' : 'border-gray-200 dark:border-gray-700'); ?>">
                                                            <div class="flex items-start justify-between mb-2">
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-8 h-8 rounded-full <?php echo e($feedback->user->role === 'internal' ? 'bg-black dark:bg-white' : 'bg-blue-600 dark:bg-blue-500'); ?> flex items-center justify-center">
                                                                        <span class="text-xs font-bold text-white dark:text-black">
                                                                            <?php echo e(strtoupper(substr($feedback->user->name, 0, 2))); ?>

                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="font-bold text-gray-900 dark:text-white text-sm"><?php echo e($feedback->user->name); ?></span>
                                                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full <?php echo e($feedback->user->role === 'internal' ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-blue-600 text-white'); ?>">
                                                                                <?php echo e($feedback->user->role === 'internal' ? '👔 Employee' : '👤 Client'); ?>

                                                                            </span>
                                                                        </div>
                                                                        <span class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($feedback->created_at->diffForHumans()); ?></span>
                                                                    </div>
                                                                </div>
                                                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                                                    <?php echo e($feedback->status === 'passed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : ''); ?>

                                                                    <?php echo e($feedback->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : ''); ?>

                                                                    <?php echo e($feedback->status === 'blocked' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : ''); ?>

                                                                    <?php echo e($feedback->status === 'pending' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : ''); ?>

                                                                ">
                                                                    <?php echo e(ucfirst($feedback->status)); ?>

                                                                </span>
                                                            </div>
                                                            <?php if($feedback->comment): ?>
                                                                <div class="pl-10 mt-2">
                                                                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed"><?php echo e($feedback->comment); ?></p>
                                                                </div>
                                                            <?php else: ?>
                                                                <div class="pl-10 mt-2">
                                                                    <p class="text-xs italic text-gray-500 dark:text-gray-400">Status updated to: <?php echo e(ucfirst($feedback->status)); ?></p>
                                                                </div>
                                                            <?php endif; ?>
                                                            <?php if($feedback->attachment_path): ?>
                                                                <a href="<?php echo e(Storage::url($feedback->attachment_path)); ?>" target="_blank" 
                                                                    class="inline-flex items-center gap-2 mt-3 ml-10 px-3 py-1.5 text-xs font-medium bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                                    </svg>
                                                                    View Attachment
                                                                </a>
                                                            <?php endif; ?>
                                                        </div>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                                            No feedback yet
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Footer: Edit & Delete Buttons -->
                                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                        <button @click="editingTestCase = editingTestCase === <?php echo e($testCase->id); ?> ? null : <?php echo e($testCase->id); ?>" type="button"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </button>
                                        
                                        <form action="<?php echo e(route('uat.test-cases.destroy', [$project, $testCase])); ?>" method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this test case?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" 
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-full transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>

                                    <!-- Edit Form Modal Overlay -->
                                    <div x-show="editingTestCase === <?php echo e($testCase->id); ?>" 
                                        x-transition:enter="transition ease-out duration-300"
                                        x-transition:enter-start="opacity-0"
                                        x-transition:enter-end="opacity-100"
                                        x-transition:leave="transition ease-in duration-200"
                                        x-transition:leave-start="opacity-100"
                                        x-transition:leave-end="opacity-0"
                                        class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4" 
                                        @click.self="editingTestCase = null"
                                        style="display: none;">
                                        <div class="bg-white dark:bg-gray-800 rounded-xl max-w-3xl w-full max-h-[90vh] overflow-y-auto shadow-2xl" @click.stop>
                                            <form action="<?php echo e(route('uat.test-cases.update', [$project, $testCase])); ?>" method="POST"
                                                x-data="{ 
                                                    steps: <?php echo e(json_encode(array_map(function($step) {
                                                        return ['text' => trim(preg_replace('/^\d+\.\s*/', '', $step))];
                                                    }, array_filter(explode("\n", $testCase->steps ?? ''))))); ?>,
                                                    addStep() { this.steps.push({ text: '' }); },
                                                    removeStep(index) { if (this.steps.length > 1) this.steps.splice(index, 1); }
                                                }"
                                                class="p-6">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PUT'); ?>

                                                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Edit Test Case</h3>
                                                    <button type="button" @click="editingTestCase = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>

                                                <div class="space-y-4">
                                                    <!-- Title & Priority -->
                                                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                                        <div class="lg:col-span-2">
                                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Title <span class="text-red-500">*</span></label>
                                                            <input type="text" name="title" value="<?php echo e($testCase->title); ?>" required 
                                                                class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Priority <span class="text-red-500">*</span></label>
                                                            <select name="priority" required 
                                                                class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                                                                <option value="low" <?php echo e($testCase->priority === 'low' ? 'selected' : ''); ?>>Low</option>
                                                                <option value="medium" <?php echo e($testCase->priority === 'medium' ? 'selected' : ''); ?>>Medium</option>
                                                                <option value="high" <?php echo e($testCase->priority === 'high' ? 'selected' : ''); ?>>High</option>
                                                                <option value="critical" <?php echo e($testCase->priority === 'critical' ? 'selected' : ''); ?>>Critical</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <!-- Description -->
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                                        <textarea name="description" rows="2" 
                                                            class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><?php echo e($testCase->description); ?></textarea>
                                                    </div>

                                                    <!-- Steps -->
                                                    <div>
                                                        <div class="flex items-center justify-between mb-2">
                                                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300">Testing Steps</label>
                                                            <button type="button" @click="addStep()" 
                                                                class="inline-flex items-center gap-1 px-3 py-1 bg-black dark:bg-white text-white dark:text-black text-xs font-medium rounded-full">
                                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                                </svg>
                                                                Add Step
                                                            </button>
                                                        </div>
                                                        
                                                        <div class="space-y-2">
                                                            <template x-for="(step, index) in steps" :key="index">
                                                                <div class="flex items-center gap-2">
                                                                    <span class="flex-shrink-0 w-6 h-6 rounded-full bg-black dark:bg-white text-white dark:text-black text-xs font-bold flex items-center justify-center" x-text="index + 1"></span>
                                                                    <input type="text" :name="'step_' + (index + 1)" 
                                                                        x-model="step.text"
                                                                        :placeholder="'Step ' + (index + 1)"
                                                                        class="flex-1 px-3 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm">
                                                                    <button type="button" @click="removeStep(index)" 
                                                                        x-show="steps.length > 1"
                                                                        class="p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg">
                                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                        </svg>
                                                                    </button>
                                                                </div>
                                                            </template>
                                                        </div>
                                                        
                                                        <input type="hidden" name="steps" x-bind:value="steps.map((s, i) => (i + 1) + '. ' + s.text).filter(s => s.length > 3).join('\n')">
                                                    </div>

                                                    <!-- Expected Result -->
                                                    <div>
                                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Expected Result</label>
                                                        <textarea name="expected_result" rows="2" 
                                                            class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"><?php echo e($testCase->expected_result); ?></textarea>
                                                    </div>

                                                    <!-- Action Buttons -->
                                                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                        <button type="button" @click="editingTestCase = null" 
                                                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                                            Cancel
                                                        </button>
                                                        <button type="submit" 
                                                            class="inline-flex items-center gap-2 px-5 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full font-medium hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Update Test Case
                                                        </button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php else: ?>
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No test cases</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a test case.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        function copyUrl(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('UAT link copied to clipboard!');
            });
        }
    </script>
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

<?php /**PATH F:\Project\salary\resources\views/uat/show.blade.php ENDPATH**/ ?>