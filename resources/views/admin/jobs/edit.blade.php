<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">Edit Job Post</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Update job posting and screening questions</p>
                </div>
                <x-black-button variant="outline" href="{{ route('admin.jobs.show', $job) }}">
                    Cancel
                </x-black-button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 mb-6">
                    <div class="flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-red-800 dark:text-red-200 mb-2">There were some errors with your submission:</h3>
                            <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-300">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('admin.jobs.update', $job) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Basic Information</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Job Title -->
                        <div class="md:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Job Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="title" id="title" value="{{ old('title', $job->title) }}" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                            @error('title')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Department -->
                        <div>
                            <label for="department" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Department
                            </label>
                            <input type="text" name="department" id="department" value="{{ old('department', $job->department) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <!-- Job Type -->
                        <div>
                            <label for="job_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Job Type <span class="text-red-500">*</span>
                            </label>
                            <select name="job_type" id="job_type" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="full-time" {{ old('job_type', $job->job_type) == 'full-time' ? 'selected' : '' }}>Full Time</option>
                                <option value="part-time" {{ old('job_type', $job->job_type) == 'part-time' ? 'selected' : '' }}>Part Time</option>
                                <option value="contract" {{ old('job_type', $job->job_type) == 'contract' ? 'selected' : '' }}>Contract</option>
                                <option value="remote" {{ old('job_type', $job->job_type) == 'remote' ? 'selected' : '' }}>Remote</option>
                            </select>
                        </div>

                        <!-- Location -->
                        <div>
                            <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Location
                            </label>
                            <input type="text" name="location" id="location" value="{{ old('location', $job->location) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <!-- Experience Level -->
                        <div>
                            <label for="experience_level" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Experience Level
                            </label>
                            <select name="experience_level" id="experience_level"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="">Select Level</option>
                                <option value="entry" {{ old('experience_level', $job->experience_level) == 'entry' ? 'selected' : '' }}>Entry Level</option>
                                <option value="mid" {{ old('experience_level', $job->experience_level) == 'mid' ? 'selected' : '' }}>Mid Level</option>
                                <option value="senior" {{ old('experience_level', $job->experience_level) == 'senior' ? 'selected' : '' }}>Senior Level</option>
                            </select>
                        </div>

                        <!-- Positions Available -->
                        <div>
                            <label for="positions_available" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Positions Available
                            </label>
                            <input type="number" name="positions_available" id="positions_available" value="{{ old('positions_available', $job->positions_available) }}" min="1"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <!-- Salary Range -->
                        <div>
                            <label for="salary_min" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Salary Min
                            </label>
                            <input type="number" name="salary_min" id="salary_min" value="{{ old('salary_min', $job->salary_min) }}" step="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <div>
                            <label for="salary_max" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Salary Max
                            </label>
                            <input type="number" name="salary_max" id="salary_max" value="{{ old('salary_max', $job->salary_max) }}" step="0.01"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <div>
                            <label for="salary_currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Currency
                            </label>
                            <select name="salary_currency" id="salary_currency"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="BDT" {{ old('salary_currency', $job->salary_currency) == 'BDT' ? 'selected' : '' }}>BDT</option>
                                <option value="USD" {{ old('salary_currency', $job->salary_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                <option value="EUR" {{ old('salary_currency', $job->salary_currency) == 'EUR' ? 'selected' : '' }}>EUR</option>
                                <option value="GBP" {{ old('salary_currency', $job->salary_currency) == 'GBP' ? 'selected' : '' }}>GBP</option>
                            </select>
                        </div>

                        <!-- Application Deadline -->
                        <div>
                            <label for="application_deadline" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Application Deadline
                            </label>
                            <input type="date" name="application_deadline" id="application_deadline"
                                   value="{{ old('application_deadline', $job->application_deadline ? $job->application_deadline->format('Y-m-d') : '') }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <!-- Contact Email -->
                        <div>
                            <label for="contact_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Contact Email
                            </label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email', $job->contact_email) }}"
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select name="status" id="status" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="draft" {{ old('status', $job->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $job->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="closed" {{ old('status', $job->status) == 'closed' ? 'selected' : '' }}>Closed</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Job Description -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <h2 class="text-lg font-semibold text-black dark:text-white mb-4">Job Details</h2>

                    <!-- Description -->
                    <div class="mb-6">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Job Description <span class="text-red-500">*</span>
                        </label>
                        <textarea name="description" id="description" rows="6" required
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">{{ old('description', $job->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Requirements -->
                    <div class="mb-6">
                        <label for="requirements" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Requirements
                        </label>
                        <textarea name="requirements" id="requirements" rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">{{ old('requirements', $job->requirements) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter each requirement on a new line</p>
                    </div>

                    <!-- Responsibilities -->
                    <div class="mb-6">
                        <label for="responsibilities" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Responsibilities
                        </label>
                        <textarea name="responsibilities" id="responsibilities" rows="6"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">{{ old('responsibilities', $job->responsibilities) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter each responsibility on a new line</p>
                    </div>

                    <!-- Benefits -->
                    <div>
                        <label for="benefits" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Benefits
                        </label>
                        <textarea name="benefits" id="benefits" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">{{ old('benefits', $job->benefits) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Enter each benefit on a new line</p>
                    </div>
                </div>

                <!-- AI Screening Configuration -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-black dark:text-white">AI Screening</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Configure AI-powered CV screening</p>
                        </div>
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="ai_screening_enabled" value="1" {{ old('ai_screening_enabled', $job->ai_screening_enabled) ? 'checked' : '' }}
                                   class="w-5 h-5 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white">
                            <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Enable AI Screening</span>
                        </label>
                    </div>

                    <div>
                        <label for="ai_screening_criteria" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            AI Screening Criteria (Optional)
                        </label>
                        <textarea name="ai_screening_criteria" id="ai_screening_criteria" rows="4"
                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
                                  placeholder="e.g., Must have 5+ years React experience, Strong communication skills, Experience with Laravel">{{ old('ai_screening_criteria', $job->ai_screening_criteria) }}</textarea>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Additional criteria for AI to consider when screening candidates</p>
                    </div>
                </div>

                <!-- Screening Questions -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-semibold text-black dark:text-white">Screening Questions</h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Add custom questions for applicants</p>
                        </div>
                        <x-black-button type="button" id="add-question-btn">
                            Add Question
                        </x-black-button>
                    </div>

                    <div id="questions-container" class="space-y-4">
                        @foreach($job->questions as $index => $question)
                            <div class="question-item border border-gray-300 dark:border-gray-600 rounded-lg p-4" data-index="{{ $index }}" data-id="{{ $question->id }}">
                                <div class="flex items-start justify-between mb-4">
                                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Question {{ $index + 1 }}</h3>
                                    <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>

                                <input type="hidden" name="questions[{{ $index }}][id]" value="{{ $question->id }}">

                                <div class="space-y-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question Text</label>
                                        <input type="text" name="questions[{{ $index }}][question]" value="{{ old('questions.' . $index . '.question', $question->question) }}" required
                                               class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                    </div>

                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question Type</label>
                                            <select name="questions[{{ $index }}][type]" onchange="updateQuestionOptions(this)" required
                                                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                                <option value="text" {{ $question->type == 'text' ? 'selected' : '' }}>Short Text</option>
                                                <option value="textarea" {{ $question->type == 'textarea' ? 'selected' : '' }}>Long Text</option>
                                                <option value="file" {{ $question->type == 'file' ? 'selected' : '' }}>File Upload</option>
                                                <option value="video" {{ $question->type == 'video' ? 'selected' : '' }}>Video Upload</option>
                                                <option value="multiple_choice" {{ $question->type == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="flex items-center cursor-pointer mt-8">
                                                <input type="checkbox" name="questions[{{ $index }}][is_required]" value="1" {{ $question->is_required ? 'checked' : '' }}
                                                       class="w-5 h-5 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white">
                                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Required</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="multiple-choice-options {{ $question->type == 'multiple_choice' ? '' : 'hidden' }}">
                                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options (one per line)</label>
                                        <textarea name="questions[{{ $index }}][options]" rows="3"
                                                  class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">{{ $question->options ? implode("\n", $question->options) : '' }}</textarea>
                                    </div>

                                    <input type="hidden" name="questions[{{ $index }}][order]" value="{{ $question->order }}">
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @if($job->questions->count() === 0)
                        <div id="empty-questions-state" class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm">No screening questions added yet. Click "Add Question" to create one.</p>
                        </div>
                    @else
                        <div id="empty-questions-state" class="hidden text-center py-8 text-gray-500 dark:text-gray-400">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-sm">No screening questions added yet. Click "Add Question" to create one.</p>
                        </div>
                    @endif
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end gap-3">
                    <x-black-button variant="outline" href="{{ route('admin.jobs.show', $job) }}">
                        Cancel
                    </x-black-button>
                    <x-black-button type="submit">
                        Update Job Post
                    </x-black-button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        let questionIndex = {{ $job->questions->count() }};

        document.getElementById('add-question-btn').addEventListener('click', function() {
            addQuestion();
        });

        function addQuestion() {
            const container = document.getElementById('questions-container');
            const emptyState = document.getElementById('empty-questions-state');

            if (emptyState) {
                emptyState.style.display = 'none';
            }

            const questionHtml = `
                <div class="question-item border border-gray-300 dark:border-gray-600 rounded-lg p-4" data-index="${questionIndex}">
                    <div class="flex items-start justify-between mb-4">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">Question ${questionIndex + 1}</h3>
                        <button type="button" onclick="removeQuestion(this)" class="text-red-500 hover:text-red-700">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                        </button>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question Text</label>
                            <input type="text" name="questions[${questionIndex}][question]" required
                                   class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Question Type</label>
                                <select name="questions[${questionIndex}][type]" onchange="updateQuestionOptions(this)" required
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                    <option value="text">Short Text</option>
                                    <option value="textarea">Long Text</option>
                                    <option value="file">File Upload</option>
                                    <option value="video">Video Upload</option>
                                    <option value="multiple_choice">Multiple Choice</option>
                                </select>
                            </div>

                            <div>
                                <label class="flex items-center cursor-pointer mt-8">
                                    <input type="checkbox" name="questions[${questionIndex}][is_required]" value="1" checked
                                           class="w-5 h-5 text-black dark:text-white border-gray-300 dark:border-gray-600 rounded focus:ring-2 focus:ring-black dark:focus:ring-white">
                                    <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Required</span>
                                </label>
                            </div>
                        </div>

                        <div class="multiple-choice-options hidden">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Options (one per line)</label>
                            <textarea name="questions[${questionIndex}][options]" rows="3"
                                      class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"></textarea>
                        </div>

                        <input type="hidden" name="questions[${questionIndex}][order]" value="${questionIndex}">
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', questionHtml);
            questionIndex++;
        }

        function removeQuestion(button) {
            const questionItem = button.closest('.question-item');
            questionItem.remove();

            const container = document.getElementById('questions-container');
            const emptyState = document.getElementById('empty-questions-state');

            if (container.children.length === 0 && emptyState) {
                emptyState.style.display = 'block';
            }

            // Renumber questions
            const questions = container.querySelectorAll('.question-item');
            questions.forEach((item, index) => {
                const title = item.querySelector('h3');
                if (title) {
                    title.textContent = `Question ${index + 1}`;
                }
            });
        }

        function updateQuestionOptions(select) {
            const questionItem = select.closest('.question-item');
            const optionsDiv = questionItem.querySelector('.multiple-choice-options');

            if (select.value === 'multiple_choice') {
                optionsDiv.classList.remove('hidden');
            } else {
                optionsDiv.classList.add('hidden');
            }
        }
    </script>
    @endpush
</x-app-layout>
