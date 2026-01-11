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
<div class="space-y-6 pb-8">
    <!-- Header -->
    <div class="mb-8">
        <a href="<?php echo e(route('admin.chatbot.index')); ?>" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mb-4 inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Back to Chatbot
        </a>
        <h1 class="text-4xl font-bold text-gray-900 dark:text-white">Chatbot Installation Guide</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-2 text-lg">Professional setup, customization, and best practices</p>
    </div>

    <!-- Table of Contents -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Table of Contents</h2>
        <ul class="grid grid-cols-1 md:grid-cols-2 gap-3">
            <li><a href="#installation" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">→ Installation Steps</a></li>
            <li><a href="#setup" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">→ Quick Setup</a></li>
            <li><a href="#customization" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">→ Customization</a></li>
            <li><a href="#styling" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">→ Styling & Appearance</a></li>
            <li><a href="#advanced" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">→ Advanced Configuration</a></li>
            <li><a href="#troubleshooting" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">→ Troubleshooting</a></li>
        </ul>
    </div>

    <!-- Installation Steps -->
    <section id="installation" class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">1. Installation Steps</h2>
            
            <div class="space-y-6">
                <!-- Step 1 -->
                <div class="border-l-4 border-blue-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Step 1: Create a Widget</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Each installation (website, CRM, app) gets its own widget with a unique token.</p>
                    <div class="mt-4 bg-gray-900 dark:bg-gray-950 rounded p-4 overflow-x-auto">
                        <pre class="text-green-400 text-sm"><code>php artisan tinker
App\Models\ChatbotWidget::create([
    'name' => 'My Website',
    'domain' => 'mywebsite.com',
    'welcome_message' => 'Hi! How can we help?',
    'is_active' => true
]);</code></pre>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">✓ Copy the <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">api_token</code> from the response</p>
                </div>

                <!-- Step 2 -->
                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Step 2: Install Widget Script</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Add this single line to your website/app before closing <code>&lt;/body&gt;</code>:</p>
                    <div class="mt-4 bg-gray-900 dark:bg-gray-950 rounded p-4 overflow-x-auto">
                        <pre class="text-green-400 text-sm"><code>&lt;script src="https://your-domain.com/chatbot-widget.js" 
        data-api-token="cbw_YOUR_TOKEN_HERE" 
        data-widget-url="https://your-domain.com"&gt;
&lt;/script&gt;</code></pre>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">✓ Replace with your actual token and domain</p>
                </div>

                <!-- Step 3 -->
                <div class="border-l-4 border-purple-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Step 3: Start Reverb (Real-Time)</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">For real-time messaging, start the Reverb WebSocket server:</p>
                    <div class="mt-4 bg-gray-900 dark:bg-gray-950 rounded p-4 overflow-x-auto">
                        <pre class="text-green-400 text-sm"><code>php artisan reverb:start</code></pre>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-3">✓ Run in a separate terminal, keep it running</p>
                </div>

                <!-- Step 4 -->
                <div class="border-l-4 border-yellow-500 pl-4">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Step 4: Test & Deploy</h3>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Visit your website and click the chat bubble. Send a test message from the widget.</p>
                    <p class="text-gray-600 dark:text-gray-400 mt-2">Check <code class="bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">/admin/chatbot</code> for incoming messages.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Setup -->
    <section id="setup" class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">2. Quick Setup Reference</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Minimal Setup -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Minimal Setup</h3>
                    <div class="bg-gray-900 dark:bg-gray-950 rounded p-3 overflow-x-auto">
                        <pre class="text-green-400 text-xs"><code>&lt;script src="https://your-domain.com/chatbot-widget.js" 
        data-api-token="cbw_..."&gt;
&lt;/script&gt;</code></pre>
                    </div>
                </div>

                <!-- Full Setup -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Full Setup (with visitor info)</h3>
                    <div class="bg-gray-900 dark:bg-gray-950 rounded p-3 overflow-x-auto">
                        <pre class="text-green-400 text-xs"><code>&lt;script src="https://your-domain.com/chatbot-widget.js" 
        data-api-token="cbw_..." 
        data-widget-url="https://your-domain.com"
        data-visitor-name="John Doe"
        data-visitor-email="john@example.com"
        data-visitor-phone="+1234567890"&gt;
&lt;/script&gt;</code></pre>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Customization -->
    <section id="customization" class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">3. Customization Options</h2>
            
            <div class="space-y-4">
                <!-- Visitor Information -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Visitor Information</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Pre-populate visitor details in the chat:</p>
                    <div class="bg-gray-900 dark:bg-gray-950 rounded p-3 overflow-x-auto">
                        <pre class="text-green-400 text-xs"><code>data-visitor-name="Customer Name"
