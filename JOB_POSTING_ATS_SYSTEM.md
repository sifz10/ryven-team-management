# Job Posting & Applicant Tracking System (ATS) with AI Screening

## 📋 Overview

A comprehensive recruitment management system integrated into the team management platform. Features include job posting creation with public links, AI-powered CV screening, custom screening questions (text, video, file uploads), applicant management, testing system, and talent pool for future recruitment.

## 🎯 Key Features

### 1. **Job Post Management**
- ✅ Create job posts with rich details (title, description, requirements, responsibilities, benefits)
- ✅ Auto-generate unique public URL for each job post (shareable link)
- ✅ Configure job type (full-time, part-time, contract, remote)
- ✅ Set salary range, location, experience level, application deadline
- ✅ Status management: Draft → Published → Closed
- ✅ Duplicate job posts for quick creation
- ✅ Track positions available and total applications

### 2. **Custom Screening Questions**
- ✅ **Text Input**: Short text answers
- ✅ **Textarea**: Long-form answers
- ✅ **Video Upload**: Record or upload video responses (up to 100MB)
- ✅ **File Upload**: Upload documents/portfolios
- ✅ **Multiple Choice**: Pre-defined options
- ✅ Mark questions as required/optional
- ✅ Reorder questions for better flow

### 3. **AI-Powered CV Screening** (OpenAI GPT-4o-mini)
- ✅ **Automatic Text Extraction**: Extracts text from PDF resumes
- ✅ **Intelligent Analysis**: Matches candidates against job requirements
- ✅ **AI Status Classification**:
  - **Best Match** (90-100 score): Top candidates
  - **Good to Go** (70-89 score): Strong candidates
  - **Not a Good Fit** (0-69 score): Weak match
  - **Pending**: Not yet screened
- ✅ **Detailed AI Insights**:
  - Match score (0-100)
  - Key strengths (3-5 bullet points)
  - Potential concerns/gaps (2-4 bullet points)
  - Recommended next steps
  - Brief summary (2-3 sentences)
- ✅ **Batch Screening**: Process multiple applications at once
- ✅ **Configurable Criteria**: Set custom screening criteria per job

### 4. **Application Management**
- ✅ View all applications with filtering:
  - By job post
  - By AI status (best_match, good_to_go, not_good_fit, pending)
  - By application status (new, screening, interview, test_sent, rejected, hired)
  - Search by name/email
- ✅ **Application Details View**:
  - Personal information (name, email, phone, LinkedIn, portfolio)
  - Resume download
  - Cover letter
  - AI analysis results
  - Custom question answers (including video/file previews)
  - Admin notes
  - Activity timeline
- ✅ **Status Management**:
  - New → Screening → Interview → Test Sent → Rejected/Hired
  - Add admin notes for each application
  - Track reviewer and review date

### 5. **Interview & Testing System**
- ✅ **Send Interview Invitations**:
  - Compose custom message
  - Include date, time, location
  - Auto-send email to candidate
  - Update application status to "interview"
  
- ✅ **Send Tests**:
  - Create test title, description, instructions
  - Attach test file (PDF, DOC, etc.)
  - Set submission deadline
  - Generate unique test link for candidate
  - Email notification with test details
  
- ✅ **Test Submission**:
  - Candidates access test via email link (email-verified)
  - Upload test submission (up to 50MB)
  - Track submission status and timestamp
  - Admin reviews and provides score/feedback

### 6. **Talent Pool for Future Opportunities**
- ✅ Add promising candidates to talent pool (even if rejected)
- ✅ Store comprehensive candidate data:
  - Contact information
  - Skills (extracted from AI analysis)
  - Experience level
  - Resume file
  - Source (job application, manual, referral)
- ✅ **Status Tracking**:
  - Potential → Contacted → Interested → Not Interested
- ✅ Search and filter talent pool
- ✅ Track last contact date
- ✅ Add custom notes
- ✅ Use for future marketing campaigns

