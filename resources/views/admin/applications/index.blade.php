<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">Job Applications</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Review and manage candidate applications</p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Applications</p>
                            <p class="text-2xl font-bold text-black dark:text-white mt-1">{{ $applications->total() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-black dark:bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Best Match</p>
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $applications->where('ai_status', 'best_match')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Good to Go</p>
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400 mt-1">{{ $applications->where('ai_status', 'good_to_go')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Pending Review</p>
                            <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $applications->where('ai_status', 'pending')->count() }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4 mb-6">
                <form method="GET" class="flex gap-4 flex-wrap">
                    <div class="flex-1 min-w-[200px]">
                        <input type="text"
                               name="search"
                               value="{{ request('search') }}"
                               placeholder="Search by name or email..."
                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-white dark:bg-gray-700 dark:text-white">
                    </div>
                    <div class="w-48">
                        <select name="job_post_id" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-white dark:bg-gray-700 dark:text-white">
                            <option value="">All Jobs</option>
                            @foreach($jobPosts as $job)
                                <option value="{{ $job->id }}" {{ request('job_post_id') == $job->id ? 'selected' : '' }}>{{ $job->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-48">
                        <select name="ai_status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-white dark:bg-gray-700 dark:text-white">
                            <option value="">All AI Status</option>
                            <option value="best_match" {{ request('ai_status') === 'best_match' ? 'selected' : '' }}>Best Match</option>
                            <option value="good_to_go" {{ request('ai_status') === 'good_to_go' ? 'selected' : '' }}>Good to Go</option>
                            <option value="not_good_fit" {{ request('ai_status') === 'not_good_fit' ? 'selected' : '' }}>Not a Good Fit</option>
                            <option value="pending" {{ request('ai_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <button type="submit" class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'job_post_id', 'ai_status']))
                        <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 border-2 border-black dark:border-white text-black dark:text-white rounded-lg font-semibold hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                            Clear
                        </a>
                    @endif
                </form>
            </div>

            <!-- Applications List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                @if($applications->count() > 0)
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($applications as $application)
                            <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <div class="flex items-start justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <div class="w-12 h-12 rounded-full bg-black dark:bg-white flex items-center justify-center text-white dark:text-black font-bold text-lg">
                                                {{ strtoupper(substr($application->first_name, 0, 1) . substr($application->last_name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <a href="{{ route('admin.applications.show', $application) }}" class="text-lg font-bold text-black dark:text-white hover:underline">
                                                    {{ $application->full_name }}
                                                </a>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $application->email }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400 ml-15">
                                            <span>Applied for: <strong class="text-black dark:text-white">{{ $application->jobPost->title }}</strong></span>
                                            <span>•</span>
                                            <span>{{ $application->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-end gap-2">
                                        @if($application->ai_status === 'best_match')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                </svg>
                                                Best Match ({{ $application->ai_match_score }}%)
                                            </span>
                                        @elseif($application->ai_status === 'good_to_go')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Good to Go ({{ $application->ai_match_score }}%)
                                            </span>
                                        @elseif($application->ai_status === 'pending')
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                Pending Review
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded-lg bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                                Not a Good Fit ({{ $application->ai_match_score }}%)
                                            </span>
                                        @endif
                                        <a href="{{ route('admin.applications.show', $application) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-black dark:text-white hover:underline">
                                            View Details
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $applications->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No applications found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Applications will appear here when candidates apply for your job posts</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
