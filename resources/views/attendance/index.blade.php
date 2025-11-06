<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-100 leading-tight">
                    {{ __('Attendance Management') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Track and manage employee attendance records</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
            @if (session('success'))
                <div class="mb-4 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-xl text-green-800 dark:text-green-200">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Employee Selection -->
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-2xl mb-6">
                <div class="p-6">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Select Employee</h3>
                    <form method="GET" action="{{ route('attendance.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div class="sm:col-span-2">
                            <label for="employee_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee</label>
                            <select name="employee_id" id="employee_id" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400" onchange="this.form.submit()">
                                <option value="">-- Select an Employee --</option>
                                @foreach($employees as $emp)
                                    <option value="{{ $emp->id }}" {{ $selectedEmployee && $selectedEmployee->id == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->first_name }} {{ $emp->last_name }} - {{ $emp->position }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        @if($selectedEmployee)
                            <div>
                                <label for="month" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Month</label>
                                <select name="month" id="month" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400" onchange="this.form.submit()">
                                    @for($m = 1; $m <= 12; $m++)
                                        <option value="{{ $m }}" {{ $month == $m ? 'selected' : '' }}>
                                            {{ \Carbon\Carbon::create()->month($m)->format('F') }}
                                        </option>
                                    @endfor
                                </select>
                            </div>

                            <div>
                                <label for="year" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Year</label>
                                <select name="year" id="year" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400" onchange="this.form.submit()">
                                    @for($y = now()->year - 1; $y <= now()->year + 1; $y++)
                                        <option value="{{ $y }}" {{ $year == $y ? 'selected' : '' }}>{{ $y }}</option>
                                    @endfor
                                </select>
                            </div>
                        @endif
                    </form>
                </div>
            </div>

            @if($selectedEmployee)
                <!-- Employee Info Card -->
                <div class="bg-gradient-to-r from-gray-800 to-black dark:from-gray-100 dark:to-white rounded-2xl shadow-lg p-4 sm:p-6 mb-6 text-white dark:text-black border border-gray-700 dark:border-gray-300">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold">{{ $selectedEmployee->first_name }} {{ $selectedEmployee->last_name }}</h3>
                            <p class="text-gray-300 dark:text-gray-700 mt-1">{{ $selectedEmployee->position }}</p>
                            <p class="text-gray-300 dark:text-gray-700 text-sm mt-2">Base Salary: {{ number_format($selectedEmployee->salary, 2) }} {{ $selectedEmployee->currency ?? 'USD' }}</p>
                            <p class="text-gray-400 dark:text-gray-600 text-xs mt-1">Hourly Rate: {{ number_format($selectedEmployee->salary / 208, 2) }} {{ $selectedEmployee->currency ?? 'USD' }}/hr</p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-gray-400 dark:text-gray-600 text-sm">Viewing</p>
                            <p class="text-xl sm:text-2xl font-bold">{{ \Carbon\Carbon::create($year, $month)->format('F Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Bulk Populate Section -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-2xl mb-6">
                    <div class="p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-4">
                            <div>
                                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100">🚀 Auto-Populate Monthly Hours</h3>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Enter total monthly hours to automatically fill all working days (Saturday is weekend)</p>
                            </div>
                            <button onclick="toggleBulkPopulate()" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full shadow hover:bg-gray-800 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-colors whitespace-nowrap">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                <span id="toggleBulkBtn" class="hidden sm:inline">Show Options</span>
                                <span id="toggleBulkBtn" class="sm:hidden">Show</span>
                            </button>
                        </div>

                        <div id="bulkPopulateForm" class="hidden">
                            <form method="POST" action="{{ route('attendance.bulk-populate') }}" onsubmit="return confirmBulkPopulate(event)">
                                @csrf
                                <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
                                <input type="hidden" name="year" value="{{ $year }}">
                                <input type="hidden" name="month" value="{{ $month }}">

                                <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border-2 border-gray-300 dark:border-gray-700">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div>
                                            <label for="total_hours" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Total Monthly Hours <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" step="0.01" min="0" name="total_hours" id="total_hours" required
                                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400"
                                                   placeholder="e.g., 208"
                                                   oninput="calculateAveragePreview()">
                                        </div>
                                        <div>
                                            <label for="working_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                Working Days <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" min="1" max="31" name="working_days" id="working_days" required
                                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400"
                                                   placeholder="e.g., 26"
                                                   value="26"
                                                   oninput="calculateAveragePreview()">
                                        </div>
                                        <div class="flex items-end">
                                            <label class="flex items-center cursor-pointer">
                                                <input type="checkbox" name="exclude_saturdays" value="1" checked
                                                       class="rounded border-gray-300 text-gray-900 shadow-sm focus:border-gray-900 focus:ring focus:ring-gray-200 focus:ring-opacity-50">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Exclude Saturdays</span>
                                            </label>
                                        </div>
                                    </div>

                                    <!-- Preview -->
                                    <div id="averagePreview" class="hidden bg-white dark:bg-gray-800 p-4 rounded-lg border-2 border-gray-300 dark:border-gray-600 mb-4">
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Average Per Day</p>
                                                <p id="avgTime" class="text-xl font-bold text-gray-900 dark:text-gray-100"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Payment Per Day</p>
                                                <p id="avgPayment" class="text-xl font-bold text-gray-900 dark:text-gray-100"></p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-600 dark:text-gray-400">Total Monthly Payment</p>
                                                <p id="totalPayment" class="text-xl font-bold text-gray-900 dark:text-gray-100"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="flex gap-3">
                                        <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 font-medium transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            Populate Entire Month
                                        </button>
                                        <button type="button" onclick="toggleBulkPopulate()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-full transition-colors">
                                            Cancel
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Calendar -->
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-2xl mb-6">
                    <div class="p-3 sm:p-6">
                        <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-4">
                            @foreach(['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'] as $day)
                                <div class="text-center text-xs sm:text-sm font-semibold text-gray-700 dark:text-gray-300 py-2">{{ $day }}</div>
                            @endforeach
                        </div>

                        <div class="grid grid-cols-7 gap-1 sm:gap-2">
                            @php
                                $date = \Carbon\Carbon::create($year, $month, 1);
                                $daysInMonth = $date->daysInMonth;
                                $startDayOfWeek = $date->dayOfWeek;
                                $today = now()->format('Y-m-d');
                            @endphp

                            {{-- Empty cells before month starts --}}
                            @for($i = 0; $i < $startDayOfWeek; $i++)
                                <div class="aspect-square"></div>
                            @endfor

                            {{-- Days of the month --}}
                            @for($day = 1; $day <= $daysInMonth; $day++)
                                @php
                                    $currentDate = \Carbon\Carbon::create($year, $month, $day);
                                    $dateString = $currentDate->format('Y-m-d');
                                    $attendance = $attendances->get($dateString);
                                    $isToday = $dateString === $today;
                                    $isFuture = $currentDate->isFuture();
                                @endphp

                                <div class="aspect-square border border-gray-200 dark:border-gray-600 rounded-lg p-2 hover:border-gray-900 dark:hover:border-gray-400 transition-all cursor-pointer relative {{ $isToday ? 'ring-2 ring-gray-900 dark:ring-gray-400' : '' }} attendance-day"
                                     onclick="openAttendanceModal('{{ $dateString }}', '{{ $day }}', {{ $attendance ? json_encode($attendance) : 'null' }})"
                                     data-date="{{ $dateString }}">
                                    
                                    <div class="text-sm font-semibold mb-1 {{ $isToday ? 'text-gray-900 dark:text-white' : 'text-gray-700 dark:text-gray-300' }}">
                                        {{ $day }}
                                    </div>

                                    @if($attendance)
                                        <div class="space-y-1">
                                            {{-- Status Badge --}}
                                            <div class="text-xs px-2 py-0.5 rounded-full text-center font-medium
                                                @if($attendance->status === 'present') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                                @elseif($attendance->status === 'absent') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                                @elseif($attendance->status === 'half_day') bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                                @elseif($attendance->status === 'leave') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                                @elseif($attendance->status === 'holiday') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $attendance->status)) }}
                                            </div>

                                            {{-- Hours Worked --}}
                                            @if($attendance->hours_worked !== null || $attendance->minutes_worked !== null)
                                                <div class="text-xs font-semibold text-gray-700 dark:text-gray-300 text-center" title="Hours worked">
                                                    ⏱️ {{ $attendance->formatted_time }}
                                                </div>
                                            @endif

                                            {{-- Payment Amount --}}
                                            @if($attendance->calculated_payment > 0)
                                                <div class="text-xs font-bold text-gray-700 dark:text-gray-300 text-center" title="Calculated payment">
                                                    {{ number_format($attendance->calculated_payment, 0) }}
                                                </div>
                                            @endif

                                            {{-- Bonus/Penalty Indicators --}}
                                            @if($attendance->bonus > 0 || $attendance->penalty > 0)
                                                <div class="flex gap-1 justify-center text-xs">
                                                    @if($attendance->bonus > 0)
                                                        <span class="text-green-600 dark:text-green-400" title="Bonus">+{{ number_format($attendance->bonus, 0) }}</span>
                                                    @endif
                                                    @if($attendance->penalty > 0)
                                                        <span class="text-red-600 dark:text-red-400" title="Penalty">-{{ number_format($attendance->penalty, 0) }}</span>
                                                    @endif
                                                </div>
                                            @endif
                                        </div>
                                    @else
                                        <div class="text-xs text-gray-400 dark:text-gray-500 text-center mt-2">
                                            No entry
                                        </div>
                                    @endif
                                </div>
                            @endfor
                        </div>
                    </div>
                </div>

                <!-- Monthly Summary -->
                @if($monthlySummary)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-2xl">
                        <div class="p-4 sm:p-6">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                                <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100">Monthly Summary</h3>
                                <button onclick="toggleMonthlyAdjustment()" class="inline-flex items-center gap-2 px-4 sm:px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full shadow hover:bg-gray-800 dark:hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-colors whitespace-nowrap">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span id="toggleAdjustmentBtn" class="hidden sm:inline">Monthly Adjustment</span>
                                    <span id="toggleAdjustmentBtn" class="sm:hidden">Adjust</span>
                                </button>
                            </div>

                            <!-- Monthly Adjustment Form -->
                            <div id="monthlyAdjustmentForm" class="hidden mb-6">
                                <form method="POST" action="{{ route('attendance.monthly-adjustment') }}">
                                    @csrf
                                    <input type="hidden" name="employee_id" value="{{ $selectedEmployee->id }}">
                                    <input type="hidden" name="year" value="{{ $year }}">
                                    <input type="hidden" name="month" value="{{ $month }}">

                                    <div class="bg-gray-50 dark:bg-gray-900/50 p-6 rounded-xl border-2 border-gray-300 dark:border-gray-700">
                                        <h4 class="text-md font-bold text-gray-900 dark:text-gray-100 mb-4">💵 Monthly Adjustments (Applied Before Tax)</h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                            <!-- Bonus Section -->
                                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border-2 border-gray-300 dark:border-gray-600">
                                                <h5 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">✅ Monthly Bonus</h5>
                                                <div class="mb-3">
                                                    <label for="monthly_bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                        Amount ({{ $selectedEmployee->currency ?? 'USD' }})
                                                    </label>
                                                    <input type="number" step="0.01" min="0" name="bonus" id="monthly_bonus"
                                                           value="{{ $monthlyAdjustment->bonus ?? 0 }}"
                                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                                                           placeholder="0.00">
                                                </div>
                                                <div>
                                                    <label for="bonus_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                        Description (Optional)
                                                    </label>
                                                    <textarea name="bonus_description" id="bonus_description" rows="2"
                                                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500"
                                                              placeholder="e.g., Performance bonus, Holiday bonus...">{{ $monthlyAdjustment->bonus_description ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <!-- Penalty Section -->
                                            <div class="bg-white dark:bg-gray-800 p-4 rounded-lg border-2 border-gray-300 dark:border-gray-600">
                                                <h5 class="text-sm font-semibold text-gray-900 dark:text-gray-100 mb-3">❌ Monthly Penalty</h5>
                                                <div class="mb-3">
                                                    <label for="monthly_penalty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                        Amount ({{ $selectedEmployee->currency ?? 'USD' }})
                                                    </label>
                                                    <input type="number" step="0.01" min="0" name="penalty" id="monthly_penalty"
                                                           value="{{ $monthlyAdjustment->penalty ?? 0 }}"
                                                           class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500"
                                                           placeholder="0.00">
                                                </div>
                                                <div>
                                                    <label for="penalty_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                                        Description (Optional)
                                                    </label>
                                                    <textarea name="penalty_description" id="penalty_description" rows="2"
                                                              class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500"
                                                              placeholder="e.g., Late arrival, Unpaid leave...">{{ $monthlyAdjustment->penalty_description ?? '' }}</textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mt-4 flex gap-3">
                                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 font-medium transition-colors">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Save Adjustment
                                            </button>
                                            <button type="button" onclick="toggleMonthlyAdjustment()" class="px-6 py-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium rounded-full transition-colors">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                                <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg">
                                    <p class="text-sm text-green-600 dark:text-green-400 font-medium">Present Days</p>
                                    <p class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $monthlySummary['present_days'] }}</p>
                                </div>
                                
                                <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg">
                                    <p class="text-sm text-red-600 dark:text-red-400 font-medium">Absent Days</p>
                                    <p class="text-3xl font-bold text-red-700 dark:text-red-300">{{ $monthlySummary['absent_days'] }}</p>
                                </div>
                                
                                <div class="bg-yellow-50 dark:bg-yellow-900/20 p-4 rounded-lg">
                                    <p class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">Half Days</p>
                                    <p class="text-3xl font-bold text-yellow-700 dark:text-yellow-300">{{ $monthlySummary['half_days'] }}</p>
                                </div>
                                
                                <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg">
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium">Leave/Holiday</p>
                                    <p class="text-3xl font-bold text-blue-700 dark:text-blue-300">{{ $monthlySummary['leave_days'] + $monthlySummary['holidays'] }}</p>
                                </div>
                            </div>

                            <div class="border-t dark:border-gray-700 pt-6">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Base Salary:</span>
                                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($monthlySummary['base_salary'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-gray-600 dark:text-gray-400">Hourly Rate:</span>
                                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($monthlySummary['hourly_rate'], 2) }} {{ $monthlySummary['currency'] }}/hr</span>
                                        </div>
                                            @if($monthlySummary['total_hours_worked'] > 0)
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Total Hours Worked:</span>
                                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($monthlySummary['total_hours_worked'], 2) }} hrs</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Hours Payment:</span>
                                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($monthlySummary['total_calculated_payment'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                            </div>
                                        @else
                                            <div class="flex justify-between">
                                                <span class="text-gray-600 dark:text-gray-400">Daily Rate:</span>
                                                <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($monthlySummary['daily_rate'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div class="space-y-3">
                                        <div class="flex justify-between">
                                            <span class="text-green-600 dark:text-green-400">Daily Bonus (Total):</span>
                                            <span class="font-semibold text-green-600 dark:text-green-400">+{{ number_format($monthlySummary['total_bonus'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-red-600 dark:text-red-400">Daily Penalty (Total):</span>
                                            <span class="font-semibold text-red-600 dark:text-red-400">-{{ number_format($monthlySummary['total_penalty'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                        </div>
                                        
                                        @if($monthlySummary['monthly_bonus'] > 0 || $monthlySummary['monthly_penalty'] > 0)
                                            <div class="pt-2 border-t dark:border-gray-700">
                                                @if($monthlySummary['monthly_bonus'] > 0)
                                                    <div class="flex justify-between items-center mb-2">
                                                        <span class="text-green-700 dark:text-green-300 font-semibold">💰 Monthly Bonus:</span>
                                                        <span class="font-bold text-green-700 dark:text-green-300">+{{ number_format($monthlySummary['monthly_bonus'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                                    </div>
                                                    @if($monthlyAdjustment && $monthlyAdjustment->bonus_description)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 italic">{{ $monthlyAdjustment->bonus_description }}</p>
                                                    @endif
                                                @endif
                                                @if($monthlySummary['monthly_penalty'] > 0)
                                                    <div class="flex justify-between items-center mb-2">
                                                        <span class="text-red-700 dark:text-red-300 font-semibold">❌ Monthly Penalty:</span>
                                                        <span class="font-bold text-red-700 dark:text-red-300">-{{ number_format($monthlySummary['monthly_penalty'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                                    </div>
                                                    @if($monthlyAdjustment && $monthlyAdjustment->penalty_description)
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mb-2 italic">{{ $monthlyAdjustment->penalty_description }}</p>
                                                    @endif
                                                @endif
                                            </div>
                                        @endif

                                        <div class="flex justify-between pt-3 border-t dark:border-gray-700">
                                            <span class="text-sm text-gray-600 dark:text-gray-400">Salary Before Tax:</span>
                                            <span class="font-semibold text-gray-900 dark:text-gray-100">{{ number_format($monthlySummary['salary_before_tax'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="text-orange-600 dark:text-orange-400">Tax ({{ $monthlySummary['tax_percentage'] }}%):</span>
                                            <span class="font-semibold text-orange-600 dark:text-orange-400">-{{ number_format($monthlySummary['tax_amount'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                        </div>
                                        <div class="flex justify-between pt-3 border-t-2 border-gray-300 dark:border-gray-700">
                                            <span class="text-lg font-bold text-gray-900 dark:text-gray-100">Final Salary (After Tax):</span>
                                            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($monthlySummary['calculated_salary'], 2) }} {{ $monthlySummary['currency'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Attendance Modal -->
    <div id="attendanceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-75 overflow-y-auto h-full w-full z-50 px-4" onclick="if(event.target === this) closeAttendanceModal()">
        <div class="relative top-10 sm:top-20 mx-auto p-4 sm:p-5 border w-full max-w-md shadow-lg rounded-2xl bg-white dark:bg-gray-800" onclick="event.stopPropagation()">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-base sm:text-lg font-bold text-gray-900 dark:text-gray-100" id="modalTitle">Mark Attendance</h3>
                <button onclick="closeAttendanceModal()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <form id="attendanceForm" method="POST">
                @csrf
                <input type="hidden" name="_method" id="formMethod" value="POST">
                <input type="hidden" name="employee_id" value="{{ $selectedEmployee?->id }}">
                <input type="hidden" name="date" id="attendanceDate">

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <div class="grid grid-cols-2 gap-2">
                            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-green-500 transition-all has-[:checked]:border-green-500 has-[:checked]:bg-green-50 dark:has-[:checked]:bg-green-900/20">
                                <input type="radio" name="status" value="present" class="sr-only" required>
                                <span class="text-sm font-medium">Present</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-red-500 transition-all has-[:checked]:border-red-500 has-[:checked]:bg-red-50 dark:has-[:checked]:bg-red-900/20">
                                <input type="radio" name="status" value="absent" class="sr-only">
                                <span class="text-sm font-medium">Absent</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-yellow-500 transition-all has-[:checked]:border-yellow-500 has-[:checked]:bg-yellow-50 dark:has-[:checked]:bg-yellow-900/20">
                                <input type="radio" name="status" value="half_day" class="sr-only">
                                <span class="text-sm font-medium">Half Day</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition-all has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50 dark:has-[:checked]:bg-blue-900/20">
                                <input type="radio" name="status" value="leave" class="sr-only">
                                <span class="text-sm font-medium">Leave</span>
                            </label>
                            <label class="flex items-center justify-center px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-purple-500 transition-all has-[:checked]:border-purple-500 has-[:checked]:bg-purple-50 dark:has-[:checked]:bg-purple-900/20 col-span-2">
                                <input type="radio" name="status" value="holiday" class="sr-only">
                                <span class="text-sm font-medium">Holiday</span>
                            </label>
                        </div>
                    </div>

                    <!-- Hours & Minutes Input -->
                    <div class="bg-gray-50 dark:bg-gray-900/50 p-4 rounded-lg border-2 border-gray-300 dark:border-gray-700">
                        <label class="block text-sm font-medium text-gray-900 dark:text-gray-100 mb-3">⏱️ Hours Worked (Optional)</label>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label for="hours_worked" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Hours</label>
                                                <input type="number" min="0" max="24" name="hours_worked" id="hours_worked" 
                                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400" 
                                                       placeholder="7" oninput="calculatePayment()">
                            </div>
                            <div>
                                <label for="minutes_worked" class="block text-xs text-gray-600 dark:text-gray-400 mb-1">Minutes</label>
                                                <input type="number" min="0" max="59" name="minutes_worked" id="minutes_worked" 
                                                       class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400" 
                                                       placeholder="39" oninput="calculatePayment()">
                            </div>
                        </div>
                                    <!-- Calculated Payment Display -->
                                    <div id="calculatedPaymentDisplay" class="mt-3 p-3 bg-white dark:bg-gray-800 rounded-lg border-2 border-gray-300 dark:border-gray-600 hidden">
                                        <div class="flex justify-between items-center">
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Payment for hours:</span>
                                            <span id="calculatedPaymentAmount" class="text-lg font-bold text-gray-900 dark:text-white"></span>
                                        </div>
                                    </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="bonus" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Bonus ({{ $selectedEmployee?->currency ?? 'USD' }})</label>
                            <input type="number" step="0.01" min="0" name="bonus" id="bonus" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-green-500 focus:ring-green-500" 
                                   placeholder="0.00" oninput="calculatePayment()">
                        </div>
                        <div>
                            <label for="penalty" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Penalty ({{ $selectedEmployee?->currency ?? 'USD' }})</label>
                            <input type="number" step="0.01" min="0" name="penalty" id="penalty" 
                                   class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-red-500 focus:ring-red-500" 
                                   placeholder="0.00" oninput="calculatePayment()">
                        </div>
                    </div>

                                    <!-- Final Payment Display -->
                                    <div id="finalPaymentDisplay" class="hidden p-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg border-2 border-gray-300 dark:border-gray-700">
                                        <div class="flex justify-between items-center">
                                            <span class="text-base font-bold text-gray-900 dark:text-gray-100">Final Payment:</span>
                                            <span id="finalPaymentAmount" class="text-2xl font-bold text-gray-900 dark:text-white"></span>
                                        </div>
                        <div class="mt-2 text-xs text-gray-600 dark:text-gray-400">
                            <div id="paymentBreakdown"></div>
                        </div>
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Description (Optional)</label>
                                        <textarea name="description" id="description" rows="3" class="w-full border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 rounded-lg shadow-sm focus:border-gray-900 dark:focus:border-gray-400 focus:ring-gray-900 dark:focus:ring-gray-400" placeholder="Add any notes..."></textarea>
                    </div>
                </div>

                <div class="mt-6 flex gap-3">
                                <button type="submit" class="flex-1 bg-black hover:bg-gray-800 text-white font-medium py-2.5 px-4 rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                                    Save
                                </button>
                    <button type="button" id="deleteBtn" onclick="deleteAttendance()" class="hidden bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 px-4 rounded-full transition-colors">
                        Delete
                    </button>
                    <button type="button" onclick="closeAttendanceModal()" class="bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 font-medium py-2.5 px-4 rounded-full transition-colors">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>

    @if($selectedEmployee)
    <script>
        let currentAttendanceId = null;
        const baseSalary = {{ $selectedEmployee->salary ?? 0 }};
        const currency = '{{ $selectedEmployee->currency ?? 'USD' }}';
        // 8 hours/day × 6 days/week × 52 weeks/year ÷ 12 months = 208 hours/month
        const hourlyRate = baseSalary / 208; // 26 working days/month × 8 hours/day

        // Toggle bulk populate form
        function toggleBulkPopulate() {
            const form = document.getElementById('bulkPopulateForm');
            const btn = document.getElementById('toggleBulkBtn');
            
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                btn.textContent = 'Hide';
            } else {
                form.classList.add('hidden');
                btn.textContent = 'Show Options';
            }
        }

        // Toggle monthly adjustment form
        function toggleMonthlyAdjustment() {
            const form = document.getElementById('monthlyAdjustmentForm');
            const btn = document.getElementById('toggleAdjustmentBtn');
            
            if (form.classList.contains('hidden')) {
                form.classList.remove('hidden');
                btn.textContent = 'Hide Form';
            } else {
                form.classList.add('hidden');
                btn.textContent = 'Monthly Adjustment';
            }
        }

        // Calculate and show preview
        function calculateAveragePreview() {
            const totalHours = parseFloat(document.getElementById('total_hours').value) || 0;
            const workingDays = parseInt(document.getElementById('working_days').value) || 0;
            
            if (totalHours <= 0 || workingDays <= 0) {
                document.getElementById('averagePreview').classList.add('hidden');
                return;
            }

            const avgHoursPerDay = totalHours / workingDays;
            const avgHours = Math.floor(avgHoursPerDay);
            const avgMinutes = Math.round((avgHoursPerDay - avgHours) * 60);
            
            const paymentPerDay = hourlyRate * avgHoursPerDay;
            const totalPayment = hourlyRate * totalHours;

            document.getElementById('avgTime').textContent = `${avgHours}h ${avgMinutes}m`;
            document.getElementById('avgPayment').textContent = `${paymentPerDay.toFixed(2)} ${currency}`;
            document.getElementById('totalPayment').textContent = `${totalPayment.toFixed(2)} ${currency}`;
            
            document.getElementById('averagePreview').classList.remove('hidden');
        }

        // Confirm before populating
        function confirmBulkPopulate(event) {
            const totalHours = parseFloat(document.getElementById('total_hours').value) || 0;
            const workingDays = parseInt(document.getElementById('working_days').value) || 0;
            const avgHoursPerDay = totalHours / workingDays;
            const avgHours = Math.floor(avgHoursPerDay);
            const avgMinutes = Math.round((avgHoursPerDay - avgHours) * 60);

            const message = `Are you sure you want to populate ${workingDays} days with ${avgHours}h ${avgMinutes}m each?\n\nThis will overwrite any existing attendance records for this month.`;
            
            if (!confirm(message)) {
                event.preventDefault();
                return false;
            }
            return true;
        }

        function calculatePayment() {
            const hours = parseInt(document.getElementById('hours_worked').value) || 0;
            const minutes = parseInt(document.getElementById('minutes_worked').value) || 0;
            const bonus = parseFloat(document.getElementById('bonus').value) || 0;
            const penalty = parseFloat(document.getElementById('penalty').value) || 0;

            // Calculate payment for hours worked
            const totalHours = hours + (minutes / 60);
            const calculatedPayment = hourlyRate * totalHours;

            // Show/hide calculated payment display
            const calcDisplay = document.getElementById('calculatedPaymentDisplay');
            const calcAmount = document.getElementById('calculatedPaymentAmount');
            
            if (hours > 0 || minutes > 0) {
                calcDisplay.classList.remove('hidden');
                calcAmount.textContent = calculatedPayment.toFixed(2) + ' ' + currency;
            } else {
                calcDisplay.classList.add('hidden');
            }

            // Calculate final payment
            const finalPayment = calculatedPayment + bonus - penalty;

            // Show/hide final payment display
            const finalDisplay = document.getElementById('finalPaymentDisplay');
            const finalAmount = document.getElementById('finalPaymentAmount');
            const breakdown = document.getElementById('paymentBreakdown');

            if (hours > 0 || minutes > 0 || bonus > 0 || penalty > 0) {
                finalDisplay.classList.remove('hidden');
                finalAmount.textContent = Math.max(0, finalPayment).toFixed(2) + ' ' + currency;
                
                // Build breakdown
                let breakdownText = '';
                if (hours > 0 || minutes > 0) {
                    breakdownText += `Hours: ${calculatedPayment.toFixed(2)} ${currency}`;
                }
                if (bonus > 0) {
                    breakdownText += ` + Bonus: ${bonus.toFixed(2)} ${currency}`;
                }
                if (penalty > 0) {
                    breakdownText += ` - Penalty: ${penalty.toFixed(2)} ${currency}`;
                }
                breakdown.textContent = breakdownText;
            } else {
                finalDisplay.classList.add('hidden');
            }
        }

        function openAttendanceModal(date, day, attendance) {
            const modal = document.getElementById('attendanceModal');
            const form = document.getElementById('attendanceForm');
            const title = document.getElementById('modalTitle');
            const dateInput = document.getElementById('attendanceDate');
            const formMethod = document.getElementById('formMethod');
            const deleteBtn = document.getElementById('deleteBtn');

            // Reset form
            form.reset();
            dateInput.value = date;
            currentAttendanceId = null;

            // Hide displays
            document.getElementById('calculatedPaymentDisplay').classList.add('hidden');
            document.getElementById('finalPaymentDisplay').classList.add('hidden');

            if (attendance) {
                // Edit mode
                title.textContent = `Edit Attendance - Day ${day}`;
                form.action = "{{ route('attendance.update', ':id') }}".replace(':id', attendance.id);
                formMethod.value = 'PUT';
                
                // Set values
                document.querySelector(`input[name="status"][value="${attendance.status}"]`).checked = true;
                
                // Set hours and minutes
                if (attendance.hours_worked !== null) {
                    document.getElementById('hours_worked').value = attendance.hours_worked;
                }
                if (attendance.minutes_worked !== null) {
                    document.getElementById('minutes_worked').value = attendance.minutes_worked;
                }
                
                document.getElementById('bonus').value = attendance.bonus;
                document.getElementById('penalty').value = attendance.penalty;
                document.getElementById('description').value = attendance.description || '';
                
                currentAttendanceId = attendance.id;
                deleteBtn.classList.remove('hidden');

                // Calculate payment to show current values
                calculatePayment();
            } else {
                // Create mode
                title.textContent = `Mark Attendance - Day ${day}`;
                form.action = "{{ route('attendance.store') }}";
                formMethod.value = 'POST';
                document.querySelector('input[name="status"][value="present"]').checked = true;
                deleteBtn.classList.add('hidden');
            }

            modal.classList.remove('hidden');
        }

        function closeAttendanceModal() {
            document.getElementById('attendanceModal').classList.add('hidden');
        }

        function deleteAttendance() {
            if (!currentAttendanceId || !confirm('Are you sure you want to delete this attendance record?')) {
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = "{{ route('attendance.destroy', ':id') }}".replace(':id', currentAttendanceId);
            
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            
            form.appendChild(csrfInput);
            form.appendChild(methodInput);
            document.body.appendChild(form);
            form.submit();
        }

        // Close modal on Escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                closeAttendanceModal();
            }
        });
    </script>
    @endif
</x-app-layout>

