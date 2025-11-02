# GitHub Pull Request Review System - Setup Guide

## 🎉 What's New

You can now view PR details, code changes, and post reviews/comments directly from your platform to GitHub!

## ✅ Features

### 1. **Pull Request Modal**
- View full PR description and details
- See all code changes with diffs
- View existing comments
- Post new comments
- Submit reviews (Approve, Request Changes, or Comment)

### 2. **Available On**
- **Employee Profile** → GitHub Activity tab → "View Details & Comment" button on any PR
- **GitHub Logs Page** (`/github-logs`) → "Review PR" button on any PR

### 3. **Realtime Updates**
- GitHub Logs page auto-refreshes every 30 seconds
- Shows a "Live" indicator with animation
- Auto-refresh pauses when you apply filters (to not interrupt your work)
- Pauses when tab is hidden (saves resources)

## 🔧 Required Setup

### Step 1: Create GitHub Personal Access Token

1. Go to GitHub → Settings → Developer settings → [Personal access tokens](https://github.com/settings/tokens) → Tokens (classic)
2. Click **"Generate new token (classic)"**
3. Give it a name: `Employee Management System`
4. Set expiration: Choose your preference (recommend "No expiration" for production)
5. Select the following scopes:
   - ✅ **`repo`** - Full control of private repositories
     - This includes: repo:status, repo_deployment, public_repo, repo:invite, security_events
   - ✅ **`read:org`** - Read org and team membership (optional, if you use org repos)

6. Click **"Generate token"**
7. **IMPORTANT**: Copy the token immediately (you won't be able to see it again!)

### Step 2: Add Token to Your .env File

Open your `.env` file and add:

```env
GITHUB_API_TOKEN=ghp_your_actual_token_here
```

Replace `ghp_your_actual_token_here` with the token you copied from GitHub.

### Step 3: Restart Your Server (if running)

If your Laravel server is running, restart it:

```bash
php artisan serve
```

## 🎨 How to Use

### From Employee Profile:
1. Go to an employee's profile
2. Click on the "GitHub Activity" tab
3. Find a Pull Request in the timeline
4. Click **"View Details & Comment"** button
5. Modal opens with 3 tabs:
   - **Description**: PR info, stats (additions, deletions, files changed)
   - **Files Changed**: View all code diffs
   - **Comments**: See existing comments and add new ones

### From GitHub Logs Page:
1. Go to `/github-logs` in your browser
2. Find a Pull Request in the table
3. Click **"Review PR"** button
4. Same modal as above opens

### Posting Comments:
1. In the modal, go to the **Comments** tab
2. Type your comment in the text area
3. Choose an action:
   - **Comment**: Just leave a comment
   - **✅ Approve**: Approve the PR with your comment
   - **❌ Request Changes**: Request changes with your feedback
4. Your comment/review is posted directly to GitHub!

## 🔒 Security Notes

- Keep your GitHub token secure
- Never commit the `.env` file to version control
- The token has full access to your repositories
- You can revoke the token anytime from GitHub settings

## 🐛 Troubleshooting

### "Failed to load PR details"
- Check that `GITHUB_API_TOKEN` is set in `.env`
- Verify the token has correct permissions
- Restart your Laravel server

### "Failed to post comment"
- Verify your token hasn't expired
- Check that you have write access to the repository
- Ensure the PR is still open on GitHub

### Button doesn't work
- Clear browser cache
- Check browser console for JavaScript errors
- Make sure Alpine.js is loaded (check other interactive features)

## 📝 Notes

- All actions are performed under the GitHub account that owns the token
- Comments and reviews appear on GitHub immediately
- The system uses GitHub's official API
- Code diffs are displayed exactly as they appear on GitHub

## 🎯 Next Steps

1. Set up your GitHub token (steps above)
2. Test on a real Pull Request
3. Enjoy reviewing code without leaving your platform!

---

**Need help?** Check the console (F12) for detailed error messages.

