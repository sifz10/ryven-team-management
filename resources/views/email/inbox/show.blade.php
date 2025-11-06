<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-5xl mx-auto px-6">
            <!-- Back Button -->
            <div class="mb-6">
                <a href="{{ route('email.inbox.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Inbox
                </a>
            </div>

            <!-- Email Container -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl overflow-hidden">
                <!-- Header -->
                <div class="p-8 border-b border-gray-800">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex-1">
                            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-4">{{ $message->subject ?: '(No Subject)' }}</h1>
                            <div class="flex items-center gap-4 text-sm">
                                <div>
                                    <span class="text-gray-600 dark:text-gray-600 dark:text-gray-600 dark:text-gray-400">From:</span>
                                    <span class="text-gray-900 dark:text-white font-semibold ml-2">{{ $message->from_name }} &lt;{{ $message->from_email }}&gt;</span>
                                </div>
                                <span class="text-gray-600">•</span>
                                <span class="text-gray-600 dark:text-gray-600 dark:text-gray-400">{{ $message->sent_at->format('M d, Y \a\t g:i A') }}</span>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2 ml-6">
                            <form action="{{ route('email.inbox.star', $message) }}" method="POST">
                                @csrf
                                <button class="p-3 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-full transition">
                                    <svg class="w-5 h-5 {{ $message->is_starred ? 'fill-yellow-500 text-yellow-500' : 'text-gray-600 dark:text-gray-400' }}" fill="{{ $message->is_starred ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </button>
                            </form>

                            @if($message->folder === 'Trash')
                                <form action="{{ route('email.inbox.restore', $message) }}" method="POST">
                                    @csrf
                                    <button class="inline-flex items-center px-6 py-3 bg-green-600 text-white rounded-full font-semibold hover:bg-green-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                        </svg>
                                        Restore
                                    </button>
                                </form>

                                <form action="{{ route('email.inbox.destroy', $message) }}" method="POST" onsubmit="return confirm('Permanently delete this email?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="inline-flex items-center px-6 py-3 bg-red-600 text-white rounded-full font-semibold hover:bg-red-700 transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete Forever
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('email.inbox.trash', $message) }}" method="POST">
                                    @csrf
                                    <button class="p-3 bg-red-100 dark:bg-red-900/30 hover:bg-red-200 dark:hover:bg-red-900/50 border border-red-200 dark:border-red-800 rounded-full transition">
                                        <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>

                                <a href="{{ route('email.inbox.reply', $message) }}" class="inline-flex items-center px-6 py-3 bg-black text-white rounded-full font-semibold hover:bg-gray-800 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                                    </svg>
                                    Reply
                                </a>
                            @endif
                        </div>
                    </div>

                    @if($message->to)
                        <div class="text-sm">
                            <span class="text-gray-500">To:</span>
                            <span class="text-gray-600 dark:text-gray-400 ml-2">
                                @foreach($message->to as $recipient)
                                    {{ $recipient['name'] ?: $recipient['email'] }}{{ !$loop->last ? ', ' : '' }}
                                @endforeach
                            </span>
                        </div>
                    @endif
                </div>

                <!-- Conversation Thread -->
                @php
                    $thread = $message->thread();
                    $isConversation = $thread->count() > 1;
                @endphp

                @if($isConversation)
                    <div class="p-8 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                Conversation ({{ $thread->count() }} messages)
                            </span>
                        </div>
                        
                        <div class="space-y-4">
                            @foreach($thread as $threadMessage)
                                <div class="pl-4 border-l-2 {{ $threadMessage->id === $message->id ? 'border-black dark:border-white' : 'border-gray-300 dark:border-gray-600' }}">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $threadMessage->from_name ?: $threadMessage->from_email }}
                                        </span>
                                        @if($threadMessage->id === $message->id)
                                            <span class="px-2 py-0.5 bg-black text-white text-xs font-semibold rounded-full">Current</span>
                                        @endif
                                        <span class="text-xs text-gray-500 dark:text-gray-400 ml-auto">
                                            {{ $threadMessage->sent_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    @if($threadMessage->id !== $message->id)
                                        <a href="{{ route('email.inbox.show', $threadMessage) }}" 
                                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                            {{ $threadMessage->subject ?: '(No Subject)' }}
                                        </a>
                                    @else
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ $threadMessage->subject ?: '(No Subject)' }}
                                        </span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Body -->
                <div class="p-8">
                    <div class="prose prose-lg dark:prose-invert max-w-none">
                        {!! $message->body_html ?: nl2br(e($message->body_text)) !!}
                    </div>
                </div>

                <!-- Attachments -->
                @if($message->attachments->count() > 0)
                    <div class="p-8 border-t border-gray-800">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Attachments ({{ $message->attachments->count() }})</h3>
                        <div class="grid gap-3 md:grid-cols-2">
                            @foreach($message->attachments as $attachment)
                                <a href="{{ route('email.attachments.download', $attachment->id) }}" 
                                    class="flex items-center p-4 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-2xl transition">
                                    <svg class="w-8 h-8 text-gray-600 dark:text-gray-400 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-gray-900 dark:text-white font-semibold truncate">{{ $attachment->filename }}</p>
                                        <p class="text-gray-500 text-sm">{{ $attachment->getFormattedSize() }}</p>
                                    </div>
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 ml-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
