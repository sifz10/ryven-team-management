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
        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Edit Widget</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2">{{ $widget->name }}</p>
    </div>

    <!-- Main Form -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 mb-6">
        <form action="{{ route('admin.chatbot.widgets.update', $widget) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

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
                       value="{{ old('name', $widget->name) }}" 
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                       value="{{ old('domain', $widget->domain) }}" 
                       required>
                @error('domain')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                          required>{{ old('welcome_message', $widget->welcome_message) }}</textarea>
                @error('welcome_message')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Installed In -->
            <div>
                <label for="installed_in" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Installed In (Optional)
                </label>
                <input type="text" 
                       id="installed_in" 
                       name="installed_in" 
                       placeholder="e.g., WordPress, Shopify, Custom" 
                       class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                       value="{{ old('installed_in', $widget->installed_in) }}">
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">The platform or CMS where this widget is installed</p>
            </div>

            <!-- Status -->
            <div>
                <label for="is_active" class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" 
                           id="is_active" 
                           name="is_active" 
                           value="1" 
                           {{ old('is_active', $widget->is_active) ? 'checked' : '' }}
                           class="w-4 h-4 text-blue-600 rounded focus:ring-2 focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Widget is Active</span>
                </label>
                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2 ml-7">When disabled, the widget won't appear for visitors</p>
            </div>

            <!-- Actions -->
            <div class="flex gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                <a href="{{ route('admin.chatbot.widgets.show', $widget) }}" class="px-6 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                    Cancel
                </a>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    Save Changes
                </button>
            </div>
        </form>
    </div>

    <!-- Token Section -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-8 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">API Token</h2>
        
        <div class="space-y-4">
            <p class="text-gray-600 dark:text-gray-400">Use this token to install the widget on your website or app.</p>
            
            <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                <p class="text-xs text-gray-500 dark:text-gray-400 mb-2">Your Token</p>
                <div class="flex gap-2">
                    <input type="text" 
                           readonly 
                           value="{{ $widget->api_token }}" 
                           class="flex-1 px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded text-sm font-mono text-gray-700 dark:text-gray-300">
                    <button type="button" 
                            onclick="navigator.clipboard.writeText('{{ $widget->api_token }}'); this.textContent = 'Copied!'; setTimeout(() => this.textContent = 'Copy', 2000);"
                            class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded hover:bg-gray-300 dark:hover:bg-gray-600 transition-colors text-sm font-medium">
                        Copy
                    </button>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/30 border border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                <p class="text-sm text-yellow-800 dark:text-yellow-200">
                    <strong>⚠️ Keep this token secret!</strong> Anyone with this token can send messages as your widget. Store it securely in your website's configuration.
                </p>
            </div>

            <form action="{{ route('admin.chatbot.widgets.rotate-token', $widget) }}" method="POST" onsubmit="return confirm('Rotate token? The old token will stop working.');">
                @csrf
                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium">
                    🔄 Rotate Token
                </button>
            </form>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-2 gap-6 mb-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Conversations</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['conversations'] }}</div>
        </div>
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Total Messages</div>
            <div class="text-3xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['messages'] }}</div>
        </div>
    </div>

    <!-- Danger Zone -->
    <div class="bg-red-50 dark:bg-red-900/30 border-2 border-red-200 dark:border-red-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-red-900 dark:text-red-300 mb-2">Danger Zone</h3>
        
        @if($stats['conversations'] > 0)
            <p class="text-red-800 dark:text-red-200 text-sm mb-4">This widget has {{ $stats['conversations'] }} conversation(s). You must delete or archive these conversations before removing the widget.</p>
            <button type="button" disabled class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-500 dark:text-gray-400 rounded-lg cursor-not-allowed text-sm font-medium">
                Cannot Delete (Has Conversations)
            </button>
        @else
            <p class="text-red-800 dark:text-red-200 text-sm mb-4">Delete this widget permanently. This action cannot be undone.</p>
            <form action="{{ route('admin.chatbot.widgets.destroy', $widget) }}" method="POST" onsubmit="return confirm('Are you absolutely sure? This cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    🗑️ Delete Widget
                </button>
            </form>
        @endif
    </div>
</div>
</x-app-layout>