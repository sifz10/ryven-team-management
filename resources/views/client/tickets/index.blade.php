<x-client-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                    {{ __('Support') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Get help and track your support tickets</p>
            </div>
            <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                New Ticket
            </button>
        </div>
    </x-slot>

    <div class="space-y-6">
        <!-- Tickets List -->
        <div class="space-y-4">
            <!-- Sample Ticket -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm hover:shadow-md transition-shadow">
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">
                                Need help with project setup
                            </h3>
                            <span class="px-3 py-1 bg-blue-100 dark:bg-blue-900/20 text-blue-800 dark:text-blue-300 text-xs font-medium rounded-full">
                                Open
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            I'm having trouble setting up the development environment for the new project...
                        </p>
                    </div>
                </div>
                <div class="flex items-center justify-between pt-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center gap-4 text-sm text-gray-600 dark:text-gray-400">
                        <span>Ticket #1234</span>
                        <span>•</span>
                        <span>Created 2 hours ago</span>
                    </div>
                    <button class="inline-flex items-center gap-2 px-4 py-2 text-black dark:text-white font-medium rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all text-sm">
                        View Details
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Empty State -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-12 shadow-sm text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Support Tickets</h3>
                <p class="text-gray-600 dark:text-gray-400 mb-4">
                    You haven't created any support tickets yet. Need help? Create a new ticket!
                </p>
                <button class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Create Your First Ticket
                </button>
            </div>
        </div>
    </div>
</x-client-app-layout>
