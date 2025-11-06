<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Goals & OKRs') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track objectives, key results, and performance metrics</p>
            </div>
            <a href="{{ route('goals.create') }}" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Goal
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($goals->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-800 to-black mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">No Goals Yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                Set your first goal to start tracking OKRs, KPIs, and performance achievements.
                            </p>
                            <a href="{{ route('goals.create') }}" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create First Goal
                            </a>
                        </div>
                    @else
                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($goals as $goal)
                                <div class="border dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-3">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg mb-1">{{ $goal->title }}</h3>
                                            <div class="flex gap-2 mb-2">
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                    {{ strtoupper($goal->type) }}
                                                </span>
                                                <span class="px-2 py-0.5 text-xs font-semibold rounded 
                                                    @if($goal->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                    @elseif($goal->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                    @elseif($goal->status === 'on_hold') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                    @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                    @endif">
                                                    {{ ucfirst(str_replace('_', ' ', $goal->status)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($goal->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">{{ $goal->description }}</p>
                                    @endif
                                    
                                    <!-- Progress Bar -->
                                    <div class="mb-3">
                                        <div class="flex justify-between text-sm mb-1">
                                            <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                            <span class="font-semibold">{{ $goal->progress ?? 0 }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-blue-600 h-2 rounded-full transition-all" style="width: {{ $goal->progress ?? 0 }}%"></div>
                                        </div>
                                    </div>

                                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                                        <div class="flex items-center gap-2">
                                            <span>👤</span>
                                            <span>{{ $goal->employee->first_name }} {{ $goal->employee->last_name }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span>📅</span>
                                            <span>{{ $goal->due_date->format('M d, Y') }}</span>
                                            @if($goal->isOverdue())
                                                <span class="text-red-600 dark:text-red-400 font-semibold">(Overdue)</span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="mt-4 flex gap-2">
                                        <a href="{{ route('goals.show', $goal) }}" 
                                            class="flex-1 text-center px-3 py-2 bg-blue-100 text-blue-700 dark:bg-blue-900 dark:text-blue-200 rounded hover:bg-blue-200 dark:hover:bg-blue-800 transition text-sm font-medium">
                                            View Details
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $goals->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
