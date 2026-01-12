# 🚀 DEPLOYMENT CHECKLIST - Real-Time Messaging

## Pre-Deployment (Development)

### Code Quality
- [ ] Run `php artisan syntax:check` or `php -l` on all modified files
- [ ] No PHP syntax errors
- [ ] No JavaScript console errors
- [ ] All routes registered properly
- [ ] All controller methods exist

### Testing
- [ ] Test polling system (messages appear in ~1s)
- [ ] Test real-time system (if Pusher available)
- [ ] Test admin message sending
- [ ] Test message read marking
- [ ] Test error scenarios (network failure, etc.)

### Configuration
- [ ] `.env` file configured correctly
- [ ] `BROADCAST_CONNECTION` set (pusher or reverb)
- [ ] Pusher credentials (if using Pusher)
- [ ] Database migrations run
- [ ] Cache cleared: `php artisan cache:clear`
- [ ] Views compiled: `php artisan view:clear`

---

## Before Going Live

### Security Check
- [ ] Admin authentication required (✅ in code)
- [ ] CSRF protection enabled (✅ in code)
- [ ] Input validation enabled (✅ in code)
- [ ] SSL/HTTPS enabled
- [ ] Pusher credentials stored in `.env` (not hardcoded)

### Performance Check
- [ ] Response time <200ms
- [ ] Database query optimized
- [ ] No N+1 queries
- [ ] Message loading with relationships
- [ ] Polling interval appropriate (1000ms default)

### Functionality Check
- [ ] Messages load on page
- [ ] Polling updates in real-time
- [ ] Echo/Pusher works (or polling backup works)
- [ ] Message read marking works
- [ ] Conversation timestamp updates
- [ ] Admin messages appear instantly

---

## Deployment Steps

### Step 1: Backup
```bash
# Backup database
mysqldump -u root -p database_name > backup_$(date +%Y%m%d).sql

# Backup code (git)
git add -A
git commit -m "Backup before real-time deployment"
git push
```

### Step 2: Update Code
```bash
# Pull latest code
git pull origin main

# Clear caches
php artisan cache:clear
php artisan view:clear
php artisan config:clear

# Run migrations (if any)
php artisan migrate
```

### Step 3: Verify Routes
```bash
php artisan route:list | Select-String "chatbot.*messages"
# Should show: GET|HEAD admin/chatbot/{conversation}/messages
```

### Step 4: Restart Services
```bash
# Restart queue worker (if using)
php artisan queue:restart

# Restart echo/reverb (if using)
# Kill existing processes and restart
```

### Step 5: Test
```
1. Open admin dashboard
2. Send test message
3. Verify message appears within 1 second
4. Check logs for errors
```

---

## Post-Deployment Monitoring

### Immediate (First Hour)
- [ ] Monitor error logs: `tail -f storage/logs/laravel.log`
- [ ] Check admin dashboard is accessible
- [ ] Send test messages
- [ ] Verify messages appear

### Short-term (First Day)
- [ ] Monitor response times
- [ ] Check error rate
- [ ] Verify no 401/403 errors
- [ ] Confirm polling requests are being made

### Long-term (Ongoing)
- [ ] Monitor message delivery times
- [ ] Track API response times
- [ ] Monitor server resource usage
- [ ] Check for any logged errors

---

## Rollback Plan

If anything goes wrong:

### Option 1: Revert to Previous Version
```bash
git revert HEAD
git push
```

### Option 2: Temporarily Disable Real-Time
Edit `resources/views/admin/chatbot/show.blade.php`:
```javascript
// Comment out polling
// startPolling();

// Messages still load on page, just no auto-update
```

### Option 3: Emergency Contact
Check error logs for specific issues:
```bash
tail -n 100 storage/logs/laravel.log | grep -i error
```

---

## Success Criteria

After deployment, verify:

✅ **Messages load on page** - Initial conversation loads
✅ **Polling works** - GET requests every 1 second
✅ **Messages appear** - Visitor messages in admin within ~1s
✅ **No errors** - Clean error logs
✅ **Performance OK** - Response times <200ms
✅ **Scaling ready** - Works with multiple conversations

---

## Environment Variables

Ensure these are set in production `.env`:

```env
APP_ENV=production
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=your_production_id
PUSHER_APP_KEY=your_production_key
PUSHER_APP_SECRET=your_production_secret
PUSHER_APP_CLUSTER=ap2
```

---

## Production URLs to Test