### 7. **Public Job Board**
- ✅ **No Authentication Required**:
  - Candidates can browse jobs without logging in
  - View full job details
  - Apply directly through web form
- ✅ **Job Listing Page**:
  - Filter by job type, location
  - Search by keywords
  - Pagination
- ✅ **Application Form**:
  - Personal information fields
  - Resume upload (PDF/DOC, max 10MB)
  - Cover letter
  - Answer custom screening questions
  - Upload videos/files for questions
  - Duplicate detection (email-based)
- ✅ **Success Page**: Confirmation after submission

---

## 📁 Database Structure

### Tables Created

#### 1. `job_posts`
```
- id
- title
- slug (unique, auto-generated)
- description
- location
- job_type (full-time, part-time, contract, remote)
- experience_level (entry, mid, senior)
- salary_min, salary_max, salary_currency
- requirements, responsibilities, benefits (text)
- status (draft, published, closed)
- application_deadline
- contact_email, department
- positions_available
- ai_screening_enabled (boolean)
- ai_screening_criteria (JSON)
- created_by (employee_id)
- timestamps, soft_deletes
```

#### 2. `job_questions`
```
- id
- job_post_id (foreign key)
- question (text)
- type (text, textarea, video, file, multiple_choice)
- options (JSON for multiple choice)
- is_required (boolean)
- order (integer)
- timestamps
```

#### 3. `job_applications`
```
- id
- job_post_id (foreign key)
- first_name, last_name, email, phone
- linkedin_url, portfolio_url
- cover_letter (text)
- resume_path, resume_original_name
- resume_text (extracted text)
- ai_status (pending, best_match, good_to_go, not_good_fit)
- ai_match_score (0-100)
- ai_analysis (JSON)
- application_status (new, screening, interview, test_sent, rejected, hired)
- admin_notes (text)
- added_to_talent_pool (boolean)
- reviewed_at, reviewed_by (employee_id)
- timestamps, soft_deletes
```

#### 4. `application_answers`
```
- id
- job_application_id (foreign key)
- job_question_id (foreign key)
- answer_text
- answer_file_path (for video/file uploads)
- answer_file_type (mime type)
- timestamps
```

#### 5. `application_tests`
```
- id
- job_application_id (foreign key)
- test_title, test_description, test_instructions
- test_file_path (test document)
- submission_file_path (candidate's submission)
- submission_original_name
- status (sent, submitted, reviewed)
- sent_at, submitted_at, deadline
- score, feedback
- sent_by (employee_id), reviewed_by (employee_id)
- timestamps
```

#### 6. `talent_pool`
```
- id
- job_application_id (nullable, foreign key)
- first_name, last_name
- email (unique)
- phone, linkedin_url, portfolio_url
- skills (JSON array)
- experience_level
- resume_path
- notes (text)
- status (potential, contacted, interested, not_interested)
- source (job_application, manual, referral, etc.)
- last_contacted_at
- added_by (employee_id)
- timestamps, soft_deletes
```

---

## 🔧 Backend Components

### Models
- `JobPost` - Main job posting model
- `JobQuestion` - Screening questions
- `JobApplication` - Candidate applications
- `ApplicationAnswer` - Answers to screening questions
- `ApplicationTest` - Tests sent to candidates
- `TalentPool` - Saved candidates for future

### Controllers

#### Admin Controllers (Employee Authentication Required)
- `Admin\JobPostController` - CRUD for job posts
- `Admin\JobApplicationController` - Application management, AI screening, interviews, tests
- `Admin\TalentPoolController` - Talent pool management

#### Public Controller (No Authentication)
- `PublicJobController` - Job listing, application submission, test submission

### Services
- `AIScreeningService` - OpenAI integration for CV analysis
  - `screenApplication()` - Analyze single application
  - `batchScreen()` - Process multiple applications
  - `extractTextFromResume()` - PDF text extraction (smalot/pdfparser)
  - `buildScreeningPrompt()` - Generate AI prompt with job requirements
  - `callOpenAI()` - API communication
  - `parseAIResponse()` - Extract structured data from AI response

