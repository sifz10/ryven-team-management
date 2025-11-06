<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Employees Directory') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track your team members</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 sm:gap-3">
                <a href="{{ route('employees.deleted') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-gray-600 text-white dark:bg-gray-700 dark:text-white rounded-full hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all duration-200 font-semibold text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    <span class="hidden sm:inline">Deleted Users</span>
                    <span class="sm:hidden">Deleted</span>
                </a>
                <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 bg-black text-white dark:bg-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:ring-offset-2 transition-all duration-200 font-semibold text-sm sm:text-base">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    <span class="hidden sm:inline">Add Employee</span>
                    <span class="sm:hidden">Add</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
            
            @if (session('status'))
                <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 px-4 py-3 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Total Employees -->
                <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Total Employees</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $totalEmployees }}</p>
                        </div>
                        <div class="p-3 bg-gray-700 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Employees -->
                <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Active</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $activeEmployees }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $totalEmployees > 0 ? round(($activeEmployees / $totalEmployees) * 100) : 0 }}% of total</p>
                        </div>
                        <div class="p-3 bg-gray-700 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Discontinued Employees -->
                <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Discontinued</p>
                            <p class="text-3xl font-bold text-white mt-2">{{ $discontinuedEmployees }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $totalEmployees > 0 ? round(($discontinuedEmployees / $totalEmployees) * 100) : 0 }}% of total</p>
                        </div>
                        <div class="p-3 bg-gray-700 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Monthly Payroll -->
                <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-2xl p-6 border border-gray-700 dark:border-gray-800 shadow-lg">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <p class="text-gray-300 text-sm font-semibold uppercase tracking-wide">Monthly Payroll</p>
                            <div class="mt-2 space-y-1">
                                @forelse($payrollByCurrency as $payroll)
                                    <p class="text-2xl font-bold text-white">
                                        {{ number_format($payroll->total, 0) }} <span class="text-lg">{{ $payroll->currency ?? 'USD' }}</span>
                                    </p>
                                @empty
                                    <p class="text-2xl font-bold text-white">—</p>
                                @endforelse
                            </div>
                        </div>
                        <div class="p-3 bg-gray-700 rounded-full">
                            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Section -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                <form method="GET" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                                Search
                            </label>
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search by name, email, phone, position..." class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition" />
                        </div>

                        <!-- Status Filter -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Status
                            </label>
                            <select name="status" class="block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl px-4 py-2.5 shadow-sm focus:ring-2 focus:ring-black focus:border-black transition">
                                <option value="">All Status</option>
                                <option value="active" @selected(request('status')==='active')>Active</option>
                                <option value="discontinued" @selected(request('status')==='discontinued')>Discontinued</option>
                            </select>
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
                        @if(request()->hasAny(['q', 'status', 'department']))
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Clear Filters
                            </a>
                        @endif
                        <span class="text-sm text-gray-600 dark:text-gray-400">
                            Showing <strong>{{ $employees->count() }}</strong> of <strong>{{ $employees->total() }}</strong> employees
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
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl overflow-hidden shadow-sm hover:shadow-lg transition-all duration-300 group">
                        <!-- Card Header with Avatar -->
                        <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 p-4 relative">
                            @if($employee->discontinued_at)
                                <span class="absolute top-2 right-2 px-2.5 py-1 bg-gray-800 dark:bg-black text-white text-xs font-bold rounded-full border border-gray-700 dark:border-gray-800 shadow-lg">
                                    Discontinued
                                </span>
                            @else
                                <span class="absolute top-2 right-2 px-2.5 py-1 bg-gray-800 dark:bg-black text-white text-xs font-bold rounded-full border border-gray-700 dark:border-gray-800 shadow-lg">
                                    Active
                                </span>
                            @endif
                            
                            <div class="flex items-center gap-3">
                                <!-- Avatar with Initials -->
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-black to-gray-700 flex items-center justify-center text-white text-xl font-bold shadow-lg border-4 border-white dark:border-gray-800 flex-shrink-0">
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

                            <!-- Last Activity -->
                            @if($lastActivity)
                                <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="flex items-start gap-2">
                                        @php
                                            $activityIcon = match($lastActivity->activity_type) {
                                                'achievement' => '🟢',
                                                'warning' => '🔴',
                                                'payment' => '🔵',
                                                default => '⚪'
                                            };
                                        @endphp
                                        <span class="text-lg mt-0.5">{{ $activityIcon }}</span>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase">Last Activity</span>
                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $lastActivity->paid_at->diffForHumans() }}</span>
                                            </div>
                                            @if($lastActivity->note)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-1">{{ $lastActivity->note }}</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Salary & Last Payment Info -->
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700 space-y-2">
                                <div class="grid grid-cols-2 gap-3">
                                    <!-- Monthly Salary -->
                                    <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-lg p-3 border border-gray-700 dark:border-gray-800 shadow-md">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-300 uppercase">Salary</span>
                                        </div>
                                        @if($employee->salary)
                                            <p class="text-sm font-bold text-white">{{ number_format($employee->salary, 0) }}</p>
                                            <p class="text-xs text-gray-400">{{ $employee->currency ?? 'USD' }}/month</p>
                                        @else
                                            <p class="text-xs text-gray-400">Not set</p>
                                        @endif
                                    </div>

                                    <!-- Last Payment -->
                                    <div class="bg-gradient-to-br from-gray-800 to-black dark:from-gray-900 dark:to-black rounded-lg p-3 border border-gray-700 dark:border-gray-800 shadow-md">
                                        <div class="flex items-center gap-1.5 mb-1">
                                            <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span class="text-xs font-semibold text-gray-300 uppercase">Last Paid</span>
                                        </div>
                                        @if($lastPayment)
                                            <p class="text-sm font-bold text-white">{{ number_format($lastPayment->amount, 0) }}</p>
                                            <p class="text-xs text-gray-400">{{ $lastPayment->paid_at->format('M d') }}</p>
                                        @else
                                            <p class="text-xs text-gray-400">No payment</p>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Quick Stats Row -->
                            <div class="pt-3 border-t border-gray-200 dark:border-gray-700">
                                <div class="grid grid-cols-2 gap-2 text-xs">
                                    <div class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <span class="truncate">
                                            @if($employee->hired_at)
                                                {{ \Carbon\Carbon::parse($employee->hired_at)->diffForHumans(null, true) }}
                                            @else
                                                No hire date
                                            @endif
                                        </span>
                                    </div>
                                    <div class="flex items-center gap-1.5 text-gray-600 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <span>{{ $totalActivities }} activities</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer with Actions -->
                        <div class="px-4 pb-4 flex items-center gap-2">
                            <a href="{{ route('employees.show', $employee) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2 bg-black text-white rounded-full shadow hover:bg-gray-800 transition-all group-hover:shadow-lg text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                View Full Profile
                            </a>
                            <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center justify-center p-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all" title="Edit Employee">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('employees.destroy', $employee) }}" onsubmit="return confirm('Are you sure you want to delete this employee? This action can be undone from the Deleted Users tab.');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center justify-center p-2 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded-full hover:bg-red-200 dark:hover:bg-red-900/40 transition-all" title="Delete Employee">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div class="bg-white dark:bg-gray-800 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-2xl p-12 text-center">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No employees found</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                @if(request()->hasAny(['q', 'status', 'department']))
                                    Try adjusting your search or filters to find what you're looking for.
                                @else
                                    Get started by adding your first employee to the system.
                                @endif
                            </p>
                            <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Your First Employee
                            </a>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($employees->hasPages())
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-4 sm:p-6">
                    {{ $employees->links() }}
                </div>
            @endif

    </div>
</x-app-layout>