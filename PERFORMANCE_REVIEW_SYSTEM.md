# Performance Review System

## Overview
Comprehensive performance management system with periodic reviews, 360-degree feedback, goal tracking (OKRs/KPIs), and skill assessments.

## Database Schema

### Tables Created
1. **review_cycles** - Define review periods (quarterly, annual, etc.)
2. **review_templates** - Reusable review templates with custom sections and rating scales
3. **performance_reviews** - Main performance review records
4. **review_feedbacks** - 360-degree feedback from multiple sources
5. **goals** - Goal tracking (OKRs, KPIs, SMART goals)
6. **skills** - Master skills catalog
7. **employee_skills** - Employee skill assessments and proficiency levels

## Models

### ReviewCycle
Manages review periods with configurable types and statuses.

**Key Features:**
- Types: quarterly, annual, mid_year, probation, project_based
- Statuses: scheduled, active, completed, cancelled
- Relationships: performanceReviews, goals, creator
- Scopes: active(), upcoming(), completed()

### PerformanceReview
Core performance review record linking employee, cycle, and template.

**Key Features:**
- Statuses: draft, in_progress, under_review, completed
- JSON ratings storage for flexible section-based ratings
- Comments: strengths, areas_for_improvement, achievements, manager_comments, employee_comments
- PIP tracking: requires_pip, pip_created flags
- Relationships: employee, reviewCycle, template, reviewer, feedbacks
- Helper methods: isCompleted(), canEdit()

### ReviewFeedback
360-degree feedback from multiple reviewers.

**Key Features:**
- Feedback types: self, manager, peer, subordinate, client
- JSON ratings for detailed multi-section feedback
- Statuses: pending, submitted
- Scopes: selfAssessment(), managerFeedback(), peerFeedback()
- Helper methods: isSubmitted(), canEdit()

### ReviewTemplate
Reusable review templates with customizable sections and rating scales.

**Key Features:**
- JSON sections with name, description, and weight
- Customizable rating scales (1-5, 1-4, etc.)
- Default template flag
- Active/inactive status
- Helper methods: getSections(), getRatingScale()

**Default Templates Seeded:**
1. Annual Performance Review (6 sections, 4-point scale)
2. Quarterly Performance Review (3 sections, 3-point scale)
3. Probation Period Review (4 sections, 3-point scale)

### Goal
Goal tracking supporting multiple frameworks (OKRs, KPIs, SMART).

**Key Features:**
- Types: okr, kpi, smart, development, project
- Progress tracking (0-100%)
- JSON key_results for OKR tracking
- JSON metrics for KPI measurements
- Statuses: not_started, in_progress, completed, on_hold, cancelled
- Scopes: active(), completed(), overdue(), OKRs(), KPIs()
- Helper methods: isOverdue(), updateProgress()

### Skill
Master skills catalog with categorization.

**Key Features:**
- Categories: technical, soft, leadership, domain, tool, language
- Active/inactive status
- Many-to-many with employees via employee_skills
- Scopes: active(), byCategory(), technical(), soft(), leadership()

**Default Skills Seeded:**
- Technical: PHP, Laravel, JavaScript, Vue.js, React, MySQL, PostgreSQL, Git, API Development, Docker, AWS, Testing
- Soft Skills: Communication, Problem Solving, Time Management, Adaptability, Attention to Detail, Critical Thinking, Collaboration, Creativity
- Leadership: Team Leadership, Decision Making, Delegation, Mentoring, Conflict Resolution, Strategic Thinking
- Domain: E-commerce, Fintech, Healthcare, SaaS, Agile/Scrum
- Tools: Jira, Figma, Postman, VS Code

### EmployeeSkill
Pivot model for employee skill assessments.

**Key Features:**
- Proficiency levels: 1 (Beginner) to 5 (Master)
- Auto-generated proficiency labels
- Years of experience tracking
- Assessment tracking: last_assessed_at, assessed_by
- Primary skill flag
- Scopes: primary(), byProficiency(), expert(), beginner()
- Helper methods: isPrimary(), isExpert(), getProficiencyLabelAttribute()

## Routes

All routes are prefixed with `/performance` and require authentication.

