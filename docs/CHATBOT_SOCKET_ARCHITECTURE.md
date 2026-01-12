# Real-Time Socket Architecture

## System Architecture Overview

```
CLIENT (Browser)                WEB SERVER (Laravel)              WEBSOCKET SERVER
┌──────────────────────┐        ┌──────────────────────┐         ┌──────────────────┐
│  Chatbot Widget      │        │  ChatbotApiController │        │  Reverb/Pusher  │
│  (chatbot-widget.js) │        │  ChatbotService       │        │  (Port 8080)     │
│                      │        │                      │        │                  │
│ 1. Load Echo + Pusher├───────→│ API Endpoints        │        │                  │
│                      │  REST  │ ├─ /init             │        │                  │
│ 2. Try Providers:   │  HTTP  │ ├─ /message          │───┐    │                  │
│    ├─ Pusher        │        │ ├─ /config   ⭐ NEW  │   │    │                  │
│    ├─ Reverb        │        │ ├─ /file             │   │    │                  │
│    └─ Polling       │        │ └─ /voice            │   │    │                  │
│                      │        │                      │   │    │                  │
│ 3. Subscribe:       │        │ Event Dispatcher     │   └───→│ private:chat.*   │
│    private:chat.*    ├─WebSocket─→ broadcast()      │   WebSocket  Listen       │
│                      │        │ Event: ChatMsg       │        │                  │
│ 4. Listen:          │        │ Received             │        │                  │
│    ChatMessageRcvd   │←─WebSocket─ Send to subscribers←───────│                  │
│                      │        │                      │        │                  │
│ 5. Display in UI     │        │ Queue Worker        │        │                  │
│    (Instant!)        │        │ (if using queue)    │        │                  │
│                      │        │                      │        │                  │
└──────────────────────┘        └──────────────────────┘        └──────────────────┘
```

## Real-Time Provider Selection Flow

```
Widget Loads
    │
    ▼
Load Dependencies
├─ Laravel Echo (CDN)
└─ Pusher (CDN) - optional
    │
    ▼
Try Initialize Real-Time
    │
    ├─────────────────────────────────┐
    │                                 │
    ▼                                 ▼
Pusher Available?                Reverb/Local Available?
    │ YES                            │ YES
    ├─→ Use Pusher ✓         ├─→ Use Reverb ✓
    │   wss://api.pusher.com │     ws://localhost:8080
    │                                 │
    │ NO                              │ NO
    └────────────────────→ Use Polling (HTTP 2s) ⚠️
```

## Message Real-Time Flow

```
User Types Message
        ↓
Click Send Button
        ↓
JavaScript: sendMessage()
        ↓
HTTP POST /api/chatbot/message
        │
        ▼
Laravel: ChatbotApiController::sendMessage()
        ↓
Validate & Store in DB
        ↓
Fire Event: ChatMessageReceived($message)
        ↓
broadcast(event) → Queue
        ↓
Queue Worker Processes
        ↓
Send to Broadcasting Backend (Reverb/Pusher)
        ↓
broadcast() → private:chat.conversation.{id}
        ↓
Connected Browsers Receive via WebSocket
        ↓
handleNewMessage(event)
        ↓
Update state.messages
        ↓
renderMessages()
        ↓
✓ Message appears instantly in UI
```

## Fallback Chain

```
Try Real-Time (1st)     Try Real-Time (2nd)     Fall Back (Last)
┌──────────────────┐  ┌──────────────────┐  ┌──────────────────┐
│    PUSHER        │  │     REVERB       │  │    POLLING       │
├──────────────────┤  ├──────────────────┤  ├──────────────────┤
│ wss://           │  │ ws://            │  │ HTTP GET every   │
│ api.pusher.com   │  │ localhost:8080   │  │ 2 seconds        │
│                  │  │                  │  │                  │
│ cluster: mt1     │  │ port: 8080       │  │ endpoint:        │
│ encrypted: true  │  │ forceTLS: false  │  │ /api/chatbot/    │
│                  │  │                  │  │ conversation/{id}│
│ ✓ if available   │  │ ✓ currently used │  │                  │
│ ✗ external svc   │  │ ✓ local server   │  │ Always available │
└──────────┬───────┘  └────────┬─────────┘  └────────┬─────────┘
           │ Fails             │ Fails               │ Success
           └─────────────┬─────┘                     │
                         │                           │
                         └───────────┬───────────────┘
                                     │
                            Use Active Provider
                                     │
                            ┌────────┴───────┐
                            │                │
                            ▼                ▼
                        Subscribe        Subscribe
                        to Channel      to Channel
                            │                │
                            └────────┬───────┘
                                     │
                          Listen for Real-Time
                          ChatMessageReceived
```

## Configuration Endpoints

```
GET /api/chatbot/config (NEW)
    │
    ├─ Authorization: Bearer {token}
    │
    ▼
Response: {
    success: true,
    realtimeProvider: 'reverb',
    
    reverb: {
        enabled: true,
        key: 'ysgs6pjrsn52uowbeuv7',
        host: 'localhost',
        port: 8080,
        scheme: 'http'
    },
    
    pusher: {
        enabled: false,
        key: '...',
        cluster: 'mt1',
        encrypted: true
    }
}
```

---

**Version**: 2.0 (Real-Time Enabled)  
**Date**: January 2026  
**Status**: Production Ready
