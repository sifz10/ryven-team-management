<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $job->title }} - Join Our Team</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .slide-in { animation: slideIn 0.6s ease-out forwards; }
        .stagger-1 { animation-delay: 0.1s; }
        .stagger-2 { animation-delay: 0.2s; }
        .stagger-3 { animation-delay: 0.3s; }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 via-white to-gray-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
    <!-- Header with Logo -->
    <header class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Logo Image -->
                    <img src="{{ asset('black-logo.png') }}" alt="Company Logo" class="h-12 w-auto dark:invert">
                </div>
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 bg-gradient-to-r from-green-400 to-emerald-500 text-white rounded-full text-sm font-semibold shadow-lg">
                        ✨ Now Hiring
                    </span>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Banner -->
    <div class="bg-gradient-to-r from-black via-gray-800 to-black dark:from-white dark:via-gray-100 dark:to-white py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto text-center">
            <h1 class="text-4xl md:text-5xl font-bold text-white dark:text-black mb-4 slide-in">
                {{ $job->title }}
            </h1>
            <div class="flex flex-wrap items-center justify-center gap-3 mb-6 slide-in stagger-1">
                @if($job->department)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 dark:bg-black/20 backdrop-blur-sm text-white dark:text-black rounded-full text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        {{ $job->department }}
                    </span>
                @endif
                <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 dark:bg-black/20 backdrop-blur-sm text-white dark:text-black rounded-full text-sm font-medium">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ ucfirst(str_replace('-', ' ', $job->job_type)) }}
                </span>
                @if($job->location)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 dark:bg-black/20 backdrop-blur-sm text-white dark:text-black rounded-full text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        {{ $job->location }}
                    </span>
                @endif
                @if($job->experience_level)
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/20 dark:bg-black/20 backdrop-blur-sm text-white dark:text-black rounded-full text-sm font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                        {{ ucfirst($job->experience_level) }} Level
                    </span>
                @endif
            </div>
            @if($job->application_deadline)
                <div class="flex items-center justify-center gap-2 text-sm text-white/90 dark:text-black/90 slide-in stagger-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Apply before {{ $job->application_deadline->format('F d, Y') }}
                </div>
            @endif
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Column - Job Details -->
            <div class="lg:col-span-2 space-y-6">

                <!-- Description -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 p-6 md:p-8 slide-in">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center flex-shrink-0 shadow-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-black dark:text-white">About the Position</h2>
                    </div>
                    <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                        {!! nl2br(e($job->description)) !!}
                    </div>
                </div>

                <!-- Requirements -->
                @if($job->requirements)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 p-6 md:p-8 slide-in stagger-1">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-black dark:text-white">Requirements</h2>
                        </div>
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! nl2br(e($job->requirements)) !!}
                        </div>
                    </div>
                @endif

                <!-- Responsibilities -->
                @if($job->responsibilities)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 p-6 md:p-8 slide-in stagger-2">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-black dark:text-white">Responsibilities</h2>
                        </div>
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! nl2br(e($job->responsibilities)) !!}
                        </div>
                    </div>
                @endif

                <!-- Benefits -->
                @if($job->benefits)
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300 border border-gray-100 dark:border-gray-700 p-6 md:p-8 slide-in stagger-3">
                        <div class="flex items-start gap-3 mb-4">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-green-400 to-emerald-500 flex items-center justify-center flex-shrink-0 shadow-md">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-black dark:text-white">What We Offer</h2>
                        </div>
                        <div class="prose dark:prose-invert max-w-none text-gray-700 dark:text-gray-300 leading-relaxed">
                            {!! nl2br(e($job->benefits)) !!}
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column - Application Form -->
            <div class="lg:col-span-1">
                <div class="bg-gradient-to-br from-white to-gray-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700 p-6 md:p-8 sticky top-24 slide-in stagger-3">
                    <div class="text-center mb-6">
                        <div class="w-16 h-16 bg-gradient-to-br from-black to-gray-700 dark:from-white dark:to-gray-300 rounded-full flex items-center justify-center mx-auto mb-4 shadow-lg float-animation">
                            <svg class="w-9 h-9 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <h2 class="text-2xl font-bold text-black dark:text-white mb-2">Apply Now!</h2>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Let's start your journey with us</p>
                    </div>

                    @if ($errors->any())
                        <div class="bg-red-50 dark:bg-red-900/20 border-2 border-red-200 dark:border-red-800 rounded-xl p-4 mb-6 animate-bounce">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="flex-1">
                                    <h4 class="font-semibold text-red-800 dark:text-red-200 mb-1">Oops! Please check:</h4>
                                    <ul class="list-disc list-inside space-y-1 text-sm text-red-700 dark:text-red-300">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('jobs.apply', $job->slug) }}" method="POST" enctype="multipart/form-data" class="space-y-4" id="application-form">
                        @csrf

                        <!-- First Name -->
                        <div class="group">
                            <label for="first_name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" required
                                   placeholder="John"
                                   class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all duration-200 @error('first_name') border-red-500 dark:border-red-500 animate-shake @enderror">
                            @error('first_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Last Name -->
                        <div class="group">
                            <label for="last_name" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" required
                                   placeholder="Doe"
                                   class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all duration-200 @error('last_name') border-red-500 dark:border-red-500 animate-shake @enderror">
                            @error('last_name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="group">
                            <label for="email" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                Email Address <span class="text-red-500">*</span>
                            </label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                   placeholder="john@example.com"
                                   class="w-full px-4 py-3 border-2 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all duration-200 @error('email') border-red-500 dark:border-red-500 animate-shake @else border-gray-300 dark:border-gray-600 @enderror">
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="group">
                            <label for="phone" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
                                   placeholder="+1 (555) 000-0000"
                                   class="w-full px-4 py-3 border-2 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all duration-200 @error('phone') border-red-500 dark:border-red-500 animate-shake @else border-gray-300 dark:border-gray-600 @enderror">
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Resume Upload -->
                        <div class="group">
                            <label class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Resume/CV <span class="text-red-500">*</span>
                            </label>
                            <div class="relative border-2 border-dashed rounded-xl p-6 text-center hover:border-black dark:hover:border-white hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-all duration-300 cursor-pointer @error('resume') border-red-500 dark:border-red-500 animate-shake @else border-gray-300 dark:border-gray-600 @enderror">
                                <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" required
                                       class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="updateFileName(this)">
                                <div class="pointer-events-none">
                                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-500 dark:text-gray-400 group-hover:text-black dark:group-hover:text-white transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                    </svg>
                                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Drop your resume here or click to browse
                                    </span>
                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">PDF, DOC, DOCX • Max 10MB</p>
                                    <p id="file-name" class="text-sm font-semibold text-green-600 dark:text-green-400 mt-3 hidden"></p>
                                </div>
                            </div>
                            @error('resume')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cover Letter -->
                        <div class="group">
                            <label for="cover_letter" class="flex items-center gap-2 text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Cover Letter <span class="text-gray-400 text-xs">(Optional)</span>
                            </label>
                            <textarea name="cover_letter" id="cover_letter" rows="4"
                                      placeholder="Tell us why you're a great fit for this role..."
                                      class="w-full px-4 py-3 border-2 border-gray-300 dark:border-gray-600 rounded-xl bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all duration-200 group-hover:border-gray-400 resize-none">{{ old('cover_letter') }}</textarea>
                        </div>

                        <!-- Screening Questions -->
                        @if($job->questions->count() > 0)
                            <div class="border-t border-gray-200 dark:border-gray-700 pt-6" x-data="{ expanded: false }">
                                <button type="button" @click="expanded = !expanded"
                                        class="w-full flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-800 dark:to-gray-700 rounded-xl hover:from-gray-100 hover:to-gray-200 dark:hover:from-gray-700 dark:hover:to-gray-600 transition-all duration-200 group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-black dark:bg-white flex items-center justify-center group-hover:scale-110 transition-transform">
                                            <svg class="w-5 h-5 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div class="text-left">
                                            <h3 class="text-base font-bold text-black dark:text-white flex items-center gap-2">
                                                Screening Questions
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md bg-black dark:bg-white text-white dark:text-black text-xs font-semibold">
                                                    {{ $job->questions->count() }}
                                                </span>
                                            </h3>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                                                <span x-show="!expanded">Click to expand and answer questions</span>
                                                <span x-show="expanded" x-cloak>Click to collapse</span>
                                            </p>
                                        </div>
                                    </div>
                                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-400 transition-transform duration-200"
                                         :class="expanded ? 'rotate-180' : ''"
                                         fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>

                                <div x-show="expanded"
                                     x-collapse
                                     x-cloak
                                     class="mt-4 space-y-4 pl-1">
                                    @foreach($job->questions as $index => $question)
                                        <div class="p-4 bg-white dark:bg-gray-800 border-l-4 border-black dark:border-white rounded-r-xl shadow-sm hover:shadow-md transition-shadow">
                                            <label class="block text-sm font-semibold text-gray-900 dark:text-white mb-3">
                                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-black dark:bg-white text-white dark:text-black text-xs font-bold mr-2">
                                                    {{ $index + 1 }}
                                                </span>
                                                {{ $question->question }}
                                                @if($question->is_required)
                                                    <span class="text-red-500 ml-1">*</span>
                                                @endif
                                            </label>

                                            @if($question->type === 'text')
                                                <input type="text"
                                                       name="answers[{{ $question->id }}]"
                                                       value="{{ old('answers.' . $question->id) }}"
                                                       {{ $question->is_required ? 'required' : '' }}
                                                       placeholder="Type your answer here..."
                                                       class="w-full px-4 py-2.5 border-2 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all @error('answers.' . $question->id) border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                                @error('answers.' . $question->id)
                                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                                @enderror

                                            @elseif($question->type === 'textarea')
                                                <textarea name="answers[{{ $question->id }}]"
                                                          rows="3"
                                                          {{ $question->is_required ? 'required' : '' }}
                                                          placeholder="Provide a detailed answer..."
                                                          class="w-full px-4 py-2.5 border-2 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all resize-none @error('answers.' . $question->id) border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">{{ old('answers.' . $question->id) }}</textarea>
                                                @error('answers.' . $question->id)
                                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                                @enderror

                                            @elseif($question->type === 'file')
                                                <input type="file"
                                                       name="answers[{{ $question->id }}]"
                                                       accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                                       {{ $question->is_required ? 'required' : '' }}
                                                       class="w-full px-4 py-2.5 border-2 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white dark:file:bg-white dark:file:text-black hover:file:opacity-80 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all @error('answers.' . $question->id) border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">PDF, DOC, DOCX, JPG, PNG • Max 10MB</p>
                                                @error('answers.' . $question->id)
                                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                                @enderror

                                            @elseif($question->type === 'video')
                                                <input type="file"
                                                       name="answers[{{ $question->id }}]"
                                                       accept="video/*"
                                                       {{ $question->is_required ? 'required' : '' }}
                                                       class="w-full px-4 py-2.5 border-2 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white dark:file:bg-white dark:file:text-black hover:file:opacity-80 focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-black dark:focus:border-white transition-all @error('answers.' . $question->id) border-red-500 dark:border-red-500 @else border-gray-300 dark:border-gray-600 @enderror">
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1.5">Video formats • Max 50MB</p>
                                                @error('answers.' . $question->id)
                                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                                @enderror

                                            @elseif($question->type === 'multiple_choice' && $question->options)
                                                <div class="space-y-2 @error('answers.' . $question->id) animate-shake @enderror">
                                                    @foreach($question->options as $option)
                                                        <label class="flex items-center gap-3 p-3 rounded-lg border-2 border-gray-200 dark:border-gray-700 hover:border-black dark:hover:border-white hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-all">
                                                            <input type="radio"
                                                                   name="answers[{{ $question->id }}]"
                                                                   value="{{ $option }}"
                                                                   {{ old('answers.' . $question->id) == $option ? 'checked' : '' }}
                                                                   {{ $question->is_required ? 'required' : '' }}
                                                                   class="w-4 h-4 text-black dark:text-white border-gray-300 dark:border-gray-600 focus:ring-2 focus:ring-black dark:focus:ring-white">
                                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ $option }}</span>
                                                        </label>
                                                    @endforeach
                                                </div>
                                                @error('answers.' . $question->id)
                                                    <p class="text-red-500 text-xs mt-1.5">{{ $message }}</p>
                                                @enderror
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <button type="submit"
                                class="group relative w-full px-6 py-4 bg-black dark:bg-white text-white dark:text-black rounded-full font-bold text-lg hover:scale-105 active:scale-95 transition-all duration-300 shadow-xl hover:shadow-2xl overflow-hidden">
                            <span class="relative z-10 flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                                Submit Application
                            </span>
                            <!-- Shine effect -->
                            <span class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-1000 bg-gradient-to-r from-transparent via-white/20 to-transparent"></span>
                        </button>

                        <div class="text-center space-y-2">
                            <p class="text-xs text-gray-500 dark:text-gray-400">
                                🔒 Your data is secure and confidential
                            </p>
                            <p class="text-xs text-gray-400 dark:text-gray-500">
                                By submitting, you agree to our terms and conditions
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gradient-to-r from-gray-900 via-black to-gray-900 dark:from-gray-100 dark:via-white dark:to-gray-100 border-t border-gray-800 dark:border-gray-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('black-logo.png') }}" alt="Company Logo" class="h-10 w-auto invert dark:invert-0">
                </div>
                <p class="text-sm text-gray-400 dark:text-gray-600">
                    © {{ date('Y') }} All rights reserved.
                </p>
            </div>
        </div>
    </footer>

    <script>
        function updateFileName(input) {
            const fileName = input.files[0]?.name;
            const fileNameDisplay = document.getElementById('file-name');
            if (fileName) {
                fileNameDisplay.textContent = '✓ ' + fileName;
                fileNameDisplay.classList.remove('hidden');

                // Add success animation
                fileNameDisplay.style.opacity = '0';
                setTimeout(() => {
                    fileNameDisplay.style.transition = 'opacity 0.5s ease-in-out';
                    fileNameDisplay.style.opacity = '1';
                }, 100);
            } else {
                fileNameDisplay.classList.add('hidden');
            }
        }

        // Add smooth scroll behavior
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });

        // Add form validation feedback
        const form = document.querySelector('form');
        if (form) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            inputs.forEach(input => {
                input.addEventListener('blur', function() {
                    if (this.value.trim() === '') {
                        this.classList.add('border-red-300', 'dark:border-red-600');
                        this.classList.remove('border-gray-300', 'dark:border-gray-600');
                    } else {
                        this.classList.remove('border-red-300', 'dark:border-red-600');
                        this.classList.add('border-green-300', 'dark:border-green-600');
                    }
                });
            });
        }
    </script>
</body>
</html>
