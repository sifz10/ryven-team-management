<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Salary History
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $employee->first_name }} {{ $employee->last_name }}
                </p>
            </div>
            <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Profile
            </a>
        </div>
    </x-slot>

    <!-- Page Content -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <div class="py-6 px-4 sm:px-6 lg:px-8">
            <div class="w-full space-y-8">
                
                <!-- Current Salary Card -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-8">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-1">Current Salary</p>
                                <p class="text-5xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($employee->salary, 2) }}
                                </p>
                                <p class="text-gray-500 dark:text-gray-400 text-sm mt-2">{{ $employee->currency ?? 'USD' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-gray-600 dark:text-gray-400 text-sm font-medium mb-3">Position</p>
                                <p class="text-xl font-semibold text-gray-900 dark:text-white">{{ $employee->position ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Hired: {{ $employee->hired_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary Reviews Section -->
                @if($reviews->count() > 0)
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6-Month Salary Reviews</h3>
                        <div class="space-y-4">
                            @foreach($reviews as $review)
                                <a href="{{ route('salary-reviews.show', $review) }}" class="block bg-white dark:bg-gray-800 rounded-lg shadow hover:shadow-lg transition p-6 border-l-4 @if($review->status === 'completed') border-l-green-500 @elseif($review->status === 'in_progress') border-l-blue-500 @else border-l-yellow-500 @endif">
                                    <div class="flex items-center justify-between mb-3">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white">
                                            Review Date: {{ $review->review_date->format('M d, Y') }}
                                        </h4>
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium
                                            @if($review->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                            @elseif($review->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($review->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @endif
                                        ">
                                            {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                        </span>
                                    </div>

                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                        <div>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Previous Salary</p>
                                            <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                {{ number_format($review->previous_salary, 2) }}
                                            </p>
                                        </div>
                                        @if($review->adjusted_salary)
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">New Salary</p>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    {{ number_format($review->adjusted_salary, 2) }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Adjustment</p>
                                                <p class="text-lg font-semibold @if($review->adjustment_amount > 0) text-green-600 dark:text-green-400 @elseif($review->adjustment_amount < 0) text-red-600 dark:text-red-400 @else text-gray-600 dark:text-gray-400 @endif">
                                                    {{ $review->adjustment_amount > 0 ? '+' : '' }}{{ number_format($review->adjustment_amount, 2) }}
                                                </p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Performance</p>
                                                <p class="text-lg font-semibold text-gray-900 dark:text-white">
                                                    @if($review->performance_rating)
                                                        @php
                                                            $ratings = [
                                                                'poor' => '🔴',
                                                                'below_average' => '🟠',
                                                                'average' => '🟡',
                                                                'good' => '🟢',
                                                                'excellent' => '💚'
                                                            ];
                                                        @endphp
                                                        {{ $ratings[$review->performance_rating] ?? '' }} {{ ucfirst(str_replace('_', ' ', $review->performance_rating)) }}
                                                    @else
                                                        —
                                                    @endif
                                                </p>
                                            </div>
                                        @else
                                            <div class="col-span-3">
                                                <p class="text-sm text-gray-600 dark:text-gray-400 italic">Pending review completion...</p>
                                            </div>
                                        @endif
                                    </div>

                                    @if($review->adjustment_reason)
                                        <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Reason</p>
                                            <p class="text-gray-900 dark:text-white">{{ $review->adjustment_reason }}</p>
                                        </div>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- Adjustment History Section -->
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Complete Adjustment History</h3>
                    
                    @if($adjustmentHistory->count() > 0)
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                            <table class="w-full">
                                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Date</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Type</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Old Salary</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">New Salary</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Adjustment</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Reason</th>
                                        <th class="px-6 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">By</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                                    @foreach($adjustmentHistory as $adjustment)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $adjustment->created_at->format('M d, Y H:i') }}
                                            </td>
                                            <td class="px-6 py-4 text-sm">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($adjustment->type === 'review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($adjustment->type === 'promotion') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($adjustment->type === 'demotion') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                                    @endif
                                                ">
                                                    {{ ucfirst($adjustment->type) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                                {{ number_format($adjustment->old_salary, 2) }} {{ $adjustment->currency }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300 font-medium">
                                                {{ number_format($adjustment->new_salary, 2) }} {{ $adjustment->currency }}
                                            </td>
                                            <td class="px-6 py-4 text-sm font-semibold
                                                @if($adjustment->adjustment_amount > 0) text-green-600 dark:text-green-400
                                                @elseif($adjustment->adjustment_amount < 0) text-red-600 dark:text-red-400
                                                @else text-gray-600 dark:text-gray-400
                                                @endif
                                            ">
                                                {{ $adjustment->adjustment_amount > 0 ? '+' : '' }}{{ number_format($adjustment->adjustment_amount, 2) }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                {{ $adjustment->reason ?? '—' }}
                                            </td>
                                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">
                                                @if($adjustment->adjustedBy)
                                                    {{ $adjustment->adjustedBy->name }}
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <!-- Pagination -->
                            @if($adjustmentHistory->hasPages())
                                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                                    {{ $adjustmentHistory->links() }}
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">No salary adjustments yet</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
