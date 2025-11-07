<div x-data="{
    showUploadModal: false,
    selectedCategory: 'all',
    uploadForm: {
        name: '',
        category: '',
        assigned_to: ''
    },
    resetForm() {
        this.uploadForm = {
            name: '',
            category: '',
            assigned_to: ''
        };
    }
}" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            <select x-model="selectedCategory" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                <option value="all">All Files</option>
                <option value="document">Documents</option>
                <option value="design">Designs</option>
                <option value="code">Code</option>
                <option value="image">Images</option>
                <option value="other">Other</option>
            </select>
            <span class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($project->files->count()); ?> files</span>
        </div>

        <button @click="showUploadModal = true" class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
            </svg>
            Upload File
        </button>
    </div>

    <!-- Files Grid -->
    <?php if($project->files->count() > 0): ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            <?php $__currentLoopData = $project->files; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $file): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div x-show="selectedCategory === 'all' || selectedCategory === '<?php echo e($file->category); ?>'" class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-4 hover:shadow-lg transition-shadow">
                    <!-- File Icon/Preview -->
                    <div class="aspect-square rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-900 flex items-center justify-center mb-4 overflow-hidden">
                        <?php
                            $extension = pathinfo($file->name, PATHINFO_EXTENSION);
                            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
                        ?>

                        <?php if(in_array(strtolower($extension), $imageExtensions)): ?>
                            <img src="<?php echo e(Storage::url($file->file_path)); ?>" alt="<?php echo e($file->name); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="text-center">
                                <?php if(in_array($extension, ['pdf'])): ?>
                                    <svg class="w-16 h-16 mx-auto text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                <?php elseif(in_array($extension, ['doc', 'docx'])): ?>
                                    <svg class="w-16 h-16 mx-auto text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                <?php elseif(in_array($extension, ['xls', 'xlsx'])): ?>
                                    <svg class="w-16 h-16 mx-auto text-green-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                <?php elseif(in_array($extension, ['zip', 'rar', '7z'])): ?>
                                    <svg class="w-16 h-16 mx-auto text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                <?php else: ?>
                                    <svg class="w-16 h-16 mx-auto text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8l-6-6z"/>
                                        <path d="M14 2v6h6"/>
                                    </svg>
                                <?php endif; ?>
                                <p class="text-sm font-bold text-gray-600 dark:text-gray-400 uppercase mt-2"><?php echo e($extension); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- File Info -->
                    <div class="space-y-2">
                        <h4 class="font-semibold text-sm text-gray-900 dark:text-white truncate" title="<?php echo e($file->name); ?>"><?php echo e($file->name); ?></h4>

                        <div class="flex items-center justify-between text-xs">
                            <span class="inline-flex items-center px-2 py-1 rounded-full font-semibold
                                <?php if($file->category === 'document'): ?> bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                <?php elseif($file->category === 'design'): ?> bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                <?php elseif($file->category === 'code'): ?> bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                <?php elseif($file->category === 'image'): ?> bg-pink-100 text-pink-800 dark:bg-pink-900/30 dark:text-pink-400
                                <?php else: ?> bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 <?php endif; ?>">
                                <?php echo e(ucfirst($file->category)); ?>

                            </span>
                            <?php if($file->size): ?>
                                <span class="text-gray-600 dark:text-gray-400"><?php echo e(number_format($file->size / 1024, 2)); ?> KB</span>
                            <?php endif; ?>
                        </div>

                        <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            <span><?php echo e($file->uploader->first_name ?? 'Unknown'); ?></span>
                        </div>

                        <?php if($file->assignee): ?>
                            <div class="flex items-center gap-2 text-xs text-gray-600 dark:text-gray-400">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                                <span>Assigned to <?php echo e($file->assignee->first_name); ?> <?php echo e($file->assignee->last_name); ?></span>
                            </div>
                        <?php endif; ?>

                        <p class="text-xs text-gray-500 dark:text-gray-400"><?php echo e($file->created_at->diffForHumans()); ?></p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <a href="<?php echo e(Storage::url($file->file_path)); ?>" download class="flex-1 inline-flex items-center justify-center gap-2 px-3 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-lg text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                        <form action="<?php echo e(route('projects.files.destroy', [$project, $file])); ?>" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-16 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No files uploaded yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Upload your first file to get started</p>
            <button @click="showUploadModal = true" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Upload File
            </button>
        </div>
    <?php endif; ?>

    <!-- Upload Modal -->
    <div x-show="showUploadModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showUploadModal = false; resetForm()">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full p-6 border border-gray-200 dark:border-gray-700" @click.stop>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Upload File</h2>
                    <button @click="showUploadModal = false; resetForm()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="<?php echo e(route('projects.files.store', $project)); ?>" method="POST" enctype="multipart/form-data" class="space-y-4">
                    <?php echo csrf_field(); ?>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">File</label>
                        <input type="file" name="file" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white dark:file:bg-white dark:file:text-black hover:file:opacity-90">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">File Name (Optional)</label>
                        <input type="text" name="name" x-model="uploadForm.name" placeholder="Leave blank to use original filename" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Category</label>
                            <select name="category" x-model="uploadForm.category" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="">Select Category</option>
                                <option value="document">Document</option>
                                <option value="design">Design</option>
                                <option value="code">Code</option>
                                <option value="image">Image</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assign To (Optional)</label>
                            <select name="assigned_to" x-model="uploadForm.assigned_to" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="">Unassigned</option>
                                <?php $__currentLoopData = $project->members->where('member_type', 'internal'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($member->employee_id); ?>"><?php echo e($member->employee->first_name); ?> <?php echo e($member->employee->last_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showUploadModal = false; resetForm()" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                            Upload File
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
</style>
<?php /**PATH F:\Project\salary\resources\views/projects/tabs/files.blade.php ENDPATH**/ ?>