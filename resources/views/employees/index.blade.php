<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Employees') }}
            </h2>
            <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2">
                <svg class="w-5 h-5 -ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" clip-rule="evenodd" />
                </svg>
                Add Employee
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl">
                <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <a href="{{ route('dashboard') }}" class="text-gray-600">← Back to Dashboard</a>
                    </div>
                    @if (session('status'))
                        <div class="mb-4 text-green-600">{{ session('status') }}</div>
                    @endif

                    <form method="GET" class="mb-6 grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div class="sm:col-span-2">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Search name, email, phone, position..." class="block w-full border-gray-300 rounded-full px-4 py-2" />
                        </div>
                        <div class="flex items-center gap-2">
                            <select name="status" class="border-gray-300 rounded-full px-3 py-2">
                                <option value="">All</option>
                                <option value="active" @selected(request('status')==='active')>Active</option>
                                <option value="discontinued" @selected(request('status')==='discontinued')>Discontinued</option>
                            </select>
                            <x-primary-button class="bg-black hover:bg-gray-800 rounded-full">Filter</x-primary-button>
                        </div>
                    </form>

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @forelse ($employees as $employee)
                            <div class="border border-gray-200 rounded-2xl p-5 shadow-sm hover:shadow transition">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="text-lg font-semibold">{{ $employee->first_name }} {{ $employee->last_name }}</div>
                                        <div class="text-sm text-gray-600">{{ $employee->position ?? '—' }}</div>
                                    </div>
                                    @if($employee->discontinued_at)
                                        <span class="text-xs text-red-600">Discontinued</span>
                                    @endif
                                </div>
                                <div class="mt-3 text-sm text-gray-700 space-y-1">
                                    <div>{{ $employee->email }}</div>
                                    @if($employee->phone)
                                        <div>{{ $employee->phone }}</div>
                                    @endif
                                    <div>Hired: {{ $employee->hired_at ?? '—' }}</div>
                                    @if($employee->salary)
                                        <div>Salary: {{ number_format($employee->salary,2) }} {{ $employee->currency ?? 'USD' }}</div>
                                    @endif
                                </div>
                                <div class="mt-4 flex items-center gap-2">
                                    <a href="{{ route('employees.show', $employee) }}" class="inline-flex items-center px-4 py-1.5 bg-black text-white rounded-full shadow hover:bg-gray-800">View</a>
                                    <a href="{{ route('employees.edit', $employee) }}" class="inline-flex items-center px-4 py-1.5 bg-black text-white rounded-full shadow hover:bg-gray-800">Edit</a>
                                    <form method="POST" action="{{ route('employees.destroy', $employee) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center px-4 py-1.5 bg-black text-white rounded-full shadow hover:bg-gray-800" onclick="return confirm('Delete this employee?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full text-center text-gray-500">
                                <div class="mb-3">No employees yet.</div>
                                <a href="{{ route('employees.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow hover:bg-gray-800">
                                    <svg class="w-5 h-5 -ms-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3.5a.75.75 0 01.75.75v5h5a.75.75 0 010 1.5h-5v5a.75.75 0 01-1.5 0v-5h-5a.75.75 0 010-1.5h5v-5A.75.75 0 0110 3.5z" clip-rule="evenodd" />
                                    </svg>
                                    Add your first employee
                                </a>
                            </div>
                        @endforelse
                    </div>

                    <div class="mt-6">
                        {{ $employees->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


