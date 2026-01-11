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
                }

                .chatbot-bubble {
                    position: fixed;
                    bottom: 24px;
                    right: 24px;
                    width: 56px;
                    height: 56px;
                    border-radius: 50%;
                    background: #000;
                    color: white;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 24px;
                    z-index: 999998;
                    transition: all 0.3s ease;
                }

                .chatbot-bubble:hover {
                    transform: scale(1.1);
                    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
                }

                .chatbot-window {
                    position: fixed;
                    bottom: 100px;
                    right: 24px;
                    width: 400px;
                    height: 600px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 5px 40px rgba(0, 0, 0, 0.16);
                    display: none;
                    flex-direction: column;
                    z-index: 999999;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
                }

                .chatbot-window.open {
                    display: flex;
                }

                .chatbot-header {
                    background: #000;
                    color: white;
                    padding: 16px;
                    border-radius: 12px 12px 0 0;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                }

                .chatbot-header h3 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 600;
                }

                .chatbot-close {
                    background: none;
                    border: none;
                    color: white;
                    cursor: pointer;
                    font-size: 20px;
                    padding: 0;
                }

                .chatbot-messages {
                    flex: 1;
                    overflow-y: auto;
                    padding: 16px;
                    display: flex;
                    flex-direction: column;
                    gap: 12px;
                }

                .chatbot-message {
                    display: flex;
                    margin-bottom: 8px;
                }

                .chatbot-message.visitor {
                    justify-content: flex-end;
                }

                .chatbot-message-content {
                    max-width: 80%;
                    padding: 12px 16px;
                    border-radius: 8px;
                    word-break: break-word;
                    font-size: 14px;
                    line-height: 1.4;
                }

                .chatbot-message.visitor .chatbot-message-content {
                    background: #007AFF;
                    color: white;
                    border-radius: 8px 0 8px 8px;
                }

                .chatbot-message.employee .chatbot-message-content {
                    background: #f0f0f0;
                    color: #333;
                    border-radius: 0 8px 8px 8px;
                }

                .chatbot-message-time {
                    font-size: 12px;
                    color: #999;
                    margin-top: 4px;
                    padding: 0 4px;
                }

                .chatbot-input-area {
                    padding: 16px;
                    border-top: 1px solid #e0e0e0;
                    display: flex;
                    gap: 8px;
                }

                .chatbot-input {
                    flex: 1;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 10px 12px;
                    font-size: 14px;
                    font-family: inherit;
                    resize: none;
                    max-height: 100px;
                }

                .chatbot-input:focus {
                    outline: none;
                    border-color: #007AFF;
                    box-shadow: 0 0 0 3px rgba(0, 122, 255, 0.1);
                }

                .chatbot-send {
                    background: #000;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 10px 16px;
                    cursor: pointer;
                    font-weight: 500;
                    transition: background 0.2s;
                }

                .chatbot-send:hover {
                    background: #333;
                }

                .chatbot-send:disabled {
                    background: #ccc;
                    cursor: not-allowed;
                }

                .chatbot-loading {
                    text-align: center;
                    padding: 20px;
                    color: #999;
                }

                .chatbot-spinner {
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    border: 2px solid #f3f3f3;
                    border-top: 2px solid #000;
                    border-radius: 50%;
                    animation: spin 1s linear infinite;
                }

                @keyframes spin {
                    0% { transform: rotate(0deg); }
                    100% { transform: rotate(360deg); }
                }

                @media (max-width: 600px) {
                    .chatbot-window {
                        width: 100%;
                        height: 100%;
                        right: 0;
                        bottom: 0;
                        border-radius: 0;
                    }

                    .chatbot-message-content {
                        max-width: 90%;
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
