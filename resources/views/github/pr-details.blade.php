<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('github.logs') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        Pull Request #{{ $pr['number'] }}
                    </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $repo['owner'] }}/{{ $repo['repo'] }}</p>
                </div>
            </div>
            
            <div class="flex items-center gap-3">
                @if($pr['merged'])
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        Merged
                    </span>
                @elseif($pr['state'] === 'open')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                        Open
                    </span>
                @else
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400">
                        <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                        </svg>
                        Closed
                    </span>
                @endif
                
                <a href="{{ $pr['html_url'] }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 transition-all shadow-sm text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                    </svg>
                    View on GitHub
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12" x-data="prDetailsPage()" x-init="init()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- PR Header Info -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $pr['title'] }}</h1>
                
                <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 mb-4">
                    <div class="flex items-center gap-2">
                        <img src="{{ $pr['user']['avatar_url'] }}" alt="{{ $pr['user']['login'] }}" class="w-6 h-6 rounded-full">
                        <span>{{ $pr['user']['login'] }}</span>
                    </div>
                    <span>•</span>
                    <span>opened on {{ \Carbon\Carbon::parse($pr['created_at'])->format('M d, Y') }}</span>
                    <span>•</span>
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7 3a1 1 0 000 2h6a1 1 0 100-2H7zM4 7a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM2 11a2 2 0 012-2h12a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4z"></path>
                        </svg>
                        {{ $pr['commits'] }} commits
                    </span>
                </div>

                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400 mb-6">
                    <span class="inline-flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.707 3.293a1 1 0 010 1.414L5.414 7H11a7 7 0 017 7v2a1 1 0 11-2 0v-2a5 5 0 00-5-5H5.414l2.293 2.293a1 1 0 11-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-mono bg-blue-100 dark:bg-blue-900/30 px-2 py-0.5 rounded">{{ $pr['head']['ref'] }}</span>
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-2 py-0.5 rounded">{{ $pr['base']['ref'] }}</span>
                    </span>
                </div>

                @if($pr['body'])
                    <div class="prose dark:prose-invert max-w-none">
                        <p class="text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $pr['body'] }}</p>
                    </div>
                @else
                    <p class="text-gray-500 dark:text-gray-400 italic">No description provided.</p>
                @endif

                <div class="grid grid-cols-3 gap-4 mt-6">
                    <div class="p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                        <div class="text-xs text-green-700 dark:text-green-400 uppercase font-medium">Additions</div>
                        <div class="text-2xl font-bold text-green-700 dark:text-green-400 mt-1">+{{ number_format($pr['additions']) }}</div>
                    </div>
                    <div class="p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                        <div class="text-xs text-red-700 dark:text-red-400 uppercase font-medium">Deletions</div>
                        <div class="text-2xl font-bold text-red-700 dark:text-red-400 mt-1">-{{ number_format($pr['deletions']) }}</div>
                    </div>
                    <div class="p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                        <div class="text-xs text-blue-700 dark:text-blue-400 uppercase font-medium">Files Changed</div>
                        <div class="text-2xl font-bold text-blue-700 dark:text-blue-400 mt-1">{{ number_format($pr['changed_files']) }}</div>
                    </div>
                </div>
            </div>

            <!-- Assignment Section -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    Assign to Team Member
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Assign as Reviewer -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Request Review From
                        </label>
                        <div class="flex gap-2">
                            <select x-model="selectedReviewer" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-full bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="">Select a reviewer...</option>
                                @foreach($employees as $employee)
                                    <option value="<?php echo e($employee->github_username); ?>">
                                        <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?> (@<?php echo e($employee->github_username); ?>)
                                    </option>
                                @endforeach
                            </select>
                            <button @click="assignReviewer()" :disabled="!selectedReviewer || assigning" 
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                                <svg x-show="!assigning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <svg x-show="assigning" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="assigning ? 'Requesting...' : 'Request Review'"></span>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Reviewer will be notified to review this PR</p>
                    </div>

                    <!-- Assign as Assignee -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Assign PR To
                        </label>
                        <div class="flex gap-2">
                            <select x-model="selectedAssignee" class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-full bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="">Select an assignee...</option>
                                @foreach($employees as $employee)
                                    <option value="<?php echo e($employee->github_username); ?>">
                                        <?php echo e($employee->first_name); ?> <?php echo e($employee->last_name); ?> (@<?php echo e($employee->github_username); ?>)
                                    </option>
                                @endforeach
                            </select>
                            <button @click="assignAssignee()" :disabled="!selectedAssignee || assigning" 
                                    class="inline-flex items-center gap-2 px-5 py-2 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed whitespace-nowrap">
                                <svg x-show="!assigning" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                                <svg x-show="assigning" class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="assigning ? 'Assigning...' : 'Assign'"></span>
                            </button>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Assignee is responsible for the PR</p>
                    </div>
                </div>

                <!-- Current Assignees/Reviewers -->
                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Current Reviewers -->
                        @if(!empty($pr['requested_reviewers']))
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Reviewers</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($pr['requested_reviewers'] as $reviewer)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 rounded-full text-xs">
                                            <img src="<?php echo e($reviewer['avatar_url']); ?>" alt="<?php echo e($reviewer['login']); ?>" class="w-4 h-4 rounded-full">
                                            <?php echo e($reviewer['login']); ?>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Current Assignees -->
                        @if(!empty($pr['assignees']))
                            <div>
                                <h4 class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assignees</h4>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($pr['assignees'] as $assignee)
                                        <span class="inline-flex items-center gap-2 px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 rounded-full text-xs">
                                            <img src="<?php echo e($assignee['avatar_url']); ?>" alt="<?php echo e($assignee['login']); ?>" class="w-4 h-4 rounded-full">
                                            <?php echo e($assignee['login']); ?>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tabs -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex px-6">
                        <button @click="activeTab = 'files'" 
                                :class="activeTab === 'files' ? 'border-black text-black dark:border-white dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition">
                            Files Changed
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-200 dark:bg-gray-700">{{ count($files) }}</span>
                        </button>
                        <button @click="activeTab = 'comments'" 
                                :class="activeTab === 'comments' ? 'border-black text-black dark:border-white dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300'"
                                class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition ml-8">
                            Comments
                            <span class="ml-2 px-2 py-0.5 rounded-full text-xs bg-gray-200 dark:bg-gray-700">{{ count($comments) }}</span>
                        </button>
                    </nav>
                </div>

                <!-- Files Changed Tab -->
                <div x-show="activeTab === 'files'" class="p-6 space-y-4">
                    @foreach($files as $file)
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                            <div class="flex items-center justify-between p-4 bg-gray-100 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $file['filename'] }}</span>
                                    <span class="px-2 py-0.5 text-xs rounded-full
                                        @if($file['status'] === 'added') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($file['status'] === 'removed') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @else bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @endif">
                                        {{ $file['status'] }}
                                    </span>
                                    <span class="text-xs text-green-600 dark:text-green-400">+{{ $file['additions'] }}</span>
                                    <span class="text-xs text-red-600 dark:text-red-400">-{{ $file['deletions'] }}</span>
                                </div>
                                <a href="{{ $file['blob_url'] }}" target="_blank" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">View File</a>
                            </div>
                            @if(isset($file['patch']))
                                <div class="p-4 overflow-x-auto">
                                    <pre class="text-xs font-mono text-gray-800 dark:text-gray-200 bg-gray-50 dark:bg-gray-900 p-3 rounded-md">{{ $file['patch'] }}</pre>
                                </div>
                            @else
                                <div class="p-4 text-sm text-gray-500 dark:text-gray-400">No diff available</div>
                            @endif
                        </div>
                    @endforeach
                </div>

                <!-- Comments Tab -->
                <div x-show="activeTab === 'comments'" class="p-6 space-y-6">
                    <!-- Existing Comments -->
                    @foreach($comments as $comment)
                        <div class="flex items-start gap-3 bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border border-gray-200 dark:border-gray-700">
                            <img src="{{ $comment['user']['avatar_url'] }}" alt="{{ $comment['user']['login'] }}" class="w-8 h-8 rounded-full flex-shrink-0">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $comment['user']['login'] }}</span>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($comment['created_at'])->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $comment['body'] }}</p>
                            </div>
                        </div>
                    @endforeach

                    @if(count($comments) === 0)
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            No comments yet. Be the first to comment!
                        </div>
                    @endif

                    <!-- Comment/Review Form -->
                    <div class="mt-8 p-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-sm">
                        <h4 class="font-semibold text-gray-900 dark:text-white mb-4">Leave a Review or Comment</h4>
                        <form @submit.prevent="submitComment">
                            <textarea x-model="commentBody" rows="4" placeholder="Leave a comment..."
                                      class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent resize-none text-sm"></textarea>
                            <div class="flex flex-col sm:flex-row items-center justify-end gap-3 mt-4">
                                <button type="submit" :disabled="submitting || !commentBody.trim()"
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                    <span x-text="submitting ? 'Posting...' : 'Comment'"></span>
                                </button>
                                <div class="flex items-center gap-3">
                                    <button type="button" @click="submitReview('APPROVE')" :disabled="submitting || !commentBody.trim()"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Approve
                                    </button>
                                    <button type="button" @click="submitReview('REQUEST_CHANGES')" :disabled="submitting || !commentBody.trim()"
                                            class="inline-flex items-center gap-2 px-5 py-2 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 transition-all font-medium text-sm disabled:opacity-50 disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        Request Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
    function prDetailsPage() {
        return {
            activeTab: 'files',
            commentBody: '',
            submitting: false,
            selectedReviewer: '',
            selectedAssignee: '',
            assigning: false,

            init() {
                console.log('PR Details page initialized');
            },
            
            async submitComment() {
                if (!this.commentBody.trim() || this.submitting) return;
                
                this.submitting = true;
                
                try {
                    const response = await fetch('{{ route('github.pr.comment', $log) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            body: this.commentBody
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.commentBody = '';
                        alert('Comment posted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Failed to post comment'));
                    }
                } catch (error) {
                    console.error('Error posting comment:', error);
                    alert('Failed to post comment. Please try again.');
                } finally {
                    this.submitting = false;
                }
            },
            
            async submitReview(event) {
                if (!this.commentBody.trim() || this.submitting) return;
                
                this.submitting = true;
                
                try {
                    const response = await fetch('{{ route('github.pr.review', $log) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            body: this.commentBody,
                            event: event
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        this.commentBody = '';
                        const eventText = event === 'APPROVE' ? 'approved' : 'requested changes for';
                        alert(`Successfully ${eventText} the pull request!`);
                        window.location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Failed to post review'));
                    }
                } catch (error) {
                    console.error('Error posting review:', error);
                    alert('Failed to post review. Please try again.');
                } finally {
                    this.submitting = false;
                }
            },

            async assignReviewer() {
                if (!this.selectedReviewer || this.assigning) return;
                
                this.assigning = true;
                
                try {
                    const response = await fetch('{{ route('github.pr.assign', $log) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            github_username: this.selectedReviewer,
                            type: 'reviewer'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('✅ Reviewer requested successfully! The page will reload to show the update.');
                        window.location.reload();
                    } else {
                        alert('❌ Error: ' + (data.error || 'Failed to request reviewer'));
                    }
                } catch (error) {
                    console.error('Error requesting reviewer:', error);
                    alert('❌ Failed to request reviewer. Please try again.');
                } finally {
                    this.assigning = false;
                }
            },

            async assignAssignee() {
                if (!this.selectedAssignee || this.assigning) return;
                
                this.assigning = true;
                
                try {
                    const response = await fetch('{{ route('github.pr.assign', $log) }}', {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            github_username: this.selectedAssignee,
                            type: 'assignee'
                        })
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        alert('✅ Assignee added successfully! The page will reload to show the update.');
                        window.location.reload();
                    } else {
                        alert('❌ Error: ' + (data.error || 'Failed to assign'));
                    }
                } catch (error) {
                    console.error('Error assigning:', error);
                    alert('❌ Failed to assign. Please try again.');
                } finally {
                    this.assigning = false;
                }
            }
        }
    }
    </script>
</x-app-layout>

