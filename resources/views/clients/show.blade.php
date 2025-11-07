<x-app-layout>
<div class="p-6 space-y-6" x-data="{ showInviteModal: false }">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
            @if($client->logo)
                <img src="{{ Storage::url($client->logo) }}" alt="{{ $client->name }}" class="w-16 h-16 object-contain rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-2">
            @else
                <div class="w-16 h-16 flex items-center justify-center bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 text-white dark:text-black rounded-lg font-bold text-xl">
                    {{ strtoupper(substr($client->name, 0, 2)) }}
                </div>
            @endif
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $client->name }}</h1>
                <div class="flex items-center gap-3 mt-1">
                    @if($client->status === 'active')
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Active
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 8 8">
                                <circle cx="4" cy="4" r="3" />
                            </svg>
                            Inactive
                        </span>
                    @endif
                    @if($client->company)
                        <span class="text-gray-600 dark:text-gray-400">{{ $client->company }}</span>
                    @endif
                </div>
            </div>
        </div>
        <div class="flex items-center gap-3">
            @can('edit-clients')
                <a href="{{ route('clients.edit', $client) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-semibold hover:opacity-90 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Client
                </a>
            @endcan
            @can('delete-clients')
                <form action="{{ route('clients.destroy', $client) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this client? This will affect {{ $client->projects->count() }} {{ Str::plural('project', $client->projects->count()) }}.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center gap-2 px-4 py-2.5 border border-red-300 dark:border-red-600 rounded-full text-red-700 dark:text-red-400 font-semibold hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            @endcan
            <a href="{{ route('clients.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-full text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div class="bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-xl p-6 text-white dark:text-black">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Total Projects</p>
                    <p class="text-3xl font-bold mt-2">{{ $client->projects->count() }}</p>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Active Projects</p>
                    <p class="text-3xl font-bold mt-2">{{ $client->projects->where('status', 'active')->count() }}</p>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">In Progress</p>
                    <p class="text-3xl font-bold mt-2">{{ $client->projects->where('status', 'in_progress')->count() }}</p>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
        </div>

        <div class="bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium opacity-90">Completed</p>
                    <p class="text-3xl font-bold mt-2">{{ $client->projects->where('status', 'completed')->count() }}</p>
                </div>
                <svg class="w-12 h-12 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Client Details -->
        <div class="lg:col-span-1 space-y-6">
            <!-- Contact Information -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contact Information</h3>
                <div class="space-y-4">
                    @if($client->email)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                                <a href="mailto:{{ $client->email }}" class="text-gray-900 dark:text-white hover:underline">{{ $client->email }}</a>
                            </div>
                        </div>
                    @endif

                    @if($client->phone)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                                <a href="tel:{{ $client->phone }}" class="text-gray-900 dark:text-white hover:underline">{{ $client->phone }}</a>
                            </div>
                        </div>
                    @endif

                    @if($client->website)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Website</p>
                                <a href="{{ $client->website }}" target="_blank" class="text-blue-600 dark:text-blue-400 hover:underline">{{ $client->website }}</a>
                            </div>
                        </div>
                    @endif

                    @if($client->address)
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Address</p>
                                <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $client->address }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Contact Person -->
            @if($client->contact_person)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Contact Person</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 flex items-center justify-center bg-gradient-to-br from-gray-600 to-gray-700 dark:from-gray-300 dark:to-gray-400 text-white dark:text-black rounded-full font-bold">
                                {{ strtoupper(substr($client->contact_person, 0, 2)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-white">{{ $client->contact_person }}</p>
                                @if($client->contact_person_email)
                                    <a href="mailto:{{ $client->contact_person_email }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline">{{ $client->contact_person_email }}</a>
                                @endif
                            </div>
                        </div>

                        @if($client->contact_person_phone)
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                <div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                                    <a href="tel:{{ $client->contact_person_phone }}" class="text-gray-900 dark:text-white hover:underline">{{ $client->contact_person_phone }}</a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Notes -->
            @if($client->notes)
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notes</h3>
                    <p class="text-gray-700 dark:text-gray-300 whitespace-pre-line">{{ $client->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Right Column - Projects -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white">Projects</h3>
                        @can('create-projects')
                            <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-semibold hover:opacity-90 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                                New Project
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="p-6">
                    @if($client->projects->count() > 0)
                        <div class="space-y-4">
                            @foreach($client->projects as $project)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-black dark:hover:border-white transition-colors">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center gap-3 mb-2">
                                                <a href="{{ route('projects.show', $project) }}" class="text-lg font-bold text-gray-900 dark:text-white hover:underline">
                                                    {{ $project->name }}
                                                </a>
                                                @if($project->status === 'active')
                                                    <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">Active</span>
                                                @elseif($project->status === 'completed')
                                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 text-xs font-semibold rounded-full">Completed</span>
                                                @elseif($project->status === 'in_progress')
                                                    <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 text-xs font-semibold rounded-full">In Progress</span>
                                                @elseif($project->status === 'on_hold')
                                                    <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs font-semibold rounded-full">On Hold</span>
                                                @endif
                                            </div>

                                            @if($project->description)
                                                <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">{{ Str::limit($project->description, 150) }}</p>
                                            @endif

                                            <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                                    </svg>
                                                    <span>{{ $project->tasks->count() }} {{ Str::plural('task', $project->tasks->count()) }}</span>
                                                </div>
                                                <div class="flex items-center gap-2">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                    </svg>
                                                    <span>{{ $project->members->count() }} {{ Str::plural('member', $project->members->count()) }}</span>
                                                </div>
                                                @if($project->start_date)
                                                    <div class="flex items-center gap-2">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        <span>{{ \Carbon\Carbon::parse($project->start_date)->format('M d, Y') }}</span>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Progress Bar -->
                                            <div class="mt-3">
                                                <div class="flex items-center justify-between text-sm mb-1">
                                                    <span class="text-gray-600 dark:text-gray-400">Progress</span>
                                                    <span class="font-semibold text-gray-900 dark:text-white">{{ $project->progress }}%</span>
                                                </div>
                                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                                    <div class="bg-gradient-to-r from-black to-gray-700 dark:from-white dark:to-gray-300 h-2 rounded-full transition-all" style="width: {{ $project->progress }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No projects yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Get started by creating a new project for this client.</p>
                            @can('create-projects')
                                <a href="{{ route('projects.create', ['client_id' => $client->id]) }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-semibold hover:opacity-90 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Create First Project
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

            <!-- Team Members Section -->
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 mt-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Team Members</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Invite team members to access the client portal</p>
                        </div>
                        <button @click="showInviteModal = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-semibold hover:opacity-90 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                            </svg>
                            Invite Member
                        </button>
                    </div>
                </div>

                <div class="p-6">
                    @if($client->teamMembers->count() > 0)
                        <div class="space-y-3">
                            @foreach($client->teamMembers as $member)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-black dark:hover:border-white transition-colors">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-4 flex-1">
                                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 text-white dark:text-black flex items-center justify-center font-bold">
                                                {{ strtoupper(substr($member->name, 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h4 class="font-semibold text-gray-900 dark:text-white">{{ $member->name }}</h4>
                                                    @if($member->status === 'active')
                                                        <span class="px-2 py-0.5 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">Active</span>
                                                    @elseif($member->status === 'pending')
                                                        <span class="px-2 py-0.5 bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs font-semibold rounded-full">Pending</span>
                                                    @else
                                                        <span class="px-2 py-0.5 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 text-xs font-semibold rounded-full">Inactive</span>
                                                    @endif
                                                </div>
                                                <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                    <span>{{ $member->email }}</span>
                                                    @if($member->role)
                                                        <span>•</span>
                                                        <span>{{ $member->role }}</span>
                                                    @endif
                                                    @if($member->projects->count() > 0)
                                                        <span>•</span>
                                                        <span>{{ $member->projects->count() }} {{ Str::plural('project', $member->projects->count()) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            @if($member->status === 'pending')
                                                <form action="{{ route('clients.team-members.resend', [$client, $member]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="text-blue-600 dark:text-blue-400 hover:underline text-sm">
                                                        Resend Invitation
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('clients.team-members.destroy', [$client, $member]) }}" method="POST" onsubmit="return confirm('Are you sure you want to remove this team member?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline text-sm">
                                                    Remove
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="w-16 h-16 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No team members yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-4">Invite team members to collaborate on projects.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Invite Team Member Modal -->
    <div x-cloak>
        <div x-show="showInviteModal" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="showInviteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showInviteModal = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="showInviteModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form action="{{ route('clients.team-members.store', $client) }}" method="POST">
                        @csrf
                        <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Invite Team Member</h3>
                                <button type="button" @click="showInviteModal = false" class="text-gray-400 hover:text-gray-500">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label for="name" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Name *</label>
                                    <input type="text" name="name" id="name" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                </div>

                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Email *</label>
                                    <input type="email" name="email" id="email" required class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                </div>

                                <div>
                                    <label for="role" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Role (Optional)</label>
                                    <input type="text" name="role" id="role" placeholder="e.g., Project Manager, Developer" class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assign Projects (Optional)</label>
                                    <div class="space-y-2 max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-600 rounded-lg p-3">
                                        @forelse($client->projects as $project)
                                            <label class="flex items-center gap-2 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded">
                                                <input type="checkbox" name="project_ids[]" value="{{ $project->id }}" class="rounded border-gray-300 dark:border-gray-600 text-black dark:text-white focus:ring-black dark:focus:ring-white">
                                                <span class="text-sm text-gray-900 dark:text-white">{{ $project->name }}</span>
                                            </label>
                                        @empty
                                            <p class="text-sm text-gray-500 dark:text-gray-400">No projects available</p>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse gap-3">
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-full border border-transparent shadow-sm px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black text-base font-semibold hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black sm:ml-3 sm:w-auto sm:text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                                Send Invitation
                            </button>
                            <button type="button" @click="showInviteModal = false" class="mt-3 w-full inline-flex justify-center rounded-full border border-gray-300 dark:border-gray-600 shadow-sm px-5 py-2.5 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</x-app-layout>
