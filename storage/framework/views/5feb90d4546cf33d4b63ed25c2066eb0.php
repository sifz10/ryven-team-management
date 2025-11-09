<!-- Sidebar Overlay for Mobile -->
<div x-show="sidebarOpen"
     @click="sidebarOpen = false"
     x-cloak
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 lg:hidden">
</div>

<!-- Sidebar -->
<aside
    class="fixed inset-y-0 left-0 z-50 flex flex-col transition-all duration-300 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-lg"
    :class="{
        'w-64': !sidebarCollapsed,
        'w-20': sidebarCollapsed,
        '-translate-x-full lg:translate-x-0': !sidebarOpen,
        'translate-x-0': sidebarOpen
    }">

    <!-- Logo Section -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-200 dark:border-gray-700">
        <!-- Logo -->
        <a href="<?php echo e(route('client.dashboard')); ?>" class="flex items-center justify-center overflow-hidden">
            <img x-show="!sidebarCollapsed"
                 x-cloak
                 src="<?php echo e(asset('black-logo.png')); ?>"
                 alt="Logo"
                 class="h-10 w-auto dark:invert transition-opacity duration-200">
            <img x-show="sidebarCollapsed"
                 x-cloak
                 src="<?php echo e(asset('favicon.png')); ?>"
                 alt="Logo"
                 class="h-10 w-10 flex-shrink-0 transition-opacity duration-200">
        </a>

        <!-- Close Button (Mobile Only) -->
        <button @click="sidebarOpen = false"
                class="lg:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1 scrollbar-hide">
        <!-- Dashboard -->
        <a href="<?php echo e(route('client.dashboard')); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all group <?php echo e(request()->routeIs('client.dashboard') ? 'bg-black dark:bg-white text-white dark:text-black' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="font-medium">Dashboard</span>
        </a>

        <!-- Projects -->
        <a href="<?php echo e(route('client.projects.index')); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all group <?php echo e(request()->routeIs('client.projects.*') ? 'bg-black dark:bg-white text-white dark:text-black' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="font-medium">Projects</span>
        </a>

        <!-- Team Members -->
        <a href="<?php echo e(route('client.team.index')); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all group <?php echo e(request()->routeIs('client.team.*') ? 'bg-black dark:bg-white text-white dark:text-black' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="font-medium">Team</span>
        </a>

        <!-- Invoices -->
        <a href="<?php echo e(route('client.invoices.index')); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all group <?php echo e(request()->routeIs('client.invoices.*') ? 'bg-black dark:bg-white text-white dark:text-black' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="font-medium">Invoices</span>
        </a>

        <!-- Tickets/Support -->
        <a href="<?php echo e(route('client.tickets.index')); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all group <?php echo e(request()->routeIs('client.tickets.*') ? 'bg-black dark:bg-white text-white dark:text-black' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="font-medium">Support</span>
        </a>

        <!-- Divider -->
        <div class="my-4 border-t border-gray-200 dark:border-gray-700"></div>

        <!-- Settings Section -->
        <div x-show="!sidebarCollapsed" x-cloak class="px-3 mb-2">
            <p class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Settings</p>
        </div>

        <!-- Profile -->
        <a href="<?php echo e(route('client.profile')); ?>"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all group <?php echo e(request()->routeIs('client.profile') ? 'bg-black dark:bg-white text-white dark:text-black' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700'); ?>">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="font-medium">Profile</span>
        </a>
    </nav>

    <!-- Collapse Toggle Button (Desktop) -->
    <div class="hidden lg:block p-3 border-t border-gray-200 dark:border-gray-700">
        <button @click="toggleSidebar()"
                class="w-full flex items-center justify-center gap-2 px-3 py-2.5 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all">
            <svg x-show="!sidebarCollapsed"
                 x-cloak
                 class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <svg x-show="sidebarCollapsed"
                 x-cloak
                 class="w-5 h-5"
                 fill="none"
                 stroke="currentColor"
                 viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7"/>
            </svg>
            <span x-show="!sidebarCollapsed" x-cloak class="text-sm font-medium">Collapse</span>
        </button>
    </div>
</aside>
<?php /**PATH F:\Project\salary\resources\views/layouts/client-sidebar.blade.php ENDPATH**/ ?>