<?php

namespace App\Services;

use App\Models\PostGeneration;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialPostGenerationService
{
    private string $apiKey;
    private string $model = 'gpt-4o-mini';

    public function __construct()
    {
        $this->apiKey = config('services.openai.api_key');
    }

    /**
     * Generate viral social media post content
     */
    public function generatePost(SocialPost $post, string $platform): PostGeneration
    {
        $prompt = $this->buildPrompt($post->title, $post->description, $platform);
        
        try {
            $response = Http::timeout(60)
                ->withHeaders([
                    'Authorization' => 'Bearer ' . $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                ->post('https://api.openai.com/v1/chat/completions', [
                    'model' => $this->model,
                    'messages' => [
                        [
                            'role' => 'system',
                            'content' => $this->getSystemPrompt($platform)
                        ],
                        [
                            'role' => 'user',
                            'content' => $prompt
                        ]
                    ],
                    'temperature' => 0.8,
                    'max_tokens' => 800,
                ]);

            if ($response->failed()) {
                throw new \Exception('OpenAI API request failed: ' . $response->body());
            }

            $data = $response->json();
            $generatedContent = $data['choices'][0]['message']['content'] ?? '';

            // Store the generation
            return PostGeneration::create([
                'social_post_id' => $post->id,
                'platform' => $platform,
                'title' => $post->title,
                'description' => $post->description,
                'generated_content' => $generatedContent,
                'generation_metadata' => [
                    'model' => $this->model,
                    'tokens_used' => $data['usage']['total_tokens'] ?? 0,
                    'finish_reason' => $data['choices'][0]['finish_reason'] ?? 'unknown',
                    'generated_at' => now()->toDateTimeString(),
                ],
                'is_selected' => false,
            ]);

        } catch (\Exception $e) {
            Log::error('Post generation failed', [
                'post_id' => $post->id,
                'platform' => $platform,
                'error' => $e->getMessage()
            ]);
            
            throw $e;
        }
    }

    /**
     * Build the user prompt
     */
    private function buildPrompt(string $title, ?string $description, string $platform): string
    {
        $prompt = "Create a viral social media post for {$platform}.\n\n";
        $prompt .= "Topic: {$title}\n";
        
        if ($description) {
            $prompt .= "Additional Context: {$description}\n";
        }
        
        $prompt .= "\nGenerate an engaging post following this structure:\n";
        $prompt .= "Please do not use any bold or italics or other formatting also use line breaks for readability and consistency.\n\n";
        $prompt .= "Do not use image links.\n\n";
        $prompt .= "Do not use emojis.\n\n";
        $prompt .= "Use voice tone more casual and conversational.\n\n";
        $prompt .= "1. Hook: Attention-grabbing opening line\n";
        $prompt .= "2. Problem: Identify the pain point or challenge\n";
        $prompt .= "3. Value: Provide actionable insights or solution\n";
        $prompt .= "4. CTA: Clear call-to-action\n\n";
        $prompt .= "Make it conversational, authentic, and optimized for {$platform}.";
        
        return $prompt;
    }

    /**
     * Get system prompt based on platform
     */
    private function getSystemPrompt(string $platform): string
    {
        $basePrompt = "You are an expert social media content creator specializing in viral content. ";
        
        return $basePrompt . match($platform) {
            'linkedin' => "For LinkedIn, create professional yet engaging content. Use industry insights, statistics, and thought leadership. Keep it between 150-300 words. Use line breaks for readability. Include relevant hashtags (3-5). Maintain a professional but conversational tone.",
            
            'facebook' => "For Facebook, create engaging, shareable content. Be conversational and relatable. Use emojis naturally (2-3). Keep it concise (100-200 words). Include a question or poll to encourage engagement. Use 2-3 relevant hashtags.",
            
            'twitter' => "For Twitter (X), create punchy, concise content. Maximum 280 characters. Use strong hooks. Include 1-2 relevant hashtags. Make it quotable and retweet-worthy. Be bold and direct.",
            
            default => "Create engaging social media content with a hook, problem identification, value proposition, and call-to-action.",
        };
    }

    /**
     * Generate multiple variations
     */
    public function generateVariations(SocialPost $post, string $platform, int $count = 3): array
    {
        $generations = [];
        
        for ($i = 0; $i < $count; $i++) {
            try {
                $generations[] = $this->generatePost($post, $platform);
                
                // Small delay between requests to avoid rate limiting
                if ($i < $count - 1) {
                    usleep(500000); // 0.5 seconds
                }
            } catch (\Exception $e) {
                Log::warning("Failed to generate variation " . ($i + 1), [
                    'post_id' => $post->id,
                    'error' => $e->getMessage()
                ]);
            }
        }
        
        return $generations;
    }

    /**
     * Regenerate content for an existing generation
     */
    public function regenerate(PostGeneration $generation): PostGeneration
    {
        $post = $generation->socialPost;
        return $this->generatePost($post, $generation->platform);
    }
}