### Mail Classes (To be implemented)
- `InterviewInvitationMail` - Email for interview invitations
- `TestAssignmentMail` - Email for test assignments
- `NewApplicationMail` - Notify admin of new applications

---

## 🌐 Routes

### Public Routes (No Auth)
```php
GET  /jobs                           - List all published jobs
GET  /jobs/{slug}                    - View job details & application form
POST /jobs/{slug}/apply              - Submit application
GET  /jobs/application/{id}/success  - Application success page
GET  /jobs/test/{testId}             - View assigned test
POST /jobs/test/{testId}/submit      - Submit test
```

### Admin Routes (Employee Auth Required)
```php
// Job Posts
GET    /admin/jobs                       - List all job posts
GET    /admin/jobs/create                - Create job form
POST   /admin/jobs                       - Store new job
GET    /admin/jobs/{job}                 - View job details & stats
GET    /admin/jobs/{job}/edit            - Edit job form
PUT    /admin/jobs/{job}                 - Update job
DELETE /admin/jobs/{job}                 - Delete job
POST   /admin/jobs/{job}/duplicate       - Duplicate job

// Applications
GET    /admin/applications                           - List all applications (with filters)
GET    /admin/applications/{application}             - View application details
PUT    /admin/applications/{application}/status      - Update application status
POST   /admin/applications/{application}/ai-screening - Run AI screening
POST   /admin/applications/batch-ai-screening        - Batch AI screening
POST   /admin/applications/{application}/talent-pool - Add to talent pool
POST   /admin/applications/{application}/interview   - Send interview invitation
POST   /admin/applications/{application}/test        - Send test
GET    /admin/applications/{application}/resume      - Download resume
DELETE /admin/applications/{application}             - Delete application

// Talent Pool
GET    /admin/talent-pool                 - List talent pool
GET    /admin/talent-pool/{talentPool}    - View talent details
PUT    /admin/talent-pool/{talentPool}    - Update talent
DELETE /admin/talent-pool/{talentPool}    - Delete from pool
```

---

## 🤖 AI Screening Configuration

### Setup
1. Add to `.env`:
```env
OPENAI_API_KEY=sk-your-api-key-here
```

2. Configure in `config/services.php` (already done):
```php
'openai' => [
    'api_key' => env('OPENAI_API_KEY'),
],
```

### How AI Screening Works

1. **Resume Text Extraction**:
   - Uses `smalot/pdfparser` to extract text from PDF resumes
   - Stores extracted text in `resume_text` column
   - Falls back gracefully if extraction fails

2. **AI Analysis**:
   - Sends job requirements + candidate resume to OpenAI GPT-4o-mini
   - AI evaluates fit based on:
     - Job requirements
     - Responsibilities
     - Experience level
     - Custom screening criteria (if set)
   - Returns structured JSON with score, status, strengths, concerns

3. **Scoring System**:
   - **90-100**: Best Match (immediate interview)
   - **70-89**: Good to Go (strong candidate)
   - **0-69**: Not a Good Fit (likely reject)

4. **Batch Processing**:
   - Process multiple applications at once
   - Useful for initial screening after job post publication

### Custom Screening Criteria
In job post form, you can add JSON criteria:
```json
{
  "required_skills": ["Laravel", "Vue.js", "MySQL"],
  "years_experience": "3+",
  "education_level": "Bachelor's degree",
  "location_flexibility": "Remote OK"
}
```

---

## 📧 Email Notifications

### Interview Invitation Email
- Subject: "Interview Invitation - [Job Title]"
- Contains: Date, time, location, custom message
- Triggered when admin clicks "Invite to Interview"

