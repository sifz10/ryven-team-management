<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Employee') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600">← Back to Dashboard</a>
                    </div>
                    <form method="POST" action="{{ route('employees.update', $employee) }}" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div>
                            <x-input-label for="first_name" value="First Name" />
                            <x-text-input id="first_name" name="first_name" type="text" class="mt-1 block w-full" value="{{ old('first_name', $employee->first_name) }}" required />
                            <x-input-error :messages="$errors->get('first_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="last_name" value="Last Name" />
                            <x-text-input id="last_name" name="last_name" type="text" class="mt-1 block w-full" value="{{ old('last_name', $employee->last_name) }}" required />
                            <x-input-error :messages="$errors->get('last_name')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email', $employee->email) }}" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="phone" value="Phone" />
                            <x-text-input id="phone" name="phone" type="text" class="mt-1 block w-full" value="{{ old('phone', $employee->phone) }}" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="position" value="Position" />
                            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" value="{{ old('position', $employee->position) }}" />
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="department" value="Department" />
                            <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" value="{{ old('department', $employee->department) }}" />
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="salary" value="Salary" />
                            <x-text-input id="salary" name="salary" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('salary', $employee->salary) }}" />
                            <x-input-error :messages="$errors->get('salary')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="currency" value="Currency" />
                            <select id="currency" name="currency" class="mt-1 block w-full border-gray-300 rounded-md">
                                @php($currencies = ['USD' => 'US Dollar', 'EUR' => 'Euro', 'GBP' => 'British Pound', 'BDT' => 'Bangladeshi Taka', 'INR' => 'Indian Rupee'])
                                @foreach($currencies as $code => $label)
                                    <option value="{{ $code }}" @selected(old('currency', $employee->currency ?? 'USD') === $code)>{{ $code }} - {{ $label }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('currency')" class="mt-2" />
                        </div>
                        <div>
                            <x-input-label for="hired_at" value="Hired Date" />
                            <x-text-input id="hired_at" name="hired_at" type="date" class="mt-1 block w-full" value="{{ old('hired_at', $employee->hired_at) }}" />
                            <x-input-error :messages="$errors->get('hired_at')" class="mt-2" />
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('employees.index') }}" class="text-gray-700 hover:text-gray-900">Cancel</a>
                            <x-primary-button class="bg-gray-900 hover:bg-gray-800 focus:bg-gray-800">Update</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


