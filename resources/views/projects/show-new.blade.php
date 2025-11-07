<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-4">
                <a href="{{ route('projects.index') }}" class="p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $project->name }}
                    </h2>
                    <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                        @if($project->client)
                            {{ $project->client->name }}
                        @elseif($project->client_name)
                            {{ $project->client_name }}
                        @else
                            No client assigned
                        @endif
                    </p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(auth()->user()->hasPermission('edit-projects'))
                <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white dark:bg-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all duration-200 font-semibold text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Project
                </a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="{ activeTab: '{{ $tab }}' }">
        @if (session('status'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-3">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                {{ session('status') }}
            </div>
        @endif

        <!-- Tab Navigation -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="flex overflow-x-auto border-b border-gray-200 dark:border-gray-700">
                <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'overview']) }}" :class="activeTab === 'overview' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'" class="flex items-center gap-2 px-6 py-4 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Overview
                </a>
                <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'tasks', 'sub_tab' => $subTab ?? 'list']) }}" :class="activeTab === 'tasks' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'" class="flex items-center gap-2 px-6 py-4 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                    </svg>
                    Tasks
                    @if($project->tasks->count() > 0)
                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs font-bold">{{ $project->tasks->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'files']) }}" :class="activeTab === 'files' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'" class="flex items-center gap-2 px-6 py-4 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Files
                    @if($project->files->count() > 0)
                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs font-bold">{{ $project->files->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'discussion']) }}" :class="activeTab === 'discussion' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'" class="flex items-center gap-2 px-6 py-4 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    Discussion
                    @if($project->discussions->count() > 0)
                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs font-bold">{{ $project->discussions->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'finance']) }}" :class="activeTab === 'finance' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'" class="flex items-center gap-2 px-6 py-4 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Finance
                    @if($project->expenses->count() > 0)
                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs font-bold">{{ $project->expenses->count() }}</span>
                    @endif
                </a>
                <a href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'tickets']) }}" :class="activeTab === 'tickets' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200'" class="flex items-center gap-2 px-6 py-4 font-semibold text-sm border-b-2 transition-colors whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                    </svg>
                    Tickets
                    @if($project->tickets->count() > 0)
                        <span class="px-2 py-0.5 bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full text-xs font-bold">{{ $project->tickets->count() }}</span>
                    @endif
                </a>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <!-- Overview Tab -->
                <div x-show="activeTab === 'overview'" x-cloak>
                    @include('projects.tabs.overview')
                </div>

                <!-- Tasks Tab -->
                <div x-show="activeTab === 'tasks'" x-cloak>
                    @include('projects.tabs.tasks')
                </div>

                <!-- Files Tab -->
                <div x-show="activeTab === 'files'" x-cloak>
                    @include('projects.tabs.files')
                </div>

                <!-- Discussion Tab -->
                <div x-show="activeTab === 'discussion'" x-cloak>
                    @include('projects.tabs.discussion')
                </div>

                <!-- Finance Tab -->
                <div x-show="activeTab === 'finance'" x-cloak>
                    @include('projects.tabs.finance')
                </div>

                <!-- Tickets Tab -->
                <div x-show="activeTab === 'tickets'" x-cloak>
                    @include('projects.tabs.tickets')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
