<?php

namespace App\Http\Controllers;

use App\Models\ChecklistTemplate;
use App\Models\ChecklistTemplateItem;
use App\Models\DailyChecklist;
use App\Models\DailyChecklistItem;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
}