```
https://team.ryven.co/admin/chatbot/28
  └─ Should load conversations

https://team.ryven.co/admin/chatbot/28/messages
  └─ Should return JSON with messages
  └─ Requires admin login

https://team.ryven.co/api/chatbot/message
  └─ Visitor message endpoint
  └─ Requires widget token
```

---

## Troubleshooting Deployment Issues

### Issue: 401 on messages API
**Cause**: Auth middleware too strict
**Solution**: Check if routes under `auth` middleware group

### Issue: Empty messages array
**Cause**: Database query not loading messages
**Solution**: Run migration if needed, check database

### Issue: Polling too slow
**Cause**: Server overload or slow DB
**Solution**: Check DB query performance, optimize if needed

### Issue: Real-time not working (polling works)
**Cause**: Pusher credentials wrong
**Solution**: Verify `.env` has correct Pusher credentials

### Issue: 500 errors in logs
**Cause**: Relationship not loaded
**Solution**: Check if `messages.sender` relationship loaded in controller

---

## Performance Tuning

If polling is causing issues:

### Reduce Polling Frequency
```javascript
// In show.blade.php line 274
// Increase interval: 2000 = 2 seconds (less server load)
}, 2000);
```

### Optimize Message Query
```php
// In ChatbotController::getMessages()
$messages = $conversation->messages()
    ->with('sender')  // Eager load sender
    ->latest()
    ->limit(100)      // Limit to recent 100
    ->get();
```

### Database Indexing
Ensure these are indexed:
- `chat_messages.chat_conversation_id`
- `chat_messages.created_at`
- `chat_messages.sender_type`

---

## Monitoring Alerts

Set up alerts for:
- [ ] API response time > 500ms
- [ ] Error rate > 1%
- [ ] 401/403 errors spike
- [ ] Message delivery delay > 5s
- [ ] Server CPU > 80%
- [ ] Database connections > 50%

---

## Documentation for Operations Team

### For Support
- **Real-time system**: Polling-based fallback with Pusher enhancement
- **Delivery guarantee**: ~1 second via polling
- **No refresh needed**: Messages update automatically
- **Admin only**: Messages visible only to logged-in admins

### For DevOps
- **Services**: Web server + database (Pusher cloud-based)
- **No new services**: Uses existing Laravel infrastructure
- **Scalable**: HTTP polling, not WebSocket
- **Monitoring**: Check `/admin/chatbot` response times

---

## Verification Command

After deployment, run this command to verify everything:

```bash
#!/bin/bash

echo "Verifying Real-Time Messaging Deployment..."

# 1. Check route exists
php artisan route:list | grep "chatbot.*messages" && echo "✅ Route registered" || echo "❌ Route missing"

# 2. Check controller method exists
grep -q "public function getMessages" app/Http/Controllers/Admin/ChatbotController.php && echo "✅ Method exists" || echo "❌ Method missing"

# 3. Check view has polling
grep -q "startPolling()" resources/views/admin/chatbot/show.blade.php && echo "✅ Polling code present" || echo "❌ Polling code missing"

# 4. Test database
php artisan tinker --execute "echo App\Models\ChatConversation::count() . ' conversations'" && echo "✅ Database accessible" || echo "❌ Database error"

# 5. Check syntax
php -l app/Http/Controllers/Admin/ChatbotController.php > /dev/null && echo "✅ PHP syntax OK" || echo "❌ PHP syntax error"

echo "Verification complete!"
```

---

## Sign-Off

When deployment complete and verified:

- [ ] Code deployed successfully
- [ ] Tests passed
- [ ] Monitoring in place
- [ ] Team notified
- [ ] Documentation updated
- [ ] Rollback plan ready

**Deployed by**: ________________
**Date**: ________________
**Version**: ________________

---

## Quick Reference

| Action | Command |
|--------|---------|
| Clear caches | `php artisan cache:clear && php artisan view:clear` |
| View logs | `tail -f storage/logs/laravel.log` |
| Check routes | `php artisan route:list \| grep chatbot` |
| Test API | `curl -H "Authorization: Bearer TOKEN" http://localhost:8000/admin/chatbot/28/messages` |
| Restart queue | `php artisan queue:restart` |
| Restart echo | Kill process, restart `php artisan reverb:start` |

---

**Status**: Ready for Deployment
**Risk Level**: Low (polling provides guaranteed fallback)
**Rollback Time**: <5 minutes
