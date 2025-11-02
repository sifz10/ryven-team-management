# AI Code Review Setup Guide

## Overview
The AI Code Review feature uses OpenAI's GPT-4o-mini model to analyze pull requests and provide intelligent feedback on:
- Code quality and best practices
- Potential bugs or issues
- Performance considerations
- Security concerns
- Readability and maintainability
- Suggestions for improvement

## Setup Instructions

### 1. Get an OpenAI API Key

1. Visit [OpenAI Platform](https://platform.openai.com/)
2. Sign up or log in to your account
3. Navigate to **API Keys** section
4. Click **"Create new secret key"**
5. Copy the API key (you won't be able to see it again!)

### 2. Add API Key to Your Environment

Open your `.env` file and add:

```env
OPENAI_API_KEY=sk-your-api-key-here
```

### 3. Clear Configuration Cache

Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
```

## Usage

### Generate AI Review

1. Go to any Pull Request details page
2. Click on the **"Comments"** tab
3. Click the **"Generate AI Review"** button
4. Wait for the AI to analyze the code (usually 10-30 seconds)
5. Review the AI-generated feedback

### Actions Available

- **Post to GitHub**: Publish the review as a comment on the PR
- **Copy**: Copy the review text to your clipboard
- **Discard**: Remove the generated review

## Features

### What the AI Analyzes

- **Pull Request Metadata**: Title, description, branch names
- **Code Changes**: Up to 10 files with their diffs
- **File Statistics**: Additions, deletions, modifications
- **Code Patterns**: Detects anti-patterns and suggests improvements

### Review Format

The AI provides structured feedback including:
- **Executive Summary**: Quick overview of the PR
- **Code Quality**: Assessment of coding standards
- **Potential Issues**: Bugs, security concerns, or problems
- **Performance Notes**: Optimization suggestions
- **Best Practices**: Recommendations for improvement
- **Specific File Feedback**: Line-by-line suggestions (when applicable)

## Configuration

### Model Settings

Located in: `app/Http/Controllers/GitHubPullRequestController.php`

```php
'model' => 'gpt-4o-mini',  // You can change to 'gpt-4' for better quality
'temperature' => 0.7,       // Controls creativity (0.0-1.0)
'max_tokens' => 2000,       // Maximum response length
```

### File Limit

By default, only the first **10 files** are analyzed to stay within token limits. You can adjust this in the `prepareAIContext()` method:

```php
if ($index >= 10) break; // Change this number
```

## Cost Considerations

### Pricing (as of 2024)
- **GPT-4o-mini**: ~$0.15 per 1M input tokens, ~$0.60 per 1M output tokens
- **GPT-4**: ~$30 per 1M input tokens, ~$60 per 1M output tokens

### Estimated Costs
- Small PR (1-3 files): ~$0.001 - $0.005 per review
- Medium PR (4-10 files): ~$0.005 - $0.02 per review
- Large PR (10+ files): ~$0.02 - $0.05 per review

## Troubleshooting

### "OpenAI API key not configured"
- Make sure `OPENAI_API_KEY` is set in your `.env` file
- Run `php artisan config:clear`
- Verify the key starts with `sk-`

### "Failed to generate AI review"
- Check your OpenAI account has credits
- Verify your API key is valid
- Check the Laravel logs: `storage/logs/laravel.log`

### SSL Certificate Error (Local Development)
The code automatically disables SSL verification in local environment. If you still face issues:
- Make sure `APP_ENV=local` in your `.env`
- Or remove the SSL bypass in production

### Timeout Errors
If reviews take too long:
- Reduce the number of files analyzed (change the `10` limit)
- Increase timeout in controller: `Http::timeout(120)` (default is 60 seconds)

## Tips for Best Results

1. **Write good PR descriptions**: AI uses this context
2. **Keep PRs focused**: Smaller PRs get better reviews
3. **Review the AI feedback**: It's a tool, not a replacement for human review
4. **Customize the prompt**: Edit the system message to focus on your team's priorities

## Security Notes

- API keys are stored in `.env` (never commit this file!)
- All API calls go directly to OpenAI's servers
- Code is sent to OpenAI for analysis
- Don't use for sensitive/proprietary code without checking OpenAI's terms

## Advanced Configuration

### Custom System Prompt

Edit the system message in `callOpenAI()` method to customize the AI's focus:

```php
'content' => 'You are an expert [language] developer specializing in [domain]...'
```

### Different Models

You can switch between models:
- `gpt-4o-mini`: Fast, cheap, good quality
- `gpt-4o`: Better quality, more expensive
- `gpt-4`: Highest quality, most expensive

## Support

For issues or questions:
1. Check Laravel logs: `storage/logs/laravel.log`
2. Enable debug mode: `APP_DEBUG=true` in `.env`
3. Check OpenAI status: https://status.openai.com/

---

**Note**: This feature requires an active OpenAI account with available credits.

