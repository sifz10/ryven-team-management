# AI-Powered Ticket Support System

## Overview
Intelligent AI assistant that automatically responds to new ticket comments, gathers information from clients, and escalates to human support when needed. The AI acts as a first-line support agent, having natural conversations with clients to understand their issues before involving the team.

## Features

### 🤖 AI Assistant Capabilities
- **Natural Conversations**: Writes like a real human, not a robot - empathetic and conversational
- **Information Gathering**: Asks clarifying questions to fully understand the problem
- **Smart Troubleshooting**: Suggests simple solutions when appropriate
- **Intelligent Escalation**: Knows when to hand off to human support
- **Priority Assessment**: Adjusts ticket priority based on client urgency
- **Status Management**: Updates ticket status automatically (in-progress, resolved)

### 🎯 When AI Responds
✅ **AI Will Respond When:**
- New comment added to unassigned ticket
- Ticket status is open or in-progress
- Ticket not yet escalated to human team
- Client is asking questions or providing updates

❌ **AI Will NOT Respond When:**
- Ticket is assigned to a human team member
- Ticket status is resolved or closed
- AI has already escalated the ticket
- Comment is from AI itself

### 🔄 AI Actions
The AI can perform these actions automatically:

**Priority Changes:**
- `[PRIORITY:critical]` - System down, multiple users affected, data loss
- `[PRIORITY:high]` - Significant impact, blocking work
- `[PRIORITY:medium]` - Important but not blocking
- `[PRIORITY:low]` - Minor issues, feature requests

**Status Updates:**
- `[STATUS:in-progress]` - AI is actively gathering information
- `[STATUS:resolved]` - Client confirms fix worked

**Escalation:**
- `[ESCALATE]` - Hand off to human support team with all gathered context

## How It Works

### 1. Client Creates Ticket
```
Client: "The login page isn't working. I can't access my account."
```

### 2. AI Responds Immediately
```
AI Assistant: "Oh no, I'm sorry to hear the login isn't working! That must be 
really frustrating. Can you tell me what happens when you try to log in? Do you 
see an error message, or does it just not respond when you click the login button?"
```

### 3. AI Gathers Information
```
Client: "I get an error saying 'Invalid credentials' but I'm sure my password is correct."

AI Assistant: "Thanks for that detail! A few quick questions:
- When did this start happening?
- Have you tried resetting your password?
- Does this happen on all browsers or just one specific browser?"
```

### 4. AI Escalates When Ready
```
Client: "Started yesterday. Yes, I tried resetting but the reset email never arrives."

AI Assistant: "Got it - thanks for all that information! Since the password reset 
emails aren't being delivered, this looks like it needs our technical team to 
investigate the email system. I'm escalating this to a senior team member who 
will reach out to you within 2 hours. [ESCALATE] [PRIORITY:high]"
```

**Result:** Ticket priority upgraded to HIGH, team notified, all context preserved.

## Architecture

### Core Components

**TicketAIService** (`app/Services/TicketAIService.php`)
- Central AI logic and OpenAI integration
- Conversation context building
- Action parsing and execution
- Escalation decision making

**TicketController** (`app/Http/Controllers/TicketController.php`)
- Detects when comments are posted
- Triggers AI response generation
- Applies AI-suggested actions (priority, status)
- Returns AI responses to frontend

**TicketComment Model** (`app/Models/TicketComment.php`)
- Supports null `user_id` for AI comments
- Helper methods: `isAI()`, `author_name`, `author_initials`
- Distinguishes AI vs human comments

### AI Conversation Flow

```
┌─────────────────────┐
│ Client Posts Comment│
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ shouldAIRespond()?  │◄─── Check ticket status, assignment
└──────────┬──────────┘
           │ Yes
           ▼
┌─────────────────────┐
│ Build Context       │◄─── Ticket info + comment history
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Call OpenAI API     │◄─── GPT-4o-mini with system prompt
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Parse AI Response   │◄─── Extract [ESCALATE], [PRIORITY:x], [STATUS:x]
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Apply Actions       │◄─── Update ticket, notify team if escalated
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Save AI Comment     │◄─── Store with user_id=null
└──────────┬──────────┘
           │
           ▼
┌─────────────────────┐
│ Return to Client    │◄─── Display AI response in chat
└─────────────────────┘
```

