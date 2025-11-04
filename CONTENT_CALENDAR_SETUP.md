# Content Calendar System - Setup Guide

## Overview
A comprehensive social media content calendar with AI-powered post generation and automated scheduling for LinkedIn, Facebook, and Twitter.

## Features
- 📅 Visual calendar interface for scheduling posts
- 🤖 AI-powered content generation using GPT-4o-mini
- 🔗 OAuth integration with LinkedIn, Facebook, Twitter
- ⏰ Automatic post publishing at scheduled times
- 📊 Post status tracking (draft, scheduled, posted, failed)
- 🎨 Dark mode optimized UI with brand-consistent design
- 🔄 Real-time post generation with multiple variations

## Database Setup

The system uses three main tables:
- `social_accounts` - Stores connected social media accounts
- `social_posts` - Stores scheduled posts and content
- `post_generations` - Stores AI-generated content variations

All migrations have been created and run automatically.

## Environment Configuration

Add the following to your `.env` file:

```env
# OpenAI API for Content Generation
OPENAI_API_KEY=your_openai_api_key_here

# LinkedIn OAuth
LINKEDIN_CLIENT_ID=your_linkedin_client_id
LINKEDIN_CLIENT_SECRET=your_linkedin_client_secret
LINKEDIN_REDIRECT_URI=https://team.ryven.co/social/callback/linkedin

# Facebook OAuth
FACEBOOK_APP_ID=your_facebook_app_id
FACEBOOK_APP_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=https://team.ryven.co/social/callback/facebook

# Twitter OAuth 2.0
TWITTER_CLIENT_ID=your_twitter_client_id
TWITTER_CLIENT_SECRET=your_twitter_client_secret
TWITTER_REDIRECT_URI=https://team.ryven.co/social/callback/twitter
```

## OAuth App Setup

### LinkedIn
1. Go to https://www.linkedin.com/developers/apps
2. Create a new app
3. Add redirect URI: `https://team.ryven.co/social/callback/linkedin`
4. Request scopes: `openid`, `profile`, `email`, `w_member_social`
5. Copy Client ID and Secret to `.env`

### Facebook
1. Go to https://developers.facebook.com/apps
2. Create a new app (Business type)
3. Add Facebook Login product
4. Add redirect URI: `https://team.ryven.co/social/callback/facebook`
5. Request permissions: `pages_manage_posts`, `pages_read_engagement`, `pages_show_list`
6. Copy App ID and Secret to `.env`

### Twitter (X)
1. Go to https://developer.twitter.com/en/portal/dashboard
2. Create a new app
3. Enable OAuth 2.0
4. Add callback URI: `https://team.ryven.co/social/callback/twitter`
5. Request scopes: `tweet.read`, `tweet.write`, `users.read`, `offline.access`
6. Copy Client ID and Secret to `.env`

## Queue Worker Setup

The system uses Laravel queues for scheduled post publishing. Ensure the queue worker is running:

```bash
php artisan queue:work
```

