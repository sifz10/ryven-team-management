# Chatbot System Architecture Diagram

## System Overview

```
┌─────────────────────────────────────────────────────────────────┐
│                    EXTERNAL APPLICATIONS                        │
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────┐         │
│  │   CRM App    │  │  Website     │  │  Custom App  │         │
│  │              │  │              │  │              │         │
│  └──────┬───────┘  └──────┬───────┘  └──────┬───────┘         │
│         │                 │                 │                  │
│         └─────────────────┼─────────────────┘                  │
│                           │                                    │
│         ┌─────────────────▼──────────────────┐                 │
│         │   Chatbot Widget (JavaScript)      │                 │
│         │  public/chatbot-widget.js           │                 │
│         │                                    │                 │
│         │  • Floating chat bubble            │                 │
│         │  • Message input/display           │                 │
│         │  • Real-time updates               │                 │
│         └─────────────────┬──────────────────┘                 │
│                           │                                    │
└───────────────────────────┼────────────────────────────────────┘
                            │
                   [Bearer Token Auth]
                            │
                 ┌──────────▼──────────┐
                 │   INTERNET/HTTPS    │
                 └──────────┬──────────┘
                            │
┌───────────────────────────┼────────────────────────────────────┐
│                           │   MAIN SYSTEM (Laravel)            │
│                           │                                    │
│         ┌─────────────────▼──────────────────┐                 │
│         │   Chat API Controller              │                 │
│         │  ChatbotApiController              │                 │
│         │                                    │                 │
│         │  POST /api/chatbot/init            │                 │
│         │  POST /api/chatbot/message         │                 │
│         │  GET  /api/chatbot/conversation    │                 │
│         └──────────────┬─────────────────────┘                 │
│                        │                                       │
│         ┌──────────────▼─────────────────┐                    │
│         │   ChatbotService               │                    │
│         │  • Authenticate widget         │                    │
│         │  • Get/create conversation     │                    │
│         │  • Store messages              │                    │
│         │  • Format responses            │                    │
│         └──────────────┬─────────────────┘                    │
│                        │                                       │
│      ┌─────────────────┼─────────────────┐                    │
│      │                 │                 │                    │
│      ▼                 ▼                 ▼                    │
│  ┌────────┐      ┌──────────┐      ┌──────────┐              │
│  │ Chat   │      │ Chat     │      │ Chatbot  │              │
│  │Messages│      │Conver-   │      │ Widget   │              │
│  │        │      │sations   │      │ Configs  │              │
│  └────────┘      └──────────┘      └──────────┘              │
│      │                 │                 │                    │
│      └─────────────────┼─────────────────┘                    │
│                        │                                       │
│                   [MySQL Database]                            │
│                        │                                       │
│         ┌──────────────▼──────────────────┐                   │
│         │  ChatMessageReceived Event      │                   │
│         │  Broadcasting                   │                   │
│         └──────────────┬──────────────────┘                   │
│                        │                                       │
└────────────────────────┼───────────────────────────────────────┘
                         │
                [WebSocket/Reverb]
                         │
        ┌────────────────┼────────────────┐
        │                │                │
        ▼                ▼                ▼
   ┌────────┐      ┌────────┐      ┌────────────┐
   │Widget  │      │Widget  │      │Admin Panel │
   │(User 1)│      │(User 2)│      │Dashboard   │
   │        │      │        │      │            │
   │Updates │      │Updates │      │Updates     │
   │in RT   │      │in RT   │      │in RT       │
   └────────┘      └────────┘      └────────────┘
```

## Message Flow

### 1. Visitor Sends Message

```
Widget (CRM) 
    ↓
    Makes POST /api/chatbot/message
    ↓
ChatbotApiController
    ↓
ChatbotService::storeMessage()
    ↓
ChatMessage created in DB
    ↓
Event: ChatMessageReceived
    ↓
Broadcasts to 'chat.conversation.{id}' channel
    ↓
Widget updates in real-time ✓
Admin dashboard updates in real-time ✓
```

### 2. Admin Sends Reply

```
Admin Dashboard
    ↓
Click "Send Reply"
    ↓
POST /admin/chatbot/{id}/reply
    ↓
ChatbotController::sendReply()
    ↓
ChatMessage created (sender_type: employee)
    ↓
Event: ChatMessageReceived broadcasts
    ↓
Widget receives update
    ↓
Visitor sees reply in real-time ✓
```

