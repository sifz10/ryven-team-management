<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Review Cycles') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Manage quarterly and annual review periods</p>
            </div>
            <a href="{{ route('review-cycles.create') }}" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Cycle
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($cycles->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-800 to-black mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">No Review Cycles Yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                Create your first review cycle to start managing quarterly and annual performance reviews.
                            </p>
                            <a href="{{ route('review-cycles.create') }}" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Create First Cycle
                            </a>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($cycles as $cycle)
                                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl p-6 hover:shadow-lg transition-all">
                                    <div class="flex justify-between items-start">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-3">
                                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $cycle->name }}</h3>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full 
                                                    @if($cycle->status === 'active') bg-green-500 text-white
                                                    @elseif($cycle->status === 'scheduled') bg-blue-500 text-white
                                                    @elseif($cycle->status === 'completed') bg-gray-500 text-white
                                                    @else bg-red-500 text-white
                                                    @endif">
                                                    {{ ucfirst($cycle->status) }}
                                                </span>
                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-purple-500 text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $cycle->type)) }}
                                                </span>
                                            </div>
                                            @if($cycle->description)
                                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">{{ $cycle->description }}</p>
                                            @endif
                                            <div class="flex flex-wrap gap-4 text-sm text-gray-600 dark:text-gray-400">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    <span>{{ $cycle->start_date->format('M d, Y') }} - {{ $cycle->end_date->format('M d, Y') }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <span>Due: {{ $cycle->review_due_date->format('M d, Y') }}</span>
                                                </div>
                                                @if($cycle->creator)
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                        </svg>
                                                        <span>{{ $cycle->creator->name }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex gap-2 ml-4">
                                            <a href="{{ route('review-cycles.show', $cycle) }}" 
                                                class="inline-flex items-center gap-1 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                                View
                                            </a>
                                            <a href="{{ route('review-cycles.edit', $cycle) }}" 
                                                class="inline-flex items-center gap-1 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                                Edit
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $cycles->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
