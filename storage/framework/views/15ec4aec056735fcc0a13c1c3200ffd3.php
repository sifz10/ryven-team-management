<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

        <title><?php echo e(config('app.name', 'Laravel')); ?> - Client Portal</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">
        <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('favicon.png')); ?>">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>

        <?php echo $__env->yieldPushContent('styles'); ?>
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900"
          x-data="{
              sidebarOpen: false,
              sidebarCollapsed: localStorage.getItem('clientSidebarCollapsed') === 'true',
              toggleSidebar() {
                  this.sidebarCollapsed = !this.sidebarCollapsed;
                  localStorage.setItem('clientSidebarCollapsed', this.sidebarCollapsed);
              }
          }"
          x-init="document.documentElement.classList.toggle('dark', localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches))">

        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <?php echo $__env->make('layouts.client-sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen" :class="{'lg:ml-64': !sidebarCollapsed, 'lg:ml-20': sidebarCollapsed}">
                <!-- Top Navigation Bar -->
                <?php echo $__env->make('layouts.client-topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Page Heading -->
                <?php if(isset($header)): ?>
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                            <?php echo e($header); ?>

                        </div>
                    </header>
                <?php endif; ?>

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
                    <?php echo e($slot); ?>

                </main>
            </div>
        </div>

        <?php echo $__env->yieldPushContent('scripts'); ?>
    </body>
</html>
<?php /**PATH F:\Project\salary\resources\views/layouts/client-app.blade.php ENDPATH**/ ?>