### Review Cycles
- `GET /performance/review-cycles` - List all review cycles
- `GET /performance/review-cycles/create` - Create new cycle form
- `POST /performance/review-cycles` - Store new cycle
- `GET /performance/review-cycles/{id}` - View cycle details
- `GET /performance/review-cycles/{id}/edit` - Edit cycle form
- `PUT /performance/review-cycles/{id}` - Update cycle
- `DELETE /performance/review-cycles/{id}` - Delete cycle
- `POST /performance/review-cycles/{id}/activate` - Activate cycle
- `POST /performance/review-cycles/{id}/complete` - Complete cycle

### Performance Reviews
- `GET /performance/reviews` - List all reviews
- `GET /performance/reviews/create` - Create review form
- `POST /performance/reviews` - Store new review
- `GET /performance/reviews/{id}` - View review details
- `GET /performance/reviews/{id}/edit` - Edit review form
- `PUT /performance/reviews/{id}` - Update review
- `DELETE /performance/reviews/{id}` - Delete review
- `POST /performance/reviews/{id}/submit` - Submit review for approval
- `POST /performance/reviews/{id}/approve` - Approve review
- `GET /performance/reviews/{id}/pdf` - Download review PDF

### 360 Feedback
- `GET /performance/reviews/{id}/feedback` - Feedback form
- `POST /performance/reviews/{id}/feedback` - Submit feedback

### Goals & OKRs
- `GET /performance/goals` - List all goals
- `GET /performance/goals/create` - Create goal form
- `POST /performance/goals` - Store new goal
- `GET /performance/goals/{id}` - View goal details
- `GET /performance/goals/{id}/edit` - Edit goal form
- `PUT /performance/goals/{id}` - Update goal
- `DELETE /performance/goals/{id}` - Delete goal
- `POST /performance/goals/{id}/update-progress` - Update progress percentage
- `POST /performance/goals/{id}/complete` - Mark goal as completed

### Skills
- `GET /performance/skills` - List all skills
- `GET /performance/skills/create` - Create skill form
- `POST /performance/skills` - Store new skill
- `GET /performance/skills/{id}` - View skill details
- `GET /performance/skills/{id}/edit` - Edit skill form
- `PUT /performance/skills/{id}` - Update skill
- `DELETE /performance/skills/{id}` - Delete skill
- `POST /performance/skills/bulk-create` - Bulk create skills

### Employee Skills Assessment
- `GET /employees/{employee}/skills` - View employee skills
- `POST /employees/{employee}/skills` - Assess new skill for employee
- `PUT /employees/{employee}/skills/{id}` - Update skill assessment
- `DELETE /employees/{employee}/skills/{id}` - Remove skill from employee

## Controllers

### ReviewCycleController
Manages review cycles (create, activate, complete).

**Methods to implement:**
- index() - List cycles with filtering
- create() - Show create form
- store() - Save new cycle
- show() - View cycle with associated reviews
- edit() - Show edit form
- update() - Update cycle
- destroy() - Delete cycle
- activate() - Activate scheduled cycle
- complete() - Complete active cycle

### PerformanceReviewController
Manages performance reviews and 360 feedback.

**Methods to implement:**
- index() - List reviews with filtering (by employee, cycle, status)
- create() - Show review form with template selection
- store() - Create new review
- show() - View review details with all feedbacks
- edit() - Edit review (if status allows)
- update() - Update review
- destroy() - Delete review
- submit() - Submit for manager approval
- approve() - Manager approval
- downloadPdf() - Generate PDF report
- feedbackForm() - Show 360 feedback form
- submitFeedback() - Save 360 feedback

### GoalController
Manages goal tracking (OKRs, KPIs, SMART goals).

**Methods to implement:**
- index() - List goals with filtering (by employee, cycle, type, status)
- create() - Show goal creation form
- store() - Save new goal
- show() - View goal details with progress history
- edit() - Edit goal form
- update() - Update goal
- destroy() - Delete goal
- updateProgress() - Update progress percentage
- complete() - Mark goal as completed

### SkillController
Manages skill catalog.

**Methods to implement:**
- index() - List skills with category filtering
- create() - Show skill creation form
- store() - Save new skill
- show() - View skill with employee proficiency stats
- edit() - Edit skill form
- update() - Update skill
- destroy() - Delete skill (soft delete if in use)
- bulkCreate() - Create multiple skills at once

### EmployeeController (additions)
Add skill assessment methods to existing EmployeeController.

**New methods to implement:**
- skills() - View employee skill matrix
- assessSkill() - Assess employee on a skill
- updateSkillAssessment() - Update existing assessment
- removeSkill() - Remove skill from employee

## Employee Model Updates

