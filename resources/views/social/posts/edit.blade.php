<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Edit Social Media Post') }}
            </h2>
            <a href="{{ route('social.posts.index') }}" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                {{ __('Cancel') }}
            </a>
        </div>
    </x-slot>

    <div class="py-6" x-data="postEditor()">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('social.posts.update', $socialPost) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Post Information</h3>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Post Title / Topic <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" required 
                                value="{{ old('title', $socialPost->title) }}"
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
                            <textarea name="description" id="description" rows="3"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600"
                                placeholder="Provide additional context or key points you want to include...">{{ old('description', $socialPost->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Social Account -->
                        <div class="mb-4">
                            <label for="social_account_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Social Media Account
                            </label>
                            <select name="social_account_id" id="social_account_id"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                <option value="">Select account (optional)</option>
                                @foreach($accounts as $account)
                                    <option value="{{ $account->id }}" {{ old('social_account_id', $socialPost->social_account_id) == $account->id ? 'selected' : '' }}>
                                        {{ $account->platform_name }} - {{ $account->platform_username }}
                                    </option>
                                @endforeach
                            </select>
                            @error('social_account_id')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Content -->
                        <div class="mb-4">
                            <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Post Content
                            </label>
                            <textarea name="content" id="content" rows="8"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600"
                                placeholder="Write your post content here...">{{ old('content', $socialPost->content) }}</textarea>
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
                                    <input type="radio" name="status" value="draft" 
                                        {{ old('status', $socialPost->status) === 'draft' ? 'checked' : '' }}
                                        class="border-gray-300 dark:border-gray-700 text-black dark:text-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Save as Draft</span>
                                </label>
                                <label class="flex items-center gap-3">
                                    <input type="radio" name="status" value="scheduled" x-model="status"
                                        {{ old('status', $socialPost->status) === 'scheduled' ? 'checked' : '' }}
                                        class="border-gray-300 dark:border-gray-700 text-black dark:text-gray-600 focus:ring-black dark:focus:ring-gray-600">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Schedule for Later</span>
                                </label>
                            </div>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Scheduled Date/Time -->
                        <div x-show="status === 'scheduled'" class="mb-4">
                            <label for="scheduled_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Schedule Date & Time
                            </label>
                            <input type="datetime-local" name="scheduled_at" id="scheduled_at"
                                value="{{ old('scheduled_at', $socialPost->scheduled_at ? $socialPost->scheduled_at->format('Y-m-d\TH:i') : '') }}"
                                :min="new Date().toISOString().slice(0, 16)"
                                class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-black dark:focus:border-gray-600 focus:ring-black dark:focus:ring-gray-600">
                            @error('scheduled_at')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($socialPost->posted_at)
                            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                                <div class="flex items-center gap-2">
                                    <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    <span class="text-sm font-medium text-green-800 dark:text-green-200">
                                        Posted on {{ $socialPost->posted_at->format('M d, Y \a\t h:i A') }}
                                    </span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- AI Generations -->
                @if($socialPost->generations && $socialPost->generations->count() > 0)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg mb-6">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">AI Generated Versions</h3>
                            <div class="space-y-4">
                                @foreach($socialPost->generations as $generation)
                                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                        <div class="flex items-start justify-between gap-3 mb-2">
                                            <span class="text-xs font-medium text-gray-500 dark:text-gray-400">
                                                Generated {{ $generation->created_at->diffForHumans() }}
                                            </span>
                                            @if($generation->is_selected)
                                                <span class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    Selected
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap">{{ $generation->content }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="flex items-center justify-end gap-3">
                    <a href="{{ route('social.posts.index') }}" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-lg font-semibold text-xs text-gray-800 dark:text-gray-200 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-gray-900 border border-transparent rounded-lg font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-700 active:bg-gray-900 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-black focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Post
                    </button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        function postEditor() {
            return {
                status: '{{ old('status', $socialPost->status) }}'
            };
        }
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
