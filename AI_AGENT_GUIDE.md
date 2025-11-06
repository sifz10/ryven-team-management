# 🤖 AI Agent System - Complete Guide

## Overview

The AI Agent is an intelligent assistant integrated into your team management system. It uses OpenAI's GPT-4o-mini model to understand natural language commands and perform various tasks such as managing employees, checking GitHub activity, and generating reports.

## ✨ Key Features

### 🗣️ Voice Command Support
- **Speech Recognition**: Use the microphone button to speak your commands
- **Hands-Free Operation**: Perfect for when you're on the go
- **Automatic Transcription**: Your voice is converted to text automatically
- **Browser Support**: Works in Chrome, Edge, and Safari (requires HTTPS in production)

### 🎯 Natural Language Understanding
The AI Agent understands conversational commands like:
- "Who didn't push code today?"
- "Add a new employee named John Doe with email john@example.com"
- "Show me GitHub activity for the last 7 days"
- "Get me details about employee with ID 5"
- "Search for employees with the name Sarah"

### 🛠️ Available Functions

#### 1. **Employee Management**
- **Add Employee**: Create new employee records
  ```
  "Add an employee named Sarah Johnson, email sarah@company.com, position Senior Developer"
  ```

- **List Employees**: View all active employees
  ```
  "Show me all employees"
  "List all team members including discontinued ones"
  ```

- **Search Employees**: Find employees by name, email, or GitHub username
  ```
  "Find employees named John"
  "Search for developer@company.com"
  ```

- **Get Employee Details**: View detailed information about a specific employee
  ```
  "Show details for employee 5"
  "Tell me about employee ID 12"
  ```

#### 2. **GitHub Activity Tracking**

- **Check Today's Activity**: See all GitHub events today
  ```
  "What happened on GitHub today?"
  "Show me today's GitHub activity"
  ```

- **Find Inactive Developers**: Identify who hasn't pushed code today
  ```
  "Who didn't push code today?"
  "Which developers are inactive today?"
  "Show me who hasn't committed anything today"
  ```

- **Get GitHub Activity**: View activity over a custom period
  ```
  "Show GitHub activity for the last 7 days"
  "What's the GitHub activity for employee 3 in the last 30 days?"
  "Show me only pull requests from the last week"
  ```

- **GitHub Statistics**: Get detailed statistics for an employee
  ```
  "Show GitHub stats for employee 5"
  "Get me GitHub statistics for employee 2 over the last 30 days"
  ```

## 🚀 Getting Started

### Prerequisites

1. **OpenAI API Key**: Required for AI processing
   ```bash
   OPENAI_API_KEY=sk-your-api-key-here
   ```
   Add this to your `.env` file.

2. **HTTPS (Production)**: Voice input requires HTTPS in production
   - Works on localhost without HTTPS during development

### Installation

The AI Agent is already integrated! Just make sure:

1. OpenAI API key is configured in `.env`
2. Run migrations (if not already run):
   ```bash
   php artisan migrate
   ```

3. Clear config cache:
   ```bash
   php artisan config:clear
   ```

### Accessing the AI Agent

1. Navigate to **AI Assistant** in the sidebar (🔔 with gradient icon)
2. Or visit: `https://team.ryven.co/ai-agent`

## 💬 Usage Examples

### Example 1: Check Who's Inactive Today
**You say/type**: "Who didn't push code today?"

**AI Response**: 
```
Based on today's GitHub activity, the following employees with GitHub accounts 
have not pushed any code today:

1. John Smith (john.smith@company.com) - GitHub: @johnsmith
   Position: Senior Developer, Department: Engineering

2. Sarah Johnson (sarah.j@company.com) - GitHub: @sarahj
   Position: Backend Developer, Department: Engineering

Total employees with GitHub: 10
Active today: 8
Inactive today: 2
```

### Example 2: Add New Employee
**You say/type**: "Add a new employee named Alex Turner, email alex@company.com, position Frontend Developer, salary 80000 USD"

**AI Response**:
```
✅ Successfully added new employee!

Name: Alex Turner
Email: alex@company.com
Position: Frontend Developer
Employee ID: 15

The employee has been added to the system and is now active.
```

### Example 3: Get GitHub Stats
**You say/type**: "Show me GitHub statistics for employee 5 over the last 30 days"

**AI Response**:
```
📊 GitHub Statistics for John Smith (@johnsmith)
Period: Last 30 days

Statistics:
- Total Pushes: 45
- Total Commits: 127
- Pull Requests Created: 12
- Pull Request Reviews: 18
- Total Activities: 75
- Average Commits per Push: 2.82

Repositories Worked On:
- team-management-app
- api-gateway
- mobile-app
- documentation
```

### Example 4: Search Employees
**You say/type**: "Search for developers"

**AI Response**:
```
Found 5 employees matching "developers":

1. John Smith - john@company.com - @johnsmith
   Position: Senior Developer, Department: Engineering

2. Sarah Johnson - sarah@company.com - @sarahj
   Position: Backend Developer, Department: Engineering

3. Mike Chen - mike@company.com - @mikechen
   Position: Frontend Developer, Department: Engineering

4. Lisa Park - lisa@company.com - @lisapark
   Position: Full Stack Developer, Department: Engineering

5. Alex Turner - alex@company.com
   Position: Frontend Developer, Department: Engineering
```

## 🎤 Using Voice Commands

1. Click the **microphone icon** in the input area
2. The icon will turn **red** and **pulse** when listening
3. Speak your command clearly
4. The system will automatically transcribe your speech
5. Review the text and press **Send** or press **Enter**

