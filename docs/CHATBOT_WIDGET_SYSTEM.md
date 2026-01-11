# Chatbot Widget System - Setup & Usage Guide

## Overview
The chatbot widget system allows you to embed a chat interface into any external application (CRM, website, etc.) with seamless authentication and real-time messaging. Messages are stored in your main system and can be managed by admin users.

## Architecture

### Components
1. **Embeddable Widget** (`public/chatbot-widget.js`) - Lightweight JS that embeds into external sites
2. **Chat API** - Token-based REST API for widget communication
3. **Admin Dashboard** - Manage conversations and send replies
4. **Real-time Broadcasting** - Laravel Reverb for instant message delivery

### Data Flow
```
External App (CRM)
    ↓
Chatbot Widget (JavaScript)
    ↓
Chat API (/api/chatbot/*)
    ↓
Chatbot System (Laravel)
    ↓
Admin Dashboard
```

## Installation Steps

### 1. Create a Chatbot Widget in Admin

First, create a new widget entry:

```bash
php artisan tinker
```

```php
App\Models\ChatbotWidget::create([
    'name' => 'CRM Chat',
    'domain' => 'crm.yourapp.com',
    'installed_in' => 'CRM',
    'welcome_message' => 'Welcome! How can we help you?',
    'is_active' => true,
]);
```

This will generate a unique `api_token` (e.g., `cbw_xxxxxxxxx`)

### 2. Install Widget in Your External App

Add this single script tag to your external application (CRM):

```html
<script 
    src="https://team.ryven.co/chatbot-widget.js" 
    data-api-token="cbw_YOUR_TOKEN_HERE"
    data-widget-url="https://team.ryven.co"
    data-visitor-name="John Doe"
    data-visitor-email="john@example.com"
    data-visitor-phone="+1234567890"
    data-visitor-id="crm_user_123"
></script>
```

### 3. Configure Attributes

The script tag accepts these optional attributes:

| Attribute | Type | Description |
|-----------|------|-------------|
| `data-api-token` | string | **Required** - Widget authentication token |
| `data-widget-url` | string | **Required** - Your main app URL |
| `data-visitor-name` | string | Name of the visitor (auto-filled if possible) |
| `data-visitor-email` | string | Email address (for CRM lookup) |
| `data-visitor-phone` | string | Phone number |
| `data-visitor-id` | string | External system visitor ID |
| `data-visitor-metadata` | JSON | Custom metadata object |

### Example: CRM Integration

```html
<!-- In your CRM template -->
<script 
    src="https://team.ryven.co/chatbot-widget.js" 
    data-api-token="cbw_abc123xyz789"
    data-widget-url="https://team.ryven.co"
    data-visitor-name="{{ $currentUser->name }}"
    data-visitor-email="{{ $currentUser->email }}"
    data-visitor-phone="{{ $currentUser->phone }}"
    data-visitor-id="{{ $currentUser->id }}"
    data-visitor-metadata='{"crm_account":"{{ $currentUser->account_id }}", "department":"{{ $currentUser->department }}"}'
></script>
```

## Admin Dashboard

### Access Chatbot Management
- URL: `https://team.ryven.co/admin/chatbot`
- Shows all conversations, stats, and filters
- Real-time message updates

### Key Features

#### Conversation List
- Filter by status (Pending, Active, Closed)
- Filter by widget
- Sort by last message time
- See unread message count

#### View Conversation
- Full message history
- Visitor information (email, phone, IP, metadata)
- Send direct replies
- Assign to employee
- Close or delete conversation
- Real-time message updates via Reverb

#### Reply to Messages
- Type reply in admin dashboard
- Send instantly to visitor's widget
- Appear in real-time on visitor's browser
- Marked with employee name

## API Reference

### POST `/api/chatbot/init`
Initialize or retrieve conversation for a visitor.

**Headers:**
```
Authorization: Bearer cbw_YOUR_TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
    "visitor_name": "John Doe",
    "visitor_email": "john@example.com",
    "visitor_phone": "+1234567890",
    "visitor_id": "crm_user_123",
    "visitor_metadata": {
        "crm_account": "acc_123",
        "department": "Sales"
    }
}
```

**Response:**
```json
{
    "success": true,
    "conversation_id": 1,
    "widget_name": "CRM Chat",
    "welcome_message": "Welcome! How can we help you?"
}
```

---

### POST `/api/chatbot/message`
Send a message in a conversation.

**Headers:**
```
Authorization: Bearer cbw_YOUR_TOKEN
Content-Type: application/json
```

**Request Body:**
```json
{
    "conversation_id": 1,
    "message": "Hello, I need help with...",
    "sender_type": "visitor"
}
```

**Response:**
```json
{
    "success": true,
    "message_id": 42,
    "timestamp": "2026-01-11 14:30:00"
}
```

---

### GET `/api/chatbot/conversation/{id}`
Retrieve full conversation history with all messages.

