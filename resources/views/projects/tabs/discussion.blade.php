<div x-data="{
    showMessageForm: false,
    replyingTo: null,
    messageForm: {
        message: '',
        mentions: []
    },
    resetForm() {
        this.messageForm = {
            message: '',
            mentions: []
        };
        this.replyingTo = null;
    },
    openReply(messageId) {
        this.replyingTo = messageId;
        this.showMessageForm = true;
    }
}" class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white">
            Discussion ({{ $project->discussions->count() }} messages)
        </h3>
        <button @click="showMessageForm = !showMessageForm; resetForm()" class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            New Message
        </button>
    </div>

    <!-- Message Form -->
    <div x-show="showMessageForm" x-transition class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6">
        <form action="{{ route('projects.discussions.store', $project) }}" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="parent_id" :value="replyingTo">

            <div>
                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <span x-show="!replyingTo">Post a message</span>
                    <span x-show="replyingTo">Reply to message</span>
                </label>
                <textarea name="message"
                          x-model="messageForm.message"
                          rows="4"
                          required
                          placeholder="Type your message here... Use @ to mention team members"
                          class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"></textarea>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    Tip: Type @ followed by a name to mention someone
                </p>
            </div>

            <div class="flex items-center justify-end gap-3">
                <button type="button" @click="showMessageForm = false; resetForm()" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                    Cancel
                </button>
                <button type="submit" class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                    Post Message
                </button>
            </div>
        </form>
    </div>

    <!-- Pinned Messages -->
    @php
        $pinnedMessages = $project->discussions->where('is_pinned', true)->where('parent_id', null);
    @endphp
    @if($pinnedMessages->count() > 0)
        <div class="space-y-3">
            <h4 class="text-sm font-bold text-gray-700 dark:text-gray-300 uppercase tracking-wide flex items-center gap-2">
                <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M16 12V4H17V2H7V4H8V12L6 14V16H11.2V22H12.8V16H18V14L16 12Z"/>
                </svg>
                Pinned Messages
            </h4>
            @foreach($pinnedMessages as $message)
                @include('projects.tabs.partials.discussion-message', ['message' => $message, 'isPinned' => true])
            @endforeach
        </div>
    @endif

    <!-- All Messages -->
    @if($project->discussions->where('parent_id', null)->count() > 0)
        <div class="space-y-4">
            @foreach($project->discussions->where('parent_id', null)->sortByDesc('created_at') as $message)
                @if(!$message->is_pinned)
                    @include('projects.tabs.partials.discussion-message', ['message' => $message, 'isPinned' => false])
                @endif
            @endforeach
        </div>
    @else
        <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-16 text-center">
            <svg class="w-20 h-20 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No messages yet</h3>
            <p class="text-gray-600 dark:text-gray-400 mb-6">Start the conversation by posting the first message</p>
            <button @click="showMessageForm = true" class="inline-flex items-center gap-2 px-6 py-3 bg-black dark:bg-white text-white dark:text-black rounded-lg font-semibold hover:opacity-90 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Post First Message
            </button>
        </div>
    @endif
</div>

<!-- Tribute.js CSS -->
<style>
.tribute-container {
    position: absolute;
    top: 0;
    left: 0;
    height: auto;
    max-height: 300px;
    overflow: auto;
    display: block;
    z-index: 999999;
}

.tribute-container ul {
    margin: 0;
    margin-top: 2px;
    padding: 0;
    list-style: none;
    background: white;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1);
}

.dark .tribute-container ul {
    background: #1f2937;
    border-color: #374151;
}

.tribute-container li {
    padding: 8px 12px;
    cursor: pointer;
    font-size: 14px;
    color: #374151;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dark .tribute-container li {
    color: #d1d5db;
}

.tribute-container li:hover,
.tribute-container li.highlight {
    background: #f3f4f6;
}

.dark .tribute-container li:hover,
.dark .tribute-container li.highlight {
    background: #374151;
}

.tribute-container .avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: linear-gradient(to bottom right, #000, #4b5563);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 12px;
}

.dark .tribute-container .avatar {
    background: linear-gradient(to bottom right, #fff, #9ca3af);
    color: black;
}
</style>

<!-- Tribute.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/tributejs@5.1.3/dist/tribute.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get team members data
    const teamMembers = [
        @foreach($project->members->where('member_type', 'internal') as $member)
        {
            key: '{{ $member->employee->first_name }} {{ $member->employee->last_name }}',
            value: '{{ $member->employee->first_name }} {{ $member->employee->last_name }}',
            email: '{{ $member->employee->email ?? '' }}',
            initials: '{{ substr($member->employee->first_name, 0, 1) }}{{ substr($member->employee->last_name, 0, 1) }}'
        },
        @endforeach
    ];

    // Initialize Tribute for all message textareas
    const tribute = new Tribute({
        values: teamMembers,
        selectTemplate: function(item) {
            return '@' + item.original.value;
        },
        menuItemTemplate: function(item) {
            return `
                <div class="avatar">${item.original.initials}</div>
                <div>
                    <div style="font-weight: 600;">${item.original.value}</div>
                    ${item.original.email ? `<div style="font-size: 12px; opacity: 0.7;">${item.original.email}</div>` : ''}
                </div>
            `;
        },
        noMatchTemplate: function() {
            return '<li style="padding: 8px 12px; color: #9ca3af;">No team members found</li>';
        },
        lookup: 'key',
        fillAttr: 'value',
        requireLeadingSpace: false,
        allowSpaces: true,
        menuShowMinLength: 1
    });

    // Attach to all textareas with name="message"
    const messageTextareas = document.querySelectorAll('textarea[name="message"]');
    messageTextareas.forEach(textarea => {
        tribute.attach(textarea);
    });

    // Re-attach when new textareas are added dynamically
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1) { // Element node
                    const newTextareas = node.querySelectorAll('textarea[name="message"]');
                    newTextareas.forEach(textarea => {
                        if (!textarea.tribute) {
                            tribute.attach(textarea);
                        }
                    });
                }
            });
        });
    });

    observer.observe(document.body, {
        childList: true,
        subtree: true
    });
});
</script>
