/**
 * Chatbot Widget - Embeddable Chat Interface
 * Usage: <script src="https://yourdomain.com/chatbot-widget.js" data-api-token="cbw_xxxxx" data-widget-url="https://yourdomain.com"></script>
 */

(function () {
    'use strict';

    // Configuration from window.ChatbotConfig or script attributes
    const CONFIG = window.ChatbotConfig || {
        apiToken: null,
        widgetUrl: 'http://127.0.0.1:8000',
        visitorName: null,
        visitorEmail: null,
        visitorPhone: null,
        visitorId: null,
        visitorMetadata: {},
    };

    // If using script attributes instead, read from them
    if (!window.ChatbotConfig) {
        const script = document.currentScript || document.scripts[document.scripts.length - 1];
        CONFIG.apiToken = script.getAttribute('data-api-token');
        CONFIG.widgetUrl = script.getAttribute('data-widget-url') || CONFIG.widgetUrl;
        CONFIG.visitorName = script.getAttribute('data-visitor-name') || CONFIG.visitorName;
        CONFIG.visitorEmail = script.getAttribute('data-visitor-email') || CONFIG.visitorEmail;
        CONFIG.visitorPhone = script.getAttribute('data-visitor-phone') || CONFIG.visitorPhone;
        CONFIG.visitorId = script.getAttribute('data-visitor-id') || CONFIG.visitorId;
        if (script.getAttribute('data-visitor-metadata')) {
            CONFIG.visitorMetadata = JSON.parse(script.getAttribute('data-visitor-metadata'));
        }
    }

    // Validate token
    if (!CONFIG.apiToken) {
        console.error('Chatbot widget error: No API token provided. Set window.ChatbotConfig.apiToken or data-api-token attribute.');
        return;
    }

    // Chatbot State
    let state = {
        isOpen: false,
        conversationId: null,
        messages: [],
        isLoading: false,
        isRecording: false,
        mediaRecorder: null,
        audioChunks: [],
    };

    // Load Laravel Echo for real-time messaging
    function loadLaravelEcho() {
        // Only load if not already loaded
        if (window.Echo) return;

        // Create a script to load Laravel Echo and Pusher
        const script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/laravel-echo@1.15.0/dist/echo.iife.js';
        script.onload = () => {
            try {
                // Get the app key from the server
                const url = new URL(CONFIG.widgetUrl || window.location.href);
                const wsHost = url.hostname;
                const wsProtocol = url.protocol === 'https:' ? 'wss' : 'ws';
                
                // Initialize Echo with Reverb
                window.Echo = new window.Echo({
                    broadcaster: 'reverb',
                    key: 'your-app-key', // This will be set by server-side config
                    wsHost: wsHost,
                    wsPort: 8080,
                    wssPort: 443,
                    forceTLS: window.location.protocol === 'https:',
                    encrypted: true,
                    disableStats: true,
                });
                console.log('✓ Laravel Echo initialized for real-time messaging');
            } catch (error) {
                console.error('Failed to initialize Echo:', error);
                startPollingMessages();
            }
        };
        script.onerror = () => {
            console.log('Laravel Echo not available, using polling fallback');
            startPollingMessages();
        };
        document.head.appendChild(script);
    }

    // Create widget HTML
    function createWidgetHTML() {
        const container = document.createElement('div');
        container.id = 'chatbot-widget-container';
        container.innerHTML = `
            <style>
                #chatbot-widget-container * {
                    box-sizing: border-box;
                    margin: -1;
                    padding: -1;
                }

                /* Bubble Button */
                .chatbot-bubble {
                    position: fixed;
                    bottom: 24px;
                    right: 24px;
                    width: 60px;
                    height: 60px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: white;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 8px 24px rgba(37, 99, 235, 0.35), 0 2px 8px rgba(0, 0, 0, 0.1);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 999998;
                    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                }

                .chatbot-bubble:hover {
                    transform: scale(1.1);
                    box-shadow: 0 12px 32px rgba(37, 99, 235, 0.45), 0 2px 8px rgba(0, 0, 0, 0.15);
                }

                .chatbot-bubble:active {
                    transform: scale(0.95);
                }

                .chatbot-bubble svg {
                    width: 26px;
                    height: 26px;
                    stroke: white;
                    stroke-width: 2;
                    fill: none;
                    stroke-linecap: round;
                    stroke-linejoin: round;
                    transition: all 0.2s ease;
                }

                /* Chat Window */
                .chatbot-window {
                    position: fixed;
                    bottom: 100px;
                    right: 24px;
                    width: 420px;
                    height: 650px;
                    background: #ffffff;
                    border-radius: 16px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12), 0 8px 16px rgba(0, 0, 0, 0.08);
                    display: none;
                    flex-direction: column;
                    z-index: 999999;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
                    animation: slideUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                    overflow: hidden;
                    border: 1px solid rgba(0, 0, 0, 0.06);
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(15px) scale(0.95);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0) scale(1);
                    }
                }

                .chatbot-window.open {
                    display: flex;
                }

                /* Header */
                .chatbot-header {
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: white;
                    padding: 24px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-shrink: 0;
                    border-radius: 16px 16px 0 0;
                }

                .chatbot-header h3 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 700;
                    letter-spacing: -0.5px;
                    line-height: 1.3;
                }

                .chatbot-close {
                    background: rgba(255, 255, 255, 0.1);
                    border: none;
                    color: white;
                    cursor: pointer;
                    padding: 4px;
                    width: 36px;
                    height: 36px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: all 0.2s ease;
                    border-radius: 8px;
                }

                .chatbot-close:hover {
                    background: rgba(255, 255, 255, 0.2);
                    transform: rotate(90deg);
                }

                .chatbot-close:active {
                    background: rgba(255, 255, 255, 0.25);
                }

                .chatbot-close svg {
                    width: 22px;
                    height: 22px;
                    stroke: white;
                    stroke-width: 2;
                    fill: none;
                    transition: transform 0.2s;
                }

                /* Messages Container */
                .chatbot-messages {
                    flex: 1;
                    overflow-y: auto;
                    overflow-x: hidden;
                    padding: 20px 18px;
                    display: flex;
                    flex-direction: column;
                    gap: 14px;
                    background: #fafbfc;
                }

                .chatbot-messages::-webkit-scrollbar {
                    width: 6px;
                }

                .chatbot-messages::-webkit-scrollbar-track {
                    background: transparent;
                }

                .chatbot-messages::-webkit-scrollbar-thumb {
                    background: #ddd;
                    border-radius: 3px;
                }

                .chatbot-messages::-webkit-scrollbar-thumb:hover {
                    background: #999;
                }

                /* Empty State */
                .chatbot-empty-state {
                    flex: 1;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: center;
                    padding: 40px 20px;
                    text-align: center;
                    color: #999;
                    background: linear-gradient(135deg, rgba(0, 0, 0, 0.02) 0%, rgba(0, 0, 0, 0.05) 100%);
                }

                .chatbot-empty-state svg {
                    width: 56px;
                    height: 56px;
                    stroke: #ddd;
                    stroke-width: 1.2;
                    fill: none;
                    margin-bottom: 16px;
                    opacity: 0.5;
                    animation: float 3s ease-in-out infinite;
                }

                @keyframes float {
                    0%, 100% { transform: translateY(0px); }
                    50% { transform: translateY(-8px); }
                }

                .chatbot-empty-state p {
                    margin: 0;
                    font-size: 15px;
                    line-height: 1.6;
                    font-weight: 500;
                }

                /* Message */
                .chatbot-message {
                    display: flex;
                    margin-bottom: 0;
                    animation: fadeInUp 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
                }

                @keyframes fadeInUp {
                    from {
                        opacity: 0;
                        transform: translateY(12px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .chatbot-message.visitor {
                    justify-content: flex-end;
                }

                .chatbot-message-wrapper {
                    display: flex;
                    flex-direction: column;
                    max-width: 75%;
                    gap: 6px;
                }

                .chatbot-message.visitor .chatbot-message-wrapper {
                    align-items: flex-end;
                }

                .chatbot-message.employee .chatbot-message-wrapper {
                    align-items: flex-start;
                }

                .chatbot-message-content {
                    padding: 12px 16px;
                    border-radius: 12px;
                    word-break: break-word;
                    font-size: 14px;
                    line-height: 1.5;
                    letter-spacing: 0.2px;
                    transition: all 0.2s ease;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                }

                .chatbot-message.visitor .chatbot-message-content {
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: white;
                    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.25);
                }

                .chatbot-message.visitor .chatbot-message-content:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(37, 99, 235, 0.3);
                }

                .chatbot-message.employee .chatbot-message-content {
                    background: white;
                    color: #1f2937;
                    border: 1px solid #e5e7eb;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
                }

                .chatbot-message.employee .chatbot-message-content:hover {
                    border-color: #d1d5db;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
                }

                .chatbot-message.visitor .chatbot-message-content:hover {
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2), 0 2px 4px rgba(0, 0, 0, 0.1);
                    transform: translateY(-2px);
                }

                .chatbot-message.employee .chatbot-message-content {
                    background: linear-gradient(135deg, #f5f7fa 0%, #eef2f7 100%);
                    color: #333;
                    border: none;
                    border-radius: 4px 16px 16px 16px;
                    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
                }

                .chatbot-message.employee .chatbot-message-content:hover {
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
                    transform: translateY(-1px);
                }

                .chatbot-message-time {
                    font-size: 12px;
                    color: #999;
                    margin-top: 2px;
                    padding: 0 4px;
                    font-weight: 400;
                    letter-spacing: 0.3px;
                }

                /* Input Area */
                .chatbot-input-area {
                    padding: 16px 16px;
                    border-top: 1px solid #e5e7eb;
                    display: flex;
                    gap: 12px;
                    background: white;
                    flex-shrink: 0;
                    border-radius: 0 0 16px 16px;
                }

                #chatbot-file-input {
                    display: none;
                }

                .chatbot-input {
                    flex: 1;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 10px 14px;
                    font-size: 14px;
                    font-family: inherit;
                    resize: none;
                    max-height: 100px;
                    transition: all 0.2s ease;
                    background: #f9fafb;
                    line-height: 1.5;
                    letter-spacing: 0.2px;
                    color: #1f2937;
                }

                .chatbot-input:focus {
                    outline: none;
                    border-color: #2563eb;
                    background: white;
                    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
                }

                .chatbot-input::placeholder {
                    color: #9ca3af;
                }

                /* Buttons */
                .chatbot-action-btn {
                    background: #f3f4f6;
                    color: #4b5563;
                    border: 1px solid #e5e7eb;
                    border-radius: 8px;
                    padding: 0;
                    cursor: pointer;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 38px;
                    height: 38px;
                    line-height: 1;
                    flex-shrink: 0;
                    min-width: 38px;
                }

                .chatbot-action-btn:hover:not(.recording) {
                    background: #e5e7eb;
                    border-color: #d1d5db;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.06);
                    transform: translateY(-1px);
                    color: #374151;
                }

                .chatbot-action-btn:active:not(.recording) {
                    transform: translateY(0);
                    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
                }

                .chatbot-action-btn.recording {
                    background: #ef4444;
                    color: white;
                    border-color: #dc2626;
                    animation: pulse 1s infinite;
                    box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
                }

                @keyframes pulse {
                    0%, 100% { box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3); }
                    50% { box-shadow: 0 4px 20px rgba(239, 68, 68, 0.4); }
                }

                .chatbot-action-btn svg {
                    width: 18px;
                    height: 18px;
                    stroke: currentColor;
                    stroke-width: 2;
                    fill: none;
                    stroke-linecap: round;
                    stroke-linejoin: round;
                    transition: all 0.2s;
                }

                .chatbot-action-btn:hover:not(.recording) svg {
                    stroke-width: 2.2;
                }

                .chatbot-send {
                    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 0;
                    cursor: pointer;
                    font-weight: 600;
                    font-size: 13px;
                    transition: all 0.2s ease;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 38px;
                    padding: 0 20px;
                    white-space: nowrap;
                    flex-shrink: 0;
                    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.2);
                    letter-spacing: 0.3px;
                    min-width: 60px;
                }

                .chatbot-send:hover {
                    background: linear-gradient(135deg, #1d4ed8 0%, #1e40af 100%);
                    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
                    transform: translateY(-2px);
                }

                .chatbot-send:active {
                    background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 100%);
                    transform: translateY(0);
                    box-shadow: 0 2px 6px rgba(37, 99, 235, 0.2);
                }

                .chatbot-send:disabled {
                    background: #d1d5db;
                    cursor: not-allowed;
                    transform: none;
                    box-shadow: none;
                    color: #9ca3af;
                }
                /* File/Voice Messages */
                .chatbot-file-message {
                    padding: 10px;
                    background: rgba(0, 0, 0, 0.04);
                    border-radius: 12px;
                    margin-top: 8px;
                    border: 1px solid rgba(0, 0, 0, 0.06);
                    backdrop-filter: blur(10px);
                }

                .chatbot-message.visitor .chatbot-file-message {
                    background: rgba(255, 255, 255, 0.12);
                    border-color: rgba(255, 255, 255, 0.25);
                }

                .chatbot-file-link {
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    color: #1f2937;
                    text-decoration: none;
                    font-size: 13px;
                    font-weight: 600;
                    padding: 10px 14px;
                    background: white;
                    border-radius: 8px;
                    transition: all 0.2s ease;
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
                }

                .chatbot-message.visitor .chatbot-file-link {
                    color: white;
                    background: rgba(255, 255, 255, 0.18);
                    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
                }

                .chatbot-file-link:hover {
                    background: #f8f8f8;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
                    transform: translateY(-2px);
                    transform: translateX(2px);
                }

                .chatbot-message.visitor .chatbot-file-link:hover {
                    background: rgba(0, 0, 0, 0.3);
                }

                .chatbot-file-link svg {
                    width: 14px;
                    height: 14px;
                    stroke: currentColor;
                    stroke-width: 2;
                    fill: none;
                    flex-shrink: 0;
                }

                .chatbot-voice-player {
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    background: rgba(0, 0, 0, 0.04);
                    padding: 12px;
                    border-radius: 12px;
                    margin-top: 10px;
                    border: 1px solid rgba(0, 0, 0, 0.06);
                    backdrop-filter: blur(10px);
                }

                .chatbot-message.visitor .chatbot-voice-player {
                    background: rgba(255, 255, 255, 0.15);
                    border-color: rgba(255, 255, 255, 0.3);
                }

                .chatbot-voice-player audio {
                    max-width: 100%;
                    height: 32px;
                    border-radius: 6px;
                }

                /* Loading State */
                .chatbot-loading {
                    text-align: center;
                    padding: 30px 20px;
                    color: #9ca3af;
                }

                .chatbot-spinner {
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    border: 2px solid #e5e7eb;
                    border-top-color: #2563eb;
                    border-radius: 50%;
                    animation: spin 0.6s linear infinite;
                    margin-bottom: 10px;
                }

                @keyframes spin {
                    to { transform: rotate(360deg); }
                }

                @keyframes slideIn {
                    from {
                        opacity: 0;
                        transform: translateX(100px);
                    }
                    to {
                        opacity: 1;
                        transform: translateX(0);
                    }
                }

                @keyframes slideOut {
                    from {
                        opacity: 1;
                        transform: translateX(0);
                    }
                    to {
                        opacity: 0;
                        transform: translateX(100px);
                    }
                }

                /* Mobile Responsive */
                @media (max-width: 480px) {
                    .chatbot-bubble {
                        bottom: 16px;
                        right: 16px;
                        width: 56px;
                        height: 56px;
                    }

                    .chatbot-window {
                        position: fixed;
                        bottom: 0;
                        right: 0;
                        left: 0;
                        top: 0;
                        width: 100%;
                        height: 100%;
                        border-radius: 0;
                        animation: slideUpMobile 0.3s ease;
                    }

                    @keyframes slideUpMobile {
                        from {
                            opacity: 0;
                            transform: translateY(100%);
                        }
                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    .chatbot-message-content {
                        max-width: 85%;
                    }

                    .chatbot-messages {
                        padding: 14px;
                        gap: 10px;
                    }

                    .chatbot-input-area {
                        padding: 12px;
                        gap: 8px;
                    }

                    .chatbot-action-btn {
                        width: 32px;
                        height: 32px;
                    }

                    .chatbot-send {
                        padding: 0 12px;
                        font-size: 11px;
                    }
                }
            </style>

            <!-- Chat Bubble Button -->
            <button class="chatbot-bubble" id="chatbot-bubble" title="Open Chat">
                <svg viewBox="0 0 24 24" width="20" height="20" stroke="currentColor" fill="none" stroke-width="2">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                </svg>
            </button>

            <!-- Chat Window -->
            <div class="chatbot-window" id="chatbot-window">
                <div class="chatbot-header">
                    <h3>Chat with us</h3>
                    <button class="chatbot-close" id="chatbot-close">
                        <svg viewBox="0 0 24 24" width="18" height="18" stroke="currentColor" fill="none" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                </div>

                <div class="chatbot-messages" id="chatbot-messages">
                    <div class="chatbot-loading">
                        <div class="chatbot-spinner"></div>
                        <p>Loading...</p>
                    </div>
                </div>

                <div class="chatbot-input-area">
                    <input type="file" id="chatbot-file-input" accept="*/*">
                    <button class="chatbot-action-btn" id="chatbot-file-btn" title="Send File">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <line x1="12" y1="19" x2="12" y2="5"></line>
                            <line x1="9" y1="8" x2="15" y2="8"></line>
                        </svg>
                    </button>
                    <button class="chatbot-action-btn" id="chatbot-voice-btn" title="Send Voice Message">
                        <svg viewBox="0 0 24 24" width="16" height="16" stroke="currentColor" fill="none" stroke-width="2">
                            <path d="M12 1a3 3 0 0 0-3 3v8a3 3 0 0 0 6 0V4a3 3 0 0 0-3-3z"></path>
                            <path d="M19 10v2a7 7 0 0 1-14 0v-2"></path>
                            <line x1="12" y1="19" x2="12" y2="23"></line>
                            <line x1="8" y1="23" x2="16" y2="23"></line>
                        </svg>
                    </button>
                    <textarea class="chatbot-input" id="chatbot-input" placeholder="Type a message..." rows="1"></textarea>
                    <button class="chatbot-send" id="chatbot-send">Send</button>
                </div>
            </div>
        `;

        document.body.appendChild(container);
    }

    // Initialize chat session
    async function initChat() {
        try {
            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/init`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: JSON.stringify({
                    visitor_name: CONFIG.visitorName || 'Guest',
                    visitor_email: CONFIG.visitorEmail,
                    visitor_phone: CONFIG.visitorPhone,
                    visitor_id: CONFIG.visitorId,
                    visitor_metadata: {
                        ...CONFIG.visitorMetadata,
                        url: window.location.href,
                        userAgent: navigator.userAgent,
                    },
                }),
            });

            const data = await response.json();
            if (data.success) {
                state.conversationId = data.conversation_id;
                await loadMessages();
                setupEventListeners();
                setupRealtimeUpdates();
            } else {
                console.error('Init failed:', data);
                showNotification(data.error || 'Failed to initialize chat', 'error');
            }
        } catch (error) {
            console.error('Init error:', error);
            showNotification('Failed to connect to chat service', 'error');
        }
    }

    // Load conversation messages
    async function loadMessages() {
        try {
            const response = await fetch(
                `${CONFIG.widgetUrl}/api/chatbot/conversation/${state.conversationId}`,
                {
                    headers: {
                        'Authorization': `Bearer ${CONFIG.apiToken}`,
                    },
                }
            );

            const data = await response.json();
            if (data.success) {
                state.messages = data.data.messages || [];
                renderMessages();
            }
        } catch (error) {
            console.error('Failed to load messages:', error);
        }
    }

    // Render messages
    function renderMessages() {
        const container = document.getElementById('chatbot-messages');
        container.innerHTML = '';

        if (state.messages.length === 0) {
            container.innerHTML = `
                <div class="chatbot-empty-state">
                    <svg viewBox="0 0 24 24" stroke="currentColor" fill="none" stroke-width="1.5">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                    <p>Start a conversation with us!</p>
                </div>
            `;
            return;
        }

        state.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = `chatbot-message ${msg.sender_type === 'visitor' ? 'visitor' : 'employee'}`;
            
            let content = `<div class="chatbot-message-content">${escapeHtml(msg.message)}</div>`;
            
            // Add file attachment if exists
            if (msg.attachment_path) {
                const fileName = msg.attachment_name || msg.attachment_path.split('/').pop();
                const fileExt = fileName.split('.').pop().toLowerCase();
                let fileIcon = `<svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>`;
                
                if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(fileExt)) 
                    fileIcon = `<svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect><circle cx="8.5" cy="8.5" r="1.5"></circle><polyline points="21 15 16 10 5 21"></polyline></svg>`;
                else if (['mp3', 'wav', 'ogg', 'm4a'].includes(fileExt)) 
                    fileIcon = `<svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M9 18V5l12-2v13"></path><circle cx="6" cy="18" r="3"></circle><circle cx="18" cy="16" r="3"></circle></svg>`;
                else if (['pdf'].includes(fileExt)) 
                    fileIcon = `<svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><text x="9" y="17" font-size="6">PDF</text></svg>`;
                else if (['doc', 'docx'].includes(fileExt)) 
                    fileIcon = `<svg viewBox="0 0 24 24" width="14" height="14" stroke="currentColor" fill="none" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline></svg>`;
                
                content += `
                    <div class="chatbot-file-message">
                        <a href="${msg.attachment_path}" class="chatbot-file-link" target="_blank" download>
                            ${fileIcon}
                            <span>${escapeHtml(fileName)}</span>
                        </a>
                    </div>
                `;
            }
            
            // Add voice message if exists
            if (msg.is_voice) {
                const audioUrl = msg.attachment_path;
                content += `
                    <div class="chatbot-voice-player">
                        <audio controls>
                            <source src="${audioUrl}" type="audio/webm">
                            Your browser does not support the audio element.
                        </audio>
                    </div>
                `;
            }
            
            div.innerHTML = `
                <div class="chatbot-message-wrapper">
                    ${content}
                    <div class="chatbot-message-time">${new Date(msg.timestamp || msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                </div>
            `;
            container.appendChild(div);
        });

        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }

    // Send message
    async function sendMessage(messageText) {
        const input = document.getElementById('chatbot-input');
        const message = messageText || input.value.trim();

        if (!message || typeof message !== 'string') {
            console.warn('Invalid message:', message, typeof message);
            input.focus();
            return;
        }

        const sendBtn = document.getElementById('chatbot-send');
        const originalText = sendBtn.textContent;

        try {
            state.isLoading = true;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<div class="chatbot-spinner" style="width: 10px; height: 10px; margin: 0; display: inline-block;"></div>';

            const payload = {
                conversation_id: state.conversationId,
                message: String(message).trim(),
                sender_type: 'visitor',
            };
            
            console.log('Sending message payload:', payload);

            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: JSON.stringify(payload),
            });

            const responseData = await response.json();
            
            if (response.ok) {
                if (!messageText) input.value = '';
                input.style.height = 'auto';
                state.messages.push({
                    sender_type: 'visitor',
                    message: message,
                    timestamp: new Date().toISOString(),
                });
                renderMessages();
            } else {
                console.error('API Error:', responseData);
                showNotification(responseData.details?.message?.[0] || responseData.error || 'Failed to send message', 'error');
            }
        } catch (error) {
            console.error('Failed to send message:', error);
            showNotification('Network error. Please try again.', 'error');
        } finally {
            state.isLoading = false;
            sendBtn.disabled = false;
            sendBtn.textContent = originalText;
            input.focus();
        }
    }

    // Notification function
    function showNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 16px;
            background: ${type === 'error' ? '#ff4444' : '#000'};
            color: white;
            border-radius: 6px;
            font-size: 12px;
            z-index: 1000000;
            animation: slideIn 0.3s ease;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        `;
        notification.textContent = message;
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = 'slideOut 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    // Setup event listeners
    function setupEventListeners() {
        const input = document.getElementById('chatbot-input');
        const sendBtn = document.getElementById('chatbot-send');

        // Bubble button
        document.getElementById('chatbot-bubble').addEventListener('click', () => {
            state.isOpen = !state.isOpen;
            document.getElementById('chatbot-window').classList.toggle('open');
            if (state.isOpen) {
                setTimeout(() => input.focus(), 100);
            }
        });

        // Close button
        document.getElementById('chatbot-close').addEventListener('click', () => {
            state.isOpen = false;
            document.getElementById('chatbot-window').classList.remove('open');
        });

        // Send button
        sendBtn.addEventListener('click', () => sendMessage());

        // Input field - auto-resize and keyboard support
        input.addEventListener('input', (e) => {
            // Auto-resize textarea
            e.target.style.height = 'auto';
            e.target.style.height = Math.min(e.target.scrollHeight, 100) + 'px';
        });

        input.addEventListener('keypress', (e) => {
            // Send on Enter (without Shift)
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Focus management
        input.addEventListener('focus', () => {
            input.parentElement.style.borderColor = '#000';
        });

        input.addEventListener('blur', () => {
            input.parentElement.style.borderColor = '#ddd';
        });

        // File upload handler
        document.getElementById('chatbot-file-btn').addEventListener('click', () => {
            document.getElementById('chatbot-file-input').click();
        });

        document.getElementById('chatbot-file-input').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
                // Validate file size (10MB max)
                if (file.size > 10 * 1024 * 1024) {
                    showNotification('File size exceeds 10MB limit', 'error');
                    e.target.value = '';
                    return;
                }
                sendFile(file);
            }
        });

        // Voice recording handler
        document.getElementById('chatbot-voice-btn').addEventListener('click', toggleVoiceRecording);

        // Setup real-time updates if Reverb is available
        if (window.Echo) {
            setupRealtimeUpdates();
        }
    }

    // Send file
    async function sendFile(file) {
        if (!file) return;

        const formData = new FormData();
        formData.append('conversation_id', state.conversationId);
        formData.append('file', file);
        formData.append('sender_type', 'visitor');

        const sendBtn = document.getElementById('chatbot-send');
        const originalText = sendBtn.textContent;

        try {
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<div class="chatbot-spinner" style="width: 10px; height: 10px; margin: 0; display: inline-block;"></div>';
            showNotification(`Uploading ${file.name}...`, 'success');
            
            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/file`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: formData,
            });

            const responseData = await response.json();
            
            if (response.ok) {
                state.messages.push({
                    sender_type: 'visitor',
                    message: file.name,
                    attachment_path: responseData.file_url,
                    attachment_name: file.name,
                    timestamp: new Date().toISOString(),
                });
                renderMessages();
                document.getElementById('chatbot-file-input').value = '';
                showNotification('File uploaded successfully', 'success');
            } else {
                console.error('File upload API Error:', responseData);
                showNotification(responseData.error || 'Failed to upload file', 'error');
            }
        } catch (error) {
            console.error('Failed to send file:', error);
            showNotification('Upload failed. Please try again.', 'error');
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = originalText;
        }
    }

    // Voice recording
    async function toggleVoiceRecording() {
        if (!state.isRecording) {
            startVoiceRecording();
        } else {
            stopVoiceRecording();
        }
    }

    async function startVoiceRecording() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
            state.mediaRecorder = new MediaRecorder(stream, { mimeType: 'audio/webm' });
            state.audioChunks = [];

            state.mediaRecorder.ondataavailable = (event) => {
                state.audioChunks.push(event.data);
            };

            state.mediaRecorder.onstop = async () => {
                const audioBlob = new Blob(state.audioChunks, { type: 'audio/webm' });
                sendVoiceMessage(audioBlob);
                stream.getTracks().forEach(track => track.stop());
            };

            state.mediaRecorder.start();
            state.isRecording = true;
            const voiceBtn = document.getElementById('chatbot-voice-btn');
            voiceBtn.classList.add('recording');
            voiceBtn.textContent = '⏹️';
        } catch (error) {
            console.error('Microphone access denied:', error);
            alert('Please allow microphone access to send voice messages.');
        }
    }

    function stopVoiceRecording() {
        if (state.mediaRecorder) {
            state.mediaRecorder.stop();
            state.isRecording = false;
            const voiceBtn = document.getElementById('chatbot-voice-btn');
            voiceBtn.classList.remove('recording');
            voiceBtn.textContent = '🎤';
        }
    }

    async function sendVoiceMessage(audioBlob) {
        const formData = new FormData();
        formData.append('conversation_id', state.conversationId);
        formData.append('voice_message', audioBlob, 'voice-message.webm');
        formData.append('sender_type', 'visitor');
        formData.append('message', 'Voice message');

        const sendBtn = document.getElementById('chatbot-send');
        const originalText = sendBtn.textContent;

        try {
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<div class="chatbot-spinner" style="width: 10px; height: 10px; margin: 0; display: inline-block;"></div>';
            showNotification('Uploading voice message...', 'success');
            
            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/voice`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: formData,
            });

            const responseData = await response.json();
            
            if (response.ok) {
                state.messages.push({
                    sender_type: 'visitor',
                    message: 'Voice message',
                    attachment_path: responseData.file_url,
                    is_voice: true,
                    timestamp: new Date().toISOString(),
                });
                renderMessages();
                showNotification('Voice message sent', 'success');
            } else {
                console.error('Voice API Error:', responseData);
                showNotification(responseData.error || 'Failed to send voice message', 'error');
            }
        } catch (error) {
            console.error('Failed to send voice message:', error);
            showNotification('Voice upload failed. Please try again.', 'error');
        } finally {
            sendBtn.disabled = false;
            sendBtn.textContent = originalText;
        }
    }

    // Setup real-time message updates via Reverb or polling
    function setupRealtimeUpdates() {
        if (typeof window.Echo !== 'undefined') {
            // Subscribe to private channel for this conversation
            window.Echo.private(`chat.conversation.${state.conversationId}`)
                .listen('.ChatMessageReceived', (event) => {
                    // Check if message already exists (to avoid duplicates)
                    const messageExists = state.messages.some(m => m.id === event.id);
                    if (!messageExists) {
                        state.messages.push({
                            id: event.id,
                            sender_type: event.sender_type,
                            sender_name: event.sender_name,
                            message: event.message,
                            attachment_path: event.attachment_path,
                            attachment_name: event.attachment_name,
                            is_voice: event.is_voice,
                            timestamp: event.timestamp,
                            created_at: event.timestamp,
                        });
                        renderMessages();
                    }
                })
                .error((error) => {
                    console.error('Echo subscription error:', error);
                    // Fallback to polling if Echo fails
                    startPollingMessages();
                });
        } else {
            // Echo not available, use polling fallback
            startPollingMessages();
        }
    }

    // Polling fallback for real-time updates (when Echo unavailable)
    function startPollingMessages() {
        // Check for new messages every 2 seconds
        setInterval(async () => {
            if (!state.conversationId) return;
            try {
                const response = await fetch(
                    `${CONFIG.widgetUrl}/api/chatbot/conversation/${state.conversationId}`,
                    {
                        headers: {
                            'Authorization': `Bearer ${CONFIG.apiToken}`,
                        },
                    }
                );
                const data = await response.json();
                if (data.success && data.data.messages) {
                    const newMessages = data.data.messages.filter(msg => {
                        return !state.messages.some(m => m.id === msg.id);
                    });
                    if (newMessages.length > 0) {
                        state.messages.push(...newMessages);
                        renderMessages();
                    }
                }
            } catch (error) {
                console.debug('Polling error (non-critical):', error);
            }
        }, 2000);
    }

    // Utility function to escape HTML
    function escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', () => {
            loadLaravelEcho();
            createWidgetHTML();
            initChat();
        });
    } else {
        loadLaravelEcho();
        createWidgetHTML();
        initChat();
    }
})();
