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
        isRecording: false,
        mediaRecorder: null,
        audioChunks: [],
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
                    width: 56px;
                    height: 56px;
                    border-radius: 50%;
                    background: #000;
                    color: white;
                    border: none;
                    cursor: pointer;
                    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.25);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    z-index: 999998;
                    transition: all 0.2s ease;
                }

                .chatbot-bubble:hover {
                    transform: scale(1.1);
                    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
                }

                .chatbot-bubble:active {
                    transform: scale(0.95);
                }

                .chatbot-bubble svg {
                    width: 24px;
                    height: 24px;
                    stroke: white;
                    stroke-width: 2;
                    fill: none;
                    stroke-linecap: round;
                    stroke-linejoin: round;
                }

                /* Chat Window */
                .chatbot-window {
                    position: fixed;
                    bottom: 90px;
                    right: 24px;
                    width: 400px;
                    height: 600px;
                    background: white;
                    border-radius: 12px;
                    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.16);
                    display: none;
                    flex-direction: column;
                    z-index: 999999;
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Helvetica Neue', sans-serif;
                    animation: slideUp 0.3s ease;
                    overflow: hidden;
                    border: 1px solid #eee;
                }

                @keyframes slideUp {
                    from {
                        opacity: 0;
                        transform: translateY(10px);
                    }
                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                .chatbot-window.open {
                    display: flex;
                }

                /* Header */
                .chatbot-header {
                    background: #000;
                    color: white;
                    padding: 16px 20px;
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    flex-shrink: 0;
                    border-radius: 12px 12px 0 0;
                }

                .chatbot-header h3 {
                    margin: 0;
                    font-size: 16px;
                    font-weight: 600;
                    letter-spacing: -0.3px;
                }

                .chatbot-close {
                    background: none;
                    border: none;
                    color: white;
                    cursor: pointer;
                    font-size: 24px;
                    padding: 0;
                    width: 28px;
                    height: 28px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transition: opacity 0.2s;
                }

                .chatbot-close:hover {
                    opacity: 0.7;
                }

                .chatbot-close svg {
                    width: 20px;
                    height: 20px;
                    stroke: white;
                    stroke-width: 2;
                    fill: none;
                }

                /* Messages Container */
                .chatbot-messages {
                    flex: 1;
                    overflow-y: auto;
                    overflow-x: hidden;
                    padding: 16px;
                    display: flex;
                    flex-direction: column;
                    gap: 10px;
                    background: #f9f9f9;
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

                /* Message */
                .chatbot-message {
                    display: flex;
                    margin-bottom: 2px;
                    animation: fadeIn 0.3s ease;
                }

                @keyframes fadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(5px);
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
                }

                .chatbot-message.visitor .chatbot-message-wrapper {
                    align-items: flex-end;
                }

                .chatbot-message.employee .chatbot-message-wrapper {
                    align-items: flex-start;
                }

                .chatbot-message-content {
                    padding: 10px 14px;
                    border-radius: 8px;
                    word-break: break-word;
                    font-size: 13px;
                    line-height: 1.4;
                }

                .chatbot-message.visitor .chatbot-message-content {
                    background: #000;
                    color: white;
                    border-radius: 8px 0 8px 8px;
                }

                .chatbot-message.employee .chatbot-message-content {
                    background: white;
                    color: #333;
                    border: 1px solid #ddd;
                    border-radius: 0 8px 8px 8px;
                }

                .chatbot-message-time {
                    font-size: 11px;
                    color: #999;
                    margin-top: 4px;
                    padding: 0 4px;
                }

                /* Input Area */
                .chatbot-input-area {
                    padding: 12px;
                    border-top: 1px solid #eee;
                    display: flex;
                    gap: 8px;
                    background: white;
                    flex-shrink: 0;
                }

                .chatbot-input {
                    flex: 1;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 9px 12px;
                    font-size: 13px;
                    font-family: inherit;
                    resize: none;
                    max-height: 100px;
                    transition: border-color 0.2s;
                    background: white;
                }

                .chatbot-input:focus {
                    outline: none;
                    border-color: #000;
                    box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
                }

                .chatbot-input::placeholder {
                    color: #999;
                }

                /* Buttons */
                .chatbot-action-btn {
                    background: #f0f0f0;
                    color: #333;
                    border: 1px solid #ddd;
                    border-radius: 8px;
                    padding: 8px 10px;
                    cursor: pointer;
                    font-size: 16px;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    width: 36px;
                    height: 36px;
                    line-height: 1;
                }

                .chatbot-action-btn:hover {
                    background: #e0e0e0;
                    border-color: #999;
                }

                .chatbot-action-btn.recording {
                    background: #ff4444;
                    color: white;
                    border-color: #ff4444;
                    animation: pulse 1s infinite;
                }

                @keyframes pulse {
                    0%, 100% { box-shadow: 0 0 0 0 rgba(255, 68, 68, 0.4); }
                    50% { box-shadow: 0 0 0 6px rgba(255, 68, 68, 0); }
                }

                .chatbot-action-btn svg {
                    width: 16px;
                    height: 16px;
                    stroke: currentColor;
                    stroke-width: 2;
                    fill: none;
                    stroke-linecap: round;
                    stroke-linejoin: round;
                }

                .chatbot-send {
                    background: #000;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    padding: 8px 14px;
                    cursor: pointer;
                    font-weight: 500;
                    font-size: 13px;
                    transition: all 0.2s;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    height: 36px;
                    white-space: nowrap;
                    flex-shrink: 0;
                }

                .chatbot-send:hover {
                    background: #222;
                }

                .chatbot-send:active {
                    background: #111;
                }

                .chatbot-send:disabled {
                    background: #ccc;
                    cursor: not-allowed;
                }

                /* File/Voice Messages */
                .chatbot-file-message {
                    padding: 8px;
                    background: rgba(0, 0, 0, 0.05);
                    border-radius: 6px;
                    margin-top: 6px;
                }

                .chatbot-message.visitor .chatbot-file-message {
                    background: rgba(0, 0, 0, 0.2);
                }

                .chatbot-file-link {
                    display: flex;
                    align-items: center;
                    gap: 6px;
                    color: #000;
                    text-decoration: none;
                    font-size: 12px;
                    font-weight: 500;
                    padding: 6px;
                    background: white;
                    border-radius: 4px;
                    transition: all 0.2s;
                }

                .chatbot-message.visitor .chatbot-file-link {
                    color: white;
                    background: rgba(0, 0, 0, 0.3);
                }

                .chatbot-file-link:hover {
                    background: #f0f0f0;
                }

                .chatbot-message.visitor .chatbot-file-link:hover {
                    background: rgba(0, 0, 0, 0.4);
                }

                .chatbot-file-link svg {
                    width: 14px;
                    height: 14px;
                    stroke: currentColor;
                    stroke-width: 2;
                    fill: none;
                }

                .chatbot-voice-player {
                    display: flex;
                    align-items: center;
                    gap: 8px;
                    background: white;
                    padding: 6px 8px;
                    border-radius: 4px;
                    margin-top: 6px;
                    border: 1px solid #ddd;
                }

                .chatbot-message.visitor .chatbot-voice-player {
                    background: rgba(0, 0, 0, 0.2);
                    border-color: rgba(0, 0, 0, 0.3);
                }

                .chatbot-voice-player audio {
                    max-width: 160px;
                    height: 24px;
                }

                /* Loading State */
                .chatbot-loading {
                    text-align: center;
                    padding: 30px 20px;
                    color: #999;
                }

                .chatbot-spinner {
                    display: inline-block;
                    width: 16px;
                    height: 16px;
                    border: 2px solid #eee;
                    border-top-color: #000;
                    border-radius: 50%;
                    animation: spin 0.6s linear infinite;
                    margin-bottom: 10px;
                }

                @keyframes spin {
                    to { transform: rotate(360deg); }
                }

                /* Mobile Responsive */
                @media (max-width: 480px) {
                    .chatbot-bubble {
                        bottom: 16px;
                        right: 16px;
                        width: 52px;
                        height: 52px;
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
                        padding: 12px;
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
                <div>
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

        // File upload handler
        document.getElementById('chatbot-file-btn').addEventListener('click', () => {
            document.getElementById('chatbot-file-input').click();
        });

        document.getElementById('chatbot-file-input').addEventListener('change', (e) => {
            const file = e.target.files[0];
            if (file) {
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

        try {
            document.getElementById('chatbot-send').disabled = true;
            
            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/file`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: formData,
            });

            if (response.ok) {
                const data = await response.json();
                state.messages.push({
                    sender_type: 'visitor',
                    message: `📎 ${file.name}`,
                    attachment_path: data.file_url,
                    attachment_name: file.name,
                    timestamp: new Date().toISOString(),
                });
                renderMessages();
                // Reset file input
                document.getElementById('chatbot-file-input').value = '';
            }
        } catch (error) {
            console.error('Failed to send file:', error);
            alert('Failed to upload file. Please try again.');
        } finally {
            document.getElementById('chatbot-send').disabled = false;
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
        formData.append('message', '🎤 Voice message');

        try {
            document.getElementById('chatbot-send').disabled = true;
            
            const response = await fetch(`${CONFIG.widgetUrl}/api/chatbot/voice`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${CONFIG.apiToken}`,
                },
                body: formData,
            });

            if (response.ok) {
                const data = await response.json();
                state.messages.push({
                    sender_type: 'visitor',
                    message: '🎤 Voice message',
                    attachment_path: data.file_url,
                    is_voice: true,
                    timestamp: new Date().toISOString(),
                });
                renderMessages();
            }
        } catch (error) {
            console.error('Failed to send voice message:', error);
            alert('Failed to send voice message. Please try again.');
        } finally {
            document.getElementById('chatbot-send').disabled = false;
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
