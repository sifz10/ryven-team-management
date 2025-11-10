<?php

namespace App\Services;

use App\Models\JobApplication;
use App\Models\JobPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Smalot\PdfParser\Parser as PdfParser;

class AIScreeningService
{
    protected $openaiApiKey;
    protected $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->openaiApiKey = config('services.openai.api_key');
    }

    /**
     * Screen a job application using AI
     */
    public function screenApplication(JobApplication $application): array
    {
        try {
            // Extract text from resume if not already extracted
            if (!$application->resume_text) {
                $resumeText = $this->extractTextFromResume($application->resume_path);
                $application->resume_text = $resumeText;
                $application->save();
            }

            // Get job post details
            $jobPost = $application->jobPost;

            // Prepare prompt for AI
            $prompt = $this->buildScreeningPrompt($jobPost, $application);

            // Call OpenAI API
            $response = $this->callOpenAI($prompt);

            // Parse AI response
            $analysis = $this->parseAIResponse($response);

            // Update application with AI results
            $application->update([
                'ai_status' => $analysis['status'],
                'ai_match_score' => $analysis['score'],
                'ai_analysis' => $analysis['details'],
            ]);

            return $analysis;

        } catch (\Exception $e) {
            Log::error('AI Screening Error: ' . $e->getMessage());

            // Fallback: mark as pending for manual review
            $application->update([
                'ai_status' => 'pending',
                'ai_match_score' => null,
                'ai_analysis' => ['error' => 'AI screening failed. Please review manually.'],
            ]);

            return [
                'status' => 'pending',
                'score' => 0,
                'details' => ['error' => 'AI screening failed'],
            ];
        }
    }

    /**
     * Extract text from PDF resume
     */
    protected function extractTextFromResume(string $path): string
    {
        try {
            $fullPath = storage_path('app/' . $path);

            if (!file_exists($fullPath)) {
                return '';
            }

            $parser = new PdfParser();
            $pdf = $parser->parseFile($fullPath);
            $text = $pdf->getText();

            return $text;
        } catch (\Exception $e) {
            Log::error('Resume text extraction error: ' . $e->getMessage());
            return '';
        }
    }

    /**
     * Build the screening prompt for AI
     */
    protected function buildScreeningPrompt(JobPost $jobPost, JobApplication $application): string
    {
        $criteria = $jobPost->ai_screening_criteria ?? [];

        $prompt = "You are an expert HR recruiter analyzing job applications. Please evaluate this candidate's resume against the job requirements.\n\n";

        $prompt .= "JOB DETAILS:\n";
        $prompt .= "Title: {$jobPost->title}\n";
        $prompt .= "Experience Level: {$jobPost->experience_level}\n";
        $prompt .= "Location: {$jobPost->location}\n";

        if ($jobPost->requirements) {
            $prompt .= "\nREQUIREMENTS:\n{$jobPost->requirements}\n";
        }

        if ($jobPost->responsibilities) {
            $prompt .= "\nRESPONSIBILITIES:\n{$jobPost->responsibilities}\n";
        }

        if (!empty($criteria)) {
            $prompt .= "\nSPECIFIC SCREENING CRITERIA:\n";
            foreach ($criteria as $key => $value) {
                $prompt .= "- {$key}: {$value}\n";
            }
        }

        $prompt .= "\n\nCANDIDATE INFORMATION:\n";
        $prompt .= "Name: {$application->full_name}\n";
        $prompt .= "Email: {$application->email}\n";

        if ($application->linkedin_url) {
            $prompt .= "LinkedIn: {$application->linkedin_url}\n";
        }

        if ($application->cover_letter) {
            $prompt .= "\nCOVER LETTER:\n{$application->cover_letter}\n";
        }

        $prompt .= "\n\nRESUME CONTENT:\n";
        $prompt .= substr($application->resume_text, 0, 10000); // Limit to avoid token limits

        $prompt .= "\n\n---\n\n";
        $prompt .= "Please analyze this candidate and provide:\n";
        $prompt .= "1. A match score from 0-100 based on how well they fit the role\n";
        $prompt .= "2. A classification: 'best_match' (90-100), 'good_to_go' (70-89), or 'not_good_fit' (0-69)\n";
        $prompt .= "3. Key strengths (3-5 bullet points)\n";
        $prompt .= "4. Potential concerns or gaps (2-4 bullet points)\n";
        $prompt .= "5. Recommended next steps\n";
        $prompt .= "6. A brief summary (2-3 sentences)\n\n";
        $prompt .= "Format your response as JSON with this structure:\n";
        $prompt .= "{\n";
        $prompt .= '  "score": 85,' . "\n";
        $prompt .= '  "status": "good_to_go",' . "\n";
        $prompt .= '  "strengths": ["point 1", "point 2", ...],' . "\n";
        $prompt .= '  "concerns": ["concern 1", "concern 2", ...],' . "\n";
        $prompt .= '  "next_steps": "Recommended action",' . "\n";
        $prompt .= '  "summary": "Brief overview"' . "\n";
        $prompt .= "}";

        return $prompt;
    }

    /**
     * Call OpenAI API
     */
    protected function callOpenAI(string $prompt): string
    {
        if (!$this->openaiApiKey) {
            throw new \Exception('OpenAI API key not configured');
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->openaiApiKey,
            'Content-Type' => 'application/json',
        ])->timeout(60)->post('https://api.openai.com/v1/chat/completions', [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'You are an expert HR recruiter and talent acquisition specialist with years of experience screening candidates.',
                ],
                [
                    'role' => 'user',
                    'content' => $prompt,
                ],
            ],
            'temperature' => 0.7,
            'max_tokens' => 1500,
        ]);

        if (!$response->successful()) {
            throw new \Exception('OpenAI API request failed: ' . $response->body());
        }

        $data = $response->json();
        return $data['choices'][0]['message']['content'] ?? '';
    }

    /**
     * Parse AI response
     */
    protected function parseAIResponse(string $response): array
    {
        try {
            // Try to extract JSON from response
            if (preg_match('/\{[\s\S]*\}/', $response, $matches)) {
                $json = json_decode($matches[0], true);

                if ($json && isset($json['score']) && isset($json['status'])) {
                    return [
                        'score' => (int) $json['score'],
                        'status' => $json['status'],
                        'details' => $json,
                    ];
                }
            }

            // Fallback parsing if JSON extraction fails
            return [
                'score' => 50,
                'status' => 'good_to_go',
                'details' => [
                    'summary' => $response,
                    'raw_response' => $response,
                ],
            ];

        } catch (\Exception $e) {
            Log::error('AI Response parsing error: ' . $e->getMessage());

            return [
                'score' => 50,
                'status' => 'pending',
                'details' => [
                    'error' => 'Could not parse AI response',
                    'raw_response' => $response,
                ],
            ];
        }
    }

    /**
     * Batch screen multiple applications
     */
    public function batchScreen(array $applicationIds): array
    {
        $results = [];

        foreach ($applicationIds as $id) {
            $application = JobApplication::find($id);

            if ($application && $application->ai_status === 'pending') {
                $results[$id] = $this->screenApplication($application);
            }
        }

        return $results;
    }
}
