<?php

namespace App\Http\Controllers;

use App\Models\ApplicationTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestSubmissionController extends Controller
{
    public function show($token)
    {
        $test = ApplicationTest::where('submission_token', $token)
            ->with(['application', 'application.jobPost'])
            ->firstOrFail();

        // Check if test is expired
        if ($test->isExpired()) {
            return view('test-submission.expired', compact('test'));
        }

        // Check if already submitted
        if ($test->status === 'submitted') {
            return view('test-submission.already-submitted', compact('test'));
        }

        return view('test-submission.show', compact('test'));
    }

    public function submit(Request $request, $token)
    {
        $test = ApplicationTest::where('submission_token', $token)
            ->with(['application', 'application.jobPost'])
            ->firstOrFail();

        // Validate that test can be submitted
        if (!$test->canSubmit()) {
            if ($test->isExpired()) {
                return back()->with('error', 'This test submission deadline has passed.');
            }
            return back()->with('error', 'This test has already been submitted.');
        }

        $validated = $request->validate([
            'submission_file' => 'required|file|mimes:zip,pdf|max:51200', // Only ZIP and PDF, 50MB max
            'submission_notes' => 'nullable|string|max:5000',
        ], [
            'submission_file.required' => 'Please upload your test submission file.',
            'submission_file.mimes' => 'Only ZIP and PDF files are allowed.',
            'submission_file.max' => 'File size must not exceed 50MB.',
        ]);

        // Store the submission file
        if ($request->hasFile('submission_file')) {
            $file = $request->file('submission_file');
            $originalName = $file->getClientOriginalName();

            // Store with sanitized filename to prevent execution
            $sanitizedName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '_', $originalName);
            $path = $file->storeAs('test-submissions', $sanitizedName, 'public');

            $test->update([
                'submission_file_path' => $path,
                'submission_original_name' => $originalName,
                'submission_notes' => $validated['submission_notes'] ?? null,
                'status' => 'submitted',
                'submitted_at' => now(),
            ]);
        }

        return redirect()->route('test.submission.show', $token)
            ->with('success', 'Your test has been submitted successfully! We will review it and get back to you soon.');
    }

    public function downloadTest($token)
    {
        $test = ApplicationTest::where('submission_token', $token)->firstOrFail();

        if (!$test->test_file_path) {
            abort(404, 'Test file not found.');
        }

        $filePath = storage_path('app/public/' . $test->test_file_path);

        if (!file_exists($filePath)) {
            abort(404, 'Test file not found.');
        }

        return response()->download($filePath, $test->test_title . '_test.' . pathinfo($filePath, PATHINFO_EXTENSION));
    }
}
