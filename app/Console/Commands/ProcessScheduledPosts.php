<?php

namespace App\Console\Commands;

use App\Jobs\PublishScheduledPost;
use App\Models\SocialPost;
use Illuminate\Console\Command;

class ProcessScheduledPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'social:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process and publish scheduled social media posts';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Processing scheduled posts...');

        // Get all posts ready to be published
        $posts = SocialPost::readyToPost()->get();

        if ($posts->isEmpty()) {
            $this->info('No posts ready to publish.');
            return 0;
        }

        $this->info("Found {$posts->count()} post(s) ready to publish.");

        foreach ($posts as $post) {
            try {
                // Dispatch job to publish the post
                PublishScheduledPost::dispatch($post);
                
                $this->line("✓ Queued post #{$post->id} for publishing");
            } catch (\Exception $e) {
                $this->error("✗ Failed to queue post #{$post->id}: " . $e->getMessage());
            }
        }

        $this->info('Done!');
        return 0;
    }
}
