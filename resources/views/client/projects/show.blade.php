<x-client-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('client.projects.index') }}" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h2>
                        @if(isset($isTeamMember) && $isTeamMember)
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-full">
                                Team Member
                            </span>
                        @endif
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Created {{ $project->created_at->diffForHumans() }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                @if(!isset($isTeamMember) || !$isTeamMember)
                    @if($project->status === 'planning')
                    <a href="{{ route('client.projects.edit', $project) }}" class="inline-flex items-center gap-2 px-5 py-2.5 text-black dark:text-white font-medium rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="{{ route('client.projects.destroy', $project) }}" id="delete-project-form">
                        @csrf
                        @method('DELETE')
                        <button type="button"
                                @click="$dispatch('open-delete-modal', { id: 'deleteProjectModal', form: document.getElementById('delete-project-form') })"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white font-medium rounded-full hover:bg-red-700 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                    @endif
                @endif
            </div>
        </div>
    </x-slot>

    @php
    $filesData = $project->files->map(function($file) {
        $extension = pathinfo($file->name, PATHINFO_EXTENSION);
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'];
        return [
            'id' => $file->id,
            'name' => $file->name,
            'url' => \Illuminate\Support\Facades\Storage::url($file->file_path),
            'extension' => $extension,
            'is_image' => in_array(strtolower($extension), $imageExtensions),
            'size_formatted' => $file->file_size ? number_format($file->file_size / 1024 / 1024, 2) . ' MB' : 'Unknown',
            'category' => $file->category ?? 'other',
            'tags' => $file->tags ?? [],
            'uploaded_at' => $file->created_at->format('M d, Y'),
        ];
    });
    @endphp

    <div class="py-8">
        <div class="px-4 sm:px-6 lg:px-8">
            <!-- Team Member Notice Banner -->
            @if(isset($isTeamMember) && $isTeamMember)
            <div class="mb-6 bg-purple-50 dark:bg-purple-900/20 border border-purple-200 dark:border-purple-800 rounded-2xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-purple-800 dark:text-purple-200">
                        <p class="font-medium">Team Member - View Only Access</p>
                        <p class="mt-1">You have been assigned to this project as a team member. You can view all project details but cannot make changes.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Status Banner -->
            @if($project->status === 'planning')
            <div class="mb-6 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-2xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-200">
                        <p class="font-medium">Project Pending Approval</p>
                        <p class="mt-1">This project is awaiting admin review. The admin team will activate it once approved.</p>
                    </div>
                </div>
            </div>
            @elseif($project->status === 'completed' || $project->status === 'cancelled')
            <div class="mb-6 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <p class="font-medium">Project {{ ucfirst($project->status) }}</p>
                        <p class="mt-1">This project cannot be edited. Please contact admin for any changes.</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Tab Navigation -->
            <div class="mb-6" x-data="{
                activeTab: new URLSearchParams(window.location.search).get('tabs') || 'overview',
                changeTab(tab) {
                    this.activeTab = tab;
                    const url = new URL(window.location);
                    url.searchParams.set('tabs', tab);
                    window.history.pushState({}, '', url);
                },

                // Task Manager
                showTaskDetailModal: false,
                viewingTask: null,
                taskFiles: [],
                comments: [],
                newComment: '',
                taskReminders: [],
                showAddReminderForm: false,
                isDragging: false,

                viewTaskDetail(task) {
                    console.log('viewTaskDetail called', task);
                    this.viewingTask = task;
                    this.showTaskDetailModal = true;
                    this.loadFiles();
                    this.loadComments();
                    this.loadReminders();
                },

                loadFiles() {
                    if (!this.viewingTask || !this.viewingTask.id) return;

                    fetch(`/client/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/files`)
                        .then(response => response.json())
                        .then(data => {
                            this.taskFiles = data.files || [];
                        })
                        .catch(error => {
                            console.error('Error loading files:', error);
                        });
                },

                loadComments() {
                    if (!this.viewingTask || !this.viewingTask.id) return;

                    fetch(`/client/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/comments`)
                        .then(response => response.json())
                        .then(data => {
                            this.comments = data.comments || [];
                        })
                        .catch(error => {
                            console.error('Error loading comments:', error);
                        });
                },

                addComment() {
                    if (!this.viewingTask || !this.viewingTask.id || !this.newComment.trim()) return;

                    const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                    fetch(`/client/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/comments`, {
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
                            this.comments.unshift(data.comment);
                            this.newComment = '';
                        }
                    })
                    .catch(error => {
                        console.error('Error adding comment:', error);
                    });
                },

                loadReminders() {
                    if (!this.viewingTask || !this.viewingTask.id) return;

                    fetch(`/client/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/reminders`)
                        .then(response => response.json())
                        .then(data => {
                            this.taskReminders = data.reminders || [];
                        })
                        .catch(error => {
                            console.error('Error loading reminders:', error);
                        });
                },

                handleFileDrop(event) {
                    this.isDragging = false;
                    const files = Array.from(event.dataTransfer.files);
                    files.forEach(file => this.uploadFile(file));
                },

                handleFileSelect(event) {
                    const files = Array.from(event.target.files);
                    files.forEach(file => this.uploadFile(file));
                    event.target.value = '';
                },

                uploadFile(file) {
                    if (!this.viewingTask || !this.viewingTask.id) return;

                    const maxSize = 100 * 1024 * 1024;
                    if (file.size > maxSize) {
                        alert(`File size exceeds 100MB limit. Your file is ${(file.size / 1024 / 1024).toFixed(2)}MB.`);
                        return;
                    }

                    const formData = new FormData();
                    formData.append('file', file);

                    const csrfToken = document.querySelector('meta[name=csrf-token]').getAttribute('content');
                    fetch(`/client/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/files`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.taskFiles.push(data.file);
                        } else {
                            alert(data.message || 'Failed to upload file');
                        }
                    })
                    .catch(error => {
                        console.error('Error uploading file:', error);
                        alert('Error uploading file. Please try again.');
                    });
                }
            }">

        <!-- Mobile Dropdown -->
        <div class="sm:hidden">
            <select x-model="activeTab" @change="changeTab(activeTab)" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-black dark:focus:border-white focus:ring-black dark:focus:ring-white">
                <option value="overview">Overview</option>
                <option value="tasks">Tasks</option>
                <option value="files">Files</option>
                <option value="discussion">Discussion</option>
                @if(!isset($isTeamMember) || !$isTeamMember)
                <option value="finance">Finance</option>
                @endif
                <option value="tickets">Tickets</option>
            </select>
        </div>                <!-- Desktop Tabs -->
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                            <button @click="changeTab('overview')"
                                    :class="activeTab === 'overview' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Overview
                            </button>
                            <button @click="changeTab('tasks')"
                                    :class="activeTab === 'tasks' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Tasks
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">{{ $project->tasks_count }}</span>
                            </button>
                            <button @click="changeTab('files')"
                                    :class="activeTab === 'files' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Files
                            </button>
                            <button @click="changeTab('discussion')"
                                    :class="activeTab === 'discussion' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Discussion
                            </button>
                            @if(!isset($isTeamMember) || !$isTeamMember)
                            <button @click="changeTab('finance')"
                                    :class="activeTab === 'finance' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Finance
                            </button>
                            @endif
                            <button @click="changeTab('tickets')"
                                    :class="activeTab === 'tickets' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                Tickets
                            </button>
                        </nav>
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="mt-6">
                    <!-- Overview Tab -->
                    <div x-show="activeTab === 'overview'" x-cloak>
            <!-- Project Overview -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Main Content -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Details Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Project Details</h3>

                        @if($project->description)
                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Description</p>
                            <p class="text-gray-900 dark:text-white leading-relaxed">{{ $project->description }}</p>
                        </div>
                        @endif

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Start Date</p>
                                <p class="text-gray-900 dark:text-white">{{ $project->start_date ? $project->start_date->format('M d, Y') : 'Not set' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">End Date</p>
                                <p class="text-gray-900 dark:text-white">{{ $project->end_date ? $project->end_date->format('M d, Y') : 'Not set' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Progress</h3>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-black dark:bg-white transition-all" style="width: {{ $project->progress ?? 0 }}%"></div>
                                </div>
                            </div>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->progress ?? 0 }}%</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Project completion</p>
                    </div>

                    <!-- Tasks Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tasks</h3>
                        @if($project->tasks && $project->tasks->count() > 0)
                        <div class="space-y-3">
                            @foreach($project->tasks as $task)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                                <input type="checkbox" {{ $task->status === 'completed' ? 'checked' : '' }} disabled class="w-5 h-5 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white {{ $task->status === 'completed' ? 'line-through' : '' }}">{{ $task->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $task->assignee->name ?? 'Unassigned' }}</p>
                                </div>
                                <span class="text-sm px-2 py-1 rounded-full
                                    {{ $task->priority === 'high' ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : '' }}
                                    {{ $task->priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : '' }}
                                    {{ $task->priority === 'low' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : '' }}">
                                    {{ ucfirst($task->priority) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @else
                        <p class="text-gray-600 dark:text-gray-400 text-center py-8">No tasks yet</p>
                        @endif
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Status</h3>
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium
                            {{ $project->status === 'active' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : '' }}
                            {{ $project->status === 'planning' ? 'bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300' : '' }}
                            {{ $project->status === 'on_hold' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : '' }}
                            {{ $project->status === 'completed' ? 'bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300' : '' }}
                            {{ $project->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : '' }}">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>

                    <!-- Budget Card (Hidden from team members) -->
                    @if(!isset($isTeamMember) || !$isTeamMember)
                        @if($project->budget)
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Budget</h3>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $project->currency }} {{ number_format($project->budget, 2) }}</p>
                        </div>
                        @endif
                    @endif

                    <!-- Priority Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Priority</h3>
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium
                            {{ $project->priority === 1 ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : '' }}
                            {{ $project->priority === 2 ? 'bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-300' : '' }}
                            {{ $project->priority === 3 ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : '' }}
                            {{ $project->priority === 4 ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : '' }}
                            {{ $project->priority === 5 ? 'bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300' : '' }}">
                            Priority {{ $project->priority }}
                        </span>
                    </div>

                    <!-- Team Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm" x-data="{ showAddModal: false, showEditModal: false, editMember: null }">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Team</h3>
                            @if(!isset($isTeamMember) || !$isTeamMember)
                            <button @click="showAddModal = true"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-black dark:bg-white text-white dark:text-black font-medium rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Member
                            </button>
                            @endif
                        </div>

                        @if($project->projectManager)
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Project Manager (Admin)</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-black dark:bg-white rounded-full flex items-center justify-center">
                                    <span class="text-white dark:text-black font-bold">{{ substr($project->projectManager->name, 0, 1) }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $project->projectManager->name }}</p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ $project->projectManager->email }}</p>
                                </div>
                            </div>
                        </div>
                        @endif

                        @php
                            $internalMembers = $project->members->where('member_type', 'internal');
                            $clientMembers = $project->members->whereIn('member_type', ['client', 'client_team']);
                        @endphp

                        @if($internalMembers->count() > 0)
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Internal Team ({{ $internalMembers->count() }})</p>
                            <div class="space-y-2">
                                @foreach($internalMembers as $member)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                        <span class="text-blue-800 dark:text-blue-300 text-sm font-medium">
                                            {{ substr($member->employee->first_name ?? 'T', 0, 1) }}{{ substr($member->employee->last_name ?? 'M', 0, 1) }}
                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $member->employee->first_name }} {{ $member->employee->last_name }}
                                        </p>
                                        @if($member->role)
                                        <p class="text-xs text-gray-600 dark:text-gray-400">{{ $member->role }}</p>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if($clientMembers->count() > 0)
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Your Team ({{ $clientMembers->count() }})</p>
                            <div class="space-y-2">
                                @foreach($clientMembers as $member)
                                <div class="flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                                            <span class="text-purple-800 dark:text-purple-300 text-sm font-medium">
                                                {{ substr($member->client_member_name ?? 'C', 0, 1) }}
                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                {{ $member->client_member_name }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                {{ $member->client_member_email }}
                                            </p>
                                            @if($member->role)
                                            <p class="text-xs text-gray-500 dark:text-gray-500">{{ $member->role }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    @if(!isset($isTeamMember) || !$isTeamMember)
                                    <form action="{{ route('client.projects.members.remove', [$project, $member]) }}"
                                          method="POST"
                                          id="remove-member-form-{{ $member->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                                @click="$dispatch('open-delete-modal', { id: 'removeMemberModal', form: document.getElementById('remove-member-form-{{ $member->id }}') })"
                                                class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @else
                        <p class="text-sm text-gray-600 dark:text-gray-400">No team members added yet. Click "Add Member" to invite your team.</p>
                        @endif

                        <!-- Add Member Modal -->
                        <div x-show="showAddModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto" style="display: none;">
                            <div class="flex items-center justify-center min-h-screen px-4">
                                <div @click="showAddModal = false" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity"></div>
                                <div class="relative bg-white dark:bg-gray-800 rounded-2xl max-w-lg w-full p-6 shadow-xl">
                                    <div class="flex items-center justify-between mb-4">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Add Team Member</h3>
                                        <button @click="showAddModal = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>

                                    @php
                                        $availableTeamMembers = $client->teamMembers()
                                            ->where('status', 'active')
                                            ->whereNotIn('id', $project->members()
                                                ->where('member_type', 'client_team')
                                                ->pluck('client_team_member_id')
                                            )
                                            ->get();
                                    @endphp

                                    @if($availableTeamMembers->count() > 0)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select team members to add to this project:</p>

                                        <form action="{{ route('client.projects.members.add', $project) }}" method="POST">
                                            @csrf
                                            <div class="max-h-96 overflow-y-auto space-y-2 mb-6">
                                                @foreach($availableTeamMembers as $teamMember)
                                                    <label class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-900 cursor-pointer transition-colors">
                                                        <input type="checkbox" name="team_member_ids[]" value="{{ $teamMember->id }}"
                                                            class="w-5 h-5 text-black dark:text-white rounded border-gray-300 dark:border-gray-600 focus:ring-black dark:focus:ring-white">
                                                        <div class="flex items-center gap-3 flex-1">
                                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                                                {{ strtoupper(substr($teamMember->name, 0, 1)) }}
                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="font-medium text-gray-900 dark:text-white">{{ $teamMember->name }}</p>
                                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ $teamMember->email }}</p>
                                                                @if($teamMember->role)
                                                                    <p class="text-xs text-gray-500 dark:text-gray-500">{{ $teamMember->role }}</p>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </label>
                                                @endforeach
                                            </div>

                                            <div class="flex items-center gap-3">
                                                <button type="button" @click="showAddModal = false"
                                                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-black dark:bg-black text-white rounded-full hover:bg-gray-800 dark:hover:bg-gray-900 transition-all duration-200 font-medium">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                    </svg>
                                                    Add Selected
                                                </button>
                                            </div>
                                        </form>
                                    @else
                                        <div class="text-center py-8">
                                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-400 mb-4">No team members available to add.</p>
                                            <a href="{{ route('client.team.index') }}"
                                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-black dark:bg-black text-white rounded-full hover:bg-gray-800 dark:hover:bg-gray-900 transition-all duration-200 font-medium">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Invite Team Members
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                    </div>
                    <!-- End Overview Tab -->

                    <!-- Tasks Tab -->
                    <div x-show="activeTab === 'tasks'" x-cloak>
                        <div class="space-y-6">
                            <!-- Kanban Board -->
                            @if($project->tasks->count() > 0)
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
                                    @php
                                        $kanbanStatuses = [
                                            'todo' => ['label' => 'To Do', 'color' => 'gray'],
                                            'on-hold' => ['label' => 'On Hold', 'color' => 'yellow'],
                                            'in-progress' => ['label' => 'In Progress', 'color' => 'blue'],
                                            'awaiting-feedback' => ['label' => 'Awaiting Feedback', 'color' => 'orange'],
                                            'staging' => ['label' => 'Staging', 'color' => 'purple'],
                                            'live' => ['label' => 'Updated on Live', 'color' => 'indigo'],
                                            'completed' => ['label' => 'Completed', 'color' => 'green']
                                        ];
                                    @endphp

                                    @foreach($kanbanStatuses as $status => $config)
                                    <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                                        <!-- Column Header -->
                                        <div class="flex items-center justify-between mb-4">
                                            <h3 class="font-bold text-sm text-gray-900 dark:text-white">{{ $config['label'] }}</h3>
                                            <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400 bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 rounded-full">
                                                {{ $project->tasks->where('status', $status)->count() }}
                                            </span>
                                        </div>

                                        <!-- Task Cards in Column -->
                                        <div class="space-y-3 min-h-[200px]">
                                            @foreach($project->tasks->where('status', $status)->sortBy('order') as $task)
                                            <a href="{{ route('client.projects.tasks.show', [$project, $task]) }}" class="block bg-white dark:bg-gray-800 rounded-lg p-3 border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow duration-200">
                                                <!-- Task Title -->
                                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white mb-2 line-clamp-2">{!! strip_tags($task->title) !!}</h4>

                                                <!-- Description -->
                                                @if($task->description)
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{!! Str::limit(strip_tags($task->description), 100) !!}</p>
                                                @endif

                                                <!-- Tags -->
                                                @if($task->tags && $task->tags->count() > 0)
                                                <div class="flex flex-wrap gap-1.5 mb-3">
                                                    @foreach($task->tags->take(2) as $tag)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold shadow-sm transition-all hover:shadow-md"
                                                          style="background: linear-gradient(135deg, {{ $tag->color }}15 0%, {{ $tag->color }}25 100%);
                                                                 color: {{ $tag->color }};
                                                                 border: 1.5px solid {{ $tag->color }}50;
                                                                 box-shadow: 0 1px 2px {{ $tag->color }}15;">
                                                        {{ $tag->name }}
                                                    </span>
                                                    @endforeach
                                                    @if($task->tags->count() > 2)
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600 shadow-sm">
                                                        +{{ $task->tags->count() - 2 }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @endif

                                                <!-- Progress Bar -->
                                                @if($task->checklists && $task->checklists->count() > 0)
                                                @php
                                                    $totalItems = $task->checklists->count();
                                                    $completedItems = $task->checklists->where('is_completed', true)->count();
                                                    $progressPercent = $totalItems > 0 ? round(($completedItems / $totalItems) * 100) : 0;
                                                @endphp
                                                <div class="flex items-center gap-2 mb-3">
                                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                    <span class="text-xs text-gray-600 dark:text-gray-400">{{ $completedItems }}/{{ $totalItems }}</span>
                                                </div>
                                                @endif

                                                <!-- Footer with Priority, Assignee, and Due Date -->
                                                <div class="flex items-center justify-between pt-3 border-t border-gray-100 dark:border-gray-700">
                                                    <div class="flex items-center gap-2">
                                                        <!-- Priority Badge -->
                                                        @if($task->priority)
                                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium
                                                            @if($task->priority === 'critical') bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400
                                                            @elseif($task->priority === 'high') bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400
                                                            @elseif($task->priority === 'medium') bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400
                                                            @else bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300
                                                            @endif">
                                                            {{ ucfirst($task->priority) }}
                                                        </span>
                                                        @endif

                                                        <!-- Due Date -->
                                                        @if($task->due_date)
                                                        <div class="flex items-center gap-1 text-xs {{ \Carbon\Carbon::parse($task->due_date)->isPast() && $task->status !== 'completed' ? 'text-red-600 dark:text-red-400 font-semibold' : 'text-gray-500 dark:text-gray-400' }}">
                                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                            </svg>
                                                            <span>{{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                                                        </div>
                                                        @endif
                                                    </div>

                                                    <!-- Assigned To Avatar -->
                                                    @if($task->assigned_to)
                                                    <div class="w-6 h-6 bg-gradient-to-br from-indigo-500 to-purple-600 text-white rounded-full flex items-center justify-center text-xs font-semibold ring-2 ring-white dark:ring-gray-800"
                                                         title="{{ $task->assignee->first_name ?? '' }} {{ $task->assignee->last_name ?? '' }}">
                                                        {{ substr($task->assignee->first_name ?? '', 0, 1) }}{{ substr($task->assignee->last_name ?? '', 0, 1) }}
                                                    </div>
                                                    @endif
                                                </div>
                                            </a>
                                            @endforeach

                                            <!-- Empty State for Column -->
                                            @if($project->tasks->where('status', $status)->count() === 0)
                                            <div class="text-center py-12 px-4">
                                                <svg class="w-12 h-12 mx-auto text-gray-300 dark:text-gray-700 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                                </svg>
                                                <p class="text-xs text-gray-400 dark:text-gray-600 font-medium">No tasks</p>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <!-- Empty State -->
                                <div class="bg-white dark:bg-gray-800 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700 p-12 text-center">
                                    <svg class="w-20 h-20 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                    </svg>
                                    <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Tasks Yet</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-6">This project doesn't have any tasks assigned yet. Tasks will appear here once the admin creates them.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Files Tab -->
                    <div x-show="activeTab === 'files'" x-cloak>

                        <div x-data="{
                            files: {{ $filesData->toJson() }},
                            filteredFiles: {{ $filesData->toJson() }},
                            searchQuery: '',
                            selectedCategory: 'all',
                            selectedTag: 'all',
                            showUploadModal: false,
                            showPreviewModal: false,
                            showDeleteModal: false,
                            previewFile: null,
                            fileToDelete: null,
                            selectedFiles: [],
                            currentTag: '',
                            isDragging: false,
                            isUploading: false,
                            uploadForm: {
                                category: '',
                                tags: []
                            },

                            init() {
                                this.filterFiles();
                            },

                            get allTags() {
                                const tags = new Set();
                                this.files.forEach(file => {
                                    if (Array.isArray(file.tags)) {
                                        file.tags.forEach(tag => tags.add(tag));
                                    }
                                });
                                return Array.from(tags).sort();
                            },

                            filterFiles() {
                                this.filteredFiles = this.files.filter(file => {
                                    const matchesSearch = !this.searchQuery ||
                                        file.name.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                                        (file.tags && file.tags.some(tag => tag.toLowerCase().includes(this.searchQuery.toLowerCase())));

                                    const matchesCategory = this.selectedCategory === 'all' || file.category === this.selectedCategory;
                                    const matchesTag = this.selectedTag === 'all' || (file.tags && file.tags.includes(this.selectedTag));

                                    return matchesSearch && matchesCategory && matchesTag;
                                });
                            },

                            openPreview(file) {
                                this.previewFile = file;
                                this.showPreviewModal = true;
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

                                const maxSize = 52428800; // 50MB
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
                                if (this.uploadForm.tags.length > 0) {
                                    formData.append('tags', JSON.stringify(this.uploadForm.tags));
                                }
                                formData.append('_token', document.querySelector('meta[name=\"csrf-token\"]').content);

                                try {
                                    const response = await fetch('{{ route("client.projects.files.store", $project) }}', {
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

                            async deleteFile() {
                                if (!this.fileToDelete) return;

                                try {
                                    const response = await fetch('{{ route("client.projects.files.destroy", [$project, "FILE_ID"]) }}'.replace('FILE_ID', this.fileToDelete.id), {
                                        method: 'DELETE',
                                        headers: {
                                            'X-CSRF-TOKEN': document.querySelector('meta[name=\"csrf-token\"]').content,
                                            'Accept': 'application/json'
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
                                }
                            }
                        }" class="space-y-6">
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
                                                <option value="all">📁 All Categories</option>
                                                <option value="document">📄 Documents</option>
                                                <option value="design">🎨 Designs</option>
                                                <option value="code">💻 Code</option>
                                                <option value="image">🖼️ Images</option>
                                                <option value="other">📎 Other</option>
                                            </select>
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Tag Filter -->
                                        <div class="relative" x-show="allTags.length > 0">
                                            <select x-model="selectedTag" @change="filterFiles()" class="appearance-none pl-4 pr-10 py-3 bg-white dark:bg-gray-800 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all duration-200 shadow-sm hover:shadow-md cursor-pointer">
                                                <option value="all">🏷️ All Tags</option>
                                                <template x-for="tag in allTags" :key="tag">
                                                    <option :value="tag" x-text="'🏷️ ' + tag"></option>
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
                                        @click="showUploadModal = true; console.log('Upload button clicked, showUploadModal:', showUploadModal)"
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
                                                <a :href="file.url" download class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 transition-all text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                                    </svg>
                                                </a>
                                                <template x-if="file.is_image">
                                                    <button @click="openPreview(file)" class="inline-flex items-center gap-1.5 px-4 py-2 bg-white text-black rounded-full hover:bg-gray-200 transition-all text-sm font-medium">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                        </svg>
                                                    </button>
                                                </template>
                                                <button @click="fileToDelete = file; showDeleteModal = true" class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 text-white rounded-full hover:bg-red-700 transition-all text-sm font-medium">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>

                                        <!-- File Info -->
                                        <div class="p-4 border-t-2 border-gray-100 dark:border-gray-700">
                                            <h3 class="font-bold text-gray-900 dark:text-white truncate mb-1" :title="file.name" x-text="file.name"></h3>
                                            <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400">
                                                <span x-text="file.size_formatted"></span>
                                                <span x-text="file.uploaded_at"></span>
                                            </div>

                                            <!-- Tags -->
                                            <div x-show="file.tags && file.tags.length > 0" class="mt-2 flex flex-wrap gap-1">
                                                <template x-for="tag in file.tags" :key="tag">
                                                    <span class="inline-flex px-2 py-0.5 text-xs font-medium rounded-md bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300" x-text="tag"></span>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <!-- Empty State -->
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
                            <div x-show="showUploadModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto">
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
                                            <!-- Hidden File Input -->
                                            <input type="file" x-ref="fileInput" @change="handleFileSelect" multiple accept="*/*" class="hidden">

                                            <!-- Drop Zone -->
                                            <div
                                                @drop.prevent="handleDrop"
                                                @dragover.prevent="isDragging = true"
                                                @dragleave.prevent="isDragging = false"
                                                @click.prevent="$refs.fileInput.click()"
                                                class="relative group cursor-pointer">
                                                <div class="border-3 border-dashed rounded-2xl p-12 text-center transition-all duration-300" x-bind:class="isDragging ? 'border-black dark:border-white bg-gray-50 dark:bg-gray-700 scale-105' : 'border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500 hover:bg-gray-50 dark:hover:bg-gray-700/50'">
                                                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-br from-gray-900 to-black text-white rounded-2xl mb-4 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                                                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                                        </svg>
                                                    </div>
                                                    <p class="text-xl font-bold text-gray-900 dark:text-white mb-2">Drop files here or click to browse</p>
                                                    <p class="text-sm text-gray-500 dark:text-gray-400">Upload up to 10 files • Max 50MB each • All file types supported</p>
                                                </div>
                                            </div>

                                            <div x-show="selectedFiles.length > 0" class="space-y-3">
                                                <div class="flex items-center justify-between">
                                                    <h3 class="font-bold text-gray-900 dark:text-white">Selected Files (<span x-text="selectedFiles.length"></span>)</h3>
                                                    <button type="button" @click="selectedFiles = []" class="text-sm text-red-600 hover:text-red-700 font-medium">Clear All</button>
                                                </div>
                                                <div class="space-y-2 max-h-60 overflow-y-auto">
                                                    <template x-for="(file, index) in selectedFiles" :key="index">
                                                        <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                                            <div class="flex items-center gap-3 flex-1 min-w-0">
                                                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                                <div class="flex-1 min-w-0">
                                                                    <p class="text-sm font-medium text-gray-900 dark:text-white truncate" x-text="file.name"></p>
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="(file.size / 1024 / 1024).toFixed(2) + ' MB'"></p>
                                                                </div>
                                                            </div>
                                                            <button type="button" @click="selectedFiles.splice(index, 1)" class="p-1 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </div>
                                                    </template>
                                                </div>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Category *</label>
                                                <select x-model="uploadForm.category" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                                                    <option value="">Select category</option>
                                                    <option value="document">📄 Document</option>
                                                    <option value="design">🎨 Design</option>
                                                    <option value="code">💻 Code</option>
                                                    <option value="image">🖼️ Image</option>
                                                    <option value="other">📎 Other</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Tags</label>
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <template x-for="(tag, index) in uploadForm.tags" :key="index">
                                                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-sm font-medium bg-gradient-to-r from-gray-900 to-black text-white shadow-md">
                                                            <span x-text="tag"></span>
                                                            <button type="button" @click="uploadForm.tags.splice(index, 1)" class="hover:bg-white/20 rounded-full p-0.5 transition-colors">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        </span>
                                                    </template>
                                                </div>
                                                <div class="flex gap-2">
                                                    <input
                                                        type="text"
                                                        x-model="currentTag"
                                                        @keydown.enter.prevent="addTag"
                                                        @keydown.comma.prevent="addTag"
                                                        placeholder="Type a tag and press Enter"
                                                        class="flex-1 px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                                                    <button type="button" @click="addTag" class="px-6 py-3 bg-black text-white rounded-xl hover:bg-gray-800 transition-all font-medium">Add</button>
                                                </div>
                                            </div>

                                            <div class="flex items-center gap-3 pt-4">
                                                <button type="button" @click="showUploadModal = false" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                    <span>Cancel</span>
                                                </button>
                                                <button type="submit" x-bind:disabled="isUploading || selectedFiles.length === 0" class="flex-1 inline-flex items-center justify-center gap-2 px-8 py-3 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium">
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
                                    <div x-show="showPreviewModal" class="relative max-w-6xl w-full z-10">
                                        <button @click="showPreviewModal = false" class="absolute top-4 right-4 p-2 bg-white/10 text-white hover:bg-white/20 rounded-full transition-all">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                        <img x-show="previewFile" :src="previewFile?.url" :alt="previewFile?.name" class="w-full h-auto max-h-[90vh] object-contain rounded-2xl shadow-2xl">
                                        <div class="mt-4 text-center">
                                            <p class="text-white font-bold text-lg" x-text="previewFile?.name"></p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Confirmation Modal -->
                            <div x-show="showDeleteModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
                                <div class="flex items-center justify-center min-h-screen px-4">
                                    <div x-show="showDeleteModal" @click="showDeleteModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-md transition-opacity"></div>
                                    <div x-show="showDeleteModal" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-md w-full p-8 z-10 border-2 border-gray-200 dark:border-gray-700">
                                        <div class="text-center">
                                            <div class="inline-flex items-center justify-center w-16 h-16 bg-red-100 dark:bg-red-900/20 rounded-full mb-4">
                                                <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </div>
                                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Delete File</h3>
                                            <p class="text-gray-600 dark:text-gray-400 mb-6">
                                                Are you sure you want to delete "<span class="font-semibold" x-text="fileToDelete?.name"></span>"? This action cannot be undone.
                                            </p>
                                            <div class="flex items-center gap-3">
                                                <button @click="showDeleteModal = false" class="flex-1 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-all">
                                                    Cancel
                                                </button>
                                                <button @click="deleteFile" class="flex-1 px-6 py-3 bg-red-600 text-white rounded-full hover:bg-red-700 font-medium transition-all">
                                                    Delete
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Discussion Tab -->
                    <div x-show="activeTab === 'discussion'" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Discussion</h3>
                                <p class="text-gray-600 dark:text-gray-400">Discussion board feature coming soon</p>
                            </div>
                        </div>
                    </div>

                    <!-- Finance Tab -->
                    @if(!isset($isTeamMember) || !$isTeamMember)
                    <div x-show="activeTab === 'finance'" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Financial Overview</h3>
                            </div>

                            <!-- Budget Card -->
                            @if($project->budget)
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                                    <p class="text-sm text-green-600 dark:text-green-400 font-medium mb-2">Total Budget</p>
                                    <p class="text-3xl font-bold text-green-900 dark:text-green-100">{{ $project->currency }} {{ number_format($project->budget, 2) }}</p>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-2">Spent</p>
                                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">{{ $project->currency }} 0.00</p>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium mb-2">Remaining</p>
                                    <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">{{ $project->currency }} {{ number_format($project->budget, 2) }}</p>
                                </div>
                            </div>
                            @endif

                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Financial Tracking</h3>
                                <p class="text-gray-600 dark:text-gray-400">Expense tracking and invoicing features coming soon</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Tickets Tab -->
                    <div x-show="activeTab === 'tickets'" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Support Tickets</h3>
                                <p class="text-gray-600 dark:text-gray-400">Ticket management feature coming soon</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

    <!-- Delete Confirmation Modals -->
    <x-delete-modal id="deleteProjectModal">
        <x-slot name="title">Delete Project</x-slot>
        <x-slot name="message">
            Are you sure you want to delete this project? This action cannot be undone and all project data will be permanently removed.
        </x-slot>
    </x-delete-modal>

    <x-delete-modal id="removeMemberModal">
        <x-slot name="title">Remove Team Member</x-slot>
        <x-slot name="message">
            Are you sure you want to remove this team member from the project? They will lose access to all project resources.
        </x-slot>
    </x-delete-modal>

    <!-- Task Detail Modal -->
    <div x-show="showTaskDetailModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showTaskDetailModal = false; viewingTask = null">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black/60 backdrop-blur-md transition-opacity"></div>

            <!-- Modal Container -->
            <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-2xl max-w-5xl w-full max-h-[95vh] overflow-hidden border border-gray-200 dark:border-gray-800" @click.stop x-show="viewingTask">
                <!-- Header with Gradient -->
                <div class="sticky top-0 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 px-8 py-6 z-20">
                    <div class="flex items-start justify-between">
                        <div class="flex-1 mr-4">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-bold bg-white/20 text-white backdrop-blur-sm">
                                    <span x-text="viewingTask?.status ? viewingTask.status.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : ''"></span>
                                </span>
                                <span x-show="viewingTask?.priority" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                                      :class="{
                                          'bg-red-500/20 text-white backdrop-blur-sm': viewingTask?.priority === 'critical',
                                          'bg-orange-500/20 text-white backdrop-blur-sm': viewingTask?.priority === 'high',
                                          'bg-blue-500/20 text-white backdrop-blur-sm': viewingTask?.priority === 'medium',
                                          'bg-gray-500/20 text-white backdrop-blur-sm': viewingTask?.priority === 'low'
                                      }">
                                    <span x-text="viewingTask?.priority ? viewingTask.priority.charAt(0).toUpperCase() + viewingTask.priority.slice(1) : ''"></span>
                                </span>
                            </div>
                            <h2 class="text-3xl font-extrabold text-white drop-shadow-lg" x-text="viewingTask?.title"></h2>
                        </div>
                        <button @click="showTaskDetailModal = false; viewingTask = null" class="text-white/80 hover:text-white hover:bg-white/10 rounded-full p-2 transition-all">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Scrollable Content -->
                <div class="overflow-y-auto max-h-[calc(95vh-120px)] bg-gray-50 dark:bg-gray-950">
                    <div class="p-8 space-y-6">
                        <!-- Quick Info Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Assigned To Card -->
                            <div x-show="viewingTask?.assignee" class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-lg border border-gray-200 dark:border-gray-800 hover:shadow-xl transition-all">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 rounded-full bg-indigo-100 dark:bg-indigo-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Assigned To</h3>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 flex items-center justify-center text-white font-bold text-xl shadow-lg" x-text="viewingTask?.assignee ? ((viewingTask.assignee.first_name || '').charAt(0) + (viewingTask.assignee.last_name || '').charAt(0)) : ''"></div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-bold text-gray-900 dark:text-white text-base truncate" x-text="viewingTask?.assignee ? ((viewingTask.assignee.first_name || '') + ' ' + (viewingTask.assignee.last_name || '')) : ''"></p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate" x-text="viewingTask?.assignee?.email || ''"></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Due Date Card -->
                            <div x-show="viewingTask?.due_date" class="bg-white dark:bg-gray-900 rounded-2xl p-5 shadow-lg border border-gray-200 dark:border-gray-800 hover:shadow-xl transition-all">
                                <div class="flex items-center gap-2 mb-4">
                                    <div class="w-8 h-8 rounded-full bg-pink-100 dark:bg-pink-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Due Date</h3>
                                </div>
                                <p class="font-bold text-gray-900 dark:text-white text-lg mb-1" x-text="viewingTask?.due_date ? new Date(viewingTask.due_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : ''"></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Due in ' + Math.ceil((new Date(viewingTask?.due_date) - new Date()) / (1000 * 60 * 60 * 24)) + ' days'"></p>
                            </div>
                        </div>

                        <!-- Description -->
                        <div x-show="viewingTask?.description" class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-full bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Description</h3>
                            </div>
                            <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed" x-html="viewingTask?.description || ''"></div>
                        </div>

                        <!-- Checklist -->
                        <div x-show="viewingTask?.checklists && viewingTask.checklists.length > 0" class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-green-100 dark:bg-green-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Checklist</h3>
                                </div>
                                <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-bold text-gray-700 dark:text-gray-300"
                                      x-text="viewingTask?.checklists ? (viewingTask.checklists.filter(c => c.is_completed).length + '/' + viewingTask.checklists.length + ' Done') : ''">
                                </span>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(item, index) in viewingTask?.checklists || []" :key="item.id">
                                    <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700/50 hover:border-gray-300 dark:hover:border-gray-600 transition-all">
                                        <div class="relative flex-shrink-0 w-6 h-6">
                                            <div class="w-6 h-6 rounded-lg border-2 transition-all flex items-center justify-center"
                                                 :class="item.is_completed
                                                     ? 'bg-green-500 border-green-500'
                                                     : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600'">
                                                <svg x-show="item.is_completed"
                                                     class="w-4 h-4 text-white"
                                                     fill="none"
                                                     stroke="currentColor"
                                                     viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <span class="flex-1 text-sm font-medium text-gray-900 dark:text-white"
                                              :class="{ 'line-through text-gray-400 dark:text-gray-500': item.is_completed }"
                                              x-text="item.title">
                                        </span>

                                        <div x-show="item.is_completed"
                                             class="inline-flex items-center gap-1 px-2.5 py-1 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-400 rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            Done
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>

                        <!-- File Attachments -->
                        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">File Attachments</h3>
                            </div>

                            <!-- Modern Drop Zone -->
                            <div
                                @drop.prevent="handleFileDrop($event)"
                                @dragover.prevent="isDragging = true"
                                @dragleave.prevent="isDragging = false"
                                :class="isDragging ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 scale-[1.02]' : 'border-gray-300 dark:border-gray-700'"
                                class="border-2 border-dashed rounded-2xl p-10 transition-all duration-300 mb-4 group hover:border-indigo-400 dark:hover:border-indigo-600">
                                <div class="text-center">
                                    <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                        </svg>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 font-medium mb-2">
                                        <span class="font-bold">Drag and drop</span> files here or
                                        <label class="text-indigo-600 dark:text-indigo-400 font-bold cursor-pointer hover:text-indigo-700 dark:hover:text-indigo-300 underline decoration-2 underline-offset-2">
                                            browse
                                            <input type="file" multiple class="hidden" @change="handleFileSelect($event)">
                                        </label>
                                    </p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500">Maximum file size: 100MB</p>
                                </div>
                            </div>

                            <!-- Files List -->
                            <div class="space-y-3" x-show="taskFiles.length > 0">
                                <template x-for="file in taskFiles" :key="file.id">
                                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700/50 hover:border-indigo-300 dark:hover:border-indigo-700 hover:shadow-md transition-all">
                                        <div class="flex items-center gap-4 flex-1 min-w-0">
                                            <!-- File Icon -->
                                            <div class="flex-shrink-0">
                                                <template x-if="['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(file.extension?.toLowerCase())">
                                                    <div class="w-12 h-12 rounded-xl bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </template>
                                                <template x-if="file.extension?.toLowerCase() === 'pdf'">
                                                    <div class="w-12 h-12 rounded-xl bg-red-100 dark:bg-red-900/30 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </template>
                                                <template x-if="!['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf'].includes(file.extension?.toLowerCase())">
                                                    <div class="w-12 h-12 rounded-xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                                        <svg class="w-6 h-6 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                                        </svg>
                                                    </div>
                                                </template>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-bold text-gray-900 dark:text-white truncate" x-text="file.original_name"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                    <span x-text="file.size_formatted"></span>
                                                    <span class="mx-1">•</span>
                                                <span x-text="file.uploaded_at"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <a :href="file.url" download target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 bg-black dark:bg-white text-white dark:text-black rounded-full text-xs font-bold hover:opacity-90 transition-all">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                        </svg>
                                        Download
                                    </a>
                                </div>
                            </template>
                        </div>
                        <div x-show="taskFiles.length === 0" class="text-center py-6 text-gray-400 dark:text-gray-600 text-sm">
                            No files attached yet
                        </div>
                    </div>

                        <!-- Reminders -->
                        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-full bg-yellow-100 dark:bg-yellow-900/30 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Reminders</h3>
                                </div>
                                <button
                                    @click="showAddReminderForm = true"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl text-xs font-bold hover:from-indigo-700 hover:to-purple-700 shadow-md hover:shadow-lg transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Reminder
                                </button>
                            </div>

                            <!-- Reminders List -->
                            <div x-show="taskReminders.length > 0" class="space-y-3">
                                <template x-for="reminder in taskReminders" :key="reminder.id">
                                    <div class="flex items-start gap-4 p-4 bg-gradient-to-br from-yellow-50 to-orange-50 dark:from-yellow-900/20 dark:to-orange-900/20 rounded-xl border border-yellow-200 dark:border-yellow-800">
                                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-yellow-200 dark:bg-yellow-800 flex items-center justify-center">
                                            <svg class="w-5 h-5 text-yellow-700 dark:text-yellow-300" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z"></path>
                                            </svg>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="new Date(reminder.remind_at).toLocaleString()"></span>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 font-medium mb-2" x-text="reminder.message"></p>
                                            <span class="inline-flex items-center px-2.5 py-1 bg-white/50 dark:bg-black/20 rounded-full text-xs font-bold text-gray-700 dark:text-gray-300">
                                                <span x-text="reminder.recipients_count + ' recipient(s)'"></span>
                                            </span>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="taskReminders.length === 0" class="text-center py-10">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold mb-1">No reminders set</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">Click "Add Reminder" to create one</p>
                            </div>
                        </div>

                        <!-- Tags -->
                        <div x-show="viewingTask?.tags && viewingTask.tags.length > 0" class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-8 h-8 rounded-full bg-cyan-100 dark:bg-cyan-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Tags</h3>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <template x-for="tag in viewingTask?.tags || []" :key="tag.id">
                                    <span class="inline-flex items-center px-4 py-2 rounded-xl text-xs font-bold shadow-md hover:shadow-lg transition-all"
                                          :style="`background: linear-gradient(135deg, ${tag.color}20 0%, ${tag.color}35 100%); color: ${tag.color}; border: 2px solid ${tag.color}60;`"
                                          x-text="tag.name">
                                    </span>
                                </template>
                            </div>
                        </div>

                        <!-- Comments -->
                        <div class="bg-white dark:bg-gray-900 rounded-2xl p-6 shadow-lg border border-gray-200 dark:border-gray-800">
                            <div class="flex items-center gap-2 mb-5">
                                <div class="w-8 h-8 rounded-full bg-teal-100 dark:bg-teal-900/30 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Comments</h3>
                                <span class="px-2.5 py-1 bg-gray-100 dark:bg-gray-800 rounded-full text-xs font-bold text-gray-700 dark:text-gray-300" x-text="comments.length"></span>
                            </div>

                            <!-- Comments List -->
                            <div class="space-y-4 mb-5 max-h-[400px] overflow-y-auto">
                                <template x-for="comment in comments" :key="comment.id">
                                    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl border border-gray-200 dark:border-gray-700/50 p-4 hover:border-gray-300 dark:hover:border-gray-600 transition-all">
                                        <div class="flex gap-3">
                                            <div class="flex-shrink-0">
                                                <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 via-purple-600 to-pink-600 flex items-center justify-center text-white text-sm font-bold shadow-md">
                                                    <span x-text="comment.employee.first_name.charAt(0) + comment.employee.last_name.charAt(0)"></span>
                                                </div>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center justify-between mb-2">
                                                    <p class="text-sm font-bold text-gray-900 dark:text-white">
                                                        <span x-text="comment.employee.first_name + ' ' + comment.employee.last_name"></span>
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 font-medium" x-text="comment.created_at_human || comment.created_at"></p>
                                                </div>
                                                <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed whitespace-pre-wrap" x-text="comment.comment"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>

                            <div x-show="comments.length === 0" class="text-center py-10 mb-5">
                                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-gray-100 dark:bg-gray-800 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 font-bold mb-1">No comments yet</p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">Be the first to comment!</p>
                            </div>

                            <!-- Add Comment -->
                            <div class="flex gap-3">
                                <input
                                    type="text"
                                    x-model="newComment"
                                    @keydown.enter="addComment()"
                                    placeholder="Write a comment..."
                                    class="flex-1 px-4 py-3 border-2 border-gray-300 dark:border-gray-700 rounded-xl bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-sm font-medium focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 focus:border-transparent transition-all placeholder:text-gray-400 dark:placeholder:text-gray-500"
                                >
                                <button
                                    @click="addComment()"
                                    :disabled="!newComment.trim()"
                                    class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl font-bold hover:from-indigo-700 hover:to-purple-700 shadow-md hover:shadow-lg transition-all disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:shadow-md">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Post
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
<!-- End of Tab Navigation x-data -->

<style>
    [x-cloak] { display: none !important; }
</style>
</x-client-app-layout>
