<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Employment Contracts') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Manage and track employment contracts</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 sm:px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full shadow hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                    <span class="hidden sm:inline">Employees</span>
                    <span class="sm:hidden">Emps</span>
                </a>
                <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 sm:px-5 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    <span class="hidden sm:inline">Dashboard</span>
                    <span class="sm:hidden">Home</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
            @if(session('status'))
                <div class="mb-6 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Filter/Stats Bar -->
            <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Total Contracts</div>
                    <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $contracts->total() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Active</div>
                    <div class="text-2xl font-bold text-green-600 dark:text-green-400 mt-1">{{ \App\Models\EmploymentContract::where('status', 'active')->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Draft</div>
                    <div class="text-2xl font-bold text-gray-600 dark:text-gray-400 mt-1">{{ \App\Models\EmploymentContract::where('status', 'draft')->count() }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                    <div class="text-sm text-gray-600 dark:text-gray-400">Terminated</div>
                    <div class="text-2xl font-bold text-red-600 dark:text-red-400 mt-1">{{ \App\Models\EmploymentContract::where('status', 'terminated')->count() }}</div>
                </div>
            </div>

            <!-- Contracts List -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @forelse($contracts as $contract)
                        <div class="mb-4 last:mb-0 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                            <div class="p-6">
                                <div class="flex items-start justify-between mb-4">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <a href="{{ route('employees.show', $contract->employee) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">
                                                {{ $contract->employee->first_name }} {{ $contract->employee->last_name }}
                                            </a>
                                            @php
                                                $statusColors = [
                                                    'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                    'active' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                    'terminated' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                    'expired' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                                ];
                                                $badgeColor = $statusColors[$contract->status] ?? $statusColors['draft'];
                                            @endphp
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                                {{ ucwords($contract->status) }}
                                            </span>
                                            <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                                {{ ucwords(str_replace('_', ' ', $contract->contract_type)) }}
                                            </span>
                                        </div>
                                        <h4 class="text-base font-semibold text-gray-700 dark:text-gray-300 mb-1">{{ $contract->job_title }}</h4>
                                        @if($contract->department)
                                            <p class="text-sm text-gray-600 dark:text-gray-400">{{ $contract->department }}</p>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('contracts.pdf', $contract) }}" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                            PDF
                                        </a>
                                        <a href="{{ route('employees.show', $contract->employee) }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition text-sm font-medium">
                                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                            View Employee
                                        </a>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Salary</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ number_format($contract->salary, 2) }} {{ $contract->currency }}</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Start Date</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->start_date->format('M d, Y') }}</p>
                                    </div>
                                    @if($contract->end_date)
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">End Date</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->end_date->format('M d, Y') }}</p>
                                    </div>
                                    @endif
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Working Hours</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->working_hours_per_week }}h/week</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Annual Leave</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->annual_leave_days }} days</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Created</p>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->created_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No contracts yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Go to an employee profile to create their first employment contract</p>
                            <a href="{{ route('employees.index') }}" class="inline-flex items-center px-5 py-2.5 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                View Employees
                            </a>
                        </div>
                    @endforelse

                    <!-- Pagination -->
                    @if($contracts->hasPages())
                        <div class="mt-6">
                            {{ $contracts->links() }}
                        </div>
                    @endif
                </div>
            </div>
    </div>
</x-app-layout>

