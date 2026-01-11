<x-app-layout>
<div class="space-y-6 pb-8">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Chat Widgets</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage your installed chat widgets</p>
        </div>
        <a href="{{ route('admin.chatbot.widgets.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Create Widget
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Widgets</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Active</div>
            <div class="text-3xl font-bold text-green-600 mt-2">{{ $stats['active'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Inactive</div>
            <div class="text-3xl font-bold text-gray-600 mt-2">{{ $stats['inactive'] }}</div>
        </div>
    </div>

    <!-- Widgets Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($widgets as $widget)
            <a href="{{ route('admin.chatbot.widgets.show', $widget) }}" class="block group">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition-shadow h-full p-6">
                    <!-- Header -->
                    <div class="flex items-start justify-between mb-4">
                        <div class="flex-1">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $widget->name }}</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $widget->domain }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap ml-2
                            {{ $widget->is_active ? 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300' : 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' }}">
                            {{ $widget->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Welcome Message -->
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 line-clamp-2">{{ $widget->welcome_message }}</p>

                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-3 py-4 border-t border-gray-200 dark:border-gray-700">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Conversations</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $widget->conversations_count ?? 0 }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Created</p>
                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ $widget->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Token Preview -->
                    <div class="mt-4 bg-gray-50 dark:bg-gray-900/50 rounded p-3">
                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Token</p>
                        <p class="text-xs font-mono text-gray-700 dark:text-gray-300 break-all">{{ substr($widget->api_token, 0, 20) }}...</p>
                    </div>

                    <!-- Actions -->
                    <div class="mt-4 flex gap-2">
                        <a href="{{ route('admin.chatbot.widgets.edit', $widget) }}" class="flex-1 px-3 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded text-sm font-medium hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors text-center">
                            Edit
                        </a>
                        <form action="{{ route('admin.chatbot.widgets.destroy', $widget) }}" method="POST" class="flex-1" onsubmit="return confirm('Delete this widget?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full px-3 py-2 bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 rounded text-sm font-medium hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </a>
        @empty
            <!-- Empty State -->
            <div class="col-span-full">
                <div class="bg-white dark:bg-gray-800 rounded-lg p-12 text-center shadow">
                    <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No widgets yet</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first widget to start accepting customer messages</p>
                    <a href="{{ route('admin.chatbot.widgets.create') }}" class="inline-block px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        Create First Widget
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($widgets->hasPages())
        <div class="flex justify-center mt-8">
            {{ $widgets->links() }}
        </div>
    @endif
</div>
</x-app-layout>