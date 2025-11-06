<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                    {{ $reviewCycle->name }}
                </h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ ucfirst(str_replace('_', ' ', $reviewCycle->type)) }} Review Cycle
                </p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('review-cycles.edit', $reviewCycle) }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-600 text-white rounded-full shadow-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <span>Edit</span>
                </a>
                <a href="{{ route('review-cycles.index') }}" 
                   class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <span>Back to Cycles</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            <!-- Cycle Details Card -->
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl dark:bg-gray-800">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Cycle Information
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <!-- Status -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Status</label>
                            <div class="mt-1">
                                @php
                                    $statusColors = [
                                        'scheduled' => 'bg-blue-500',
                                        'active' => 'bg-green-500',
                                        'completed' => 'bg-gray-500',
                                        'cancelled' => 'bg-red-500'
                                    ];
                                    $statusColor = $statusColors[$reviewCycle->status] ?? 'bg-gray-500';
                                @endphp
                                <span class="inline-flex items-center gap-1.5 rounded-full {{ $statusColor }} px-3 py-1 text-xs font-medium text-white">
                                    <span class="h-1.5 w-1.5 rounded-full bg-white"></span>
                                    {{ ucfirst($reviewCycle->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Type</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ ucfirst(str_replace('_', ' ', $reviewCycle->type)) }}
                            </p>
                        </div>

                        <!-- Created By -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Created By</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $reviewCycle->creator->name ?? 'Unknown' }}
                            </p>
                        </div>

                        <!-- Start Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Start Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $reviewCycle->start_date->format('M d, Y') }}
                            </p>
                        </div>

                        <!-- End Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">End Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $reviewCycle->end_date->format('M d, Y') }}
                            </p>
                        </div>

                        <!-- Review Due Date -->
                        <div>
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Review Due Date</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $reviewCycle->review_due_date->format('M d, Y') }}
                            </p>
                        </div>
                    </div>

                    @if($reviewCycle->description)
                        <div class="mt-6 border-t border-gray-200 pt-6 dark:border-gray-700">
                            <label class="block text-sm font-medium text-gray-600 dark:text-gray-400">Description</label>
                            <p class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                                {{ $reviewCycle->description }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Performance Reviews Section -->
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Performance Reviews
                            <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                                ({{ $reviewCycle->performanceReviews->count() }})
                            </span>
                        </h3>
                        <a href="{{ route('reviews.create', ['cycle' => $reviewCycle->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white rounded-full text-sm shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>New Review</span>
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($reviewCycle->performanceReviews->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-800 to-black mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No performance reviews for this cycle yet</p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Create your first review to get started</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900">
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">Employee</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">Status</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">Rating</th>
                                        <th class="px-4 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">Reviewer</th>
                                        <th class="px-4 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-700 dark:text-gray-300">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 bg-white dark:divide-gray-700 dark:bg-gray-800">
                                    @foreach($reviewCycle->performanceReviews as $review)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $review->employee->name }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm">
                                                @php
                                                    $reviewStatusColors = [
                                                        'draft' => 'bg-gray-500',
                                                        'in_progress' => 'bg-blue-500',
                                                        'under_review' => 'bg-yellow-500',
                                                        'completed' => 'bg-green-500'
                                                    ];
                                                    $reviewStatusColor = $reviewStatusColors[$review->status] ?? 'bg-gray-500';
                                                @endphp
                                                <span class="inline-flex rounded-full {{ $reviewStatusColor }} px-2 py-1 text-xs font-medium text-white">
                                                    {{ ucfirst(str_replace('_', ' ', $review->status)) }}
                                                </span>
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $review->overall_rating ?? 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                                {{ $review->reviewer->name ?? 'N/A' }}
                                            </td>
                                            <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                                                <a href="{{ route('reviews.show', $review) }}" 
                                                   class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                                                    View
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Goals Section -->
            <div class="overflow-hidden bg-white shadow-lg sm:rounded-2xl dark:bg-gray-800">
                <div class="border-b border-gray-200 bg-gray-50 px-6 py-4 dark:border-gray-700 dark:bg-gray-900">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Goals & OKRs
                            <span class="ml-2 text-sm font-normal text-gray-500 dark:text-gray-400">
                                ({{ $reviewCycle->goals->count() }})
                            </span>
                        </h3>
                        <a href="{{ route('goals.create', ['cycle' => $reviewCycle->id]) }}" 
                           class="inline-flex items-center gap-2 px-4 py-2 bg-black text-white rounded-full text-sm shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            <span>New Goal</span>
                        </a>
                    </div>
                </div>
                
                <div class="p-6">
                    @if($reviewCycle->goals->isEmpty())
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-gray-800 to-black mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">No goals set for this cycle yet</p>
                            <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Create your first goal to track progress</p>
                        </div>
                    @else
                        <div class="space-y-4">
                            @foreach($reviewCycle->goals as $goal)
                                <div class="flex items-center justify-between rounded-lg border border-gray-200 p-4 dark:border-gray-700">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3">
                                            <h4 class="font-medium text-gray-900 dark:text-gray-100">{{ $goal->title }}</h4>
                                            @php
                                                $goalStatusColors = [
                                                    'not_started' => 'bg-gray-500',
                                                    'in_progress' => 'bg-blue-500',
                                                    'completed' => 'bg-green-500',
                                                    'on_hold' => 'bg-yellow-500',
                                                    'cancelled' => 'bg-red-500'
                                                ];
                                                $goalStatusColor = $goalStatusColors[$goal->status] ?? 'bg-gray-500';
                                            @endphp
                                            <span class="inline-flex rounded-full {{ $goalStatusColor }} px-2 py-1 text-xs font-medium text-white">
                                                {{ ucfirst(str_replace('_', ' ', $goal->status)) }}
                                            </span>
                                        </div>
                                        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                            {{ $goal->employee->name }} • {{ ucfirst($goal->type) }}
                                        </p>
                                        
                                        <!-- Progress Bar -->
                                        <div class="mt-3">
                                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                                <span>Progress</span>
                                                <span class="font-medium">{{ $goal->progress }}%</span>
                                            </div>
                                            <div class="mt-1 h-2 w-full overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                                                <div class="h-full bg-gradient-to-r from-blue-500 to-green-500 transition-all" 
                                                     style="width: {{ $goal->progress }}%"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('goals.show', $goal) }}" 
                                       class="ml-4 text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                                        View
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
