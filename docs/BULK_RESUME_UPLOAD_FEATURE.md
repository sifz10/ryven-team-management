# Bulk Resume Upload & AI Analysis System

## Overview
Added bulk resume upload feature to admin dashboard. Admin can drag-and-drop multiple resume files for a specific job post. AI automatically analyzes all resumes and categorizes them by match quality.

## Access
**Admin Dashboard** → **Jobs** → **[Select Job]** → **Bulk Upload Resumes** button (purple button in Quick Actions sidebar)

## Features

### 1. Drag-and-Drop Upload Interface
- **Location**: `/admin/jobs/{job}/bulk-upload`
- **View**: `resources/views/admin/jobs/bulk-upload.blade.php`
- **Features**:
  - Modern drag-and-drop zone with hover effects
  - Click to browse alternative
  - Multiple file selection
  - File preview cards showing name, type, size
  - Remove individual files before upload
  - Progress bar during processing
  - Supported formats: PDF, DOC, DOCX
  - Max size: 10MB per file

### 2. AI-Powered Analysis
- **Controller**: `JobPostController@processBulkUpload`
- **Process**:
  1. Validates uploaded files (format, size)
  2. Stores files in `storage/bulk-resumes/{job_id}/`
  3. Creates `JobApplication` records with temp email `bulk-upload-{random}@temp.com`
  4. Extracts text from PDF/DOC files
  5. Runs AI screening using `AIScreeningService` (GPT-4o-mini)
  6. Analyzes against job requirements
  7. Assigns match score and detailed analysis
  8. Categorizes into 4 groups

### 3. Results Display
- **Location**: `/admin/jobs/{job}/bulk-upload` (POST redirects here)
- **View**: `resources/views/admin/jobs/bulk-results.blade.php`
- **Categories**:
  - ✅ **Best Match** (Green cards) - Score 80-100%
  - 👍 **Good to Go** (Blue cards) - Score 60-79%
  - ❌ **Not a Good Fit** (Red cards) - Score 0-59%
  - ⚠️ **Errors** (Yellow alerts) - Processing failures

### 4. Results Interface
- **Summary Stats** at top:
  - Total processed count
  - Best match count
  - Good to go count
  - Not a good fit count
- **Candidate Cards** showing:
  - Full name (from filename)
  - Match score percentage
  - AI analysis summary
  - Action buttons:
    - "View Details" → Full application page
    - "Download" → Original resume file

## Backend Implementation

### Routes (web.php)
```php
Route::middleware(['auth', 'verified'])->prefix('admin')->group(function () {
    // ... existing routes
    Route::get('/jobs/{job}/bulk-upload', [JobPostController::class, 'bulkUpload'])
        ->name('admin.jobs.bulk-upload');
    Route::post('/jobs/{job}/bulk-upload', [JobPostController::class, 'processBulkUpload'])
        ->name('admin.jobs.bulk-upload.process');
});
```

### Controller Methods (JobPostController.php)
```php
public function bulkUpload(JobPost $job)
{
    return view('admin.jobs.bulk-upload', compact('job'));
}

public function processBulkUpload(Request $request, JobPost $job)
{
    $request->validate([
        'resumes' => 'required|array|min:1',
        'resumes.*' => 'required|file|mimes:pdf,doc,docx|max:10240', // 10MB
    ]);

    $results = [
        'best_match' => [],
        'good_to_go' => [],
        'not_good_fit' => [],
        'errors' => [],
    ];

    foreach ($request->file('resumes') as $resume) {
        // Store file
        $path = $resume->store("bulk-resumes/{$job->id}", 'public');
        
        // Extract name from filename
        $nameFromFile = pathinfo($resume->getClientOriginalName(), PATHINFO_FILENAME);
        $nameParts = explode(' ', str_replace(['_', '-'], ' ', $nameFromFile), 2);
        $firstName = $nameParts[0];
        $lastName = isset($nameParts[1]) ? $nameParts[1] : 'N/A';
        
        // Create application with temp email
        $application = JobApplication::create([
            'job_post_id' => $job->id,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => 'bulk-upload-' . Str::random(10) . '@temp.com',
            'phone' => 'N/A',
            'resume_path' => $path,
            'cover_letter' => 'Uploaded via bulk upload',
            'application_source' => 'bulk_upload',
        ]);

        // Run AI screening if enabled
        if ($job->ai_screening_enabled) {
            try {
                $screening = app(AIScreeningService::class)->screenApplication($application);
                
                // Categorize by score
                if ($screening->score >= 80) {
                    $results['best_match'][] = [
                        'application' => $application,
                        'score' => $screening->score,
                        'analysis' => $screening->analysis,
                    ];
                } elseif ($screening->score >= 60) {
                    $results['good_to_go'][] = [
                        'application' => $application,
                        'score' => $screening->score,
                        'analysis' => $screening->analysis,
                    ];
                } else {
                    $results['not_good_fit'][] = [
                        'application' => $application,
                        'score' => $screening->score,
                        'analysis' => $screening->analysis,
                    ];
                }
            } catch (\Exception $e) {
                $results['errors'][] = [
                    'filename' => $resume->getClientOriginalName(),
                    'error' => $e->getMessage(),
                ];
            }
        }
    }

    return view('admin.jobs.bulk-results', compact('job', 'results'));
}
```

## UI Design

### Brand Guidelines Applied
- **Colors**: Pure black/white with category colors (green/blue/red/yellow)
- **Buttons**: `rounded-lg` with hover effects, purple bulk upload button
- **Cards**: Bordered cards with hover shadows
- **Icons**: Stroke-width 2.5 for visibility
- **Animations**: Smooth transitions (300ms)
- **Dark Mode**: Full dark mode support with proper contrast

