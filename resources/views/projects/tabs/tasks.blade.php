<div x-data="taskManager" class="space-y-6">
    <!-- Ultra Modern Header -->
    <div class="relative">
        <div class="flex flex-col lg:flex-row items-start lg:items-center justify-between gap-6">
            <!-- View Toggle with Gradient -->
            <div class="flex items-center gap-3 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-900 rounded-2xl p-2 shadow-sm border border-gray-200/50 dark:border-gray-700/50 backdrop-blur-sm">
                <a
                    href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'tasks', 'sub_tab' => 'list']) }}"
                    :class="view === 'list' ? 'bg-gradient-to-r from-black to-gray-900 dark:from-white dark:to-gray-100 text-white dark:text-black shadow-lg scale-105' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-800/50'"
                    class="px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 ease-out flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                    </svg>
                    <span class="font-extrabold">List View</span>
                </a>
                <a
                    href="{{ route('projects.show', ['project' => $project->id, 'tab' => 'tasks', 'sub_tab' => 'kanban']) }}"
                    :class="view === 'kanban' ? 'bg-gradient-to-r from-black to-gray-900 dark:from-white dark:to-gray-100 text-white dark:text-black shadow-lg scale-105' : 'text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 hover:bg-white/50 dark:hover:bg-gray-800/50'"
                    class="px-6 py-3 rounded-xl text-sm font-bold transition-all duration-300 ease-out flex items-center gap-2.5">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 0v10m0-10a2 2 0 012 2h2a2 2 0 012-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2z"></path>
                    </svg>
                    <span class="font-extrabold">Kanban</span>
                </a>
            </div>

            <!-- Premium Add Task Button -->
            <button @click="openTaskModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                </svg>
                <span>New Task</span>
            </button>
        </div>
    </div>

    <!-- Ultra Modern List View -->
    <div x-show="view === 'list'"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100">
        @if($project->tasks->count() > 0)
            <div class="space-y-4">
                @foreach($project->tasks as $task)
                <div @click="viewTaskDetail(@js($task->toArray()))"
                     class="group relative bg-gradient-to-br from-white via-white to-gray-50/50 dark:from-gray-800 dark:via-gray-800 dark:to-gray-900/50 rounded-2xl border-2 border-gray-200/60 dark:border-gray-700/60 hover:border-black dark:hover:border-white transition-all duration-300 cursor-pointer hover:shadow-2xl hover:shadow-black/5 dark:hover:shadow-white/5 transform hover:scale-[1.02] hover:-translate-y-1 overflow-hidden">

                    <!-- Subtle gradient overlay on hover -->
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-600/0 via-purple-600/5 to-blue-600/0 opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>

                    <div class="relative p-7">
                        <div class="flex items-start justify-between gap-6 mb-5">
                            <!-- Left: Task Title & Description -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-3">
                                    <h3 class="text-xl font-extrabold text-gray-900 dark:text-white group-hover:text-black dark:group-hover:text-white transition-colors flex-1">{!! $task->title !!}</h3>
                                </div>
                                @if($task->description)
                                    <p class="text-sm leading-relaxed text-gray-600 dark:text-gray-400 line-clamp-2">{!! Str::limit(strip_tags($task->description), 180) !!}</p>
                                @endif
                            </div>

                            <!-- Right: Quick Actions -->
                            <div class="flex items-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-x-2 group-hover:translate-x-0">
                                <button @click.stop="openTaskModal(@js($task->toArray()))"
                                        class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-black to-gray-900 dark:from-white dark:to-gray-100 text-white dark:text-black rounded-xl text-xs font-extrabold hover:shadow-lg hover:scale-105 transition-all duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('projects.tasks.destroy', [$project, $task]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this task?')" @click.stop>
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="current_view" :value="view">
                                    <button type="submit"
                                            class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl text-xs font-extrabold hover:shadow-lg hover:scale-105 transition-all duration-200">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Modern Meta Information Grid -->
                        <div class="flex flex-wrap items-center gap-3">
                            <!-- Status Badge with Icon -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold shadow-sm
                                @if($task->status === 'completed') bg-gradient-to-r from-green-500 to-emerald-600 text-white
                                @elseif($task->status === 'live') bg-gradient-to-r from-indigo-500 to-blue-600 text-white
                                @elseif($task->status === 'staging') bg-gradient-to-r from-purple-500 to-violet-600 text-white
                                @elseif($task->status === 'awaiting-feedback') bg-gradient-to-r from-orange-500 to-amber-600 text-white
                                @elseif($task->status === 'in-progress') bg-gradient-to-r from-blue-500 to-cyan-600 text-white
                                @elseif($task->status === 'on-hold') bg-gradient-to-r from-yellow-500 to-yellow-600 text-white
                                @else bg-gradient-to-r from-gray-500 to-slate-600 text-white @endif">
                                @if($task->status === 'completed')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($task->status === 'live')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($task->status === 'staging')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z"></path>
                                    </svg>
                                @elseif($task->status === 'awaiting-feedback')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($task->status === 'in-progress')
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                @elseif($task->status === 'on-hold')
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zM7 8a1 1 0 012 0v4a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v4a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                                <span class="uppercase tracking-wider">{{ ucfirst(str_replace('-', ' ', $task->status)) }}</span>
                            </div>

                            <!-- Priority Badge with Enhanced Styling -->
                            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold border-2 backdrop-blur-sm
                                @if($task->priority === 4) bg-red-50/80 border-red-300 text-red-900 dark:bg-red-900/20 dark:border-red-700 dark:text-red-300
                                @elseif($task->priority === 3) bg-orange-50/80 border-orange-300 text-orange-900 dark:bg-orange-900/20 dark:border-orange-700 dark:text-orange-300
                                @elseif($task->priority === 2) bg-blue-50/80 border-blue-300 text-blue-900 dark:bg-blue-900/20 dark:border-blue-700 dark:text-blue-300
                                @else bg-gray-50/80 border-gray-300 text-gray-900 dark:bg-gray-700/20 dark:border-gray-600 dark:text-gray-300 @endif">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 3a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2V5a2 2 0 00-2-2H5zM5 11a2 2 0 00-2 2v2a2 2 0 002 2h2a2 2 0 002-2v-2a2 2 0 00-2-2H5zM11 5a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V5zM14 11a1 1 0 011 1v1h1a1 1 0 110 2h-1v1a1 1 0 11-2 0v-1h-1a1 1 0 110-2h1v-1a1 1 0 011-1z"></path>
                                </svg>
                                <span class="uppercase tracking-wider">{{ $task->priority_label }}</span>
                            </div>

                            <!-- Assignee with Avatar -->
                            @if($task->assignee)
                                <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-gradient-to-r from-gray-100 to-gray-50 dark:from-gray-700 dark:to-gray-800 rounded-xl border border-gray-200 dark:border-gray-600">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-br from-purple-600 via-pink-600 to-blue-600 p-0.5">
                                        <div class="w-full h-full rounded-full bg-gradient-to-br from-gray-900 to-black dark:from-gray-100 dark:to-white flex items-center justify-center text-white dark:text-black text-xs font-extrabold">
                                            {{ substr($task->assignee->first_name, 0, 1) }}{{ substr($task->assignee->last_name, 0, 1) }}
                                        </div>
                                    </div>
                                    <span class="text-xs font-bold text-gray-900 dark:text-white">{{ $task->assignee->first_name }} {{ $task->assignee->last_name }}</span>
                                </div>
                            @endif

                            <!-- Due Date with Premium Styling -->
                            @if($task->due_date)
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-amber-50 to-yellow-50 dark:from-amber-900/20 dark:to-yellow-900/20 border border-amber-200 dark:border-amber-800 rounded-xl">
                                    <svg class="w-4 h-4 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-bold text-amber-900 dark:text-amber-300">{{ $task->due_date->format('M d, Y') }}</span>
                                </div>
                            @endif

                            <!-- Checklist Progress with Bar -->
                            @if($task->checklists->count() > 0)
                                @php
                                    $completed = $task->checklists->where('is_completed', true)->count();
                                    $total = $task->checklists->count();
                                    $percentage = ($completed / $total) * 100;
                                @endphp
                                <div class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-teal-50 to-cyan-50 dark:from-teal-900/20 dark:to-cyan-900/20 border border-teal-200 dark:border-teal-800 rounded-xl">
                                    <svg class="w-4 h-4 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <div class="flex items-center gap-2">
                                        <span class="text-xs font-bold text-teal-900 dark:text-teal-300">{{ $completed }}/{{ $total }}</span>
                                        <div class="w-12 h-1.5 bg-teal-200 dark:bg-teal-800 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-teal-500 to-cyan-500 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <!-- Tags with Modern Styling -->
                            @if($task->tags->count() > 0)
                                <div class="flex items-center gap-2 ml-auto">
                                    @foreach($task->tags->take(3) as $tag)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold border bg-{{ $tag->color }}-50/50 dark:bg-{{ $tag->color }}-900/20 border-{{ $tag->color }}-200 dark:border-{{ $tag->color }}-800 text-{{ $tag->color }}-900 dark:text-{{ $tag->color }}-300 backdrop-blur-sm">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                    @if($task->tags->count() > 3)
                                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300 border border-gray-300 dark:border-gray-600">
                                            +{{ $task->tags->count() - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Bottom accent line -->
                    <div class="h-1 bg-gradient-to-r from-transparent via-gray-200 dark:via-gray-700 to-transparent group-hover:via-black dark:group-hover:via-white transition-colors duration-300"></div>
                </div>
                @endforeach
            </div>
        @else
            <!-- Premium Empty State -->
            <div class="relative bg-gradient-to-br from-white via-gray-50 to-white dark:from-gray-800 dark:via-gray-900 dark:to-gray-800 rounded-3xl border-2 border-dashed border-gray-300 dark:border-gray-700 py-24 overflow-hidden">
                <!-- Decorative background -->
                <div class="absolute inset-0 opacity-5">
                    <div class="absolute top-10 left-10 w-32 h-32 bg-purple-500 rounded-full blur-3xl"></div>
                    <div class="absolute bottom-10 right-10 w-40 h-40 bg-blue-500 rounded-full blur-3xl"></div>
                </div>

                <div class="relative text-center px-6">
                    <!-- Icon with gradient background -->
                    <div class="inline-flex items-center justify-center w-24 h-24 mb-6 bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-800 rounded-3xl shadow-xl">
                        <svg class="w-12 h-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                    </div>

                    <h3 class="text-3xl font-extrabold text-gray-900 dark:text-white mb-3 bg-gradient-to-r from-gray-900 via-black to-gray-900 dark:from-white dark:via-gray-100 dark:to-white bg-clip-text text-transparent">
                        No Tasks Yet
                    </h3>
                    <p class="text-base text-gray-600 dark:text-gray-400 mb-8 max-w-md mx-auto leading-relaxed">
                        Your task list is empty. Start by creating your first task to organize and track your project work efficiently.
                    </p>

                    <button @click="openTaskModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span>Create Your First Task</span>
                    </button>
                </div>
            </div>
        @endif
    </div>

    <!-- Kanban View -->
    <div x-show="view === 'kanban'" x-transition>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-7 gap-4">
            @php
                $statuses = [
                    'todo' => ['label' => 'To Do', 'color' => 'gray'],
                    'on-hold' => ['label' => 'On Hold', 'color' => 'yellow'],
                    'in-progress' => ['label' => 'In Progress', 'color' => 'blue'],
                    'awaiting-feedback' => ['label' => 'Awaiting Feedback', 'color' => 'orange'],
                    'staging' => ['label' => 'Staging', 'color' => 'purple'],
                    'live' => ['label' => 'Updated on Live', 'color' => 'indigo'],
                    'completed' => ['label' => 'Completed', 'color' => 'green']
                ];
            @endphp

            @foreach($statuses as $status => $config)
                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-xl p-4 border border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="font-bold text-sm text-gray-900 dark:text-white">{{ $config['label'] }}</h3>
                        <span class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-{{ $config['color'] }}-600 dark:text-{{ $config['color'] }}-400 bg-{{ $config['color'] }}-100 dark:bg-{{ $config['color'] }}-900/30 rounded-full">
                            {{ $project->tasks->where('status', $status)->count() }}
                        </span>
                    </div>

                    <div class="kanban-column space-y-3 min-h-[200px]" data-status="{{ $status }}">
                        @foreach($project->tasks->where('status', $status)->sortBy('order') as $task)
                            <div @click="viewTaskDetail(@js($task->toArray()))" class="kanban-task bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700 cursor-pointer hover:shadow-lg transition-shadow"
                                 data-task-id="{{ $task->id }}">
                                <h4 class="font-semibold text-sm text-gray-900 dark:text-white mb-2">{!! $task->title !!}</h4>

                                @if($task->description)
                                    <div class="text-xs text-gray-600 dark:text-gray-400 mb-3 line-clamp-3">{!! Str::limit(strip_tags($task->description), 80) !!}</div>
                                @endif

                                <!-- Checklist Progress -->
                                @if($task->checklists->count() > 0)
                                    <div class="flex items-center gap-2 mb-3 text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                        </svg>
                                        <span>{{ $task->checklists->where('is_completed', true)->count() }}/{{ $task->checklists->count() }}</span>
                                    </div>
                                @endif

                                <!-- Tags -->
                                @if($task->tags->count() > 0)
                                    <div class="flex flex-wrap gap-1 mb-3">
                                        @foreach($task->tags->take(2) as $tag)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $tag->color }}-100 dark:bg-{{ $tag->color }}-900/30 text-{{ $tag->color }}-800 dark:text-{{ $tag->color }}-400">
                                                {{ $tag->name }}
                                            </span>
                                        @endforeach
                                        @if($task->tags->count() > 2)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400">
                                                +{{ $task->tags->count() - 2 }}
                                            </span>
                                        @endif
                                    </div>
                                @endif

                                <div class="flex items-center justify-between">
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        @if($task->priority === 'critical') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @elseif($task->priority === 'high') bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400
                                        @elseif($task->priority === 'medium') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ $task->priority_label }}
                                    </span>

                                    @if($task->assignedTo)
                                        <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-700 dark:to-gray-900 flex items-center justify-center text-white text-xs font-bold" title="{{ $task->assignedTo->first_name }} {{ $task->assignedTo->last_name }}">
                                            {{ substr($task->assignedTo->first_name, 0, 1) }}{{ substr($task->assignedTo->last_name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>

                                @if($task->due_date)
                                    <div class="flex items-center gap-1 mt-3 text-xs text-gray-600 dark:text-gray-400">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        {{ $task->due_date->format('M d') }}
                                    </div>
                                @endif
                            </div>
                        @endforeach

                        @if($project->tasks->where('status', $status)->count() === 0)
                            <div class="kanban-placeholder text-center py-8 text-gray-400 dark:text-gray-600 text-sm">
                                Drop tasks here
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Task Modal -->
    <div x-show="showTaskModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showTaskModal = false; resetForm()">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full p-6 border border-gray-200 dark:border-gray-700" @click.stop>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="editingTask ? 'Edit Task' : 'Create New Task'"></h2>
                    <button @click="showTaskModal = false; resetForm()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form :action="editingTask ? '{{ route('projects.tasks.update', [$project, '__TASK__']) }}'.replace('__TASK__', editingTask) : '{{ route('projects.tasks.store', $project) }}'" method="POST" class="space-y-4" @keydown.enter.prevent="$event.target.form && $event.target.tagName !== 'TEXTAREA' && $event.target.id !== 'task-description-editor' ? false : true">
                    @csrf
                    <template x-if="editingTask">
                        <input type="hidden" name="_method" value="PUT">
                    </template>
                    <input type="hidden" name="current_view" :value="view">

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Task Title</label>
                        <input type="text" name="title" x-model="taskForm.title" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <div id="task-description-editor" class="bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg" style="min-height: 150px;"></div>
                        <textarea name="description" x-model="taskForm.description" class="hidden"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                            <select name="status" x-model="taskForm.status" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="todo">To Do</option>
                                <option value="on-hold">On Hold</option>
                                <option value="in-progress">In Progress</option>
                                <option value="awaiting-feedback">Awaiting Feedback</option>
                                <option value="staging">Staging</option>
                                <option value="live">Updated on Live</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                            <select name="priority" x-model="taskForm.priority" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div x-data="{ open: false }" @click.away="showAssigneeDropdown = false" class="relative">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assign To</label>

                            <!-- Hidden input for form submission -->
                            <input type="hidden" name="assigned_to" x-model="taskForm.assigned_to">

                            <!-- Custom Select Button -->
                            <button
                                type="button"
                                @click="showAssigneeDropdown = !showAssigneeDropdown"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-left focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent flex items-center justify-between"
                            >
                                <div class="flex items-center gap-3 flex-1">
                                    <template x-if="selectedEmployee">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-600 dark:to-gray-800 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                <span x-text="selectedEmployee.initials"></span>
                                            </div>
                                            <div class="min-w-0">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="selectedEmployee.name"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="selectedEmployee.email"></div>
                                            </div>
                                        </div>
                                    </template>
                                    <template x-if="!selectedEmployee">
                                        <span class="text-gray-500 dark:text-gray-400">Unassigned</span>
                                    </template>
                                </div>
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <!-- Dropdown Menu -->
                            <div
                                x-show="showAssigneeDropdown"
                                x-transition:enter="transition ease-out duration-100"
                                x-transition:enter-start="transform opacity-0 scale-95"
                                x-transition:enter-end="transform opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-75"
                                x-transition:leave-start="transform opacity-100 scale-100"
                                x-transition:leave-end="transform opacity-0 scale-95"
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-xl max-h-80 overflow-hidden"
                                style="display: none;"
                            >
                                <!-- Search Input -->
                                <div class="p-3 border-b border-gray-200 dark:border-gray-700">
                                    <div class="relative">
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <input
                                            type="text"
                                            x-model="assigneeSearch"
                                            @click.stop
                                            placeholder="Search by name or email..."
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
                                        >
                                    </div>
                                </div>

                                <!-- Employee List -->
                                <div class="overflow-y-auto max-h-60">
                                    <!-- Unassigned Option -->
                                    <button
                                        type="button"
                                        @click="selectEmployee('')"
                                        class="w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-3 border-b border-gray-100 dark:border-gray-700"
                                    >
                                        <div class="w-8 h-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">Unassigned</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">No assignee</div>
                                        </div>
                                    </button>

                                    <!-- Employee Options -->
                                    <template x-for="employee in filteredEmployees" :key="employee.id">
                                        <button
                                            type="button"
                                            @click="selectEmployee(employee.id)"
                                            class="w-full px-4 py-3 text-left hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors flex items-center gap-3 border-b border-gray-100 dark:border-gray-700 last:border-b-0"
                                            :class="{ 'bg-gray-50 dark:bg-gray-700': taskForm.assigned_to == employee.id }"
                                        >
                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-600 dark:to-gray-800 flex items-center justify-center text-white text-xs font-bold flex-shrink-0">
                                                <span x-text="employee.initials"></span>
                                            </div>
                                            <div class="min-w-0 flex-1">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="employee.name"></div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="employee.email"></div>
                                            </div>
                                            <template x-if="taskForm.assigned_to == employee.id">
                                                <svg class="w-5 h-5 text-black dark:text-white flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                            </template>
                                        </button>
                                    </template>

                                    <!-- No Results -->
                                    <div x-show="filteredEmployees.length === 0" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <p class="text-sm">No employees found</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Due Date</label>
                            <input type="date" name="due_date" x-model="taskForm.due_date" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>
                    </div>

                    <!-- Checklist Section -->
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Checklist</label>
                        <div class="space-y-2">
                            <!-- Existing checklist items -->
                            <template x-for="(item, index) in taskForm.checklist" :key="index">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <input
                                        type="checkbox"
                                        x-model="item.is_completed"
                                        class="w-5 h-5 rounded border-gray-300 dark:border-gray-600 text-black dark:text-white focus:ring-black dark:focus:ring-white"
                                    >
                                    <input
                                        type="text"
                                        x-model="item.title"
                                        :name="'checklist[' + index + '][title]'"
                                        class="flex-1 px-3 py-1.5 border-0 bg-transparent text-gray-900 dark:text-white focus:ring-0"
                                        :class="{ 'line-through text-gray-500': item.is_completed }"
                                    >
                                    <input type="hidden" :name="'checklist[' + index + '][id]'" :value="item.id || ''">
                                    <input type="hidden" :name="'checklist[' + index + '][is_completed]'" :value="item.is_completed ? '1' : '0'">
                                    <button
                                        type="button"
                                        @click="removeChecklistItem(index)"
                                        class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300"
                                    >
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>

                            <!-- Add new checklist item -->
                            <div class="flex items-center gap-2">
                                <input
                                    type="text"
                                    x-model="newChecklistItem"
                                    @keydown.enter.prevent="addChecklistItem()"
                                    placeholder="Add checklist item..."
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
                                >
                                <button
                                    type="button"
                                    @click="addChecklistItem()"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Tags Section -->
                    <div x-data="{ showTagDropdown: false }" @click.away="showTagDropdown = false">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tags</label>

                        <!-- Selected tags -->
                        <div class="flex flex-wrap gap-2 mb-3">
                            <template x-for="(tag, index) in taskForm.tags" :key="index">
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm font-semibold">
                                    <span x-text="tag"></span>
                                    <input type="hidden" :name="'tags[]'" :value="tag">
                                    <button
                                        type="button"
                                        @click="removeTag(index)"
                                        class="hover:text-blue-600 dark:hover:text-blue-300"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </template>
                        </div>

                        <!-- Tag input with autocomplete -->
                        <div class="relative">
                            <div class="flex items-center gap-2">
                                <input
                                    type="text"
                                    x-model="tagInput"
                                    @keydown.enter.prevent="addTag()"
                                    @focus="showTagDropdown = true"
                                    placeholder="Add tags..."
                                    class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
                                >
                                <button
                                    type="button"
                                    @click="addTag()"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Add
                                </button>
                            </div>

                            <!-- Tag suggestions dropdown -->
                            <div
                                x-show="showTagDropdown && filteredTags.length > 0 && tagInput.length > 0"
                                class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-xl max-h-48 overflow-y-auto"
                                style="display: none;"
                            >
                                <template x-for="tag in filteredTags.slice(0, 10)" :key="tag">
                                    <button
                                        type="button"
                                        @click="addTag(tag); showTagDropdown = false"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-900 dark:text-white text-sm"
                                        x-text="tag"
                                    ></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showTaskModal = false; resetForm()" class="inline-flex items-center gap-2 px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-full text-gray-700 dark:text-gray-300 font-extrabold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </button>
                        <button type="submit" @click.prevent="$event.target.closest('form').submit()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span x-text="editingTask ? 'Update Task' : 'Create Task'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Task Detail Modal -->
    <div x-show="showTaskDetailModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showTaskDetailModal = false; viewingTask = null">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-3xl w-full p-6 border border-gray-200 dark:border-gray-700" @click.stop x-show="viewingTask">
                <!-- Header -->
                <div class="flex items-start justify-between mb-6 pb-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-3" x-text="viewingTask?.title"></h2>
                        <div class="flex items-center gap-3">
                            <span class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-xs font-extrabold shadow-sm"
                                  :class="{
                                      'bg-gradient-to-r from-green-500 to-emerald-600 text-white': viewingTask?.status === 'completed',
                                      'bg-gradient-to-r from-indigo-500 to-blue-600 text-white': viewingTask?.status === 'live',
                                      'bg-gradient-to-r from-purple-500 to-violet-600 text-white': viewingTask?.status === 'staging',
                                      'bg-gradient-to-r from-orange-500 to-amber-600 text-white': viewingTask?.status === 'awaiting-feedback',
                                      'bg-gradient-to-r from-blue-500 to-cyan-600 text-white': viewingTask?.status === 'in-progress',
                                      'bg-gradient-to-r from-yellow-500 to-yellow-600 text-white': viewingTask?.status === 'on-hold',
                                      'bg-gradient-to-r from-gray-500 to-slate-600 text-white': viewingTask?.status === 'todo'
                                  }">
                                <!-- Status Icons -->
                                <template x-if="viewingTask?.status === 'completed'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                </template>
                                <template x-if="viewingTask?.status === 'live'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </template>
                                <template x-if="viewingTask?.status === 'staging'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                </template>
                                <template x-if="viewingTask?.status === 'awaiting-feedback'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                    </svg>
                                </template>
                                <template x-if="viewingTask?.status === 'in-progress'">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                </template>
                                <template x-if="viewingTask?.status === 'on-hold'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </template>
                                <template x-if="viewingTask?.status === 'todo'">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </template>
                                <span x-text="viewingTask?.status ? viewingTask.status.replace(/-/g, ' ').replace(/\b\w/g, l => l.toUpperCase()) : ''"></span>
                            </span>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold"
                                  :class="{
                                      'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400': viewingTask?.priority === 'critical',
                                      'bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400': viewingTask?.priority === 'high',
                                      'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400': viewingTask?.priority === 'medium',
                                      'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300': viewingTask?.priority === 'low'
                                  }"
                                  x-text="viewingTask?.priority_label || ''">
                            </span>
                        </div>
                    </div>
                    <button @click="showTaskDetailModal = false; viewingTask = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div class="space-y-6">
                    <!-- Description -->
                    <div x-show="viewingTask?.description">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</h3>
                        <div class="prose dark:prose-invert max-w-none bg-gray-50 dark:bg-gray-900/50 rounded-lg p-4 border border-gray-200 dark:border-gray-700"
                             x-html="viewingTask?.description || ''">
                        </div>
                    </div>

                    <!-- Assignment & Due Date -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assigned To</h3>
                            <div x-show="viewingTask?.assigned_to || viewingTask?.assignedTo">
                                <div class="flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-700 dark:to-gray-900 flex items-center justify-center text-white text-sm font-bold"
                                         x-text="(viewingTask?.assignedTo || viewingTask?.assigned_to) ? ((viewingTask.assignedTo?.first_name || viewingTask.assigned_to?.first_name || '').charAt(0) + (viewingTask.assignedTo?.last_name || viewingTask.assigned_to?.last_name || '').charAt(0)) : ''">
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white"
                                             x-text="(viewingTask?.assignedTo || viewingTask?.assigned_to) ? ((viewingTask.assignedTo?.first_name || viewingTask.assigned_to?.first_name || '') + ' ' + (viewingTask.assignedTo?.last_name || viewingTask.assigned_to?.last_name || '')) : ''">
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400"
                                             x-text="viewingTask?.assignedTo?.email || viewingTask?.assigned_to?.email || ''">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div x-show="!viewingTask?.assigned_to && !viewingTask?.assignedTo" class="text-sm text-gray-500 dark:text-gray-400 italic p-3">
                                Unassigned
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Due Date</h3>
                            <div x-show="viewingTask?.due_date" class="p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                <div class="flex items-center gap-2 text-gray-900 dark:text-white">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-sm font-medium" x-text="viewingTask?.due_date_formatted || ''"></span>
                                </div>
                            </div>
                            <div x-show="!viewingTask?.due_date" class="text-sm text-gray-500 dark:text-gray-400 italic p-3">
                                No due date
                            </div>
                        </div>
                    </div>

                    <!-- Checklist -->
                    <div x-show="viewingTask?.checklists && viewingTask.checklists.length > 0">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Checklist
                                <span class="text-xs font-normal text-gray-500 dark:text-gray-400 ml-2"
                                      x-text="viewingTask?.checklists ? (viewingTask.checklists.filter(c => c.is_completed).length + '/' + viewingTask.checklists.length + ' completed') : ''">
                                </span>
                            </h3>
                            <button
                                @click="saveChecklistProgress()"
                                x-show="checklistChanged"
                                :disabled="savingChecklist"
                                class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-xs font-extrabold hover:opacity-90 transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                <svg x-show="!savingChecklist" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                                </svg>
                                <svg x-show="savingChecklist" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span x-text="savingChecklist ? 'Saving...' : 'Save Progress'"></span>
                            </button>
                        </div>
                        <div class="space-y-2">
                            <template x-for="(item, index) in viewingTask?.checklists || []" :key="item.id">
                                <div
                                    @click="toggleChecklistItem(index)"
                                    class="group flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-black dark:hover:border-white transition-all duration-200 cursor-pointer hover:shadow-md">
                                    <!-- Interactive Checkbox -->
                                    <div class="relative flex-shrink-0 w-5 h-5">
                                        <input
                                            type="checkbox"
                                            :checked="item.is_completed"
                                            @click.stop="toggleChecklistItem(index)"
                                            class="w-5 h-5 rounded border-2 transition-all appearance-none cursor-pointer"
                                            :class="item.is_completed
                                                ? 'bg-white dark:bg-white border-gray-300 dark:border-gray-300'
                                                : 'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500'"
                                        >
                                        <svg x-show="item.is_completed"
                                             class="absolute top-0 left-0 w-5 h-5 text-black pointer-events-none"
                                             fill="none"
                                             stroke="currentColor"
                                             viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>

                                    <span class="flex-1 text-sm font-medium text-gray-900 dark:text-white transition-all duration-200"
                                          :class="{ 'line-through text-gray-400 dark:text-gray-500': item.is_completed }"
                                          x-text="item.title">
                                    </span>

                                    <!-- Completion Badge -->
                                    <div x-show="item.is_completed"
                                         x-transition:enter="transition ease-out duration-200"
                                         x-transition:enter-start="opacity-0 scale-75"
                                         x-transition:enter-end="opacity-100 scale-100"
                                         class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs font-bold">
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
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">File Attachments</h3>

                        <!-- Drop Zone -->
                        <div
                            @drop.prevent="isDragging = false; [...$event.dataTransfer.files].forEach(file => uploadFile(file))"
                            @dragover.prevent="isDragging = true"
                            @dragleave.prevent="isDragging = false"
                            :class="isDragging ? 'border-black dark:border-white bg-gray-100 dark:bg-gray-700' : 'border-gray-300 dark:border-gray-600'"
                            class="border-2 border-dashed rounded-xl p-6 transition-all duration-200 mb-3">
                            <div class="text-center">
                                <svg class="w-12 h-12 mx-auto text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span class="font-semibold">Drag and drop</span> files here or
                                    <label class="text-black dark:text-white font-bold cursor-pointer hover:underline">
                                        browse
                                        <input type="file" class="hidden" @change="uploadFile($event.target.files[0]); $event.target.value = ''">
                                    </label>
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-500">Maximum file size: 100MB</p>
                            </div>
                        </div>

                        <!-- Files List -->
                        <div class="space-y-2" x-show="taskFiles.length > 0">
                            <template x-for="file in taskFiles" :key="file.id">
                                <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700 hover:border-black dark:hover:border-white transition-all cursor-pointer group"
                                     @click="openFilePreview(file)">
                                    <div class="flex items-center gap-3 flex-1 min-w-0">
                                        <!-- File Icon based on extension -->
                                        <div class="flex-shrink-0">
                                            <template x-if="['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(file.extension.toLowerCase())">
                                                <svg class="w-8 h-8 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                </svg>
                                            </template>
                                            <template x-if="file.extension.toLowerCase() === 'pdf'">
                                                <svg class="w-8 h-8 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </template>
                                            <template x-if="['mp4', 'webm', 'ogg', 'mov'].includes(file.extension.toLowerCase())">
                                                <svg class="w-8 h-8 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path>
                                                </svg>
                                            </template>
                                            <template x-if="['mp3', 'wav', 'ogg', 'm4a'].includes(file.extension.toLowerCase())">
                                                <svg class="w-8 h-8 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"></path>
                                                </svg>
                                            </template>
                                            <template x-if="['doc', 'docx'].includes(file.extension.toLowerCase())">
                                                <svg class="w-8 h-8 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd"></path>
                                                </svg>
                                            </template>
                                            <template x-if="['xls', 'xlsx'].includes(file.extension.toLowerCase())">
                                                <svg class="w-8 h-8 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </template>
                                            <template x-if="!['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', 'pdf', 'mp4', 'webm', 'ogg', 'mov', 'mp3', 'wav', 'm4a', 'doc', 'docx', 'xls', 'xlsx'].includes(file.extension.toLowerCase())">
                                                <svg class="w-8 h-8 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                </svg>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white truncate group-hover:text-black dark:group-hover:text-white" x-text="file.original_name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                                <span x-text="file.file_size"></span> •
                                                <span x-text="file.created_at"></span> •
                                                <span x-text="file.uploader.first_name + ' ' + file.uploader.last_name"></span>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <button @click.stop="openFilePreview(file)" class="p-2 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors opacity-0 group-hover:opacity-100">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                            </svg>
                                        </button>
                                        <a :href="file.download_url" @click.stop class="p-2 text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white rounded-lg hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>
                                        <button @click.stop="deleteFile(file.id)" class="p-2 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 rounded-lg hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div>
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">Comments</h3>

                        <!-- Add Comment -->
                        <div class="mb-4 relative">
                            <textarea
                                x-ref="commentTextarea"
                                x-model="newComment"
                                @input="handleCommentInput($event)"
                                @keydown.ctrl.enter="addComment()"
                                placeholder="Add a comment... (Use @ to mention someone, Ctrl+Enter to submit)"
                                rows="3"
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent resize-none"
                            ></textarea>

                            <!-- Mention Dropdown -->
                            <div
                                x-show="showMentionDropdown && mentionEmployees.length > 0"
                                class="absolute z-50 w-64 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto">
                                <template x-for="employee in mentionEmployees" :key="employee.id">
                                    <button
                                        @click="selectMention(employee)"
                                        type="button"
                                        class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-600 dark:to-gray-800 flex items-center justify-center text-white text-xs font-bold">
                                            <span x-text="employee.name.split(' ').map(n => n[0]).join('')"></span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="employee.name"></p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400" x-text="employee.email"></p>
                                        </div>
                                    </button>
                                </template>
                            </div>

                            <div class="flex justify-end mt-2">
                                <button
                                    @click="addComment()"
                                    :disabled="!newComment.trim()"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                    </svg>
                                    Post Comment
                                </button>
                            </div>
                        </div>                        <!-- Comments List -->
                        <div class="space-y-4 max-h-96 overflow-y-auto">
                            <template x-for="comment in comments" :key="comment.id">
                                <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                    <!-- Main Comment -->
                                    <div class="flex gap-3 p-3">
                                        <div class="flex-shrink-0">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-600 dark:to-gray-800 flex items-center justify-center text-white text-sm font-bold">
                                                <span x-text="comment.employee.first_name.charAt(0) + comment.employee.last_name.charAt(0)"></span>
                                            </div>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between mb-1">
                                                <p class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    <span x-text="comment.employee.first_name + ' ' + comment.employee.last_name"></span>
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400" x-text="comment.created_at_human || comment.created_at"></p>
                                            </div>
                                            <p class="text-sm text-gray-700 dark:text-gray-300 whitespace-pre-wrap mb-3" x-text="comment.comment"></p>

                                            <!-- Reactions Bar -->
                                            <div class="flex items-center gap-3 mb-2">
                                                <!-- Reaction Buttons -->
                                                <div class="flex items-center gap-1">
                                                    <template x-for="reactionType in ['like', 'love', 'laugh', 'wow', 'sad', 'angry']" :key="reactionType">
                                                        <button
                                                            @click="toggleReaction(comment.id, reactionType)"
                                                            :class="hasUserReacted(comment, reactionType) ? 'bg-blue-100 dark:bg-blue-900/30 border-blue-300 dark:border-blue-700' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700 hover:bg-gray-100 dark:hover:bg-gray-700'"
                                                            class="inline-flex items-center gap-1 px-2 py-1 border rounded-full text-xs transition-all"
                                                            :title="reactionType">
                                                            <span x-text="getReactionEmoji(reactionType)"></span>
                                                            <span x-show="getReactionCount(comment, reactionType) > 0"
                                                                  x-text="getReactionCount(comment, reactionType)"
                                                                  class="text-gray-700 dark:text-gray-300 font-medium"></span>
                                                        </button>
                                                    </template>
                                                </div>

                                                <!-- Reply Button -->
                                                <button
                                                    @click="comment.showReplyInput = !comment.showReplyInput"
                                                    class="text-xs text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white font-medium">
                                                    <span x-text="comment.showReplyInput ? 'Cancel' : 'Reply'"></span>
                                                </button>
                                            </div>

                                            <!-- Reply Input -->
                                            <div x-show="comment.showReplyInput" class="mt-3 relative">
                                                <div class="flex gap-2">
                                                    <div class="flex-1 relative">
                                                        <input
                                                            type="text"
                                                            x-model="comment.replyText"
                                                            @input="handleReplyInput($event, comment)"
                                                            @keydown.enter="addReply(comment)"
                                                            :data-reply-input="comment.id"
                                                            placeholder="Write a reply... (Use @ to mention)"
                                                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
                                                        >

                                                        <!-- Mention Dropdown for Reply -->
                                                        <div
                                                            x-show="comment.showReplyMentionDropdown && comment.replyMentionEmployees && comment.replyMentionEmployees.length > 0"
                                                            @click.away="comment.showReplyMentionDropdown = false"
                                                            class="absolute z-50 w-64 mt-1 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg max-h-48 overflow-y-auto"
                                                            style="display: none;">
                                                            <template x-for="employee in comment.replyMentionEmployees" :key="employee.id">
                                                                <button
                                                                    @click.stop.prevent="selectReplyMention(employee, comment, $event)"
                                                                    type="button"
                                                                    class="w-full px-4 py-2 text-left hover:bg-gray-100 dark:hover:bg-gray-700 flex items-center gap-2">
                                                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-600 dark:to-gray-800 flex items-center justify-center text-white text-xs font-bold">
                                                                        <span x-text="employee.name.split(' ').map(n => n[0]).join('')"></span>
                                                                    </div>
                                                                    <div>
                                                                        <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="employee.name"></p>
                                                                        <p class="text-xs text-gray-500 dark:text-gray-400" x-text="employee.email"></p>
                                                                    </div>
                                                                </button>
                                                            </template>
                                                        </div>
                                                    </div>
                                                    <button
                                                        @click="addReply(comment)"
                                                        :disabled="!comment.replyText || !comment.replyText.trim()"
                                                        class="px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-bold text-sm hover:opacity-90 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                                                        Send
                                                    </button>
                                                </div>
                                            </div>

                                            <!-- Replies List -->
                                            <div x-show="comment.replies && comment.replies.length > 0" class="mt-3 space-y-2 pl-4 border-l-2 border-gray-300 dark:border-gray-600">
                                                <template x-for="reply in comment.replies" :key="reply.id">
                                                    <div class="flex gap-2">
                                                        <div class="flex-shrink-0">
                                                            <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 dark:from-gray-500 dark:to-gray-700 flex items-center justify-center text-white text-xs font-bold">
                                                                <span x-text="reply.employee.first_name.charAt(0) + reply.employee.last_name.charAt(0)"></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <div class="bg-white dark:bg-gray-800 rounded-lg p-2 border border-gray-200 dark:border-gray-700">
                                                                <div class="flex items-center justify-between mb-1">
                                                                    <p class="text-xs font-semibold text-gray-900 dark:text-white">
                                                                        <span x-text="reply.employee.first_name + ' ' + reply.employee.last_name"></span>
                                                                    </p>
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400" x-text="reply.created_at_human || reply.created_at"></p>
                                                                </div>
                                                                <p class="text-xs text-gray-700 dark:text-gray-300" x-text="reply.reply"></p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </template>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </template>
                            <div x-show="comments.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-sm">No comments yet. Be the first to comment!</p>
                            </div>
                        </div>
                    </div>

                    <!-- Reminders Section -->
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 flex items-center gap-2">
                                <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                Reminders
                                <span x-show="taskReminders.length > 0" class="inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-yellow-500 rounded-full" x-text="taskReminders.length"></span>
                            </h3>
                            <button @click="showAddReminderForm = !showAddReminderForm" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-xs font-bold transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                Add Reminder
                            </button>
                        </div>

                        <!-- Add/Edit Reminder Form -->
                        <div x-show="showAddReminderForm || editingReminder" x-cloak class="mb-4 bg-yellow-50 dark:bg-yellow-900/20 border-2 border-yellow-200 dark:border-yellow-800 rounded-lg p-4">
                            <div class="space-y-3">
                                <!-- Recipient Selection -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Remind Who? (Select multiple)</label>

                                    <!-- Selected Recipients -->
                                    <div x-show="reminderForm.selectedRecipients.length > 0" class="flex flex-wrap gap-2 mb-2">
                                        <template x-for="recipient in reminderForm.selectedRecipients" :key="recipient.value">
                                            <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white dark:bg-gray-800 border-2 border-yellow-400 dark:border-yellow-500 rounded-lg">
                                                <!-- Avatar -->
                                                <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold text-white"
                                                     :class="recipient.type === 'employee' ? 'bg-gradient-to-br from-blue-500 to-blue-700' : 'bg-gradient-to-br from-purple-500 to-purple-700'">
                                                    <span x-text="recipient.initials"></span>
                                                </div>
                                                <span class="text-sm font-semibold text-gray-900 dark:text-white" x-text="recipient.name"></span>
                                                <span class="text-xs px-1.5 py-0.5 rounded-full font-semibold"
                                                      :class="recipient.type === 'employee' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400'"
                                                      x-text="recipient.type === 'employee' ? 'Team' : 'Client'">
                                                </span>
                                                <button @click="removeRecipient(recipient.value)" type="button" class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </template>
                                    </div>

                                    <!-- Search Input -->
                                    <div class="relative" x-effect="recipientSearch && (focusedRecipientIndex = 0)">
                                        <div class="relative flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 absolute left-3 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                            </svg>
                                            <input
                                                type="text"
                                                x-model="recipientSearch"
                                                @focus="showRecipientDropdown = true"
                                                @input="showRecipientDropdown = true"
                                                @keydown.escape="showRecipientDropdown = false"
                                                @keydown.arrow-down.prevent="focusNextRecipient()"
                                                @keydown.arrow-up.prevent="focusPrevRecipient()"
                                                @keydown.enter.prevent="selectFocusedRecipient()"
                                                placeholder="Type to search team members or clients..."
                                                class="w-full px-3 py-2 pl-10 pr-10 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 focus:border-yellow-500 dark:focus:ring-yellow-400 dark:focus:border-yellow-400 transition-all">
                                            <div class="absolute right-3 flex items-center gap-1">
                                                <span x-show="recipientSearch"
                                                      @click="recipientSearch = ''; showRecipientDropdown = true"
                                                      class="cursor-pointer text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                    </svg>
                                                </span>
                                                <svg x-show="showRecipientDropdown" class="w-4 h-4 text-gray-400 transition-transform" :class="{'rotate-180': showRecipientDropdown}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Dropdown -->
                                        <div x-show="showRecipientDropdown"
                                             @click.away="showRecipientDropdown = false"
                                             x-cloak
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute z-50 w-full mt-2 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-lg shadow-xl max-h-72 overflow-y-auto">

                                            <!-- Loading State -->
                                            <template x-if="!reminderRecipients.employees && !reminderRecipients.clients">
                                                <div class="px-4 py-8 text-center">
                                                    <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-yellow-500"></div>
                                                    <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Loading recipients...</p>
                                                </div>
                                            </template>

                                            <!-- Team Members -->
                                            <template x-if="filteredReminderEmployees.length > 0">
                                                <div>
                                                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 sticky top-0">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Team Members</span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="`${filteredReminderEmployees.length} found`"></span>
                                                        </div>
                                                    </div>
                                                    <div class="py-1">
                                                        <template x-for="(employee, empIndex) in filteredReminderEmployees" :key="'emp-' + employee.id">
                                                            <button
                                                                @click="addRecipient('employee', employee)"
                                                                type="button"
                                                                :class="{
                                                                    'bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500': isRecipientSelected('employee-' + employee.id),
                                                                    'bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500': focusedRecipientIndex === empIndex,
                                                                    'hover:bg-gray-100 dark:hover:bg-gray-700 border-l-4 border-transparent': !isRecipientSelected('employee-' + employee.id) && focusedRecipientIndex !== empIndex
                                                                }"
                                                                class="w-full flex items-center gap-3 px-3 py-2.5 transition-all duration-150 text-left">
                                                                <!-- Avatar -->
                                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm">
                                                                    <span x-text="employee.initials"></span>
                                                                </div>
                                                                <!-- Info -->
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center gap-2">
                                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="employee.name"></div>
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300">
                                                                            Team
                                                                        </span>
                                                                    </div>
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="employee.email"></div>
                                                                </div>
                                                                <!-- Checkmark if selected -->
                                                                <svg x-show="isRecipientSelected('employee-' + employee.id)" class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- Client Users -->
                                            <template x-if="filteredReminderClients.length > 0">
                                                <div>
                                                    <div class="px-3 py-2 bg-gray-50 dark:bg-gray-700/50 border-b border-gray-200 dark:border-gray-600 sticky top-0">
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">Client Users</span>
                                                            <span class="text-xs text-gray-500 dark:text-gray-400" x-text="`${filteredReminderClients.length} found`"></span>
                                                        </div>
                                                    </div>
                                                    <div class="py-1">
                                                        <template x-for="(client, clientIndex) in filteredReminderClients" :key="'client-' + client.id">
                                                            <button
                                                                @click="addRecipient('client', client)"
                                                                type="button"
                                                                :class="{
                                                                    'bg-purple-50 dark:bg-purple-900/20 border-l-4 border-purple-500': isRecipientSelected('client-' + client.id),
                                                                    'bg-yellow-50 dark:bg-yellow-900/20 border-l-4 border-yellow-500': focusedRecipientIndex === (filteredReminderEmployees.length + clientIndex),
                                                                    'hover:bg-gray-100 dark:hover:bg-gray-700 border-l-4 border-transparent': !isRecipientSelected('client-' + client.id) && focusedRecipientIndex !== (filteredReminderEmployees.length + clientIndex)
                                                                }"
                                                                class="w-full flex items-center gap-3 px-3 py-2.5 transition-all duration-150 text-left">
                                                                <!-- Avatar -->
                                                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-purple-500 to-purple-700 flex items-center justify-center text-white text-xs font-bold flex-shrink-0 shadow-sm">
                                                                    <span x-text="client.initials"></span>
                                                                </div>
                                                                <!-- Info -->
                                                                <div class="flex-1 min-w-0">
                                                                    <div class="flex items-center gap-2">
                                                                        <div class="text-sm font-semibold text-gray-900 dark:text-white truncate" x-text="client.name"></div>
                                                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300">
                                                                            Client
                                                                        </span>
                                                                    </div>
                                                                    <div class="text-xs text-gray-500 dark:text-gray-400 truncate" x-text="client.email"></div>
                                                                </div>
                                                                <!-- Checkmark if selected -->
                                                                <svg x-show="isRecipientSelected('client-' + client.id)" class="w-5 h-5 text-purple-600 dark:text-purple-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                                </svg>
                                                            </button>
                                                        </template>
                                                    </div>
                                                </div>
                                            </template>

                                            <!-- No Results -->
                                            <div x-show="filteredReminderEmployees.length === 0 && filteredReminderClients.length === 0" class="px-3 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                No recipients found
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Helper Text -->
                                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">You can select multiple recipients to send the same reminder to all of them</p>
                                </div>

                                <!-- Date/Time Selection -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Remind When?</label>
                                    <input type="datetime-local" x-model="reminderForm.remind_at" class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400">
                                </div>

                                <!-- Optional Message -->
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 dark:text-gray-300 mb-2">Message (Optional)</label>
                                    <textarea x-model="reminderForm.message" rows="2" placeholder="Add a note for the reminder..." class="w-full px-3 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:ring-2 focus:ring-yellow-500 dark:focus:ring-yellow-400 resize-none"></textarea>
                                </div>

                                <!-- Form Actions -->
                                <div class="flex items-center justify-end gap-2">
                                    <button @click="cancelReminderForm()" class="px-4 py-2 text-sm font-bold text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
                                        Cancel
                                    </button>
                                    <button @click="saveReminder()" :disabled="reminderForm.selectedRecipients.length === 0 || !reminderForm.remind_at" class="inline-flex items-center gap-1.5 px-4 py-2 bg-yellow-500 hover:bg-yellow-600 disabled:bg-gray-300 dark:disabled:bg-gray-700 text-white rounded-lg text-sm font-bold transition-colors disabled:cursor-not-allowed">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span x-text="editingReminder ? 'Update' : 'Save'"></span> Reminder
                                        <span x-show="!editingReminder && reminderForm.selectedRecipients.length > 1" class="ml-1 px-1.5 py-0.5 bg-yellow-600 rounded-full text-xs" x-text="'(' + reminderForm.selectedRecipients.length + ')'"></span>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Reminders List -->
                        <div class="space-y-2">
                            <template x-for="reminder in taskReminders" :key="reminder.id">
                                <div class="relative group bg-white dark:bg-gray-800 border-2 rounded-lg p-3 transition-all"
                                     :class="reminder.is_sent ? 'border-gray-300 dark:border-gray-600 opacity-60' : 'border-yellow-300 dark:border-yellow-600'">
                                    <!-- Sent Badge -->
                                    <div x-show="reminder.is_sent" class="absolute top-2 right-2">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400 rounded-full text-xs font-bold">
                                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                            Sent
                                        </span>
                                    </div>

                                    <div class="flex items-start justify-between gap-3 pr-16">
                                        <div class="flex-1 min-w-0">
                                            <!-- Recipient & Time -->
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-yellow-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                </svg>
                                                <span class="text-sm font-bold text-gray-900 dark:text-white" x-text="reminder.recipient_name"></span>
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold"
                                                      :class="reminder.recipient_type === 'employee' ? 'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400' : 'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400'"
                                                      x-text="reminder.recipient_type === 'employee' ? 'Team' : 'Client'">
                                                </span>
                                            </div>

                                            <!-- Time -->
                                            <div class="flex items-center gap-2 mb-2">
                                                <svg class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                <span class="text-xs text-gray-600 dark:text-gray-400">
                                                    <span x-text="reminder.remind_at_human"></span>
                                                    <span class="text-gray-400 dark:text-gray-500">(<span x-text="reminder.remind_at"></span>)</span>
                                                </span>
                                            </div>

                                            <!-- Message -->
                                            <div x-show="reminder.message" class="mt-2 px-3 py-2 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-200 dark:border-gray-700">
                                                <p class="text-xs text-gray-700 dark:text-gray-300" x-text="reminder.message"></p>
                                            </div>

                                            <!-- Created By & Time -->
                                            <div class="mt-2 flex items-center gap-2 text-xs text-gray-500 dark:text-gray-400">
                                                <span>Set by <span class="font-semibold" x-text="reminder.created_by.name"></span></span>
                                                <span>•</span>
                                                <span x-text="reminder.created_at"></span>
                                                <template x-if="reminder.is_sent && reminder.sent_at">
                                                    <span>• Sent <span x-text="reminder.sent_at"></span></span>
                                                </template>
                                            </div>
                                        </div>

                                        <!-- Actions (only for non-sent reminders created by current user) -->
                                        <div x-show="!reminder.is_sent && reminder.created_by.id === {{ auth()->user()->employee?->id ?? 'null' }}" class="flex items-center gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                            <button @click="editReminder(reminder)" class="p-1.5 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>
                                            <button @click="deleteReminder(reminder.id)" class="p-1.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded transition-colors">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty State -->
                            <div x-show="taskReminders.length === 0" class="text-center py-8 text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/30 rounded-lg border-2 border-dashed border-gray-300 dark:border-gray-700">
                                <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                                </svg>
                                <p class="text-sm font-semibold">No reminders set</p>
                                <p class="text-xs mt-1">Click "Add Reminder" to create one</p>
                            </div>
                        </div>
                    </div>

                    <!-- Tags -->
                    <div x-show="viewingTask?.tags && viewingTask.tags.length > 0">
                        <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Tags</h3>
                        <div class="flex flex-wrap gap-2">
                            <template x-for="tag in viewingTask?.tags || []" :key="tag.id">
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-sm font-medium"
                                      :class="{
                                          'bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400': tag.color === 'blue',
                                          'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-400': tag.color === 'green',
                                          'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-400': tag.color === 'yellow',
                                          'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400': tag.color === 'red',
                                          'bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400': tag.color === 'purple',
                                          'bg-pink-100 dark:bg-pink-900/30 text-pink-800 dark:text-pink-400': tag.color === 'pink',
                                          'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-800 dark:text-indigo-400': tag.color === 'indigo',
                                          'bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300': tag.color === 'gray'
                                      }"
                                      x-text="tag.name">
                                </span>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Footer Actions -->
                <div class="flex items-center justify-end gap-3 pt-6 mt-6 border-t border-gray-200 dark:border-gray-700">
                    <button @click="showTaskDetailModal = false; viewingTask = null" class="inline-flex items-center gap-2 px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 rounded-full text-gray-700 dark:text-gray-300 font-extrabold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        Close
                    </button>
                    <button @click="editFromDetail()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit Task
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- File Preview Modal -->
    <div x-show="showFilePreview"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showFilePreview = false; previewFile = null">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col border border-gray-200 dark:border-gray-700" @click.stop x-show="previewFile">
                <!-- Header -->
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-3 flex-1 min-w-0">
                        <svg class="w-6 h-6 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                        </svg>
                        <div class="flex-1 min-w-0">
                            <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate" x-text="previewFile?.original_name"></h2>
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                <span x-text="previewFile?.file_size"></span> •
                                <span x-text="previewFile?.uploader?.first_name + ' ' + previewFile?.uploader?.last_name"></span>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a :href="previewFile?.download_url" class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-bold hover:opacity-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Download
                        </a>
                        <button @click="showFilePreview = false; previewFile = null" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Preview Content -->
                <div class="flex-1 overflow-auto p-6 bg-gray-50 dark:bg-gray-900/50">
                    <!-- Image Preview -->
                    <template x-if="previewFile && ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'].includes(previewFile.extension.toLowerCase())">
                        <div class="flex items-center justify-center min-h-[400px]">
                            <img :src="previewFile.url" :alt="previewFile.original_name" class="max-w-full max-h-[70vh] object-contain rounded-lg shadow-lg">
                        </div>
                    </template>

                    <!-- PDF Preview -->
                    <template x-if="previewFile && previewFile.extension.toLowerCase() === 'pdf'">
                        <div class="w-full min-h-[600px] bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                            <iframe :src="previewFile.url + '#toolbar=1&navpanes=0&scrollbar=1'" class="w-full h-[70vh] border-0"></iframe>
                        </div>
                    </template>

                    <!-- Video Preview -->
                    <template x-if="previewFile && ['mp4', 'webm', 'ogg', 'mov'].includes(previewFile.extension.toLowerCase())">
                        <div class="flex items-center justify-center min-h-[400px]">
                            <video controls class="max-w-full max-h-[70vh] rounded-lg shadow-lg">
                                <source :src="previewFile.url" :type="'video/' + previewFile.extension.toLowerCase()">
                                Your browser does not support the video tag.
                            </video>
                        </div>
                    </template>

                    <!-- Audio Preview -->
                    <template x-if="previewFile && ['mp3', 'wav', 'ogg', 'm4a'].includes(previewFile.extension.toLowerCase())">
                        <div class="flex items-center justify-center min-h-[200px]">
                            <div class="w-full max-w-2xl">
                                <audio controls class="w-full">
                                    <source :src="previewFile.url" :type="'audio/' + previewFile.extension.toLowerCase()">
                                    Your browser does not support the audio tag.
                                </audio>
                            </div>
                        </div>
                    </template>

                    <!-- Text/Code Preview -->
                    <template x-if="previewFile && ['txt', 'md', 'json', 'xml', 'csv', 'js', 'css', 'html', 'php', 'py', 'java', 'cpp', 'c', 'h'].includes(previewFile.extension.toLowerCase())">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                            <pre class="text-sm text-gray-900 dark:text-gray-100 whitespace-pre-wrap font-mono" x-text="previewFile.textContent || 'Loading...'"></pre>
                        </div>
                    </template>

                    <!-- Document Preview (Word, Excel, PowerPoint) -->
                    <template x-if="previewFile && ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'].includes(previewFile.extension.toLowerCase())">
                        <div class="w-full min-h-[600px] bg-white dark:bg-gray-800 rounded-lg overflow-hidden">
                            <!-- Google Docs Viewer -->
                            <iframe :src="'https://docs.google.com/gview?url=' + encodeURIComponent(window.location.origin + previewFile.url) + '&embedded=true'" class="w-full h-[70vh] border-0"></iframe>

                            <!-- Fallback message -->
                            <div class="p-6 text-center">
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    If the preview doesn't load, please download the file to view it.
                                </p>
                                <a :href="previewFile.download_url" class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-bold hover:opacity-90 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                    </svg>
                                    Download to View
                                </a>
                            </div>
                        </div>
                    </template>

                    <!-- Unsupported File Type -->
                    <template x-if="previewFile && !isPreviewable(previewFile.extension)">
                        <div class="flex flex-col items-center justify-center min-h-[400px] text-center">
                            <svg class="w-24 h-24 text-gray-300 dark:text-gray-600 mb-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Preview not available</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">This file type cannot be previewed in the browser.</p>
                            <a :href="previewFile?.download_url" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-full font-extrabold hover:opacity-90 transition-all shadow-md">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Download File
                            </a>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Reminder Confirmation Modal -->
    <div x-show="showDeleteModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="cancelDeleteReminder()">
        <div class="flex items-center justify-center min-h-screen px-4 py-6">
            <div class="fixed inset-0 bg-black/70 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md border-2 border-gray-200 dark:border-gray-700"
                 @click.stop
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100">

                <!-- Icon -->
                <div class="flex items-center justify-center pt-8 pb-4">
                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-red-500 to-red-600 flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-8 pb-8 text-center">
                    <h3 class="text-2xl font-extrabold text-gray-900 dark:text-white mb-3">
                        Delete Reminder?
                    </h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                        Are you sure you want to delete this reminder? This action cannot be undone.
                    </p>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 p-6 bg-gray-50 dark:bg-gray-900/50 rounded-b-2xl border-t border-gray-200 dark:border-gray-700">
                    <button @click="cancelDeleteReminder()"
                            class="flex-1 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 font-extrabold hover:bg-gray-100 dark:hover:bg-gray-800 transition-all">
                        Cancel
                    </button>
                    <button @click="confirmDeleteReminder()"
                            class="flex-1 px-6 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-extrabold hover:shadow-lg hover:scale-105 transition-all">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    [x-cloak] { display: none !important; }
    .sortable-ghost {
        opacity: 0.4;
        background: #e5e7eb;
    }
    .dark .sortable-ghost {
        background: #374151;
    }
    .sortable-drag {
        opacity: 0.8;
        transform: rotate(2deg);
    }

    /* Quill Editor Styles */
    .ql-toolbar {
        background: #f9fafb;
        border-top-left-radius: 0.5rem;
        border-top-right-radius: 0.5rem;
    }
    .dark .ql-toolbar {
        background: #1f2937;
        border-color: #4b5563;
    }
    .ql-container {
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
        font-family: inherit;
    }
    .dark .ql-container {
        border-color: #4b5563;
    }
    .dark .ql-editor {
        color: #f9fafb;
    }
    .dark .ql-editor.ql-blank::before {
        color: #9ca3af;
    }
    .dark .ql-stroke {
        stroke: #d1d5db;
    }
    .dark .ql-fill {
        fill: #d1d5db;
    }
    .dark .ql-picker-label {
        color: #d1d5db;
    }
