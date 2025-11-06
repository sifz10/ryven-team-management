<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Welcome back! Here's what's happening today.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white dark:bg-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all duration-200 font-semibold focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black dark:focus:ring-white shadow-sm hover:shadow-md">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Add Employee</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Top KPI Cards with Icons -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <!-- Total Employees -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Employees</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $employeesCount }}</div>
                    <div class="mt-2 flex items-center text-xs text-gray-500 dark:text-gray-400">
                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 rounded-full">{{ $discontinuedCount }} discontinued</span>
                    </div>
                </div>

                <!-- Payments This Month -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        @if($paymentTrend > 0)
                            <span class="flex items-center text-xs font-medium text-green-600 dark:text-green-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                {{ $paymentTrend }}%
                            </span>
                        @elseif($paymentTrend < 0)
                            <span class="flex items-center text-xs font-medium text-red-600 dark:text-red-400">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                </svg>
                                {{ abs($paymentTrend) }}%
                            </span>
                        @endif
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Payments This Month</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $paymentsMonthCount }}</div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">vs {{ $paymentsLastMonthCount }} last month</div>
                </div>

                <!-- New Hires -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">New Hires (30 days)</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $newHires }}</div>
                    <div class="mt-2 text-xs text-gray-500 dark:text-gray-400">Recent additions</div>
                </div>

                <!-- Contracts -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div class="p-3 bg-black rounded-xl">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Contracts</div>
                    <div class="mt-1 text-3xl font-bold text-gray-900 dark:text-white">{{ $contractsCount }}</div>
                    <div class="mt-2 flex items-center gap-2 text-xs">
                        <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 rounded-full">{{ $activeContractsCount }} active</span>
                        @if($draftContractsCount > 0)
                            <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full">{{ $draftContractsCount }} draft</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Monthly Payroll Overview -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Monthly Payroll Overview</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Current monthly obligations by currency</p>
                            </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($monthlyByCurrency as $row)
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition-shadow">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="px-3 py-1 bg-black text-white rounded-lg text-sm font-bold">{{ $row->currency }}</span>
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="text-3xl font-bold text-gray-900 dark:text-white mb-1">{{ number_format($row->total, 2) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Monthly total</div>
                                <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                    <div class="text-xs text-gray-600 dark:text-gray-400 space-y-1">
                                        <div class="flex justify-between">
                                            <span>Quarterly:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($row->total * 3, 0) }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span>Annually:</span>
                                            <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($row->total * 12, 0) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p>No salary data available</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Payments -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-black rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Payments</h3>
                            </div>
                            <a href="{{ route('employees.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">View all →</a>
                        </div>
                    </div>
                    <div class="p-6">
                            @forelse ($paymentsRecent as $p)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr(optional($p->employee)->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(optional($p->employee)->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ optional($p->employee)->first_name }} {{ optional($p->employee)->last_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ \Carbon\Carbon::parse($p->paid_at)->format('M d, Y') }}</div>
                                    </div>
                                    </div>
                                    <div class="text-right">
                                        @if($p->amount)
                                        <div class="font-bold text-gray-900 dark:text-white">{{ number_format($p->amount, 2) }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $p->currency ?? optional($p->employee)->currency ?? 'USD' }}</div>
                                        @else
                                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400 rounded-full text-xs">Note</span>
                                        @endif
                                    </div>
                            </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                <p class="text-sm">No payments yet</p>
                            </div>
                            @endforelse
                    </div>
                </div>

                <!-- Recent Contracts -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-black rounded-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Contracts</h3>
                            </div>
                            <a href="{{ route('contracts.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">View all →</a>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse ($recentContracts as $contract)
                            <div class="flex items-center justify-between py-3 border-b border-gray-200 dark:border-gray-700 last:border-0">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-black flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr(optional($contract->employee)->first_name ?? 'U', 0, 1)) }}{{ strtoupper(substr(optional($contract->employee)->last_name ?? 'N', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-semibold text-gray-900 dark:text-white">{{ optional($contract->employee)->first_name }} {{ optional($contract->employee)->last_name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $contract->job_title }}</div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    @php
                                        $statusColors = [
                                            'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                            'active' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                            'terminated' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                            'expired' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                        ];
                                        $badgeColor = $statusColors[$contract->status] ?? $statusColors['draft'];
                                    @endphp
                                    <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                        {{ ucwords($contract->status) }}
                                    </span>
                                    <a href="{{ route('contracts.pdf', $contract) }}" class="p-1 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition">
                                        <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </a>
                                </div>
                                    </div>
                            @empty
                            <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-sm">No contracts yet</p>
                            </div>
                            @endforelse
                    </div>
                </div>
            </div>

            <!-- Recent Activity Log -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Activity Log</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Latest employee activities and updates</p>
                            </div>
                        </div>
                        <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                            {{ $recentActivities->count() }} activities
                        </span>
                    </div>
                </div>
                <div class="divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($recentActivities as $activity)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors group">
                            <div class="flex gap-3">
                                <!-- Timeline Dot with Color -->
                                @php
                                    $dotColors = [
                                        'achievement' => 'bg-green-500 text-white border-green-200 dark:border-green-900',
                                        'warning' => 'bg-red-500 text-white border-red-200 dark:border-red-900',
                                        'payment' => 'bg-blue-500 text-white border-blue-200 dark:border-blue-900',
                                        'note' => 'bg-gray-500 text-white border-gray-200 dark:border-gray-700',
                                    ];
                                    $dotColor = $dotColors[$activity['type']] ?? $dotColors['note'];
                                    
                                    $badgeEmoji = match($activity['type']) {
                                        'achievement' => '🟢',
                                        'warning' => '🔴',
                                        'payment' => '🔵',
                                        default => '⚪'
                                    };
                                @endphp
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 rounded-full {{ $dotColor }} flex items-center justify-center font-semibold shadow-md text-base border-2 group-hover:scale-110 transition-transform">
                                        {{ $activity['icon'] }}
                                    </div>
                                </div>

                                <!-- Activity Content -->
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-start justify-between gap-3 mb-1.5">
                                        <div class="flex items-center gap-2 flex-wrap">
                                            @php
                                                $typeColors = [
                                                    'achievement' => 'bg-green-50 text-green-700 dark:bg-green-500/20 dark:text-green-300 border-green-300 dark:border-green-500/30',
                                                    'warning' => 'bg-red-50 text-red-700 dark:bg-red-500/20 dark:text-red-300 border-red-300 dark:border-red-500/30',
                                                    'payment' => 'bg-blue-50 text-blue-700 dark:bg-blue-500/20 dark:text-blue-300 border-blue-300 dark:border-blue-500/30',
                                                    'note' => 'bg-gray-100 text-gray-700 dark:bg-gray-500/20 dark:text-gray-300 border-gray-300 dark:border-gray-500/30',
                                                ];
                                                $typeBadge = $typeColors[$activity['type']] ?? $typeColors['note'];
                                            @endphp
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $typeBadge }}">
                                                <span class="mr-1">{{ $badgeEmoji }}</span>
                                                <span class="uppercase tracking-wide">{{ $activity['type'] }}</span>
                                            </span>
                                            @if($activity['employee'])
                                                <a href="{{ route('employees.show', ['employee' => $activity['employee'], 'tab' => 'timeline']) }}" 
                                                   class="font-semibold text-gray-900 dark:text-white hover:text-black dark:hover:text-gray-200 transition">
                                                    {{ $activity['employee']->first_name }} {{ $activity['employee']->last_name }}
                                                </a>
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 whitespace-nowrap">
                                            {{ $activity['date']->diffForHumans() }}
                                        </div>
                                    </div>
                                    
                                    @if($activity['description'])
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-2 line-clamp-2">{{ $activity['description'] }}</p>
                                    @endif
                                    
                                    @if($activity['amount'])
                                        <div class="inline-flex items-center px-2.5 py-1 bg-gray-100 dark:bg-gray-700 rounded-lg text-sm font-semibold text-gray-900 dark:text-white border border-gray-200 dark:border-gray-600">
                                            <svg class="w-3.5 h-3.5 mr-1.5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ number_format($activity['amount'], 2) }} {{ $activity['currency'] }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-sm">No activities yet</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- ====================================== -->
            <!-- ADVANCED ANALYTICS SECTION -->
            <!-- ====================================== -->

            <!-- Analytics Header -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">📊 Advanced Analytics</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Comprehensive insights and performance metrics</p>
                </div>
            </div>

            <!-- Performance Trends (Last 6 Months) -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Performance Trends</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Achievements vs Warnings over last 6 months</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        @foreach($performanceTrends as $trend)
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                                <div class="text-xs font-semibold text-gray-600 dark:text-gray-400 mb-3">{{ $trend['month'] }}</div>
                                <div class="space-y-2">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">🟢 Achievements</span>
                                        <span class="text-sm font-bold text-green-600 dark:text-green-400">{{ $trend['achievements'] }}</span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs text-gray-600 dark:text-gray-400">🔴 Warnings</span>
                                        <span class="text-sm font-bold text-red-600 dark:text-red-400">{{ $trend['warnings'] }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Department Analytics & Top Performers -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Department Analytics -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Department Analytics</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Performance by department</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            @forelse($departmentAnalytics as $dept)
                                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center justify-between mb-3">
                                        <div class="font-bold text-gray-900 dark:text-white">{{ $dept['department'] }}</div>
                                        <span class="px-2 py-1 bg-black text-white rounded-lg text-xs font-semibold">{{ $dept['employee_count'] }} employees</span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 text-xs">
                                        <div class="text-center p-2 bg-gray-50 dark:bg-gray-900 rounded-lg">
                                            <div class="text-gray-600 dark:text-gray-400">Avg Salary</div>
                                            <div class="font-bold text-gray-900 dark:text-white mt-1">${{ number_format($dept['avg_salary']) }}</div>
                                        </div>
                                        <div class="text-center p-2 bg-green-50 dark:bg-green-900/20 rounded-lg">
                                            <div class="text-gray-600 dark:text-gray-400">🟢 Achievements</div>
                                            <div class="font-bold text-green-600 dark:text-green-400 mt-1">{{ $dept['achievements'] }}</div>
                                        </div>
                                        <div class="text-center p-2 bg-red-50 dark:bg-red-900/20 rounded-lg">
                                            <div class="text-gray-600 dark:text-gray-400">🔴 Warnings</div>
                                            <div class="font-bold text-red-600 dark:text-red-400 mt-1">{{ $dept['warnings'] }}</div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <p class="text-sm">No department data available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Top Performers -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Top Performers</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Most achievements (last 3 months)</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-3">
                            @forelse($topPerformers as $index => $performer)
                                <div class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                                    <div class="flex-shrink-0">
                                        @php
                                            $rankColors = [
                                                0 => 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-white',
                                                1 => 'bg-gradient-to-br from-gray-300 to-gray-500 text-white',
                                                2 => 'bg-gradient-to-br from-orange-400 to-orange-600 text-white',
                                            ];
                                            $color = $rankColors[$index] ?? 'bg-gray-200 dark:bg-gray-700 text-gray-700 dark:text-gray-300';
                                        @endphp
                                        <div class="w-10 h-10 rounded-full {{ $color }} flex items-center justify-center font-bold text-lg shadow-lg">
                                            #{{ $index + 1 }}
                                        </div>
                                    </div>
                                    <div class="flex-1">
                                        @if($performer->employee)
                                            <a href="{{ route('employees.show', $performer->employee) }}" class="font-bold text-gray-900 dark:text-white hover:text-black dark:hover:text-gray-200">
                                                {{ $performer->employee->first_name }} {{ $performer->employee->last_name }}
                                            </a>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $performer->employee->position ?? 'No position' }}</div>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $performer->achievement_count }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">achievements</div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    <p class="text-sm">No achievement data available</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Attendance & GitHub Stats -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Attendance Overview -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Attendance Overview</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Last 30 days statistics</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl border border-green-200 dark:border-green-700 p-4">
                                <div class="text-sm font-medium text-green-700 dark:text-green-300 mb-1">Present</div>
                                <div class="text-3xl font-bold text-green-600 dark:text-green-400">{{ $attendanceStats['present'] }}</div>
                            </div>
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl border border-red-200 dark:border-red-700 p-4">
                                <div class="text-sm font-medium text-red-700 dark:text-red-300 mb-1">Absent</div>
                                <div class="text-3xl font-bold text-red-600 dark:text-red-400">{{ $attendanceStats['absent'] }}</div>
                            </div>
                        </div>
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Overall Attendance Rate</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $attendanceStats['attendance_rate'] }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3">
                                <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full" style="width: {{ $attendanceStats['attendance_rate'] }}%"></div>
                            </div>
                            <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                                {{ $attendanceStats['late'] }} late arrivals • {{ $attendanceStats['total_records'] }} total records
                            </div>
                        </div>
                    </div>
                </div>

                <!-- GitHub Activity Stats -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.430.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">GitHub Activity</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Last 30 days development metrics</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-purple-50 dark:bg-purple-900/20 rounded-xl border border-purple-200 dark:border-purple-700 p-4">
                                <div class="text-sm font-medium text-purple-700 dark:text-purple-300 mb-1">Total Commits</div>
                                <div class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ $githubStats['total_commits'] }}</div>
                            </div>
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-700 p-4">
                                <div class="text-sm font-medium text-blue-700 dark:text-blue-300 mb-1">Pull Requests</div>
                                <div class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ $githubStats['pull_requests'] }}</div>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3 text-center">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $githubStats['pushes'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Pushes</div>
                            </div>
                            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-3 text-center">
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $githubStats['reviews'] }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Code Reviews</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Employee Growth & Payment History Charts -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Employee Growth (12 Months) -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Employee Growth</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Team size over last 12 months</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach($employeeGrowth as $month)
                                <div class="flex items-center gap-3">
                                    <div class="w-20 text-xs font-medium text-gray-600 dark:text-gray-400">{{ $month['month'] }}</div>
                                    <div class="flex-1 h-8 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 flex items-center px-3" style="width: {{ $month['count'] > 0 ? min(($month['count'] / max(array_column($employeeGrowth, 'count'))) * 100, 100) : 0 }}%">
                                            <span class="text-white text-xs font-bold">{{ $month['count'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Payment History (12 Months) -->
                <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-black rounded-lg">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Payment Activity</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Monthly payment volume</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6">
                        <div class="space-y-2">
                            @foreach($paymentHistory as $month)
                                <div class="flex items-center gap-3">
                                    <div class="w-20 text-xs font-medium text-gray-600 dark:text-gray-400">{{ $month['month'] }}</div>
                                    <div class="flex-1 h-8 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden">
                                        <div class="h-full bg-gradient-to-r from-green-500 to-green-600 flex items-center px-3" style="width: {{ $month['count'] > 0 ? min(($month['count'] / max(array_column($paymentHistory, 'count'))) * 100, 100) : 0 }}%">
                                            <span class="text-white text-xs font-bold">{{ $month['count'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-gradient-to-br from-gray-50 to-white dark:from-gray-800 dark:to-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3">
                        <div class="p-2 bg-black rounded-lg">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Quick Actions</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Common tasks and shortcuts</p>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('employees.create') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">Add Employee</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Create new record</div>
                            </div>
                        </a>

                        <a href="{{ route('attendance.index') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">Attendance</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Track hours</div>
                            </div>
                        </a>

                        <a href="{{ route('contracts.index') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">View Contracts</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Manage documents</div>
                            </div>
                        </a>

                        <a href="{{ route('employees.index') }}" class="flex items-center gap-4 p-4 bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 hover:shadow-lg hover:border-gray-300 dark:hover:border-gray-600 transition-all group">
                            <div class="p-3 bg-black group-hover:bg-gray-800 rounded-lg transition-colors">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold text-gray-900 dark:text-white">All Employees</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Browse team</div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
    </div>
</x-app-layout>
