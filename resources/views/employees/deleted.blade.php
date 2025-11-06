<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Deleted Employees') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">View and restore deleted team members</p>
            </div>
            <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Active Employees
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

            <!-- Statistics Card -->
            <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Deleted Employees</p>
                        <p class="text-3xl font-bold text-white mt-2">{{ $deletedCount }}</p>
                        <p class="text-xs text-gray-400 mt-1">Can be restored or permanently deleted</p>
                    </div>
                    <div class="p-3 bg-gray-700 rounded-full">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Search -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email, phone, position..." class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition" />
                        </div>

                        <!-- Department Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                                Department
                            </label>
                            <select name="department" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">
                                <option value="">All Departments</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept }}" @selected(request('department')===$dept)>{{ $dept }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                            </svg>
                            Apply Filters
                        </button>
                        @if(request()->hasAny(['q', 'department']))
                            <a href="{{ route('employees.deleted') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Showing <strong>{{ $employees->count() }}</strong> of <strong>{{ $employees->total() }}</strong> deleted employees
                        </span>
                    </div>
                </form>
            </div>

            <!-- Employee Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($employees as $employee)
                    @php
                        $lastActivity = $employee->payments->first();
                        $lastPayment = $employee->payments->where('activity_type', 'payment')->first();
                        $totalActivities = $employee->achievement_count + $employee->warning_count + $employee->payment_count + $employee->note_count;
                    @endphp
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group opacity-75">
                        <!-- Card Header with Avatar -->
                        <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/30 dark:to-red-800/30 p-4 relative">
                            <span class="absolute top-2 right-2 px-2.5 py-1 bg-red-600 text-white text-xs font-bold rounded-full border border-red-700 shadow-lg">
                                Deleted
                            </span>
                            
                            <div class="flex items-center gap-3">
                                <!-- Avatar with Initials -->
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-red-600 to-red-800 flex items-center justify-center text-white text-xl font-bold shadow-lg border-4 border-white dark:border-gray-800 flex-shrink-0">
                                    {{ strtoupper(substr($employee->first_name, 0, 1) . substr($employee->last_name, 0, 1)) }}
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white truncate">
                                        {{ $employee->first_name }} {{ $employee->last_name }}
                                    </h3>
                                    <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                        {{ $employee->position ?? 'No position' }}
                                    </p>
                                    @if($employee->department)
                                        <span class="inline-flex items-center gap-1 mt-1 px-2 py-0.5 bg-white dark:bg-gray-900 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full border border-gray-200 dark:border-gray-600">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                            </svg>
                                            {{ $employee->department }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Card Body -->
                        <div class="p-4 space-y-3">
                            <!-- Deletion Info -->
                            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 rounded-lg p-3">
                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <div class="flex-1">
                                        <p class="text-xs font-semibold text-red-800 dark:text-red-300">Deleted</p>
                                        <p class="text-xs text-red-600 dark:text-red-400">{{ $employee->deleted_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Activity Overview -->
                            @if($totalActivities > 0)
                                <div>
                                    <div class="flex items-center justify-between mb-2">
                                        <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wide">Activity Overview</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $totalActivities }} total</span>
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if($employee->achievement_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-50 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs font-semibold rounded-lg border border-green-200 dark:border-green-700">
                                                <span>🟢</span>
                                                <span>{{ $employee->achievement_count }}</span>
                                            </span>
                                        @endif
                                        @if($employee->warning_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-300 text-xs font-semibold rounded-lg border border-red-200 dark:border-red-700">
                                                <span>🔴</span>
                                                <span>{{ $employee->warning_count }}</span>
                                            </span>
                                        @endif
                                        @if($employee->payment_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-blue-50 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 text-xs font-semibold rounded-lg border border-blue-200 dark:border-blue-700">
                                                <span>🔵</span>
                                                <span>{{ $employee->payment_count }}</span>
                                            </span>
                                        @endif
                                        @if($employee->note_count > 0)
                                            <span class="inline-flex items-center gap-1 px-2 py-1 bg-gray-50 dark:bg-gray-900/20 text-gray-700 dark:text-gray-300 text-xs font-semibold rounded-lg border border-gray-200 dark:border-gray-700">
                                                <span>⚪</span>
                                                <span>{{ $employee->note_count }}</span>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            <!-- Contact Info -->
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="truncate">{{ $employee->email }}</span>
                                </div>
                                @if($employee->phone)
                                <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $employee->phone }}</span>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Card Footer with Actions -->
                        <div class="px-4 pb-4 flex items-center gap-2">
                            <form method="POST" action="{{ route('employees.restore', $employee->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2 bg-green-600 text-white rounded-full shadow hover:bg-green-700 transition-all text-sm font-medium">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Restore
                                </button>
                            </form>
                            <form method="POST" action="{{ route('employees.force-delete', $employee->id) }}" onsubmit="return confirm('Are you sure you want to permanently delete this employee? This action CANNOT be undone!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center p-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-all" title="Permanently Delete">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No deleted employees</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                @if(request()->hasAny(['q', 'department']))
                                    Try adjusting your search or filters to find what you're looking for.
                                @else
                                    There are no deleted employees at this time.
                                @endif
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                    {{ $employees->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