data-visitor-email="customer@example.com"
data-visitor-phone="+1234567890"
data-visitor-company="Company Name"
data-visitor-metadata='{"user_id": 123, "plan": "premium"}'</code></pre>
                    </div>
                </div>

                <!-- Welcome Message -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Welcome Message</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Set a custom greeting message from the admin panel:</p>
                    <div class="bg-gray-900 dark:bg-gray-950 rounded p-3 overflow-x-auto">
                        <pre class="text-green-400 text-xs"><code>Go to /admin/chatbot
Find your widget
Update "Welcome Message" field
Save changes</code></pre>
                    </div>
                </div>

                <!-- Auto-Hide after Reply -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Multi-Language Support</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Set welcome message in different languages:</p>
                    <ul class="mt-2 text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>✓ English: "Hi! How can we help?"</li>
                        <li>✓ Spanish: "¡Hola! ¿Cómo podemos ayudarte?"</li>
                        <li>✓ French: "Bonjour! Comment pouvons-nous vous aider?"</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Styling -->
    <section id="styling" class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">4. Styling & Appearance</h2>
            
            <div class="space-y-4">
                <!-- Default Appearance -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Default Appearance</h3>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Light Mode</p>
                            <div class="bg-white border-2 border-gray-300 rounded p-4 h-32">
                                <div class="w-12 h-12 rounded-full bg-black mb-2"></div>
                                <p class="text-xs text-gray-600">Chat Bubble</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Dark Mode</p>
                            <div class="bg-gray-900 border-2 border-gray-700 rounded p-4 h-32">
                                <div class="w-12 h-12 rounded-full bg-white mb-2"></div>
                                <p class="text-xs text-gray-400">Chat Bubble</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customization Notes -->
                <div class="border-l-4 border-blue-500 bg-blue-50 dark:bg-blue-900/30 p-4 rounded">
                    <p class="text-sm text-blue-900 dark:text-blue-300">
                        <strong>📝 Note:</strong> The widget automatically detects system theme preference. CSS is embedded in the widget and works out-of-the-box.
                    </p>
                </div>

                <!-- Position & Size -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Widget Position</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <li>✓ <strong>Desktop:</strong> Fixed 400×600px bubble, bottom-right corner</li>
                        <li>✓ <strong>Mobile:</strong> Full-screen chat window</li>
                        <li>✓ <strong>Responsive:</strong> Automatically adapts to screen size</li>
                        <li>✓ <strong>Z-index:</strong> 50000 (stays on top of most elements)</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Advanced Configuration -->
    <section id="advanced" class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">5. Advanced Configuration</h2>
            
            <div class="space-y-4">
                <!-- Multiple Widgets -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Multiple Widgets (CRM + Website)</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Install different widgets in different apps with unique tokens:</p>
                    <div class="bg-gray-900 dark:bg-gray-950 rounded p-3 overflow-x-auto">
                        <pre class="text-green-400 text-xs"><code>// In CRM (token: cbw_xxx)
&lt;script src="..." data-api-token="cbw_xxx"&gt;&lt;/script&gt;

// In Website (token: cbw_yyy)
&lt;script src="..." data-api-token="cbw_yyy"&gt;&lt;/script&gt;

