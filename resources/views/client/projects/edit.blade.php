<x-client-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('client.projects.show', $project) }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Project</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Warning Banner for Limitations -->
            <div class="mb-6 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-2xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <div class="text-sm text-yellow-800 dark:text-yellow-200">
                        <p class="font-medium mb-1">Limited Editing</p>
                        <p>You can edit project name, description, dates, and budget. Project status, priority, and team assignments are managed by the admin.</p>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('client.projects.update', $project) }}" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-6">
                    <!-- Project Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Project Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $project->name) }}" required
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all"
                            placeholder="Enter project name">
                        @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="5"
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all"
                            placeholder="Describe your project, its goals, and requirements">{{ old('description', $project->description) }}</textarea>
                        @error('description')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date Range -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Start Date
                            </label>
                            <input type="date" name="start_date" id="start_date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all">
                            @error('start_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                End Date
                            </label>
                            <input type="date" name="end_date" id="end_date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}"
                                class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all">
                            @error('end_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Budget -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="budget" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Budget
                            </label>
                            <input type="number" name="budget" id="budget" value="{{ old('budget', $project->budget) }}" step="0.01" min="0"
                                class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all"
                                placeholder="0.00">
                            @error('budget')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="currency" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Currency
                            </label>
                            <select name="currency" id="currency"
                                class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent transition-all">
                                <option value="USD" {{ old('currency', $project->currency) == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="BDT" {{ old('currency', $project->currency) == 'BDT' ? 'selected' : '' }}>BDT - Bangladeshi Taka</option>
                                <option value="EUR" {{ old('currency', $project->currency) == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ old('currency', $project->currency) == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="INR" {{ old('currency', $project->currency) == 'INR' ? 'selected' : '' }}>INR - Indian Rupee</option>
                            </select>
                            @error('currency')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Read-Only Fields Display -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                        <p class="text-sm font-medium text-gray-900 dark:text-white mb-3">Admin-Controlled Fields</p>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1">Status</p>
                                <span class="inline-flex px-3 py-1 rounded-full text-xs font-medium
                                    {{ $project->status === 'active' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : '' }}
                                    {{ $project->status === 'planning' ? 'bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300' : '' }}
                                    {{ $project->status === 'on_hold' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : '' }}
                                    {{ $project->status === 'completed' ? 'bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300' : '' }}
                                    {{ $project->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : '' }}">
                                    {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                                </span>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1">Priority</p>
                                <p class="text-gray-900 dark:text-white font-medium">Priority {{ $project->priority }}</p>
                            </div>
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-1">Project Manager</p>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $project->projectManager->name ?? 'Not assigned' }}</p>
                            </div>
                        </div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mt-3">
                            These fields can only be modified by the admin team.
                        </p>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700 rounded-b-2xl flex items-center justify-between">
                    <a href="{{ route('client.projects.show', $project) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-gray-700 dark:text-gray-300 font-medium rounded-full hover:bg-gray-200 dark:hover:bg-gray-800 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Update Project
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-client-app-layout>