Added relationships to Employee model:
```php
public function performanceReviews(): HasMany
public function goals(): HasMany
public function skills() // Many-to-many with pivot data
public function employeeSkills(): HasMany
```

## Seeder

**PerformanceReviewSeeder** created with:
- 3 default review templates (Annual, Quarterly, Probation)
- 40+ skills across 5 categories
  - 12 Technical skills
  - 8 Soft skills
  - 6 Leadership skills
  - 5 Domain skills
  - 4 Tool skills

Run seeder:
```bash
php artisan db:seed --class=PerformanceReviewSeeder
```

## Next Steps

### 1. Implement Controllers
Start with basic CRUD operations for each controller, then add specialized methods.

### 2. Create Views
- Review cycle management dashboard
- Performance review form with dynamic template sections
- 360 feedback submission interface
- Goal tracking dashboard with progress charts
- Skill matrix view (heat map style)
- Employee profile integration

### 3. Add Navigation
Add menu items to existing layout for:
- Performance Reviews
- Goals & OKRs
- Skills Management
- Review Cycles (admin only)

### 4. Implement Notifications
- Review cycle start/end reminders
- Feedback request notifications
- Goal milestone achievements
- Overdue review alerts

### 5. Reports & Analytics
- Performance trends over time
- Goal achievement rates
- Skill gap analysis
- Team performance comparisons

### 6. Integration with Existing Features
- Link reviews to attendance data
- Connect goals to GitHub activity
- Include reviews in employee dashboard
- Add skill requirements to job positions

## Features Summary

✅ **Database Schema** - All 7 tables created with proper relationships and indexes
✅ **Models** - Complete with fillable fields, casts, relationships, and helper methods
✅ **Routes** - RESTful routes for all resources with custom actions
✅ **Seeder** - Default templates and skills populated
✅ **Employee Integration** - Relationships added to Employee model

🔄 **Pending:**
- Controller implementations (index, create, store, show, edit, update, destroy)
- Views for all CRUD operations
- PDF generation for performance reviews
- Real-time notifications
- Analytics dashboard
- Integration with employee profiles

## Usage Examples

### Create Review Cycle
```php
ReviewCycle::create([
    'name' => 'Q1 2025 Review',
    'type' => 'quarterly',
    'start_date' => '2025-01-01',
    'end_date' => '2025-03-31',
    'review_due_date' => '2025-04-15',
    'status' => 'scheduled',
    'created_by' => auth()->id(),
]);
```

### Create Performance Review
```php
PerformanceReview::create([
    'employee_id' => $employee->id,
    'review_cycle_id' => $cycle->id,
    'template_id' => $template->id,
    'status' => 'draft',
    'reviewer_id' => auth()->id(),
]);
```

### Submit 360 Feedback
```php
ReviewFeedback::create([
    'performance_review_id' => $review->id,
    'reviewer_id' => auth()->id(),
    'feedback_type' => 'peer',
    'ratings' => [
        'Job Knowledge' => 4,
        'Quality of Work' => 3,
        'Teamwork' => 5,
    ],
    'comments' => 'Excellent collaboration and technical skills',
    'status' => 'submitted',
]);
```

### Create Goal
```php
Goal::create([
    'employee_id' => $employee->id,
    'review_cycle_id' => $cycle->id,
    'title' => 'Improve API Performance',
    'type' => 'okr',
    'category' => 'technical',
    'weight' => 30,
    'start_date' => now(),
    'due_date' => now()->addMonths(3),
    'status' => 'in_progress',
    'progress' => 25,
    'key_results' => [
        ['result' => 'Reduce API response time by 50%', 'achieved' => false],
        ['result' => 'Implement caching layer', 'achieved' => true],
        ['result' => 'Add database indexing', 'achieved' => false],
    ],
]);
```

### Assess Employee Skill
```php
EmployeeSkill::create([
    'employee_id' => $employee->id,
    'skill_id' => $skill->id,
    'proficiency_level' => 4, // Expert
    'years_experience' => 3.5,
    'last_assessed_at' => now(),
    'assessed_by' => auth()->id(),
    'is_primary' => true,
]);
```

### Update Goal Progress
```php
$goal->updateProgress(75); // Automatically updates status if needed
```

## API Integration

The system is designed to work with the existing notification system:
- New reviews trigger notifications to employees
- Feedback requests notify reviewers
- Goal milestones broadcast to team
- Overdue reviews alert managers

All can be integrated with Laravel Reverb for real-time updates.
