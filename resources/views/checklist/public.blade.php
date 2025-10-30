<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Checklist - {{ $checklist->template->title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <!-- Header -->
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">📋 Daily Checklist</h1>
                <p class="text-gray-600">{{ now()->format('l, F j, Y') }}</p>
                <p class="text-sm text-gray-500 mt-2">For: {{ $checklist->employee->first_name }} {{ $checklist->employee->last_name }}</p>
            </div>

            @if(session('status'))
                <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 rounded-xl text-green-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1">{{ session('status') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 px-4 py-3 bg-red-50 border border-red-200 rounded-xl text-red-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1">{{ session('error') }}</span>
                </div>
            @endif

            @if($isExpired)
                <div class="mb-6 px-4 py-3 bg-orange-50 border border-orange-200 rounded-xl text-orange-700 flex items-center gap-3 shadow-sm">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="flex-1">⚠️ This checklist link has expired (valid for 12 hours). You can view it but cannot make changes.</span>
                </div>
            @endif

            <!-- Checklist Card -->
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                <!-- Checklist Header -->
                <div class="bg-gradient-to-r from-gray-800 to-gray-700 p-6">
                    <h2 class="text-2xl font-bold text-white mb-2">{{ $checklist->template->title }}</h2>
                    @if($checklist->template->description)
                        <p class="text-gray-200 text-sm">{{ $checklist->template->description }}</p>
                    @endif
                </div>

                <!-- Progress Bar -->
                @php
                    $completedCount = $checklist->items->where('is_completed', true)->count();
                    $totalCount = $checklist->items->count();
                    $percentage = $totalCount > 0 ? round(($completedCount / $totalCount) * 100) : 0;
                @endphp
                
                <div class="p-6 bg-gray-50 border-b border-gray-200">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Progress</span>
                        <span class="text-2xl font-bold text-gray-900">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        <div class="bg-gradient-to-r from-green-500 to-green-600 h-3 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                    <p class="text-sm text-gray-600 mt-2">{{ $completedCount }} of {{ $totalCount }} items completed</p>
                </div>

                <!-- Checklist Items -->
                <div class="p-6">
                    <div class="space-y-3">
                        @foreach($checklist->items as $item)
                            <div class="flex items-start gap-4 p-4 rounded-xl border-2 transition-all hover:shadow-md {{ $item->is_completed ? 'bg-green-50 border-green-200' : 'bg-white border-gray-200' }}">
                                <div class="flex-shrink-0 mt-0.5">
                                    @if($item->is_completed)
                                        <div class="w-6 h-6 rounded-md bg-green-500 flex items-center justify-center">
                                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </div>
                                    @else
                                        <div class="w-6 h-6 rounded-md border-2 border-gray-300 bg-white"></div>
                                    @endif
                                </div>
                                
                                <div class="flex-1">
                                    <p class="text-base {{ $item->is_completed ? 'line-through text-gray-600' : 'text-gray-900 font-medium' }}">
                                        {{ $item->title }}
                                    </p>
                                    @if($item->completed_at)
                                        <p class="text-sm text-green-600 mt-1">
                                            ✓ Completed at {{ $item->completed_at->format('g:i A') }}
                                        </p>
                                    @endif
                                </div>

                                <div class="flex-shrink-0">
                                    @if(!$isExpired)
                                        <form method="GET" action="{{ route('checklist.public.toggle', ['token' => $checklist->email_token, 'item' => $item->id]) }}">
                                            <button type="submit" class="px-4 py-2 rounded-lg font-medium text-sm transition-all {{ $item->is_completed ? 'bg-gray-100 text-gray-700 hover:bg-gray-200' : 'bg-black text-white hover:bg-gray-800' }}">
                                                {{ $item->is_completed ? 'Undo' : 'Complete' }}
                                            </button>
                                        </form>
                                    @else
                                        <span class="px-4 py-2 bg-gray-200 text-gray-500 rounded-lg font-medium text-sm cursor-not-allowed">
                                            Expired
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                    <p class="text-center text-sm text-gray-600">
                        This is your daily checklist. Check off items as you complete them!
                    </p>
                </div>
            </div>

            <!-- Back to Email Notice -->
            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    You can also check off items directly from your email.
                </p>
            </div>
        </div>
    </div>
</body>
</html>