## Database Schema

### ticket_comments Table
```sql
CREATE TABLE ticket_comments (
    id BIGINT PRIMARY KEY,
    ticket_id BIGINT NOT NULL,
    user_id BIGINT NULL,  -- NULL for AI comments
    comment TEXT NOT NULL,
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    
    FOREIGN KEY (ticket_id) REFERENCES project_tickets(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES employees(id) ON DELETE CASCADE
);
```

**Key Change:** `user_id` is now nullable to support AI-generated comments.

## AI System Prompt

The AI is instructed to:

### Communication Style
- Write like a real human with natural, conversational language
- Use contractions and empathy ("I understand how frustrating this must be")
- Keep responses concise (2-4 sentences typically)
- Ask ONE specific question at a time
- Avoid technical jargon unless client uses it first
- **Never mention being an AI or provide code snippets**

### Information to Gather
1. What exactly happened? (step-by-step if possible)
2. When did this start occurring?
3. Is this affecting multiple users or just them?
4. Have they tried any solutions already?
5. What's the impact on their work/business?
6. Can they provide screenshots or error messages?

### Escalation Criteria
- Complex technical issues requiring code changes
- Security or data concerns
- Issues affecting multiple users/critical systems
- Client explicitly requests human support
- Asked 4-5 questions and still need more info
- Issue requires access to systems AI can't see

## Configuration

### 1. OpenAI API Key
Add to `.env`:
```env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxx
```

**Cost:** ~$0.15-0.60 per 1M tokens (GPT-4o-mini)
- Average conversation: 2-5 cents total
- Extremely cost-effective for support automation

### 2. Model Settings
In `TicketAIService.php`:
```php
private string $model = 'gpt-4o-mini';  // Fast & affordable
private int $maxTokens = 800;           // ~2-3 paragraphs response
```

**Alternative Models:**
- `gpt-4o` - More intelligent but 20x more expensive
- `gpt-3.5-turbo` - Older, less capable, slightly cheaper

## Frontend Integration

### Visual Design
- **AI Comments**: Blue gradient background with 🤖 badge
- **Human Comments**: Standard gray background
- **AI Badge**: "AI Assistant" label on AI responses
- **Typing Indicator**: 1.5s delay before showing AI response (simulates typing)

### JavaScript Handling
```javascript
// When comment is posted
if (data.ai_response) {
    setTimeout(() => {
        // Add AI comment to UI
        this.comments.push(data.ai_response);
        
        // Show notification if ticket updated
        if (data.ticket_updated) {
            this.showNotification('AI Assistant updated the ticket priority/status');
        }
    }, 1500); // Typing delay
}
```

## Usage Examples

### Example 1: Simple Troubleshooting
```
Client: "I can't upload files to the project."

AI: "I'm sorry you're having trouble with uploads! Let me help figure this out. 
What happens when you try to upload? Do you get an error message, or does it just 
not upload?"

Client: "It says 'File too large' but the file is only 2MB."

AI: "Ah, that's helpful! The system should allow files up to 50MB. Can you tell me:
1. What type of file is it? (PDF, image, video, etc.)
2. Have you successfully uploaded files before, or is this your first time?"

Client: "It's a PDF. I've uploaded before with no issues."

AI: "Thanks! This sounds like it might be a temporary server issue. I'm escalating 
this to our technical team to investigate the upload limits. They'll reach out within 
an hour. [ESCALATE] [PRIORITY:medium]"
```

### Example 2: Urgent Production Issue
```
Client: "URGENT: The entire website is down! All customers are affected!"

AI: "I understand this is critical and I'm treating this with high urgency. 
Can you tell me:
1. When did you first notice it was down?
2. What error do customers see?
This is going immediately to our emergency response team. [ESCALATE] [PRIORITY:critical]"
```

### Example 3: Quick Resolution
```
Client: "How do I reset my password?"

AI: "Happy to help! You can reset your password by:
1. Go to the login page
2. Click 'Forgot Password' below the login button
3. Enter your email
4. Check your inbox for the reset link (arrives within 2 minutes)

Does this help, or are you having trouble with any of these steps?"

Client: "That worked, thanks!"

AI: "Wonderful! Glad I could help. I'm marking this as resolved. If you need 
anything else, just let me know! [STATUS:resolved]"
```

