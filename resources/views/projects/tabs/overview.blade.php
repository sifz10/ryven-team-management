<div class="space-y-6">
    <!-- Project Stats -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Progress</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $project->progress ?? 0 }}%</p>
                </div>
                <div class="p-3 bg-blue-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Tasks</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $project->tasks->count() }}</p>
                </div>
                <div class="p-3 bg-purple-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Team Members</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $project->members->count() }}</p>
                </div>
                <div class="p-3 bg-green-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-xl p-6 border border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-300 text-xs font-semibold uppercase tracking-wide">Files</p>
                    <p class="text-3xl font-bold text-white mt-2">{{ $project->files->count() }}</p>
                </div>
                <div class="p-3 bg-yellow-500/20 rounded-lg">
                    <svg class="w-8 h-8 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Project Details -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Project Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Project Information</h3>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Description</label>
                        <p class="text-gray-900 dark:text-gray-200 mt-1">{{ $project->description ?? 'No description provided' }}</p>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Status</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($project->status === 'active') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                    @elseif($project->status === 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @elseif($project->status === 'on-hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ ucfirst($project->status) }}
                                </span>
                            </div>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Priority</label>
                            <div class="mt-1">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                    @if($project->priority === 4) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                    @elseif($project->priority === 3) bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400
                                    @elseif($project->priority === 2) bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                    {{ $project->priority_label }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($project->start_date || $project->end_date)
                    <div class="grid grid-cols-2 gap-4">
                        @if($project->start_date)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Start Date</label>
                            <p class="text-gray-900 dark:text-gray-200 mt-1">{{ $project->start_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                        @if($project->end_date)
                        <div>
                            <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Due Date</label>
                            <p class="text-gray-900 dark:text-gray-200 mt-1">{{ $project->end_date->format('M d, Y') }}</p>
                        </div>
                        @endif
                    </div>
                    @endif

                    @if($project->budget)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Budget</label>
                        <p class="text-gray-900 dark:text-gray-200 mt-1 text-2xl font-bold">{{ number_format($project->budget, 2) }} {{ $project->currency }}</p>
                    </div>
                    @endif

                    @if($project->project_manager)
                    <div>
                        <label class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Project Manager</label>
                        <p class="text-gray-900 dark:text-gray-200 mt-1">{{ $project->project_manager }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Client Information -->
            @if($project->client)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Client Information</h3>

                <div class="flex items-start gap-4">
                    @if($project->client->logo)
                        <img src="{{ Storage::url($project->client->logo) }}" alt="{{ $project->client->name }}" class="w-16 h-16 rounded-lg object-cover">
                    @else
                        <div class="w-16 h-16 rounded-lg bg-gradient-to-br from-gray-800 to-black dark:from-gray-700 dark:to-gray-900 flex items-center justify-center text-white font-bold text-2xl">
                            {{ substr($project->client->name, 0, 1) }}
                        </div>
                    @endif

                    <div class="flex-1 space-y-3">
                        <div>
                            <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ $project->client->name }}</h4>
                            @if($project->client->company)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->client->company }}</p>
                            @endif
                        </div>

                        <div class="space-y-2">
                            @if($project->client->email)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                <a href="mailto:{{ $project->client->email }}" class="hover:text-black dark:hover:text-white">{{ $project->client->email }}</a>
                            </div>
                            @endif
                            @if($project->client->phone)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <a href="tel:{{ $project->client->phone }}" class="hover:text-black dark:hover:text-white">{{ $project->client->phone }}</a>
                            </div>
                            @endif
                            @if($project->client->website)
                            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                                </svg>
                                <a href="{{ $project->client->website }}" target="_blank" class="hover:text-black dark:hover:text-white">{{ $project->client->website }}</a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column - Team Members -->
        <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Team Members</h3>

                @if($project->members->count() > 0)
                <div class="space-y-3">
                    @foreach($project->members as $member)
                    <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-700 dark:to-gray-900 flex items-center justify-center text-white font-bold">
                            @if($member->employee)
                                {{ substr($member->employee->first_name, 0, 1) }}{{ substr($member->employee->last_name, 0, 1) }}
                            @else
                                {{ substr($member->client_member_name ?? 'C', 0, 1) }}
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 dark:text-white truncate">
                                @if($member->employee)
                                    {{ $member->employee->first_name }} {{ $member->employee->last_name }}
                                @else
                                    {{ $member->client_member_name }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                {{ $member->role ?? ucfirst($member->member_type) }}
                            </p>
                        </div>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                            {{ $member->member_type === 'internal' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400' : 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400' }}">
                            {{ $member->member_type === 'internal' ? 'Team' : 'Client' }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">No team members assigned yet</p>
                </div>
                @endif
            </div>

            <!-- Progress Bar -->
            @if($project->progress !== null)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-bold text-gray-900 dark:text-white">Overall Progress</h3>
                    <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4">
                    <div class="bg-gradient-to-r from-gray-800 to-black dark:from-gray-600 dark:to-gray-400 h-4 rounded-full transition-all duration-300" style="width: {{ $project->progress }}%"></div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
