<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="mb-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600 dark:text-gray-300">← Back to Dashboard</a>
                    </div>
                    <form method="POST" action="{{ route('employees.store') }}" class="space-y-4">
                        @csrf
                        <div>
                            <x-input-label for="first_name" value="First Name" />
                            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" value="{{ old('first_name') }}" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="last_name" value="Last Name" />
                            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" value="{{ old('last_name') }}" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email') }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="github_username" value="GitHub Username" />
                            <div class="mt-1">
                                <x-text-input id="github_username" name="github_username" type="text" class="block w-full" value="{{ old('github_username') }}" placeholder="e.g., johndoe" />
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                    <svg class="w-3 h-3 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                    Enter their GitHub username (without @) to automatically track their GitHub activities
                                </p>
                            </div>
                            <x-input-error :messages="$errors->get('github_username')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone') }}" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="position" value="Position" />
                            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" value="{{ old('position') }}" />
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="department" value="Department" />
                            <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" value="{{ old('department') }}" />
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="salary" value="Salary" />
                            <x-text-input id="salary" name="salary" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('salary') }}" />
                            <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="currency" value="Currency" />
                            <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 rounded-md focus:border-indigo-500 focus:ring-indigo-500">
                                @php($currencies = ['USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 'BDT' => 'Bangladeshi Taka', 'INR' => 'Indian Rupee'])
                                @foreach($currencies as $code => $label)
                                    <option value="{{ $code }}" @selected(old('currency', 'USD') === $code)>{{ $code }} - {{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="hired_at" value="Hired Date" />
                            <x-text-input id="hired_at" name="hired_at" type="date" class="mt-1 block w-full" value="{{ old('hired_at') }}" />
                            <x-input-error :messages="$errors->get('hired_at')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('employees.index') }}" class="text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white">Cancel</a>
                            <x-primary-button class="bg-gray-900 hover:bg-gray-800 focus:bg-gray-800">Save</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


