<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TestOpenAI extends Command
{
    protected $signature = 'test:openai';
    protected $description = 'Test OpenAI API connection';

    public function handle()
    {
        $this->info('Testing OpenAI API connection...');

        $apiKey = config('services.openai.api_key');

        if (!$apiKey) {
            $this->error('❌ OpenAI API key not found!');
            $this->info('Please add OPENAI_API_KEY to your .env file');
            return 1;
        }

        $this->info('✓ API key found: ' . substr($apiKey, 0, 10) . '...');

        try {
            $this->info('Making test API call...');

            $httpClient = Http::timeout(30);

            if (config('app.env') === 'local') {
                $httpClient = $httpClient->withoutVerifying();
                $this->info('✓ SSL verification disabled (local environment)');
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
                            'role' => 'user',
                            'content' => 'Say "Hello" in one word.'
                        ]
                    ],
                    'max_tokens' => 10,
                ]);

            if ($response->successful()) {
                $result = $response->json();
                $content = $result['choices'][0]['message']['content'] ?? '';

                $this->info('✓ API call successful!');
                $this->info('Response: ' . $content);
                $this->info('✓ OpenAI integration is working correctly!');
                return 0;
            } else {
                $this->error('❌ API call failed!');
                $this->error('Status: ' . $response->status());
                $this->error('Response: ' . $response->body());

                if ($response->status() === 401) {
                    $this->warn('This looks like an authentication error. Please check your API key.');
                } elseif ($response->status() === 429) {
                    $this->warn('Rate limit exceeded or insufficient quota. Check your OpenAI account.');
                }

                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
