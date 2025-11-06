<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-5xl mx-auto px-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Reply to Email</h1>
            <p class="text-gray-600 dark:text-gray-400 mb-8">{{ $message->subject }}</p>

            <form action="{{ route('email.inbox.send-reply', $message) }}" method="POST" enctype="multipart/form-data" 
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-8">
                @csrf

                <div class="mb-6">
                    <select name="account_id" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:border-white transition">
                        @foreach($accounts as $acc)
                            <option value="{{ $acc->id }}" {{ $acc->id == $account->id ? 'selected' : '' }}>
                                {{ $acc->name }} ({{ $acc->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-6">
                    <textarea name="body" rows="12" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition resize-none">{{ old('body') }}</textarea>
                </div>

                <div class="mb-8">
                    <input type="file" name="attachments[]" multiple
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-gray-200">
                </div>

                <div class="flex gap-4">
                    <button type="submit" 
                        class="inline-flex items-center px-8 py-4 bg-black text-white rounded-full font-bold hover:bg-gray-800 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path>
                        </svg>
                        Send Reply
                    </button>
                    <a href="{{ route('email.inbox.show', $message) }}" 
                        class="inline-flex items-center px-8 py-4 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-bold hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition">
                        Cancel
                    </a>
                </div>
            </form>

            <!-- Original Message -->
            <div class="mt-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-8">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Original Message</h3>
                <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2 mb-6">
                    <p><strong class="text-gray-900 dark:text-white">From:</strong> {{ $message->from_name }} &lt;{{ $message->from_email }}&gt;</p>
                    <p><strong class="text-gray-900 dark:text-white">Date:</strong> {{ $message->sent_at->format('M d, Y \a\t g:i A') }}</p>
                    <p><strong class="text-gray-900 dark:text-white">Subject:</strong> {{ $message->subject }}</p>
                </div>
                <div class="prose prose-invert max-w-none text-gray-600 dark:text-gray-500">
                    {!! $message->body_html ?: nl2br(e($message->body_text)) !!}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
