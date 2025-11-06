<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Welcome Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                                Welcome back, {{ $employee->first_name }}! 👋
                            </h2>
                            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $employee->position }} • {{ $employee->department }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-3">
                            <div class="text-right mr-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Employee Since</p>
                                <p class="text-gray-900 dark:text-white font-medium">{{ $employee->hired_at->format('M d, Y') }}</p>
                            </div>
                            <x-black-button href="{{ route('employee.profile.edit') }}" class="inline-flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                View Profile
                            </x-black-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Total Payments -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Total Payments</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['total_payments'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">{{ $stats['this_month_payments'] }} this month</p>
                </div>

                <!-- Attendance -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Attendance</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['attendance_percentage'] }}%</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Current month</p>
                </div>

                <!-- GitHub Activities -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">GitHub Activities</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['github_activities'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.840 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">Total contributions</p>
                </div>

                <!-- Active Goals -->
                <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 dark:text-gray-400 text-sm">Active Goals</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $stats['active_goals'] }}</p>
                        </div>
                        <div class="w-12 h-12 bg-yellow-500/10 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-2">In progress</p>
                </div>
            </div>

            <!-- Today's Checklist -->
            @if($todayChecklist)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-xl font-bold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Today's Checklist
                    </h3>
                </div>
                <div class="p-6">
                    <div class="space-y-3">
                        @forelse($todayChecklist->items as $item)
                        <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                            <input
                                type="checkbox"
                                {{ $item->is_completed ? 'checked' : '' }}
                                class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-blue-500 focus:ring-blue-500"
                                disabled
                            >
                            <span class="text-gray-700 dark:text-gray-300 {{ $item->is_completed ? 'line-through text-gray-400 dark:text-gray-500' : '' }}">
                                {{ $item->item }}
                            </span>
                        </div>
                        @empty
                        <p class="text-gray-600 dark:text-gray-400 text-center py-4">No checklist items for today</p>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

            <!-- Recent Activity Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Payments -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent Payments</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($employee->payments->take(5) as $payment)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                <div>
                                    <p class="text-gray-900 dark:text-white font-medium">{{ $payment->currency }} {{ number_format($payment->amount, 2) }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $payment->paid_at->format('M d, Y') }}</p>
                                </div>
                                <span class="px-3 py-1 text-xs font-medium bg-green-500/10 text-green-500 rounded-full">
                                    {{ $payment->activity_type }}
                                </span>
                            </div>
                            @empty
                            <p class="text-gray-600 dark:text-gray-400 text-center py-8">No payment records yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Recent GitHub Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Recent GitHub Activity</h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($employee->githubLogs->take(5) as $log)
                            <div class="flex items-start gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg">
                                <div class="w-8 h-8 bg-purple-500/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900 dark:text-white truncate">{{ $log->event_type }}</p>
                                    <p class="text-xs text-gray-600 dark:text-gray-400">{{ $log->event_at->diffForHumans() }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-gray-600 dark:text-gray-400 text-center py-8">No GitHub activity yet</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
