<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-full transition-all shadow-md" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </a>
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Edit Project') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update project and client information</p>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl">
                <form action="{{ route('projects.update', $project) }}" method="POST" class="p-8 space-y-8">
                    @csrf
                    @method('PUT')

                    <!-- Project Information -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Project Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Project Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="name" name="name" value="{{ old('name', $project->name) }}" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                                <textarea id="description" name="description" rows="4"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">{{ old('description', $project->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Status <span class="text-red-500">*</span>
                                </label>
                                <select id="status" name="status" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="on-hold" {{ old('status', $project->status) === 'on-hold' ? 'selected' : '' }}>On Hold</option>
                                    <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                                @error('status')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="priority" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Priority <span class="text-red-500">*</span>
                                </label>
                                <select id="priority" name="priority" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="1" {{ old('priority', $project->priority) == 1 ? 'selected' : '' }}>Low</option>
                                    <option value="2" {{ old('priority', 2) == 2 ? 'selected' : '' }}>Medium</option>
                                    <option value="3" {{ old('priority', $project->priority) == 3 ? 'selected' : '' }}>High</option>
                                    <option value="4" {{ old('priority', $project->priority) == 4 ? 'selected' : '' }}>Critical</option>
                                </select>
                                @error('priority')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $project->start_date) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                                <input type="date" id="end_date" name="end_date" value="{{ old('end_date', $project->end_date) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="budget" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Budget</label>
                                <input type="number" id="budget" name="budget" value="{{ old('budget', $project->budget) }}" step="0.01" min="0"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('budget')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="currency" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Currency <span class="text-red-500">*</span>
                                </label>
                                <select id="currency" name="currency" required
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                    <option value="USD" {{ old('currency', 'USD') === 'USD' ? 'selected' : '' }}>USD</option>
                                    <option value="BDT" {{ old('currency', $project->currency) === 'BDT' ? 'selected' : '' }}>BDT</option>
                                    <option value="EUR" {{ old('currency', $project->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                                    <option value="GBP" {{ old('currency', $project->currency) === 'GBP' ? 'selected' : '' }}>GBP</option>
                                </select>
                                @error('currency')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Client Information -->
                    <div class="border-t border-gray-200 dark:border-gray-700 pt-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Client Information
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="client_name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Client Name</label>
                                <input type="text" id="client_name" name="client_name" value="{{ old('client_name', $project->client_name) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('client_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_company" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Company</label>
                                <input type="text" id="client_company" name="client_company" value="{{ old('client_company', $project->client_company) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('client_company')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email</label>
                                <input type="email" id="client_email" name="client_email" value="{{ old('client_email', $project->client_email) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('client_email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="client_phone" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Phone</label>
                                <input type="text" id="client_phone" name="client_phone" value="{{ old('client_phone', $project->client_phone) }}"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">
                                @error('client_phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="client_address" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Address</label>
                                <textarea id="client_address" name="client_address" rows="3"
                                    class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-gray-500 focus:border-black dark:focus:border-gray-500 dark:bg-gray-700 dark:text-gray-100">{{ old('client_address', $project->client_address) }}</textarea>
                                @error('client_address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex items-center gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all font-semibold shadow-md" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Update Project
                        </button>
                        <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-white rounded-full transition-all font-semibold shadow-md" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
