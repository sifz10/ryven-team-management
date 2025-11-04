<?php

namespace App\Services;

use App\Models\SocialAccount;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialMediaPublishingService
{
    /**
     * Publish post to the specified platform
     */
    public function publish(SocialPost $post): bool
    {
        $account = $post->socialAccount;
        
        if (!$account || !$account->is_active) {
            throw new \Exception('Social account not available or inactive');
        }

        if ($account->isTokenExpired()) {
            throw new \Exception('Access token expired. Please reconnect the account.');
        }

        $content = $post->final_content ?? $post->content;
        
        if (!$content) {
            throw new \Exception('No content to publish');
        }

        try {
            $result = match($account->platform) {
                'linkedin' => $this->publishToLinkedIn($account, $content),
                'facebook' => $this->publishToFacebook($account, $content),
                'twitter' => $this->publishToTwitter($account, $content),
                default => throw new \Exception('Unsupported platform: ' . $account->platform),
            };

            // Update post status
            $post->update([
                'status' => 'posted',
                'posted_at' => now(),
                'platform_post_id' => $result['post_id'] ?? null,
                'platform_response' => $result,
                'error_message' => null,
            ]);

            Log::info('Post published successfully', [
                'post_id' => $post->id,
                'platform' => $account->platform,
                'platform_post_id' => $result['post_id'] ?? null,
            ]);

            return true;

        } catch (\Exception $e) {
            // Update post with error
            $post->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
                'retry_count' => $post->retry_count + 1,
            ]);

            Log::error('Post publishing failed', [
                'post_id' => $post->id,
                'platform' => $account->platform,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Publish to LinkedIn
     */
    private function publishToLinkedIn(SocialAccount $account, string $content): array
    {
        $response = Http::withToken($account->access_token)
            ->post('https://api.linkedin.com/v2/ugcPosts', [
                'author' => 'urn:li:person:' . $account->platform_user_id,
                'lifecycleState' => 'PUBLISHED',
                'specificContent' => [
                    'com.linkedin.ugc.ShareContent' => [
                        'shareCommentary' => [
                            'text' => $content
                        ],
                        'shareMediaCategory' => 'NONE'
                    ]
                ],
                'visibility' => [
                    'com.linkedin.ugc.MemberNetworkVisibility' => 'PUBLIC'
                ]
            ]);

        if ($response->failed()) {
            throw new \Exception('LinkedIn API error: ' . $response->body());
        }

        return [
            'post_id' => $response->header('X-RestLi-Id'),
            'response' => $response->json(),
        ];
    }

    /**
     * Publish to Facebook
     */
    private function publishToFacebook(SocialAccount $account, string $content): array
    {
        $pageId = $account->platform_user_id;
        
        $response = Http::post("https://graph.facebook.com/v18.0/{$pageId}/feed", [
            'message' => $content,
            'access_token' => $account->access_token,
        ]);

        if ($response->failed()) {
            throw new \Exception('Facebook API error: ' . $response->body());
        }

        $data = $response->json();
        
        return [
            'post_id' => $data['id'] ?? null,
            'response' => $data,
        ];
    }

    /**
     * Publish to Twitter
     */
    private function publishToTwitter(SocialAccount $account, string $content): array
    {
        // Twitter API v2 requires OAuth 1.0a or OAuth 2.0 with PKCE
        // This is a simplified version - you'll need to implement proper OAuth
        
        $response = Http::withToken($account->access_token)
            ->post('https://api.twitter.com/2/tweets', [
                'text' => $content,
            ]);

        if ($response->failed()) {
            throw new \Exception('Twitter API error: ' . $response->body());
        }

        $data = $response->json();
        
        return [
            'post_id' => $data['data']['id'] ?? null,
            'response' => $data,
        ];
    }

    /**
     * Test connection to a social account
     */
    public function testConnection(SocialAccount $account): bool
    {
        try {
            return match($account->platform) {
                'linkedin' => $this->testLinkedInConnection($account),
                'facebook' => $this->testFacebookConnection($account),
                'twitter' => $this->testTwitterConnection($account),
                default => false,
            };
        } catch (\Exception $e) {
            Log::error('Connection test failed', [
                'account_id' => $account->id,
                'platform' => $account->platform,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    private function testLinkedInConnection(SocialAccount $account): bool
    {
        $response = Http::withToken($account->access_token)
            ->get('https://api.linkedin.com/v2/userinfo');
        
        return $response->successful();
    }

    private function testFacebookConnection(SocialAccount $account): bool
    {
        $response = Http::get('https://graph.facebook.com/v18.0/me', [
            'access_token' => $account->access_token,
        ]);
        
        return $response->successful();
    }

    private function testTwitterConnection(SocialAccount $account): bool
    {
        $response = Http::withToken($account->access_token)
            ->get('https://api.twitter.com/2/users/me');
        
        return $response->successful();
    }
}
