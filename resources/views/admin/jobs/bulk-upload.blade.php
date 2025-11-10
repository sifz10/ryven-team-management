<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900">
        <!-- Page Header -->
        <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-black dark:text-white">Bulk Resume Upload</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Upload multiple resumes for AI analysis</p>
                    <p class="text-sm text-gray-500 dark:text-gray-500 mt-1">Job: <span class="font-semibold text-black dark:text-white">{{ $job->title }}</span></p>
                </div>
                <x-black-button variant="outline" href="{{ route('admin.jobs.show', $job) }}">
                    Back to Job
                </x-black-button>
            </div>
        </div>

        <!-- Content -->
        <div class="p-6">
            <div class="max-w-4xl mx-auto">
                <!-- Job Context Card -->
                <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-6 mb-6">
                    <div class="flex items-start gap-3">
                        <div class="flex-shrink-0">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="text-sm font-semibold text-blue-900 dark:text-blue-100 mb-2">AI Screening Context</h3>
                            <p class="text-sm text-blue-800 dark:text-blue-200 mb-2">
                                Resumes will be analyzed against this job posting's requirements using AI.
                            </p>
                            @if($job->ai_screening_enabled)
                                <div class="text-sm text-blue-700 dark:text-blue-300">
                                    <span class="font-medium">✓ AI Screening Enabled</span>
                                    @if($job->ai_screening_criteria)
                                        <p class="mt-2 text-xs">Criteria: {{ Str::limit($job->ai_screening_criteria, 150) }}</p>
                                    @endif
                                </div>
                            @else
                                <div class="text-sm text-orange-700 dark:text-orange-300">
                                    <span class="font-medium">⚠ AI Screening Disabled</span> - Resumes will be saved without AI analysis
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Upload Form -->
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-8">
                    <form action="{{ route('admin.jobs.bulk-upload.process', $job) }}" method="POST" enctype="multipart/form-data" id="bulk-upload-form">
                        @csrf

                        <!-- Drag and Drop Area -->
                        <div id="drop-zone" class="relative border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-xl p-12 text-center hover:border-black dark:hover:border-white transition-all duration-300 cursor-pointer group">
                            <input type="file" name="resumes[]" id="resumes" accept=".pdf,.doc,.docx" multiple required class="hidden">

                            <div id="upload-prompt" class="pointer-events-none">
                                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400 dark:text-gray-500 group-hover:text-black dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Drop resumes here or click to browse
                                </h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">
                                    Upload multiple resume files at once
                                </p>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    Supported formats: PDF, DOC, DOCX (Max 10MB each)
                                </p>
                            </div>

                            <!-- File List -->
                            <div id="file-list" class="hidden mt-6 space-y-2 text-left"></div>
                        </div>

                        @error('resumes')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        @error('resumes.*')
                            <p class="mt-2 text-sm text-red-500">{{ $message }}</p>
                        @enderror

                        <!-- Progress Bar -->
                        <div id="upload-progress" class="hidden mt-6">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">Processing resumes...</span>
                                <span id="progress-text" class="text-sm text-gray-500 dark:text-gray-400">0%</span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2.5">
                                <div id="progress-bar" class="bg-black dark:bg-white h-2.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end gap-3 mt-8">
                            <x-black-button variant="outline" href="{{ route('admin.jobs.show', $job) }}" type="button">
                                Cancel
                            </x-black-button>
                            <x-black-button type="submit" id="submit-btn" disabled>
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Analyze Resumes with AI
                            </x-black-button>
                        </div>
                    </form>
                </div>

                <!-- Instructions -->
                <div class="mt-6 bg-gray-50 dark:bg-gray-800/50 rounded-lg p-6">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-3">How it works:</h3>
                    <ol class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-6 h-6 bg-black dark:bg-white text-white dark:text-black rounded-full flex items-center justify-center text-xs font-bold">1</span>
                            <span>Upload multiple resume files (you can drag and drop or click to browse)</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-6 h-6 bg-black dark:bg-white text-white dark:text-black rounded-full flex items-center justify-center text-xs font-bold">2</span>
                            <span>AI will extract text from each resume and analyze it against the job requirements</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-6 h-6 bg-black dark:bg-white text-white dark:text-black rounded-full flex items-center justify-center text-xs font-bold">3</span>
                            <span>Resumes will be automatically categorized as "Best Match", "Good to Go", or "Not a Good Fit"</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="flex-shrink-0 w-6 h-6 bg-black dark:bg-white text-white dark:text-black rounded-full flex items-center justify-center text-xs font-bold">4</span>
                            <span>Review the results and manage applications from the job details page</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('resumes');
        const fileList = document.getElementById('file-list');
        const uploadPrompt = document.getElementById('upload-prompt');
        const submitBtn = document.getElementById('submit-btn');
        const form = document.getElementById('bulk-upload-form');
        const progressDiv = document.getElementById('upload-progress');
        const progressBar = document.getElementById('progress-bar');
        const progressText = document.getElementById('progress-text');

        // Click to browse
        dropZone.addEventListener('click', () => fileInput.click());

        // Drag and drop events
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.add('border-black', 'dark:border-white', 'bg-gray-50', 'dark:bg-gray-700');
            });
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, () => {
                dropZone.classList.remove('border-black', 'dark:border-white', 'bg-gray-50', 'dark:bg-gray-700');
            });
        });

        dropZone.addEventListener('drop', (e) => {
            const dt = e.dataTransfer;
            const files = dt.files;

            // Create a new FileList-like object
            const dataTransfer = new DataTransfer();
            Array.from(files).forEach(file => dataTransfer.items.add(file));
            fileInput.files = dataTransfer.files;

            displayFiles(fileInput.files);
        });

        fileInput.addEventListener('change', (e) => {
            displayFiles(e.target.files);
        });

        function displayFiles(files) {
            if (files.length === 0) {
                uploadPrompt.classList.remove('hidden');
                fileList.classList.add('hidden');
                submitBtn.disabled = true;
                return;
            }

            uploadPrompt.classList.add('hidden');
            fileList.classList.remove('hidden');
            fileList.innerHTML = '';
            submitBtn.disabled = false;

            Array.from(files).forEach((file, index) => {
                const fileItem = document.createElement('div');
                fileItem.className = 'flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg border border-gray-200 dark:border-gray-600';

                const fileSize = (file.size / 1024 / 1024).toFixed(2);
                const fileType = file.name.split('.').pop().toUpperCase();

                fileItem.innerHTML = `
                    <div class="flex items-center gap-3 flex-1">
                        <div class="flex-shrink-0 w-10 h-10 bg-black dark:bg-white rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">${file.name}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">${fileType} • ${fileSize} MB</p>
                        </div>
                    </div>
                    <button type="button" onclick="removeFile(${index})" class="flex-shrink-0 text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;

                fileList.appendChild(fileItem);
            });
        }

        window.removeFile = function(index) {
            const dt = new DataTransfer();
            const files = Array.from(fileInput.files);

            files.forEach((file, i) => {
                if (i !== index) dt.items.add(file);
            });

            fileInput.files = dt.files;
            displayFiles(fileInput.files);

            if (fileInput.files.length === 0) {
                uploadPrompt.classList.remove('hidden');
                fileList.classList.add('hidden');
                submitBtn.disabled = true;
            }
        };

        // Simulate progress on submit
        form.addEventListener('submit', (e) => {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Processing...';
            progressDiv.classList.remove('hidden');

            let progress = 0;
            const interval = setInterval(() => {
                progress += 10;
                if (progress <= 90) {
                    progressBar.style.width = progress + '%';
                    progressText.textContent = progress + '%';
                } else {
                    clearInterval(interval);
                }
            }, 500);
        });
    </script>
    @endpush
</x-app-layout>
