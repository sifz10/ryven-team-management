<?php

namespace App\Http\Controllers;

use App\Models\UatProject;
use App\Models\UatUser;
use App\Models\UatTestCase;
use App\Models\UatFeedback;
use App\Mail\UatInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;

class UatPublicController extends Controller
{
    public function view(Request $request, $token)
    {
        $project = UatProject::where('unique_token', $token)
            ->with(['testCases.feedbacks.user', 'users'])
            ->firstOrFail();

        // Get or create UAT user based on email in session
        $uatUser = null;
        if ($request->session()->has('uat_user_email_' . $project->id)) {
            $email = $request->session()->get('uat_user_email_' . $project->id);
            $uatUser = $project->users()->where('email', $email)->first();
            
            if ($uatUser) {
                $uatUser->update(['last_accessed_at' => now()]);
            }
        }

        return view('uat.public.view', compact('project', 'uatUser'));
    }

    public function getUpdates(Request $request, $token)
    {
        $project = UatProject::where('unique_token', $token)
            ->with(['testCases.feedbacks.user', 'users'])
            ->firstOrFail();

        // Get authenticated user
        $uatUser = null;
        if ($request->session()->has('uat_user_email_' . $project->id)) {
            $email = $request->session()->get('uat_user_email_' . $project->id);
            $uatUser = $project->users()->where('email', $email)->first();
        }

        if (!$uatUser) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }

        // Prepare test cases data
        $testCases = $project->testCases->map(function ($testCase) use ($uatUser) {
            $userFeedback = $testCase->feedbacks->where('uat_user_id', $uatUser->id)->first();
            
            return [
                'id' => $testCase->id,
                'title' => $testCase->title,
                'feedbacks_count' => $testCase->feedbacks->groupBy('status')->map->count()->toArray(),
                'user_feedback' => $userFeedback ? [
                    'status' => $userFeedback->status,
                    'comment' => $userFeedback->comment,
                ] : null,
            ];
        });

        return response()->json([
            'test_cases' => $testCases,
            'users_count' => $project->users->count(),
            'project_status' => $project->status,
        ]);
    }

    public function authenticate(Request $request, $token)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $project = UatProject::where('unique_token', $token)->firstOrFail();
        
        $uatUser = $project->users()->where('email', $validated['email'])->first();

        if (!$uatUser) {
            return back()->with('error', 'You are not authorized to access this UAT project.');
        }

        $request->session()->put('uat_user_email_' . $project->id, $validated['email']);
        $uatUser->update(['last_accessed_at' => now()]);

        return redirect()->route('uat.public.view', $token)
            ->with('status', 'Welcome, ' . $uatUser->name . '!');
    }

    public function submitFeedback(Request $request, $token, UatTestCase $testCase)
    {
        $project = UatProject::where('unique_token', $token)->firstOrFail();
        
        // Check if user is authenticated
        if (!$request->session()->has('uat_user_email_' . $project->id)) {
            return back()->with('error', 'Please authenticate first.');
        }

        $email = $request->session()->get('uat_user_email_' . $project->id);
        $uatUser = $project->users()->where('email', $email)->firstOrFail();

        $validated = $request->validate([
            'comment' => 'nullable|string',
            'status' => 'required|in:pending,passed,failed,blocked',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,gif,pdf,doc,docx',
        ]);

        $attachmentPath = null;
        if ($request->hasFile('attachment')) {
            $attachmentPath = $request->file('attachment')->store('uat-attachments', 'public');
        }

        UatFeedback::updateOrCreate(
            [
                'uat_test_case_id' => $testCase->id,
                'uat_user_id' => $uatUser->id,
            ],
            [
                'comment' => $validated['comment'],
                'status' => $validated['status'],
                'attachment_path' => $attachmentPath ?? null,
            ]
        );

        return back()->with('status', 'Feedback submitted successfully!');
    }

    public function updateStatus(Request $request, $token, UatTestCase $testCase)
    {
        $project = UatProject::where('unique_token', $token)->firstOrFail();
        
        // Check if user is authenticated
        if (!$request->session()->has('uat_user_email_' . $project->id)) {
            return back()->with('error', 'Please authenticate first.');
        }

        $email = $request->session()->get('uat_user_email_' . $project->id);
        $uatUser = $project->users()->where('email', $email)->firstOrFail();

        $validated = $request->validate([
            'status' => 'required|in:pending,passed,failed,blocked',
        ]);

        UatFeedback::updateOrCreate(
            [
                'uat_test_case_id' => $testCase->id,
                'uat_user_id' => $uatUser->id,
            ],
            [
                'status' => $validated['status'],
            ]
        );

        return back()->with('status', 'Status updated successfully!');
    }

    public function storeTestCase(Request $request, $token)
    {
        $project = UatProject::where('unique_token', $token)->firstOrFail();
        
        // Check if user is authenticated
        if (!$request->session()->has('uat_user_email_' . $project->id)) {
            return back()->with('error', 'Please authenticate first.');
        }

        $email = $request->session()->get('uat_user_email_' . $project->id);
        $uatUser = $project->users()->where('email', $email)->firstOrFail();

        // Only internal users can create test cases
        if ($uatUser->role !== 'internal') {
            return back()->with('error', 'You do not have permission to create test cases.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $lastOrder = $project->testCases()->max('order') ?? 0;

        // We need to get the actual User ID for created_by
        // For now, we'll use a placeholder or the first admin user
        $adminUser = \App\Models\User::first();

        UatTestCase::create([
            'uat_project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'steps' => $validated['steps'],
            'expected_result' => $validated['expected_result'],
            'priority' => $validated['priority'],
            'order' => $lastOrder + 1,
            'created_by' => $adminUser->id, // Using admin user as creator
        ]);

        return back()->with('status', 'Test case created successfully!');
    }

    public function addUser(Request $request, $token)
    {
        $project = UatProject::where('unique_token', $token)->firstOrFail();
        
        // Check if user is authenticated
        if (!$request->session()->has('uat_user_email_' . $project->id)) {
            return back()->with('error', 'Please authenticate first.');
        }

        $email = $request->session()->get('uat_user_email_' . $project->id);
        $uatUser = $project->users()->where('email', $email)->firstOrFail();

        // Only internal users can invite other users
        if ($uatUser->role !== 'internal') {
            return back()->with('error', 'You do not have permission to invite users.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:internal,external',
        ]);

        // Check if user already exists in this project
        $existingUser = $project->users()->where('email', $validated['email'])->first();
        
        if ($existingUser) {
            return back()->with('error', 'A user with this email is already part of this project.');
        }

        $newUser = UatUser::create([
            'uat_project_id' => $project->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Send invitation email
        try {
            Mail::to($newUser->email)->send(new UatInvitation($project, $newUser, $uatUser));
        } catch (\Exception $e) {
            // Log the error but don't fail the invitation
            \Log::error('Failed to send UAT invitation email: ' . $e->getMessage());
        }

        return back()->with('status', 'User invited successfully! An invitation email has been sent to ' . $newUser->email);
    }
}

