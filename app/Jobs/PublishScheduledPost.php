<?php

namespace App\Jobs;

use App\Models\SocialPost;
use App\Services\SocialMediaPublishingService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class PublishScheduledPost implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [60, 300, 900]; // 1 min, 5 min, 15 min

    /**
     * Create a new job instance.
     */
    public function __construct(
        public SocialPost $post
    ) {
    }

    /**
     * Execute the job.
     */
    public function handle(SocialMediaPublishingService $publishingService): void
    {
        try {
            // Check if post is still ready to be published
            if (!$this->post->isReadyToPost()) {
                Log::info('Post no longer ready to publish', ['post_id' => $this->post->id]);
                return;
            }

            // Publish the post
            $publishingService->publish($this->post);

            Log::info('Scheduled post published successfully', [
                'post_id' => $this->post->id,
                'platform' => $this->post->socialAccount->platform,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to publish scheduled post', [
                'post_id' => $this->post->id,
                'error' => $e->getMessage(),
                'attempt' => $this->attempts(),
            ]);

            // Re-throw to trigger retry mechanism
            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Scheduled post publishing permanently failed', [
            'post_id' => $this->post->id,
            'error' => $exception->getMessage(),
        ]);

        // Update post status to failed
        $this->post->update([
            'status' => 'failed',
            'error_message' => 'Failed after ' . $this->tries . ' attempts: ' . $exception->getMessage(),
        ]);
    }
}
