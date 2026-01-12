# Chat Widget - Setup and Deployment Guide

## 🚀 Quick Start for Production

### Step 1: Run Migrations on Production

SSH into your production server and run:

```bash
cd /path/to/ryven-team-management
php artisan migrate
```

This creates the necessary database tables:
- `chatbot_widgets`
- `chat_conversations`
- `chat_messages`

### Step 2: Create Your First Chatbot Widget

```bash
php artisan tinker
```

Then paste and run:

```php
$widget = App\Models\ChatbotWidget::create([
    'name' => 'Website Chat',
    'domain' => 'team.ryven.co',
    'welcome_message' => 'Welcome! How can we help you?',
    'is_active' => true,
]);

echo "API Token: " . $widget->api_token;
```

Copy the API token (it starts with `cbw_` followed by 60 random characters).

### Step 3: Embed the Widget in Your Website

In your website's HTML (at the bottom of the `<body>` tag):

```html
<script>
  window.ChatbotConfig = {
    widgetUrl: 'https://team.ryven.co',
    apiToken: 'cbw_YOUR_TOKEN_HERE',  // Replace with your token from Step 2
    visitorName: 'Guest',
    visitorEmail: 'user@example.com',
  };
</script>
<script src="https://team.ryven.co/chatbot-widget.js"></script>
```

**Important:**
- Replace `cbw_YOUR_TOKEN_HERE` with your actual token
- Replace `team.ryven.co` with your actual domain
- Keep the token secret (treat it like a password)

### Step 4: Test the Widget

1. Open your website in a browser
2. You should see a black chat bubble in the bottom-right corner
3. Click it to open the chat widget
4. Try sending a message
5. Check the browser console (F12) for any errors

---

## 🔧 Configuration Options

The `ChatbotConfig` object supports the following options:

```javascript
window.ChatbotConfig = {
    // REQUIRED
    widgetUrl: 'https://team.ryven.co',      // Your domain URL
    apiToken: 'cbw_...',                     // Widget API token
    
    // OPTIONAL
    visitorName: 'Guest',                    // Default visitor name
    visitorEmail: 'user@example.com',        // Visitor email
    visitorPhone: '+1-234-567-8900',         // Visitor phone
    visitorId: 'unique-id',                  // Track repeat visitors
    visitorMetadata: {                       // Custom data object
        company: 'Acme Corp',
        plan: 'premium',
        // ... any custom fields
    },
};
```

---

## 📊 Database Tables

### chatbot_widgets
Stores widget configurations

```sql
id          | INTEGER | Primary Key
name        | VARCHAR | Widget name
domain      | VARCHAR | Domain where widget is installed
api_token   | VARCHAR | Bearer token for API (cbw_...)
installed_in| VARCHAR | Where widget code is installed
welcome_message | VARCHAR | Welcome message for visitors
is_active   | BOOLEAN | Enable/disable widget
settings    | JSON    | Additional settings
created_at  | TIMESTAMP
updated_at  | TIMESTAMP
```

### chat_conversations
Stores visitor conversations

```sql
id                    | INTEGER | Primary Key
chatbot_widget_id     | INTEGER | FK to chatbot_widgets
visitor_name          | VARCHAR | Visitor's name
visitor_email         | VARCHAR | Visitor's email
visitor_phone         | VARCHAR | Visitor's phone
visitor_ip            | VARCHAR | Visitor's IP address
visitor_metadata      | JSON    | Browser info, location, etc
assigned_to_employee_id | INTEGER | FK to employees (optional)
status                | ENUM    | pending, active, closed
last_message_at       | TIMESTAMP
created_at            | TIMESTAMP
updated_at            | TIMESTAMP
```

### chat_messages
Stores individual messages

```sql
id                      | INTEGER | Primary Key
chat_conversation_id    | INTEGER | FK to chat_conversations
sender_type             | VARCHAR | visitor, employee
sender_id               | INTEGER | Optional FK to employees
message                 | TEXT    | Message content
attachment_path         | VARCHAR | Path to uploaded file
attachment_name         | VARCHAR | Original filename
is_voice                | BOOLEAN | Is this a voice message?
read_at                 | TIMESTAMP | When message was read
created_at              | TIMESTAMP
updated_at              | TIMESTAMP
```

---

## 🔐 Security Considerations

### API Token Security
- The `api_token` is like a password - keep it secret
- Use HTTPS only on production
- Consider rotating tokens periodically
- Don't commit tokens to version control
- Use environment variables to store tokens

### Rate Limiting
Consider adding rate limiting to prevent abuse:

```php
// In routes/web.php
Route::middleware(['throttle:60,1'])->group(function () {
    Route::post('/api/chatbot/init', [ChatbotApiController::class, 'initWidget']);
    Route::post('/api/chatbot/message', [ChatbotApiController::class, 'sendMessage']);
    Route::post('/api/chatbot/file', [ChatbotApiController::class, 'uploadFile']);
    Route::post('/api/chatbot/voice', [ChatbotApiController::class, 'uploadVoice']);
});
```

### File Upload Security
- Maximum file size: 10MB
- Allowed extensions: All (configurable)
- Files stored in: `storage/app/public/chatbot-uploads/`
- Files accessible via: `/storage/chatbot-uploads/{path}`

---

## 📝 API Reference

### POST /api/chatbot/init
Initialize a new chat session

