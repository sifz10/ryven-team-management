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
<div class="space-y-6 pb-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <a href="<?php echo e(route('admin.chatbot.widgets.index')); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mb-4 inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Back to Widgets
            </a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($widget->name); ?></h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2"><?php echo e($widget->domain); ?></p>
        </div>
        <div class="flex gap-2">
            <a href="<?php echo e(route('admin.chatbot.widgets.edit', $widget)); ?>" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit
            </a>
            <span class="px-3 py-2 rounded-lg text-sm font-semibold
                <?php echo e($widget->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300'); ?>">
                <?php echo e($widget->is_active ? '✓ Active' : '○ Inactive'); ?>

            </span>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Conversations</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo e($stats['conversations']); ?></div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pending</div>
            <div class="text-3xl font-bold text-yellow-600 mt-2"><?php echo e($stats['pending']); ?></div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Active</div>
            <div class="text-3xl font-bold text-blue-600 mt-2"><?php echo e($stats['active']); ?></div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Messages</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2"><?php echo e($stats['messages']); ?></div>
        </div>
    </div>

    <!-- Widget Details -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Information Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Widget Information</h2>
                
                <div class="space-y-4">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">NAME</p>
                        <p class="text-gray-900 dark:text-white mt-1"><?php echo e($widget->name); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">DOMAIN</p>
                        <p class="text-gray-900 dark:text-white mt-1"><?php echo e($widget->domain); ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">WELCOME MESSAGE</p>
                        <p class="text-gray-900 dark:text-white mt-1"><?php echo e($widget->welcome_message); ?></p>
                    </div>
                    <?php if($widget->installed_in): ?>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">INSTALLED IN</p>
                        <p class="text-gray-900 dark:text-white mt-1"><?php echo e($widget->installed_in); ?></p>
                    </div>
                    <?php endif; ?>
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 font-semibold">CREATED AT</p>
                        <p class="text-gray-900 dark:text-white mt-1"><?php echo e($widget->created_at->format('F j, Y \a\t g:i A')); ?></p>
                    </div>
                </div>
            </div>

            <!-- Installation Code -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Installation Code</h2>
                
                <div class="bg-gray-900 dark:bg-gray-950 rounded p-4 overflow-x-auto mb-4">
                    <pre class="text-green-400 text-sm"><code>&lt;script src="https://<?php echo e(config('app.domain', 'your-domain.com')); ?>/chatbot-widget.js"
        data-api-token="<?php echo e($widget->api_token); ?>"
        data-widget-url="https://<?php echo e(config('app.domain', 'your-domain.com')); ?>"&gt;
&lt;/script&gt;</code></pre>
                </div>

                <div class="flex gap-2">
                    <button type="button" 
                            onclick="const code = document.querySelector('code').innerText; navigator.clipboard.writeText(code); this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy Code', 2000);"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                        Copy Code
                    </button>
                    <a href="<?php echo e(route('admin.chatbot.guide')); ?>" class="px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium">
                        View Installation Guide
                    </a>
                </div>
            </div>

            <!-- Recent Conversations -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Conversations</h2>
                    <a href="<?php echo e(route('admin.chatbot.index', ['widget_id' => $widget->id])); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 text-sm font-medium">
                        View All →
                    </a>
                </div>

                <?php if($recentConversations->count() > 0): ?>
                    <div class="space-y-3">
                        <?php $__currentLoopData = $recentConversations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $conversation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <a href="<?php echo e(route('admin.chatbot.show', $conversation)); ?>" class="block p-4 border border-gray-200 dark:border-gray-700 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white"><?php echo e($conversation->visitor_name ?? 'Anonymous'); ?></p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1"><?php echo e(Str::limit($conversation->messages->last()?->message, 50) ?? 'No messages yet'); ?></p>
                                    </div>
                                    <span class="px-2 py-1 text-xs font-semibold rounded
                                        <?php echo e($conversation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : ''); ?>

                                        <?php echo e($conversation->status === 'active' ? 'bg-blue-100 text-blue-800' : ''); ?>

                                        <?php echo e($conversation->status === 'closed' ? 'bg-gray-100 text-gray-800' : ''); ?>">
                                        <?php echo e(ucfirst($conversation->status)); ?>

                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2"><?php echo e($conversation->created_at->diffForHumans()); ?></p>
                            </a>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php else: ?>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">No conversations yet. Once your widget is installed, conversations will appear here.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Token Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">API Token</h3>
                
                <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded p-3 mb-4">
                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Token</p>
                    <p class="text-xs font-mono text-gray-700 dark:text-gray-300 break-all"><?php echo e($widget->api_token); ?></p>
                </div>

                <button type="button" 
                        onclick="navigator.clipboard.writeText('<?php echo e($widget->api_token); ?>'); this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy Token', 2000);"
                        class="w-full px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium mb-2">
                    Copy Token
                </button>

                <form action="<?php echo e(route('admin.chatbot.widgets.rotate-token', $widget)); ?>" method="POST" onsubmit="return confirm('Generate a new token? The old token will stop working.');">
                    <?php echo csrf_field(); ?>
                    <button type="submit" class="w-full px-4 py-2 border border-orange-300 dark:border-orange-700 text-orange-700 dark:text-orange-300 rounded-lg hover:bg-orange-50 dark:hover:bg-orange-900/30 transition-colors text-sm font-medium">
                        🔄 Generate New Token
                    </button>
                </form>
            </div>

            <!-- Status Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Status</h3>
                
                <div class="space-y-2">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        <strong>Current Status:</strong>
                    </p>
                    <p class="text-sm
                        <?php echo e($widget->is_active ? 'text-green-700 dark:text-green-300' : 'text-gray-700 dark:text-gray-300'); ?>">
                        <?php echo e($widget->is_active ? '✓ This widget is active and visible to visitors' : '○ This widget is inactive and hidden from visitors'); ?>

                    </p>
                </div>
                
                <a href="<?php echo e(route('admin.chatbot.widgets.edit', $widget)); ?>" class="mt-4 block w-full px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors text-sm font-medium text-center">
                    Change Status
                </a>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
                
                <div class="space-y-2">
                    <a href="<?php echo e(route('admin.chatbot.index', ['widget_id' => $widget->id])); ?>" class="block w-full px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors text-sm font-medium text-center">
                        View Conversations
                    </a>
                    <a href="<?php echo e(route('admin.chatbot.guide')); ?>" class="block w-full px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 rounded-lg hover:bg-purple-200 dark:hover:bg-purple-900/50 transition-colors text-sm font-medium text-center">
                        Installation Guide
                    </a>
                    <a href="<?php echo e(route('admin.chatbot.widgets.edit', $widget)); ?>" class="block w-full px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors text-sm font-medium text-center">
                        Edit Widget
                    </a>
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
<?php endif; ?><?php /**PATH D:\Ryven Works\ryven-team-management\resources\views/admin/chatbot/widgets/show.blade.php ENDPATH**/ ?>