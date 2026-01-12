/**
 * Chatbot Widget - Embeddable Chat Interface
 * Usage: <script src="https://yourdomain.com/chatbot-widget.js" data-api-token="cbw_xxxxx" data-widget-url="https://yourdomain.com"></script>
 */

(function () {
    'use strict';

    // Configuration from script attributes
    const script = document.currentScript || document.scripts[document.scripts.length - 1];
    const CONFIG = {
        apiToken: script.getAttribute('data-api-token'),
        widgetUrl: script.getAttribute('data-widget-url') || 'http://localhost:8000',
        visitorName: script.getAttribute('data-visitor-name') || null,
        visitorEmail: script.getAttribute('data-visitor-email') || null,
        visitorPhone: script.getAttribute('data-visitor-phone') || null,
        visitorId: script.getAttribute('data-visitor-id') || null,
        visitorMetadata: script.getAttribute('data-visitor-metadata') ? JSON.parse(script.getAttribute('data-visitor-metadata')) : {},
    };

    // Chatbot State
    let state = {
        isOpen: false,
        conversationId: null,
        messages: [],
        isLoading: false,
    };

    // Create widget HTML
    function createWidgetHTML() {
        const container = document.createElement('div');
        container.id = 'chatbot-widget-container';
        container.innerHTML = `
            <style>
                #chatbot-widget-container * {
                    box-sizing: border-box;
                    margin: 0;
                    padding: 0;
                }

                /* Bubble Button */
                .chatbot-bubble {
                    position: fixed;
                    bottom: 24px;
                    right: 24px;
                    width: 64px;
                    height: 64px;
                    border-radius: 50%;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 8px 24px rgba(102, 126, 234, 0.4);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 28px;
                    z-index: 999998;
                    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    font-weight: 600;
                }

                .chatbot-bubble:hover {
                    transform: scale(1.12) translateY(-4px);
                    box-shadow: 0 12px 32px rgba(102, 126, 234, 0.5);
                }

                .chatbot-bubble:active {
                    transform: scale(0.98);
                }

                /* Chat Window */
                .chatbot-window {
                    position: fixed;
                    bottom: 100px;
                    right: 24px;
                    width: 420px;
                    height: 680px;
                    background: white;
                    border-radius: 16px;
                    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
                    display: none;
                    flex-direction: column;
                    z-index: 999999;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
                    animation: slideUp 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
                    overflow: hidden;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px) scale(0.95);
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
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    padding: 20px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-shrink: 0;
                }

                .chatbot-header h3 {
                    margin: 0;
                    font-size: 18px;
                    font-weight: 700;
                    letter-spacing: -0.5px;
                }

                .chatbot-header p {
                    margin: 4px 0 0 0;
                    font-size: 12px;
                    opacity: 0.9;
                    font-weight: 500;
                }

                .chatbot-close {
                    background: rgba(255, 255, 255, 0.2);
                    border: none;
                    color: white;
                    cursor: pointer;
                    font-size: 24px;
                    padding: 4px 8px;
                    border-radius: 6px;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 32px;
                    height: 32px;
                    line-height: 1;
                }

                .chatbot-close:hover {
                    background: rgba(255, 255, 255, 0.3);
                }

                /* Messages Container */
                .chatbot-messages {
                    flex: 1;
                    overflow-y: auto;
                    overflow-x: hidden;
                    padding: 20px;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                    background: #fafbfc;
                }

                .chatbot-messages::-webkit-scrollbar {
                    width: 6px;
                }

                .chatbot-messages::-webkit-scrollbar-track {
                    background: transparent;
                }

                .chatbot-messages::-webkit-scrollbar-thumb {
                    background: #ccc;
                    border-radius: 3px;
                }

                .chatbot-messages::-webkit-scrollbar-thumb:hover {
                    background: #999;
                }

                /* Message */
                .chatbot-message {
                    display: flex;
                    margin-bottom: 4px;
                    animation: fadeIn 0.3s ease;
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(8px);
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
                    max-width: 80%;
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
                    max-width: 100%;
                }

                .chatbot-message.visitor .chatbot-message-content {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border-radius: 12px 4px 12px 12px;
                    box-shadow: 0 2px 8px rgba(102, 126, 234, 0.2);
                }

                .chatbot-message.employee .chatbot-message-content {
                    background: white;
                    color: #333;
                    border-radius: 4px 12px 12px 12px;
                    border: 1px solid #e8e8e8;
                    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
                }

                .chatbot-message-time {
                    font-size: 12px;
                    color: #999;
                    margin-top: 6px;
                    padding: 0 4px;
                    font-weight: 500;
                }

                /* Input Area */
                .chatbot-input-area {
                    padding: 16px;
                    border-top: 1px solid #e8e8e8;
                    display: flex;
                    gap: 8px;
                    background: white;
                    flex-shrink: 0;
                }

                .chatbot-input {
                    flex: 1;
                    border: 1px solid #ddd;
                    border-radius: 10px;
                    padding: 11px 14px;
                    font-size: 14px;
                    font-family: inherit;
                    resize: none;
                    max-height: 100px;
                    transition: all 0.2s;
                    background: white;
                }

                .chatbot-input:focus {
                    outline: none;
                    border-color: #667eea;
                    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
                }

                .chatbot-input::placeholder {
                    color: #999;
                }

                .chatbot-send {
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    color: white;
                    border: none;
                    border-radius: 10px;
                    padding: 11px 18px;
                    cursor: pointer;
                    font-weight: 600;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                    height: 40px;
                    box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
                }

                .chatbot-send:hover {
                    transform: translateY(-2px);
                    box-shadow: 0 6px 16px rgba(102, 126, 234, 0.4);
                }

                .chatbot-send:active {
                    transform: translateY(0);
                }

                .chatbot-send:disabled {
                    background: #ccc;
                    cursor: not-allowed;
                    box-shadow: none;
                    transform: none;
                }

                /* Loading State */
                .chatbot-loading {
                    text-align: center;
                    padding: 40px 20px;
                    color: #999;
                }

                .chatbot-spinner {
                    display: inline-block;
                    width: 20px;
                    height: 20px;
                    border: 3px solid rgba(102, 126, 234, 0.2);
                    border-top-color: #667eea;
                    border-radius: 50%;
                    animation: spin 0.8s linear infinite;
                    margin-bottom: 12px;
                }

                @keyframes spin {
                    to { transform: rotate(360deg); }
                }

                /* Mobile Responsive */
                @media (max-width: 768px) {
                    .chatbot-bubble {
                        bottom: 20px;
                        right: 20px;
                        width: 56px;
                        height: 56px;
                        font-size: 24px;
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
                        animation: slideUpMobile 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
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
                        max-width: 100%;
                    }

                    .chatbot-messages {
                        padding: 16px;
                    }

                    .chatbot-header {
                        padding: 16px;
                    }
                }

                /* Dark Mode Support */
                @media (prefers-color-scheme: dark) {
                    .chatbot-window {
                        background: #1a1a1a;
                    }

                    .chatbot-messages {
                        background: #0d0d0d;
                    }

                    .chatbot-input-area {
                        background: #1a1a1a;
                        border-top-color: #333;
                    }

                    .chatbot-input {
                        background: #2a2a2a;
                        color: white;
                        border-color: #444;
                    }

                    .chatbot-input:focus {
                        border-color: #667eea;
                    }

                    .chatbot-message.employee .chatbot-message-content {
                        background: #2a2a2a;
                        color: white;
                        border-color: #333;
                    }
                }
            </style>

            <!-- Chat Bubble Button -->
            <button class="chatbot-bubble" id="chatbot-bubble" title="Open Chat">
                💬
            </button>

            <!-- Chat Window -->
            <div class="chatbot-window" id="chatbot-window">
                <div class="chatbot-header">
                    <h3>Chat with us</h3>
                    <button class="chatbot-close" id="chatbot-close">✕</button>
                </div>

                <div class="chatbot-messages" id="chatbot-messages">
                    <div class="chatbot-loading">
                        <div class="chatbot-spinner"></div>
                        <p>Loading...</p>
                    </div>
                </div>

                <div class="chatbot-input-area">
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
                loadMessages();
                setupEventListeners();
            }
        } catch (error) {
            console.error('Failed to init chat:', error);
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
            container.innerHTML = '<div style="text-align: center; color: #999; padding: 20px;">No messages yet. Start a conversation!</div>';
            return;
        }

        state.messages.forEach(msg => {
            const div = document.createElement('div');
            div.className = `chatbot-message ${msg.sender_type === 'visitor' ? 'visitor' : 'employee'}`;
            div.innerHTML = `
                <div>
                    <div class="chatbot-message-content">${escapeHtml(msg.message)}</div>
                    <div class="chatbot-message-time">${new Date(msg.timestamp).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' })}</div>
                </div>
            `;
            container.appendChild(div);
        });

        // Scroll to bottom
        container.scrollTop = container.scrollHeight;
    }

    // Send message
    async function sendMessage() {
        const input = document.getElementById('chatbot-input');
        const message = input.value.trim();

        if (!message) return;

        try {
            state.isLoading = true;
            document.getElementById('chatbot-send').disabled = true;

            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/message`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: JSON.stringify({
                    conversation_id: state.conversationId,
                    message: message,
                    sender_type: 'visitor',
                }),
            });

            if (response.ok) {
                input.value = '';
                state.messages.push({
                    sender_type: 'visitor',
                    message: message,
                    timestamp: new Date().toISOString(),
                });
                renderMessages();
            }
        } catch (error) {
            console.error('Failed to send message:', error);
        } finally {
            state.isLoading = false;
            document.getElementById('chatbot-send').disabled = false;
        }
    }

    // Setup event listeners
    function setupEventListeners() {
        document.getElementById('chatbot-bubble').addEventListener('click', () => {
            state.isOpen = !state.isOpen;
            document.getElementById('chatbot-window').classList.toggle('open');
            if (state.isOpen) {
                document.getElementById('chatbot-input').focus();
            }
        });

        document.getElementById('chatbot-close').addEventListener('click', () => {
            state.isOpen = false;
            document.getElementById('chatbot-window').classList.remove('open');
        });

        document.getElementById('chatbot-send').addEventListener('click', sendMessage);

        document.getElementById('chatbot-input').addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                sendMessage();
            }
        });

        // Setup real-time updates if Reverb is available
        if (window.Echo) {
            setupRealtimeUpdates();
        }
    }

    // Setup real-time message updates via Reverb
    function setupRealtimeUpdates() {
        window.Echo.private(`chat.conversation.${state.conversationId}`)
            .listen('.chat.message.received', (event) => {
                state.messages.push({
                    sender_type: event.sender_type,
                    sender_name: event.sender_name,
                    message: event.message,
                    timestamp: event.timestamp,
                });
                renderMessages();
            });
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
            createWidgetHTML();
            initChat();
        });
    } else {
        createWidgetHTML();
        initChat();
    }
})();
