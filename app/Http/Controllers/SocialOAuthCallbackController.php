<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SocialOAuthCallbackController extends Controller
{
    /**
     * Handle LinkedIn OAuth callback
     */
    public function linkedin(Request $request)
    {
        // Verify state to prevent CSRF
        if ($request->state !== session('linkedin_oauth_state')) {
            return redirect()->route('social.accounts.index')
                ->with('error', 'Invalid OAuth state. Please try again.');
        }

        if ($request->has('error')) {
            return redirect()->route('social.accounts.index')
                ->with('error', 'LinkedIn authorization failed: ' . $request->error_description);
        }

        try {
            // Exchange code for access token
            $response = Http::asForm()->post('https://www.linkedin.com/oauth/v2/accessToken', [
                'grant_type' => 'authorization_code',
                'code' => $request->code,
                'redirect_uri' => config('services.linkedin.redirect'),
                'client_id' => config('services.linkedin.client_id'),
                'client_secret' => config('services.linkedin.client_secret'),
            ]);

            if ($response->failed()) {
                throw new \Exception('Failed to get access token: ' . $response->body());
            }

            $tokenData = $response->json();
            
            Log::info('LinkedIn token received', [
                'has_access_token' => isset($tokenData['access_token']),
                'expires_in' => $tokenData['expires_in'] ?? 'not set',
                'token_type' => $tokenData['token_type'] ?? 'not set'
            ]);
            
            // Try to get user profile - LinkedIn may require different endpoints
            // Try userinfo first (OpenID Connect)
            $profileResponse = Http::withToken($tokenData['access_token'])
                ->get('https://api.linkedin.com/v2/userinfo');

            if ($profileResponse->failed()) {
                Log::error('LinkedIn userinfo failed, trying v2/me', [
                    'status' => $profileResponse->status(),
                    'body' => $profileResponse->body(),
                ]);
                
                // Fallback to v2/me endpoint
                $profileResponse = Http::withToken($tokenData['access_token'])
                    ->get('https://api.linkedin.com/v2/me');
                
                if ($profileResponse->failed()) {
                    $errorBody = $profileResponse->body();
                    Log::error('LinkedIn v2/me also failed', [
                        'status' => $profileResponse->status(),
                        'body' => $errorBody,
                    ]);
                    throw new \Exception('Failed to get user profile from both endpoints. Status: ' . $profileResponse->status() . '. Error: ' . $errorBody);
                }
            }

            $profile = $profileResponse->json();
            
            Log::info('LinkedIn profile received', [
                'profile_keys' => array_keys($profile),
                'profile_data' => $profile
            ]);

            // Extract user information - handle both formats
            $userId = $profile['sub'] ?? $profile['id'] ?? null;
            
            // Handle different name formats
            if (isset($profile['name'])) {
                $userName = $profile['name'];
            } elseif (isset($profile['localizedFirstName']) && isset($profile['localizedLastName'])) {
                $userName = $profile['localizedFirstName'] . ' ' . $profile['localizedLastName'];
            } elseif (isset($profile['given_name']) && isset($profile['family_name'])) {
                $userName = $profile['given_name'] . ' ' . $profile['family_name'];
            } elseif (isset($profile['email'])) {
                $userName = $profile['email'];
            } else {
                $userName = 'LinkedIn User';
            }

            if (!$userId) {
                Log::error('No user ID found in profile', ['profile' => $profile]);
                throw new \Exception('Could not extract user ID from profile. Available fields: ' . implode(', ', array_keys($profile)));
            }

            // Store or update account
            Auth::user()->socialAccounts()->updateOrCreate(
                [
                    'platform' => 'linkedin',
                    'platform_user_id' => $userId,
                ],
                [
                    'platform_username' => trim($userName),
                    'access_token' => $tokenData['access_token'],
                    'token_expires_at' => now()->addSeconds($tokenData['expires_in'] ?? 5184000),
                    'platform_data' => $profile,
                    'is_active' => true,
                ]
            );

            return redirect()->route('social.accounts.index')
                ->with('success', 'LinkedIn account connected successfully!');

        } catch (\Exception $e) {
            Log::error('LinkedIn OAuth failed', ['error' => $e->getMessage()]);
            return redirect()->route('social.accounts.index')
                ->with('error', 'Failed to connect LinkedIn: ' . $e->getMessage());
        }
    }

    /**
     * Handle Facebook OAuth callback
     */
    public function facebook(Request $request)
    {
        // Verify state
        if ($request->state !== session('facebook_oauth_state')) {
            return redirect()->route('social.accounts.index')
                ->with('error', 'Invalid OAuth state. Please try again.');
        }

        if ($request->has('error')) {
            return redirect()->route('social.accounts.index')
                ->with('error', 'Facebook authorization failed: ' . $request->error_description);
        }

        try {
            // Exchange code for access token
            $response = Http::get('https://graph.facebook.com/v18.0/oauth/access_token', [
                'client_id' => config('services.facebook.client_id'),
                'client_secret' => config('services.facebook.client_secret'),
                'redirect_uri' => config('services.facebook.redirect'),
                'code' => $request->code,
            ]);

            if ($response->failed()) {
                throw new \Exception('Failed to get access token: ' . $response->body());
            }

            $tokenData = $response->json();

            // Get user/page information
            $meResponse = Http::get('https://graph.facebook.com/v18.0/me', [
                'access_token' => $tokenData['access_token'],
                'fields' => 'id,name,accounts',
            ]);

            if ($meResponse->failed()) {
                throw new \Exception('Failed to get user profile');
            }

            $profile = $meResponse->json();

            // If user has pages, use the first page
            $pageId = $profile['id'];
            $pageName = $profile['name'];
            $pageToken = $tokenData['access_token'];

            if (isset($profile['accounts']['data'][0])) {
                $page = $profile['accounts']['data'][0];
                $pageId = $page['id'];
                $pageName = $page['name'];
                $pageToken = $page['access_token'];
            }

            // Store or update account
            Auth::user()->socialAccounts()->updateOrCreate(
                [
                    'platform' => 'facebook',
                    'platform_user_id' => $pageId,
                ],
                [
                    'platform_username' => $pageName,
                    'access_token' => $pageToken,
                    'platform_data' => $profile,
                    'is_active' => true,
                ]
            );

            return redirect()->route('social.accounts.index')
                ->with('success', 'Facebook account connected successfully!');

        } catch (\Exception $e) {
            Log::error('Facebook OAuth failed', ['error' => $e->getMessage()]);
            return redirect()->route('social.accounts.index')
                ->with('error', 'Failed to connect Facebook: ' . $e->getMessage());
        }
    }

    /**
     * Handle Twitter OAuth callback
     */
    public function twitter(Request $request)
    {
        // Verify state
        if ($request->state !== session('twitter_oauth_state')) {
            return redirect()->route('social.accounts.index')
                ->with('error', 'Invalid OAuth state. Please try again.');
        }

        if ($request->has('error')) {
            return redirect()->route('social.accounts.index')
                ->with('error', 'Twitter authorization failed: ' . $request->error_description);
        }

        try {
            // Exchange code for access token
            $codeVerifier = session('twitter_code_verifier');
            
            $response = Http::asForm()
                ->withBasicAuth(
                    config('services.twitter.client_id'),
                    config('services.twitter.client_secret')
                )
                ->post('https://api.twitter.com/2/oauth2/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $request->code,
                    'redirect_uri' => config('services.twitter.redirect'),
                    'code_verifier' => $codeVerifier,
                ]);

            if ($response->failed()) {
                throw new \Exception('Failed to get access token: ' . $response->body());
            }

            $tokenData = $response->json();

            // Get user profile
            $profileResponse = Http::withToken($tokenData['access_token'])
                ->get('https://api.twitter.com/2/users/me', [
                    'user.fields' => 'id,name,username',
                ]);

            if ($profileResponse->failed()) {
                throw new \Exception('Failed to get user profile');
            }

            $profile = $profileResponse->json();
            $userData = $profile['data'];

            // Store or update account
            Auth::user()->socialAccounts()->updateOrCreate(
                [
                    'platform' => 'twitter',
                    'platform_user_id' => $userData['id'],
                ],
                [
                    'platform_username' => '@' . $userData['username'],
                    'access_token' => $tokenData['access_token'],
                    'refresh_token' => $tokenData['refresh_token'] ?? null,
                    'token_expires_at' => isset($tokenData['expires_in']) 
                        ? now()->addSeconds($tokenData['expires_in']) 
                        : null,
                    'platform_data' => $userData,
                    'is_active' => true,
                ]
            );

            return redirect()->route('social.accounts.index')
                ->with('success', 'Twitter account connected successfully!');

        } catch (\Exception $e) {
            Log::error('Twitter OAuth failed', ['error' => $e->getMessage()]);
            return redirect()->route('social.accounts.index')
                ->with('error', 'Failed to connect Twitter: ' . $e->getMessage());
        }
    }
}
