<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-white font-bold text-lg shadow-lg">
                    {{ strtoupper(substr($employee->first_name, 0, 1)) }}{{ strtoupper(substr($employee->last_name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-100 leading-tight flex items-center gap-2">
                {{ $employee->first_name }} {{ $employee->last_name }}
                @if($employee->discontinued_at)
                            <span class="px-2 py-0.5 text-xs bg-red-100 text-red-700 rounded-full">Discontinued</span>
                @endif
            </h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $employee->position }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('employees.index') }}" class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back
                </a>
                <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                <a href="{{ route('contracts.create', $employee) }}" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Create Contract
                </a>
                @if(!$employee->discontinued_at)
                    <form method="POST" action="{{ route('employees.discontinue', $employee) }}" onsubmit="return confirm('Mark this employee as discontinued?')">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-full shadow-lg hover:bg-red-700 hover:shadow-xl transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Discontinue
                        </button>
                    </form>
                @else
                    <form method="POST" action="{{ route('employees.reactivate', $employee) }}">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-full shadow-lg hover:bg-green-700 hover:shadow-xl transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Reactivate
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div x-data="{ tab: getInitialTab() }" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Employee Info Card -->
            <div class="mb-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Email</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $employee->email }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Phone</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $employee->phone }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Hired Date</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ \Carbon\Carbon::parse($employee->hired_at)->format('M d, Y') }}</div>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide">Currency</div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white mt-1">{{ $employee->currency ?? 'USD' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                    </div>

                    @php
                        $currencyCode = $employee->currency ?? 'USD';
                        $monthlySalary = (float) ($employee->salary ?? 0);
                        $forecastMonths = [4, 6, 8, 12];
                        $currencies = ['USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 'BDT' => 'Bangladeshi Taka', 'INR' => 'Indian Rupee'];

                        $from = request('from');
                        $to = request('to');

                        $paymentsQuery = \App\Models\EmployeePayment::where('employee_id', $employee->id);
                        if ($from) { $paymentsQuery->whereDate('paid_at', '>=', $from); }
                        if ($to) { $paymentsQuery->whereDate('paid_at', '<=', $to); }
                        $payments = $paymentsQuery->orderByDesc('created_at')->get();

                        $totalPaid = \App\Models\EmployeePayment::where('employee_id', $employee->id)
                            ->whereNotNull('amount')
                            ->when($from, fn($q) => $q->whereDate('paid_at', '>=', $from))
                            ->when($to, fn($q) => $q->whereDate('paid_at', '<=', $to))
                            ->sum('amount');

                        $orgPaidByCurrency = \App\Models\EmployeePayment::whereNotNull('amount')
                            ->when($from, fn($q) => $q->whereDate('paid_at', '>=', $from))
                            ->when($to, fn($q) => $q->whereDate('paid_at', '<=', $to))
                            ->selectRaw('COALESCE(currency, "USD") as currency, SUM(amount) as total')
                            ->groupBy('currency')
                            ->get();
                    @endphp

            <!-- Tabs Navigation -->
            <div class="mb-6 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-2">
                    <div class="flex flex-wrap items-center gap-2">
                        <button type="button" @click="tab='overview'; updateTabUrl('overview')" :class="tab==='overview' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            Overview
                        </button>
                        <button type="button" @click="tab='bank'; updateTabUrl('bank')" :class="tab==='bank' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                            Bank Accounts
                        </button>
                        <button type="button" @click="tab='access'; updateTabUrl('access')" :class="tab==='access' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                            </svg>
                            Shared Access
                        </button>
                        <button type="button" @click="tab='timeline'; updateTabUrl('timeline')" :class="tab==='timeline' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Activity Log
                        </button>
                        <button type="button" @click="tab='contracts'; updateTabUrl('contracts')" :class="tab==='contracts' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Contracts
                        </button>
                        <button type="button" @click="tab='org'; updateTabUrl('org')" :class="tab==='org' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            Organization
                        </button>
                        <button type="button" @click="tab='checklist'; updateTabUrl('checklist')" :class="tab==='checklist' ? 'bg-black text-white shadow-lg' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'" class="flex items-center gap-2 px-4 py-2.5 rounded-xl text-sm font-medium transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                            </svg>
                            Daily Checklist
                        </button>
                    </div>
                </div>
                        </div>

            <!-- Overview Tab -->
            <div x-cloak x-show="tab==='overview'" x-transition.opacity class="space-y-6">
                <!-- Filter Section -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                                <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Compensation Overview</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Summary of payments and projected cost based on current monthly salary</p>
                        </div>
                    </div>
                    <form method="GET" class="flex flex-wrap items-end gap-3">
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="from" value="From Date" />
                                    <x-text-input id="from" type="date" name="from" class="mt-1 block w-full" value="{{ request('from') }}" />
                                </div>
                        <div class="flex-1 min-w-[150px]">
                            <x-input-label for="to" value="To Date" />
                                    <x-text-input id="to" type="date" name="to" class="mt-1 block w-full" value="{{ request('to') }}" />
                                </div>
                        <div class="flex items-center gap-2">
                            <button type="submit" class="inline-flex items-center px-5 py-2.5 bg-black hover:bg-gray-800 text-white rounded-lg shadow-lg hover:shadow-xl transition font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path>
                                </svg>
                                Apply
                            </button>
                                    @if(request('from') || request('to'))
                                <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center px-4 py-2.5 text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition">
                                    Clear
                                </a>
                                    @endif
                                </div>
                            </form>
                            @if(request('from') || request('to'))
                        <div class="mt-3 px-3 py-2 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                            <p class="text-xs text-blue-700 dark:text-blue-300">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Filtering payments @if(request('from')) from {{ \Carbon\Carbon::parse(request('from'))->format('M d, Y') }} @endif @if(request('to')) to {{ \Carbon\Carbon::parse(request('to'))->format('M d, Y') }} @endif
                            </p>
                        </div>
                            @endif
                        </div>
                        
                <!-- Key Metrics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-xl">
                                <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Paid</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalPaid, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $currencyCode }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-xl">
                                <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Monthly Salary</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($monthlySalary, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $currencyCode }}</div>
                    </div>
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-xl">
                                <svg class="w-6 h-6 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Annual (12 mo)</div>
                        <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($monthlySalary * 12, 2) }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $currencyCode }}</div>
                    </div>
                </div>

                <!-- Cost Projections -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm p-6">
                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Cost Projections</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                                @foreach ($forecastMonths as $m)
                            <div class="bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-md transition">
                                <div class="flex items-center gap-2 mb-2">
                                    <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-gray-600 to-gray-700 flex items-center justify-center text-white text-xs font-bold">
                                        {{ $m }}
                                    </div>
                                    <div class="text-xs text-gray-600 dark:text-gray-400">months</div>
                                </div>
                                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ number_format($monthlySalary * $m, 0) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $currencyCode }}</div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
            </div>

            <!-- Contracts Tab -->
            <div x-cloak x-show="tab==='contracts'" x-transition.opacity>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Employment Contracts</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Manage and download employment contracts</p>
                                </div>
                            </div>
                            <a href="{{ route('contracts.create', $employee) }}" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Contract
                            </a>
                        </div>
                    </div>
                    <div class="p-6">
                        @forelse ($employee->contracts as $contract)
                            <div class="mb-4 last:mb-0 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:shadow-lg transition-shadow">
                                <div class="p-6">
                                    <div class="flex items-start justify-between mb-4">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $contract->job_title }}</h4>
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
                                            @if($contract->department)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $contract->department }}</p>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('contracts.pdf', $contract) }}" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition text-sm font-medium">
                                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                Download PDF
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
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Probation Period</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->probation_period_days }} days</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Notice Period</p>
                                            <p class="font-semibold text-gray-900 dark:text-white">{{ $contract->notice_period_days }} days</p>
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

                                    @if($contract->job_description || $contract->benefits)
                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                        @if($contract->job_description)
                                        <div class="mb-3">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Job Description</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($contract->job_description, 150) }}</p>
                                        </div>
                                        @endif
                                        @if($contract->benefits)
                                        <div>
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-1">Benefits</p>
                                            <p class="text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($contract->benefits, 150) }}</p>
                                        </div>
                                        @endif
                                    </div>
                                @endif
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
                                <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first employment contract for this employee</p>
                                <a href="{{ route('contracts.create', $employee) }}" class="inline-flex items-center px-5 py-2.5 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create First Contract
                                </a>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Organization Tab -->
            <div x-cloak x-show="tab==='org'" x-transition.opacity>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Organization Totals</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Cumulative payments across the organization (all time), by currency</p>
                            </div>
                        </div>
                        </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                            @forelse ($orgPaidByCurrency as $row)
                                <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 hover:shadow-lg transition">
                                    <div class="flex items-center justify-between mb-3">
                                        <span class="px-3 py-1 bg-white dark:bg-gray-700 rounded-lg text-xs font-bold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">{{ $row->currency }}</span>
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                    <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($row->total, 2) }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">Total paid in {{ $row->currency }}</div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">No payments recorded yet</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bank Accounts Section -->
            <div x-cloak x-show="tab==='bank'" x-transition.opacity>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Bank Accounts</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Manage employee bank account information</p>
                            </div>
                        </div>
                    </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('employees.bank-accounts.store', $employee) }}" class="mb-6 space-y-3">
                        @csrf
                        <div>
                            <x-input-label for="ba_title" value="Title" />
                            <x-text-input id="ba_title" type="text" name="title" class="mt-1 block w-full" placeholder="e.g. Primary USD account" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="ba_details" value="Details (Markdown)" />
                            <textarea id="ba_details" name="details_markdown" rows="4" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Account number, routing, bank name...&#10;&#10;You can use **bold**, lists, etc."></textarea>
                            <x-input-error :messages="$errors->get('details_markdown')" class="mt-2" />
                        </div>
                        <div>
                            <x-primary-button class="bg-black hover:bg-gray-800">Add account</x-primary-button>
                        </div>
                    </form>

                    <ul class="space-y-4">
                        @forelse($employee->bankAccounts as $account)
                            <li class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">{{ $account->title }}</div>
                                        <div class="prose prose-sm mt-2">
                                            {!! \Illuminate\Support\Str::of($account->details_markdown)->markdown() !!}
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-gray-700 text-sm" x-data @click="$dispatch('open-modal', 'edit-account-{{ $account->id }}')">Edit</button>
                                        <form method="POST" action="{{ route('employees.bank-accounts.destroy', [$employee, $account]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 text-sm" onclick="return confirm('Remove this account?')">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                <x-modal name="edit-account-{{ $account->id }}">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4">Edit Bank Account</h4>
                                        <form method="POST" action="{{ route('employees.bank-accounts.update', [$employee, $account]) }}" class="space-y-3">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <x-input-label for="title_{{ $account->id }}" value="Title" />
                                                <x-text-input id="title_{{ $account->id }}" type="text" name="title" class="mt-1 block w-full" value="{{ $account->title }}" required />
                                            </div>
                                            <div>
                                                <x-input-label for="details_{{ $account->id }}" value="Details (Markdown)" />
                                                <textarea id="details_{{ $account->id }}" name="details_markdown" rows="4" class="mt-1 block w-full border-gray-300 rounded-md">{{ $account->details_markdown }}</textarea>
                                            </div>
                                            <div class="flex items-center justify-end gap-3">
                                                <button type="button" class="text-gray-700" x-data @click="$dispatch('close-modal', 'edit-account-{{ $account->id }}')">Cancel</button>
                                                <x-primary-button class="bg-black hover:bg-gray-800">Save changes</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            </li>
                        @empty
                            <li class="text-gray-500">No bank accounts added.</li>
                        @endforelse
                    </ul>
                    </div>
                </div>
            </div>

            <!-- Shared Access section -->
            <div x-cloak x-show="tab==='access'" x-transition.opacity>
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm rounded-2xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Shared Access</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Manage shared accounts and access credentials</p>
                            </div>
                        </div>
                    </div>
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ route('employees.accesses.store', $employee) }}" class="mb-6 space-y-3" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="acc_title" value="Title" />
                            <x-text-input id="acc_title" type="text" name="title" class="mt-1 block w-full" placeholder="e.g. GitHub, AWS, CRM" required />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="acc_note" value="Note (Markdown)" />
                            <textarea id="acc_note" name="note_markdown" rows="3" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Access details, user names, scopes... (optional)"></textarea>
                            <x-input-error :messages="$errors->get('note_markdown')" class="mt-2" />
                        </div>
                        <div x-data="{ isDragging:false }" @dragover.prevent="isDragging=true" @dragleave.prevent="isDragging=false" @drop.prevent="isDragging=false" class="border-2 border-dashed rounded-lg p-4" :class="isDragging ? 'border-black bg-gray-50 dark:bg-gray-900' : 'border-gray-300 dark:border-gray-600'">
                            <x-input-label for="acc_file" value="Attachment (optional)" />
                            <input id="acc_file" name="attachment" type="file" class="mt-1 block w-full" />
                            <p class="text-xs text-gray-500 mt-1">Drag & drop or click to upload (max 10 MB).</p>
                        </div>
                        <div>
                            <x-primary-button class="bg-black hover:bg-gray-800">Add access</x-primary-button>
                        </div>
                    </form>

                    <ul class="space-y-4">
                        @forelse($employee->accesses as $access)
                            <li class="border border-gray-200 dark:border-gray-700 rounded-xl p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <div class="font-semibold">{{ $access->title }}</div>
                                        @if($access->note_markdown)
                                            <div class="prose prose-sm mt-2">{!! \Illuminate\Support\Str::of($access->note_markdown)->markdown() !!}</div>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button type="button" class="text-gray-700 text-sm" x-data @click="$dispatch('open-modal', 'edit-access-{{ $access->id }}')">Edit</button>
                                        <form method="POST" action="{{ route('employees.accesses.destroy', [$employee, $access]) }}">
                                            @csrf
                                            @method('DELETE')
                                            <button class="text-red-600 text-sm" onclick="return confirm('Remove this access?')">Delete</button>
                                        </form>
                                    </div>
                                    @if($access->attachment_path)
                                        <div class="mt-2 text-sm">
                                            <a href="{{ asset('storage/'.$access->attachment_path) }}" class="text-indigo-600" target="_blank">{{ $access->attachment_name ?? 'Download attachment' }}</a>
                                        </div>
                                    @endif
                                </div>

                                <x-modal name="edit-access-{{ $access->id }}">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4">Edit Access</h4>
                                        <form method="POST" action="{{ route('employees.accesses.update', [$employee, $access]) }}" class="space-y-3" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <x-input-label for="acc_title_{{ $access->id }}" value="Title" />
                                                <x-text-input id="acc_title_{{ $access->id }}" type="text" name="title" class="mt-1 block w-full" value="{{ $access->title }}" required />
                                            </div>
                                            <div>
                                                <x-input-label for="acc_note_{{ $access->id }}" value="Note (Markdown)" />
                                                <textarea id="acc_note_{{ $access->id }}" name="note_markdown" rows="3" class="mt-1 block w-full border-gray-300 rounded-md">{{ $access->note_markdown }}</textarea>
                                            </div>
                                            <div>
                                                <x-input-label for="acc_file_{{ $access->id }}" value="Attachment (optional)" />
                                                <input id="acc_file_{{ $access->id }}" name="attachment" type="file" class="mt-1 block w-full" />
                                            </div>
                                            <div class="flex items-center justify-end gap-3">
                                                <button type="button" class="text-gray-700" x-data @click="$dispatch('close-modal', 'edit-access-{{ $access->id }}')">Cancel</button>
                                                <x-primary-button class="bg-black hover:bg-gray-800">Save changes</x-primary-button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            </li>
                        @empty
                            <li class="text-gray-500">No shared access recorded.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
            </div>
            <!-- Activity Log Section -->
            <div x-cloak x-show="tab==='timeline'" x-transition.opacity>
                <div class="bg-white dark:bg-gradient-to-br dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm dark:shadow-lg rounded-2xl">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Log</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Track payments, achievements, and important events</p>
                                </div>
                            </div>
                            <span class="px-3 py-1.5 bg-gray-100 dark:bg-gray-700 rounded-lg text-xs font-semibold text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">{{ $payments->count() }} entries</span>
                        </div>
                    </div>
                    <div class="p-6 text-gray-900 dark:text-white">
                    @if (session('status'))
                            <div class="mb-6 px-4 py-3 bg-green-50 dark:bg-green-500/20 border border-green-200 dark:border-green-500/30 rounded-xl text-green-700 dark:text-green-300 flex items-center gap-3 shadow-sm" 
                                 x-data="{ show: true }" 
                                 x-show="show" 
                                 x-transition
                                 x-init="setTimeout(() => show = false, 5000)">
                                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="flex-1">{{ session('status') }}</span>
                                <button @click="show = false" class="text-green-700 dark:text-green-300 hover:text-green-900 dark:hover:text-green-100">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>
                    @endif

                    <!-- Add Activity Form -->
                    <div class="mb-8 bg-gray-50 dark:bg-gray-800/50 backdrop-blur-sm rounded-xl p-6 border border-gray-200 dark:border-gray-700/50 shadow-sm">
                        <h4 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4 uppercase tracking-wide">Add New Entry</h4>
                        <form method="POST" action="{{ route('employees.payments.store', $employee) }}?tab=timeline" class="space-y-4" x-data="{ submitting: false }" @submit="submitting = true">
                        @csrf
                        <div>
                                <x-input-label for="activity_type" value="Activity Type" />
                                <select id="activity_type" name="activity_type" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400" required>
                                    <option value="payment" @selected(old('activity_type', 'payment') === 'payment')>🔵 Payment - Blue</option>
                                    <option value="achievement" @selected(old('activity_type') === 'achievement')>🟢 Achievement - Green (positive notes)</option>
                                    <option value="warning" @selected(old('activity_type') === 'warning')>🔴 Warning - Red (issues/concerns)</option>
                                    <option value="note" @selected(old('activity_type') === 'note')>⚪ Note - Gray (general notes)</option>
                                </select>
                                <x-input-error :messages="$errors->get('activity_type')" class="mt-2" />
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <x-input-label for="paid_at" value="Date" />
                            <x-text-input id="paid_at" type="date" name="paid_at" class="mt-1 block w-full" required />
                            <x-input-error :messages="$errors->get('paid_at')" class="mt-2" />
                        </div>
                        <div>
                                    <x-input-label for="amount" value="Amount (Optional)" />
                                    <x-text-input id="amount" type="number" step="0.01" name="amount" class="mt-1 block w-full" placeholder="0.00" />
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                                </div>
                        </div>
                        <div>
                            <x-input-label for="currency" value="Currency" />
                                <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400">
                                @foreach($currencies as $code => $label)
                                    <option value="{{ $code }}" @selected(old('currency', $employee->currency ?? 'USD') === $code)>{{ $code }} - {{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>
                            <div>
                                <x-input-label for="note" value="Description / Note" />
                                <textarea id="note" name="note" rows="3" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md shadow-sm focus:border-gray-500 dark:focus:border-gray-400 focus:ring-gray-500 dark:focus:ring-gray-400" placeholder="Payment details, achievement description, or any notes...">{{ old('note') }}</textarea>
                            <x-input-error :messages="$errors->get('note')" class="mt-2" />
                        </div>
                            <div class="flex items-center justify-end">
                                <button type="submit" :disabled="submitting" class="inline-flex items-center px-6 py-2.5 bg-black hover:bg-gray-800 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg x-show="!submitting" class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    <svg x-show="submitting" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span x-text="submitting ? 'Adding...' : 'Add Entry'">Add Entry</span>
                                </button>
                        </div>
                    </form>
                    </div>

                    <!-- Activity Timeline -->
                    <div class="relative">
                        <div class="absolute left-6 top-0 bottom-0 w-0.5 bg-gradient-to-b from-gray-300 via-gray-400 to-transparent dark:from-gray-600 dark:via-gray-700 dark:to-transparent"></div>
                        <ul class="space-y-4">
                            @forelse($payments as $payment)
                                @php
                                    // Get activity type from database or default to 'note'
                                    $activityType = $payment->activity_type ?? 'note';
                                    
                                    // Set colors, icons, and badges based on stored activity type
                                    switch($activityType) {
                                        case 'achievement':
                                            $bgColor = 'bg-green-500';
                                            $borderColor = 'border-green-200 dark:border-green-500/30';
                                            $textColor = 'text-green-600 dark:text-green-400';
                                            $iconType = 'achievement';
                                            $badge = 'Achievement';
                                            $badgeBg = 'bg-green-50 dark:bg-green-500/20 text-green-700 dark:text-green-300 border-green-300 dark:border-green-500/30';
                                            break;
                                        case 'warning':
                                            $bgColor = 'bg-red-500';
                                            $borderColor = 'border-red-200 dark:border-red-500/30';
                                            $textColor = 'text-red-600 dark:text-red-400';
                                            $iconType = 'warning';
                                            $badge = 'Warning';
                                            $badgeBg = 'bg-red-50 dark:bg-red-500/20 text-red-700 dark:text-red-300 border-red-300 dark:border-red-500/30';
                                            break;
                                        case 'payment':
                                            $bgColor = 'bg-blue-500';
                                            $borderColor = 'border-blue-200 dark:border-blue-500/30';
                                            $textColor = 'text-blue-600 dark:text-blue-400';
                                            $iconType = 'payment';
                                            $badge = 'Payment';
                                            $badgeBg = 'bg-blue-50 dark:bg-blue-500/20 text-blue-700 dark:text-blue-300 border-blue-300 dark:border-blue-500/30';
                                            break;
                                        default: // 'note'
                                            $bgColor = 'bg-gray-500';
                                            $borderColor = 'border-gray-200 dark:border-gray-500/30';
                                            $textColor = 'text-gray-600 dark:text-gray-400';
                                            $iconType = 'note';
                                            $badge = 'Note';
                                            $badgeBg = 'bg-gray-100 dark:bg-gray-500/20 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-500/30';
                                    }
                                @endphp
                                <li class="relative pl-16 group">
                                    <!-- Timeline Dot -->
                                    <div class="absolute left-3 top-3 w-6 h-6 rounded-full {{ $bgColor }} shadow-lg flex items-center justify-center ring-4 ring-white dark:ring-gray-800 group-hover:ring-gray-100 dark:group-hover:ring-gray-700 transition-all">
                                        <svg class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($iconType === 'achievement')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @elseif($iconType === 'warning')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            @elseif($iconType === 'payment')
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            @else
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            @endif
                                        </svg>
                                    </div>
                                    
                                    <!-- Activity Card -->
                                    <div class="bg-white dark:bg-gray-800/60 backdrop-blur-sm border {{ $borderColor }} rounded-xl p-4 shadow-sm hover:shadow-md dark:hover:shadow-xl transition-all duration-200">
                                        <div class="flex flex-wrap items-start justify-between gap-3 mb-3">
                                        <div class="flex items-center gap-2">
                                                @php
                                                    $badgeEmoji = match($activityType) {
                                                        'achievement' => '🟢',
                                                        'warning' => '🔴',
                                                        'payment' => '🔵',
                                                        default => '⚪'
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center text-xs font-semibold px-2.5 py-1 rounded-full border {{ $badgeBg }}">
                                                    <span class="mr-1.5">{{ $badgeEmoji }}</span>
                                                    <span class="uppercase tracking-wide">{{ $badge }}</span>
                                                </span>
                                                <span class="text-sm text-gray-600 dark:text-gray-400">{{ \Carbon\Carbon::parse($payment->paid_at)->format('M d, Y') }}</span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition" x-data @click="$dispatch('open-modal', 'edit-payment-{{ $payment->id }}')">Edit</button>
                                                <form method="POST" action="{{ route('employees.payments.destroy', [$employee, $payment]) }}?tab=timeline" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                    <button class="text-xs text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 transition" onclick="return confirm('Remove this entry?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                        
                                        @if($payment->amount)
                                            <div class="mb-3">
                                                <div class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                                    <svg class="w-4 h-4 mr-2 {{ $textColor }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($payment->amount, 2) }}</span>
                                                    <span class="text-sm text-gray-600 dark:text-gray-400 ml-1">{{ $payment->currency ?? $employee->currency ?? 'USD' }}</span>
                                                </div>
                                            </div>
                                        @endif
                                        
                                        @if($payment->note)
                                            <div class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">{{ $payment->note }}</div>
                                        @endif
                                    </div>

                                    <!-- Edit Modal -->
                                    <x-modal name="edit-payment-{{ $payment->id }}">
                                        <div class="p-6">
                                            <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Activity Entry</h4>
                                            <form method="POST" action="{{ route('employees.payments.update', [$employee, $payment]) }}?tab=timeline" class="space-y-4" x-data="{ saving: false }" @submit="saving = true">
                                                @csrf
                                                @method('PUT')
                                                <div>
                                                    <x-input-label for="activity_type_{{ $payment->id }}" value="Activity Type" />
                                                    <select id="activity_type_{{ $payment->id }}" name="activity_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                                        <option value="payment" @selected(($payment->activity_type ?? 'payment') === 'payment')>🔵 Payment - Blue</option>
                                                        <option value="achievement" @selected(($payment->activity_type ?? '') === 'achievement')>🟢 Achievement - Green (positive notes)</option>
                                                        <option value="warning" @selected(($payment->activity_type ?? '') === 'warning')>🔴 Warning - Red (issues/concerns)</option>
                                                        <option value="note" @selected(($payment->activity_type ?? '') === 'note')>⚪ Note - Gray (general notes)</option>
                                                    </select>
                                                </div>
                                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                                    <div>
                                                        <x-input-label for="paid_at_{{ $payment->id }}" value="Date" />
                                                    <x-text-input id="paid_at_{{ $payment->id }}" type="date" name="paid_at" class="mt-1 block w-full" value="{{ $payment->paid_at }}" required />
                                                </div>
                                                <div>
                                                        <x-input-label for="amount_{{ $payment->id }}" value="Amount (Optional)" />
                                                        <x-text-input id="amount_{{ $payment->id }}" type="number" step="0.01" name="amount" class="mt-1 block w-full" value="{{ $payment->amount }}" placeholder="0.00" />
                                                    </div>
                                                </div>
                                                <div>
                                                    <x-input-label for="currency_{{ $payment->id }}" value="Currency" />
                                                    <select id="currency_{{ $payment->id }}" name="currency" class="mt-1 block w-full border-gray-300 rounded-md">
                                                        @foreach($currencies as $code => $label)
                                                            <option value="{{ $code }}" @selected(($payment->currency ?? $employee->currency ?? 'USD') === $code)>{{ $code }} - {{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div>
                                                    <x-input-label for="note_{{ $payment->id }}" value="Description / Note" />
                                                    <textarea id="note_{{ $payment->id }}" name="note" rows="3" class="mt-1 block w-full border-gray-300 rounded-md" placeholder="Payment details, achievement, or notes...">{{ $payment->note }}</textarea>
                                                </div>
                                                <div class="flex items-center justify-end gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                    <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-full transition" x-data @click="$dispatch('close-modal', 'edit-payment-{{ $payment->id }}')">Cancel</button>
                                                    <button type="submit" :disabled="saving" class="inline-flex items-center px-6 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition font-semibold shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed">
                                                        <svg x-show="saving" class="animate-spin w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24">
                                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                                        </svg>
                                                        <span x-text="saving ? 'Saving...' : 'Save Changes'">Save Changes</span>
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </x-modal>
                                </li>
                            @empty
                                <li class="pl-16">
                                    <div class="bg-gray-50 dark:bg-gray-800/40 border border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400">No activity entries yet. Add your first entry above.</p>
                                    </div>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                    </div>
                </div>
            </div>

            <!-- Checklist Tab -->
            <div x-cloak x-show="tab==='checklist'" x-transition.opacity>
                <div class="space-y-6">
                    <!-- Today's Checklists -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Today's Checklist</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">{{ now()->format('l, F j, Y') }}</p>
                                    </div>
                                </div>
                                <button type="button" onclick="generateTodayChecklists({{ $employee->id }})" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition text-sm font-medium" id="generate-btn">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <span id="generate-btn-text">Generate Today's Checklist</span>
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @forelse ($todayChecklists as $checklist)
                                <div class="mb-6 last:mb-0 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden" data-checklist-id="{{ $checklist->id }}">
                                    <div class="p-6">
                                        <div class="flex items-center justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between gap-4">
                                                    <div>
                                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $checklist->template->title }}</h4>
                                                        @if($checklist->template->description)
                                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $checklist->template->description }}</p>
                                                        @endif
                                                        @if($checklist->email_sent_at)
                                                            <p class="text-xs text-blue-600 dark:text-blue-400 mt-2">
                                                                📧 Sent to email at {{ $checklist->email_sent_at->format('g:i A') }}
                                                            </p>
                                                        @endif
                                                    </div>
                                                    <div class="flex flex-col items-end gap-2">
                                                        <div class="text-right">
                                                            <div class="text-2xl font-bold text-gray-900 dark:text-white" data-completion-display="{{ $checklist->id }}">{{ $checklist->completion_percentage }}%</div>
                                                            <div class="text-xs text-gray-500 dark:text-gray-400">Complete</div>
                                                        </div>
                                                        <button type="button" onclick="sendChecklistEmail({{ $employee->id }}, {{ $checklist->id }})" class="inline-flex items-center px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow-sm hover:shadow-md transition text-xs font-medium" data-send-btn="{{ $checklist->id }}">
                                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span data-send-text="{{ $checklist->id }}">Send to Email</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="space-y-2">
                                            @foreach ($checklist->items as $item)
                                                <div class="flex items-center gap-3 p-3 bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg hover:shadow-md transition" data-item-container="{{ $item->id }}">
                                                    <input 
                                                        type="checkbox" 
                                                        id="item-{{ $item->id }}"
                                                        {{ $item->is_completed ? 'checked' : '' }}
                                                        onchange="toggleChecklistItem({{ $item->id }}, {{ $employee->id }}, {{ $checklist->id }})"
                                                        class="w-5 h-5 text-black border-gray-300 rounded focus:ring-black cursor-pointer"
                                                    >
                                                    <label for="item-{{ $item->id }}" class="flex-1 cursor-pointer" data-item-label="{{ $item->id }}">
                                                        <span class="{{ $item->is_completed ? 'line-through text-gray-500 dark:text-gray-600' : 'text-gray-900 dark:text-white' }}">{{ $item->title }}</span>
                                                    </label>
                                                    <span class="text-xs text-gray-500 dark:text-gray-400" data-item-timestamp="{{ $item->id }}">
                                                        @if($item->completed_at)
                                                            ✓ {{ $item->completed_at->format('g:i A') }}
                                                        @endif
                                                    </span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No checklists for today</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Click the button above to generate today's checklist based on templates</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <!-- Checklist History -->
                    @if($checklistHistory->isNotEmpty())
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center gap-3">
                                <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                    <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sent Checklist History</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">All checklists sent to employee's email ({{ $checklistHistory->count() }} total)</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                @foreach($checklistHistory as $history)
                                    @php
                                        $completedCount = $history->items->where('is_completed', true)->count();
                                        $totalCount = $history->items->count();
                                        $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                                        $isExpired = $history->email_sent_at && $history->email_sent_at->copy()->addHours(12)->isPast();
                                        $linkStatus = $isExpired ? 'Expired' : 'Active';
                                        $linkStatusColor = $isExpired ? 'bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-400' : 'bg-green-100 text-green-600 dark:bg-green-900 dark:text-green-300';
                                    @endphp

                                    <div class="border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden hover:shadow-lg transition-shadow bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900">
                                        <div class="p-5">
                                            <!-- Header -->
                                            <div class="flex items-start justify-between mb-4">
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-2">
                                                        <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $history->template->title }}</h4>
                                                        <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $linkStatusColor }}">
                                                            {{ $linkStatus }}
                                                        </span>
                                                    </div>
                                                    <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            {{ $history->date->format('M d, Y') }}
                                                        </span>
                                                        <span class="flex items-center gap-1">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                            </svg>
                                                            Sent: {{ $history->email_sent_at->format('M d, g:i A') }}
                                                        </span>
                                                        @if($isExpired)
                                                            <span class="flex items-center gap-1 text-orange-600 dark:text-orange-400">
                                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                                </svg>
                                                                Expired: {{ $history->email_sent_at->copy()->addHours(12)->format('M d, g:i A') }}
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="text-right">
                                                    <div class="text-3xl font-bold {{ $percentage == 100 ? 'text-green-600 dark:text-green-400' : 'text-gray-900 dark:text-white' }}">
                                                        {{ $percentage }}%
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ $completedCount }}/{{ $totalCount }} completed
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="mb-4">
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5 overflow-hidden">
                                                    <div class="h-2.5 rounded-full transition-all duration-300 {{ $percentage == 100 ? 'bg-gradient-to-r from-green-500 to-green-600' : 'bg-gradient-to-r from-blue-500 to-blue-600' }}" style="width: {{ $percentage }}%"></div>
                                                </div>
                                            </div>

                                            <!-- Items List -->
                                            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-4">
                                                <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3 uppercase tracking-wide">Checklist Items</p>
                                                <div class="space-y-2">
                                                    @foreach($history->items as $item)
                                                        <div class="flex items-center gap-2 text-sm">
                                                            @if($item->is_completed)
                                                                <svg class="w-4 h-4 text-green-600 dark:text-green-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                <span class="line-through text-gray-500 dark:text-gray-500">{{ $item->title }}</span>
                                                                @if($item->completed_at)
                                                                    <span class="ml-auto text-xs text-green-600 dark:text-green-400">
                                                                        ✓ {{ $item->completed_at->format('g:i A') }}
                                                                    </span>
                                                                @endif
                                                            @else
                                                                <svg class="w-4 h-4 text-gray-400 dark:text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                                <span class="text-gray-700 dark:text-gray-300">{{ $item->title }}</span>
                                                                <span class="ml-auto text-xs text-gray-400 dark:text-gray-600">Not completed</span>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Checklist Templates Management -->
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="p-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                        <svg class="w-5 h-5 text-gray-700 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Checklist Templates</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-0.5">Manage checklist templates that repeat daily</p>
                                    </div>
                                </div>
                                <button type="button" x-data @click="$dispatch('open-modal', 'create-checklist-template')" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition text-sm font-medium">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    New Template
                                </button>
                            </div>
                        </div>
                        <div class="p-6">
                            @forelse ($checklistTemplates as $template)
                                <div class="mb-4 last:mb-0 bg-gradient-to-br from-gray-50 to-white dark:from-gray-900 dark:to-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden">
                                    <div class="p-6">
                                        <div class="flex items-start justify-between mb-4">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 mb-2">
                                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $template->title }}</h4>
                                                    <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $template->is_active ? 'bg-green-100 text-green-700 dark:bg-green-900 dark:text-green-300' : 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300' }}">
                                                        {{ $template->is_active ? 'Active' : 'Inactive' }}
                                                    </span>
                                                    @if($template->role)
                                                        <span class="px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-300">
                                                            {{ $template->role }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($template->description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $template->description }}</p>
                                                @endif
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <button type="button" x-data @click="$dispatch('open-modal', 'edit-template-{{ $template->id }}')" class="text-sm text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">
                                                    Edit
                                                </button>
                                                <form method="POST" action="{{ route('employees.checklists.templates.destroy', [$employee, $template]) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300" onclick="return confirm('Delete this template? This will also remove all associated daily checklists.')">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                        
                                        <div class="bg-white dark:bg-gray-800/50 border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 mb-3">CHECKLIST ITEMS ({{ $template->items->count() }})</p>
                                            <ul class="space-y-2">
                                                @foreach ($template->items as $item)
                                                    <li class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                        </svg>
                                                        {{ $item->title }}
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Edit Template Modal -->
                                <x-modal name="edit-template-{{ $template->id }}">
                                    <div class="p-6">
                                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Edit Checklist Template</h4>
                                        <form method="POST" action="{{ route('employees.checklists.templates.update', [$employee, $template]) }}" class="space-y-4" x-data="{ items: {{ json_encode($template->items->pluck('title')->values()) }} }">
                                            @csrf
                                            @method('PUT')
                                            <div>
                                                <x-input-label for="title_{{ $template->id }}" value="Template Title" />
                                                <x-text-input id="title_{{ $template->id }}" type="text" name="title" class="mt-1 block w-full" value="{{ $template->title }}" required />
                                            </div>
                                            <div>
                                                <x-input-label for="description_{{ $template->id }}" value="Description (Optional)" />
                                                <textarea id="description_{{ $template->id }}" name="description" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md">{{ $template->description }}</textarea>
                                            </div>
                                            <div>
                                                <x-input-label for="role_{{ $template->id }}" value="Role (Optional)" />
                                                <x-text-input id="role_{{ $template->id }}" type="text" name="role" class="mt-1 block w-full" value="{{ $template->role }}" placeholder="e.g., Developer, Manager" />
                                            </div>
                                            <div>
                                                <x-input-label value="Checklist Items" />
                                                <div class="space-y-2 mt-2">
                                                    <template x-for="(item, index) in items" :key="index">
                                                        <div class="flex items-center gap-2">
                                                            <input type="text" :name="'items[' + index + ']'" x-model="items[index]" class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md" placeholder="Item description" required>
                                                            <button type="button" @click="items.splice(index, 1)" class="px-3 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                                <button type="button" @click="items.push('')" class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                    </svg>
                                                    Add Item
                                                </button>
                                            </div>
                                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg" x-data @click="$dispatch('close-modal', 'edit-template-{{ $template->id }}')">Cancel</button>
                                                <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </x-modal>
                            @empty
                                <div class="text-center py-12">
                                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                        <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No templates yet</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-4">Create your first checklist template for this employee</p>
                                    <button type="button" x-data @click="$dispatch('open-modal', 'create-checklist-template')" class="inline-flex items-center px-5 py-2.5 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Create First Template
                                    </button>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Create Template Modal -->
                <x-modal name="create-checklist-template">
                    <div class="p-6">
                        <h4 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Create Checklist Template</h4>
                        <form method="POST" action="{{ route('employees.checklists.templates.store', $employee) }}" class="space-y-4" x-data="{ items: [''] }">
                            @csrf
                            <div>
                                <x-input-label for="template_title" value="Template Title" />
                                <x-text-input id="template_title" type="text" name="title" class="mt-1 block w-full" placeholder="e.g., Daily Tasks, Morning Routine" required />
                            </div>
                            <div>
                                <x-input-label for="template_description" value="Description (Optional)" />
                                <textarea id="template_description" name="description" rows="2" class="mt-1 block w-full border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md" placeholder="Brief description of this checklist"></textarea>
                            </div>
                            <div>
                                <x-input-label for="template_role" value="Role (Optional)" />
                                <x-text-input id="template_role" type="text" name="role" class="mt-1 block w-full" value="{{ $employee->position }}" placeholder="e.g., Developer, Manager" />
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">This helps organize templates by employee role</p>
                            </div>
                            <div>
                                <x-input-label value="Checklist Items" />
                                <div class="space-y-2 mt-2">
                                    <template x-for="(item, index) in items" :key="index">
                                        <div class="flex items-center gap-2">
                                            <input type="text" :name="'items[' + index + ']'" x-model="items[index]" class="flex-1 border-gray-300 dark:border-gray-600 dark:bg-gray-900 dark:text-white rounded-md" placeholder="Item description" required>
                                            <button type="button" @click="items.length > 1 && items.splice(index, 1)" class="px-3 py-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-md" :disabled="items.length === 1">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </template>
                                </div>
                                <button type="button" @click="items.push('')" class="mt-3 inline-flex items-center px-3 py-1.5 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-md">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add Item
                                </button>
                            </div>
                            <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" class="px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg" x-data @click="$dispatch('close-modal', 'create-checklist-template')">Cancel</button>
                                <button type="submit" class="px-6 py-2 bg-black text-white rounded-lg hover:bg-gray-800">Create Template</button>
                            </div>
                        </form>
                    </div>
                </x-modal>
            </div>

        </div>
    </div>

    <style>
        .scale-110 {
            transform: scale(1.1);
            transition: transform 0.2s ease;
        }
    </style>

    <script>
        // Get initial tab from URL parameter or default to overview
        function getInitialTab() {
            const urlParams = new URLSearchParams(window.location.search);
            return urlParams.get('tab') || 'overview';
        }

        // Update URL with current tab without reloading page
        function updateTabUrl(tabName) {
            const url = new URL(window.location);
            url.searchParams.set('tab', tabName);
            window.history.pushState({}, '', url);
        }

        // Toast notification function
        function showToast(message, type = 'success') {
            const toast = document.createElement('div');
            toast.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : type === 'info' ? 'bg-blue-500' : 'bg-red-500'
            } text-white font-medium`;
            toast.textContent = message;
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.style.opacity = '0';
                setTimeout(() => toast.remove(), 300);
            }, 3000);
        }

        function generateTodayChecklists(employeeId) {
            const btn = document.getElementById('generate-btn');
            const btnText = document.getElementById('generate-btn-text');
            const originalText = btnText.textContent;
            
            // Disable button and show loading
            btn.disabled = true;
            btn.classList.add('opacity-75', 'cursor-not-allowed');
            btnText.textContent = 'Generating...';
            
            fetch(`/employees/${employeeId}/checklists/generate-today`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || 'Checklists generated successfully!', 'success');
                    // Reload the page to show the new checklists
                    setTimeout(() => {
                        window.location.href = window.location.pathname + '?tab=checklist';
                    }, 1000);
                } else {
                    showToast(data.message || 'Failed to generate checklists', 'error');
                    btn.disabled = false;
                    btn.classList.remove('opacity-75', 'cursor-not-allowed');
                    btnText.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to generate checklists', 'error');
                btn.disabled = false;
                btn.classList.remove('opacity-75', 'cursor-not-allowed');
                btnText.textContent = originalText;
            });
        }

        function sendChecklistEmail(employeeId, checklistId) {
            const btn = document.querySelector(`[data-send-btn="${checklistId}"]`);
            const btnText = document.querySelector(`[data-send-text="${checklistId}"]`);
            const originalText = btnText.textContent;
            
            // Disable button and show loading
            btn.disabled = true;
            btn.classList.add('opacity-50', 'cursor-not-allowed');
            btnText.textContent = 'Sending...';
            
            fetch(`/employees/${employeeId}/checklists/${checklistId}/send-email`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message || '📧 Email sent successfully!', 'success');
                    btnText.textContent = '✓ Sent';
                    setTimeout(() => {
                        btnText.textContent = originalText;
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        // Reload to show updated timestamp
                        window.location.reload();
                    }, 2000);
                } else {
                    showToast(data.message || 'Failed to send email', 'error');
                    btn.disabled = false;
                    btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnText.textContent = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Failed to send email', 'error');
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btnText.textContent = originalText;
            });
        }

        function toggleChecklistItem(itemId, employeeId, checklistId) {
            const checkbox = document.getElementById(`item-${itemId}`);
            const originalState = checkbox.checked;
            
            fetch(`/employees/${employeeId}/checklists/items/${itemId}/toggle`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update the label styling
                    const label = document.querySelector(`[data-item-label="${itemId}"] span`);
                    if (data.is_completed) {
                        label.className = 'line-through text-gray-500 dark:text-gray-600';
                    } else {
                        label.className = 'text-gray-900 dark:text-white';
                    }
                    
                    // Update the timestamp
                    const timestamp = document.querySelector(`[data-item-timestamp="${itemId}"]`);
                    if (data.completed_at) {
                        timestamp.textContent = `✓ ${data.completed_at}`;
                    } else {
                        timestamp.textContent = '';
                    }
                    
                    // Update completion percentage
                    updateCompletionPercentage(checklistId);
                    
                    // Show success toast
                    showToast(data.is_completed ? '✓ Item completed!' : '○ Item unchecked');
                } else {
                    // Revert checkbox if failed
                    checkbox.checked = originalState;
                    showToast('Failed to update item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Revert checkbox on error
                checkbox.checked = originalState;
                showToast('Failed to update item', 'error');
            });
        }

        function updateCompletionPercentage(checklistId) {
            const checklistEl = document.querySelector(`[data-checklist-id="${checklistId}"]`);
            if (!checklistEl) return;
            
            const checkboxes = checklistEl.querySelectorAll('input[type="checkbox"]');
            const total = checkboxes.length;
            const completed = Array.from(checkboxes).filter(cb => cb.checked).length;
            const percentage = total > 0 ? Math.round((completed / total) * 100 * 10) / 10 : 0;
            
            const display = document.querySelector(`[data-completion-display="${checklistId}"]`);
            if (display) {
                display.textContent = `${percentage}%`;
                
                // Add a brief animation
                display.classList.add('scale-110');
                setTimeout(() => display.classList.remove('scale-110'), 200);
            }
        }
    </script>
</x-app-layout>