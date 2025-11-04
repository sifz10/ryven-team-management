<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Social Media Post') }}
            </h2>
            <a href="{{ route('social.calendar') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ __('Cancel') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="postCreator()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if($accounts->count() === 0)
                <div class="bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-700 text-yellow-700 dark:text-yellow-300 px-4 py-3 rounded-lg mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 mt-0.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                        <div>
                            <strong>No social media accounts connected!</strong>
                            <p class="mt-1">Please <a href="{{ route('social.accounts.index') }}" class="underline font-semibold">connect your accounts</a> before creating posts.</p>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('social.posts.store') }}" method="POST">
                @csrf

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Post Information</h3>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Post Title / Topic <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" required x-model="title"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600"
                                placeholder="e.g., 5 Tips for Remote Team Management">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description / Context
                            </label>
                            <textarea name="description" id="description" rows="3" x-model="description"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600"
                                placeholder="Provide additional context or key points you want to include..."></textarea>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This will help AI generate better content</p>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Social Account -->
                        <div class="mb-4">
                            <label for="social_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Social Media Account
                            </label>
                            <select name="social_account_id" id="social_account_id" x-model="socialAccountId"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                <option value="">Select account (optional)</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->platform_name }} - {{ $account->platform_username }}</option>
                                @endforeach
                            </select>
                            @error('social_account_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Auto Generate Toggle -->
                        <div class="mb-4">
                            <label class="flex items-center gap-3">
                                <input type="checkbox" name="auto_generate" value="1" x-model="autoGenerate"
                                    class="rounded border-gray-300 dark:border-gray-700 text-black dark:text-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Generate AI-powered content automatically
                                </span>
                            </label>
                            <p class="ml-6 mt-1 text-sm text-gray-500 dark:text-gray-400">
                                AI will create a viral post with hook, problem understanding, value, and CTA
                            </p>
                        </div>

                        <!-- Manual Content (if not auto-generating) -->
                        <div x-show="!autoGenerate" x-cloak class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Post Content
                            </label>
                            <textarea name="content" id="content" rows="6"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600"
                                placeholder="Write your post content here..."></textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Scheduling -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Scheduling</h3>

                        <!-- Status -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Post Status <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center gap-3">
                                    <input type="radio" name="status" value="draft" checked
                                        class="border-gray-300 dark:border-gray-700 text-black dark:text-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Save as Draft</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input type="radio" name="status" value="scheduled" x-model="status"
                                        class="border-gray-300 dark:border-gray-700 text-black dark:text-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Schedule for Later</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Date/Time -->
                        <div x-show="status === 'scheduled'" x-cloak class="mb-4">
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Schedule Date & Time
                            </label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                :min="new Date().toISOString().slice(0, 16)"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600">
                            @error('scheduled_at')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('social.calendar') }}" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-700 active:bg-gray-900 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Create Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function postCreator() {
            return {
                title: '',
                description: '',
                socialAccountId: '',
                autoGenerate: false,
                status: 'draft'
            };
        }
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
