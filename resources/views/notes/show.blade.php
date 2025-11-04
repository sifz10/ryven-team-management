<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('notes.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $note->title }}
                </h2>
            </div>
            <a href="{{ route('notes.edit', $note) }}" 
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-8">
                    <!-- Type Badge -->
                    <div class="mb-6">
                        <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium
                            @if($note->type == 'website_link') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                            @elseif($note->type == 'password') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                            @elseif($note->type == 'backup_code') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                            @elseif($note->type == 'text') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                            @else bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                            @endif">
                            @if($note->type == 'website_link') 🔗 Website Link
                            @elseif($note->type == 'password') 🔐 Password
                            @elseif($note->type == 'backup_code') 🔑 Backup Code
                            @elseif($note->type == 'text') 📝 Text Note
                            @else 📎 File
                            @endif
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                        {{ $note->title }}
                    </h1>

                    <!-- URL -->
                    @if($note->url)
                        <div class="mb-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Website URL:</label>
                            <a href="{{ $note->url }}" target="_blank" 
                                class="text-blue-600 dark:text-blue-400 hover:underline break-all">
                                {{ $note->url }}
                            </a>
                        </div>
                    @endif

                    <!-- File -->
                    @if($note->file_path)
                        <div class="mb-6 p-4 bg-purple-50 dark:bg-purple-900/20 rounded-lg">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Attached File:</label>
                            <a href="{{ Storage::url($note->file_path) }}" target="_blank"
                                class="inline-flex items-center text-purple-600 dark:text-purple-400 hover:underline">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                </svg>
                                Download File
                            </a>
                        </div>
                    @endif

                    <!-- Content -->
                    @if($note->content)
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Content:</label>
                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg whitespace-pre-wrap text-gray-900 dark:text-gray-100">
                                {{ $note->content }}
                            </div>
                        </div>
                    @endif

                    <!-- Reminder Info -->
                    @if($note->reminder_time)
                        <div class="mb-6 p-4 border-l-4 border-orange-500 bg-orange-50 dark:bg-orange-900/20">
                            <div class="flex items-start">
                                <svg class="w-6 h-6 text-orange-600 dark:text-orange-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path>
                                </svg>
                                <div class="flex-1">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">Reminder Set</h3>
                                    <p class="text-gray-700 dark:text-gray-300 mb-2">
                                        <span class="font-medium">Time:</span> {{ $note->reminder_time->format('M d, Y h:i A') }}
                                    </p>
                                    @if($note->recipients->count() > 0)
                                        <p class="text-gray-700 dark:text-gray-300">
                                            <span class="font-medium">Recipients:</span>
                                        </p>
                                        <ul class="mt-2 space-y-1">
                                            @foreach($note->recipients as $recipient)
                                                <li class="text-sm text-gray-600 dark:text-gray-400">• {{ $recipient->email }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                    @if($note->reminder_sent)
                                        <span class="inline-block mt-2 px-3 py-1 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full text-sm">
                                            ✓ Reminder Sent
                                        </span>
                                    @else
                                        <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 rounded-full text-sm">
                                            ⏳ Pending
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Metadata -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700 text-sm text-gray-500 dark:text-gray-400">
                        <p>Created: {{ $note->created_at->format('M d, Y h:i A') }}</p>
                        @if($note->updated_at != $note->created_at)
                            <p>Last updated: {{ $note->updated_at->format('M d, Y h:i A') }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
