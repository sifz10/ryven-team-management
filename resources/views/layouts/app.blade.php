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

        <!-- Floating AI Chat Button (Hidden for employees) -->
        @if(Auth::guard('web')->check())
        <div x-data="aiChatButton()"
             x-init="init()"
             class="fixed bottom-6 right-6 z-50">

            <!-- Chat Button -->
            <button @click="toggleChat()"
                    class="group relative w-16 h-16 bg-black dark:bg-white rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300 flex items-center justify-center hover:scale-110 focus:outline-none focus:ring-4 focus:ring-black/20 dark:focus:ring-white/20">

                <!-- Voice Active Rings Animation -->
                <div x-show="isListening"
                     x-cloak
                     class="absolute inset-0 rounded-full">
                    <div class="absolute inset-0 rounded-full bg-black dark:bg-white opacity-30 animate-ping"></div>
                    <div class="absolute inset-0 rounded-full bg-black dark:bg-white opacity-20 animate-pulse"></div>
                </div>

                <!-- AI Icon -->
                <div class="relative z-10 transition-transform duration-300 group-hover:rotate-12">
                    <svg class="w-8 h-8 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>

                <!-- Unread Badge (optional for future use) -->
                <div x-show="unreadCount > 0"
                     x-cloak
                     class="absolute -top-1 -right-1 w-6 h-6 bg-red-500 rounded-full flex items-center justify-center border-2 border-white dark:border-gray-900">
                    <span x-text="unreadCount" class="text-xs font-bold text-white"></span>
                </div>
            </button>

            <!-- Mini Chat Panel -->
            <div x-show="isOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4 scale-95"
                 x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 transform translate-y-4 scale-95"
                 class="absolute bottom-20 right-0 w-96 bg-white dark:bg-gray-800 rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-700 overflow-hidden"
                 @click.away="isOpen = false">

                <!-- Header -->
                <div class="bg-black dark:bg-white p-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white dark:bg-black rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-white dark:text-black font-semibold">AI Assistant</h3>
                            <p class="text-xs text-white/80 dark:text-black/80">Ask me anything</p>
                        </div>
                    </div>
                    <button @click="isOpen = false"
                            class="text-white dark:text-black hover:bg-white/10 dark:hover:bg-black/10 p-2 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Quick Actions -->
                <div class="p-4 space-y-2 max-h-96 overflow-y-auto">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Quick actions:</p>

                    <button @click="sendQuickMessage('List all employees')"
                            class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm text-gray-700 dark:text-gray-300">
                        📋 List all employees
                    </button>

                    <button @click="sendQuickMessage('Who didn\'t push code today?')"
                            class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm text-gray-700 dark:text-gray-300">
                        🔍 Check today's GitHub activity
                    </button>

                    <button @click="sendQuickMessage('Show attendance summary')"
                            class="w-full text-left px-4 py-3 bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 rounded-lg transition-colors text-sm text-gray-700 dark:text-gray-300">
                        📊 Attendance summary
                    </button>

                    <button @click="openVoiceInput()"
                            class="w-full text-left px-4 py-3 bg-black dark:bg-white hover:bg-gray-800 dark:hover:bg-gray-200 rounded-lg transition-colors text-sm text-white dark:text-black font-medium flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" />
                        </svg>
                        <span x-text="isListening ? 'Listening...' : 'Voice input'"></span>
                    </button>
                </div>

                <!-- Footer -->
                <div class="p-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <a href="{{ route('ai-agent.index') }}"
                       class="block w-full text-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-800 dark:hover:bg-gray-200 transition-colors text-sm font-medium">
                        Open Full Chat
                    </a>
                </div>
            </div>
        </div>
        @endif

        <div class="fixed bottom-6 left-6 z-40" x-data>
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
            // Floating AI Chat Button Component
            function aiChatButton() {
                return {
                    isOpen: false,
                    isListening: false,
                    unreadCount: 0,
                    recognition: null,

                    init() {
                        // Initialize Speech Recognition
                        if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                            const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                            this.recognition = new SpeechRecognition();
                            this.recognition.continuous = false;
                            this.recognition.interimResults = false;
                            this.recognition.lang = 'en-US';

                            this.recognition.onresult = (event) => {
                                const transcript = event.results[0][0].transcript;
                                this.isListening = false;
                                this.sendQuickMessage(transcript);
                            };

                            this.recognition.onerror = (event) => {
                                console.error('Speech recognition error:', event.error);
                                this.isListening = false;
                                this.showNotification('Voice input error: ' + event.error, 'error');
                            };

                            this.recognition.onend = () => {
                                this.isListening = false;
                            };
                        }
                    },

                    toggleChat() {
                        this.isOpen = !this.isOpen;
                    },

                    openVoiceInput() {
                        if (!this.recognition) {
                            this.showNotification('Speech recognition is not supported in your browser', 'error');
                            return;
                        }

                        if (this.isListening) {
                            this.recognition.stop();
                            this.isListening = false;
                        } else {
                            this.recognition.start();
                            this.isListening = true;
                        }
                    },

                    sendQuickMessage(message) {
                        // Store message and redirect to AI agent page
                        sessionStorage.setItem('aiPendingMessage', message);
                        window.location.href = '{{ route("ai-agent.index") }}';
                    },

                    showNotification(message, type = 'info') {
                        const notification = document.createElement('div');
                        const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-black dark:bg-white';
                        const textColor = type === 'error' || type === 'success' ? 'text-white' : 'text-white dark:text-black';

                        notification.className = `fixed top-4 right-4 px-5 py-3.5 rounded-xl shadow-2xl z-50 flex items-center space-x-3 ${bgColor} ${textColor}`;
                        notification.innerHTML = `<span class="font-medium">${message}</span>`;

                        document.body.appendChild(notification);
                        setTimeout(() => notification.remove(), 3000);
                    }
                }
            }

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
