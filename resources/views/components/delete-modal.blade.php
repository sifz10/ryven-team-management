@props(['id' => 'deleteModal'])

<!-- Delete Confirmation Modal -->
<div x-data="{ show: false, form: null }"
     @open-delete-modal.window="if ($event.detail.id === '{{ $id }}') { show = true; form = $event.detail.form; }"
     x-show="show"
     x-cloak
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;">

    <!-- Background Overlay -->
    <div x-show="show"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="show = false"
         class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm"></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <!-- Modal Content -->
        <div x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             @click.stop
             class="relative bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden">

            <!-- Icon Section -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-red-900/20 dark:to-red-800/20 p-6 text-center">
                <div class="mx-auto w-16 h-16 bg-red-100 dark:bg-red-900/40 rounded-full flex items-center justify-center mb-4 animate-pulse">
                    <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white">
                    {{ $title ?? 'Confirm Delete' }}
                </h3>
            </div>

            <!-- Message Section -->
            <div class="p-6">
                <p class="text-gray-600 dark:text-gray-300 text-center mb-6">
                    {{ $message ?? 'Are you sure you want to delete this item? This action can be undone from the Deleted Users tab.' }}
                </p>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3">
                    <button @click="show = false"
                            type="button"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-full hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200 font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        Cancel
                    </button>
                    <button @click="if (form) { form.submit(); } show = false;"
                            type="button"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-2.5 bg-red-600 dark:bg-red-600 text-white rounded-full hover:bg-red-700 dark:hover:bg-red-700 transition-all duration-200 font-medium shadow-lg shadow-red-600/30">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
