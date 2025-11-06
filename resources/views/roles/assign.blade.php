<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h2 class="text-2xl font-semibold text-gray-800 dark:text-gray-200">Assign Roles to Employees</h2>
                        <a href="{{ route('roles.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                            ← Back to Roles
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-700 text-green-700 dark:text-green-300 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="space-y-6">
                        @foreach($employees as $employee)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4">
                                <form action="{{ route('roles.assign-roles', $employee) }}" method="POST">
                                    @csrf

                                    <div class="flex items-start justify-between mb-4">
                                        <div>
                                            <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                                                {{ $employee->name }}
                                            </h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                                {{ $employee->email }} • {{ $employee->position ?? 'No position' }}
                                            </p>
                                        </div>
                                        <button type="submit" class="px-4 py-2 bg-black dark:bg-white border border-transparent rounded-lg font-semibold text-xs text-white dark:text-black uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-100 transition ease-in-out duration-150">
                                            Update Roles
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                        @foreach($roles as $role)
                                            <label class="flex items-center">
                                                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                                                    {{ $employee->roles->contains($role->id) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 dark:border-gray-700 text-black dark:text-white focus:ring-black dark:focus:ring-white">
                                                <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                                    {{ $role->name }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>

                                    @if($employee->roles->count() > 0)
                                        <div class="mt-3 text-sm text-gray-500 dark:text-gray-400">
                                            Current roles:
                                            @foreach($employee->roles as $role)
                                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200">
                                                    {{ $role->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @endif
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
