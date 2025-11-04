<?php

namespace App\Http\Controllers;

use App\Models\SocialAccount;
use App\Services\SocialMediaPublishingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SocialAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $accounts = Auth::user()->socialAccounts ?? collect();
        
        return view('social.accounts.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('social.accounts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // This will be called by OAuth callback
        $validated = $request->validate([
            'platform' => 'required|in:linkedin,facebook,twitter',
            'platform_user_id' => 'required|string',
            'platform_username' => 'nullable|string',
            'access_token' => 'required|string',
            'refresh_token' => 'nullable|string',
            'token_expires_at' => 'nullable|date',
            'platform_data' => 'nullable|array',
        ]);

        $account = Auth::user()->socialAccounts()->updateOrCreate(
            [
                'platform' => $validated['platform'],
                'platform_user_id' => $validated['platform_user_id'],
            ],
            $validated
        );

        return redirect()
            ->route('social.accounts.index')
            ->with('success', ucfirst($validated['platform']) . ' account connected successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialAccount $socialAccount)
    {
        $this->authorize('view', $socialAccount);
        
        return view('social.accounts.show', compact('socialAccount'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialAccount $socialAccount)
    {
        $this->authorize('update', $socialAccount);
        
        return view('social.accounts.edit', compact('socialAccount'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialAccount $socialAccount)
    {
        $this->authorize('update', $socialAccount);

        $validated = $request->validate([
            'is_active' => 'boolean',
        ]);

        $socialAccount->update($validated);

        return redirect()
            ->route('social.accounts.index')
            ->with('success', 'Account updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialAccount $socialAccount)
    {
        $this->authorize('delete', $socialAccount);

        $platform = $socialAccount->platform_name;
        $socialAccount->delete();

        return redirect()
            ->route('social.accounts.index')
            ->with('success', $platform . ' account disconnected successfully!');
    }

    /**
     * Test connection to a social account
     */
    public function testConnection(SocialAccount $socialAccount, SocialMediaPublishingService $service)
    {
        $this->authorize('update', $socialAccount);

        try {
            $isConnected = $service->testConnection($socialAccount);

            if ($isConnected) {
                return response()->json([
                    'success' => true,
                    'message' => 'Connection successful!',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Connection failed. Please reconnect your account.',
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Connection error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Initiate OAuth flow for LinkedIn
     */
    public function connectLinkedIn()
    {
        $clientId = config('services.linkedin.client_id');
        $redirectUri = route('social.callback.linkedin');
        $state = bin2hex(random_bytes(16));
        
        session(['linkedin_oauth_state' => $state]);

        $authUrl = 'https://www.linkedin.com/oauth/v2/authorization?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'state' => $state,
            'scope' => 'openid profile email w_member_social',
        ]);

        return redirect($authUrl);
    }

    /**
     * Initiate OAuth flow for Facebook
     */
    public function connectFacebook()
    {
        $appId = config('services.facebook.client_id');
        $redirectUri = route('social.callback.facebook');
        $state = bin2hex(random_bytes(16));
        
        session(['facebook_oauth_state' => $state]);

        $authUrl = 'https://www.facebook.com/v18.0/dialog/oauth?' . http_build_query([
            'client_id' => $appId,
            'redirect_uri' => $redirectUri,
            'state' => $state,
            'scope' => 'pages_manage_posts,pages_read_engagement,pages_show_list',
        ]);

        return redirect($authUrl);
    }

    /**
     * Initiate OAuth flow for Twitter
     */
    public function connectTwitter()
    {
        // Twitter OAuth 2.0 with PKCE
        $clientId = config('services.twitter.client_id');
        $redirectUri = route('social.callback.twitter');
        $state = bin2hex(random_bytes(16));
        $codeVerifier = bin2hex(random_bytes(32));
        $codeChallenge = rtrim(strtr(base64_encode(hash('sha256', $codeVerifier, true)), '+/', '-_'), '=');
        
        session([
            'twitter_oauth_state' => $state,
            'twitter_code_verifier' => $codeVerifier,
        ]);

        $authUrl = 'https://twitter.com/i/oauth2/authorize?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'scope' => 'tweet.read tweet.write users.read offline.access',
            'state' => $state,
            'code_challenge' => $codeChallenge,
            'code_challenge_method' => 'S256',
        ]);

        return redirect($authUrl);
    }
}
