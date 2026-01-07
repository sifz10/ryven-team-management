<x-app-layout>
    <div class="py-6 sm:py-8">
        <div class="w-full px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 dark:text-white flex items-center gap-3">
                            <svg class="w-10 h-10 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Leave Requests
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 mt-2">Time off and leave requests synced from Jibble</p>
                    </div>
                    <a href="{{ route('jibble.dashboard') }}" class="px-4 py-2 text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 font-medium">
                        ← Back to Dashboard
                    </a>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4 sm:gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Requests</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white mt-2">{{ $stats['total_requests'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Approved</p>
                    <p class="text-2xl font-bold text-green-600 dark:text-green-400 mt-2">{{ $stats['approved'] ?? 0 }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm p-6 border border-gray-200 dark:border-gray-700">
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Rejected</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400 mt-2">{{ $stats['rejected'] ?? 0 }}</p>
                </div>
            </div>

            <!-- Filters -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Employee</label>
                        <select id="employee-filter" onchange="applyFilters()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Employees</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                        <select id="status-filter" onchange="applyFilters()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">All Statuses</option>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                        <input type="date" id="start-date-filter" onchange="applyFilters()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date</label>
                        <input type="date" id="end-date-filter" onchange="applyFilters()" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                </div>
            </div>

            <!-- Requests Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Employee</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Type</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Start Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">End Date</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Days</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($requests as $request)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">
                                        <div class="flex items-center gap-2">
                                            <div class="w-8 h-8 rounded-full bg-orange-100 dark:bg-orange-900 flex items-center justify-center text-xs font-semibold text-orange-700 dark:text-orange-300">
                                                {{ substr($request->employee->first_name ?? 'U', 0, 1) }}
                                            </div>
                                            {{ $request->employee->first_name ?? '' }} {{ $request->employee->last_name ?? '' }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                            {{ ucfirst($request->leave_type) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $request->start_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">
                                        {{ $request->end_date->format('M d, Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">
                                        {{ $request->days_count }}
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">day{{ $request->days_count !== 1 ? 's' : '' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                            @if($request->status === 'approved') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200
                                            @elseif($request->status === 'rejected') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200
                                            @else bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200
                                            @endif">
                                            {{ ucfirst($request->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                        @if($request->reason)
                                            <span title="{{ $request->reason }}" class="cursor-help">{{ Str::limit($request->reason, 30) }}</span>
                                        @else
                                            <span class="text-gray-400">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-gray-600 dark:text-gray-400">No leave requests found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($requests->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $requests->links('pagination::tailwind') }}
                    </div>
                @endif
            </div>

            <!-- Summary Footer -->
            @if($requests->count() > 0)
                <div class="mt-8 bg-orange-50 dark:bg-orange-900 border border-orange-200 dark:border-orange-800 rounded-xl p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                        <div>
                            <p class="text-sm font-medium text-orange-900 dark:text-orange-300">Total Days on Page</p>
                            <p class="text-3xl font-bold text-orange-600 dark:text-orange-400 mt-2">
                                {{ $requests->sum(fn($r) => $r->days_count) }} days
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-orange-900 dark:text-orange-300">Pending Days</p>
                            <p class="text-3xl font-bold text-yellow-600 dark:text-yellow-400 mt-2">
                                {{ $requests->where('status', 'pending')->sum(fn($r) => $r->days_count) }} days
                            </p>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-orange-900 dark:text-orange-300">Approved Days</p>
                            <p class="text-3xl font-bold text-green-600 dark:text-green-400 mt-2">
                                {{ $requests->where('status', 'approved')->sum(fn($r) => $r->days_count) }} days
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        function applyFilters() {
            const employee = document.getElementById('employee-filter').value;
            const status = document.getElementById('status-filter').value;
            const startDate = document.getElementById('start-date-filter').value;
            const endDate = document.getElementById('end-date-filter').value;

            const params = new URLSearchParams();
            if (employee) params.append('employee_id', employee);
            if (status) params.append('status', status);
            if (startDate) params.append('start_date', startDate);
            if (endDate) params.append('end_date', endDate);

            window.location.search = params.toString();
        }
    </script>
</x-app-layout>
