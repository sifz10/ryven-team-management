# 🎨 Chatbot System - Visual Quick Reference

## Installation Quick Steps

```
┌─────────────────────────────────────────────────────────┐
│  YOUR WEBSITE / CRM                                     │
│                                                         │
│  <script src="chatbot-widget.js"                        │
│    data-api-token="cbw_xxx"                            │
│    data-widget-url="https://...">                      │
│  </script>                                              │
└────────────────┬────────────────────────────────────────┘
                 │
                 │ Customer sees
                 ▼
            ┌─────────┐
            │   💬    │  ◄─ Chat Bubble
            └─────────┘
                 │
           Customer clicks
                 │
                 ▼
        ┌───────────────────┐
        │   Chat Window     │
        │  Messages here    │ ◄─ Real-time
        │ [Type & Send] 🔘  │
        └─────────┬─────────┘
                  │
           Message sent
                  │
                  ▼
        ┌───────────────────┐
        │ Your System DB    │
        │ chat_messages     │
        │ chat_conversations│
        └────────┬──────────┘
                 │
         Broadcast to admin
                 │
                 ▼
         ┌──────────────────────┐
         │  /admin/chatbot      │
         │  Conversation List   │
         │  Message Thread      │
         │  [Reply] 🔘          │
         └──────┬───────────────┘
                │
          Admin replies
                │
                ▼
         Message to widget
                │
                ▼
    Customer sees reply (Real-time!)
```

---

## Directory Structure

```
/chatbot-system
├── Backend
│   ├── Models (3)
│   │   ├── ChatbotWidget
│   │   ├── ChatConversation
│   │   └── ChatMessage
│   ├── Controllers (2)
│   │   ├── ChatbotApiController (API)
│   │   └── ChatbotController (Admin)
│   ├── Service (1)
│   │   └── ChatbotService
│   ├── Event (1)
│   │   └── ChatMessageReceived
│   └── Migrations (3)
│       ├── chatbot_widgets
│       ├── chat_conversations
│       └── chat_messages
│
├── Frontend
│   ├── Widget (1)
│   │   └── public/chatbot-widget.js
│   └── Admin Views (2)
│       ├── admin/chatbot/index.blade.php
│       └── admin/chatbot/show.blade.php
│
└── Documentation
    ├── CHATBOT_WIDGET_SETUP.md
    ├── CHATBOT_WIDGET_SYSTEM.md
    ├── CHATBOT_ARCHITECTURE.md
    ├── CHATBOT_DEPLOYMENT_CHECKLIST.md
    └── (This file)
```

---

## Data Flow Diagram

```
┌──────────────────────────────────────────────────────────────┐
│                  External Application                        │
│  (Your CRM, Website, Custom App)                            │
└────────────────────┬─────────────────────────────────────────┘
                     │
        ┌────────────▼────────────┐
        │  Chatbot Widget (JS)    │
        │  • Floating Bubble      │
        │  • Message Input        │
        │  • Real-time Updates    │
        └────────────┬────────────┘
                     │
        ┌────────────▼────────────────────────────┐
        │  HTTP/HTTPS + Bearer Token Auth        │
        │  /api/chatbot/init                     │
        │  /api/chatbot/message                  │
        │  /api/chatbot/conversation/{id}        │
        └────────────┬────────────────────────────┘
                     │
        ┌────────────▼────────────────────────────┐
        │  Main System (Laravel)                 │
        │                                        │
        │  ┌──────────────────────────────────┐ │
        │  │  ChatbotApiController            │ │
        │  │  • Validate Token                │ │
        │  │  • Process Messages              │ │
        │  │  • Return Conversations          │ │
        │  └─────────┬────────────────────────┘ │
        │            │                          │
        │  ┌─────────▼────────────────────────┐ │
        │  │  ChatbotService                  │ │
        │  │  • Auth Widget                   │ │
        │  │  • Create Conversation           │ │
        │  │  • Store Message                 │ │
        │  │  • Get History                   │ │
        │  └─────────┬────────────────────────┘ │
        │            │                          │
        │  ┌─────────▼────────────────────────┐ │
        │  │  Models                          │ │
        │  │  • ChatbotWidget                 │ │
        │  │  • ChatConversation              │ │
        │  │  • ChatMessage                   │ │
        │  └─────────┬────────────────────────┘ │
        │            │                          │
        └────────────┼──────────────────────────┘
                     │
        ┌────────────▼────────────────────────────┐
        │  MySQL Database                        │
        │  • chatbot_widgets                     │
        │  • chat_conversations                  │
        │  • chat_messages                       │
        └────────────┬────────────────────────────┘
                     │
        ┌────────────▼────────────────────────────┐
        │  ChatMessageReceived Event              │
        │  (Broadcasts to Reverb)                │
        └────────────┬────────────────────────────┘
                     │
        ┌────────────┼────────────────────────────┐
        │            │                           │
        ▼            ▼                           ▼
   ┌────────┐  ┌────────┐              ┌────────────────┐
   │Widget  │  │Widget  │              │Admin Dashboard │
   │User 1  │  │User 2  │              │                │
   │        │  │        │              │/admin/chatbot  │
   │Updated │  │Updated │              │                │
   │in RT   │  │in RT   │              │Updated in RT   │
   └────────┘  └────────┘              └────────────────┘
```

