# GitHub Activity Tracking System - Setup Guide

## 🎯 Overview

This system allows you to track GitHub activities (pushes, pull requests, issues, etc.) for each employee in your system. When an employee performs an action on GitHub, it will be automatically logged and displayed on their profile page under the "GitHub Activity" tab.

## 📋 Features

- ✅ Track **Push events** with commit counts and messages
- ✅ Track **Pull Requests** (opened, closed, merged)
- ✅ Track **Pull Request Reviews** and comments
- ✅ Track **Issues** creation and comments
- ✅ Track **Branch/Tag** creation and deletion
- ✅ Beautiful UI with statistics and activity timeline
- ✅ Filter by repository and event type
- ✅ Real-time activity updates via webhooks

## 🚀 Setup Instructions

### Step 1: Configure GitHub Webhook

1. **Go to your GitHub repository** that you want to track
2. Navigate to **Settings** → **Webhooks** → **Add webhook**
3. Configure the webhook:
   - **Payload URL**: `https://your-domain.com/webhook/github`
     - For local testing: `https://your-ngrok-url.ngrok.io/webhook/github`
   - **Content type**: `application/json`
   - **Secret**: (Optional but recommended)
   - **SSL verification**: Enable if using HTTPS
   - **Which events would you like to trigger this webhook?**
     - Select "Let me select individual events"
     - Check these events:
       - ✅ Pushes
       - ✅ Pull requests
       - ✅ Pull request reviews
       - ✅ Pull request review comments
       - ✅ Issues
       - ✅ Issue comments
       - ✅ Branch or tag creation
       - ✅ Branch or tag deletion
4. Click **Add webhook**

### Step 2: Match Employees to GitHub Users

For the system to track activities, it needs to match GitHub users with your employees. The system currently matches by **email address**.

**Important**: Make sure the email address in your employee records matches the email address used in GitHub commits.

To check an employee's GitHub email:
- Look at their recent commits on GitHub
- The commit author email should match the employee's email in your system

### Step 3: Test the Webhook

1. Make a commit and push to the repository
2. Check the webhook deliveries in GitHub Settings → Webhooks → Recent Deliveries
3. Verify the webhook was delivered successfully (green checkmark)
4. Go to the employee's profile page and click on the **"GitHub Activity"** tab
5. You should see the recent push activity!

## 🔍 How It Works

### Webhook Flow

```
GitHub Event → Webhook Endpoint (/webhook/github) → GitHubWebhookController
                                                    ↓
                                        Identify Employee by Email
                                                    ↓
                                        Create GitHubLog Record
                                                    ↓
                                        Display on Employee Profile
```

### Event Matching Logic

The `GitHubWebhookController` receives webhook payloads and:

1. Extracts event information (type, author, repository, etc.)
2. Matches the GitHub user to an employee by email address
3. Creates a detailed log entry in the `github_logs` table
4. The activity is immediately available on the employee's profile

## 📊 Viewing GitHub Activity

### Access the GitHub Tab

1. Navigate to **Employees** in your system
2. Click on an employee's name to view their profile
3. Click on the **"GitHub Activity"** tab

### Activity Statistics

At the top of the GitHub tab, you'll see:
- **Total Activities**: Total number of GitHub events
- **Commits**: Total number of commits pushed
- **Pull Requests**: Number of PRs created
- **Repositories**: Number of unique repositories worked on

### Activity Timeline

Below the statistics, you'll see a detailed timeline of all activities:
- **Event Type** (Push, Pull Request, etc.)
- **Repository Name** with link to GitHub
- **Branch Name**
- **Commit Messages** (for pushes)
- **PR Title and Description** (for pull requests)
- **Event Timestamp** (relative and absolute)
- **Author Username**

## 🛠️ Advanced Configuration

### Adding GitHub Username Field (Optional Enhancement)

Currently, the system matches employees by email. To also support matching by GitHub username:

1. Add a migration to add `github_username` field to employees table:

```php
Schema::table('employees', function (Blueprint $table) {
    $table->string('github_username')->nullable()->after('email');
    $table->index('github_username');
});
```

2. Update the `findEmployeeByEmailOrUsername` method in `GitHubWebhookController`:

```php
protected function findEmployeeByEmailOrUsername(string $email, string $username): ?Employee
{
    if ($email) {
        $employee = Employee::where('email', $email)->first();
        if ($employee) return $employee;
    }

    if ($username) {
        $employee = Employee::where('github_username', $username)->first();
        if ($employee) return $employee;
    }

    return null;
}
```

