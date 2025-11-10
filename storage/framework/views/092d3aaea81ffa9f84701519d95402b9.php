<?php if (isset($component)) { $__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.client-app-layout','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('client-app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
<div class="min-h-screen bg-gray-50 dark:bg-gray-900" x-data="{
    taskFiles: [],
    comments: [],
    newComment: '',
    taskReminders: [],
    isDragging: false,
    showMentionDropdown: false,
    mentionEmployees: [],
    mentionSearch: '',

    init() {
        this.loadFiles();
        this.loadComments();
        this.loadReminders();
    },

    loadFiles() {
        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch(`/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/files`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.taskFiles = data.files || [];
        })
        .catch(error => console.error('Error loading files:', error));
    },

    loadComments() {
        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch(`/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/comments`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.comments = data.comments || [];
        })
        .catch(error => console.error('Error loading comments:', error));
    },

    loadReminders() {
        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch(`/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/reminders`, {
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.taskReminders = data.reminders || [];
        })
        .catch(error => console.error('Error loading reminders:', error));
    },

    addComment() {
        if (!this.newComment.trim()) return;

        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch(`/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ comment: this.newComment })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.loadComments();
                this.newComment = '';
                this.showMentionDropdown = false;
            }
        })
        .catch(error => console.error('Error adding comment:', error));
    },

    addReply(comment) {
        if (!comment.replyText || !comment.replyText.trim()) return;

        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        const url = '/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/comments/' + comment.id + '/replies';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ reply: comment.replyText })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.loadComments();
            }
        })
        .catch(error => console.error('Error adding reply:', error));
    },

    toggleReaction(commentId, reactionType) {
        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        const url = '/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/comments/' + commentId + '/reactions';

        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ reaction_type: reactionType })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.loadComments();
            }
        })
        .catch(error => console.error('Error toggling reaction:', error));
    },

    handleCommentInput(event) {
        const text = event.target.value;
        const cursorPos = event.target.selectionStart;
        const textBeforeCursor = text.substring(0, cursorPos);
        const lastAtIndex = textBeforeCursor.lastIndexOf('@');

        if (lastAtIndex !== -1 && lastAtIndex === cursorPos - 1) {
            this.showMentionDropdown = true;
            this.searchMentionEmployees('');
        } else if (lastAtIndex !== -1) {
            const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);
            if (!textAfterAt.includes(' ')) {
                this.mentionSearch = textAfterAt;
                this.showMentionDropdown = true;
                this.searchMentionEmployees(textAfterAt);
            } else {
                this.showMentionDropdown = false;
            }
        } else {
            this.showMentionDropdown = false;
        }
    },

    handleReplyInput(event, comment) {
        const text = event.target.value;
        const cursorPos = event.target.selectionStart;
        const textBeforeCursor = text.substring(0, cursorPos);
        const lastAtIndex = textBeforeCursor.lastIndexOf('@');

        if (lastAtIndex !== -1) {
            const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);
            if (!textAfterAt.includes(' ')) {
                comment.showReplyMentionDropdown = true;
                const mentionSearch = textAfterAt;

                const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                fetch('/client/projects/<?php echo e($project->id); ?>/employees/mention?search=' + mentionSearch, {
                    headers: { 'X-CSRF-TOKEN': csrfToken }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        comment.replyMentionEmployees = data.employees;
                    }
                })
                .catch(error => console.error('Error fetching employees:', error));
            } else {
                comment.showReplyMentionDropdown = false;
            }
        } else {
            comment.showReplyMentionDropdown = false;
        }
    },

    searchMentionEmployees(search) {
        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch('/client/projects/<?php echo e($project->id); ?>/employees/mention?search=' + search, {
            headers: { 'X-CSRF-TOKEN': csrfToken }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.mentionEmployees = data.employees;
            }
        })
        .catch(error => console.error('Error fetching employees:', error));
    },

    selectMention(employee, event) {
        const textarea = event.target.closest('.relative').querySelector('textarea');
        const cursorPos = textarea.selectionStart;
        const text = textarea.value;
        const lastAtIndex = text.lastIndexOf('@', cursorPos - 1);

        const beforeAt = text.substring(0, lastAtIndex);
        const afterCursor = text.substring(cursorPos);

        this.newComment = beforeAt + '@' + employee.name + ' ' + afterCursor;
        this.showMentionDropdown = false;

        setTimeout(() => textarea.focus(), 10);
    },

    selectReplyMention(employee, comment, event) {
        const input = document.querySelector('[data-reply-input=\"' + comment.id + '\"]');
        const cursorPos = input.selectionStart;
        const text = input.value;
        const lastAtIndex = text.lastIndexOf('@', cursorPos - 1);

        const beforeAt = text.substring(0, lastAtIndex);
        const afterCursor = text.substring(cursorPos);

        comment.replyText = beforeAt + '@' + employee.name + ' ' + afterCursor;
        comment.showReplyMentionDropdown = false;

        setTimeout(() => input.focus(), 10);
    },

    getReactionEmoji(type) {
        const emojis = {
            'like': '👍',
            'love': '❤️',
            'laugh': '😂',
            'wow': '😮',
            'sad': '😢',
            'angry': '😡'
        };
        return emojis[type] || '👍';
    },

    getReactionCount(comment, type) {
        const reaction = comment.reactions.find(r => r.type === type);
        return reaction ? reaction.count : 0;
    },

    hasUserReacted(comment, type) {
        // For clients, we would need to track this differently
        // For now, return false as we don't have user identification in reactions
        return false;
    },

    handleFileDrop(event) {
        event.preventDefault();
        this.isDragging = false;

        const files = event.dataTransfer.files;
        if (files.length > 0) {
            this.uploadFile(files[0]);
        }
    },

    handleFileSelect(event) {
        const files = event.target.files;
        if (files.length > 0) {
            this.uploadFile(files[0]);
        }
    },

    uploadFile(file) {
        const formData = new FormData();
        formData.append('file', file);

        const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
        fetch(`/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/files`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.loadFiles();
            }
        })
        .catch(error => console.error('Error uploading file:', error));
    },

    formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1024 * 1024) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / (1024 * 1024)).toFixed(1) + ' MB';
    },

    getFileIcon(fileName) {
        const ext = fileName.split('.').pop().toLowerCase();
        const icons = {
            'pdf': '📄',
            'doc': '📝', 'docx': '📝',
            'xls': '📊', 'xlsx': '📊',
            'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️', 'gif': '🖼️',
            'zip': '📦', 'rar': '📦',
            'txt': '📃'
        };
        return icons[ext] || '📎';
    },

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }
}">
    <!-- Back Button -->
    <div class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 px-3 sm:px-6 lg:px-8 py-3 sm:py-4">
        <a href="<?php echo e(route('client.projects.show', $project)); ?>?tabs=tasks" class="inline-flex items-center text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
            <svg class="w-4 h-4 sm:w-5 sm:h-5 mr-1.5 sm:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Back to Project
        </a>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6 p-3 sm:p-4 lg:p-8">
        <!-- Left Column - Main Content (2/3) -->
        <div class="lg:col-span-2 space-y-4 sm:space-y-6">
            <!-- Task Header Card -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="bg-black dark:bg-white p-4 sm:p-6">
                    <h1 class="text-lg sm:text-xl lg:text-2xl font-bold text-white dark:text-black mb-2 sm:mb-3 break-words"><?php echo e($task->title); ?></h1>
                    <div class="flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 bg-white/20 dark:bg-black/20 text-white dark:text-black text-xs font-medium rounded-md">
                            <?php echo e(ucfirst(str_replace('-', ' ', $task->status))); ?>

                        </span>
                        <?php if($task->priority): ?>
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-1 bg-white/20 dark:bg-black/20 text-white dark:text-black text-xs font-medium rounded-md">
                                <?php echo e(ucfirst($task->priority)); ?>

                            </span>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Task Meta Info -->
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
                        <?php if($task->assignee): ?>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black dark:bg-white flex items-center justify-center text-white dark:text-black font-bold text-sm sm:text-base flex-shrink-0">
                                <?php echo e(strtoupper(substr($task->assignee->name, 0, 1))); ?>

                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Assigned To</div>
                                <div class="font-medium text-sm sm:text-base text-black dark:text-white truncate"><?php echo e($task->assignee->name); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($task->due_date): ?>
                        <div class="flex items-center gap-2 sm:gap-3">
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black dark:bg-white flex items-center justify-center text-white dark:text-black flex-shrink-0">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="text-xs text-gray-500 dark:text-gray-400">Due Date</div>
                                <div class="font-medium text-sm sm:text-base text-black dark:text-white"><?php echo e(\Carbon\Carbon::parse($task->due_date)->format('M d, Y')); ?></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if($task->description): ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                        </svg>
                        <h3 class="text-sm sm:text-base font-semibold text-black dark:text-white">Description</h3>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    <div class="prose prose-sm sm:prose dark:prose-invert max-w-none text-black dark:text-white leading-relaxed break-words overflow-hidden">
                        <?php echo $task->description; ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Checklist -->
            <?php if($task->checklists->count() > 0): ?>
            <div x-data="{
                checklists: <?php echo e($task->checklists->map(function($item) {
                    return [
                        'id' => $item->id,
                        'title' => $item->title,
                        'is_completed' => $item->is_completed
                    ];
                })->toJson()); ?>,
                get completedCount() {
                    return this.checklists.filter(item => item.is_completed).length;
                },
                get totalCount() {
                    return this.checklists.length;
                },
                get percentage() {
                    return this.totalCount > 0 ? (this.completedCount / this.totalCount) * 100 : 0;
                },
                async toggleChecklist(checklistId) {
                    const checklist = this.checklists.find(c => c.id === checklistId);
                    if (!checklist) return;

                    // Optimistic update
                    checklist.is_completed = !checklist.is_completed;

                    try {
                        const response = await fetch(`/client/tasks/<?php echo e($task->id); ?>/checklist/${checklistId}/toggle`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
                            }
                        });

                        if (!response.ok) {
                            // Revert on failure
                            checklist.is_completed = !checklist.is_completed;
                            alert('Failed to update checklist');
                        }
                    } catch (error) {
                        // Revert on error
                        checklist.is_completed = !checklist.is_completed;
                        console.error('Error:', error);
                    }
                }
            }" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-black dark:text-white">Checklist</h3>
                        </div>
                        <div class="flex items-center gap-1.5 sm:gap-2">
                            <span class="text-xs sm:text-sm font-semibold text-black dark:text-white" x-text="`${completedCount}/${totalCount}`"></span>
                            <span class="text-xs text-gray-500 dark:text-gray-400">completed</span>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-3">
                        <div class="h-2 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                            <div class="h-full bg-black dark:bg-white transition-all duration-300" :style="`width: ${percentage}%`"></div>
                        </div>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4 sm:py-5">
                    <div class="space-y-2 sm:space-y-3">
                        <template x-for="item in checklists" :key="item.id">
                            <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-black dark:hover:border-white transition-all group cursor-pointer"
                                 @click="toggleChecklist(item.id)">
                                <div class="relative flex items-center justify-center flex-shrink-0 mt-0.5">
                                    <div x-show="item.is_completed"
                                         class="w-5 h-5 sm:w-6 sm:h-6 bg-black dark:bg-white rounded-md flex items-center justify-center shadow-sm"
                                         x-transition>
                                        <svg class="w-3 h-3 sm:w-4 sm:h-4 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </div>
                                    <div x-show="!item.is_completed"
                                         class="w-5 h-5 sm:w-6 sm:h-6 border-2 border-gray-300 dark:border-gray-600 rounded-md bg-gray-50 dark:bg-gray-900 group-hover:border-black dark:group-hover:border-white transition-colors"
                                         x-transition></div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs sm:text-sm leading-relaxed break-words"
                                       :class="item.is_completed ? 'line-through text-gray-500 dark:text-gray-400' : 'text-gray-900 dark:text-gray-100 font-medium'"
                                       x-text="item.title"></p>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- File Attachments -->
            <div x-data="{
                taskFiles: [],
                isDragging: false,
                isUploading: false,
                showDeleteFileModal: false,
                deletingFileId: null,
                showFilePreview: false,
                previewFile: null,

                init() {
                    this.loadFiles();
                },

                async loadFiles() {
                    try {
                        const response = await fetch('/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/files');
                        const data = await response.json();
                        if (data.success) {
                            this.taskFiles = data.files;
                        }
                    } catch (error) {
                        console.error('Error loading files:', error);
                    }
                },

                async uploadFile(file) {
                    if (!file) return;

                    const maxSize = 100 * 1024 * 1024;
                    if (file.size > maxSize) {
                        const sizeMB = (file.size / 1024 / 1024).toFixed(2);
                        alert('File size exceeds 100MB limit. Your file is ' + sizeMB + 'MB.');
                        return;
                    }

                    this.isUploading = true;
                    const formData = new FormData();
                    formData.append('file', file);

                    try {
                        const response = await fetch('/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/files', {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            await this.loadFiles();
                            alert('File uploaded successfully!');
                        } else {
                            alert('Upload failed: ' + data.message);
                        }
                    } catch (error) {
                        console.error('Upload error:', error);
                        alert('Error uploading file');
                    } finally {
                        this.isUploading = false;
                    }
                },

                handleFileSelect(event) {
                    const file = event.target.files[0];
                    if (file) this.uploadFile(file);
                    event.target.value = '';
                },

                handleFileDrop(event) {
                    this.isDragging = false;
                    const file = event.dataTransfer.files[0];
                    if (file) this.uploadFile(file);
                },

                deleteFile(fileId) {
                    this.deletingFileId = fileId;
                    this.showDeleteFileModal = true;
                },

                async confirmDeleteFile() {
                    if (!this.deletingFileId) return;

                    try {
                        const deleteUrl = '/client/projects/<?php echo e($project->id); ?>/tasks/<?php echo e($task->id); ?>/files/' + this.deletingFileId;
                        const response = await fetch(deleteUrl, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                'Accept': 'application/json'
                            }
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.taskFiles = this.taskFiles.filter(f => f.id !== this.deletingFileId);
                            this.cancelDeleteFile();
                            alert('File deleted successfully');
                        } else {
                            alert('Failed to delete file');
                        }
                    } catch (error) {
                        console.error('Delete error:', error);
                        alert('Error deleting file');
                    }
                },

                cancelDeleteFile() {
                    this.showDeleteFileModal = false;
                    this.deletingFileId = null;
                },

                openFilePreview(file) {
                    this.previewFile = file;
                    this.showFilePreview = true;
                },

                getFileIcon(fileName) {
                    const ext = fileName.split('.').pop().toLowerCase();
                    const icons = {
                        'pdf': '📄',
                        'doc': '📝', 'docx': '📝',
                        'xls': '📊', 'xlsx': '📊',
                        'ppt': '📊', 'pptx': '📊',
                        'zip': '🗜️', 'rar': '🗜️',
                        'jpg': '🖼️', 'jpeg': '🖼️', 'png': '🖼️', 'gif': '🖼️', 'svg': '🖼️',
                        'mp4': '🎥', 'avi': '🎥', 'mov': '🎥',
                        'mp3': '🎵', 'wav': '🎵',
                        'txt': '📃', 'csv': '📃'
                    };
                    return icons[ext] || '📎';
                },

                formatFileSize(bytes) {
                    if (bytes === 0) return '0 Bytes';
                    const k = 1024;
                    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                    const i = Math.floor(Math.log(bytes) / Math.log(k));
                    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
                },

                isImage(fileName) {
                    const ext = fileName.split('.').pop().toLowerCase();
                    return ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp'].includes(ext);
                },

                isPreviewable(fileName) {
                    const ext = fileName.split('.').pop().toLowerCase();
                    // Images, PDFs, videos, text files
                    return ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp', 'bmp', 'pdf', 'mp4', 'webm', 'ogg', 'txt', 'json', 'xml', 'html', 'css', 'js'].includes(ext);
                },

                isPDF(fileName) {
                    return fileName.split('.').pop().toLowerCase() === 'pdf';
                },

                isVideo(fileName) {
                    const ext = fileName.split('.').pop().toLowerCase();
                    return ['mp4', 'webm', 'ogg', 'avi', 'mov'].includes(ext);
                },

                isText(fileName) {
                    const ext = fileName.split('.').pop().toLowerCase();
                    return ['txt', 'json', 'xml', 'html', 'css', 'js', 'md'].includes(ext);
                }
            }" class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between flex-wrap gap-2">
                        <div class="flex items-center gap-2 sm:gap-3">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            <h3 class="text-sm sm:text-base lg:text-lg font-semibold text-black dark:text-white">File Attachments</h3>
                        </div>
                        <span class="text-xs sm:text-sm font-semibold text-gray-600 dark:text-gray-400" x-text="`${taskFiles.length} files`"></span>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-4 sm:py-5">
                    <!-- Drag & Drop Upload Zone -->
                    <div @dragover.prevent="isDragging = true"
                         @dragleave.prevent="isDragging = false"
                         @drop.prevent="handleFileDrop($event)"
                         :class="{'border-black dark:border-white bg-gray-50 dark:bg-gray-700': isDragging}"
                         class="border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg p-4 sm:p-6 lg:p-8 text-center transition-all duration-200 hover:border-gray-400 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50 mb-3 sm:mb-4">
                        <input type="file" @change="handleFileSelect($event)" class="hidden" id="fileInput" multiple>
                        <label for="fileInput" class="cursor-pointer block">
                            <svg class="mx-auto h-8 w-8 sm:h-10 sm:w-10 lg:h-12 lg:w-12 mb-2 sm:mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100 mb-1">
                                <span class="text-black dark:text-white">Drag and drop</span> files here or <span class="text-black dark:text-white hover:underline">browse</span>
                            </p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Maximum file size: 100MB</p>
                        </label>
                        <div x-show="isUploading" class="mt-2 sm:mt-3">
                            <div class="inline-flex items-center gap-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                                <svg class="animate-spin h-3 w-3 sm:h-4 sm:w-4" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Uploading...
                            </div>
                        </div>
                    </div>

                    <!-- Files List -->
                    <div class="space-y-2 sm:space-y-3" x-show="taskFiles.length > 0">
                        <template x-for="file in taskFiles" :key="file.id">
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-black dark:hover:border-white transition-all group"
                                 x-data="{
                                     clickFile() {
                                         if(isPreviewable(file.original_name)) {
                                             openFilePreview(file);
                                         }
                                     }
                                 }">
                                <!-- Clickable Preview Area (Left Side) -->
                                <div @click="clickFile()"
                                     :class="isPreviewable(file.original_name) ? 'cursor-pointer' : ''"
                                     class="flex items-center gap-3 sm:gap-4 flex-1 min-w-0">
                                    <div class="flex-shrink-0">
                                        <span x-text="getFileIcon(file.original_name)" class="text-2xl sm:text-3xl"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="text-xs sm:text-sm font-semibold text-black dark:text-white truncate break-all" x-text="file.original_name"></div>
                                        <div class="text-xs text-gray-600 dark:text-gray-400 font-medium" x-text="file.size_formatted || formatFileSize(file.size)"></div>
                                        <!-- Preview Hint -->
                                        <div x-show="isPreviewable(file.original_name)" class="text-xs text-black dark:text-white font-medium mt-0.5 sm:mt-1">
                                            👁️ Click to preview
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons (Right Side) -->
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-shrink-0 w-full sm:w-auto mt-2 sm:mt-0">
                                    <!-- Preview Button -->
                                    <template x-if="isPreviewable(file.original_name)">
                                        <button @click.stop="openFilePreview(file)"
                                                class="px-3 sm:px-4 py-2 sm:py-2.5 rounded-md bg-black dark:bg-white text-white dark:text-black hover:opacity-80 transition-all flex items-center gap-1.5 sm:gap-2 font-medium text-xs sm:text-sm flex-1 sm:flex-initial justify-center"
                                                title="Preview">
                                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                            <span class="sm:inline">Preview</span>
                                        </button>
                                    </template>

                                    <!-- Download Button -->
                                    <a :href="file.url"
                                       download
                                       class="p-2 sm:p-2.5 rounded-md bg-black dark:bg-white text-white dark:text-black hover:opacity-80 transition-all flex items-center justify-center"
                                       title="Download"
                                       @click.stop>
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>

                                    <!-- Delete Button -->
                                    <button @click.stop="deleteFile(file.id)"
                                            class="p-2 sm:p-2.5 rounded-md bg-black dark:bg-white text-white dark:text-black hover:opacity-80 transition-all flex items-center justify-center"
                                            title="Delete">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Empty State -->
                    <div x-show="taskFiles.length === 0" class="text-center py-8 sm:py-12">
                        <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-xs sm:text-sm font-semibold text-gray-500 dark:text-gray-400">No files attached yet</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Upload files to share with your team</p>
                    </div>
                </div>

                <!-- Delete Confirmation Modal -->
                <div x-show="showDeleteFileModal"
                     x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto"
                     @click.self="cancelDeleteFile()">
                    <div class="flex items-center justify-center min-h-screen px-3 sm:px-4 py-4 sm:py-6">
                        <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>
                        <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md border-2 border-gray-200 dark:border-gray-700"
                             @click.stop
                             x-transition>
                            <div class="px-4 sm:px-6 pt-4 sm:pt-6">
                                <div class="flex items-center justify-center w-10 h-10 sm:w-12 sm:h-12 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full mb-3 sm:mb-4">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="px-4 sm:px-8 pb-6 sm:pb-8 text-center">
                                <h3 class="text-lg sm:text-2xl font-bold text-gray-900 dark:text-white mb-2 sm:mb-3">Delete File?</h3>
                                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">This action cannot be undone and the file will be permanently removed.</p>
                            </div>
                            <div class="flex items-center gap-2 sm:gap-3 p-4 sm:p-6 bg-gray-50 dark:bg-gray-900/50 rounded-b-2xl border-t border-gray-200 dark:border-gray-700">
                                <button @click="cancelDeleteFile()"
                                        class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 border-2 border-black dark:border-white rounded-md text-black dark:text-white text-sm sm:text-base font-bold hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                                    Cancel
                                </button>
                                <button @click="confirmDeleteFile()"
                                        class="flex-1 px-4 sm:px-6 py-2.5 sm:py-3 bg-black dark:bg-white text-white dark:text-black rounded-md text-sm sm:text-base font-bold hover:opacity-80 transition-all">
                                    Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- File Preview Modal -->
                <div x-show="showFilePreview"
                     x-cloak
                     class="fixed inset-0 z-50 overflow-y-auto"
                     @click.self="showFilePreview = false">
                    <div class="flex items-center justify-center min-h-screen px-2 sm:px-4 py-4 sm:py-6">
                        <div class="fixed inset-0 bg-black/90 backdrop-blur-sm transition-opacity"></div>
                        <div class="relative bg-white dark:bg-gray-800 rounded-xl sm:rounded-2xl shadow-2xl w-full max-w-4xl border-2 border-gray-200 dark:border-gray-700"
                             @click.stop
                             x-transition>
                            <div class="flex items-center justify-between px-3 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                                <h3 class="text-sm sm:text-lg font-bold text-black dark:text-white truncate pr-2" x-text="previewFile?.original_name"></h3>
                                <button @click="showFilePreview = false" class="p-1.5 sm:p-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors flex-shrink-0">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="p-3 sm:p-6 max-h-[60vh] sm:max-h-[70vh] overflow-auto">
                                <!-- Image Preview -->
                                <template x-if="previewFile && isImage(previewFile.original_name)">
                                    <img :src="previewFile.url"
                                         :alt="previewFile.original_name"
                                         class="max-w-full mx-auto rounded-lg">
                                </template>

                                <!-- PDF Preview -->
                                <template x-if="previewFile && isPDF(previewFile.original_name)">
                                    <iframe :src="previewFile.url"
                                            class="w-full h-[70vh] rounded-lg border border-gray-200 dark:border-gray-700"></iframe>
                                </template>

                                <!-- Video Preview -->
                                <template x-if="previewFile && isVideo(previewFile.original_name)">
                                    <video controls class="max-w-full mx-auto rounded-lg">
                                        <source :src="previewFile.url" :type="'video/' + previewFile.original_name.split('.').pop()">
                                        Your browser does not support video playback.
                                    </video>
                                </template>

                                <!-- Text File Preview -->
                                <template x-if="previewFile && isText(previewFile.original_name)">
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded-lg p-3 sm:p-4">
                                        <iframe :src="previewFile.url"
                                                class="w-full h-[50vh] sm:h-[60vh] rounded border border-gray-200 dark:border-gray-700"></iframe>
                                    </div>
                                </template>

                                <!-- Unsupported Preview -->
                                <template x-if="previewFile && !isPreviewable(previewFile.original_name)">
                                    <div class="text-center py-8 sm:py-12">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16 mx-auto mb-3 sm:mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mb-3 sm:mb-4">Preview not available for this file type</p>
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-500">Download the file to view it</p>
                                    </div>
                                </template>
                            </div>
                            <div class="flex items-center justify-center gap-2 sm:gap-3 p-4 sm:p-6 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                                <a :href="previewFile?.url"
                                   download
                                   class="inline-flex items-center gap-1.5 sm:gap-2 px-4 sm:px-6 py-2.5 sm:py-3 bg-black dark:bg-white text-white dark:text-black rounded-md text-sm sm:text-base font-bold hover:opacity-80 transition-all">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        <!-- Right Sidebar - Meta Info (1/3) -->
        <div class="space-y-4 sm:space-y-6">
            <!-- Tags -->
            <?php if($task->tags->count() > 0): ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        <h3 class="text-sm sm:text-base font-semibold text-black dark:text-white">Tags</h3>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                        <?php $__currentLoopData = $task->tags; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $tag): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <span class="inline-flex items-center px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg text-xs font-semibold bg-black dark:bg-white text-white dark:text-black border-2 border-black dark:border-white">
                            <?php echo e($tag->name); ?>

                        </span>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Reminders -->
            <?php if($task->reminders->count() > 0): ?>
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        <h3 class="text-sm sm:text-base font-semibold text-black dark:text-white">Reminders</h3>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    <div class="space-y-2 sm:space-y-3">
                        <template x-for="reminder in taskReminders" :key="reminder.id">
                            <div class="p-2.5 sm:p-3 rounded-lg bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600">
                                <div class="flex items-start gap-1.5 sm:gap-2 mb-1.5 sm:mb-2">
                                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span class="text-xs sm:text-sm font-medium text-black dark:text-white flex-1 break-words" x-text="reminder.reminder_text"></span>
                                </div>
                                <div class="text-xs font-medium text-gray-600 dark:text-gray-300 pl-5 sm:pl-7" x-text="formatDate(reminder.reminder_date)"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Activity -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <h3 class="text-sm sm:text-base font-semibold text-black dark:text-white">Activity</h3>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-black dark:bg-white mt-1.5 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-black dark:text-white">Created</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300"><?php echo e(\Carbon\Carbon::parse($task->created_at)->diffForHumans()); ?></p>
                            </div>
                        </div>
                        <?php if($task->updated_at != $task->created_at): ?>
                        <div class="flex items-start gap-2 sm:gap-3">
                            <div class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full bg-black dark:bg-white mt-1.5 flex-shrink-0"></div>
                            <div class="flex-1 min-w-0">
                                <p class="text-xs sm:text-sm font-medium text-black dark:text-white">Last Updated</p>
                                <p class="text-xs text-gray-600 dark:text-gray-300"><?php echo e(\Carbon\Carbon::parse($task->updated_at)->diffForHumans()); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

            <!-- Comments -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <h3 class="text-sm sm:text-base font-semibold text-black dark:text-white">Comments</h3>
                    </div>
                </div>
                <div class="px-4 sm:px-6 py-3 sm:py-4">
                    <!-- Add Comment -->
                    <div class="mb-3 sm:mb-4 relative">
                        <textarea x-model="newComment"
                                  @input="handleCommentInput($event)"
                                  rows="3"
                                  placeholder="Add a comment... (Use @ to mention team members)"
                                  class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-xs sm:text-sm border border-gray-300 dark:border-gray-600 rounded-lg focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent dark:bg-gray-700 dark:text-white placeholder-gray-400 dark:placeholder-gray-500"></textarea>

                        <!-- Mention Dropdown -->
                        <div x-show="showMentionDropdown && mentionEmployees.length > 0"
                             @click.away="showMentionDropdown = false"
                             class="absolute z-50 w-64 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                            <template x-for="employee in mentionEmployees" :key="employee.id">
                                <button @click="selectMention(employee, $event)"
                                        type="button"
                                        class="w-full px-3 sm:px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                    <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-black dark:bg-white flex items-center justify-center text-white dark:text-black text-xs font-bold">
                                        <span x-text="employee.name.split(' ').map(n => n[0]).join('')"></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate" x-text="employee.name"></p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="employee.email"></p>
                                    </div>
                                </button>
                            </template>
                        </div>

                        <button @click="addComment()" 
                                x-bind:disabled="!newComment.trim()"
                                class="mt-2 text-xs sm:text-sm inline-flex items-center gap-1.5 sm:gap-2 px-3 sm:px-4 py-2 sm:py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="w-3 h-3 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                            </svg>
                            Post Comment
                        </button>
                    </div>

                    <!-- Comments List -->
                    <div class="space-y-3 sm:space-y-4" x-show="comments.length > 0">
                        <template x-for="comment in comments" :key="comment.id">
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <!-- Main Comment -->
                                <div class="flex gap-2 sm:gap-3 p-3 sm:p-4">
                                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-black dark:bg-white flex items-center justify-center text-white dark:text-black font-bold text-xs sm:text-sm flex-shrink-0">
                                        <span x-text="(comment.employee.first_name.charAt(0) + comment.employee.last_name.charAt(0)).toUpperCase()"></span>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="font-semibold text-xs sm:text-sm text-black dark:text-white" x-text="comment.employee.first_name + ' ' + comment.employee.last_name"></span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="comment.created_at_human"></span>
                                            <span x-show="comment.is_client" class="text-xs px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">Client</span>
                                        </div>
                                        <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap mb-2 sm:mb-3 break-words" x-text="comment.comment"></p>

                                        <!-- Reactions Bar -->
                                        <div class="flex items-center gap-2 sm:gap-3 mb-2 flex-wrap">
                                            <!-- Reaction Buttons -->
                                            <div class="flex items-center gap-1 flex-wrap">
                                                <template x-for="reactionType in ['like', 'love', 'laugh', 'wow', 'sad', 'angry']" :key="reactionType">
                                                    <button @click="toggleReaction(comment.id, reactionType)"
                                                            :class="hasUserReacted(comment, reactionType) ? 'bg-blue-100 dark:bg-blue-900/30 border-blue-300 dark:border-blue-700' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                            class="inline-flex items-center gap-0.5 sm:gap-1 px-1.5 sm:px-2 py-0.5 sm:py-1 border rounded-full text-xs transition-all"
                                                            :title="reactionType">
                                                        <span x-text="getReactionEmoji(reactionType)"></span>
                                                        <span x-show="getReactionCount(comment, reactionType) > 0"
                                                              x-text="getReactionCount(comment, reactionType)"
                                                              class="text-gray-700 dark:text-gray-300 font-medium text-xs"></span>
                                                    </button>
                                                </template>
                                            </div>

                                            <!-- Reply Button -->
                                            <button @click="comment.showReplyInput = !comment.showReplyInput"
                                                    class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">
                                                <span x-text="comment.showReplyInput ? 'Cancel' : 'Reply'"></span>
                                            </button>
                                        </div>

                                        <!-- Reply Input -->
                                        <div x-show="comment.showReplyInput" class="mt-2 sm:mt-3 relative">
                                            <div class="flex gap-2">
                                                <div class="flex-1 relative">
                                                    <input type="text"
                                                           x-model="comment.replyText"
                                                           @input="handleReplyInput($event, comment)"
                                                           @keydown.enter="addReply(comment)"
                                                           :data-reply-input="comment.id"
                                                           placeholder="Write a reply... (Use @ to mention)"
                                                           class="w-full px-2 sm:px-3 py-1.5 sm:py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-xs sm:text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">

                                                    <!-- Mention Dropdown for Reply -->
                                                    <div x-show="comment.showReplyMentionDropdown && comment.replyMentionEmployees && comment.replyMentionEmployees.length > 0"
                                                         @click.away="comment.showReplyMentionDropdown = false"
                                                         class="absolute z-50 w-56 sm:w-64 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-40 sm:max-h-48 overflow-y-auto">
                                                        <template x-for="employee in comment.replyMentionEmployees" :key="employee.id">
                                                            <button @click.stop.prevent="selectReplyMention(employee, comment, $event)"
                                                                    type="button"
                                                                    class="w-full px-3 sm:px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                                <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-black dark:bg-white flex items-center justify-center text-white dark:text-black text-xs font-bold">
                                                                    <span x-text="employee.name.split(' ').map(n => n[0]).join('')"></span>
                                                                </div>
                                                                <div class="min-w-0 flex-1">
                                                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-white truncate" x-text="employee.name"></p>
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="employee.email"></p>
                                                                </div>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                                <button @click="addReply(comment)"
                                                        class="px-2 sm:px-3 py-1.5 sm:py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:opacity-80 transition-all text-xs sm:text-sm font-medium">
                                                    Send
                                                </button>
                                            </div>
                                        </div>

                                        <!-- Replies -->
                                        <div x-show="comment.replies && comment.replies.length > 0" class="mt-3 sm:mt-4 space-y-2 sm:space-y-3 pl-2 sm:pl-4 border-l-2 border-gray-200 dark:border-gray-700">
                                            <template x-for="reply in comment.replies" :key="reply.id">
                                                <div class="flex gap-2">
                                                    <div class="w-6 h-6 sm:w-8 sm:h-8 rounded-full bg-gray-800 dark:bg-gray-600 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                        <span x-text="(reply.employee.first_name.charAt(0) + reply.employee.last_name.charAt(0)).toUpperCase()"></span>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center gap-1.5 sm:gap-2 mb-0.5 sm:mb-1">
                                                            <span class="font-semibold text-xs text-gray-900 dark:text-white" x-text="reply.employee.first_name + ' ' + reply.employee.last_name"></span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="reply.created_at_human"></span>
                                                            <span x-show="reply.is_client" class="text-xs px-1 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded">Client</span>
                                                        </div>
                                                        <p class="text-xs sm:text-sm text-gray-700 dark:text-gray-300 break-words" x-text="reply.reply"></p>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                    <div x-show="comments.length === 0" class="text-center py-6 sm:py-8">
                        <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 sm:mb-3 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400">No comments yet. Be the first to comment!</p>
                    </div>
                </div>
            </div>
        </div>
</div>
<style>
    [x-cloak] { display: none !important; }
</style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc)): ?>
<?php $attributes = $__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc; ?>
<?php unset($__attributesOriginalcd49e3eb920c6c3f36f5bb30ae238bcc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc)): ?>
<?php $component = $__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc; ?>
<?php unset($__componentOriginalcd49e3eb920c6c3f36f5bb30ae238bcc); ?>
<?php endif; ?>
<?php /**PATH F:\Project\salary\resources\views/client/projects/tasks/show.blade.php ENDPATH**/ ?>