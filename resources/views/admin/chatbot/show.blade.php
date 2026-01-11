<x-app-layout>
<div class="p-6">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('admin.chatbot.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mb-2 inline-block">← Back to Conversations</a>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Chat with {{ $conversation->visitor_name }}</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $conversation->visitor_email }}</p>
        </div>
        <div class="flex gap-2">
            <button onclick="closeConversation()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">Close Chat</button>
        </div>
    </div>

    <div class="grid grid-cols-3 gap-6">
        <!-- Chat Messages -->
        <div class="col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow h-96 flex flex-col">
                <!-- Chat Header -->
                <div class="border-b dark:border-gray-700 p-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white">{{ $conversation->visitor_name }}</h3>
                            <p class="text-sm text-gray-500">Status: <span class="font-medium capitalize">{{ $conversation->status }}</span></p>
                        </div>
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $conversation->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $conversation->status === 'active' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $conversation->status === 'closed' ? 'bg-gray-100 text-gray-800' : '' }}
                        ">
                            {{ ucfirst($conversation->status) }}
                        </span>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-4 flex flex-col gap-3">
                    @forelse($conversation->messages as $message)
                        <div class="flex {{ $message->sender_type === 'employee' ? 'justify-end' : 'justify-start' }}">
                            <div class="max-w-xs">
                                <div class="text-xs text-gray-500 mb-1 {{ $message->sender_type === 'employee' ? 'text-right' : '' }}">
                                    {{ $message->sender_type === 'employee' ? 'You' : $conversation->visitor_name }} · {{ $message->created_at->format('H:i') }}
                                </div>
                                <div class="px-4 py-2 rounded-lg
                                    {{ $message->sender_type === 'employee' ? 'bg-blue-600 text-white' : 'bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-white' }}
                                ">
                                    {{ $message->message }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full text-gray-500 dark:text-gray-400">
                            <p>No messages yet</p>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="border-t dark:border-gray-700 p-4">
                    <form id="reply-form" class="flex gap-2">
                        <textarea id="reply-message" class="flex-1 px-4 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none" placeholder="Type your reply..." rows="1"></textarea>
                        <button type="submit" class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 font-medium">Send</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Visitor Info -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Visitor Information</h3>
                <div class="space-y-3">
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400">Name</label>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $conversation->visitor_name }}</p>
                    </div>
                    @if($conversation->visitor_email)
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">Email</label>
                            <p class="font-medium text-gray-900 dark:text-white break-all">{{ $conversation->visitor_email }}</p>
                        </div>
                    @endif
                    @if($conversation->visitor_phone)
                        <div>
                            <label class="text-xs text-gray-500 dark:text-gray-400">Phone</label>
                            <p class="font-medium text-gray-900 dark:text-white">{{ $conversation->visitor_phone }}</p>
                        </div>
                    @endif
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400">IP Address</label>
                        <p class="font-medium text-gray-900 dark:text-white text-sm">{{ $conversation->visitor_ip }}</p>
                    </div>
                    <div>
                        <label class="text-xs text-gray-500 dark:text-gray-400">Started</label>
                        <p class="font-medium text-gray-900 dark:text-white">{{ $conversation->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Assign to Employee -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Assign To</h3>
                <form id="assign-form" class="space-y-3">
                    <select id="employee_id" class="w-full px-3 py-2 border dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $conversation->assigned_to_employee_id === $employee->id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium">Assign</button>
                </form>
            </div>

            <!-- Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
                <h3 class="font-semibold text-gray-900 dark:text-white mb-4">Actions</h3>
                <div class="space-y-2">
                    <button onclick="deleteConversation()" class="w-full px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Delete Chat</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Send reply
    document.getElementById('reply-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const message = document.getElementById('reply-message').value;
        if (!message.trim()) return;

        try {
            const response = await fetch('{{ route("admin.chatbot.send-reply", $conversation) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ message }),
            });

            if (response.ok) {
                document.getElementById('reply-message').value = '';
                location.reload();
            }
        } catch (error) {
            console.error('Error sending reply:', error);
        }
    });

    // Assign to employee
    document.getElementById('assign-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const employeeId = document.getElementById('employee_id').value;
        if (!employeeId) return;

        try {
            const response = await fetch('{{ route("admin.chatbot.assign", $conversation) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ employee_id: employeeId }),
            });

            if (response.ok) {
                alert('Conversation assigned successfully!');
            }
        } catch (error) {
            console.error('Error assigning:', error);
        }
    });

    // Close conversation
    function closeConversation() {
        if (!confirm('Are you sure you want to close this conversation?')) return;

        fetch('{{ route("admin.chatbot.close", $conversation) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        }).then(() => {
            window.location.href = '{{ route("admin.chatbot.index") }}';
        });
    }

    // Delete conversation
    function deleteConversation() {
        if (!confirm('Are you sure you want to delete this entire conversation?')) return;

        fetch('{{ route("admin.chatbot.destroy", $conversation) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        }).then(() => {
            window.location.href = '{{ route("admin.chatbot.index") }}';
        });
    }

    // Real-time updates via Reverb
    @if(config('broadcasting.default') === 'reverb')
        Echo.private('chat.conversation.{{ $conversation->id }}')
            .listen('.chat.message.received', (event) => {
                // Reload messages on new message
                setTimeout(() => location.reload(), 500);
            });
    @endif
</script>
</x-app-layout>
