<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center gap-2 px-4 py-2 text-white rounded-full transition-all shadow-md" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back
            </a>
            <div class="flex-1">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    📊 {{ $project->name }} - Today's Work
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ \Carbon\Carbon::parse($date)->format('l, F d, Y') }}</p>
            </div>
            <div class="flex items-center gap-3">
                <input type="date" id="date-picker" value="{{ $date }}" max="{{ now()->toDateString() }}"
                    class="px-4 py-2.5 bg-white dark:bg-gray-800 text-gray-900 dark:text-white rounded-full border-2 border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-500 transition-all">
                <button onclick="sendReport()" class="inline-flex items-center gap-2 px-6 py-2.5 text-white rounded-full shadow-lg focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all font-semibold" style="background-color: #000000; border: 2px solid #000000;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Send to Client
                </button>
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            @if (session('status'))
                <div class="bg-gray-100 dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-700 text-gray-800 dark:text-gray-300 px-5 py-4 rounded-2xl flex items-center gap-3 shadow-lg">
                    <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span class="font-medium">{{ session('status') }}</span>
                </div>
            @endif

            <!-- Project Info Card -->
            <div class="bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border-2 border-gray-200 dark:border-gray-700 shadow-xl">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">{{ $project->name }}</h3>
                        @if($project->description)
                            <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $project->description }}</p>
                        @endif
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold text-white" style="background-color: #000000; border: 1px solid #333333;">
                                {{ ucfirst($project->status) }}
                            </span>
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-semibold text-white" style="background-color: #000000; border: 1px solid #333333;">
                                Priority: {{ $project->priority_label }}
                            </span>
                        </div>
                    </div>
                    @if($project->client_name)
                        <div class="bg-white/50 dark:bg-gray-700/50 rounded-xl p-4 border border-gray-300 dark:border-gray-600">
                            <p class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-1">Client</p>
                            <p class="font-semibold text-gray-900 dark:text-white">{{ $project->client_name }}</p>
                            @if($project->client_company)
                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->client_company }}</p>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <!-- Work Log Summary -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl p-6 border-2 border-gray-200 dark:border-gray-700 shadow-xl">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                        <svg class="w-6 h-6 text-gray-700 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Work Submissions ({{ $workSubmissions->count() }})
                    </h3>
                </div>

                @forelse($workSubmissions as $submission)
                    <div class="mb-4 last:mb-0" x-data="{ editing: false, description: '{{ addslashes($submission->work_description) }}' }">
                        <div class="bg-gray-50 dark:bg-gray-700/50 border-2 border-gray-200 dark:border-gray-600 rounded-2xl p-5 hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm" style="background-color: #000000; border: 2px solid #333333;">
                                        {{ substr($submission->employee->first_name, 0, 1) }}{{ substr($submission->employee->last_name, 0, 1) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900 dark:text-white">{{ $submission->employee->first_name }} {{ $submission->employee->last_name }}</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $submission->created_at->format('g:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2">
                                    <button @click="editing = !editing" class="inline-flex items-center gap-1.5 px-4 py-2 text-white rounded-full text-sm font-medium transition-all shadow-lg" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                        <span x-text="editing ? 'Cancel' : 'Edit'"></span>
                                    </button>
                                    <form action="{{ route('projects.work.delete', [$project, $submission]) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this work entry?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center gap-1.5 px-4 py-2 text-white rounded-full text-sm font-medium transition-all shadow-lg" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                            
                            <!-- View Mode -->
                            <div x-show="!editing" class="text-gray-700 dark:text-gray-300 leading-relaxed pl-13">
                                {{ $submission->work_description }}
                            </div>
                            
                            <!-- Edit Mode -->
                            <div x-show="editing" x-cloak class="pl-13">
                                <form action="{{ route('projects.work.update', [$project, $submission]) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <textarea x-model="description" name="work_description" rows="4" required
                                        class="w-full px-4 py-3 bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-gray-500 dark:focus:ring-gray-500 focus:border-gray-500 dark:focus:border-gray-500 transition-all resize-none"></textarea>
                                    <div class="flex items-center gap-2 mt-3">
                                        <button type="submit" class="inline-flex items-center gap-2 px-5 py-2 text-white rounded-full text-sm font-semibold transition-all shadow-lg" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                            Save Changes
                                        </button>
                                        <button type="button" @click="editing = false; description = '{{ addslashes($submission->work_description) }}'" class="px-5 py-2 text-white rounded-full text-sm font-semibold transition-all" style="background-color: #000000; border: 1px solid #333333;" onmouseover="this.style.backgroundColor='#1a1a1a'" onmouseout="this.style.backgroundColor='#000000'">
                                            Cancel
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-20 h-20 mx-auto mb-4 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        <p class="text-gray-600 dark:text-gray-400 font-medium text-lg">No work logged for this date</p>
                        <p class="text-gray-500 dark:text-gray-500 text-sm mt-2">Your team hasn't submitted any updates yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        // Date picker navigation
        document.getElementById('date-picker').addEventListener('change', function() {
            const selectedDate = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('date', selectedDate);
            window.location.href = url.toString();
        });

        // Send report function
        function sendReport() {
            if (confirm('Send this report to the client via Email and SMS?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route('projects.send-report', $project) }}';
                
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                const dateInput = document.createElement('input');
                dateInput.type = 'hidden';
                dateInput.name = 'date';
                dateInput.value = '{{ $date }}';
                form.appendChild(dateInput);
                
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>

    <style>
        [x-cloak] { display: none !important; }
    </style>
    @endpush
</x-app-layout>
