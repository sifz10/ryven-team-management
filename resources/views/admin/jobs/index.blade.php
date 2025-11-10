<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <!-- Page Header -->
        <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-10 shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 rounded-xl flex items-center justify-center shadow-lg">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <h1 class="text-xl sm:text-2xl font-bold text-black dark:text-white">Job Postings</h1>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-0.5">Manage job posts and track applications</p>
                        </div>
                    </div>
                    <x-black-button href="{{ route('admin.jobs.create') }}" class="w-full sm:w-auto">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        <span class="hidden sm:inline">Create Job Post</span>
                        <span class="sm:hidden">Create</span>
                    </x-black-button>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6 sm:mb-8">
                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Total Jobs</p>
                            <p class="text-2xl sm:text-3xl font-bold text-black dark:text-white mt-1">{{ $jobs->total() }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-green-200 dark:border-green-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-green-100 dark:hover:shadow-green-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Published</p>
                            <p class="text-2xl sm:text-3xl font-bold text-green-600 dark:text-green-400 mt-1">{{ $jobs->where('status', 'published')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-green-500 to-emerald-600 dark:from-green-400 dark:to-emerald-500 rounded-xl flex items-center justify-center shadow-lg shadow-green-200 dark:shadow-green-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-yellow-200 dark:border-yellow-800/50 p-4 sm:p-5 hover:shadow-lg hover:shadow-yellow-100 dark:hover:shadow-yellow-900/20 hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Draft</p>
                            <p class="text-2xl sm:text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-1">{{ $jobs->where('status', 'draft')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-yellow-500 to-orange-500 dark:from-yellow-400 dark:to-orange-400 rounded-xl flex items-center justify-center shadow-lg shadow-yellow-200 dark:shadow-yellow-900/30 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-5 hover:shadow-lg hover:scale-105 transition-all duration-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div class="flex-1">
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 font-medium">Closed</p>
                            <p class="text-2xl sm:text-3xl font-bold text-gray-600 dark:text-gray-400 mt-1">{{ $jobs->where('status', 'closed')->count() }}</p>
                        </div>
                        <div class="w-10 h-10 sm:w-12 sm:h-12 bg-gradient-to-br from-gray-400 to-gray-600 dark:from-gray-600 dark:to-gray-700 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 mb-6 sm:mb-8 shadow-sm">
                <form method="GET" class="space-y-4">
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <!-- Search Input -->
                        <div class="flex-1">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                <input type="text"
                                       name="search"
                                       value="{{ request('search') }}"
                                       placeholder="Search by title, location, or type..."
                                       class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent dark:bg-gray-700 dark:text-white transition-all text-sm sm:text-base">
                            </div>
                        </div>

                        <!-- Status Select -->
                        <div class="w-full sm:w-48">
                            <div class="relative">
                                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                                </svg>
                                <select name="status" class="w-full pl-10 pr-4 py-2.5 sm:py-3 border border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent dark:bg-gray-700 dark:text-white appearance-none transition-all text-sm sm:text-base">
                                    <option value="">All Status</option>
                                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Published</option>
                                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Closed</option>
                                </select>
                                <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <x-black-button type="submit" class="flex-1 sm:flex-initial justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            <span>Apply Filters</span>
                        </x-black-button>
                        @if(request()->hasAny(['search', 'status']))
                            <x-black-button variant="outline" href="{{ route('admin.jobs.index') }}" class="flex-1 sm:flex-initial justify-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                <span>Clear Filters</span>
                            </x-black-button>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Jobs List -->
            <div class="space-y-4">
                @if($jobs->count() > 0)
                    @foreach($jobs as $job)
                        <div class="group bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 p-4 sm:p-6 hover:shadow-xl hover:scale-[1.01] transition-all duration-300">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-4">
                                <!-- Job Info -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start gap-3">
                                        <div class="flex-shrink-0 w-12 h-12 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-200 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform">
                                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <a href="{{ route('admin.jobs.show', $job) }}" class="text-lg sm:text-xl font-bold text-black dark:text-white hover:text-gray-700 dark:hover:text-gray-300 transition-colors block truncate">
                                                {{ $job->title }}
                                            </a>
                                            @if($job->department)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $job->department }}</p>
                                            @endif

                                            <!-- Mobile-only details -->
                                            <div class="flex flex-wrap items-center gap-2 mt-3 lg:hidden">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                                </span>
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    </svg>
                                                    {{ $job->location ?? 'Not specified' }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Metadata -->
                                <div class="hidden lg:flex items-center gap-6">
                                    <!-- Type -->
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Type</p>
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                                        </span>
                                    </div>

                                    <!-- Location -->
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Location</p>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $job->location ?? 'Remote' }}</p>
                                    </div>

                                    <!-- Applications -->
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Applications</p>
                                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-bold rounded-md bg-black dark:bg-white text-white dark:text-black">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                            {{ $job->applications_count }}
                                        </span>
                                    </div>

                                    <!-- Status -->
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Status</p>
                                        @if($job->status === 'published')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                                Published
                                            </span>
                                        @elseif($job->status === 'draft')
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                                Draft
                                            </span>
                                        @else
                                            <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                                Closed
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Created Date -->
                                    <div class="text-center">
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Created</p>
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $job->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2 pt-3 lg:pt-0 border-t lg:border-t-0 lg:border-l border-gray-200 dark:border-gray-700 lg:pl-6">
                                    <x-black-button variant="outline" href="{{ route('admin.jobs.show', $job) }}" class="flex-1 lg:flex-initial">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="hidden sm:inline">View</span>
                                    </x-black-button>
                                    <x-black-button href="{{ route('admin.jobs.edit', $job) }}" class="flex-1 lg:flex-initial">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="hidden sm:inline">Edit</span>
                                    </x-black-button>
                                </div>
                            </div>

                            <!-- Mobile-only Status & Applications -->
                            <div class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200 dark:border-gray-700 lg:hidden">
                                <div class="flex items-center gap-3">
                                    @if($job->status === 'published')
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400">
                                            Published
                                        </span>
                                    @elseif($job->status === 'draft')
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-yellow-100 dark:bg-yellow-900/30 text-yellow-700 dark:text-yellow-400">
                                            Draft
                                        </span>
                                    @else
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-400">
                                            Closed
                                        </span>
                                    @endif
                                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $job->created_at->format('M d, Y') }}</span>
                                </div>
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 text-sm font-bold rounded-md bg-black dark:bg-white text-white dark:text-black">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    {{ $job->applications_count }}
                                </span>
                            </div>
                        </div>
                    @endforeach

                    <!-- Pagination -->
                    <div class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-sm rounded-xl border border-gray-200 dark:border-gray-700 px-6 py-4">
                        {{ $jobs->links() }}
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-lg font-semibold text-gray-500 dark:text-gray-400">No job posts found</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Create your first job post to start recruiting</p>
                        <x-black-button href="{{ route('admin.jobs.create') }}" class="mt-4">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Create Job Post
                        </x-black-button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