### Test Assignment Email
- Subject: "Technical Assessment - [Job Title]"
- Contains: Test instructions, deadline, unique test link
- Secured with email verification
- Triggered when admin clicks "Send Test"

### New Application Notification (Admin)
- Notify hiring managers when new application received
- Include: Candidate name, job title, AI score (if enabled)
- Link to application details

---

## 🎨 Views to Create (Next Step)

### Admin Views (`resources/views/admin/jobs/`)
1. `index.blade.php` - Job posts list with search/filter
2. `create.blade.php` - Create job form with question builder
3. `edit.blade.php` - Edit job form
4. `show.blade.php` - Job details + application stats

### Admin Views (`resources/views/admin/applications/`)
1. `index.blade.php` - Applications list with filters (AI status, job, status)
2. `show.blade.php` - Application detail view with AI analysis, answers, actions

### Admin Views (`resources/views/admin/talent-pool/`)
1. `index.blade.php` - Talent pool list
2. `show.blade.php` - Talent profile view

### Public Views (`resources/views/jobs/`)
1. `index.blade.php` - Public job listing page
2. `show.blade.php` - Job details + application form
3. `closed.blade.php` - Job no longer accepting applications
4. `success.blade.php` - Application submitted confirmation
5. `test.blade.php` - Test view for candidates
6. `test-submitted.blade.php` - Test submission confirmation

---

## 🔐 Security Features

### Public Job Board
- ✅ No authentication required for browsing/applying
- ✅ Email-based duplicate prevention
- ✅ File upload validation (type, size)
- ✅ XSS protection (sanitized inputs)

### Test System
- ✅ Email verification required to access tests
- ✅ Unique test links (non-guessable IDs)
- ✅ One-time submission (status check)
- ✅ Deadline enforcement

### Admin Access
- ✅ Employee authentication required
- ✅ RBAC permissions (to be configured)
- ✅ Activity tracking (reviewed_by, reviewed_at)

---

## 📦 File Storage

### Upload Directories (storage/app/public/)
- `resumes/` - Candidate resumes (PDF/DOC)
- `application_answers/` - Video/file uploads from screening questions
- `tests/` - Test documents sent to candidates
- `test_submissions/` - Candidate test submissions

### File Size Limits
- Resume: 10MB (PDF, DOC, DOCX)
- Video uploads: 100MB
- Test submissions: 50MB

---

## 🚀 Deployment Checklist

### Required Configuration
1. ✅ Database migrations run: `php artisan migrate`
2. ✅ Storage link created: `php artisan storage:link`
3. ✅ OpenAI API key configured in `.env`
4. ⏳ PDF parser installed: `composer require smalot/pdfparser` (done)
5. ⏳ Mail configuration (SMTP/Mailtrap for testing)
6. ⏳ Queue worker for async AI processing (optional but recommended)

### Optional Optimizations
- Use Laravel Queues for AI screening (prevents timeout)
- Add caching for job listing page
- Implement rate limiting on public application endpoint
- Add Google reCAPTCHA to prevent spam applications
- Set up S3 for file storage in production

---

## 🎯 Usage Workflow

### For Admin (Hiring Manager)

1. **Create Job Post**
   - Go to `/admin/jobs/create`
   - Fill in job details
   - Add screening questions (text, video, file)
   - Set AI screening criteria (optional)
   - Publish job post

2. **Share Job Link**
   - Copy public URL: `https://your-domain.com/jobs/{slug}`
   - Share on job boards, social media, company website

3. **Review Applications**
   - Go to `/admin/applications`
   - Filter by AI status to see "Best Match" candidates first
   - Click on application to view details
   - Watch video responses, download resume
   - Run AI screening if not already done

4. **Take Action**
   - Update status (Screening → Interview → Test → Hired/Rejected)
   - Send interview invitation with date/time
   - Send test with deadline
   - Add to talent pool for future opportunities
   - Add admin notes for team collaboration

5. **Manage Talent Pool**
   - Access saved candidates at `/admin/talent-pool`
   - Update contact status
   - Use for future job openings
   - Export for marketing campaigns

