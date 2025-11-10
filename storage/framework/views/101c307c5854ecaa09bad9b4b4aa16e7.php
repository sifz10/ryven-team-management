<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?></title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    </head>
    <body class="font-sans antialiased" x-data x-init="document.documentElement.classList.toggle('dark', localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches))">
        <!-- Background with animated gradient -->
        <div class="min-h-screen relative overflow-hidden bg-gradient-to-br from-gray-50 via-gray-100 to-gray-200 dark:from-gray-900 dark:via-gray-800 dark:to-black">
            <!-- Animated background shapes -->
            <div class="absolute inset-0 overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-500/10 dark:bg-green-500/5 rounded-full blur-3xl animate-pulse"></div>
                <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-gray-900/5 dark:bg-gray-100/5 rounded-full blur-3xl animate-pulse" style="animation-delay: 1s;"></div>
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-green-500/5 dark:bg-green-500/3 rounded-full blur-3xl animate-pulse" style="animation-delay: 2s;"></div>
            </div>

            <!-- Content -->
            <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 py-12 sm:px-6 lg:px-8">
                <!-- Logo -->
                <div class="mb-8 transform hover:scale-105 transition-transform duration-300">
                    <a href="/" class="flex flex-col items-center group">
                        <img src="<?php echo e(asset('black-logo.png')); ?>" alt="RYAYEN Logo" class="h-14 w-auto mb-3 dark:filter dark:invert">
                        <div class="flex items-center space-x-2">
                            <div class="h-px w-8 bg-gray-400 dark:bg-gray-600 group-hover:w-12 transition-all duration-300"></div>
                            <span class="text-xs font-medium text-gray-600 dark:text-gray-400 uppercase tracking-widest">RTM Portal</span>
                            <div class="h-px w-8 bg-gray-400 dark:bg-gray-600 group-hover:w-12 transition-all duration-300"></div>
                        </div>
                    </a>
                </div>

                <!-- Card -->
                <div class="w-full max-w-md">
                    <div class="backdrop-blur-xl bg-white/80 dark:bg-gray-900/80 shadow-2xl rounded-3xl p-8 sm:p-10 border border-gray-200/50 dark:border-gray-700/50 transform hover:scale-[1.01] transition-all duration-300">
                        <?php echo e($slot); ?>

                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 text-center space-y-3">
                        <div class="flex items-center justify-center space-x-6 text-xs text-gray-600 dark:text-gray-400">
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M2.166 4.999A11.954 11.954 0 0010 1.944 11.954 11.954 0 0017.834 5c.11.65.166 1.32.166 2.001 0 5.225-3.34 9.67-8 11.317C5.34 16.67 2 12.225 2 7c0-.682.057-1.35.166-2.001zm11.541 3.708a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Secure Access</span>
                            </div>
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                                </svg>
                                <span>End-to-End Encrypted</span>
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-500">
                            &copy; <?php echo e(date('Y')); ?> RYAYEN.CO. All rights reserved.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
<?php /**PATH F:\Project\salary\resources\views/layouts/guest.blade.php ENDPATH**/ ?>