3. Add GitHub username field to employee creation/edit forms

### Webhook Security (Recommended)

To secure your webhook endpoint:

1. Generate a secret token in GitHub webhook settings
2. Add verification in `GitHubWebhookController`:

```php
public function handle(Request $request)
{
    $signature = $request->header('X-Hub-Signature-256');
    $secret = config('services.github.webhook_secret');
    
    if ($secret && $signature) {
        $payload = $request->getContent();
        $hash = 'sha256=' . hash_hmac('sha256', $payload, $secret);
        
        if (!hash_equals($hash, $signature)) {
            return response()->json(['error' => 'Invalid signature'], 401);
        }
    }
    
    // ... rest of the code
}
```

3. Add the secret to your `.env` file:

```env
GITHUB_WEBHOOK_SECRET=your_secret_here
```

## 🧪 Testing Locally with ngrok

To test webhooks locally:

1. Install ngrok: `https://ngrok.com/download`
2. Start your Laravel app: `php artisan serve`
3. Start ngrok: `ngrok http 8000`
4. Copy the ngrok HTTPS URL (e.g., `https://abc123.ngrok.io`)
5. Use this URL for the webhook: `https://abc123.ngrok.io/webhook/github`
6. Make commits and test!

## 📱 API Endpoints

### Webhook Endpoint

- **URL**: `/webhook/github`
- **Method**: `POST`
- **Authentication**: None (public endpoint, secure with secret token)
- **Headers**: 
  - `X-GitHub-Event`: Event type (push, pull_request, etc.)
  - `X-Hub-Signature-256`: Webhook signature (if secret is configured)

### Employee Profile

- **URL**: `/employees/{id}?tab=github`
- **Method**: `GET`
- **Authentication**: Required (auth middleware)

## 🎨 UI Customization

The GitHub activity tab uses your brand colors (black primary color). The UI components include:

- Modern rounded cards with shadows
- Color-coded event types (blue for pushes, purple for PRs, etc.)
- Responsive design for mobile and desktop
- Dark mode support
- Smooth animations and transitions

## 🐛 Troubleshooting

### No Activities Showing Up

1. **Check webhook deliveries** in GitHub:
   - Go to Settings → Webhooks
   - Check "Recent Deliveries"
   - Look for failed deliveries or errors

2. **Verify employee email matches**:
   - Check the employee's email in your system
   - Compare with the commit author email in GitHub
   - They must match exactly

3. **Check Laravel logs**:
   ```bash
   tail -f storage/logs/laravel.log
   ```

4. **Test the webhook endpoint**:
   ```bash
   curl -X POST https://your-domain.com/webhook/github \
        -H "Content-Type: application/json" \
        -H "X-GitHub-Event: ping" \
        -d '{"zen":"Keep it simple.","hook_id":12345}'
   ```

### Webhook Returns 500 Error

1. Check storage/logs/laravel.log for errors
2. Verify database connection
3. Ensure all migrations are run
4. Check PHP error logs

### Activities Not Linked to Employee

- The employee's email must match their GitHub commit email
- Add a `github_username` field (see Advanced Configuration)
- Check logs to see if employee matching is failing

## 📝 Database Schema

The `github_logs` table stores all GitHub activities:

```sql
- id (primary key)
- employee_id (foreign key to employees)
- event_type (push, pull_request, etc.)
- action (opened, closed, merged, etc.)
- repository_name
- repository_url
- branch
- commit_message
- commit_sha
- commit_url
- commits_count
- pr_number
- pr_title
- pr_description
- pr_url
- pr_state
- pr_merged
- author_username
- author_avatar_url
- payload (full JSON payload)
- event_at (timestamp of GitHub event)
- created_at
- updated_at
```

## 🔒 Security Considerations

1. **Always use HTTPS** for webhook URLs in production
2. **Implement webhook signature verification** (see Advanced Configuration)
3. **Rate limit the webhook endpoint** if needed
4. **Monitor webhook logs** for suspicious activity
5. **Keep Laravel and dependencies updated**

## 📚 Additional Resources

- [GitHub Webhooks Documentation](https://docs.github.com/en/developers/webhooks-and-events/webhooks)
- [Laravel Documentation](https://laravel.com/docs)
- [ngrok Documentation](https://ngrok.com/docs)

## 🎉 Success!

Once configured, your GitHub activity tracking system is ready! Every push, pull request, and issue will be automatically tracked and displayed beautifully on employee profiles.

Enjoy tracking your team's GitHub contributions! 🚀