**Request:**
```json
{
  "visitor_name": "John Doe",
  "visitor_email": "john@example.com",
  "visitor_phone": "+1-234-567-8900",
  "visitor_metadata": {
    "custom_field": "value"
  }
}
```

**Response:**
```json
{
  "success": true,
  "conversation_id": 123,
  "widget_name": "Website Chat",
  "welcome_message": "Welcome!"
}
```

### POST /api/chatbot/message
Send a message

**Request:**
```json
{
  "conversation_id": 123,
  "message": "Hello!",
  "sender_type": "visitor",
  "sender_id": null
}
```

**Response:**
```json
{
  "success": true,
  "message_id": 456,
  "timestamp": "2026-01-12 14:30:45"
}
```

### GET /api/chatbot/conversation/{id}
Get conversation history

**Response:**
```json
{
  "success": true,
  "data": {
    "id": 123,
    "visitor_name": "John Doe",
    "visitor_email": "john@example.com",
    "status": "active",
    "messages": [
      {
        "id": 456,
        "sender_type": "visitor",
        "sender_name": "John Doe",
        "message": "Hello!",
        "timestamp": "2026-01-12 14:30:45",
        "is_voice": false
      }
    ]
  }
}
```

### POST /api/chatbot/file
Upload a file

**Request (multipart/form-data):**
```
conversation_id: 123
file: <file>
sender_type: visitor
message: (optional)
```

**Response:**
```json
{
  "success": true,
  "file_url": "/storage/chatbot-uploads/..."
}
```

### POST /api/chatbot/voice
Upload a voice message

**Request (multipart/form-data):**
```
conversation_id: 123
voice_message: <audio file>
sender_type: visitor
message: (optional)
```

**Response:**
```json
{
  "success": true,
  "file_url": "/storage/chatbot-uploads/..."
}
```

---

## 🎯 Admin Management

### View Conversations (Admin)
```php
// In controller or artisan tinker
$conversations = ChatConversation::with('messages', 'assignedEmployee')->get();
```

### Assign Conversation to Employee
```php
// In artisan tinker
$conversation = ChatConversation::find(1);
$conversation->update(['assigned_to_employee_id' => 5]);
```

### Mark Conversation as Closed
```php
$conversation = ChatConversation::find(1);
$conversation->update(['status' => 'closed']);
```

---

## 🚨 Troubleshooting

### "Server error" on `/api/chatbot/message`

**Causes:**
1. Migrations not run → Run `php artisan migrate`
2. Widget not created → Create in tinker
3. Wrong API token → Use correct token from database
4. Conversation doesn't exist → Call `/api/chatbot/init` first
5. Conversation doesn't belong to widget → Verify conversation is linked to widget

**Debug:**
```bash
# Enable debug mode temporarily
.env: APP_DEBUG=true

# Check logs
tail -f storage/logs/laravel.log

# Test API directly
curl -X POST https://team.ryven.co/api/chatbot/message \
  -H "Authorization: Bearer cbw_YOUR_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "conversation_id": 1,
    "message": "test",
    "sender_type": "visitor"
  }'
```

### Widget Not Appearing

**Check:**
1. JavaScript console for errors (F12)
2. Is the token correct?
3. Is the domain correct?
4. Is the widget CSS loading?

### Messages Not Sending

**Check:**
1. Is the conversation initialized? (Check `/api/chatbot/init`)
2. Is the conversation_id correct?
3. Is the API token correct?
4. Check browser Network tab for failed requests

---

## 📈 Monitoring & Maintenance

### Regular Tasks
- Monitor file uploads size and cleanup old files
- Archive old conversations monthly
- Review widget analytics
- Check error logs regularly

### Performance Tips
- Use database indexing (migrations already included)
- Archive conversations after 30 days
- Clean up old uploaded files
- Monitor API response times

---

## 🔄 Updates & Upgrades

### Updating the Widget
1. Pull latest code
2. Run migrations: `php artisan migrate`
3. Clear cache: `php artisan cache:clear`
4. Rebuild assets if needed: `npm run build`

### Backup Before Updates
```bash
# Backup database
mysqldump -u user -p database > backup.sql

# Backup uploaded files
tar -czf chatbot-files-backup.tar.gz storage/app/public/chatbot-uploads/
```

---

## 📚 Additional Resources

- See `CHATBOT_WIDGET_UX_ENHANCEMENTS.md` for feature details
- See `CHATBOT_WIDGET_QUICK_REFERENCE.md` for quick reference
- See `CHATBOT_WIDGET_VISUAL_GUIDE.md` for UI/UX details
- See `CHATBOT_WIDGET_SERVER_ERROR_TROUBLESHOOTING.md` for error debugging

---

## ✅ Deployment Checklist

Before going live:

- [ ] Migrations run on production
- [ ] Chatbot widget created in database
- [ ] API token saved securely
- [ ] Widget embedded in website
- [ ] Test message sending
- [ ] Test file uploads
- [ ] Test voice recording (HTTPS required)
- [ ] Check mobile responsiveness
- [ ] Verify error logging works
- [ ] Monitor first 24 hours
- [ ] Set up backups
- [ ] Document token in secure location

---

## 🎉 You're Ready!

Your chat widget is now set up and ready to use. Visitors will see the chat bubble and be able to message you directly from your website.

Good luck! 🚀
