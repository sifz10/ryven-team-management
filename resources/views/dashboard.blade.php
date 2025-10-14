<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="flex items-center gap-3">
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
    @endphp

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- KPI cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-sm text-gray-600">Total Employees</div>
                    <div class="mt-2 text-3xl font-semibold">{{ $employeesCount }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-sm text-gray-600">Payments this month</div>
                    <div class="mt-2 text-3xl font-semibold">{{ $paymentsMonthCount }}</div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-sm text-gray-600">Monthly Payroll</div>
                    <div class="mt-3 space-y-1">
                        @forelse ($monthlyByCurrency as $row)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $row->currency }}</span>
                                <span class="font-semibold">{{ number_format($row->total, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">No salary data</div>
                        @endforelse
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                    <div class="text-sm text-gray-600">Paid last 30 days</div>
                    <div class="mt-3 space-y-1">
                        @forelse ($paid30ByCurrency as $row)
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">{{ $row->currency }}</span>
                                <span class="font-semibold">{{ number_format($row->total, 2) }}</span>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500">No recent payments</div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Two columns: Recent payments and New hires -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold">Recent Payments</h3>
                        <a href="{{ route('employees.index') }}" class="text-sm text-gray-700 hover:text-black">View all</a>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            @forelse ($paymentsRecent as $p)
                                <li class="py-3 flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">{{ optional($p->employee)->first_name }} {{ optional($p->employee)->last_name }}</div>
                                        <div class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($p->paid_at)->toFormattedDateString() }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if($p->amount)
                                            <div class="font-semibold">{{ number_format($p->amount, 2) }} {{ $p->currency ?? optional($p->employee)->currency ?? 'USD' }}</div>
                                        @else
                                            <div class="text-sm text-gray-500">Note only</div>
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="py-6 text-gray-500 text-sm">No payments yet.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold">New Hires</h3>
                    </div>
                    <div class="p-6">
                        <ul class="divide-y divide-gray-200">
                            @forelse ($newHires as $e)
                                <li class="py-3 flex items-center justify-between">
                                    <div>
                                        <div class="font-medium">{{ $e->first_name }} {{ $e->last_name }}</div>
                                        <div class="text-sm text-gray-600">Hired {{ \Carbon\Carbon::parse($e->hired_at)->diffForHumans() }}</div>
                                    </div>
                                    <div class="text-right text-sm text-gray-600">
                                        @if($e->salary)
                                            {{ number_format($e->salary, 2) }} {{ $e->currency ?? 'USD' }}/mo
                                        @else
                                            —
                                        @endif
                                    </div>
                                </li>
                            @empty
                                <li class="py-6 text-gray-500 text-sm">No new hires.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Forecast quick tiles -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold">Payroll Forecast</h3>
                    <p class="text-sm text-gray-600 mt-1">Based on current employee monthly salaries, grouped by currency.</p>
                </div>
                <div class="p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ($monthlyByCurrency as $row)
                        @php($curr = $row->currency)
                        @php($monthly = (float) $row->total)
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-600">{{ $curr }} • 4 months</div>
                            <div class="mt-1 text-xl font-semibold">{{ number_format($monthly * 4, 2) }} {{ $curr }}</div>
                        </div>
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-600">{{ $curr }} • 6 months</div>
                            <div class="mt-1 text-xl font-semibold">{{ number_format($monthly * 6, 2) }} {{ $curr }}</div>
                        </div>
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-600">{{ $curr }} • 8 months</div>
                            <div class="mt-1 text-xl font-semibold">{{ number_format($monthly * 8, 2) }} {{ $curr }}</div>
                        </div>
                        <div class="rounded-xl border border-gray-200 p-4">
                            <div class="text-sm text-gray-600">{{ $curr }} • 12 months</div>
                            <div class="mt-1 text-xl font-semibold">{{ number_format($monthly * 12, 2) }} {{ $curr }}</div>
                        </div>
                    @endforeach
                    @if($monthlyByCurrency->isEmpty())
                        <div class="text-sm text-gray-500">No salary data available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
