<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('uat.index') }}" class="text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $project->name }}
                    </h2>
                    <div class="flex items-center gap-2 mt-1">
                        <span class="px-2 py-0.5 text-xs font-medium rounded-full
                            {{ $project->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                            {{ $project->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                            {{ $project->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                        ">
                            {{ ucfirst($project->status) }}
                        </span>
                        @if($project->deadline)
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Due: {{ $project->deadline->format('M d, Y') }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2" x-data="{ showDeleteConfirm: false }">
                <button onclick="copyUrl('{{ $project->public_url }}')" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy UAT Link
                </button>
                <a href="{{ route('uat.edit', $project) }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Project
                </a>
                <button @click="showDeleteConfirm = true" type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete
                </button>

                <!-- Confirmation Dialog -->
                <div x-show="showDeleteConfirm" 
                    x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-150"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    @click.away="showDeleteConfirm = false"
                    class="fixed inset-0 z-50 overflow-y-auto" 
                    style="display: none;">
                    <div class="flex items-center justify-center min-h-screen px-4">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                        
                        <div class="relative bg-white dark:bg-gray-800 rounded-lg max-w-md w-full p-6 shadow-xl">
                            <div class="flex items-start gap-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete UAT Project</h3>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                        Are you sure you want to delete <strong>"{{ $project->name }}"</strong>? This will permanently delete all test cases, feedback, and user data. This action cannot be undone.
                                    </p>
                                    <div class="flex gap-3 justify-end">
                                        <button @click="showDeleteConfirm = false" type="button"
                                            class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 transition-all">
                                            Cancel
                                        </button>
                                        <form action="{{ route('uat.destroy', $project) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 transition-all">
                                                Delete Project
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Project Info -->
            @if($project->description)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <p class="text-gray-700 dark:text-gray-300">{!! $project->description !!}</p>
                </div>
            @endif

            <!-- Users Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Users ({{ $project->users->count() }})</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($project->users as $user)
                            <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $user->role === 'internal' ? 'bg-blue-100 dark:bg-blue-900' : 'bg-gray-200 dark:bg-gray-600' }} flex items-center justify-center">
                                        <span class="text-sm font-medium {{ $user->role === 'internal' ? 'text-blue-800 dark:text-blue-200' : 'text-gray-700 dark:text-gray-300' }}">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </span>
                                    </div>
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        {{-- <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div> --}}
                                        <div class="text-xs">
                                            <span class="px-2 py-0.5 rounded {{ $user->role === 'internal' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Test Cases Section -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg" x-data="{ showCreateForm: false }">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Test Cases ({{ $project->testCases->count() }})</h3>
                        <button @click="showCreateForm = !showCreateForm" 
                            class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full shadow-lg hover:bg-gray-800 dark:hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Add Test Case
                        </button>
                    </div>

                    <!-- Create Test Case Form -->
                    <div x-show="showCreateForm" x-collapse class="mb-6">
                        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 overflow-hidden shadow-sm">
                            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Create New Test Case</h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fill in the details below</p>
                                    </div>
                                    <button type="button" @click="showCreateForm = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <form action="{{ route('uat.test-cases.store', $project) }}" method="POST" 
                                x-data="{ 
                                    steps: [{ text: '' }],
                                    addStep() { this.steps.push({ text: '' }); },
                                    removeStep(index) { if (this.steps.length > 1) this.steps.splice(index, 1); },
                                    descriptionEditor: null,
                                    stepsEditor: null,
                                    expectedResultEditor: null,
                                    initEditors() {
                                        this.$nextTick(() => {
                                            if (!this.descriptionEditor) {
                                                this.descriptionEditor = new Quill('#create-description-editor', {
                                                    theme: 'snow',
                                                    placeholder: 'Brief description of what this test case covers...',
                                                    modules: {
                                                        toolbar: [
                                                            ['bold', 'italic', 'underline', 'strike'],
                                                            ['blockquote', 'code-block'],
                                                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                            [{ 'header': [1, 2, 3, false] }],
                                                            ['link'],
                                                            ['clean']
                                                        ]
                                                    }
                                                });
                                            }
                                            if (!this.stepsEditor) {
                                                this.stepsEditor = new Quill('#create-steps-editor', {
                                                    theme: 'snow',
                                                    placeholder: 'Describe the testing steps in detail...',
                                                    modules: {
                                                        toolbar: [
                                                            ['bold', 'italic', 'underline'],
                                                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                            ['link', 'code-block'],
                                                            ['clean']
                                                        ]
                                                    }
                                                });
                                            }
                                            if (!this.expectedResultEditor) {
                                                this.expectedResultEditor = new Quill('#create-expected-result-editor', {
                                                    theme: 'snow',
                                                    placeholder: 'What should happen when the test is performed correctly...',
                                                    modules: {
                                                        toolbar: [
                                                            ['bold', 'italic', 'underline'],
                                                            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                            ['link', 'code-block'],
                                                            ['clean']
                                                        ]
                                                    }
                                                });
                                            }
                                        });
                                    }
                                }"
                                x-init="initEditors()"
                                @submit="
                                    $event.target.querySelector('[name=description]').value = descriptionEditor.root.innerHTML;
                                    $event.target.querySelector('[name=steps]').value = stepsEditor.root.innerHTML;
                                    $event.target.querySelector('[name=expected_result]').value = expectedResultEditor.root.innerHTML;
                                " 
                                class="p-6 space-y-6">
                                @csrf

                                <!-- Title & Priority Row -->
                                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                    <div class="lg:col-span-2">
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                            Title <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="title" required 
                                            placeholder="e.g., User Login with Valid Credentials"
                                            class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all">
                                    </div>
                                    <div>
                                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                            Priority <span class="text-red-500">*</span>
                                        </label>
                                        <select name="priority" required 
                                            class="block w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-gray-900 dark:focus:border-white focus:ring-1 focus:ring-gray-900 dark:focus:ring-white transition-all">
                                            <option value="low">Low</option>
                                            <option value="medium" selected>Medium</option>
                                            <option value="high">High</option>
                                            <option value="critical">Critical</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- Description -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                        Description
                                    </label>
                                    <div id="create-description-editor" class="bg-white dark:bg-gray-700 rounded-lg" style="min-height: 150px;"></div>
                                    <input type="hidden" name="description">
                                </div>

                                <!-- Steps to Test -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                        Testing Steps
                                    </label>
                                    <div id="create-steps-editor" class="bg-white dark:bg-gray-700 rounded-lg" style="min-height: 200px;"></div>
                                    <input type="hidden" name="steps">
                                </div>

                                <!-- Expected Result -->
                                <div>
                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                        Expected Result
                                    </label>
                                    <div id="create-expected-result-editor" class="bg-white dark:bg-gray-700 rounded-lg" style="min-height: 150px;"></div>
                                    <input type="hidden" name="expected_result">
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex justify-end gap-3 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button type="button" @click="showCreateForm = false" 
                                        class="px-4 py-2 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-medium hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                        Cancel
                                    </button>
                                    <button type="submit" 
                                        class="inline-flex items-center gap-2 px-5 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full font-medium hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Create Test Case
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Test Cases List -->
                    @if($project->testCases->count() > 0)
                        <div class="space-y-4">
                            @foreach($project->testCases as $index => $testCase)
                                <div class="relative bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-lg transition-all">
                                    <!-- Header -->
                                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                                        <div class="flex items-start gap-4">
                                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-black dark:bg-white flex items-center justify-center">
                                                <span class="text-sm font-bold text-white dark:text-black">#{{ $index + 1 }}</span>
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-3 flex-wrap mb-1">
                                                    <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $testCase->title }}</h4>
                                                    <span class="px-3 py-1 text-xs font-bold rounded-full
                                                        {{ $testCase->priority === 'critical' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                        {{ $testCase->priority === 'high' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                        {{ $testCase->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                        {{ $testCase->priority === 'low' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                    ">
                                                        {{ ucfirst($testCase->priority) }}
                                                    </span>
                                                    @php
                                                        $clientFeedbackCount = $testCase->feedbacks->where('user.role', 'external')->count();
                                                    @endphp
                                                    @if($clientFeedbackCount > 0)
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-blue-600 text-white">
                                                            💬 {{ $clientFeedbackCount }} Client {{ $clientFeedbackCount === 1 ? 'Reply' : 'Replies' }}
                                                        </span>
                                                    @endif
                                                </div>
                                                @if($testCase->description)
                                                    <p class="text-sm text-gray-600 dark:text-gray-400">{!! $testCase->description !!}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="p-6 space-y-5">
                                        @if($testCase->description)
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                                    {!! $testCase->description !!}
                                                </div>
                                            </div>
                                        @endif

                                        @if($testCase->steps)
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">Steps to Test</h5>
                                                <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                                    {!! $testCase->steps !!}
                                                </div>
                                            </div>
                                        @endif

                                        @if($testCase->expected_result)
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wide">Expected Result</h5>
                                                <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                                    {!! $testCase->expected_result !!}
                                                </div>
                                            </div>
                                        @endif

                                        <!-- Feedback Summary -->
                                        @if($testCase->feedbacks->count() > 0)
                                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">Feedback Summary</h5>
                                                
                                                <!-- Status Badges -->
                                                <div class="flex flex-wrap gap-2 mb-4">
                                                    @php
                                                        $feedbackCounts = $testCase->feedbacks->groupBy('status')->map->count();
                                                    @endphp
                                                    @if($feedbackCounts->get('passed', 0) > 0)
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                            ✅ {{ $feedbackCounts->get('passed') }} Passed
                                                        </span>
                                                    @endif
                                                    @if($feedbackCounts->get('failed', 0) > 0)
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                            ❌ {{ $feedbackCounts->get('failed') }} Failed
                                                        </span>
                                                    @endif
                                                    @if($feedbackCounts->get('blocked', 0) > 0)
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                            🚫 {{ $feedbackCounts->get('blocked') }} Blocked
                                                        </span>
                                                    @endif
                                                    @if($feedbackCounts->get('pending', 0) > 0)
                                                        <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                            ⏳ {{ $feedbackCounts->get('pending') }} Pending
                                                        </span>
                                                    @endif
                                                </div>

                                                <!-- Detailed Feedback -->
                                                <div class="space-y-3">
                                                    @forelse($testCase->feedbacks as $feedback)
                                                        <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-2 {{ $feedback->user->role === 'external' ? 'border-blue-200 dark:border-blue-900 bg-blue-50/30 dark:bg-blue-900/10' : 'border-gray-200 dark:border-gray-700' }}">
                                                            <div class="flex items-start justify-between mb-2">
                                                                <div class="flex items-center gap-2">
                                                                    <div class="w-8 h-8 rounded-full {{ $feedback->user->role === 'internal' ? 'bg-black dark:bg-white' : 'bg-blue-600 dark:bg-blue-500' }} flex items-center justify-center">
                                                                        <span class="text-xs font-bold text-white dark:text-black">
                                                                            {{ strtoupper(substr($feedback->user->name, 0, 2)) }}
                                                                        </span>
                                                                    </div>
                                                                    <div>
                                                                        <div class="flex items-center gap-2">
                                                                            <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $feedback->user->name }}</span>
                                                                            <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $feedback->user->role === 'internal' ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-blue-600 text-white' }}">
                                                                                {{ $feedback->user->role === 'internal' ? '👔 Employee' : '👤 Client' }}
                                                                            </span>
                                                                        </div>
                                                                        <span class="text-xs text-gray-500 dark:text-gray-400">{{ $feedback->created_at->diffForHumans() }}</span>
                                                                    </div>
                                                                </div>
                                                                <span class="px-3 py-1 text-xs font-bold rounded-full
                                                                    {{ $feedback->status === 'passed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                                    {{ $feedback->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                                    {{ $feedback->status === 'blocked' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                                    {{ $feedback->status === 'pending' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                                ">
                                                                    {{ ucfirst($feedback->status) }}
                                                                </span>
                                                            </div>
                                                            @if($feedback->comment)
                                                                <div class="pl-10 mt-2">
                                                                    <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">{{ $feedback->comment }}</p>
                                                                </div>
                                                            @else
                                                                <div class="pl-10 mt-2">
                                                                    <p class="text-xs italic text-gray-500 dark:text-gray-400">Status updated to: {{ ucfirst($feedback->status) }}</p>
                                                                </div>
                                                            @endif
                                                            @if($feedback->attachment_path)
                                                                <a href="{{ Storage::url($feedback->attachment_path) }}" target="_blank" 
                                                                    class="inline-flex items-center gap-2 mt-3 ml-10 px-3 py-1.5 text-xs font-medium bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                                    </svg>
                                                                    View Attachment
                                                                </a>
                                                            @endif
                                                        </div>
                                                    @empty
                                                        <div class="text-center py-4 text-sm text-gray-500 dark:text-gray-400">
                                                            No feedback yet
                                                        </div>
                                                    @endforelse
                                                </div>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Footer: Edit & Delete Buttons -->
                                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-between items-center">
                                        <a href="{{ route('uat.test-cases.edit', [$project, $testCase]) }}"
                                            class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                            </svg>
                                            Edit
                                        </a>
                                        
                                        <form action="{{ route('uat.test-cases.destroy', [$project, $testCase]) }}" method="POST" 
                                            onsubmit="return confirm('Are you sure you want to delete this test case?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-full transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No test cases</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a test case.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyUrl(url) {
            navigator.clipboard.writeText(url).then(() => {
                alert('UAT link copied to clipboard!');
            });
        }
    </script>
    @endpush
</x-app-layout>

