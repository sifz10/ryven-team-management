<div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 {{ $isPinned ? 'ring-2 ring-yellow-400 dark:ring-yellow-600' : '' }}">
    <div class="flex items-start gap-4">
        <!-- User Avatar -->
        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-700 dark:to-gray-900 flex items-center justify-center text-white font-bold flex-shrink-0">
            @if($message->user)
                {{ substr($message->user->first_name, 0, 1) }}{{ substr($message->user->last_name, 0, 1) }}
            @else
                ?
            @endif
        </div>

        <div class="flex-1 min-w-0">
            <!-- Header -->
            <div class="flex items-center justify-between mb-2">
                <div>
                    <h4 class="font-bold text-gray-900 dark:text-white">
                        {{ $message->user ? $message->user->first_name . ' ' . $message->user->last_name : 'Unknown User' }}
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $message->created_at->diffForHumans() }}</p>
                </div>

                <div class="flex items-center gap-2">
                    @if($isPinned)
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400 rounded-full text-xs font-semibold">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M16 12V4H17V2H7V4H8V12L6 14V16H11.2V22H12.8V16H18V14L16 12Z"/>
                            </svg>
                            Pinned
                        </span>
                    @endif

                    <!-- Actions Dropdown -->
                    <div x-data="{ open: false }" class="relative">
                        <button @click="open = !open" class="p-1 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 5v.01M12 12v.01M12 19v.01M12 6a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2zm0 7a1 1 0 110-2 1 1 0 010 2z"></path>
                            </svg>
                        </button>

                        <div x-show="open"
                             @click.away="open = false"
                             x-transition
                             class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 py-1 z-10">
                            <form action="{{ route('projects.discussions.toggle-pin', [$project, $message]) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                    </svg>
                                    {{ $message->is_pinned ? 'Unpin' : 'Pin' }} Message
                                </button>
                            </form>
                            <button @click="openReply({{ $message->id }}); open = false" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                </svg>
                                Reply
                            </button>
                            <form action="{{ route('projects.discussions.destroy', [$project, $message]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this message?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Message Content -->
            <div class="prose prose-sm dark:prose-invert max-w-none">
                <p class="text-gray-900 dark:text-gray-200 whitespace-pre-wrap">{{ $message->message }}</p>
            </div>

            <!-- Mentions -->
            @if($message->mentions && count(json_decode($message->mentions, true) ?? []) > 0)
                <div class="flex items-center gap-2 mt-3 flex-wrap">
                    <span class="text-xs text-gray-500 dark:text-gray-400">Mentioned:</span>
                    @foreach(json_decode($message->mentions, true) as $mentionId)
                        @php
                            $mentionedUser = \App\Models\Employee::find($mentionId);
                        @endphp
                        @if($mentionedUser)
                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-xs font-semibold">
                                @{{ $mentionedUser->first_name }} {{ $mentionedUser->last_name }}
                            </span>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Replies -->
            @if($message->replies->count() > 0)
                <div class="mt-4 space-y-3 pl-6 border-l-2 border-gray-300 dark:border-gray-600">
                    @foreach($message->replies as $reply)
                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4">
                            <div class="flex items-start gap-3">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 dark:from-gray-600 dark:to-gray-800 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                    @if($reply->user)
                                        {{ substr($reply->user->first_name, 0, 1) }}{{ substr($reply->user->last_name, 0, 1) }}
                                    @else
                                        ?
                                    @endif
                                </div>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <h5 class="font-semibold text-sm text-gray-900 dark:text-white">
                                            {{ $reply->user ? $reply->user->first_name . ' ' . $reply->user->last_name : 'Unknown User' }}
                                        </h5>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ $reply->created_at->diffForHumans() }}</p>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $reply->message }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Reply Button -->
            <button @click="openReply({{ $message->id }})" class="mt-3 inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                </svg>
                Reply
            </button>
        </div>
    </div>
</div>
