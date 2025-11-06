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
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10">
            <div class="max-w-screen-2xl mx-auto px-6 py-4">
                <div class="flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Inbox</h1>
                    
                    <div class="flex items-center gap-3" x-data="{ isSyncing: false }">
                        <!-- Sync Button -->
                        <?php if($currentAccount): ?>
                            <button @click="isSyncing = true; fetch('<?php echo e(route('email.accounts.sync', $currentAccount)); ?>', { method: 'POST', headers: { 'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>', 'Accept': 'application/json' } }).then(r => r.json()).then(d => { console.log('Sync response:', d); setTimeout(() => { isSyncing = false; window.location.reload(); }, 1000); }).catch(err => { console.error('Sync error:', err); isSyncing = false; alert('Sync failed. Please check console for details.'); })"
                                :disabled="isSyncing"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition disabled:opacity-50">
                                <svg class="w-4 h-4 mr-2" :class="{ 'animate-spin': isSyncing }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                                <span x-text="isSyncing ? 'Syncing...' : 'Sync'"></span>
                            </button>
                        <?php endif; ?>

                        <!-- Account Selector -->
                        <?php if($accounts->count() > 1): ?>
                            <select onchange="window.location.href='?account='+this.value" 
                                class="px-4 py-2 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-full text-gray-900 dark:text-white text-sm focus:outline-none focus:border-black dark:focus:border-white transition">
                                <?php $__currentLoopData = $accounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $account): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($account->id); ?>" <?php echo e($currentAccount?->id == $account->id ? 'selected' : ''); ?>>
                                        <?php echo e($account->name); ?> (<?php echo e($account->unread_messages->count()); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        <?php endif; ?>

                        <a href="<?php echo e(route('email.inbox.compose')); ?>" class="inline-flex items-center px-6 py-2 bg-black text-white rounded-full font-semibold text-sm hover:bg-gray-800 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Compose
                        </a>

                        <a href="<?php echo e(route('email.accounts.index')); ?>" class="inline-flex items-center px-6 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Settings
                        </a>
                    </div>
                </div>

                <!-- Search Bar -->
                <form method="GET" class="mt-4">
                    <input type="hidden" name="account" value="<?php echo e($currentAccount?->id); ?>">
                    <input type="hidden" name="folder" value="<?php echo e($folder); ?>">
                    <input type="hidden" name="filter" value="<?php echo e($filter); ?>">
                    <div class="relative">
                        <svg class="absolute left-4 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" name="search" value="<?php echo e(request('search')); ?>" placeholder="Search emails..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 rounded-full text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition">
                    </div>
                </form>
            </div>
        </div>

        <div class="max-w-screen-2xl mx-auto px-6 py-6">
            <div class="flex gap-6">
                <!-- Sidebar -->
                <div class="w-64 flex-shrink-0">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-4 sticky top-24">
                        <nav class="space-y-1">
                            <a href="<?php echo e(route('email.inbox.index', ['account' => $currentAccount?->id, 'folder' => 'INBOX'])); ?>" 
                                class="flex items-center px-4 py-3 rounded-2xl <?php echo e($folder == 'INBOX' ? 'bg-black text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?> transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <span class="font-semibold">Inbox</span>
                            </a>

                            <a href="<?php echo e(route('email.inbox.index', ['account' => $currentAccount?->id, 'folder' => 'Sent'])); ?>" 
                                class="flex items-center px-4 py-3 rounded-2xl <?php echo e($folder == 'Sent' ? 'bg-white text-black' : 'text-gray-400 hover:bg-gray-900'); ?> transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                </svg>
                                <span class="font-semibold">Sent</span>
                            </a>

                            <a href="<?php echo e(route('email.inbox.index', ['account' => $currentAccount?->id, 'folder' => 'Drafts'])); ?>" 
                                class="flex items-center px-4 py-3 rounded-2xl <?php echo e($folder == 'Drafts' ? 'bg-black text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?> transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                <span class="font-semibold">Drafts</span>
                            </a>

                            <a href="<?php echo e(route('email.inbox.index', ['account' => $currentAccount?->id, 'folder' => 'Trash'])); ?>" 
                                class="flex items-center px-4 py-3 rounded-2xl <?php echo e($folder == 'Trash' ? 'bg-black text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?> transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                <span class="font-semibold">Trash</span>
                            </a>

                            <div class="border-t border-gray-200 dark:border-gray-700 my-3"></div>

                            <a href="<?php echo e(route('email.inbox.index', ['account' => $currentAccount?->id, 'folder' => $folder, 'filter' => 'unread'])); ?>" 
                                class="flex items-center px-4 py-3 rounded-2xl <?php echo e($filter == 'unread' ? 'bg-black text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?> transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <span class="font-semibold">Unread</span>
                            </a>

                            <a href="<?php echo e(route('email.inbox.index', ['account' => $currentAccount?->id, 'folder' => $folder, 'filter' => 'starred'])); ?>" 
                                class="flex items-center px-4 py-3 rounded-2xl <?php echo e($filter == 'starred' ? 'bg-black text-white' : 'text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700'); ?> transition">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                </svg>
                                <span class="font-semibold">Starred</span>
                            </a>
                        </nav>
                    </div>
                </div>

                <!-- Email List -->
                <div class="flex-1" x-data="{ selectedEmails: [], selectAll: false }">
                    <?php if($messages->isEmpty()): ?>
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-12 text-center">
                            <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Emails</h3>
                            <p class="text-gray-600 dark:text-gray-400">There are no emails in this folder</p>
                        </div>
                    <?php else: ?>
                        <!-- Bulk Actions Bar -->
                        <div x-show="selectedEmails.length > 0" 
                             x-transition
                             class="bg-black text-white rounded-2xl p-4 mb-4 flex items-center justify-between"
                             x-data="{ showModal: false, modalAction: '', modalTitle: '', modalMessage: '' }">
                            <div>
                                <span class="font-semibold" x-text="selectedEmails.length"></span> email(s) selected
                            </div>
                            <div class="flex gap-2">
                                <?php if($folder === 'Trash'): ?>
                                    <button @click="window.bulkAction('restore', selectedEmails)" 
                                            class="px-4 py-2 bg-green-600 hover:bg-green-700 rounded-lg font-semibold transition">
                                        ♻️ Restore
                                    </button>
                                    <button @click="modalAction = 'delete'; modalTitle = 'Delete Forever?'; modalMessage = 'Are you sure you want to permanently delete ' + selectedEmails.length + ' email(s)? This action cannot be undone.'; showModal = true" 
                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold transition">
                                        ❌ Delete Forever
                                    </button>
                                <?php else: ?>
                                    <button @click="modalAction = 'trash'; modalTitle = 'Move to Trash?'; modalMessage = 'Are you sure you want to move ' + selectedEmails.length + ' email(s) to trash?'; showModal = true" 
                                            class="px-4 py-2 bg-red-600 hover:bg-red-700 rounded-lg font-semibold transition">
                                        🗑️ Move to Trash
                                    </button>
                                <?php endif; ?>
                                <button @click="selectedEmails = []; selectAll = false" 
                                        class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-lg font-semibold transition">
                                    Cancel
                                </button>
                            </div>

                            <!-- Confirmation Modal -->
                            <div x-show="showModal" 
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0"
                                 x-transition:enter-end="opacity-100"
                                 x-transition:leave="transition ease-in duration-200"
                                 x-transition:leave-start="opacity-100"
                                 x-transition:leave-end="opacity-0"
                                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 px-4"
                                 @click.self="showModal = false">
                                <div x-show="showModal"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     x-transition:leave="transition ease-in duration-200"
                                     x-transition:leave-start="opacity-100 scale-100"
                                     x-transition:leave-end="opacity-0 scale-95"
                                     class="bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-6">
                                    <!-- Modal Header -->
                                    <div class="flex items-start gap-4 mb-4">
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center"
                                             :class="modalAction === 'delete' ? 'bg-red-100 dark:bg-red-900/30' : 'bg-yellow-100 dark:bg-yellow-900/30'">
                                            <svg class="w-6 h-6"
                                                 :class="modalAction === 'delete' ? 'text-red-600 dark:text-red-400' : 'text-yellow-600 dark:text-yellow-400'"
                                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white" x-text="modalTitle"></h3>
                                            <p class="text-gray-600 dark:text-gray-400 mt-2" x-text="modalMessage"></p>
                                        </div>
                                    </div>

                                    <!-- Modal Actions -->
                                    <div class="flex gap-3 justify-end mt-6">
                                        <button @click="showModal = false" 
                                                class="px-6 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                                            Cancel
                                        </button>
                                        <button @click="window.bulkAction(modalAction, selectedEmails); showModal = false" 
                                                class="px-6 py-2.5 rounded-full font-semibold transition"
                                                :class="modalAction === 'delete' ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-black hover:bg-gray-800 text-white'">
                                            <span x-text="modalAction === 'delete' ? 'Delete Forever' : 'Move to Trash'"></span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl overflow-hidden">
                            <!-- Select All Header -->
                            <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex items-center gap-4">
                                <input type="checkbox" 
                                       x-model="selectAll"
                                       @change="selectAll ? selectedEmails = [<?php echo e($messages->pluck('id')->join(',')); ?>] : selectedEmails = []"
                                       class="w-4 h-4 text-black bg-gray-100 border-gray-300 rounded focus:ring-black dark:focus:ring-black dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                <span class="text-sm font-semibold text-gray-600 dark:text-gray-400">Select All</span>
                            </div>
                            <?php $__currentLoopData = $messages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $message): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="group flex items-center p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition <?php echo e(!$message->is_read ? 'bg-blue-50 dark:bg-gray-750' : ''); ?>">
                                    
                                    <!-- Checkbox -->
                                    <input type="checkbox" 
                                           value="<?php echo e($message->id); ?>"
                                           x-model="selectedEmails"
                                           @click.stop
                                           class="w-4 h-4 text-black bg-gray-100 border-gray-300 rounded focus:ring-black flex-shrink-0">
                                    
                                    <!-- Star -->
                                    <form action="<?php echo e(route('email.inbox.star', $message)); ?>" method="POST" class="ml-3 flex-shrink-0" onclick="event.preventDefault(); event.stopPropagation(); this.submit();">
                                        <?php echo csrf_field(); ?>
                                        <button type="submit" class="text-gray-400 hover:text-yellow-500 transition">
                                            <svg class="w-5 h-5 <?php echo e($message->is_starred ? 'fill-yellow-500 text-yellow-500' : ''); ?>" fill="<?php echo e($message->is_starred ? 'currentColor' : 'none'); ?>" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                            </svg>
                                        </button>
                                    </form>

                                    <!-- Email Content - Clickable -->
                                    <a href="<?php echo e(route('email.inbox.show', $message)); ?>" class="flex items-center flex-1 min-w-0 ml-4">
                                        <!-- From -->
                                        <div class="w-48 flex-shrink-0">
                                            <p class="font-semibold <?php echo e(!$message->is_read ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400'); ?> truncate">
                                                <?php echo e($message->from_name ?: $message->from_email); ?>

                                            </p>
                                        </div>

                                        <!-- Subject & Preview -->
                                        <div class="flex-1 mx-6 min-w-0">
                                            <p class="font-semibold <?php echo e(!$message->is_read ? 'text-gray-900 dark:text-white' : 'text-gray-600 dark:text-gray-400'); ?> truncate">
                                                <?php echo e(Str::limit($message->subject ?: '(No Subject)', 60)); ?>

                                            </p>
                                            <p class="text-sm text-gray-500 dark:text-gray-500 truncate">
                                                <?php echo e(Str::limit(strip_tags($message->body_text), 80)); ?>

                                            </p>
                                        </div>

                                        <!-- Date -->
                                        <div class="flex items-center gap-3 flex-shrink-0">
                                            <?php if($message->has_attachments): ?>
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                </svg>
                                            <?php endif; ?>
                                            <span class="text-sm text-gray-500 dark:text-gray-400 w-20 text-right">
                                                <?php echo e($message->sent_at->format('M d')); ?>

                                            </span>
                                        </div>
                                    </a>
                                    
                                    <!-- Quick Actions (on hover) -->
                                    <div class="flex gap-2 ml-3 opacity-0 group-hover:opacity-100 transition-opacity flex-shrink-0" onclick="event.preventDefault(); event.stopPropagation();">
                                        <?php if($folder === 'Trash'): ?>
                                            <form action="<?php echo e(route('email.inbox.restore', $message)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="p-2 bg-green-100 dark:bg-green-900/30 hover:bg-green-200 dark:hover:bg-green-900/50 rounded-lg transition" title="Restore">
                                                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('email.inbox.destroy', $message)); ?>" method="POST" class="inline" onsubmit="return confirm('Permanently delete this email?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition" title="Delete Forever">
                                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php else: ?>
                                            <form action="<?php echo e(route('email.inbox.trash', $message)); ?>" method="POST" class="inline">
                                                <?php echo csrf_field(); ?>
                                                <button type="submit" class="p-2 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 rounded-lg transition" title="Move to Trash">
                                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>

                        <!-- Pagination & Stats -->
                        <div class="mt-6 flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Showing <span class="font-semibold"><?php echo e($messages->firstItem() ?? 0); ?></span> 
                                    to <span class="font-semibold"><?php echo e($messages->lastItem() ?? 0); ?></span> 
                                    of <span class="font-semibold"><?php echo e($messages->total()); ?></span> emails
                                </p>
                                
                                <!-- Per Page Selector -->
                                <select onchange="window.location.href='?account=<?php echo e($currentAccount?->id); ?>&folder=<?php echo e($folder); ?>&filter=<?php echo e($filter); ?>&search=<?php echo e(request('search')); ?>&per_page='+this.value" 
                                    class="px-3 py-1 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-lg text-sm text-gray-900 dark:text-white focus:outline-none focus:border-black dark:focus:border-white transition">
                                    <option value="25" <?php echo e(request('per_page', 25) == 25 ? 'selected' : ''); ?>>25 per page</option>
                                    <option value="50" <?php echo e(request('per_page', 25) == 50 ? 'selected' : ''); ?>>50 per page</option>
                                    <option value="100" <?php echo e(request('per_page', 25) == 100 ? 'selected' : ''); ?>>100 per page</option>
                                </select>
                            </div>
                            
                            <div>
                                <?php echo e($messages->links()); ?>

                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php $__env->startPush('scripts'); ?>
    <script>
        // Real-time email notifications
        Echo.private('user.<?php echo e(auth()->id()); ?>')
            .listen('.email.new', (e) => {
                console.log('New email received:', e);
                
                // Show browser notification if permitted
                if (Notification.permission === 'granted') {
                    new Notification('New Email: ' + e.subject, {
                        body: 'From: ' + (e.from_name || e.from) + '\n' + e.preview,
                        icon: '/favicon.ico',
                        tag: 'email-' + e.id
                    });
                }
                
                // Show toast notification in UI
                showEmailToast(e);
                
                // Reload page to show new email if on inbox page
                <?php if(!request('search') && $folder === 'inbox'): ?>
                    setTimeout(() => {
                        window.location.reload();
                    }, 2000);
                <?php endif; ?>
            });

        // Request notification permission on page load
        if (Notification.permission === 'default') {
            Notification.requestPermission();
        }

        // Aggressive auto-sync for real-time updates
        let lastSyncTime = Date.now();
        const SYNC_COOLDOWN = 15000; // 15 seconds cooldown
        const AUTO_SYNC_INTERVAL = 30000; // Auto-sync every 30 seconds

        // Periodic auto-sync
        <?php if($currentAccount): ?>
        setInterval(function() {
            console.log('Periodic auto-sync triggered...');
            triggerSync();
        }, AUTO_SYNC_INTERVAL);
        <?php endif; ?>

        // Sync when user returns to the tab
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                const timeSinceLastSync = Date.now() - lastSyncTime;
                if (timeSinceLastSync > SYNC_COOLDOWN) {
                    console.log('Tab became visible, triggering sync...');
                    triggerSync();
                }
            }
        });

        // Sync when window gains focus
        window.addEventListener('focus', function() {
            const timeSinceLastSync = Date.now() - lastSyncTime;
            if (timeSinceLastSync > SYNC_COOLDOWN) {
                console.log('Window gained focus, triggering sync...');
                triggerSync();
            }
        });

        // Function to trigger background sync
        function triggerSync() {
            <?php if($currentAccount): ?>
                lastSyncTime = Date.now();
                
                fetch('<?php echo e(route("email.accounts.sync", ["account" => $currentAccount->id ?? 0])); ?>', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>',
                        'Accept': 'application/json',
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Background sync completed:', data);
                    // Silently reload if new emails were found
                    if (data.new_emails > 0) {
                        console.log(`Found ${data.new_emails} new emails, reloading...`);
                        setTimeout(() => window.location.reload(), 1000);
                    }
                })
                .catch(error => console.log('Background sync failed:', error));
            <?php endif; ?>
        }

        // Initial sync when page loads
        <?php if($currentAccount): ?>
        setTimeout(() => {
            console.log('Initial sync on page load...');
            triggerSync();
        }, 2000); // Wait 2 seconds after page load
        <?php endif; ?>

        // Toast notification function
        function showEmailToast(email) {
            const toast = document.createElement('div');
            toast.className = 'fixed top-4 right-4 z-50 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-2xl p-4 max-w-md animate-slide-in';
            toast.innerHTML = `
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-10 h-10 bg-black rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-gray-900 dark:text-white">New Email</p>
                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">${email.from_name || email.from}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-1 font-semibold truncate">${email.subject}</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1 line-clamp-2">${email.preview}</p>
                    </div>
                    <button onclick="this.closest('.animate-slide-in').remove()" class="flex-shrink-0 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Auto remove after 8 seconds
            setTimeout(() => {
                toast.style.transition = 'opacity 0.3s';
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 8000);
        }
    </script>
    
    <style>
        @keyframes slide-in {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        .animate-slide-in {
            animation: slide-in 0.3s ease-out;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        // Global function for bulk actions - accessible from Alpine
        window.bulkAction = function(action, selectedIds) {
            if (!selectedIds || selectedIds.length === 0) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '<?php echo e(route("email.inbox.bulk-action")); ?>';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '<?php echo e(csrf_token()); ?>';
            form.appendChild(csrfToken);

            const actionInput = document.createElement('input');
            actionInput.type = 'hidden';
            actionInput.name = 'action';
            actionInput.value = action;
            form.appendChild(actionInput);

            const idsInput = document.createElement('input');
            idsInput.type = 'hidden';
            idsInput.name = 'ids';
            idsInput.value = JSON.stringify(selectedIds);
            form.appendChild(idsInput);

            document.body.appendChild(form);
            form.submit();
        };
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
<?php /**PATH F:\Project\salary\resources\views/email/inbox/index.blade.php ENDPATH**/ ?>