<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center gap-3">
                <a href="{{ route('attendance.index') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-full shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Attendance
                </a>
                <a href="{{ route('contracts.index') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 text-white rounded-full shadow hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Contracts
                </a>
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">Employees</a>
                <a href="{{ route('employees.create') }}" class="inline-flex items-center px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">+ Add Employee</a>
            </div>
        </div>
    </x-slot>

    @php
        $employeesCount = \App\Models\Employee::whereNull('discontinued_at')->count();
        $monthlyByCurrency = \App\Models\Employee::whereNull('discontinued_at')
            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(salary) as total')
            ->groupBy('currency')->get();
        $paid30ByCurrency = \App\Models\EmployeePayment::where('paid_at', '>=', now()->subDays(30))
            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(amount) as total')
            ->groupBy('currency')->get();
        $paymentsMonthCount = \App\Models\EmployeePayment::whereBetween('paid_at', [now()->startOfMonth(), now()->endOfMonth()])->count();
        $paymentsRecent = \App\Models\EmployeePayment::with('employee')->orderByDesc('paid_at')->limit(6)->get();
        $newHires = \App\Models\Employee::whereNull('discontinued_at')->whereNotNull('hired_at')->orderByDesc('hired_at')->limit(6)->get();
        $contractsCount = \App\Models\EmploymentContract::count();
        $activeContractsCount = \App\Models\EmploymentContract::where('status', 'active')->count();
        $recentContracts = \App\Models\EmploymentContract::with('employee')->latest()->limit(6)->get();
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- KPI cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total Employees</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $employeesCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Payments this month</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $paymentsMonthCount }}</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Total Contracts</div>
                    <div class="mt-2 text-3xl font-semibold text-gray-900 dark:text-white">{{ $contractsCount }}</div>
                    <div class="mt-1 text-xs text-gray-500 dark:text-gray-400">{{ $activeContractsCount }} active</div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Monthly Payroll</div>
                    <div class="mt-3 space-y-1">
                        @forelse ($monthlyByCurrency as $row)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-300">{{ $row->currency }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($row->total, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">No salary data</div>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6">
                    <div class="text-sm text-gray-600 dark:text-gray-300">Paid last 30 days</div>
                    <div class="mt-3 space-y-1">
                        @forelse ($paid30ByCurrency as $row)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600 dark:text-gray-300">{{ $row->currency }}</span>
                                <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($row->total, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-gray-400">No recent payments</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Two columns: Recent payments and New hires -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold dark:text-white">Recent Payments</h3>
                        <a href="{{ route('employees.index') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-black">View all</a>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($paymentsRecent as $p)
                                <li class="py-3 flex items-center justify-between">
                                    <div>
                                        <div class="font-medium dark:text-white">{{ optional($p->employee)->first_name }} {{ optional($p->employee)->last_name }}</div>
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ \Carbon\Carbon::parse($p->paid_at)->toFormattedDateString() }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if($p->amount)
                                            <div class="font-semibold text-gray-900 dark:text-white">{{ number_format($p->amount, 2) }} {{ $p->currency ?? optional($p->employee)->currency ?? 'USD' }}</div>
                                        @else
                                            <div class="text-sm text-gray-500 dark:text-gray-400">Note only</div>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="py-6 text-gray-500 dark:text-gray-400 text-sm">No payments yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">
                        <h3 class="text-lg font-semibold dark:text-white">Recent Contracts</h3>
                        <a href="{{ route('contracts.index') }}" class="text-sm text-gray-700 dark:text-gray-300 hover:text-black">View all</a>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse ($recentContracts as $contract)
                                <li class="py-3">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="font-medium dark:text-white">{{ optional($contract->employee)->first_name }} {{ optional($contract->employee)->last_name }}</div>
                                        @php
                                            $statusColors = [
                                                'draft' => 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300',
                                                'active' => 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300',
                                                'terminated' => 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-300',
                                                'expired' => 'bg-orange-100 text-orange-700 dark:bg-orange-900 dark:text-orange-300',
                                            ];
                                            $badgeColor = $statusColors[$contract->status] ?? $statusColors['draft'];
                                        @endphp
                                        <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $badgeColor }}">
                                            {{ ucwords($contract->status) }}
                                        </span>
                                    </div>
                                    <div class="flex items-center justify-between">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">{{ $contract->job_title }}</div>
                                        <a href="{{ route('contracts.pdf', $contract) }}" class="text-xs text-indigo-600 dark:text-indigo-400 hover:underline">Download PDF</a>
                                    </div>
                                </li>
                            @empty
                                <li class="py-6 text-gray-500 dark:text-gray-400 text-sm">No contracts yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Forecast quick tiles -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-semibold dark:text-white">Payroll Forecast</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">Based on current employee monthly salaries, grouped by currency.</p>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($monthlyByCurrency as $row)
                        @php($curr = $row->currency)
                        @php($monthly = (float) $row->total)
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $curr }} • 4 months</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($monthly * 4, 2) }} {{ $curr }}</div>
                        </div>
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $curr }} • 6 months</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($monthly * 6, 2) }} {{ $curr }}</div>
                        </div>
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $curr }} • 8 months</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($monthly * 8, 2) }} {{ $curr }}</div>
                        </div>
                        <div class="rounded-xl border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-300">{{ $curr }} • 12 months</div>
                            <div class="mt-1 text-xl font-semibold text-gray-900 dark:text-white">{{ number_format($monthly * 12, 2) }} {{ $curr }}</div>
                        </div>
                    @endforeach
                    @if($monthlyByCurrency->isEmpty())
                        <div class="text-sm text-gray-500 dark:text-gray-400">No salary data available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
