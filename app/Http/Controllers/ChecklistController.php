<?php

namespace App\Http\Controllers;

use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateItem;
use App\Models\DailyChecklist;
use App\Models\DailyChecklistItem;
use App\Models\DailyWorkSubmission;
use App\Models\Employee;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\DailyChecklistMail;

class ChecklistController extends Controller
{
    // Store a new checklist template
    public function storeTemplate(Request $request, Employee $employee)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'role' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $template = $employee->checklistTemplates()->create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'role' => $validated['role'] ?? $employee->position,
                'is_active' => true,
            ]);

            foreach ($validated['items'] as $index => $itemTitle) {
                $template->items()->create([
                    'title' => $itemTitle,
                    'order' => $index,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('status', 'Checklist template created successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to create checklist template.');
        }
    }

    // Update a checklist template
    public function updateTemplate(Request $request, Employee $employee, ChecklistTemplate $template)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'role' => 'nullable|string|max:255',
            'items' => 'required|array|min:1',
            'items.*' => 'required|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $template->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? null,
                'role' => $validated['role'] ?? $employee->position,
            ]);

            // Delete old items and create new ones
            $template->items()->delete();
            foreach ($validated['items'] as $index => $itemTitle) {
                $template->items()->create([
                    'title' => $itemTitle,
                    'order' => $index,
                ]);
            }

            DB::commit();
            return redirect()->back()->with('status', 'Checklist template updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to update checklist template.');
        }
    }

    // Delete a checklist template
    public function destroyTemplate(Employee $employee, ChecklistTemplate $template)
    {
        $template->delete();
        return redirect()->back()->with('status', 'Checklist template deleted successfully!');
    }

    // Toggle checklist item completion
    public function toggleItem(Request $request, Employee $employee, DailyChecklistItem $item)
    {
        $isCompleted = !$item->is_completed;
        $item->update([
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        return response()->json([
            'success' => true,
            'is_completed' => $item->is_completed,
            'completed_at' => $item->completed_at?->format('g:i A'),
        ]);
    }

    // Generate today's checklists for an employee
    public function generateTodayChecklists(Employee $employee)
    {
        $today = now()->toDateString();
        $templates = $employee->checklistTemplates()->where('is_active', true)->get();

        if ($templates->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No active checklist templates found. Please create a template first.',
            ]);
        }

        DB::beginTransaction();
        try {
            $generatedCount = 0;
            $skippedCount = 0;

            foreach ($templates as $template) {
                // Check if checklist already exists for today
                $existingChecklist = DailyChecklist::where('employee_id', $employee->id)
                    ->where('checklist_template_id', $template->id)
                    ->where('date', $today)
                    ->first();

                if (!$existingChecklist) {
                    $dailyChecklist = DailyChecklist::create([
                        'employee_id' => $employee->id,
                        'checklist_template_id' => $template->id,
                        'date' => $today,
                    ]);

                    // Create items from template
                    foreach ($template->items as $templateItem) {
                        $dailyChecklist->items()->create([
                            'checklist_template_item_id' => $templateItem->id,
                            'title' => $templateItem->title,
                            'is_completed' => false,
                        ]);
                    }
                    $generatedCount++;
                } else {
                    $skippedCount++;
                }
            }

            DB::commit();
            
            if ($generatedCount > 0) {
                $message = "Generated {$generatedCount} checklist(s) successfully!";
            } elseif ($skippedCount > 0) {
                $message = "Today's checklists already exist. Nothing new to generate.";
            } else {
                $message = "No checklists generated.";
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'generated' => $generatedCount,
                'skipped' => $skippedCount,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate checklists: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Send checklist via email
    public function sendChecklistEmail(Employee $employee, DailyChecklist $checklist)
    {
        try {
            // Generate a unique token if not already set
            if (!$checklist->email_token) {
                $checklist->email_token = Str::random(64);
            }

            // Set email_sent_at timestamp BEFORE sending (needed for email template)
            $checklist->email_sent_at = now();
            $checklist->save();

            // Load relationships
            $checklist->load(['template', 'items']);

            // Send email
            Mail::to($employee->email)->send(new DailyChecklistMail($checklist, $employee));

            return response()->json([
                'success' => true,
                'message' => 'Checklist sent successfully to ' . $employee->email,
                'sent_at' => $checklist->email_sent_at->format('g:i A'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to send email: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Public endpoint to toggle item from email (no authentication required)
    public function publicToggleItem(Request $request, $token, DailyChecklistItem $item)
    {
        // Find the checklist by token
        $checklist = DailyChecklist::where('email_token', $token)->first();

        if (!$checklist) {
            abort(404, 'Checklist not found');
        }

        // Check if link has expired (12 hours after email was sent)
        if ($checklist->email_sent_at && $checklist->email_sent_at->copy()->addHours(12)->isPast()) {
            return redirect()->route('checklist.public.view', ['token' => $token])
                ->with('error', 'This checklist link has expired. Links are valid for 12 hours after sending.');
        }

        // Verify that the item belongs to this checklist
        if ($item->daily_checklist_id !== $checklist->id) {
            abort(403, 'Unauthorized');
        }

        // Toggle the item
        $isCompleted = !$item->is_completed;
        $item->update([
            'is_completed' => $isCompleted,
            'completed_at' => $isCompleted ? now() : null,
        ]);

        // Redirect back with a success message
        return redirect()->route('checklist.public.view', ['token' => $token])
            ->with('status', $isCompleted ? 'Item marked as complete!' : 'Item unmarked.');
    }

    // Public view of checklist from email
    public function publicView($token)
    {
        $checklist = DailyChecklist::where('email_token', $token)
            ->with(['template', 'items', 'employee', 'workSubmissions.project'])
            ->firstOrFail();

        // Check if link has expired (12 hours after email was sent)
        $isExpired = $checklist->email_sent_at && $checklist->email_sent_at->copy()->addHours(12)->isPast();

        // Get active projects for dropdown
        $availableProjects = Project::where('status', 'active')->orderBy('name')->get();

        return view('checklist.public', compact('checklist', 'isExpired', 'availableProjects'));
    }

    // Store work submission from public checklist
    public function storeWorkSubmission(Request $request, $token)
    {
        $checklist = DailyChecklist::where('email_token', $token)->firstOrFail();

        // Check if link has expired
        if ($checklist->email_sent_at && $checklist->email_sent_at->copy()->addHours(12)->isPast()) {
            return redirect()->route('checklist.public.view', ['token' => $token])
                ->with('error', 'This checklist link has expired. Links are valid for 12 hours after sending.');
        }

        $validated = $request->validate([
            'project_id' => 'required|exists:projects,id',
            'work_description' => 'required|string|max:1000',
        ]);

        $checklist->workSubmissions()->create([
            'employee_id' => $checklist->employee_id,
            'project_id' => $validated['project_id'],
            'work_description' => $validated['work_description'],
        ]);

        return redirect()->route('checklist.public.view', ['token' => $token])
            ->with('status', 'Work submitted successfully!');
    }

    // Delete work submission from public checklist
    public function deleteWorkSubmission($token, $submissionId)
    {
        $checklist = DailyChecklist::where('email_token', $token)->firstOrFail();

        // Check if link has expired
        if ($checklist->email_sent_at && $checklist->email_sent_at->copy()->addHours(12)->isPast()) {
            return redirect()->route('checklist.public.view', ['token' => $token])
                ->with('error', 'This checklist link has expired. Links are valid for 12 hours after sending.');
        }

        $submission = $checklist->workSubmissions()->findOrFail($submissionId);
        $submission->delete();

        return redirect()->route('checklist.public.view', ['token' => $token])
            ->with('status', 'Work entry deleted successfully!');
    }
}
