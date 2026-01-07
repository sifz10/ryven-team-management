# Real-Time Email Sync Setup

## Overview
Your email inbox system now supports **real-time email notifications** using Laravel Reverb (WebSocket). When new emails arrive, you'll get instant notifications without refreshing the page.

## Features Implemented

### 1. Real-Time Browser Notifications
- Desktop notifications when new emails arrive (requires permission)
- Shows sender name, subject, and preview text
- Click notification to open email

### 2. In-App Toast Notifications
- Beautiful slide-in notification cards in the top-right corner
- Shows sender, subject, and message preview
- Auto-dismisses after 8 seconds or click to close
- Smooth animations with dark mode support

### 3. Live Unread Badge in Navigation
- Navigation menu shows unread email count in a black badge
- Updates instantly when new emails arrive
- Pulses when new email notification received

### 4. Auto-Refresh Inbox
- Inbox page automatically reloads 2 seconds after new email arrives
- Only refreshes when viewing main inbox (not search/filtered views)

### 5. Background Auto-Fetch
- Scheduled job fetches emails every **5 minutes** automatically
- Runs for all active email accounts
- Broadcasts real-time events when new emails found

## How It Works

### Broadcasting Flow
1. **Email Fetch** → `EmailFetchService::fetchEmails()` retrieves new emails
2. **Event Broadcast** → `NewEmailReceived` event fires via Laravel Reverb
3. **Frontend Listens** → Laravel Echo picks up the event on user's private channel
4. **UI Updates** → Toast notification, browser notification, badge update, page reload

### WebSocket Channel
- Private channel: `user.{userId}`
- Event name: `.email.new`
- Authentication: Laravel's built-in broadcasting auth

## Setup Requirements

### 1. Start Laravel Reverb Server
```bash
php artisan reverb:start
```
**Important:** Keep this running in a separate terminal window!

### 2. Start Queue Worker
```bash
php artisan queue:work
```
Queue worker processes the background email fetching job.

### 3. Start Laravel Scheduler (Optional - for production)
```bash
php artisan schedule:work
```
Or add to cron (Linux/Mac):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 4. Development Mode (All Services)
Use the convenient composer command:
```bash
composer run dev
```
This runs: Laravel server + Queue worker + Vite dev server

Then manually start Reverb in another terminal:
```bash
php artisan reverb:start
```

## Testing Real-Time Sync

### Method 1: Manual Test (Recommended First)
1. Open your inbox: http://127.0.0.1:8000/email/inbox
2. Open browser console (F12) to see WebSocket connection logs
3. In another tab, go to Email Accounts and click **"Sync"** button
4. Watch for toast notifications and badge updates

### Method 2: Automatic Background Fetch
1. Ensure all services are running (Laravel, Reverb, Queue Worker, Scheduler)
2. Send an email to your configured email account from another device/service
3. Wait up to 5 minutes for automatic sync (or force by clicking "Sync")
4. Real-time notification will appear when email is detected

### Method 3: Test Connection First
Before testing sync, click the **"Test"** button on your email account to ensure:
- IMAP connection is working
- Credentials are correct
- No timeout issues

## Troubleshooting

### No Notifications Appearing
1. **Check Reverb is running:** 
   ```bash
   php artisan reverb:start
   ```
   Should show: `Reverb server started on ...`

2. **Check browser console for WebSocket errors:**
   - Press F12 → Console tab
   - Look for Echo connection messages
   - Should see: `Echo connected to channel: private-user.{id}`

3. **Verify .env settings:**
   ```env
   BROADCAST_CONNECTION=reverb
   REVERB_APP_ID=your-app-id
   REVERB_APP_KEY=your-key
   REVERB_APP_SECRET=your-secret
   REVERB_HOST=127.0.0.1
   REVERB_PORT=8080
   REVERB_SCHEME=http
   
   VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
   VITE_REVERB_HOST="${REVERB_HOST}"
   VITE_REVERB_PORT="${REVERB_PORT}"
   VITE_REVERB_SCHEME="${REVERB_SCHEME}"
   ```

4. **Rebuild assets after .env changes:**
   ```bash
   npm run dev
   ```

### Browser Notifications Not Working
- Click the bell icon or "Allow" when browser asks for permission
- Check browser settings → Notifications → Allow for your site
- Some browsers block notifications on localhost - try https://team.ryven.co

