<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $test->test_title }} - Submit Your Test</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .fade-in {
            animation: fadeIn 0.6s ease-out;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Header -->
        <div class="text-center mb-8 fade-in">
            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-2xl shadow-lg mb-4">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $test->test_title }}</h1>
            <p class="text-gray-600">Test Assignment for {{ $test->application->jobPost->title }}</p>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-green-700 font-medium">{{ session('success') }}</p>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if(session('error'))
            <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg fade-in">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-red-700 font-medium">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <!-- Candidate Info Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 fade-in border border-gray-200">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-pink-600 flex items-center justify-center text-white font-bold text-xl">
                    {{ substr($test->application->first_name, 0, 1) }}{{ substr($test->application->last_name, 0, 1) }}
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">{{ $test->application->full_name }}</h3>
                    <p class="text-gray-600 text-sm">{{ $test->application->email }}</p>
                </div>
            </div>

            <!-- Deadline Warning -->
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-lg">
                <div class="flex items-start">
                    <svg class="w-6 h-6 text-yellow-500 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <p class="text-yellow-800 font-semibold">Submission Deadline</p>
                        <p class="text-yellow-700 text-sm mt-1">{{ $test->deadline->format('F j, Y \a\t g:i A') }}</p>
                        <p class="text-yellow-600 text-xs mt-1">{{ $test->deadline->diffForHumans() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Details Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 fade-in border border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 mb-4 flex items-center">
                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Test Details
            </h2>

            @if($test->test_description)
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Description</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $test->test_description }}</p>
                </div>
            @endif

            @if($test->test_instructions)
                <div class="mb-4">
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">Instructions</h3>
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <p class="text-blue-900 text-sm leading-relaxed whitespace-pre-wrap">{{ $test->test_instructions }}</p>
                    </div>
                </div>
            @endif

            @if($test->test_file_path)
                <div class="mt-4">
                    <a href="{{ route('test.submission.download', $test->submission_token) }}"
                       class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-full font-semibold transition-all shadow-lg hover:shadow-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download Test Materials
                    </a>
                </div>
            @endif
        </div>

        <!-- Submission Form -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-6 fade-in border border-gray-200" x-data="fileUpload()">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                </svg>
                Submit Your Work
            </h2>

            <form action="{{ route('test.submission.submit', $test->submission_token) }}" method="POST" enctype="multipart/form-data" class="space-y-6" @submit="handleSubmit">
                @csrf

                <!-- Drag & Drop File Upload -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Upload Your Completed Test <span class="text-red-500">*</span>
                    </label>

                    <!-- Drop Zone -->
                    <div @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleDrop($event)"
                         :class="isDragging ? 'border-blue-500 bg-blue-50' : 'border-gray-300 bg-gray-50'"
                         class="relative border-2 border-dashed rounded-xl p-8 text-center transition-all duration-200 cursor-pointer hover:border-blue-400 hover:bg-blue-50/50"
                         @click="$refs.fileInput.click()">

                        <!-- Upload Icon -->
                        <div x-show="!selectedFile" class="space-y-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-full">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-700 mb-1">
                                    Drag & drop your file here
                                </p>
                                <p class="text-sm text-gray-500">or click to browse</p>
                            </div>
                            <div class="flex items-center justify-center gap-2 text-xs text-gray-500">
                                <span class="px-2 py-1 bg-white rounded border border-gray-300">ZIP</span>
                                <span class="px-2 py-1 bg-white rounded border border-gray-300">PDF</span>
                            </div>
                        </div>

                        <!-- Selected File Preview -->
                        <div x-show="selectedFile" class="space-y-4">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-lg font-semibold text-gray-900" x-text="selectedFile?.name"></p>
                                <p class="text-sm text-gray-500" x-text="formatFileSize(selectedFile?.size)"></p>
                            </div>
                            <button type="button" @click.stop="clearFile()"
                                    class="inline-flex items-center gap-1 px-3 py-1 text-xs font-medium text-red-600 hover:text-red-700 hover:bg-red-50 rounded-full transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Remove
                            </button>
                        </div>

                        <!-- Hidden File Input -->
                        <input type="file"
                               name="submission_file"
                               x-ref="fileInput"
                               @change="handleFileSelect($event)"
                               accept=".zip,.pdf"
                               class="hidden"
                               required>
                    </div>

                    <!-- File Requirements -->
                    <div class="mt-3 flex items-start gap-2 text-xs text-gray-600 bg-blue-50 border border-blue-200 rounded-lg p-3">
                        <svg class="w-4 h-4 text-blue-500 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="font-semibold mb-1">File Requirements:</p>
                            <ul class="list-disc list-inside space-y-0.5">
                                <li>Only ZIP and PDF files are allowed</li>
                                <li>Maximum file size: 50MB</li>
                                <li>Files are stored securely and cannot be executed</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Error Message -->
                    <div x-show="errorMessage" class="mt-3 bg-red-50 border border-red-200 rounded-lg p-3 flex items-center gap-2">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-sm text-red-700" x-text="errorMessage"></p>
                    </div>

                    @error('submission_file')
                        <p class="mt-2 text-sm text-red-600 flex items-center gap-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <!-- Notes -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Additional Notes (Optional)
                    </label>
                    <textarea name="submission_notes" rows="4"
                              class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 p-3 transition-colors"
                              placeholder="Add any comments, explanations, or notes about your submission...">{{ old('submission_notes') }}</textarea>
                    @error('submission_notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Upload Progress -->
                <div x-show="uploading" class="space-y-2">
                    <div class="flex items-center justify-between text-sm">
                        <span class="font-medium text-gray-700">Uploading...</span>
                        <span class="text-gray-600" x-text="uploadProgress + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 h-full transition-all duration-300"
                             :style="'width: ' + uploadProgress + '%'"></div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        :disabled="uploading || !selectedFile"
                        :class="(uploading || !selectedFile) ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-800'"
                        class="w-full inline-flex items-center justify-center gap-2 px-6 py-4 bg-black text-white rounded-full text-base font-semibold transition-all shadow-lg hover:shadow-xl disabled:hover:bg-black">
                    <svg x-show="!uploading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <svg x-show="uploading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span x-text="uploading ? 'Submitting...' : 'Submit Test'"></span>
                </button>
            </form>
        </div>

        <!-- Footer -->
        <div class="text-center text-gray-600 text-sm fade-in">
            <p>© {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            <p class="mt-2">If you have any questions, please contact our recruitment team.</p>
        </div>
    </div>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function fileUpload() {
            return {
                selectedFile: null,
                isDragging: false,
                errorMessage: '',
                uploading: false,
                uploadProgress: 0,
                allowedTypes: ['application/zip', 'application/x-zip-compressed', 'application/pdf'],
                allowedExtensions: ['.zip', '.pdf'],
                maxSize: 50 * 1024 * 1024, // 50MB

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    this.validateAndSetFile(file);
                },

                handleDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];

                    if (file) {
                        // Update the hidden input
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        this.$refs.fileInput.files = dataTransfer.files;

                        this.validateAndSetFile(file);
                    }
                },

                validateAndSetFile(file) {
                    this.errorMessage = '';

                    if (!file) {
                        return;
                    }

                    // Check file type
                    const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
                    if (!this.allowedExtensions.includes(fileExtension) && !this.allowedTypes.includes(file.type)) {
                        this.errorMessage = 'Invalid file type. Only ZIP and PDF files are allowed.';
                        this.$refs.fileInput.value = '';
                        this.selectedFile = null;
                        return;
                    }

                    // Check file size
                    if (file.size > this.maxSize) {
                        this.errorMessage = 'File size exceeds 50MB limit. Please compress or reduce your file size.';
                        this.$refs.fileInput.value = '';
                        this.selectedFile = null;
                        return;
                    }

                    // File is valid
                    this.selectedFile = file;
                    this.errorMessage = '';
                },

                clearFile() {
                    this.selectedFile = null;
                    this.errorMessage = '';
                    this.$refs.fileInput.value = '';
                },

                formatFileSize(bytes) {
                    if (!bytes) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                },

                handleSubmit(event) {
                    if (!this.selectedFile) {
                        event.preventDefault();
                        this.errorMessage = 'Please select a file to upload.';
                        return;
                    }

                    this.uploading = true;
                    this.uploadProgress = 0;

                    // Simulate upload progress
                    const interval = setInterval(() => {
                        if (this.uploadProgress < 90) {
                            this.uploadProgress += Math.random() * 15;
                        }
                    }, 200);

                    // Form will submit naturally, clear interval when done
                    setTimeout(() => {
                        clearInterval(interval);
                        this.uploadProgress = 100;
                    }, 2000);
                }
            }
        }
    </script>
</body>
</html>
