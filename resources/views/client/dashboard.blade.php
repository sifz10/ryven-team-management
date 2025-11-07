<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Client Dashboard - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-gray-900">
    <div class="min-h-full">
        <!-- Navigation -->
        <nav class="bg-black dark:bg-white border-b border-gray-800 dark:border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <h1 class="text-xl font-bold text-white dark:text-black">{{ config('app.name') }}</h1>
                        <span class="ml-4 px-3 py-1 bg-gray-800 dark:bg-gray-200 text-white dark:text-black text-sm rounded-full">Client Portal</span>
                    </div>
                    <div class="flex items-center gap-4">
                        <span class="text-white dark:text-black text-sm">{{ $client->name }}</span>
                        <form method="POST" action="{{ route('client.logout') }}">
                            @csrf
                            <button type="submit" class="text-white dark:text-black hover:opacity-70 text-sm font-semibold">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            @if (session('status'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-xl flex items-center gap-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Welcome Section -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">
                    Welcome back, {{ $client->name }}!
                </h2>
                <p class="text-gray-600 dark:text-gray-400">
                    Here's an overview of your projects and activities.
                </p>
            </div>

            <!-- Client Info Card -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6 mb-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Your Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Email</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $client->email }}</p>
                    </div>
                    @if($client->phone)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Phone</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $client->phone }}</p>
                    </div>
                    @endif
                    @if($client->company)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Company</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $client->company }}</p>
                    </div>
                    @endif
                    @if($client->address)
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Address</p>
                        <p class="font-semibold text-gray-900 dark:text-white">{{ $client->address }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Projects Section -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-6">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">
                    {{ isset($teamMember) && $teamMember->projects()->count() > 0 ? 'Your Assigned Projects' : 'Your Projects' }}
                </h3>

                @if($projects->count() > 0)
                    <div class="space-y-4">
                        @foreach($projects as $project)
                            <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-900 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-bold text-gray-900 dark:text-white mb-1">{{ $project->name }}</h4>
                                        @if($project->description)
                                            <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">{{ Str::limit($project->description, 150) }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-sm">
                                            <span class="text-gray-600 dark:text-gray-400">
                                                Status:
                                                <span class="font-semibold capitalize {{ $project->status === 'active' ? 'text-green-600 dark:text-green-400' : 'text-gray-600 dark:text-gray-400' }}">
                                                    {{ $project->status }}
                                                </span>
                                            </span>
                                            <span class="text-gray-600 dark:text-gray-400">
                                                Progress: <span class="font-semibold">{{ $project->progress }}%</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">No Projects Yet</h3>
                        <p class="text-gray-600 dark:text-gray-400">Projects will appear here once they are assigned to you.</p>
                    </div>
                @endif
            </div>
        </main>
    </div>
</body>
</html>
