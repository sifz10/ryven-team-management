# Chat Widget Server Error - Troubleshooting Guide

## Error: "Server error" on POST `/api/chatbot/message`

This guide helps you diagnose and fix the server error.

## 🔍 Root Causes (Most Common First)

### 1. **Migrations Not Run on Production**
The chatbot tables don't exist in your database.

**Check:**
```bash
# SSH to your production server
php artisan migrate:status | grep chat

# Should show:
# 2026_01_11_000001_create_chatbot_widgets_table.php
# 2026_01_11_000002_create_chat_conversations_table.php
# 2026_01_11_000003_create_chat_messages_table.php
# 2026_01_12_141307_add_is_voice_to_chat_messages.php
```

**Fix:**
```bash
php artisan migrate
```

### 2. **Chatbot Widget Not Created**
You haven't created a chatbot widget in the database.

**Check:**
```bash
php artisan tinker
>>> App\Models\ChatbotWidget::all();
# Should return at least one widget

# Get the API token:
>>> $widget = App\Models\ChatbotWidget::first();
>>> echo $widget->api_token;
```

**Fix:**
```bash
php artisan tinker
>>> $widget = App\Models\ChatbotWidget::create([
    'name' => 'My Website Chat',
    'domain' => 'team.ryven.co',
    'welcome_message' => 'Welcome to our chat!',
    'is_active' => true,
]);
>>> echo $widget->api_token;
```

### 3. **Wrong API Token in Widget**
The token in your embed code doesn't match any widget.

**Check:**
- What token are you using in `CONFIG.apiToken`?
- Does it exist in the database?

```bash
php artisan tinker
>>> App\Models\ChatbotWidget::where('api_token', 'cbw_YOUR_TOKEN_HERE')->first();
# Should return the widget, not null
```

**Fix:**
- Get the correct token from the database
- Update your embed code with the correct token

### 4. **Conversation Not Found**
The conversation_id in the message request doesn't exist.

**Check:** The error might be "validation failed" with details about conversation_id
- Did you first call `/api/chatbot/init` to create a conversation?
- Is the conversation_id correct?

**Fix:**
```javascript
// Make sure to:
// 1. Call initWidget first
const initResponse = await fetch('/api/chatbot/init', {...});
const initData = await initResponse.json();
const conversationId = initData.conversation_id; // Use this in messages

// 2. Then use that conversationId in sendMessage
```

## 🛠️ Server-Side Debugging

### Check Application Logs
```bash
# SSH to production
tail -f storage/logs/laravel.log

# Look for errors with "Send message error:" or validation details
```

### Enable Debug Mode Temporarily
```bash
# SSH to production
# Edit .env
APP_DEBUG=true

# Test the endpoint
# This will return error details in the response

# Important: Turn it off after debugging!
APP_DEBUG=false
```

### Test the Endpoint Directly
```bash
# Replace TOKEN and CONVERSATION_ID with real values
curl -X POST https://team.ryven.co/api/chatbot/message \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer cbw_YOUR_TOKEN_HERE" \
  -d '{
    "conversation_id": 1,
    "message": "Test message",
    "sender_type": "visitor"
  }'
```

## 📋 Complete Setup Checklist

### 1. Database Setup
- [ ] Run migrations: `php artisan migrate`
- [ ] Verify tables exist: Check `chat_conversations`, `chat_messages`, `chatbot_widgets`

### 2. Create Chatbot Widget
- [ ] Create widget in database
- [ ] Copy the API token (starts with `cbw_`)
- [ ] Verify widget is active (`is_active = true`)

### 3. Get Widget Token
```bash
php artisan tinker
$widget = App\Models\ChatbotWidget::first();
echo "Token: " . $widget->api_token;
```

### 4. Update Embed Code
```html
<script>
  window.ChatbotConfig = {
    widgetUrl: 'https://team.ryven.co',
    apiToken: 'cbw_YOUR_TOKEN_FROM_STEP_3',
    visitorName: 'Guest',
    visitorEmail: 'user@example.com',
  };
</script>
<script src="https://team.ryven.co/chatbot-widget.js"></script>
```

### 5. Test on Browser
- [ ] Open website where widget is embedded
- [ ] Open Developer Tools (F12)
- [ ] Go to Console tab
- [ ] Check for JavaScript errors
- [ ] Go to Network tab
- [ ] Send a message
- [ ] Check if `/api/chatbot/message` request succeeds
- [ ] Look at response details

## 🔐 Security Notes

- Only set `APP_DEBUG=true` for debugging, always turn it off
- The `api_token` is sensitive - keep it secret
- The token allows anyone to send messages - use HTTPS only
- Consider implementing rate limiting on production

## 📊 Expected Response

### Success Response
```json
{
  "success": true,
  "message_id": 123,
  "timestamp": "2026-01-12 14:30:45"
}
```

### Error Response
```json
{
  "error": "Server error",
  "debug": "Error details here (only if APP_DEBUG=true)"
}
```

## 🚀 Quick Start (If Everything is New)

```bash
# 1. SSH to production server
ssh user@team.ryven.co

# 2. Run migrations
cd /path/to/ryven-team-management
php artisan migrate

# 3. Create widget
php artisan tinker
>>> $widget = App\Models\ChatbotWidget::create(['name' => 'Website Chat', 'domain' => 'team.ryven.co', 'is_active' => true]);
>>> echo $widget->api_token;

# 4. Copy the token and update your embed code
# 5. Test in browser
```

## 📞 Still Not Working?

1. **Check Laravel logs:** `tail -f storage/logs/laravel.log`
2. **Verify database connection:** Can Laravel connect to MySQL?
3. **Check table structure:** `php artisan tinker` → `\DB::table('chat_conversations')->where('id', 1)->first();`
4. **Test with curl:** Manually test the API endpoint
5. **Check network:** Is the request reaching the server? (Network tab in DevTools)

## 🎯 Most Likely Cause

Based on the error, the **most likely causes are:**

1. **❌ Migrations not run** (90% probability)
   - Solution: `php artisan migrate`

2. **❌ Widget token doesn't exist** (8% probability)
   - Solution: Create widget with `php artisan tinker`

3. **❌ Wrong token in embed code** (2% probability)
   - Solution: Verify token matches database

## Next Steps

1. Check if migrations are run
2. Create a chatbot widget if needed
3. Update your embed code with the correct token
4. Test again

If you still get an error after these steps, **enable APP_DEBUG=true**, try again, and share the "debug" field from the error response for further diagnosis.