### Upload Interface Features
- Drag-and-drop zone with border animations
- File preview with remove buttons
- Upload progress indicator
- Step-by-step instructions
- Context card showing job info and AI status

### Results Interface Features
- Color-coded category sections
- Summary statistics cards
- Candidate cards with scores
- Action buttons for each candidate
- Error handling display

## Workflow Example

1. **Admin navigates** to job detail page
2. **Clicks** "Bulk Upload Resumes" button (purple button)
3. **Uploads** 10 PDF resumes via drag-and-drop
4. **Reviews** file list, removes any wrong files
5. **Clicks** "Analyze Resumes with AI"
6. **System processes**:
   - Validates files
   - Stores in `bulk-resumes/` directory
   - Creates 10 application records
   - Runs AI analysis on each
   - Categorizes by match quality
7. **Admin sees** results page with:
   - 3 Best Match candidates (green)
   - 5 Good to Go candidates (blue)
   - 2 Not a Good Fit candidates (red)
8. **Admin reviews** AI analysis for each candidate
9. **Admin clicks** "View Details" to see full application
10. **Admin downloads** resumes for top candidates

## Database Storage

### File Storage
- **Directory**: `storage/app/public/bulk-resumes/{job_id}/`
- **Naming**: Original filename preserved
- **Access**: Public via `storage/` symlink

### Application Records
- **Table**: `job_applications`
- **Fields**:
  - `first_name`: Extracted from filename (first part)
  - `last_name`: Extracted from filename (remaining part, or "N/A")
  - `email`: `bulk-upload-{random}@temp.com`
  - `phone`: "N/A"
  - `resume_path`: Path to stored file
  - `application_source`: "bulk_upload"
  - `ai_screening_score`: Score from AI
  - `ai_screening_result`: Pass/fail status
  - `ai_screening_notes`: Detailed analysis

## AI Integration

### Service Used
- **Service**: `AIScreeningService`
- **Model**: OpenAI GPT-4o-mini
- **Context**: Job description + requirements + responsibilities
- **Analysis**: Skills match, experience fit, qualifications assessment
- **Output**: Score (0-100) + detailed analysis text

### Scoring Thresholds
- **80-100%**: Best Match - Strong candidate, highly recommended
- **60-79%**: Good to Go - Qualified candidate, worth considering
- **0-59%**: Not a Good Fit - Missing key requirements

### Error Handling
- File parsing failures
- AI API errors
- Invalid file formats
- Oversized files
- All errors displayed in yellow alert section

## Technical Requirements

### Server Requirements
- PHP 8.4+
- Laravel 12.x
- OpenAI API key configured
- PDF parser library: `smalot/pdfparser`
- Storage disk: `public` (symlinked)

### Configuration
```env
OPENAI_API_KEY=sk-...
```

### Permissions
- Storage directory writable
- Public disk accessible via web

## Future Enhancements

### Potential Features
- [ ] Email extraction from resumes
- [ ] Phone number extraction
- [ ] Batch actions (approve/reject multiple)
- [ ] Export results to CSV
- [ ] Email notifications for best matches
- [ ] Duplicate resume detection
- [ ] Resume parsing for structured data
- [ ] Bulk interview scheduling
- [ ] Integration with talent pool
- [ ] Resume ranking algorithm
- [ ] Custom scoring criteria per job

## Troubleshooting

### Issue: Files not uploading
- **Check**: File size (max 10MB)
- **Check**: File format (PDF, DOC, DOCX only)
- **Check**: Storage permissions
- **Check**: PHP upload limits

### Issue: AI analysis failing
- **Check**: OPENAI_API_KEY is set
- **Check**: Job has AI screening enabled
- **Check**: PDF text extraction working
- **Check**: Network connectivity to OpenAI API

### Issue: Results not displaying
- **Check**: Route names match
- **Check**: View file exists
- **Check**: Data structure from controller
- **Check**: Browser console for errors

## Related Files

### Controllers
- `app/Http/Controllers/Admin/JobPostController.php` (lines 200-248)

### Routes
- `routes/web.php` (lines 643-644)

### Views
- `resources/views/admin/jobs/bulk-upload.blade.php` (upload form)
- `resources/views/admin/jobs/bulk-results.blade.php` (results display)
- `resources/views/admin/jobs/show.blade.php` (bulk upload button)

### Services
- `app/Services/AIScreeningService.php` (AI analysis)

### Models
- `app/Models/JobPost.php`
- `app/Models/JobApplication.php`

## Testing

### Manual Testing Steps
1. Create a test job post with AI screening enabled
2. Navigate to job detail page
3. Click "Bulk Upload Resumes"
4. Upload 5-10 test resume PDFs
5. Verify progress indicator shows
6. Check results page displays correctly
7. Verify AI scores and analysis
8. Test "View Details" button
9. Test "Download" button
10. Verify files stored in correct directory

### Test Cases
- ✅ Upload single file
- ✅ Upload multiple files (5-10)
- ✅ Remove file before upload
- ✅ Upload with AI enabled
- ✅ Upload with AI disabled
- ✅ Invalid file format (JPG, PNG)
- ✅ Oversized file (>10MB)
- ✅ Empty upload (no files)
- ✅ View application details
- ✅ Download resume file

## Notes
- Temp emails prevent duplicate email validation errors
- Files organized by job ID for easy management
- AI analysis runs in real-time during upload
- Progress bar is simulated (actual upload is synchronous)
- All applications searchable from main applications page
- Results page can be bookmarked and revisited
- No pagination on results (all displayed at once)
