<?php if (isset($component)) { $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54 = $attributes; } ?>
<?php $component = App\View\Components\AppLayout::resolve([] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('app-layout'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\App\View\Components\AppLayout::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
    <div class="py-6 h-screen flex flex-col" x-data="aiAgent()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 flex-1 flex flex-col">
            <!-- Modern Header -->
            <div class="mb-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <!-- AI Icon with Animation -->
                        <div class="relative">
                            <div class="w-12 h-12 bg-black dark:bg-white rounded-full flex items-center justify-center">
                                <svg class="w-7 h-7 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div x-show="isLoading" class="absolute inset-0 border-2 border-black dark:border-white border-t-transparent rounded-full animate-spin"></div>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">AI Assistant</h2>
                            <div class="flex items-center space-x-2 mt-1">
                                <div class="flex items-center space-x-1.5">
                                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                                    <span class="text-sm text-gray-600 dark:text-gray-400">Online</span>
                                </div>
                                <span class="text-gray-400">•</span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Real-time enabled</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-3">
                        <!-- Voice Status Badge -->
                        <div class="flex items-center space-x-2 px-3 py-1.5 rounded-lg bg-gray-100 dark:bg-gray-700">
                            <svg class="w-4 h-4" :class="isListening ? 'text-red-500 animate-pulse' : 'text-gray-500 dark:text-gray-400'" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-xs font-medium" :class="isListening ? 'text-red-500' : 'text-gray-600 dark:text-gray-400'">
                                <span x-show="!isListening">Voice Ready</span>
                                <span x-show="isListening">Listening...</span>
                            </span>
                        </div>

                        <!-- Clear Chat Button -->
                        <?php if (isset($component)) { $__componentOriginal658398a0e73a18931bb7def04d911f42 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal658398a0e73a18931bb7def04d911f42 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon-button','data' => ['variant' => 'black','@click' => 'clearConversation()','title' => 'Clear conversation']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon-button'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['variant' => 'black','@click' => 'clearConversation()','title' => 'Clear conversation']); ?>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $attributes = $__attributesOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__attributesOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal658398a0e73a18931bb7def04d911f42)): ?>
