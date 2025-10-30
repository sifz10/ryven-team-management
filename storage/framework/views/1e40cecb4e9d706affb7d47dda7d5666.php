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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                <?php echo e(__('Employees')); ?>

            </h2>
            <a href="<?php echo e(route('employees.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                <svg class="w-5 h-5 -ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" clip-rule="evenodd" />
                </svg>
                Add Employee
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="<?php echo e(route('dashboard')); ?>" class="text-gray-600 dark:text-gray-300">← Back to Dashboard</a>
                    </div>
                    <?php if(session('status')): ?>
                        <div class="mb-4 text-green-600"><?php echo e(session('status')); ?></div>
                    <?php endif; ?>

                    <form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search name, email, phone, position..." class="block w-full border-gray-300 rounded-full px-4 py-2" />
                        </div>
                        <div class="flex items-center gap-2">
                            <select name="status" class="border-gray-300 rounded-full px-3 py-2">
                                <option value="">All</option>
                                <option value="active" <?php if(request('status')==='active'): echo 'selected'; endif; ?>>Active</option>
                                <option value="discontinued" <?php if(request('status')==='discontinued'): echo 'selected'; endif; ?>>Discontinued</option>
                            </select>
                            <?php if (isset($component)) { $__componentOriginald411d1792bd6cc877d687758b753742c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginald411d1792bd6cc877d687758b753742c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.primary-button','data' => ['class' => 'bg-black hover:bg-gray-800 rounded-full']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('primary-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['class' => 'bg-black hover:bg-gray-800 rounded-full']); ?>Filter <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $attributes = $__attributesOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__attributesOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginald411d1792bd6cc877d687758b753742c)): ?>
<?php $component = $__componentOriginald411d1792bd6cc877d687758b753742c; ?>
<?php unset($__componentOriginald411d1792bd6cc877d687758b753742c); ?>
<?php endif; ?>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php $__empty_1 = true; $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-5 shadow-sm hover:shadow transition">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="text-lg font-semibold"><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300"><?php echo e($employee->position ?? '—'); ?></div>
                                    </div>
                                    <?php if($employee->discontinued_at): ?>
                                        <span class="text-xs text-red-600">Discontinued</span>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-3 text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                    <div><?php echo e($employee->email); ?></div>
                                    <?php if($employee->phone): ?>
                                        <div><?php echo e($employee->phone); ?></div>
                                    <?php endif; ?>
                                    <div>Hired: <?php echo e($employee->hired_at ?? '—'); ?></div>
                                    <?php if($employee->salary): ?>
                                        <div>Salary: <?php echo e(number_format($employee->salary,2)); ?> <?php echo e($employee->currency ?? 'USD'); ?></div>
                                    <?php endif; ?>
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <a href="<?php echo e(route('employees.show', $employee)); ?>" class="inline-flex items-center px-4 py-1.5 bg-black text-white rounded-full shadow hover:bg-gray-800">View</a>
                                    <a href="<?php echo e(route('employees.edit', $employee)); ?>" class="inline-flex items-center px-4 py-1.5 bg-black text-white rounded-full shadow hover:bg-gray-800">Edit</a>
                                    <form method="POST" action="<?php echo e(route('employees.destroy', $employee)); ?>" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="inline-flex items-center px-4 py-1.5 bg-black text-white rounded-full shadow hover:bg-gray-800" onclick="return confirm('Delete this employee?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <div class="col-span-full text-center text-gray-500 dark:text-gray-400">
                                <div class="mb-3">No employees yet.</div>
                                <a href="<?php echo e(route('employees.create')); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800">
                                    <svg class="w-5 h-5 -ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" clip-rule="evenodd" />
                                    </svg>
                                    Add your first employee
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="mt-6">
                        <?php echo e($employees->links()); ?>

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


<?php /**PATH F:\Project\salary\resources\views/employees/index.blade.php ENDPATH**/ ?>