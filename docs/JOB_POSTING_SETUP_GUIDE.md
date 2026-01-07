# Job Posting System - Quick Setup Guide

## 🚀 Quick Start

### 1. Run Migrations
```bash
php artisan migrate
```

This creates 6 new tables:
- `job_posts`
- `job_questions`
- `job_applications`
- `application_answers`
- `application_tests`
- `talent_pool`

### 2. Configure OpenAI API (for AI Screening)

Add to `.env`:
```env
OPENAI_API_KEY=sk-your-openai-api-key-here
```

Get your API key from: https://platform.openai.com/api-keys

### 3. Create Storage Link (if not already done)
```bash
php artisan storage:link
```

### 4. Configure Mail (for notifications)

Add to `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=jobs@yourcompany.com
MAIL_FROM_NAME="${APP_NAME}"
```

For testing, use Mailtrap: https://mailtrap.io

### 5. Set Upload Limits

Check `php.ini`:
```ini
upload_max_filesize = 100M
post_max_size = 100M
max_execution_time = 300
```

### 6. Clear Config Cache
```bash
php artisan config:clear
php artisan cache:clear
```

---

## 📋 Next Steps (Views to Create)

The backend is **100% complete**. You now need to create the views (UI):

### Admin Views Needed

#### 1. Job Posts Management
- `resources/views/admin/jobs/index.blade.php` - List all jobs
- `resources/views/admin/jobs/create.blade.php` - Create new job
- `resources/views/admin/jobs/edit.blade.php` - Edit job
- `resources/views/admin/jobs/show.blade.php` - View job details + stats

#### 2. Applications Management
- `resources/views/admin/applications/index.blade.php` - List applications with filters
- `resources/views/admin/applications/show.blade.php` - View application details + AI analysis

#### 3. Talent Pool
- `resources/views/admin/talent-pool/index.blade.php` - List saved candidates
- `resources/views/admin/talent-pool/show.blade.php` - View talent profile

### Public Views Needed

#### 4. Public Job Board
- `resources/views/jobs/index.blade.php` - Browse jobs (no login)
- `resources/views/jobs/show.blade.php` - Job details + application form
- `resources/views/jobs/closed.blade.php` - Job no longer accepting applications
- `resources/views/jobs/success.blade.php` - Application submitted confirmation

#### 5. Test Submission
- `resources/views/jobs/test.blade.php` - View and submit test
- `resources/views/jobs/test-submitted.blade.php` - Test submitted confirmation

---

## 🎨 View Components to Reuse

You can use existing components from your project:
- `<x-app-layout>` - For admin pages
- `<x-black-button>` - Primary action buttons
- `<x-icon-button>` - Icon buttons
- Tailwind CSS classes (already configured)
- Alpine.js for interactivity

---

## 🧪 Testing the System

### Test Job Creation
1. Go to `/admin/jobs/create` (after creating the view)
2. Fill in job details
3. Add screening questions
4. Publish job
5. Copy public URL: `/jobs/{slug}`

### Test Application Flow
1. Visit public job URL (no login)
2. Fill in application form
3. Upload resume
4. Answer screening questions
5. Submit application
6. AI automatically screens resume (if enabled)

### Test Admin Review
1. Go to `/admin/applications`
2. View application details
3. See AI analysis (score, strengths, concerns)
4. Send interview invitation
5. Send test assignment
6. Add to talent pool

---

## 🔗 Available Routes

### Public (No Auth)
```
GET  /jobs                              - Browse jobs
GET  /jobs/{slug}                       - View job + apply
POST /jobs/{slug}/apply                 - Submit application
GET  /jobs/application/{id}/success     - Success page
GET  /jobs/test/{testId}?email={email}  - View test
POST /jobs/test/{testId}/submit         - Submit test
```

### Admin (Employee Auth)
```
# Job Posts
GET    /admin/jobs
GET    /admin/jobs/create
POST   /admin/jobs
GET    /admin/jobs/{job}
GET    /admin/jobs/{job}/edit
PUT    /admin/jobs/{job}
DELETE /admin/jobs/{job}
POST   /admin/jobs/{job}/duplicate

# Applications
GET    /admin/applications
GET    /admin/applications/{application}
PUT    /admin/applications/{application}/status
POST   /admin/applications/{application}/ai-screening
POST   /admin/applications/batch-ai-screening
POST   /admin/applications/{application}/talent-pool
POST   /admin/applications/{application}/interview
POST   /admin/applications/{application}/test
GET    /admin/applications/{application}/resume
DELETE /admin/applications/{application}

# Talent Pool
GET    /admin/talent-pool
GET    /admin/talent-pool/{talentPool}
PUT    /admin/talent-pool/{talentPool}
DELETE /admin/talent-pool/{talentPool}
```