</style>

<!-- Quill.js CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

<!-- SortableJS CDN -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

<!-- Quill.js -->
<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

<script>
// Alpine.js Task Manager Component
document.addEventListener('alpine:init', () => {
    Alpine.data('taskManager', () => ({
        view: '{{ $subTab ?? 'list' }}',
        showTaskModal: false,
        showTaskDetailModal: false,
        viewingTask: null,
        editingTask: null,
        showAssigneeDropdown: false,
        assigneeSearch: '',
        taskForm: {
            title: '',
            description: '',
            status: 'todo',
            priority: 'medium',
            assigned_to: '',
            due_date: '',
            checklist: [],
            tags: []
        },
        newChecklistItem: '',
        newTag: '',
        tagInput: '',
        checklistChanged: false,
        savingChecklist: false,
        allTags: @json(\App\Models\ProjectTaskTag::pluck('name')->toArray()),

        // File upload state
        isDragging: false,
        taskFiles: [],

        // File preview state
        showFilePreview: false,
        previewFile: null,

        // Comments state
        comments: [],
        newComment: '',
        showMentionDropdown: false,
        mentionSearch: '',
        mentionEmployees: [],
        mentionPosition: 0,

        // Reminders state
        taskReminders: [],
        reminderRecipients: { employees: [], clients: [] },
        showAddReminderForm: false,
        editingReminder: null,
        showRecipientDropdown: false,
        recipientSearch: '',
        focusedRecipientIndex: -1,
        showDeleteModal: false,
        deletingReminderId: null,
        reminderForm: {
            selectedRecipients: [],
            remind_at: '',
            message: ''
        },

        get filteredReminderEmployees() {
            if (!this.reminderRecipients.employees) return [];
            if (!this.recipientSearch) return this.reminderRecipients.employees;
            const search = this.recipientSearch.toLowerCase();
            return this.reminderRecipients.employees.filter(emp =>
                emp.name.toLowerCase().includes(search) ||
                emp.email.toLowerCase().includes(search)
            );
        },

        get filteredReminderClients() {
            if (!this.reminderRecipients.clients) return [];
            if (!this.recipientSearch) return this.reminderRecipients.clients;
            const search = this.recipientSearch.toLowerCase();
            return this.reminderRecipients.clients.filter(client =>
                client.name.toLowerCase().includes(search) ||
                client.email.toLowerCase().includes(search)
            );
        },

        get filteredTags() {
            if (!this.tagInput) return this.allTags;
            const search = this.tagInput.toLowerCase();
            return this.allTags.filter(tag => tag.toLowerCase().includes(search));
        },

        employees: [
            @foreach($employees as $employee)
            {
                id: {{ $employee->id }},
                name: '{{ $employee->first_name }} {{ $employee->last_name }}',
                email: '{{ $employee->email }}',
                initials: '{{ substr($employee->first_name, 0, 1) }}{{ substr($employee->last_name, 0, 1) }}'
            }{{ !$loop->last ? ',' : '' }}
            @endforeach
        ],

        get filteredEmployees() {
            if (!this.assigneeSearch) return this.employees;
            const search = this.assigneeSearch.toLowerCase();
            return this.employees.filter(emp =>
                emp.name.toLowerCase().includes(search) ||
                emp.email.toLowerCase().includes(search)
            );
        },

        get selectedEmployee() {
            if (!this.taskForm.assigned_to) return null;
            return this.employees.find(emp => emp.id == this.taskForm.assigned_to);
        },

        selectEmployee(employeeId) {
            this.taskForm.assigned_to = employeeId;
            this.showAssigneeDropdown = false;
            this.assigneeSearch = '';
        },

        addChecklistItem() {
            if (this.newChecklistItem.trim()) {
                this.taskForm.checklist.push({
                    id: null,
                    title: this.newChecklistItem.trim(),
                    is_completed: false
                });
                this.newChecklistItem = '';
            }
        },

        removeChecklistItem(index) {
            this.taskForm.checklist.splice(index, 1);
        },

        addTag(tagName = null) {
            const tag = tagName || this.tagInput.trim();
            if (tag && !this.taskForm.tags.includes(tag)) {
                this.taskForm.tags.push(tag);
                if (!this.allTags.includes(tag)) {
                    this.allTags.push(tag);
                }
            }
            this.tagInput = '';
        },

        removeTag(index) {
            this.taskForm.tags.splice(index, 1);
        },

        resetForm() {
            this.taskForm = {
                title: '',
                description: '',
                status: 'todo',
                priority: 'medium',
                assigned_to: '',
                due_date: '',
                checklist: [],
                tags: []
            };
            this.editingTask = null;
            this.assigneeSearch = '';
            this.newChecklistItem = '';
            this.tagInput = '';
        },

        viewTaskDetail(task) {
            this.viewingTask = task;
            this.checklistChanged = false; // Reset change tracker
            if (task.due_date) {
                const date = new Date(task.due_date);
                this.viewingTask.due_date_formatted = date.toLocaleDateString('en-US', {
                    month: 'short',
                    day: 'numeric',
                    year: 'numeric'
                });
            }
            this.showTaskDetailModal = true;

            // Load files, comments, and reminders
            this.loadFiles();
            this.loadComments();
            this.subscribeToComments();
            this.loadReminders();
            this.loadReminderRecipients();
        },

        toggleChecklistItem(index) {
            if (this.viewingTask && this.viewingTask.checklists && this.viewingTask.checklists[index]) {
                this.viewingTask.checklists[index].is_completed = !this.viewingTask.checklists[index].is_completed;

                // Auto-save immediately
                this.saveChecklistItem(this.viewingTask.checklists[index]);
            }
        },

        saveChecklistItem(item) {
            if (!this.viewingTask || !this.viewingTask.id || !item.id) return;

            const checklistData = [{
                id: item.id,
                is_completed: item.is_completed
            }];

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/checklist`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ checklist: checklistData })
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    console.error('Failed to save checklist item');
                }
            })
            .catch(error => {
                console.error('Error saving checklist item:', error);
            });
        },

        saveChecklistProgress() {
            if (!this.viewingTask || !this.viewingTask.id || this.savingChecklist) return;

            this.savingChecklist = true;
            const checklistData = this.viewingTask.checklists.map(item => ({
                id: item.id,
                is_completed: item.is_completed
            }));

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/checklist`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ checklist: checklistData })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.checklistChanged = false;
                    this.savingChecklist = false;
                    // Show brief success message
                    const event = new CustomEvent('show-toast', {
                        detail: { message: 'Checklist saved successfully!', type: 'success' }
                    });
                    window.dispatchEvent(event);
                } else {
                    this.savingChecklist = false;
                    console.error('Failed to save checklist');
                }
            })
            .catch(error => {
                this.savingChecklist = false;
                console.error('Error saving checklist:', error);
                const event = new CustomEvent('show-toast', {
                    detail: { message: 'Failed to save checklist', type: 'error' }
                });
                window.dispatchEvent(event);
            });
        },

        // File upload methods
        uploadFile(file) {
            if (!this.viewingTask || !this.viewingTask.id) return;

            // Client-side file size validation (100MB = 104857600 bytes)
            const maxSize = 100 * 1024 * 1024; // 100MB in bytes
            if (file.size > maxSize) {
                alert(`File size exceeds 100MB limit. Your file is ${(file.size / 1024 / 1024).toFixed(2)}MB.\n\nPlease compress the file or choose a smaller file.`);
                return;
            }

            const formData = new FormData();
            formData.append('file', file);

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/files`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            })
            .then(response => {
                // Check if response is JSON
                const contentType = response.headers.get("content-type");
                if (!response.ok) {
                    if (!contentType || !contentType.includes("application/json")) {
                        throw new Error(`Server error (${response.status}). You may not have permission to upload files.`);
                    }
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    this.taskFiles.push(data.file);
                    alert('✅ File uploaded successfully!');
                } else {
                    // Show detailed error message
                    let errorMsg = data.message || 'Failed to upload file';

                    // Add helpful hints for common errors
                    if (errorMsg.includes('too large for server configuration') || errorMsg.includes('PHP limit')) {
                        errorMsg += '\n\n💡 Solution:\n1. Run PowerShell as Administrator\n2. Execute: .\\update-php-limits.ps1\n3. Restart your development server';
                    }

                    alert('❌ ' + errorMsg);
                }
            })
            .catch(error => {
                console.error('Error uploading file:', error);
                alert('❌ Error uploading file: ' + error.message);
            });
        },

        deleteFile(fileId) {
            if (!this.viewingTask || !this.viewingTask.id) return;
            if (!confirm('Are you sure you want to delete this file?')) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/files/${fileId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.taskFiles = this.taskFiles.filter(f => f.id !== fileId);
                }
            });
        },

        openFilePreview(file) {
            const fileExtension = file.original_name.split('.').pop();
            const downloadUrl = `/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/files/${file.id}/download`;

            this.previewFile = {
                ...file,
                extension: fileExtension,
                url: downloadUrl,
                download_url: downloadUrl
            };

            // For text-based files, fetch content
            const textExtensions = ['txt', 'md', 'json', 'xml', 'csv', 'pptx', 'docx', 'xlsx'];
            if (textExtensions.includes(this.previewFile.extension.toLowerCase())) {
                fetch(this.previewFile.url)
                    .then(response => response.text())
                    .then(text => {
                        this.previewFile.textContent = text;
                    })
                    .catch(error => {
                        this.previewFile.textContent = 'Error loading file content.';
                    });
            }

            this.showFilePreview = true;
        },

        isPreviewable(extension) {
            const previewableExtensions = [
                'jpg', 'jpeg', 'png', 'gif', 'webp', 'svg', // Images
                'pdf', // PDF
                'mp4', 'webm', 'ogg', 'mov', // Video
                'mp3', 'wav', 'm4a', // Audio
                'txt', 'md', 'json', 'xml', 'csv', // Text
                'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx' // Documents
            ];
            return previewableExtensions.includes(extension.toLowerCase());
        },

        loadFiles() {
            if (!this.viewingTask || !this.viewingTask.id) return;

            // Load files from viewingTask if available
            if (this.viewingTask.files && Array.isArray(this.viewingTask.files)) {
                this.taskFiles = this.viewingTask.files.map(file => ({
                    id: file.id,
                    original_name: file.original_name,
                    extension: file.original_name.split('.').pop(),
                    file_size: file.file_size_formatted || this.formatFileSize(file.file_size),
                    file_type: file.file_type,
                    created_at: file.created_at_human || file.created_at,
                    uploader: file.uploader || { first_name: 'Unknown', last_name: 'User' },
                    download_url: `/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/files/${file.id}/download`
                }));
            } else {
                this.taskFiles = [];
            }
        },

        formatFileSize(bytes) {
            if (bytes === 0) return '0 Bytes';
            const k = 1024;
            const sizes = ['Bytes', 'KB', 'MB', 'GB'];
            const i = Math.floor(Math.log(bytes) / Math.log(k));
            return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
        },        // Comments methods
        loadComments() {
            if (!this.viewingTask || !this.viewingTask.id) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/comments`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Initialize comments with mention state for replies
                        this.comments = data.comments.map(comment => ({
                            ...comment,
                            showReplyInput: false,
                            replyText: '',
                            showReplyMentionDropdown: false,
                            replyMentionEmployees: [],
                            replyMentionPosition: 0
                        }));
                    }
                });
        },

        handleCommentInput(event) {
            const textarea = event.target;
            const text = textarea.value;
            const cursorPosition = textarea.selectionStart;

            // Find the last @ symbol before cursor
            const textBeforeCursor = text.substring(0, cursorPosition);
            const lastAtIndex = textBeforeCursor.lastIndexOf('@');

            if (lastAtIndex !== -1) {
                const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);

                // Check if there's a space after @ (which would close the mention)
                if (!textAfterAt.includes(' ')) {
                    this.mentionSearch = textAfterAt;
                    this.showMentionDropdown = true;
                    this.mentionPosition = lastAtIndex;

                    // Fetch matching employees
                    fetch(`/projects/{{ $project->id }}/employees/mention?search=${this.mentionSearch}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                this.mentionEmployees = data.employees;
                            }
                        });
                } else {
                    this.showMentionDropdown = false;
                }
            } else {
                this.showMentionDropdown = false;
            }
        },

        selectMention(employee) {
            const textarea = this.$refs.commentTextarea;
            if (!textarea) return;

            const text = this.newComment;
            const beforeMention = text.substring(0, this.mentionPosition);
            const afterMention = text.substring(textarea.selectionStart);

            this.newComment = beforeMention + '@' + employee.mention + ' ' + afterMention;
            this.showMentionDropdown = false;
            this.mentionSearch = '';

            // Refocus textarea
            setTimeout(() => {
                textarea.focus();
                const newPosition = (beforeMention + '@' + employee.mention + ' ').length;
                textarea.setSelectionRange(newPosition, newPosition);
            }, 10);
        },

        addComment() {
            if (!this.viewingTask || !this.viewingTask.id) return;
            if (!this.newComment.trim()) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/comments`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ comment: this.newComment })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.comments.unshift(data.comment);
                    this.newComment = '';
                    this.showMentionDropdown = false;
                }
            });
        },

        // Reply to comment
        addReply(comment) {
            if (!comment.replyText || !comment.replyText.trim()) return;
            if (!this.viewingTask || !this.viewingTask.id) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/comments/${comment.id}/replies`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ reply: comment.replyText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Initialize replies array if it doesn't exist
                    if (!comment.replies) {
                        comment.replies = [];
                    }
                    comment.replies.push(data.reply);
                    comment.replyText = '';
                    comment.showReplyInput = false;
                    comment.showReplyMentionDropdown = false;
                }
            });
        },

        // Handle reply input for mentions
        handleReplyInput(event, comment) {
            const input = event.target;
            const text = input.value;
            const cursorPosition = input.selectionStart;

            // Find the last @ symbol before cursor
            const textBeforeCursor = text.substring(0, cursorPosition);
            const lastAtIndex = textBeforeCursor.lastIndexOf('@');

            if (lastAtIndex !== -1) {
                const textAfterAt = textBeforeCursor.substring(lastAtIndex + 1);

                // Check if there's a space after @ (which would close the mention)
                if (!textAfterAt.includes(' ')) {
                    const mentionSearch = textAfterAt;
                    comment.showReplyMentionDropdown = true;
                    comment.replyMentionPosition = lastAtIndex;

                    // Fetch matching employees
                    fetch(`/projects/{{ $project->id }}/employees/mention?search=${mentionSearch}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                comment.replyMentionEmployees = data.employees;
                            }
                        });
                } else {
                    comment.showReplyMentionDropdown = false;
                }
            } else {
                comment.showReplyMentionDropdown = false;
            }
        },

        // Select mention in reply
        selectReplyMention(employee, comment, event) {
            // Find the input element by data attribute
            const replyInput = document.querySelector(`input[data-reply-input="${comment.id}"]`);
            if (!replyInput) return;

            const text = comment.replyText || '';
            const cursorPosition = replyInput.selectionStart;
            const textBeforeCursor = text.substring(0, cursorPosition);
            const lastAtIndex = textBeforeCursor.lastIndexOf('@');

            const beforeMention = text.substring(0, lastAtIndex);
            const afterMention = text.substring(cursorPosition);

            comment.replyText = beforeMention + '@' + employee.mention + ' ' + afterMention;
            comment.showReplyMentionDropdown = false;

            // Refocus input
            this.$nextTick(() => {
                replyInput.focus();
                const newPosition = (beforeMention + '@' + employee.mention + ' ').length;
                replyInput.setSelectionRange(newPosition, newPosition);
            });
        },        // Toggle reaction on comment
        toggleReaction(commentId, reactionType) {
            if (!this.viewingTask || !this.viewingTask.id) return;

            const comment = this.comments.find(c => c.id === commentId);
            if (!comment) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/comments/${commentId}/reactions`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
                },
                body: JSON.stringify({ reaction_type: reactionType })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload comments to get updated reactions
                    this.loadComments();
                }
            });
        },

        // Get reaction emoji
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

        // Get reaction count for a comment
        getReactionCount(comment, reactionType) {
            if (!comment.reactions) return 0;
            return comment.reactions.filter(r => r.reaction_type === reactionType).length;
        },

        // Check if current user has reacted
        hasUserReacted(comment, reactionType) {
            if (!comment.reactions) return false;
            // You'll need to pass the current user's employee ID from the backend
            const currentEmployeeId = {{ auth()->user()->employee ? auth()->user()->employee->id : 'null' }};
            if (!currentEmployeeId) return false;
            return comment.reactions.some(r =>
                r.reaction_type === reactionType && r.employee_id === currentEmployeeId
            );
        },

        subscribeToComments() {
            if (!this.viewingTask || !this.viewingTask.id) return;

            if (window.Echo) {
                window.Echo.channel(`task.${this.viewingTask.id}`)
                    .listen('.comment.added', (event) => {
                        // Check if comment already exists
                        if (!this.comments.find(c => c.id === event.comment.id)) {
                            this.comments.unshift(event.comment);
                        }
                    });
            }
        },

        // ========== REMINDER FUNCTIONS ==========

        loadReminders() {
            if (!this.viewingTask || !this.viewingTask.id) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/reminders`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.taskReminders = data.reminders;
                    }
                });
        },

        loadReminderRecipients() {
            if (!this.viewingTask || !this.viewingTask.id) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/reminder-recipients`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add initials to recipients
                        this.reminderRecipients = {
                            employees: (data.recipients.employees || []).map(emp => ({
                                ...emp,
                                initials: this.getInitials(emp.name)
                            })),
                            clients: (data.recipients.clients || []).map(client => ({
                                ...client,
                                initials: this.getInitials(client.name)
                            }))
                        };
                    }
                });
        },

        getInitials(name) {
            if (!name) return '?';
            const parts = name.trim().split(' ');
            if (parts.length >= 2) {
                return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase();
            }
            return name.substring(0, 2).toUpperCase();
        },

        isRecipientSelected(value) {
            return this.reminderForm.selectedRecipients.some(r => r.value === value);
        },

        addRecipient(type, user) {
            const value = `${type}-${user.id}`;

            // Check if already selected
            if (this.isRecipientSelected(value)) {
                // If already selected, remove it (toggle behavior)
                this.removeRecipient(value);
                return;
            }

            this.reminderForm.selectedRecipients.push({
                value: value,
                type: type,
                id: user.id,
                name: user.name,
                email: user.email,
                initials: user.initials || this.getInitials(user.name)
            });

            // Don't clear search or close dropdown - allow multi-select
            // User can press Escape or click outside to close
        },

        removeRecipient(value) {
            this.reminderForm.selectedRecipients = this.reminderForm.selectedRecipients.filter(r => r.value !== value);
        },

        saveReminder() {
            if (this.reminderForm.selectedRecipients.length === 0 || !this.reminderForm.remind_at) {
                alert('Please select at least one recipient and reminder time');
                return;
            }

            // If editing, only allow single recipient (for now, update this reminder)
            if (this.editingReminder) {
                if (this.reminderForm.selectedRecipients.length !== 1) {
                    alert('Please select exactly one recipient when editing');
                    return;
                }

                const recipient = this.reminderForm.selectedRecipients[0];
                const [type, id] = recipient.value.split('-');

                const reminderData = {
                    recipient_type: type,
                    recipient_id: parseInt(id),
                    remind_at: this.reminderForm.remind_at,
                    message: this.reminderForm.message || null
                };

                fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/reminders/${this.editingReminder.id}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(reminderData)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        this.loadReminders();
                        this.cancelReminderForm();
                        window.showToast('success', 'Reminder updated successfully!');
                    } else {
                        window.showToast('error', data.message || 'Failed to update reminder');
                    }
                })
                .catch(error => {
                    console.error('Error updating reminder:', error);
                    window.showToast('error', 'Error updating reminder. Please try again.');
                });
                return;
            }

            // For creating, allow multiple recipients - create one reminder per recipient
            const promises = this.reminderForm.selectedRecipients.map(recipient => {
                const [type, id] = recipient.value.split('-');
                const reminderData = {
                    recipient_type: type,
                    recipient_id: parseInt(id),
                    remind_at: this.reminderForm.remind_at,
                    message: this.reminderForm.message || null
                };

                return fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/reminders`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify(reminderData)
                });
            });

            Promise.all(promises)
                .then(responses => Promise.all(responses.map(r => r.json())))
                .then(results => {
                    const successCount = results.filter(r => r.success).length;
                    const failCount = results.filter(r => !r.success).length;

                    if (failCount === 0) {
                        window.showToast('success', `${successCount} reminder(s) created successfully!`);
                        this.loadReminders();
                        this.cancelReminderForm();
                    } else {
                        window.showToast('warning', `${successCount} created, ${failCount} failed.`);
                        this.loadReminders();
                        this.cancelReminderForm();
                    }
                })
                .catch(error => {
                    console.error('Error creating reminders:', error);
                    window.showToast('error', 'Error creating reminders. Please try again.');
                });
        },

        editReminder(reminder) {
            this.editingReminder = reminder;

            // Find the recipient in our lists to get full details
            let recipientObj = null;
            if (reminder.recipient_type === 'employee') {
                recipientObj = this.reminderRecipients.employees?.find(e => e.id === reminder.recipient_id);
            } else {
                recipientObj = this.reminderRecipients.clients?.find(c => c.id === reminder.recipient_id);
            }

            this.reminderForm = {
                selectedRecipients: recipientObj ? [{
                    value: `${reminder.recipient_type}-${reminder.recipient_id}`,
                    type: reminder.recipient_type,
                    id: reminder.recipient_id,
                    name: recipientObj.name || reminder.recipient_name,
                    email: recipientObj.email || '',
                    initials: recipientObj.initials || this.getInitials(reminder.recipient_name)
                }] : [],
                remind_at: reminder.remind_at.replace(' ', 'T').substring(0, 16),
                message: reminder.message || ''
            };
            this.showAddReminderForm = true;
        },

        deleteReminder(reminderId) {
            this.deletingReminderId = reminderId;
            this.showDeleteModal = true;
        },

        confirmDeleteReminder() {
            if (!this.deletingReminderId) return;

            fetch(`/projects/{{ $project->id }}/tasks/${this.viewingTask.id}/reminders/${this.deletingReminderId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.loadReminders();
                    window.showToast('success', 'Reminder deleted successfully!');
                } else {
                    window.showToast('error', data.message || 'Failed to delete reminder');
                }
                this.showDeleteModal = false;
                this.deletingReminderId = null;
            })
            .catch(error => {
                console.error('Error deleting reminder:', error);
                window.showToast('error', 'Error deleting reminder. Please try again.');
                this.showDeleteModal = false;
                this.deletingReminderId = null;
            });
        },

        cancelDeleteReminder() {
            this.showDeleteModal = false;
            this.deletingReminderId = null;
        },

        cancelReminderForm() {
            this.showAddReminderForm = false;
            this.editingReminder = null;
            this.showRecipientDropdown = false;
            this.recipientSearch = '';
            this.focusedRecipientIndex = -1;
            this.reminderForm = {
                selectedRecipients: [],
                remind_at: '',
                message: ''
            };
        },

        // Keyboard navigation for recipient selection
        focusNextRecipient() {
            const allRecipients = [...this.filteredReminderEmployees, ...this.filteredReminderClients];
            if (allRecipients.length === 0) return;

            this.focusedRecipientIndex = (this.focusedRecipientIndex + 1) % allRecipients.length;
        },

        focusPrevRecipient() {
            const allRecipients = [...this.filteredReminderEmployees, ...this.filteredReminderClients];
            if (allRecipients.length === 0) return;

            if (this.focusedRecipientIndex <= 0) {
                this.focusedRecipientIndex = allRecipients.length - 1;
            } else {
                this.focusedRecipientIndex--;
            }
        },

        selectFocusedRecipient() {
            const allRecipients = [...this.filteredReminderEmployees, ...this.filteredReminderClients];
            if (this.focusedRecipientIndex < 0 || this.focusedRecipientIndex >= allRecipients.length) return;

            const recipient = allRecipients[this.focusedRecipientIndex];
            const type = this.focusedRecipientIndex < this.filteredReminderEmployees.length ? 'employee' : 'client';

            this.addRecipient(type, recipient);
        },

        editFromDetail() {
            this.showTaskDetailModal = false;
            this.openTaskModal(this.viewingTask);
        },

        openTaskModal(task = null) {
            if (task) {
                this.editingTask = task.id;
                this.taskForm = {
                    title: task.title,
                    description: task.description || '',
                    status: task.status,
                    priority: task.priority,
                    assigned_to: task.assigned_to || '',
                    due_date: task.due_date || '',
                    checklist: task.checklists ? task.checklists.map(c => ({
                        id: c.id,
                        title: c.title,
                        is_completed: c.is_completed
                    })) : [],
                    tags: task.tags ? task.tags.map(t => t.name) : []
                };
                setTimeout(() => {
                    if (window.quillEditor) {
                        window.quillEditor.root.innerHTML = task.description || '';
                    }
                }, 100);
            } else {
                this.resetForm();
                setTimeout(() => {
                    if (window.quillEditor) {
                        window.quillEditor.setText('');
                    }
                }, 100);
            }
            this.showTaskModal = true;
        }
    }));
});

// Quill Editor and Kanban
let quillEditor = null;

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Quill Editor
    quillEditor = new Quill('#task-description-editor', {
        theme: 'snow',
        placeholder: 'Enter task description...',
        modules: {
            toolbar: [
                [{ 'header': [1, 2, 3, false] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                [{ 'color': [] }, { 'background': [] }],
                ['link', 'code-block'],
                ['clean']
            ]
        }
    });

    // Make quillEditor globally accessible
    window.quillEditor = quillEditor;

    // Sync Quill content with hidden textarea
    quillEditor.on('text-change', function() {
        const description = quillEditor.root.innerHTML;
        // Update Alpine.js model
        const textarea = document.querySelector('textarea[name="description"]');
        if (textarea) {
            textarea.value = description;
            // Trigger Alpine update
            textarea.dispatchEvent(new Event('input'));
        }
    });

    // Initialize Sortable on all kanban columns
    const columns = document.querySelectorAll('.kanban-column');

    columns.forEach(column => {
        new Sortable(column, {
            group: 'kanban',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            filter: '.kanban-placeholder',
            preventOnFilter: false,
            onStart: function() {
                // Disable click events during drag
                document.querySelectorAll('.kanban-task').forEach(task => {
                    task.style.pointerEvents = 'none';
                });
            },
            onEnd: function(evt) {
                // Re-enable click events
                document.querySelectorAll('.kanban-task').forEach(task => {
                    task.style.pointerEvents = 'auto';
                });

                const taskId = evt.item.dataset.taskId;
                const newStatus = evt.to.dataset.status;
                const newIndex = evt.newIndex;

                // Get all task IDs in the new column in their new order
                const taskIds = Array.from(evt.to.querySelectorAll('.kanban-task'))
                    .map(task => task.dataset.taskId);

                // Send AJAX request to update task status and order
                fetch(`/projects/{{ $project->id }}/tasks/${taskId}/move`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        status: newStatus,
                        order: newIndex,
                        task_ids: taskIds
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Update column counts
                        updateColumnCounts();

                        // Show success toast (optional)
                        console.log('Task moved successfully');
                    } else {
                        // Revert on error
                        console.error('Failed to move task');
                        location.reload();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    // Revert on error
                    location.reload();
                });
            }
        });
    });

    function updateColumnCounts() {
        columns.forEach(column => {
            const status = column.dataset.status;
            const count = column.querySelectorAll('.kanban-task').length;
            const badge = column.previousElementSibling?.querySelector('span');
            if (badge) {
                badge.textContent = count;
            }

            // Show/hide placeholder
            const placeholder = column.querySelector('.kanban-placeholder');
            if (placeholder) {
                placeholder.style.display = count === 0 ? 'block' : 'none';
            }
        });
    }
});
</script>

<!-- Toast Notification Component -->
<div x-data="toastNotification()"
     x-cloak
     x-show="show"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 transform translate-y-2"
     x-transition:enter-end="opacity-100 transform translate-y-0"
     x-transition:leave="transition ease-in duration-200"
     x-transition:leave-start="opacity-100 transform translate-y-0"
     x-transition:leave-end="opacity-0 transform translate-y-2"
     class="fixed top-4 right-4 z-[9999] max-w-sm w-full pointer-events-auto">
    <div class="rounded-xl shadow-2xl border-2 overflow-hidden"
         :class="{
             'bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border-green-500/50': type === 'success',
             'bg-gradient-to-r from-red-50 to-rose-50 dark:from-red-900/20 dark:to-rose-900/20 border-red-500/50': type === 'error',
             'bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-yellow-900/20 dark:to-amber-900/20 border-yellow-500/50': type === 'warning',
             'bg-gradient-to-r from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 border-blue-500/50': type === 'info'
         }">
        <div class="p-4 flex items-start gap-3">
            <!-- Icon -->
            <div class="flex-shrink-0">
                <svg x-show="type === 'success'" class="w-6 h-6 text-green-600 dark:text-green-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="type === 'error'" class="w-6 h-6 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="type === 'warning'" class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <svg x-show="type === 'info'" class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <!-- Message -->
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold"
                   :class="{
                       'text-green-900 dark:text-green-100': type === 'success',
                       'text-red-900 dark:text-red-100': type === 'error',
                       'text-yellow-900 dark:text-yellow-100': type === 'warning',
                       'text-blue-900 dark:text-blue-100': type === 'info'
                   }"
                   x-text="message"></p>
            </div>
            <!-- Close Button -->
            <button @click="hide()" class="flex-shrink-0 rounded-lg p-1.5 inline-flex hover:bg-black/5 dark:hover:bg-white/5 transition-colors"
                    :class="{
                        'text-green-600 dark:text-green-400': type === 'success',
                        'text-red-600 dark:text-red-400': type === 'error',
                        'text-yellow-600 dark:text-yellow-400': type === 'warning',
                        'text-blue-600 dark:text-blue-400': type === 'info'
                    }">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </div>
    </div>
</div>

<script>
// Toast Notification Component
function toastNotification() {
    return {
        show: false,
        type: 'info',
        message: '',
        timeout: null,

        showToast(type, message, duration = 5000) {
            this.type = type;
            this.message = message;
            this.show = true;

            if (this.timeout) {
                clearTimeout(this.timeout);
            }

            this.timeout = setTimeout(() => {
                this.hide();
            }, duration);
        },

        hide() {
            this.show = false;
            if (this.timeout) {
                clearTimeout(this.timeout);
            }
        }
    }
}

// Global toast function
window.showToast = function(type, message, duration = 5000) {
    const toastEl = document.querySelector('[x-data*="toastNotification"]');

    if (toastEl && toastEl.__x && toastEl.__x.$data && typeof toastEl.__x.$data.showToast === 'function') {
        toastEl.__x.$data.showToast(type, message, duration);
    } else if (toastEl && toastEl._x_dataStack && toastEl._x_dataStack[0] && typeof toastEl._x_dataStack[0].showToast === 'function') {
        toastEl._x_dataStack[0].showToast(type, message, duration);
    } else {
        console.error('Toast component not found, fallback to alert');
        alert(`${type.toUpperCase()}: ${message}`);
    }
};
</script>
