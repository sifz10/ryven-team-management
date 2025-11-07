<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Projects') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Manage projects and track progress</p>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()->hasPermission('create-projects'))
                <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white dark:bg-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    New Project
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ showDeleteModal: false, projectToDelete: null, projectName: '' }">
        @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Active</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $projects->where('status', 'active')->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-500/20 rounded-xl">
                        <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Completed</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $projects->where('status', 'completed')->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-500/20 rounded-xl">
                        <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">On Hold</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $projects->where('status', 'on-hold')->count() }}</p>
                    </div>
                    <div class="p-3 bg-yellow-500/20 rounded-xl">
                        <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Total Projects</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $projects->total() }}</p>
                    </div>
                    <div class="p-3 bg-purple-500/20 rounded-xl">
                        <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Projects Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($projects as $project)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-lg transition-all duration-200">
                    <!-- Project Header -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="font-bold text-lg text-gray-900 dark:text-white">{{ $project->name }}</h3>
                                @if($project->client)
                                    <div class="flex items-center gap-2 mt-2">
                                        @if($project->client->logo)
                                            <img src="{{ Storage::url($project->client->logo) }}" alt="{{ $project->client->name }}" class="w-5 h-5 rounded object-cover">
                                        @endif
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->client->name }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($project->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($project->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($project->status === 'on-hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>

                        @if($project->description)
                            <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">{{ $project->description }}</p>
                        @endif
                    </div>

                    <!-- Project Stats -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->tasks_count }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Tasks</p>
                        </div>
                        <div class="text-center border-x border-gray-200 dark:border-gray-700">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->members_count }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Team</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->progress ?? 0 }}%</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Progress</p>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    @if($project->progress !== null)
                        <div class="px-6 py-3 bg-gray-50 dark:bg-gray-900/50">
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-gradient-to-r from-gray-800 to-black dark:from-gray-600 dark:to-gray-400 h-2 rounded-full transition-all duration-300" style="width: {{ $project->progress }}%"></div>
                            </div>
                        </div>
                    @endif

                    <!-- Project Details -->
                    <div class="p-6 space-y-3">
                        @if($project->start_date || $project->end_date)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                @if($project->start_date && $project->end_date)
                                    {{ $project->start_date->format('M d') }} - {{ $project->end_date->format('M d, Y') }}
                                @elseif($project->start_date)
                                    Started {{ $project->start_date->format('M d, Y') }}
                                @elseif($project->end_date)
                                    Due {{ $project->end_date->format('M d, Y') }}
                                @endif
                            </div>
                        @endif

                        @if($project->budget)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Budget: {{ number_format($project->budget, 2) }} {{ $project->currency }}
                            </div>
                        @endif

                        @if($project->project_manager)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                PM: {{ $project->project_manager }}
                            </div>
                        @endif
                    </div>

                    <!-- Actions -->
                    <div class="p-6 pt-0 flex items-center gap-2">
                        @if(auth()->user()->hasPermission('view-projects'))
                            <a href="{{ route('projects.show', $project) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-black text-white dark:bg-white dark:text-black rounded-full hover:opacity-90 transition-all font-semibold text-sm shadow-md">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Details
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('edit-projects'))
                            <a href="{{ route('projects.edit', $project) }}" class="p-2.5 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                        @endif
                        @if(auth()->user()->hasPermission('delete-projects'))
                            <button
                                @click="projectToDelete = {{ $project->id }}; projectName = '{{ addslashes($project->name) }}'; showDeleteModal = true"
                                type="button"
                                class="p-2.5 text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-lg">No projects found</p>
                    <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Create your first project to get started</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($projects->hasPages())
            <div class="mt-6">
                {{ $projects->links() }}
            </div>
        @endif

        <!-- Delete Confirmation Modal -->
        <div x-show="showDeleteModal"
             x-cloak
             class="fixed inset-0 z-50 overflow-y-auto"
             aria-labelledby="modal-title"
             role="dialog"
             aria-modal="true">
            <!-- Backdrop -->
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100"
                     x-transition:leave-end="opacity-0"
                     @click="showDeleteModal = false"
                     class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity"
                     aria-hidden="true"></div>

                <!-- Center modal -->
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showDeleteModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                     x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                     class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border-2 border-gray-200 dark:border-gray-700">

                    <!-- Modal Content -->
                    <div class="bg-white dark:bg-gray-800 px-6 pt-6 pb-4">
                        <!-- Icon -->
                        <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-100 dark:bg-red-900/30">
                            <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                        </div>

                        <!-- Title and Description -->
                        <div class="mt-4 text-center">
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white" id="modal-title">
                                Delete Project
                            </h3>
                            <div class="mt-3">
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Are you sure you want to delete
                                    <span class="font-semibold text-gray-900 dark:text-white" x-text="projectName"></span>?
                                </p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    This action cannot be undone. All project data will be permanently removed.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-4 flex flex-col-reverse sm:flex-row gap-3 justify-end">
                        <button
                            type="button"
                            @click="showDeleteModal = false"
                            class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-2 border-gray-300 dark:border-gray-600 rounded-full font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all shadow-sm"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                        <form :action="`/projects/${projectToDelete}`" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-red-600 dark:bg-red-500 text-white rounded-full font-semibold hover:opacity-90 transition-all shadow-lg w-full sm:w-auto"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete Project
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
