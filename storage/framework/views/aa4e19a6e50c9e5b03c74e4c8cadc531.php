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
            <a href="<?php echo e(route('uat.show', $project)); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Edit UAT Project')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <form action="<?php echo e(route('uat.update', $project)); ?>" method="POST" class="p-6" 
                    x-data="{ 
                        users: <?php echo e(json_encode($project->users->map(fn($u) => ['name' => $u->name, 'email' => $u->email, 'role' => $u->role]))); ?>,
                        descriptionEditor: null,
                        initEditor() {
                            setTimeout(() => {
                                if (this.$refs.descriptionEditor && !this.descriptionEditor) {
                                    this.descriptionEditor = new Quill(this.$refs.descriptionEditor, {
                                        theme: 'snow',
                                        placeholder: 'Enter project description...',
                                        modules: {
                                            toolbar: [
                                                ['bold', 'italic', 'underline', 'strike'],
                                                ['blockquote', 'code-block'],
                                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                [{ 'header': [1, 2, 3, false] }],
                                                ['link'],
                                                ['clean']
                                            ]
                                        }
                                    });
                                    const content = `<?php echo addslashes($project->description ?? '<p><br></p>'); ?>`;
                                    this.descriptionEditor.clipboard.dangerouslyPasteHTML(content);
                                }
                            }, 100);
                        }
                    }"
                    x-init="initEditor()"
                    @submit="if (descriptionEditor) { $event.target.querySelector('input[name=description]').value = descriptionEditor.root.innerHTML; }">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="space-y-6">
                        <!-- Project Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Project Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-900 focus:ring-gray-900" 
                                value="<?php echo e(old('name', $project->name)); ?>">
                            <?php $__errorArgs = ['name'];
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

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Description
                            </label>
                            <div class="mt-1 rounded-md overflow-hidden border border-gray-300 dark:border-gray-600">
                                <div x-ref="descriptionEditor" class="bg-white dark:bg-gray-700" style="min-height: 200px;"></div>
                            </div>
                            <input type="hidden" name="description">
                            <?php $__errorArgs = ['description'];
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

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-900 focus:ring-gray-900">
                                <option value="active" <?php echo e(old('status', $project->status) === 'active' ? 'selected' : ''); ?>>Active</option>
                                <option value="completed" <?php echo e(old('status', $project->status) === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                <option value="cancelled" <?php echo e(old('status', $project->status) === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                            </select>
                            <?php $__errorArgs = ['status'];
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

                        <!-- Deadline -->
                        <div>
                            <label for="deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Deadline
                            </label>
                            <input type="datetime-local" name="deadline" id="deadline"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-900 focus:ring-gray-900" 
                                value="<?php echo e(old('deadline', $project->deadline?->format('Y-m-d\TH:i'))); ?>">
                            <?php $__errorArgs = ['deadline'];
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

                        <!-- Users -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Users <span class="text-red-500">*</span>
                                </label>
                                <button type="button" @click="users.push({ name: '', email: '', role: 'external' })"
                                    class="inline-flex items-center gap-2 px-3 py-1.5 bg-black dark:bg-white text-white dark:text-black text-sm rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add User
                                </button>
                            </div>

                            <div class="space-y-3">
                                <template x-for="(user, index) in users" :key="index">
                                    <div class="flex gap-3 items-start p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                                            <div>
                                                <input type="text" :name="'users[' + index + '][name]'" :value="user.name" placeholder="Name" required
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-gray-900 focus:ring-gray-900 text-sm">
                                            </div>
                                            <div>
                                                <input type="email" :name="'users[' + index + '][email]'" :value="user.email" placeholder="Email" required
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-gray-900 focus:ring-gray-900 text-sm">
                                            </div>
                                            <div>
                                                <select :name="'users[' + index + '][role]'" required
                                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white shadow-sm focus:border-gray-900 focus:ring-gray-900 text-sm">
                                                    <option value="internal" :selected="user.role === 'internal'">Internal (Employee)</option>
                                                    <option value="external" :selected="user.role === 'external'">External (Client)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <button type="button" @click="users.splice(index, 1)" x-show="users.length > 1"
                                            class="p-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                            <?php $__errorArgs = ['users'];
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

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="<?php echo e(route('uat.show', $project)); ?>"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full shadow-lg hover:bg-gray-800 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Update Project
                            </button>
                        </div>
                    </div>
                </form>

                <!-- Delete Project -->
                <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Delete Project</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                Permanently delete this UAT project and all associated data.
                            </p>
                        </div>
                        <form action="<?php echo e(route('uat.destroy', $project)); ?>" method="POST" 
                            onsubmit="return confirm('Are you sure you want to delete this project? This action cannot be undone.')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-500 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Project
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

<?php /**PATH F:\Project\salary\resources\views/uat/edit.blade.php ENDPATH**/ ?>