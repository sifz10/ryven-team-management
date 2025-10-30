# 🚀 GitHub Activity Tracking - Setup Checklist

## ✅ What's Already Done

- [x] Database migration created and run
- [x] GitHubLog model created
- [x] Webhook controller implemented
- [x] GitHub tab added to employee profile
- [x] Beautiful UI with your brand colors
- [x] All routes configured
- [x] Employee model updated with relationship

## 📋 What You Need to Do

### Step 1: Configure GitHub Webhook (5 minutes)

1. **Open your GitHub repository**
   - Example: `https://github.com/your-org/your-repo`

2. **Navigate to Settings → Webhooks**
   - Click "Settings" in the top menu
   - Click "Webhooks" in the left sidebar
   - Click "Add webhook" button

3. **Configure webhook settings:**
   ```
   Payload URL:    https://team.ryven.co/webhook/github
   Content type:   application/json
   Secret:         (leave empty for now, add later for security)
   SSL:            Enable SSL verification
   ```

4. **Select which events to send:**
   - Choose "Let me select individual events"
   - Check these boxes:
     ✅ Pushes
     ✅ Pull requests
     ✅ Pull request reviews
     ✅ Pull request review comments
     ✅ Issues
     ✅ Issue comments
     ✅ Branch or tag creation
     ✅ Branch or tag deletion
   - Uncheck "Pushes" default if it was pre-selected
   - Then recheck all the ones listed above

5. **Save webhook**
   - Check "Active" checkbox
   - Click "Add webhook" button
   - You should see a green checkmark after GitHub sends a test ping

### Step 2: Verify Employee Emails (2 minutes)

The system matches GitHub activities to employees by email address.

**Check this:**
- Employee email in your system: `john@example.com`
- Same email used in Git commits: `john@example.com`
- They must match exactly!

**To verify:**
```bash
# Check employee emails in your database
php artisan tinker
>>> App\Models\Employee::all(['id', 'first_name', 'last_name', 'email']);
```

**To check GitHub commit email:**
- Go to any commit on GitHub
- Look at the commit author email
- It should match the employee's email in your system

### Step 3: Test It! (1 minute)

1. **Make a test commit:**
   ```bash
   git commit -m "Test GitHub tracking" --allow-empty
   git push
   ```

2. **Check webhook delivery:**
   - Go to GitHub → Settings → Webhooks
   - Click on your webhook
   - Check "Recent Deliveries"
   - Should see a delivery with green checkmark (200 response)

3. **View in your system:**
   - Go to `https://team.ryven.co/employees/3`
   - Click "GitHub Activity" tab
   - Should see your test commit! 🎉

## 🎯 Example URLs

- **Your webhook endpoint:**
  ```
  https://team.ryven.co/webhook/github
  ```

- **Employee profile with GitHub tab:**
  ```
  https://team.ryven.co/employees/3?tab=github
  ```

- **GitHub webhook settings:**
  ```
  https://github.com/YOUR_ORG/YOUR_REPO/settings/hooks
  ```

## 🐛 Quick Troubleshooting

### Problem: No activities showing up

**Solution 1: Check webhook deliveries**
- GitHub → Settings → Webhooks → Recent Deliveries
- Look for red X (failed) or green checkmark (success)
- Click on delivery to see response details

**Solution 2: Check employee email match**
- Employee email in database must match GitHub commit email
- Check `git log --format='%ae'` to see commit emails

**Solution 3: Check logs**
```bash
tail -f storage/logs/laravel.log
```

### Problem: Webhook returns 404

**Solution:**
- Make sure URL is exactly: `https://team.ryven.co/webhook/github`
- No trailing slash
- HTTPS enabled

### Problem: Activities tracked but wrong employee

**Solution:**
- Email addresses don't match
- Update employee email or commit email
- Or add `github_username` field (see advanced setup)

## 🎨 What It Looks Like

When you open an employee profile and click "GitHub Activity" tab, you'll see:

**Top Section - Statistics:**
```
┌─────────────┬─────────────┬─────────────┬─────────────┐
│   Total     │  Commits    │   Pull      │ Repos       │
│ Activities  │             │  Requests   │             │
│     24      │     156     │     8       │     3       │
└─────────────┴─────────────┴─────────────┴─────────────┘
```

**Activity Timeline:**
```
📤 Push · main                    team.ryven.co/repo
   Pushed 3 commits
   [Fixed login bug and updated tests]
   abc1234 → View on GitHub
   @johndoe · 2 hours ago

🔀 Pull Request · Opened           team.ryven.co/repo  
   #42: Add new feature
   [This PR adds the new dashboard feature...]
   🟢 Open
   @johndoe · 5 hours ago

👀 Pull Request Review · Approved  team.ryven.co/repo
   #41: Fix authentication
   [Looks good to me! ✓]
   @johndoe · Yesterday
```

## 📱 Mobile Support

The GitHub activity tab is fully responsive and looks great on:
- 📱 Mobile phones
- 📱 Tablets
- 💻 Desktop
- 🌙 Dark mode

## 🔐 Optional: Add Security (Recommended)

After basic setup works, add webhook security:

1. **Generate a secret in GitHub webhook settings**
2. **Add to your `.env`:**
   ```env
   GITHUB_WEBHOOK_SECRET=your_random_secret_here
   ```
3. See `GITHUB_WEBHOOK_SETUP.md` for implementation details

## ✨ Advanced Features

Once basic tracking works, you can add:

- [ ] GitHub username field for better matching
- [ ] Filter activities by repository
- [ ] Export activity reports
- [ ] Email notifications for specific events
- [ ] Activity statistics dashboard

See `GITHUB_WEBHOOK_SETUP.md` for details!

## 📞 Need Help?

Check these files:
- `GITHUB_WEBHOOK_SETUP.md` - Detailed setup guide
- `GITHUB_TRACKING_SUMMARY.md` - System overview
- `storage/logs/laravel.log` - Application logs

Or review the GitHub webhook delivery details for error messages.

## 🎉 Success Checklist

You're all set when you can:
- [x] See green checkmark in GitHub webhook deliveries
- [x] Open employee profile → GitHub Activity tab
- [x] See commit activities listed with details
- [x] Click links that go to GitHub repository
- [x] See accurate statistics at the top

---

**That's it! Your GitHub activity tracking system is ready! 🚀**

Make a commit, push it, and watch it appear in your employee's profile! 