**Headers:**
```
Authorization: Bearer cbw_YOUR_TOKEN
```

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "visitor_name": "John Doe",
        "visitor_email": "john@example.com",
        "visitor_phone": "+1234567890",
        "status": "active",
        "assigned_to": {
            "id": 5,
            "name": "Support Agent"
        },
        "messages": [
            {
                "id": 1,
                "sender_type": "visitor",
                "sender_name": "John Doe",
                "message": "Hello!",
                "timestamp": "2026-01-11 14:25:00"
            },
            {
                "id": 2,
                "sender_type": "employee",
                "sender_name": "Support Agent",
                "message": "Hi John! How can we help?",
                "timestamp": "2026-01-11 14:26:00"
            }
        ],
        "created_at": "2026-01-11 14:20:00",
        "last_message_at": "2026-01-11 14:26:00"
    }
}
```

## Real-Time Updates

### Laravel Reverb Integration

The system broadcasts messages in real-time using Laravel Reverb. Both the widget and admin dashboard receive instant updates:

1. **Widget listens on**: `private.chat.conversation.{id}`
2. **Admin listens on**: `private.chat.conversation.{id}`
3. **Event**: `.chat.message.received`

### Example: Custom Frontend Integration

```javascript
// In your custom frontend app
Echo.private('chat.conversation.1')
    .listen('.chat.message.received', (event) => {
        console.log('New message:', event.message);
        // Update UI with new message
    });
```

## Security

### Authentication
- Widget uses Bearer token in Authorization header
- Token is tied to specific domain/widget
- Server verifies widget is active before processing

### Data Privacy
- Visitor metadata optional (not required)
- IP address logged for security
- Soft deletes - conversations can be recovered
- All messages indexed by conversation

### Token Security
```php
// Generate new token if compromised
$widget = ChatbotWidget::find(1);
$newToken = $widget->generateNewToken();
```

## Troubleshooting

### Widget Not Showing
1. Check token is correct in script tag
2. Check widget is active: `ChatbotWidget::find(1)->is_active`
3. Check domain matches (if domain filtering enabled)
4. Check browser console for CORS errors

### Messages Not Appearing in Admin
1. Verify Reverb is running: `php artisan reverb:start`
2. Check WebSocket connection in browser DevTools
3. Verify chat conversation was created: `ChatConversation::count()`

### Real-Time Not Working
1. Ensure `BROADCAST_CONNECTION=reverb` in `.env`
2. Check Reverb server is accessible from client IP
3. Check `VITE_REVERB_*` environment variables
4. Run `npm run dev` to compile frontend assets

## Database Cleanup

```php
// Get all conversations for a widget
$widget = ChatbotWidget::find(1);
$conversations = $widget->conversations();

// Soft delete old conversations (30 days)
ChatConversation::where('created_at', '<', now()->subDays(30))->delete();

// Permanently delete
ChatConversation::whereNull('deleted_at')->forceDelete();
```

## Environment Configuration

```env
# Required for real-time features
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
REVERB_PORT=8080

# Widget URLs
CHATBOT_WIDGET_URL=https://team.ryven.co
```

## Examples

### Example 1: Simple Website Chat

```html
<!DOCTYPE html>
<html>
<head>
    <title>Our Website</title>
</head>
<body>
    <h1>Welcome to our website</h1>
    
    <!-- Chatbot widget -->
    <script 
        src="https://team.ryven.co/chatbot-widget.js" 
        data-api-token="cbw_website_token"
        data-widget-url="https://team.ryven.co"
        data-visitor-name="Guest"
    ></script>
</body>
</html>
```

### Example 2: Authenticated CRM User

```blade
<!-- In Laravel Blade template -->
<script 
    src="{{ config('app.url') }}/chatbot-widget.js" 
    data-api-token="{{ $chatbotToken }}"
    data-widget-url="{{ config('app.url') }}"
    data-visitor-name="{{ Auth::user()->name }}"
    data-visitor-email="{{ Auth::user()->email }}"
    data-visitor-phone="{{ Auth::user()->phone }}"
    data-visitor-id="{{ Auth::user()->id }}"
    data-visitor-metadata='@json([
        "account_id" => Auth::user()->account_id,
        "user_role" => Auth::user()->role,
    ])'
></script>
```

### Example 3: Dynamic Installation Script

```javascript
// Generate widget script dynamically
function installChatbot(token, userName, userEmail) {
    const script = document.createElement('script');
    script.src = 'https://team.ryven.co/chatbot-widget.js';
    script.setAttribute('data-api-token', token);
    script.setAttribute('data-widget-url', 'https://team.ryven.co');
    script.setAttribute('data-visitor-name', userName);
    script.setAttribute('data-visitor-email', userEmail);
    document.body.appendChild(script);
}

// Usage
installChatbot('cbw_abc123', 'John Doe', 'john@example.com');
```

## Performance Considerations

- Widget is lightweight (~15KB minified)
- Lazy loads messages on demand
- Handles 1000+ concurrent conversations
- Real-time updates via WebSocket (not polling)
- Messages indexed by `created_at` and `conversation_id`

## Future Enhancements

- File attachments in messages
- Typing indicators
- Chat history export
- Auto-assignment rules
- Canned responses
- Chat satisfaction rating
- Conversation search
- Analytics dashboard
