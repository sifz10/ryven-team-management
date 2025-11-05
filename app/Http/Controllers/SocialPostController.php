<?php

namespace App\Http\Controllers;

use App\Models\SocialPost;
use App\Models\SocialAccount;
use App\Services\SocialPostGenerationService;
use App\Services\SocialMediaPublishingService;
use App\Jobs\PublishScheduledPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class SocialPostController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->socialPosts()->with('socialAccount')->latest();

        // Filter by status
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->where('scheduled_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->where('scheduled_at', '<=', $request->to);
        }

        $posts = $query->paginate(20);
        $accounts = Auth::user()->socialAccounts;

        return view('social.posts.index', compact('posts', 'accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $accounts = Auth::user()->socialAccounts()->where('is_active', true)->get();
        
        return view('social.posts.create', compact('accounts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'social_account_id' => 'nullable|exists:social_accounts,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'auto_generate' => 'boolean',
            'scheduled_at' => 'nullable|date|after:now',
            'status' => 'required|in:draft,scheduled',
        ]);

        $validated['user_id'] = Auth::id();

        $post = SocialPost::create($validated);

        // If auto-generate is enabled, generate content
        if ($request->boolean('auto_generate') && $request->filled('social_account_id')) {
            $account = SocialAccount::find($request->social_account_id);
            
            try {
                $generationService = app(SocialPostGenerationService::class);
                $generation = $generationService->generatePost($post, $account->platform);
                $generation->select();
            } catch (\Exception $e) {
                return redirect()
                    ->route('social.posts.edit', $post)
                    ->with('warning', 'Post created but AI generation failed: ' . $e->getMessage());
            }
        }

        // Schedule the post if status is scheduled
        if ($post->status === 'scheduled' && $post->scheduled_at) {
            PublishScheduledPost::dispatch($post)->delay($post->scheduled_at);
        }

        return redirect()
            ->route('social.posts.index')
            ->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(SocialPost $socialPost)
    {
        $this->authorize('view', $socialPost);
        
        $socialPost->load('socialAccount', 'generations');
        
        return view('social.posts.show', compact('socialPost'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SocialPost $socialPost)
    {
        $this->authorize('update', $socialPost);
        
        $accounts = Auth::user()->socialAccounts()->where('is_active', true)->get();
        $socialPost->load('generations');
        
        return view('social.posts.edit', compact('socialPost', 'accounts'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SocialPost $socialPost)
    {
        $this->authorize('update', $socialPost);

        $validated = $request->validate([
            'social_account_id' => 'nullable|exists:social_accounts,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'content' => 'nullable|string',
            'final_content' => 'nullable|string',
            'scheduled_at' => 'nullable|date',
            'status' => 'required|in:draft,scheduled,posted,failed',
        ]);

        $socialPost->update($validated);

        // Schedule the post if status changed to scheduled
        if ($validated['status'] === 'scheduled' && $socialPost->scheduled_at) {
            PublishScheduledPost::dispatch($socialPost)->delay($socialPost->scheduled_at);
        }

        return redirect()
            ->route('social.posts.index')
            ->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SocialPost $socialPost)
    {
        $this->authorize('delete', $socialPost);

        $socialPost->delete();

        return redirect()
            ->route('social.posts.index')
            ->with('success', 'Post deleted successfully!');
    }

    /**
     * Generate AI content for a post
     */
    public function generate(Request $request, SocialPost $socialPost, SocialPostGenerationService $service)
    {
        $this->authorize('update', $socialPost);

        $validated = $request->validate([
            'platform' => 'required|in:linkedin,facebook,twitter',
            'variations' => 'nullable|integer|min:1|max:5',
        ]);

        try {
            $variations = $validated['variations'] ?? 3;
            $generations = $service->generateVariations($socialPost, $validated['platform'], $variations);

            return response()->json([
                'success' => true,
                'message' => "Generated {$variations} variation(s) successfully!",
                'generations' => $generations,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Generation failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Select a generated version
     */
    public function selectGeneration(Request $request, SocialPost $socialPost)
    {
        $this->authorize('update', $socialPost);

        $validated = $request->validate([
            'generation_id' => 'required|exists:post_generations,id',
        ]);

        $generation = $socialPost->generations()->findOrFail($validated['generation_id']);
        $generation->select();

        return response()->json([
            'success' => true,
            'message' => 'Generation selected successfully!',
            'content' => $generation->generated_content,
        ]);
    }

    /**
     * Publish a post immediately
     */
    public function publish(SocialPost $socialPost, SocialMediaPublishingService $service)
    {
        $this->authorize('update', $socialPost);

        try {
            $service->publish($socialPost);

            return redirect()
                ->route('social.posts.show', $socialPost)
                ->with('success', 'Post published successfully!');
        } catch (\Exception $e) {
            return redirect()
                ->route('social.posts.show', $socialPost)
                ->with('error', 'Failed to publish post: ' . $e->getMessage());
        }
    }
}