## Authentication Flow

```
External App (CRM)
    │
    ├─ Embeds: <script data-api-token="cbw_xxx">
    │
    ├─ Widget loads and calls init
    │         │
    │         └─ POST /api/chatbot/init
    │            Header: Authorization: Bearer cbw_xxx
    │
    └─ Server validates token against ChatbotWidget
       │
       ├─ If valid: Return conversation_id ✓
       │
       └─ If invalid: Return 401 ✗


For subsequent messages:
    │
    ├─ POST /api/chatbot/message
    │  Header: Authorization: Bearer cbw_xxx
    │  (Same token used throughout)
    │
    └─ All requests must include valid Bearer token
```

## Real-Time Update Architecture

```
Database Changes
    ↓
Model creates ChatMessage
    ↓
Model dispatches ChatMessageReceived event
    ↓
Event implements ShouldBroadcast
    ↓
Laravel broadcasts to channel: 'chat.conversation.{id}'
    ↓
Reverb WebSocket Server
    ↓
    ├─ Connected widget clients
    │  └─ Receive update, render message
    │
    └─ Connected admin clients
       └─ Receive update, refresh conversation


Without Reverb: Falls back to polling (slower)
With Reverb: Instant (WebSocket)
```

## Database Schema Relationships

```
ChatbotWidget (1)
    │
    ├─ hasMany ChatConversation
    │             │
    │             └─ hasMany ChatMessage
    │                         │
    │                         ├─ BelongsTo ChatConversation
    │                         │
    │                         └─ BelongsTo sender (Employee or null)
    │
    └─ Generated: api_token (unique, auto-generated)


Employee (1)
    │
    └─ hasMany ChatConversation (assigned_to_employee_id)
```

## Widget Lifecycle

```
1. LOAD PHASE
   External app loads: <script data-api-token="cbw_xxx">
   Widget JS executes IIFE (Immediately Invoked Function)

2. INITIALIZATION PHASE
   createWidgetHTML() → Injects CSS + HTML (bubble + window)
   initChat() → POST /api/chatbot/init
   Server creates ChatConversation record

3. READY PHASE
   loadMessages() → GET /api/chatbot/conversation/{id}
   setupEventListeners() → Attach click/keypress handlers
   setupRealtimeUpdates() → Connect Echo.private channel (if available)

4. INTERACTION PHASE
   User clicks bubble → Window opens
   User types message → Send button available
   User clicks send → POST /api/chatbot/message
   New message added to state.messages array
   renderMessages() → Update UI
   Reverb broadcasts → Both widget and admin update

5. REAL-TIME PHASE
   Echo.private('chat.conversation.{id}')
   .listen('.chat.message.received', callback)
   When admin sends reply → Message received event
   Widget renders new message instantly
```

## Configuration Files

```
config/
├─ broadcasting.php
│  └─ BROADCAST_CONNECTION=reverb (required for real-time)
│
└─ services.php
   └─ Optional: chatbot settings
```

## Environment Variables Required

```env
# For Widget API to work
APP_URL=https://team.ryven.co

# For Real-Time Messaging
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_PORT=8080

# Frontend Reverb config (for Widget Echo)
VITE_REVERB_HOST=team.ryven.co
VITE_REVERB_PORT=443
VITE_REVERB_SCHEME=https
```

## Scaling Considerations

```
Low Volume (< 100 conversations/day)
├─ Single server sufficient
├─ No special configuration needed
└─ Real-time via Reverb with default settings

Medium Volume (100-1000 conversations/day)
├─ Load balance API servers
├─ Use Reverb on separate server
├─ Database indexes on: widget_id, created_at, status
└─ Monitor message queue

High Volume (1000+ conversations/day)
├─ Dedicated Reverb cluster
├─ Database replication (read replicas)
├─ Message archival (old conversations to separate table)
├─ Cache layer for widget configs
└─ Consider async message processing (queues)
```

---

This architecture provides:
✅ **Scalability** - Can handle multiple external apps  
✅ **Security** - Token-based, no credentials exposed  
✅ **Real-Time** - WebSocket broadcasts  
✅ **Persistence** - All data stored in DB  
✅ **User Experience** - Instant message delivery  