---

## 📊 Example Job Post Creation

```php
$job = JobPost::create([
    'title' => 'Senior Laravel Developer',
    'slug' => 'senior-laravel-developer-abc123', // Auto-generated
    'description' => 'We are looking for...',
    'location' => 'Remote',
    'job_type' => 'full-time',
    'experience_level' => 'senior',
    'salary_min' => 80000,
    'salary_max' => 120000,
    'salary_currency' => 'USD',
    'requirements' => '5+ years Laravel experience...',
    'responsibilities' => 'Lead development team...',
    'benefits' => 'Health insurance, remote work...',
    'status' => 'published',
    'application_deadline' => '2025-12-31',
    'positions_available' => 2,
    'ai_screening_enabled' => true,
    'created_by' => auth()->user()->employee->id,
]);

// Add screening questions
JobQuestion::create([
    'job_post_id' => $job->id,
    'question' => 'Tell us about your Laravel experience',
    'type' => 'textarea',
    'is_required' => true,
    'order' => 0,
]);

JobQuestion::create([
    'job_post_id' => $job->id,
    'question' => 'Record a 2-minute video introducing yourself',
    'type' => 'video',
    'is_required' => true,
    'order' => 1,
]);
```

Public URL: `https://yoursite.com/jobs/senior-laravel-developer-abc123`

---

## 🤖 AI Screening Example

When a candidate applies, the AI analyzes:

**Input to AI:**
- Job requirements
- Job responsibilities
- Candidate resume (extracted text)
- Cover letter
- Screening question answers

**AI Output:**
```json
{
  "score": 87,
  "status": "good_to_go",
  "strengths": [
    "8+ years of Laravel experience",
    "Strong portfolio of enterprise applications",
    "Experience with team leadership",
    "Excellent problem-solving skills"
  ],
  "concerns": [
    "Limited Vue.js experience mentioned",
    "No recent DevOps/CI/CD experience"
  ],
  "next_steps": "Proceed to technical interview to assess Vue.js skills",
  "summary": "Strong Laravel developer with extensive experience. Good cultural fit based on cover letter. Minor skill gaps in frontend that can be addressed."
}
```

---

## 📧 Email Templates to Create

### 1. Interview Invitation
- Subject: "Interview Invitation - [Job Title]"
- Greeting with candidate name
- Interview date, time, location
- Custom message from hiring manager
- Company contact info

### 2. Test Assignment
- Subject: "Technical Assessment - [Job Title]"
- Test instructions
- Deadline
- Unique test link
- Support contact

### 3. New Application Notification (to Admin)
- Subject: "New Application: [Candidate Name] for [Job Title]"
- Candidate summary
- AI score (if available)
- Link to review application

---

## 🛠️ Optional Enhancements

### Use Queue for AI Screening (Recommended)
```php
// In JobPostController@apply, replace:
$this->aiScreeningService->screenApplication($application);

// With:
dispatch(new ScreenApplicationJob($application->id));
```

Run queue worker:
```bash
php artisan queue:work
```

### Add Caching for Job Listing
```php
// In PublicJobController@index
$jobs = Cache::remember('published_jobs', 3600, function () {
    return JobPost::published()->latest()->get();
});
```

### Add Rate Limiting
In `RouteServiceProvider`:
```php
RateLimiter::for('jobs', function (Request $request) {
    return Limit::perMinute(10)->by($request->ip());
});
```

Apply to routes:
```php
Route::post('/{slug}/apply', [...])->middleware('throttle:jobs');
```

---

## ✅ System Status

**✅ Completed:**
- Database schema (6 tables)
- Models with relationships (6 models)
- AI Screening Service (OpenAI GPT-4o-mini)
- Admin controllers (Job Posts, Applications, Talent Pool)
- Public controller (Job listing, Applications, Tests)
- Routes (Admin + Public)
- PDF text extraction (smalot/pdfparser)
- Configuration

**⏳ Remaining:**
- Admin views (job management, application review)
- Public views (job board, application form)
- Email templates (interview, test, notifications)
- Navigation menu updates
- Permission/role setup

**Estimated time to complete views: 4-6 hours**

---

## 🆘 Support

For questions or issues:
1. Check `storage/logs/laravel.log`
2. Review this documentation
3. Test with Tinker: `php artisan tinker`
4. Enable debug mode: `APP_DEBUG=true` in `.env`

---

## 📚 References

- [Full Documentation](./JOB_POSTING_ATS_SYSTEM.md)
- [Laravel Docs](https://laravel.com/docs/11.x)
- [OpenAI API](https://platform.openai.com/docs)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/)

---

**🎉 The backend is production-ready! Just add views and you're good to go!**
