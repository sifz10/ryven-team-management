<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostGeneration extends Model
{
    protected $fillable = [
        'social_post_id',
        'platform',
        'title',
        'description',
        'generated_content',
        'generation_metadata',
        'is_selected',
    ];

    protected $casts = [
        'generation_metadata' => 'array',
        'is_selected' => 'boolean',
    ];

    /**
     * Get the social post that owns this generation
     */
    public function socialPost(): BelongsTo
    {
        return $this->belongsTo(SocialPost::class);
    }

    /**
     * Mark this generation as selected
     */
    public function select(): void
    {
        // Unselect all other generations for this post
        static::where('social_post_id', $this->social_post_id)
            ->where('id', '!=', $this->id)
            ->update(['is_selected' => false]);
        
        // Select this one
        $this->update(['is_selected' => true]);
        
        // Update the post's content with this generation
        $this->socialPost->update(['content' => $this->generated_content]);
    }
}
