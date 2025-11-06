# 🚀 AI Agent Quick Setup

## Quick Start (3 minutes)

### Step 1: Ensure OpenAI API Key is Set

Open your `.env` file and make sure this line exists:
```env
OPENAI_API_KEY=sk-your-actual-api-key-here
```

If you don't have an OpenAI API key:
1. Go to [OpenAI Platform](https://platform.openai.com/api-keys)
2. Create a new API key
3. Copy it to your `.env` file

### Step 2: Clear Configuration Cache

Run this command in your terminal:
```bash
php artisan config:clear
```

### Step 3: Access the AI Agent

1. Start your development server (if not already running):
   ```bash
   composer run dev
   ```

2. Open your browser and navigate to the **AI Assistant** in the sidebar
   - Or go directly to: `http://localhost:8000/ai-agent`

3. You should see the AI Agent interface with:
   - ✅ Welcome message
   - ✅ Input box at the bottom
   - ✅ Microphone button for voice input
   - ✅ Send button

### Step 4: Test with Sample Commands

Try these commands to verify everything works:

#### Test 1: List Employees
Type: `Show me all employees`

Expected: List of all active employees in your system

#### Test 2: Check GitHub Activity
Type: `Who didn't push code today?`

Expected: List of developers who haven't pushed code today

#### Test 3: Search
Type: `Search for developers`

Expected: List of employees matching "developers"

## ✅ Verification Checklist

- [ ] OpenAI API key is set in `.env`
- [ ] Config cache is cleared
- [ ] AI Agent page loads without errors
- [ ] Can type and send messages
- [ ] AI responds to commands
- [ ] Voice input button is visible (may not work on HTTP localhost)

## 🎯 What's Included

### Files Created:
1. **`app/Services/AIAgentService.php`** - Core AI logic
2. **`app/Http/Controllers/AIAgentController.php`** - HTTP controller
3. **`resources/views/ai-agent/index.blade.php`** - Beautiful UI
4. **`routes/web.php`** - Routes added
5. **`resources/views/layouts/sidebar.blade.php`** - Navigation link added

### Features Ready to Use:
- ✅ Natural language command processing
- ✅ Voice input (works on HTTPS or localhost with permissions)
- ✅ Add/search/list employees
- ✅ Check GitHub activity
- ✅ Find inactive developers
- ✅ Get GitHub statistics
- ✅ Beautiful chat interface
- ✅ Real-time message streaming
- ✅ Dark mode support

## 🎤 Voice Input Setup

Voice input works differently based on your environment:

### Development (HTTP localhost)
- Chrome/Edge: ✅ Works with permission prompt
- Firefox: ❌ Not supported (HTTPS only)
- Safari: ⚠️ May work with flags enabled

### Production (HTTPS)
- All modern browsers: ✅ Works with permission prompt

To test voice input:
1. Click the microphone icon
2. Allow microphone access when prompted
3. Speak your command
4. The text will appear in the input box
5. Click Send or press Enter

## 🐛 Common Issues & Fixes

### Issue: "OpenAI API error"
**Fix**: 
```bash
# Check if API key is set
grep OPENAI_API_KEY .env

# If not set or wrong, update it and clear cache
php artisan config:clear
```

### Issue: "404 Not Found" on /ai-agent
**Fix**:
```bash
# Clear route cache
php artisan route:clear
php artisan route:cache
```

### Issue: Voice input not working
**Causes**:
- Browser doesn't support Web Speech API (use Chrome/Edge)
- Microphone permission denied (check browser settings)
- Using HTTP on non-localhost domain (requires HTTPS)

### Issue: Page doesn't load properly
**Fix**:
```bash
# Rebuild assets
npm run build

# Clear view cache
php artisan view:clear
```

## 📖 Next Steps

1. **Read Full Documentation**: Check `AI_AGENT_GUIDE.md` for detailed examples
2. **Try Advanced Commands**: Ask complex questions like "Show GitHub stats for employee 5"
3. **Customize**: Edit `app/Services/AIAgentService.php` to add more functions
4. **Integrate**: Use the AI Agent in your daily workflow

## 💰 Cost Monitoring

To monitor OpenAI API usage:
1. Go to [OpenAI Usage Dashboard](https://platform.openai.com/usage)
2. Set up billing limits if needed
3. Track cost per request (typically $0.001-0.005)

Estimated costs:
- Light usage (100 queries/day): ~$3-10/month
- Moderate usage (500 queries/day): ~$15-50/month
- Heavy usage (1000+ queries/day): ~$30-150/month

## 🎉 You're Ready!

The AI Agent is now fully operational. Start by asking:
- "Who didn't push code today?"
- "Show me all employees"
- "Add a new employee named John Doe"
- "Get GitHub statistics for employee 5"

Enjoy your new AI assistant! 🤖✨
