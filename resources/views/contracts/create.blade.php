<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Create Employment Contract') }}
            </h2>
            <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center px-4 py-2 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Employee Info -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3 class="text-lg font-semibold mb-2">Employee Information</h3>
                    <p class="text-gray-600 dark:text-gray-400">
                        {{ $employee->first_name }} {{ $employee->last_name }} - {{ $employee->email }}
                    </p>
                </div>
            </div>

            <!-- Contract Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form action="{{ route('contracts.store', $employee) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Contract Details Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Contract Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="contract_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Contract Type</label>
                                    <select id="contract_type" name="contract_type" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="permanent" selected>Permanent</option>
                                        <option value="fixed_term">Fixed Term</option>
                                        <option value="part_time">Part Time</option>
                                    </select>
                                    @error('contract_type')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="job_title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Title</label>
                                    <input type="text" id="job_title" name="job_title" value="{{ old('job_title', $employee->position) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('job_title')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Department</label>
                                    <input type="text" id="department" name="department" value="{{ old('department') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('department')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Start Date</label>
                                    <input type="date" id="start_date" name="start_date" value="{{ old('start_date', $employee->hired_at ? $employee->hired_at->format('Y-m-d') : '') }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('start_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">End Date (Optional)</label>
                                    <input type="date" id="end_date" name="end_date" value="{{ old('end_date') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('end_date')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="job_description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Job Description</label>
                                <textarea id="job_description" name="job_description" rows="4" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('job_description') }}</textarea>
                                @error('job_description')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                            </div>
                        </div>

                        <!-- Compensation Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Compensation</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="salary" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Salary</label>
                                    <input type="number" step="0.01" id="salary" name="salary" value="{{ old('salary', $employee->salary ?? '') }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('salary')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Currency</label>
                                    <select id="currency" name="currency" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="USD" {{ ($employee->currency ?? 'USD') == 'USD' ? 'selected' : '' }}>USD</option>
                                        <option value="EUR" {{ ($employee->currency ?? 'USD') == 'EUR' ? 'selected' : '' }}>EUR</option>
                                        <option value="GBP" {{ ($employee->currency ?? 'USD') == 'GBP' ? 'selected' : '' }}>GBP</option>
                                        <option value="BDT" {{ ($employee->currency ?? 'USD') == 'BDT' ? 'selected' : '' }}>BDT</option>
                                    </select>
                                    @error('currency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="payment_frequency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Payment Frequency</label>
                                    <select id="payment_frequency" name="payment_frequency" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="monthly" selected>Monthly</option>
                                        <option value="bi-weekly">Bi-Weekly</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                    @error('payment_frequency')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Working Conditions Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Working Conditions</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="working_hours_per_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Working Hours Per Week</label>
                                    <input type="number" id="working_hours_per_week" name="working_hours_per_week" value="{{ old('working_hours_per_week', 40) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('working_hours_per_week')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="work_location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Work Location</label>
                                    <input type="text" id="work_location" name="work_location" value="{{ old('work_location') }}" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('work_location')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="work_schedule" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Work Schedule</label>
                                    <input type="text" id="work_schedule" name="work_schedule" value="{{ old('work_schedule', 'Monday - Friday, 9:00 AM - 5:00 PM') }}" placeholder="e.g., Monday - Friday, 9:00 AM - 5:00 PM" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('work_schedule')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Probation & Notice Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Probation & Notice Period</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="probation_period_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Probation Period (Days)</label>
                                    <input type="number" id="probation_period_days" name="probation_period_days" value="{{ old('probation_period_days', 90) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('probation_period_days')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="notice_period_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notice Period (Days)</label>
                                    <input type="number" id="notice_period_days" name="notice_period_days" value="{{ old('notice_period_days', 30) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('notice_period_days')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Benefits & Leave Section -->
                        <div class="border-b border-gray-200 dark:border-gray-700 pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Benefits & Leave</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="annual_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Annual Leave Days</label>
                                    <input type="number" id="annual_leave_days" name="annual_leave_days" value="{{ old('annual_leave_days', 20) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('annual_leave_days')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="sick_leave_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sick Leave Days</label>
                                    <input type="number" id="sick_leave_days" name="sick_leave_days" value="{{ old('sick_leave_days', 10) }}" required class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @error('sick_leave_days')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label for="benefits" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Benefits</label>
                                    <textarea id="benefits" name="benefits" rows="4" placeholder="e.g., Health insurance, Dental coverage, Retirement plan, etc." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('benefits') }}</textarea>
                                    @error('benefits')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Additional Terms Section -->
                        <div class="pb-6">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Additional Terms</h3>
                            <div class="space-y-6">
                                <div>
                                    <label for="responsibilities" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Key Responsibilities</label>
                                    <textarea id="responsibilities" name="responsibilities" rows="6" placeholder="List the key responsibilities and duties..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('responsibilities') }}</textarea>
                                    @error('responsibilities')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>

                                <div>
                                    <label for="additional_terms" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Additional Terms & Conditions</label>
                                    <textarea id="additional_terms" name="additional_terms" rows="6" placeholder="Any additional terms, conditions, or clauses..." class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('additional_terms') }}</textarea>
                                    @error('additional_terms')<p class="mt-1 text-sm text-red-600">{{ $message }}</p>@enderror
                                </div>
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center px-6 py-2.5 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full border border-gray-300 dark:border-gray-600 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                            <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-black hover:bg-gray-800 text-white rounded-full shadow-lg hover:shadow-xl transition">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Create Contract
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

