<x-app-layout>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 py-8">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="mb-8">
                <a href="{{ route('uat.show', $project) }}" 
                    class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition-colors mb-4 group">
                    <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    <span class="font-medium">Back to Project</span>
                </a>
                
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                            Edit Test Case
                        </h1>
                        <p class="text-gray-600 dark:text-gray-400 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $project->name }}
                        </p>
                    </div>
                    <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-blue-50 dark:bg-blue-900/20 rounded-full">
                        <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                        <span class="text-sm font-medium text-blue-700 dark:text-blue-300">Editing Mode</span>
                    </div>
                </div>
            </div>

            <!-- Main Form Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden border border-gray-200 dark:border-gray-700">
                <form action="{{ route('uat.test-cases.update', [$project, $testCase]) }}" method="POST"
                    x-data="{
                        descriptionEditor: null,
                        stepsEditor: null,
                        expectedResultEditor: null,
                        initEditors() {
                            setTimeout(() => {
                                console.log('Initializing Quill editors...');
                                
                                // Initialize Description Editor
                                if (this.$refs.descriptionEditor && !this.descriptionEditor) {
                                    this.descriptionEditor = new Quill(this.$refs.descriptionEditor, {
                                        theme: 'snow',
                                        placeholder: 'Enter a detailed description...',
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
                                    const descContent = `{!! addslashes($testCase->description ?? '<p><br></p>') !!}`;
                                    this.descriptionEditor.clipboard.dangerouslyPasteHTML(descContent);
                                    console.log('Description editor initialized');
                                }

                                // Initialize Steps Editor
                                if (this.$refs.stepsEditor && !this.stepsEditor) {
                                    this.stepsEditor = new Quill(this.$refs.stepsEditor, {
                                        theme: 'snow',
                                        placeholder: 'Enter testing steps...',
                                        modules: {
                                            toolbar: [
                                                ['bold', 'italic', 'underline'],
                                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                ['link', 'code-block'],
                                                ['clean']
                                            ]
                                        }
                                    });
                                    const stepsContent = `{!! addslashes($testCase->steps ?? '<p><br></p>') !!}`;
                                    this.stepsEditor.clipboard.dangerouslyPasteHTML(stepsContent);
                                    console.log('Steps editor initialized');
                                }

                                // Initialize Expected Result Editor
                                if (this.$refs.expectedResultEditor && !this.expectedResultEditor) {
                                    this.expectedResultEditor = new Quill(this.$refs.expectedResultEditor, {
                                        theme: 'snow',
                                        placeholder: 'Enter expected result...',
                                        modules: {
                                            toolbar: [
                                                ['bold', 'italic', 'underline'],
                                                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                ['link', 'code-block'],
                                                ['clean']
                                            ]
                                        }
                                    });
                                    const resultContent = `{!! addslashes($testCase->expected_result ?? '<p><br></p>') !!}`;
                                    this.expectedResultEditor.clipboard.dangerouslyPasteHTML(resultContent);
                                    console.log('Expected result editor initialized');
                                }
                            }, 100);
                        },
                        init() {
                            this.initEditors();
                        },
                        submitForm(event) {
                            // Update hidden fields with editor content
                            if (this.descriptionEditor) {
                                event.target.querySelector('[name=description]').value = this.descriptionEditor.root.innerHTML;
                            }
                            if (this.stepsEditor) {
                                event.target.querySelector('[name=steps]').value = this.stepsEditor.root.innerHTML;
                            }
                            if (this.expectedResultEditor) {
                                event.target.querySelector('[name=expected_result]').value = this.expectedResultEditor.root.innerHTML;
                            }
                        }
                    }"
                    x-init="init()"
                    @submit="submitForm($event)"
                    class="p-8">
                    @csrf
                    @method('PUT')

                    <!-- Form Header -->
                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 px-8 py-6 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-black dark:bg-white rounded-xl flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Test Case Details</h2>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Update the test case information below</p>
                            </div>
                        </div>
                    </div>

                    <div class="p-8 space-y-8">
                        <!-- Title & Priority -->
                        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                            <div class="lg:col-span-2">
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                    Test Case Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="title" value="{{ old('title', $testCase->title) }}" required 
                                    placeholder="e.g., User Login Validation"
                                    class="block w-full px-4 py-3.5 rounded-xl border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all text-base shadow-sm">
                                @error('title')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                            <div>
                                <label class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Priority Level <span class="text-red-500">*</span>
                                </label>
                                <select name="priority" required 
                                    class="block w-full px-4 py-3.5 rounded-xl border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all text-base shadow-sm">
                                    <option value="low" {{ old('priority', $testCase->priority) === 'low' ? 'selected' : '' }}>🟢 Low</option>
                                    <option value="medium" {{ old('priority', $testCase->priority) === 'medium' ? 'selected' : '' }}>🟡 Medium</option>
                                    <option value="high" {{ old('priority', $testCase->priority) === 'high' ? 'selected' : '' }}>🟠 High</option>
                                    <option value="critical" {{ old('priority', $testCase->priority) === 'critical' ? 'selected' : '' }}>🔴 Critical</option>
                                </select>
                                @error('priority')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Divider -->
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300 dark:border-gray-600"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-4 bg-white dark:bg-gray-800 text-gray-500 dark:text-gray-400 font-medium">Content Editors</span>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Description
                                <span class="ml-auto text-xs text-gray-500 dark:text-gray-400 font-normal">Brief overview of the test case</span>
                            </label>
                            <div class="relative rounded-xl overflow-hidden border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors shadow-sm">
                                <div class="bg-white dark:bg-gray-700">
                                    <div x-ref="descriptionEditor" style="min-height: 200px;"></div>
                                </div>
                            </div>
                            <input type="hidden" name="description">
                            @error('description')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Testing Steps -->
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                                Testing Steps
                                <span class="ml-auto text-xs text-gray-500 dark:text-gray-400 font-normal">Step-by-step instructions</span>
                            </label>
                            <div class="relative rounded-xl overflow-hidden border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors shadow-sm">
                                <div class="bg-white dark:bg-gray-700">
                                    <div x-ref="stepsEditor" style="min-height: 300px;"></div>
                                </div>
                            </div>
                            <input type="hidden" name="steps">
                            @error('steps')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Expected Result -->
                        <div class="space-y-3">
                            <label class="flex items-center gap-2 text-sm font-bold text-gray-700 dark:text-gray-300">
                                <svg class="w-5 h-5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Expected Result
                                <span class="ml-auto text-xs text-gray-500 dark:text-gray-400 font-normal">What should happen</span>
                            </label>
                            <div class="relative rounded-xl overflow-hidden border-2 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-colors shadow-sm">
                                <div class="bg-white dark:bg-gray-700">
                                    <div x-ref="expectedResultEditor" style="min-height: 200px;"></div>
                                </div>
                            </div>
                            <input type="hidden" name="expected_result">
                            @error('expected_result')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-center gap-4 pt-8 mt-8 border-t-2 border-gray-200 dark:border-gray-700">
                            <a href="{{ route('uat.show', $project) }}" 
                                class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-8 py-4 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-xl font-bold hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-all shadow-md hover:shadow-lg transform hover:scale-105 group">
                                <svg class="w-5 h-5 transform group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                </svg>
                                Cancel Changes
                            </a>
                            <button type="submit" 
                                class="w-full sm:flex-1 inline-flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-black to-gray-800 dark:from-white dark:to-gray-100 text-white dark:text-black rounded-xl font-bold hover:from-gray-800 hover:to-gray-700 dark:hover:from-gray-100 dark:hover:to-white transition-all shadow-xl hover:shadow-2xl transform hover:scale-105 group">
                                <svg class="w-6 h-6 transform group-hover:rotate-12 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <span class="text-lg">Save Test Case</span>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Help Section -->
            <div class="mt-6 bg-blue-50 dark:bg-blue-900/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-blue-900 dark:text-blue-200 mb-2">💡 Tips for Writing Test Cases</h3>
                        <ul class="text-sm text-blue-800 dark:text-blue-300 space-y-1">
                            <li>• Be specific and clear in your test steps</li>
                            <li>• Use the rich text editor to format your content with lists, bold, and code blocks</li>
                            <li>• Set appropriate priority levels based on feature criticality</li>
                            <li>• Include expected results that are measurable and verifiable</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