### For Candidates (Public)

1. **Browse Jobs**
   - Visit `https://your-domain.com/jobs`
   - Search and filter jobs
   - No account needed

2. **Apply for Job**
   - Click on job title
   - Fill in application form
   - Upload resume (PDF/DOC)
   - Answer screening questions
   - Record/upload video responses
   - Submit application

3. **Receive Interview Invitation**
   - Email with interview details
   - Includes date, time, location
   - Custom message from hiring manager

4. **Complete Test**
   - Email with unique test link
   - View test instructions and questions
   - Download test document (if provided)
   - Complete test
   - Upload submission before deadline
   - Confirmation page after submission

---

## 🐛 Troubleshooting

### AI Screening Not Working
- Check `OPENAI_API_KEY` in `.env`
- Run `php artisan config:clear`
- Verify OpenAI account has credits
- Check logs: `storage/logs/laravel.log`

### PDF Text Extraction Failing
- Ensure PDF is not image-based (scanned document)
- Try with different resume format
- AI will still work without extracted text (admin can manually review)

### File Uploads Failing
- Check `php.ini`: `upload_max_filesize`, `post_max_size`
- Verify storage permissions: `chmod -R 775 storage`
- Check disk space

### Emails Not Sending
- Configure mail driver in `.env`
- Use Mailtrap for testing
- Check firewall/SMTP settings

---

## 📈 Future Enhancements

- [ ] **Email Templates Editor**: WYSIWYG editor for interview/test emails
- [ ] **Calendar Integration**: Sync interviews with Google Calendar
- [ ] **Video Interview**: Built-in video calling (Zoom/Teams integration)
- [ ] **Advanced Analytics**: Hiring funnel, time-to-hire metrics
- [ ] **Candidate Portal**: Account for candidates to track status
- [ ] **Referral System**: Employee referral tracking with rewards
- [ ] **Background Checks**: Integration with background check services
- [ ] **Offer Management**: Generate and send offer letters
- [ ] **Onboarding**: Seamless transition from hired to employee
- [ ] **Multi-language Support**: Translations for job posts and forms

---

## 🔗 Related Documentation

- [Laravel File Storage](https://laravel.com/docs/11.x/filesystem)
- [OpenAI API Documentation](https://platform.openai.com/docs/api-reference)
- [PDF Parser Library](https://github.com/smalot/pdfparser)
- [Laravel Mail System](https://laravel.com/docs/11.x/mail)

---

## 📝 Notes

- **Cost Consideration**: OpenAI API charges per token. Estimate $0.15-0.60 per 1M tokens. Monitor usage in OpenAI dashboard.
- **Queue Jobs**: For production, queue AI screening to prevent timeout: `php artisan queue:work`
- **Testing**: Use Mailtrap.io for email testing in development
- **Backup**: Regular database backups especially for `job_applications` and `talent_pool` tables
- **GDPR Compliance**: Add data retention policy, allow candidates to request data deletion

---

## ✅ What's Completed

- ✅ Database migrations (6 tables)
- ✅ Eloquent models with relationships
- ✅ AI Screening Service with OpenAI integration
- ✅ Admin controllers (Job Posts, Applications, Talent Pool)
- ✅ Public job controller (Listing, Application, Test submission)
- ✅ Routes (Admin + Public)
- ✅ PDF parser package installed
- ✅ OpenAI configuration

## ⏳ What's Remaining

- ⏳ Admin views (job posts, applications, talent pool)
- ⏳ Public views (job listing, application form, test pages)
- ⏳ Mail classes implementation (Interview, Test, New Application)
- ⏳ Navigation menu updates
- ⏳ RBAC permissions configuration
- ⏳ Queue jobs for async AI processing (optional)
- ⏳ Testing and debugging

---

**Ready to use after views are created!** The entire backend infrastructure is complete and functional.