---

## Message Journey

```
Step 1: Widget Initialization
┌─────────────────────────────────┐
│ External App loads widget       │
│ <script data-api-token="cbw_X"> │
└────────────┬────────────────────┘
             │
             ▼
    ┌──────────────────┐
    │ createWidgetHTML │
    │ initChat()       │
    │ setupListeners() │
    └────────┬─────────┘
             │
             ▼
    POST /api/chatbot/init
    (Create or get conversation)

Step 2: Message Sent
┌─────────────────────────────────┐
│ User types and clicks Send      │
│ Message text: "Hello!"          │
└────────────┬────────────────────┘
             │
             ▼
    POST /api/chatbot/message
    {
      conversation_id: 1,
      message: "Hello!",
      sender_type: "visitor"
    }

Step 3: Stored in Database
┌─────────────────────────────────┐
│ ChatbotService::storeMessage()  │
└────────────┬────────────────────┘
             │
             ▼
    Insert into chat_messages
    INSERT INTO chat_messages (
      chat_conversation_id = 1,
      sender_type = 'visitor',
      message = 'Hello!',
      created_at = NOW()
    )

Step 4: Real-Time Broadcast
┌─────────────────────────────────┐
│ ChatMessageReceived Event       │
│ broadcasts on channel:          │
│ 'chat.conversation.1'           │
└────────────┬────────────────────┘
             │
             ▼
    ┌────────┴──────────┐
    │                   │
    ▼                   ▼
  Widget            Admin Panel
  Receives          Receives
  Update            Update
  
Step 5: UI Updates
Widget: Adds message to list, scrolls down
Admin:  Shows new message, highlights unread

Step 6: Admin Replies
┌─────────────────────────────────┐
│ Admin types reply in dashboard  │
│ "Hi there! How can we help?"    │
└────────────┬────────────────────┘
             │
             ▼
    POST /admin/chatbot/1/reply
    {
      message: "Hi there! How can we help?"
    }

Step 7: Reply Stored & Broadcast
┌─────────────────────────────────┐
│ ChatMessage created             │
│ sender_type = 'employee'        │
└────────────┬────────────────────┘
             │
             ▼
    ChatMessageReceived broadcasts
    to chat.conversation.1
             │
             ▼
    Widget receives & displays reply
    in real-time (< 100ms)
```

---

## Widget Lifecycle

```
1. PAGE LOADS
   │
   └─> <script> tag executes
       IIFE (Immediately Invoked Function Expression)

2. WIDGET INITIALIZATION
   │
   ├─> createWidgetHTML()
   │   ├─ Create CSS styles
   │   ├─ Create HTML elements
   │   └─ Inject into DOM
   │
   ├─> initChat()
   │   ├─ POST /api/chatbot/init
   │   ├─ Get conversation_id
   │   └─ Store in state
   │
   └─> setupEventListeners()
       ├─ Click bubble
       ├─ Click close
       ├─ Click send
       └─ Keypress (Enter to send)

3. USER INTERACTION
   │
   ├─> User clicks bubble
   │   └─ Toggle window visibility
   │
   ├─> User types message
   │   └─ Update state
   │
   └─> User clicks send
       ├─ POST /api/chatbot/message
       ├─ Add to local state
       └─ renderMessages()

4. REAL-TIME UPDATES
   │
   ├─> Echo.private channel listening
   │   └─ On message received
   │
   ├─> Message added to state
   │
   └─> renderMessages()
       └─ Update DOM
```

---

## Admin Dashboard Flow

```
Landing Page: /admin/chatbot
│
├─ Load conversations
│  └─ Query with filters
│
├─ Display stats
│  ├─ Total conversations
│  ├─ Pending
│  ├─ Active
│  ├─ Closed
│  └─ Unread
│
└─ Show conversation list
   ├─ Filter options (status, widget, employee)
   ├─ Search box
   ├─ Pagination
   └─ Table with conversations

Click conversation
│
├─ Load full conversation
│  └─ GET /api/chatbot/conversation/1
│
├─ Display message history
│  ├─ Visitor messages
│  ├─ Employee replies
│  └─ Timestamps
│
├─ Show visitor info
│  ├─ Name
│  ├─ Email
│  ├─ Phone
│  ├─ IP Address
│  └─ Metadata
│
└─ Show action panel
   ├─ Reply form (textarea + send)
   ├─ Assign dropdown
   ├─ Close button
   └─ Delete button

Admin types reply
│
├─ POST /admin/chatbot/1/reply
│  └─ { message: "..." }
│
├─ Message saved
│
├─ Event broadcast to widget
│
└─ Widget displays reply in real-time
```

---

## Database Schema Visualization

