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
<div x-data="ticketDetail()" x-init="init()" class="py-6">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-4 mb-4">
                <a href="<?php echo e(route('tickets.index')); ?>" class="p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                </a>
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                        <h1 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($ticket->ticket_number); ?></h1>

                        <!-- Status Badge -->
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold
                            <?php echo e($ticket->status === 'open' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : ''); ?>

                            <?php echo e($ticket->status === 'in-progress' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300' : ''); ?>

                            <?php echo e($ticket->status === 'resolved' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : ''); ?>

                            <?php echo e($ticket->status === 'closed' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : ''); ?>">
                            <?php echo e(ucfirst($ticket->status)); ?>

                        </span>

                        <!-- Priority Badge -->
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold
                            <?php echo e($ticket->priority === 'low' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : ''); ?>

                            <?php echo e($ticket->priority === 'medium' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : ''); ?>

                            <?php echo e($ticket->priority === 'high' ? 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300' : ''); ?>

                            <?php echo e($ticket->priority === 'critical' ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' : ''); ?>">
                            <?php echo e(ucfirst($ticket->priority)); ?> Priority
                        </span>

                        <!-- Type Badge -->
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                            <?php echo e(ucfirst($ticket->type)); ?>

                        </span>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Created <?php echo e($ticket->created_at->format('M d, Y \a\t g:i A')); ?>

                    </p>
                </div>

                <div class="flex items-center gap-2">
                    <button
                        @click="showEditModal = true"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <span>Edit</span>
                    </button>

                    <button
                        @click="confirmDelete()"
                        class="inline-flex items-center gap-2 px-6 py-3 border-2 border-red-500 text-red-500 rounded-full hover:bg-red-50 dark:hover:bg-red-900/20 transition-all font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        <span>Delete</span>
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Title & Description -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4"><?php echo e($ticket->title); ?></h2>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                        <?php echo nl2br(e($ticket->description)); ?>

                    </div>
                </div>

                <!-- Comments/Replies Section -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <span>Comments & Activity</span>
                    </h3>

                    <!-- Comment Form -->
                    <form @submit.prevent="addComment()" class="mb-6">
                        <div class="relative">
                            <textarea
                                x-ref="commentTextarea"
                                x-model="commentText"
                                @input="checkMention($event)"
                                @keydown="handleMentionKeydown($event)"
                                rows="3"
                                placeholder="Add a comment or update... (Type @ to mention someone)"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 resize-none"
                                required></textarea>

                            <!-- Mention Autocomplete Dropdown -->
                            <div x-show="showMentionDropdown"
                                 x-cloak
                                 class="absolute z-50 w-64 mt-1 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="(employee, index) in filteredEmployees" :key="employee.id">
                                    <div @click="selectMention(employee)"
                                         :class="{'bg-gray-100 dark:bg-gray-600': index === selectedMentionIndex}"
                                         class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer flex items-center gap-2">
                                        <div class="w-8 h-8 bg-gray-300 dark:bg-gray-500 rounded-full flex items-center justify-center">
                                            <span class="text-xs font-bold" x-text="employee.initials"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="employee.name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="'@' + employee.username"></p>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                💡 Tip: Use <code class="px-1 py-0.5 bg-gray-100 dark:bg-gray-700 rounded">@username</code> to mention team members
                            </p>
                            <div class="flex items-center gap-2">
                            <button
                                type="submit"
                                x-bind:disabled="isSubmittingComment || !commentText.trim()"
                                class="inline-flex items-center gap-2 px-6 py-2.5 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium text-sm">
                                <svg x-show="isSubmittingComment" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <svg x-show="!isSubmittingComment" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                                <span x-text="isSubmittingComment ? 'Posting...' : 'Post Comment'"></span>
                            </button>
                            </div>
                        </div>
                    </form>

                    <!-- AI Typing Indicator -->
                    <div x-show="aiIsTyping" class="flex gap-3 p-4 rounded-xl bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 animate-pulse">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0 bg-gradient-to-br from-blue-500 to-purple-600">
                            <span class="text-sm font-bold text-white">AI</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="font-medium text-sm text-blue-600 dark:text-blue-400 flex items-center gap-2">
                                    <span>AI Assistant</span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                        🤖 AI Assistant
                                    </span>
                                </span>
                            </div>
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-300">
                                <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>AI is analyzing your message and preparing a response...</span>
                            </div>
                        </div>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-4" x-show="comments.length > 0">
                        <template x-for="comment in comments" :key="comment.id">
                            <div
                                class="flex gap-3 p-4 rounded-xl"
                                :class="comment.is_ai ? 'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500' : 'bg-gray-50 dark:bg-gray-700/50'"
                            >
                                <div
                                    class="w-10 h-10 rounded-full flex items-center justify-center flex-shrink-0"
                                    :class="comment.is_ai ? 'bg-gradient-to-br from-blue-500 to-purple-600' : 'bg-gray-300 dark:bg-gray-600'"
                                >
                                    <span
                                        class="text-sm font-bold"
                                        :class="comment.is_ai ? 'text-white' : 'text-gray-700 dark:text-gray-300'"
                                        x-text="comment.author_initials"
                                    ></span>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span
                                            class="font-medium text-sm flex items-center gap-2"
                                            :class="comment.is_ai ? 'text-blue-600 dark:text-blue-400' : 'text-gray-900 dark:text-white'"
                                        >
                                            <span x-text="comment.author_name"></span>
                                            <span x-show="comment.is_ai" class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">
                                                🤖 AI Assistant
                                            </span>
                                        </span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400" x-text="comment.created_at"></span>
                                    </div>
                                    <p
                                        class="text-sm whitespace-pre-wrap"
                                        :class="comment.is_ai ? 'text-gray-700 dark:text-gray-200' : 'text-gray-700 dark:text-gray-300'"
                                        x-text="comment.comment"
                                    ></p>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="comments.length === 0" class="text-center py-8">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-sm text-gray-500 dark:text-gray-400">No comments yet. Be the first to comment!</p>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info -->
            <div class="space-y-6">
                <!-- Project Info -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Project</h3>
                    <a href="<?php echo e(route('projects.show', $ticket->project)); ?>" class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-600 transition-all group">
                        <div class="w-10 h-10 bg-black dark:bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900 dark:text-white truncate"><?php echo e($ticket->project->name); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">View Project</p>
                        </div>
                    </a>
                </div>

                <!-- People -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">People</h3>
                    <div class="space-y-4">
                        <!-- Reported By -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Reported By</p>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center flex-shrink-0">
                                    <span class="text-sm font-bold text-gray-600 dark:text-gray-300">
                                        <?php echo e(substr($ticket->reportedBy->first_name, 0, 1)); ?><?php echo e(substr($ticket->reportedBy->last_name, 0, 1)); ?>

                                    </span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white text-sm">
                                        <?php echo e($ticket->reportedBy->first_name); ?> <?php echo e($ticket->reportedBy->last_name); ?>

                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Assigned To -->
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-2">Assigned To</p>
                            <?php if($ticket->assignedTo): ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-black dark:bg-white rounded-full flex items-center justify-center flex-shrink-0">
                                        <span class="text-sm font-bold text-white dark:text-black">
                                            <?php echo e(substr($ticket->assignedTo->first_name, 0, 1)); ?><?php echo e(substr($ticket->assignedTo->last_name, 0, 1)); ?>

                                        </span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-gray-900 dark:text-white text-sm">
                                            <?php echo e($ticket->assignedTo->first_name); ?> <?php echo e($ticket->assignedTo->last_name); ?>

                                        </p>
                                    </div>
                                </div>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Unassigned</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-sm font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-4">Timeline</h3>
                    <div class="space-y-3">
                        <div class="flex items-start gap-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-900 dark:text-white">Created</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($ticket->created_at->format('M d, Y \a\t g:i A')); ?></p>
                            </div>
                        </div>

                        <?php if($ticket->resolved_at): ?>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Resolved</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e(\Carbon\Carbon::parse($ticket->resolved_at)->format('M d, Y \a\t g:i A')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if($ticket->updated_at != $ticket->created_at): ?>
                            <div class="flex items-start gap-3">
                                <div class="w-2 h-2 bg-gray-400 rounded-full mt-2 flex-shrink-0"></div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900 dark:text-white">Last Updated</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($ticket->updated_at->format('M d, Y \a\t g:i A')); ?></p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div x-show="showEditModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showEditModal" @click="showEditModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-md"></div>
            <div x-show="showEditModal" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full p-8 z-10 border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Ticket</h2>
                    <button @click="showEditModal = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="updateTicket()" class="space-y-4">
                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                        <input type="text" x-model="editForm.title" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                        <textarea x-model="editForm.description" required rows="4" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Status *</label>
                            <select x-model="editForm.status" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                                <option value="open">Open</option>
                                <option value="in-progress">In Progress</option>
                                <option value="resolved">Resolved</option>
                                <option value="closed">Closed</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Priority *</label>
                            <select x-model="editForm.priority" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                            <select x-model="editForm.type" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                                <option value="bug">Bug</option>
                                <option value="feature">Feature</option>
                                <option value="enhancement">Enhancement</option>
                                <option value="question">Question</option>
                            </select>
                        </div>

                        <!-- Assigned To -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Assign To</label>
                            <select x-model="editForm.assigned_to" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                                <option value="">Unassigned</option>
                                <?php $__currentLoopData = $employees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $employee): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($employee->id); ?>"><?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                        <button type="button" @click="showEditModal = false" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-all">
                            <span>Cancel</span>
                        </button>
                        <button type="submit" x-bind:disabled="isSubmitting" class="inline-flex items-center gap-2 px-8 py-3 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium">
                            <svg x-show="isSubmitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isSubmitting ? 'Saving...' : 'Save Changes'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function ticketDetail() {
    return {
        showEditModal: false,
        isSubmitting: false,
        isSubmittingComment: false,
        aiIsTyping: false,
        commentText: '',
        comments: [],
        showMentionDropdown: false,
        mentionSearch: '',
        filteredEmployees: [],
        selectedMentionIndex: 0,
        mentionStartPos: 0,
        allEmployees: <?php echo e(Js::from($employeesData)); ?>,
        editForm: {
            title: <?php echo e(Js::from($ticket->title)); ?>,
            description: <?php echo e(Js::from($ticket->description)); ?>,
            status: <?php echo e(Js::from($ticket->status)); ?>,
            priority: <?php echo e(Js::from($ticket->priority)); ?>,
            type: <?php echo e(Js::from($ticket->type)); ?>,
            assigned_to: <?php echo e(Js::from($ticket->assigned_to)); ?>

        },

        async init() {
            await this.loadComments();
        },

        checkMention(event) {
            const text = event.target.value;
            const cursorPos = event.target.selectionStart;

            // Find last @ before cursor
            const textBeforeCursor = text.substring(0, cursorPos);
            const lastAtIndex = textBeforeCursor.lastIndexOf('@');

            if (lastAtIndex !== -1) {
                const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);

                // Check if there's a space after @ (if so, close dropdown)
                if (textAfterAt.includes(' ')) {
                    this.showMentionDropdown = false;
                    return;
                }

                // Filter employees by text after @
                this.mentionSearch = textAfterAt.toLowerCase();
                this.mentionStartPos = lastAtIndex;
                this.filteredEmployees = this.allEmployees.filter(emp =>
                    emp.name.toLowerCase().includes(this.mentionSearch) ||
                    emp.username.toLowerCase().includes(this.mentionSearch)
                ).slice(0, 5);

                this.showMentionDropdown = this.filteredEmployees.length > 0;
                this.selectedMentionIndex = 0;
            } else {
                this.showMentionDropdown = false;
            }
        },

        handleMentionKeydown(event) {
            if (!this.showMentionDropdown) return;

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                this.selectedMentionIndex = Math.min(this.selectedMentionIndex + 1, this.filteredEmployees.length - 1);
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                this.selectedMentionIndex = Math.max(this.selectedMentionIndex - 1, 0);
            } else if (event.key === 'Enter' && this.filteredEmployees.length > 0) {
                event.preventDefault();
                this.selectMention(this.filteredEmployees[this.selectedMentionIndex]);
            } else if (event.key === 'Escape') {
                this.showMentionDropdown = false;
            }
        },

        selectMention(employee) {
            const beforeMention = this.commentText.substring(0, this.mentionStartPos);
            const afterMention = this.commentText.substring(this.$refs.commentTextarea.selectionStart);

            this.commentText = beforeMention + '@' + employee.username + ' ' + afterMention;
            this.showMentionDropdown = false;

            // Set cursor position after mention
            this.$nextTick(() => {
                const newPos = beforeMention.length + employee.username.length + 2;
                this.$refs.commentTextarea.setSelectionRange(newPos, newPos);
                this.$refs.commentTextarea.focus();
            });
        },

        async loadComments() {
            try {
                const response = await fetch('<?php echo e(route("tickets.show", $ticket)); ?>/comments');
                const data = await response.json();
                this.comments = data.comments || [];
            } catch (error) {
                console.error('Error loading comments:', error);
            }
        },

        async addComment() {
            if (!this.commentText.trim() || this.isSubmittingComment) return;
            this.isSubmittingComment = true;

            try {
                const response = await fetch('<?php echo e(route("tickets.show", $ticket)); ?>/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({ comment: this.commentText })
                });

                const data = await response.json();

                if (data.success) {
                    this.commentText = '';

                    // Add user comment immediately
                    this.comments.push(data.comment);

                    // Check if AI will respond
                    if (data.ai_response) {
                        // Show "AI is typing..." indicator
                        this.aiIsTyping = true;

                        // Scroll to show typing indicator
                        await this.$nextTick();
                        const container = this.$el.querySelector('.space-y-4');
                        if (container) {
                            container.scrollIntoView({ behavior: 'smooth', block: 'end' });
                        }

                        // Wait 2-3 seconds to simulate AI thinking/typing
                        setTimeout(() => {
                            this.aiIsTyping = false;
                            this.comments.push(data.ai_response);

                            // Show notification if ticket was updated
                            if (data.ticket_updated) {
                                this.showNotification('🤖 AI Assistant updated the ticket priority/status');
                            }

                            // Scroll to new comment
                            setTimeout(() => {
                                const container = this.$el.querySelector('.space-y-4');
                                if (container) {
                                    container.scrollIntoView({ behavior: 'smooth', block: 'end' });
                                }
                            }, 100);
                        }, 2500); // 2.5 second typing delay
                    }
                } else {
                    alert('Error: ' + (data.message || 'Failed to post comment'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to post comment. Please try again.');
            } finally {
                this.isSubmittingComment = false;
            }
        },

        showNotification(message) {
            // Simple notification toast (you can enhance this)
            const notification = document.createElement('div');
            notification.className = 'fixed top-4 right-4 bg-blue-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in';
            notification.textContent = message;
            document.body.appendChild(notification);
            setTimeout(() => notification.remove(), 3000);
        },

        async updateTicket() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            try {
                const response = await fetch('<?php echo e(route("tickets.update", $ticket)); ?>', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.editForm)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to update ticket'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to update ticket. Please try again.');
            } finally {
                this.isSubmitting = false;
            }
        },

        async confirmDelete() {
            if (!confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) {
                return;
            }

            try {
                const response = await fetch('<?php echo e(route("tickets.destroy", $ticket)); ?>', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const data = await response.json();

                if (data.success) {
                    window.location.href = '<?php echo e(route("tickets.index")); ?>';
                } else {
                    alert('Error: ' + (data.message || 'Failed to delete ticket'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete ticket. Please try again.');
            }
        }
    };
}
</script>

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
<?php /**PATH F:\Project\salary\resources\views/tickets/show.blade.php ENDPATH**/ ?>