// All messages appear in same dashboard
// Filter by "widget" to see which app sent it</code></pre>
                    </div>
                </div>

                <!-- Metadata Tracking -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Advanced Visitor Tracking</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">Track custom data with each conversation:</p>
                    <div class="bg-gray-900 dark:bg-gray-950 rounded p-3 overflow-x-auto">
                        <pre class="text-green-400 text-xs"><code>data-visitor-metadata='{
  "user_id": 12345,
  "account_type": "premium",
  "sign_up_date": "2024-01-01",
  "last_login": "2024-01-10"
}'</code></pre>
                    </div>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">✓ Visible in conversation details</p>
                </div>

                <!-- Widget Management -->
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-3">Widget Management</h3>
                    <div class="text-sm text-gray-600 dark:text-gray-400 space-y-2">
                        <p>✓ <strong>Deactivate Widget:</strong> Go to widget settings → toggle "is_active"</p>
                        <p>✓ <strong>Rotate Token:</strong> Click "Generate New Token" for security</p>
                        <p>✓ <strong>Monitor Usage:</strong> View conversation stats per widget</p>
                        <p>✓ <strong>Bulk Operations:</strong> Export conversations as CSV</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Troubleshooting -->
    <section id="troubleshooting" class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">6. Troubleshooting Guide</h2>
            
            <div class="space-y-4">
                <!-- Issue 1 -->
                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Widget not appearing?</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 mt-2 space-y-1 ml-4">
                        <li>✓ Check browser console for errors: F12 → Console</li>
                        <li>✓ Verify token is correct and not expired</li>
                        <li>✓ Ensure widget is_active = true in database</li>
                        <li>✓ Check CORS headers if cross-domain</li>
                        <li>✓ Try in private/incognito window (no cache)</li>
                    </ul>
                </div>

                <!-- Issue 2 -->
                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Messages not saving?</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 mt-2 space-y-1 ml-4">
                        <li>✓ Check database connection: php artisan migrate</li>
                        <li>✓ Verify API endpoint is accessible</li>
                        <li>✓ Check network tab in browser DevTools</li>
                        <li>✓ Ensure API responses return HTTP 200</li>
                    </ul>
                </div>

                <!-- Issue 3 -->
                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Real-time updates not working?</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 mt-2 space-y-1 ml-4">
                        <li>✓ Start Reverb: php artisan reverb:start</li>
                        <li>✓ Check WebSocket connection: Browser DevTools → Network → WS</li>
                        <li>✓ Verify REVERB_* env variables are set</li>
                        <li>✓ Check if port 8080 is open/not blocked</li>
                        <li>✓ Refresh page after starting Reverb</li>
                    </ul>
                </div>

                <!-- Issue 4 -->
                <div class="border-l-4 border-red-500 pl-4 py-2">
                    <h3 class="font-semibold text-gray-900 dark:text-white">Admin dashboard empty?</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 mt-2 space-y-1 ml-4">
                        <li>✓ Verify widget exists: SELECT * FROM chatbot_widgets</li>
                        <li>✓ Check if conversations table is populated</li>
                        <li>✓ Clear browser cache (Ctrl+Shift+Delete)</li>
                        <li>✓ Test with browser DevTools Network tab open</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Best Practices -->
    <section class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">7. Best Practices</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Security</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>✓ Use HTTPS (not HTTP)</li>
                        <li>✓ Rotate tokens regularly</li>
                        <li>✓ Keep tokens secret</li>
                        <li>✓ Monitor IP addresses</li>
                    </ul>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Performance</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>✓ Load widget async</li>
                        <li>✓ Place before &lt;/body&gt;</li>
                        <li>✓ Use content delivery network</li>
                        <li>✓ Monitor real-time connections</li>
                    </ul>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">User Experience</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>✓ Use clear welcome message</li>
                        <li>✓ Reply quickly to messages</li>
                        <li>✓ Set auto-reply for off-hours</li>
                        <li>✓ Test on mobile devices</li>
                    </ul>
                </div>

                <div class="border-l-4 border-green-500 pl-4">
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-2">Maintenance</h3>
                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1">
                        <li>✓ Monitor conversation count</li>
                        <li>✓ Archive old conversations</li>
                        <li>✓ Update welcome messages</li>
                        <li>✓ Review analytics weekly</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Current Widgets -->
    <?php if($widgets->count() > 0): ?>
    <section class="space-y-4">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Your Active Widgets</h2>
            
            <div class="space-y-3">
                <?php $__currentLoopData = $widgets; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $widget): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-900 dark:text-white"><?php echo e($widget->name); ?></h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Domain: <?php echo e($widget->domain); ?></p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-2 font-mono bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded inline-block">
                                <?php echo e($widget->api_token); ?>

                            </p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold <?php echo e($widget->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'); ?>">
                            <?php echo e($widget->is_active ? 'Active' : 'Inactive'); ?>

                        </span>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Footer CTA -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 dark:from-blue-700 dark:to-blue-800 rounded-lg shadow p-8 text-white">
        <h2 class="text-2xl font-bold mb-2">Ready to Get Started?</h2>
        <p class="mb-4">Follow the installation steps above and you'll have a working chatbot in minutes.</p>
        <div class="flex gap-3">
            <a href="<?php echo e(route('admin.chatbot.index')); ?>" class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                View Conversations
            </a>
            <a href="<?php echo e(config('app.url')); ?>/public/chatbot-demo.html" target="_blank" class="border-2 border-white text-white px-6 py-2 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                View Demo
            </a>
        </div>
    </div>
</div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $attributes = $__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__attributesOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54)): ?>
<?php $component = $__componentOriginal9ac128a9029c0e4701924bd2d73d7f54; ?>
<?php unset($__componentOriginal9ac128a9029c0e4701924bd2d73d7f54); ?>
<?php endif; ?><?php /**PATH D:\Ryven Works\ryven-team-management\resources\views/admin/chatbot/guide.blade.php ENDPATH**/ ?>