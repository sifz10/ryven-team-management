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
        <a href="{{ route('dashboard') }}" class="flex items-center justify-center overflow-hidden">
            <img x-show="!sidebarCollapsed"
                 x-cloak
                 src="{{ asset('black-logo.png') }}"
                 alt="Logo"
                 class="h-10 w-auto dark:invert transition-opacity duration-200">
            <img x-show="sidebarCollapsed"
                 x-cloak
                 src="{{ asset('favicon.png') }}"
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
    <nav class="flex-1 overflow-y-auto py-4 px-3 space-y-1">
        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('dashboard')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Dashboard' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Dashboard</span>
        </a>

        <!-- Employees -->
        <a href="{{ route('employees.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('employees.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Employees' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Employees</span>
        </a>

        <!-- Attendance -->
        <a href="{{ route('attendance.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('attendance.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Attendance' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Attendance</span>
        </a>

        <!-- Projects -->
        <a href="{{ route('projects.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('projects.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Projects' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Projects</span>
        </a>

        <!-- UAT -->
        <a href="{{ route('uat.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('uat.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'UAT Testing' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">UAT Testing</span>
        </a>

        <!-- GitHub Logs -->
        <a href="{{ route('github.logs') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('github.logs')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'GitHub Logs' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">GitHub Logs</span>
        </a>

        <!-- AI Assistant -->
        <a href="{{ route('ai-agent.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('ai-agent.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'AI Assistant' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">AI Assistant</span>
        </a>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
        <div x-show="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Management
        </div>

        <!-- Invoices -->
        <a href="{{ route('invoices.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('invoices.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Invoices' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Invoices</span>
        </a>

        <!-- Contracts -->
        <a href="{{ route('contracts.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('contracts.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Contracts' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Contracts</span>
        </a>

        <!-- Personal Notes -->
        <a href="{{ route('notes.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('notes.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Personal Notes' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Personal Notes</span>
        </a>

        <!-- Email Inbox -->
        <a href="{{ route('email.inbox.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group relative
                  {{ request()->routeIs('email.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Email Inbox' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Email Inbox</span>
            <span id="email-unread-badge"
                  class="absolute top-1 right-1 px-1.5 py-0.5 text-xs font-bold rounded-full bg-red-500 text-white"
                  style="display: none;">0</span>
        </a>

        <!-- Content Calendar -->
        <a href="{{ route('social.calendar') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('social.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Content Calendar' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Content Calendar</span>
        </a>

        <!-- SOP -->
        <a href="{{ route('sop') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('sop')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'SOP' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">SOP</span>
        </a>

        <!-- Divider -->
        <div class="border-t border-gray-200 dark:border-gray-700 my-2"></div>
        <div x-show="!sidebarCollapsed" class="px-3 py-2 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
            Performance
        </div>

        <!-- Review Cycles -->
        <a href="{{ route('review-cycles.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('review-cycles.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Review Cycles' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Review Cycles</span>
        </a>

        <!-- Performance Reviews -->
        <a href="{{ route('reviews.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('reviews.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Performance Reviews' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Performance Reviews</span>
        </a>

        <!-- Goals & OKRs -->
        <a href="{{ route('goals.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('goals.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Goals & OKRs' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Goals & OKRs</span>
        </a>

        <!-- Skills -->
        <a href="{{ route('skills.index') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all duration-200 group
                  {{ request()->routeIs('skills.*')
                     ? 'bg-black text-white dark:bg-white dark:text-black'
                     : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
           x-tooltip="sidebarCollapsed ? 'Skills' : ''">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium">Skills</span>
        </a>
    </nav>

    <!-- Collapse Toggle Button (Desktop Only) -->
    <div class="hidden lg:block border-t border-gray-200 dark:border-gray-700 p-3">
        <button @click="toggleSidebar()"
                class="w-full flex items-center justify-center gap-2 px-3 py-2 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200">
            <svg class="w-5 h-5 transition-transform duration-200"
                 :class="{'rotate-180': sidebarCollapsed}"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
            </svg>
            <span x-show="!sidebarCollapsed" class="font-medium text-sm">Collapse</span>
        </button>
    </div>
</aside>