```
chatbot_widgets
├─ id (PK)
├─ name
├─ domain (unique)
├─ api_token (unique) ◄─ Used for authentication
├─ installed_in
├─ welcome_message
├─ is_active
├─ settings (JSON)
└─ timestamps
   │
   └─ hasMany
      │
      ▼
   chat_conversations
   ├─ id (PK)
   ├─ chatbot_widget_id (FK)
   ├─ visitor_name
   ├─ visitor_email
   ├─ visitor_phone
   ├─ visitor_ip
   ├─ visitor_metadata (JSON)
   ├─ assigned_to_employee_id (FK)
   ├─ status (pending, active, closed)
   ├─ last_message_at
   └─ timestamps (+ soft deletes)
      │
      └─ hasMany
         │
         ▼
      chat_messages
      ├─ id (PK)
      ├─ chat_conversation_id (FK)
      ├─ sender_type (visitor, employee)
      ├─ sender_id
      ├─ message
      ├─ attachment_path
      ├─ attachment_name
      ├─ read_at
      └─ timestamps
```

---

## API Authentication Flow

```
1. WIDGET GENERATION
   └─ API Token: cbw_abc123xyz789 (unique)

2. EXTERNAL APP
   └─ Add to script tag
      <script data-api-token="cbw_abc123xyz789">

3. WIDGET CALLS API
   └─ POST /api/chatbot/init
      Header: Authorization: Bearer cbw_abc123xyz789

4. SERVER VALIDATION
   └─ ChatbotService::authenticateWidget($token)
      ├─ Find token in database
      ├─ Check widget is active
      └─ Return ChatbotWidget | null

5. RESPONSE
   ├─ If valid: Return conversation_id ✓
   └─ If invalid: Return 401 error ✗

6. SUBSEQUENT REQUESTS
   └─ ALL requests include same Bearer token
      ├─ POST /api/chatbot/message
      ├─ GET /api/chatbot/conversation/{id}
      └─ All require valid token
```

---

## Real-Time Channel Architecture

```
Reverb WebSocket Server
│
├─ Private channel: chat.conversation.1
│  ├─ Connected widget 1 (User A)
│  ├─ Connected widget 2 (User B)
│  └─ Connected admin browser
│
├─ Private channel: chat.conversation.2
│  ├─ Connected widget 1 (User C)
│  └─ Connected admin browser
│
└─ Private channel: chat.conversation.3
   └─ Connected admin browser

Event: ChatMessageReceived
│
├─ Broadcasts on channel: chat.conversation.{id}
│
└─ All connected clients receive:
   {
     id: 42,
     conversation_id: 1,
     sender_type: 'employee',
     sender_name: 'Support Agent',
     message: 'How can we help?',
     timestamp: '2026-01-11 14:30:00'
   }

Listeners react:
├─ Widget: renderMessages()
└─ Admin: reload conversation or update in-place
```

---

## Responsive Design

```
Desktop (≥ 1024px)
┌────────────────────────────────────────┐
│ Full window                            │
│ Fixed position: bottom-right           │
│ Width: 400px                           │
│ Height: 600px                          │
│                                        │
│ ┌──────────────────────────────────┐  │
│ │ Chat Bubble                      │  │
│ │ 🔵 (56px diameter)               │  │
│ │ (stays visible)                  │  │
│ └──────────────────────────────────┘  │
└────────────────────────────────────────┘

Tablet (768px - 1023px)
┌────────────────────────────────────┐
│ Width: 400px (responsive)          │
│ Height: 600px                      │
│ Adjusts to screen                  │
└────────────────────────────────────┘

Mobile (< 768px)
┌─────────────────────────────────┐
│ Full width                      │
│ Full height (minus keyboard)    │
│ Maximized: 100% × 100%          │
│ Takes entire screen             │
└─────────────────────────────────┘
```

---

## Status & Workflow

```
Conversation Status Flow

New Conversation
      │
      ▼
   pending ◄─ Waiting for assignment
      │
      ▼
   active ◄─ Admin actively replying
      │
      ├─ (Can close anytime)
      │
      ▼
   closed ◄─ Finished
      │
      └─ (Can soft delete for recovery)
```

---

## Performance Metrics

```
Widget Load Time
  External App        Chatbot Widget         Main System
      │                   │                      │
      ├─ Load script ─────>│                      │
      │  (15KB)            │                      │
      │                    ├─ POST /api/init ─────>│
      │                    │                      ├─ Auth token
      │                    │                      ├─ Get/create conv
      │<─── Response ──────┤<─── JSON response ────┤
      │                    │                      │
      │                    ├─ GET /api/conversation
      │                    │                      ├─ Load messages
      │<─── Messages ──────┤<─── JSON ─────────────┤
      │                    │                      │
      └─ Ready (< 2s)      │                      │

Message Delivery
  Customer sends       Main System            Admin sees
      │                    │                      │
      ├─ POST message ─────>│                      │
      │                    ├─ Store in DB        │
      │                    ├─ Broadcast event ───>│
      │<─── ACK ───────────┤                      │<─ Update
      │                    │                      │
      └─ (< 100ms)         │                      │ (< 100ms)
```

---

This visual guide helps understand the entire chatbot system at a glance! 🎨

