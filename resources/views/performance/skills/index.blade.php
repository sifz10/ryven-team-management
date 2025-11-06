<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Skills Management') }}
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Track employee competencies and proficiency levels</p>
            </div>
            <a href="{{ route('skills.create') }}" 
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Skill
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if($skills->isEmpty())
                        <div class="text-center py-16">
                            <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-gray-800 to-black mb-6">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                            </div>
                            <h3 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">No Skills Yet</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                Add skills to track employee competencies and proficiency levels across your team.
                            </p>
                            <a href="{{ route('skills.create') }}" 
                                class="inline-flex items-center gap-2 px-6 py-3 bg-black text-white rounded-full shadow-lg hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-700 focus:ring-offset-2 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add First Skill
                            </a>
                        </div>
                    @else
                        <!-- Category Tabs -->
                        <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                            <nav class="-mb-px flex space-x-8">
                                <a href="?category=" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('category') == '' ? 'border-blue-500 text-blue-600' : '' }}">
                                    All ({{ $skills->total() }})
                                </a>
                                <a href="?category=technical" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('category') == 'technical' ? 'border-blue-500 text-blue-600' : '' }}">
                                    Technical
                                </a>
                                <a href="?category=soft" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('category') == 'soft' ? 'border-blue-500 text-blue-600' : '' }}">
                                    Soft Skills
                                </a>
                                <a href="?category=leadership" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('category') == 'leadership' ? 'border-blue-500 text-blue-600' : '' }}">
                                    Leadership
                                </a>
                                <a href="?category=domain" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('category') == 'domain' ? 'border-blue-500 text-blue-600' : '' }}">
                                    Domain
                                </a>
                                <a href="?category=tool" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm {{ request('category') == 'tool' ? 'border-blue-500 text-blue-600' : '' }}">
                                    Tools
                                </a>
                            </nav>
                        </div>

                        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                            @foreach($skills as $skill)
                                <div class="border dark:border-gray-700 rounded-lg p-4 hover:shadow-md transition">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-lg">{{ $skill->name }}</h3>
                                            <span class="inline-block mt-1 px-2 py-0.5 text-xs font-semibold rounded 
                                                @if($skill->category === 'technical') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @elseif($skill->category === 'soft') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($skill->category === 'leadership') bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200
                                                @elseif($skill->category === 'domain') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                                                @endif">
                                                {{ ucfirst($skill->category) }}
                                            </span>
                                        </div>
                                        @if($skill->is_active)
                                            <span class="text-green-500">✓</span>
                                        @else
                                            <span class="text-gray-400">○</span>
                                        @endif
                                    </div>
                                    
                                    @if($skill->description)
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">{{ $skill->description }}</p>
                                    @endif

                                    <div class="flex items-center justify-between text-sm">
                                        <span class="text-gray-600 dark:text-gray-400">
                                            {{ $skill->employee_skills_count }} employee(s)
                                        </span>
                                        <div class="flex gap-2">
                                            <a href="{{ route('skills.show', $skill) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                            <a href="{{ route('skills.edit', $skill) }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-300">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-6">
                            {{ $skills->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
