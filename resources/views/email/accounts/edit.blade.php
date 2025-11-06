<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Edit Email Account') }}
            </h2>
            <a href="{{ route('email.accounts.index') }}" class="inline-flex items-center px-6 py-3 bg-gray-900 text-white rounded-full font-semibold text-sm hover:bg-gray-800 border border-gray-800 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Accounts
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('email.accounts.update', $account) }}" method="POST" class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-8">
                @csrf
                @method('PUT')

                <div class="grid gap-6 mb-6">
                    <!-- Account Name -->
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Account Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $account->name) }}" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-black dark:focus:border-white transition">
                        @error('name')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Email Address</label>
                        <input type="email" name="email" id="email" value="{{ $account->email }}" disabled
                            class="w-full px-4 py-3 bg-gray-900 border border-gray-800 rounded-2xl text-gray-500 cursor-not-allowed">
                        <p class="mt-1 text-xs text-gray-500">Email address cannot be changed</p>
                    </div>

                    <!-- Protocol -->
                    <div>
                        <label for="protocol" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Protocol</label>
                        <select name="protocol" id="protocol" required
                            class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white focus:outline-none focus:border-black dark:focus:border-white transition">
                            <option value="imap" {{ old('protocol') == 'imap' ? 'selected' : '' }}>IMAP</option>
                            <option value="pop3" {{ old('protocol') == 'pop3' ? 'selected' : '' }}>POP3</option>
                        </select>
                        @error('protocol')
                            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Incoming Mail Settings -->
                <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"></path>
                        </svg>
                        Incoming Mail Settings (IMAP/POP3)
                    </h3>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="imap_host" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Host</label>
                            <input type="text" name="imap_host" id="imap_host" value="{{ old('imap_host') }}" required
                                placeholder="imap.gmail.com"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('imap_host')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="imap_port" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Port</label>
                            <input type="number" name="imap_port" id="imap_port" value="{{ old('imap_port', 993) }}" required
                                placeholder="993"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('imap_port')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="imap_encryption" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Encryption</label>
                            <select name="imap_encryption" id="imap_encryption"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-white focus:outline-none focus:border-white transition">
                                <option value="">None</option>
                                <option value="ssl" {{ old('imap_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ old('imap_encryption', 'ssl') == 'tls' ? 'selected' : '' }}>TLS</option>
                            </select>
                            @error('imap_encryption')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="imap_username" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Username</label>
                            <input type="text" name="imap_username" id="imap_username" value="{{ old('imap_username') }}" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('imap_username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="imap_password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Password</label>
                            <input type="password" name="imap_password" id="imap_password" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('imap_password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Outgoing Mail Settings -->
                <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Outgoing Mail Settings (SMTP)
                    </h3>

                    <div class="grid gap-6 md:grid-cols-2">
                        <div>
                            <label for="smtp_host" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Host</label>
                            <input type="text" name="smtp_host" id="smtp_host" value="{{ old('smtp_host') }}" required
                                placeholder="smtp.gmail.com"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('smtp_host')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="smtp_port" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Port</label>
                            <input type="number" name="smtp_port" id="smtp_port" value="{{ old('smtp_port', 587) }}" required
                                placeholder="587"
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('smtp_port')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="smtp_encryption" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Encryption</label>
                            <select name="smtp_encryption" id="smtp_encryption"
                                class="w-full px-4 py-3 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl text-white focus:outline-none focus:border-white transition">
                                <option value="">None</option>
                                <option value="ssl" {{ old('smtp_encryption') == 'ssl' ? 'selected' : '' }}>SSL</option>
                                <option value="tls" {{ old('smtp_encryption', 'tls') == 'tls' ? 'selected' : '' }}>TLS</option>
                            </select>
                            @error('smtp_encryption')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="smtp_username" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Username</label>
                            <input type="text" name="smtp_username" id="smtp_username" value="{{ old('smtp_username') }}" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('smtp_username')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="smtp_password" class="block text-sm font-semibold text-gray-900 dark:text-white mb-2">Password</label>
                            <input type="password" name="smtp_password" id="smtp_password" required
                                class="w-full px-4 py-3 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-2xl text-gray-900 dark:text-white placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:border-white transition">
                            @error('smtp_password')
                                <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Settings -->
                <div class="mb-8 p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-700">
                    <label class="flex items-center">
                        <input type="checkbox" name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}
                            class="w-5 h-5 bg-black border-gray-800 rounded text-white focus:ring-0 focus:ring-offset-0">
                        <span class="ml-3 text-white font-semibold">Set as default account</span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 inline-flex items-center justify-center px-8 py-4 bg-black text-white rounded-full font-bold hover:bg-gray-800 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Update Account
                    </button>
                    <a href="{{ route('email.accounts.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-bold hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
