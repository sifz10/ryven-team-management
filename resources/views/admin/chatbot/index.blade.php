<x-app-layout>
<div class="p-6">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Chat Management</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Manage customer conversations</p>
        </div>
        <a href="{{ route('admin.chatbot.guide') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Installation Guide
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Conversations</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Pending</div>
            <div class="text-3xl font-bold text-yellow-600 mt-2">{{ $stats['pending'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Active</div>
            <div class="text-3xl font-bold text-blue-600 mt-2">{{ $stats['active'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 shadow">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Unread</div>
            <div class="text-3xl font-bold text-red-600 mt-2">{{ $stats['unread'] }}</div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white dark:bg-gray-800 rounded-lg p-4 mb-6 shadow">
        <form method="GET" class="flex gap-4 flex-wrap">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                <select name="status" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Widget</label>
                <select name="widget_id" class="px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white">
                    <option value="">All Widgets</option>
                    @foreach($widgets as $widget)
                        <option value="{{ $widget->id }}" {{ request('widget_id') == $widget->id ? 'selected' : '' }}>{{ $widget->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Filter</button>
                <a href="{{ route('admin.chatbot.index') }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-white rounded-lg">Clear</a>
            </div>
        </form>
    </div>

    <!-- Conversations List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-50 dark:bg-gray-700 border-b dark:border-gray-600">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Visitor</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Widget</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Assigned To</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Status</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Last Message</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y dark:divide-gray-700">
                @forelse($conversations as $conversation)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        <td class="px-6 py-4">
                            <div class="font-medium text-gray-900 dark:text-white">{{ $conversation->visitor_name }}</div>
                            <div class="text-sm text-gray-500">{{ $conversation->visitor_email }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $conversation->chatbotWidget->name }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $conversation->assignedEmployee ? $conversation->assignedEmployee->first_name . ' ' . $conversation->assignedEmployee->last_name : '—' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 text-xs font-semibold rounded-full
                                {{ $conversation->status === 'pending' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : '' }}
                                {{ $conversation->status === 'active' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300' : '' }}
                                {{ $conversation->status === 'closed' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/30 dark:text-gray-300' : '' }}
                            ">
                                {{ ucfirst($conversation->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                            {{ $conversation->last_message_at?->diffForHumans() ?? '—' }}
                        </td>
                        <td class="px-6 py-4 text-sm">
                            <a href="{{ route('admin.chatbot.show', $conversation) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 font-medium">View</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                            No conversations found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        @if($conversations->hasPages())
            <div class="px-6 py-4 border-t dark:border-gray-700">
                {{ $conversations->links() }}
            </div>
        @endif
    </div>
</div>
</x-app-layout>
