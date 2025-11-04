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
            <a href="<?php echo e(route('notes.index')); ?>" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                <?php echo e(__('Create Personal Note')); ?>

            </h2>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <form action="<?php echo e(route('notes.store')); ?>" method="POST" enctype="multipart/form-data" 
                    class="p-6" 
                    x-data="{
                        type: 'text',
                        reminderEmails: [],
                        emailInput: '',
                        emailSuggestions: [],
                        showSuggestions: false,
                        async searchEmails() {
                            if (this.emailInput.length < 2) {
                                this.emailSuggestions = [];
                                this.showSuggestions = false;
                                return;
                            }
                            
                            try {
                                const response = await fetch('<?php echo e(route('notes.emails.search')); ?>?q=' + encodeURIComponent(this.emailInput));
                                const data = await response.json();
                                this.emailSuggestions = data;
                                this.showSuggestions = data.length > 0;
                            } catch (error) {
                                console.error('Error fetching emails:', error);
                            }
                        },
                        addEmail(email) {
                            if (email && !this.reminderEmails.includes(email)) {
                                this.reminderEmails.push(email);
                                this.emailInput = '';
                                this.emailSuggestions = [];
                                this.showSuggestions = false;
                            }
                        },
                        removeEmail(index) {
                            this.reminderEmails.splice(index, 1);
                        }
                    }">
                    <?php echo csrf_field(); ?>

                    <div class="space-y-6">
                        <!-- Type Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-3">
                                Note Type <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="text" x-model="type" class="sr-only peer" required>
                                    <div class="p-4 text-center rounded-xl border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-gray-400 dark:hover:border-gray-500 transition">
                                        <div class="text-3xl mb-2">📝</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Text</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="website_link" x-model="type" class="sr-only peer">
                                    <div class="p-4 text-center rounded-xl border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-gray-400 dark:hover:border-gray-500 transition">
                                        <div class="text-3xl mb-2">🔗</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Link</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="password" x-model="type" class="sr-only peer">
                                    <div class="p-4 text-center rounded-xl border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-gray-400 dark:hover:border-gray-500 transition">
                                        <div class="text-3xl mb-2">🔐</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Password</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="backup_code" x-model="type" class="sr-only peer">
                                    <div class="p-4 text-center rounded-xl border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-gray-400 dark:hover:border-gray-500 transition">
                                        <div class="text-3xl mb-2">🔑</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">Backup Code</div>
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" name="type" value="file" x-model="type" class="sr-only peer">
                                    <div class="p-4 text-center rounded-xl border-2 border-gray-300 dark:border-gray-600 peer-checked:border-blue-600 dark:peer-checked:border-blue-500 peer-checked:bg-blue-50 dark:peer-checked:bg-blue-900/20 hover:border-gray-400 dark:hover:border-gray-500 transition">
                                        <div class="text-3xl mb-2">📎</div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-gray-100">File</div>
                                    </div>
                                </label>
                            </div>
                            <?php $__errorArgs = ['type'];
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

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" required
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900" 
                                value="<?php echo e(old('title')); ?>"
                                placeholder="Enter a descriptive title...">
                            <?php $__errorArgs = ['title'];
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

                        <!-- URL (only for website_link) -->
                        <div x-show="type === 'website_link'" x-transition>
                            <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Website URL
                            </label>
                            <input type="url" name="url" id="url"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900" 
                                value="<?php echo e(old('url')); ?>"
                                placeholder="https://example.com">
                            <?php $__errorArgs = ['url'];
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

                        <!-- File Upload (only for file type) -->
                        <div x-show="type === 'file'" x-transition>
                            <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Upload File (Max: 10MB)
                            </label>
                            <input type="file" name="file" id="file"
                                class="mt-1 block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 dark:file:bg-blue-900 dark:file:text-blue-300 dark:hover:file:bg-blue-800">
                            <?php $__errorArgs = ['file'];
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

                        <!-- Content -->
                        <div>
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Content/Details
                            </label>
                            <textarea name="content" id="content" rows="6"
                                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900"
                                placeholder="Add any additional details, notes, or instructions..."><?php echo e(old('content')); ?></textarea>
                            <?php $__errorArgs = ['content'];
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

                        <!-- Reminder Section -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                                <svg class="w-5 h-5 inline-block mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path>
                                </svg>
                                Set Reminder (Optional)
                            </h3>

                            <!-- Reminder Time -->
                            <div class="mb-4">
                                <label for="reminder_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Reminder Date & Time
                                </label>
                                <input type="datetime-local" name="reminder_time" id="reminder_time"
                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900" 
                                    value="<?php echo e(old('reminder_time')); ?>">
                                <?php $__errorArgs = ['reminder_time'];
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

                            <!-- Reminder Emails with Autocomplete -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Reminder Email Addresses
                                </label>
                                
                                <!-- Email Input with Autocomplete -->
                                <div class="relative">
                                    <div class="flex gap-2">
                                        <input 
                                            type="text" 
                                            x-model="emailInput"
                                            @input.debounce.300ms="searchEmails()"
                                            @keydown.enter.prevent="addEmail(emailInput)"
                                            placeholder="Type email address..."
                                            class="flex-1 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900">
                                        <button 
                                            type="button" 
                                            @click="addEmail(emailInput)"
                                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                                            Add
                                        </button>
                                    </div>

                                    <!-- Autocomplete Suggestions -->
                                    <div 
                                        x-show="showSuggestions" 
                                        @click.away="showSuggestions = false"
                                        class="absolute z-10 w-full mt-1 bg-white dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                        <template x-for="suggestion in emailSuggestions" :key="suggestion.email">
                                            <button 
                                                type="button"
                                                @click="addEmail(suggestion.email)"
                                                class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-600 transition">
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100" x-text="suggestion.email"></div>
                                                <div x-show="suggestion.name" class="text-xs text-gray-500 dark:text-gray-400" x-text="suggestion.name"></div>
                                            </button>
                                        </template>
                                    </div>
                                </div>

                                <!-- Selected Emails -->
                                <div class="mt-3 flex flex-wrap gap-2">
                                    <template x-for="(email, index) in reminderEmails" :key="index">
                                        <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full text-sm">
                                            <span x-text="email"></span>
                                            <button 
                                                type="button" 
                                                @click="removeEmail(index)"
                                                class="hover:text-blue-900 dark:hover:text-blue-100">
                                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                            <input type="hidden" :name="'reminder_emails[' + index + ']'" :value="email">
                                        </div>
                                    </template>
                                </div>

                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                                    💡 Tip: Previously used emails will appear as suggestions
                                </p>
                                <?php $__errorArgs = ['reminder_emails'];
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

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                            <a href="<?php echo e(route('notes.index')); ?>"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full shadow-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Note
                            </button>
                        </div>
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
<?php /**PATH F:\Project\salary\resources\views/notes/create.blade.php ENDPATH**/ ?>