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
            <a href="<?php echo e(route('projects.index')); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    <?php echo e(__('Create New Project')); ?>

                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add a new project with client details</p>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl">
                <form action="<?php echo e(route('projects.store')); ?>" method="POST" class="p-8 space-y-8">
                    <?php echo csrf_field(); ?>

                    <!-- Project Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Project Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Project Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="<?php echo e(old('name')); ?>" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100"><?php echo e(old('description')); ?></textarea>
                                <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="active" <?php echo e(old('status') === 'active' ? 'selected' : ''); ?>>Active</option>
                                    <option value="on-hold" <?php echo e(old('status') === 'on-hold' ? 'selected' : ''); ?>>On Hold</option>
                                    <option value="completed" <?php echo e(old('status') === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                    <option value="cancelled" <?php echo e(old('status') === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                </select>
                                <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select id="priority" name="priority" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="1" <?php echo e(old('priority') == 1 ? 'selected' : ''); ?>>Low</option>
                                    <option value="2" <?php echo e(old('priority', 2) == 2 ? 'selected' : ''); ?>>Medium</option>
                                    <option value="3" <?php echo e(old('priority') == 3 ? 'selected' : ''); ?>>High</option>
                                    <option value="4" <?php echo e(old('priority') == 4 ? 'selected' : ''); ?>>Critical</option>
                                </select>
                                <?php $__errorArgs = ['priority'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="<?php echo e(old('start_date')); ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="<?php echo e(old('end_date')); ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="budget" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Budget</label>
                                <input type="number" id="budget" name="budget" value="<?php echo e(old('budget')); ?>" step="0.01" min="0"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['budget'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="currency" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Currency <span class="text-red-500">*</span>
                                </label>
                                <select id="currency" name="currency" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="USD" <?php echo e(old('currency', 'USD') === 'USD' ? 'selected' : ''); ?>>USD</option>
                                    <option value="BDT" <?php echo e(old('currency') === 'BDT' ? 'selected' : ''); ?>>BDT</option>
                                    <option value="EUR" <?php echo e(old('currency') === 'EUR' ? 'selected' : ''); ?>>EUR</option>
                                    <option value="GBP" <?php echo e(old('currency') === 'GBP' ? 'selected' : ''); ?>>GBP</option>
                                </select>
                                <?php $__errorArgs = ['currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Client Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Client Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Client Name</label>
                                <input type="text" id="client_name" name="client_name" value="<?php echo e(old('client_name')); ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['client_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="client_company" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Company</label>
                                <input type="text" id="client_company" name="client_company" value="<?php echo e(old('client_company')); ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['client_company'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="client_email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" id="client_email" name="client_email" value="<?php echo e(old('client_email')); ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['client_email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div>
                                <label for="client_phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                <input type="text" id="client_phone" name="client_phone" value="<?php echo e(old('client_phone')); ?>"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                <?php $__errorArgs = ['client_phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>

                            <div class="md:col-span-2">
                                <label for="client_address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                <textarea id="client_address" name="client_address" rows="3"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100"><?php echo e(old('client_address')); ?></textarea>
                                <?php $__errorArgs = ['client_address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="px-6 py-3 bg-black text-white rounded-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all font-semibold">
                            Create Project
                        </button>
                        <a href="<?php echo e(route('projects.index')); ?>" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-all font-semibold">
                            Cancel
                        </a>
                    </div>
                </form>
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
<?php /**PATH F:\Project\salary\resources\views/projects/create.blade.php ENDPATH**/ ?>