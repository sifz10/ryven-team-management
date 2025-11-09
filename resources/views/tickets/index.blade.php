<x-app-layout>
<div x-data="ticketManager()" class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Ticket Management</h1>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage all support tickets across projects</p>
                </div>
                <button
                    @click="showCreateModal = true"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full hover:bg-gray-800 transition-all font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span>Create Ticket</span>
                </button>
            </div>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <!-- Search -->
                <div class="lg:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search</label>
                    <input
                        type="text"
                        x-model="filters.search"
                        @input.debounce.300ms="applyFilters()"
                        placeholder="Search by ticket number, title..."
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                </div>

                <!-- Status Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select
                        x-model="filters.status"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                        <option value="all">All Status</option>
                        <option value="open">Open</option>
                        <option value="in-progress">In Progress</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>

                <!-- Priority Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                    <select
                        x-model="filters.priority"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                        <option value="all">All Priorities</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                </div>

                <!-- Type Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Type</label>
                    <select
                        x-model="filters.type"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                        <option value="all">All Types</option>
                        <option value="bug">Bug</option>
                        <option value="feature">Feature</option>
                        <option value="enhancement">Enhancement</option>
                        <option value="question">Question</option>
                    </select>
                </div>

                <!-- Project Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Project</label>
                    <select
                        x-model="filters.project_id"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                        <option value="all">All Projects</option>
                        @foreach($projects as $project)
                            <option value="{{ $project->id }}">{{ $project->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Assigned To Filter -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Assigned To</label>
                    <select
                        x-model="filters.assigned_to"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                        <option value="all">All Assignees</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Date From -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date From</label>
                    <input
                        type="date"
                        x-model="filters.date_from"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                </div>

                <!-- Date To -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Date To</label>
                    <input
                        type="date"
                        x-model="filters.date_to"
                        @change="applyFilters()"
                        class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border-2 border-gray-200 dark:border-gray-600 rounded-full text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0 transition-all">
                </div>
            </div>

            <!-- Clear Filters Button -->
            <div class="mt-4 flex justify-end">
                <button
                    @click="clearFilters()"
                    class="inline-flex items-center gap-2 px-4 py-2 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    <span>Clear Filters</span>
                </button>
            </div>
        </div>

        <!-- Tickets List -->
        <div class="space-y-4">
            @forelse($tickets as $ticket)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-all">
                    <div class="flex items-start justify-between">
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                    {{ $ticket->ticket_number }}
                                </span>

                                <!-- Status Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                    {{ $ticket->status === 'open' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}
                                    {{ $ticket->status === 'in-progress' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300' : '' }}
                                    {{ $ticket->status === 'resolved' ? 'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300' : '' }}
                                    {{ $ticket->status === 'closed' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : '' }}">
                                    {{ ucfirst($ticket->status) }}
                                </span>

                                <!-- Priority Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold
                                    {{ $ticket->priority === 'low' ? 'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300' : '' }}
                                    {{ $ticket->priority === 'medium' ? 'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300' : '' }}
                                    {{ $ticket->priority === 'high' ? 'bg-orange-100 dark:bg-orange-900 text-orange-700 dark:text-orange-300' : '' }}
                                    {{ $ticket->priority === 'critical' ? 'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300' : '' }}">
                                    {{ ucfirst($ticket->priority) }}
                                </span>

                                <!-- Type Badge -->
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-purple-100 dark:bg-purple-900 text-purple-700 dark:text-purple-300">
                                    {{ ucfirst($ticket->type) }}
                                </span>
                            </div>

                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">
                                <a href="{{ route('tickets.show', $ticket) }}" class="hover:text-black dark:hover:text-white">
                                    {{ $ticket->title }}
                                </a>
                            </h3>

                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-3 line-clamp-2">
                                {{ $ticket->description }}
                            </p>

                            <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                    </svg>
                                    {{ $ticket->project->name }}
                                </span>

                                @if($ticket->assignedTo)
                                    <span class="inline-flex items-center gap-1">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        {{ $ticket->assignedTo->first_name }} {{ $ticket->assignedTo->last_name }}
                                    </span>
                                @endif

                                <span class="inline-flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    {{ $ticket->created_at->format('M d, Y') }}
                                </span>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 ml-4">
                            <a
                                href="{{ route('tickets.show', $ticket) }}"
                                class="inline-flex items-center gap-1.5 px-4 py-2 bg-black text-white rounded-full hover:bg-gray-800 transition-all text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                <span>View</span>
                            </a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center py-16 bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No tickets found</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or create a new ticket</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($tickets->hasPages())
            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        @endif
    </div>

    <!-- Create Ticket Modal -->
    <div x-show="showCreateModal" x-cloak class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div x-show="showCreateModal" @click="showCreateModal = false" class="fixed inset-0 bg-black/60 backdrop-blur-md"></div>
            <div x-show="showCreateModal" class="relative bg-white dark:bg-gray-800 rounded-3xl shadow-2xl max-w-2xl w-full p-8 z-10 border-2 border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Ticket</h2>
                    <button @click="showCreateModal = false" class="p-2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-xl transition-all">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form @submit.prevent="createTicket()" class="space-y-4">
                    <!-- Project -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Project *</label>
                        <select x-model="ticketForm.project_id" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                            <option value="">Select project</option>
                            @foreach($projects as $project)
                                <option value="{{ $project->id }}">{{ $project->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Title *</label>
                        <input type="text" x-model="ticketForm.title" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Description *</label>
                        <textarea x-model="ticketForm.description" required rows="4" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <!-- Type -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Type *</label>
                            <select x-model="ticketForm.type" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                                <option value="">Select type</option>
                                <option value="bug">Bug</option>
                                <option value="feature">Feature</option>
                                <option value="enhancement">Enhancement</option>
                                <option value="question">Question</option>
                            </select>
                        </div>

                        <!-- Priority -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Priority *</label>
                            <select x-model="ticketForm.priority" required class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                                <option value="">Select priority</option>
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                    </div>

                    <!-- Assigned To -->
                    <div>
                        <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Assign To</label>
                        <select x-model="ticketForm.assigned_to" class="w-full px-4 py-3 bg-white dark:bg-gray-700 border-2 border-gray-200 dark:border-gray-600 rounded-xl text-gray-900 dark:text-white focus:border-black dark:focus:border-white focus:ring-0">
                            <option value="">Unassigned</option>
                            @foreach($employees as $employee)
                                <option value="{{ $employee->id }}">{{ $employee->first_name }} {{ $employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-6 border-t-2 border-gray-200 dark:border-gray-700">
                        <button type="button" @click="showCreateModal = false" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 font-medium transition-all">
                            <span>Cancel</span>
                        </button>
                        <button type="submit" x-bind:disabled="isSubmitting" class="inline-flex items-center gap-2 px-8 py-3 bg-black text-white rounded-full hover:bg-gray-800 disabled:opacity-50 disabled:cursor-not-allowed transition-all font-medium">
                            <svg x-show="isSubmitting" class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span x-text="isSubmitting ? 'Creating...' : 'Create Ticket'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function ticketManager() {
    return {
        showCreateModal: false,
        isSubmitting: false,
        filters: {
            search: new URLSearchParams(window.location.search).get('search') || '',
            status: new URLSearchParams(window.location.search).get('status') || 'all',
            priority: new URLSearchParams(window.location.search).get('priority') || 'all',
            type: new URLSearchParams(window.location.search).get('type') || 'all',
            project_id: new URLSearchParams(window.location.search).get('project_id') || 'all',
            assigned_to: new URLSearchParams(window.location.search).get('assigned_to') || 'all',
            date_from: new URLSearchParams(window.location.search).get('date_from') || '',
            date_to: new URLSearchParams(window.location.search).get('date_to') || ''
        },
        ticketForm: {
            project_id: '',
            title: '',
            description: '',
            type: '',
            priority: 'medium',
            assigned_to: ''
        },

        applyFilters() {
            const params = new URLSearchParams();

            Object.keys(this.filters).forEach(key => {
                if (this.filters[key] && this.filters[key] !== 'all') {
                    params.set(key, this.filters[key]);
                }
            });

            window.location.href = '{{ route("tickets.index") }}?' + params.toString();
        },

        clearFilters() {
            this.filters = {
                search: '',
                status: 'all',
                priority: 'all',
                type: 'all',
                project_id: 'all',
                assigned_to: 'all',
                date_from: '',
                date_to: ''
            };
            window.location.href = '{{ route("tickets.index") }}';
        },

        async createTicket() {
            if (this.isSubmitting) return;
            this.isSubmitting = true;

            try {
                const response = await fetch('{{ route("tickets.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(this.ticketForm)
                });

                const data = await response.json();

                if (data.success) {
                    window.location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to create ticket'));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to create ticket. Please try again.');
            } finally {
                this.isSubmitting = false;
            }
        }
    };
}
</script>

<style>
[x-cloak] { display: none !important; }
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
</x-app-layout>
