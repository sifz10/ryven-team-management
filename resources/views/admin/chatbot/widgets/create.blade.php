<x-app-layout>
<div class="max-w-2xl mx-auto pb-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="{{ route('admin.chatbot.widgets.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Widgets
        </a>
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Create New Widget</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">Set up a new chat widget for your application</p>
    </div>

    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8">
        <form action="{{ route('admin.chatbot.widgets.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Widget Name -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Widget Name <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       placeholder="e.g., My Website Chat" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                       value="{{ old('name') }}" 
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">A friendly name to identify this widget</p>
            </div>

            <!-- Domain -->
            <div>
                <label for="domain" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Domain <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       id="domain" 
                       name="domain" 
                       placeholder="e.g., mywebsite.com" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('domain') border-red-500 @enderror"
                       value="{{ old('domain') }}" 
                       required>
                @error('domain')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">The domain where this widget is installed</p>
            </div>

            <!-- Welcome Message -->
            <div>
                <label for="welcome_message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Welcome Message <span class="text-red-500">*</span>
                </label>
                <textarea id="welcome_message" 
                          name="welcome_message" 
                          rows="3" 
                          placeholder="e.g., Hi! How can we help you today?" 
                          class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('welcome_message') border-red-500 @enderror"
                          required>{{ old('welcome_message') }}</textarea>
                @error('welcome_message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">The message shown to visitors when they open the chat</p>
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', true) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Activate this widget immediately</span>
                </label>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 ml-7">Visitors will see the chat widget on the installed page</p>
            </div>

            <!-- Help Text -->
            <div class="bg-blue-50 dark:bg-blue-900/30 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mt-8">
                <h3 class="font-semibold text-blue-900 dark:text-blue-300 mb-2">💡 What's a Widget?</h3>
                <p class="text-sm text-blue-800 dark:text-blue-200">A widget is a chat bubble that you install on your website or app. Visitors can click it to send messages directly to your team. You'll need a unique widget for each website/domain.</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.chatbot.widgets.index') }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Create Widget
                </button>
            </div>
        </form>
    </div>
</div>
</x-app-layout>