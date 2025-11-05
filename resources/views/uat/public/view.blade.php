<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ $project->name }} - UAT</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50 dark:bg-gray-900" x-data="{ 
        theme: localStorage.theme || 'light',
        updateData: null,
        lastUpdate: null,
        pollingInterval: null,
        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.theme = this.theme;
            document.documentElement.classList.toggle('dark', this.theme === 'dark');
        },
        async fetchUpdates() {
            try {
                const response = await fetch('{{ route('uat.public.updates', $project->unique_token) }}');
                if (response.ok) {
                    this.updateData = await response.json();
                    this.lastUpdate = new Date().toLocaleTimeString();
                    // Trigger page reload if there are significant changes
                    if (this.updateData && JSON.stringify(this.updateData) !== JSON.stringify(window.initialData)) {
                        // Soft reload - just update the specific elements
                        this.updateUI();
                    }
                }
            } catch (error) {
                console.error('Failed to fetch updates:', error);
            }
        },
        updateUI() {
            // Page will auto-update via Alpine reactivity
            // You can add custom update logic here
        },
        startPolling() {
            // Store initial data
            window.initialData = this.updateData;
            // Poll every 5 seconds
            this.pollingInterval = setInterval(() => this.fetchUpdates(), 5000);
        },
        stopPolling() {
            if (this.pollingInterval) {
                clearInterval(this.pollingInterval);
            }
        }
    }" x-init="
        document.documentElement.classList.toggle('dark', theme === 'dark');
        @if($uatUser)
            fetchUpdates().then(() => startPolling());
        @endif
    " @beforeunload.window="stopPolling()">
        
        <!-- Modern Header with Glassmorphism -->
        <header class="sticky top-0 z-50 backdrop-blur-xl bg-white/80 dark:bg-gray-800/80 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-20">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('black-logo.png') }}" alt="Ryven Logo" class="h-12 w-auto dark:invert">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $project->name }}</h1>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="px-2.5 py-0.5 text-xs font-semibold rounded-full
                                    {{ $project->status === 'active' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                    {{ $project->status === 'completed' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : '' }}
                                    {{ $project->status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                ">
                                    {{ ucfirst($project->status) }}
                                </span>
                                @if($uatUser)
                                    <span class="flex items-center gap-1.5 px-2.5 py-0.5 bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 text-xs font-semibold rounded-full">
                                        <span class="relative flex h-2 w-2">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2 w-2 bg-green-500"></span>
                                        </span>
                                        Live
                                    </span>
                                @endif
                                @if($project->deadline)
                                    <span class="text-xs text-gray-500 dark:text-gray-400 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        Due {{ $project->deadline->format('M d, Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-3">
                        <!-- Theme Toggle -->
                        <button @click="toggleTheme()" 
                            class="p-2.5 rounded-full bg-black dark:bg-white text-white dark:text-black hover:bg-gray-800 dark:hover:bg-gray-200 transition-all shadow-sm">
                            <svg x-show="theme === 'light'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="theme === 'dark'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

                        @if($uatUser)
                            <div class="flex items-center gap-3 px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg">
                                <div class="w-10 h-10 rounded-full {{ $uatUser->role === 'internal' ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-gray-500 to-gray-600' }} flex items-center justify-center shadow-lg">
                                    <span class="text-sm font-bold text-white">
                                        {{ strtoupper(substr($uatUser->name, 0, 2)) }}
                                    </span>
                                </div>
                                <div>
                                    <div class="text-sm font-semibold text-gray-900 dark:text-white">{{ $uatUser->name }}</div>
                                    <div class="text-xs">
                                        <span class="px-2 py-0.5 rounded-full {{ $uatUser->role === 'internal' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                            {{ $uatUser->role === 'internal' ? '👔 Employee' : '👤 Client' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                
                <!-- Toast Notifications -->
                @if (session('status'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)" 
                        class="mb-6 p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg flex items-center gap-3 animate-slide-in">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div class="flex-1">{{ session('status') }}</div>
                        <button @click="show = false" class="p-1 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 5000)"
                        class="mb-6 p-4 bg-gradient-to-r from-red-500 to-red-600 text-white rounded-xl shadow-lg flex items-center gap-3 animate-slide-in">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                        <div class="flex-1">{{ session('error') }}</div>
                        <button @click="show = false" class="p-1 hover:bg-white/20 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                @endif

                <!-- Authentication Card -->
                @if(!$uatUser)
                    <div class="min-h-[70vh] flex items-center justify-center">
                        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl p-8 max-w-md w-full border border-gray-200 dark:border-gray-700">
                            <div class="text-center mb-8">
                                <div class="w-20 h-20 bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-xl">
                                    <svg class="w-10 h-10 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                    </svg>
                                </div>
                                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-2">Access Required</h2>
                                <p class="text-gray-600 dark:text-gray-400">Please enter your registered email to access this UAT project</p>
                            </div>

                            <form action="{{ route('uat.public.authenticate', $project->unique_token) }}" method="POST" class="space-y-4">
                                @csrf
                                <div>
                                    <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                        Email Address
                                    </label>
                                    <input type="email" name="email" id="email" required autofocus
                                        class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-900 dark:focus:border-white focus:ring-2 focus:ring-gray-900 dark:focus:ring-white transition-all"
                                        placeholder="your@email.com">
                                </div>
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-semibold shadow-xl hover:shadow-2xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-900/50 dark:focus:ring-white/50 transition-all transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                                    </svg>
                                    Access UAT Project
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <!-- Project Description -->
                    @if($project->description)
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-6">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Project Overview</h3>
                                    <p class="text-gray-600 dark:text-gray-300">{{ $project->description }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Invite Users Section (Internal Only) -->
                    @if($uatUser->isInternal())
                        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6" 
                            x-data="{ showInviteForm: false }">
                            <div class="p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Team Members</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->users->count() }} members • Invite more people</p>
                                        </div>
                                    </div>
                                    <button @click="showInviteForm = !showInviteForm" 
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-blue-600/50 transition-all transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span x-text="showInviteForm ? 'Cancel' : 'Invite User'"></span>
                                    </button>
                                </div>

                                <!-- Invite User Form -->
                                <div x-show="showInviteForm" x-collapse class="mt-6 p-6 bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 rounded-xl border-2 border-dashed border-blue-300 dark:border-blue-600">
                                    <form action="{{ route('uat.public.users.add', $project->unique_token) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    Full Name <span class="text-red-500">*</span>
                                                </label>
                                                <input type="text" name="name" required 
                                                    placeholder="John Doe"
                                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-400 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    Email Address <span class="text-red-500">*</span>
                                                </label>
                                                <input type="email" name="email" required 
                                                    placeholder="john@example.com"
                                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-400 transition-all">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                    Role <span class="text-red-500">*</span>
                                                </label>
                                                <select name="role" required 
                                                    class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-600 dark:focus:border-blue-400 focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-400 transition-all">
                                                    <option value="external">👤 Client</option>
                                                    <option value="internal">👔 Employee</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3 p-3 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            <div class="text-sm text-blue-800 dark:text-blue-300">
                                                <strong>Employee:</strong> Can create test cases and view all feedback. 
                                                <strong>Client:</strong> Can only test and provide feedback.
                                            </div>
                                        </div>
                                        <div class="flex justify-end gap-3 pt-2">
                                            <button type="button" @click="showInviteForm = false" 
                                                class="px-5 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl hover:scale-105 transition-all transform">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                                </svg>
                                                Send Invite
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Current Team Members -->
                                <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($project->users as $member)
                                        <div class="flex items-center gap-3 p-4 bg-gray-50 dark:bg-gray-700 rounded-lg {{ $member->id === $uatUser->id ? 'ring-2 ring-blue-500 dark:ring-blue-400' : '' }}">
                                            <div class="w-12 h-12 rounded-full {{ $member->role === 'internal' ? 'bg-gradient-to-br from-blue-500 to-blue-600' : 'bg-gradient-to-br from-gray-500 to-gray-600' }} flex items-center justify-center shadow-md">
                                                <span class="text-sm font-bold text-white">
                                                    {{ strtoupper(substr($member->name, 0, 2)) }}
                                                </span>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                                    {{ $member->name }}
                                                    @if($member->id === $uatUser->id)
                                                        <span class="text-blue-600 dark:text-blue-400">(You)</span>
                                                    @endif
                                                </div>
                                                {{-- <div class="text-xs text-gray-500 dark:text-gray-400 truncate">{{ $member->email }}</div> --}}
                                                <div class="text-xs mt-1">
                                                    <span class="px-2 py-0.5 rounded-full {{ $member->role === 'internal' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200' : 'bg-gray-200 text-gray-800 dark:bg-gray-600 dark:text-gray-200' }}">
                                                        {{ $member->role === 'internal' ? '👔 Employee' : '👤 Client' }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Test Cases Section -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700" 
                        x-data="{ 
                            activeFeedback: null,
                            showCreateForm: false,
                            expandedCase: null,
                            deleteTestCaseId: null,
                            deleteFormAction: ''
                        }">
                        <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-12 h-12 bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-6 h-6 text-white dark:text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">Test Cases</h3>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $project->testCases->count() }} test cases available</p>
                                    </div>
                                </div>

                                @if($uatUser->isInternal())
                                    <button @click="showCreateForm = !showCreateForm" 
                                        class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-semibold shadow-lg hover:shadow-xl hover:scale-105 focus:outline-none focus:ring-4 focus:ring-gray-900/50 dark:focus:ring-white/50 transition-all transform">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        <span x-text="showCreateForm ? 'Cancel' : 'Add Test Case'"></span>
                                    </button>
                                @endif
                            </div>

                            <!-- Create Test Case Form (Internal Users Only) -->
                            @if($uatUser->isInternal())
                                <div x-show="showCreateForm" x-collapse class="mt-6">
                                    <div class="bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-xl border-2 border-blue-200 dark:border-gray-600 overflow-hidden shadow-lg">
                                        <div class="bg-white dark:bg-gray-800 border-b border-blue-200 dark:border-gray-700 px-6 py-4">
                                            <h3 class="text-lg font-bold text-gray-900 dark:text-white flex items-center gap-2">
                                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                                </svg>
                                                Create New Test Case
                                            </h3>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Fill in the details below to create a comprehensive test case</p>
                                        </div>
                                        
                        <form action="{{ route('uat.public.test-cases.store', $project->unique_token) }}" method="POST" 
                            x-data="{ 
                                steps: [{ text: '' }],
                                addStep() { this.steps.push({ text: '' }); },
                                removeStep(index) { if (this.steps.length > 1) this.steps.splice(index, 1); },
                                descriptionEditor: null,
                                stepsEditor: null,
                                expectedResultEditor: null,
                                initEditors() {
                                    setTimeout(() => {
                                        if (this.$refs.descriptionEditor && !this.descriptionEditor) {
                                            this.descriptionEditor = new Quill(this.$refs.descriptionEditor, {
                                                theme: 'snow',
                                                placeholder: 'Brief description of what this test case covers...',
                                                modules: {
                                                    toolbar: [
                                                        ['bold', 'italic', 'underline', 'strike'],
                                                        ['blockquote', 'code-block'],
                                                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                        [{ 'header': [1, 2, 3, false] }],
                                                        ['link'],
                                                        ['clean']
                                                    ]
                                                }
                                            });
                                        }

                                        if (this.$refs.stepsEditor && !this.stepsEditor) {
                                            this.stepsEditor = new Quill(this.$refs.stepsEditor, {
                                                theme: 'snow',
                                                placeholder: 'Enter testing steps...',
                                                modules: {
                                                    toolbar: [
                                                        ['bold', 'italic', 'underline'],
                                                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                        ['link', 'code-block'],
                                                        ['clean']
                                                    ]
                                                }
                                            });
                                        }

                                        if (this.$refs.expectedResultEditor && !this.expectedResultEditor) {
                                            this.expectedResultEditor = new Quill(this.$refs.expectedResultEditor, {
                                                theme: 'snow',
                                                placeholder: 'What should happen when the test is performed correctly...',
                                                modules: {
                                                    toolbar: [
                                                        ['bold', 'italic', 'underline'],
                                                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                                                        ['link', 'code-block'],
                                                        ['clean']
                                                    ]
                                                }
                                            });
                                        }
                                    }, 100);
                                }
                            }" 
                            x-init="initEditors()"
                            @submit="
                                if (descriptionEditor) {
                                    $event.target.querySelector('input[name=description]').value = descriptionEditor.root.innerHTML;
                                }
                                if (stepsEditor) {
                                    $event.target.querySelector('input[name=steps]').value = stepsEditor.root.innerHTML;
                                }
                                if (expectedResultEditor) {
                                    $event.target.querySelector('input[name=expected_result]').value = expectedResultEditor.root.innerHTML;
                                }
                            "
                            class="p-6 space-y-6">
                            @csrf                                            <!-- Title & Priority Row -->
                                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                                                <div class="lg:col-span-2">
                                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                            </svg>
                                                            Test Case Title <span class="text-red-500">*</span>
                                                        </span>
                                                    </label>
                                                    <input type="text" name="title" required 
                                                        placeholder="e.g., User Login with Valid Credentials"
                                                        class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all">
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                                        <span class="flex items-center gap-2">
                                                            <svg class="w-4 h-4 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                            </svg>
                                                            Priority <span class="text-red-500">*</span>
                                                        </span>
                                                    </label>
                                                    <select name="priority" required 
                                                        class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500 transition-all">
                                                        <option value="low">🟢 Low</option>
                                                        <option value="medium" selected>🟡 Medium</option>
                                                        <option value="high">🟠 High</option>
                                                        <option value="critical">🔴 Critical</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <!-- Description -->
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                                    <span class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path>
                                                        </svg>
                                                        Description
                                                    </span>
                                                </label>
                                                <div x-ref="descriptionEditor" class="bg-white dark:bg-gray-700"></div>
                                                <input type="hidden" name="description">
                                            </div>

                                            <!-- Steps to Test -->
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                                    <span class="flex items-center gap-2">
                                                        <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                                        </svg>
                                                        Testing Steps
                                                    </span>
                                                </label>
                                                <div x-ref="stepsEditor" class="bg-white dark:bg-gray-700"></div>
                                                <input type="hidden" name="steps">
                                            </div>

                                            <!-- Expected Result -->
                                            <div>
                                                <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">
                                                    <span class="flex items-center gap-2">
                                                        <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        Expected Result
                                                    </span>
                                                </label>
                                                <div x-ref="expectedResultEditor" class="bg-white dark:bg-gray-700"></div>
                                                <input type="hidden" name="expected_result">
                                            </div>

                                            <!-- Action Buttons -->
                                            <div class="flex justify-end gap-3 pt-4 border-t-2 border-blue-200 dark:border-gray-700">
                                                <button type="button" @click="showCreateForm = false" 
                                                    class="px-5 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                                    Cancel
                                                </button>
                                                <button type="submit" 
                                                    class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all transform hover:scale-105">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                    Create Test Case
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Test Cases List -->
                        <div class="divide-y divide-gray-200 dark:divide-gray-700">
                            @if($project->testCases->count() > 0)
                                @foreach($project->testCases as $index => $testCase)
                                    @php
                                        $userFeedback = $testCase->feedbacks->where('uat_user_id', $uatUser->id)->first();
                                    @endphp
                                    <div class="p-6 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <div class="flex items-start gap-4">
                                            <!-- Test Case Number Badge -->
                                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-gray-900 to-gray-700 dark:from-white dark:to-gray-300 flex items-center justify-center flex-shrink-0 shadow-lg">
                                                <span class="text-lg font-bold text-white dark:text-gray-900">#{{ $index + 1 }}</span>
                                            </div>

                                            <div class="flex-1 min-w-0">
                                                <!-- Header -->
                                                <div class="flex items-start justify-between gap-4 mb-3">
                                                    <div class="flex-1">
                                                        <div class="flex items-center gap-2 flex-wrap mb-2">
                                                            <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $testCase->title }}</h4>
                                                            <span class="px-2.5 py-1 text-xs font-bold rounded-lg
                                                                {{ $testCase->priority === 'critical' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                                {{ $testCase->priority === 'high' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                                {{ $testCase->priority === 'medium' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200' : '' }}
                                                                {{ $testCase->priority === 'low' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                            ">
                                                                {{ $testCase->priority === 'critical' ? '🔴' : '' }}
                                                                {{ $testCase->priority === 'high' ? '🟠' : '' }}
                                                                {{ $testCase->priority === 'medium' ? '🟡' : '' }}
                                                                {{ $testCase->priority === 'low' ? '🟢' : '' }}
                                                                {{ ucfirst($testCase->priority) }}
                                                            </span>
                                                        </div>
                                                        @if($testCase->description)
                                                            <div class="text-sm text-gray-600 dark:text-gray-400 mb-3 prose prose-sm dark:prose-invert max-w-none">
                                                                {!! $testCase->description !!}
                                                            </div>
                                                        @endif
                                                    </div>

                                                    @if($userFeedback)
                                                        <div class="flex-shrink-0">
                                                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-bold rounded-full shadow-sm
                                                                {{ $userFeedback->status === 'passed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                                {{ $userFeedback->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                                {{ $userFeedback->status === 'blocked' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                                {{ $userFeedback->status === 'pending' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                            ">
                                                                {{ $userFeedback->status === 'passed' ? '✅' : '' }}
                                                                {{ $userFeedback->status === 'failed' ? '❌' : '' }}
                                                                {{ $userFeedback->status === 'blocked' ? '🚫' : '' }}
                                                                {{ $userFeedback->status === 'pending' ? '⏳' : '' }}
                                                                {{ ucfirst($userFeedback->status) }}
                                                            </span>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- Expandable Details -->
                                                <button @click="expandedCase = expandedCase === {{ $testCase->id }} ? null : {{ $testCase->id }}" 
                                                    type="button"
                                                    class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white mb-3 transition-colors">
                                                    <svg class="w-4 h-4 transition-transform" :class="expandedCase === {{ $testCase->id }} ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                    <span x-text="expandedCase === {{ $testCase->id }} ? 'Hide Details' : 'Show Details'"></span>
                                                </button>

                                                <div x-show="expandedCase === {{ $testCase->id }}" x-collapse class="space-y-4 mb-4">
                                                    @if($testCase->steps)
                                                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                            <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-3 uppercase tracking-wide">Steps to Test</h5>
                                                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300">
                                                                {!! $testCase->steps !!}
                                                            </div>
                                                        </div>
                                                    @endif

                                                    @if($testCase->expected_result)
                                                        <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                            <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-2 uppercase tracking-wide">Expected Result</h5>
                                                            <div class="prose prose-sm dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                                                                {!! $testCase->expected_result !!}
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>

                                                <!-- All Feedback (For Employees) -->
                                                @if($uatUser->isInternal() && $testCase->feedbacks->count() > 0)
                                                    <div class="mb-4 bg-gray-50 dark:bg-gray-900/50 rounded-lg p-5 border border-gray-200 dark:border-gray-700">
                                                        <div class="flex items-center gap-2 mb-4">
                                                            <div class="w-8 h-8 rounded-lg bg-black dark:bg-white flex items-center justify-center">
                                                                <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                                                                </svg>
                                                            </div>
                                                            <h5 class="text-lg font-bold text-gray-900 dark:text-white">All Feedback</h5>
                                                        </div>
                                                        
                                                        <!-- Status Summary -->
                                                        <div class="flex flex-wrap gap-2 mb-4">
                                                            @php
                                                                $feedbackCounts = $testCase->feedbacks->groupBy('status')->map->count();
                                                            @endphp
                                                            @if($feedbackCounts->get('passed', 0) > 0)
                                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                                                    ✅ {{ $feedbackCounts->get('passed') }} Passed
                                                                </span>
                                                            @endif
                                                            @if($feedbackCounts->get('failed', 0) > 0)
                                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                                    ❌ {{ $feedbackCounts->get('failed') }} Failed
                                                                </span>
                                                            @endif
                                                            @if($feedbackCounts->get('blocked', 0) > 0)
                                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200">
                                                                    🚫 {{ $feedbackCounts->get('blocked') }} Blocked
                                                                </span>
                                                            @endif
                                                            @if($feedbackCounts->get('pending', 0) > 0)
                                                                <span class="px-3 py-1 text-xs font-bold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                                    ⏳ {{ $feedbackCounts->get('pending') }} Pending
                                                                </span>
                                                            @endif
                                                        </div>

                                                        <!-- Individual Feedback Items -->
                                                        <div class="space-y-3">
                                                            @foreach($testCase->feedbacks as $feedback)
                                                                <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border-2 {{ $feedback->user->role === 'external' ? 'border-blue-200 dark:border-blue-900 bg-blue-50/30 dark:bg-blue-900/10' : 'border-gray-200 dark:border-gray-700' }}">
                                                                    <div class="flex items-start justify-between mb-2">
                                                                        <div class="flex items-center gap-2">
                                                                            <div class="w-8 h-8 rounded-full {{ $feedback->user->role === 'internal' ? 'bg-black dark:bg-white' : 'bg-blue-600 dark:bg-blue-500' }} flex items-center justify-center">
                                                                                <span class="text-xs font-bold text-white dark:text-black">
                                                                                    {{ strtoupper(substr($feedback->user->name, 0, 2)) }}
                                                                                </span>
                                                                            </div>
                                                                            <div>
                                                                                <div class="flex items-center gap-2">
                                                                                    <span class="font-bold text-gray-900 dark:text-white text-sm">{{ $feedback->user->name }}</span>
                                                                                    <span class="px-2 py-0.5 text-xs font-bold rounded-full {{ $feedback->user->role === 'internal' ? 'bg-black dark:bg-white text-white dark:text-black' : 'bg-blue-600 text-white' }}">
                                                                                        {{ $feedback->user->role === 'internal' ? '👔 Employee' : '👤 Client' }}
                                                                                    </span>
                                                                                    @if($feedback->user->id === $uatUser->id)
                                                                                        <span class="px-2 py-0.5 text-xs font-bold rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                                                            You
                                                                                        </span>
                                                                                    @endif
                                                                                </div>
                                                                                <span class="text-xs text-gray-500 dark:text-gray-400">{{ $feedback->created_at->diffForHumans() }}</span>
                                                                            </div>
                                                                        </div>
                                                                        <span class="px-3 py-1 text-xs font-bold rounded-full
                                                                            {{ $feedback->status === 'passed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                                            {{ $feedback->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                                            {{ $feedback->status === 'blocked' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                                            {{ $feedback->status === 'pending' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                                        ">
                                                                            {{ ucfirst($feedback->status) }}
                                                                        </span>
                                                                    </div>
                                                                    @if($feedback->comment)
                                                                        <div class="pl-10 mt-2">
                                                                            <p class="text-sm text-gray-800 dark:text-gray-200 leading-relaxed">{{ $feedback->comment }}</p>
                                                                        </div>
                                                                    @else
                                                                        <div class="pl-10 mt-2">
                                                                            <p class="text-xs italic text-gray-500 dark:text-gray-400">Status updated to: {{ ucfirst($feedback->status) }}</p>
                                                                        </div>
                                                                    @endif
                                                                    @if($feedback->attachment_path)
                                                                        <a href="{{ Storage::url($feedback->attachment_path) }}" target="_blank" 
                                                                            class="inline-flex items-center gap-2 mt-3 ml-10 px-3 py-1.5 text-xs font-medium bg-black dark:bg-white text-white dark:text-black rounded-full hover:bg-gray-800 dark:hover:bg-gray-200 transition-all">
                                                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                                            </svg>
                                                                            View Attachment
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @elseif(!$uatUser->isInternal() && $userFeedback && $userFeedback->comment)
                                                    <!-- Your Previous Feedback (For Clients) -->
                                                    <div class="mb-4 p-4 bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                                                        <div class="flex items-start gap-3">
                                                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center flex-shrink-0">
                                                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                                </svg>
                                                            </div>
                                                            <div class="flex-1">
                                                                <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-1">Your Feedback:</h5>
                                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $userFeedback->comment }}</p>
                                                                @if($userFeedback->attachment_path)
                                                                    <a href="{{ Storage::url($userFeedback->attachment_path) }}" target="_blank" 
                                                                        class="inline-flex items-center gap-1 mt-2 text-xs font-medium text-blue-600 dark:text-blue-400 hover:underline">
                                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                                        </svg>
                                                                        View Attachment
                                                                    </a>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- Quick Status Buttons -->
                                                <div class="flex flex-wrap gap-2 mb-3">
                                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300 self-center">Quick Status:</span>
                                                    <form action="{{ route('uat.public.status', [$project->unique_token, $testCase]) }}" method="POST" class="inline" title="Test completed successfully with expected results">
                                                        @csrf
                                                        <input type="hidden" name="status" value="passed">
                                                        <button type="submit" 
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-full transition-all"
                                                            title="✅ Use when: Test works as expected and all steps completed successfully">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                            </svg>
                                                            Passed
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('uat.public.status', [$project->unique_token, $testCase]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="failed">
                                                        <button type="submit" 
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-full transition-all"
                                                            title="❌ Use when: Test did not work as expected, found bugs, or got different results than expected">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                            Failed
                                                        </button>
                                                    </form>
                                                    <form action="{{ route('uat.public.status', [$project->unique_token, $testCase]) }}" method="POST" class="inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="blocked">
                                                        <button type="submit" 
                                                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-orange-600 hover:bg-orange-700 text-white text-sm font-medium rounded-full transition-all"
                                                            title="🚫 Use when: Cannot complete test due to missing features, dependencies, or access issues">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                            </svg>
                                                            Blocked
                                                        </button>
                                                    </form>
                                                </div>

                                                <!-- Detailed Feedback Toggle -->
                                                <button @click="activeFeedback = activeFeedback === {{ $testCase->id }} ? null : {{ $testCase->id }}" type="button"
                                                    class="inline-flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path>
                                                    </svg>
                                                    <span x-text="activeFeedback === {{ $testCase->id }} ? 'Hide Feedback Form' : '{{ $userFeedback && $userFeedback->comment ? 'Update' : 'Add' }} Detailed Feedback'"></span>
                                                </button>

                                                <!-- Detailed Feedback Form -->
                                                <div x-show="activeFeedback === {{ $testCase->id }}" x-collapse class="mt-4 p-5 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl border-2 border-gray-200 dark:border-gray-600">
                                                    <form action="{{ route('uat.public.feedback', [$project->unique_token, $testCase]) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                                        @csrf
                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                                            <select name="status" required
                                                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-900 dark:focus:border-white focus:ring-2 focus:ring-gray-900 dark:focus:ring-white transition-all">
                                                                <option value="pending" {{ $userFeedback && $userFeedback->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                                <option value="passed" {{ $userFeedback && $userFeedback->status === 'passed' ? 'selected' : '' }}>✅ Passed</option>
                                                                <option value="failed" {{ $userFeedback && $userFeedback->status === 'failed' ? 'selected' : '' }}>❌ Failed</option>
                                                                <option value="blocked" {{ $userFeedback && $userFeedback->status === 'blocked' ? 'selected' : '' }}>🚫 Blocked</option>
                                                            </select>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">Comment</label>
                                                            <textarea name="comment" rows="4"
                                                                class="block w-full px-4 py-3 rounded-lg border-2 border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-gray-900 dark:focus:border-white focus:ring-2 focus:ring-gray-900 dark:focus:ring-white transition-all"
                                                                placeholder="Share your feedback, issues found, or suggestions...">{{ $userFeedback ? $userFeedback->comment : '' }}</textarea>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                                                <span class="flex items-center gap-2">
                                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                                    </svg>
                                                                    Attachment (optional)
                                                                </span>
                                                            </label>
                                                            <input type="file" name="attachment" accept="image/*,.pdf,.doc,.docx"
                                                                class="block w-full text-sm text-gray-700 dark:text-gray-300 
                                                                file:mr-4 file:py-2.5 file:px-4 
                                                                file:rounded-lg file:border-0 
                                                                file:text-sm file:font-semibold 
                                                                file:bg-black dark:file:bg-white file:text-white dark:file:text-black
                                                                hover:file:bg-gray-800 dark:hover:file:bg-gray-200
                                                                file:cursor-pointer file:transition-all file:shadow-md hover:file:shadow-lg">
                                                        </div>
                                                        <div class="flex justify-end gap-3 pt-2">
                                                            <button type="button" @click="activeFeedback = null" 
                                                                class="px-5 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                                                Cancel
                                                            </button>
                                                            <button type="submit"
                                                                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black dark:bg-white text-white dark:text-black rounded-full font-semibold shadow-lg hover:shadow-xl hover:scale-105 transition-all transform">
                                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                                </svg>
                                                                Submit Feedback
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>

                                                <!-- Other Users Feedback (Internal Only) -->
                                                @if($uatUser->isInternal() && $testCase->feedbacks->count() > 1)
                                                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                        <h5 class="text-sm font-bold text-gray-900 dark:text-white mb-3 flex items-center gap-2">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                            </svg>
                                                            Team Feedback:
                                                        </h5>
                                                        <div class="space-y-2">
                                                            @foreach($testCase->feedbacks->where('uat_user_id', '!=', $uatUser->id) as $feedback)
                                                                <div class="p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                                                    <div class="flex items-center justify-between mb-2">
                                                                        <div class="flex items-center gap-2">
                                                                            <div class="w-7 h-7 rounded-full bg-gradient-to-br from-gray-500 to-gray-600 flex items-center justify-center">
                                                                                <span class="text-xs font-bold text-white">{{ strtoupper(substr($feedback->user->name, 0, 2)) }}</span>
                                                                            </div>
                                                                            <span class="text-sm font-semibold text-gray-900 dark:text-white">{{ $feedback->user->name }}</span>
                                                                        </div>
                                                                        <span class="px-2 py-0.5 text-xs font-bold rounded
                                                                            {{ $feedback->status === 'passed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200' : '' }}
                                                                            {{ $feedback->status === 'failed' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200' : '' }}
                                                                            {{ $feedback->status === 'blocked' ? 'bg-orange-100 text-orange-800 dark:bg-orange-900 dark:text-orange-200' : '' }}
                                                                            {{ $feedback->status === 'pending' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200' : '' }}
                                                                        ">
                                                                            {{ ucfirst($feedback->status) }}
                                                                        </span>
                                                                    </div>
                                                                    @if($feedback->comment)
                                                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $feedback->comment }}</p>
                                                                    @endif
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- Delete Button (Employees Only) -->
                                            @if($uatUser->isInternal())
                                                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
                                                    <button type="button"
                                                        @click="deleteTestCaseId = {{ $testCase->id }}; deleteFormAction = '{{ route('uat.public.test-cases.destroy', [$project->unique_token, $testCase]) }}'"
                                                        class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium bg-red-600 hover:bg-red-700 text-white rounded-full transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                        Delete Test Case
                                                    </button>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="p-12 text-center">
                                    <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No test cases available</h3>
                                    <p class="text-gray-500 dark:text-gray-400">Test cases will appear here once they are added by the team.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Delete Confirmation Modal -->
                        <div x-show="deleteTestCaseId !== null" 
                            x-transition:enter="transition ease-out duration-300"
                            x-transition:enter-start="opacity-0"
                            x-transition:enter-end="opacity-100"
                            x-transition:leave="transition ease-in duration-200"
                            x-transition:leave-start="opacity-100"
                            x-transition:leave-end="opacity-0"
                            class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
                            @click.self="deleteTestCaseId = null"
                            style="display: none;">
                            <div x-show="deleteTestCaseId !== null"
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 scale-90"
                                x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 scale-100"
                                x-transition:leave-end="opacity-0 scale-90"
                                class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden"
                                @click.stop>
                                <!-- Modal Header -->
                                <div class="bg-gradient-to-r from-red-500 to-red-600 px-6 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white">Delete Test Case</h3>
                                            <p class="text-red-100 text-sm mt-0.5">This action cannot be undone</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Modal Body -->
                                <div class="px-6 py-5">
                                    <p class="text-gray-700 dark:text-gray-300 leading-relaxed">
                                        Are you sure you want to delete this test case? All associated feedback and data will be permanently removed.
                                    </p>
                                </div>

                                <!-- Modal Footer -->
                                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-900/50 flex justify-end gap-3">
                                    <button type="button" 
                                        @click="deleteTestCaseId = null"
                                        class="px-5 py-2.5 bg-white dark:bg-gray-700 border-2 border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-full font-semibold hover:bg-gray-50 dark:hover:bg-gray-600 transition-all">
                                        Cancel
                                    </button>
                                    <form :action="deleteFormAction" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-full font-semibold shadow-lg hover:shadow-xl transition-all">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                            Delete Test Case
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </main>

        <!-- Modern Footer -->
        <footer class="mt-12 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        © {{ date('Y') }} {{ $project->name }} - UAT System
                    </p>
                    <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                        <span class="text-xs text-gray-500 dark:text-gray-400">Live</span>
                    </div>
                </div>
            </div>
        </footer>

        <style>
            @keyframes slide-in {
                from {
                    transform: translateY(-100%);
                    opacity: 0;
                }
                to {
                    transform: translateY(0);
                    opacity: 1;
                }
            }
            .animate-slide-in {
                animation: slide-in 0.3s ease-out;
            }
        </style>
    </body>
</html>
