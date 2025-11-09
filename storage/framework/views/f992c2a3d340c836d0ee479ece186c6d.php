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
     <?php $__env->slot('header', null, []); ?> 
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="<?php echo e(route('client.projects.index')); ?>" class="inline-flex items-center text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <div>
                    <div class="flex items-center gap-3">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($project->name); ?></h2>
                        <?php if(isset($isTeamMember) && $isTeamMember): ?>
                            <span class="px-3 py-1 bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300 text-xs font-medium rounded-full">
                                Team Member
                            </span>
                        <?php endif; ?>
                    </div>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Created <?php echo e($project->created_at->diffForHumans()); ?></p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                    <?php if($project->status === 'planning'): ?>
                    <a href="<?php echo e(route('client.projects.edit', $project)); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 text-black dark:text-white font-medium rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </a>
                    <form method="POST" action="<?php echo e(route('client.projects.destroy', $project)); ?>" id="delete-project-form">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="button"
                                @click="$dispatch('open-delete-modal', { id: 'deleteProjectModal', form: document.getElementById('delete-project-form') })"
                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 text-white font-medium rounded-full hover:bg-red-700 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
     <?php $__env->endSlot(); ?>

    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Team Member Notice Banner -->
            <?php if(isset($isTeamMember) && $isTeamMember): ?>
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
            <?php endif; ?>

            <!-- Status Banner -->
            <?php if($project->status === 'planning'): ?>
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
            <?php elseif($project->status === 'completed' || $project->status === 'cancelled'): ?>
            <div class="mb-6 bg-gray-50 dark:bg-gray-900/50 border border-gray-200 dark:border-gray-700 rounded-2xl p-4">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-sm text-gray-700 dark:text-gray-300">
                        <p class="font-medium">Project <?php echo e(ucfirst($project->status)); ?></p>
                        <p class="mt-1">This project cannot be edited. Please contact admin for any changes.</p>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Tab Navigation -->
            <div class="mb-6" x-data="{ activeTab: 'overview' }">
                <!-- Mobile Dropdown -->
                <div class="sm:hidden">
                    <select x-model="activeTab" class="block w-full rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-black dark:focus:border-white focus:ring-black dark:focus:ring-white">
                        <option value="overview">Overview</option>
                        <option value="tasks">Tasks</option>
                        <option value="files">Files</option>
                        <option value="discussion">Discussion</option>
                        <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                        <option value="finance">Finance</option>
                        <?php endif; ?>
                        <option value="tickets">Tickets</option>
                    </select>
                </div>

                <!-- Desktop Tabs -->
                <div class="hidden sm:block">
                    <div class="border-b border-gray-200 dark:border-gray-700">
                        <nav class="-mb-px flex space-x-8 overflow-x-auto" aria-label="Tabs">
                            <button @click="activeTab = 'overview'"
                                    :class="activeTab === 'overview' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Overview
                            </button>
                            <button @click="activeTab = 'tasks'"
                                    :class="activeTab === 'tasks' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                Tasks
                                <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300"><?php echo e($project->tasks_count); ?></span>
                            </button>
                            <button @click="activeTab = 'files'"
                                    :class="activeTab === 'files' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                Files
                            </button>
                            <button @click="activeTab = 'discussion'"
                                    :class="activeTab === 'discussion' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                                Discussion
                            </button>
                            <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                            <button @click="activeTab = 'finance'"
                                    :class="activeTab === 'finance' ? 'border-black dark:border-white text-black dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600'"
                                    class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center gap-2 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Finance
                            </button>
                            <?php endif; ?>
                            <button @click="activeTab = 'tickets'"
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

                        <?php if($project->description): ?>
                        <div class="mb-6">
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Description</p>
                            <p class="text-gray-900 dark:text-white leading-relaxed"><?php echo e($project->description); ?></p>
                        </div>
                        <?php endif; ?>

                        <div class="grid grid-cols-2 gap-6">
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Start Date</p>
                                <p class="text-gray-900 dark:text-white"><?php echo e($project->start_date ? $project->start_date->format('M d, Y') : 'Not set'); ?></p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">End Date</p>
                                <p class="text-gray-900 dark:text-white"><?php echo e($project->end_date ? $project->end_date->format('M d, Y') : 'Not set'); ?></p>
                            </div>
                        </div>
                    </div>

                    <!-- Progress Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Progress</h3>
                        <div class="flex items-center gap-4">
                            <div class="flex-1">
                                <div class="h-4 bg-gray-200 dark:bg-gray-700 rounded-full overflow-hidden">
                                    <div class="h-full bg-black dark:bg-white transition-all" style="width: <?php echo e($project->progress ?? 0); ?>%"></div>
                                </div>
                            </div>
                            <span class="text-2xl font-bold text-gray-900 dark:text-white"><?php echo e($project->progress ?? 0); ?>%</span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">Project completion</p>
                    </div>

                    <!-- Tasks Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Tasks</h3>
                        <?php if($project->tasks && $project->tasks->count() > 0): ?>
                        <div class="space-y-3">
                            <?php $__currentLoopData = $project->tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl">
                                <input type="checkbox" <?php echo e($task->status === 'completed' ? 'checked' : ''); ?> disabled class="w-5 h-5 rounded">
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white <?php echo e($task->status === 'completed' ? 'line-through' : ''); ?>"><?php echo e($task->name); ?></p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($task->assignee->name ?? 'Unassigned'); ?></p>
                                </div>
                                <span class="text-sm px-2 py-1 rounded-full
                                    <?php echo e($task->priority === 'high' ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : ''); ?>

                                    <?php echo e($task->priority === 'medium' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : ''); ?>

                                    <?php echo e($task->priority === 'low' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : ''); ?>">
                                    <?php echo e(ucfirst($task->priority)); ?>

                                </span>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                        <?php else: ?>
                        <p class="text-gray-600 dark:text-gray-400 text-center py-8">No tasks yet</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    <!-- Status Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Status</h3>
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium
                            <?php echo e($project->status === 'active' ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : ''); ?>

                            <?php echo e($project->status === 'planning' ? 'bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300' : ''); ?>

                            <?php echo e($project->status === 'on_hold' ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : ''); ?>

                            <?php echo e($project->status === 'completed' ? 'bg-purple-100 dark:bg-purple-900/20 text-purple-800 dark:text-purple-300' : ''); ?>

                            <?php echo e($project->status === 'cancelled' ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : ''); ?>">
                            <?php echo e(ucfirst(str_replace('_', ' ', $project->status))); ?>

                        </span>
                    </div>

                    <!-- Budget Card (Hidden from team members) -->
                    <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                        <?php if($project->budget): ?>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Budget</h3>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white"><?php echo e($project->currency); ?> <?php echo e(number_format($project->budget, 2)); ?></p>
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <!-- Priority Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Priority</h3>
                        <span class="inline-flex px-4 py-2 rounded-full text-sm font-medium
                            <?php echo e($project->priority === 1 ? 'bg-red-100 dark:bg-red-900/20 text-red-800 dark:text-red-300' : ''); ?>

                            <?php echo e($project->priority === 2 ? 'bg-orange-100 dark:bg-orange-900/20 text-orange-800 dark:text-orange-300' : ''); ?>

                            <?php echo e($project->priority === 3 ? 'bg-yellow-100 dark:bg-yellow-900/20 text-yellow-800 dark:text-yellow-300' : ''); ?>

                            <?php echo e($project->priority === 4 ? 'bg-green-100 dark:bg-green-900/20 text-green-800 dark:text-green-300' : ''); ?>

                            <?php echo e($project->priority === 5 ? 'bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300' : ''); ?>">
                            Priority <?php echo e($project->priority); ?>

                        </span>
                    </div>

                    <!-- Team Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm" x-data="{ showAddModal: false, showEditModal: false, editMember: null }">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Team</h3>
                            <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                            <button @click="showAddModal = true"
                                class="inline-flex items-center gap-1 px-3 py-1.5 text-sm bg-black dark:bg-white text-white dark:text-black font-medium rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Member
                            </button>
                            <?php endif; ?>
                        </div>

                        <?php if($project->projectManager): ?>
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Project Manager (Admin)</p>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-black dark:bg-white rounded-full flex items-center justify-center">
                                    <span class="text-white dark:text-black font-bold"><?php echo e(substr($project->projectManager->name, 0, 1)); ?></span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-white"><?php echo e($project->projectManager->name); ?></p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($project->projectManager->email); ?></p>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php
                            $internalMembers = $project->members->where('member_type', 'internal');
                            $clientMembers = $project->members->whereIn('member_type', ['client', 'client_team']);
                        ?>

                        <?php if($internalMembers->count() > 0): ?>
                        <div class="mb-4 pb-4 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Internal Team (<?php echo e($internalMembers->count()); ?>)</p>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $internalMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 bg-blue-100 dark:bg-blue-900/20 rounded-full flex items-center justify-center">
                                        <span class="text-blue-800 dark:text-blue-300 text-sm font-medium">
                                            <?php echo e(substr($member->employee->first_name ?? 'T', 0, 1)); ?><?php echo e(substr($member->employee->last_name ?? 'M', 0, 1)); ?>

                                        </span>
                                    </div>
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-900 dark:text-white">
                                            <?php echo e($member->employee->first_name); ?> <?php echo e($member->employee->last_name); ?>

                                        </p>
                                        <?php if($member->role): ?>
                                        <p class="text-xs text-gray-600 dark:text-gray-400"><?php echo e($member->role); ?></p>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if($clientMembers->count() > 0): ?>
                        <div>
                            <p class="text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">Your Team (<?php echo e($clientMembers->count()); ?>)</p>
                            <div class="space-y-2">
                                <?php $__currentLoopData = $clientMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $member): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="flex items-center justify-between gap-3 p-2 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-900/50">
                                    <div class="flex items-center gap-3 flex-1">
                                        <div class="w-8 h-8 bg-purple-100 dark:bg-purple-900/20 rounded-full flex items-center justify-center">
                                            <span class="text-purple-800 dark:text-purple-300 text-sm font-medium">
                                                <?php echo e(substr($member->client_member_name ?? 'C', 0, 1)); ?>

                                            </span>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                <?php echo e($member->client_member_name); ?>

                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 truncate">
                                                <?php echo e($member->client_member_email); ?>

                                            </p>
                                            <?php if($member->role): ?>
                                            <p class="text-xs text-gray-500 dark:text-gray-500"><?php echo e($member->role); ?></p>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                                    <form action="<?php echo e(route('client.projects.members.remove', [$project, $member])); ?>"
                                          method="POST"
                                          id="remove-member-form-<?php echo e($member->id); ?>">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="button"
                                                @click="$dispatch('open-delete-modal', { id: 'removeMemberModal', form: document.getElementById('remove-member-form-<?php echo e($member->id); ?>') })"
                                                class="p-1.5 text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </div>
                        </div>
                        <?php else: ?>
                        <p class="text-sm text-gray-600 dark:text-gray-400">No team members added yet. Click "Add Member" to invite your team.</p>
                        <?php endif; ?>

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

                                    <?php
                                        $availableTeamMembers = $client->teamMembers()
                                            ->where('status', 'active')
                                            ->whereNotIn('id', $project->members()
                                                ->where('member_type', 'client_team')
                                                ->pluck('client_team_member_id')
                                            )
                                            ->get();
                                    ?>

                                    <?php if($availableTeamMembers->count() > 0): ?>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">Select team members to add to this project:</p>

                                        <form action="<?php echo e(route('client.projects.members.add', $project)); ?>" method="POST">
                                            <?php echo csrf_field(); ?>
                                            <div class="max-h-96 overflow-y-auto space-y-2 mb-6">
                                                <?php $__currentLoopData = $availableTeamMembers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teamMember): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <label class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-900 cursor-pointer transition-colors">
                                                        <input type="checkbox" name="team_member_ids[]" value="<?php echo e($teamMember->id); ?>"
                                                            class="w-5 h-5 text-black dark:text-white rounded border-gray-300 dark:border-gray-600 focus:ring-black dark:focus:ring-white">
                                                        <div class="flex items-center gap-3 flex-1">
                                                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white font-bold">
                                                                <?php echo e(strtoupper(substr($teamMember->name, 0, 1))); ?>

                                                            </div>
                                                            <div class="flex-1">
                                                                <p class="font-medium text-gray-900 dark:text-white"><?php echo e($teamMember->name); ?></p>
                                                                <p class="text-sm text-gray-600 dark:text-gray-400"><?php echo e($teamMember->email); ?></p>
                                                                <?php if($teamMember->role): ?>
                                                                    <p class="text-xs text-gray-500 dark:text-gray-500"><?php echo e($teamMember->role); ?></p>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </label>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                                    <?php else: ?>
                                        <div class="text-center py-8">
                                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                                                </svg>
                                            </div>
                                            <p class="text-gray-600 dark:text-gray-400 mb-4">No team members available to add.</p>
                                            <a href="<?php echo e(route('client.team.index')); ?>"
                                                class="inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-black dark:bg-black text-white rounded-full hover:bg-gray-800 dark:hover:bg-gray-900 transition-all duration-200 font-medium">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                                </svg>
                                                Invite Team Members
                                            </a>
                                        </div>
                                    <?php endif; ?>
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
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Tasks</h3>
                                <p class="text-gray-600 dark:text-gray-400">Task management feature coming soon</p>
                            </div>
                        </div>
                    </div>

                    <!-- Files Tab -->
                    <div x-show="activeTab === 'files'" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Files</h3>
                                <p class="text-gray-600 dark:text-gray-400">File management feature coming soon</p>
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
                    <?php if(!isset($isTeamMember) || !$isTeamMember): ?>
                    <div x-show="activeTab === 'finance'" x-cloak>
                        <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900 dark:text-white">Financial Overview</h3>
                            </div>

                            <!-- Budget Card -->
                            <?php if($project->budget): ?>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-green-900/20 dark:to-green-800/20 rounded-xl p-6 border border-green-200 dark:border-green-800">
                                    <p class="text-sm text-green-600 dark:text-green-400 font-medium mb-2">Total Budget</p>
                                    <p class="text-3xl font-bold text-green-900 dark:text-green-100"><?php echo e($project->currency); ?> <?php echo e(number_format($project->budget, 2)); ?></p>
                                </div>
                                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-800/20 rounded-xl p-6 border border-blue-200 dark:border-blue-800">
                                    <p class="text-sm text-blue-600 dark:text-blue-400 font-medium mb-2">Spent</p>
                                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100"><?php echo e($project->currency); ?> 0.00</p>
                                </div>
                                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-purple-900/20 dark:to-purple-800/20 rounded-xl p-6 border border-purple-200 dark:border-purple-800">
                                    <p class="text-sm text-purple-600 dark:text-purple-400 font-medium mb-2">Remaining</p>
                                    <p class="text-3xl font-bold text-purple-900 dark:text-purple-100"><?php echo e($project->currency); ?> <?php echo e(number_format($project->budget, 2)); ?></p>
                                </div>
                            </div>
                            <?php endif; ?>

                            <div class="text-center py-12">
                                <svg class="w-16 h-16 text-gray-400 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Financial Tracking</h3>
                                <p class="text-gray-600 dark:text-gray-400">Expense tracking and invoicing features coming soon</p>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

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
        </div>
    </div>

    <!-- Delete Confirmation Modals -->
    <?php if (isset($component)) { $__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-modal','data' => ['id' => 'deleteProjectModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'deleteProjectModal']); ?>
         <?php $__env->slot('title', null, []); ?> Delete Project <?php $__env->endSlot(); ?>
         <?php $__env->slot('message', null, []); ?> 
            Are you sure you want to delete this project? This action cannot be undone and all project data will be permanently removed.
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd)): ?>
<?php $attributes = $__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd; ?>
<?php unset($__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd)): ?>
<?php $component = $__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd; ?>
<?php unset($__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd); ?>
<?php endif; ?>

    <?php if (isset($component)) { $__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.delete-modal','data' => ['id' => 'removeMemberModal']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('delete-modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['id' => 'removeMemberModal']); ?>
         <?php $__env->slot('title', null, []); ?> Remove Team Member <?php $__env->endSlot(); ?>
         <?php $__env->slot('message', null, []); ?> 
            Are you sure you want to remove this team member from the project? They will lose access to all project resources.
         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd)): ?>
<?php $attributes = $__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd; ?>
<?php unset($__attributesOriginalb7eac87efb73c0c2c26fe03ec80faafd); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd)): ?>
<?php $component = $__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd; ?>
<?php unset($__componentOriginalb7eac87efb73c0c2c26fe03ec80faafd); ?>
<?php endif; ?>
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
<?php /**PATH F:\Project\salary\resources\views/client/projects/show.blade.php ENDPATH**/ ?>