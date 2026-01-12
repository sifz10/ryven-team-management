<x-app-layout>

<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }

    #messages-container {
        scrollbar-color: rgba(148, 163, 184, 0.5) transparent;
        scrollbar-width: thin;
    }

    #messages-container::-webkit-scrollbar {
        width: 6px;
    }

    #messages-container::-webkit-scrollbar-track {
        background: transparent;
    }

    #messages-container::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.5);
        border-radius: 3px;
    }

    #messages-container::-webkit-scrollbar-thumb:hover {
        background: rgba(148, 163, 184, 0.7);
    }

    .attachment-btn {
        cursor: pointer;
        transition: all 0.2s;
    }

    .attachment-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .attachment-icon {
        transition: transform 0.2s;
    }

    .attachment-btn:hover .attachment-icon {
        transform: scale(1.1);
    }

    .modal-overlay {
        animation: fadeIn 0.2s ease-out;
    }
</style>

<!-- Attachment Preview Modal -->
<div id="attachmentModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center modal-overlay" onclick="closeAttachmentModal(event)">
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-auto m-4" onclick="event.stopPropagation()">
        <!-- Modal Header -->
        <div class="sticky top-0 bg-white dark:bg-gray-800 border-b border-slate-200 dark:border-gray-700 px-6 py-4 flex items-center justify-between">
            <h3 id="attachmentFileName" class="text-lg font-bold text-gray-900 dark:text-white">Attachment</h3>
            <div class="flex items-center gap-2">
                <a id="attachmentDownloadBtn" href="#" download class="p-2 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-colors" title="Download">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                    </svg>
                </a>
                <button onclick="closeAttachmentModal()" class="p-2 hover:bg-slate-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
                    <svg class="w-5 h-5 text-slate-600 dark:text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Modal Content -->
        <div class="p-6 flex items-center justify-center">
            <!-- Image Preview -->
            <img id="previewImage" class="hidden max-h-[70vh] rounded-lg" />

            <!-- PDF Preview -->
            <iframe id="previewPdf" class="hidden w-full h-[70vh] rounded-lg border border-slate-200 dark:border-gray-700"></iframe>

            <!-- Audio Player -->
            <audio id="previewAudio" class="hidden w-full" controls></audio>

            <!-- Video Player -->
            <video id="previewVideo" class="hidden max-h-[70vh] rounded-lg" controls></video>

            <!-- File Icon for unsupported types -->
            <div id="previewUnsupported" class="hidden text-center">
                <svg class="w-24 h-24 text-slate-300 dark:text-slate-600 mx-auto mb-4" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path>
                    <path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 10H17a1 1 0 001-1v-3a1 1 0 00-1-1h-3z"></path>
                </svg>
                <p class="text-slate-600 dark:text-slate-400 font-medium" id="previewUnsupportedText">Preview not available</p>
                <p class="text-sm text-slate-500 dark:text-slate-500 mt-2">Use the download button to save this file</p>
            </div>
        </div>
    </div>
</div>

