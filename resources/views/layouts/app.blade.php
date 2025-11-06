<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900" 
          x-data="{ 
              sidebarOpen: false,
              sidebarCollapsed: localStorage.getItem('sidebarCollapsed') === 'true',
              toggleSidebar() {
                  this.sidebarCollapsed = !this.sidebarCollapsed;
                  localStorage.setItem('sidebarCollapsed', this.sidebarCollapsed);
              }
          }" 
          x-init="document.documentElement.classList.toggle('dark', localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches))">
        
        <div class="min-h-screen flex">
            <!-- Sidebar -->
            @include('layouts.sidebar')
            
            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col min-h-screen" :class="{'lg:ml-64': !sidebarCollapsed, 'lg:ml-20': sidebarCollapsed}">
                <!-- Top Navigation Bar -->
                @include('layouts.topbar')
                
                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="mx-auto py-4 sm:py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-x-hidden overflow-y-auto p-4 sm:p-6 lg:p-8">
                    {{ $slot }}
                </main>
            </div>
        </div>
        <div class="fixed bottom-6 right-6 z-50" x-data>
            @if (session('status'))
                <x-toast type="success" :message="session('status')" />
            @endif
            @if (session('error'))
                <div class="mt-2">
                    <x-toast type="error" :message="session('error')" />
                </div>
            @endif
        </div>
        
        @stack('scripts')
        
        <script>
            // Global real-time email listener for navigation badge
            document.addEventListener('DOMContentLoaded', function() {
                @auth
                Echo.private('user.{{ auth()->id() }}')
                    .listen('.email.new', (e) => {
                        // Update unread badge in navigation
                        const badge = document.getElementById('email-unread-badge');
                        if (badge) {
                            const currentCount = parseInt(badge.textContent) || 0;
                            const newCount = currentCount + 1;
                            badge.textContent = newCount;
                            badge.style.display = 'inline-block';
                            
                            // Add pulse animation
                            badge.classList.add('animate-pulse');
                            setTimeout(() => badge.classList.remove('animate-pulse'), 2000);
                        }
                    });
                    
                // Fetch initial unread count
                fetch('/email/inbox/unread-count')
                    .then(res => res.json())
                    .then(data => {
                        const badge = document.getElementById('email-unread-badge');
                        if (badge && data.count > 0) {
                            badge.textContent = data.count;
                            badge.style.display = 'inline-block';
                        }
                    })
                    .catch(err => console.log('Could not fetch unread count'));
                @endauth
            });
        </script>
    </body>
</html>