## Testing the System

### Test 1: Basic AI Response
1. Create a new ticket (ensure `assigned_to` is NULL)
2. Add a comment: "The dashboard won't load"
3. Wait 2 seconds
4. Verify AI responds with a clarifying question
5. Check ticket status remains "open"

### Test 2: Priority Escalation
1. Create ticket with priority=low
2. Comment: "This is blocking all our users from working!"
3. Verify AI responds and suggests priority:high
4. Check database: `project_tickets.priority` updated to "high"

### Test 3: Escalation Flow
1. Create ticket
2. Have conversation with AI (4-5 back-and-forth messages)
3. Provide complex technical details
4. Verify AI escalates with [ESCALATE]
5. Check notifications sent to admin team

### Test 4: AI Stops When Assigned
1. Create ticket, get AI response
2. Assign ticket to a human team member
3. Add another comment
4. Verify AI does NOT respond (human is handling it)

## Monitoring & Analytics

### Log Files
Check `storage/logs/laravel.log` for:
```
[INFO] AI response generated for ticket #123
[ERROR] OpenAI API error: Rate limit exceeded
[WARNING] OpenAI API key not configured
```

### Database Queries
```sql
-- Count AI comments
SELECT COUNT(*) FROM ticket_comments WHERE user_id IS NULL;

-- Find escalated tickets
SELECT * FROM ticket_comments 
WHERE comment LIKE '%[ESCALATE]%' 
ORDER BY created_at DESC;

-- AI response rate
SELECT 
    COUNT(CASE WHEN user_id IS NULL THEN 1 END) as ai_comments,
    COUNT(*) as total_comments,
    (COUNT(CASE WHEN user_id IS NULL THEN 1 END) * 100.0 / COUNT(*)) as ai_percentage
FROM ticket_comments;
```

## Best Practices

### For Support Teams
1. **Let AI Handle Initial Contact**: Don't jump in immediately, give AI 2-3 exchanges
2. **Review AI Conversations**: Check escalated tickets to see what info AI gathered
3. **Assign Tickets to Take Over**: Once assigned, AI stops responding
4. **Update AI Prompt**: Adjust system prompt based on common issues

### For Clients
- AI works best with detailed descriptions
- Answer AI's questions directly
- Request human support anytime ("I'd like to speak with a person")

### Cost Management
- Monitor API usage at platform.openai.com
- Set monthly spending limits in OpenAI dashboard
- Average cost: $5-20/month for moderate ticket volume
- GPT-4o-mini is 20x cheaper than GPT-4

## Troubleshooting

### AI Not Responding
**Check:**
1. `.env` has valid `OPENAI_API_KEY`
2. Ticket `assigned_to` is NULL
3. Ticket status is not "closed" or "resolved"
4. Run `php artisan config:clear` after adding API key

**Test API Key:**
```bash
php artisan tinker
>>> config('services.openai.api_key')
```

### AI Responses Are Poor Quality
**Solutions:**
1. Use GPT-4o instead of GPT-4o-mini (edit `TicketAIService.php`)
2. Increase `maxTokens` to 1000+ for longer responses
3. Refine system prompt for your use case
4. Add more context about your product in system prompt

### Database Error: user_id Cannot Be Null
**Fix:**
Run migration to allow null:
```bash
php artisan migrate:refresh --path=database/migrations/2025_11_09_113508_create_ticket_comments_table.php
```

### OpenAI API Errors
**Common Issues:**
- `401 Unauthorized` - Invalid API key
- `429 Rate Limit` - Too many requests, upgrade plan
- `500 Server Error` - OpenAI service down, retry later

## Security Considerations

### API Key Protection
- Never commit `.env` file to git
- Rotate API keys periodically
- Use separate keys for dev/production
- Set spending limits in OpenAI dashboard

### Content Filtering
OpenAI has built-in content moderation:
- Blocks harmful/inappropriate responses
- Filters personal information leaks
- Prevents code injection in responses

### Data Privacy
- Conversations sent to OpenAI for processing
- OpenAI doesn't train models on API data (as of 2024)
- Consider GDPR compliance for EU users
- Review OpenAI's privacy policy for your region

## Advanced Customization

