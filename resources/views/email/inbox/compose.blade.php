<x-app-layout>
    <div class="min-h-screen bg-gray-50 dark:bg-gray-900 py-12">
        <div class="max-w-5xl mx-auto px-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-8">Compose Email</h1>

            <form action="{{ route('email.inbox.send') }}" method="POST" enctype="multipart/form-data" 
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-8">
                @csrf

                <!-- Account Selection -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">From</label>
                    <select name="account_id" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:border-white transition">
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ $account->id == $defaultAccount?->id ? 'selected' : '' }}>
                                {{ $account->name }} ({{ $account->email }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- To -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">To</label>
                    <input type="text" name="to" value="{{ old('to') }}" required
                        placeholder="recipient@example.com"
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition">
                    <p class="mt-1 text-xs text-gray-500">Separate multiple recipients with commas</p>
                    @error('to')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- CC/BCC -->
                <div x-data="{ showCcBcc: false }" class="mb-6">
                    <button type="button" @click="showCcBcc = !showCcBcc" 
                        class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white transition">
                        + Add Cc/Bcc
                    </button>

                    <div x-show="showCcBcc" x-cloak class="grid md:grid-cols-2 gap-4 mt-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Cc</label>
                            <input type="text" name="cc" value="{{ old('cc') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Bcc</label>
                            <input type="text" name="bcc" value="{{ old('bcc') }}"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition">
                        </div>
                    </div>
                </div>

                <!-- Subject -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Subject</label>
                    <input type="text" name="subject" value="{{ old('subject') }}" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition">
                    @error('subject')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Body -->
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Message</label>
                    <textarea name="body" rows="12" required
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition resize-none">{{ old('body') }}</textarea>
                    @error('body')
                        <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Attachments -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Attachments</label>
                    <input type="file" name="attachments[]" multiple
                        class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-white file:text-black hover:file:bg-gray-200">
                    <p class="mt-1 text-xs text-gray-500">Maximum file size: 10MB per file</p>
                </div>

                <!-- Actions -->
                <div class="flex gap-4">
                    <button type="submit" 
                        class="inline-flex items-center px-8 py-4 bg-black text-white rounded-full font-bold hover:bg-gray-800 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Send Email
                    </button>
                    <a href="{{ route('email.inbox.index') }}" 
                        class="inline-flex items-center px-8 py-4 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-bold hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
