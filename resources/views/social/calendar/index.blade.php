<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h2 class="font-semibold text-xl sm:text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Content Calendar') }}
                </h2>
                <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1">Plan and schedule social media posts</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('social.accounts.index') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                    <span class="hidden sm:inline">{{ __('Accounts') }}</span>
                    <span class="sm:hidden">Acc</span>
                </a>
                <a href="{{ route('social.posts.create') }}" 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    {{ __('New Post') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6" x-data="contentCalendar()">
            <!-- Month Navigation -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-2xl mb-6">
                <div class="p-4 sm:p-6 flex flex-col sm:flex-row justify-between items-center gap-4">
                    <button @click="previousMonth()" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                        <span class="hidden sm:inline">Previous</span>
                        <span class="sm:hidden">Prev</span>
                    </button>
                    <h3 class="text-lg sm:text-xl font-bold text-gray-900 dark:text-gray-100" x-text="currentMonthName"></h3>
                    <button @click="nextMonth()" 
                        class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-black dark:bg-white text-white dark:text-black border border-transparent rounded-full font-semibold text-xs uppercase tracking-widest hover:bg-gray-800 dark:hover:bg-gray-100 transition">
                        Next
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Calendar Grid -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
                <div class="p-6">
                    <!-- Weekday Headers -->
                    <div class="grid grid-cols-7 gap-2 mb-4">
                        <template x-for="day in weekDays" :key="day">
                            <div class="text-center font-bold text-gray-700 dark:text-gray-300 py-2">
                                <span x-text="day"></span>
                            </div>
                        </template>
                    </div>

                    <!-- Calendar Days -->
                    <div class="grid grid-cols-7 gap-2">
                        <template x-for="day in calendarDays" :key="day.date">
                            <div @click="selectDate(day)" 
                                class="min-h-[120px] p-3 border rounded-lg cursor-pointer transition-all"
                                :class="{
                                    'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700': !day.isCurrentMonth,
                                    'bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-600 hover:border-gray-400 dark:hover:border-gray-500': day.isCurrentMonth,
                                    'ring-2 ring-black dark:ring-gray-400': day.isToday
                                }">
                                <div class="flex justify-between items-start mb-2">
                                    <span class="text-sm font-semibold"
                                        :class="{
                                            'text-gray-400 dark:text-gray-600': !day.isCurrentMonth,
                                            'text-gray-900 dark:text-gray-100': day.isCurrentMonth && !day.isToday,
                                            'text-black dark:text-white': day.isToday
                                        }"
                                        x-text="day.dayNumber"></span>
                                    <template x-if="day.postCount > 0">
                                        <span class="text-xs px-2 py-1 bg-black dark:bg-gray-700 text-white rounded-full font-bold" 
                                            x-text="day.postCount"></span>
                                    </template>
                                </div>
                                
                                <!-- Post indicators -->
                                <div class="space-y-1">
                                    <template x-for="post in day.posts.slice(0, 3)" :key="post.id">
                                        <div class="text-xs px-2 py-1 rounded truncate"
                                            :class="{
                                                'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300': post.status === 'draft',
                                                'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': post.status === 'scheduled',
                                                'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300': post.status === 'posted',
                                                'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300': post.status === 'failed'
                                            }"
                                            x-text="post.title"></div>
                                    </template>
                                    <template x-if="day.postCount > 3">
                                        <div class="text-xs text-gray-500 dark:text-gray-400 px-2" 
                                            x-text="`+${day.postCount - 3} more`"></div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>

            <!-- Posts List for Selected Date Modal -->
            <div x-show="selectedDate" 
                x-cloak
                @click.self="selectedDate = null"
                class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-3xl w-full max-h-[80vh] overflow-hidden">
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100" x-text="`Posts for ${selectedDateFormatted}`"></h3>
                        <button @click="selectedDate = null" 
                            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="p-6 overflow-y-auto max-h-[60vh]">
                        <template x-if="selectedDatePosts.length === 0">
                            <p class="text-gray-500 dark:text-gray-400 text-center py-8">No posts scheduled for this date.</p>
                        </template>
                        <div class="space-y-4">
                            <template x-for="post in selectedDatePosts" :key="post.id">
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-gray-300 dark:hover:border-gray-600 transition">
                                    <div class="flex justify-between items-start mb-2">
                                        <div class="flex-1">
                                            <h4 class="font-semibold text-gray-900 dark:text-gray-100" x-text="post.title"></h4>
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" x-text="post.description || 'No description'"></p>
                                        </div>
                                        <span class="text-xs px-3 py-1 rounded-full font-semibold"
                                            :class="{
                                                'bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300': post.status === 'draft',
                                                'bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300': post.status === 'scheduled',
                                                'bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300': post.status === 'posted',
                                                'bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300': post.status === 'failed'
                                            }"
                                            x-text="post.status"></span>
                                    </div>
                                    <div class="flex items-center justify-between mt-3">
                                        <div class="flex items-center gap-3 text-sm text-gray-600 dark:text-gray-400">
                                            <span x-show="post.social_account" x-text="post.social_account?.platform_username"></span>
                                            <span x-text="post.scheduled_at_time"></span>
                                        </div>
                                        <a :href="`/social/posts/${post.id}`" 
                                            class="inline-flex items-center gap-2 px-3 py-1.5 bg-black dark:bg-gray-900 text-white text-xs rounded-lg hover:bg-gray-800 dark:hover:bg-gray-700 transition">
                                            View
                                        </a>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    @push('scripts')
    <script>
        function contentCalendar() {
            return {
                currentDate: new Date('{{ $date->format("Y-m-d") }}'),
                calendarData: @json($calendar),
                weekDays: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
                selectedDate: null,
                selectedDatePosts: [],

                get currentMonthName() {
                    return this.currentDate.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
                },

                get calendarDays() {
                    const year = this.currentDate.getFullYear();
                    const month = this.currentDate.getMonth();
                    const firstDay = new Date(year, month, 1);
                    const lastDay = new Date(year, month + 1, 0);
                    const prevLastDay = new Date(year, month, 0);
                    
                    const firstDayOfWeek = firstDay.getDay();
                    const lastDateOfMonth = lastDay.getDate();
                    const prevLastDate = prevLastDay.getDate();
                    
                    const days = [];
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    
                    // Previous month days
                    for (let i = firstDayOfWeek - 1; i >= 0; i--) {
                        const dayNum = prevLastDate - i;
                        const date = new Date(year, month - 1, dayNum);
                        days.push(this.createDayObject(date, false));
                    }
                    
                    // Current month days
                    for (let i = 1; i <= lastDateOfMonth; i++) {
                        const date = new Date(year, month, i);
                        days.push(this.createDayObject(date, true));
                    }
                    
                    // Next month days
                    const remainingDays = 42 - days.length;
                    for (let i = 1; i <= remainingDays; i++) {
                        const date = new Date(year, month + 1, i);
                        days.push(this.createDayObject(date, false));
                    }
                    
                    return days;
                },

                createDayObject(date, isCurrentMonth) {
                    const dateStr = date.toISOString().split('T')[0];
                    const posts = this.calendarData[dateStr] || [];
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    date.setHours(0, 0, 0, 0);
                    
                    return {
                        date: dateStr,
                        dayNumber: date.getDate(),
                        isCurrentMonth: isCurrentMonth,
                        isToday: date.getTime() === today.getTime(),
                        posts: posts,
                        postCount: posts.length
                    };
                },

                get selectedDateFormatted() {
                    if (!this.selectedDate) return '';
                    const date = new Date(this.selectedDate);
                    return date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                },

                async selectDate(day) {
                    if (day.postCount === 0) return;
                    
                    this.selectedDate = day.date;
                    this.selectedDatePosts = day.posts.map(post => ({
                        ...post,
                        scheduled_at_time: post.scheduled_at ? new Date(post.scheduled_at).toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' }) : ''
                    }));
                },

                async previousMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() - 1, 1);
                    await this.fetchMonthData();
                },

                async nextMonth() {
                    this.currentDate = new Date(this.currentDate.getFullYear(), this.currentDate.getMonth() + 1, 1);
                    await this.fetchMonthData();
                },

                async fetchMonthData() {
                    const month = `${this.currentDate.getFullYear()}-${String(this.currentDate.getMonth() + 1).padStart(2, '0')}`;
                    
                    try {
                        const response = await fetch(`/social/calendar/month-data?month=${month}`);
                        const data = await response.json();
                        
                        if (data.success) {
                            this.calendarData = data.data;
                        }
                    } catch (error) {
                        console.error('Failed to fetch calendar data:', error);
                    }
                }
            };
        }
    </script>
    @endpush

    <style>
        [x-cloak] { display: none !important; }
    </style>
</x-app-layout>
