<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Projects') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage all your projects and clients</p>
            </div>
            <a href="{{ route('projects.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Project
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('status'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-green-100 text-sm font-semibold uppercase">Active</p>
                    <p class="text-3xl font-bold mt-2">{{ $projects->where('status', 'active')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-blue-600 to-blue-700 rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-blue-100 text-sm font-semibold uppercase">Completed</p>
                    <p class="text-3xl font-bold mt-2">{{ $projects->where('status', 'completed')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-yellow-600 to-yellow-700 rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-yellow-100 text-sm font-semibold uppercase">On Hold</p>
                    <p class="text-3xl font-bold mt-2">{{ $projects->where('status', 'on-hold')->count() }}</p>
                </div>
                <div class="bg-gradient-to-br from-gray-600 to-gray-700 rounded-2xl p-6 text-white shadow-lg">
                    <p class="text-gray-100 text-sm font-semibold uppercase">Total</p>
                    <p class="text-3xl font-bold mt-2">{{ $projects->total() }}</p>
                </div>
            </div>

            <!-- Projects List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Project</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Priority</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Timeline</th>
                                <th class="px-6 py-4 text-left text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Budget</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($projects as $project)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4">
                                        <div>
                                            <div class="font-semibold text-gray-900 dark:text-gray-100">{{ $project->name }}</div>
                                            @if($project->description)
                                                <div class="text-sm text-gray-500 dark:text-gray-400 mt-1 line-clamp-1">{{ $project->description }}</div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($project->client_name)
                                            <div>
                                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $project->client_name }}</div>
                                                @if($project->client_company)
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $project->client_company }}</div>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500 text-sm">No client</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($project->status === 'active') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                            @elseif($project->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @elseif($project->status === 'on-hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                            @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @endif">
                                            {{ ucfirst($project->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                            @if($project->priority === 4) bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                            @elseif($project->priority === 3) bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-300
                                            @elseif($project->priority === 2) bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300
                                            @endif">
                                            {{ $project->priority_label }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        @if($project->start_date)
                                            <div>{{ $project->start_date->format('M d, Y') }}</div>
                                            @if($project->end_date)
                                                <div class="text-xs text-gray-500 dark:text-gray-400">to {{ $project->end_date->format('M d, Y') }}</div>
                                            @endif
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">Not set</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                        @if($project->budget)
                                            <div class="font-semibold">{{ $project->currency }} {{ number_format($project->budget, 2) }}</div>
                                        @else
                                            <span class="text-gray-400 dark:text-gray-500">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right space-x-2">
                                        <a href="{{ route('projects.show', $project) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-800 dark:hover:text-blue-300 text-sm font-medium">View</a>
                                        <a href="{{ route('projects.edit', $project) }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300 text-sm font-medium">Edit</a>
                                        <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 text-sm font-medium">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="w-16 h-16 mx-auto text-gray-300 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-500 dark:text-gray-400 font-medium">No projects yet</p>
                                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Get started by creating your first project</p>
                                        <a href="{{ route('projects.create') }}" class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-lg hover:bg-gray-800 transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            Create Project
                                        </a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($projects->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $projects->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
