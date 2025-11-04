<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Personal Notes') }}
            </h2>
            <a href="{{ route('notes.create') }}" class="inline-flex items-center px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 hover:shadow-xl transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                New Note
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-4 px-4 py-3 rounded-lg bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filters -->
            <div class="mb-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                <form method="GET" action="{{ route('notes.index') }}" class="flex flex-wrap gap-4">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text" name="search" value="{{ request('search') }}" 
                            placeholder="Search notes..." 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900">
                    </div>
                    <div class="min-w-[180px]">
                        <select name="type" 
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 focus:border-blue-500 focus:ring focus:ring-blue-200 dark:focus:ring-blue-900">
                            <option value="">All Types</option>
                            <option value="website_link" {{ request('type') == 'website_link' ? 'selected' : '' }}>🔗 Website Link</option>
                            <option value="password" {{ request('type') == 'password' ? 'selected' : '' }}>🔐 Password</option>
                            <option value="backup_code" {{ request('type') == 'backup_code' ? 'selected' : '' }}>🔑 Backup Code</option>
                            <option value="text" {{ request('type') == 'text' ? 'selected' : '' }}>📝 Text Note</option>
                            <option value="file" {{ request('type') == 'file' ? 'selected' : '' }}>📎 File</option>
                        </select>
                    </div>
                    <button type="submit" 
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'type']))
                        <a href="{{ route('notes.index') }}" 
                            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl">
                <div class="p-6">
                    @if($notes->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($notes as $note)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-600 hover:shadow-lg transition">
                                    <!-- Type Badge -->
                                    <div class="flex items-center justify-between mb-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($note->type == 'website_link') bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200
                                            @elseif($note->type == 'password') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                            @elseif($note->type == 'backup_code') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                            @elseif($note->type == 'text') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                            @else bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200
                                            @endif">
                                            @if($note->type == 'website_link') 🔗 Link
                                            @elseif($note->type == 'password') 🔐 Password
                                            @elseif($note->type == 'backup_code') 🔑 Backup Code
                                            @elseif($note->type == 'text') 📝 Text
                                            @else 📎 File
                                            @endif
                                        </span>
                                        
                                        @if($note->reminder_time && !$note->reminder_sent)
                                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs bg-orange-100 dark:bg-orange-900 text-orange-800 dark:text-orange-200">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z"></path>
                                                </svg>
                                                Reminder
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Title -->
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2 line-clamp-2">
                                        {{ $note->title }}
                                    </h3>

                                    <!-- Content Preview -->
                                    @if($note->content)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-3">
                                            {{ $note->content }}
                                        </p>
                                    @endif

                                    <!-- URL Preview -->
                                    @if($note->url)
                                        <a href="{{ $note->url }}" target="_blank" 
                                            class="text-sm text-blue-600 dark:text-blue-400 hover:underline mb-4 block truncate">
                                            {{ $note->url }}
                                        </a>
                                    @endif

                                    <!-- File Preview -->
                                    @if($note->file_path)
                                        <div class="mb-4">
                                            <a href="{{ Storage::url($note->file_path) }}" target="_blank"
                                                class="inline-flex items-center text-sm text-blue-600 dark:text-blue-400 hover:underline">
                                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8 4a3 3 0 00-3 3v4a5 5 0 0010 0V7a1 1 0 112 0v4a7 7 0 11-14 0V7a5 5 0 0110 0v4a3 3 0 11-6 0V7a1 1 0 012 0v4a1 1 0 102 0V7a3 3 0 00-3-3z" clip-rule="evenodd"></path>
                                                </svg>
                                                View File
                                            </a>
                                        </div>
                                    @endif

                                    <!-- Reminder Info -->
                                    @if($note->reminder_time)
                                        <div class="mb-4 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="font-medium">Reminder:</span> {{ $note->reminder_time->format('M d, Y h:i A') }}
                                            @if($note->recipients->count() > 0)
                                                <br>
                                                <span class="font-medium">To:</span> {{ $note->recipients->pluck('email')->join(', ') }}
                                            @endif
                                        </div>
                                    @endif

                                    <!-- Actions -->
                                    <div class="flex gap-2 mt-4">
                                        <a href="{{ route('notes.show', $note) }}" 
                                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            View
                                        </a>
                                        <a href="{{ route('notes.edit', $note) }}" 
                                            class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-medium hover:bg-gray-800 dark:hover:bg-gray-200 transition shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        <form action="{{ route('notes.destroy', $note) }}" method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this note?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="inline-flex items-center justify-center gap-2 px-3 py-2 bg-red-600 dark:bg-red-500 text-white rounded-full text-sm font-medium hover:bg-red-700 dark:hover:bg-red-600 transition shadow-sm">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $notes->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No notes</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new note.</p>
                            <div class="mt-6">
                                <a href="{{ route('notes.create') }}" class="inline-flex items-center px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800 transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    New Note
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
