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
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    <?php echo e(__('Social Media Accounts')); ?>

                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Connect and manage your social platforms</p>
            </div>
            <a href="<?php echo e(route('social.calendar')); ?>" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="hidden sm:inline"><?php echo e(__('Back to Calendar')); ?></span>
                <span class="sm:hidden">Calendar</span>
            </a>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="space-y-6">
            <?php if(session('success')): ?>
                <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                    <?php echo e(session('success')); ?>

                </div>
            <?php endif; ?>

            <!-- Connect Accounts Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Connect Social Media Accounts</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- LinkedIn -->
                        <a href="<?php echo e(route('social.connect.linkedin')); ?>" 
                            class="flex items-center justify-center gap-3 px-6 py-4 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            Connect LinkedIn
                        </a>

                        <!-- Facebook -->
                        <a href="<?php echo e(route('social.connect.facebook')); ?>" 
                            class="flex items-center justify-center gap-3 px-6 py-4 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Connect Facebook
                        </a>

                        <!-- Twitter -->
                        <a href="<?php echo e(route('social.connect.twitter')); ?>" 
                            class="flex items-center justify-center gap-3 px-6 py-4 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-sm text-white hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                            </svg>
                            Connect Twitter
                        </a>
                    </div>
                </div>
            </div>

            <!-- Connected Accounts -->
            <?php if($accounts->count() > 0): ?>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Connected Accounts</h3>
                        <div class="space-y-4">
                            <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4" x-data="{ testing: false, testResult: null }">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4">
                                            <!-- Platform Icon -->
                                            <div class="w-12 h-12 bg-black dark:bg-gray-900 rounded-lg flex items-center justify-center">
                                                <?php if($account->platform === 'linkedin'): ?>
                                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                                                    </svg>
                                                <?php elseif($account->platform === 'facebook'): ?>
                                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                    </svg>
                                                <?php elseif($account->platform === 'twitter'): ?>
                                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                                    </svg>
                                                <?php endif; ?>
                                            </div>

                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100"><?php echo e($account->platform_name); ?></h4>
                                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($account->platform_username ?? 'No username'); ?></p>
                                                <div class="flex items-center gap-2 mt-1">
                                                    <span class="text-xs px-2 py-0.5 rounded-full <?php echo e($account->is_active ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300'); ?>">
                                                        <?php echo e($account->is_active ? 'Active' : 'Inactive'); ?>

                                                    </span>
                                                    <?php if($account->isTokenExpired()): ?>
                                                        <span class="text-xs px-2 py-0.5 rounded-full bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300">
                                                            Token Expired
                                                        </span>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="flex items-center gap-2">
                                            <!-- Test Connection Button -->
                                            <button @click="testConnection(<?php echo e($account->id); ?>)" 
                                                :disabled="testing"
                                                class="inline-flex items-center gap-2 px-3 py-2 bg-black dark:bg-gray-900 text-white text-xs rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 disabled:opacity-50 transition">
                                                <svg class="w-4 h-4" :class="{ 'animate-spin': testing }" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                                </svg>
                                                <span x-text="testing ? 'Testing...' : 'Test'"></span>
                                            </button>

                                            <!-- Delete Form -->
                                            <form action="<?php echo e(route('social.accounts.destroy', $account)); ?>" method="POST" 
                                                onsubmit="return confirm('Are you sure you want to disconnect this account?');">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-2 px-3 py-2 bg-red-600 dark:bg-red-700 text-white text-xs rounded-lg hover:bg-red-700 dark:hover:bg-red-800 transition">
                                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                    Disconnect
                                                </button>
                                            </form>
                                        </div>
                                    </div>

                                    <!-- Test Result -->
                                    <div x-show="testResult" x-cloak class="mt-3 text-sm p-2 rounded"
                                        :class="testResult?.success ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300'"
                                        x-text="testResult?.message"></div>
                                </div>

                                <?php $__env->startPush('scripts'); ?>
                                <script>
                                    function testConnection(accountId) {
                                        this.testing = true;
                                        this.testResult = null;

                                        fetch(`/social/accounts/${accountId}/test`, {
                                            method: 'POST',
                                            headers: {
                                                'Content-Type': 'application/json',
                                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                                            }
                                        })
                                        .then(response => response.json())
                                        .then(data => {
                                            this.testResult = data;
                                        })
                                        .catch(error => {
                                            this.testResult = { success: false, message: 'Connection test failed' };
                                        })
                                        .finally(() => {
                                            this.testing = false;
                                        });
                                    }
                                </script>
                                <?php $__env->stopPush(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php else: ?>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No accounts connected</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by connecting your social media accounts above.</p>
                    </div>
                </div>
            <?php endif; ?>

    <style>
        [x-cloak] { display: none !important; }
    </style>
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
<?php /**PATH F:\Project\salary\resources\views/social/accounts/index.blade.php ENDPATH**/ ?>