<div x-data="{
    showTicketModal: false,
    editingTicket: null,
    filterStatus: 'all',
    filterPriority: 'all',
    ticketForm: {
        title: '',
        description: '',
        type: '',
        priority: 2,
        assigned_to: ''
    },
    resetForm() {
        this.ticketForm = {
            title: '',
            description: '',
            type: '',
            priority: 2,
            assigned_to: ''
        };
        this.editingTicket = null;
    },
    openTicketModal(ticket = null) {
        if (ticket) {
            this.editingTicket = ticket.id;
            this.ticketForm = {
                title: ticket.title,
                description: ticket.description || '',
                type: ticket.type,
                priority: ticket.priority,
                assigned_to: ticket.assigned_to || ''
            };
        } else {
            this.resetForm();
        }
        this.showTicketModal = true;
    }
}" class="space-y-6">
    <!-- Header with Filters -->
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <select x-model="filterStatus" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                <option value="all">All Status</option>
                <option value="open">Open</option>
                <option value="in-progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>

            <select x-model="filterPriority" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white text-sm focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                <option value="all">All Priority</option>
                <option value="1">Low</option>
                <option value="2">Medium</option>
                <option value="3">High</option>
                <option value="4">Critical</option>
            </select>

            <span class="text-sm text-gray-600 dark:text-gray-400">{{ $project->tickets->count() }} tickets</span>
        </div>

        <button @click="openTicketModal()" class="inline-flex items-center gap-2 px-6 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-semibold hover:bg-gray-800 dark:hover:bg-gray-100 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Create Ticket
        </button>
    </div>

    <!-- Tickets List -->
    @if($project->tickets->count() > 0)
        <div class="grid grid-cols-1 gap-4">
            @foreach($project->tickets as $ticket)
                <div x-show="(filterStatus === 'all' || filterStatus === '{{ $ticket->status }}') && (filterPriority === 'all' || filterPriority == {{ $ticket->priority }})"
                     class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-start gap-4">
                        <!-- Ticket Icon -->
                        <div class="w-12 h-12 rounded-lg bg-gradient-to-br
                            @if($ticket->type === 'bug') from-red-500 to-red-700
                            @elseif($ticket->type === 'feature') from-blue-500 to-blue-700
                            @elseif($ticket->type === 'enhancement') from-purple-500 to-purple-700
                            @else from-gray-500 to-gray-700 @endif
                            flex items-center justify-center text-white flex-shrink-0">
                            @if($ticket->type === 'bug')
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20 8h-2.81c-.45-.78-1.07-1.45-1.82-1.96L17 4.41 15.59 3l-2.17 2.17C12.96 5.06 12.49 5 12 5c-.49 0-.96.06-1.41.17L8.41 3 7 4.41l1.62 1.63C7.88 6.55 7.26 7.22 6.81 8H4v2h2.09c-.05.33-.09.66-.09 1v1H4v2h2v1c0 .34.04.67.09 1H4v2h2.81c1.04 1.79 2.97 3 5.19 3s4.15-1.21 5.19-3H20v-2h-2.09c.05-.33.09-.66.09-1v-1h2v-2h-2v-1c0-.34-.04-.67-.09-1H20V8zm-6 8h-4v-2h4v2zm0-4h-4v-2h4v2z"/>
                                </svg>
                            @elseif($ticket->type === 'feature')
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                </svg>
                            @elseif($ticket->type === 'enhancement')
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                                </svg>
                            @else
                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                                </svg>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <!-- Ticket Header -->
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-xs font-mono text-gray-500 dark:text-gray-400">{{ $ticket->ticket_number }}</span>
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                            @if($ticket->type === 'bug') bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                            @elseif($ticket->type === 'feature') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($ticket->type === 'enhancement') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                            @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                            {{ ucfirst($ticket->type) }}
                                        </span>
                                    </div>
                                    <h4 class="font-bold text-lg text-gray-900 dark:text-white">{{ $ticket->title }}</h4>
                                    @if($ticket->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-2">{{ Str::limit($ticket->description, 150) }}</p>
                                    @endif
                                </div>

                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                                        @if($ticket->status === 'open') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                        @elseif($ticket->status === 'in-progress') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @elseif($ticket->status === 'resolved') bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ ucfirst(str_replace('-', ' ', $ticket->status)) }}
                                    </span>
                                </div>
                            </div>

                            <!-- Ticket Meta -->
                            <div class="flex items-center gap-6 text-sm text-gray-600 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <span class="font-semibold">Priority:</span>
                                    <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-semibold
                                        @if($ticket->priority === 4) bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400
                                        @elseif($ticket->priority === 3) bg-orange-100 text-orange-800 dark:bg-orange-900/30 dark:text-orange-400
                                        @elseif($ticket->priority === 2) bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                        @else bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                        {{ $ticket->priority_label }}
                                    </span>
                                </div>

                                @if($ticket->assignee)
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">Assigned to:</span>
                                        <div class="flex items-center gap-2">
                                            <div class="w-6 h-6 rounded-full bg-gradient-to-br from-gray-800 to-black dark:from-gray-700 dark:to-gray-900 flex items-center justify-center text-white text-xs font-bold">
                                                {{ substr($ticket->assignee->first_name, 0, 1) }}{{ substr($ticket->assignee->last_name, 0, 1) }}
                                            </div>
                                            <span>{{ $ticket->assignee->first_name }} {{ $ticket->assignee->last_name }}</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-2">
                                        <span class="font-semibold">Unassigned</span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <span>{{ $ticket->created_at->diffForHumans() }}</span>
                                </div>
                            </div>

                            <!-- Actions -->
                            <div class="flex items-center gap-2 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('tickets.show', $ticket) }}" class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-full text-sm font-semibold hover:bg-gray-800 dark:hover:bg-gray-100 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    View Details
                                </a>

                                @if($ticket->status !== 'closed')
                                    <form action="{{ route('projects.tickets.update', [$project, $ticket]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        @if($ticket->status === 'open')
                                            <input type="hidden" name="status" value="in-progress">
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-400 rounded-full text-sm font-semibold hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-all">
                                                Start Progress
                                            </button>
                                        @elseif($ticket->status === 'in-progress')
                                            <input type="hidden" name="status" value="resolved">
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-400 rounded-full text-sm font-semibold hover:bg-purple-200 dark:hover:bg-purple-900/50 transition-all">
                                                Mark Resolved
                                            </button>
                                        @elseif($ticket->status === 'resolved')
                                            <input type="hidden" name="status" value="closed">
                                            <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                                Close Ticket
                                            </button>
                                        @endif
                                    </form>
                                @endif

                                <button @click="openTicketModal({{ $ticket }})" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 rounded-full text-sm font-semibold hover:bg-gray-200 dark:hover:bg-gray-600 transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </button>                                <form action="{{ route('projects.tickets.destroy', [$project, $ticket]) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-400 rounded-lg text-sm font-semibold hover:bg-red-200 dark:hover:bg-red-900/50 transition-colors">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-16 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No tickets yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Create your first ticket to track issues and requests</p>
            <button @click="openTicketModal()" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Create First Ticket
            </button>
        </div>
    @endif

    <!-- Ticket Modal -->
    <div x-show="showTicketModal"
         x-cloak
         class="fixed inset-0 z-50 overflow-y-auto"
         @click.self="showTicketModal = false; resetForm()">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity"></div>

            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-2xl w-full p-6 border border-gray-200 dark:border-gray-700" @click.stop>
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white" x-text="editingTicket ? 'Edit Ticket' : 'Create New Ticket'"></h2>
                    <button @click="showTicketModal = false; resetForm()" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form :action="editingTicket ? '{{ route('projects.tickets.update', [$project, '__TICKET__']) }}'.replace('__TICKET__', editingTicket) : '{{ route('projects.tickets.store', $project) }}'" method="POST" class="space-y-4">
                    @csrf
                    <template x-if="editingTicket">
                        <input type="hidden" name="_method" value="PUT">
                    </template>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Title</label>
                        <input type="text" name="title" x-model="ticketForm.title" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Description</label>
                        <textarea name="description" x-model="ticketForm.description" rows="4" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Type</label>
                            <select name="type" x-model="ticketForm.type" required class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="">Select Type</option>
                                <option value="bug">Bug</option>
                                <option value="feature">Feature</option>
                                <option value="enhancement">Enhancement</option>
                                <option value="question">Question</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Priority</label>
                            <select name="priority" x-model="ticketForm.priority" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                                <option value="1">Low</option>
                                <option value="2">Medium</option>
                                <option value="3">High</option>
                                <option value="4">Critical</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Assign To (Optional)</label>
                        <select name="assigned_to" x-model="ticketForm.assigned_to" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                            <option value="">Unassigned</option>
                            @foreach($project->members->where('member_type', 'internal') as $member)
                                <option value="{{ $member->employee_id }}">{{ $member->employee->first_name }} {{ $member->employee->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-4">
                        <button type="button" @click="showTicketModal = false; resetForm()" class="px-6 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Cancel
                        </button>
                        <button type="submit" class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                            <span x-text="editingTicket ? 'Update Ticket' : 'Create Ticket'"></span>
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
