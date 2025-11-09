<x-client-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Projects') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and manage your projects</p>
            </div>
            <a href="{{ route('client.projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-semibold hover:opacity-90 transition-all shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Project
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('status'))
            <div class="bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('error') }}
            </div>
        @endif

                <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="rounded-2xl p-6 shadow-lg" style="background-color: #000000; border: 2px solid #1a1a1a;">
                <p class="text-gray-400 text-sm font-semibold uppercase">Active</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $projects->where('status', 'active')->count() }}</p>
            </div>
            <div class="rounded-2xl p-6 shadow-lg" style="background-color: #000000; border: 2px solid #1a1a1a;">
                <p class="text-gray-400 text-sm font-semibold uppercase">Planning</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $projects->where('status', 'planning')->count() }}</p>
            </div>
            <div class="rounded-2xl p-6 shadow-lg" style="background-color: #000000; border: 2px solid #1a1a1a;">
                <p class="text-gray-400 text-sm font-semibold uppercase">Completed</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $projects->where('status', 'completed')->count() }}</p>
            </div>
            <div class="rounded-2xl p-6 shadow-lg" style="background-color: #000000; border: 2px solid #1a1a1a;">
                <p class="text-gray-400 text-sm font-semibold uppercase">Total</p>
                <p class="text-3xl font-bold text-white mt-2">{{ $projects->total() }}</p>
            </div>
        </div>

        <!-- Projects Grid -->
        @if($projects->count() > 0)
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @foreach($projects as $project)
            <!-- Project Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-lg transition-all">
                <div class="flex items-start justify-between mb-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">
                            {{ $project->name }}
                        </h3>
                        @if($project->description)
                        <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                            {{ $project->description }}
                        </p>
                        @endif
                    </div>
                    <span class="px-3 py-1 text-xs font-medium rounded-full whitespace-nowrap ml-4
                        @if($project->status === 'active') bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300
                        @elseif($project->status === 'planning') bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300
                        @elseif($project->status === 'on-hold') bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300
                        @elseif($project->status === 'completed') bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300
                        @elseif($project->status === 'cancelled') bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300
                        @endif">
                        {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400 mb-1">Progress</p>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                <div class="h-full bg-black dark:bg-white transition-all" style="width: {{ $project->progress ?? 0 }}%"></div>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $project->progress ?? 0 }}%</span>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Team Members</p>
                        <div class="flex items-center gap-2 mt-1">
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                            </svg>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $project->members_count }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs text-gray-600 dark:text-gray-400">Tasks</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $project->tasks_count }}</p>
                    </div>
                    @if(!isset($isTeamMember) || !$isTeamMember)
                        @if($project->budget)
                        <div>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Budget</p>
                            <p class="text-sm font-semibold text-gray-900 dark:text-white mt-1">{{ $project->currency }} {{ number_format($project->budget, 2) }}</p>
                        </div>
                        @endif
                    @endif
                </div>

                <div class="pt-4 border-t border-gray-200 dark:border-gray-700 flex items-center justify-between">
                    <div class="text-sm text-gray-600 dark:text-gray-400">
                        <span class="font-medium">{{ $project->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($project->status === 'planning')
                        <a href="{{ route('client.projects.edit', $project) }}" class="inline-flex items-center gap-1 px-4 py-2 text-gray-700 dark:text-gray-300 font-medium rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        @endif
                        <a href="{{ route('client.projects.show', $project) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:opacity-90 transition-all text-sm">
                            View
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <!-- Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 shadow-xl text-center">
            <svg class="w-20 h-20 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Projects Yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">Get started by creating your first project and start collaborating with your team</p>
            <a href="{{ route('client.projects.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:opacity-90 transition-all shadow-lg">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Create Your First Project
            </a>
        </div>
        @endif

        <!-- Pagination -->
        @if($projects->hasPages())
        <div class="flex justify-center">
            {{ $projects->links() }}
        </div>
        @endif
    </div>
</x-client-app-layout>