### Badge Not Showing Count
- Badge only shows when unread count > 0
- Refresh page to fetch initial count
- Check API endpoint: http://127.0.0.1:8000/email/inbox/unread-count

### Email Sync Timing Out
- Use the **"Test"** button first to validate connection
- Check IMAP server is reachable: `mail.ryven.co:993`
- Verify firewall isn't blocking port 993 (SSL) or 143 (non-SSL)
- Try different timeout values in `EmailFetchService.php`

## File Changes Summary

### New Files
- `app/Events/NewEmailReceived.php` - Broadcast event for new emails

### Modified Files
- `app/Services/EmailFetchService.php` - Added broadcasting after email creation
- `app/Http/Controllers/EmailInboxController.php` - Added unreadCount() method
- `resources/views/email/inbox/index.blade.php` - Added Echo listener and toast UI
- `resources/views/layouts/navigation.blade.php` - Added unread badge
- `resources/views/layouts/app.blade.php` - Added global Echo listener for badge
- `routes/web.php` - Added unread-count route

### Existing (Already Configured)
- `routes/console.php` - Email fetch job scheduled every 5 minutes
- `app/Jobs/FetchEmails.php` - Background job for fetching emails
- `resources/js/bootstrap.js` - Laravel Echo already configured
- `config/broadcasting.php` - Reverb connection configured

## Production Deployment

### Supervisor Configuration (Recommended)
Create `/etc/supervisor/conf.d/team-management.conf`:

```ini
[program:team-management-reverb]
process_name=%(program_name)s
command=php /path/to/project/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/reverb.log

[program:team-management-queue]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/queue.log
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start team-management-reverb:*
sudo supervisorctl start team-management-queue:*
```

### HTTPS Consideration
For production with SSL (https://team.ryven.co), update `.env`:
```env
REVERB_SCHEME=https
REVERB_PORT=443
```

And configure reverse proxy (nginx/apache) to forward WebSocket connections to Reverb.

## Customization

### Change Sync Frequency
Edit `routes/console.php`:
```php
// Every minute (faster, more server load)
Schedule::job(new \App\Jobs\FetchEmails())->everyMinute();

// Every 10 minutes (slower, less load)
Schedule::job(new \App\Jobs\FetchEmails())->everyTenMinutes();
```

### Disable Auto-Reload on New Email
Edit `resources/views/email/inbox/index.blade.php`, remove this section:
```javascript
// Reload page to show new email if on inbox page
@if(!request('search') && $folder === 'inbox')
    setTimeout(() => {
        window.location.reload();
    }, 2000);
@endif
```

### Change Toast Duration
Edit `resources/views/email/inbox/index.blade.php`:
```javascript
// Current: 8 seconds
setTimeout(() => { /* dismiss */ }, 8000);

// Change to 5 seconds
setTimeout(() => { /* dismiss */ }, 5000);
```

### Customize Notification Sound
Add to `resources/views/email/inbox/index.blade.php`:
```javascript
Echo.private('user.{{ auth()->id() }}')
    .listen('.email.new', (e) => {
        // Play sound
        const audio = new Audio('/notification.mp3');
        audio.play();
        
        // ... rest of code
    });
```

## Security Notes
- WebSocket connections are authenticated using Laravel's broadcasting authentication
- Private channels ensure users only receive their own email notifications
- Email content is never sent via WebSocket (only metadata like subject, sender)
- Full email body is loaded on demand via HTTPS

## Performance Tips
- Reverb handles ~10,000 concurrent connections efficiently
- Queue workers can be scaled horizontally (multiple processes)
- Email fetching is rate-limited by IMAP server (usually 1 connection at a time)
- Consider reducing sync frequency during off-hours to save resources

## Next Steps
1. ✅ Start Reverb: `php artisan reverb:start`
2. ✅ Test connection with **"Test"** button
3. ✅ Try manual sync with **"Sync"** button
4. ✅ Allow browser notifications when prompted
5. ✅ Send yourself a test email and watch for real-time notification!

---

**Need Help?**
- Check `storage/logs/laravel.log` for backend errors
- Check browser console (F12) for JavaScript/WebSocket errors
- Verify all services are running: `ps aux | grep -E '(reverb|queue|schedule)'`