### Custom System Prompts
Edit `getSystemPrompt()` in `TicketAIService.php`:
```php
return <<<PROMPT
You are a support agent for {$projectName}.

Your company specializes in: [YOUR PRODUCT DESCRIPTION]

Common issues to look for:
- Login problems → Check browser/password reset
- Upload errors → Check file size/format
- Performance issues → Ask about browser/device

[REST OF PROMPT...]
PROMPT;
```

### Custom Actions
Add new action commands:
```php
// In parseAIActions()
if (preg_match('/\[ASSIGN_TO:(\d+)\]/i', $message, $matches)) {
    $actions['assign_to'] = $matches[1];
}

// In storeComment()
if ($aiResult['actions']['assign_to'] ?? false) {
    $ticket->assigned_to = $aiResult['actions']['assign_to'];
    $ticket->save();
}
```

### Multi-Language Support
Adjust system prompt:
```php
$userLanguage = $ticket->reportedBy->preferred_language ?? 'en';

$prompts = [
    'en' => 'You are a helpful support assistant...',
    'es' => 'Eres un asistente de soporte útil...',
    'fr' => 'Vous êtes un assistant de support utile...',
];

return $prompts[$userLanguage];
```

## Future Enhancements

### Potential Features
- [ ] AI sentiment analysis (detect angry/frustrated clients)
- [ ] Auto-generate knowledge base articles from resolved tickets
- [ ] Multi-language support (detect language, respond accordingly)
- [ ] Voice input/output for phone support
- [ ] Integration with external knowledge bases (search docs)
- [ ] AI-suggested solutions based on similar past tickets
- [ ] Scheduled follow-ups ("Any update on this issue?")
- [ ] Customer satisfaction ratings after AI interactions

### Integration Ideas
- **Slack**: Notify team in Slack when AI escalates
- **Jira**: Auto-create Jira issues for escalated tickets
- **Analytics**: Track AI resolution rate, escalation rate, response time
- **A/B Testing**: Compare AI vs human response times and satisfaction

## Metrics & KPIs

Track these metrics to measure AI effectiveness:

### Response Metrics
- **First Response Time**: Should be <5 seconds (AI immediate response)
- **Resolution Rate**: % of tickets resolved by AI without human intervention
- **Escalation Rate**: % of tickets AI hands off to humans (target: 20-40%)
- **Average Conversation Length**: Number of back-and-forth messages before resolution/escalation

### Quality Metrics
- **Client Satisfaction**: Post-resolution survey scores
- **Re-open Rate**: % of AI-resolved tickets that get re-opened
- **Information Quality**: % of escalated tickets with complete context
- **False Escalations**: % of escalations that didn't actually need human help

### Cost Metrics
- **Cost per Ticket**: API costs / number of tickets handled
- **Cost Savings**: Human hours saved * hourly rate - API costs
- **ROI**: (Cost savings - API costs) / API costs

## Success Criteria

✅ **System is Working Well If:**
- AI handles 60-80% of initial inquiries without human intervention
- Average first response time <10 seconds
- Escalated tickets have complete context (no "what's the issue?" from humans)
- Client satisfaction scores ≥4.0/5.0 for AI interactions
- API costs <$50/month for moderate volume

❌ **System Needs Improvement If:**
- AI escalates >50% of tickets (too conservative)
- AI escalates <10% of tickets (too aggressive)
- Clients frequently ask "Can I speak to a human?"
- AI provides incorrect information or solutions
- High re-open rate on AI-resolved tickets

---

## Quick Start Guide

### 1. Setup (5 minutes)
```bash
# Add API key to .env
OPENAI_API_KEY=sk-proj-xxxxxxxxxxxxx

# Clear config cache
php artisan optimize:clear

# Run migration
php artisan migrate:refresh --path=database/migrations/2025_11_09_113508_create_ticket_comments_table.php
```

### 2. Test (2 minutes)
1. Create ticket without assignment
2. Add comment: "The app crashed"
3. Wait 2 seconds
4. See AI response!

### 3. Monitor (ongoing)
- Check `storage/logs/laravel.log` for AI activity
- Review escalated tickets for quality
- Track OpenAI usage at platform.openai.com

---

**Status**: ✅ FULLY FUNCTIONAL
**Last Updated**: 2025-11-09
**AI Model**: GPT-4o-mini
**Average Response Time**: 2-4 seconds
**Maintained By**: Development Team
