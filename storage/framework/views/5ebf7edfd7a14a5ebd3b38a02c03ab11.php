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
<div class="max-w-2xl mx-auto pb-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="<?php echo e(route('admin.chatbot.widgets.index')); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Widgets
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Widget</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2"><?php echo e($widget->name); ?></p>
    </div>

    <!-- Main Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 mb-6">
        <form action="<?php echo e(route('admin.chatbot.widgets.update', $widget)); ?>" method="POST" class="space-y-6">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <!-- Widget Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Widget Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       placeholder="e.g., My Website Chat" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('name', $widget->name)); ?>" 
                       required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Domain -->
            <div>
                <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Domain <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="domain" 
                       name="domain" 
                       placeholder="e.g., mywebsite.com" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['domain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       value="<?php echo e(old('domain', $widget->domain)); ?>" 
                       required>
                <?php $__errorArgs = ['domain'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Welcome Message -->
            <div>
                <label for="welcome_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Welcome Message <span class="text-red-500">*</span>
                </label>
                <textarea id="welcome_message" 
                          name="welcome_message" 
                          rows="3" 
                          placeholder="e.g., Hi! How can we help you today?" 
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent <?php $__errorArgs = ['welcome_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> border-red-500 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                          required><?php echo e(old('welcome_message', $widget->welcome_message)); ?></textarea>
                <?php $__errorArgs = ['welcome_message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <p class="text-red-500 text-sm mt-1"><?php echo e($message); ?></p>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <!-- Installed In -->
            <div>
                <label for="installed_in" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Installed In (Optional)
                </label>
                <input type="text" 
                       id="installed_in" 
                       name="installed_in" 
                       placeholder="e.g., WordPress, Shopify, Custom" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="<?php echo e(old('installed_in', $widget->installed_in)); ?>">
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">The platform or CMS where this widget is installed</p>
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           <?php echo e(old('is_active', $widget->is_active) ? 'checked' : ''); ?>

                           class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Widget is Active</span>
                </label>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 ml-7">When disabled, the widget won't appear for visitors</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="<?php echo e(route('admin.chatbot.widgets.show', $widget)); ?>" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Token Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">API Token</h2>
        
        <div class="space-y-4">
            <p class="text-gray-600 dark:text-gray-400">Use this token to install the widget on your website or app.</p>
            
            <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Your Token</p>
                <div class="flex gap-2">
                    <input type="text" 
                           readonly 
                           value="<?php echo e($widget->api_token); ?>" 
                           class="flex-1 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-sm font-mono text-gray-700 dark:text-gray-300">
                    <button type="button" 
                            onclick="navigator.clipboard.writeText('<?php echo e($widget->api_token); ?>'); this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy', 2000);"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                        Copy
                    </button>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    <strong>⚠️ Keep this token secret!</strong> Anyone with this token can send messages as your widget. Store it securely in your website's configuration.
                </p>
            </div>

            <form action="<?php echo e(route('admin.chatbot.widgets.rotate-token', $widget)); ?>" method="POST" onsubmit="return confirm('Rotate token? The old token will stop working.');">
                <?php echo csrf_field(); ?>
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                    🔄 Rotate Token
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Conversations</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo e($stats['conversations']); ?></div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Messages</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo e($stats['messages']); ?></div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 dark:bg-red-900/30 border-2 border-red-200 dark:border-red-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 mb-2">Danger Zone</h3>
        
        <?php if($stats['conversations'] > 0): ?>
            <p class="text-red-800 dark:text-red-200 text-sm mb-4">This widget has <?php echo e($stats['conversations']); ?> conversation(s). You must delete or archive these conversations before removing the widget.</p>
            <button type="button" disabled class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed text-sm font-medium">
                Cannot Delete (Has Conversations)
            </button>
        <?php else: ?>
            <p class="text-red-800 dark:text-red-200 text-sm mb-4">Delete this widget permanently. This action cannot be undone.</p>
            <form action="<?php echo e(route('admin.chatbot.widgets.destroy', $widget)); ?>" method="POST" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.');">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    🗑️ Delete Widget
                </button>
            </form>
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
<?php endif; ?><?php /**PATH D:\Ryven Works\ryven-team-management\resources\views/admin/chatbot/widgets/edit.blade.php ENDPATH**/ ?>