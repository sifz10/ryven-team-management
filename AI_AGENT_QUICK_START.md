# 🤖 AI AGENT - QUICK REFERENCE

## 🚀 **Access**: `/performance/ai-agent`

## ✅ Installation Complete!

All files have been created and integrated. Your AI Agent is ready to use!

## 📦 What You Got

### Core Features:
- ✅ Natural language command processing
- ✅ Voice input with microphone button  
- ✅ 8 powerful functions for team management
- ✅ Beautiful chat interface
- ✅ Dark mode support
- ✅ Real-time responses

### Main Use Cases:
1. **Find inactive developers**: "Who didn't push code today?"
2. **Add employees**: "Add John Doe as a developer"
3. **Check GitHub activity**: "Show me GitHub activity for last week"
4. **Get employee info**: "Show details for employee 5"
5. **Search team**: "Find all developers"

## 🎯 Your Primary Request - Checking Inactive Developers

### Just Ask:
```
"Who didn't push code today?"
```

### What It Does:
1. Checks all GitHub push events from today
2. Lists all active employees with GitHub accounts
3. Compares them and finds who didn't push
4. Returns their names, positions, and contact info

### Example Response:
```
Based on today's GitHub activity, 3 employees didn't push code:

1. John Smith (john@company.com) - @johnsmith
   Position: Senior Developer
   
2. Sarah Chen (sarah@company.com) - @sarahchen  
   Position: Frontend Developer
   
3. Mike Wilson (mike@company.com) - @mikew
   Position: Backend Developer

Total employees with GitHub: 15
Active today: 12
Inactive today: 3
```

## 🎤 Voice Commands

1. Click the **🎤 microphone icon**
2. Allow microphone access
3. Speak your command clearly
4. Text appears automatically
5. Click Send or press Enter

Works best in Chrome/Edge on desktop.

## 📚 Documentation Files

- **AI_AGENT_SETUP.md** - Quick 3-minute setup guide
- **AI_AGENT_GUIDE.md** - Complete manual with all examples  
- **IMPLEMENTATION_SUMMARY.md** - Technical overview

## ⚙️ Required Setup

**IMPORTANT**: Make sure this is in your `.env`:
```env
OPENAI_API_KEY=sk-your-actual-key-here
```

Then run:
```bash
php artisan config:clear
```

## 🎨 UI Location

Look for **"AI Assistant"** in the sidebar with a gradient icon (blue → purple).

## 💡 Pro Tips

1. **Be specific**: "Show GitHub stats for employee 5 over last 30 days"
2. **Use natural language**: Ask like you're talking to a person
3. **Follow up**: The AI remembers context within the session
4. **Clear chat**: Use the "Clear Chat" button when switching topics

## 🐛 Quick Troubleshooting

### Error: "OpenAI API error"
```bash
# Check API key is set
grep OPENAI_API_KEY .env

# Clear cache
php artisan config:clear
```

### Voice input not working?
- Use Chrome or Edge browser
- Allow microphone permission
- Works on HTTPS or localhost only

### Page not loading?
```bash
php artisan route:clear
php artisan view:clear
```

## 💬 More Example Commands

```
"Add a new employee named Lisa Park"
"Search for frontend developers"  
"Show GitHub activity from last 7 days"
"Get details about employee 12"
"List all employees"
"Show me pull request activity from this week"
"What repos did employee 5 work on?"
```

## 📊 8 Available Functions

1. **add_employee** - Create new employee records
2. **list_employees** - List all team members
3. **search_employees** - Find by name/email/GitHub
4. **get_employee_details** - Get full employee info
5. **check_github_activity_today** - Today's GitHub events
6. **find_inactive_developers_today** - Who didn't push today ⭐
7. **get_github_activity** - Historical GitHub data
8. **get_employee_github_stats** - Per-employee statistics

## 🎉 You're Ready!

Visit: **`/performance/ai-agent`**

Start with: **"Who didn't push code today?"**

---

**Built with Laravel 12 + OpenAI GPT-4o-mini**
