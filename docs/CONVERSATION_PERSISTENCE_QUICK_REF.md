# Conversation Persistence - Quick Reference

## ✨ What Changed?

Visitors can now **refresh the page without losing their chat conversation**. The widget will resume the existing conversation automatically.

## 🎯 Key Features

| Feature | Before | After |
|---------|--------|-------|
| Page Refresh | New conversation | Resume existing |
| Chat History | Lost | Preserved |
| Conversation Duration | Single session | Up to 24 hours |
| Admin Close | No notification | Real-time update |

## 📱 User Experience

### Visitor Flow
```
1. Opens chatbot, sends message → Conversation ID stored
2. Refreshes page → Conversation resumes automatically
3. Continues chatting → History visible
4. After 24 hours → New conversation on refresh
```

### Admin Close Flow
```
1. Admin clicks "Close Chat" button
2. Real-time event sent to widget
3. Widget shows "Conversation ended" message
4. Input field disabled
5. Visitor refresh = new conversation
```

## 🛠️ Files Modified

| File | Changes |
|------|---------|
| `public/chatbot-widget.js` | Added localStorage persistence logic |
| `app/Events/ConversationClosed.php` | **NEW** - Broadcast event |
| `app/Http/Controllers/Admin/ChatbotController.php` | Updated close() to broadcast event |

## 💾 localStorage Keys

```javascript
// Stored automatically, you don't need to touch these:
localStorage.getItem('chatbot_conversation_id')      // e.g., "123"
localStorage.getItem('chatbot_conversation_status')  // "active" or "closed"
localStorage.getItem('chatbot_conversation_timestamp') // "1234567890"
```

## 🔍 Testing

### ✅ Test Case 1: Resume Conversation
1. Open chatbot widget
2. Type "Hello" and send
3. Refresh browser (F5)
4. **Expected**: Message visible, same conversation ID

### ✅ Test Case 2: Admin Closes
1. Visitor sends message
2. Admin: Open admin dashboard → Go to conversation
3. Admin: Click "Close Chat" button
4. Visitor: See "Conversation ended by admin" message
5. Visitor: Refresh page
6. **Expected**: New conversation created (old one gone)

### ✅ Test Case 3: 24-Hour Expiry
1. Open DevTools → Application → localStorage
2. Find `chatbot_conversation_timestamp`
3. Change timestamp to 25 hours ago: `Math.floor(Date.now() / 1000) - (25*3600)*1000`
4. Refresh page
5. **Expected**: New conversation created (old one expired)

## 🚨 Debug Console

Widget logs in browser console:
```javascript
// Starting new conversation
🆕 Creating new conversation

// Resuming existing
📝 Resuming existing conversation: 123

// Admin closed
🔴 Conversation closed by admin
```

Check with: Open DevTools (F12) → Console tab

## 🔧 Customization

### Change Session Timeout (default: 24 hours)

Edit `public/chatbot-widget.js`, find:
```javascript
if (age > 24) { // ← Change this number
    clearConversationStorage();
    return null;
}
```

For example, change to `7` for 7-hour timeout:
```javascript
if (age > 7) { // Now expires after 7 hours
    clearConversationStorage();
    return null;
}
```

## 📊 Storage Details

**Data Stored**: Conversation ID only (no messages, no personal data)
**Storage Size**: ~100 bytes total
**Duration**: Up to 24 hours (auto-clears after)
**Manual Clear**: User can clear in DevTools or browser settings

## ⚠️ Limitations & Notes

- ✅ Works across browser tabs (same browser instance)
- ❌ Doesn't sync across different browsers
- ❌ Doesn't sync across devices
- ✅ Works in incognito mode (session-based)
- ✅ Works offline for queued messages
- ❌ Lost if user clears browser cache

## 🆘 Troubleshooting

**Issue**: "Creating new conversation" on every refresh
- Check if localStorage is enabled
- Check if conversation status is "closed"
- Check browser console for errors

**Issue**: "Resume existing conversation" but no messages appear
- Messages still being loaded (check Network tab)
- Admin conversation might be different
- Try force refresh (Ctrl+Shift+R)

**Issue**: Admin close not notifying visitor
- Check if real-time (Pusher/Reverb) is connected
- Check browser console for "real-time connected" message
- Try polling fallback (automatic)

## 🎉 Benefits

✨ Better user experience - no data loss
✨ Improved visitor retention - can continue anytime
✨ Clear conversation closure - admin controls when chat ends
✨ 24-hour sessions - natural conversation timeframe
✨ Privacy-friendly - auto-clear after timeout
