<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-white leading-tight">
                {{ __('Email Accounts') }}
            </h2>
            <a href="{{ route('email.accounts.create') }}" class="inline-flex items-center px-6 py-3 bg-white text-black rounded-full font-semibold text-sm hover:bg-gray-200 transition">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Email Account
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 bg-green-600 text-white p-4 rounded-2xl">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-600 text-white p-4 rounded-2xl">
                    {{ session('error') }}
                </div>
            @endif

            @if($accounts->isEmpty())
                <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-12 text-center">
                    <svg class="w-24 h-24 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">No Email Accounts</h3>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">Add your first email account to start managing your emails</p>
                    <a href="{{ route('email.accounts.create') }}" class="inline-flex items-center px-8 py-4 bg-black dark:bg-black text-white rounded-full font-semibold hover:bg-gray-800 dark:hover:bg-gray-900 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Your First Account
                    </a>
                </div>
            @else
                <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($accounts as $account)
                        <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6 hover:border-gray-300 dark:hover:border-gray-600 transition">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2 mb-2">
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">{{ $account->name }}</h3>
                                        @if($account->is_default)
                                            <span class="px-3 py-1 bg-black text-white text-xs font-semibold rounded-full">Default</span>
                                        @endif
                                    </div>
                                    <p class="text-gray-600 dark:text-gray-400 text-sm">{{ $account->email }}</p>
                                </div>
                                <div class="ml-4">
                                    @if($account->is_active)
                                        <span class="flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-green-500"></span>
                                        </span>
                                    @else
                                        <span class="inline-flex h-3 w-3 rounded-full bg-gray-600"></span>
                                    @endif
                                </div>
                            </div>

                            <div class="space-y-3 mb-4">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400 w-24">Protocol:</span>
                                    <span class="text-gray-900 dark:text-white font-semibold uppercase">{{ $account->protocol }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400 w-24">Unread:</span>
                                    <span class="text-gray-900 dark:text-white font-bold">{{ $account->unread_messages_count }}</span>
                                </div>
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 dark:text-gray-400 w-24">Last Sync:</span>
                                    <span class="text-gray-600 dark:text-gray-400">{{ $account->last_synced_at ? $account->last_synced_at->diffForHumans() : 'Never' }}</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                                <form action="{{ route('email.accounts.sync', $account) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-black text-white rounded-full font-semibold text-sm hover:bg-gray-800 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Sync
                                    </button>
                                </form>

                                <form action="{{ route('email.accounts.test', $account) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-full font-semibold text-sm hover:bg-blue-700 transition">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Test
                                    </button>
                                </form>

                                <a href="{{ route('email.accounts.edit', $account) }}" class="inline-flex items-center justify-center px-4 py-2 bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white rounded-full font-semibold text-sm hover:bg-gray-200 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>

                                <form action="{{ route('email.accounts.destroy', $account) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this email account?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-red-100 dark:bg-red-900 text-red-900 dark:text-white rounded-full font-semibold text-sm hover:bg-red-200 dark:hover:bg-red-800 border border-red-200 dark:border-red-800 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-3xl p-6">
                    <a href="{{ route('email.inbox.index') }}" class="inline-flex items-center px-8 py-4 bg-black text-white rounded-full font-semibold hover:bg-gray-800 transition w-full justify-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Go to Inbox
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
