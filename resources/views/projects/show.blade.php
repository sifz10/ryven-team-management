<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-300">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ $project->name }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Project Details & Work Log</p>
            </div>
            <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Project Info Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Description</h3>
                            <p class="text-gray-800 dark:text-gray-200">{{ $project->description ?? 'No description provided' }}</p>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Status</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($project->status === 'active') bg-green-100 text-green-800
                                    @elseif($project->status === 'completed') bg-blue-100 text-blue-800
                                    @elseif($project->status === 'on-hold') bg-yellow-100 text-yellow-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>

                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Priority</h3>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold
                                    @if($project->priority === 4) bg-red-100 text-red-800
                                    @elseif($project->priority === 3) bg-orange-100 text-orange-800
                                    @elseif($project->priority === 2) bg-blue-100 text-blue-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                    {{ $project->priority_label }}
                                </span>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Timeline</h3>
                            <div class="text-gray-800 dark:text-gray-200">
                                @if($project->start_date)
                                    <p>Start: {{ $project->start_date->format('F d, Y') }}</p>
                                    @if($project->end_date)
                                        <p class="mt-1">End: {{ $project->end_date->format('F d, Y') }}</p>
                                    @endif
                                @else
                                    <p class="text-gray-500">Not set</p>
                                @endif
                            </div>
                        </div>

                        @if($project->budget)
                            <div>
                                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Budget</h3>
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $project->currency }} {{ number_format($project->budget, 2) }}</p>
                            </div>
                        @endif
                    </div>

                    <!-- Right Column - Client Info -->
                    <div class="space-y-6 border-l border-gray-200 dark:border-gray-700 pl-8">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Client Information
                        </h3>

                        @if($project->client_name)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Name</h4>
                                <p class="text-gray-800 dark:text-gray-200">{{ $project->client_name }}</p>
                            </div>
                        @endif

                        @if($project->client_company)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Company</h4>
                                <p class="text-gray-800 dark:text-gray-200">{{ $project->client_company }}</p>
                            </div>
                        @endif

                        @if($project->client_email)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Email</h4>
                                <a href="mailto:{{ $project->client_email }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $project->client_email }}</a>
                            </div>
                        @endif

                        @if($project->client_phone)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Phone</h4>
                                <a href="tel:{{ $project->client_phone }}" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $project->client_phone }}</a>
                            </div>
                        @endif

                        @if($project->client_address)
                            <div>
                                <h4 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Address</h4>
                                <p class="text-gray-800 dark:text-gray-200">{{ $project->client_address }}</p>
                            </div>
                        @endif

                        @if(!$project->client_name && !$project->client_email && !$project->client_phone)
                            <p class="text-gray-500 dark:text-gray-400">No client information provided</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Work Submissions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl rounded-2xl">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Work Log ({{ $project->workSubmissions->count() }} entries)</h3>
                </div>

                <div class="p-6">
                    @forelse($project->workSubmissions->sortByDesc('created_at') as $submission)
                        <div class="mb-4 p-5 border-2 border-gray-200 dark:border-gray-700 rounded-xl hover:shadow-md transition-all">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $submission->employee->first_name }} {{ $submission->employee->last_name }}</span>
                                        <span class="text-sm text-gray-500 dark:text-gray-400">{{ $submission->created_at->format('M d, Y g:i A') }}</span>
                                    </div>
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">{{ $submission->work_description }}</p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="font-medium text-gray-500">No work logged yet</p>
                            <p class="text-sm mt-1 text-gray-400">Work submissions will appear here when employees log their work</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