### Voice Input Tips:
- Speak clearly and at a normal pace
- Use complete sentences for best results
- If recognition fails, try again or type your command
- Works best in quiet environments

## 🔧 Technical Details

### Architecture

```
User Input (Text/Voice)
    ↓
AIAgentController
    ↓
AIAgentService
    ↓
OpenAI API (GPT-4o-mini)
    ↓
Function Calling
    ↓
Execute Tool (Database Queries)
    ↓
Format Response
    ↓
Display to User
```

### OpenAI Function Calling

The AI Agent uses OpenAI's function calling feature to:
1. Understand user intent
2. Determine which function to call
3. Extract parameters from natural language
4. Execute the appropriate database queries
5. Format results in natural language

### Available Tools (Functions)

| Function Name | Description | Parameters |
|--------------|-------------|------------|
| `add_employee` | Add new employee | first_name, last_name, email, github_username, position, department, salary, currency |
| `list_employees` | List all employees | include_discontinued (optional) |
| `search_employees` | Search employees | query |
| `get_employee_details` | Get employee details | employee_id |
| `check_github_activity_today` | Check today's GitHub activity | None |
| `find_inactive_developers_today` | Find inactive developers | None |
| `get_github_activity` | Get GitHub activity | employee_id (optional), days, event_type (optional) |
| `get_employee_github_stats` | Get employee GitHub stats | employee_id, days |

## 📝 Best Practices

### 1. Be Specific
✅ Good: "Show me GitHub activity for employee 5 over the last 7 days"
❌ Vague: "Show me some activity"

### 2. Use Natural Language
✅ Good: "Who didn't push code today?"
✅ Good: "Which developers are inactive?"
(Both work equally well!)

### 3. Follow Up Questions
The AI maintains context within a session, so you can ask follow-up questions:
```
You: "Search for John"
AI: [Shows Johns]
You: "Get details for the first one"
AI: [Shows details]
```

### 4. Clear Chat When Switching Topics
Use the "Clear Chat" button when starting a new topic to avoid confusion.

## 🔒 Security & Privacy

- **Authentication Required**: Only logged-in users can access the AI Agent
- **User Context**: Each user has their own conversation session
- **No Sensitive Data**: The AI doesn't store or log sensitive information
- **API Security**: OpenAI API calls are made server-side with your API key

## 🐛 Troubleshooting

### Voice Input Not Working

**Problem**: Microphone button doesn't work or no red indicator

**Solutions**:
1. **Check Browser Permissions**: Allow microphone access
2. **Use Supported Browser**: Chrome, Edge, or Safari
3. **HTTPS Required**: In production, voice input requires HTTPS
4. **Microphone Hardware**: Ensure your microphone is connected and working

### AI Not Understanding Commands

**Problem**: AI gives incorrect or irrelevant responses

**Solutions**:
1. **Be More Specific**: Add more context to your command
2. **Use Examples**: Refer to the examples in this guide
3. **Clear Conversation**: Start fresh with "Clear Chat"
4. **Check Spelling**: For typed commands, ensure correct spelling

### Slow Response Times

**Problem**: AI takes long to respond

**Solutions**:
1. **OpenAI API Latency**: Normal response time is 2-5 seconds
2. **Complex Queries**: Queries involving large datasets take longer
3. **Network Issues**: Check your internet connection

### API Errors

**Problem**: "Sorry, I encountered an error" message

**Solutions**:
1. **Check API Key**: Ensure `OPENAI_API_KEY` is set in `.env`
2. **API Limits**: Check if you've hit OpenAI rate limits
3. **Clear Config**: Run `php artisan config:clear`
4. **Check Logs**: View Laravel logs at `storage/logs/laravel.log`

## 💡 Advanced Tips

### 1. Combine Multiple Queries
"Show me employees in the Engineering department who didn't push code today"

### 2. Use Relative Time
- "Last week"
- "Past 30 days"
- "This month"

### 3. Filter Results
"Show me only pull request activity from the last 7 days"

### 4. Get Statistics
"Compare GitHub activity between employees 5 and 8"

## 🚧 Future Enhancements

Planned features:
- [ ] Conversation history persistence
- [ ] Export reports to PDF
- [ ] Scheduled reports via email
- [ ] Multi-language support
- [ ] Advanced analytics and visualizations
- [ ] Integration with Slack/Teams for notifications
- [ ] Custom commands and workflows
- [ ] Team performance insights
- [ ] Predictive analytics

## 📊 Cost Considerations

### OpenAI API Pricing (GPT-4o-mini)
- **Input**: ~$0.15 per 1M tokens
- **Output**: ~$0.60 per 1M tokens
- **Average Query**: ~$0.001 - $0.005 per request

### Typical Usage
- Simple queries: ~500 tokens (~$0.0003)
- Complex queries with data: ~2000 tokens (~$0.001)
- 1000 queries/month: ~$1-5/month

## 🤝 Support

If you encounter issues:
1. Check this documentation
2. Review Laravel logs: `storage/logs/laravel.log`
3. Check browser console for JavaScript errors
4. Verify OpenAI API key is valid and has credits

## 📚 Related Documentation

- [OpenAI Function Calling](https://platform.openai.com/docs/guides/function-calling)
- [Web Speech API](https://developer.mozilla.org/en-US/docs/Web/API/Web_Speech_API)
- [Laravel Service Container](https://laravel.com/docs/11.x/container)

---

**Built with ❤️ using Laravel 12 & OpenAI GPT-4o-mini**
