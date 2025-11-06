<!-- Top Navigation Bar -->
<header class="sticky top-0 z-30 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 shadow-sm"
        x-data="{ 
            toggleTheme() {
                const isDark = document.documentElement.classList.toggle('dark');
                localStorage.theme = isDark ? 'dark' : 'light';
            }
        }">
    <div class="flex items-center justify-between h-16 px-4 sm:px-6 lg:px-8">
        <!-- Left Section: Mobile Menu Button + Page Title -->
        <div class="flex items-center gap-4">
            <!-- Mobile Hamburger -->
            <button @click="sidebarOpen = !sidebarOpen" 
                    class="lg:hidden inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <!-- Page Title/Breadcrumb -->
            <div class="hidden sm:block">
                <?php if(isset($header)): ?>
                    <!-- If header slot is used, show a simple page indicator -->
                <?php else: ?>
                    <h1 class="text-xl font-semibold text-gray-900 dark:text-white">
                        <?php echo $__env->yieldContent('page-title', 'Dashboard'); ?>
                    </h1>
                <?php endif; ?>
            </div>
        </div>

        <!-- Right Section: Actions, Theme Toggle, Notifications, Profile -->
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Theme Toggle -->
            <button @click="toggleTheme()" 
                    class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-white dark:hover:bg-gray-700 focus:outline-none transition">
                <!-- Sun Icon (Light Mode) -->
                <svg x-show="!document.documentElement.classList.contains('dark')" 
                     class="w-5 h-5" 
                     fill="none" 
                     stroke="currentColor" 
                     viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v2m0 14v2m9-9h-2M5 12H3m15.364 6.364l-1.414-1.414M7.05 7.05 5.636 5.636m12.728 0-1.414 1.414M7.05 16.95l-1.414 1.414M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <!-- Moon Icon (Dark Mode) -->
                <svg x-show="document.documentElement.classList.contains('dark')" 
                     class="w-5 h-5" 
                     fill="currentColor" 
                     viewBox="0 0 24 24">
                    <path d="M21.64 13.64A9 9 0 1110.36 2.36a7 7 0 1011.28 11.28z"/>
                </svg>
            </button>

            <!-- Notifications Dropdown -->
            <?php echo $__env->make('components.notifications-dropdown', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Profile Dropdown -->
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" 
                        class="flex items-center gap-2 p-1.5 sm:px-3 sm:py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition">
                    <!-- Avatar -->
                    <div class="w-8 h-8 rounded-full bg-black dark:bg-white flex items-center justify-center">
                        <span class="text-white dark:text-black font-semibold text-sm">
                            <?php echo e(strtoupper(substr(Auth::user()->name, 0, 1))); ?>

                        </span>
                    </div>
                    <!-- Name (Hidden on mobile) -->
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium"><?php echo e(Auth::user()->name); ?></p>
                    </div>
                    <!-- Dropdown Icon -->
                    <svg class="hidden sm:block w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" 
                     @click.away="open = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="absolute right-0 mt-2 w-56 rounded-lg shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 z-50"
                     style="display: none;">
                    
                    <!-- User Info Section -->
                    <div class="px-4 py-3">
                        <p class="text-sm font-medium text-gray-900 dark:text-white"><?php echo e(Auth::user()->name); ?></p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 truncate"><?php echo e(Auth::user()->email); ?></p>
                    </div>

                    <!-- Menu Items -->
                    <div class="py-1">
                        <a href="<?php echo e(route('profile.edit')); ?>" 
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            Profile Settings
                        </a>
                        <a href="<?php echo e(route('dashboard')); ?>" 
                           class="flex items-center gap-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                            Dashboard
                        </a>
                    </div>

                    <!-- Logout Section -->
                    <div class="py-1">
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" 
                                    class="flex items-center gap-3 w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<?php /**PATH F:\Project\salary\resources\views/layouts/topbar.blade.php ENDPATH**/ ?>