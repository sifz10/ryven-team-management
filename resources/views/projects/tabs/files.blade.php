@php
use Illuminate\Support\Facades\Storage;

$filesData = $project->files->map(function($file) {
    $extension = pathinfo($file->name, PATHINFO_EXTENSION);
    $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
    return [
        'id' => $file->id,
        'name' => $file->name,
        'url' => Storage::url($file->file_path),
        'extension' => $extension,
        'is_image' => in_array(strtolower($extension), $imageExtensions),
        'size_formatted' => $file->file_size ? number_format($file->file_size / 1024 / 1024, 2) . ' MB' : 'Unknown',
        'category' => $file->category ?? 'other',
        'tags' => $file->tags ?? [],
        'uploader_name' => $file->uploader ? $file->uploader->first_name . ' ' . $file->uploader->last_name : 'Unknown',
        'uploaded_at' => $file->created_at->format('M d, Y'),
    ];
});
@endphp

<div x-data="fileManager()" class="space-y-6">
    <!-- Modern Header -->
    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-6 border border-gray-200 dark:border-gray-700">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-4">
            <!-- Left: Search & Filters -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 flex-1 w-full lg:w-auto">
                <!-- Modern Search Bar -->
                <div class="relative flex-1 w-full sm:max-w-md group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400 group-focus-within:text-black dark:group-focus-within:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input
                        type="text"
                        x-model="searchQuery"
                        @input.debounce.300ms="filterFiles()"
                        placeholder="Search files by name or tag..."
                        class="w-full pl-11 pr-4 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:border-black dark:focus:border-white focus:ring-0 transition-all duration-200 shadow-sm hover:shadow-md">
                </div>

                <!-- Category Filter -->
                <div class="relative">
                    <select x-model="selectedCategory" @change="filterFiles()" class="appearance-none pl-4 pr-10 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer">
                        <option value="all"> All Categories</option>
                        <option value="document"> Documents</option>
                        <option value="design"> Designs</option>
                        <option value="code"> Code</option>
                        <option value="image"> Images</option>
                        <option value="other"> Other</option>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>

                <!-- Tag Filter -->
                <div class="relative">
                    <select x-model="selectedTag" @change="filterFiles()" class="appearance-none pl-4 pr-10 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer">
                        <option value="all"> All Tags</option>
                        <template x-for="tag in allTags" :key="tag">
                            <option :value="tag" x-text="' ' + tag"></option>
                        </template>
                    </select>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Right: Upload Button -->
            <button
                @click="showUploadModal = true"
                class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-all duration-200 font-medium">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                <span>Upload Files</span>
            </button>
        </div>

        <!-- Files Count Badge -->
        <div class="mt-4 inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full text-sm">
            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
            <span class="font-semibold text-gray-900 dark:text-white" x-text="filteredFiles.length"></span>
            <span class="text-gray-600 dark:text-gray-400" x-text="filteredFiles.length === 1 ? 'file' : 'files'"></span>
        </div>
    </div>

    <!-- Modern Files Grid -->
    <div x-show="filteredFiles.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        <template x-for="file in filteredFiles" :key="file.id">
            <div class="group relative bg-white dark:bg-gray-800 rounded-2xl border-2 border-gray-100 dark:border-gray-700 overflow-hidden hover:border-black dark:hover:border-white hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
                <!-- File Preview -->
                <div class="relative aspect-square bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 flex items-center justify-center overflow-hidden">
                    <template x-if="file.is_image">
                        <div class="relative w-full h-full">
                            <img
                                :src="file.url"
                                :alt="file.name"
                                class="w-full h-full object-cover cursor-pointer group-hover:scale-110 transition-transform duration-500"
                                @click="openPreview(file)">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                        </div>
                    </template>
                    <template x-if="!file.is_image">
                        <div class="text-center p-6">
                            <div class="w-20 h-20 mx-auto mb-3 bg-gradient-to-br from-gray-200 to-gray-300 dark:from-gray-600 dark:to-gray-700 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-10 h-10 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <span class="inline-flex px-3 py-1 text-xs font-bold text-gray-700 dark:text-gray-300 bg-gray-200 dark:bg-gray-600 rounded-full uppercase tracking-wide" x-text="file.extension"></span>
                        </div>
                    </template>

                    <!-- Category Badge -->
                    <div class="absolute top-3 left-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold shadow-lg backdrop-blur-sm" x-bind:class="{
                            'bg-blue-500/90 text-white': file.category === 'document',
                            'bg-purple-500/90 text-white': file.category === 'design',
                            'bg-green-500/90 text-white': file.category === 'code',
                            'bg-pink-500/90 text-white': file.category === 'image',
                            'bg-gray-500/90 text-white': file.category === 'other'
                        }" x-text="file.category.charAt(0).toUpperCase() + file.category.slice(1)"></span>
                    </div>

                    <!-- Quick Actions Overlay -->
                    <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center gap-2">
                        <a :href="file.url" download class="inline-flex items-center gap-1.5 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-all text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            <span>Download</span>
                        </a>
                        <template x-if="file.is_image">
                            <button @click="openPreview(file)" class="inline-flex items-center gap-1.5 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>Preview</span>
                            </button>
                        </template>
                        <button @click="fileToDelete = file; showDeleteModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-all text-sm font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>

                <!-- File Info -->
                <div class="p-4 space-y-3">
                    <!-- File Name & Size -->
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white truncate group-hover:text-black dark:group-hover:text-white transition-colors" :title="file.name" x-text="file.name"></h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400 flex items-center gap-1 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                            <span x-text="file.size_formatted"></span>
                        </p>
                    </div>

                    <!-- Tags -->
                    <template x-if="file.tags && file.tags.length > 0">
                        <div class="flex flex-wrap gap-1.5">
                            <template x-for="tag in file.tags.slice(0, 3)" :key="tag">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600" x-text="tag"></span>
                            </template>
                            <template x-if="file.tags.length > 3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-gradient-to-r from-gray-900 to-black text-white" x-text="'+' + (file.tags.length - 3)"></span>
                            </template>
                        </div>
                    </template>

                    <!-- Uploader Info -->
                    <div class="flex items-center gap-2 pt-3 border-t border-gray-100 dark:border-gray-700 text-xs text-gray-500 dark:text-gray-400">
                        <div class="w-6 h-6 bg-gradient-to-br from-gray-900 to-black text-white rounded-full flex items-center justify-center text-xs font-bold">
                            <span x-text="file.uploader_name.charAt(0).toUpperCase()"></span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-700 dark:text-gray-300 truncate" x-text="file.uploader_name"></p>
                            <p x-text="file.uploaded_at"></p>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>

    <!-- Modern Empty State -->
    <div x-show="filteredFiles.length === 0" class="text-center py-16">
        <div class="inline-flex items-center justify-center w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-3xl mb-6 shadow-lg">
            <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No files found</h3>
        <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto">
            <template x-if="searchQuery || selectedCategory !== 'all' || selectedTag !== 'all'">
                <span>Try adjusting your filters or search query to find what you're looking for</span>
            </template>
            <template x-if="!searchQuery && selectedCategory === 'all' && selectedTag === 'all'">
                <span>Get started by uploading your first file to this project</span>
            </template>
        </p>
        <button
            @click="showUploadModal = true"
            class="inline-flex items-center gap-2 px-8 py-4 bg-black text-white rounded-full hover:bg-gray-800 transition-all font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            <span>Upload Your First File</span>
        </button>
    </div>

    <!-- Upload Modal -->
    <div x-show="showUploadModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showUploadModal" @click="showUploadModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-md transition-opacity"></div>
            <div x-show="showUploadModal" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-3xl w-full p-8 z-10 border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Upload Files</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Add files to your project</p>
                    </div>
                    <button @click="showUploadModal = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="submitFiles" class="space-y-6">
                    <div @drop.prevent="handleDrop" @dragover.prevent="isDragging = true" @dragleave.prevent="isDragging = false" @click="$refs.fileInput.click()" class="relative group cursor-pointer">
                        <div class="border-3 border-dashed rounded-2xl p-12 text-center transition-all duration-300" x-bind:class="isDragging ? 'border-black dark:border-white bg-gray-50 dark:bg-gray-700 scale-105' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                            <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-900 to-black text-white rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                            </div>
                            <p class="text-xl font-bold text-gray-900 dark:text-white mb-2">Drop files here or click to browse</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Upload up to 10 files • Max 50MB each • All file types supported</p>
                            <input type="file" x-ref="fileInput" @change="handleFileSelect" multiple class="hidden">
                        </div>
                    </div>

                    <div x-show="selectedFiles.length > 0" class="space-y-3">
                        <div class="flex items-center justify-between">
                            <h3 class="font-bold text-gray-900 dark:text-white">Selected Files (<span x-text="selectedFiles.length"></span>)</h3>
                            <button type="button" @click="selectedFiles = []" class="text-sm text-red-600 hover:text-red-700 font-medium">Clear All</button>
                        </div>
                        <div class="space-y-2 max-h-60 overflow-y-auto">
                            <template x-for="(file, index) in selectedFiles" :key="index">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-xl border border-gray-200 dark:border-gray-600 group hover:border-black dark:hover:border-white transition-all">
                                    <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-gray-900 to-black text-white rounded-lg flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate" x-text="file.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                    </div>
                                    <button type="button" @click="selectedFiles.splice(index, 1)" class="flex-shrink-0 p-2 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                            <select x-model="uploadForm.category" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                                <option value="">Select category</option>
                                <option value="document"> Document</option>
                                <option value="design"> Design</option>
                                <option value="code"> Code</option>
                                <option value="image"> Image</option>
                                <option value="other"> Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Assign To</label>
                            <select x-model="uploadForm.assigned_to" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                                <option value="">Not assigned</option>
                                @foreach($employees as $employee)
                                    <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                        <div class="flex flex-wrap gap-2 mb-3">
                            <template x-for="(tag, index) in uploadForm.tags" :key="index">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-medium bg-gradient-to-r from-gray-900 to-black text-white shadow-md">
                                    <span x-text="tag"></span>
                                    <button type="button" @click="uploadForm.tags.splice(index, 1)" class="hover:bg-white/20 rounded-full p-0.5 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </span>
                            </template>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" x-model="currentTag" @keydown.enter.prevent="addTag" @keydown.comma.prevent="addTag" placeholder="Type tag and press Enter" class="flex-1 px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white placeholder-gray-400 focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                            <button type="button" @click="addTag" class="px-6 py-3 bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 font-semibold transition-all hover:scale-105">Add</button>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Press Enter or comma to add a tag</p>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                        <button type="button" @click="showUploadModal = false" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <button type="submit" x-bind:disabled="isUploading || selectedFiles.length === 0" class="inline-flex items-center gap-2 px-8 py-3 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium">
                            <svg x-show="isUploading" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <svg x-show="!isUploading" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <span x-text="isUploading ? 'Uploading...' : 'Upload Files'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div x-show="showPreviewModal" x-cloak class="fixed inset-0 z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showPreviewModal" @click="showPreviewModal = false" class="fixed inset-0 bg-black/95 backdrop-blur-sm"></div>
            <div x-show="showPreviewModal" class="relative z-10 max-w-7xl w-full">
                <button @click="showPreviewModal = false" class="absolute -top-16 right-0 inline-flex items-center gap-2 px-4 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Close</span>
                </button>
                <img x-bind:src="previewFile?.url" x-bind:alt="previewFile?.name" class="w-full h-auto max-h-[90vh] object-contain rounded-2xl shadow-2xl">
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showDeleteModal" @click="showDeleteModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-md"></div>
            <div x-show="showDeleteModal" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-8 z-10 border-2 border-gray-200 dark:border-gray-700">
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center w-16 h-16 rounded-2xl bg-gradient-to-br from-red-500 to-red-600 mb-6 shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete File</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Are you sure you want to delete</p>
                    <p class="font-semibold text-gray-900 dark:text-white mb-6" x-text="'\"' + (fileToDelete?.name || '') + '\"'"></p>
                    <p class="text-xs text-red-600 dark:text-red-400 mb-8">This action cannot be undone</p>
                    <div class="flex items-center gap-3">
                        <button type="button" @click="showDeleteModal = false" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            <span>Cancel</span>
                        </button>
                        <button type="button" @click="confirmDelete" class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-red-600 text-white rounded-full hover:bg-red-700 transition-all font-medium">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function fileManager() {
    const filesJson = @json($filesData);

    return {
        showUploadModal: false,
        showPreviewModal: false,
        showDeleteModal: false,
        isDragging: false,
        isUploading: false,
        searchQuery: '',
        selectedCategory: 'all',
        selectedTag: 'all',
        selectedFiles: [],
        currentTag: '',
        uploadForm: {
            category: '',
            assigned_to: '',
            tags: []
        },
        previewFile: null,
        fileToDelete: null,
        allFiles: filesJson,
        filteredFiles: [],

        init() {
            this.filteredFiles = this.allFiles;
        },

        get allTags() {
            const tags = new Set();
            this.allFiles.forEach(file => {
                if (file.tags) {
                    file.tags.forEach(tag => tags.add(tag));
                }
            });
            return Array.from(tags).sort();
        },

        filterFiles() {
            this.filteredFiles = this.allFiles.filter(file => {
                const matchesSearch = !this.searchQuery ||
                    file.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    (file.tags && file.tags.some(tag => tag.toLowerCase().includes(this.searchQuery.toLowerCase())));

                const matchesCategory = this.selectedCategory === 'all' || file.category === this.selectedCategory;
                const matchesTag = this.selectedTag === 'all' || (file.tags && file.tags.includes(this.selectedTag));

                return matchesSearch && matchesCategory && matchesTag;
            });
        },

        handleDrop(e) {
            this.isDragging = false;
            const files = Array.from(e.dataTransfer.files);
            this.addFiles(files);
        },

        handleFileSelect(e) {
            const files = Array.from(e.target.files);
            this.addFiles(files);
        },

        addFiles(files) {
            const remaining = 10 - this.selectedFiles.length;
            if (remaining <= 0) {
                alert('You can only upload up to 10 files at once');
                return;
            }
            const newFiles = files.slice(0, remaining);
            this.selectedFiles.push(...newFiles);
            if (files.length > remaining) {
                alert(`Only ${remaining} files can be added. You have reached the limit of 10 files.`);
            }
        },

        addTag() {
            const tag = this.currentTag.trim().replace(/,$/, '');
            if (tag && !this.uploadForm.tags.includes(tag)) {
                this.uploadForm.tags.push(tag);
                this.currentTag = '';
            }
        },

        async submitFiles() {
            if (this.selectedFiles.length === 0) return;

            // Validate file sizes (50MB = 52428800 bytes)
            const maxSize = 52428800;
            const oversizedFiles = this.selectedFiles.filter(file => file.size > maxSize);
            if (oversizedFiles.length > 0) {
                alert(`The following files exceed 50MB limit:\n${oversizedFiles.map(f => `• ${f.name} (${(f.size / 1024 / 1024).toFixed(2)}MB)`).join('\n')}\n\nPlease remove them and try again.`);
                return;
            }

            this.isUploading = true;

            const formData = new FormData();
            this.selectedFiles.forEach((file) => {
                formData.append('files[]', file);
            });
            formData.append('category', this.uploadForm.category);
            if (this.uploadForm.assigned_to) {
                formData.append('assigned_to', this.uploadForm.assigned_to);
            }
            if (this.uploadForm.tags.length > 0) {
                formData.append('tags', JSON.stringify(this.uploadForm.tags));
            }
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

            try {
                const response = await fetch('{{ route("projects.files.store", $project) }}', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error uploading files: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Upload error:', error);
                alert('Error uploading files. Please try again.');
            } finally {
                this.isUploading = false;
            }
        },

        openPreview(file) {
            if (file.is_image) {
                this.previewFile = file;
                this.showPreviewModal = true;
            }
        },

        async confirmDelete() {
            if (!this.fileToDelete) return;

            try {
                const response = await fetch(`/projects/{{ $project->id }}/files/${this.fileToDelete.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                const data = await response.json();
                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error deleting file: ' + (data.message || 'Unknown error'));
                }
            } catch (error) {
                console.error('Delete error:', error);
                alert('Error deleting file. Please try again.');
            } finally {
                this.showDeleteModal = false;
                this.fileToDelete = null;
            }
        }
    };
}
</script>

<style>
[x-cloak] { display: none !important; }
</style>
