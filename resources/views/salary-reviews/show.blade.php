<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Salary Review Details
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $salaryReview->employee->first_name }} {{ $salaryReview->employee->last_name }}
                </p>
            </div>
            @if($salaryReview->status === 'pending')
                <a href="{{ route('salary-reviews.edit', $salaryReview) }}" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Review Salary
                </a>
            @endif
        </div>
    </x-slot>

    <!-- Page Content -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
                <!-- Employee Info -->
                <div class="col-span-1 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Employee Details</h3>
                    
                    <div class="mb-6 pb-6 border-b border-gray-200 dark:border-gray-700">
                        <a href="{{ route('employees.show', $salaryReview->employee) }}" class="inline-flex items-center gap-3 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition group">
                            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white font-bold text-lg">
                                {{ substr($salaryReview->employee->first_name, 0, 1) }}{{ substr($salaryReview->employee->last_name, 0, 1) }}
                            </div>
                            <div class="flex-1">
                                <p class="font-semibold text-gray-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                    {{ $salaryReview->employee->first_name }} {{ $salaryReview->employee->last_name }}
                                </p>
                                <p class="text-xs text-gray-600 dark:text-gray-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition">
                                    View full profile →
                                </p>
                            </div>
                        </a>
                    </div>
                    
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Position</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $salaryReview->employee->position ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Department</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $salaryReview->employee->department ?? 'N/A' }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">Hired Date</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $salaryReview->employee->hired_at->format('M d, Y') }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm text-gray-600 dark:text-gray-400">6-Month Review</dt>
                            <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $salaryReview->review_date->format('M d, Y') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Salary Info -->
                <div class="col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Salary Details</h3>
                    
                    <div class="grid grid-cols-3 gap-4 mb-6">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Previous Salary</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                {{ number_format($salaryReview->previous_salary, 2) }}
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $salaryReview->employee->currency ?? 'USD' }}</p>
                        </div>

                        @if($salaryReview->adjusted_salary)
                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">New Salary</p>
                                <p class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($salaryReview->adjusted_salary, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $salaryReview->employee->currency ?? 'USD' }}</p>
                            </div>

                            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">Adjustment</p>
                                <p class="text-2xl font-bold
                                    @if($salaryReview->adjustment_amount > 0) text-green-600 dark:text-green-400
                                    @elseif($salaryReview->adjustment_amount < 0) text-red-600 dark:text-red-400
                                    @else text-gray-900 dark:text-white
                                    @endif
                                ">
                                    {{ $salaryReview->adjustment_amount > 0 ? '+' : '' }}{{ number_format($salaryReview->adjustment_amount, 2) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">{{ $salaryReview->employee->currency ?? 'USD' }}</p>
                            </div>
                        @endif
                    </div>

                    @if($salaryReview->status !== 'pending')
                        <div class="space-y-3 mt-6 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Reviewed By</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $salaryReview->reviewer?->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Reviewed Date</p>
                                <p class="font-medium text-gray-900 dark:text-white">{{ $salaryReview->reviewed_at?->format('M d, Y H:i') ?? 'N/A' }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Performance & Notes -->
            @if($salaryReview->status !== 'pending')
                <div class="grid grid-cols-2 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Performance Rating</h3>
                        @if($salaryReview->performance_rating)
                            <div class="flex items-center gap-2 mb-4">
                                <span class="text-3xl">
                                    @switch($salaryReview->performance_rating)
                                        @case('poor') 🔴 @break
                                        @case('below_average') 🟠 @break
                                        @case('average') 🟡 @break
                                        @case('good') 🟢 @break
                                        @case('excellent') 💚 @break
                                    @endswitch
                                </span>
                                <span class="text-xl font-semibold text-gray-900 dark:text-white capitalize">
                                    {{ str_replace('_', ' ', $salaryReview->performance_rating) }}
                                </span>
                            </div>
                        @endif
                        @if($salaryReview->performance_notes)
                            <p class="text-gray-700 dark:text-gray-300">{{ $salaryReview->performance_notes }}</p>
                        @else
                            <p class="text-gray-500 dark:text-gray-400">No notes provided</p>
                        @endif
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Adjustment Reason</h3>
                        <p class="text-gray-700 dark:text-gray-300">{{ $salaryReview->adjustment_reason ?? 'No reason provided' }}</p>
                    </div>
                </div>
            @endif

            <!-- Employee Activities -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Recent Activities (Last 20)</h3>
                
                <div class="space-y-4">
                    @forelse($activities as $activity)
                        <div class="flex items-start gap-4 pb-4 border-b border-gray-200 dark:border-gray-700 last:border-0">
                            <div class="text-2xl">
                                @switch($activity->activity_type)
                                    @case('achievement') 🏆 @break
                                    @case('warning') ⚠️ @break
                                    @case('payment') 💰 @break
                                    @case('note') 📝 @break
                                    @default ✓
                                @endswitch
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center justify-between">
                                    <h4 class="font-semibold text-gray-900 dark:text-white capitalize">
                                        {{ ucfirst($activity->activity_type) }}
                                    </h4>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $activity->paid_at?->format('M d, Y') ?? 'N/A' }}
                                    </span>
                                </div>
                                @if($activity->amount)
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                        {{ number_format($activity->amount, 2) }} {{ $activity->currency ?? 'USD' }}
                                    </p>
                                @endif
                                @if($activity->note)
                                    <p class="text-sm text-gray-700 dark:text-gray-300 mt-2">{{ $activity->note }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 dark:text-gray-400">No activities found</p>
                    @endforelse
                </div>
            </div>

            <!-- Salary Adjustment History -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Salary Adjustment History</h3>
                
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Date</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Type</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Old Salary</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">New Salary</th>
                                <th class="px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-white">Adjustment</th>
                                <th class="px-4 py-3 text-left text-sm font-semibold text-gray-900 dark:text-white">Reason</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($salaryHistory as $history)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ $history->created_at->format('M d, Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="inline-flex items-center px-2 py-1 rounded text-xs font-medium
                                            @if($history->type === 'review') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                            @elseif($history->type === 'promotion') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                            @elseif($history->type === 'demotion') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200
                                            @endif
                                        ">
                                            {{ ucfirst($history->type) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-700 dark:text-gray-300">
                                        {{ number_format($history->old_salary, 2) }} {{ $history->currency }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right text-gray-700 dark:text-gray-300">
                                        {{ number_format($history->new_salary, 2) }} {{ $history->currency }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-right font-medium
                                        @if($history->adjustment_amount > 0) text-green-600 dark:text-green-400
                                        @elseif($history->adjustment_amount < 0) text-red-600 dark:text-red-400
                                        @else text-gray-700 dark:text-gray-300
                                        @endif
                                    ">
                                        {{ $history->adjustment_amount > 0 ? '+' : '' }}{{ number_format($history->adjustment_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-300">{{ substr($history->reason ?? 'N/A', 0, 50) }}...</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No salary adjustments found
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
