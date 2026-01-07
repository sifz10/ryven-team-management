<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    Complete Salary Review
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">
                    {{ $salaryReview->employee->first_name }} {{ $salaryReview->employee->last_name }}
                </p>
            </div>
        </div>
    </x-slot>

    <!-- Page Content -->
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <div class="py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-4xl mx-auto">
            <!-- Header -->
            <div class="mb-8">
                <a href="{{ route('salary-reviews.show', $salaryReview) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                    Back
                </a>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Complete Salary Review</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ $salaryReview->employee->first_name }} {{ $salaryReview->employee->last_name }}
                </p>
            </div>

            <!-- Form -->
            <form action="{{ route('salary-reviews.update', $salaryReview) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 gap-6">
                    <!-- Current Salary -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Current Information</h3>
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Position</dt>
                                <dd class="text-lg font-medium text-gray-900 dark:text-white">{{ $salaryReview->employee->position ?? 'N/A' }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Current Salary</dt>
                                <dd class="text-2xl font-bold text-gray-900 dark:text-white">
                                    {{ number_format($salaryReview->previous_salary, 2) }}
                                    <span class="text-sm text-gray-500 dark:text-gray-400 font-normal">{{ $salaryReview->employee->currency ?? 'USD' }}</span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Hired Date</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $salaryReview->employee->hired_at->format('M d, Y') }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-gray-600 dark:text-gray-400">Review Date</dt>
                                <dd class="font-medium text-gray-900 dark:text-white">{{ $salaryReview->review_date->format('M d, Y') }}</dd>
                            </div>
                        </dl>
                    </div>

                    <!-- New Salary -->
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">New Salary</h3>
                        
                        <div class="mb-4">
                            <label for="adjusted_salary" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                                Adjusted Salary *
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-600 dark:text-gray-400">
                                    {{ $salaryReview->employee->currency ?? 'USD' }}
                                </span>
                                <input type="number" 
                                    id="adjusted_salary" 
                                    name="adjusted_salary" 
                                    step="0.01"
                                    value="{{ old('adjusted_salary', $salaryReview->previous_salary) }}"
                                    required
                                    class="w-full pl-16 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                                >
                            </div>
                            @error('adjusted_salary')
                                <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="adjustment-preview" class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 hidden">
                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Adjustment Amount</p>
                            <p id="adjustment-amount" class="text-2xl font-bold text-gray-900 dark:text-white">
                                +0.00
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Performance & Reason -->
                <div class="grid grid-cols-2 gap-6">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <label for="performance_rating" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Performance Rating
                        </label>
                        <select id="performance_rating" 
                            name="performance_rating"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                        >
                            <option value="">Select a rating</option>
                            <option value="poor" @selected(old('performance_rating') === 'poor')>🔴 Poor</option>
                            <option value="below_average" @selected(old('performance_rating') === 'below_average')>🟠 Below Average</option>
                            <option value="average" @selected(old('performance_rating') === 'average')>🟡 Average</option>
                            <option value="good" @selected(old('performance_rating') === 'good')>🟢 Good</option>
                            <option value="excellent" @selected(old('performance_rating') === 'excellent')>💚 Excellent</option>
                        </select>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                        <label for="adjustment_reason" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                            Adjustment Reason *
                        </label>
                        <input type="text" 
                            id="adjustment_reason" 
                            name="adjustment_reason" 
                            value="{{ old('adjustment_reason') }}"
                            placeholder="e.g., Strong performance, exceeding targets"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                        >
                        @error('adjustment_reason')
                            <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Performance Notes -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <label for="performance_notes" class="block text-sm font-medium text-gray-900 dark:text-white mb-2">
                        Performance Notes
                    </label>
                    <textarea id="performance_notes" 
                        name="performance_notes" 
                        rows="6"
                        placeholder="Detailed feedback about the employee's performance over the past 6 months"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 outline-none transition"
                    >{{ old('performance_notes') }}</textarea>
                    @error('performance_notes')
                        <p class="text-red-600 dark:text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center justify-between gap-4">
                    <a href="{{ route('salary-reviews.show', $salaryReview) }}" class="inline-flex items-center px-6 py-3 text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600 transition font-medium">
                        Cancel
                    </a>
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Complete Review
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const previousSalary = {{ $salaryReview->previous_salary }};
const salaryInput = document.getElementById('adjusted_salary');
const adjustmentPreview = document.getElementById('adjustment-preview');
const adjustmentAmount = document.getElementById('adjustment-amount');

salaryInput.addEventListener('input', function() {
    const newSalary = parseFloat(this.value) || 0;
    const difference = newSalary - previousSalary;
    
    adjustmentPreview.classList.remove('hidden');
    adjustmentAmount.textContent = (difference >= 0 ? '+' : '') + difference.toFixed(2);
    adjustmentAmount.className = 'text-2xl font-bold ' + (
        difference > 0 ? 'text-green-600 dark:text-green-400' :
        difference < 0 ? 'text-red-600 dark:text-red-400' :
        'text-gray-900 dark:text-white'
    );
});
</script>
    </div>
</x-app-layout>
