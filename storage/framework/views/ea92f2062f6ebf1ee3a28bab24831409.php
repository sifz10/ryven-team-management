<!-- PR Details Modal -->
<div x-show="showPrModal" 
     x-cloak
     @keydown.escape.window="showPrModal = false"
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">
    
    <!-- Backdrop -->
    <div x-show="showPrModal"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="showPrModal = false"
         class="fixed inset-0 bg-black bg-opacity-50"></div>
    
    <!-- Modal Content -->
    <div class="flex items-start justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
        <div x-show="showPrModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             @click.stop
             class="relative inline-block w-full max-w-6xl my-8 overflow-hidden text-left align-middle bg-white dark:bg-gray-800 rounded-2xl shadow-2xl transform transition-all">
            
            <!-- Loading State -->
            <div x-show="prLoading" class="p-12 text-center">
                <svg class="animate-spin h-12 w-12 mx-auto text-gray-400" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Loading PR details...</p>
            </div>

            <!-- PR Details (shown when loaded) -->
            <div x-show="!prLoading && prData" style="max-height: 90vh; overflow-y: auto;">
                
                <!-- Header -->
                <div class="sticky top-0 z-10 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                                      :class="{
                                          'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400': prData?.pr?.state === 'open',
                                          'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400': prData?.pr?.merged,
                                          'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': prData?.pr?.state === 'closed' && !prData?.pr?.merged
                                      }">
                                    <span x-text="prData?.pr?.merged ? 'Merged' : (prData?.pr?.state === 'open' ? 'Open' : 'Closed')"></span>
                                </span>
                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="'#' + prData?.pr?.number"></span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="prData?.pr?.title"></h2>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                <span x-text="prData?.pr?.user?.login"></span> wants to merge 
                                <span class="font-mono text-xs bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 rounded" x-text="prData?.pr?.commits"></span> commits
                                into <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded" x-text="prData?.pr?.base?.ref"></span>
                                from <span class="font-mono text-xs bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded" x-text="prData?.pr?.head?.ref"></span>
                            </p>
                        </div>
                        <button @click="showPrModal = false" class="ml-4 p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Tabs -->
                <div class="border-b border-gray-200 dark:border-gray-700 px-6" x-data="{ prTab: 'description' }">
                    <nav class="-mb-px flex gap-6">
                        <button @click="prTab = 'description'" 
                                :class="prTab === 'description' ? 'border-black text-black dark:border-white dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Description
                        </button>
                        <button @click="prTab = 'files'" 
                                :class="prTab === 'files' ? 'border-black text-black dark:border-white dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Files Changed
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-200 dark:bg-gray-700" x-text="prData?.pr?.changed_files || 0"></span>
                        </button>
                        <button @click="prTab = 'comments'" 
                                :class="prTab === 'comments' ? 'border-black text-black dark:border-white dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Comments
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-200 dark:bg-gray-700" x-text="prData?.comments?.length || 0"></span>
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div class="p-6">
                    
                    <!-- Description Tab -->
                    <div x-show="prTab === 'description'" class="space-y-4">
                        <div class="prose dark:prose-invert max-w-none">
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap" x-text="prData?.pr?.body || 'No description provided.'"></p>
                            </div>
                        </div>

                        <!-- PR Stats -->
                        <div class="grid grid-cols-3 gap-4 mt-6">
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                                <div class="text-2xl font-bold text-green-700 dark:text-green-400" x-text="prData?.pr?.additions || 0"></div>
                                <div class="text-xs text-green-600 dark:text-green-500">Additions</div>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4">
                                <div class="text-2xl font-bold text-red-700 dark:text-red-400" x-text="prData?.pr?.deletions || 0"></div>
                                <div class="text-xs text-red-600 dark:text-red-500">Deletions</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                                <div class="text-2xl font-bold text-blue-700 dark:text-blue-400" x-text="prData?.pr?.changed_files || 0"></div>
                                <div class="text-xs text-blue-600 dark:text-blue-500">Files Changed</div>
                            </div>
                        </div>
                    </div>

                    <!-- Files Tab -->
                    <div x-show="prTab === 'files'" class="space-y-4">
                        <template x-for="file in prData?.files || []" :key="file.sha">
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                                <!-- File Header -->
                                <div class="bg-gray-50 dark:bg-gray-900/50 px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <span class="text-sm font-mono text-gray-900 dark:text-white" x-text="file.filename"></span>
                                        <span class="inline-flex items-center gap-1 text-xs">
                                            <span class="text-green-600 dark:text-green-400" x-text="'+' + file.additions"></span>
                                            <span class="text-red-600 dark:text-red-400" x-text="'-' + file.deletions"></span>
                                        </span>
                                    </div>
                                    <a :href="file.blob_url" target="_blank" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
                                        View on GitHub
                                    </a>
                                </div>
                                <!-- File Patch -->
                                <div class="bg-gray-900 dark:bg-gray-950 p-4 overflow-x-auto">
                                    <pre class="text-xs text-gray-300 font-mono whitespace-pre" x-text="file.patch || 'Binary file or no changes to display'"></pre>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Comments Tab -->
                    <div x-show="prTab === 'comments'" class="space-y-4">
                        <!-- Existing Comments -->
                        <div class="space-y-3">
                            <template x-for="comment in prData?.comments || []" :key="comment.id">
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start gap-3">
                                        <img :src="comment.user.avatar_url" :alt="comment.user.login" class="w-8 h-8 rounded-full">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-1">
                                                <span class="font-medium text-sm text-gray-900 dark:text-white" x-text="comment.user.login"></span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400" x-text="new Date(comment.created_at).toLocaleString()"></span>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap" x-text="comment.body"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="!prData?.comments || prData.comments.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No comments yet. Be the first to comment!
                            </div>
                        </div>

                        <!-- Add Comment Form -->
                        <div class="border-t border-gray-200 dark:border-gray-700 pt-4 mt-6">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">Add Comment or Review</h3>
                            <form @submit.prevent="submitPrComment" class="space-y-3">
                                <textarea 
                                    x-model="prComment"
                                    rows="4"
                                    placeholder="Leave a comment..."
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent resize-none text-sm"></textarea>
                                
                                <div class="flex items-center gap-3">
                                    <button type="submit" 
                                            :disabled="submittingComment || !prComment.trim()"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <span x-show="!submittingComment">Comment</span>
                                        <span x-show="submittingComment">Posting...</span>
                                    </button>
                                    
                                    <button type="button"
                                            @click="submitPrReview('APPROVE')"
                                            :disabled="submittingComment || !prComment.trim()"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Approve
                                    </button>
                                    
                                    <button type="button"
                                            @click="submitPrReview('REQUEST_CHANGES')"
                                            :disabled="submittingComment || !prComment.trim()"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Request Changes
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>

        </div>
    </div>
</div>

<?php /**PATH F:\Project\salary\resources\views/employees/partials/github-pr-modal.blade.php ENDPATH**/ ?>