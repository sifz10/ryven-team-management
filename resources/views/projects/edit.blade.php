<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Edit Project
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Update project information and team members</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                    View
                </a>
                <a href="{{ route('projects.index') }}" class="inline-flex items-center gap-2 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
    <!-- Form -->
    <form action="{{ route('projects.update', $project) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <!-- Basic Information -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Basic Information</h3>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Client Selection -->
                <div class="lg:col-span-2">
                    <label for="client_id" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Client <span class="text-red-500">*</span>
                    </label>
                    <select name="client_id"
                            id="client_id"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('client_id') border-red-500 @enderror">
                        <option value="">Select a client</option>
                        @foreach($clients as $client)
                            <option value="{{ $client->id }}" {{ old('client_id', $project->client_id) == $client->id ? 'selected' : '' }}>
                                {{ $client->name }} @if($client->company)({{ $client->company }})@endif
                            </option>
                        @endforeach
                    </select>
                    @error('client_id')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project Name -->
                <div class="lg:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Project Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text"
                           name="name"
                           id="name"
                           value="{{ old('name', $project->name) }}"
                           required
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('name') border-red-500 @enderror">
                    @error('name')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="lg:col-span-2">
                    <label for="description" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Description
                    </label>
                    <textarea name="description"
                              id="description"
                              rows="4"
                              class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description', $project->description) }}</textarea>
                    @error('description')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                            id="status"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('status') border-red-500 @enderror">
                        <option value="planning" {{ old('status', $project->status) === 'planning' ? 'selected' : '' }}>Planning</option>
                        <option value="active" {{ old('status', $project->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="in_progress" {{ old('status', $project->status) === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="on_hold" {{ old('status', $project->status) === 'on_hold' ? 'selected' : '' }}>On Hold</option>
                        <option value="completed" {{ old('status', $project->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="cancelled" {{ old('status', $project->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Priority -->
                <div>
                    <label for="priority" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Priority <span class="text-red-500">*</span>
                    </label>
                    <select name="priority"
                            id="priority"
                            required
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('priority') border-red-500 @enderror">
                        <option value="1" {{ old('priority', $project->priority) == 1 ? 'selected' : '' }}>🔴 Critical</option>
                        <option value="2" {{ old('priority', $project->priority) == 2 ? 'selected' : '' }}>🟠 High</option>
                        <option value="3" {{ old('priority', $project->priority) == 3 ? 'selected' : '' }}>🟡 Medium</option>
                        <option value="4" {{ old('priority', $project->priority) == 4 ? 'selected' : '' }}>🟢 Low</option>
                    </select>
                    @error('priority')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Start Date -->
                <div>
                    <label for="start_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Start Date
                    </label>
                    <input type="date"
                           name="start_date"
                           id="start_date"
                           value="{{ old('start_date', $project->start_date) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('start_date') border-red-500 @enderror">
                    @error('start_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- End Date -->
                <div>
                    <label for="end_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        End Date
                    </label>
                    <input type="date"
                           name="end_date"
                           id="end_date"
                           value="{{ old('end_date', $project->end_date) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('end_date') border-red-500 @enderror">
                    @error('end_date')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Budget -->
                <div>
                    <label for="budget" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Budget
                    </label>
                    <input type="number"
                           name="budget"
                           id="budget"
                           step="0.01"
                           value="{{ old('budget', $project->budget) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('budget') border-red-500 @enderror">
                    @error('budget')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Currency -->
                <div>
                    <label for="currency" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Currency
                    </label>
                    <select name="currency"
                            id="currency"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('currency') border-red-500 @enderror">
                        <option value="USD" {{ old('currency', $project->currency) === 'USD' ? 'selected' : '' }}>USD</option>
                        <option value="BDT" {{ old('currency', $project->currency) === 'BDT' ? 'selected' : '' }}>BDT</option>
                        <option value="EUR" {{ old('currency', $project->currency) === 'EUR' ? 'selected' : '' }}>EUR</option>
                        <option value="GBP" {{ old('currency', $project->currency) === 'GBP' ? 'selected' : '' }}>GBP</option>
                    </select>
                    @error('currency')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Project Manager -->
                <div class="lg:col-span-2">
                    <label for="project_manager" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Project Manager
                    </label>
                    <input type="text"
                           name="project_manager"
                           id="project_manager"
                           value="{{ old('project_manager', $project->project_manager) }}"
                           class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent @error('project_manager') border-red-500 @enderror">
                    @error('project_manager')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Progress -->
                <div class="lg:col-span-2">
                    <label for="progress" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                        Progress (0-100%)
                    </label>
                    <div class="flex items-center gap-4">
                        <input type="range"
                               name="progress"
                               id="progress"
                               min="0"
                               max="100"
                               value="{{ old('progress', $project->progress ?? 0) }}"
                               x-data="{ value: {{ old('progress', $project->progress ?? 0) }} }"
                               x-model="value"
                               class="flex-1">
                        <span x-text="value + '%'" class="font-bold text-gray-900 dark:text-white w-16 text-right"></span>
                    </div>
                    @error('progress')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Team Members -->
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Team Members</h3>
            <div class="space-y-4">
                <p class="text-sm text-gray-600 dark:text-gray-400">Select team members to assign to this project</p>

                @php
                    $currentMemberIds = $project->members->where('member_type', 'internal')->pluck('employee_id')->toArray();
                @endphp

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($employees as $employee)
                        <label class="flex items-center gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <input type="checkbox"
                                   name="team_members[]"
                                   value="{{ $employee->id }}"
                                   {{ in_array($employee->id, old('team_members', $currentMemberIds)) ? 'checked' : '' }}
                                   class="w-5 h-5 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 flex items-center justify-center bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 text-white dark:text-black rounded-full font-bold text-sm">
                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900 dark:text-white">{{ $employee->first_name }} {{ $employee->last_name }}</p>
                                    @if($employee->designation)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $employee->designation }}</p>
                                    @endif
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>

                @error('team_members')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4">
            <a href="{{ route('projects.show', $project) }}" class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                Cancel
            </a>
            <button type="submit" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Update Project
            </button>
        </div>
    </form>
    </div>

<script>
document.addEventListener('alpine:init', () => {
    // Alpine.js already initialized globally
});
</script>
</x-app-layout>
