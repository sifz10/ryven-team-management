<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TestInvitationMail;
use App\Models\ApplicationTest;
use App\Models\JobApplication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApplicationTestController extends Controller
{
    /**
     * Generate test content using AI
     */
    public function generateWithAI(Request $request, JobApplication $application)
    {
        try {
            $validated = $request->validate([
                'test_type' => 'nullable|string|max:255',
            ]);

            $job = $application->jobPost;

            if (!$job) {
                Log::error('Job post not found for application: ' . $application->id);
                return response()->json([
                    'success' => false,
                    'message' => 'Job post not found'
                ], 404);
            }

            $testType = $validated['test_type'] ?? 'Technical Assessment';

            // Build AI prompt
            $prompt = $this->buildAIPrompt($job, $application, $testType);

            // Call OpenAI API
            $apiKey = config('services.openai.api_key');
            if (!$apiKey) {
                Log::warning('OpenAI API key not configured');
                return response()->json([
                    'success' => false,
                    'message' => 'OpenAI API key not configured. Please add OPENAI_API_KEY to your .env file.'
                ], 500);
            }

            // Disable SSL verification in local environment (Windows fix)
            $httpClient = Http::timeout(30);

            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withoutVerifying();
            }

            $response = $httpClient
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => 'gpt-4o-mini',
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => 'You are an expert recruiter and technical assessor. Generate comprehensive, practical tests for job candidates.'
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.7,
                    'max_tokens' => 2000,
                ]);

            if (!$response->successful()) {
                $errorBody = $response->body();
                Log::error('OpenAI API error', [
                    'status' => $response->status(),
                    'body' => $errorBody
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'OpenAI API error: ' . $response->status() . '. Please check your API key and quota.'
                ], 500);
            }

            $result = $response->json();
            $generatedContent = $result['choices'][0]['message']['content'] ?? '';

            if (empty($generatedContent)) {
                Log::warning('Empty content from OpenAI', ['result' => $result]);
                return response()->json([
                    'success' => false,
                    'message' => 'AI returned empty content. Please try again.'
                ], 500);
            }

            // Parse the generated content
            $parsed = $this->parseAIResponse($generatedContent);

            return response()->json([
                'success' => true,
                'data' => $parsed
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error: ' . $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Test generation error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Build AI prompt for test generation
     */
    private function buildAIPrompt($job, $application, $testType)
    {
        $prompt = "Generate a {$testType} for the following job position:\n\n";
        $prompt .= "Job Title: {$job->title}\n";
        $prompt .= "Job Type: {$job->job_type}\n";
        $prompt .= "Experience Level: {$job->experience_level}\n\n";

        if ($job->description) {
            $prompt .= "Job Description:\n{$job->description}\n\n";
        }

        if ($job->requirements) {
            $prompt .= "Requirements:\n{$job->requirements}\n\n";
        }

        if ($job->responsibilities) {
            $prompt .= "Responsibilities:\n{$job->responsibilities}\n\n";
        }

        $prompt .= "Candidate Name: {$application->full_name}\n\n";

        $prompt .= "Generate a comprehensive test with the following EXACT structure. Do NOT use markdown formatting, asterisks, or special characters. Use plain text only:\n\n";
        $prompt .= "TITLE: [A clear, professional test title without any special formatting]\n\n";
        $prompt .= "DESCRIPTION: [A brief 2-3 sentence overview of what the test covers. Write in plain text, no bold or italic formatting.]\n\n";
        $prompt .= "INSTRUCTIONS: [Detailed step-by-step instructions for the candidate. Format as plain text with clear numbered lists (1., 2., 3.) or bullet points using hyphens (-). Include:\n";
        $prompt .= "- What they need to do\n";
        $prompt .= "- Time expectations (2-4 hours typical)\n";
        $prompt .= "- What to submit\n";
        $prompt .= "- Evaluation criteria\n";
        $prompt .= "- Any specific requirements or constraints]\n\n";
        $prompt .= "IMPORTANT FORMATTING RULES:\n";
        $prompt .= "- Use plain text only\n";
        $prompt .= "- NO asterisks (*) for bold or emphasis\n";
        $prompt .= "- NO markdown symbols (#, **, __, etc.)\n";
        $prompt .= "- Use numbered lists (1., 2., 3.) or simple hyphens (-) for bullet points\n";
        $prompt .= "- Keep paragraphs clear with line breaks\n";
        $prompt .= "- Write professionally but without formatting markup\n\n";
        $prompt .= "Make the test practical, relevant to the role, and clearly defined.";

        return $prompt;
    }

    /**
     * Parse AI response into structured data
     */
    private function parseAIResponse($content)
    {
        $title = '';
        $description = '';
        $instructions = '';

        // Extract TITLE
        if (preg_match('/TITLE:\s*(.+?)(?=\n\n|DESCRIPTION:|$)/s', $content, $matches)) {
            $title = trim($matches[1]);
        }

        // Extract DESCRIPTION
        if (preg_match('/DESCRIPTION:\s*(.+?)(?=\n\n|INSTRUCTIONS:|$)/s', $content, $matches)) {
            $description = trim($matches[1]);
        }

        // Extract INSTRUCTIONS
        if (preg_match('/INSTRUCTIONS:\s*(.+?)$/s', $content, $matches)) {
            $instructions = trim($matches[1]);
        }

        // Fallback: if parsing fails, use the whole content as instructions
        if (empty($title) && empty($description) && empty($instructions)) {
            $lines = explode("\n", $content);
            $title = $lines[0] ?? 'Technical Assessment';
            $instructions = $content;
        }

        // Clean up markdown formatting from all fields
        $title = $this->cleanMarkdown($title);
        $description = $this->cleanMarkdown($description);
        $instructions = $this->cleanMarkdown($instructions);

        return [
            'title' => $title ?: 'Technical Assessment',
            'description' => $description,
            'instructions' => $instructions ?: $content,
        ];
    }

    /**
     * Remove markdown formatting and clean up text
     */
    private function cleanMarkdown($text)
    {
        if (empty($text)) {
            return $text;
        }

        // Remove bold (**text** or __text__)
        $text = preg_replace('/\*\*(.+?)\*\*/', '$1', $text);
        $text = preg_replace('/__(.+?)__/', '$1', $text);

        // Remove italic (*text* or _text_)
        $text = preg_replace('/\*(.+?)\*/', '$1', $text);
        $text = preg_replace('/_(.+?)_/', '$1', $text);

        // Remove heading markers (# ## ###)
        $text = preg_replace('/^#{1,6}\s+/m', '', $text);

        // Remove code blocks (``` or `)
        $text = preg_replace('/```.*?```/s', '', $text);
        $text = preg_replace('/`(.+?)`/', '$1', $text);

        // Remove horizontal rules (---, ___, ***)
        $text = preg_replace('/^[\-_*]{3,}$/m', '', $text);

        // Clean up multiple asterisks or underscores
        $text = preg_replace('/\*{2,}/', '', $text);
        $text = preg_replace('/_{2,}/', '', $text);

        // Clean up extra whitespace
        $text = preg_replace('/\n{3,}/', "\n\n", $text);

        return trim($text);
    }

    /**
     * Store a newly created test and send to candidate
     */
    public function store(Request $request, JobApplication $application)
    {
        $validated = $request->validate([
            'test_title' => 'required|string|max:255',
            'test_description' => 'nullable|string',
            'test_instructions' => 'required|string',
            'deadline' => 'required|date|after:now',
            'test_file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240', // 10MB
        ]);

        // Handle test file upload
        $testFilePath = null;
        if ($request->hasFile('test_file')) {
            $testFilePath = $request->file('test_file')->store('test-files');
        }

        // Create test record
        $test = ApplicationTest::create([
            'job_application_id' => $application->id,
            'test_title' => $validated['test_title'],
            'test_description' => $validated['test_description'] ?? null,
            'test_instructions' => $validated['test_instructions'],
            'deadline' => $validated['deadline'],
            'test_file_path' => $testFilePath,
            'status' => 'sent',
            'sent_at' => now(),
            'sent_by' => Auth::id(),
        ]);

        // Send email to candidate
        try {
            Mail::to($application->email)->send(new TestInvitationMail($test, $application));

            return redirect()
                ->route('admin.applications.show', $application)
                ->with('success', "Test sent successfully to {$application->full_name}!");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.applications.show', $application)
                ->with('error', 'Test created but email failed to send: ' . $e->getMessage());
        }
    }

    /**
     * Update an existing test
     */
    public function update(Request $request, JobApplication $application, ApplicationTest $test)
    {
        $validated = $request->validate([
            'test_title' => 'required|string|max:255',
            'test_description' => 'nullable|string',
            'test_instructions' => 'required|string',
            'deadline' => 'required|date',
            'test_file' => 'nullable|file|mimes:pdf,doc,docx,zip|max:10240',
        ]);

        // Handle new test file upload
        if ($request->hasFile('test_file')) {
            // Delete old file if exists
            if ($test->test_file_path) {
                Storage::delete($test->test_file_path);
            }
            $validated['test_file_path'] = $request->file('test_file')->store('test-files');
        }

        $test->update([
            'test_title' => $validated['test_title'],
            'test_description' => $validated['test_description'] ?? null,
            'test_instructions' => $validated['test_instructions'],
            'deadline' => $validated['deadline'],
            'test_file_path' => $validated['test_file_path'] ?? $test->test_file_path,
        ]);

        return redirect()
            ->route('admin.applications.show', $application)
            ->with('success', 'Test updated successfully!');
    }

    /**
     * Resend test email to candidate
     */
    public function sendEmail(Request $request, JobApplication $application, ApplicationTest $test)
    {
        try {
            Mail::to($application->email)->send(new TestInvitationMail($test, $application));

            return redirect()
                ->route('admin.applications.show', $application)
                ->with('success', "Test reminder sent to {$application->full_name}!");
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.applications.show', $application)
                ->with('error', 'Failed to send test email: ' . $e->getMessage());
        }
    }
}