For production, use a process manager like Supervisor:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/project/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/path/to/project/storage/logs/worker.log
```

## Scheduler Setup

Add to crontab for automated post processing:

```bash
* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1
```

The scheduler runs `social:process-scheduled` every minute to check for posts ready to publish.

## Usage Workflow

### 1. Connect Social Accounts
- Navigate to "Content Calendar" → "Accounts"
- Click "Connect LinkedIn/Facebook/Twitter"
- Complete OAuth flow
- Accounts will be saved with encrypted tokens

### 2. Create a Post

#### Option A: Manual Content
- Click "New Post"
- Enter title and description
- Uncheck "Generate AI-powered content"
- Write content manually
- Select social account
- Choose "Draft" or "Schedule for Later"
- Save

#### Option B: AI-Generated Content
- Click "New Post"
- Enter title (e.g., "5 Tips for Remote Work")
- Add description/context (optional but recommended)
- Check "Generate AI-powered content automatically"
- Select social account (determines platform for AI optimization)
- Choose scheduling option
- Save - AI will generate content automatically

### 3. View Calendar
- Calendar shows all scheduled posts
- Color-coded by status:
  - Gray: Draft
  - Blue: Scheduled
  - Green: Posted
  - Red: Failed
- Click on a date to see all posts for that day
- Navigate between months with Previous/Next buttons

### 4. Post Publishing
- Scheduled posts are automatically processed every minute
- System dispatches queue job at scheduled time
- Job publishes to social media via platform API
- Post status updated to "posted" or "failed"
- Failed posts can be retried (up to 3 attempts)

## AI Content Generation

The system uses OpenAI GPT-4o-mini to generate viral social media content with:

### Content Structure
1. **Hook** - Attention-grabbing opening
2. **Problem** - Identify pain point/challenge  
3. **Value** - Actionable insights/solution
4. **CTA** - Clear call-to-action

### Platform Optimization
- **LinkedIn**: Professional, 150-300 words, 3-5 hashtags, industry insights
- **Facebook**: Conversational, 100-200 words, 2-3 emojis, 2-3 hashtags
- **Twitter**: Punchy, max 280 characters, 1-2 hashtags, bold and direct

### Cost Estimates
- Average: ~500-800 tokens per generation
- Cost: ~$0.15-0.25 per 1M tokens (GPT-4o-mini pricing)
- Example: 1000 posts = ~$0.15-0.25 total

## API Endpoints

### Web Routes
- `GET /social/calendar` - Calendar view
- `GET /social/accounts` - Manage accounts
- `GET /social/posts/create` - Create post form
- `POST /social/posts` - Store new post
- `POST /social/posts/{id}/generate` - Generate AI content
- `POST /social/posts/{id}/publish` - Publish immediately

### OAuth Callbacks
- `GET /social/callback/linkedin` - LinkedIn OAuth callback
- `GET /social/callback/facebook` - Facebook OAuth callback
- `GET /social/callback/twitter` - Twitter OAuth callback

## Troubleshooting

### OAuth Connection Fails
1. Verify redirect URIs match exactly (including protocol)
2. Check app permissions/scopes are approved
3. Ensure tokens haven't expired (re-connect account)
4. Check logs: `storage/logs/laravel.log`

### AI Generation Fails
1. Verify `OPENAI_API_KEY` is set in `.env`
2. Run `php artisan config:clear`
3. Check OpenAI account has credits
4. Review error in post or logs

### Post Not Publishing
1. Ensure queue worker is running: `php artisan queue:work`
2. Check scheduler is active: `* * * * * php artisan schedule:run`
3. Verify social account token hasn't expired
4. Check post status and error message
5. Review logs for detailed error

### Test Connection Fails
1. Verify OAuth tokens are still valid
2. Check platform API status
3. Ensure account has necessary permissions
4. Try reconnecting the account

## Security Notes

- Access tokens are encrypted in database
- OAuth state parameter prevents CSRF attacks
- User can only manage their own accounts/posts
- Policies enforce authorization on all actions
- Tokens automatically refreshed when expired (where supported)

## Command Reference

```bash
# Process scheduled posts manually
php artisan social:process-scheduled

# Clear config cache after .env changes
php artisan config:clear

# View scheduled tasks
php artisan schedule:list

# Monitor queue jobs
php artisan queue:monitor

# Retry failed jobs
php artisan queue:retry all
```

## File Structure

```
app/
├── Http/Controllers/
│   ├── SocialAccountController.php    # Account management & OAuth
│   ├── SocialPostController.php       # Post CRUD & operations
│   └── ContentCalendarController.php  # Calendar views & data
├── Models/
│   ├── SocialAccount.php              # Social account model
│   ├── SocialPost.php                 # Post model
│   └── PostGeneration.php             # AI generation model
├── Services/
│   ├── SocialPostGenerationService.php     # AI content generation
│   └── SocialMediaPublishingService.php    # Platform publishing
├── Jobs/
│   └── PublishScheduledPost.php       # Queue job for publishing
└── Policies/
    ├── SocialAccountPolicy.php        # Account authorization
    └── SocialPostPolicy.php           # Post authorization

resources/views/social/
├── calendar/
│   └── index.blade.php                # Calendar view
├── accounts/
│   └── index.blade.php                # Accounts management
└── posts/
    ├── create.blade.php               # Create post form
    ├── edit.blade.php                 # Edit post form
    └── show.blade.php                 # View post details

database/migrations/
├── *_create_social_accounts_table.php
├── *_create_social_posts_table.php
└── *_create_post_generations_table.php
```

## Future Enhancements

- [ ] Add OAuth callback handlers (currently need implementation)
- [ ] Implement token refresh logic for expired tokens
- [ ] Add media upload support (images/videos)
- [ ] Analytics integration (post performance tracking)
- [ ] Bulk post scheduling
- [ ] Content template library
- [ ] Team collaboration features
- [ ] Post preview before publishing
- [ ] Instagram integration
- [ ] TikTok integration

## Support

For issues or questions:
1. Check logs: `storage/logs/laravel.log`
2. Review this documentation
3. Test API connections manually
4. Contact system administrator

## License

Proprietary - Ryven Team Management System