<?php $component = $__componentOriginal658398a0e73a18931bb7def04d911f42; ?>
<?php unset($__componentOriginal658398a0e73a18931bb7def04d911f42); ?>
<?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Modern Chat Container -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 flex-1 flex flex-col overflow-hidden">
                <!-- Messages Area with Modern Styling -->
                <div id="messages-container" class="flex-1 overflow-y-auto p-6 space-y-6 bg-gradient-to-b from-gray-50 to-white dark:from-gray-900 dark:to-gray-800">
                    <!-- Modern Welcome Message -->
                    <div class="flex items-start space-x-4 animate-fade-in">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 bg-black dark:bg-white rounded-full flex items-center justify-center shadow-lg">
                                <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 max-w-3xl">
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl rounded-tl-none p-5 shadow-sm">
                                <p class="text-base font-medium text-gray-900 dark:text-white mb-3">
                                    👋 Welcome! I'm your AI assistant
                                </p>
                                <p class="text-sm text-gray-700 dark:text-gray-300 mb-4">
                                    I can help you manage your team effectively. Here's what I can do:
                                </p>
                                <div class="grid gap-2.5">
                                    <div class="flex items-start space-x-2.5">
                                        <div class="w-5 h-5 rounded-full bg-black dark:bg-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3 h-3 text-white dark:text-black" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Manage employees (add, search, view details)</span>
                                    </div>
                                    <div class="flex items-start space-x-2.5">
                                        <div class="w-5 h-5 rounded-full bg-black dark:bg-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3 h-3 text-white dark:text-black" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Check GitHub activity and find inactive developers</span>
                                    </div>
                                    <div class="flex items-start space-x-2.5">
                                        <div class="w-5 h-5 rounded-full bg-black dark:bg-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3 h-3 text-white dark:text-black" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Generate team statistics and reports</span>
                                    </div>
                                    <div class="flex items-start space-x-2.5">
                                        <div class="w-5 h-5 rounded-full bg-black dark:bg-white flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <svg class="w-3 h-3 text-white dark:text-black" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-700 dark:text-gray-300">Answer questions about your team</span>
                                    </div>
                                </div>
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <p class="text-xs text-gray-600 dark:text-gray-400 flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                        </svg>
                                        <span>Try: <span class="font-medium text-gray-900 dark:text-white">"Who didn't push code today?"</span></span>
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 mt-2 ml-1">
                                <span class="text-xs text-gray-500">Just now</span>
                                <span class="text-gray-400">•</span>
                                <span class="text-xs text-gray-500">AI Assistant</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modern Input Area -->
                <div class="border-t border-gray-200 dark:border-gray-700 p-5 bg-gray-50 dark:bg-gray-900">
                    <form @submit.prevent="sendMessage()">
                        <!-- Main Input Container with Better Alignment -->
                        <div class="flex items-center gap-3">
                            <!-- Voice Input Button - Fixed Height -->
                            <button
                                type="button"
                                @click="toggleVoiceInput()"
                                :class="isListening ? 'animate-pulse bg-red-500 hover:bg-red-600' : 'bg-black hover:bg-gray-800 dark:bg-white dark:hover:bg-gray-200'"
                                class="flex-shrink-0 w-12 h-12 rounded-xl flex items-center justify-center transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:ring-offset-2 shadow-sm"
                                title="Voice input">
                                <svg class="w-5 h-5" :class="isListening ? 'text-white' : 'text-white dark:text-black'" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7 4a3 3 0 016 0v4a3 3 0 11-6 0V4zm4 10.93A7.001 7.001 0 0017 8a1 1 0 10-2 0A5 5 0 015 8a1 1 0 00-2 0 7.001 7.001 0 006 6.93V17H6a1 1 0 100 2h8a1 1 0 100-2h-3v-2.07z" clip-rule="evenodd" />
                                </svg>
                            </button>

                            <!-- Input Field with Icons Inside -->
                            <div class="flex-1 relative">
                                <textarea
                                    id="message-input"
                                    x-model="currentMessage"
                                    @keydown.enter.prevent="if(!$event.shiftKey) sendMessage()"
                                    rows="1"
                                    class="w-full pl-5 pr-12 py-3.5 bg-white dark:bg-gray-800 border-2 border-gray-300 dark:border-gray-600 rounded-xl focus:ring-2 focus:ring-black focus:border-black dark:focus:ring-white dark:focus:border-white dark:text-white resize-none transition-all placeholder-gray-500 dark:placeholder-gray-400 shadow-sm"
                                    placeholder="Ask me anything..."
                                    :disabled="isLoading"
                                    style="min-height: 52px; max-height: 200px;"
                                    @input="autoResize($event.target)"
                                ></textarea>

                                <!-- Character Count Inside Input -->
                                <div class="absolute right-4 bottom-3 pointer-events-none">
                                    <span x-show="currentMessage.length > 0" class="text-xs text-gray-400 dark:text-gray-500" x-text="currentMessage.length"></span>
                                </div>
                            </div>

                            <!-- Send Button - Fixed Height to Match -->
                            <button
                                type="submit"
                                :disabled="isLoading || !currentMessage.trim()"
                                class="flex-shrink-0 h-12 px-6 rounded-xl font-medium transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 bg-black text-white hover:bg-gray-800 focus:ring-black dark:bg-white dark:text-black dark:hover:bg-gray-200 dark:focus:ring-white disabled:opacity-50 disabled:cursor-not-allowed shadow-sm flex items-center gap-2">
                                <span x-show="!isLoading" class="flex items-center gap-2">
                                    <span class="font-semibold">Send</span>
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                </span>
                                <span x-show="isLoading" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="font-semibold">Sending</span>
                                </span>
                            </button>
                        </div>

                        <!-- Helper Text - Cleaner Layout -->
                        <div class="mt-2 px-1">
                            <div class="flex items-center gap-6 text-xs text-gray-500 dark:text-gray-400">
                                <div class="flex items-center gap-2">
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded font-mono text-xs">Enter</kbd>
                                    <span>Send</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded font-mono text-xs">Shift</kbd>
                                    <span>+</span>
                                    <kbd class="px-2 py-1 bg-gray-100 dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded font-mono text-xs">Enter</kbd>
                                    <span>New line</span>
                                </div>
                                <div class="flex items-center gap-2 ml-auto">
                                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                    </svg>
                                    <span>Powered by OpenAI GPT-4o-mini</span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Initialize Alpine.js data
        document.addEventListener('alpine:init', () => {
            Alpine.data('aiAgent', () => ({
                currentMessage: '',
                isLoading: false,
                isListening: false,
                recognition: null,
                messageCount: 0,

                init() {
                    // Check for pending message from floating chat button
                    const pendingMessage = sessionStorage.getItem('aiPendingMessage');
                    if (pendingMessage) {
                        sessionStorage.removeItem('aiPendingMessage');
                        this.currentMessage = pendingMessage;
                        // Auto-send after a short delay
                        setTimeout(() => this.sendMessage(), 500);
                    }

                    // Initialize Speech Recognition
                    if ('webkitSpeechRecognition' in window || 'SpeechRecognition' in window) {
                        const SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;
                        this.recognition = new SpeechRecognition();
                        this.recognition.continuous = false;
                        this.recognition.interimResults = false;
                        this.recognition.lang = 'en-US';

                        this.recognition.onresult = (event) => {
                            const transcript = event.results[0][0].transcript;
                            this.currentMessage = transcript;
                            this.isListening = false;
                            this.showNotification('Voice captured: "' + transcript + '"', 'success');
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

                    // Initialize real-time Echo listener (if available)
                    this.initializeRealtime();

                    // Auto-scroll to bottom
                    this.$nextTick(() => {
                        this.scrollToBottom();
                    });
                },

                initializeRealtime() {
                    // Check if Echo is available
                    if (typeof Echo !== 'undefined') {
                        try {
                            // Listen for new notifications on user channel
                            Echo.private('user.<?php echo e(auth()->id()); ?>')
                                .listen('.notification.new', (e) => {
                                    console.log('Real-time notification received:', e);
                                    this.showNotification('New real-time update received', 'info');
                                });

                            console.log('Real-time connection established');
                        } catch (error) {
                            console.warn('Real-time not available:', error);
                        }
                    }
                },

                autoResize(textarea) {
                    textarea.style.height = 'auto';
                    textarea.style.height = Math.min(textarea.scrollHeight, 200) + 'px';
                },

                toggleVoiceInput() {
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

                async sendMessage() {
                    if (!this.currentMessage.trim() || this.isLoading) return;

                    const userMessage = this.currentMessage.trim();
                    this.currentMessage = '';
                    this.isLoading = true;

                    // Add user message to chat
                    this.addMessage('user', userMessage);

                    try {
                        const response = await fetch('<?php echo e(route('ai-agent.command')); ?>', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
                            },
                            body: JSON.stringify({ message: userMessage })
                        });

                        const data = await response.json();

                        if (data.success) {
                            this.addMessage('assistant', data.message);
                        } else {
                            this.addMessage('assistant', data.message || 'Sorry, something went wrong.', true);
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        this.addMessage('assistant', 'Sorry, I encountered an error processing your request.', true);
                    } finally {
                        this.isLoading = false;
                    }
                },

                addMessage(role, content, isError = false) {
                    const container = document.getElementById('messages-container');
                    const messageDiv = document.createElement('div');
                    messageDiv.className = 'flex items-start space-x-4 animate-fade-in';
                    this.messageCount++;

                    const timestamp = new Date().toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit' });

                    if (role === 'user') {
                        messageDiv.innerHTML = `
                            <div class="flex-1"></div>
                            <div class="flex-1 flex flex-col items-end max-w-3xl">
                                <div class="bg-black dark:bg-white text-white dark:text-black rounded-2xl rounded-tr-none p-4 shadow-lg">
                                    <p class="text-sm whitespace-pre-wrap">${this.escapeHtml(content)}</p>
                                </div>
                                <div class="flex items-center space-x-2 mt-2 mr-1">
                                    <span class="text-xs text-gray-500">${timestamp}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="text-xs text-gray-500">You</span>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-gray-200 dark:bg-gray-700 rounded-full flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                            </div>
                        `;
                    } else {
                        const bgColor = isError ? 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' : 'bg-white dark:bg-gray-800 border-gray-200 dark:border-gray-700';
                        const textColor = isError ? 'text-red-900 dark:text-red-100' : 'text-gray-900 dark:text-white';

                        messageDiv.innerHTML = `
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 bg-black dark:bg-white rounded-full flex items-center justify-center shadow-lg">
                                    <svg class="w-6 h-6 text-white dark:text-black" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="flex-1 max-w-3xl">
                                <div class="${bgColor} border rounded-2xl rounded-tl-none p-4 shadow-sm">
                                    <p class="text-sm ${textColor} whitespace-pre-wrap">${this.formatMessage(content)}</p>
                                </div>
                                <div class="flex items-center space-x-2 mt-2 ml-1">
                                    <span class="text-xs text-gray-500">${timestamp}</span>
                                    <span class="text-gray-400">•</span>
                                    <span class="text-xs text-gray-500">AI Assistant</span>
                                    ${isError ? '<span class="text-gray-400">•</span><span class="text-xs text-red-500">Error</span>' : ''}
                                </div>
                            </div>
                        `;
                    }

                    container.appendChild(messageDiv);
                    this.scrollToBottom();
                },

                formatMessage(content) {
                    // Convert markdown-style formatting to HTML
                    content = this.escapeHtml(content);
                    content = content.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    content = content.replace(/\*(.*?)\*/g, '<em>$1</em>');
                    content = content.replace(/`(.*?)`/g, '<code class="bg-gray-200 dark:bg-gray-600 px-1 rounded">$1</code>');
                    return content;
                },

                escapeHtml(text) {
                    const map = {
                        '&': '&amp;',
                        '<': '&lt;',
                        '>': '&gt;',
                        '"': '&quot;',
                        "'": '&#039;'
                    };
                    return text.replace(/[&<>"']/g, m => map[m]);
                },

                scrollToBottom() {
                    const container = document.getElementById('messages-container');
                    container.scrollTop = container.scrollHeight;
                },

                clearConversation() {
                    if (confirm('Are you sure you want to clear the conversation?')) {
                        const container = document.getElementById('messages-container');
                        // Keep only the welcome message (first child)
                        while (container.children.length > 1) {
                            container.removeChild(container.lastChild);
                        }
                        this.showNotification('Conversation cleared', 'success');
                    }
                },

                showNotification(message, type = 'info') {
                    // Modern notification system
                    const notification = document.createElement('div');
                    const bgColor = type === 'error' ? 'bg-red-500' : type === 'success' ? 'bg-green-500' : 'bg-black dark:bg-white';
                    const textColor = type === 'error' || type === 'success' ? 'text-white' : 'text-white dark:text-black';

                    notification.className = `fixed top-4 right-4 px-5 py-3.5 rounded-xl shadow-2xl z-50 flex items-center space-x-3 ${bgColor} ${textColor} animate-slide-in-right`;

                    const icon = type === 'error' ?
                        '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>' :
                        type === 'success' ?
                        '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>' :
                        '<svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/></svg>';

                    notification.innerHTML = `
                        ${icon}
                        <span class="font-medium">${message}</span>
                    `;

                    document.body.appendChild(notification);

                    setTimeout(() => {
                        notification.classList.add('animate-fade-out');
                        setTimeout(() => notification.remove(), 300);
                    }, 3000);
                }
            }));
        });
    </script>

    <style>
        @keyframes fade-in {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes slide-in-right {
            from { opacity: 0; transform: translateX(100px); }
            to { opacity: 1; transform: translateX(0); }
        }

        @keyframes fade-out {
            from { opacity: 1; }
            to { opacity: 0; }
        }

        .animate-fade-in {
            animation: fade-in 0.3s ease-out;
        }

        .animate-slide-in-right {
            animation: slide-in-right 0.3s ease-out;
        }

        .animate-fade-out {
            animation: fade-out 0.3s ease-out;
        }

        #messages-container::-webkit-scrollbar {
            width: 8px;
        }

        #messages-container::-webkit-scrollbar-track {
            background: transparent;
        }

        #messages-container::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        #messages-container::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        .dark #messages-container::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }

        .dark #messages-container::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php /**PATH F:\Project\salary\resources\views/ai-agent/index.blade.php ENDPATH**/ ?>