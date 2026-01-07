# Real-Time Notifications Setup Guide

## 🎉 What's Installed

Laravel Reverb has been installed for **real-time WebSocket notifications**. This means notifications will appear **instantly** (< 1 second) instead of with a 10-second delay.

## 📋 Setup Steps

### 1. Update Your `.env` File

Add these lines to your `.env` file:

```env
# Broadcasting Configuration
BROADCAST_CONNECTION=reverb

# Reverb Configuration  
REVERB_APP_ID=12345
REVERB_APP_KEY=your-unique-app-key
REVERB_APP_SECRET=your-secret-key
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

# Vite Configuration for Frontend
VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**Note:** You can use any values for `REVERB_APP_ID`, `REVERB_APP_KEY`, and `REVERB_APP_SECRET`. Example:
- `REVERB_APP_ID=12345`
- `REVERB_APP_KEY=abc123xyz`
- `REVERB_APP_SECRET=mysecret123`

### 2. Build Frontend Assets

Run this command to compile the JavaScript with Laravel Echo:

```bash
npm run build
# OR for development with hot reload:
npm run dev
```

### 3. Start the Reverb Server

Open a **new terminal window** and run:

```bash
php artisan reverb:start
```

Keep this terminal running! You should see:
```
Reverb server started on http://localhost:8080
```

### 4. Test It!

1. **Open your website** in a browser
2. **Trigger a GitHub webhook** (push code to a connected repository)
3. **Watch the notification appear instantly!** 🎉

## 🚀 How It Works

1. **GitHub webhook** triggers → Creates notification in database
2. **Laravel broadcasts** the event via Reverb WebSocket server
3. **Browser receives** the notification instantly via Laravel Echo
4. **UI updates** immediately with new notification badge
5. **Browser notification** pops up (if permitted)

## 🔧 Production Setup

For production, you'll want to:

### Option 1: Use Reverb on Your Server

```bash
# Install as a service (Linux)
php artisan reverb:install

# Or run with supervisor
```

### Option 2: Use a Process Manager

Add to your `supervisor.conf`:

```ini
[program:reverb]
command=php /path/to/your/app/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/path/to/your/app/storage/logs/reverb.log
```

### For Production HTTPS

Update your `.env`:

```env
REVERB_SCHEME=https
REVERB_PORT=443
REVERB_HOST=your-domain.com
```

## 🐛 Troubleshooting

### Notifications Not Appearing?

1. **Check Reverb is running:**
   ```bash
   php artisan reverb:start
   ```

2. **Check browser console** for errors (F12)

3. **Verify .env settings** are correct

4. **Rebuild frontend:**
   ```bash
   npm run build
   ```

5. **Clear cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   ```

### Port Already in Use?

Change the port in `.env`:
```env
REVERB_PORT=8081
```

Then restart Reverb.

## 📊 What Changed

### Backend Changes:
- ✅ Installed Laravel Reverb package
- ✅ Created `NewNotification` event with broadcasting
- ✅ Updated `GitHubWebhookController` to broadcast events
- ✅ Configured `routes/channels.php` for private channels

### Frontend Changes:
- ✅ Installed Laravel Echo + Pusher JS
- ✅ Configured `resources/js/bootstrap.js` with Echo
- ✅ Updated notification component to use WebSockets
- ✅ Removed polling (no more 10-second delays!)

## 🎯 Benefits

| Before (Polling) | After (WebSockets) |
|-----------------|-------------------|
| 10-second delay | Instant (< 1 sec) |
| Constant server requests | One connection |
| Higher server load | Minimal load |
| Battery drain | Battery friendly |

## 💡 Commands Reference

```bash
# Start Reverb server
php artisan reverb:start

# Start with custom options
php artisan reverb:start --host=0.0.0.0 --port=8080

# Build frontend
npm run build

# Watch for changes (development)
npm run dev

# Check Reverb is running
curl http://localhost:8080
```

## ✅ You're Done!

Real-time notifications are now set up! Just remember to:
1. Keep Reverb server running: `php artisan reverb:start`
2. Have frontend compiled: `npm run build` or `npm run dev`
3. Enjoy instant notifications! 🎉

---

**Need Help?** Check the Laravel Reverb documentation:
https://laravel.com/docs/11.x/reverb