<div class="min-h-screen bg-gradient-to-br from-slate-50 to-slate-100 dark:from-gray-900 dark:to-gray-800 p-6">
    <!-- Header Section -->
    <div class="mb-8">
        <a href="{{ route('admin.chatbot.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-900 dark:text-slate-400 dark:hover:text-slate-200 mb-4 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            <span>Back to Conversations</span>
        </a>
        
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white mb-1">{{ $conversation->visitor_name }}</h1>
                <p class="text-slate-600 dark:text-slate-400 flex items-center gap-2">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                    {{ $conversation->visitor_email }}
                </p>
            </div>
            <div class="flex gap-3">
                <span class="px-4 py-2 text-sm font-semibold rounded-full transition-colors
                    {{ $conversation->status === 'pending' ? 'bg-amber-100 text-amber-800 dark:bg-amber-900 dark:text-amber-200' : '' }}
                    {{ $conversation->status === 'active' ? 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900 dark:text-emerald-200' : '' }}
                    {{ $conversation->status === 'closed' ? 'bg-slate-200 text-slate-800 dark:bg-slate-700 dark:text-slate-200' : '' }}
                ">
                    {{ ucfirst($conversation->status) }}
                </span>
                <button onclick="closeConversation()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium shadow-md hover:shadow-lg">Close Chat</button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Chat Messages -->
        <div class="lg:col-span-2">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg flex flex-col h-[600px] overflow-hidden">
                <!-- Chat Header -->
                <div class="border-b border-slate-200 dark:border-gray-700 bg-gradient-to-r from-slate-50 to-transparent dark:from-gray-700 dark:to-transparent px-6 py-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white text-lg">{{ $conversation->visitor_name }}</h3>
                            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $conversation->status === 'active' ? '🟢 Active now' : 'Last seen ' . $conversation->updated_at?->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>

                <!-- Messages Area -->
                <div id="messages-container" class="flex-1 overflow-y-auto px-6 py-4 flex flex-col gap-4 scroll-smooth">
                    @forelse($conversation->messages as $message)
                        <div class="flex {{ $message->sender_type === 'employee' ? 'justify-end' : 'justify-start' }} animate-fadeIn">
                            <div class="max-w-sm">
                                <div class="text-xs text-slate-500 dark:text-slate-400 mb-1.5 {{ $message->sender_type === 'employee' ? 'text-right' : '' }}">
                                    {{ $message->sender_type === 'employee' ? 'You' : $conversation->visitor_name }} • {{ $message->created_at->format('H:i') }}
                                </div>
                                @if($message->attachments && $message->attachments->count() > 0)
                                    <div class="flex flex-wrap gap-2 mb-2">
                                        @foreach($message->attachments as $attachment)
                                            @php
                                                $fileExt = strtolower(pathinfo($attachment->file_name, PATHINFO_EXTENSION));
                                                $isImage = in_array($fileExt, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                                $isAudio = in_array($fileExt, ['mp3', 'wav', 'ogg', 'm4a']);
                                                $isPdf = in_array($fileExt, ['pdf']);
                                                $isVideo = in_array($fileExt, ['mp4', 'webm', 'mov', 'avi']);
                                            @endphp
                                            @if($isImage)
                                                <div class="rounded-lg overflow-hidden max-w-xs attachment-btn group cursor-pointer" onclick="openAttachmentPreview('{{ asset('storage/' . $attachment->file_path) }}', '{{ $attachment->file_name }}', 'image')" title="Click to preview">
                                                    <img src="{{ asset('storage/' . $attachment->file_path) }}" alt="{{ $attachment->file_name }}" class="max-h-48 rounded-lg object-cover group-hover:opacity-90 transition-opacity">
                                                </div>
                                            @elseif($isAudio)
                                                <div class="bg-slate-100 dark:bg-gray-600 px-3 py-2 rounded-lg flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v2.605A7.969 7.969 0 015.5 6c1.255 0 2.443.29 3.5.804V4.804z"></path><path d="M9 15.675V9.375c0-.563.448-1.028 1.006-1.028s1.006.465 1.006 1.028v6.3c0 .563-.448 1.028-1.006 1.028s-1.006-.465-1.006-1.028z"></path><path d="M15 9.375c0-.563.448-1.028 1.006-1.028s1.006.465 1.006 1.028v6.3c0 .563-.448 1.028-1.006 1.028s-1.006-.465-1.006-1.028V9.375z"></path><path d="M19 4.804A7.968 7.968 0 0015.5 4c-1.255 0-2.443.29-3.5.804v2.605A7.969 7.969 0 0115.5 6c1.255 0 2.443.29 3.5.804V4.804z"></path></svg>
                                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-sm font-medium truncate text-blue-600 dark:text-blue-400 hover:underline">{{ $attachment->file_name }}</a>
                                                </div>
                                            @else
                                                <div class="bg-slate-100 dark:bg-gray-600 px-3 py-2 rounded-lg flex items-center gap-2">
                                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 10H17a1 1 0 001-1v-3a1 1 0 00-1-1h-3z"></path></svg>
                                                    <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="text-sm font-medium truncate text-blue-600 dark:text-blue-400 hover:underline">{{ $attachment->file_name }}</a>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @endif
                                <div class="px-4 py-3 rounded-2xl font-medium break-words
                                    {{ $message->sender_type === 'employee' ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm' }}
                                ">
                                    {{ $message->message }}
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="flex items-center justify-center h-full text-center">
                            <div>
                                <svg class="w-16 h-16 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                                <p class="text-slate-500 dark:text-slate-400 font-medium">No messages yet</p>
                                <p class="text-sm text-slate-400 dark:text-slate-500 mt-1">Start the conversation below</p>
                            </div>
                        </div>
                    @endforelse
                </div>

                <!-- Input Area -->
                <div class="border-t border-slate-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-6 py-4 space-y-3">
                    <!-- Attachments Preview -->
                    <div id="attachments-preview" class="hidden flex flex-wrap gap-2"></div>
                    
                    <form id="reply-form" class="flex gap-3">
                        <!-- Action Buttons -->
                        <div class="flex gap-2">
                            <!-- File Upload Button -->
                            <button type="button" id="file-upload-btn" class="p-3 bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-slate-200 dark:hover:bg-gray-600 transition-all duration-200" title="Attach file">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 10H17a1 1 0 001-1v-3a1 1 0 00-1-1h-3z"></path></svg>
                            </button>
                            
                            <!-- Voice Record Button -->
                            <button type="button" id="voice-record-btn" class="p-3 bg-slate-100 dark:bg-gray-700 text-slate-600 dark:text-slate-400 rounded-xl hover:bg-slate-200 dark:hover:bg-gray-600 transition-all duration-200" title="Record voice message">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z"></path></svg>
                            </button>
                        </div>

                        <!-- Message Input -->
                        <div class="flex-1 relative">
                            <textarea id="reply-message" class="w-full px-4 py-3 border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 resize-none shadow-sm transition-all" placeholder="Type your message..." rows="1"></textarea>
                        </div>

                        <!-- Send Button -->
                        <button type="submit" class="px-6 py-3 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 font-semibold shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5.951-2.976 5.951 2.976a1 1 0 001.169-1.409l-7-14z"></path></svg>
                            <span class="hidden sm:inline">Send</span>
                        </button>
                    </form>
                </div>

                <!-- Hidden File Input -->
                <input type="file" id="file-input" multiple hidden accept="image/*,application/pdf,.doc,.docx,.txt,.xls,.xlsx,.ppt,.pptx,.zip" />
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Visitor Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-lg">{{ substr($conversation->visitor_name, 0, 1) }}</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 dark:text-white">Visitor Information</h3>
                        <p class="text-xs text-slate-500 dark:text-slate-400">Contact Details</p>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="border-t border-slate-200 dark:border-gray-700 pt-4">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Name</label>
                        <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $conversation->visitor_name }}</p>
                    </div>
                    @if($conversation->visitor_email)
                        <div class="border-t border-slate-200 dark:border-gray-700 pt-4">
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Email</label>
                            <p class="font-medium text-blue-600 dark:text-blue-400 mt-1 break-all text-sm">{{ $conversation->visitor_email }}</p>
                        </div>
                    @endif
                    @if($conversation->visitor_phone)
                        <div class="border-t border-slate-200 dark:border-gray-700 pt-4">
                            <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Phone</label>
                            <p class="font-semibold text-gray-900 dark:text-white mt-1">{{ $conversation->visitor_phone }}</p>
                        </div>
                    @endif
                    <div class="border-t border-slate-200 dark:border-gray-700 pt-4">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">IP Address</label>
                        <p class="font-mono text-gray-900 dark:text-white mt-1 text-xs bg-slate-50 dark:bg-gray-700 px-2 py-1 rounded">{{ $conversation->visitor_ip }}</p>
                    </div>
                    <div class="border-t border-slate-200 dark:border-gray-700 pt-4">
                        <label class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Started</label>
                        <p class="font-medium text-gray-900 dark:text-white mt-1">{{ $conversation->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>
            </div>

            <!-- Assign to Employee Card -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                    <h3 class="font-bold text-gray-900 dark:text-white">Assign Employee</h3>
                </div>
                <form id="assign-form" class="space-y-3">
                    <select id="employee_id" class="w-full px-4 py-2.5 border border-slate-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent dark:focus:ring-blue-400 font-medium transition-all">
                        <option value="">Select Employee</option>
                        @foreach($employees as $employee)
                            <option value="{{ $employee->id }}" {{ $conversation->assigned_to_employee_id === $employee->id ? 'selected' : '' }}>
                                {{ $employee->first_name }} {{ $employee->last_name }}
                            </option>
                        @endforeach
                    </select>
                    <button type="submit" class="w-full px-4 py-2.5 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-lg hover:from-emerald-600 hover:to-emerald-700 font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                        Assign
                    </button>
                </form>
            </div>

            <!-- Actions Card -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900 dark:to-red-800 rounded-xl shadow-lg p-6 border border-red-200 dark:border-red-700">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <h3 class="font-bold text-red-900 dark:text-red-200">Delete Conversation</h3>
                </div>
                <p class="text-sm text-red-800 dark:text-red-300 mb-4">This action cannot be undone.</p>
                <button onclick="deleteConversation()" class="w-full px-4 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 font-semibold shadow-md hover:shadow-lg transition-all duration-200">
                    Delete This Chat
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-scroll to bottom
    const messagesContainer = document.getElementById('messages-container');
    messagesContainer.scrollTop = messagesContainer.scrollHeight;

    // Auto-resize textarea
    const textarea = document.getElementById('reply-message');
    textarea.addEventListener('input', function() {
        this.style.height = '45px';
        this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // ====== FILE UPLOAD FUNCTIONALITY ======
    const fileInput = document.getElementById('file-input');
    const fileUploadBtn = document.getElementById('file-upload-btn');
    const attachmentsPreview = document.getElementById('attachments-preview');
    let selectedFiles = [];

    fileUploadBtn.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', (e) => {
        selectedFiles = Array.from(e.target.files);
        updateAttachmentsPreview();
    });

    function updateAttachmentsPreview() {
        attachmentsPreview.innerHTML = '';
        if (selectedFiles.length === 0) {
            attachmentsPreview.classList.add('hidden');
            return;
        }

        attachmentsPreview.classList.remove('hidden');
        selectedFiles.forEach((file, index) => {
            const chip = document.createElement('div');
            chip.className = 'flex items-center gap-2 bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 px-3 py-2 rounded-lg text-sm font-medium';
            chip.innerHTML = `
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 10H17a1 1 0 001-1v-3a1 1 0 00-1-1h-3z"></path></svg>
                <span class="truncate" title="${file.name}">${file.name}</span>
                <button type="button" class="ml-auto hover:text-red-600" onclick="removeFile(${index})">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                </button>
            `;
            attachmentsPreview.appendChild(chip);
        });
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        fileInput.value = '';
        updateAttachmentsPreview();
    }

    // ====== VOICE RECORDING FUNCTIONALITY ======
    let mediaRecorder = null;
    let audioChunks = [];
    let isRecording = false;
    const voiceRecordBtn = document.getElementById('voice-record-btn');

    voiceRecordBtn.addEventListener('click', async () => {
        if (!isRecording) {
            // Start recording
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];

                mediaRecorder.addEventListener('dataavailable', (event) => {
                    audioChunks.push(event.data);
                });

                mediaRecorder.addEventListener('stop', () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                    const file = new File([audioBlob], `voice-${Date.now()}.webm`, { type: 'audio/webm' });
                    selectedFiles.push(file);
                    updateAttachmentsPreview();
                    isRecording = false;
                    voiceRecordBtn.classList.remove('bg-red-200', 'dark:bg-red-800', 'text-red-600', 'dark:text-red-400');
                    voiceRecordBtn.classList.add('bg-slate-100', 'dark:bg-gray-700', 'text-slate-600', 'dark:text-slate-400');
                });

                mediaRecorder.start();
                isRecording = true;
                voiceRecordBtn.classList.remove('bg-slate-100', 'dark:bg-gray-700', 'text-slate-600', 'dark:text-slate-400');
                voiceRecordBtn.classList.add('bg-red-200', 'dark:bg-red-800', 'text-red-600', 'dark:text-red-400');
            } catch (error) {
                console.error('Microphone access denied:', error);
                alert('Microphone access is required for voice recording.');
            }
        } else {
            // Stop recording
            mediaRecorder.stop();
            mediaRecorder.stream.getTracks().forEach(track => track.stop());
        }
    });


    // Send reply
    document.getElementById('reply-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const messageText = document.getElementById('reply-message').value.trim();
        
        if (!messageText && selectedFiles.length === 0) return;

        const button = e.target.querySelector('button[type="submit"]');
        const originalHTML = button.innerHTML;
        button.disabled = true;

        // Optimistic UI update - show message immediately
        const now = new Date();
        const timeString = now.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });
        const messageDiv = document.createElement('div');
        messageDiv.className = 'flex justify-end animate-fadeIn';
        
        let attachmentsHTML = '';
        if (selectedFiles.length > 0) {
            attachmentsHTML = '<div class="flex flex-wrap gap-2 mb-2">';
            selectedFiles.forEach(file => {
                if (file.type.startsWith('image/')) {
                    attachmentsHTML += `
                        <div class="rounded-lg overflow-hidden max-w-xs">
                            <img src="${URL.createObjectURL(file)}" alt="attachment" class="max-h-48 rounded-lg">
                        </div>
                    `;
                } else if (file.type.startsWith('audio/')) {
                    attachmentsHTML += `
                        <div class="bg-slate-100 dark:bg-gray-600 px-3 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M9 4.804A7.968 7.968 0 005.5 4c-1.255 0-2.443.29-3.5.804v2.605A7.969 7.969 0 015.5 6c1.255 0 2.443.29 3.5.804V4.804z"></path><path d="M9 15.675V9.375c0-.563.448-1.028 1.006-1.028s1.006.465 1.006 1.028v6.3c0 .563-.448 1.028-1.006 1.028s-1.006-.465-1.006-1.028z"></path><path d="M15 9.375c0-.563.448-1.028 1.006-1.028s1.006.465 1.006 1.028v6.3c0 .563-.448 1.028-1.006 1.028s-1.006-.465-1.006-1.028V9.375z"></path><path d="M19 4.804A7.968 7.968 0 0015.5 4c-1.255 0-2.443.29-3.5.804v2.605A7.969 7.969 0 0115.5 6c1.255 0 2.443.29 3.5.804V4.804z"></path></svg>
                            <span class="text-sm font-medium truncate">${file.name}</span>
                        </div>
                    `;
                } else {
                    attachmentsHTML += `
                        <div class="bg-slate-100 dark:bg-gray-600 px-3 py-2 rounded-lg flex items-center gap-2">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 10H17a1 1 0 001-1v-3a1 1 0 00-1-1h-3z"></path></svg>
                            <span class="text-sm font-medium truncate">${file.name}</span>
                        </div>
                    `;
                }
            });
            attachmentsHTML += '</div>';
        }
        
        messageDiv.innerHTML = `
            <div class="max-w-sm">
                <div class="text-xs text-slate-500 dark:text-slate-400 mb-1.5 text-right">
                    You • ${timeString}
                </div>
                ${attachmentsHTML}
                ${messageText ? '<div class="px-4 py-3 rounded-2xl font-medium bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md">' + escapeHtml(messageText) + '</div>' : ''}
            </div>
        `;
        messagesContainer.appendChild(messageDiv);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
        document.getElementById('reply-message').value = '';
        textarea.style.height = '45px';

        try {
            // Use FormData for file uploads
            const formData = new FormData();
            formData.append('message', messageText);
            selectedFiles.forEach(file => {
                formData.append('attachments[]', file);
            });

            const response = await fetch('{{ route("admin.chatbot.send-reply", $conversation) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: formData,
            });

            if (!response.ok) {
                const errorData = await response.json().catch(() => ({}));
                console.error('Error sending message:', response.status, errorData);
                alert('Failed to send message: ' + (errorData.error || response.statusText));
                // Remove the optimistic message on error
                messagesContainer.removeChild(messageDiv);
            } else {
                const data = await response.json();
                console.log('Message sent successfully:', data);
                selectedFiles = [];
                updateAttachmentsPreview();
                
                // Update lastMessageId after successful send
                if (data.message_id) {
                    lastMessageId = Math.max(lastMessageId, data.message_id);
                }
            }
        } catch (error) {
            console.error('Error sending reply:', error);
            alert('Network error: ' + error.message);
            // Remove the optimistic message on error
            messagesContainer.removeChild(messageDiv);
        } finally {
            button.disabled = false;
            button.innerHTML = originalHTML;
        }
    });

    // Assign to employee
    document.getElementById('assign-form').addEventListener('submit', async (e) => {
        e.preventDefault();
        const employeeId = document.getElementById('employee_id').value;
        if (!employeeId) return;

        const button = e.target.querySelector('button[type="submit"]');
        button.disabled = true;
        const originalText = button.textContent;
        button.textContent = 'Assigning...';

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
                button.textContent = '✓ Assigned!';
                setTimeout(() => {
                    button.disabled = false;
                    button.textContent = originalText;
                }, 1500);
            }
        } catch (error) {
            console.error('Error assigning:', error);
            button.disabled = false;
            button.textContent = originalText;
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
        if (!confirm('Are you sure you want to delete this entire conversation? This cannot be undone.')) return;

        fetch('{{ route("admin.chatbot.destroy", $conversation) }}', {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        }).then(() => {
            window.location.href = '{{ route("admin.chatbot.index") }}';
        });
    }

    // Real-time updates via WebSocket (Pusher) with Polling Fallback
    const conversationId = {{ $conversation->id }};
    let lastMessageId = {{ $conversation->messages->last()?->id ?? 0 }};
    let pollingInterval = null;
    let lastSentMessage = null;
    let realtimeConnected = false;  // Track connection state
    let channelSubscription = null;   // Track subscription object
    
    console.log('🔌 Setting up real-time listener for conversation:', conversationId);
    
    // Polling fallback - only runs if real-time disconnects
    function startPolling() {
        if (pollingInterval) return; // Already polling
        
        console.log('📡 Starting polling fallback (real-time disconnected)...');
        realtimeConnected = false;
        
        pollingInterval = setInterval(() => {
            fetch('{{ route("admin.chatbot.get-messages", $conversation) }}', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.messages) {
                    data.messages.forEach(msg => {
                        // Display both visitor and employee messages that are newer than last loaded
                        if (msg.id > lastMessageId) {
                            console.log('📬 Polling: New message detected:', msg.sender_type, msg.message);
                            addMessageToUI(msg);
                            if (msg.sender_type === 'visitor') {
                                markAsRead(msg.id);
                            }
                            lastMessageId = msg.id;
                        }
                    });
                }
            })
            .catch(err => console.debug('Polling error:', err));
        }, 3000); // Increased from 1s to 3s since this is fallback
    }
    
    // Stop polling if real-time connects
    function stopPolling() {
        if (pollingInterval) {
            clearInterval(pollingInterval);
            pollingInterval = null;
            console.log('⏹️ Polling stopped - real-time connected');
        }
    }
    
    // Wait for Echo to be available (up to 5 seconds)
    let echoWaitCount = 0;
    const echoInterval = setInterval(() => {
        echoWaitCount++;
        console.log('⏳ Waiting for Echo... Attempt', echoWaitCount);
        
        if (window.Echo) {
            clearInterval(echoInterval);
            console.log('✅ Echo available! Broadcasting driver:', '{{ config("broadcasting.default") }}');
            subscribeToRealtimeUpdates();
        } else if (echoWaitCount > 50) {
            clearInterval(echoInterval);
            console.error('❌ Echo not available after 5 seconds, using polling fallback');
            startPolling(); // Start polling if Echo never loads
        }
    }, 100);
    
    function subscribeToRealtimeUpdates() {
        if (!window.Echo) {
            console.warn('⚠️ Echo not available, real-time disabled');
            startPolling(); // Fallback to polling
            return;
        }
        
        // Check if Echo has the channel method
        if (typeof Echo.channel !== 'function' && typeof Echo.private !== 'function') {
            console.warn('⚠️ Echo channel methods not available, real-time disabled');
            startPolling();
            return;
        }
        
        console.log('🔔 Subscribing to chat.conversation.' + conversationId);
        console.log('Echo driver:', '{{ config("broadcasting.default") }}');
        
        try {
            // Subscribe to public channel
            const channel = window.Echo.channel(`chat.conversation.${conversationId}`);
            
            // Listen for incoming messages
            channelSubscription = channel
                .listen('.ChatMessageReceived', (event) => {
                    try {
                        console.log('🔔 Real-time message received:', event);
                        
                        // Mark connection as active
                        if (!realtimeConnected) {
                            realtimeConnected = true;
                            stopPolling(); // Stop polling since real-time is working
                        }
                        
                        // Add visitor messages in real-time
                        if (event.sender_type === 'visitor') {
                            // Skip if duplicate of sent message
                            if (lastSentMessage && event.id === lastSentMessage.id) {
                                console.log('⏭️ Skipping duplicate:', event.id);
                                lastSentMessage = null;
                                return;
                            }
                            
                            console.log('➕ Adding visitor message:', event.message);
                            addMessageToUI(event);
                            markAsRead(event.id);
                            lastMessageId = Math.max(lastMessageId, event.id);
                        } else {
                            console.log('⏭️ Skipping employee message (optimistic update):', event.id);
                        }
                    } catch (err) {
                        console.error('❌ Error processing real-time event:', err);
                    }
                })
                .listen('.ConversationClosed', (event) => {
                    console.log('🔴 Conversation closed by admin');
                    // Handle conversation closure if needed
                })
                .error((error) => {
                    console.warn('⚠️ Channel error, falling back to polling:', error);
                    realtimeConnected = false;
                    // Don't immediately start polling, wait for a message to trigger retry
                });
            
            // Set connection status
            realtimeConnected = true;
            stopPolling();
            console.log('✅ Real-time listener registered successfully');
            
        } catch (error) {
            console.error('❌ Error subscribing to channel:', error);
            realtimeConnected = false;
            startPolling();
        }
    }

    // Monitor Echo connection state periodically
    setInterval(() => {
        if (window.Echo && window.Echo.connector) {
            const isConnected = window.Echo.connector.socket?.connected;
            if (!realtimeConnected && isConnected) {
                console.log('✅ Echo reconnected, stopping polling');
                stopPolling();
                realtimeConnected = true;
            } else if (realtimeConnected && !isConnected) {
                console.log('❌ Echo disconnected, starting polling');
                realtimeConnected = false;
                startPolling();
            }
        }
    }, 5000);
    
    // Message queue for batching DOM updates
    let messageQueue = [];
    let renderTimeout = null;
    const BATCH_DELAY = 50; // milliseconds - batches messages received within 50ms
    
    // Helper to render batch of messages efficiently
    function renderMessageBatch(messages) {
        const container = document.getElementById('messages-container');
        if (!container) return;
        
        // Create document fragment for efficient DOM insertion
        const fragment = document.createDocumentFragment();
        let shouldScroll = false;
        
        messages.forEach(message => {
            // Deduplicate at UI level
            if (document.getElementById(`message-${message.id}`)) {
                console.log('⏭️ Message already rendered:', message.id);
                return;
            }
            
            const isEmployee = message.sender_type === 'employee';
            const messageDiv = document.createElement('div');
            messageDiv.id = `message-${message.id}`;
            messageDiv.className = `flex ${isEmployee ? 'justify-end' : 'justify-start'} animate-fadeIn`;
            
            // Build attachments HTML
            let attachmentsHTML = '';
            if (message.attachments && message.attachments.length > 0) {
                attachmentsHTML = '<div class="flex flex-wrap gap-2 mb-2">';
                message.attachments.forEach(att => {
                    if (att.type.startsWith('image/')) {
                        attachmentsHTML += `
                            <div class="rounded-lg overflow-hidden max-w-xs">
                                <img src="${att.url}" alt="attachment" class="max-h-48 rounded-lg cursor-pointer hover:opacity-80" onclick="window.open('${att.url}', '_blank')">
                            </div>
                        `;
                    } else if (att.type.startsWith('audio/')) {
                        attachmentsHTML += `
                            <div class="bg-slate-100 dark:bg-gray-600 px-4 py-2 rounded-lg">
                                <audio controls class="max-w-xs">
                                    <source src="${att.url}" type="${att.type}">
                                    Your browser does not support the audio element.
                                </audio>
                            </div>
                        `;
                    } else {
                        attachmentsHTML += `
                            <a href="${att.url}" target="_blank" class="bg-slate-100 dark:bg-gray-600 px-3 py-2 rounded-lg flex items-center gap-2 hover:bg-slate-200 dark:hover:bg-gray-500 transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path d="M8 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15 16.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"></path><path d="M3 4a1 1 0 00-1 1v10a1 1 0 001 1h1.05a2.5 2.5 0 014.9 0H10a1 1 0 001-1V5a1 1 0 00-1-1H3zM14 7a1 1 0 00-1 1v6.05A2.5 2.5 0 0115.95 10H17a1 1 0 001-1v-3a1 1 0 00-1-1h-3z"></path></svg>
                                <span class="text-sm font-medium truncate max-w-xs" title="${att.name}">${att.name}</span>
                            </a>
                        `;
                    }
                });
                attachmentsHTML += '</div>';
            }
            
            messageDiv.innerHTML = `
                <div class="max-w-sm">
                    <div class="text-xs text-slate-500 dark:text-slate-400 mb-1.5 ${isEmployee ? 'text-right' : ''}">
                        ${message.sender_name || 'Support'} • ${new Date(message.created_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' })}
                    </div>
                    ${attachmentsHTML}
                    ${message.message ? `<div class="px-4 py-3 rounded-2xl font-medium break-words ${isEmployee ? 'bg-gradient-to-br from-blue-500 to-blue-600 text-white shadow-md' : 'bg-slate-100 dark:bg-gray-700 text-gray-900 dark:text-white shadow-sm'}">
                        ${escapeHtml(message.message)}
                    </div>` : ''}
                </div>
            `;
            
            fragment.appendChild(messageDiv);
            shouldScroll = true;
        });
        
        // Append all at once (single reflow instead of multiple)
        if (fragment.childNodes.length > 0) {
            container.appendChild(fragment);
            console.log(`✅ Batch rendered ${fragment.childNodes.length} messages`);
            
            // Scroll once after batch render
            if (shouldScroll) {
                requestAnimationFrame(() => {
                    container.scrollTop = container.scrollHeight;
                });
            }
        }
    }
    
    // Queue message for batched rendering
    function addMessageToUI(message) {
        // Deduplicate before queueing
        if (document.getElementById(`message-${message.id}`)) {
            return;
        }
        
        // Add to queue
        messageQueue.push(message);
        
        // Clear existing timeout
        if (renderTimeout) clearTimeout(renderTimeout);
        
        // Batch render after BATCH_DELAY
        renderTimeout = setTimeout(() => {
            if (messageQueue.length > 0) {
                const batch = messageQueue.splice(0);
                renderMessageBatch(batch);
            }
            renderTimeout = null;
        }, BATCH_DELAY);
    }
    
    // Mark message as read
    // Batch mark as read calls
    let readQueue = [];
    let readTimeout = null;
    
    function markAsRead(messageId) {
        readQueue.push(messageId);
        
        // Clear existing timeout
        if (readTimeout) clearTimeout(readTimeout);
        
        // Batch mark-as-read calls after 100ms
        readTimeout = setTimeout(() => {
            if (readQueue.length === 0) return;
            
            const ids = readQueue.splice(0);
            
            // Deduplicate IDs
            const uniqueIds = [...new Set(ids)];
            
            fetch('{{ route("admin.chatbot.mark-read", $conversation) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({
                    message_ids: uniqueIds,
                    conversation_id: conversationId,
                }),
            }).catch(err => console.debug('Mark read error:', err));
            
            readTimeout = null;
        }, 100);
    }
    
    // Update conversation last message time
    function updateConversationTime() {
        // Update the last_message_at in database
        fetch('{{ route("admin.chatbot.update-time", $conversation) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
            },
        }).catch(err => console.debug('Update time error:', err));
    }
    
    // Escape HTML
    function escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Attachment Preview Functions
    function openAttachmentPreview(url, fileName, type) {
        const modal = document.getElementById('attachmentModal');
        const fileNameEl = document.getElementById('attachmentFileName');
        const downloadBtn = document.getElementById('attachmentDownloadBtn');
        
        // Set file name and download button
        fileNameEl.textContent = fileName;
        downloadBtn.href = url;
        downloadBtn.download = fileName;
        
        // Hide all preview elements
        document.getElementById('previewImage').classList.add('hidden');
        document.getElementById('previewPdf').classList.add('hidden');
        document.getElementById('previewAudio').classList.add('hidden');
        document.getElementById('previewVideo').classList.add('hidden');
        document.getElementById('previewUnsupported').classList.add('hidden');
        
        // Show appropriate preview based on type
        if (type === 'image') {
            const img = document.getElementById('previewImage');
            img.src = url;
            img.classList.remove('hidden');
        } else if (type === 'pdf') {
            const iframe = document.getElementById('previewPdf');
            iframe.src = url;
            iframe.classList.remove('hidden');
        } else if (type === 'audio') {
            const audio = document.getElementById('previewAudio');
            audio.src = url;
            audio.classList.remove('hidden');
        } else if (type === 'video') {
            const video = document.getElementById('previewVideo');
            video.src = url;
            video.classList.remove('hidden');
        } else {
            document.getElementById('previewUnsupported').classList.remove('hidden');
        }
        
        // Show modal
        modal.classList.remove('hidden');
    }

    function closeAttachmentModal(event) {
        // Close if clicking overlay or close button
        if (!event || event.target.id === 'attachmentModal') {
            document.getElementById('attachmentModal').classList.add('hidden');
            // Clear sources
            document.getElementById('previewImage').src = '';
            document.getElementById('previewPdf').src = '';
            document.getElementById('previewAudio').src = '';
            document.getElementById('previewVideo').src = '';
        }
    }

    // Wire up old attachment links to new modal system
    document.addEventListener('DOMContentLoaded', function() {
        // Convert all old-style file links to use the modal
        document.querySelectorAll('.chatbot-file-link').forEach(link => {
            const url = link.getAttribute('href');
            const fileName = link.textContent.trim();
            const fileExt = fileName.split('.').pop().toLowerCase();
            
            // Determine type
            let type = 'file';
            if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) type = 'image';
            else if (['mp3', 'wav', 'ogg', 'm4a'].includes(fileExt)) type = 'audio';
            else if (['pdf'].includes(fileExt)) type = 'pdf';
            else if (['mp4', 'webm', 'mov', 'avi'].includes(fileExt)) type = 'video';
            
            // Replace with onclick handler
            link.onclick = (e) => {
                e.preventDefault();
                openAttachmentPreview(url, fileName, type);
            };
            link.style.cursor = 'pointer';
            link.removeAttribute('target');
            link.removeAttribute('download');
        });
    });

</script>
</x-app-layout>
