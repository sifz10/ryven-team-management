<?php

namespace App\Http\Controllers;

use App\Models\UatProject;
use App\Models\UatUser;
use App\Models\UatTestCase;
use App\Mail\UatInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class UatProjectController extends Controller
{
    public function index()
    {
        $projects = UatProject::with('creator', 'users', 'testCases')
            ->latest()
            ->paginate(10);

        return view('uat.index', compact('projects'));
    }

    public function create()
    {
        return view('uat.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'deadline' => 'nullable|date',
            'users' => 'required|array|min:1',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email',
            'users.*.role' => 'required|in:internal,external',
        ]);

        $project = UatProject::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'created_by' => Auth::id(),
            'deadline' => $validated['deadline'] ?? null,
        ]);

        // Create a dummy UAT user representing the admin
        $adminUatUser = new UatUser([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => 'internal'
        ]);

        foreach ($validated['users'] as $userData) {
            $newUser = UatUser::create([
                'uat_project_id' => $project->id,
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => $userData['role'],
            ]);

            // Send invitation email to each user
            try {
                Mail::to($newUser->email)->send(new UatInvitation($project, $newUser, $adminUatUser));
            } catch (\Exception $e) {
                \Log::error('Failed to send UAT invitation email: ' . $e->getMessage());
            }
        }

        return redirect()->route('uat.show', $project)
            ->with('status', 'UAT Project created successfully! Invitation emails have been sent to all users.');
    }

    public function show(UatProject $project)
    {
        $project->load(['users', 'testCases.feedbacks.user', 'creator']);

        return view('uat.show', compact('project'));
    }

    public function edit(UatProject $project)
    {
        $project->load('users');
        return view('uat.edit', compact('project'));
    }

    public function update(Request $request, UatProject $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,completed,cancelled',
            'deadline' => 'nullable|date',
            'users' => 'required|array|min:1',
            'users.*.name' => 'required|string|max:255',
            'users.*.email' => 'required|email',
            'users.*.role' => 'required|in:internal,external',
        ]);

        $project->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'status' => $validated['status'],
            'deadline' => $validated['deadline'] ?? null,
        ]);

        // Track existing user emails
        $existingEmails = $project->users()->pluck('email')->toArray();

        // Update users - remove old ones and add new ones
        $project->users()->delete();

        // Create a dummy UAT user representing the admin
        $adminUatUser = new UatUser([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => 'internal'
        ]);

        foreach ($validated['users'] as $userData) {
            $newUser = UatUser::create([
                'uat_project_id' => $project->id,
                'name' => $userData['name'],
                'email' => $userData['email'],
                'role' => $userData['role'],
            ]);

            // Only send email to newly added users
            if (!in_array($userData['email'], $existingEmails)) {
                try {
                    Mail::to($newUser->email)->send(new UatInvitation($project, $newUser, $adminUatUser));
                } catch (\Exception $e) {
                    \Log::error('Failed to send UAT invitation email: ' . $e->getMessage());
                }
            }
        }

        return redirect()->route('uat.show', $project)
            ->with('status', 'UAT Project updated successfully!');
    }

    public function destroy(UatProject $project)
    {
        $project->delete();
        
        return redirect()->route('uat.index')
            ->with('status', 'UAT Project deleted successfully!');
    }

    public function storeTestCase(Request $request, UatProject $project)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $lastOrder = $project->testCases()->max('order') ?? 0;

        UatTestCase::create([
            'uat_project_id' => $project->id,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'steps' => $validated['steps'],
            'expected_result' => $validated['expected_result'],
            'priority' => $validated['priority'],
            'order' => $lastOrder + 1,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('uat.show', $project)
            ->with('status', 'Test case created successfully!');
    }

    public function updateTestCase(Request $request, UatProject $project, UatTestCase $testCase)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'steps' => 'nullable|string',
            'expected_result' => 'nullable|string',
            'priority' => 'required|in:low,medium,high,critical',
        ]);

        $testCase->update($validated);

        return redirect()->route('uat.show', $project)
            ->with('status', 'Test case updated successfully!');
    }

    public function destroyTestCase(UatProject $project, UatTestCase $testCase)
    {
        $testCase->delete();
        
        return redirect()->route('uat.show', $project)
            ->with('status', 'Test case deleted successfully!');
    }

    public function addUser(Request $request, UatProject $project)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'role' => 'required|in:internal,external',
        ]);

        // Check if user already exists
        $existingUser = $project->users()->where('email', $validated['email'])->first();
        
        if ($existingUser) {
            return back()->with('error', 'User with this email already exists in this project.');
        }

        $newUser = UatUser::create([
            'uat_project_id' => $project->id,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        // Create a dummy UAT user representing the admin
        $adminUatUser = new UatUser([
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'role' => 'internal'
        ]);

        // Send invitation email
        try {
            Mail::to($newUser->email)->send(new UatInvitation($project, $newUser, $adminUatUser));
        } catch (\Exception $e) {
            \Log::error('Failed to send UAT invitation email: ' . $e->getMessage());
        }

        return redirect()->route('uat.show', $project)
            ->with('status', 'User added successfully! An invitation email has been sent.');
    }

    public function removeUser(UatProject $project, UatUser $user)
    {
        $user->delete();
        
        return redirect()->route('uat.show', $project)
            ->with('status', 'User removed successfully!');
    }
}

