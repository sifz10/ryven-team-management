<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title>Welcome Back - <?php echo e(config('app.name')); ?></title>
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
</head>
<body class="h-full">
    <!-- Modern Split Layout -->
    <div class="min-h-full flex">
        <!-- Left Side - Branding -->
        <div class="hidden lg:flex lg:flex-1 relative bg-gradient-to-br from-black via-gray-900 to-gray-800 dark:from-gray-900 dark:via-black dark:to-gray-900">
            <!-- Decorative Background Pattern -->
            <div class="absolute inset-0 opacity-10">
                <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 40px 40px;"></div>
            </div>

            <div class="relative flex flex-col justify-between p-12 text-white">
                <!-- Logo & Brand -->
                <div>
                    <img src="<?php echo e(asset('white-logo.png')); ?>" alt="Ryven Logo" class="h-16 w-auto mb-6">
                    <h1 class="text-5xl font-bold mb-4"><?php echo e(config('app.name')); ?></h1>
                    <p class="text-xl text-gray-300 font-light">Empowering teams, one project at a time</p>
                </div>

                <!-- Features -->
                <div class="space-y-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Lightning Fast</h3>
                            <p class="text-gray-400">Access your projects and collaborate in real-time</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Secure & Private</h3>
                            <p class="text-gray-400">Enterprise-grade security for your data</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-white/10 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-lg">Team Collaboration</h3>
                            <p class="text-gray-400">Work together seamlessly with our team</p>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="text-sm text-gray-400">
                    © <?php echo e(date('Y')); ?> <?php echo e(config('app.name')); ?>. All rights reserved.
                </div>
            </div>
        </div>

        <!-- Right Side - Login Form -->
        <div class="flex-1 flex items-center justify-center p-8 bg-gray-50 dark:bg-gray-900">
            <div class="w-full max-w-md">
                <!-- Mobile Logo -->
                <div class="lg:hidden text-center mb-8">
                    <img src="<?php echo e(asset('black-logo.png')); ?>" alt="Ryven Logo" class="h-12 w-auto mx-auto mb-4 dark:invert">
                    <h1 class="text-3xl font-bold text-black dark:text-white mb-2"><?php echo e(config('app.name')); ?></h1>
                    <p class="text-gray-600 dark:text-gray-400">Client Portal</p>
                </div>

                <!-- Welcome Text -->
                <div class="mb-8">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Welcome back</h2>
                    <p class="text-gray-600 dark:text-gray-400">Sign in to access your client portal</p>
                </div>

                <!-- Login Card -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 p-8 shadow-lg" x-data="{
                    loginMethod: 'otp',
                    otpSent: false,
                    email: '<?php echo e(old('email')); ?>',
                    sending: false,
                    errorMessage: '',
                    successMessage: '',

                    async sendOtp() {
                        if (!this.email) {
                            this.errorMessage = 'Please enter your email address';
                            return;
                        }

                        this.sending = true;
                        this.errorMessage = '';
                        this.successMessage = '';

                        try {
                            const response = await fetch('<?php echo e(route('client.login.send-otp')); ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ email: this.email })
                            });

                            const data = await response.json();

                            if (response.ok && data.success) {
                                this.successMessage = data.message;
                                this.otpSent = true;
                            } else {
                                this.errorMessage = data.message || 'Failed to send OTP. Please try again.';
                            }
                        } catch (error) {
                            this.errorMessage = 'Network error. Please check your connection and try again.';
                        } finally {
                            this.sending = false;
                        }
                    }
                }">
            <?php if(session('status')): ?>
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-lg text-sm">
                    <?php echo e(session('status')); ?>

                </div>
            <?php endif; ?>

            <?php if($errors->any()): ?>
                <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg text-sm">
                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <p><?php echo e($error); ?></p>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            <?php endif; ?>

            <!-- Dynamic Error Message -->
            <div x-show="errorMessage" x-transition class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-700 text-red-800 dark:text-red-300 rounded-lg text-sm">
                <p x-text="errorMessage"></p>
            </div>

            <!-- Dynamic Success Message -->
            <div x-show="successMessage" x-transition class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-300 rounded-lg text-sm">
                <p x-text="successMessage"></p>
            </div>

            <form method="POST" action="<?php echo e(route('client.login.post')); ?>" class="space-y-6">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="login_method" x-model="loginMethod">

                <!-- OTP Login (Default) -->
                <div x-show="loginMethod === 'otp'">
                    <!-- Email Input (before OTP sent) -->
                    <div x-show="!otpSent" x-transition>
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-black/5 dark:bg-white/5 mb-4">
                                <svg class="w-8 h-8 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Login with OTP</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Enter your email and we'll send you a one-time password to sign in securely. No need to remember passwords!
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Email Address
                                </label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       x-model="email"
                                       required
                                       autofocus
                                       class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            </div>

                            <button type="button"
                                    @click="sendOtp()"
                                    :disabled="sending"
                                    class="w-full py-3 px-4 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
                                <span x-show="!sending">Send OTP to Email</span>
                                <span x-show="sending" class="flex items-center justify-center gap-2">
                                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </div>

                    <!-- OTP Entry (after OTP sent) -->
                    <div x-show="otpSent" x-transition>
                        <div class="text-center mb-6">
                            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 dark:bg-green-900/20 mb-4">
                                <svg class="w-8 h-8 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Check Your Email</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                We've sent a 6-digit code to <span class="font-semibold" x-text="email"></span>
                            </p>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label for="otp" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    Enter OTP Code
                                </label>
                                <input type="text"
                                       name="otp"
                                       id="otp"
                                       placeholder="000000"
                                       maxlength="6"
                                       required
                                       class="w-full px-4 py-4 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent text-center text-2xl tracking-[0.5em] font-bold">
                            </div>

                            <button type="submit"
                                    class="w-full py-3 px-4 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                Verify & Sign In
                            </button>

                            <button type="button"
                                    @click="otpSent = false"
                                    class="w-full py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition-colors">
                                ← Back to email
                            </button>
                        </div>
                    </div>

                    <!-- Switch to Password Option -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                @click="loginMethod = 'password'"
                                class="w-full flex items-center justify-center gap-2 py-3 px-4 text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-all group">
                            <span class="text-xl">🤔</span>
                            <span class="font-medium">Wait, I remember my password!</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Password Login -->
                <div x-show="loginMethod === 'password'" x-transition>
                    <div class="text-center mb-6">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-black/5 dark:bg-white/5 mb-4">
                            <svg class="w-8 h-8 text-black dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2">Sign In with Password</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            For existing clients only
                        </p>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label for="email_password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                Email Address
                            </label>
                            <input type="email"
                                   name="email"
                                   id="email_password"
                                   x-model="email"
                                   required
                                   class="w-full px-4 py-3 border dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> !border-red-500 <?php else: ?> border-gray-300 <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label for="password" class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    Password
                                </label>
                                <a href="#" class="text-xs text-black dark:text-white font-semibold hover:underline">
                                    Forgot password?
                                </a>
                            </div>
                            <input type="password"
                                   name="password"
                                   id="password"
                                   required
                                   class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-gray-900 dark:text-white focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        </div>

                        <div class="flex items-center">
                            <input type="checkbox"
                                   name="remember"
                                   id="remember"
                                   class="w-4 h-4 border-gray-300 dark:border-gray-600 rounded text-black dark:text-white focus:ring-black dark:focus:ring-white">
                            <label for="remember" class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                                Remember me
                            </label>
                        </div>

                        <button type="submit"
                                class="w-full py-3 px-4 bg-black dark:bg-white text-white dark:text-black font-semibold rounded-full hover:bg-gray-800 dark:hover:bg-gray-100 transition-all shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                            Sign In to Portal
                        </button>
                    </div>

                    <!-- Switch to OTP Option -->
                    <div class="mt-8 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <button type="button"
                                @click="loginMethod = 'otp'; otpSent = false"
                                class="w-full flex items-center justify-center gap-2 py-3 px-4 text-gray-700 dark:text-gray-300 hover:text-black dark:hover:text-white transition-all group">
                            <span class="text-xl">🔐</span>
                            <span class="font-medium">Prefer to use OTP instead?</span>
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </form>

            <!-- Additional Links -->
            <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-700 space-y-4">
                <div class="text-center">
                    <a href="/admin/login" class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-black dark:hover:text-white transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        Admin & Employee Login
                    </a>
                </div>

                <div class="text-center">
                    <p class="text-sm text-gray-600 dark:text-gray-400">
                        Need help? <a href="mailto:<?php echo e(config('mail.from.address')); ?>" class="text-black dark:text-white font-semibold hover:underline">Contact Support</a>
                    </p>
                </div>
            </div>
        </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php /**PATH F:\Project\salary\resources\views/client/auth/login.blade.php ENDPATH**/ ?>