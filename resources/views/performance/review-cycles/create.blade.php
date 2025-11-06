<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ __('Create Review Cycle') }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Define a new performance review cycle for your team
                </p>
            </div>
            <a href="{{ route('review-cycles.index') }}" 
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-600 text-white rounded-full shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                <span>Back to Review Cycles</span>
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-4xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl dark:bg-gray-800">
                <form method="POST" action="{{ route('review-cycles.store') }}" class="p-6 space-y-6">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cycle Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="name" 
                               id="name" 
                               value="{{ old('name') }}"
                               required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500"
                               placeholder="e.g., Q1 2025 Performance Review">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type and Status Row -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <!-- Type -->
                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Cycle Type <span class="text-red-500">*</span>
                            </label>
                            <select name="type" 
                                    id="type" 
                                    required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500">
                                <option value="">Select type...</option>
                                <option value="quarterly" {{ old('type') == 'quarterly' ? 'selected' : '' }}>Quarterly Review</option>
                                <option value="annual" {{ old('type') == 'annual' ? 'selected' : '' }}>Annual Review</option>
                                <option value="mid_year" {{ old('type') == 'mid_year' ? 'selected' : '' }}>Mid-Year Review</option>
                                <option value="probation" {{ old('type') == 'probation' ? 'selected' : '' }}>Probation Review</option>
                                <option value="project_based" {{ old('type') == 'project_based' ? 'selected' : '' }}>Project-Based Review</option>
                            </select>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" 
                                    id="status" 
                                    required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500">
                                <option value="scheduled" {{ old('status', 'scheduled') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Dates Row -->
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <!-- Start Date -->
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Start Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="start_date" 
                                   id="start_date" 
                                   value="{{ old('start_date') }}"
                                   required
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                End Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="end_date" 
                                   id="end_date" 
                                   value="{{ old('end_date') }}"
                                   required
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Review Due Date -->
                        <div>
                            <label for="review_due_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Review Due Date <span class="text-red-500">*</span>
                            </label>
                            <input type="date" 
                                   name="review_due_date" 
                                   id="review_due_date" 
                                   value="{{ old('review_due_date') }}"
                                   required
                                   class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500">
                            @error('review_due_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                When should reviews be completed by?
                            </p>
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description" 
                                  id="description" 
                                  rows="4"
                                  class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-black focus:ring-black dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:border-gray-500 dark:focus:ring-gray-500"
                                  placeholder="Provide additional context or goals for this review cycle...">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('review-cycles.index') }}" 
                           class="inline-flex items-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full border border-gray-300 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                            <span>Cancel</span>
                        </a>
                        <button type="submit" 
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            <span>Create Review Cycle</span>
                        </button>
                    </div>
                </form>
            </div>

            <!-- Help Text -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-300">
                        <p class="font-medium mb-1">Tips for creating review cycles:</p>
                        <ul class="list-disc list-inside space-y-1 text-blue-700 dark:text-blue-400">
                            <li>Choose a descriptive name that includes the period (e.g., "Q1 2025 Performance Review")</li>
                            <li>The review due date should be after the cycle end date to allow time for completion</li>
                            <li>Start with "Scheduled" status, then activate when ready to begin</li>
                            <li>Add a description to provide context and expectations to reviewers</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
